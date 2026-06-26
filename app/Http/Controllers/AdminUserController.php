<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_badge', 'like', "%{$search}%")
                  ->orWhere('department_name', 'like', "%{$search}%");
            });
        }

        // Role Filter
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Department Filter
        if ($request->filled('department_name') && $request->department_name !== 'all') {
            $query->where('department_name', $request->department_name);
        }

        $users = $query->paginate(10)->withQueryString();
        $departments = \App\Models\Department::orderBy('name')->get();

        // Summary Stats
        $stats = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'user' => User::where('role', 'user')->count(),
        ];

        return view('admin.user.index', compact('users', 'departments', 'stats'));
    }

    public function create()
    {
        $departments = \App\Models\Department::orderBy('name')->get();
        return view('admin.user.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'no_badge' => 'nullable|unique:users,no_badge',
            'department_name' => 'nullable|string|max:255',
            'role' => 'required|in:admin,user',
            'password' => 'required|min:6',
        ]);

        try {

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'department_name' => $request->department_name,
                'no_badge' => $request->no_badge,
                'role' => $request->role,
            ]);

            return redirect()
                ->route('admin.user.index')
                ->with('success', 'User berhasil ditambahkan');
        } catch (QueryException $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data');
        }
    }

    public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }

    public function edit(User $user)
    {
        $departments = \App\Models\Department::orderBy('name')->get();
        return view('admin.user.edit', compact('user', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_badge' => 'nullable|unique:users,no_badge,' . $user->id,
            'department_name' => 'nullable|string|max:255',
            'role' => 'required|in:admin,user',
            'password' => 'nullable|min:6',
        ]);

        try {

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'no_badge' => $request->no_badge,
                'department_name' => $request->department_name,
                'role' => $request->role,
            ]);

            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            return redirect()
                ->route('admin.user.index')
                ->with('success', 'User berhasil diupdate');
        } catch (QueryException $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengupdate data');
        }
    }

    public function destroy(User $user)
    {
        try {

            $user->delete();

            return redirect()
                ->route('admin.user.index')
                ->with('success', 'User berhasil dihapus');
        } catch (QueryException $e) {

            return redirect()
                ->back()
                ->with('error', 'Data gagal dihapus');
        }
    }
}
