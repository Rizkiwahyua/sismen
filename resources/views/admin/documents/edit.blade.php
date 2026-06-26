@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto pb-12">

        <!-- Navigation / Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">Edit Dokumen</h2>
                <p class="text-xs text-slate-400 mt-0.5">Ubah informasi detail dokumen di bawah ini</p>
            </div>

            <a href="{{ route('admin.documents.index') }}"
                class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-4 py-2.5 rounded-xl transition duration-150 shadow-sm border border-slate-200/40">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>

        <!-- Form Container -->
        <form method="POST" action="{{ route('admin.documents.update', $document->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8 space-y-6">
                <!-- Two Column Form Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- LEFT COLUMN -->
                    <div class="space-y-5">
                        <!-- Judul Dokumen -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Judul Dokumen <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title', $document->title) }}" 
                                class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 text-slate-800 transition duration-150"
                                placeholder="Masukkan judul dokumen..." required>
                        </div>

                        <!-- Kategori Dokumen -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Kategori Dokumen <span class="text-rose-500">*</span>
                            </label>
                            <select name="document_category_id"
                                class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 text-slate-700 transition duration-150 cursor-pointer">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('document_category_id', $document->document_category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kode Dokumen -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Kode Dokumen <span class="text-rose-500">*</span>
                            </label>
                            <select name="document_code_id"
                                class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 text-slate-700 transition duration-150 cursor-pointer">
                                @foreach ($codes as $code)
                                    <option value="{{ $code->id }}" {{ old('document_code_id', $document->document_code_id) == $code->id ? 'selected' : '' }}>
                                        {{ $code->code }} - {{ $code->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="space-y-5">
                        <!-- Unit Kerja -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Unit Kerja Pemilik <span class="text-rose-500">*</span>
                            </label>
                            <select name="department_id"
                                class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 text-slate-700 transition duration-150 cursor-pointer">
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $document->department_id) == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Revisi -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Revisi
                            </label>
                            <input type="text" name="revision" value="{{ old('revision', $document->revision) }}"
                                class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 text-slate-800 transition duration-150"
                                placeholder="Contoh: 0 atau 1">
                        </div>

                        <!-- Tanggal Dokumen -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Tanggal Dokumen
                            </label>
                            <input type="date" name="document_date" value="{{ old('document_date', $document->document_date) }}"
                                class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 text-slate-700 transition duration-150 cursor-pointer">
                        </div>

                        <!-- File Dokumen (Custom Dropzone) -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Berkas Dokumen
                            </label>

                            <!-- Old File Indicator -->
                            @if ($document->file_document)
                                <div class="mb-3 bg-emerald-50/60 border border-emerald-100 rounded-xl p-3 flex items-center justify-between text-xs text-emerald-800 font-semibold shadow-sm">
                                    <span class="flex items-center gap-1.5 truncate">
                                        <i class="bi bi-file-earmark-check-fill text-emerald-500 text-sm"></i>
                                        <span class="truncate">Berkas terunggah saat ini</span>
                                    </span>
                                    <a href="{{ asset($document->file_document) }}" target="_blank"
                                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold shadow-sm transition flex items-center gap-1 whitespace-nowrap">
                                        <i class="bi bi-eye"></i> Lihat Berkas
                                    </a>
                                </div>
                            @endif

                            <label for="file_document"
                                class="border-2 border-dashed border-slate-200 hover:border-indigo-400 rounded-2xl p-5 bg-slate-50/50 hover:bg-indigo-50/10 cursor-pointer transition flex flex-col items-center justify-center gap-1.5 group select-none">
                                <i class="bi bi-cloud-arrow-up text-2xl text-slate-400 group-hover:text-indigo-500 transition duration-150"></i>
                                <span class="text-xs font-bold text-slate-700 group-hover:text-indigo-650">Unggah Berkas Baru</span>
                                <span class="text-[10px] text-slate-400 font-semibold">Pilih file untuk mengganti berkas sebelumnya (Maks. 50MB)</span>
                                
                                <input type="file" name="file_document" id="file_document" class="hidden" onchange="updateFileName(this)">
                            </label>

                            <!-- Selected File Indicator -->
                            <div id="file-name-preview" class="hidden w-full mt-2 bg-indigo-50/60 border border-indigo-100/50 rounded-xl px-4 py-2 flex items-center justify-between text-xs text-indigo-700 font-bold transition duration-150 shadow-sm">
                                <span class="truncate pr-4 flex items-center gap-1.5">
                                    <i class="bi bi-file-earmark-plus-fill text-indigo-500 text-sm"></i>
                                    <span id="file-name-text" class="truncate font-bold"></span>
                                </span>
                                <button type="button" onclick="removeSelectedFile()" class="text-indigo-900 hover:text-rose-600 transition" title="Batalkan unggahan">
                                    <i class="bi bi-x-circle-fill text-base"></i>
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Nomor Dokumen Generator Row -->
                    <div class="col-span-1 md:col-span-2 bg-slate-50/50 border border-slate-200/60 rounded-xl p-4.5 space-y-3">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            Nomor Dokumen Generator <span class="text-rose-500">*</span>
                        </label>
                        
                        <div class="flex flex-col sm:flex-row items-stretch gap-2">
                            <!-- Prefix Input -->
                            <div class="flex-1">
                                <input type="text" name="document_number_prefix" id="doc_num_prefix" 
                                    value="{{ old('document_number_prefix', $document->document_number_prefix ?? 'PI-SMT') }}"
                                    class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 text-slate-800 transition"
                                    placeholder="Prefix (contoh: PI-SMT)" oninput="updateDocNumberPreview()">
                            </div>
                            
                            <!-- Separator -->
                            <div class="hidden sm:flex items-center text-slate-400 font-bold px-1 text-sm">-</div>
                            
                            <!-- Code Display -->
                            <div class="w-full sm:w-32">
                                <input type="text" id="doc_num_code" 
                                    value="" 
                                    class="w-full px-3 py-2 text-xs bg-slate-100 border border-slate-200 rounded-lg text-slate-500 font-bold text-center cursor-not-allowed" 
                                    readonly placeholder="KODE">
                            </div>
                            
                            <!-- Separator -->
                            <div class="hidden sm:flex items-center text-slate-400 font-bold px-1 text-sm">-</div>
                            
                            <!-- Suffix Input -->
                            <div class="flex-1">
                                <input type="text" name="document_number_suffix" id="doc_num_suffix" 
                                    value="{{ old('document_number_suffix', $document->document_number_suffix) }}"
                                    class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 text-slate-800 transition"
                                    placeholder="Suffix / Nomor (contoh: 007)" oninput="updateDocNumberPreview()">
                            </div>
                        </div>
                        
                        <!-- Real-time Preview Box -->
                        <div class="text-[11px] text-slate-550 font-semibold flex items-center gap-1.5 bg-white border border-slate-200/50 rounded-lg px-3 py-2">
                            <span class="text-slate-400">Pratinjau Nomor:</span>
                            <span id="doc_num_preview" class="text-indigo-655 font-bold tracking-wider"></span>
                        </div>
                    </div>
                </div>

                <!-- Full Width: Keterangan / Deskripsi -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                        Deskripsi / Keterangan Dokumen
                    </label>
                    <textarea name="description"
                        class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 text-slate-800 transition duration-150 h-24 resize-y"
                        placeholder="Tuliskan keterangan detail mengenai dokumen ini...">{{ old('description', $document->description) }}</textarea>
                </div>

                <!-- SUB DETAIL SECTION -->
                <div id="subDetailSection" class="space-y-4 pt-4 border-t border-slate-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="bi bi-list-task text-indigo-500"></i> Daftar Sub Judul / Uraian Detail
                            </h4>
                            <p class="text-[10px] text-slate-400 font-medium">Sub judul/uraian yang sudah tersimpan untuk dokumen ini</p>
                        </div>
                        <button type="button" onclick="addSub()"
                            class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-650 border border-indigo-100 hover:bg-indigo-100/80 px-3 py-1.5 rounded-lg text-xs font-bold transition">
                            <i class="bi bi-plus-lg"></i>
                            Tambah Baris Baru
                        </button>
                    </div>

                    <!-- Existing Sub-titles List -->
                    @if ($document->details->count() > 0)
                        <div class="space-y-2.5">
                            @foreach ($document->details as $index => $d)
                                <div class="flex flex-col sm:flex-row gap-3 bg-indigo-50/20 border border-indigo-100/50 rounded-xl p-4 relative group">
                                    
                                    <!-- Sub Judul -->
                                    <div class="flex-1">
                                        <label class="block text-[9px] font-bold text-indigo-550 uppercase tracking-wider mb-1">Sub Judul / Uraian</label>
                                        <input type="text" name="existing_sub_title[{{ $d->id }}]" value="{{ $d->sub_title }}"
                                            class="w-full px-3 py-2 text-xs border border-indigo-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 text-slate-800 bg-white transition shadow-sm">
                                    </div>

                                    <!-- Button Pilih Unit Kerja -->
                                    <div class="w-full sm:w-60">
                                        <label class="block text-[9px] font-bold text-indigo-550 uppercase tracking-wider mb-1">Unit Kerja Terkait</label>
                                        <button type="button" onclick="openDepartmentModal('existing_{{ $d->id }}')"
                                            class="border border-indigo-200 px-3 py-2 rounded-lg w-full text-left bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-sm flex justify-between items-center">
                                            <span>Pilih Unit Kerja</span>
                                            <i class="bi bi-chevron-down text-[10px] text-slate-400"></i>
                                        </button>
                                        @php
                                            $deptIds = [];
                                            if (!empty($d->department_ids)) {
                                                $deptIds = is_array($d->department_ids) ? $d->department_ids : json_decode($d->department_ids, true);
                                            }
                                        @endphp
                                        <input type="hidden" name="existing_sub_department[{{ $d->id }}]" id="sub_department_existing_{{ $d->id }}" value="{{ json_encode($deptIds) }}">
                                        <div id="selected_departments_existing_{{ $d->id }}" class="flex flex-wrap gap-1 mt-1.5">
                                            @php
                                                $deptNames = \App\Models\Department::whereIn('id', $deptIds)->pluck('name')->toArray();
                                            @endphp
                                            @foreach ($deptNames as $name)
                                                <span class="bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded text-[9px] font-bold text-indigo-700 shadow-sm">{{ $name }}</span>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Keterangan Sub -->
                                    <div class="flex-1">
                                        <label class="block text-[9px] font-bold text-indigo-550 uppercase tracking-wider mb-1">Keterangan Tambahan</label>
                                        <input type="text" name="existing_sub_description[{{ $d->id }}]" value="{{ $d->description }}"
                                            class="w-full px-3 py-2 text-xs border border-indigo-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 text-slate-800 bg-white transition shadow-sm">
                                    </div>
                                    
                                    <!-- Delete Existing Subdetail Action -->
                                    <button type="button" onclick="deleteExistingSub({{ $d->id }})"
                                        class="w-10 h-10 self-end flex items-center justify-center rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100 hover:border-rose-200 transition shadow-sm"
                                        title="Hapus permanen sub judul ini">
                                        <i class="bi bi-trash text-sm"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 bg-slate-50 border border-slate-200/40 rounded-xl text-xs text-slate-400 font-medium">
                            Belum ada sub judul pada dokumen ini. Gunakan tombol di atas untuk menambahkan.
                        </div>
                    @endif

                    <!-- Dynamic Container for NEW sub-titles -->
                    <div id="subContainer" class="space-y-3.5 mt-4">
                        <!-- Dynamic rows injected here -->
                    </div>
                </div>

                <!-- Actions row -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.documents.index') }}"
                        class="px-5 py-2.5 text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition duration-150 shadow-sm border border-slate-200/40">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-xs font-bold px-6 py-2.5 rounded-xl shadow-sm transition-all duration-150 hover:shadow">
                        Simpan Perubahan
                    </button>
                </div>

            </div>
        </form>
    </div>

    <!-- SHARED DEPARTMENT SELECTION MODAL -->
    <div id="departmentModal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden z-50 items-center justify-center transition-all duration-205">
        <div class="bg-white w-[480px] rounded-2xl border border-slate-200 shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
            
            <!-- Modal Header -->
            <div class="px-6 py-4.5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Pilih Unit Kerja Terkait</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Centang satu atau beberapa unit kerja terkait</p>
                </div>
                <button type="button" onclick="closeDepartmentModal()" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>

            <!-- Modal Content (Scrollable) -->
            <div class="p-6 max-h-[300px] overflow-y-auto space-y-2">
                @foreach ($departments as $dept)
                    <label class="flex items-center gap-3 px-3 py-2 border border-slate-200/50 hover:bg-slate-50 rounded-xl transition cursor-pointer select-none">
                        <input type="checkbox" class="dept-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/20"
                            value="{{ $dept->id }}" data-name="{{ $dept->name }}">
                        <span class="text-xs font-semibold text-slate-700">{{ $dept->name }}</span>
                    </label>
                @endforeach
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-2.5">
                <button type="button" onclick="closeDepartmentModal()"
                    class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-sm">
                    Batal
                </button>
                <button type="button" onclick="saveDepartments()"
                    class="px-5 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-xl text-xs font-bold shadow-sm transition hover:shadow">
                    Simpan Pilihan
                </button>
            </div>

        </div>
    </div>

    <!-- Hidden Form for Deleting Existing Sub-detail -->
    <form id="delete-sub-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- Scripts -->
    <script>
        // Custom File Name Preview helpers
        function updateFileName(input) {
            const preview = document.getElementById('file-name-preview');
            const text = document.getElementById('file-name-text');
            if (input.files && input.files.length > 0) {
                text.innerText = input.files[0].name;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        }

        function removeSelectedFile() {
            const input = document.getElementById('file_document');
            input.value = '';
            const preview = document.getElementById('file-name-preview');
            preview.classList.add('hidden');
        }

        // Live Document Number Preview Generator
        function updateDocNumberPreview() {
            const prefix = document.getElementById('doc_num_prefix').value.trim();
            const suffix = document.getElementById('doc_num_suffix').value.trim();
            
            const codeSelect = document.querySelector('select[name="document_code_id"]');
            let codeStr = '';
            if (codeSelect && codeSelect.selectedIndex >= 0) {
                const selectedText = codeSelect.options[codeSelect.selectedIndex].text;
                codeStr = selectedText.split(' - ')[0].trim();
            }
            
            document.getElementById('doc_num_code').value = codeStr;
            
            let parts = [];
            if (prefix) parts.push(prefix);
            if (codeStr) parts.push(codeStr);
            if (suffix) parts.push(suffix);
            
            document.getElementById('doc_num_preview').innerText = parts.join('-');
        }

        document.addEventListener("DOMContentLoaded", () => {
            const codeSelect = document.querySelector('select[name="document_code_id"]');
            if (codeSelect) {
                codeSelect.addEventListener('change', updateDocNumberPreview);
            }
            updateDocNumberPreview();
        });

        // Dynamic Sub Titles Row Management
        let subIndex = 1;

        function addSub() {
            const container = document.getElementById('subContainer');
            const html = `
                <div class="flex flex-col sm:flex-row gap-3 bg-slate-50/50 border border-slate-200/60 rounded-xl p-4 relative group animate-in fade-in slide-in-from-top-2 duration-150">
                    
                    <!-- Sub Judul -->
                    <div class="flex-1">
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Sub Judul / Uraian</label>
                        <input type="text" name="sub_title[${subIndex}]" placeholder="Masukkan sub judul..."
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 text-slate-800 bg-white transition shadow-sm">
                    </div>

                    <!-- Button Pilih Unit Kerja -->
                    <div class="w-full sm:w-60">
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Unit Kerja Terkait</label>
                        <button type="button" onclick="openDepartmentModal(${subIndex})"
                            class="border border-slate-200 px-3 py-2 rounded-lg w-full text-left bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-sm flex justify-between items-center">
                            <span>Pilih Unit Kerja</span>
                            <i class="bi bi-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <input type="hidden" name="sub_department[${subIndex}]" id="sub_department_${subIndex}" value="[]">
                        <div id="selected_departments_${subIndex}" class="flex flex-wrap gap-1 mt-1.5">
                            <!-- Dynamic badges list -->
                        </div>
                    </div>

                    <!-- Keterangan Sub -->
                    <div class="flex-1">
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Keterangan Tambahan</label>
                        <input type="text" name="sub_description[${subIndex}]" placeholder="Keterangan singkat..."
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 text-slate-800 bg-white transition shadow-sm">
                    </div>

                    <!-- Delete Row Button -->
                    <button type="button" onclick="this.parentElement.remove()"
                        class="w-10 h-10 self-end flex items-center justify-center rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100 hover:border-rose-200 transition shadow-sm"
                        title="Hapus sub judul">
                        <i class="bi bi-trash text-sm"></i>
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            subIndex++;
        }

        // Shared Department Selection Modal Logic
        let currentIndex = 0;

        function openDepartmentModal(index) {
            currentIndex = index;
            
            // Clear checked state
            document.querySelectorAll('.dept-checkbox').forEach(cb => cb.checked = false);
            
            // Parse existing selection
            const inputVal = document.getElementById('sub_department_' + index).value;
            if (inputVal) {
                try {
                    const ids = JSON.parse(inputVal);
                    ids.forEach(id => {
                        const cb = document.querySelector(`.dept-checkbox[value="${id}"]`);
                        if (cb) cb.checked = true;
                    });
                } catch(e) {
                    console.error("Failed to parse department IDs:", e);
                }
            }

            const modal = document.getElementById('departmentModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDepartmentModal() {
            const modal = document.getElementById('departmentModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        function saveDepartments() {
            const checked = document.querySelectorAll('.dept-checkbox:checked');
            const ids = [];
            const names = [];

            checked.forEach(item => {
                ids.push(item.value);
                names.push(item.dataset.name);
            });

            // Update input and badges
            document.getElementById('sub_department_' + currentIndex).value = JSON.stringify(ids);
            
            const badgesHtml = names.map(name => 
                `<span class="inline-flex items-center bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded text-[9px] font-bold text-indigo-700 shadow-sm">${name}</span>`
            ).join('');
            
            document.getElementById('selected_departments_' + currentIndex).innerHTML = badgesHtml;

            closeDepartmentModal();
        }

        // Delete Existing Sub-detail
        function deleteExistingSub(id) {
            if (confirm('Apakah Anda yakin ingin menghapus sub judul ini secara permanen?')) {
                const form = document.getElementById('delete-sub-form');
                form.action = '/sub-detail/' + id;
                form.submit();
            }
        }
    </script>
@endsection
