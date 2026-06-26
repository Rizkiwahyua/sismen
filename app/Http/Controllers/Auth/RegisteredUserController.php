<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Department;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */

    /**
     * Handle an incoming registration request.
     */

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();

        return view('auth.register', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'department_name' => ['nullable', 'string', 'max:255'],
            'no_badge' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:admin,user'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_name' => $request->department_name,
            'no_badge' => $request->no_badge,
            'role' => $request->role,
        ]);

        return redirect('/')
            ->with('success', 'Registrasi berhasil, silakan login');
    }
}
