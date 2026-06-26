@extends('layouts.app')

@section('title', 'User Profile')

@section('content')
    <div class="py-6 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Pengaturan Profil</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola data diri, ganti avatar, perbarui password, dan pengaturan akun Anda.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- LEFT COLUMN: User Summary & Photo Upload Preview -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 text-center">
                    
                    <!-- Avatar Upload Container -->
                    <div class="relative w-32 h-32 mx-auto mb-5 group cursor-pointer" onclick="triggerPhotoUpload()">
                        <div class="w-full h-full rounded-full overflow-hidden border-4 border-slate-100 shadow-inner flex items-center justify-center bg-slate-50">
                            <img id="avatar-preview" 
                                 src="{{ Auth::user()->photo ? asset('images/' . Auth::user()->photo) : asset('images/profile.png') }}"
                                 class="w-full h-full object-cover transition duration-300 group-hover:scale-105" 
                                 alt="User Avatar" />
                        </div>
                        
                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-slate-900/50 rounded-full flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
                            <i class="bi bi-camera-fill text-white text-lg mb-1"></i>
                            <span class="text-[9px] text-white font-bold uppercase tracking-wider">Ganti Foto</span>
                        </div>
                    </div>

                    <!-- User Meta Details -->
                    <h2 class="text-lg font-bold text-slate-800 leading-tight">{{ Auth::user()->name }}</h2>
                    <p class="text-xs text-slate-500 mt-1">{{ Auth::user()->email }}</p>
                    
                    <div class="mt-4 flex justify-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ Auth::user()->role === 'admin' 
                                ? 'bg-blue-50 text-blue-600 border border-blue-100' 
                                : 'bg-teal-50 text-teal-600 border border-teal-100' }}">
                            <i class="bi {{ Auth::user()->role === 'admin' ? 'bi-shield-fill-check' : 'bi-person-fill' }} mr-1 text-[9px]"></i>
                            {{ Auth::user()->role }}
                        </span>
                    </div>

                    <div class="border-t border-slate-100 my-5"></div>

                    <!-- Information List -->
                    <div class="text-left space-y-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400">
                                <i class="bi bi-building text-xs"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Departemen</p>
                                <p class="text-xs font-semibold text-slate-700 truncate">{{ Auth::user()->department_name ?? 'Belum ditentukan' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400">
                                <i class="bi bi-hash text-xs"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Nomor Badge</p>
                                <p class="text-xs font-semibold text-slate-700 truncate">{{ Auth::user()->no_badge ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Edit Forms -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- CARD 1: Profile Information Form -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Informasi Profil</h3>
                        <p class="text-xs text-slate-500 mt-1">Perbarui informasi profil akun, departemen, dan email Anda.</p>
                    </div>

                    <div class="p-6">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            @method('patch')

                            <!-- Hidden file input for Photo Upload -->
                            <input type="file" 
                                   name="photo" 
                                   id="photo-input" 
                                   class="hidden" 
                                   accept="image/*" 
                                   onchange="previewPhoto(event)" />

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Nama -->
                                <div class="col-span-1 md:col-span-2">
                                    <label for="name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                            <i class="bi bi-person text-xs"></i>
                                        </span>
                                        <input type="text" 
                                               name="name" 
                                               id="name" 
                                               value="{{ old('name', Auth::user()->name) }}" 
                                               required 
                                               class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-[#0f3c7a] transition-all text-xs text-slate-800 shadow-sm" />
                                    </div>
                                    @error('name')
                                        <p class="text-red-500 text-[10px] mt-1.5 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Alamat Email</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                            <i class="bi bi-envelope text-xs"></i>
                                        </span>
                                        <input type="email" 
                                               name="email" 
                                               id="email" 
                                               value="{{ old('email', Auth::user()->email) }}" 
                                               required 
                                               class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-[#0f3c7a] transition-all text-xs text-slate-800 shadow-sm" />
                                    </div>
                                    @error('email')
                                        <p class="text-red-500 text-[10px] mt-1.5 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- No Badge -->
                                <div>
                                    <label for="no_badge" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Nomor Badge</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                            <i class="bi bi-hash text-xs"></i>
                                        </span>
                                        <input type="text" 
                                               name="no_badge" 
                                               id="no_badge" 
                                               value="{{ old('no_badge', Auth::user()->no_badge) }}" 
                                               class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-[#0f3c7a] transition-all text-xs text-slate-800 shadow-sm" />
                                    </div>
                                    @error('no_badge')
                                        <p class="text-red-500 text-[10px] mt-1.5 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Departemen -->
                                <div class="col-span-1 md:col-span-2">
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
                                                <option value="{{ $dept->name }}" {{ old('department_name', Auth::user()->department_name) == $dept->name ? 'selected' : '' }}>
                                                    {{ $dept->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                            <i class="bi bi-chevron-down text-[10px]"></i>
                                        </span>
                                    </div>
                                    @error('department_name')
                                        <p class="text-red-500 text-[10px] mt-1.5 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-end pt-3 border-t border-slate-100">
                                <button type="submit" 
                                        class="px-5 py-2.5 bg-[#0f3c7a] hover:bg-[#0a2c5b] text-white text-xs font-bold rounded-xl shadow-md transition duration-200">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- CARD 2: Update Password Form -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Ubah Password</h3>
                        <p class="text-xs text-slate-500 mt-1">Pastikan akun Anda menggunakan password yang aman dan kuat.</p>
                    </div>

                    <div class="p-6">
                        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                            @csrf
                            @method('put')

                            <!-- Password Lama -->
                            <div>
                                <label for="current_password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Password Lama</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="bi bi-shield-lock text-xs"></i>
                                    </span>
                                    <input type="password" 
                                           name="current_password" 
                                           id="current_password" 
                                           autocomplete="current-password" 
                                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-[#0f3c7a] transition-all text-xs text-slate-800 shadow-sm" 
                                           placeholder="••••••••" />
                                </div>
                                @error('current_password', 'updatePassword')
                                    <p class="text-red-500 text-[10px] mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password Baru -->
                            <div>
                                <label for="password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Password Baru</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="bi bi-lock text-xs"></i>
                                    </span>
                                    <input type="password" 
                                           name="password" 
                                           id="password" 
                                           autocomplete="new-password" 
                                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-[#0f3c7a] transition-all text-xs text-slate-800 shadow-sm" 
                                           placeholder="••••••••" />
                                </div>
                                @error('password', 'updatePassword')
                                    <p class="text-red-500 text-[10px] mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div>
                                <label for="password_confirmation" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="bi bi-lock-fill text-xs"></i>
                                    </span>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           id="password_confirmation" 
                                           autocomplete="new-password" 
                                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-[#0f3c7a] transition-all text-xs text-slate-800 shadow-sm" 
                                           placeholder="••••••••" />
                                </div>
                            </div>

                            <div class="flex justify-end pt-3 border-t border-slate-100">
                                <button type="submit" 
                                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md transition duration-200">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- CARD 3: Delete Account Form -->
                <div class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-red-50/50 bg-red-50/10">
                        <h3 class="text-sm font-bold text-red-600 uppercase tracking-wider">Hapus Akun</h3>
                        <p class="text-xs text-slate-500 mt-1">Menghapus akun Anda akan menghapus semua data Anda secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
                    </div>

                    <div class="p-6">
                        <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                            @csrf
                            @method('delete')

                            <div>
                                <label for="delete_password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Password Anda</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-450">
                                        <i class="bi bi-shield-lock-fill text-xs"></i>
                                    </span>
                                    <input type="password" 
                                           name="password" 
                                           id="delete_password" 
                                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all text-xs text-slate-800 shadow-sm" 
                                           placeholder="Masukkan password Anda untuk konfirmasi" 
                                           required />
                                </div>
                                @error('password', 'userDeletion')
                                    <p class="text-red-500 text-[10px] mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end pt-3 border-t border-slate-100">
                                <button type="submit" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini? Data yang terhapus tidak dapat dikembalikan.')"
                                        class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-md transition duration-200">
                                    Hapus Akun Permanen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- JS helper for triggering file input click & file preview -->
    <script>
        function triggerPhotoUpload() {
            document.getElementById('photo-input').click();
        }

        function previewPhoto(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
