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
            <a href="{{ route('admin.dashboard') }}"
               :class="sidebarCollapsed ? 'justify-center px-1' : 'px-3 gap-3'"
               :title="sidebarCollapsed ? 'Dashboard' : ''"
               class="menu-item flex items-center py-2 rounded-xl border border-transparent transition-all duration-150
               {{ request()->routeIs('admin.dashboard')
                    ? 'active text-[#10a362] font-semibold bg-[#e2f7ea]'
                    : 'text-slate-600 hover:bg-slate-200/40 hover:text-slate-800' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-150
                    {{ request()->routeIs('admin.dashboard')
                        ? 'bg-[#10a362] text-white shadow-md shadow-[#10a362]/20'
                        : 'bg-white border border-slate-200/70 text-slate-500' }}">
                    <i class="bi bi-grid-1x2-fill text-sm"></i>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Dashboard</span>
            </a>
        </li>

        <!-- Section Title: Documents -->
        <li class="section-label px-3 pt-5 pb-2 transition-all duration-300"
            x-show="!sidebarCollapsed"
            x-transition.opacity>
            Manajemen Dokumen
        </li>

        <!-- Dokumen -->
        <li>
            <a href="{{ route('admin.documents.index') }}"
               :class="sidebarCollapsed ? 'justify-center px-1' : 'px-3 gap-3'"
               :title="sidebarCollapsed ? 'Daftar Dokumen' : ''"
               class="menu-item flex items-center py-2 rounded-xl border border-transparent transition-all duration-150
               {{ request()->routeIs('admin.documents.index', 'admin.documents.create', 'admin.documents.edit')
                    ? 'active text-[#10a362] font-semibold bg-[#e2f7ea]'
                    : 'text-slate-600 hover:bg-slate-200/40 hover:text-slate-800' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-150
                    {{ request()->routeIs('admin.documents.index', 'admin.documents.create', 'admin.documents.edit')
                        ? 'bg-[#10a362] text-white shadow-md shadow-[#10a362]/20'
                        : 'bg-white border border-slate-200/70 text-slate-500' }}">
                    <i class="bi bi-file-earmark-text-fill text-sm"></i>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Daftar Dokumen</span>
            </a>
        </li>

        <!-- Kode Dokumen -->
        <li>
            <a href="{{ route('admin.document-codes.index') }}"
               :class="sidebarCollapsed ? 'justify-center px-1' : 'px-3 gap-3'"
               :title="sidebarCollapsed ? 'Kode Dokumen' : ''"
               class="menu-item flex items-center py-2 rounded-xl border border-transparent transition-all duration-150
               {{ request()->routeIs('admin.document-codes.*')
                    ? 'active text-[#10a362] font-semibold bg-[#e2f7ea]'
                    : 'text-slate-600 hover:bg-slate-200/40 hover:text-slate-800' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-150
                    {{ request()->routeIs('admin.document-codes.*')
                        ? 'bg-[#10a362] text-white shadow-md shadow-[#10a362]/20'
                        : 'bg-white border border-slate-200/70 text-slate-500' }}">
                    <i class="bi bi-qr-code text-sm"></i>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Kode Dokumen</span>
            </a>
        </li>

        <!-- Unit Kerja -->
        <li>
            <a href="{{ route('admin.department.index') }}"
               :class="sidebarCollapsed ? 'justify-center px-1' : 'px-3 gap-3'"
               :title="sidebarCollapsed ? 'Unit Kerja' : ''"
               class="menu-item flex items-center py-2 rounded-xl border border-transparent transition-all duration-150
               {{ request()->routeIs('admin.department.*')
                    ? 'active text-[#10a362] font-semibold bg-[#e2f7ea]'
                    : 'text-slate-600 hover:bg-slate-200/40 hover:text-slate-800' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-150
                    {{ request()->routeIs('admin.department.*')
                        ? 'bg-[#10a362] text-white shadow-md shadow-[#10a362]/20'
                        : 'bg-white border border-slate-200/70 text-slate-500' }}">
                    <i class="bi bi-building-fill text-sm"></i>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Unit Kerja</span>
            </a>
        </li>

        <!-- Rekap Dokumen -->
        <li>
            <a href="{{ route('admin.rekap.index') }}"
               :class="sidebarCollapsed ? 'justify-center px-1' : 'px-3 gap-3'"
               :title="sidebarCollapsed ? 'Rekap Dokumen' : ''"
               class="menu-item flex items-center py-2 rounded-xl border border-transparent transition-all duration-150
               {{ request()->routeIs('admin.rekap.*')
                    ? 'active text-[#10a362] font-semibold bg-[#e2f7ea]'
                    : 'text-slate-600 hover:bg-slate-200/40 hover:text-slate-800' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-150
                    {{ request()->routeIs('admin.rekap.*')
                        ? 'bg-[#10a362] text-white shadow-md shadow-[#10a362]/20'
                        : 'bg-white border border-slate-200/70 text-slate-500' }}">
                    <i class="bi bi-file-earmark-bar-graph-fill text-sm"></i>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Rekap Dokumen</span>
            </a>
        </li>

        <!-- Section Title: Administration -->
        <li class="section-label px-3 pt-5 pb-2 transition-all duration-300"
            x-show="!sidebarCollapsed"
            x-transition.opacity>
            Administrasi Sistem
        </li>

        <!-- User -->
        <li>
            <a href="{{ route('admin.user.index') }}"
               :class="sidebarCollapsed ? 'justify-center px-1' : 'px-3 gap-3'"
               :title="sidebarCollapsed ? 'Manajemen User' : ''"
               class="menu-item flex items-center py-2 rounded-xl border border-transparent transition-all duration-150
               {{ request()->routeIs('admin.user.*')
                    ? 'active text-[#10a362] font-semibold bg-[#e2f7ea]'
                    : 'text-slate-600 hover:bg-slate-200/40 hover:text-slate-800' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-150
                    {{ request()->routeIs('admin.user.*')
                        ? 'bg-[#10a362] text-white shadow-md shadow-[#10a362]/20'
                        : 'bg-white border border-slate-200/70 text-slate-500' }}">
                    <i class="bi bi-people-fill text-sm"></i>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Manajemen User</span>
            </a>
        </li>

        <!-- Recycle Bin -->
        <li>
            <a href="{{ route('admin.documents.trash') }}"
               :class="sidebarCollapsed ? 'justify-center px-1' : 'px-3 gap-3'"
               :title="sidebarCollapsed ? 'Dokumen Terhapus' : ''"
               class="menu-item flex items-center py-2 rounded-xl border border-transparent transition-all duration-150
               {{ request()->routeIs('admin.documents.trash')
                    ? 'active text-[#10a362] font-semibold bg-[#e2f7ea]'
                    : 'text-slate-600 hover:bg-slate-200/40 hover:text-slate-800' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-150
                    {{ request()->routeIs('admin.documents.trash')
                        ? 'bg-[#10a362] text-white shadow-md shadow-[#10a362]/20'
                        : 'bg-white border border-slate-200/70 text-slate-500' }}">
                    <i class="bi bi-trash3-fill text-sm"></i>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Dokumen Terhapus</span>
            </a>
        </li>

    </ul>
</nav>
