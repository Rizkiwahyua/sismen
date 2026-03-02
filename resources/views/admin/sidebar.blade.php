<nav class="p-6 flex-1 bg-indigo-700">
    <ul class="space-y-3 text-sm font-semibold">

  <!-- Dashboard -->
<li>
    <a href="{{ route('admin.dashboard') }}"
       class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300
       {{ request()->routeIs('admin.dashboard')
            ? 'bg-white/20 text-white backdrop-blur-md shadow-lg'
            : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
        <span class="w-10 h-10 flex items-center justify-center rounded-lg transition-all duration-300
            {{ request()->routeIs('admin.dashboard')
                ? 'bg-white text-indigo-700'
                : 'bg-indigo-600 group-hover:bg-white group-hover:text-indigo-700' }}">
            <i class="bi bi-speedometer2 text-lg"></i>
        </span>
        Dashboard
    </a>
</li>

<!-- Dokumen -->
<li>
    <a href="{{ route('admin.documents.index') }}"
       class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300
       {{ request()->routeIs('admin.documents.*')
            ? 'bg-white/20 text-white backdrop-blur-md shadow-lg'
            : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
        <span class="w-10 h-10 flex items-center justify-center rounded-lg transition-all duration-300
            {{ request()->routeIs('admin.documents.*')
                ? 'bg-white text-indigo-700'
                : 'bg-indigo-600 group-hover:bg-white group-hover:text-indigo-700' }}">
            <i class="bi bi-folder text-lg"></i>
        </span>
        Dokumen
    </a>
</li>

<!-- Kategori -->
{{-- <li>
    <a href="{{ route('admin.document-categories.index') }}"
       class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300
       {{ request()->routeIs('admin.document-categories.*')
            ? 'bg-white/20 text-white backdrop-blur-md shadow-lg'
            : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
        <span class="w-10 h-10 flex items-center justify-center rounded-lg transition-all duration-300
            {{ request()->routeIs('admin.document-categories.*')
                ? 'bg-white text-indigo-700'
                : 'bg-indigo-600 group-hover:bg-white group-hover:text-indigo-700' }}">
            <i class="bi bi-tags text-lg"></i>
        </span>
        Kategori
    </a>
</li> --}}

<!-- Kode Dokumen -->
<li>
    <a href="{{ route('admin.document-codes.index') }}"
       class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300
       {{ request()->routeIs('admin.document-codes.*')
            ? 'bg-white/20 text-white backdrop-blur-md shadow-lg'
            : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
        <span class="w-10 h-10 flex items-center justify-center rounded-lg transition-all duration-300
            {{ request()->routeIs('admin.document-codes.*')
                ? 'bg-white text-indigo-700'
                : 'bg-indigo-600 group-hover:bg-white group-hover:text-indigo-700' }}">
            <i class="bi bi-hash text-lg"></i>
        </span>
        Kode Dokumen
    </a>
</li>

<!-- Unit Kerja -->
<li>
    <a href="{{ route('admin.department.index') }}"
       class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300
       {{ request()->routeIs('admin.department.*')
            ? 'bg-white/20 text-white backdrop-blur-md shadow-lg'
            : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
        <span class="w-10 h-10 flex items-center justify-center rounded-lg transition-all duration-300
            {{ request()->routeIs('admin.department.*')
                ? 'bg-white text-indigo-700'
                : 'bg-indigo-600 group-hover:bg-white group-hover:text-indigo-700' }}">
            <i class="bi bi-hash text-lg"></i>
        </span>
        Unit Kerja
    </a>
</li>

<li>
    <a href="{{ route('admin.rekap.index') }}"
       class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300
       {{ request()->routeIs('admin.rekap.*')
            ? 'bg-white/20 text-white backdrop-blur-md shadow-lg'
            : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">

        <span class="w-10 h-10 flex items-center justify-center rounded-lg transition-all duration-300
            {{ request()->routeIs('admin.rekap.*')
                ? 'bg-white text-indigo-700 shadow-md'
                : 'bg-indigo-600 group-hover:bg-white group-hover:text-indigo-700' }}">

            <i class="bi bi-hash text-lg"></i>
        </span>

        <span class="font-medium tracking-wide">
            Rekap Dokumen
        </span>
    </a>
</li>


<!-- User -->
<li>
    <a href="{{ route('admin.user.index') }}"
       class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300
       {{ request()->routeIs('admin.user.*')
            ? 'bg-white/20 text-white backdrop-blur-md shadow-lg'
            : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
        <span class="w-10 h-10 flex items-center justify-center rounded-lg transition-all duration-300
            {{ request()->routeIs('admin.user.*')
                ? 'bg-white text-indigo-700'
                : 'bg-indigo-600 group-hover:bg-white group-hover:text-indigo-700' }}">
            <i class="bi bi-people text-lg"></i>
        </span>
        User
    </a>
</li>

{{-- hapus --}}
<li>
    <a href="{{ route('admin.documents.trash') }}"
       class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300
       {{ request()->routeIs('admin.documents.*')
            ? 'bg-white/20 text-white backdrop-blur-md shadow-lg'
            : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
        <span class="w-10 h-10 flex items-center justify-center rounded-lg transition-all duration-300
            {{ request()->routeIs('admin.documents.*')
                ? 'bg-white text-indigo-700'
                : 'bg-indigo-600 group-hover:bg-white group-hover:text-indigo-700' }}">
            <i class="bi bi-people text-lg"></i>
        </span>
        🗑 Recycle Bin
    </a>
</li>

{{-- <li>
    <a href="{{ route('admin.documents.trash') }}">
        🗑 Recycle Bin
    </a>
</li> --}}


    </ul>
</nav>
