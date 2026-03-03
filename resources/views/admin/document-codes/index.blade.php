@extends('layouts.app')

@section('content')
    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Kode Dokumen</h2>
            <p class="text-sm text-gray-500">Kelola kode klasifikasi dokumen sistem</p>
        </div>

        <a href="{{ route('admin.document-codes.create') }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl shadow-md transition">
            + Tambah Kode
        </a>
    </div>

    {{-- SUCCESS --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 mb-6 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- SUMMARY --}}
    <div class="mb-6">
        <div class="bg-white shadow rounded-xl p-5 border flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Kode Dokumen</p>
                <p class="text-2xl font-bold text-indigo-600">
                    {{ $codes->count() }}
                </p>
            </div>
            <div class="bg-indigo-100 text-indigo-600 p-4 rounded-full text-2xl">
                📂
            </div>
        </div>
    </div>

    {{-- GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

        @forelse ($codes as $code)
            <div
                class="bg-white rounded-2xl shadow-sm border hover:shadow-xl transition duration-300 p-6 flex flex-col justify-between">

                {{-- TOP --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-bold text-gray-800">
                            {{ $code->code }}
                        </h3>

                        <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-medium">
                            {{ $code->documents_count }} Dokumen
                        </span>
                    </div>

                    <p class="text-gray-500 text-sm mb-4">
                        {{ $code->name }}
                    </p>

                    {{-- Progress visual --}}
                    <div class="h-1 w-full bg-gray-100 rounded">
                        <div class="h-1 bg-indigo-500 rounded" style="width: {{ min($code->documents_count * 10, 100) }}%">
                        </div>
                    </div>
                </div>

                {{-- ACTION --}}
                <div class="flex gap-2 mt-5">

                    <a href="{{ route('admin.document-codes.show', $code->id) }}"
                        class="flex-1 text-center bg-blue-50 text-blue-600 py-2 rounded-lg text-sm hover:bg-blue-100 transition">
                        Detail
                    </a>

                    <a href="{{ route('admin.document-codes.edit', $code->id) }}"
                        class="flex-1 text-center bg-yellow-50 text-yellow-600 py-2 rounded-lg text-sm hover:bg-yellow-100 transition">
                        Edit
                    </a>

                    <form action="{{ route('admin.document-codes.destroy', $code->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus kode ini?')" class="flex-1">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="w-full bg-red-50 text-red-600 py-2 rounded-lg text-sm hover:bg-red-100 transition">
                            Hapus
                        </button>
                    </form>

                </div>

            </div>

        @empty
            <div class="col-span-full text-center py-16 text-gray-400">
                Belum ada kode dokumen
            </div>
        @endforelse

    </div>
@endsection
