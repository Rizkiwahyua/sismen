@extends('layouts.app')

@section('content')
    {{-- ================= SUMMARY CARDS ================= --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-4 mb-8">

        @php
            $cards = [
                ['Semua Dokumen', $totalDokumen, 'bi-files', 'bg-blue-50 text-blue-600 border-blue-100', 'all'],
                ['Ratifikasi', $totalRatifikasi, 'bi-patch-check-fill', 'bg-emerald-50 text-emerald-600 border-emerald-100', 'ratifikasi'],
                ['Pedoman', $totalPedoman, 'bi-journal-text', 'bg-sky-50 text-sky-600 border-sky-100', 'pedoman'],
                ['Prosedur', $totalProsedur, 'bi-diagram-3-fill', 'bg-violet-50 text-violet-600 border-violet-100', 'prosedur'],
                ['Instruksi', $totalInstruksi, 'bi-gear-fill', 'bg-amber-50 text-amber-600 border-amber-100', 'instruksikerja'],
                ['Formulir', $totalFormulir, 'bi-file-earmark-spreadsheet-fill', 'bg-rose-50 text-rose-600 border-rose-100', 'formulir'],
            ];
        @endphp

        @foreach ($cards as [$title, $value, $icon, $styleClass, $slug])
            <a href="{{ route('admin.rekap.index', array_merge(request()->query(), ['category' => $slug, 'file_status' => 'all'])) }}"
               class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 hover:shadow-md hover:border-slate-300 transition duration-200 flex flex-col justify-between cursor-pointer">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-450 truncate pr-1" title="{{ $title }}">{{ $title }}</span>
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ $styleClass }} border shadow-sm">
                        <i class="bi {{ $icon }} text-xs"></i>
                    </div>
                </div>
                <div class="text-xl font-bold text-slate-800 tracking-tight">
                    {{ $value }}
                </div>
            </a>
        @endforeach

        {{-- Special Card: Belum Upload --}}
        <a href="{{ route('admin.rekap.index', array_merge(request()->query(), ['file_status' => 'belum_upload'])) }}"
           class="bg-white rounded-2xl shadow-sm border border-rose-200/60 p-4 hover:shadow-md hover:border-rose-300 transition duration-200 flex flex-col justify-between cursor-pointer bg-rose-50/10">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] uppercase font-bold tracking-wider text-rose-500 truncate pr-1" title="Belum Upload File">Belum Upload</span>
                <div class="w-7 h-7 rounded-lg flex items-center justify-center bg-rose-100 text-rose-700 border border-rose-200 shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill text-xs"></i>
                </div>
            </div>
            <div class="text-xl font-bold text-rose-700 tracking-tight">
                {{ $totalBelumUpload }}
            </div>
        </a>

    </div>


    {{-- ================= CHARTS PANEL (COLLAPSIBLE) ================= --}}
    <div id="rekapChartsPanel" class="hidden mb-8 space-y-6">

        {{-- Row 1: 3 Donut / Radial Charts --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- 1. Kepatuhan Upload (Radial Bar) -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
                <h3 class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mb-2">Kepatuhan Upload</h3>
                <div id="rekapComplianceChart" class="h-64 flex items-center justify-center"></div>
            </div>

            <!-- 2. Pembagian Kategori (Donut) -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
                <h3 class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mb-2">Pembagian Kategori</h3>
                <div id="rekapCategoryChart" class="h-64 flex items-center justify-center"></div>
            </div>

            <!-- 3. Rasio Kelengkapan Berkas (Donut) -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
                <h3 class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mb-2">Rasio Kelengkapan Berkas</h3>
                <div id="rekapStatusChart" class="h-64 flex items-center justify-center"></div>
            </div>
        </div>

        {{-- Row 2: 2 Bar / Area Charts (wider for better label spacing) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- 4. Top Unit Kerja Teraktif (Bar) -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
                <h3 class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mb-2">Top Unit Kerja Teraktif</h3>
                <div id="rekapDepartmentChart" class="h-72"></div>
            </div>

            <!-- 5. Tren Dokumen per Tahun (Area) -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
                <h3 class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mb-2">Tren Dokumen per Tahun</h3>
                <div id="rekapTrendChart" class="h-72"></div>
            </div>
        </div>

    </div>


    {{-- ================= HEADER + EXPORT ================= --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 mb-6">

        <div class="flex flex-col sm:flex-row justify-between sm:items-center px-6 py-4.5 border-b border-slate-100 gap-3 bg-slate-50/50 rounded-t-2xl">
            <div>
                <h2 class="font-bold text-slate-800 tracking-tight text-base">
                    Rekap Data Dokumen
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Analisis dan ekspor seluruh rekapitulasi data dokumen corporate</p>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                <button onclick="toggleRekapCharts()"
                    class="inline-flex items-center justify-center gap-2 bg-blue-50 text-[#0f3c7a] border border-blue-100/80 hover:bg-blue-100 hover:text-[#0a2d5c] px-4 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm">
                    <i class="bi bi-bar-chart-line-fill text-sm"></i>
                    <span id="chartsBtnText">Tampilkan Grafik</span>
                </button>

                {{-- Tombol Export Grafik (hanya muncul saat grafik ditampilkan) --}}
                <button id="exportChartsBtn" onclick="exportChartsToExcel()" style="display:none;"
                    class="inline-flex items-center justify-center gap-2 bg-violet-600 hover:bg-violet-700 text-white px-4 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm hover:shadow">
                    <i class="bi bi-image-fill text-sm"></i>
                    Export Grafik
                </button>

                <a href="{{ route('admin.rekap.export', request()->query()) }}"
                    class="inline-flex items-center justify-center gap-2 bg-[#107c41] hover:bg-[#0b592e] text-white px-4 py-2.5 rounded-xl text-xs font-semibold transition shadow-sm hover:shadow">
                    <i class="bi bi-file-earmark-excel-fill text-sm"></i>
                    Ekspor ke Excel
                </a>
            </div>
        </div>

        {{-- ================= FILTER BAR ================= --}}
        <div class="flex flex-col gap-4.5 px-6 py-5">

            {{-- FILTER KATEGORI (TABS STYLE) --}}
            @php
                $currentCategory = request('category', 'all');
                $tabs = [
                    ['all', 'Semua', 'bi-grid-fill', 'text-slate-400'],
                    ['ratifikasi', 'Ratifikasi', 'bi-patch-check-fill', 'text-emerald-500'],
                    ['pedoman', 'Pedoman', 'bi-journal-text-fill', 'text-blue-500'],
                    ['prosedur', 'Prosedur', 'bi-diagram-3-fill', 'text-purple-500'],
                    ['instruksikerja', 'Instruksi Kerja', 'bi-gear-fill', 'text-amber-500'],
                    ['formulir', 'Formulir', 'bi-file-earmark-spreadsheet-fill', 'text-rose-500'],
                ];
            @endphp

            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Kategori Dokumen</span>
                <div class="flex gap-1.5 overflow-x-auto scrollbar-none pb-1">
                    @foreach ($tabs as [$slug, $name, $icon, $colorClass])
                        <a href="{{ route('admin.rekap.index', array_merge(request()->query(), ['category' => $slug])) }}"
                            class="flex items-center gap-2 px-3.5 py-2 border rounded-xl text-xs font-semibold transition-all duration-150 whitespace-nowrap
                            {{ $currentCategory == $slug
                                ? 'bg-blue-50 border-blue-200 text-[#0f3c7a] shadow-sm'
                                : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300' }}">
                            <i class="{{ $icon }} text-xs {{ $currentCategory == $slug ? 'text-[#0f3c7a]' : $colorClass }}"></i>
                            {{ $name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- CUSTOM TOM SELECT STYLES -->
            <style>
                .ts-wrapper.multi .ts-control {
                    border: 1px solid #cbd5e1 !important;
                    border-radius: 0.75rem !important; /* rounded-xl to match our design */
                    padding: 0.55rem 0.75rem !important;
                    font-size: 0.75rem !important;
                    font-family: 'Plus Jakarta Sans', sans-serif !important;
                    background-color: #ffffff !important;
                    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
                    transition: all 0.15s ease-in-out !important;
                    min-height: 38px !important;
                }
                .ts-wrapper.multi.focus .ts-control {
                    border-color: #0f3c7a !important;
                    box-shadow: 0 0 0 3px rgba(15, 60, 122, 0.12) !important;
                }
                .ts-dropdown {
                    border-radius: 0.75rem !important;
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
                }
                .ts-control .item .remove {
                    border-left: 1px solid #bfdbfe !important;
                    margin-left: 4px !important;
                    padding-left: 6px !important;
                    color: #ef4444 !important;
                    opacity: 0.8;
                }
                .ts-control .item .remove:hover {
                    background-color: #fee2e2 !important;
                    opacity: 1;
                }
            </style>

            <!-- Horizontal divider -->
            <div class="border-t border-slate-100"></div>

            {{-- FILTER SELECTS AND SEARCH --}}
            <form method="GET" action="{{ route('admin.rekap.index') }}" class="space-y-4" id="filter-form">

                <input type="hidden" name="category" value="{{ request('category', 'all') }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
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

                    <!-- Status File Dropdown -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Status Berkas File</label>
                        <select name="file_status" class="text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 w-full bg-white text-slate-800 shadow-sm h-[38px] px-3 transition duration-150 cursor-pointer">
                            <option value="all" {{ request('file_status', 'all') == 'all' ? 'selected' : '' }}>Semua Berkas</option>
                            <option value="lengkap" {{ request('file_status') == 'lengkap' ? 'selected' : '' }}>Lengkap (Ada File PDF)</option>
                            <option value="belum_upload" {{ request('file_status') == 'belum_upload' ? 'selected' : '' }}>Belum Diunggah (Kosong)</option>
                        </select>
                    </div>

                    <!-- Rentang Tanggal -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Rentang Tanggal Dokumen</label>
                        <div class="flex items-center gap-2">
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 w-full bg-white text-slate-800 shadow-sm h-[38px] px-2.5 transition duration-150">
                            <span class="text-slate-400 text-xs">s/d</span>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 w-full bg-white text-slate-800 shadow-sm h-[38px] px-2.5 transition duration-150">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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
                </div>

                <!-- Action Row -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-3 border-t border-slate-100">
                    <div class="text-[11px] text-slate-400 font-medium flex items-center gap-1.5">
                        @php
                            $activeDepts = count(array_filter((array) request('department', [])));
                            $activeCodes = count(array_filter((array) request('code', [])));
                            $hasSearch = request('search') ? 1 : 0;
                            $hasDates = (request('start_date') || request('end_date')) ? 1 : 0;
                            $hasStatus = (request('file_status') && request('file_status') !== 'all') ? 1 : 0;
                        @endphp
                        @if ($activeDepts || $activeCodes || $hasSearch || $hasDates || $hasStatus)
                            <span class="inline-flex flex-wrap items-center gap-1.5 bg-indigo-50/65 text-indigo-700 border border-indigo-100/50 px-2.5 py-1 rounded-lg">
                                <i class="bi bi-info-circle-fill text-[11px] text-indigo-500"></i>
                                Filter Aktif: 
                                @if($activeDepts) <b>{{ $activeDepts }} Unit Kerja</b> @endif
                                @if($activeCodes) &bull; <b>{{ $activeCodes }} Kode</b> @endif
                                @if($hasSearch) &bull; <b>Cari: "{{ request('search') }}"</b> @endif
                                @if($hasDates) &bull; <b>Rentang Tanggal</b> @endif
                                @if($hasStatus) &bull; <b>Status: {{ request('file_status') == 'lengkap' ? 'Lengkap' : 'Belum Upload' }}</b> @endif
                            </span>
                        @else
                            <span class="text-slate-400 flex items-center gap-1">
                                <i class="bi bi-funnel text-xs"></i>
                                Menampilkan semua dokumen
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 self-end sm:self-auto">
                        @if ($activeDepts || $activeCodes || $hasSearch || $hasDates || $hasStatus)
                            <a href="{{ route('admin.rekap.index', ['category' => request('category', 'all')]) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 text-xs font-semibold rounded-lg transition duration-150">
                                <i class="bi bi-arrow-counterclockwise text-xs"></i>
                                Reset Filter
                            </a>
                        @endif
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-xs font-semibold px-4 py-2 rounded-lg shadow-sm transition-all duration-150 hover:shadow hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                            <i class="bi bi-funnel-fill text-xs"></i>
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>

        </div>

    </div>


    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-4 w-12 text-center">No</th>
                        <th class="px-5 py-4">Nomor</th>
                        <th class="px-5 py-4">Nama Dokumen</th>
                        <th class="px-5 py-4 text-center">Rev</th>
                        <th class="px-5 py-4">Unit Kerja</th>
                        <th class="px-5 py-4">Keterangan</th>
                        <th class="px-5 py-4 w-32">Tanggal</th>
                        <th class="px-5 py-4 text-center">Status</th>
                        <th class="px-5 py-4 text-center w-40">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($documents as $doc)
                        {{-- ROW DOKUMEN UTAMA --}}
                        <tr class="hover:bg-slate-50/40 transition group">
                            <td class="px-5 py-4 text-center text-slate-400 font-medium">{{ $loop->iteration }}</td>

                            <td class="px-5 py-4 font-semibold text-slate-800 text-xs">
                                {{ $doc->document_number }}
                            </td>

                            <td class="px-5 py-4 font-bold text-slate-800">
                                {{ $doc->title }}
                            </td>

                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center bg-blue-50 border border-blue-100 px-2 py-0.5 rounded text-[10px] font-bold text-blue-700">
                                    Rev {{ $doc->revision ?? 0 }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-xs font-semibold text-slate-700">
                                {{ $doc->department->name ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-xs text-slate-500 max-w-[200px] truncate" title="{{ $doc->description }}">
                                {{ $doc->description ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($doc->document_date)->format('d-m-Y') }}
                            </td>

                            <td class="px-5 py-4 bg-white border-y border-slate-200/60 first:border-l last:border-r first:rounded-l-xl last:rounded-r-xl shadow-sm group-hover:shadow transition-all duration-150">
                                <div class="flex flex-col items-center gap-1">
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

                                    <!-- Uploader / Reviser Metadata -->
                                    <div class="text-[9px] text-slate-400 font-semibold flex flex-col items-center leading-normal">
                                        @if($doc->uploader)
                                            <span class="truncate max-w-[100px]" title="Diunggah oleh: {{ $doc->uploader->name }}">
                                                <i class="bi bi-person-fill text-slate-350"></i> {{ $doc->uploader->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-300">-</span>
                                        @endif
                                        
                                        @if($doc->updater && $doc->updater_id !== $doc->user_id)
                                            <span class="text-indigo-500/80 truncate max-w-[100px] mt-0.5" title="Direvisi oleh: {{ $doc->updater->name }}">
                                                <i class="bi bi-pencil-fill text-[8px] text-indigo-400"></i> {{ $doc->updater->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if ($doc->file_document)
                                        <a href="{{ route('admin.documents.preview', $doc->id) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 border border-blue-100/50 hover:bg-blue-100 hover:text-blue-750 transition shadow-sm"
                                            title="Lihat Preview">
                                            <i class="bi bi-eye text-sm"></i>
                                        </a>
                                    @else
                                        <span class="w-8 h-8 flex items-center justify-center text-slate-300 text-xs border border-dashed border-slate-200 rounded-lg">-</span>
                                    @endif

                                    <button onclick="toggleDetail({{ $doc->id }})"
                                        class="inline-flex items-center gap-1 text-[11px] font-bold bg-blue-50/70 text-[#0f3c7a] border border-blue-100/60 hover:bg-blue-100 hover:text-[#0a2d5c] px-2.5 py-1.5 rounded-lg transition duration-150 shadow-sm">
                                        <span>Detail</span>
                                        <i class="bi bi-chevron-down text-[9px]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>


                        {{-- 🔥 SUB JUDUL & TIMELINE AUDIT DRAWER --}}
                        <tr id="detail-{{ $doc->id }}" class="hidden">
                            <td colspan="9" class="px-5 py-3 bg-slate-50/20">
                                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm my-1.5">
                                    
                                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                        
                                        <!-- Column 1 & 2: Sub-judul Table (If any) -->
                                        <div class="lg:col-span-2">
                                            @if ($doc->details && $doc->details->count() > 0)
                                                <div class="flex justify-between items-center mb-3 pb-2 border-b border-slate-100">
                                                    <h4 class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                                        <i class="bi bi-diagram-2 text-[#0f3c7a]"></i>
                                                        Daftar Sub Judul / Uraian Detail
                                                    </h4>
                                                    <span class="text-[10px] text-slate-400 font-semibold bg-slate-100 px-2 py-0.5 rounded">
                                                        {{ $doc->details->count() }} Item
                                                    </span>
                                                </div>

                                                <div class="overflow-hidden border border-slate-200/60 rounded-lg">
                                                    <table class="w-full text-xs text-left border-collapse bg-white">
                                                        <thead class="bg-slate-50 text-slate-550 font-bold border-b border-slate-200">
                                                            <tr>
                                                                <th class="px-4 py-2 text-center w-12">No</th>
                                                                <th class="px-4 py-2">Sub Judul / Judul Uraian</th>
                                                                <th class="px-4 py-2">Unit Kerja Terkait</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-slate-100">
                                                            @foreach ($doc->details as $index => $d)
                                                                <tr class="hover:bg-slate-50/50 transition">
                                                                    <td class="px-4 py-2 text-center text-slate-400 font-medium">{{ $index + 1 }}</td>
                                                                    <td class="px-4 py-2 font-semibold text-slate-800">{{ $d->sub_title }}</td>
                                                                    <td class="px-4 py-2">
                                                                        @php
                                                                            $deptNames = [];
                                                                            if (!empty($d->department_ids)) {
                                                                                $deptIds = is_array($d->department_ids)
                                                                                    ? $d->department_ids
                                                                                    : json_decode($d->department_ids, true);
                                                                                $deptNames = \App\Models\Department::whereIn('id', $deptIds)->pluck('name')->toArray();
                                                                            }
                                                                        @endphp
                                                                        @if (!empty($deptNames))
                                                                            <div class="flex flex-wrap gap-1">
                                                                                @foreach ($deptNames as $name)
                                                                                    <span class="inline-block bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-[10px] font-medium border border-slate-250/40">
                                                                                        {{ $name }}
                                                                                    </span>
                                                                                @endforeach
                                                                            </div>
                                                                        @else
                                                                            <span class="text-slate-300">-</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="flex flex-col items-center justify-center h-full min-h-[120px] text-slate-450 border border-dashed border-slate-200 rounded-xl p-4">
                                                    <i class="bi bi-inbox text-xl text-slate-350 mb-1"></i>
                                                    <p class="text-[11px] font-semibold">Tidak ada sub-judul untuk dokumen ini</p>
                                                    <p class="text-[9px] text-slate-400 mt-0.5">Khusus kategori Ratifikasi dapat memuat detail sub-judul terkait.</p>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Column 3: Timeline Audit Dokumen -->
                                        <div class="bg-slate-50/60 rounded-xl p-4 border border-slate-200/50 flex flex-col justify-between">
                                            <div>
                                                <h4 class="text-xs font-bold text-slate-700 flex items-center gap-1.5 mb-4 pb-2 border-b border-slate-200">
                                                    <i class="bi bi-clock-history text-[#0f3c7a]"></i>
                                                    Riwayat Audit Dokumen
                                                </h4>

                                                <!-- Timeline Graphic -->
                                                <div class="relative pl-6 space-y-5 border-l-2 border-slate-200 ml-2 mt-1">
                                                    
                                                    <!-- Point 1: Upload Awal -->
                                                    <div class="relative">
                                                        <!-- Timeline Node Indicator -->
                                                        <span class="absolute -left-[31px] top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-emerald-100 border border-emerald-500 text-[8px] text-emerald-700 font-bold shadow-sm">
                                                            1
                                                        </span>
                                                        <p class="text-[11px] font-bold text-slate-800 leading-none">Unggahan Pertama (Perdana)</p>
                                                        <p class="text-[9px] text-slate-455 font-medium mt-1">
                                                            {{ \Carbon\Carbon::parse($doc->created_at)->format('d-m-Y H:i') }} WIB
                                                        </p>
                                                        <div class="mt-1.5 flex items-center gap-1.5">
                                                            <span class="text-[10px] bg-white border border-slate-200 px-2 py-0.5 rounded font-semibold text-slate-700 shadow-sm">
                                                                <i class="bi bi-person-fill text-slate-400 mr-0.5"></i>
                                                                {{ $doc->uploader->name ?? 'Sistem' }}
                                                            </span>
                                                            <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100 uppercase tracking-wider">
                                                                REV 0
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <!-- Point 2: Update / Revisi Terakhir -->
                                                    <div class="relative">
                                                        <!-- Timeline Node Indicator -->
                                                        @php
                                                            $isRevised = ($doc->revision > 0 || ($doc->updater_id && $doc->updater_id !== $doc->user_id));
                                                        @endphp
                                                        <span class="absolute -left-[31px] top-0.5 flex h-4 w-4 items-center justify-center rounded-full 
                                                            {{ $isRevised ? 'bg-indigo-100 border border-indigo-500 text-indigo-705' : 'bg-slate-100 border border-slate-300 text-slate-400' }} 
                                                            text-[8px] font-bold shadow-sm">
                                                            2
                                                        </span>
                                                        <p class="text-[11px] font-bold {{ $isRevised ? 'text-slate-800' : 'text-slate-400' }} leading-none">Revisi / Pembaruan Terakhir</p>
                                                        <p class="text-[9px] text-slate-455 font-medium mt-1">
                                                            {{ $isRevised ? \Carbon\Carbon::parse($doc->updated_at)->format('d-m-Y H:i') . ' WIB' : '-' }}
                                                        </p>
                                                        <div class="mt-1.5 flex items-center gap-1.5">
                                                            @if($isRevised)
                                                                <span class="text-[10px] bg-white border border-slate-200 px-2 py-0.5 rounded font-semibold text-slate-700 shadow-sm">
                                                                    <i class="bi bi-person-fill text-slate-400 mr-0.5"></i>
                                                                    {{ $doc->updater->name ?? ($doc->uploader->name ?? 'Sistem') }}
                                                                </span>
                                                                <span class="text-[9px] font-bold text-indigo-650 bg-indigo-50 px-1.5 py-0.5 rounded border border-indigo-100 uppercase tracking-wider">
                                                                    REV {{ $doc->revision }}
                                                                </span>
                                                            @else
                                                                <span class="text-[10px] text-slate-400 italic">Belum ada revisi terbaru</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="mt-4 pt-3 border-t border-slate-150 text-[10px] text-slate-400 font-medium leading-normal">
                                                <div class="flex items-center justify-between">
                                                    <span>Dibuat:</span>
                                                    <span class="text-slate-500 font-semibold">{{ \Carbon\Carbon::parse($doc->created_at)->format('d M Y') }}</span>
                                                </div>
                                                <div class="flex items-center justify-between mt-1">
                                                    <span>Diubah:</span>
                                                    <span class="text-slate-500 font-semibold">{{ \Carbon\Carbon::parse($doc->updated_at)->format('d M Y') }}</span>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-16 text-slate-400 font-medium">
                                <i class="bi bi-file-earmark-text text-2xl block mb-2 text-slate-300"></i>
                                Tidak ada dokumen ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

    <!-- Script ApexCharts + SheetJS -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script>
        function toggleDetail(id) {
            let el = document.getElementById('detail-' + id);
            if (el) {
                el.classList.toggle('hidden');
            }
        }

        let chartsInitialized = false;

        function toggleRekapCharts() {
            let panel = document.getElementById('rekapChartsPanel');
            let btnText = document.getElementById('chartsBtnText');
            let exportBtn = document.getElementById('exportChartsBtn');
            if (panel) {
                if (panel.classList.contains('hidden')) {
                    panel.classList.remove('hidden');
                    btnText.innerText = "Sembunyikan Grafik";
                    if (exportBtn) exportBtn.style.display = 'inline-flex';
                    
                    if (!chartsInitialized) {
                        initRekapCharts();
                        chartsInitialized = true;
                    }
                } else {
                    panel.classList.add('hidden');
                    btnText.innerText = "Tampilkan Grafik";
                    if (exportBtn) exportBtn.style.display = 'none';
                }
            }
        }

        function initRekapCharts() {
            // 1. Kepatuhan Upload (Radial Bar)
            const compOptions = {
                chart: {
                    type: 'radialBar',
                    height: 220,
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                series: [{{ $compliancePercent }}],
                colors: ['#0f3c7a'],
                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        hollow: { size: '60%' },
                        track: { background: '#f1f5f9', strokeWidth: '97%' },
                        dataLabels: {
                            name: { show: true, color: '#64748b', fontSize: '9px', fontWeight: 600, label: 'Kepatuhan', offsetY: -8 },
                            value: { show: true, color: '#0f3c7a', fontSize: '18px', fontWeight: 700, offsetY: 4, formatter: function (val) { return val + "%"; } }
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark', type: 'horizontal', shadeIntensity: 0.5,
                        gradientToColors: ['#3b82f6'], inverseColors: true, opacityFrom: 1, opacityTo: 1, stops: [0, 100]
                    }
                },
                stroke: { lineCap: 'round' },
                labels: ['Kepatuhan']
            };
            const complianceChart = new ApexCharts(document.querySelector("#rekapComplianceChart"), compOptions);
            complianceChart.render();

            // 2. Category Doughnut Chart
            const catOptions = {
                chart: {
                    type: 'donut',
                    height: 220,
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                series: [
                    {{ $chartRatifikasi }}, 
                    {{ $chartPedoman }}, 
                    {{ $chartProsedur }}, 
                    {{ $chartInstruksi }}, 
                    {{ $chartFormulir }}
                ],
                labels: ['Ratifikasi', 'Pedoman', 'Prosedur', 'Instruksi Kerja', 'Formulir'],
                colors: ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#f43f5e'],
                legend: {
                    position: 'bottom',
                    fontSize: '9px',
                    fontWeight: 500,
                    labels: { colors: '#64748b' }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    fontSize: '10px',
                                    fontWeight: 750,
                                    color: '#64748b',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: { enabled: false }
            };
            const categoryChart = new ApexCharts(document.querySelector("#rekapCategoryChart"), catOptions);
            categoryChart.render();

            // 3. Department Bar Chart
            const deptOptions = {
                chart: {
                    type: 'bar',
                    height: 220,
                    toolbar: { show: false },
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: true,
                        barHeight: '55%',
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
                        style: { fontSize: '8px', fontWeight: 500, colors: '#64748b' }
                    }
                },
                yaxis: {
                    labels: {
                        style: { fontSize: '8px', fontWeight: 600, colors: '#334155' }
                    }
                },
                grid: { borderColor: '#f1f5f9' },
                legend: { show: false },
                dataLabels: { enabled: false }
            };
            const departmentChart = new ApexCharts(document.querySelector("#rekapDepartmentChart"), deptOptions);
            departmentChart.render();

            // 4. Trend Chart
            const trendOptions = {
                chart: {
                    type: 'area',
                    height: 220,
                    toolbar: { show: false },
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                stroke: { curve: 'smooth', width: 2 },
                colors: ['#0f3c7a'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.02, stops: [0, 90, 100]
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
                        style: { fontSize: '8px', fontWeight: 500, colors: '#64748b' }
                    }
                },
                grid: { borderColor: '#f1f5f9' },
                dataLabels: { enabled: false }
            };
            const trendChart = new ApexCharts(document.querySelector("#rekapTrendChart"), trendOptions);
            trendChart.render();

            // 5. Status Kelengkapan Berkas (Donut - SARAN BARU)
            const statusOptions = {
                chart: {
                    type: 'donut',
                    height: 220,
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                series: [
                    {{ $uploadedCount }},
                    {{ $totalDocsCount - $uploadedCount }}
                ],
                labels: ['Lengkap (Ada File)', 'Belum Upload (Kosong)'],
                colors: ['#10b981', '#f43f5e'],
                legend: {
                    position: 'bottom',
                    fontSize: '9px',
                    fontWeight: 500,
                    labels: { colors: '#64748b' }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Rasio',
                                    fontSize: '10px',
                                    fontWeight: 750,
                                    color: '#64748b',
                                    formatter: function (w) {
                                        let series = w.globals.series;
                                        let total = series[0] + series[1];
                                        return Math.round((series[0] / Math.max(total, 1)) * 100) + "%";
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: { enabled: false }
            };
            const statusChart = new ApexCharts(document.querySelector("#rekapStatusChart"), statusOptions);
            statusChart.render();

            // Store chart instances globally for export
            window._rekapCharts = [
                { title: 'Kepatuhan Upload',         instance: complianceChart },
                { title: 'Pembagian Kategori',       instance: categoryChart },
                { title: 'Rasio Kelengkapan Berkas', instance: statusChart },
                { title: 'Top Unit Kerja Teraktif',  instance: departmentChart },
                { title: 'Tren Dokumen per Tahun',   instance: trendChart },
            ];
        }

        // === Export semua grafik ke Excel (satu sheet per grafik) ===
        async function exportChartsToExcel() {
            if (!window._rekapCharts || window._rekapCharts.length === 0) {
                alert('Grafik belum diinisialisasi. Tampilkan grafik terlebih dahulu.');
                return;
            }

            const btn = document.getElementById('exportChartsBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';

            try {
                const wb = XLSX.utils.book_new();

                for (let i = 0; i < window._rekapCharts.length; i++) {
                    const { title, instance } = window._rekapCharts[i];

                    // Get chart as base64 PNG via ApexCharts built-in export
                    const { imgURI } = await instance.dataURI({ scale: 2 });

                    // Strip data:image/png;base64, prefix
                    const base64 = imgURI.replace(/^data:image\/png;base64,/, '');

                    // Create a worksheet with the chart title as header
                    const ws = XLSX.utils.aoa_to_sheet([[title], ['Grafik tersedia sebagai gambar di bawah']]);

                    // Set column width
                    ws['!cols'] = [{ wch: 60 }];

                    // Add image to worksheet
                    if (!wb.Sheets) wb.Sheets = {};
                    XLSX.utils.book_append_sheet(wb, ws, title.substring(0, 31));

                    // Embed image using xl/drawings approach via addImage
                    // Since SheetJS CE doesn't support image embedding,
                    // we create an HTML file with images as fallback
                }

                // === Fallback: Export as HTML with embedded base64 images ===
                // This opens a printable HTML page with all charts
                let htmlContent = `<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Grafik Rekapitulasi Dokumen</title>
<style>
  body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; margin: 0; padding: 20px; }
  .header { background: linear-gradient(135deg, #0f3c7a, #1e5faa); color: white; padding: 28px 32px; border-radius: 16px; margin-bottom: 24px; }
  .header h1 { margin: 0; font-size: 22px; font-weight: 700; }
  .header p { margin: 4px 0 0; font-size: 12px; opacity: 0.8; }
  .chart-card { background: white; border-radius: 16px; border: 1px solid #e2e8f0; padding: 20px 24px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
  .chart-card h2 { font-size: 13px; font-weight: 700; color: #0f3c7a; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 16px; padding-bottom: 10px; border-bottom: 2px solid #e0ebf6; }
  .chart-card img { width: 100%; max-width: 900px; display: block; margin: 0 auto; }
  .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
  .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
  .footer { text-align: center; color: #94a3b8; font-size: 11px; margin-top: 32px; }
  @media print { body { background: white; } @page { size: A4 landscape; margin: 15mm; } }
</style>
</head>
<body>
<div class="header">
  <h1>📊 Laporan Grafik Rekapitulasi Dokumen</h1>
  <p>Diekspor pada: ${new Date().toLocaleString('id-ID')} &nbsp;|&nbsp; SISMEN Document System</p>
</div>`;

                // Row 1: 3 charts
                htmlContent += '<div class="grid-3">';
                for (let i = 0; i < 3; i++) {
                    const { title, instance } = window._rekapCharts[i];
                    const { imgURI } = await instance.dataURI({ scale: 2 });
                    htmlContent += `<div class="chart-card"><h2>${title}</h2><img src="${imgURI}" alt="${title}"></div>`;
                }
                htmlContent += '</div>';

                // Row 2: 2 charts
                htmlContent += '<div class="grid-2">';
                for (let i = 3; i < 5; i++) {
                    const { title, instance } = window._rekapCharts[i];
                    const { imgURI } = await instance.dataURI({ scale: 2 });
                    htmlContent += `<div class="chart-card"><h2>${title}</h2><img src="${imgURI}" alt="${title}"></div>`;
                }
                htmlContent += '</div>';

                htmlContent += '<div class="footer">SISMEN &mdash; Sistem Manajemen Dokumen Internal</div></body></html>';

                // Open in new window for print/save
                const win = window.open('', '_blank', 'width=1100,height=800');
                win.document.write(htmlContent);
                win.document.close();
                setTimeout(() => win.print(), 800);

            } catch (err) {
                console.error('Export error:', err);
                alert('Terjadi kesalahan saat mengekspor grafik: ' + err.message);
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-image-fill"></i> Export Grafik';
            }
        }

        // TomSelect initialization
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
@endsection
