@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="py-6 max-w-4xl mx-auto px-4 sm:px-6">
    
    <!-- Breadcrumb & Back action -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.user.index') }}" 
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-[#0f3c7a] transition">
            <i class="bi bi-arrow-left text-sm"></i>
            Kembali ke Daftar User
        </a>
    </div>

    <!-- Form Card Container -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-[#0f3c7a] to-[#164d94] px-6 py-5 text-white">
            <h2 class="text-lg font-bold tracking-tight">Edit Data Pengguna</h2>
            <p class="text-slate-200 text-xs mt-1">Perbarui informasi profil, departemen, role akses, atau ubah password akun pengguna ini.</p>
        </div>

        <!-- Form Body -->
        <div class="p-6">
            <form action="{{ route('admin.user.update', $user->id) }}" method="POST" autocomplete="off" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Dummy Anti Autofill inputs to prevent browser overriding password fields -->
                <input type="text" name="fakeusernameremembered" class="hidden">
                <input type="password" name="fakepasswordremembered" class="hidden">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <!-- Nama Lengkap -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-person text-xs"></i>
                            </span>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $user->name) }}"
                                   placeholder="Masukkan nama lengkap user" 
                                   required
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-[#0f3c7a] transition-all text-xs text-slate-800 shadow-sm" />
                        </div>
                        @error('name')
                            <p class="text-rose-500 text-[10px] mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Alamat Email <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-envelope text-xs"></i>
                            </span>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email', $user->email) }}"
                                   placeholder="nama@email.com" 
                                   required
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-[#0f3c7a] transition-all text-xs text-slate-800 shadow-sm" />
                        </div>
                        @error('email')
                            <p class="text-rose-500 text-[10px] mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Badge -->
                    <div>
                        <label for="no_badge" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Nomor Badge</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-hash text-xs"></i>
                            </span>
                            <input type="text" 
                                   name="no_badge" 
                                   id="no_badge" 
                                   value="{{ old('no_badge', $user->no_badge) }}"
                                   placeholder="Nomor badge karyawan" 
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-[#0f3c7a] transition-all text-xs text-slate-800 shadow-sm" />
                        </div>
                        @error('no_badge')
                            <p class="text-rose-500 text-[10px] mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Departemen -->
                    <div>
                        <label for="department_name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Departemen</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-building text-xs"></i>
                            </span>
                            <select name="department_name" 
                                    id="department_name" 
                                    class="w-full pl-10 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-[#0f3c7a] transition-all text-xs text-slate-700 shadow-sm cursor-pointer appearance-none">
                                <option value="">-- Pilih Departemen --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->name }}" {{ old('department_name', $user->department_name) == $dept->name ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-chevron-down text-[10px]"></i>
                            </span>
                        </div>
                        @error('department_name')
                            <p class="text-rose-500 text-[10px] mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role Hak Akses -->
                    <div>
                        <label for="role" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Role Akses</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-shield-lock text-xs"></i>
                            </span>
                            <select name="role" 
                                    id="role" 
                                    required
                                    class="w-full pl-10 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-[#0f3c7a] transition-all text-xs text-slate-700 shadow-sm cursor-pointer appearance-none">
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-chevron-down text-[10px]"></i>
                            </span>
                        </div>
                        @error('role')
                            <p class="text-rose-500 text-[10px] mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Baru (Optional) -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Password Baru <span class="text-slate-400">(Optional)</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-lock text-xs"></i>
                            </span>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   placeholder="Kosongkan jika tidak ingin mengganti password" 
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-[#0f3c7a] transition-all text-xs text-slate-800 shadow-sm" />
                        </div>
                        @error('password')
                            <p class="text-rose-500 text-[10px] mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Footer submit -->
                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('admin.user.index') }}"
                       class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-650 text-xs font-bold rounded-xl transition duration-150">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-[#0f3c7a] hover:bg-[#0a2c5b] text-white text-xs font-bold rounded-xl shadow-md transition duration-150">
                        Update Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
