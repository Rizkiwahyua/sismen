@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-4 gap-4 mb-6">

    </div>
    <div class="bg-indigo-600 text-white px-6 py-3 rounded-t-xl">
        <h2 class="font-semibold text-lg">Data Dokumen</h2>
    </div>

    <div class="bg-white rounded-b-xl shadow border border-gray-100 p-5">

        <!-- Tombol Tambah -->
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.documents.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
                Tambah Dokumen
            </a>
        </div>

        <!-- Show + Search -->
        <div class="flex justify-between items-center mb-4">

      <a href="{{ route('admin.documents.export', ['category' => request('category', 'all')]) }}"
   class="btn btn-success">
   Export Excel
</a>
            <div class="text-sm text-gray-600">
                Show
                <select class="border border-gray-300 rounded px-2 py-1 text-sm mx-1">
                    <option>10</option>
                    <option>20</option>
                    <option>50</option>
                </select>
                entries
            </div>

            <div class="flex border border-gray-300 rounded-lg overflow-hidden">
                <input type="text" placeholder="Search..." class="px-3 py-1 text-sm focus:outline-none">
                <button class="px-3 bg-indigo-500 text-white text-sm">
                    Cari
                </button>
            </div>

        </div>

        @php
            $currentCategory = request('category', 'all');

        @endphp

        <div class="flex gap-3 mb-6 flex-wrap">

            <!-- Semua -->
            <a href="{{ route('admin.documents.index') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition
        {{ $currentCategory == 'all' ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                <i class="bi bi-grid"></i>
                Semua
            </a>

            <!-- Ratifikasi -->
            <a href="{{ route('admin.documents.index', ['category' => 'ratifikasi']) }}"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition
        {{ $currentCategory == 'ratifikasi' ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                <i class="bi bi-patch-check"></i>
                Ratifikasi
            </a>

            <!-- Pedoman -->
            <a href="{{ route('admin.documents.index', ['category' => 'pedoman']) }}"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition
        {{ $currentCategory == 'pedoman' ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                <i class="bi bi-journal-text"></i>
                Pedoman
            </a>

            <!-- Prosedur -->
            <a href="{{ route('admin.documents.index', ['category' => 'prosedur']) }}"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition
        {{ $currentCategory == 'prosedur' ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                <i class="bi bi-diagram-3"></i>
                Prosedur
            </a>

            <!-- Instruksi Kerja -->
            <a href="{{ route('admin.documents.index', ['category' => 'instruksikerja']) }}"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition
        {{ $currentCategory == 'instruksikerja' ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                <i class="bi bi-gear"></i>
                Instruksi Kerja
            </a>

            <!-- Formulir -->
            <a href="{{ route('admin.documents.index', ['category' => 'formulir']) }}"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition
        {{ $currentCategory == 'formulir' ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                <i class="bi bi-files"></i>
                Formulir
            </a>

        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto bg-white shadow rounded-xl">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-sm uppercase text-gray-600">
                        <th class="p-3">No</th>
                        <th class="p-3">Nomor</th>
                        <th class="p-3">Nama Dokumen</th>
                        <th class="p-3">Revisi</th>
                        <th class="p-3">Unit Kerja</th>
                        <th class="p-3">Keterangan</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($documents as $doc)
                        <tr class="border-b hover:bg-gray-50">

                               <td class="p-3">{{ $loop->iteration }}</td>
                            <!-- ACTION -->


                                <!-- NOMOR -->
                            <td class="p-3">{{ $doc->document_number }}</td>

                            <!-- NAMA DOKUMEN -->
                            <td class="p-3 font-semibold">
                                {{ $doc->title }}
                            </td>

                            <!-- REVISI -->
                            <td class="p-3">{{ $doc->revision ?? 0 }}</td>

                            <!-- UNIT KERJA -->
                            <td class="p-3">
                                {{ $doc->department->name ?? '-' }}
                            </td>

                            <!-- KETERANGAN -->
                            <td class="p-3">
                                {{ $doc->description ?? '-' }}
                            </td>

                            <!-- TANGGAL -->
                            <td class="p-3">
                                {{ \Carbon\Carbon::parse($doc->document_date)->format('d-m-Y') }}
                            </td>
                             <td class="p-3 flex gap-2">
                                <a href="{{ route('admin.documents.preview', $doc->id) }}"
                                    class="bg-blue-500 text-white px-2 py-1 rounded text-xs">
                                    👁
                                </a>

                                {{-- @if ($doc->file_document)
                                    <a href="{{ asset($doc->file_document) }}"
                                        class="bg-green-500 text-white px-2 py-1 rounded text-xs">
                                        ⬇
                                    </a>
                                @endif --}}

                                <a href="{{ route('admin.documents.edit', $doc->id) }}"
                                    class="bg-yellow-500 text-white px-2 py-1 rounded text-xs">
                                    ✏
                                </a>
                                <button type="button" onclick="openDeleteModal({{ $doc->id }})"
                                    class="bg-red-500 text-white px-2 py-1 rounded text-xs">
                                    🗑
                                </button>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-5 text-gray-400">
                                Tidak ada dokumen
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

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
@endsection
