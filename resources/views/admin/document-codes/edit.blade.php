@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto pb-12">
        
        <!-- Header & Navigation -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">Edit Kode Dokumen</h2>
                <p class="text-xs text-slate-400 mt-0.5">Ubah informasi detail kode klasifikasi dokumen</p>
            </div>

            <a href="{{ route('admin.document-codes.index') }}"
                class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-4 py-2.5 rounded-xl transition duration-150 shadow-sm border border-slate-200/40">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8">
            
            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-100 text-rose-700 rounded-xl p-4 mb-6 shadow-sm">
                    <h4 class="text-xs font-bold uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <i class="bi bi-exclamation-triangle-fill"></i> Terjadi Kesalahan Input
                    </h4>
                    <ul class="list-disc list-inside text-xs space-y-1 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.document-codes.update', $document_code->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Code Input -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                        Kode Klasifikasi <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="code" value="{{ old('code', $document_code->code) }}"
                        class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 text-slate-800 transition duration-150 font-bold tracking-wider"
                        placeholder="Contoh: PI-SMT, PD, PRO, dll." required>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Ubah kode penomoran (misal: PI-SMT)</p>
                </div>

                <!-- Description Input -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                        Keterangan / Nama Kode <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="description" value="{{ old('description', $document_code->description) }}"
                        class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 text-slate-800 transition duration-150"
                        placeholder="Contoh: Pedoman Sistem Manajemen, Prosedur Kerja, dll." required>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Ubah penjelasan deskriptif dari arti kode klasifikasi di atas</p>
                </div>

                <!-- Actions Button Row -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.document-codes.index') }}"
                        class="px-5 py-2.5 text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition duration-150 shadow-sm border border-slate-200/40">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-xs font-bold px-6 py-2.5 rounded-xl shadow-sm transition duration-150 hover:shadow hover:-translate-y-0.5">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

    </div>
@endsection
