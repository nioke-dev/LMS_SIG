@extends('layouts.backoffice')

@section('sidebar-nav')
    @include('partials.ac-sidebar')
@endsection

@section('title', 'Edit Blueprint Pelatihan')

@section('content')
<div x-data="blueprintEdit()" class="pb-12 relative" x-cloak>
    
    {{-- Top Header Section --}}
    <div class="mb-8">
        <div class="max-w-2xl">
            <h1 class="text-4xl font-black text-zinc-900 leading-tight tracking-tight mb-2 uppercase">Edit Blueprint Pelatihan</h1>
            <p class="text-zinc-500 font-medium leading-relaxed text-xs">Revisi objektif utama, cakupan materi, dan sesuaikan penugasan Subject Matter Expert (SME).</p>
        </div>
    </div>

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
                        <input type="text" x-model="blueprint.title" placeholder="Contoh: Vibration Analysis Masterclass..." 
                            class="w-full px-5 py-4 bg-zinc-50 border-none rounded-2xl text-sm font-bold focus:ring-4 focus:ring-primary/5 transition-all outline-none">
                    </div>

                    {{-- Kategori Utama Blueprint (Searchable Dropdown) --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest ml-1">Kategori Utama Blueprint</label>
                        <div class="relative" @click.away="catSearchOpen = false">
                            <div class="relative cursor-pointer" @click="catSearchOpen = !catSearchOpen">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 transition-colors duration-200" :class="blueprint.category ? 'text-red-600' : 'text-zinc-400'">category</span>
                                <input type="text" readonly :value="blueprint.category || 'Pilih Kategori Utama...'"
                                    class="w-full pl-12 pr-10 py-4 bg-zinc-50 border-none rounded-2xl text-sm font-bold focus:ring-4 focus:ring-red-600/5 transition-all outline-none cursor-pointer"
                                    :class="blueprint.category ? 'text-zinc-900' : 'text-zinc-400'">
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-zinc-400 transition-transform duration-200" :class="catSearchOpen ? 'rotate-180' : ''">expand_more</span>
                            </div>

                            {{-- Dropdown Search List --}}
                            <div x-show="catSearchOpen" 
                                x-transition 
                                class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-xl border border-zinc-100 overflow-hidden" style="display: none;">
                                
                                {{-- Search Input inside Dropdown --}}
                                <div class="p-3 border-b border-zinc-100 bg-zinc-50/50">
                                    <div class="relative">
                                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-sm">search</span>
                                        <input type="text" x-model="catSearch" placeholder="Ketik untuk mencari kategori..." x-ref="catSearchInput"
                                            class="w-full pl-9 pr-4 py-2 bg-white border border-zinc-200 rounded-xl text-xs font-medium focus:border-red-500 focus:ring-2 focus:ring-red-600/10 transition-all outline-none">
                                    </div>
                                </div>
                                
                                {{-- Options List --}}
                                <div class="max-h-60 overflow-y-auto py-1">
                                    <template x-for="cat in filteredCategories" :key="cat">
                                        <div @click="selectCategory(cat)" 
                                            class="px-5 py-3 hover:bg-red-50 cursor-pointer transition-colors flex items-center justify-between group border-b border-zinc-50 last:border-0">
                                            <span class="text-xs font-bold text-zinc-700 group-hover:text-red-700" x-text="cat"></span>
                                            <span class="material-symbols-outlined text-red-600 text-base" x-show="blueprint.category === cat">check_circle</span>
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
                            <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Course Objective (Tujuan Pelatihan)</label>
                            {{-- Simple Editor Toolbar --}}
                            <div class="flex items-center gap-0.5">
                                <button @mousedown.prevent @click="applyFormat('bold', 'editorObj')" class="w-7 h-7 rounded-lg hover:bg-zinc-100 flex items-center justify-center text-zinc-400 transition-colors" title="Bold"><span class="material-symbols-outlined text-xs font-bold">format_bold</span></button>
                                <button @mousedown.prevent @click="applyFormat('italic', 'editorObj')" class="w-7 h-7 rounded-lg hover:bg-zinc-100 flex items-center justify-center text-zinc-400 transition-colors" title="Italic"><span class="material-symbols-outlined text-xs font-bold">format_italic</span></button>
                                <button @mousedown.prevent @click="applyFormat('underline', 'editorObj')" class="w-7 h-7 rounded-lg hover:bg-zinc-100 flex items-center justify-center text-zinc-400 transition-colors" title="Underline"><span class="material-symbols-outlined text-xs font-bold">format_underlined</span></button>
                                <div class="w-px h-3 bg-zinc-200 mx-1"></div>
                                <button @mousedown.prevent @click="applyFormat('insertUnorderedList', 'editorObj')" class="w-7 h-7 rounded-lg hover:bg-zinc-100 flex items-center justify-center text-zinc-400 transition-colors" title="Bullet List"><span class="material-symbols-outlined text-xs font-bold">format_list_bulleted</span></button>
                                <button @mousedown.prevent @click="applyFormat('insertOrderedList', 'editorObj')" class="w-7 h-7 rounded-lg hover:bg-zinc-100 flex items-center justify-center text-zinc-400 transition-colors" title="Number List"><span class="material-symbols-outlined text-xs font-bold">format_list_numbered</span></button>
                                <div class="w-px h-3 bg-zinc-200 mx-1"></div>
                                <button @mousedown.prevent @click="applyFormat('outdent', 'editorObj')" class="w-7 h-7 rounded-lg hover:bg-zinc-100 flex items-center justify-center text-zinc-400 transition-colors" title="Decrease Indent"><span class="material-symbols-outlined text-xs font-bold">format_indent_decrease</span></button>
                                <button @mousedown.prevent @click="applyFormat('indent', 'editorObj')" class="w-7 h-7 rounded-lg hover:bg-zinc-100 flex items-center justify-center text-zinc-400 transition-colors" title="Increase Indent"><span class="material-symbols-outlined text-xs font-bold">format_indent_increase</span></button>
                            </div>
                        </div>
                        <div contenteditable="true" 
                             x-ref="editorObj"
                             @keydown.tab.prevent="handleTab($event, 'editorObj')"
                             @blur="blueprint.objective = $el.innerHTML"
                             class="rich-editor w-full min-h-[120px] px-6 py-5 bg-zinc-50 border-none rounded-[1.5rem] text-sm font-medium leading-relaxed focus:ring-4 focus:ring-primary/5 transition-all outline-none empty:before:content-[attr(placeholder)] empty:before:text-zinc-300"
                             placeholder="Jelaskan apa yang ingin dicapai..."></div>
                    </div>

                    {{-- Course Content --}}
                    <div class="space-y-2">
                        <div class="flex items-center justify-between px-1">
                            <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Course Content</label>
                            <div class="flex items-center gap-0.5">
                                <button @mousedown.prevent @click="applyFormat('bold', 'editorCnt')" class="w-7 h-7 rounded-lg hover:bg-zinc-100 flex items-center justify-center text-zinc-400 transition-colors" title="Bold"><span class="material-symbols-outlined text-xs font-bold">format_bold</span></button>
                                <button @mousedown.prevent @click="applyFormat('italic', 'editorCnt')" class="w-7 h-7 rounded-lg hover:bg-zinc-100 flex items-center justify-center text-zinc-400 transition-colors" title="Italic"><span class="material-symbols-outlined text-xs font-bold">format_italic</span></button>
                                <button @mousedown.prevent @click="applyFormat('underline', 'editorCnt')" class="w-7 h-7 rounded-lg hover:bg-zinc-100 flex items-center justify-center text-zinc-400 transition-colors" title="Underline"><span class="material-symbols-outlined text-xs font-bold">format_underlined</span></button>
                                <div class="w-px h-3 bg-zinc-200 mx-1"></div>
                                <button @mousedown.prevent @click="applyFormat('insertUnorderedList', 'editorCnt')" class="w-7 h-7 rounded-lg hover:bg-zinc-100 flex items-center justify-center text-zinc-400 transition-colors" title="Bullet List"><span class="material-symbols-outlined text-xs font-bold">format_list_bulleted</span></button>
                                <button @mousedown.prevent @click="applyFormat('insertOrderedList', 'editorCnt')" class="w-7 h-7 rounded-lg hover:bg-zinc-100 flex items-center justify-center text-zinc-400 transition-colors" title="Number List"><span class="material-symbols-outlined text-xs font-bold">format_list_numbered</span></button>
                                <div class="w-px h-3 bg-zinc-200 mx-1"></div>
                                <button @mousedown.prevent @click="applyFormat('outdent', 'editorCnt')" class="w-7 h-7 rounded-lg hover:bg-zinc-100 flex items-center justify-center text-zinc-400 transition-colors" title="Decrease Indent"><span class="material-symbols-outlined text-xs font-bold">format_indent_decrease</span></button>
                                <button @mousedown.prevent @click="applyFormat('indent', 'editorCnt')" class="w-7 h-7 rounded-lg hover:bg-zinc-100 flex items-center justify-center text-zinc-400 transition-colors" title="Increase Indent"><span class="material-symbols-outlined text-xs font-bold">format_indent_increase</span></button>
                            </div>
                        </div>
                        <div contenteditable="true" 
                             x-ref="editorCnt"
                             @keydown.tab.prevent="handleTab($event, 'editorCnt')"
                             @blur="blueprint.content = $el.innerHTML"
                             class="rich-editor w-full min-h-[200px] px-6 py-6 bg-zinc-50 border-none rounded-[1.5rem] text-sm font-medium leading-relaxed focus:ring-4 focus:ring-primary/5 transition-all outline-none empty:before:content-[attr(placeholder)] empty:before:text-zinc-300"
                             placeholder="Uraikan materi pelatihan per bab..."></div>
                    </div>

                    {{-- Additional Instructions --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest ml-1 italic opacity-60">Instruksi Tambahan untuk SME</label>
                        <textarea x-model="blueprint.sme_instructions" placeholder="Mohon fokuskan studi kasus kerusakan bearing khusus pada mesin Rotary Kiln pabrik Tuban 4." 
                            class="w-full h-20 px-5 py-4 bg-zinc-50 border-none rounded-xl text-[11px] font-medium italic text-zinc-500 focus:ring-4 focus:ring-primary/5 transition-all outline-none resize-none"></textarea>
                    </div>
                </div>
            </div>

            {{-- Workshop Card --}}
            <div class="bg-white rounded-[2rem] border border-zinc-100 p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xs font-black text-zinc-900 uppercase tracking-widest flex items-center gap-2">
                        KEBUTUHAN WORKSHOP PRAKTIK
                        <div class="w-1.5 h-1.5 rounded-full" :class="blueprint.need_workshop ? 'bg-red-600' : 'bg-zinc-200'"></div>
                    </h3>
                    <button @click="blueprint.need_workshop = !blueprint.need_workshop" 
                        class="w-12 h-7 rounded-full p-1 transition-all duration-300 relative outline-none"
                        :class="blueprint.need_workshop ? 'bg-red-600' : 'bg-zinc-200'">
                        <div class="w-5 h-5 bg-white rounded-full transition-all duration-300 transform" :class="blueprint.need_workshop ? 'translate-x-5' : 'translate-x-0'"></div>
                    </button>
                </div>

                <div x-show="blueprint.need_workshop" x-collapse>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest ml-1">Catatan untuk Workshop</label>
                        <textarea x-model="blueprint.workshop_note" placeholder="Sebutkan fokus kebutuhan workshop..." 
                            class="w-full h-20 px-5 py-4 bg-zinc-50 border-none rounded-xl text-xs font-medium focus:ring-4 focus:ring-primary/5 transition-all outline-none resize-none"></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Snapshot & SME Assignment --}}
        <div class="lg:col-span-5 space-y-6">
            
            {{-- Blueprint Info Card --}}
            <div class="bg-zinc-50 rounded-[2rem] p-8 border border-zinc-100 shadow-sm relative overflow-hidden group">
                <div class="absolute right-[-10px] top-[-10px] opacity-[0.03] group-hover:scale-110 transition-transform duration-700 pointer-events-none">
                    <span class="material-symbols-outlined text-[100px]">info</span>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-zinc-400 text-lg">info</span>
                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em]">Informasi Blueprint</p>
                    </div>
                    <span class="text-[10px] font-black px-3 py-1 rounded-full bg-amber-100 text-amber-700 border border-amber-200 uppercase tracking-widest">{{ $blueprint['status'] }}</span>
                </div>
                <h4 class="text-xl font-black text-zinc-900 mb-2 leading-tight">{{ $blueprint['id'] }}</h4>
                <div class="space-y-1">
                    <p class="text-[11px] font-bold text-zinc-500 leading-relaxed">Kategori: <span class="text-red-600">{{ $blueprint['category'] }}</span></p>
                    <p class="text-[11px] font-bold text-zinc-500 leading-relaxed">Total TNA Tergabung: <span class="text-zinc-800">{{ $blueprint['merged_tna_count'] }} Usulan TNA</span></p>
                </div>
            </div>

            {{-- SME Assignment Card --}}
            <div class="bg-zinc-50 rounded-[2rem] p-8 border border-zinc-100 shadow-sm relative overflow-hidden group">
                <div class="absolute right-[-15px] top-[-10px] opacity-[0.05] pointer-events-none group-hover:scale-110 transition-transform duration-500">
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
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest ml-1">Cari Subject Matter Expert</label>
                        <div class="relative" @click.away="smeSearchOpen = false">
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 text-xl">search</span>
                                <input type="text" x-model="smeSearch" @focus="smeSearchOpen = true" placeholder="Ketik nama SME..." 
                                    class="w-full pl-12 pr-4 py-4 bg-white border-none rounded-2xl text-sm font-bold shadow-sm focus:ring-4 focus:ring-red-600/5 transition-all outline-none">
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
                                        <div class="flex items-center gap-4">
                                            <div class="text-right">
                                                <p class="text-[9px] font-black uppercase" :class="sme.status === 'Available' ? 'text-emerald-500' : 'text-orange-500'" x-text="sme.status"></p>
                                                <p class="text-[8px] font-bold text-zinc-400" x-text="sme.load"></p>
                                            </div>
                                            <span class="material-symbols-outlined text-zinc-300 group-hover:text-red-600 text-base">add_circle</span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Selected SME Display --}}
                    <template x-if="selectedSME">
                        <div class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm flex items-center gap-4 animate-in fade-in slide-in-from-top-2 duration-300 relative">
                            <img :src="selectedSME.avatar" class="w-12 h-12 rounded-full border-2 border-zinc-50">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-xs font-black text-zinc-900" x-text="selectedSME.name"></p>
                                    <button @click="selectedSME = null" class="text-zinc-300 hover:text-red-600 transition-colors">
                                        <span class="material-symbols-outlined text-lg">close</span>
                                    </button>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Status:</p>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-emerald-600" x-text="selectedSME.status || 'Available'"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Beban Aktif:</p>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-red-600" x-text="selectedSME.load || '1 Blueprint Aktif'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Deadline Selection --}}
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest ml-1">Tenggat Waktu Draft (Deadline)</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 text-xl">calendar_today</span>
                            <input type="date" x-model="blueprint.deadline" 
                                class="w-full pl-12 pr-4 py-4 bg-white border-none rounded-2xl text-sm font-bold shadow-sm focus:ring-4 focus:ring-red-600/5 transition-all outline-none">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Target Distribusi Card --}}
            <div class="bg-white rounded-[2rem] border border-zinc-100 p-8 shadow-sm">
                <h3 class="text-[9px] font-black text-zinc-400 uppercase tracking-widest mb-6">Target Distribusi</h3>
                
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <button @click="blueprint.distribution = 'internal'" 
                        class="p-5 rounded-2xl border-2 transition-all text-left"
                        :class="blueprint.distribution === 'internal' ? 'border-red-600 bg-red-50/30' : 'border-zinc-50 hover:border-zinc-100'">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-2xl font-black" :class="blueprint.distribution === 'internal' ? 'text-red-600' : 'text-zinc-200'">0</span>
                            <span class="material-symbols-outlined" :class="blueprint.distribution === 'internal' ? 'text-red-600' : 'text-zinc-200'">corporate_fare</span>
                        </div>
                        <p class="text-[11px] font-black uppercase tracking-widest" :class="blueprint.distribution === 'internal' ? 'text-zinc-800' : 'text-zinc-400'">Internal Only</p>
                    </button>
                    
                    <button @click="blueprint.distribution = 'public'" 
                        class="p-5 rounded-2xl border-2 transition-all text-left"
                        :class="blueprint.distribution === 'public' ? 'border-red-600 bg-red-50/30' : 'border-zinc-50 hover:border-zinc-100'">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-2xl font-black" :class="blueprint.distribution === 'public' ? 'text-red-600' : 'text-zinc-200'">0</span>
                            <span class="material-symbols-outlined" :class="blueprint.distribution === 'public' ? 'text-red-600' : 'text-zinc-200'">public</span>
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-widest" :class="blueprint.distribution === 'public' ? 'text-zinc-800' : 'text-zinc-400'">Public Ready</p>
                    </button>
                </div>

                {{-- Catatan Rasionalisasi --}}
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest ml-1 opacity-60">Catatan Rasionalisasi</label>
                    <textarea x-model="blueprint.rationalization" placeholder="Berikan alasan pemilihan target distribusi..." 
                        class="w-full h-20 px-5 py-4 bg-zinc-50 border-none rounded-2xl text-xs font-medium focus:ring-4 focus:ring-primary/5 transition-all outline-none resize-none"></textarea>
                </div>
            </div>

            {{-- Document Reference Card --}}
            <div class="bg-white rounded-[2rem] border border-zinc-100 p-10 shadow-sm border-dashed border-2 transition-all group"
                 :class="dragOver ? 'border-red-600 bg-red-50/10' : 'border-zinc-100'"
                 @dragover.prevent="dragOver = true"
                 @dragleave.prevent="dragOver = false"
                 @drop.prevent="handleDrop($event)">
                <div class="flex flex-col items-center justify-center text-center">
                    <div class="w-14 h-14 bg-zinc-50 rounded-2xl flex items-center justify-center text-zinc-400 mb-4 group-hover:bg-red-50 group-hover:text-red-600 transition-all shadow-inner"
                         :class="dragOver ? 'bg-red-50 text-red-600 scale-110' : ''">
                        <span class="material-symbols-outlined text-3xl">upload_file</span>
                    </div>
                    <h4 class="text-sm font-black text-zinc-800 mb-1 uppercase tracking-tight">Upload Journal atau E-Book</h4>
                    <p class="text-[10px] font-medium text-zinc-400 max-w-[240px]">
                        <span class="text-red-600 font-bold underline">Mendukung Drag & Drop</span>. Lampirkan banyak referensi pendukung sekaligus untuk membantu SME.
                    </p>
                    <input type="file" class="hidden" x-ref="fileInput" multiple @change="handleFiles($event.target.files)">
                    <button @click="$refs.fileInput.click()" class="mt-6 px-8 py-2.5 bg-zinc-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600 transition-all shadow-lg">Pilih File</button>
                </div>

                {{-- File List --}}
                <div x-show="uploadedFiles.length > 0" class="mt-8 space-y-2" x-collapse>
                    <template x-for="(file, index) in uploadedFiles" :key="index">
                        <div class="flex items-center justify-between p-4 bg-zinc-50 rounded-xl border border-zinc-100 group">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="material-symbols-outlined text-zinc-400">description</span>
                                <p class="text-[11px] font-bold text-zinc-700 truncate" x-text="file.name"></p>
                            </div>
                            <button @click="removeFile(index)" class="text-zinc-300 hover:text-red-600 transition-colors">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- Static Action Buttons --}}
    <div class="mt-12 flex justify-end gap-3 items-center">
        <a href="{{ route('admin-coordinator.blueprint-directory') }}" class="px-10 py-4 bg-white border border-zinc-200 text-zinc-500 font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-zinc-50 transition-all">Batal</a>
        <button @click="submitBlueprint()" 
            class="px-12 py-4 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-red-600/20 hover:scale-[1.02] active:scale-[0.95] transition-all flex items-center gap-3">
            <span class="material-symbols-outlined text-lg">save</span>
            Simpan Perubahan
        </button>
    </div>

</div>

<script>
    function blueprintEdit() {
        return {
            blueprint: {
                title: @json($blueprint['title'] ?? ''),
                category: @json($blueprint['category'] ?? ''),
                objective: @json($blueprint['course_objective'] ?? ''),
                content: @json($blueprint['course_content'] ?? ''),
                sme_instructions: @json($blueprint['sme_instructions'] ?? ''),
                need_workshop: @json($blueprint['need_workshop'] ?? false),
                workshop_note: @json($blueprint['workshop_note'] ?? ''),
                deadline: @json($blueprint['deadline'] ?? '2024-12-15'),
                distribution: @json($blueprint['distribution'] ?? 'internal'),
                rationalization: @json($blueprint['distribution_note'] ?? '')
            },
            smes: @json($smes),
            smeSearch: '',
            smeSearchOpen: false,
            selectedSME: @json(collect($smes)->firstWhere('name', $blueprint['sme']['name'] ?? '')),
            categoriesList: @json($categories),
            catSearch: '',
            catSearchOpen: false,
            uploadedFiles: @json($blueprint['documents'] ?? []),
            dragOver: false,

            init() {
                // Set initial content for rich editors
                this.$nextTick(() => {
                    if (this.$refs.editorObj) this.$refs.editorObj.innerHTML = this.blueprint.objective;
                    if (this.$refs.editorCnt) this.$refs.editorCnt.innerHTML = this.blueprint.content;
                });

                this.$watch('catSearchOpen', value => {
                    if (value) {
                        this.catSearch = '';
                        setTimeout(() => this.$refs.catSearchInput.focus(), 50);
                    }
                });

                this.$nextTick(() => {
                    const sidebarLinks = document.querySelectorAll('nav a, .sidebar-link');
                    sidebarLinks.forEach(link => {
                        if (link.textContent.trim().includes('Manajemen Blueprint') || link.textContent.trim().includes('Blueprint Directory')) {
                            link.classList.add('bg-red-50', 'text-red-600', 'font-black');
                            const icon = link.querySelector('.material-symbols-outlined');
                            if (icon) icon.classList.add('text-red-600');
                        }
                    });
                });
            },

            applyFormat(command, editorRef) {
                this.$refs[editorRef].focus();
                document.execCommand(command, false, null);
                this.blueprint[editorRef === 'editorObj' ? 'objective' : 'content'] = this.$refs[editorRef].innerHTML;
            },

            handleTab(e, editorRef) {
                if (e.shiftKey) {
                    this.applyFormat('outdent', editorRef);
                } else {
                    this.applyFormat('indent', editorRef);
                }
            },

            handleFiles(files) {
                for (let i = 0; i < files.length; i++) {
                    if (!this.uploadedFiles.some(f => f.name === files[i].name)) {
                        this.uploadedFiles.push({
                            name: files[i].name,
                            size: files[i].size
                        });
                    }
                }
            },

            handleDrop(e) {
                this.dragOver = false;
                const files = e.dataTransfer.files;
                this.handleFiles(files);
            },

            removeFile(index) {
                this.uploadedFiles.splice(index, 1);
            },

            get filteredSMEs() {
                if (!this.smeSearch) return this.smes;
                const q = this.smeSearch.toLowerCase();
                return this.smes.filter(s => 
                    s.name.toLowerCase().includes(q) || 
                    s.position.toLowerCase().includes(q)
                );
            },

            selectSME(sme) {
                this.selectedSME = sme;
                this.smeSearch = '';
                this.smeSearchOpen = false;
            },

            get filteredCategories() {
                if (!this.catSearch) return this.categoriesList;
                const q = this.catSearch.toLowerCase();
                return this.categoriesList.filter(c => c.toLowerCase().includes(q));
            },

            selectCategory(cat) {
                this.blueprint.category = cat;
                this.catSearchOpen = false;
            },

            submitBlueprint() {
                if (!this.blueprint.title) {
                    alert('Judul kurikulum wajib diisi.');
                    return;
                }
                if (!this.blueprint.category) {
                    alert('Kategori utama blueprint wajib dipilih.');
                    return;
                }
                if (!this.selectedSME) {
                    alert('Silakan pilih SME terlebih dahulu.');
                    return;
                }
                
                alert('Blueprint berhasil diperbarui!');
                window.location.href = "{{ route('admin-coordinator.blueprint-directory') }}";
            }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    
    /* Multi-level Numbering & Bullets */
    .rich-editor ul {
        list-style-type: disc !important;
        padding-left: 1.5rem !important;
        margin-top: 0.5rem !important;
        margin-bottom: 0.5rem !important;
    }
    
    .rich-editor ol {
        list-style-type: none !important;
        counter-reset: item;
        padding-left: 0 !important;
        margin-top: 0.5rem !important;
        margin-bottom: 0.5rem !important;
    }

    .rich-editor ol li {
        display: table;
        counter-increment: item;
        margin-bottom: 0.5rem;
        width: 100%;
    }

    .rich-editor ol li::before {
        content: counters(item, ".") ". ";
        display: table-cell;
        padding-right: 0.6rem;
        font-weight: 800;
        color: #e21d24; /* Primary Red */
        width: 1%;
        white-space: nowrap;
    }

    /* Nested Indentation */
    .rich-editor blockquote, 
    .rich-editor ul ul, 
    .rich-editor ol ol {
        margin-left: 1.5rem !important;
    }

    [contenteditable]:empty:before {
        content: attr(placeholder);
        color: #d1d5db;
        pointer-events: none;
        display: block;
    }
    
    input[type="date"]::-webkit-calendar-picker-indicator {
        opacity: 0;
        position: absolute;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
</style>
@endsection
