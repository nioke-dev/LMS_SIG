@extends('layouts.backoffice')

@section('sidebar-nav')
    @include('partials.lc-sidebar')
@endsection

@section('content')
<div x-data="tnaForm()" x-init="init()" class="max-w-[1400px] mx-auto">
    {{-- Modern Form Header --}}
    <div class="mb-10 px-4">
        <nav class="flex items-center gap-2 mb-4">
            <a href="{{ route('learning-coordinator.daftar-usulan') }}" class="text-[10px] font-black text-zinc-400 uppercase tracking-widest hover:text-primary transition-all">TNA Management</a>
            <span class="material-symbols-outlined text-zinc-300 text-[10px]">chevron_right</span>
            <span class="text-[10px] font-black text-primary uppercase tracking-widest">
                {{ isset($submission) ? 'Edit Usulan' : 'Buat Usulan Baru' }}
            </span>
        </nav>
        
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-2 h-8 bg-primary rounded-full hidden md:block"></div>
                <h1 class="text-3xl font-black text-on-surface tracking-tight">
                    {{ isset($submission) ? 'Perbarui Usulan Pelatihan' : 'Form Usulan Pelatihan' }}
                </h1>
            </div>
            
            <a href="{{ route('learning-coordinator.daftar-usulan') }}" 
               class="flex items-center gap-2 px-6 py-3 bg-zinc-100 hover:bg-zinc-200 text-zinc-600 rounded-2xl transition-all group">
                <span class="material-symbols-outlined text-lg group-hover:-translate-x-1 transition-transform">arrow_back</span>
                <span class="text-xs font-black uppercase tracking-widest">Kembali ke Daftar</span>
            </a>
        </div>
        
        <p class="text-xs text-zinc-400 font-medium mt-2 ml-0 md:ml-6 italic">
            Silakan lengkapi detail kebutuhan pelatihan di bawah ini dengan data yang akurat.
        </p>
    </div>

    <form @submit.prevent="submitForm" class="space-y-8">
        {{-- Full Width: Basic Info --}}
        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-zinc-100">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">edit_note</span>
                </div>
                <div>
                    <h3 class="text-base font-black text-on-surface tracking-tight uppercase">Informasi Dasar Pelatihan</h3>
                    <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest">Detail kebutuhan pelatihan</p>
                </div>
            </div>

            <div class="space-y-8">
                <div>
                    <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-3 ml-1">Judul Usulan Pelatihan</label>
                    <input type="text" x-model="form.title"
                           placeholder="Misal: Sertifikasi Operator Crane Level 1"
                           class="w-full px-6 py-4 bg-zinc-50 border border-zinc-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all italic shadow-sm">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <div class="flex items-center justify-between mb-3 ml-1">
                            <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest">Kategori Training</label>
                            <button type="button" @click="$dispatch('open-propose-modal')" 
                                     class="text-[9px] font-black text-primary uppercase tracking-widest hover:text-primary-dark transition-all flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">add_circle</span>
                                Usulkan Kategori Baru
                            </button>
                        </div>
                        <x-tna.category-selector :categories="$categories" :selected="$submission->category ?? null" @category-selected="form.category = $event.detail" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-3 ml-1">Tingkat Urgensi</label>
                        <x-tna.urgency-selector :selected="$submission->urgency ?? 'Medium'" @urgency-selected="form.urgency = $event.detail" />
                    </div>
                </div>

                <x-tna.propose-category-modal />

                <div>
                    <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-3 ml-1">Latar Belakang & Tujuan</label>
                    <textarea x-model="form.description" rows="5"
                              placeholder="Jelaskan mengapa pelatihan ini dibutuhkan dan apa output yang diharapkan..."
                              class="w-full px-6 py-4 bg-zinc-50 border border-zinc-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all italic shadow-sm"></textarea>
                </div>

                {{-- Integrated Document Uploader --}}
                <div class="pt-6 border-t border-zinc-100">
                    <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-4 ml-1">Dokumen Pendukung (PDF/Docx)</label>
                    <x-tna.document-uploader :existingFiles="$submission->documents ?? []" />
                </div>
            </div>
        </div>

        {{-- Participant Table (Full Width) --}}
        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-zinc-100">
            <x-tna.participant-table :participants="$participants" :selected="$submission->participants_list ?? []" @participants-updated="form.participants_count = $event.detail.count; form.participants_list = $event.detail.list" />
        </div>

        {{-- NEW Bottom Summary Card (Full Width) --}}
        <div class="bg-zinc-900 rounded-[2.5rem] p-10 shadow-2xl shadow-zinc-950/20 text-white relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-10">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-primary/5 rounded-full -ml-24 -mb-24 blur-3xl"></div>
            
            <div class="relative flex flex-col md:flex-row items-center gap-12 lg:gap-20">
                <div class="flex flex-col items-center md:items-start">
                    <h3 class="text-sm font-black uppercase tracking-widest mb-6 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                        Ringkasan Usulan
                    </h3>
                    <div class="flex items-center gap-12 lg:gap-20">
                        <div class="flex flex-col items-center md:items-start">
                            <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Total Peserta</span>
                            <span class="text-3xl font-black text-primary tracking-tighter" x-text="form.participants_count">0</span>
                        </div>
                        <div class="flex flex-col items-center md:items-start border-l border-white/10 pl-12 lg:pl-20">
                            <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Total Lampiran</span>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-zinc-500 text-sm">description</span>
                                <span class="text-xl font-black" x-text="form.documents.length + ' File'">0 File</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative w-full md:w-auto flex flex-col sm:flex-row items-center gap-4">
                <button type="button" @click="submitForm('draft')" :disabled="isSubmitting"
                        class="w-full sm:w-auto px-10 py-5 bg-white/5 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-white/10 transition-all border border-white/10 active:scale-95 disabled:opacity-50">
                    Simpan ke Draft
                </button>
                <button type="button" @click="submitForm('review')" :disabled="isSubmitting"
                        class="w-full sm:w-auto px-12 py-5 bg-primary text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary-dark transition-all shadow-lg shadow-primary/20 active:scale-95 disabled:opacity-50 flex items-center justify-center gap-3">
                    <span x-show="!isSubmitting">Kirim Usulan</span>
                    <span x-show="isSubmitting" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                    <span x-show="!isSubmitting" class="material-symbols-outlined text-base">rocket_launch</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function tnaForm() {
        return {
            form: {
                id: @js($submission->id ?? ""),
                title: @js($submission->title ?? ""),
                category: @js($submission->category ?? ""),
                urgency: @js($submission->urgency ?? "Medium"),
                description: @js($submission->description ?? ""),
                participants_count: {{ $submission->participants ?? 0 }},
                participants_list: @js($submission->participants_list ?? []),
                documents: @js($submission->documents ?? [])
            },
            isSubmitting: false,
 
            init() {
                console.log('TNA Form Initialized');
            },
 
            async submitForm(status = 'review') {
                // Mandatory Validation: Title must exist for both Draft and Review
                if (!this.form.title || this.form.title.trim() === '') {
                    Alert.warning('Judul Wajib Diisi!', 'Silakan masukkan judul pelatihan sebelum menyimpan.');
                    return;
                }

                if (status === 'review') {
                    if (!this.form.category || !this.form.urgency || !this.form.description || this.form.participants_count === 0) {
                        Alert.warning('Data Belum Lengkap', 'Untuk mengirim usulan ke review, mohon lengkapi kategori, urgensi, deskripsi, dan pilih minimal satu peserta.');
                        return;
                    }
                }
 
                this.isSubmitting = true;
                
                try {
                    const url = this.form.id ? `/learning-coordinator/tna/${this.form.id}` : '/learning-coordinator/tna';
                    const method = 'POST'; 
                    
                    const payload = {
                        ...this.form,
                        status: status,
                        _token: '{{ csrf_token() }}'
                    };

                    if (this.form.id) payload._method = 'PUT';

                    const response = await axios({ method, url, data: payload });

                    if (response.data.success) {
                        await Alert.success('Berhasil!', response.data.message);
                        window.location.href = response.data.redirect || '{{ route("learning-coordinator.daftar-usulan") }}';
                    }
                } catch (error) {
                    console.error('Submission Error:', error);
                    const msg = error.response?.data?.message || 'Terjadi kesalahan saat menghubungi server.';
                    Alert.error('Oops!', msg);
                } finally {
                    this.isSubmitting = false;
                }
            }
        }
    }
</script>
@endsection