@extends('layouts.app')

@section('title', 'Recycle Bin')

@section('content')
    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                    <i class="bi bi-trash3 text-rose-600"></i>
                    Recycle Bin
                </h1>
                <p class="text-xs text-slate-500 mt-1">Daftar dokumen yang telah dihapus. Anda dapat memulihkan (restore) kembali atau menghapusnya secara permanen.</p>
            </div>
            
            <!-- Excel Export Button for Trash -->
            <div>
                <a href="{{ route('admin.documents.trash.export', request()->all()) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-md hover:shadow-lg transition duration-200">
                    <i class="bi bi-file-earmark-excel text-sm"></i>
                    Export Excel
                </a>
            </div>
        </div>

        <!-- Category Tabs -->
        <div class="flex gap-2 border-b border-slate-200 pb-3 mb-6 overflow-x-auto">
            <a href="{{ route('admin.documents.trash', array_merge(request()->except('category'), ['category' => 'all'])) }}"
               class="px-4 py-2 text-xs font-bold rounded-xl transition duration-150 whitespace-nowrap {{ !request('category') || request('category') === 'all' ? 'bg-rose-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-100' }}">
                Semua Dokumen
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('admin.documents.trash', array_merge(request()->except('category'), ['category' => $cat->slug])) }}"
                   class="px-4 py-2 text-xs font-bold rounded-xl transition duration-150 whitespace-nowrap {{ request('category') === $cat->slug ? 'bg-rose-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-100' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <!-- Filters Row -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm mb-6">
            <form method="GET" action="{{ route('admin.documents.trash') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <!-- Keep category parameter -->
                <input type="hidden" name="category" value="{{ request('category', 'all') }}">

                <!-- Search Input -->
                <div class="md:col-span-4">
                    <label for="search" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Pencarian Dokumen</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-search text-xs"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari judul, nomor dokumen..."
                               class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition text-xs text-slate-800" />
                    </div>
                </div>

                <!-- Department Filter (Multi Select) -->
                <div class="md:col-span-3">
                    <label for="department-select" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Unit Kerja</label>
                    <select name="department[]" id="department-select" multiple placeholder="Semua Unit Kerja">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ is_array(request('department')) && in_array($dept->id, request('department')) ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Code Filter (Multi Select) -->
                <div class="md:col-span-3">
                    <label for="code-select" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Kode Dokumen</label>
                    <select name="code[]" id="code-select" multiple placeholder="Semua Kode">
                        @foreach($codes as $c)
                            <option value="{{ $c->id }}" {{ is_array(request('code')) && in_array($c->id, request('code')) ? 'selected' : '' }}>
                                {{ $c->code }} - {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Button Grid -->
                <div class="md:col-span-2 flex gap-2 w-full">
                    <button type="submit" 
                            class="flex-1 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition duration-150 shadow-sm flex items-center justify-center gap-1.5">
                        <i class="bi bi-funnel"></i>
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'department', 'code']) || (request('category') && request('category') !== 'all'))
                        <a href="{{ route('admin.documents.trash') }}" 
                           class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-650 text-xs font-bold rounded-xl transition duration-150 flex items-center justify-center shadow-sm"
                           title="Reset Filters">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>

            </form>
        </div>

        <!-- Trash Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-6 py-4 w-12 text-center">No</th>
                            <th class="px-6 py-4 w-44">Nomor Dokumen</th>
                            <th class="px-6 py-4">Judul Dokumen</th>
                            <th class="px-6 py-4 w-40">Unit Kerja</th>
                            <th class="px-6 py-4 w-36">Dihapus Oleh</th>
                            <th class="px-6 py-4 w-40">Keterangan Hapus</th>
                            <th class="px-6 py-4 text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($documents as $index => $doc)
                            <tr class="hover:bg-slate-50/50 transition">
                                <!-- NO -->
                                <td class="px-6 py-4.5 text-center text-slate-450 font-medium">
                                    {{ $documents->firstItem() + $index }}
                                </td>

                                <!-- NOMOR DOKUMEN -->
                                <td class="px-6 py-4.5 font-bold text-slate-700">
                                    {{ $doc->document_number ?? '-' }}
                                </td>

                                <!-- JUDUL DOKUMEN -->
                                <td class="px-6 py-4.5">
                                    <p class="font-bold text-slate-800 leading-normal">{{ $doc->title }}</p>
                                </td>

                                <!-- DEPARTEMEN / UNIT KERJA -->
                                <td class="px-6 py-4.5">
                                    @if($doc->department)
                                        <span class="inline-flex items-center px-2 py-0.5 bg-slate-50 border border-slate-200/80 rounded-md text-slate-600 font-semibold tracking-wide gap-1">
                                            <i class="bi bi-building text-[10px] text-slate-400"></i>
                                            {{ $doc->department->name }}
                                        </span>
                                    @else
                                        <span class="text-slate-450 font-light">-</span>
                                    @endif
                                </td>

                                <!-- DIHAPUS OLEH -->
                                <td class="px-6 py-4.5">
                                    <div class="flex flex-col gap-0.5 leading-relaxed">
                                        <span class="font-bold text-slate-700 text-xs">
                                            • Uploader: {{ $doc->uploader->name ?? '-' }}
                                        </span>
                                        <span class="text-[10px] font-semibold text-slate-450">
                                            Hapus {{ $doc->deleter->name ?? '-' }} : {{ $doc->deleted_at ? $doc->deleted_at->format('d-m-Y H:i') : '-' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- KETERANGAN HAPUS -->
                                <td class="px-6 py-4.5 text-rose-600 font-medium">
                                    {{ $doc->delete_reason ?? '-' }}
                                </td>

                                <!-- ACTION BUTTONS -->
                                <td class="px-6 py-4.5 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Preview -->
                                        @if ($doc->file_document)
                                            <a href="{{ route('admin.documents.stream', $doc->id) }}"
                                               class="w-7 text-blue-600 border border-blue-200 hover:bg-blue-50 rounded-lg py-1.5 flex items-center justify-center transition shadow-sm"
                                               title="Preview Dokumen" 
                                               target="_blank">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endif

                                        <!-- Restore Button Form -->
                                        <form action="{{ route('admin.documents.restore', $doc->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="w-7 text-emerald-600 border border-emerald-200 hover:bg-emerald-50 rounded-lg py-1.5 flex items-center justify-center transition shadow-sm"
                                                    title="Pulihkan Dokumen">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </form>

                                        <!-- Force Delete Form -->
                                        <form action="{{ route('admin.documents.forceDelete', $doc->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini secara permanen dari server? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-7 text-rose-600 border border-rose-200 hover:bg-rose-50 rounded-lg py-1.5 flex items-center justify-center transition shadow-sm"
                                                    title="Hapus Permanen">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 text-slate-400 font-light font-base">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="bi bi-trash3 text-3xl text-slate-200"></i>
                                        <span>Recycle Bin Kosong</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination footer -->
            @if($documents->hasPages())
                <div class="px-6 py-4.5 bg-slate-50/50 border-t border-slate-100">
                    {{ $documents->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript block for tom-select -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Init TomSelect for Department
            if (document.getElementById('department-select')) {
                new TomSelect('#department-select', {
                    plugins: ['remove_button'],
                    create: false,
                    sortField: {
                        field: 'text',
                        direction: 'asc'
                    }
                });
            }

            // Init TomSelect for Document Codes
            if (document.getElementById('code-select')) {
                new TomSelect('#code-select', {
                    plugins: ['remove_button'],
                    create: false,
                    sortField: {
                        field: 'text',
                        direction: 'asc'
                    }
                });
            }
        });
    </script>
@endsection
