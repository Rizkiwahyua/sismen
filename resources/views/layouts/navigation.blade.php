@php
    $dashboardRoute = auth()->user()->role === 'admin'
        ? 'admin.dashboard'
        : 'user.dashboard';
@endphp

<nav x-data="{ open: false, showHelpModal: false }" class="bg-[#10a362] border-b border-[#0c8c53] sticky top-0 z-40 text-white shadow-md">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Left Side: Breadcrumb & Workspace Title -->
            <div class="flex items-center gap-3.5">
                <!-- Sidebar Toggle Button -->
                <button @click="window.innerWidth >= 1024 ? sidebarCollapsed = !sidebarCollapsed : sidebarOpen = !sidebarOpen" 
                        type="button" 
                        class="w-9 h-9 rounded-xl border border-white/20 bg-white/10 text-white hover:bg-white/20 shadow-sm flex items-center justify-center transition focus:outline-none"
                        title="Toggle Sidebar">
                    <i class="bi text-lg transition-transform duration-200"
                       :class="sidebarCollapsed ? 'bi-text-indent-left' : 'bi-list'"></i>
                </button>

                <div class="flex items-center gap-2 text-xs font-semibold text-emerald-200/90">
                    <span class="hover:text-white transition cursor-default">Sistem Dokumen</span>
                    <span class="text-emerald-300/40">/</span>
                    <span class="text-white font-bold">
                        {{ auth()->user()->role === 'admin' ? 'Konsol Admin' : 'Halaman User' }}
                    </span>
                </div>
            </div>

            <!-- Right Side: Quick Actions (Visible on all devices) -->
            <div class="flex items-center gap-2 sm:gap-3.5">
                <!-- Quick Icon: Notifications (Static Decorative) -->
                <button class="w-8 h-8 rounded-xl flex items-center justify-center text-emerald-100 hover:text-white hover:bg-white/10 transition border border-transparent hover:border-white/10">
                    <i class="bi bi-bell text-sm"></i>
                </button>

                <!-- Quick Icon: Help Guide -->
                <button @click="showHelpModal = true" class="w-8 h-8 rounded-xl flex items-center justify-center text-emerald-100 hover:text-white hover:bg-white/10 transition border border-transparent hover:border-white/10" title="Tentang Sistem & Fitur">
                    <i class="bi bi-question-circle text-sm"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Help & About System Modal -->
    <div x-show="showHelpModal"
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showHelpModal = false"></div>
        
        <!-- Modal Content Card -->
        <div class="bg-white text-slate-700 rounded-2xl shadow-xl border border-slate-200 w-full max-w-3xl overflow-hidden relative z-10 transition-all transform duration-300"
             x-show="showHelpModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-250"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
             
            <!-- Modal Header -->
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-emerald-50/50 to-teal-50/30">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-[#10a362]">
                        <i class="bi bi-info-circle text-base"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 tracking-tight">Dokumentasi & Panduan Sistem SMTI</h3>
                </div>
                <button @click="showHelpModal = false" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 max-h-[70vh] overflow-y-auto space-y-6">
                <!-- Explanation (Penjelasan Lengkap) -->
                <div class="space-y-2.5">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Penjelasan & Tujuan Sistem</h4>
                    <p class="text-xs text-slate-650 leading-relaxed">
                        <strong>Sistem Manajemen Dokumen SMTI</strong> (Rizkiwahyua/newdid) adalah platform repositori digital tingkat lanjut (*enterprise-grade*) yang dirancang khusus untuk digitalisasi berkas, pengarsipan, pelacakan audit, dan otentikasi dokumen perusahaan secara efisien. Sistem ini bertujuan untuk mengeliminasi alur kerja kertas manual yang berisiko, meminimalisir hilangnya dokumen fisik melalui kartu kontrol kelengkapan berkas (*upload gaps summary*), dan menjamin keaslian salinan PDF ketika dibagikan menggunakan fitur tanda air (*watermark*) dinamis yang diproses secara *real-time* langsung dari server.
                    </p>
                </div>

                <!-- User Roles & Access Rights (Hak Akses Pengguna) -->
                <div class="space-y-2.5">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Hak Akses & Pembagian Peran (Roles)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Admin Role Card -->
                        <div class="p-4 border border-emerald-100 bg-emerald-50/30 rounded-xl space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-md bg-[#10a362] text-white text-[9px] font-bold uppercase tracking-wider flex items-center gap-1 shadow-sm">
                                    <i class="bi bi-shield-fill-check"></i>Admin (Administrator)
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-650 leading-relaxed">
                                Memiliki otoritas penuh terhadap sistem. Otoritas mencakup pengunggahan berkas baru, penyuntingan meta-data, penghapusan sementara ( Recycle Bin ), pemulihan arsip, penghapusan berkas permanen, pengaturan pembagian unit kerja (Departemen), penetapan kode arsip dokumen, serta manajemen pembuatan akun pengguna.
                            </p>
                        </div>
                        
                        <!-- User Role Card -->
                        <div class="p-4 border border-emerald-100 bg-emerald-50/10 rounded-xl space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-md bg-emerald-600 text-white text-[9px] font-bold uppercase tracking-wider flex items-center gap-1 shadow-sm">
                                    <i class="bi bi-person-fill"></i>User (Staf / Pegawai)
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-650 leading-relaxed">
                                Memiliki hak akses terbatas yang disesuaikan untuk keperluan operasional. Pengguna Staf dapat memantau ringkasan statistik pada dashboard pengguna dan mengelola informasi profil pribadi (seperti mengubah nama, email, mengganti foto profil, serta memperbarui kata sandi secara mandiri).
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Feature List Table -->
                <div class="space-y-2.5">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Daftar Fitur & Keterangan Lengkap</h4>
                    <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider">
                                    <th class="px-4 py-3 w-1/3">Fitur Utama</th>
                                    <th class="px-4 py-3">Keterangan & Fungsi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-150 text-slate-700">
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-4 py-3 font-bold text-slate-800 flex items-center gap-2">
                                        <i class="bi bi-speedometer2 text-[#10a362] text-sm"></i>
                                        Dashboard Statistik
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 leading-normal">
                                        Menyajikan ringkasan visual real-time seperti total berkas aktif, jumlah dokumen per kategori (Ratifikasi, Pedoman, Prosedur, Instruksi Kerja, Formulir), metrik unit kerja, dan peringatan dokumen kosong.
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-4 py-3 font-bold text-slate-800 flex items-center gap-2">
                                        <i class="bi bi-file-earmark-text text-[#10a362] text-sm"></i>
                                        Manajemen Berkas
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 leading-normal">
                                        Memfasilitasi pengunggahan berkas secara aman dengan pencatatan nomor dokumen otomatis, penetapan nomor revisi (Rev 0, 1, dst), tanggal berlaku, serta deskripsi kelengkapan.
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-4 py-3 font-bold text-slate-800 flex items-center gap-2">
                                        <i class="bi bi-eye text-[#10a362] text-sm"></i>
                                        Pratinjau PDF & Watermark
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 leading-normal">
                                        Membaca dokumen PDF secara inline di peramban tanpa memaksa unduhan otomatis. Sistem menempelkan teks tanda air (*watermark*) dinamis di atas lembar dokumen untuk mencegah penyebaran salinan asli.
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-4 py-3 font-bold text-slate-800 flex items-center gap-2">
                                        <i class="bi bi-funnel text-[#10a362] text-sm"></i>
                                        Filter & Rekapitulasi
                                    </td>
                                    <td class="px-4 py-3 text-slate-650 leading-normal">
                                        Modul pencarian mutakhir yang dapat menyaring berkas berdasarkan pencarian teks, rentang tanggal pembuatan, unit kerja terkait, status kelengkapan file (ada file / belum upload), serta kode arsip.
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-4 py-3 font-bold text-slate-800 flex items-center gap-2">
                                        <i class="bi bi-file-earmark-excel text-[#10a362] text-sm"></i>
                                        Ekspor Excel Premium
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 leading-normal">
                                        Konversi hasil rekap data aktif maupun sampah menjadi lembar kerja Excel siap cetak yang menggunakan gaya Segoe UI, kop judul, data filter, zebra striping, dan garis akuntansi total.
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-4 py-3 font-bold text-slate-800 flex items-center gap-2">
                                        <i class="bi bi-clock-history text-[#10a362] text-sm"></i>
                                        Audit Linimasa (Timeline)
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 leading-normal">
                                        Menampilkan jejak rekam modifikasi berkas, meliputi tanggal unggahan perdana (Rev 0) beserta nama pengunggah dan tanggal revisi terbaru beserta nama penyunting berkas.
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-4 py-3 font-bold text-slate-800 flex items-center gap-2">
                                        <i class="bi bi-trash3 text-[#10a362] text-sm"></i>
                                        Kotak Sampah (Recycle Bin)
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 leading-normal">
                                        Modul penampungan dokumen yang dihapus sementara. Admin dapat memulihkan berkas ke posisi semula (*restore*) atau menghapusnya secara permanen dari server lokal.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Support Contacts (Kontak Bantuan WhatsApp) -->
                <div class="space-y-2.5 pt-2 border-t border-slate-100">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kontak Hubungi (Tanya Sistem)</h4>
                    <p class="text-xs text-slate-500 mb-3">Apabila Anda mengalami kesulitan atau memiliki pertanyaan seputar operasional sistem dokumen ini, Anda dapat menghubungi tim teknis kami via WhatsApp:</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Pak Iwan Contact -->
                        <a href="https://wa.me/6281360277277" 
                           target="_blank" 
                           class="inline-flex items-center justify-between p-3 border border-emerald-200/70 bg-emerald-50/40 hover:bg-emerald-50 rounded-xl transition duration-200 group shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-8.5 h-8.5 rounded-lg bg-emerald-500 text-white flex items-center justify-center shadow-md shadow-emerald-500/20 flex-shrink-0 text-base">
                                    <i class="bi bi-whatsapp"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-xs font-bold text-slate-800 leading-tight">Pak Iwan</p>
                                    <p class="text-[10px] text-slate-500 mt-0.5">0813-6027-7277</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-100/50 px-2 py-0.5 rounded-lg transition duration-250 opacity-80 group-hover:opacity-100">
                                Chat <i class="bi bi-box-arrow-up-right text-[8px]"></i>
                            </span>
                        </a>
                        
                        <!-- Bang Wahyu Contact -->
                        <a href="https://wa.me/6282391008891" 
                           target="_blank" 
                           class="inline-flex items-center justify-between p-3 border border-emerald-200/70 bg-emerald-50/40 hover:bg-emerald-50 rounded-xl transition duration-200 group shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-8.5 h-8.5 rounded-lg bg-emerald-500 text-white flex items-center justify-center shadow-md shadow-emerald-500/20 flex-shrink-0 text-base">
                                    <i class="bi bi-whatsapp"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-xs font-bold text-slate-800 leading-tight">Bang Wahyu</p>
                                    <p class="text-[10px] text-slate-500 mt-0.5">0823-9100-8891</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-100/50 px-2 py-0.5 rounded-lg transition duration-250 opacity-80 group-hover:opacity-100">
                                Chat <i class="bi bi-box-arrow-up-right text-[8px]"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-end gap-2">
                <button @click="showHelpModal = false"
                        class="px-4.5 py-2 bg-[#10a362] hover:bg-[#0c8c53] text-white text-xs font-bold rounded-xl shadow-sm transition">
                    Tutup Panduan
                </button>
            </div>
        </div>
    </div>
</nav>
