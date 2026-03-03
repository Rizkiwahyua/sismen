@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

{{-- ================= SUMMARY CARDS ================= --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-8 gap-4 mb-8">

    @php
        $cards = [
            ['Semua Dokumen', $totalDokumen, 'indigo'],
            ['Ratifikasi', $totalRatifikasi, 'green'],
            ['Pedoman', $totalPedoman, 'blue'],
            ['Prosedur', $totalProsedur, 'purple'],
            ['Instruksi', $totalInstruksi, 'amber'],
            ['Formulir', $totalFormulir, 'rose'],
            ['Departemen', $totalDepartemen, 'cyan'],
            ['Users', $totalUsers, 'gray'],
        ];
    @endphp

    @foreach ($cards as [$title, $value, $color])
        <div class="bg-white rounded-2xl shadow-sm border p-4 hover:shadow-md transition">
            <div class="text-xs text-gray-500 mb-1">{{ $title }}</div>
            <div class="text-2xl font-bold text-{{ $color }}-600">
                {{ $value }}
            </div>
        </div>
    @endforeach

</div>


{{-- ================= SEARCH SECTION ================= --}}
<div class="bg-white rounded-2xl shadow-sm border p-5 mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h2 class="text-lg font-semibold text-gray-800">
                Pencarian Dokumen
            </h2>
            <p class="text-xs text-gray-500">
                Temukan dokumen dengan cepat
            </p>
        </div>

        <div class="flex w-full md:w-96 border rounded-xl overflow-hidden">
            <input type="text"
                id="searchInput"
                placeholder="Cari dokumen..."
                class="w-full px-4 py-2 text-sm focus:outline-none">
            <div class="px-4 bg-indigo-600 text-white flex items-center">
                🔍
            </div>
        </div>

    </div>
</div>


{{-- ================= TABLE SECTION ================= --}}
<div class="bg-white rounded-2xl shadow-sm border">

    {{-- Header --}}
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h3 class="font-semibold text-gray-800">Daftar Dokumen</h3>
        <span class="text-sm text-gray-500">
            {{ $documents->count() }} Data
        </span>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-6 py-4 text-left">No</th>
                    <th class="px-6 py-4 text-left">Nomor</th>
                    <th class="px-6 py-4 text-left">Nama</th>
                    <th class="px-6 py-4 text-center">Rev</th>
                    <th class="px-6 py-4 text-left">Unit</th>
                    <th class="px-6 py-4 text-left">Tanggal</th>
                    <th class="px-6 py-4 text-left">Keterangan</th>
                    <th class="px-6 py-4 text-center">File</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100" id="docTable">
                @foreach ($documents as $doc)
                    <tr class="hover:bg-gray-50 transition doc-row">

                        <td class="px-6 py-4">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4 font-medium text-gray-700">
                            {{ $doc->document_number }}
                        </td>

                        <td class="px-6 py-4 font-semibold text-gray-800">
                            {{ $doc->title }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="bg-indigo-50 text-indigo-600 px-2 py-1 rounded-full text-xs">
                                {{ $doc->revision }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            {{ $doc->department->name ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($doc->document_date)->format('d-m-Y') }}
                        </td>

                        <td class="px-6 py-4 text-gray-500 text-sm">
                            {{ $doc->description }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if ($doc->file_document)
                                <a href="{{ route('admin.documents.stream', $doc->id) }}"
                                    class="inline-flex items-center gap-1 bg-indigo-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-indigo-700 transition">
                                    👁 Lihat
                                </a>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>


{{-- ================= SEARCH SCRIPT ================= --}}
<script>
    const searchInput = document.getElementById('searchInput');
    const rows = document.querySelectorAll('.doc-row');

    searchInput.addEventListener('keyup', function() {
        const keyword = this.value.toLowerCase();

        rows.forEach(row => {
            row.style.display =
                row.innerText.toLowerCase().includes(keyword)
                ? ''
                : 'none';
        });
    });
</script>

@endsection