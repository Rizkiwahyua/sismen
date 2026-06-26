<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SISMEN - Document System</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon-logo.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:305,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* === GLOBAL TAILWIND INDIGO & VIOLET OVERRIDES FOR CORPORATE EMERALD === */
        .bg-indigo-500, .bg-indigo-600 {
            background-color: #10a362 !important; /* Professional Corporate Emerald */
        }
        .hover\:bg-indigo-600:hover, .hover\:bg-indigo-700:hover {
            background-color: #0c8c53 !important;
        }
        .text-indigo-500, .text-indigo-600 {
            color: #10a362 !important;
        }
        .text-indigo-700 {
            color: #0c8c53 !important;
        }
        .border-indigo-500, .border-indigo-600 {
            border-color: #10a362 !important;
        }
        .bg-indigo-50 {
            background-color: #eefbf4 !important;
        }
        .focus\:ring-indigo-500:focus {
            --tw-ring-color: #10a362 !important;
            border-color: #10a362 !important;
        }
        .focus\:border-indigo-500:focus {
            border-color: #10a362 !important;
        }
        
        /* Gradients */
        .from-indigo-500, .from-indigo-600 {
            --tw-gradient-from: #10a362 !important;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(16, 163, 98, 0)) !important;
        }
        .to-indigo-600, .to-indigo-700 {
            --tw-gradient-to: #0c8c53 !important;
        }
        .hover\:from-indigo-700:hover {
            --tw-gradient-from: #0c8c53 !important;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(12, 140, 83, 0)) !important;
        }
        .hover\:to-indigo-800:hover {
            --tw-gradient-to: #07643a !important;
        }

        /* === SIDEBAR — ELITE CORPORATE LIGHT (ICE MINT) === */
        .sidebar-corp {
            background-color: #f3faf7; /* Clean, professional bright white-green/ice-mint */
            border-right: 1px solid #e2e8f0;
        }
        
        /* Profile card */
        .profile-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }
        .profile-card:hover {
            background-color: #f4fbf8;
            border-color: #cbd5e1;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
        .profile-avatar-ring {
            background: linear-gradient(135deg, #10b981, #059669);
            padding: 1.5px;
            border-radius: 9999px;
        }

        /* Online pulse */
        .online-pulse {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.3); }
            50% { box-shadow: 0 0 0 4px rgba(16, 185, 129, 0); }
        }

        /* Menu item styles */
        .menu-item {
            position: relative;
            color: #475569; /* Slate 600 */
            transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .menu-item:hover {
            background-color: #eefbf4;
            color: #10a362;
        }
        .menu-item.active {
            background-color: #e2f7ea;
            color: #10a362;
            font-weight: 600;
        }
        .menu-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3.5px;
            height: 60%;
            background-color: #10a362;
            border-radius: 0 4px 4px 0;
        }

        /* Section label */
        .section-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #94a3b8;
            font-weight: 700;
        }

        /* Logo text */
        .logo-text {
            color: #10a362;
        }

        /* Scrollbar */
        .sidebar-corp::-webkit-scrollbar { width: 4px; }
        .sidebar-corp::-webkit-scrollbar-track { background: transparent; }
        .sidebar-corp::-webkit-scrollbar-thumb { background: rgba(16, 163, 98, 0.15); border-radius: 10px; }
        .sidebar-corp::-webkit-scrollbar-thumb:hover { background: rgba(16, 163, 98, 0.3); }

        /* Zoom 90% effect for desktop (min-width: 1024px) */
        @media (min-width: 1024px) {
            html {
                font-size: 90%;
            }
        }

        /* Page fade-in transition */
        @keyframes pageFadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-page-fade {
            animation: pageFadeIn 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
    </style>

</head>

<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<body class="antialiased">
    <div class="min-h-screen flex bg-gray-50 relative" 
         x-data="{ 
             sidebarOpen: false, 
             sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' 
         }"
         x-init="$watch('sidebarCollapsed', value => localStorage.setItem('sidebarCollapsed', value))">
        
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-45 lg:hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;"></div>

        <!-- Sidebar -->
        <aside class="sidebar-corp min-h-screen flex flex-col overflow-y-auto overflow-x-hidden shadow-2xl fixed inset-y-0 left-0 z-50 lg:relative lg:z-30 transition-all duration-300 transform flex-shrink-0"
               :class="sidebarOpen ? 'w-[260px] translate-x-0' : (sidebarCollapsed ? 'w-[260px] lg:w-[76px] -translate-x-full lg:translate-x-0' : 'w-[260px] lg:w-[260px] -translate-x-full lg:translate-x-0')">

            <!-- ================= BRAND / LOGO ================= -->
            <div class="flex items-center transition-all duration-300 pt-5 pb-3 border-b border-slate-200/70"
                 :class="sidebarCollapsed ? 'justify-center px-2 mb-2' : 'gap-2.5 px-4 mb-2'">

                <!-- Logo PIM (kiri / utama) -->
                <div class="flex-shrink-0"
                     :class="sidebarCollapsed ? 'w-10 h-10' : 'w-10 h-10'">
                    <img src="{{ asset('images/pim.png') }}"
                         alt="PIM Logo"
                         class="w-full h-full object-contain drop-shadow-sm">
                </div>

                <!-- Logo SMTI (kanan, hanya saat expanded) + Teks -->
                <div x-show="!sidebarCollapsed" x-transition.opacity class="flex items-center gap-2 min-w-0">
                    <img src="{{ asset('images/smti.png') }}"
                         alt="SMTI Logo"
                         class="w-9 h-9 object-contain drop-shadow-sm flex-shrink-0">
                    <div class="min-w-0">
                        <h1 class="logo-text text-[14px] font-extrabold tracking-tight leading-tight">SISMEN</h1>
                        <p class="text-[9px] text-slate-500 font-semibold tracking-widest uppercase leading-tight">Document System</p>
                    </div>
                </div>

            </div>

            <!-- ================= PROFILE USER ================= -->
            <div class="mb-3 transition-all duration-300" :class="sidebarCollapsed ? 'mx-2' : 'mx-4'" x-data="{ open: false }">
                <div @click="sidebarCollapsed ? sidebarCollapsed = false : open = !open" 
                     class="profile-card rounded-xl flex items-center cursor-pointer select-none relative transition-all duration-300"
                     :class="sidebarCollapsed ? 'justify-center p-2' : 'px-4 py-3.5 gap-3'"
                     :title="sidebarCollapsed ? '{{ auth()->user()->name }}' : ''">
                    <!-- Avatar with gradient ring -->
                    <div class="relative flex-shrink-0">
                        <div class="profile-avatar-ring">
                            <img src="{{ auth()->user()->photo ? asset('images/' . auth()->user()->photo) : asset('images/profile.png') }}"
                                class="w-9 h-9 rounded-full object-cover border-2 border-white" />
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-400 border-2 border-white rounded-full online-pulse"></span>
                    </div>

                    <!-- Info -->
                    <div class="min-w-0 flex-1 pr-4" x-show="!sidebarCollapsed" x-transition.opacity>
                        <p class="text-[13px] font-semibold text-slate-850 truncate leading-tight">
                            {{ auth()->user()->name }}
                        </p>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider
                                {{ auth()->user()->role === 'admin'
                                    ? 'bg-blue-50 text-blue-600 border border-blue-100'
                                    : 'bg-teal-50 text-teal-600 border border-teal-100' }}">
                                <i class="bi {{ auth()->user()->role === 'admin' ? 'bi-shield-fill-check' : 'bi-person-fill' }} mr-1 text-[8px]"></i>
                                {{ auth()->user()->role }}
                            </span>
                        </div>
                    </div>

                    <!-- Chevron -->
                    <div class="absolute right-3.5 text-slate-400 transition-transform duration-200" 
                         x-show="!sidebarCollapsed"
                         x-transition.opacity
                         :class="open ? 'rotate-180' : ''">
                        <svg class="h-3 w-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <!-- Dropdown Menu (Collapsible Block) -->
                <div x-show="open && !sidebarCollapsed"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="transform opacity-0 -translate-y-2"
                     x-transition:enter-end="transform opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="transform opacity-100 translate-y-0"
                     x-transition:leave-end="transform opacity-0 -translate-y-2"
                     @click.away="open = false"
                     class="mt-2 py-1.5 bg-white border border-slate-200/85 rounded-xl shadow-sm space-y-0.5"
                     style="display: none;">
                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:text-[#0f3c7a] hover:bg-slate-50 transition">
                        <i class="bi bi-person text-sm text-slate-450"></i>
                        <span>Edit Profil</span>
                    </a>
                    
                    <div class="border-t border-slate-100 my-1"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); this.closest('form').submit();"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                            <i class="bi bi-box-arrow-left text-sm text-rose-500"></i>
                            <span>Keluar</span>
                        </a>
                    </form>
                </div>
            </div>

            <!-- ================= SIDEBAR MENU ================= -->
            @if (auth()->user()->role === 'admin')
                @include('admin.sidebar')
            @elseif(auth()->user()->role === 'user')
                @include('user.sidebar')
            @endif
        </aside>

        <!-- Main Content -->
        <div class="flex-1 min-w-0">
            @include('layouts.navigation')
            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="p-6 animate-page-fade">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#6366f1'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#6366f1'
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Periksa kembali input Anda!',
                confirmButtonColor: '#6366f1'
            });
        </script>
    @endif

</body>
</html>
