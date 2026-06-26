@extends('layouts.app')

@section('title', 'User Directory')

@section('content')
    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Manajemen User</h1>
                <p class="text-xs text-slate-500 mt-1">Kelola hak akses pengguna, informasi departemen, nomor badge, dan log masuk pengguna sistem.</p>
            </div>
            <div>
                <a href="{{ route('admin.user.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0f3c7a] hover:bg-[#0a2c5b] text-white text-xs font-bold rounded-xl shadow-md hover:shadow-lg transition duration-200">
                    <i class="bi bi-person-plus text-sm"></i>
                    Tambah User Baru
                </a>
            </div>
        </div>

        <!-- Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <!-- Card 1: Total Users -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                    <i class="bi bi-people-fill text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Pengguna</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $stats['total'] }}</h3>
                </div>
            </div>

            <!-- Card 2: Admins -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center text-red-600">
                    <i class="bi bi-shield-fill-check text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Administrator</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $stats['admin'] }}</h3>
                </div>
            </div>

            <!-- Card 3: Users -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-600">
                    <i class="bi bi-person-workspace text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Staff / User</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $stats['user'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Filter and Search Controls -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm mb-6">
            <form method="GET" action="{{ route('admin.user.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                
                <!-- Keyword Search -->
                <div class="md:col-span-4">
                    <label for="search" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Pencarian User</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-search text-xs"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ request('search') }}" 
                               placeholder="Nama, email, badge, departemen..."
                               class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-[#0f3c7a] transition text-xs text-slate-800" />
                    </div>
                </div>

                <!-- Role Filter -->
                <div class="md:col-span-3">
                    <label for="role" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Filter Role</label>
                    <div class="relative">
                        <select name="role" 
                                id="role" 
                                class="w-full pl-4 pr-10 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-[#0f3c7a] transition text-xs text-slate-700 cursor-pointer appearance-none">
                            <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-chevron-down text-[10px]"></i>
                        </span>
                    </div>
                </div>

                <!-- Department Filter -->
                <div class="md:col-span-3">
                    <label for="department_name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Filter Unit Kerja</label>
                    <div>
                        <select name="department_name" 
                                id="department-select" 
                                class="w-full text-xs">
                            <option value="all">Semua Unit Kerja</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->name }}" {{ request('department_name') == $dept->name ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="md:col-span-2 flex gap-2 w-full">
                    <button type="submit" 
                            class="flex-1 py-2 bg-[#0f3c7a] hover:bg-[#0a2c5b] text-white text-xs font-bold rounded-xl transition duration-150 shadow-sm flex items-center justify-center gap-1.5">
                        <i class="bi bi-funnel"></i>
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'role', 'department_name']))
                        <a href="{{ route('admin.user.index') }}" 
                           class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-650 text-xs font-bold rounded-xl transition duration-150 flex items-center justify-center shadow-sm"
                           title="Reset Filters">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>

            </form>
        </div>

        <!-- Users Directory Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-6 py-4 w-12 text-center">No</th>
                            <th class="px-6 py-4">Nama & Email</th>
                            <th class="px-6 py-4">No Badge</th>
                            <th class="px-6 py-4">Departemen</th>
                            <th class="px-6 py-4">Role Akses</th>
                            <th class="px-6 py-4 text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $index => $user)
                            <tr class="hover:bg-slate-50/50 transition">
                                <!-- NO -->
                                <td class="px-6 py-4.5 text-center text-slate-450 font-medium">
                                    {{ $users->firstItem() + $index }}
                                </td>

                                <!-- NAMA & EMAIL -->
                                <td class="px-6 py-4.5">
                                    <div class="flex items-center gap-3">
                                        <div class="relative flex-shrink-0">
                                            <div class="w-9 h-9 rounded-full overflow-hidden border border-slate-100 shadow-sm">
                                                <img src="{{ $user->photo ? asset('images/' . $user->photo) : asset('images/profile.png') }}"
                                                     class="w-full h-full object-cover" 
                                                     alt="Avatar" />
                                            </div>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-800 leading-tight truncate">{{ $user->name }}</p>
                                            <p class="text-[10px] text-slate-450 mt-0.5 truncate">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- NO BADGE -->
                                <td class="px-6 py-4.5 text-slate-650 font-semibold">
                                    {{ $user->no_badge ?? '-' }}
                                </td>

                                <!-- DEPARTEMEN -->
                                <td class="px-6 py-4.5">
                                    @if($user->department_name)
                                        <span class="inline-flex items-center px-2.5 py-1 bg-slate-50 border border-slate-200/80 rounded-lg text-slate-600 font-semibold tracking-wide gap-1">
                                            <i class="bi bi-building text-[10px] text-slate-400"></i>
                                            {{ $user->department_name }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 font-light">-</span>
                                    @endif
                                </td>

                                <!-- ROLE AKSES -->
                                <td class="px-6 py-4.5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider
                                        {{ $user->role === 'admin'
                                            ? 'bg-blue-50 text-blue-600 border border-blue-100'
                                            : 'bg-teal-50 text-teal-600 border border-teal-100' }}">
                                        <i class="bi {{ $user->role === 'admin' ? 'bi-shield-fill-check' : 'bi-person-fill' }} mr-1 text-[8px]"></i>
                                        {{ $user->role }}
                                    </span>
                                </td>

                                <!-- ACTION BUTTONS -->
                                <td class="px-6 py-4.5 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.user.show', $user->id) }}"
                                           class="w-7 text-slate-500 border border-slate-200 hover:border-slate-350 hover:bg-slate-50 rounded-lg py-1.5 flex items-center justify-center transition shadow-sm"
                                           title="Detail User">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.user.edit', $user->id) }}"
                                           class="w-7 text-amber-600 border border-amber-200 hover:bg-amber-50 rounded-lg py-1.5 flex items-center justify-center transition shadow-sm"
                                           title="Edit User">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        @if(Auth::id() !== $user->id)
                                            <button type="button" 
                                                    onclick="confirmDeleteUser({{ $user->id }}, '{{ $user->name }}')"
                                                    class="w-7 text-rose-600 border border-rose-200 hover:bg-rose-50 rounded-lg py-1.5 flex items-center justify-center transition shadow-sm"
                                                    title="Hapus User">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            
                                            <form id="delete-form-{{ $user->id }}"
                                                  action="{{ route('admin.user.destroy', $user->id) }}" 
                                                  method="POST" 
                                                  class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @else
                                            <div class="w-7 text-slate-300 border border-slate-100 rounded-lg py-1.5 flex items-center justify-center cursor-not-allowed"
                                                 title="Tidak dapat menghapus akun sendiri">
                                                <i class="bi bi-trash"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-slate-400 font-light">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="bi bi-people text-2xl text-slate-300"></i>
                                        <span>Tidak ada data pengguna ditemukan</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer (Pagination) -->
            @if($users->hasPages())
                <div class="px-6 py-4.5 bg-slate-50/50 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript block for tom-select and delete alerts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Init TomSelect for Department filter
            if (document.getElementById('department-select')) {
                new TomSelect('#department-select', {
                    create: false,
                    sortField: {
                        field: 'text',
                        direction: 'asc'
                    },
                    controlInput: null
                });
            }
        });

        // SweetAlert Delete Confirmation
        function confirmDeleteUser(id, name) {
            Swal.fire({
                title: 'Hapus Pengguna?',
                text: `Apakah Anda yakin ingin menghapus pengguna "${name}"? Tindakan ini tidak dapat dibatalkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection
