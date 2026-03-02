@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

    <!-- ================= CARDS ================= -->
    <div class="grid grid-cols-4 md:grid-cols-4 gap-4 mb-6">

    <!-- Semua Dokumen -->
    <div class="rounded-xl px-4 py-3 flex items-center justify-between bg-indigo-50 text-indigo-700 shadow-sm">
        <span class="text-sm font-medium">Semua Dokumen</span>
        <span class="text-xl font-bold">{{ $totalDokumen }}</span>
    </div>

    <!-- Ratifikasi -->
    <div class="rounded-xl px-4 py-3 flex items-center justify-between bg-green-50 text-green-700 shadow-sm">
        <span class="text-sm font-medium">Ratifikasi</span>
        <span class="text-xl font-bold">{{ $totalRatifikasi }}</span>
    </div>

    <!-- Pedoman -->
    <div class="rounded-xl px-4 py-3 flex items-center justify-between bg-blue-50 text-blue-700 shadow-sm">
        <span class="text-sm font-medium">Pedoman</span>
        <span class="text-xl font-bold">{{ $totalPedoman }}</span>
    </div>

    <!-- Prosedur -->
    <div class="rounded-xl px-4 py-3 flex items-center justify-between bg-purple-50 text-purple-700 shadow-sm">
        <span class="text-sm font-medium">Prosedur</span>
        <span class="text-xl font-bold">{{ $totalProsedur }}</span>
    </div>

    <!-- Instruksi -->
    <div class="rounded-xl px-4 py-3 flex items-center justify-between bg-amber-50 text-amber-700 shadow-sm">
        <span class="text-sm font-medium">Instruksi</span>
        <span class="text-xl font-bold">{{ $totalInstruksi }}</span>
    </div>

    <!-- Formulir -->
    <div class="rounded-xl px-4 py-3 flex items-center justify-between bg-rose-50 text-rose-700 shadow-sm">
        <span class="text-sm font-medium">Formulir</span>
        <span class="text-xl font-bold">{{ $totalFormulir }}</span>
    </div>

    <!-- Departemen -->
    <div class="rounded-xl px-4 py-3 flex items-center justify-between bg-indigo-50 text-indigo-700 shadow-sm">
        <span class="text-sm font-medium">Departemen</span>
        <span class="text-xl font-bold">{{ $totalDepartemen }}</span>
    </div>

    <!-- Users -->
    <div class="rounded-xl px-4 py-3 flex items-center justify-between bg-indigo-50 text-indigo-700 shadow-sm">
        <span class="text-sm font-medium">Users</span>
        <span class="text-xl font-bold">{{ $totalUsers }}</span>
    </div>

</div>
    <!-- ================= SEARCH SECTION (SOFT) ================= -->
    <div class="bg-white rounded-2xl shadow p-4 mb-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <!-- Title kecil -->
            <h2 class="text-lg font-semibold text-gray-700">
                🔍 Pencarian Dokumen
            </h2>

            <!-- Search bar kecil -->
            <!-- Search bar kecil (lebih jelas & soft modern) -->
            <div
                class="flex w-full md:w-96
            bg-white
            border border-gray-300
            rounded-xl
            overflow-hidden
            shadow-sm">

                <input type="text" placeholder="Cari dokumen..."
                    class="w-full px-4 py-2 text-sm text-gray-700
               focus:outline-none focus:ring-2 focus:ring-indigo-200">

                <button class="px-4 bg-indigo-500 hover:bg-indigo-600
               text-white text-sm transition">
                    Cari
                </button>

            </div>


        </div>

    </div>


    <!-- ================= HEADER BAR ================= -->
    <div class="bg-indigo-600 text-white px-6 py-3 rounded-t-xl">
        <h2 class="font-semibold text-lg">Daftar Dokumen</h2>
    </div>


    <!-- ================= TABLE CONTAINER ================= -->
    <div class="bg-white rounded-b-xl shadow border border-gray-100 p-5">

        <!-- ================= FILTER KATEGORI ================= -->
        <div class="flex gap-4 text-sm text-gray-500 border-b pb-3 mb-4">

            <button class="filter-btn active" data-filter="all">
                <i class="bi bi-grid-fill"></i>
                <span>Semua</span>
            </button>

            <button class="filter-btn" data-filter="ratifikasi">
                <i class="bi bi-patch-check-fill"></i>
                <span>Ratifikasi</span>
            </button>

            <button class="filter-btn" data-filter="pedoman">
                <i class="bi bi-journal-bookmark-fill"></i>
                <span>Pedoman</span>
            </button>

            <button class="filter-btn" data-filter="prosedur">
                <i class="bi bi-diagram-3-fill"></i>
                <span>Prosedur</span>
            </button>

            <button class="filter-btn" data-filter="instruksi">
                <i class="bi bi-gear-fill"></i>
                <span>Instruksi Kerja</span>
            </button>

            <button class="filter-btn" data-filter="formulir">
                <i class="bi bi-ui-checks-grid"></i>
                <span>Formulir</span>
            </button>

        </div>

        <!-- ================= TABLE CONTAINER ================= -->
        <div class="bg-white rounded-b-xl shadow border border-gray-100 p-5">

            <!-- TOP CONTROL -->
            <div class="flex justify-between items-center mb-4">

                <!-- show entries -->
                <div class="text-sm text-gray-600">
                    Show
                    <select class="border border-gray-300 rounded px-2 py-1 text-sm mx-1">
                        <option>0</option>
                        <option>10</option>
                        <option>20</option>
                    </select>
                    entries
                </div>

                <!-- search -->
                <div class="flex border border-gray-300 rounded-lg overflow-hidden">
                    <input type="text" placeholder="Search..." class="px-3 py-1 text-sm focus:outline-none">
                    <button class="px-3 bg-indigo-500 text-white text-sm">
                        Cari
                    </button>
                </div>

            </div>

            <!-- ================= TABLE ================= -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">

                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Action</th>
                            <th class="px-4 py-3">Nomor</th>
                            <th class="px-4 py-3">Nama Dokumen</th>
                            <th class="px-4 py-3">Revisi</th>
                            <th class="px-4 py-3">Unit Kerja</th>
                            <th class="px-4 py-3">Uploader</th>
                            <th class="px-4 py-3">Tanggal</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        <!-- PEDOMAN -->
                        <tr class="doc-row hover:bg-gray-50" data-category="pedoman">
                            <td class="px-4 py-3 flex gap-2">
                                <button class="bg-green-500 text-white px-2 py-1 rounded text-xs">⬇</button>
                                <button class="bg-yellow-500 text-white px-2 py-1 rounded text-xs">✏</button>
                                <button class="bg-red-500 text-white px-2 py-1 rounded text-xs">🗑</button>
                            </td>
                            <td class="px-4 py-3">SKD/50/III/2019</td>
                            <td class="px-4 py-3 font-medium">Pedoman K3LH.pdf</td>
                            <td class="px-4 py-3">0</td>
                            <td class="px-4 py-3">Departemen K3</td>
                            <td class="px-4 py-3">Iwan</td>
                            <td class="px-4 py-3">14-11-2024</td>
                        </tr>

                        <!-- RATIFIKASI -->
                        <tr class="doc-row hover:bg-gray-50" data-category="ratifikasi">
                            <td class="px-4 py-3 flex gap-2">
                                <button class="bg-green-500 text-white px-2 py-1 rounded text-xs">⬇</button>
                                <button class="bg-yellow-500 text-white px-2 py-1 rounded text-xs">✏</button>
                                <button class="bg-red-500 text-white px-2 py-1 rounded text-xs">🗑</button>
                            </td>
                            <td class="px-4 py-3">RTF/21/I/2025</td>
                            <td class="px-4 py-3 font-medium">Ratifikasi ISO.pdf</td>
                            <td class="px-4 py-3">1</td>
                            <td class="px-4 py-3">QA</td>
                            <td class="px-4 py-3">Admin</td>
                            <td class="px-4 py-3">01-01-2025</td>
                        </tr>

                    </tbody>

                </table>
            </div>

        </div>

        <!-- ================= STYLE FILTER ================= -->
        <style>
            .filter-btn {
                padding: 6px 14px;
                border-radius: 10px;
                transition: .2s;
            }

            .filter-btn:hover {
                background: #f3f4f6;
            }

            .filter-btn.active {
                background: #3b81eb;
                color: white;
            }
        </style>

        <!-- ================= SCRIPT FILTER + SEARCH ================= -->
        <script>
            const buttons = document.querySelectorAll('.filter-btn');
            const rows = document.querySelectorAll('.doc-row');
            const searchInput = document.getElementById('searchInput');

            let activeFilter = 'all';

            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    buttons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    activeFilter = btn.dataset.filter;
                    filterTable();
                });
            });

            searchInput.addEventListener('keyup', filterTable);

            function filterTable() {
                const keyword = searchInput.value.toLowerCase();

                rows.forEach(row => {
                    const matchCategory = activeFilter === 'all' || row.dataset.category === activeFilter;
                    const matchSearch = row.innerText.toLowerCase().includes(keyword);

                    row.style.display = (matchCategory && matchSearch) ? '' : 'none';
                });
            }
        </script>

    @endsection
