<x-guest-layout>
    <div class="w-full min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 relative">
        
        <!-- Glowing Backlights behind Register Card -->
        <div class="absolute w-full max-w-5xl h-[600px] pointer-events-none -z-10">
            <!-- Pulsing/Rotating Glow Blobs that sit exactly behind the card edges -->
            <div class="absolute -top-12 -left-12 w-80 h-80 bg-gradient-to-tr from-emerald-400/30 to-[#10a362]/25 rounded-full blur-[80px] animate-pulse"></div>
            <div class="absolute -bottom-16 -right-16 w-96 h-96 bg-gradient-to-br from-teal-400/25 to-[#10a362]/20 rounded-full blur-[90px] animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute top-[25%] -right-10 w-72 h-72 bg-gradient-to-r from-emerald-400/20 to-teal-400/10 rounded-full blur-[85px] animate-pulse" style="animation-delay: 4s;"></div>
        </div>

        <!-- Main Card Container -->
        <div class="w-full max-w-5xl bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl border border-white/20 overflow-hidden grid md:grid-cols-12 min-h-[600px] transition-all duration-300 hover:shadow-emerald-500/10 relative z-10">

            <!-- Kiri: Branding & Welcome (Gradient Corporate) -->
            <div class="col-span-5 hidden md:flex flex-col justify-between p-8 bg-gradient-to-br from-[#10a362] via-[#13b66e] to-[#097b47] text-white relative overflow-hidden">
                <!-- Decorative Glows -->
                <div class="absolute -top-24 -left-24 w-48 h-48 bg-emerald-400/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-teal-400/20 rounded-full blur-3xl"></div>

                <!-- Top Logo Frame -->
                <div class="relative z-10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center shadow-lg">
                        <i class="bi bi-layers-fill text-white text-base"></i>
                    </div>
                    <div>
                        <h1 class="text-sm font-extrabold tracking-wider">SMTI</h1>
                        <p class="text-[8px] text-emerald-100 tracking-widest uppercase">Document System</p>
                    </div>
                </div>

                <!-- Middle Content -->
                <div class="relative z-10 my-auto py-10">
                    <div class="w-24 h-24 mb-6 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 flex items-center justify-center shadow-inner">
                        <img src="{{ asset('images/smti.png') }}" alt="SMTI Logo" class="max-h-20 object-contain drop-shadow" />
                    </div>
                    <h2 class="text-xl font-bold leading-tight mb-3">Pendaftaran Akun Baru SMTI</h2>
                    <div class="w-12 h-1 rounded-full mb-4" style="background-color: #5fc896;"></div>
                    <p class="text-xs text-slate-100 leading-relaxed font-light">
                        Gabung ke sistem manajemen dokumen SMTI dan kelola berkas digital secara aman dan efisien sesuai unit kerja Anda.
                    </p>
                </div>

                <!-- Bottom Footer -->
                <div class="relative z-10 text-[9px] text-emerald-100/70 font-medium">
                    &copy; 2026 SMTI. All rights reserved.
                </div>
            </div>

            <!-- Kanan: Form Register -->
            <div class="col-span-12 md:col-span-7 p-8 sm:p-10 flex flex-col justify-center bg-slate-50/30">
                
                <!-- Title text -->
                <div class="mb-6">
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Daftar Akun</h3>
                    <p class="text-xs text-slate-400 mt-1">Lengkapi form di bawah untuk membuat akun baru</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Name Input -->
                        <div class="col-span-1 sm:col-span-2">
                            <label for="name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="bi bi-person text-xs"></i>
                                </span>
                                <input 
                                    id="name" 
                                    type="text" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    required 
                                    autofocus 
                                    autocomplete="name"
                                    placeholder="Masukkan nama lengkap"
                                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-emerald-500/15 focus:border-[#10a362] transition-all text-xs text-slate-800 shadow-sm"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <!-- Email Input -->
                        <div class="col-span-1 sm:col-span-2">
                            <label for="email" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Email Address</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="bi bi-envelope text-xs"></i>
                                </span>
                                <input 
                                    id="email" 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autocomplete="username"
                                    placeholder="nama@email.com"
                                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-emerald-500/15 focus:border-[#10a362] transition-all text-xs text-slate-800 shadow-sm"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>

                        <!-- Department Input -->
                        <div>
                            <label for="department_name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Departemen</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="bi bi-building text-xs"></i>
                                </span>
                                <select 
                                    name="department_name" 
                                    id="department_name" 
                                    required
                                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-emerald-500/15 focus:border-[#10a362] transition-all text-xs text-slate-700 shadow-sm cursor-pointer appearance-none"
                                >
                                    <option value="">-- Pilih Unit Kerja --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->name }}" {{ old('department_name') == $dept->name ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="bi bi-chevron-down text-[10px]"></i>
                                </span>
                            </div>
                            <x-input-error :messages="$errors->get('department_name')" class="mt-1" />
                        </div>

                        <!-- No Badge Input -->
                        <div>
                            <label for="no_badge" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">No Badge</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="bi bi-hash text-xs"></i>
                                </span>
                                <input 
                                    id="no_badge" 
                                    type="text" 
                                    name="no_badge" 
                                    value="{{ old('no_badge') }}"
                                    placeholder="Nomor badge"
                                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-emerald-500/15 focus:border-[#10a362] transition-all text-xs text-slate-800 shadow-sm"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('no_badge')" class="mt-1" />
                        </div>

                        <!-- Role Input -->
                        <div class="col-span-1 sm:col-span-2">
                            <label for="role" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Role Akses</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="bi bi-shield-lock text-xs"></i>
                                </span>
                                <select 
                                    name="role" 
                                    id="role"
                                    required
                                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-emerald-500/15 focus:border-[#10a362] transition-all text-xs text-slate-700 shadow-sm cursor-pointer appearance-none"
                                >
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="bi bi-chevron-down text-[10px]"></i>
                                </span>
                            </div>
                            <x-input-error :messages="$errors->get('role')" class="mt-1" />
                        </div>

                        <!-- Password Input -->
                        <div>
                            <label for="password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="bi bi-lock text-xs"></i>
                                </span>
                                <input 
                                    id="password" 
                                    type="password" 
                                    name="password" 
                                    required 
                                    autocomplete="new-password"
                                    placeholder="Minimal 8 karakter"
                                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-emerald-500/15 focus:border-[#10a362] transition-all text-xs text-slate-800 shadow-sm"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>

                        <!-- Confirm Password Input -->
                        <div>
                            <label for="password_confirmation" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="bi bi-lock-fill text-xs"></i>
                                </span>
                                <input 
                                    id="password_confirmation" 
                                    type="password" 
                                    name="password_confirmation" 
                                    required 
                                    autocomplete="new-password"
                                    placeholder="Ulangi password"
                                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-emerald-500/15 focus:border-[#10a362] transition-all text-xs text-slate-800 shadow-sm"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                        </div>
                    </div>

                    <!-- Register Button & Login Link -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4">
                        <a href="{{ route('login') }}" class="text-xs text-slate-450 hover:text-[#10a362] underline transition order-2 sm:order-1 text-center sm:text-left">
                            Sudah memiliki akun?
                        </a>

                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-[#10a362] to-[#13b66e] hover:from-[#0c8c53] hover:to-[#10a362] text-white text-xs font-bold rounded-2xl shadow-lg shadow-emerald-600/10 hover:shadow-xl hover:shadow-emerald-600/20 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 tracking-wide uppercase order-1 sm:order-2">
                            Daftar
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</x-guest-layout>
