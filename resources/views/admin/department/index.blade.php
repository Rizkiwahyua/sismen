@extends('layouts.app')

@section('content')
    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Departemen</h2>
            <p class="text-sm text-gray-500">Kelola unit kerja dan jumlah dokumennya</p>
        </div>

        <a href="{{ route('admin.department.create') }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl shadow-md transition">
            + Tambah Departemen
        </a>
    </div>

    {{-- SUCCESS ALERT --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 mb-6 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- SUMMARY CARD --}}
    <div class="mb-6">
        <div class="bg-white shadow rounded-xl p-5 border flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Departemen</p>
                <p class="text-2xl font-bold text-indigo-600">
                    {{ $departments->count() }}
                </p>
            </div>
            <div class="bg-indigo-100 text-indigo-600 p-4 rounded-full text-2xl">
                🏢
            </div>
        </div>
    </div>

    {{-- GRID CARD --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

        @forelse ($departments as $department)
            <div
                class="bg-white rounded-2xl shadow-sm border hover:shadow-xl transition duration-300 p-6 flex flex-col justify-between">

                {{-- TOP --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $department->name }}
                        </h3>

                        <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-medium">
                            {{ $department->documents_count }} Dokumen
                        </span>
                    </div>

                    <div class="h-1 w-full bg-gray-100 rounded mb-4">
                        <div class="h-1 bg-indigo-500 rounded"
                            style="width: {{ min($department->documents_count * 10, 100) }}%">
                        </div>
                    </div>
                </div>

                {{-- ACTION --}}
                <div class="flex gap-2 mt-4">

                    <a href="{{ route('admin.department.show', $department->id) }}"
                        class="flex-1 text-center bg-blue-50 text-blue-600 py-2 rounded-lg text-sm hover:bg-blue-100 transition">
                        Detail
                    </a>

                    <a href="{{ route('admin.department.edit', $department->id) }}"
                        class="flex-1 text-center bg-yellow-50 text-yellow-600 py-2 rounded-lg text-sm hover:bg-yellow-100 transition">
                        Edit
                    </a>

                    <form action="{{ route('admin.department.destroy', $department->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus departemen ini?')" class="flex-1">
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
                Belum ada departemen
            </div>
        @endforelse

    </div>
@endsection
