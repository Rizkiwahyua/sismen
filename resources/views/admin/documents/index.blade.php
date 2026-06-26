    @extends('layouts.app')

    @section('content')
        @php
            $currentCategory = request('category', 'all');
        @endphp

        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">Data Dokumen</h2>
                <p class="text-xs text-slate-400 mt-0.5">Kelola seluruh dokumen sistem secara terpusat</p>
            </div>

            <div class="flex items-center gap-2.5">
                <button onclick="toggleDocumentCharts()"
                    class="inline-flex items-center justify-center gap-2 bg-blue-50 text-[#0f3c7a] border border-blue-100/80 hover:bg-blue-100 hover:text-[#0a2d5c] px-4 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm cursor-pointer">
                    <i class="bi bi-bar-chart-line-fill text-sm"></i>
                    <span id="chartsBtnText">Tampilkan Grafik</span>
                </button>

                <a href="{{ route('admin.documents.create') }}"
                    class="inline-flex items-center gap-1.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-150 hover:shadow">
                    <i class="bi bi-plus-lg text-sm"></i>
                    Tambah Dokumen
                </a>
            </div>
        </div>

        <div class="border-b border-slate-200 mb-6 -mx-1">
            <!-- Category Tabs -->
            <div class="flex gap-1 overflow-x-auto scrollbar-none -mb-px">
                @php
                    $tabs = [
                        ['all', 'Semua', 'bi-grid-fill', 'text-slate-400'],
                        ['ratifikasi', 'Ratifikasi', 'bi-patch-check-fill', 'text-emerald-500'],
                        ['pedoman', 'Pedoman', 'bi-journal-text-fill', 'text-blue-500'],
                        ['prosedur', 'Prosedur', 'bi-diagram-3-fill', 'text-purple-500'],
                        ['instruksikerja', 'Instruksi Kerja', 'bi-gear-fill', 'text-amber-500'],
                        ['formulir', 'Formulir', 'bi-files', 'text-rose-500'],
                    ];
                @endphp

                @foreach($tabs as [$slug, $name, $icon, $colorClass])
                    <a href="{{ route('admin.documents.index', array_merge(request()->query(), ['category' => $slug])) }}"
                        class="flex items-center gap-2 px-4 py-3 border-b-2 text-xs font-medium transition-all duration-150 whitespace-nowrap
                        {{ $currentCategory == $slug
                            ? 'border-indigo-600 text-indigo-600 font-semibold'
                            : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-200' }}">
                        <i class="{{ $icon }} text-[13px] {{ $currentCategory == $slug ? 'text-indigo-600' : $colorClass }}"></i>
                        {{ $name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Collapsible Charts Section -->
        @php
            // 1. Hitung top unit kerja aktif
            $deptCounts = [];
            foreach ($chartDocs as $doc) {
                $deptName = $doc->department->name ?? 'Lainnya';
                $deptCounts[$deptName] = ($deptCounts[$deptName] ?? 0) + 1;
            }
            arsort($deptCounts);
            $topDepts = array_slice($deptCounts, 0, 5, true);

            // 2. Hitung tren dokumen per tahun
            $yearCounts = [];
            foreach ($chartDocs as $doc) {
                if ($doc->document_date) {
                    $year = \Carbon\Carbon::parse($doc->document_date)->format('Y');
                    $yearCounts[$year] = ($yearCounts[$year] ?? 0) + 1;
                }
            }
            ksort($yearCounts);
            if (empty($yearCounts)) {
                $yearCounts[date('Y')] = 0;
            }

            // 3. Hitung tingkat kepatuhan unggah file
            $totalDocsCount = max($chartDocs->count(), 1);
            $uploadedCount = $chartDocs->filter(fn($d) => !empty($d->file_document))->count();
            $compliancePercent = round(($uploadedCount / $totalDocsCount) * 100);
            
            // 4. Kategori Counts
            $ratifikasiCount = $chartDocs->filter(fn($d) => $d->category?->slug === 'ratifikasi')->count();
            $pedomanCount = $chartDocs->filter(fn($d) => $d->category?->slug === 'pedoman')->count();
            $prosedurCount = $chartDocs->filter(fn($d) => $d->category?->slug === 'prosedur')->count();
            $instruksiCount = $chartDocs->filter(fn($d) => $d->category?->slug === 'instruksikerja')->count();
            $formulirCount = $chartDocs->filter(fn($d) => $d->category?->slug === 'formulir')->count();
        @endphp

        <div id="documentChartsPanel" class="hidden grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <!-- 1. compliance chart -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
                <h3 class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mb-2">Kepatuhan Upload</h3>
                <div id="complianceChart" class="h-64 flex items-center justify-center"></div>
            </div>

            <!-- 2. doughnut chart -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
                <h3 class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mb-2">Pembagian Kategori</h3>
                <div id="categoryChart" class="h-64 flex items-center justify-center"></div>
            </div>

            <!-- 3. bar chart -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
                <h3 class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mb-2">Top Unit Kerja Teraktif</h3>
                <div id="departmentChart" class="h-64"></div>
            </div>

            <!-- 4. line chart -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
                <h3 class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mb-2">Tren Dokumen per Tahun</h3>
                <div id="trendChart" class="h-64"></div>
            </div>
        </div>

        <!-- CUSTOM TOM SELECT STYLES -->
        <style>
            /* Custom premium override styling for Tom Select */
            .ts-wrapper.multi .ts-control {
                border: 1px solid #cbd5e1 !important; /* slate-300 */
                border-radius: 0.5rem !important; /* rounded-lg */
                padding: 0.55rem 0.75rem !important;
                font-size: 0.75rem !important;
                font-family: 'Plus Jakarta Sans', sans-serif !important;
                background-color: #ffffff !important;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
                transition: all 0.15s ease-in-out !important;
                min-height: 38px !important;
            }
            .ts-wrapper.multi.focus .ts-control {
                border-color: #0f3c7a !important; /* Corporate Navy focus */
                box-shadow: 0 0 0 3px rgba(15, 60, 122, 0.12) !important;
            }
            .ts-dropdown {
                border-radius: 0.5rem !important;
                border: 1px solid #cbd5e1 !important;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.08) !important;
                font-size: 0.75rem !important;
                z-index: 50 !important;
                font-family: 'Plus Jakarta Sans', sans-serif !important;
                margin-top: 4px !important;
            }
            .ts-dropdown .active {
                background-color: #f0f5ff !important;
                color: #0f3c7a !important;
                font-weight: 500;
            }
            .ts-dropdown .option {
                padding: 8px 12px !important;
                transition: background-color 0.1s ease !important;
            }
            .ts-dropdown .option:hover {
                background-color: #f8fafc !important;
            }
            /* Selected items / badges */
            .ts-control .item {
                background-color: #f0f5ff !important;
                color: #0f3c7a !important;
                border: 1px solid #bfdbfe !important;
                border-radius: 0.375rem !important;
                padding: 2px 8px !important;
                font-weight: 600 !important;
                font-size: 10px !important;
                display: inline-flex !important;
                align-items: center !important;
                gap: 4px !important;
                margin: 2px 3px 2px 0 !important;
                box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.02) !important;
            }
            .ts-wrapper.multi .ts-control > input {
                font-size: 0.75rem !important;
                font-family: 'Plus Jakarta Sans', sans-serif !important;
            }
            .ts-control .item .remove {
                border-left: 1px solid #bfdbfe !important;
                margin-left: 4px !important;
                padding-left: 6px !important;
                color: #ef4444 !important;
                opacity: 0.8;
                font-weight: bold !important;
            }
            .ts-control .item .remove:hover {
                background-color: #fee2e2 !important;
                opacity: 1;
            }
        </style>

        <!-- FILTERS & SEARCH ROW -->
        <div class="bg-white rounded-xl border border-slate-200/60 p-5 mb-6 shadow-sm">
            <form method="GET" action="{{ route('admin.documents.index') }}" class="space-y-4" id="filter-form">
                <input type="hidden" name="category" value="{{ $currentCategory }}">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- Unit Kerja Dropdown -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Unit Kerja (Multi-select)</label>
                        <select name="department[]" id="filter-department" multiple autocomplete="off"
                            class="text-xs w-full bg-white text-slate-700 shadow-sm cursor-pointer">
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}" {{ in_array($dept->id, (array) request('department', [])) ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kode Dokumen Dropdown -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kode Dokumen (Multi-select)</label>
                        <select name="code[]" id="filter-code" multiple autocomplete="off"
                            class="text-xs w-full bg-white text-slate-700 shadow-sm cursor-pointer">
                            @foreach ($codes as $code)
                                <option value="{{ $code->id }}" {{ in_array($code->id, (array) request('code', [])) ? 'selected' : '' }}>
                                    {{ $code->code }} - {{ $code->description }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kata Kunci Search -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kata Kunci</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor, judul, deskripsi..."
                                class="pl-9 pr-4 py-2.5 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 w-full bg-white text-slate-800 shadow-sm h-[38px] transition duration-150">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-450">
                                <i class="bi bi-search text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Row -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-3 border-t border-slate-100">
                    <div class="text-[11px] text-slate-400 font-medium flex items-center gap-1.5">
                        @php
                            $activeDepts = count(array_filter((array) request('department', [])));
                            $activeCodes = count(array_filter((array) request('code', [])));
                            $hasSearch = request('search') ? 1 : 0;
                        @endphp
                        @if ($activeDepts || $activeCodes || $hasSearch)
                            <span class="inline-flex items-center gap-1.5 bg-indigo-50/65 text-indigo-700 border border-indigo-100/50 px-2.5 py-1 rounded-lg">
                                <i class="bi bi-info-circle-fill text-[11px] text-indigo-500"></i>
                                Filter Aktif: 
                                @if($activeDepts) <b>{{ $activeDepts }} Unit Kerja</b> @endif
                                @if($activeDepts && $activeCodes) & @endif
                                @if($activeCodes) <b>{{ $activeCodes }} Kode</b> @endif
                                @if(($activeDepts || $activeCodes) && $hasSearch) & @endif
                                @if($hasSearch) <b>Pencarian "{{ request('search') }}"</b> @endif
                            </span>
                        @else
                            <span class="text-slate-400 flex items-center gap-1">
                                <i class="bi bi-funnel text-xs"></i>
                                Menampilkan semua dokumen
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 self-end sm:self-auto">
                        @if ($activeDepts || $activeCodes || $hasSearch)
                            <a href="{{ route('admin.documents.index', ['category' => $currentCategory]) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 text-xs font-semibold rounded-lg transition duration-150">
                                <i class="bi bi-arrow-counterclockwise text-xs"></i>
                                Reset Filter
                            </a>
                        @endif
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-xs font-semibold px-4 py-2 rounded-lg shadow-sm transition-all duration-150 hover:shadow hover:-translate-y-0.5 active:translate-y-0">
                            <i class="bi bi-funnel-fill text-xs"></i>
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- INITIALIZE TOM SELECT -->
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                new TomSelect('#filter-department', {
                    plugins: ['remove_button'],
                    create: false,
                    maxItems: null,
                    placeholder: 'Pilih Unit Kerja...',
                    onDropdownOpen: function() {
                        // styling fix
                    }
                });

                new TomSelect('#filter-code', {
                    plugins: ['remove_button'],
                    create: false,
                    maxItems: null,
                    placeholder: 'Pilih Kode Dokumen...',
                    onDropdownOpen: function() {
                        // styling fix
                    }
                });
            });
        </script>

            <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-separate border-spacing-y-2.5 px-1">

                <thead>
                    <tr class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">
                        <th class="px-5 py-2 w-12">No</th>
                        <th class="px-5 py-2">Nomor</th>
                        <th class="px-5 py-2">Nama Dokumen</th>
                        <th class="px-5 py-2 text-center">Revisi</th>
                        <th class="px-5 py-2">Unit Kerja</th>
                        <th class="px-5 py-2">Tanggal</th>
                        <th class="px-5 py-2 text-center">Status</th>
                        <th class="px-5 py-2">Keterangan</th>
                        <th class="px-5 py-2 text-center w-40">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($documents as $doc)
                        <!-- ROW UTAMA -->
                        <tr class="group transition duration-150">

                            <td class="px-5 py-4 text-slate-500 font-medium bg-white border-y border-slate-200/60 first:border-l last:border-r first:rounded-l-xl last:rounded-r-xl shadow-sm group-hover:shadow transition-all duration-150">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-5 py-4 font-semibold text-slate-800 text-xs bg-white border-y border-slate-200/60 first:border-l last:border-r first:rounded-l-xl last:rounded-r-xl shadow-sm group-hover:shadow transition-all duration-150">
                                {{ $doc->document_number }}
                            </td>

                            <td class="px-5 py-4 bg-white border-y border-slate-200/60 first:border-l last:border-r first:rounded-l-xl last:rounded-r-xl shadow-sm group-hover:shadow transition-all duration-150">
                                <div class="font-bold text-slate-800 hover:text-indigo-600 transition cursor-pointer text-xs" onclick="toggleDetail({{ $doc->id }})">
                                    {{ $doc->title }}
                                </div>
                            </td>

                            <td class="px-5 py-4 text-center bg-white border-y border-slate-200/60 first:border-l last:border-r first:rounded-l-xl last:rounded-r-xl shadow-sm group-hover:shadow transition-all duration-150">
                                <span class="inline-flex items-center bg-violet-50 border border-violet-100 px-2 py-0.5 rounded text-[10px] font-bold text-violet-700">
                                    Rev {{ $doc->revision ?? 0 }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-xs text-slate-700 font-medium bg-white border-y border-slate-200/60 first:border-l last:border-r first:rounded-l-xl last:rounded-r-xl shadow-sm group-hover:shadow transition-all duration-150">
                                {{ $doc->department->name ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-xs text-slate-500 bg-white border-y border-slate-200/60 first:border-l last:border-r first:rounded-l-xl last:rounded-r-xl shadow-sm group-hover:shadow transition-all duration-150">
                                {{ \Carbon\Carbon::parse($doc->document_date)->format('d-m-Y') }}
                            </td>
                            
                            <td class="px-5 py-4 bg-white border-y border-slate-200/60 first:border-l last:border-r first:rounded-l-xl last:rounded-r-xl shadow-sm group-hover:shadow transition-all duration-150">
                                <div class="flex flex-col items-center gap-1.5">
                                    @if(!empty($doc->file_document) && file_exists(public_path($doc->file_document)))
                                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded-lg text-[10px] font-bold text-emerald-700 shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Lengkap
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-100 px-2.5 py-1 rounded-lg text-[10px] font-bold text-amber-700 shadow-sm">
                                            <span class="relative flex h-1.5 w-1.5">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-amber-500"></span>
                                            </span>
                                            Belum Upload
                                        </span>
                                    @endif
                                </div>
                            </td>
                            
                            <td class="px-5 py-4 text-xs text-slate-500 max-w-[200px] truncate bg-white border-y border-slate-200/60 first:border-l last:border-r first:rounded-l-xl last:rounded-r-xl shadow-sm group-hover:shadow transition-all duration-150" title="{{ $doc->description }}">
                                {{ $doc->description ?? '-' }}
                            </td>
                            
                            <td class="px-5 py-4 bg-white border-y border-slate-200/60 first:border-l last:border-r first:rounded-l-xl last:rounded-r-xl shadow-sm group-hover:shadow transition-all duration-150">
                                <div class="flex flex-col items-center gap-2.5">
                                    
                                    <!-- Uploader / Reviser Metadata -->
                                    <div class="text-[9px] text-slate-400 font-bold flex flex-col items-center leading-normal mb-1">
                                        @if($doc->uploader)
                                            <span class="truncate max-w-[120px] text-center" title="Diunggah oleh: {{ $doc->uploader->name }}">
                                                <i class="bi bi-person-fill text-slate-350"></i> {{ $doc->uploader->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-300">-</span>
                                        @endif
                                        
                                        @if($doc->updater && $doc->updater_id !== $doc->user_id)
                                            <span class="text-indigo-500/85 truncate max-w-[120px] mt-0.5 text-center" title="Direvisi oleh: {{ $doc->updater->name }}">
                                                <i class="bi bi-pencil-fill text-[8px] text-indigo-400"></i> {{ $doc->updater->name }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.documents.preview', $doc->id) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 border border-blue-100/50 hover:bg-blue-100 hover:text-blue-700 transition duration-150 shadow-sm"
                                            title="Lihat Preview">
                                            <i class="bi bi-eye text-sm"></i>
                                        </a>

                                        <a href="{{ route('admin.documents.edit', $doc->id) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-700 border border-amber-100/50 hover:bg-amber-100 hover:text-amber-800 transition duration-150 shadow-sm"
                                            title="Edit Dokumen">
                                            <i class="bi bi-pencil text-sm"></i>
                                        </a>

                                        <button onclick="openDeleteModal({{ $doc->id }})"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 border border-rose-100/50 hover:bg-rose-100 hover:text-rose-700 transition duration-150 shadow-sm"
                                            title="Hapus Dokumen">
                                            <i class="bi bi-trash text-sm"></i>
                                        </button>

                                        <button onclick="toggleDetail({{ $doc->id }})"
                                            class="inline-flex items-center gap-1 text-[11px] font-bold bg-indigo-50/70 text-indigo-600 border border-indigo-100/60 hover:bg-indigo-100 hover:text-indigo-700 px-2.5 py-1.5 rounded-lg transition duration-150 shadow-sm">
                                            <span>Detail</span>
                                            <i class="bi bi-chevron-down text-[9px]"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>

                        </tr>

                        <!-- DETAIL -->
                        <tr id="detail-{{ $doc->id }}" class="hidden">
                            <td colspan="9" class="px-5 py-1 bg-transparent">
                                <div class="bg-white border border-slate-200/80 rounded-xl p-5 shadow-sm mb-3">
                                    <div class="flex justify-between items-center mb-3 pb-2 border-b border-slate-100">
                                        <h4 class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                            <i class="bi bi-diagram-2 text-indigo-500"></i>
                                            Daftar Sub Judul / Uraian Detail
                                        </h4>
                                        <span class="text-[10px] text-slate-400 font-semibold bg-slate-100 px-2 py-0.5 rounded">
                                            {{ $doc->details->count() }} Item
                                        </span>
                                    </div>

                                    @if ($doc->details->count())
                                        <div class="overflow-hidden border border-slate-200/60 rounded-lg">
                                            <table class="w-full text-xs text-left border-collapse">
                                                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                                                    <tr>
                                                        <th class="px-4 py-2 w-12 text-center">No</th>
                                                        <th class="px-4 py-2">Sub Judul / Judul Uraian</th>
                                                        <th class="px-4 py-2">Unit Kerja Terkait</th>
                                                        <th class="px-4 py-2">Keterangan</th>
                                                        <th class="px-4 py-2 text-center w-28">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100 bg-white">
                                                    @foreach ($doc->details as $index => $d)
                                                        <tr class="hover:bg-slate-50/50 transition">
                                                            <td class="px-4 py-2.5 text-center text-slate-400 font-medium">{{ $index + 1 }}</td>
                                                            
                                                            <!-- SUB JUDUL / EDIT FORM -->
                                                            <td class="px-4 py-2.5">
                                                                <!-- VIEW -->
                                                                <div id="view-{{ $d->id }}" class="font-medium text-slate-700">
                                                                    {{ $d->sub_title }}
                                                                </div>

                                                                <!-- EDIT -->
                                                                <form id="edit-{{ $d->id }}" action="{{ route('subdetail.update', $d->id) }}" method="POST" class="hidden mt-2 space-y-2 max-w-md bg-slate-50 p-3 rounded-lg border border-slate-200">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div>
                                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Sub Judul</label>
                                                                        <input type="text" name="sub_title" value="{{ $d->sub_title }}" class="border border-slate-200 px-2 py-1.5 rounded-lg w-full text-xs focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500">
                                                                    </div>
                                                                    <div>
                                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Unit Kerja Terkait</label>
                                                                        <select name="department_ids[]" multiple class="border border-slate-200 p-2 rounded-lg w-full text-xs h-28 focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500">
                                                                            @foreach ($departments as $dept)
                                                                                <option value="{{ $dept->id }}"
                                                                                    {{ in_array($dept->id, $d->department_ids ?? []) ? 'selected' : '' }}>
                                                                                    {{ $dept->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div>
                                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Keterangan</label>
                                                                        <input type="text" name="description" value="{{ $d->description }}" class="border border-slate-200 px-2 py-1.5 rounded-lg w-full text-xs focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500">
                                                                    </div>
                                                                    <div class="flex gap-2 justify-end pt-1">
                                                                        <button type="button" onclick="cancelEdit({{ $d->id }})" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-2.5 py-1 rounded-md text-[10px] font-semibold transition">
                                                                            Batal
                                                                        </button>
                                                                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-2.5 py-1 rounded-md text-[10px] font-semibold transition">
                                                                            Simpan
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </td>

                                                            <!-- UNIT KERJA TERKAIT -->
                                                            <td class="px-4 py-2.5 text-slate-600">
                                                                @php
                                                                    $deptNames = [];
                                                                    if (!empty($d->department_ids)) {
                                                                        $deptIds = is_array($d->department_ids)
                                                                            ? $d->department_ids
                                                                            : json_decode($d->department_ids, true);

                                                                        $deptNames = \App\Models\Department::whereIn('id', $deptIds)
                                                                            ->pluck('name')
                                                                            ->toArray();
                                                                    }
                                                                @endphp

                                                                @if (!empty($deptNames))
                                                                    <div class="flex flex-wrap gap-1.5">
                                                                        @foreach ($deptNames as $name)
                                                                            <span class="bg-sky-50 text-sky-700 border border-sky-100 px-2 py-0.5 rounded text-[10px] font-semibold shadow-sm">{{ $name }}</span>
                                                                        @endforeach
                                                                    </div>
                                                                @else
                                                                    <span class="text-slate-400">-</span>
                                                                @endif
                                                            </td>

                                                            <!-- KETERANGAN -->
                                                            <td class="px-4 py-2.5 text-slate-500 text-xs">
                                                                {{ $d->description ?? '-' }}
                                                            </td>

                                                            <!-- AKSI SUB DETAIL -->
                                                            <td class="px-4 py-2.5">
                                                                <div class="flex gap-1.5 justify-center">
                                                                    <button onclick="editSub({{ $d->id }})"
                                                                        class="w-7 h-7 flex items-center justify-center rounded-lg bg-amber-50 text-amber-700 border border-amber-100 hover:bg-amber-100 transition"
                                                                        title="Edit">
                                                                        <i class="bi bi-pencil text-[11px]"></i>
                                                                    </button>

                                                                    <form action="{{ route('subdetail.destroy', $d->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sub detail ini?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button class="w-7 h-7 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 border border-rose-100 hover:bg-rose-100 transition"
                                                                            title="Hapus">
                                                                            <i class="bi bi-trash text-[11px]"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-6 text-slate-400 text-xs font-medium">
                                            Tidak ada sub judul pada dokumen ini
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="9" class="text-center p-5 text-gray-400">
                                Tidak ada dokumen
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>


        </div>


        <script>
            document.addEventListener("DOMContentLoaded", () => {

                const counters = document.querySelectorAll('.counter');

                counters.forEach(counter => {
                    const updateCount = () => {
                        const target = +counter.getAttribute('data-target');
                        const current = +counter.innerText;

                        const increment = Math.ceil(target / 50);

                        if (current < target) {
                            counter.innerText = current + increment;
                            setTimeout(updateCount, 20);
                        } else {
                            counter.innerText = target;
                        }
                    };

                    updateCount();
                });

            });
        </script>

        <script>
            function editSub(id) {
                document.getElementById('view-' + id).classList.add('hidden');
                document.getElementById('edit-' + id).classList.remove('hidden');
            }

            function cancelEdit(id) {
                document.getElementById('view-' + id).classList.remove('hidden');
                document.getElementById('edit-' + id).classList.add('hidden');
            }
        </script>
        <!-- DELETE MODAL -->
        <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-lg w-96 p-6">

                <h3 class="text-lg font-semibold mb-4">Keterangan Hapus Dokumen</h3>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <textarea name="delete_reason" id="deleteReason" class="w-full border rounded p-2 text-sm"
                        placeholder="Masukkan alasan penghapusan..." required></textarea>

                    <div class="flex justify-end gap-3 mt-4">
                        <button type="button" onclick="closeDeleteModal()" class="px-3 py-1 bg-gray-300 rounded">
                            Batal
                        </button>

                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">
                            Hapus
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <script>
            function openDeleteModal(id) {
                const modal = document.getElementById('deleteModal');
                const form = document.getElementById('deleteForm');

                form.action = '/admin/documents/' + id;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeDeleteModal() {
                const modal = document.getElementById('deleteModal');
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        </script>
        <script>
            let searchTimer;

            function debounceSearch(form) {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    form.submit();
                }, 500); // delay 500ms biar gak reload tiap huruf
            }
        </script>


        <script>
            function toggleDetail(id) {
                let el = document.getElementById('detail-' + id);
                el.classList.toggle('hidden');
            }
        </script>

        <!-- Script ApexCharts -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            function toggleDocumentCharts() {
                let panel = document.getElementById('documentChartsPanel');
                let btnText = document.getElementById('chartsBtnText');
                if (panel.classList.contains('hidden')) {
                    panel.classList.remove('hidden');
                    btnText.innerText = "Sembunyikan Grafik";
                    if (!window.documentChartsInitialized) {
                        initDocumentCharts();
                        window.documentChartsInitialized = true;
                    }
                } else {
                    panel.classList.add('hidden');
                    btnText.innerText = "Tampilkan Grafik";
                }
            }

            function initDocumentCharts() {
                // 1. Compliance Chart (Radial Bar)
                const compOptions = {
                    chart: {
                        type: 'radialBar',
                        height: 240,
                        fontFamily: 'Plus Jakarta Sans, sans-serif'
                    },
                    series: [{{ $compliancePercent }}],
                    colors: ['#0f3c7a'],
                    plotOptions: {
                        radialBar: {
                            startAngle: -135,
                            endAngle: 135,
                            hollow: {
                                size: '65%',
                            },
                            track: {
                                background: '#f1f5f9',
                                strokeWidth: '97%',
                            },
                            dataLabels: {
                                name: {
                                    show: true,
                                    color: '#64748b',
                                    fontSize: '10px',
                                    fontWeight: 600,
                                    label: 'Kepatuhan',
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    color: '#0f3c7a',
                                    fontSize: '20px',
                                    fontWeight: 700,
                                    offsetY: 4,
                                    formatter: function (val) {
                                        return val + "%";
                                    }
                                }
                            }
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            type: 'horizontal',
                            shadeIntensity: 0.5,
                            gradientToColors: ['#3b82f6'],
                            inverseColors: true,
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [0, 100]
                        }
                    },
                    stroke: {
                        lineCap: 'round'
                    },
                    labels: ['Kepatuhan Upload']
                };
                const complianceChart = new ApexCharts(document.querySelector("#complianceChart"), compOptions);
                complianceChart.render();

                // 2. Category Doughnut Chart
                const catOptions = {
                    chart: {
                        type: 'donut',
                        height: 250,
                        fontFamily: 'Plus Jakarta Sans, sans-serif'
                    },
                    series: [
                        {{ $ratifikasiCount }}, 
                        {{ $pedomanCount }}, 
                        {{ $prosedurCount }}, 
                        {{ $instruksiCount }}, 
                        {{ $formulirCount }}
                    ],
                    labels: ['Ratifikasi', 'Pedoman', 'Prosedur', 'Instruksi Kerja', 'Formulir'],
                    colors: ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#f43f5e'],
                    legend: {
                        position: 'bottom',
                        fontSize: '10px',
                        fontWeight: 600,
                        labels: {
                            colors: '#64748b'
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        fontSize: '11px',
                                        fontWeight: 600,
                                        color: '#64748b',
                                        formatter: function (w) {
                                            return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                        }
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: false
                    }
                };
                const categoryChart = new ApexCharts(document.querySelector("#categoryChart"), catOptions);
                categoryChart.render();

                // 3. Department Bar Chart
                const deptOptions = {
                    chart: {
                        type: 'bar',
                        height: 230,
                        toolbar: { show: false },
                        fontFamily: 'Plus Jakarta Sans, sans-serif'
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 6,
                            horizontal: true,
                            barHeight: '60%',
                            distributed: true
                        }
                    },
                    colors: ['#0f3c7a', '#2563eb', '#3b82f6', '#60a5fa', '#93c5fd'],
                    series: [{
                        name: 'Dokumen',
                        data: [
                            @foreach($topDepts as $dept => $count)
                                {{ $count }},
                            @endforeach
                        ]
                    }],
                    xaxis: {
                        categories: [
                            @foreach($topDepts as $dept => $count)
                                '{{ $dept }}',
                            @endforeach
                        ],
                        labels: {
                            style: {
                                fontSize: '9px',
                                fontWeight: 600,
                                colors: '#64748b'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                fontSize: '9px',
                                fontWeight: 650,
                                colors: '#334155'
                            }
                        }
                    },
                    grid: {
                        borderColor: '#f1f5f9'
                    },
                    legend: { show: false },
                    dataLabels: { enabled: false }
                };
                const departmentChart = new ApexCharts(document.querySelector("#departmentChart"), deptOptions);
                departmentChart.render();

                // 4. Trend Chart
                const trendOptions = {
                    chart: {
                        type: 'area',
                        height: 230,
                        toolbar: { show: false },
                        fontFamily: 'Plus Jakarta Sans, sans-serif'
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2.5
                    },
                    colors: ['#0f3c7a'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.35,
                            opacityTo: 0.05,
                            stops: [0, 90, 100]
                        }
                    },
                    series: [{
                        name: 'Jumlah Dokumen',
                        data: [
                            @foreach($yearCounts as $year => $count)
                                {{ $count }},
                            @endforeach
                        ]
                    }],
                    xaxis: {
                        categories: [
                            @foreach($yearCounts as $year => $count)
                                '{{ $year }}',
                            @endforeach
                        ],
                        labels: {
                            style: {
                                fontSize: '9px',
                                fontWeight: 600,
                                colors: '#64748b'
                            }
                        }
                    },
                    grid: {
                        borderColor: '#f1f5f9'
                    },
                    dataLabels: { enabled: false }
                };
                const trendChart = new ApexCharts(document.querySelector("#trendChart"), trendOptions);
                trendChart.render();
            }
        </script>
    @endsection
