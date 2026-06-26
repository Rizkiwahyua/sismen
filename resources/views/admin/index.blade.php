@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

{{-- ================= SUMMARY CARDS ================= --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-8 gap-4 mb-8">

    @php
        $cards = [
            ['Semua Dokumen', $totalDokumen, 'bi-files', 'bg-blue-50 text-blue-600 border-blue-100'],
            ['Ratifikasi', $totalRatifikasi, 'bi-patch-check-fill', 'bg-emerald-50 text-emerald-600 border-emerald-100'],
            ['Pedoman', $totalPedoman, 'bi-journal-text', 'bg-sky-50 text-sky-600 border-sky-100'],
            ['Prosedur', $totalProsedur, 'bi-diagram-3-fill', 'bg-violet-50 text-violet-600 border-violet-100'],
            ['Instruksi', $totalInstruksi, 'bi-gear-fill', 'bg-amber-50 text-amber-600 border-amber-100'],
            ['Formulir', $totalFormulir, 'bi-file-earmark-spreadsheet-fill', 'bg-rose-50 text-rose-600 border-rose-100'],
            ['Departemen', $totalDepartemen, 'bi-building-fill', 'bg-cyan-50 text-cyan-600 border-cyan-100'],
            ['Users', $totalUsers, 'bi-people-fill', 'bg-slate-50 text-slate-600 border-slate-100'],
        ];
    @endphp

    @foreach ($cards as [$title, $value, $icon, $styleClass])
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 hover:shadow-md hover:border-slate-300 transition duration-200 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 truncate pr-1" title="{{ $title }}">{{ $title }}</span>
                <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ $styleClass }} border shadow-sm">
                    <i class="bi {{ $icon }} text-xs"></i>
                </div>
            </div>
            <div class="text-xl font-bold text-slate-800 tracking-tight">
                {{ $value }}
            </div>
        </div>
    @endforeach

</div>


    {{-- ================= CHARTS SECTION ================= --}}
    @php
        // 1. Hitung top unit kerja aktif
        $deptCounts = [];
        foreach ($documents as $doc) {
            $deptName = $doc->department->name ?? 'Lainnya';
            $deptCounts[$deptName] = ($deptCounts[$deptName] ?? 0) + 1;
        }
        arsort($deptCounts);
        $topDepts = array_slice($deptCounts, 0, 5, true);

        // 2. Hitung tren dokumen per tahun
        $yearCounts = [];
        foreach ($documents as $doc) {
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
        $totalDocs = max($totalDokumen, 1);
        $uploadedCount = $documents->filter(fn($d) => !empty($d->file_document))->count();
        $compliancePercent = round(($uploadedCount / $totalDocs) * 100);
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <!-- 1. compliance chart -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">Kepatuhan Upload</h3>
            <div id="complianceChart" class="h-64 flex items-center justify-center"></div>
        </div>

        <!-- 2. doughnut chart -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">Pembagian Kategori</h3>
            <div id="categoryChart" class="h-64 flex items-center justify-center"></div>
        </div>

        <!-- 3. bar chart -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">Top Unit Kerja Aktif</h3>
            <div id="departmentChart" class="h-64"></div>
        </div>

        <!-- 4. line chart -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">Tren Dokumen per Tahun</h3>
            <div id="trendChart" class="h-64"></div>
        </div>
    </div>

    <!-- Script ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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

            // 2. Category Chart (Doughnut)
            const catOptions = {
                chart: {
                    type: 'donut',
                    height: 240,
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                series: [
                    {{ (int)$totalRatifikasi }},
                    {{ (int)$totalPedoman }},
                    {{ (int)$totalProsedur }},
                    {{ (int)$totalInstruksi }},
                    {{ (int)$totalFormulir }}
                ],
                labels: ['Ratifikasi', 'Pedoman', 'Prosedur', 'Instruksi', 'Formulir'],
                colors: ['#10b981', '#0ea5e9', '#8b5cf6', '#f59e0b', '#f43f5e'],
                legend: {
                    position: 'bottom',
                    fontSize: '10px',
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
                                    fontSize: '11px',
                                    fontWeight: 700,
                                    color: '#475569',
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

            // 3. Department Chart (Horizontal Bar)
            const deptOptions = {
                chart: {
                    type: 'bar',
                    height: 240,
                    toolbar: { show: false },
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        barHeight: '50%',
                        borderRadius: 4,
                        distributed: true
                    }
                },
                colors: ['#0f3c7a', '#14b8a6', '#0ea5e9', '#6366f1', '#a855f7'],
                series: [{
                    name: 'Dokumen',
                    data: [
                        @foreach($topDepts as $name => $count)
                            {{ $count }},
                        @endforeach
                    ]
                }],
                xaxis: {
                    categories: [
                        @foreach($topDepts as $name => $count)
                            '{!! addslashes($name) !!}',
                        @endforeach
                    ],
                    labels: {
                        style: { fontSize: '9px', colors: '#64748b' }
                    }
                },
                yaxis: {
                    labels: {
                        style: { fontSize: '9px', fontWeight: 600, colors: '#64748b' }
                    }
                },
                legend: { show: false },
                dataLabels: {
                    enabled: true,
                    textAnchor: 'start',
                    style: { colors: ['#fff'], fontSize: '9px', fontWeight: 700 },
                    formatter: function(val) { return val; },
                    offsetX: 6
                }
            };
            const departmentChart = new ApexCharts(document.querySelector("#departmentChart"), deptOptions);
            departmentChart.render();

            // 4. Trend Chart (Line Area)
            const trendOptions = {
                chart: {
                    type: 'area',
                    height: 240,
                    toolbar: { show: false },
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.3,
                        opacityTo: 0.02,
                        stops: [0, 90, 100]
                    }
                },
                colors: ['#0f3c7a'],
                series: [{
                    name: 'Dokumen Baru',
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
                        style: { fontSize: '9px', colors: '#64748b' }
                    }
                },
                yaxis: {
                    labels: {
                        style: { fontSize: '9px', colors: '#64748b' }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                grid: {
                    borderColor: '#f8fafc'
                }
            };
            const trendChart = new ApexCharts(document.querySelector("#trendChart"), trendOptions);
            trendChart.render();
        });
    </script>


    @php
        $currentCategory = request('category', 'all');
    @endphp

    <div class="mt-12 mb-6">
        <h2 class="text-xl font-bold text-slate-800 tracking-tight">Daftar Dokumen</h2>
        <p class="text-xs text-slate-400 mt-0.5">Pantau dan cari seluruh dokumen sistem secara terpusat</p>
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
                <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['category' => $slug])) }}"
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

    <!-- CUSTOM TOM SELECT STYLES -->
    <style>
        .ts-wrapper.multi .ts-control {
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.5rem !important;
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
        <form method="GET" action="{{ route('admin.dashboard') }}" class="space-y-4" id="filter-form">
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
                        <a href="{{ route('admin.dashboard', ['category' => $currentCategory]) }}"
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
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 bg-white">
                                                @foreach ($doc->details as $index => $d)
                                                    <tr class="hover:bg-slate-50/50 transition">
                                                        <td class="px-4 py-2.5 text-center text-slate-400 font-medium">{{ $index + 1 }}</td>
                                                        
                                                        <!-- SUB JUDUL -->
                                                        <td class="px-4 py-2.5">
                                                            <div class="font-medium text-slate-700">
                                                                {{ $d->sub_title }}
                                                            </div>
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
        function toggleDetail(id) {
            let el = document.getElementById('detail-' + id);
            el.classList.toggle('hidden');
        }
    </script>
@endsection