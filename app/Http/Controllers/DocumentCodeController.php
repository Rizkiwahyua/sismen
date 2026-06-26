<?php

namespace App\Http\Controllers;

use App\Models\DocumentCode;
use Illuminate\Http\Request;

class DocumentCodeController extends Controller
{
    public function index()
    {
        $codes = DocumentCode::withCount('documents')->get();

        return view('admin.document-codes.index', compact('codes'));
    }

    public function create()
    {
        return view('admin.document-codes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:document_codes,code'
        ]);

        DocumentCode::create([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'is_active' => true
        ]);

        return redirect()->route('admin.document-codes.index');
    }
    public function edit(DocumentCode $document_code)
    {
        return view('admin.document-codes.edit', compact('document_code'));
    }

    public function update(Request $request, DocumentCode $document_code)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        $document_code->update([
            'code' => strtoupper($request->code),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.document-codes.index')
            ->with('success', 'Kode dokumen berhasil diperbarui');
    }

    public function destroy(DocumentCode $document_code)
    {
        // 🔥 JIKA MASIH ADA DOKUMEN YANG TERHUBUNG, CEGAH PENGHAPUSAN
        if ($document_code->documents()->count() > 0) {
            return redirect()->route('admin.document-codes.index')
                ->with('error', 'Kode dokumen tidak dapat dihapus karena masih memiliki dokumen terhubung.');
        }

        $document_code->delete();

        return redirect()->route('admin.document-codes.index')
            ->with('success', 'Kode dokumen berhasil dihapus');
    }
    public function show(DocumentCode $document_code)
    {
        $documents = $document_code->documents()->with(['category', 'department'])->get();

        return view('admin.document-codes.show', compact('document_code', 'documents'));
    }
}
