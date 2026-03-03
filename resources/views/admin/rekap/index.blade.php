@extends('layouts.app')

@section('content')
    {{-- ================= SUMMARY CARDS ================= --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">

        @php
            $cards = [
                ['Semua Dokumen', $totalDokumen, 'indigo'],
                ['Ratifikasi', $totalRatifikasi, 'green'],
                ['Pedoman', $totalPedoman, 'blue'],
                ['Prosedur', $totalProsedur, 'purple'],
                ['Instruksi', $totalInstruksi, 'amber'],
                ['Formulir', $totalFormulir, 'rose'],
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


    {{-- ================= HEADER + EXPORT ================= --}}
    <div class="bg-white rounded-2xl shadow-sm border mb-6">

        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h2 class="font-semibold text-gray-800">
                Rekap Data Dokumen
            </h2>

            <a href="{{ route('admin.rekap.export', request()->query()) }}"
                class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition shadow-sm">
                📊 Export Excel
            </a>
        </div>

        {{-- ================= FILTER BAR ================= --}}
        <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4">

            {{-- FILTER KATEGORI --}}
            @php
                $currentCategory = request('category', 'all');
            @endphp

            <div class="flex flex-wrap gap-2">
                @foreach (['all', 'ratifikasi', 'pedoman', 'prosedur', 'instruksikerja', 'formulir'] as $cat)
                    <a href="{{ route('admin.rekap.index', array_merge(request()->query(), ['category' => $cat])) }}"
                        class="px-3 py-1.5 rounded-xl text-xs font-medium transition
                    {{ $currentCategory == $cat ? 'bg-indigo-600 text-white shadow' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        {{ ucfirst($cat) }}
                    </a>
                @endforeach
            </div>

            {{-- FILTER SELECT --}}
            <form method="GET" action="{{ route('admin.rekap.index') }}" class="flex items-center gap-2 flex-wrap">

                <input type="hidden" name="category" value="{{ request('category', 'all') }}">

                <select name="department" onchange="this.form.submit()"
                    class="text-xs border rounded-lg px-3 py-2 focus:ring-1 focus:ring-indigo-500">

                    <option value="">Semua Unit</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>

                <select name="code" onchange="this.form.submit()"
                    class="text-xs border rounded-lg px-3 py-2 focus:ring-1 focus:ring-indigo-500">

                    <option value="">Semua Kode</option>
                    @foreach ($codes as $code)
                        <option value="{{ $code->id }}" {{ request('code') == $code->id ? 'selected' : '' }}>
                            {{ $code->code }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..."
                    oninput="this.form.submit()"
                    class="text-xs border rounded-lg px-3 py-2 w-40 focus:ring-1 focus:ring-indigo-500">

            </form>

        </div>

    </div>


    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-x-auto">

        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-6 py-4 text-left">No</th>
                    <th class="px-6 py-4 text-left">Nomor</th>
                    <th class="px-6 py-4 text-left">Nama Dokumen</th>
                    <th class="px-6 py-4 text-center">Rev</th>
                    <th class="px-6 py-4 text-left">Unit</th>
                    <th class="px-6 py-4 text-center">File</th>
                    <th class="px-6 py-4 text-left">Keterangan</th>
                    <th class="px-6 py-4 text-left">Tanggal</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse ($documents as $doc)
                    <tr class="hover:bg-gray-50 transition">

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
                                {{ $doc->revision ?? 0 }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            {{ $doc->department->name ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if ($doc->file_document)
                                <a href="{{ asset($doc->file_document) }}" target="_blank"
                                    class="inline-flex items-center gap-1 bg-indigo-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-indigo-700 transition">
                                    👁 Lihat
                                </a>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-gray-500 text-sm">
                            {{ $doc->description ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($doc->document_date)->format('d-m-Y') }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-10 text-gray-400">
                            Tidak ada dokumen
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>
@endsection
