@extends('layouts.app')

@section('content')
@php
    $document->loadMissing(['category', 'code', 'department', 'details.department', 'uploader', 'updater']);
    $role = auth()->user()->role === 'admin' ? 'admin' : 'user';
@endphp

<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb & Header Title -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 font-medium mb-1">
                <a href="{{ $role === 'admin' ? route('admin.documents.index') : route('user.dashboard') }}" class="hover:text-indigo-600 transition">Dokumen</a>
                <i class="bi bi-chevron-right text-[10px]"></i>
                <span class="text-slate-600">Preview Dokumen</span>
            </div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">
                {{ $document->title }}
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">
                Kategori: <span class="font-semibold text-slate-600">{{ $document->category->name ?? '-' }}</span>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ $role === 'admin' ? route('admin.documents.index') : route('user.dashboard') }}"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-semibold rounded-xl transition shadow-sm cursor-pointer">
                <i class="bi bi-arrow-left text-sm"></i>
                Kembali
            </a>
            
            @if ($document->file_document)
                <a href="{{ route($role . '.documents.download', $document->id) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-xs font-semibold rounded-xl shadow-sm transition-all duration-150 hover:shadow cursor-pointer">
                    <i class="bi bi-download text-sm"></i>
                    Download Dokumen
                </a>
            @endif
        </div>
    </div>

    <!-- Main Grid Workspace -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Side: PDF Viewer & Ratifikasi details (8 cols) -->
        <div class="lg:col-span-8 flex flex-col gap-6">
            
            <!-- Document View Box -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-red-50 border border-red-100 flex items-center justify-center text-red-500">
                            <i class="bi bi-file-earmark-pdf-fill text-base"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-800">Berkas Preview</span>
                            <span class="block text-[10px] text-slate-400">PDF Watermarked (Terkunci)</span>
                        </div>
                    </div>
                    
                    <div class="text-[10px] bg-amber-50 text-amber-700 border border-amber-100 px-2.5 py-1 rounded-lg font-semibold flex items-center gap-1">
                        <i class="bi bi-shield-fill-exclamation text-xs"></i>
                        Dokumen Tidak Terkendali
                    </div>
                </div>

                <div class="p-4 bg-slate-100/50 flex justify-center items-center">
                    @if ($document->file_document)
                        <iframe src="{{ route($role . '.documents.stream', $document->id) }}?t={{ time() }}#toolbar=0" 
                            width="100%" 
                            height="750px"
                            class="rounded-xl border border-slate-200 shadow-sm bg-white">
                        </iframe>
                    @else
                        <div class="py-24 text-center text-slate-450">
                            <i class="bi bi-file-earmark-lock text-4xl block mb-2 text-slate-300"></i>
                            <span class="text-sm font-semibold block text-slate-500">File Dokumen Tidak Tersedia</span>
                            <span class="text-xs text-slate-400">Hubungi Administrator jika ini merupakan kesalahan.</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ratifikasi Sub-Details -->
            @if ($document->category && $document->category->slug == 'ratifikasi' && count($document->details) > 0)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                        <i class="bi bi-patch-check-fill text-emerald-500 text-lg"></i>
                        <div>
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Sub Judul / Judul Uraian Ratifikasi</h3>
                            <p class="text-[10px] text-slate-400">Daftar lampiran detail ratifikasi unit kerja terkait</p>
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <div class="overflow-hidden border border-slate-200/60 rounded-xl shadow-sm">
                            <table class="w-full text-xs text-left border-collapse bg-white">
                                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                                    <tr>
                                        <th class="px-4 py-3 text-center w-12">No</th>
                                        <th class="px-4 py-3">Sub Judul / Judul Uraian</th>
                                        <th class="px-4 py-3">Unit Kerja Terkait</th>
                                        <th class="px-4 py-3">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($document->details as $index => $d)
                                        <tr class="hover:bg-slate-50/50 transition duration-150">
                                            <td class="px-4 py-3.5 text-center text-slate-400 font-medium">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3.5 font-bold text-slate-800">{{ $d->sub_title }}</td>
                                            <td class="px-4 py-3.5">
                                                @php
                                                    $deptNames = [];
                                                    if (!empty($d->department_ids)) {
                                                        $deptIds = is_array($d->department_ids)
                                                            ? $d->department_ids
                                                            : json_decode($d->department_ids, true);

                                                        $deptNames = \App\Models\Department::whereIn('id', $deptIds)
                                                            ->pluck('name')
                                                            ->toArray();
                                                    }
                                                @endphp
                                                @if (!empty($deptNames))
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach ($deptNames as $name)
                                                            <span class="inline-block bg-slate-50 text-slate-700 px-2 py-0.5 rounded text-[10px] font-medium border border-slate-200/50">
                                                                {{ $name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-slate-300">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3.5 text-slate-500">
                                                {{ $d->description ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <!-- Right Side: Details & Metadata (4 cols) -->
        <div class="lg:col-span-4 flex flex-col gap-6">
            
            <!-- Metadata Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center gap-1.5">
                    <i class="bi bi-info-circle text-sm text-slate-400"></i>
                    Informasi Dokumen
                </h3>

                <div class="space-y-4">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nomor Dokumen</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $document->document_number ?? '-' }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kode Dokumen</span>
                            <span class="inline-flex items-center bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-lg text-xs font-bold border border-indigo-100/50 mt-0.5">
                                {{ $document->code->code ?? '-' }}
                            </span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Revisi</span>
                            <span class="inline-flex items-center bg-amber-50 text-amber-700 px-2.5 py-1 rounded-lg text-xs font-bold border border-amber-100/50 mt-0.5">
                                Rev. {{ $document->revision ?? '0' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Unit Kerja</span>
                        <span class="text-sm font-semibold text-slate-700 block mt-0.5">{{ $document->department->name ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Terbit</span>
                        <span class="text-sm font-semibold text-slate-800 block mt-0.5">
                            <i class="bi bi-calendar3 text-xs text-slate-400 mr-1.5"></i>
                            {{ $document->document_date ? \Carbon\Carbon::parse($document->document_date)->format('d F Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Description Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100 flex items-center gap-1.5">
                    <i class="bi bi-text-left text-sm text-slate-400"></i>
                    Keterangan / Deskripsi
                </h3>
                <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">
                    {{ $document->description ?: 'Tidak ada deskripsi tambahan.' }}
                </p>
            </div>

            <!-- User Info Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center gap-1.5">
                    <i class="bi bi-people text-sm text-slate-400"></i>
                    Aktivitas & Log
                </h3>

                <div class="space-y-4">
                    <!-- Uploader -->
                    @if ($document->uploader)
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full overflow-hidden border border-slate-200 flex-shrink-0 bg-slate-150">
                                <img src="{{ $document->uploader->photo ? asset('images/' . $document->uploader->photo) : asset('images/profile.png') }}" 
                                    alt="Uploader Avatar" 
                                    class="w-full h-full object-cover">
                            </div>
                            <div>
                                <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Diunggah Oleh</span>
                                <span class="block text-xs font-semibold text-slate-700">{{ $document->uploader->name }}</span>
                                <span class="block text-[9px] text-slate-400 mt-0.5">Pada {{ $document->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Updater -->
                    @if ($document->updater && $document->updated_at != $document->created_at)
                        <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                            <div class="w-9 h-9 rounded-full overflow-hidden border border-slate-200 flex-shrink-0 bg-slate-150">
                                <img src="{{ $document->updater->photo ? asset('images/' . $document->updater->photo) : asset('images/profile.png') }}" 
                                    alt="Updater Avatar" 
                                    class="w-full h-full object-cover">
                            </div>
                            <div>
                                <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Diperbarui Oleh</span>
                                <span class="block text-xs font-semibold text-slate-700">{{ $document->updater->name }}</span>
                                <span class="block text-[9px] text-slate-400 mt-0.5">Pada {{ $document->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
