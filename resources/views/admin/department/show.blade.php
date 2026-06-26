@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto pb-12">
        
        <!-- Header & Navigation -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                    <i class="bi bi-building-fill text-indigo-650"></i>
                    Unit Kerja: <span class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-xl font-extrabold text-sm border border-indigo-150 shadow-sm ml-1.5">{{ $department->name }}</span>
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Kelola dan tinjau semua dokumen yang dimiliki oleh unit kerja ini
                </p>
            </div>

            <a href="{{ route('admin.department.index') }}"
                class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-4 py-2.5 rounded-xl transition duration-150 shadow-sm border border-slate-200/40">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>

        <!-- Table Container -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 md:p-8">
            
            <!-- Summary Stats bar -->
            <div class="flex items-center gap-2 mb-6 text-slate-500 font-bold bg-slate-50 border border-slate-150 px-4 py-3 rounded-xl w-fit shadow-inner text-xs">
                <i class="bi bi-files text-indigo-500 text-sm"></i>
                Total dokumen yang dimiliki oleh unit kerja ini: <span class="text-indigo-700 font-black text-sm ml-1">{{ $department->documents->count() }} Dokumen</span>
            </div>

            <h3 class="text-xs font-bold text-slate-550 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                <i class="bi bi-file-earmark-text-fill text-indigo-500"></i>
                Daftar Dokumen Unit Kerja
            </h3>

            <div class="overflow-x-auto border border-slate-200/60 rounded-xl">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-550 font-bold uppercase tracking-wider border-b border-slate-200">
                            <th class="px-5 py-4">Judul Dokumen</th>
                            <th class="px-5 py-4">Kategori</th>
                            <th class="px-5 py-4">Unit Kerja Pemilik</th>
                            <th class="px-5 py-4 text-center">Nomor Dokumen</th>
                            <th class="px-5 py-4 text-center w-24">Revisi</th>
                            <th class="px-5 py-4 text-center">Tanggal Dokumen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($department->documents as $doc)
                            <tr class="hover:bg-slate-50/50 transition">
                                <!-- Judul -->
                                <td class="px-5 py-4">
                                    <div class="font-bold text-slate-800 leading-normal">{{ $doc->title }}</div>
                                </td>
                                
                                <!-- Kategori -->
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center bg-slate-100 border border-slate-200 px-2 py-0.5 rounded text-[10px] font-bold text-slate-650">
                                        {{ $doc->category->name ?? '-' }}
                                    </span>
                                </td>

                                <!-- Departemen -->
                                <td class="px-5 py-4">
                                    <span class="font-semibold text-slate-600">
                                        {{ $doc->department->name ?? '-' }}
                                    </span>
                                </td>

                                <!-- Nomor Dokumen -->
                                <td class="px-5 py-4 text-center font-bold text-slate-700 tracking-wide">
                                    {{ $doc->document_number }}
                                </td>

                                <!-- Revisi -->
                                <td class="px-5 py-4 text-center">
                                    <span class="bg-indigo-50 text-indigo-700 border border-indigo-100 px-2 py-0.5 rounded-lg text-[10px] font-bold">
                                        Rev {{ $doc->revision }}
                                    </span>
                                </td>

                                <!-- Tanggal -->
                                <td class="px-5 py-4 text-center text-slate-500 font-medium">
                                    {{ $doc->document_date ? \Carbon\Carbon::parse($doc->document_date)->translatedFormat('d M Y') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-16 text-slate-400 font-medium bg-slate-50/50">
                                    <i class="bi bi-journal-x text-3xl block text-slate-355 mb-2"></i>
                                    Belum ada dokumen terdaftar untuk unit kerja ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
