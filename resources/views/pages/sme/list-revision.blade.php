@extends('layouts.backoffice')

@section('sidebar-nav')
    @include('partials.sme-sidebar')
@endsection

@section('title', 'Daftar Revisi Materi Pelatihan')

@section('page-title', 'Revisi Materi')

@section('content')
<div class="space-y-8" x-data="{
    searchQuery: '',
    selectedCategory: 'all',
    noteModalOpen: false,
    activeNoteTitle: '',
    activeNoteContent: '',
    activeNoteDocs: [],

    submissionModalOpen: false,
    activeSubmissionId: '',
    activeSubmissionTitle: '',
    revisionNotes: '',
    selectedFile: null,
    isDragging: false,
    isSubmitting: false,
    
    openNoteModal(title, content, docs) {
        this.activeNoteTitle = title;
        this.activeNoteContent = content || 'Tidak ada catatan spesifik dari Learning Administrator.';
        try {
            this.activeNoteDocs = typeof docs === 'string' ? JSON.parse(docs) : docs;
        } catch(e) {
            this.activeNoteDocs = [];
        }
        this.noteModalOpen = true;
    },

    openSubmissionModal(id, title) {
        this.activeSubmissionId = id;
        this.activeSubmissionTitle = title;
        this.revisionNotes = '';
        this.selectedFile = null;
        this.submissionModalOpen = true;
    },

    handleFileDrop(e) {
        this.isDragging = false;
        if (e.dataTransfer.files.length > 0) {
            this.selectedFile = e.dataTransfer.files[0];
        }
    },

    handleFileSelect(e) {
        if (e.target.files.length > 0) {
            this.selectedFile = e.target.files[0];
        }
    },

    async submitRevision() {
        if (!this.revisionNotes.trim()) {
            alert('Mohon isi catatan penyerahan revisi terlebih dahulu.');
            return;
        }
        this.isSubmitting = true;
        try {
            let formData = new FormData();
            formData.append('material_notes', this.revisionNotes);
            formData.append('_token', '{{ csrf_token() }}');
            if (this.selectedFile) {
                formData.append('file_name', this.selectedFile.name);
            }

            let response = await fetch(`/sme/blueprint/${this.activeSubmissionId}/submit`, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });

            let result = await response.json();
            if (result.success) {
                alert('Berhasil! Materi revisi dan catatan perbaikan telah diserahkan ke Admin.');
                window.location.reload();
            } else {
                alert(result.message || 'Terjadi kesalahan saat mengirim revisi.');
                this.isSubmitting = false;
            }
        } catch (e) {
            alert('Terjadi kesalahan koneksi saat mengirim revisi.');
            this.isSubmitting = false;
        }
    },

    filterBlueprint(title, category) {
        let matchSearch = title.toLowerCase().includes(this.searchQuery.toLowerCase());
        let matchCat = this.selectedCategory === 'all' || category === this.selectedCategory;
        return matchSearch && matchCat;
    }
}">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0 border border-amber-100">
                <span class="material-symbols-outlined text-3xl">rule_folder</span>
            </div>
            <div>
                <h1 class="text-2xl font-black text-on-surface tracking-tight uppercase">Daftar Revisi Materi Pelatihan</h1>
                <p class="text-xs font-bold text-zinc-500 mt-1">Periksa catatan perbaikan dari Learning Administrator (CLD) dan perbarui materi atau dokumen blueprint Anda.</p>
            </div>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('sme.dashboard') }}" class="px-6 py-3 bg-zinc-100 text-zinc-600 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-zinc-200 transition-all flex items-center gap-2 flex-shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-sm">dashboard</span>
                SME Dashboard
            </a>
        </div>
    </div>

    {{-- Filter & Search Bar --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-6 bg-white p-6 rounded-[2rem] border border-zinc-100 shadow-sm">
        {{-- Search Input --}}
        <div class="relative w-full md:w-96">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 text-lg">search</span>
            <input type="text" x-model="searchQuery" placeholder="Cari judul blueprint atau kode..." class="w-full pl-11 pr-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-xs font-medium text-zinc-800 placeholder-zinc-400 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
        </div>

        {{-- Category Dropdown --}}
        <div class="flex items-center gap-3 w-full md:w-auto">
            <span class="text-xs font-bold text-zinc-500 flex-shrink-0">Filter Kategori:</span>
            <select x-model="selectedCategory" class="w-full md:w-auto px-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-xs font-bold text-zinc-700 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all cursor-pointer">
                <option value="all">Semua Kategori</option>
                @php
                    $categories = collect($blueprints ?? [])->pluck('category')->unique();
                @endphp
                @foreach($categories as $cat)
                <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Blueprints Table Card --}}
    <div class="bg-white rounded-[2.5rem] border border-zinc-100 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-zinc-50/50 border-b border-zinc-100">
                    <th class="px-8 py-5 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Informasi Blueprint</th>
                    <th class="px-8 py-5 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Catatan Revisi & Dokumen</th>
                    <th class="px-8 py-5 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Tenggat Waktu</th>
                    <th class="px-8 py-5 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50">
                @forelse($blueprints ?? [] as $bp)
                <tr x-show="filterBlueprint('{{ addslashes($bp->title . ' ' . $bp->id) }}', '{{ addslashes($bp->category) }}')" x-transition class="hover:bg-zinc-50/30 transition-colors group cursor-default">
                    <td class="px-8 py-6 w-5/12">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 group-hover:bg-primary/10 group-hover:text-primary transition-colors flex-shrink-0 shadow-sm border border-red-100 group-hover:border-primary/20">
                                <span class="material-symbols-outlined text-2xl">rule_folder</span>
                            </div>
                            <div>
                                <p class="font-black text-on-surface group-hover:text-primary transition-colors leading-tight text-sm uppercase">{{ $bp->title }}</p>
                                <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest mt-1">Kode: <span class="text-zinc-600">{{ $bp->id }}</span> • Kategori: <span class="text-blue-600">{{ $bp->category }}</span></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6 w-1/4">
                        <button @click="openNoteModal('{{ addslashes($bp->title) }}', '{{ addslashes($bp->cld_review_notes) }}', '{{ addslashes(json_encode($bp->supporting_documents ?? [])) }}')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-2xl hover:bg-amber-100 transition-all text-xs font-bold shadow-2xs active:scale-95 group">
                            <span class="material-symbols-outlined text-base text-amber-500 group-hover:scale-110 transition-transform">rate_review</span>
                            <span>Lihat Catatan & Dokumen</span>
                        </button>
                    </td>
                    <td class="px-8 py-6 whitespace-nowrap">
                        <div class="flex items-center gap-2 text-xs font-bold text-red-600 bg-red-50 px-3 py-1.5 rounded-xl border border-red-100 w-max">
                            <span class="material-symbols-outlined text-base">warning</span>
                            {{ $bp->deadline ? \Carbon\Carbon::parse($bp->deadline)->translatedFormat('d F Y') : 'Segera' }}
                        </div>
                    </td>
                    <td class="px-8 py-6 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('sme.blueprint.show', $bp->id) }}" class="px-4 py-2.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all active:scale-95 flex items-center gap-1.5" title="Buka Halaman Review">
                                <span class="material-symbols-outlined text-sm">open_in_new</span>
                                Review
                            </a>
                            <button @click="openSubmissionModal('{{ $bp->id }}', '{{ addslashes($bp->title) }}')" class="px-6 py-2.5 bg-amber-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-amber-600 shadow-lg shadow-amber-500/20 hover:shadow-amber-500/30 transition-all active:scale-95 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm">upload_file</span>
                                Serahkan Perbaikan
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-12 text-center text-zinc-400 text-xs italic bg-zinc-50/50">
                        Tidak ada blueprint yang membutuhkan revisi saat ini. Kerja bagus!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Detail Catatan Revisi & Dokumen Modal --}}
    <div x-show="noteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-900/60 backdrop-blur-sm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>
        <div class="bg-white rounded-[2.5rem] p-8 max-w-2xl w-full shadow-2xl border border-zinc-100 space-y-6 max-h-[90vh] flex flex-col" @click.away="noteModalOpen = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-zinc-100 pb-6 shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center border border-amber-100">
                        <span class="material-symbols-outlined text-2xl">rate_review</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-on-surface uppercase tracking-tight">Catatan & Masukan Perbaikan</h3>
                        <p class="text-xs font-bold text-zinc-400 mt-0.5" x-text="activeNoteTitle"></p>
                    </div>
                </div>
                <button @click="noteModalOpen = false" class="w-10 h-10 bg-zinc-100 hover:bg-zinc-200 text-zinc-500 rounded-2xl flex items-center justify-center transition-all active:scale-95">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="space-y-6 overflow-y-auto pr-2 py-2 flex-1 text-zinc-700 text-xs leading-relaxed">
                <div class="p-6 rounded-3xl bg-amber-50/50 border border-amber-100/80 space-y-3">
                    <div class="flex items-center gap-2 text-amber-800 font-bold text-xs uppercase tracking-wider">
                        <span class="material-symbols-outlined text-base">assignment_late</span>
                        <span>Deskripsi Catatan Learning Administrator:</span>
                    </div>
                    <p class="text-zinc-700 font-medium whitespace-pre-line text-sm leading-relaxed" x-text="activeNoteContent"></p>
                </div>

                {{-- Dokumen Referensi / Lampiran --}}
                <div class="space-y-3">
                    <div class="flex items-center gap-2 text-zinc-800 font-bold text-xs uppercase tracking-wider">
                        <span class="material-symbols-outlined text-base text-primary">folder_open</span>
                        <span>Dokumen Referensi / Acuan Perbaikan:</span>
                    </div>
                    <template x-if="activeNoteDocs && activeNoteDocs.length > 0">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="(doc, dIdx) in activeNoteDocs" :key="dIdx">
                                <a :href="doc.url || '#'" target="_blank" class="p-4 rounded-2xl bg-zinc-50 hover:bg-primary/5 border border-zinc-200 hover:border-primary/30 flex items-center gap-3 transition-all group">
                                    <div class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center border border-red-100 shrink-0 group-hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-xl">picture_as_pdf</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold text-zinc-800 truncate group-hover:text-primary transition-colors" x-text="doc.name"></p>
                                        <p class="text-[10px] text-zinc-400 font-medium mt-0.5" x-text="doc.size ? `${(doc.size/1024/1024).toFixed(2)} MB` : 'Dokumen Acuan'"></p>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </template>
                    <template x-if="!activeNoteDocs || activeNoteDocs.length === 0">
                        <p class="text-zinc-400 italic text-xs">Tidak ada dokumen referensi khusus yang dilampirkan pada blueprint ini.</p>
                    </template>
                </div>

                {{-- Saluran Komunikasi & Bantuan Operasional (Gap 1 Pragmatic Enterprise Solution) --}}
                <div class="space-y-3 pt-4 border-t border-zinc-100">
                    <div class="flex items-center gap-2 text-zinc-800 font-bold text-xs uppercase tracking-wider">
                        <span class="material-symbols-outlined text-base text-blue-600">contact_support</span>
                        <span>Saluran Komunikasi & Bantuan Operasional:</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Kartu Kontak CLD / Direct Contact --}}
                        <div class="p-4 rounded-2xl bg-blue-50/50 border border-blue-100 flex flex-col justify-between space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center border border-blue-200 shrink-0">
                                    <span class="material-symbols-outlined text-xl">support_agent</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-black text-zinc-800 truncate">Tim CLD / Administrator</p>
                                    <p class="text-[10px] text-zinc-500 font-medium leading-tight mt-0.5">Klarifikasi cepat terkait catatan perbaikan materi via WhatsApp.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 pt-1">
                                <a href="https://wa.me/6281123456789?text=Halo%20Tim%20CLD,%20saya%20SME%20ingin%20berdiskusi%20terkait%20revisi%20blueprint." target="_blank" class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black uppercase tracking-wider rounded-xl text-center shadow-md shadow-blue-600/20 transition-all active:scale-95 flex items-center justify-center gap-1.5">
                                    <span class="material-symbols-outlined text-xs">chat</span>
                                    Chat WhatsApp
                                </a>
                                <a href="mailto:cld.support@sig.id?subject=Klarifikasi%20Revisi%20Blueprint" class="px-3 py-2 bg-white hover:bg-zinc-50 text-zinc-700 border border-zinc-200 text-[10px] font-black uppercase tracking-wider rounded-xl text-center transition-all active:scale-95 flex items-center justify-center" title="Kirim Email">
                                    <span class="material-symbols-outlined text-xs">mail</span>
                                </a>
                            </div>
                        </div>

                        {{-- Kartu Eskalasi Helpdesk SIG --}}
                        <div class="p-4 rounded-2xl bg-zinc-50 border border-zinc-200 flex flex-col justify-between space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-zinc-200 text-zinc-700 flex items-center justify-center border border-zinc-300 shrink-0">
                                    <span class="material-symbols-outlined text-xl">headset_mic</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-black text-zinc-800 truncate">Helpdesk Terpusat SIG</p>
                                    <p class="text-[10px] text-zinc-500 font-medium leading-tight mt-0.5">Eskalasi kendala teknis sistem atau pelaporan isu operasional resmi.</p>
                                </div>
                            </div>
                            <div class="pt-1">
                                <a href="https://helpdesk.sig.id" target="_blank" class="w-full px-3 py-2 bg-zinc-800 hover:bg-zinc-900 text-white text-[10px] font-black uppercase tracking-wider rounded-xl text-center shadow-md shadow-zinc-800/20 transition-all active:scale-95 flex items-center justify-center gap-1.5">
                                    <span class="material-symbols-outlined text-xs">open_in_new</span>
                                    Portal Helpdesk SIG
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="border-t border-zinc-100 pt-6 flex justify-end shrink-0">
                <button @click="noteModalOpen = false" class="px-6 py-3 bg-zinc-900 text-white font-bold text-xs rounded-2xl hover:bg-zinc-800 transition-all active:scale-95 shadow-lg shadow-zinc-900/20">
                    Tutup Catatan
                </button>
            </div>
        </div>
    </div>

    {{-- Modal Serahkan Materi Perbaikan (Gap 2 Solution) --}}
    <div x-show="submissionModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-900/60 backdrop-blur-sm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>
        <div class="bg-white rounded-[2.5rem] p-8 max-w-2xl w-full shadow-2xl border border-zinc-100 space-y-6 max-h-[90vh] flex flex-col" @click.away="submissionModalOpen = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-zinc-100 pb-6 shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center border border-amber-100">
                        <span class="material-symbols-outlined text-2xl">upload_file</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-on-surface uppercase tracking-tight">Serahkan Materi Perbaikan</h3>
                        <p class="text-xs font-bold text-zinc-400 mt-0.5" x-text="activeSubmissionTitle"></p>
                    </div>
                </div>
                <button @click="submissionModalOpen = false" class="w-10 h-10 bg-zinc-100 hover:bg-zinc-200 text-zinc-500 rounded-2xl flex items-center justify-center transition-all active:scale-95">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="space-y-6 overflow-y-auto pr-2 py-2 flex-1 text-zinc-700 text-xs leading-relaxed">
                {{-- Info Banner --}}
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-100 flex items-start gap-3">
                    <span class="material-symbols-outlined text-amber-600 text-lg shrink-0 mt-0.5">lightbulb</span>
                    <p class="text-amber-800 text-xs font-medium leading-relaxed">
                        <strong class="font-bold">Pragmatic Enterprise Versioning:</strong> Tuliskan poin ringkasan perbaikan Anda pada kolom di bawah. Catatan ini akan menjadi acuan verifikasi instan bagi Learning Administrator tanpa perlu membandingkan file biner secara manual.
                    </p>
                </div>

                {{-- Area Upload File --}}
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest block ml-1">File Materi Perbaikan (Opsional)</label>
                    <input x-ref="fileInput" type="file" class="hidden" @change="handleFileSelect($event)">
                    
                    {{-- Dropzone --}}
                    <div @click="$refs.fileInput.click()"
                         @dragover.prevent="isDragging = true"
                         @dragleave.prevent="isDragging = false"
                         @drop.prevent="handleFileDrop($event)"
                         :class="isDragging ? 'border-primary bg-primary/5 scale-[1.01]' : 'border-zinc-200 bg-zinc-50/50 hover:bg-zinc-100/50'"
                         class="cursor-pointer border-2 border-dashed transition-all rounded-3xl p-6 flex flex-col items-center justify-center text-center group min-h-[140px]">
                        
                        <template x-if="!selectedFile">
                            <div class="space-y-2">
                                <span class="material-symbols-outlined text-3xl text-zinc-400 group-hover:scale-110 transition-transform">cloud_upload</span>
                                <p class="text-xs font-bold text-zinc-600">Klik atau tarik file materi perbaikan ke sini</p>
                                <p class="text-[10px] font-medium text-zinc-400">Format PDF, PPTX, DOCX, Excel, atau ZIP archive</p>
                            </div>
                        </template>

                        <template x-if="selectedFile">
                            <div class="flex items-center gap-4 p-4 bg-white rounded-2xl border border-zinc-200 shadow-sm w-full justify-between">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 shrink-0">
                                        <span class="material-symbols-outlined text-xl">description</span>
                                    </div>
                                    <div class="min-w-0 text-left">
                                        <p class="text-xs font-bold text-zinc-800 truncate" x-text="selectedFile.name"></p>
                                        <p class="text-[10px] text-zinc-400 font-bold" x-text="`${(selectedFile.size/1024/1024).toFixed(2)} MB`"></p>
                                    </div>
                                </div>
                                <button @click.stop="selectedFile = null" class="w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition-all">
                                    <span class="material-symbols-outlined text-base">delete</span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Kolom Catatan Penyerahan Revisi (Revision Notes) --}}
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest block ml-1">Catatan Penyerahan Revisi <span class="text-red-500">*</span></label>
                    <textarea x-model="revisionNotes" rows="4" placeholder="Jelaskan bagian mana saja yang telah Anda perbaiki (misal: 'Telah memperbaiki Sisi A pada slide 12 dan memperbarui tabel anggaran di Bab 3')..." class="w-full p-5 bg-zinc-50 border border-zinc-200 rounded-3xl text-xs font-medium text-zinc-800 placeholder-zinc-400 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all leading-relaxed"></textarea>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="border-t border-zinc-100 pt-6 flex items-center justify-end gap-4 shrink-0">
                <button @click="submissionModalOpen = false" class="px-6 py-3 bg-zinc-100 hover:bg-zinc-200 text-zinc-600 font-bold text-xs rounded-2xl transition-all active:scale-95">
                    Batal
                </button>
                <button @click="submitRevision()" :disabled="isSubmitting" class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white font-black text-xs uppercase tracking-wider rounded-2xl shadow-lg shadow-amber-500/20 hover:shadow-amber-500/30 transition-all active:scale-95 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <template x-if="isSubmitting">
                        <span class="material-symbols-outlined text-base animate-spin">progress_activity</span>
                    </template>
                    <template x-if="!isSubmitting">
                        <span class="material-symbols-outlined text-base">send</span>
                    </template>
                    <span x-text="isSubmitting ? 'Mengirim...' : 'Kirim Perbaikan'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
