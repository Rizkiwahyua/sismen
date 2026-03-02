@extends('layouts.app')

@section('content')

<div class="bg-indigo-600 text-white px-6 py-3 rounded-t-xl">
    <h2 class="font-semibold text-lg">Rekap Data Dokumen</h2>
</div>

<div class="bg-white rounded-b-xl shadow border border-gray-100 p-5">

    {{-- EXPORT --}}
    <div class="flex justify-between items-center mb-4">

     <a href="{{ route('admin.rekap.export', request()->query()) }}"
   class="btn btn-success btn-sm text-white rounded-pill px-3 py-2"
   style="background-color: #28a745; border-color: #28a745; box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: 0.3s;">
   <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
</a>
    </div>

    {{-- FILTER KATEGORI --}}
{{-- FILTER BAR --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">

    {{-- KATEGORI (KIRI) --}}
    <div class="flex flex-wrap gap-2">
        @php
            $currentCategory = request('category', 'all');
        @endphp

        @foreach(['all','ratifikasi','pedoman','prosedur','instruksikerja','formulir'] as $cat)
            <a href="{{ route('admin.rekap.index', array_merge(request()->query(), ['category' => $cat])) }}"
                class="px-3 py-1.5 rounded-lg text-xs font-medium transition
                {{ $currentCategory == $cat
                    ? 'bg-blue-600 text-white shadow'
                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ ucfirst($cat) }}
            </a>
        @endforeach
    </div>


    {{-- FILTER KANAN --}}
    <form method="GET"
          action="{{ route('admin.rekap.index') }}"
          class="flex items-center gap-2">

        {{-- Pertahankan kategori --}}
        <input type="hidden" name="category" value="{{ request('category','all') }}">

        {{-- Department kecil --}}
        <select name="department"
            onchange="this.form.submit()"
            class="text-xs border rounded-md px-2 py-1 focus:ring-1 focus:ring-blue-500">

            <option value="">Semua Unit</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}"
                    {{ $departmentId == $dept->id ? 'selected' : '' }}>
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>

        {{-- Kode Dokumen --}}
<select name="code"
    onchange="this.form.submit()"
    class="text-xs border rounded-md px-2 py-1 focus:ring-1 focus:ring-blue-500">

    <option value="">Semua Kode</option>
    @foreach($codes as $code)
        <option value="{{ $code->id }}"
            {{ request('code') == $code->id ? 'selected' : '' }}>
            {{ $code->code }}
        </option>
    @endforeach
</select>
        {{-- Search kecil --}}
        <input type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari..."
            oninput="this.form.submit()"
            class="text-xs border rounded-md px-2 py-1 w-36 focus:ring-1 focus:ring-blue-500">

    </form>

</div>
    {{-- TABLE --}}
    <div class="overflow-x-auto bg-white shadow rounded-xl">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-sm uppercase text-gray-600">
                     <th class="p-3">No</th>
                    <th class="p-3">Nomor</th>
                    <th class="p-3">Nama Dokumen</th>
                    <th class="p-3">Revisi</th>
                    <th class="p-3">Unit Kerja</th>
                    <th class="p-3">File</th>
                    <th class="p-3">Keterangan</th>
                    <th class="p-3">Tanggal</th>
                     <th class="p-3">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($documents as $doc)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $loop->iteration }}</td>
                        <td class="p-3">{{ $doc->document_number }}</td>
                        <td class="p-3 font-semibold">{{ $doc->title }}</td>
                        <td class="p-3">{{ $doc->revision ?? 0 }}</td>
                        <td class="p-3">{{ $doc->department->name ?? '-' }}</td>
                        <td class="p-3">
    @if($doc->file_document)
        <a href="{{ asset($doc->file_document) }}" target="_blank"
           class="inline-block px-3 py-1.5 bg-blue-100 text-blue-700 font-medium text-xs rounded-lg
                  hover:bg-blue-200 hover:shadow transition">
            Lihat File
        </a>
    @else
        <span class="text-gray-400">-</span>
    @endif
</td>
                        <td class="p-3">{{ $doc->description ?? '-' }}</td>
                        <td class="p-3">
                            {{ \Carbon\Carbon::parse($doc->document_date)->format('d-m-Y') }}
                        </td>
               <td class="p-3 flex gap-1">
    <!-- EDIT -->
    <a href=""
       class="btn btn-sm btn-warning">
        <i class="bi bi-pencil"></i> Edit
    </a>

    <!-- DELETE -->
    <form action=""
          method="POST"
          style="display:inline;">
        @csrf
        @method('DELETE')
        <input type="hidden" name="delete_reason" value="Dihapus dari rekap">

        <button type="submit"
                class="btn btn-sm btn-danger"
                onclick="return confirm('Yakin hapus dokumen ini?')">
            <i class="bi bi-trash"></i> Hapus
        </button>
    </form>
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
