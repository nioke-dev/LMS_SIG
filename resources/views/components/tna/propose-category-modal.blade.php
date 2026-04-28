<div x-data="{ 
    isOpen: false,
    form: { name: '', scope: '', reason: '', file: null },
    isSubmitting: false,

    submitPropose() {
        if(!this.form.name || !this.form.scope || !this.form.reason) {
            Swal.fire({
                title: 'Oops!',
                text: 'Harap lengkapi informasi kategori yang diusulkan.',
                icon: 'warning',
                customClass: { popup: 'rounded-[2rem]' }
            });
            return;
        }
        
        this.isSubmitting = true;
        // Mock submission
        setTimeout(() => {
            this.isSubmitting = false;
            this.isOpen = false;
            Swal.fire({
                title: 'Usulan Terkirim!',
                text: 'Kategori baru Anda telah diusulkan ke Admin Coordinator.',
                icon: 'success',
                confirmButtonColor: '#00B0F0',
                customClass: { popup: 'rounded-[2rem]' }
            });
            this.form = { name: '', scope: '', reason: '', file: null };
        }, 1500);
    }
}" @open-propose-modal.window="isOpen = true" class="relative z-[9999]">

    <template x-teleport="body">
        <div x-show="isOpen" 
             class="fixed inset-0 flex items-center justify-center p-4 bg-zinc-950/40 backdrop-blur-sm z-[9999]"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-cloak>
            
            <div class="bg-white w-full max-w-xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]"
                 @click.away="isOpen = false"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                {{-- Header --}}
                <div class="p-8 pb-0 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500">
                            <span class="material-symbols-outlined text-2xl">add_to_photos</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-on-surface tracking-tight uppercase">Usulkan Kategori Baru</h3>
                            <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest">Kirim permintaan ke Admin Coordinator</p>
                        </div>
                    </div>
                    <button @click="isOpen = false" class="w-10 h-10 rounded-xl bg-zinc-50 text-zinc-400 flex items-center justify-center hover:bg-zinc-100 transition-all">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                {{-- Scrollable Form --}}
                <div class="flex-1 overflow-y-auto p-8 pt-6 space-y-6 custom-scrollbar">
                    <div class="p-5 bg-amber-50/50 border border-amber-100 rounded-2xl">
                        <p class="text-[10px] text-amber-700 font-bold leading-relaxed">
                            <span class="font-black">INFO:</span> Admin Coordinator akan meninjau usulan Anda. Pastikan nama dan alasan yang diberikan sudah cukup kuat dan jelas.
                        </p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1">Nama Kategori Baru</label>
                        <input type="text" x-model="form.name" placeholder="Misal: Teknologi Berkelanjutan"
                               class="w-full px-5 py-3.5 bg-zinc-50 border border-zinc-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-sm">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1">Deskripsi / Ruang Lingkup</label>
                        <textarea x-model="form.scope" rows="3" placeholder="Jelaskan cakupan dari kategori pelatihan ini..."
                                  class="w-full px-5 py-3.5 bg-zinc-50 border border-zinc-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1">Alasan Pengajuan</label>
                        <textarea x-model="form.reason" rows="3" placeholder="Mengapa kategori ini penting untuk ditambahkan?"
                                  class="w-full px-5 py-3.5 bg-zinc-50 border border-zinc-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1">Dokumen Pendukung (Opsional)</label>
                        <div class="relative group">
                            <input type="file" @change="form.file = $event.target.files[0]" 
                                   class="absolute inset-0 opacity-0 cursor-pointer z-10">
                            <div class="w-full px-5 py-4 bg-zinc-50 border-2 border-dashed border-zinc-200 rounded-xl flex items-center justify-center gap-3 group-hover:bg-white group-hover:border-primary transition-all">
                                <span class="material-symbols-outlined text-zinc-400 group-hover:text-primary transition-colors">upload_file</span>
                                <span class="text-xs font-bold text-zinc-500 group-hover:text-primary transition-colors" x-text="form.file ? form.file.name : 'Upload file pendukung...'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="p-8 pt-4 shrink-0 bg-zinc-50 border-t border-zinc-100 flex items-center gap-4">
                    <button @click="isOpen = false" class="flex-1 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-400 hover:text-zinc-600 transition-all">
                        Batalkan
                    </button>
                    <button @click="submitPropose()" :disabled="isSubmitting"
                            class="flex-[2] py-4 bg-zinc-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-zinc-800 transition-all active:scale-95 flex items-center justify-center gap-2 disabled:opacity-50">
                        <span x-show="!isSubmitting">Kirim Usulan</span>
                        <span x-show="isSubmitting" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                        <span x-show="!isSubmitting" class="material-symbols-outlined text-sm">send</span>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
