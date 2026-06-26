<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Department;
use App\Models\DocumentCode;
use App\Exports\DocumentsExport;
use Maatwebsite\Excel\Facades\Excel;


class RekapController extends Controller
{
    public function index(Request $request)
    {
        $category     = $request->get('category', 'all');
        $search       = $request->get('search');
        $startDate    = $request->get('start_date');
        $endDate      = $request->get('end_date');
        $fileStatus   = $request->get('file_status', 'all');

        $query = Document::with([
            'category',
            'department',
            'code',
            'details.department',
            'uploader',
            'updater'
        ]);

        // 🔎 Filter kategori
        if ($category !== 'all') {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        // 🔎 Filter department (multi-select)
        if ($request->has('department') && !empty($request->department)) {
            $departmentsInput = array_filter((array) $request->department);
            if (count($departmentsInput) > 0) {
                $query->whereIn('department_id', $departmentsInput);
            }
        }

        // 🔎 Filter kode dokumen (multi-select)
        if ($request->has('code') && !empty($request->code)) {
            $codesInput = array_filter((array) $request->code);
            if (count($codesInput) > 0) {
                $query->whereIn('document_code_id', $codesInput);
            }
        }

        // 🔎 Filter search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('document_number', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        // 🔎 Filter rentang tanggal
        if ($startDate && $endDate) {
            $query->whereBetween('document_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('document_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('document_date', '<=', $endDate);
        }

        // 🔎 Filter status file
        if ($fileStatus === 'lengkap') {
            $query->whereNotNull('file_document')->where('file_document', '<>', '');
        } elseif ($fileStatus === 'belum_upload') {
            $query->where(function ($q) {
                $q->whereNull('file_document')->orWhere('file_document', '');
            });
        }

        $documents   = $query->paginate(10)->withQueryString();
        $departments = Department::all();
        $codes       = DocumentCode::all();

        // ===== HITUNG METRIK GRAFIK SECARA DINAMIS SESUAI FILTER =====
        $chartQuery = clone $query;
        $chartDocs = $chartQuery->get();

        // 1. Top Unit Kerja (Top Departments)
        $deptCounts = [];
        foreach ($chartDocs as $doc) {
            $deptName = $doc->department->name ?? 'Lainnya';
            $deptCounts[$deptName] = ($deptCounts[$deptName] ?? 0) + 1;
        }
        arsort($deptCounts);
        $topDepts = array_slice($deptCounts, 0, 5, true);

        // 2. Tren Dokumen per Tahun
        $yearCounts = [];
        foreach ($chartDocs as $doc) {
            if ($doc->document_date) {
                $year = \Carbon\Carbon::parse($doc->document_date)->format('Y');
                $yearCounts[$year] = ($yearCounts[$year] ?? 0) + 1;
            }
        }
        ksort($yearCounts);
        if (empty($yearCounts)) {
            $yearCounts[date('Y')] = 0;
        }

        // 3. Kepatuhan & Kelengkapan Upload
        $totalDocsCount = max($chartDocs->count(), 1);
        $uploadedCount = $chartDocs->filter(fn($d) => !empty($d->file_document))->count();
        $compliancePercent = round(($uploadedCount / $totalDocsCount) * 100);

        // 4. Kategori Counts untuk Chart (Doughnut)
        $chartRatifikasi = $chartDocs->filter(fn($d) => $d->category?->slug === 'ratifikasi')->count();
        $chartPedoman = $chartDocs->filter(fn($d) => $d->category?->slug === 'pedoman')->count();
        $chartProsedur = $chartDocs->filter(fn($d) => $d->category?->slug === 'prosedur')->count();
        $chartInstruksi = $chartDocs->filter(fn($d) => $d->category?->slug === 'instruksikerja')->count();
        $chartFormulir = $chartDocs->filter(fn($d) => $d->category?->slug === 'formulir')->count();

        // ===== HITUNG TOTAL CARD DENGAN SATU/DUA KUERI TEROPTIMASI =====
        $counts = Document::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN file_document IS NULL OR file_document = '' THEN 1 ELSE 0 END) as belum_upload
        ")->first();

        $categoryCounts = Document::join('document_categories', 'documents.document_category_id', '=', 'document_categories.id')
            ->selectRaw("
                SUM(CASE WHEN document_categories.slug = 'ratifikasi' THEN 1 ELSE 0 END) as ratifikasi,
                SUM(CASE WHEN document_categories.slug = 'pedoman' THEN 1 ELSE 0 END) as pedoman,
                SUM(CASE WHEN document_categories.slug = 'prosedur' THEN 1 ELSE 0 END) as prosedur,
                SUM(CASE WHEN document_categories.slug = 'instruksikerja' THEN 1 ELSE 0 END) as instruksikerja,
                SUM(CASE WHEN document_categories.slug = 'formulir' THEN 1 ELSE 0 END) as formulir
            ")->first();

        $totalDokumen = $counts->total ?? 0;
        $totalBelumUpload = $counts->belum_upload ?? 0;
        $totalRatifikasi = $categoryCounts->ratifikasi ?? 0;
        $totalPedoman = $categoryCounts->pedoman ?? 0;
        $totalProsedur = $categoryCounts->prosedur ?? 0;
        $totalInstruksi = $categoryCounts->instruksikerja ?? 0;
        $totalFormulir = $categoryCounts->formulir ?? 0;

        return view('admin.rekap.index', compact(
            'documents',
            'category',
            'departments',
            'codes',
            'search',
            'startDate',
            'endDate',
            'fileStatus',

            // kirim total card
            'totalDokumen',
            'totalRatifikasi',
            'totalPedoman',
            'totalProsedur',
            'totalInstruksi',
            'totalFormulir',
            'totalBelumUpload',

            // kirim data chart
            'topDepts',
            'yearCounts',
            'compliancePercent',
            'uploadedCount',
            'totalDocsCount',
            'chartRatifikasi',
            'chartPedoman',
            'chartProsedur',
            'chartInstruksi',
            'chartFormulir'
        ));
    }

    public function export(Request $request)
    {
        $category     = $request->get('category', 'all');
        $search       = $request->get('search');
        $startDate    = $request->get('start_date');
        $endDate      = $request->get('end_date');
        $fileStatus   = $request->get('file_status', 'all');

        $query = Document::with(['category', 'department', 'code', 'details']);

        // 🔎 Filter kategori
        if ($category !== 'all') {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        // 🔎 Filter department (multi-select)
        if ($request->has('department') && !empty($request->department)) {
            $departmentsInput = array_filter((array) $request->department);
            if (count($departmentsInput) > 0) {
                $query->whereIn('department_id', $departmentsInput);
            }
        }

        // 🔎 Filter kode dokumen (multi-select)
        if ($request->has('code') && !empty($request->code)) {
            $codesInput = array_filter((array) $request->code);
            if (count($codesInput) > 0) {
                $query->whereIn('document_code_id', $codesInput);
            }
        }

        // 🔎 Filter search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('document_number', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        // 🔎 Filter rentang tanggal
        if ($startDate && $endDate) {
            $query->whereBetween('document_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('document_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('document_date', '<=', $endDate);
        }

        // 🔎 Filter status file
        if ($fileStatus === 'lengkap') {
            $query->whereNotNull('file_document')->where('file_document', '<>', '');
        } elseif ($fileStatus === 'belum_upload') {
            $query->where(function ($q) {
                $q->whereNull('file_document')->orWhere('file_document', '');
            });
        }

        $query->orderBy('document_number', 'asc');

        $documents = $query->get();

        // Susun metadata filter aktif untuk kop Excel
        $activeFilters = [
            'category' => $category,
            'departments' => $request->has('department') ? Department::whereIn('id', (array)$request->department)->pluck('name')->toArray() : [],
            'codes' => $request->has('code') ? DocumentCode::whereIn('id', (array)$request->code)->pluck('code')->toArray() : [],
            'search' => $search,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'file_status' => $fileStatus,
            'operator' => auth()->check() ? auth()->user()->name : 'Sistem',
        ];

        return Excel::download(
            new DocumentsExport($documents, $activeFilters),
            'rekap-dokumen.xlsx'
        );
    }
}

