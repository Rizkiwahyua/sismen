<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('documents')->get();

        return view('admin.department.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.department.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:departments,name'
        ]);

        Department::create([
            'name' => $request->name,
            'is_active' => $request->is_active ?? 1
        ]);

        return redirect()->route('admin.department.index')
            ->with('success', 'Department berhasil ditambahkan');
    }

    public function show(Department $department)
    {
        $department->load('documents');

        return view('admin.department.show', compact('department'));
    }
    public function edit(Department $department)
    {
        return view('admin.department.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|unique:departments,name,' . $department->id
        ]);

        $department->update([
            'name' => $request->name,
            'is_active' => $request->is_active ?? $department->is_active
        ]);

        return redirect()->route('admin.department.index')
            ->with('success', 'Department berhasil diupdate');
    }

    public function destroy(Department $department)
    {
        // 🔥 JIKA MASIH ADA DOKUMEN YANG TERHUBUNG, CEGAH PENGHAPUSAN
        if ($department->documents()->count() > 0) {
            return redirect()->route('admin.department.index')
                ->with('error', 'Unit Kerja tidak dapat dihapus karena masih memiliki dokumen terhubung.');
        }

        $department->delete();

        return redirect()->route('admin.department.index')
            ->with('success', 'Department berhasil dihapus');
    }
}
