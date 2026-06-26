<x-guest-layout>
    <div class="w-full min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 relative">
        
        <!-- Glowing Backlights behind Login Card -->
        <div class="absolute w-full max-w-4xl h-[580px] pointer-events-none -z-10">
            <!-- Pulsing/Rotating Glow Blobs that sit exactly behind the card edges -->
            <div class="absolute -top-12 -left-12 w-80 h-80 bg-gradient-to-tr from-emerald-400/30 to-[#10a362]/25 rounded-full blur-[80px] animate-pulse"></div>
            <div class="absolute -bottom-16 -right-16 w-96 h-96 bg-gradient-to-br from-teal-400/25 to-emerald-500/20 rounded-full blur-[90px] animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute top-[25%] -right-10 w-72 h-72 bg-gradient-to-r from-emerald-400/20 to-teal-400/10 rounded-full blur-[85px] animate-pulse" style="animation-delay: 4s;"></div>
        </div>

        <!-- Main Card Container -->
        <div class="w-full max-w-4xl bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl border border-white/20 overflow-hidden grid md:grid-cols-12 min-h-[580px] transition-all duration-300 hover:shadow-emerald-500/10 relative z-10">

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
                    <h2 class="text-xl font-bold leading-tight mb-3">Selamat Datang di Portal Dokumen SMTI</h2>
                    <div class="w-12 h-1 rounded-full mb-4" style="background-color: #5fc896;"></div>
                    <p class="text-xs text-slate-100 leading-relaxed font-light">
                        Kelola dokumen kerja, ratifikasi, pedoman, prosedur, instruksi, dan formulir dalam satu platform terintegrasi secara profesional.
                    </p>
                </div>

                <!-- Bottom Footer -->
                <div class="relative z-10 text-[9px] text-emerald-100/70 font-medium">
                    &copy; 2026 SMTI. All rights reserved.
                </div>
            </div>

            <!-- Kanan: Form Login -->
            <div class="col-span-12 md:col-span-7 p-8 sm:p-12 flex flex-col justify-center bg-slate-50/30">
                
                <!-- Welcome text -->
                <div class="mb-8">
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Sign In</h3>
                    <p class="text-xs text-slate-400 mt-1">Silakan masuk menggunakan akun terdaftar Anda</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email atau No Badge Input -->
                    <div>
                        <label for="login" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Email atau Nomor Badge</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-person-badge text-xs"></i>
                            </span>
                            <input 
                                id="login" 
                                type="text" 
                                name="login" 
                                value="{{ old('login') }}" 
                                required 
                                autofocus 
                                placeholder="nama@email.com atau Nomor Badge"
                                class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-emerald-500/15 focus:border-[#10a362] transition-all text-xs text-slate-800 shadow-sm"
                            />
                        </div>
                        <x-input-error :messages="$errors->get('login')" class="mt-1.5" />
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[10px] font-semibold text-[#10a362] hover:text-[#0c8c53] hover:underline transition">
                                    Lupa Password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-lock text-xs"></i>
                            </span>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-emerald-500/15 focus:border-[#10a362] transition-all text-xs text-slate-800 shadow-sm"
                            />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                    </div>

                    <!-- Remember Me checkbox -->
                    <div class="flex items-center">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer group select-none">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="rounded border-slate-250 text-[#10a362] shadow-sm focus:ring-[#10a362]/25 focus:ring-opacity-50 transition cursor-pointer">
                            <span class="ml-2.5 text-xs text-slate-500 group-hover:text-slate-700 transition">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Login Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-[#10a362] to-[#13b66e] hover:from-[#0c8c53] hover:to-[#10a362] text-white text-xs font-bold rounded-2xl shadow-lg shadow-emerald-600/10 hover:shadow-xl hover:shadow-emerald-600/20 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 tracking-wide uppercase">
                            Masuk
                        </button>
                    </div>
                </form>

                <!-- Optional Sign Up link -->
                <p class="mt-8 text-center text-xs text-slate-450">
                    Belum memiliki akun?
                    <a href="{{ route('register') }}" class="font-bold text-[#10a362] hover:text-[#0c8c53] hover:underline transition pl-1">Daftar Sekarang</a>
                </p>

            </div>

        </div>
    </div>
</x-guest-layout>
