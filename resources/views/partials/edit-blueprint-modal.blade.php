{{-- Edit Blueprint Modal (Copied and adapted from initiate-blueprint.blade.php) --}}
<template x-teleport="body">
    <div x-show="editModalOpen" class="fixed inset-0 z-[1050] flex justify-center items-center p-6 bg-zinc-900/60 backdrop-blur-sm" x-cloak>
        <div class="bg-zinc-50 w-full max-w-7xl h-full max-h-[90vh] rounded-[2.5rem] shadow-2xl flex flex-col overflow-hidden border border-zinc-200 transform transition-all">
            
            {{-- Header --}}
            <div class="px-10 py-6 bg-white border-b border-zinc-100 flex justify-between items-center shrink-0 z-10 shadow-sm">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-zinc-900 text-white flex items-center justify-center shadow-lg">
                        <span class="material-symbols-outlined text-3xl">edit_document</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-zinc-900 leading-tight tracking-tight uppercase">Edit Blueprint Pelatihan</h1>
                        <p class="text-zinc-500 font-medium text-xs mt-1">Revisi objektif utama dan sesuaikan penugasan Subject Matter Expert (SME).</p>
                    </div>
                </div>
                <button @click="editModalOpen = false" class="w-12 h-12 rounded-full bg-white border border-zinc-200 text-zinc-400 flex items-center justify-center hover:bg-zinc-100 hover:text-red-600 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-2xl">close</span>
                </button>
            </div>

            {{-- Body --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar p-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    {{-- Left Column: Formulation --}}
                    <div class="lg:col-span-7 space-y-6">
                        
                        {{-- Formulation Card --}}
                        <div class="bg-white rounded-[2rem] border border-zinc-100 p-8 shadow-sm">
                            <div class="flex items-center gap-3 mb-8">
                                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-red-600 text-xl font-bold">edit_note</span>
                                </div>
                                <h3 class="text-lg font-black text-zinc-900 uppercase tracking-tighter">Formulasi Blueprint</h3>
                            </div>

                            <div class="space-y-8">
                                {{-- Judul Kurikulum --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest ml-1">Target Judul Kurikulum</label>
                                    <input type="text" x-model="editBp.title" placeholder="Contoh: Vibration Analysis Masterclass..." 
                                        class="w-full px-5 py-4 bg-zinc-50 border-none rounded-2xl text-sm font-bold focus:ring-4 focus:ring-primary/5 transition-all outline-none">
                                </div>

                                {{-- Kategori Utama Blueprint (Searchable Dropdown) --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest ml-1">Kategori Utama Blueprint</label>
                                    <div class="relative" @click.away="catSearchOpen = false">
                                        <div class="relative cursor-pointer" @click="catSearchOpen = !catSearchOpen; if(catSearchOpen) { catSearch = ''; setTimeout(() => $refs.catSearchInput.focus(), 50) }">
                                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 transition-colors duration-200" :class="editBp.category ? 'text-red-600' : 'text-zinc-400'">category</span>
                                            <input type="text" readonly :value="editBp.category || 'Pilih Kategori Utama...'"
                                                class="w-full pl-12 pr-10 py-4 bg-zinc-50 border-none rounded-2xl text-sm font-bold focus:ring-4 focus:ring-red-600/5 transition-all outline-none cursor-pointer"
                                                :class="editBp.category ? 'text-zinc-900' : 'text-zinc-400'">
                                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-zinc-400 transition-transform duration-200" :class="catSearchOpen ? 'rotate-180' : ''">expand_more</span>
                                        </div>

                                        {{-- Dropdown Search List --}}
                                        <div x-show="catSearchOpen" 
                                            x-transition 
                                            class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-xl border border-zinc-100 overflow-hidden" style="display: none;">
                                            
                                            <div class="p-3 border-b border-zinc-100 bg-zinc-50/50">
                                                <div class="relative">
                                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-sm">search</span>
                                                    <input type="text" x-model="catSearch" placeholder="Ketik untuk mencari kategori..." x-ref="catSearchInput"
                                                        class="w-full pl-9 pr-4 py-2 bg-white border border-zinc-200 rounded-xl text-xs font-medium focus:border-red-500 focus:ring-2 focus:ring-red-600/10 transition-all outline-none">
                                                </div>
                                            </div>
                                            
                                            <div class="max-h-60 overflow-y-auto py-1">
                                                <template x-for="cat in filteredCategories" :key="cat">
                                                    <div @click="editBp.category = cat; catSearchOpen = false" 
                                                        class="px-5 py-3 hover:bg-red-50 cursor-pointer transition-colors flex items-center justify-between group border-b border-zinc-50 last:border-0">
                                                        <span class="text-xs font-bold text-zinc-700 group-hover:text-red-700" x-text="cat"></span>
                                                        <span class="material-symbols-outlined text-red-600 text-base" x-show="editBp.category === cat">check_circle</span>
                                                    </div>
                                                </template>
                                                <template x-if="filteredCategories.length === 0">
                                                    <div class="px-5 py-8 text-center flex flex-col items-center justify-center gap-2">
                                                        <span class="material-symbols-outlined text-zinc-300 text-3xl">search_off</span>
                                                        <p class="text-xs font-bold text-zinc-400">Kategori tidak ditemukan</p>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-zinc-400 font-medium ml-1 mt-1">Kategori utama yang merepresentasikan kurikulum dari gabungan usulan TNA.</p>
                                </div>

                                {{-- Course Objective --}}
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between px-1">
                                        <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Course Objective</label>
                                        <div class="flex items-center gap-0.5">
                                            <button type="button" @mousedown.prevent @click="applyFormat('bold')" class="w-7 h-7 rounded-lg hover:bg-zinc-100 flex items-center justify-center text-zinc-400"><span class="material-symbols-outlined text-xs font-bold">format_bold</span></button>
                                            <button type="button" @mousedown.prevent @click="applyFormat('italic')" class="w-7 h-7 rounded-lg hover:bg-zinc-100 flex items-center justify-center text-zinc-400"><span class="material-symbols-outlined text-xs font-bold">format_italic</span></button>
                                            <button type="button" @mousedown.prevent @click="applyFormat('insertUnorderedList')" class="w-7 h-7 rounded-lg hover:bg-zinc-100 flex items-center justify-center text-zinc-400"><span class="material-symbols-outlined text-xs font-bold">format_list_bulleted</span></button>
                                        </div>
                                    </div>
                                    <div contenteditable="true" 
                                         x-ref="editorObjEdit"
                                         @blur="editBp.course_objective = $el.innerHTML"
                                         x-html="editBp.course_objective"
                                         class="rich-editor w-full min-h-[120px] px-6 py-5 bg-zinc-50 border-none rounded-[1.5rem] text-sm font-medium leading-relaxed focus:ring-4 focus:ring-primary/5 transition-all outline-none empty:before:content-[attr(placeholder)] empty:before:text-zinc-300"
                                         placeholder="Jelaskan apa yang ingin dicapai..."></div>
                                </div>

                                {{-- Course Content --}}
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between px-1">
                                        <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Course Content</label>
                                    </div>
                                    <div contenteditable="true" 
                                         x-ref="editorCntEdit"
                                         @blur="editBp.course_content = $el.innerHTML"
                                         x-html="editBp.course_content"
                                         class="rich-editor w-full min-h-[200px] px-6 py-6 bg-zinc-50 border-none rounded-[1.5rem] text-sm font-medium leading-relaxed focus:ring-4 focus:ring-primary/5 transition-all outline-none empty:before:content-[attr(placeholder)] empty:before:text-zinc-300"
                                         placeholder="Uraikan materi pelatihan per bab..."></div>
                                </div>

                                {{-- Additional Instructions --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest ml-1 italic opacity-60">Instruksi Tambahan untuk SME</label>
                                    <textarea x-model="editBp.sme_instructions" placeholder="Instruksi khusus..." 
                                        class="w-full h-20 px-5 py-4 bg-zinc-50 border-none rounded-xl text-[11px] font-medium italic text-zinc-500 focus:ring-4 focus:ring-primary/5 transition-all outline-none resize-none"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Workshop Card --}}
                        <div class="bg-white rounded-[2rem] border border-zinc-100 p-8 shadow-sm">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xs font-black text-zinc-900 uppercase tracking-widest flex items-center gap-2">
                                    KEBUTUHAN WORKSHOP PRAKTIK
                                    <div class="w-1.5 h-1.5 rounded-full" :class="editBp.need_workshop ? 'bg-red-600' : 'bg-zinc-200'"></div>
                                </h3>
                                <button @click="editBp.need_workshop = !editBp.need_workshop" 
                                    class="w-12 h-7 rounded-full p-1 transition-all duration-300 relative outline-none"
                                    :class="editBp.need_workshop ? 'bg-red-600' : 'bg-zinc-200'">
                                    <div class="w-5 h-5 bg-white rounded-full transition-all duration-300 transform" :class="editBp.need_workshop ? 'translate-x-5' : 'translate-x-0'"></div>
                                </button>
                            </div>

                            <div x-show="editBp.need_workshop" x-collapse>
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest ml-1">Catatan untuk Workshop</label>
                                    <textarea x-model="editBp.workshop_note" placeholder="Sebutkan fokus kebutuhan workshop..." 
                                        class="w-full h-20 px-5 py-4 bg-zinc-50 border-none rounded-xl text-xs font-medium focus:ring-4 focus:ring-primary/5 transition-all outline-none resize-none"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Target & SME Assignment --}}
                    <div class="lg:col-span-5 space-y-6">
                        
                        {{-- Blueprint Snapshot Card --}}
                        <div class="bg-zinc-900 rounded-[2rem] p-8 border border-zinc-800 shadow-sm relative overflow-hidden group">
                            <div class="absolute right-[-10px] top-[-10px] opacity-[0.05] group-hover:scale-110 transition-transform duration-700 pointer-events-none">
                                <span class="material-symbols-outlined text-[100px] text-white">fingerprint</span>
                            </div>
                            <div class="flex items-center gap-2 mb-4">
                                <span class="material-symbols-outlined text-zinc-400 text-lg">info</span>
                                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em]">Blueprint Info</p>
                            </div>
                            <h4 class="text-xl font-black text-white mb-2 leading-tight" x-text="editBp.id"></h4>
                            <div class="space-y-1">
                                <p class="text-[11px] font-medium text-zinc-400 leading-relaxed">Status saat ini: <span class="text-white font-bold uppercase tracking-widest text-[9px] px-2 py-0.5 rounded-full bg-zinc-800 ml-1" x-text="editBp.status"></span></p>
                            </div>
                        </div>

                        {{-- SME Assignment Card --}}
                        <div class="bg-white rounded-[2rem] p-8 border border-zinc-100 shadow-sm relative overflow-hidden group">
                            <div class="absolute right-[-15px] top-[-10px] opacity-[0.02] pointer-events-none group-hover:scale-110 transition-transform duration-500">
                                <span class="material-symbols-outlined text-[90px] font-bold">person_add</span>
                            </div>
                            
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shadow-sm">
                                    <span class="material-symbols-outlined font-bold">assignment_ind</span>
                                </div>
                                <h3 class="text-sm font-black text-zinc-900 uppercase tracking-widest">Assign SME</h3>
                            </div>

                            <div class="space-y-6">
                                {{-- SME Picker --}}
                                <div class="space-y-3">
                                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest ml-1">Ubah Subject Matter Expert</label>
                                    <div class="relative" @click.away="smeSearchOpen = false">
                                        <div class="relative">
                                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 text-xl">search</span>
                                            <input type="text" x-model="smeSearch" @focus="smeSearchOpen = true" placeholder="Ketik nama SME..." 
                                                class="w-full pl-12 pr-4 py-4 bg-zinc-50 border-none rounded-2xl text-sm font-bold shadow-sm focus:ring-4 focus:ring-red-600/5 transition-all outline-none">
                                        </div>
                                        
                                        {{-- SME Dropdown --}}
                                        <div x-show="smeSearchOpen && filteredSMEs.length > 0" 
                                            x-transition 
                                            class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-xl border border-zinc-100 overflow-hidden py-1" style="display: none;">
                                            <template x-for="sme in filteredSMEs" :key="sme.id">
                                                <div @click="selectSME(sme)" class="px-6 py-3 hover:bg-zinc-50 cursor-pointer transition-colors flex items-center justify-between group border-b border-zinc-50 last:border-0">
                                                    <div class="flex items-center gap-3">
                                                        <img :src="sme.avatar" class="w-8 h-8 rounded-full border border-zinc-100">
                                                        <div>
                                                            <p class="text-[11px] font-black text-zinc-800" x-text="sme.name"></p>
                                                            <p class="text-[9px] font-bold text-zinc-400" x-text="sme.position"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                {{-- Selected SME Display --}}
                                <template x-if="editBp.sme">
                                    <div class="bg-zinc-50 p-5 rounded-2xl border border-zinc-100 shadow-sm flex items-center gap-4 animate-in fade-in duration-300">
                                        <img :src="editBp.sme.avatar || 'https://ui-avatars.com/api/?name='+editBp.sme.name" class="w-12 h-12 rounded-full border-2 border-white">
                                        <div class="flex-1">
                                            <p class="text-xs font-black text-zinc-900" x-text="editBp.sme.name"></p>
                                            <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mt-1">SME Terpilih</p>
                                        </div>
                                    </div>
                                </template>

                                {{-- Deadline Selection --}}
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest ml-1">Tenggat Waktu Draft (Deadline)</label>
                                    <div class="relative">
                                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 text-xl">calendar_today</span>
                                        <input type="date" x-model="editBp.deadline" 
                                            class="w-full pl-12 pr-4 py-4 bg-zinc-50 border-none rounded-2xl text-sm font-bold shadow-sm focus:ring-4 focus:ring-red-600/5 transition-all outline-none">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Target Distribusi Card --}}
                        <div class="bg-white rounded-[2rem] border border-zinc-100 p-8 shadow-sm">
                            <h3 class="text-[9px] font-black text-zinc-400 uppercase tracking-widest mb-6">Target Distribusi</h3>
                            
                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <button type="button" @click="editBp.distribution = 'internal'" 
                                    class="p-5 rounded-2xl border-2 transition-all text-left"
                                    :class="editBp.distribution === 'internal' ? 'border-red-600 bg-red-50/30' : 'border-zinc-50 hover:border-zinc-100'">
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="material-symbols-outlined" :class="editBp.distribution === 'internal' ? 'text-red-600' : 'text-zinc-200'">corporate_fare</span>
                                    </div>
                                    <p class="text-[11px] font-black uppercase tracking-widest" :class="editBp.distribution === 'internal' ? 'text-zinc-800' : 'text-zinc-400'">Internal Only</p>
                                </button>
                                
                                <button type="button" @click="editBp.distribution = 'public'" 
                                    class="p-5 rounded-2xl border-2 transition-all text-left"
                                    :class="editBp.distribution === 'public' ? 'border-red-600 bg-red-50/30' : 'border-zinc-50 hover:border-zinc-100'">
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="material-symbols-outlined" :class="editBp.distribution === 'public' ? 'text-red-600' : 'text-zinc-200'">public</span>
                                    </div>
                                    <p class="text-[10px] font-black uppercase tracking-widest" :class="editBp.distribution === 'public' ? 'text-zinc-800' : 'text-zinc-400'">Public Ready</p>
                                </button>
                            </div>

                            {{-- Catatan Rasionalisasi --}}
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest ml-1 opacity-60">Catatan Rasionalisasi</label>
                                <textarea x-model="editBp.distribution_note" placeholder="Berikan alasan pemilihan target distribusi..." 
                                    class="w-full h-20 px-5 py-4 bg-zinc-50 border-none rounded-2xl text-xs font-medium focus:ring-4 focus:ring-primary/5 transition-all outline-none resize-none"></textarea>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Footer Fixed --}}
            <div class="px-10 py-6 bg-white border-t border-zinc-100 flex justify-end gap-3 items-center shrink-0 z-10 shadow-[0_-10px_20px_rgba(0,0,0,0.02)]">
                <button @click="editModalOpen = false" class="px-10 py-4 bg-zinc-50 border border-zinc-200 text-zinc-500 font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-zinc-100 transition-all">Batal</button>
                <button @click="saveEdit()" 
                    class="px-12 py-4 bg-red-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-red-600/20 hover:bg-red-700 transition-all flex items-center gap-3">
                    <span class="material-symbols-outlined text-sm">save</span>
                    Simpan Perubahan
                </button>
            </div>

        </div>
    </div>
</template>
