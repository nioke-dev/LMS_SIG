@extends('layouts.backoffice')

@section('sidebar-nav')
    @include('partials.sme-sidebar')
@endsection

@section('title', 'Review Blueprint & Submit Materi')

@section('page-title', 'Blueprint Review')

@section('content')
@php
    $tnaIds = is_array($blueprint->tna_submission_ids) ? $blueprint->tna_submission_ids : (json_decode($blueprint->tna_submission_ids, true) ?? []);
    $mergedSubmissions = \App\Models\TnaSubmission::whereIn('id', $tnaIds)->get();
@endphp

<div class="space-y-8" x-data="{
    submitting: false,
    materialNotes: '',
    fileName: '',
    
    submitMaterial(e) {
        if (!this.materialNotes.trim()) {
            alert('Catatan atau deskripsi materi wajib diisi.');
            e.preventDefault();
            return;
        }
        this.submitting = true;
    }
}">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-primary/10 text-primary rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0">
                <span class="material-symbols-outlined text-3xl">architecture</span>
            </div>
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <span class="px-3 py-1 bg-zinc-100 text-zinc-600 text-[10px] font-black uppercase tracking-widest rounded-md">{{ $blueprint->id }}</span>
                    <span class="px-3 py-1 bg-amber-50 text-amber-600 border border-amber-200 text-[10px] font-black uppercase tracking-widest rounded-full">
                        Status: {{ strtoupper(str_replace('_', ' ', $blueprint->status)) }}
                    </span>
                </div>
                <h1 class="text-2xl font-black text-zinc-900 tracking-tight uppercase">{{ $blueprint->title }}</h1>
                <p class="text-xs font-bold text-zinc-500 mt-1">Kategori Induk: <span class="text-primary">{{ $blueprint->category }}</span> • Deadline: {{ $blueprint->deadline ? \Carbon\Carbon::parse($blueprint->deadline)->translatedFormat('d F Y') : '19 Mei 2026' }}</p>
            </div>
        </div>
        <a href="{{ route('sme.blueprint.index') }}" class="px-6 py-3 bg-zinc-100 text-zinc-600 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-zinc-200 transition-all flex items-center gap-2 flex-shrink-0 w-max">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Kembali ke Daftar
        </a>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="p-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-4 shadow-sm">
        <span class="material-symbols-outlined text-2xl text-emerald-600 flex-shrink-0">check_circle</span>
        <div class="flex-1">
            <h4 class="text-sm font-black uppercase tracking-wider">Berhasil!</h4>
            <p class="text-xs font-medium mt-0.5">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column: Blueprint Information --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm space-y-8">
                <h2 class="text-lg font-black text-zinc-900 uppercase tracking-tight border-b border-zinc-100 pb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">menu_book</span>
                    Spesifikasi Kurikulum & Instruksi
                </h2>

                {{-- Kategori Child / Komposisi Penggabungan TNA --}}
                <div>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 mb-3 border-l-4 border-primary pl-3">Komposisi Penggabungan Kategori (Child Categories)</h3>
                    <div class="p-6 bg-zinc-50 rounded-2xl border border-zinc-100 space-y-3">
                        <p class="text-xs text-zinc-600 font-medium mb-3">Blueprint ini merupakan hasil leburan (merging) dari beberapa usulan/sub-kategori berikut di bawah naungan rumpun <span class="font-bold text-primary">{{ $blueprint->category }}</span>:</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($mergedSubmissions as $sub)
                            <div class="p-4 bg-white rounded-xl border border-zinc-200/80 shadow-sm flex items-start gap-3 group hover:border-primary/40 transition-all">
                                <div class="w-8 h-8 bg-primary/10 text-primary rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-base">account_tree</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-black text-zinc-800 leading-snug truncate">{{ $sub->title }}</p>
                                    <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest mt-1">ID: {{ $sub->id }} • {{ $sub->urgency }}</p>
                                </div>
                            </div>
                            @empty
                            <div class="col-span-2 p-4 bg-white rounded-xl border border-zinc-200 text-center">
                                <p class="text-xs text-zinc-400 font-medium italic">Data child category / usulan TNA spesifik tidak ditemukan atau menggunakan entri langsung.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Latar Belakang / Rasionalisasi --}}
                <div>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 mb-3 border-l-4 border-primary pl-3">Latar Belakang / Rasionalisasi</h3>
                    <div class="p-6 bg-zinc-50 rounded-2xl border border-zinc-100 text-xs text-zinc-700 leading-relaxed font-medium">
                        {!! $blueprint->rationalization ?? '<p class="text-zinc-400 italic">Rasionalisasi belum ditentukan.</p>' !!}
                    </div>
                </div>

                {{-- Course Objective --}}
                <div>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 mb-3 border-l-4 border-primary pl-3">Course Objective</h3>
                    <div class="p-6 bg-zinc-50 rounded-2xl border border-zinc-100 text-xs text-zinc-700 leading-relaxed font-medium">
                        {!! $blueprint->objective ?? '<p class="text-zinc-400 italic">Course objective belum ditentukan.</p>' !!}
                    </div>
                </div>

                {{-- Course Content --}}
                <div>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 mb-3 border-l-4 border-primary pl-3">Course Content</h3>
                    <div class="p-6 bg-zinc-50 rounded-2xl border border-zinc-100 text-xs text-zinc-700 leading-relaxed font-medium">
                        {!! $blueprint->content ?? '<p class="text-zinc-400 italic">Course content belum ditentukan.</p>' !!}
                    </div>
                </div>

                {{-- Instruksi Khusus SME --}}
                <div>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 mb-3 border-l-4 border-amber-500 pl-3">Instruksi Khusus dari Admin Coordinator</h3>
                    <div class="p-6 bg-amber-50 rounded-2xl border border-amber-100">
                        <p class="text-xs text-amber-800 font-medium leading-relaxed italic">{{ $blueprint->sme_instructions ?? 'Tidak ada instruksi khusus dari Admin Coordinator.' }}</p>
                    </div>
                </div>

                {{-- Dokumen Pendukung dari Admin Coordinator --}}
                <div>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 mb-3 border-l-4 border-blue-500 pl-3">Dokumen Pendukung dari Admin Coordinator</h3>
                    <div class="p-6 bg-blue-50/50 rounded-2xl border border-blue-100 space-y-3">
                        @php
                            $docs = is_string($blueprint->supporting_documents) ? json_decode($blueprint->supporting_documents, true) : ($blueprint->supporting_documents ?? []);
                        @endphp
                        @forelse($docs as $doc)
                        <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-blue-200/60 shadow-sm">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="material-symbols-outlined text-blue-600 text-2xl flex-shrink-0">attach_file</span>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-zinc-800 truncate">{{ is_array($doc) ? ($doc['name'] ?? 'Dokumen_Pendukung.pdf') : (is_string($doc) ? basename($doc) : 'Dokumen_Pendukung.pdf') }}</p>
                                    <p class="text-[10px] text-zinc-400 font-medium mt-0.5">Lampiran Referensi Pelatihan</p>
                                </div>
                            </div>
                            <a href="{{ is_array($doc) ? ($doc['url'] ?? '#') : (is_string($doc) ? $doc : '#') }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-blue-700 transition-all flex items-center gap-1.5 flex-shrink-0 shadow-sm">
                                <span class="material-symbols-outlined text-sm">open_in_new</span>
                                Buka
                            </a>
                        </div>
                        @empty
                        <p class="text-xs text-blue-800 font-medium italic">Tidak ada dokumen pendukung yang dilampirkan oleh Admin Coordinator.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Kebutuhan Workshop & Distribusi --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-zinc-100">
                    {{-- Kebutuhan Workshop --}}
                    <div class="p-6 bg-gradient-to-br from-amber-500/10 via-amber-500/5 to-transparent rounded-[2rem] border border-amber-200/60 relative overflow-hidden flex flex-col justify-between shadow-sm group hover:border-amber-300 transition-all">
                        <div class="absolute -right-4 -bottom-4 text-amber-500/10 pointer-events-none transform group-hover:scale-110 transition-transform duration-500">
                            <span class="material-symbols-outlined text-8xl">handyman</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-amber-500 text-white rounded-xl flex items-center justify-center shadow-md shadow-amber-500/20 flex-shrink-0">
                                    <span class="material-symbols-outlined text-xl">build_circle</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Kebutuhan Workshop</p>
                                    <h4 class="text-sm font-black text-zinc-900 mt-0.5">{{ $blueprint->need_workshop ? 'Memerlukan Workshop' : 'Tanpa Workshop' }}</h4>
                                </div>
                            </div>
                            <div class="bg-white/80 backdrop-blur-sm p-4 rounded-2xl border border-amber-100/80 shadow-sm">
                                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-wider mb-1">Catatan Kebutuhan Workshop:</p>
                                <p class="text-xs text-zinc-700 font-medium leading-relaxed">{{ $blueprint->workshop_note ?? 'Tidak ada catatan spesifik mengenai kebutuhan workshop.' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Target Distribusi --}}
                    <div class="p-6 bg-gradient-to-br from-blue-500/10 via-blue-500/5 to-transparent rounded-[2rem] border border-blue-200/60 relative overflow-hidden flex flex-col justify-between shadow-sm group hover:border-blue-300 transition-all">
                        <div class="absolute -right-4 -bottom-4 text-blue-500/10 pointer-events-none transform group-hover:scale-110 transition-transform duration-500">
                            <span class="material-symbols-outlined text-8xl">share</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center shadow-md shadow-blue-600/20 flex-shrink-0">
                                    <span class="material-symbols-outlined text-xl">public</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Target Distribusi</p>
                                    <h4 class="text-sm font-black text-zinc-900 mt-0.5">{{ $blueprint->distribution === 'public' ? 'Public Ready (Eksternal & Internal)' : 'Internal Only (SIG Group)' }}</h4>
                                </div>
                            </div>
                            <div class="bg-white/80 backdrop-blur-sm p-4 rounded-2xl border border-blue-100/80 shadow-sm">
                                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-wider mb-1">Catatan Distribusi:</p>
                                <p class="text-xs text-zinc-700 font-medium leading-relaxed">{{ $blueprint->distribution_note ?? 'Tidak ada catatan spesifik distribusi.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Riwayat Pengiriman Materi --}}
            <div class="bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm space-y-6">
                <h2 class="text-lg font-black text-zinc-900 uppercase tracking-tight border-b border-zinc-100 pb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600">history</span>
                    Riwayat Materi & Dokumen Terkirim
                </h2>

                @php
                    $materials = is_string($blueprint->sme_submitted_materials) ? json_decode($blueprint->sme_submitted_materials, true) : ($blueprint->sme_submitted_materials ?? []);
                @endphp

                <div class="space-y-4">
                    @forelse($materials as $mat)
                    <div class="p-6 bg-zinc-50 rounded-2xl border border-zinc-100 flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4 w-full">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-primary flex-shrink-0 border border-zinc-200">
                                <span class="material-symbols-outlined text-2xl">description</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-black text-zinc-900 uppercase tracking-tight truncate">{{ $mat['file_name'] ?? 'Materi_Pelatihan_SIG.pdf' }}</h4>
                                <p class="text-xs font-bold text-zinc-500 mt-0.5">Catatan / Deskripsi Materi:</p>
                                <div class="text-xs text-zinc-700 font-medium mt-1.5 bg-white p-4 rounded-xl border border-zinc-200 leading-relaxed whitespace-pre-wrap shadow-sm">{{ $mat['notes'] ?? 'Tidak ada deskripsi.' }}</div>
                                
                                <div class="flex items-center gap-4 mt-3 text-[10px] text-zinc-400 font-bold uppercase tracking-widest">
                                    <span>Dikirim: {{ \Carbon\Carbon::parse($mat['submitted_at'] ?? now())->translatedFormat('d F Y, H:i') }}</span>
                                    <span>Oleh: {{ $mat['submitted_by'] ?? 'SME' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 bg-zinc-50 rounded-2xl border border-zinc-100 text-center">
                        <p class="text-xs text-zinc-400 font-medium italic">Belum ada materi atau dokumen pelatihan yang dikirimkan untuk blueprint ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Column: Submit Material Form --}}
        <div class="space-y-8">
            <div class="bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm space-y-6 sticky top-8">
                <h2 class="text-lg font-black text-zinc-900 uppercase tracking-tight border-b border-zinc-100 pb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">cloud_upload</span>
                    Kirim Materi Pelatihan
                </h2>

                <form action="{{ route('sme.blueprint.submit', $blueprint->id) }}" method="POST" enctype="multipart/form-data" @submit="submitMaterial($event)" class="space-y-6">
                    @csrf

                    {{-- Realistic Professional File Upload Box --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-500 mb-2">Upload File Materi / Dokumen <span class="text-red-500">*</span></label>
                        <input type="file" name="material_file" id="material-file-upload" class="hidden" @change="fileName = $event.target.files[0].name">
                        <label for="material-file-upload" class="p-6 bg-zinc-50 border-2 border-dashed border-zinc-300 rounded-2xl flex flex-col items-center justify-center gap-3 text-center group hover:bg-primary/5 hover:border-primary transition-all cursor-pointer block">
                            <div class="w-14 h-14 bg-white rounded-2xl shadow-sm border border-zinc-200 flex items-center justify-center text-primary group-hover:scale-110 transition-transform mx-auto">
                                <span class="material-symbols-outlined text-3xl">upload_file</span>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-zinc-800" x-show="!fileName">Upload File Materi Di Sini</p>
                                <p class="text-xs font-black text-primary truncate max-w-[220px] bg-white px-3 py-1 rounded-full border border-primary/20 shadow-sm inline-block mt-1" x-show="fileName" x-text="fileName" x-cloak></p>
                            </div>
                            <p class="text-[10px] text-zinc-400 font-medium">Format: PDF, DOCX, PPTX, MP4 (Maks. 50MB)</p>
                        </label>
                        <input type="hidden" name="file_name" x-model="fileName">
                    </div>

                    {{-- Notes / Description --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-500 mb-2">Catatan / Deskripsi Materi <span class="text-red-500">*</span></label>
                        <textarea name="material_notes" x-model="materialNotes" rows="6" placeholder="Tuliskan deskripsi lengkap, poin-poin bahasan modul, atau pesan khusus mengenai file yang diunggah..." class="w-full px-4 py-3 text-xs bg-zinc-50 border border-zinc-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-medium text-zinc-800"></textarea>
                        <p class="text-[10px] text-zinc-400 mt-1.5 font-medium">Deskripsi ini akan dibaca oleh Admin Coordinator dan Tim CLD saat melakukan validasi modul.</p>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" :disabled="submitting" class="w-full py-4 bg-primary text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-primary-container shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all active:scale-95 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="material-symbols-outlined text-lg" x-show="!submitting">send</span>
                        <span class="material-symbols-outlined text-lg animate-spin" x-show="submitting" x-cloak>refresh</span>
                        <span x-text="submitting ? 'Mengirim Materi...' : 'Upload & Kirim Materi'"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
