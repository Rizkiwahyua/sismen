<nav class="px-3 py-2 flex-1">
    <ul class="space-y-1 text-[13px]">

        <!-- Section Title: Core -->
        <li class="section-label px-3 pt-3 pb-2 transition-all duration-300"
            x-show="!sidebarCollapsed"
            x-transition.opacity>
            Navigasi Utama
        </li>

        <!-- Dashboard -->
        <li>
            <a href="{{ route('user.dashboard') }}"
                :class="sidebarCollapsed ? 'justify-center px-1' : 'px-3 gap-3'"
                :title="sidebarCollapsed ? 'Dashboard' : ''"
                class="menu-item flex items-center py-2 rounded-xl border border-transparent transition-all duration-150
                {{ request()->routeIs('user.dashboard')
                    ? 'active text-[#10a362] font-semibold bg-[#e2f7ea]'
                    : 'text-slate-600 hover:bg-slate-200/40 hover:text-slate-800' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-150
                    {{ request()->routeIs('user.dashboard')
                        ? 'bg-[#10a362] text-white shadow-md shadow-[#10a362]/20'
                        : 'bg-white border border-slate-200/70 text-slate-500' }}">
                    <i class="bi bi-grid-1x2-fill text-sm"></i>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Dashboard</span>
            </a>
        </li>

        <!-- Dokumen -->
        <li>
            <a href="#"
                :class="sidebarCollapsed ? 'justify-center px-1' : 'px-3 gap-3'"
                :title="sidebarCollapsed ? 'Dokumen (Segera)' : ''"
                class="menu-item flex items-center py-2 rounded-xl border border-transparent
                text-slate-400 cursor-not-allowed opacity-60">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 bg-white border border-slate-200/50 text-slate-300">
                    <i class="bi bi-file-earmark-text-fill text-sm"></i>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">
                    Dokumen <span class="text-[9px] ml-1 text-slate-400">(Segera)</span>
                </span>
            </a>
        </li>

        <!-- Section Title: Settings -->
        <li class="section-label px-3 pt-5 pb-2 transition-all duration-300"
            x-show="!sidebarCollapsed"
            x-transition.opacity>
            Pengaturan Akun
        </li>

        <!-- Profile Settings -->
        <li>
            <a href="{{ route('profile.edit') }}"
                :class="sidebarCollapsed ? 'justify-center px-1' : 'px-3 gap-3'"
                :title="sidebarCollapsed ? 'Edit Profil' : ''"
                class="menu-item flex items-center py-2 rounded-xl border border-transparent transition-all duration-150
                {{ request()->routeIs('profile.edit')
                    ? 'active text-[#10a362] font-semibold bg-[#e2f7ea]'
                    : 'text-slate-600 hover:bg-slate-200/40 hover:text-slate-800' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-150
                    {{ request()->routeIs('profile.edit')
                        ? 'bg-[#10a362] text-white shadow-md shadow-[#10a362]/20'
                        : 'bg-white border border-slate-200/70 text-slate-500' }}">
                    <i class="bi bi-person-gear text-sm"></i>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Edit Profil</span>
            </a>
        </li>

    </ul>
</nav>
