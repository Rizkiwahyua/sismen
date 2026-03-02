<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Exports\DocumentsExport;
use Maatwebsite\Excel\Facades\Excel;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'all');
        $search   = $request->get('search');

        $query = Document::with(['category', 'department'])
                    ->latest();

        // 🔎 Filter kategori
        if ($category !== 'all') {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        // 🔎 Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('document_number', 'like', "%$search%");
            });
        }

        $documents = $query->paginate(10)->withQueryString();

        return view('admin.rekap.index', compact('documents', 'category'));
    }

    public function export(Request $request)
    {
        $category = $request->get('category', 'all');
        $search   = $request->get('search');

        $query = Document::with(['category', 'department']);

        // 🔎 Filter kategori
        if ($category !== 'all') {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        // 🔎 Search
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
