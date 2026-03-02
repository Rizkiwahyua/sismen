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
        $departmentId = $request->get('department');
        $codeId       = $request->get('code');
        $search       = $request->get('search');

        $query = Document::with(['category','department','code'])
                    ->latest();

        // 🔎 Filter kategori
        if ($category !== 'all') {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        // 🔎 Filter department
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        // 🔎 Filter kode dokumen
            if ($codeId && $codeId != 'all') {
            $query->where('document_code_id', $codeId);
        }

        // 🔎 Filter search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('document_number', 'like', "%$search%");
            });
        }

        $documents   = $query->paginate(10)->withQueryString();
        $departments = Department::all();
        $codes       = DocumentCode::all();

        return view('admin.rekap.index', compact(
            'documents',
            'category',
            'departments',
            'departmentId',
            'codes',
            'codeId',
            'search'
        ));
    }

    public function export(Request $request)
    {
        $category     = $request->get('category', 'all');
        $departmentId = $request->get('department');
        $codeId       = $request->get('code');
        $search       = $request->get('search');

        $query = Document::with(['category','department','code']);

        // 🔎 Filter kategori
        if ($category !== 'all') {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        // 🔎 Filter department
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        // 🔎 Filter kode dokumen
        if ($codeId) {
            $query->where('code_id', $codeId);
        }

        // 🔎 Filter search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('document_number', 'like', "%$search%");
            });
        }

        $documents = $query->get();

        return Excel::download(
            new DocumentsExport($documents),
            'rekap-dokumen.xlsx'
        );
    }
}
