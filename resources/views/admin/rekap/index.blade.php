@extends('layouts.app')

@section('content')

<div class="bg-indigo-600 text-white px-6 py-3 rounded-t-xl">
    <h2 class="font-semibold text-lg">Rekap Data Dokumen</h2>
</div>

<div class="bg-white rounded-b-xl shadow border border-gray-100 p-5">

    {{-- EXPORT --}}
    <div class="flex justify-between items-center mb-4">

        <a href="{{ route('admin.rekap.export', ['category' => $category]) }}"
            class="bg-green-500 text-white px-4 py-2 rounded">
            Export Excel
        </a>

    </div>

    {{-- FILTER KATEGORI --}}
    <div class="flex gap-3 mb-6 flex-wrap">

        @php
            $currentCategory = $category ?? 'all';
        @endphp

        @foreach(['all','ratifikasi','pedoman','prosedur','instruksikerja','formulir'] as $cat)
            <a href="{{ route('admin.rekap.index', ['category' => $cat]) }}"
                class="px-4 py-2 rounded-xl text-sm font-medium transition
                {{ $currentCategory == $cat ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ ucfirst($cat) }}
            </a>
        @endforeach

    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto bg-white shadow rounded-xl">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-sm uppercase text-gray-600">
                    <th class="p-3">Nomor</th>
                    <th class="p-3">Nama Dokumen</th>
                    <th class="p-3">Revisi</th>
                    <th class="p-3">Unit Kerja</th>
                    <th class="p-3">Keterangan</th>
                    <th class="p-3">Tanggal</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($documents as $doc)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $doc->document_number }}</td>
                        <td class="p-3 font-semibold">{{ $doc->title }}</td>
                        <td class="p-3">{{ $doc->revision ?? 0 }}</td>
                        <td class="p-3">{{ $doc->department->name ?? '-' }}</td>
                        <td class="p-3">{{ $doc->description ?? '-' }}</td>
                        <td class="p-3">
                            {{ \Carbon\Carbon::parse($doc->document_date)->format('d-m-Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center p-5 text-gray-400">
                            Tidak ada dokumen
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
