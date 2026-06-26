@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto pb-12">
        
        <!-- HEADER SECTION -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                    <i class="bi bi-qr-code text-indigo-650"></i>
                    Manajemen Kode Dokumen
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Kelola kode klasifikasi untuk generator nomor dokumen sistem</p>
            </div>

            <a href="{{ route('admin.document-codes.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-750 hover:from-indigo-700 hover:to-indigo-850 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md hover:shadow-lg transition duration-150 transform hover:-translate-y-0.5">
                <i class="bi bi-plus-lg"></i>
                Tambah Kode Baru
            </a>
        </div>

        <!-- SUCCESS NOTIFICATION -->
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 px-4 py-3.5 rounded-xl text-xs font-semibold shadow-sm mb-6 flex items-center gap-2">
                <i class="bi bi-check-circle-fill text-emerald-500 text-sm"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- ERROR NOTIFICATION -->
        @if (session('error'))
            <div class="bg-rose-50 border border-rose-100 text-rose-850 px-4 py-3.5 rounded-xl text-xs font-semibold shadow-sm mb-6 flex items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-rose-550 text-sm"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- STATISTICS CARD (PREMIUM COLOR GRADIENT) -->
        <div class="mb-8">
            <div class="bg-gradient-to-br from-indigo-600 via-indigo-750 to-violet-800 rounded-2xl p-5 shadow-lg shadow-indigo-600/15 flex items-center justify-between max-w-sm hover:shadow-xl hover:shadow-indigo-600/20 hover:-translate-y-0.5 transition duration-200 text-white">
                <div class="space-y-1">
                    <p class="text-[10px] font-bold text-indigo-100 uppercase tracking-wider">Total Kode Klasifikasi</p>
                    <p class="text-3xl font-black">
                        {{ $codes->count() }} <span class="text-xs font-medium text-indigo-200">Kode Aktif</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center text-white shadow-inner">
                    <i class="bi bi-folder2-open text-xl"></i>
                </div>
            </div>
        </div>

        <!-- CODE CLASSIFICATION GRID -->
        @php
            $gradients = [
                ['from' => 'from-blue-500', 'to' => 'to-indigo-600', 'bg' => 'bg-indigo-50/50', 'text' => 'text-indigo-700', 'border' => 'group-hover:border-indigo-300', 'btn_view' => 'bg-indigo-50 border border-indigo-100 text-indigo-700 hover:bg-indigo-100', 'btn_edit' => 'bg-amber-50 border border-amber-100 text-amber-700 hover:bg-amber-100'],
                ['from' => 'from-emerald-400', 'to' => 'to-teal-600', 'bg' => 'bg-teal-50/50', 'text' => 'text-teal-700', 'border' => 'group-hover:border-teal-300', 'btn_view' => 'bg-teal-50 border border-teal-100 text-teal-700 hover:bg-teal-100', 'btn_edit' => 'bg-amber-50 border border-amber-100 text-amber-700 hover:bg-amber-100'],
                ['from' => 'from-amber-400', 'to' => 'to-orange-600', 'bg' => 'bg-orange-50/40', 'text' => 'text-orange-700', 'border' => 'group-hover:border-orange-300', 'btn_view' => 'bg-orange-50 border border-orange-100 text-orange-700 hover:bg-orange-100', 'btn_edit' => 'bg-amber-50 border border-amber-100 text-amber-700 hover:bg-amber-100'],
                ['from' => 'from-rose-500', 'to' => 'to-pink-600', 'bg' => 'bg-rose-50/50', 'text' => 'text-rose-700', 'border' => 'group-hover:border-rose-300', 'btn_view' => 'bg-rose-50 border border-rose-100 text-rose-700 hover:bg-rose-100', 'btn_edit' => 'bg-amber-50 border border-amber-100 text-amber-700 hover:bg-amber-100'],
                ['from' => 'from-violet-500', 'to' => 'to-purple-650', 'bg' => 'bg-purple-50/50', 'text' => 'text-purple-750', 'border' => 'group-hover:border-purple-300', 'btn_view' => 'bg-purple-50 border border-purple-100 text-purple-750 hover:bg-purple-100', 'btn_edit' => 'bg-amber-50 border border-amber-100 text-amber-700 hover:bg-amber-100'],
                ['from' => 'from-sky-400', 'to' => 'to-blue-600', 'bg' => 'bg-sky-50/50', 'text' => 'text-sky-800', 'border' => 'group-hover:border-sky-300', 'btn_view' => 'bg-sky-50 border border-sky-100 text-sky-800 hover:bg-sky-100', 'btn_edit' => 'bg-amber-50 border border-amber-100 text-amber-700 hover:bg-amber-100']
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            @forelse ($codes as $code)
                @php
                    $color = $gradients[$loop->index % count($gradients)];
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200/80 {{ $color['border'] }} shadow-sm hover:shadow-xl transition-all duration-300 p-6 flex flex-col justify-between group relative overflow-hidden hover:-translate-y-1">
                    
                    <!-- Gradient Top Accent Bar -->
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r {{ $color['from'] }} {{ $color['to'] }}"></div>
                    
                    <!-- Decorative Soft Color Aura inside Card -->
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br {{ $color['from'] }} {{ $color['to'] }} opacity-5 rounded-full blur-xl -mr-6 -mt-6 pointer-events-none"></div>

                    <!-- Top Content -->
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-5">
                            <!-- Code Badge -->
                            <span class="inline-flex items-center px-3.5 py-1.5 text-xs font-black text-white bg-gradient-to-r {{ $color['from'] }} {{ $color['to'] }} rounded-xl shadow-sm tracking-wider uppercase">
                                {{ $code->code }}
                            </span>

                            <!-- Actions (Edit & Delete Icon Buttons on Top Right) -->
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.document-codes.edit', $code->id) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-50 hover:bg-amber-50 text-slate-400 hover:text-amber-700 border border-slate-200/60 hover:border-amber-100 transition duration-150 shadow-sm"
                                    title="Edit Kode">
                                    <i class="bi bi-pencil-fill text-[10px]"></i>
                                </a>

                                <form action="{{ route('admin.document-codes.destroy', $code->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus kode klasifikasi ini? Dokumen terkait akan kehilangan relasi kode.')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-50 hover:bg-rose-50 text-slate-400 hover:text-rose-700 border border-slate-200/60 hover:border-rose-100 transition duration-150 shadow-sm"
                                        title="Hapus Kode">
                                        <i class="bi bi-trash-fill text-[10px]"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Description & Document Info -->
                        <div class="space-y-3.5 mb-6">
                            <div>
                                <span class="text-[9px] font-bold text-slate-450 uppercase tracking-wider block mb-1">Keterangan Klasifikasi</span>
                                <p class="text-slate-700 text-xs font-bold leading-relaxed line-clamp-2 min-h-[36px]" title="{{ $code->description }}">
                                    {{ $code->description ?? 'Tidak ada keterangan tambahan' }}
                                </p>
                            </div>

                            <div class="flex items-center gap-1.5 text-[10px] text-slate-500 font-bold bg-slate-50/50 border border-slate-100 px-3 py-1.5 rounded-xl w-fit shadow-inner">
                                <i class="bi bi-files text-[11px] text-slate-400"></i>
                                <span>Terhubung dengan <strong class="text-slate-800 font-extrabold text-xs ml-0.5">{{ $code->documents_count }}</strong> dokumen</span>
                            </div>
                        </div>

                        <!-- Progress Bar Visual Indicator -->
                        <div class="space-y-1.5 mb-2">
                            <div class="flex justify-between items-center text-[9px] font-bold text-slate-400 uppercase tracking-wide">
                                <span>Rasio Penggunaan Kode Dokumen</span>
                                <span>{{ min($code->documents_count * 10, 100) }}%</span>
                            </div>
                            <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r {{ $color['from'] }} {{ $color['to'] }} rounded-full transition-all duration-300" 
                                    style="width: {{ min($code->documents_count * 10, 100) }}%">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Action Button -->
                    <div class="mt-6 pt-4 border-t border-slate-100 relative">
                        <a href="{{ route('admin.document-codes.show', $code->id) }}"
                            class="w-full inline-flex items-center justify-center gap-1.5 text-center {{ $color['btn_view'] }} py-2.5 rounded-xl text-xs font-bold transition duration-150 shadow-sm"
                            title="Detail Dokumen">
                            <i class="bi bi-eye-fill text-[11px]"></i>
                            Lihat Detail Dokumen
                        </a>
                    </div>

                </div>

            @empty
                <div class="col-span-full text-center py-16 bg-white border border-slate-200/80 rounded-2xl shadow-sm text-slate-400 font-medium text-xs">
                    <i class="bi bi-inbox text-3xl block text-slate-350 mb-2"></i>
                    Belum ada kode dokumen klasifikasi terdaftar
                </div>
            @endforelse

        </div>
    </div>
@endsection
