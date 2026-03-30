@extends('layouts.app')

@section('content')



    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Data Dokumen</h2>
            <p class="text-sm text-gray-500">Kelola seluruh dokumen sistem</p>
        </div>

        <a href="{{ route('admin.documents.create') }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl shadow-md transition">
            + Tambah Dokumen
        </a>
    </div>

    <form method="GET" action="{{ route('admin.documents.index') }}"
        class="flex border border-gray-300 rounded-xl overflow-hidden shadow-sm">

        {{-- Pertahankan category --}}
        <input type="hidden" name="category" value="{{ request('category', 'all') }}">

        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari dokumen..."
            class="px-4 py-2 text-sm focus:outline-none w-48" onkeyup="debounceSearch(this.form)">
    </form>

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
    <div class="bg-white rounded-xl shadow border overflow-hidden">
        <table class="w-full text-sm">

            <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wide">
                <tr>
                    <th class="p-4 text-left">No</th>
                    <th class="p-4 text-left">Nomor</th>
                    <th class="p-4 text-left">Nama Dokumen</th>
                    <th class="p-4 text-left">Rev</th>
                    <th class="p-4 text-left">Unit</th>
                    <th class="p-4 text-left">Tanggal</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse($documents as $doc)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="p-4 text-gray-500">
                            {{ $loop->iteration }}
                        </td>

                        <td class="p-4 font-medium">
                            {{ $doc->document_number }}
                        </td>

                        <td class="p-4">
                            <div class="font-semibold text-gray-800">
                                {{ $doc->title }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $doc->description ?? '-' }}
                            </div>
                        </td>

                        <td class="p-4">
                            <span class="bg-gray-100 px-2 py-1 rounded text-xs">
                                {{ $doc->revision ?? 0 }}
                            </span>
                        </td>

                        <td class="p-4">
                            {{ $doc->department->name ?? '-' }}
                        </td>

                        <td class="p-4 text-gray-500">
                            {{ \Carbon\Carbon::parse($doc->document_date)->format('d-m-Y') }}
                        </td>

                        <td class="p-4 text-center">
                            <div class="flex justify-center gap-2">

                                <a href="{{ route('admin.documents.preview', $doc->id) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('admin.documents.edit', $doc->id) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 transition">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <button onclick="openDeleteModal({{ $doc->id }})"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-gray-400">
                            Tidak ada dokumen
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>

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
    <script>
        let searchTimer;

        function debounceSearch(form) {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                form.submit();
            }, 500); // delay 500ms biar gak reload tiap huruf
        }
    </script>
@endsection
