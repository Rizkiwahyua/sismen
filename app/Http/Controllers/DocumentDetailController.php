<?php

namespace App\Http\Controllers;

use App\Models\DocumentDetail;
use Illuminate\Http\Request;

class DocumentDetailController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'sub_title' => 'required',
            'department_ids' => 'nullable|array',
        ]);

        $detail = DocumentDetail::findOrFail($id);

        $detail->update([
            'sub_title' => $request->sub_title,
            'department_ids' => $request->department_ids ?? [],
            'description' => $request->description,
        ]);

        return back()->with('success', 'Sub judul berhasil diupdate');
    }

    public function destroy($id)
    {
        $detail = DocumentDetail::findOrFail($id);
        $detail->delete();

        return back()->with('success', 'Sub judul berhasil dihapus');
    }
}
