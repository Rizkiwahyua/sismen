@extends('layouts.app')

@section('title', 'User Profile Detail')

@section('content')
<div class="py-6 max-w-4xl mx-auto px-4 sm:px-6">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.user.index') }}" 
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-[#0f3c7a] transition">
            <i class="bi bi-arrow-left text-sm"></i>
            Kembali ke Daftar User
        </a>
    </div>

    <!-- Detail Card Container -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <!-- Card Header with User Avatar -->
        <div class="bg-gradient-to-r from-[#0f3c7a] to-[#164d94] px-6 py-8 text-white relative">
            <div class="flex flex-col sm:flex-row items-center gap-5 relative z-10 text-center sm:text-left">
                <!-- User Avatar -->
                <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-white/20 shadow-md bg-white/10 flex-shrink-0 flex items-center justify-center">
                    <img src="{{ $user->photo ? asset('images/' . $user->photo) : asset('images/profile.png') }}"
                         class="w-full h-full object-cover" 
                         alt="Avatar" />
                </div>
                
                <div>
                    <h2 class="text-xl font-extrabold tracking-tight leading-tight">{{ $user->name }}</h2>
                    <p class="text-slate-200 text-xs mt-1">{{ $user->email }}</p>
                    
                    <div class="mt-3.5 flex justify-center sm:justify-start">
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-white/15 border border-white/10 text-white">
                            <i class="bi {{ $user->role === 'admin' ? 'bi-shield-fill-check' : 'bi-person-fill' }} mr-1 text-[8px]"></i>
                            {{ $user->role }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Abstract background shape -->
            <div class="absolute right-0 bottom-0 opacity-10 translate-y-1/4 translate-x-1/8 pointer-events-none z-0">
                <i class="bi bi-people-fill text-9xl"></i>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Detail Pengguna</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- Nama Lengkap -->
                <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</p>
                    <p class="font-bold text-slate-800 text-xs">
                        {{ $user->name }}
                    </p>
                </div>

                <!-- Nomor Badge -->
                <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor Badge</p>
                    <p class="font-bold text-slate-800 text-xs">
                        {{ $user->no_badge ?? '-' }}
                    </p>
                </div>

                <!-- Alamat Email -->
                <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Alamat Email</p>
                    <p class="font-bold text-slate-800 text-xs break-all">
                        {{ $user->email }}
                    </p>
                </div>

                <!-- Departemen / Unit Kerja -->
                <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Departemen / Unit Kerja</p>
                    <p class="font-bold text-slate-800 text-xs">
                        {{ $user->department_name ?? '-' }}
                    </p>
                </div>

                <!-- Tanggal Dibuat -->
                <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Akun Dibuat</p>
                    <p class="font-bold text-slate-700 text-xs">
                        {{ $user->created_at->format('d M Y H:i') }}
                    </p>
                </div>

                <!-- Terakhir Diperbarui -->
                <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Terakhir Diperbarui</p>
                    <p class="font-bold text-slate-700 text-xs">
                        {{ $user->updated_at->format('d M Y H:i') }}
                    </p>
                </div>
            </div>

            <!-- Footer actions -->
            <div class="mt-8 pt-5 border-t border-slate-100 flex items-center justify-between">
                <div>
                    <a href="{{ route('admin.user.edit', $user->id) }}"
                       class="inline-flex items-center gap-1.5 px-4.5 py-2 border border-amber-250 hover:bg-amber-50 text-amber-700 text-xs font-bold rounded-xl transition shadow-sm">
                        <i class="bi bi-pencil-square"></i>
                        Edit Data Pengguna
                    </a>
                </div>
                <div>
                    <a href="{{ route('admin.user.index') }}"
                       class="inline-flex items-center px-4.5 py-2 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-xl transition shadow-md">
                        ← Kembali
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
