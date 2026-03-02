@extends('layouts.app')

@section('title', 'User Admin')

@section('content')

    <div class="bg-indigo-600 text-white px-6 py-3 rounded-t-xl">
        <h2 class="font-semibold text-lg">Data User</h2>
    </div>

    <div class="bg-white rounded-b-xl shadow border border-gray-100 p-5">
        <div class="flex justify-end mb-4">

            <a href="{{ route('admin.user.create') }}"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                Tambah User
            </a>

        </div>

        <div class="bg-white rounded-b-xl shadow border border-gray-100 p-5">
            <div class="flex justify-between items-center mb-4">

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
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">No Badge</th>
                            <th class="px-4 py-3">email</th>
                            <th class="px-4 py-3">Departement</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        @forelse($users as $index => $user)
                            <tr class="hover:bg-gray-50">

                                <td class="px-4 py-3">
                                    {{ $users->firstItem() + $index }}
                                </td>

                                <td class="px-4 py-3 font-medium">
                                    {{ $user->name }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $user->no_badge ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $user->email }}
                                </td>

                                <td class="px-4 py-3">
                                {{ $user->department_name ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    <span
                                        class="px-3 py-1 text-xs rounded-full
            {{ $user->role == 'admin' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 flex gap-2">

                                    <a href="{{ route('admin.user.show', $user->id) }}"
                                        class="bg-green-500 text-white px-2 py-1 rounded text-xs">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>

                                    <a href="{{ route('admin.user.edit', $user->id) }}"
                                        class="bg-yellow-500 text-white px-2 py-1 rounded text-xs">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <button type="button" onclick="confirmDelete({{ $user->id }})"
                                        class="bg-red-500 text-white px-2 py-1 rounded text-xs">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>

                                    <form id="delete-form-{{ $user->id }}"
                                        action="{{ route('admin.user.destroy', $user->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>


                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-gray-500">
                                    Tidak ada data
                                </td>
                            </tr>
                        @endforelse

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
                background: #6366f1;
                color: white;
            }
        </style>

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

        <script>
            function confirmDelete(id) {
                Swal.fire({
                    title: 'Yakin ingin hapus?',
                    text: "Data tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                })
            }
        </script>

    @endsection
