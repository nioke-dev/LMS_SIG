@extends('layouts.backoffice')

@section('sidebar-nav')
    @include('partials.ac-sidebar')
@endsection

@section('title', 'Merging Hub')

@section('content')
<div x-data="mergingHub()" class="pb-32 relative" x-cloak>

    {{-- Header Section --}}
    <div class="mb-8 text-left">
        <h1 class="text-4xl font-black text-zinc-900 leading-tight tracking-tight mb-2 uppercase">Merging Console</h1>
        <p class="text-zinc-500 font-medium max-w-2xl leading-relaxed text-xs">Pusat konsolidasi usulan TNA. Gabungkan usulan serupa untuk optimasi kurikulum.</p>
    </div>

    @if(session('error'))
        <div class="mb-8 p-6 bg-amber-50 border border-amber-200 rounded-[1.5rem] flex items-center gap-4 text-amber-900 shadow-sm animate-bounce">
            <div class="w-12 h-12 bg-amber-400 text-zinc-900 rounded-2xl flex items-center justify-center font-bold shadow-md shrink-0">
                <span class="material-symbols-outlined text-2xl">warning</span>
            </div>
            <div>
                <h4 class="text-sm font-black uppercase tracking-widest">Peringatan Sistem</h4>
                <p class="text-xs font-bold mt-0.5">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- KPI Cards Row (Medium Scale) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-10">
        <div class="bg-white p-6 rounded-[1.5rem] border border-zinc-100 shadow-sm relative overflow-hidden group">
            <div class="absolute right-[-15px] bottom-[-15px] opacity-[0.03] group-hover:scale-110 transition-transform duration-700">
                <span class="material-symbols-outlined text-[100px]">inventory_2</span>
            </div>
            <p class="text-[10px] font-black tracking-[0.2em] text-zinc-400 uppercase mb-4">TNA Backlog</p>
            <div class="flex items-baseline gap-2">
                <h4 class="text-4xl font-black text-zinc-900 leading-none tracking-tighter" x-text="submissions.length.toLocaleString()"></h4>
                <span class="text-[10px] font-black text-red-600 uppercase tracking-widest">Proposals</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[1.5rem] border border-zinc-100 shadow-sm relative overflow-hidden group">
            <div class="absolute right-[-15px] bottom-[-15px] opacity-[0.03] group-hover:scale-110 transition-transform duration-700">
                <span class="material-symbols-outlined text-[100px]">architecture</span>
            </div>
            <p class="text-[10px] font-black tracking-[0.2em] text-zinc-400 uppercase mb-4">In Progress</p>
            <div class="flex items-baseline gap-2">
                <h4 class="text-4xl font-black text-zinc-900 leading-none tracking-tighter">42</h4>
                <span class="text-[10px] font-black text-red-600 uppercase tracking-widest">Courses</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[1.5rem] border border-zinc-100 shadow-sm relative overflow-hidden group">
            <div class="absolute right-[-15px] bottom-[-15px] opacity-[0.03] group-hover:scale-110 transition-transform duration-700">
                <span class="material-symbols-outlined text-[100px]">psychology</span>
            </div>
            <p class="text-[10px] font-black tracking-[0.2em] text-zinc-400 uppercase mb-4">SME Ready</p>
            <div class="flex items-baseline gap-2">
                <h4 class="text-4xl font-black text-zinc-900 leading-none tracking-tighter">88%</h4>
                <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Availability</span>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white p-5 rounded-[1.5rem] border border-zinc-100 shadow-sm mb-8">
        <div class="flex flex-col md:flex-row gap-4 items-center">
            <div class="relative flex-1 w-full group">
                <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-zinc-400 group-focus-within:text-red-600 transition-colors">search</span>
                <input type="text" x-model="searchQuery" placeholder="Cari ID, Judul, atau Nama LC..." 
                    class="w-full pl-14 pr-6 py-3.5 bg-zinc-50 border-none rounded-xl text-sm font-bold focus:ring-4 focus:ring-red-600/5 transition-all outline-none">
            </div>
            
            <div class="flex items-center gap-2 w-full md:w-auto">
                <button @click="openFilterModal()" class="flex items-center gap-2 px-6 py-3.5 bg-white border border-zinc-100 text-zinc-600 font-black text-[10px] uppercase tracking-widest rounded-xl hover:border-red-600/30 hover:bg-zinc-50 transition-all shadow-sm group">
                    <span class="material-symbols-outlined text-lg group-hover:text-red-600 transition-colors">tune</span>
                    Filters
                    <template x-if="activeFilterCount > 0">
                        <span class="ml-2 w-4 h-4 bg-red-600 text-white rounded-full flex items-center justify-center text-[8px]" x-text="activeFilterCount"></span>
                    </template>
                </button>
                <button class="flex items-center gap-2 px-6 py-3.5 bg-zinc-900 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-red-600 transition-all shadow-lg">
                    <span class="material-symbols-outlined text-lg">download</span>
                    Export
                </button>
            </div>
        </div>
    </div>

    {{-- Table Header Controls (OUTSIDE CARD) --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 px-4 mb-4">
        <div class="flex items-center gap-3">
            <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Tampilkan</span>
            <div class="relative" @click.away="perPageOpen = false">
                <button @click="perPageOpen = !perPageOpen" type="button"
                    class="px-3 py-1.5 bg-white border border-zinc-100 rounded-lg text-[10px] font-black text-zinc-700 flex items-center gap-2 shadow-sm hover:border-red-600/30 transition-all">
                    <span x-text="perPage"></span>
                    <span class="material-symbols-outlined text-sm transition-transform duration-200" :class="perPageOpen ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="perPageOpen" x-transition
                     class="absolute z-40 w-20 mt-1 bg-white border border-zinc-100 rounded-lg shadow-xl overflow-hidden py-1">
                    <template x-for="opt in [5, 10, 25, 50]" :key="opt">
                        <div @click="perPage = opt; perPageOpen = false; currentPage = 1"
                             class="px-3 py-1.5 text-[10px] font-black text-zinc-600 hover:bg-red-50 hover:text-red-600 cursor-pointer transition-colors text-center"
                             :class="perPage === opt ? 'bg-red-50 text-red-600' : ''">
                            <span x-text="opt"></span>
                        </div>
                    </template>
                </div>
            </div>
            <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">data per halaman</span>
        </div>

        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">
            Showing <span class="text-zinc-900" x-text="rangeText"></span> of <span class="text-zinc-900" x-text="filteredItems.length.toLocaleString()"></span> Proposals
        </p>
    </div>

    {{-- Main Table Section --}}
    <div class="bg-white rounded-[1.5rem] border border-zinc-100 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[1300px]">
                <thead>
                    <tr class="bg-zinc-50/50">
                        <th class="px-6 py-4 w-[50px]">
                            <input type="checkbox" @change="toggleAll($event.target.checked)" :checked="selectedItems.length === paginatedItems.length && paginatedItems.length > 0" class="rounded border-zinc-300 text-red-600 focus:ring-red-600 shadow-sm">
                        </th>
                        <th class="px-4 py-4 text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] min-w-[160px]">ID Usulan</th>
                        <th class="px-4 py-4 text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] min-w-[300px]">Judul TNA</th>
                        <th class="px-4 py-4 text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] min-w-[200px]">Kategori</th>
                        <th class="px-4 py-4 text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] min-w-[250px]">Pengusul</th>
                        <th class="px-4 py-4 text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] text-center">Peserta</th>
                        <th class="px-4 py-4 text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] text-center">Urgensi</th>
                        <th class="px-4 py-4 text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] text-center">Status</th>
                        <th class="px-6 py-4 text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] text-right min-w-[100px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    <template x-for="sub in paginatedItems" :key="sub.id">
                        <tr class="hover:bg-zinc-50/30 transition-all group">
                            <td class="px-6 py-5">
                                <input type="checkbox" :value="sub.id" x-model="selectedItems" class="rounded border-zinc-300 text-red-600 focus:ring-red-600 shadow-sm">
                            </td>
                            <td class="px-4 py-5">
                                <span class="text-[10px] font-black text-red-600 tracking-tight" x-text="sub.id"></span>
                            </td>
                            <td class="px-4 py-5" x-data="{ showTooltip: false, tooltipPos: { top: 0, left: 0 } }">
                                <div class="text-[12px] font-black text-zinc-800 leading-snug group-hover:text-red-600 transition-colors cursor-help" 
                                     x-text="sub.title"
                                     @mouseenter="
                                        showTooltip = true;
                                        let rect = $el.getBoundingClientRect();
                                        tooltipPos = { top: rect.bottom, left: rect.left };
                                     "
                                     @mouseleave="showTooltip = false">
                                </div>
                                <div class="text-[9px] text-zinc-400 font-bold uppercase tracking-widest mt-1" x-text="sub.date"></div>

                                {{-- Tooltip UI (Teleported to Body to escape overflow) --}}
                                <template x-teleport="body">
                                    <div x-show="showTooltip" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 -translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         :style="`top: ${tooltipPos.top + 12}px; left: ${tooltipPos.left}px;`"
                                         class="fixed z-[2000] w-[320px] bg-white rounded-2xl shadow-2xl border border-zinc-100 pointer-events-none"
                                         x-cloak>
                                         {{-- Arrow Pointer --}}
                                         <div class="absolute -top-1.5 left-8 w-3 h-3 bg-white border-t border-l border-zinc-100 rotate-45 shadow-[-2px_-2px_5px_rgba(0,0,0,0.02)]"></div>
                                         
                                         <div class="p-6 relative bg-white rounded-t-2xl">
                                            <h5 class="text-[9px] font-black text-zinc-400 uppercase tracking-[0.15em] mb-3">Deskripsi Kebutuhan (Urgensi)</h5>
                                            <p class="text-[11px] text-zinc-600 leading-relaxed font-medium" x-text="sub.description || 'Tidak ada deskripsi detail untuk usulan ini.'"></p>
                                         </div>
                                         <div class="px-6 py-4 bg-zinc-50 border-t border-zinc-100 flex items-center gap-3 rounded-b-2xl">
                                            <span class="material-symbols-outlined text-zinc-400 text-lg">description</span>
                                            <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">
                                                Terdapat <span x-text="sub.documents ? sub.documents.length : 0"></span> Dokumen Pendukung 
                                                <span class="text-zinc-300 mx-1">|</span> 
                                                <span class="text-zinc-500" x-text="sub.documents && sub.documents.length > 0 ? '(' + sub.documents.map(d => d.name).join(', ') + ')' : ''"></span>
                                            </span>
                                         </div>
                                    </div>
                                </template>
                            </td>
                            <td class="px-4 py-5">
                                <div class="text-[10px] font-black text-zinc-900 uppercase tracking-tight" x-text="sub.parent_category"></div>
                                <div class="text-[9px] font-bold text-zinc-400 flex items-center gap-1 mt-0.5">
                                    <span class="material-symbols-outlined text-[10px]">subdirectory_arrow_right</span>
                                    <span x-text="sub.category"></span>
                                </div>
                            </td>
                            <td class="px-4 py-5">
                                <div class="flex items-center gap-2">
                                    <img :src="'https://ui-avatars.com/api/?name=' + sub.proposer_name + '&background=random'" class="w-7 h-7 rounded-full border border-zinc-100 shadow-sm">
                                    <div>
                                        <div class="text-[10px] font-black text-zinc-800" x-text="sub.proposer_name"></div>
                                        <div class="text-[8px] text-zinc-400 font-black uppercase tracking-widest mt-0.5" x-text="sub.company_name"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-5 text-center text-[12px] font-black text-zinc-800" x-text="sub.participants"></td>
                            <td class="px-4 py-5 text-center text-[9px] font-black uppercase tracking-widest" :class="sub.urgency === 'High' ? 'text-red-600' : 'text-zinc-500'" x-text="sub.urgency"></td>
                            <td class="px-4 py-5 text-center">
                                <div class="flex justify-center">
                                    <span class="px-2 py-0.5 rounded-full text-[8px] font-black tracking-widest uppercase border" 
                                          :class="sub.status === 'REJECTED' ? 'bg-red-50 text-red-600 border-red-100' : 'bg-zinc-100 text-zinc-500 border-zinc-100'"
                                          x-text="sub.status"></span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button @click="openDetailModal(sub)" class="w-8 h-8 rounded-lg bg-zinc-50 text-zinc-400 flex items-center justify-center hover:bg-zinc-900 hover:text-white transition-all shadow-sm">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </button>
                                    <button @click="showRejectModal([sub.id])" class="w-8 h-8 rounded-lg bg-zinc-50 text-zinc-400 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                        <span class="material-symbols-outlined text-lg">close</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    {{-- Empty State --}}
                    <tr x-show="filteredItems.length === 0" x-cloak>
                        <td colspan="9" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-zinc-50 rounded-full flex items-center justify-center mb-4">
                                    <span class="material-symbols-outlined text-4xl text-zinc-200">inventory_2</span>
                                </div>
                                <p class="text-zinc-400 font-bold italic tracking-tight text-sm">Tidak ada usulan yang cocok dengan pencarian atau filter.</p>
                                <button @click="resetFilters(); searchQuery = ''"
                                        class="mt-4 text-red-600 font-black text-[10px] uppercase tracking-widest hover:underline">
                                    Reset Semua Filter & Pencarian
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination (ALWAYS VISIBLE - CENTERED) --}}
    <div class="flex items-center justify-center gap-1.5 pt-4">
        <button @click="prevPage()" :disabled="currentPage === 1" 
            class="w-9 h-9 rounded-lg flex items-center justify-center text-zinc-400 hover:bg-zinc-100 disabled:opacity-20 transition-all border border-transparent">
            <span class="material-symbols-outlined text-lg">chevron_left</span>
        </button>
        <template x-for="page in pageNumbers" :key="page">
            <button @click="goToPage(page)" 
                    class="w-9 h-9 rounded-lg text-[10px] font-black transition-all flex items-center justify-center border"
                    :class="page === currentPage ? 'bg-red-600 text-white border-red-600 shadow-lg' : 'text-zinc-500 hover:bg-zinc-100 border-zinc-100'"
                    x-text="page"></button>
        </template>
        <button @click="nextPage()" :disabled="currentPage === totalPages" 
            class="w-9 h-9 rounded-lg flex items-center justify-center text-zinc-400 hover:bg-zinc-100 disabled:opacity-20 transition-all border border-transparent">
            <span class="material-symbols-outlined text-lg">chevron_right</span>
        </button>
    </div>

    {{-- Floating Red Bar (FULL DESIGN RESTORED) --}}
    <div x-show="selectedItems.length > 0" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-y-20 opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         class="fixed bottom-8 right-0 z-[100] flex justify-center pointer-events-none transition-all duration-300"
         :class="sidebarOpen ? 'left-72' : 'left-20'"
         x-cloak>
        <div class="w-full max-w-5xl px-4 pointer-events-auto">
            <div class="bg-red-600 rounded-[2rem] p-3 shadow-2xl flex items-center justify-between border border-white/10">
            <div class="flex items-center gap-6 pl-8 text-white">
                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-red-600 shadow-lg shrink-0">
                    <span class="material-symbols-outlined text-xl font-bold">check</span>
                </div>
                <div>
                    <div class="flex items-center gap-3">
                        <p class="text-sm font-black tracking-tight"><span x-text="selectedItems.length"></span> Usulan Terpilih</p>
                        <template x-if="!canMerge">
                            <span class="bg-amber-400 text-zinc-900 text-[9px] font-black px-3 py-1 rounded-full shadow-md flex items-center gap-1.5 animate-pulse uppercase tracking-widest border border-amber-300">
                                <span class="material-symbols-outlined text-xs">warning</span>
                                Beda Rumpun Kategori: Hanya Bisa Tolak Masal
                            </span>
                        </template>
                        <template x-if="canMerge && selectedParentCategories.length === 1">
                            <span class="bg-white/20 text-white text-[9px] font-black px-3 py-1 rounded-full flex items-center gap-1 uppercase tracking-widest border border-white/20">
                                <span class="material-symbols-outlined text-xs">category</span>
                                <span x-text="'Rumpun: ' + selectedParentCategories[0]"></span>
                            </span>
                        </template>
                    </div>
                    <p class="text-[10px] font-bold text-red-200 uppercase tracking-widest mt-0.5">Total <span class="text-white" x-text="selectedItems.reduce((sum, id) => sum + (submissions.find(s => s.id === id)?.participants || 0), 0)"></span> Calon Peserta</p>
                </div>
            </div>
            <div class="flex items-center gap-2 pr-2">
                <button @click="selectedItems = []" class="px-6 py-4 text-[10px] font-black text-white hover:bg-white/10 rounded-2xl transition-all uppercase tracking-widest">Batal</button>
                <button @click="showRejectModal(selectedItems)" class="px-6 py-4 text-[10px] font-black text-white border border-white/20 hover:bg-white/10 rounded-2xl transition-all uppercase tracking-widest">Tolak Masal</button>
                <button @click="canMerge ? initiateMerge() : alert('Kategori usulan berbeda! Pilihan lintas rumpun kategori tidak dapat di-merge untuk menjaga konsistensi kurikulum.')" 
                        :disabled="!canMerge"
                        class="px-8 py-4 rounded-2xl text-[10px] font-black flex items-center gap-3 transition-all shadow-xl uppercase tracking-widest border"
                        :class="canMerge ? 'bg-white text-red-600 hover:bg-zinc-50 shadow-black/10 border-white' : 'bg-white/20 text-white/40 border-transparent cursor-not-allowed'">
                    <span class="material-symbols-outlined text-xl font-bold">auto_awesome</span>
                    Merge into 1 Blueprint
                </button>
            </div>
            </div>
        </div>
    </div>

    {{-- Reject Modal (FULL RESTORE WITH REASON INPUT) --}}
    <template x-teleport="body">
        <div x-show="rejectModalOpen" class="fixed inset-0 z-[2000] flex items-center justify-center p-4 bg-zinc-900/60 backdrop-blur-sm" x-cloak>
            <div @click.away="rejectModalOpen = false" class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl overflow-hidden flex flex-col">
                <div class="p-8 border-b border-zinc-100 flex justify-between items-center bg-red-50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-red-600 text-white flex items-center justify-center shadow-lg shadow-red-600/20">
                            <span class="material-symbols-outlined text-2xl">block</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-black uppercase tracking-widest text-zinc-900">Konfirmasi Penolakan</h3>
                            <p class="text-[10px] font-bold text-red-600 mt-1 uppercase tracking-widest">Menolak <span x-text="rejectingIds.length"></span> usulan terpilih</p>
                        </div>
                    </div>
                    <button @click="rejectModalOpen = false" class="w-10 h-10 rounded-full bg-white text-zinc-400 flex items-center justify-center hover:text-red-600 transition-colors shadow-sm">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="p-8 space-y-6">
                    <div class="p-4 bg-zinc-50 rounded-2xl border border-zinc-100">
                        <p class="text-[11px] text-zinc-500 font-medium leading-relaxed italic">Anda akan menolak usulan berikut: <span class="font-black text-zinc-900 not-italic" x-text="rejectingIds.join(', ')"></span>. Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-3">Alasan Penolakan <span class="text-red-600">*</span></label>
                        <textarea x-model="rejectReason" placeholder="Tulis alasan penolakan secara detail agar pengusul dapat melakukan perbaikan..."
                            class="w-full p-6 bg-zinc-50 border-none rounded-[1.5rem] text-xs font-bold focus:ring-4 focus:ring-red-600/5 transition-all min-h-[150px] outline-none resize-none"></textarea>
                    </div>
                </div>
                <div class="p-8 pt-0 flex gap-3">
                    <button @click="rejectModalOpen = false" class="flex-1 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-400 hover:text-zinc-900 transition-colors">Batal</button>
                    <button @click="confirmReject()" :disabled="!rejectReason.trim()"
                        class="flex-1 py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-600/20 disabled:opacity-30 disabled:cursor-not-allowed">Konfirmasi Penolakan</button>
                </div>
            </div>
        </div>
    </template>

    {{-- Advanced Filter Modal (Right Drawer Style) --}}
    <template x-teleport="body">
        <div x-show="showFilterModal" class="fixed inset-0 z-[2000]" x-cloak>
            {{-- Backdrop --}}
            <div x-show="showFilterModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showFilterModal = false"
                 class="fixed inset-0 bg-zinc-900/60 backdrop-blur-sm"></div>

            {{-- Drawer Panel --}}
            <div x-show="showFilterModal" 
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="fixed inset-y-0 right-0 w-full max-w-md bg-white shadow-2xl flex flex-col">
                
                {{-- Drawer Header --}}
                <div class="p-8 border-b border-zinc-100 flex justify-between items-center bg-white sticky top-0 z-10">
                    <div>
                        <h3 class="text-sm font-black uppercase tracking-widest text-zinc-900">Advanced Filters</h3>
                        <p class="text-[10px] font-bold text-zinc-400 mt-1 uppercase tracking-widest">Saring Usulan TNA</p>
                    </div>
                    <button @click="showFilterModal = false" class="w-10 h-10 rounded-full bg-zinc-50 flex items-center justify-center text-zinc-400 hover:text-red-600 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                {{-- Drawer Content (Scrollable) --}}
                <div class="flex-1 overflow-y-auto p-8 space-y-8 custom-scrollbar">
                    {{-- Perusahaan (Searchable Single-Select) --}}
                    <div class="relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-4">Perusahaan</label>
                        <button @click="open = !open" type="button" 
                                class="w-full p-4 bg-zinc-50 rounded-2xl text-xs font-bold text-left flex items-center justify-between hover:ring-4 hover:ring-red-600/5 transition-all">
                            <span x-text="tempFilters.company_id ? companies.find(c => c.id == tempFilters.company_id)?.name : 'Semua Perusahaan'"></span>
                            <span class="material-symbols-outlined text-zinc-400 transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        
                        <div x-show="open" x-transition 
                             class="absolute z-[60] w-full mt-2 bg-white rounded-2xl shadow-2xl border border-zinc-100 overflow-hidden">
                            <div class="p-3 border-b border-zinc-50">
                                <input type="text" x-model="search" placeholder="Cari perusahaan..." 
                                       class="w-full p-3 bg-zinc-50 border-none rounded-xl text-xs font-bold outline-none focus:ring-0">
                            </div>
                            <div class="max-h-60 overflow-y-auto no-scrollbar">
                                <button @click="tempFilters.company_id = ''; open = false; search = ''" 
                                        class="w-full p-4 text-left text-xs font-bold hover:bg-zinc-50 transition-colors border-b border-zinc-50">
                                    Semua Perusahaan
                                </button>
                                <template x-for="comp in companies.filter(c => c.name.toLowerCase().includes(search.toLowerCase()))" :key="comp.id">
                                    <button @click="tempFilters.company_id = comp.id; open = false; search = ''" 
                                            class="w-full p-4 text-left text-xs font-bold hover:bg-zinc-50 transition-colors border-b border-zinc-50"
                                            x-text="comp.name"></button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Organizational Levels (Searchable Multi-Select) --}}
                    <template x-for="(level, index) in getAvailableLevels().filter(l => l.order > 0)" :key="level.id">
                        <div class="relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-4" 
                                   x-text="level.name"></label>
                            <button @click="open = !open" type="button" 
                                    :disabled="index > 0 && (!tempFilters.selectedOrgLevels[getAvailableLevels().filter(l => l.order > 0)[index-1].id] || tempFilters.selectedOrgLevels[getAvailableLevels().filter(l => l.order > 0)[index-1].id].length === 0)"
                                    class="w-full p-4 bg-zinc-50 rounded-2xl text-xs font-bold text-left flex items-center justify-between hover:ring-4 hover:ring-red-600/5 transition-all disabled:opacity-50">
                                <span x-text="tempFilters.selectedOrgLevels[level.id]?.length ? tempFilters.selectedOrgLevels[level.id].length + ' ' + level.name + ' dipilih' : 'Semua ' + level.name"></span>
                                <span class="material-symbols-outlined text-zinc-400 transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                            </button>

                            <div x-show="open" x-transition 
                                 class="absolute z-[60] w-full mt-2 bg-white rounded-2xl shadow-2xl border border-zinc-100 overflow-hidden">
                                <div class="p-3 border-b border-zinc-50">
                                    <input type="text" x-model="search" :placeholder="'Cari ' + level.name + '...'" 
                                           class="w-full p-3 bg-zinc-50 border-none rounded-xl text-xs font-bold outline-none focus:ring-0">
                                </div>
                                <div class="max-h-60 overflow-y-auto no-scrollbar">
                                    <template x-for="org in getOrganizationsForLevel(level.id, index === 0 ? null : tempFilters.selectedOrgLevels[getAvailableLevels().filter(l => l.order > 0)[index-1].id]).filter(o => o.name.toLowerCase().includes(search.toLowerCase()))" :key="org.id">
                                        <label class="flex items-center gap-3 p-4 hover:bg-zinc-50 transition-colors cursor-pointer border-b border-zinc-50">
                                            <input type="checkbox" :value="org.id" x-model="tempFilters.selectedOrgLevels[level.id]" 
                                                   @change="handleLevelChange(level.id)"
                                                   class="w-4 h-4 rounded border-zinc-300 text-red-600 focus:ring-red-600/20">
                                            <span class="text-xs font-bold text-zinc-700" x-text="org.name"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Pengusul (Searchable Multi-Select) --}}
                    <div class="relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-4">Nama Pengusul</label>
                        <button @click="open = !open" type="button" 
                                class="w-full p-4 bg-zinc-50 rounded-2xl text-xs font-bold text-left flex items-center justify-between hover:ring-4 hover:ring-red-600/5 transition-all">
                            <span x-text="tempFilters.proposers.length ? tempFilters.proposers.length + ' Pengusul dipilih' : 'Semua Pengusul'"></span>
                            <span class="material-symbols-outlined text-zinc-400 transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        
                        <div x-show="open" x-transition 
                             class="absolute z-[60] w-full mt-2 bg-white rounded-2xl shadow-2xl border border-zinc-100 overflow-hidden">
                            <div class="p-3 border-b border-zinc-50">
                                <input type="text" x-model="search" placeholder="Cari pengusul..." 
                                       class="w-full p-3 bg-zinc-50 border-none rounded-xl text-xs font-bold outline-none focus:ring-0">
                            </div>
                            <div class="max-h-60 overflow-y-auto no-scrollbar">
                                <template x-for="p in proposers.filter(p => p.toLowerCase().includes(search.toLowerCase()))" :key="p">
                                    <label class="flex items-center gap-3 p-4 hover:bg-zinc-50 transition-colors cursor-pointer border-b border-zinc-50">
                                        <input type="checkbox" :value="p" x-model="tempFilters.proposers" 
                                               class="w-4 h-4 rounded border-zinc-300 text-red-600 focus:ring-red-600/20">
                                        <span class="text-xs font-bold text-zinc-700" x-text="p"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Kategori Pelatihan (Searchable Multi-Select) --}}
                    <div class="relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-4">Kategori Pelatihan</label>
                        <button @click="open = !open" type="button" 
                                class="w-full p-4 bg-zinc-50 rounded-2xl text-xs font-bold text-left flex items-center justify-between hover:ring-4 hover:ring-red-600/5 transition-all">
                            <span x-text="tempFilters.categories.length ? tempFilters.categories.length + ' Kategori dipilih' : 'Semua Kategori'"></span>
                            <span class="material-symbols-outlined text-zinc-400 transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        
                        <div x-show="open" x-transition 
                             class="absolute z-[60] w-full mt-2 bg-white rounded-2xl shadow-2xl border border-zinc-100 overflow-hidden">
                            <div class="p-3 border-b border-zinc-50">
                                <input type="text" x-model="search" placeholder="Cari kategori..." 
                                       class="w-full p-3 bg-zinc-50 border-none rounded-xl text-xs font-bold outline-none focus:ring-0">
                            </div>
                            <div class="max-h-60 overflow-y-auto no-scrollbar">
                                <template x-for="cat in categories.filter(c => c.toLowerCase().includes(search.toLowerCase()))" :key="cat">
                                    <label class="flex items-center gap-3 p-4 hover:bg-zinc-50 transition-colors cursor-pointer border-b border-zinc-50">
                                        <input type="checkbox" :value="cat" x-model="tempFilters.categories" 
                                               class="w-4 h-4 rounded border-zinc-300 text-red-600 focus:ring-red-600/20">
                                        <span class="text-xs font-bold text-zinc-700" x-text="cat"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Urgensi Pelatihan (Multi-Select) --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-4">Urgensi Pelatihan</label>
                        <div class="grid grid-cols-3 gap-2">
                            <template x-for="u in ['High', 'Medium', 'Low']" :key="u">
                                <button @click="toggleFilter('urgencies', u)" 
                                    class="py-3 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all border"
                                    :class="tempFilters.urgencies.includes(u) ? 'bg-red-600 text-white border-red-600 shadow-lg shadow-red-600/20' : 'bg-white text-zinc-500 border-zinc-100 hover:border-zinc-300'"
                                    x-text="u"></button>
                            </template>
                        </div>
                    </div>

                    {{-- Status Usulan (Multi-Select) --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-4">Status Usulan</label>
                        <div class="grid grid-cols-2 gap-2">
                            <template x-for="s in ['Submitted', 'Rejected']" :key="s">
                                <button @click="toggleFilter('statuses', s)" 
                                    class="py-3 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all border"
                                    :class="tempFilters.statuses.includes(s) ? 'bg-red-600 text-white border-red-600 shadow-lg shadow-red-600/20' : 'bg-white text-zinc-500 border-zinc-100 hover:border-zinc-300'"
                                    x-text="s"></button>
                            </template>
                    </div>
                </div>

                {{-- Drawer Footer --}}
                <div class="p-8 border-t border-zinc-100 bg-zinc-50/50 flex gap-3 sticky bottom-0">
                    <button @click="resetFilters()" class="flex-1 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-400 hover:text-zinc-900 transition-colors">Reset</button>
                    <button @click="applyFilters()" class="flex-[2] py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-600/20">Terapkan Filter</button>
                </div>
            </div>
        </div>
    </template>

    {{-- Detail Modal --}}
    <template x-teleport="body">
        <div x-show="detailModalOpen" class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-zinc-900/60 backdrop-blur-sm" x-cloak>
            <div @click.away="detailModalOpen = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                
                {{-- Modal Header --}}
                <div class="p-8 border-b border-zinc-100 flex justify-between items-center bg-white sticky top-0 z-10">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-black text-red-600 tracking-tighter" x-text="selectedSub?.id"></span>
                        <span class="px-3 py-1 bg-orange-50 text-orange-600 text-[10px] font-black uppercase tracking-widest rounded-lg" x-text="selectedSub?.status"></span>
                    </div>
                    <button @click="detailModalOpen = false" class="w-10 h-10 rounded-full bg-zinc-50 text-zinc-400 flex items-center justify-center hover:bg-red-50 hover:text-red-600 transition-all">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="flex-1 overflow-y-auto no-scrollbar p-8 space-y-8">
                    
                    {{-- Title Section --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-3 py-1 bg-zinc-100 text-zinc-800 rounded-full text-[9px] font-black uppercase tracking-widest border border-zinc-200" x-text="'Rumpun Induk: ' + selectedSub?.parent_category"></span>
                            <span class="material-symbols-outlined text-zinc-400 text-xs">arrow_forward_ios</span>
                            <span class="text-[10px] font-black text-red-600 uppercase tracking-[0.2em]" x-text="selectedSub?.category"></span>
                        </div>
                        <h2 class="text-2xl font-black text-zinc-900 leading-tight" x-text="selectedSub?.title"></h2>
                    </div>

                    {{-- Meta Info Bar --}}
                    <div class="flex flex-wrap gap-3">
                        <div class="flex items-center gap-2 px-4 py-2 bg-zinc-50 rounded-xl border border-zinc-100">
                            <span class="material-symbols-outlined text-sm text-zinc-400">calendar_today</span>
                            <span class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Tanggal Pengajuan: <span class="text-zinc-900" x-text="selectedSub?.date"></span></span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-red-50 rounded-xl border border-red-100">
                            <span class="material-symbols-outlined text-sm text-red-600 font-bold">priority_high</span>
                            <span class="text-[10px] font-bold text-red-600 uppercase tracking-wider">Urgensi: <span x-text="selectedSub?.urgency"></span></span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-zinc-50 rounded-xl border border-zinc-100">
                            <span class="material-symbols-outlined text-sm text-zinc-400">group</span>
                            <span class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Total Peserta: <span class="text-zinc-900" x-text="selectedSub?.participants + ' Calon Peserta'"></span></span>
                        </div>
                    </div>

                    {{-- Proposer Card --}}
                    <div class="p-6 bg-zinc-50 rounded-3xl border border-zinc-100 flex gap-6">
                        <div class="w-20 h-20 rounded-2xl bg-zinc-200 overflow-hidden flex-shrink-0 shadow-inner border-4 border-white">
                            <img :src="'https://ui-avatars.com/api/?name=' + encodeURIComponent(selectedSub?.proposer_name) + '&background=18181b&color=fff&bold=true'" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 space-y-4">
                            <div>
                                <h4 class="text-lg font-black text-zinc-900" x-text="selectedSub?.proposer_name"></h4>
                                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mt-0.5">Pengusul TNA</p>
                            </div>
                            <div class="grid grid-cols-1 gap-3">
                                <div class="flex items-start gap-3">
                                    <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest w-24 pt-0.5">Perusahaan</span>
                                    <span class="text-[11px] font-bold text-zinc-700 flex-1" x-text="selectedSub?.company_name"></span>
                                </div>
                                <template x-if="selectedSub?.org_path">
                                    <template x-for="org in selectedSub.org_path" :key="org.level">
                                        <div class="flex items-start gap-3">
                                            <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest w-24 pt-0.5" x-text="org.level"></span>
                                            <span class="text-[11px] font-bold text-zinc-700 flex-1" x-text="org.name"></span>
                                        </div>
                                    </template>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="space-y-4">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">Urgensi & Kebutuhan</h3>
                        <p class="text-sm text-zinc-600 leading-relaxed font-medium" x-text="selectedSub?.description || 'Berdasarkan audit teknis, ditemukan kebutuhan peningkatan kompetensi untuk menunjang performa operasional.'"></p>
                    </div>

                    {{-- Supporting Documents --}}
                    <div class="space-y-4">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">Dokumen Pendukung</h3>
                        <div class="grid grid-cols-1 gap-3">
                            <template x-for="(doc, index) in (selectedSub?.documents?.length ? selectedSub.documents : ['Analisis_Kebutuhan_TNA.pdf', 'Data_Peserta_Pelatihan.xlsx'])" :key="index">
                                <div x-data="{ 
                                    get docName() { return typeof doc === 'string' ? doc : doc.name; },
                                    get isPdf() { return this.docName.toLowerCase().includes('.pdf'); },
                                    get docUrl() { return '#' } // Placeholder URL
                                }" class="flex items-center justify-between p-4 bg-white border border-zinc-100 rounded-2xl hover:border-red-600/20 hover:shadow-lg hover:shadow-red-600/5 transition-all group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-zinc-50 flex items-center justify-center text-zinc-400 group-hover:bg-red-50 group-hover:text-red-600 transition-colors">
                                            <span class="material-symbols-outlined" x-text="isPdf ? 'description' : 'table_chart'"></span>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-zinc-900" x-text="docName"></p>
                                            <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest">
                                                1.2 MB • <span x-text="isPdf ? 'PDF Document' : 'Spreadsheet'"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <a :href="docUrl" 
                                       :target="isPdf ? '_blank' : null" 
                                       :download="isPdf ? null : docName"
                                       :title="isPdf ? 'Preview di Tab Baru' : 'Download File'"
                                       class="w-10 h-10 flex items-center justify-center rounded-full text-zinc-400 hover:bg-red-50 hover:text-red-600 transition-colors">
                                        <span class="material-symbols-outlined" x-text="isPdf ? 'open_in_new' : 'download'"></span>
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Daftar Peserta (Mini Datatable) --}}
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">Daftar Peserta (<span x-text="selectedSub?.participants"></span>)</h3>
                            <div class="relative">
                                <input type="text" x-model="participantSearch" @input="participantPage = 1" placeholder="Cari peserta..." 
                                       class="pl-8 pr-4 py-2 bg-zinc-50 border border-zinc-100 rounded-xl text-[10px] font-bold outline-none focus:ring-2 focus:ring-red-600/10 transition-all w-48">
                                <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-sm text-zinc-400">search</span>
                            </div>
                        </div>

                        <div class="bg-white border border-zinc-100 rounded-[2rem] overflow-hidden shadow-sm">
                            <table class="w-full text-left border-collapse border-hidden">
                                <thead class="bg-zinc-50/50 border-b border-zinc-100 text-[9px] font-black uppercase tracking-widest text-zinc-400">
                                    <tr>
                                        <th class="p-5">Peserta</th>
                                        <th class="p-5 text-right">Unit/Jabatan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-50">
                                    <template x-for="p in paginatedParticipants" :key="p.nik">
                                        <tr class="hover:bg-zinc-50/50 transition-colors">
                                            <td class="p-5">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-lg bg-red-600/5 text-red-600 flex items-center justify-center text-[10px] font-black uppercase" x-text="p.name.substring(0,1)"></div>
                                                    <div>
                                                        <p class="text-[11px] font-bold text-zinc-900" x-text="p.name"></p>
                                                        <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest" x-text="p.nik"></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-5 text-right">
                                                <p class="text-[11px] font-bold text-zinc-700" x-text="p.position"></p>
                                                <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest" x-text="p.organization"></p>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="filteredParticipants.length === 0">
                                        <tr>
                                            <td colspan="2" class="p-10 text-center text-[10px] font-bold text-zinc-400 uppercase tracking-widest italic">Tidak ada peserta ditemukan</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            
                            {{-- Participant Pagination Controls --}}
                            <div x-show="totalParticipantPages > 1" class="p-4 bg-zinc-50/50 border-t border-zinc-100 flex items-center justify-between">
                                <span class="text-[9px] font-black uppercase tracking-widest text-zinc-400">Hal. <span class="text-zinc-900" x-text="participantPage"></span> dari <span class="text-zinc-900" x-text="totalParticipantPages"></span></span>
                                <div class="flex gap-1">
                                    <button @click="participantPage--" :disabled="participantPage <= 1" class="w-8 h-8 rounded-lg border flex items-center justify-center disabled:opacity-30 transition-all hover:bg-white border-zinc-200 shadow-sm">
                                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                                    </button>
                                    <button @click="participantPage++" :disabled="participantPage >= totalParticipantPages" class="w-8 h-8 rounded-lg border flex items-center justify-center disabled:opacity-30 transition-all hover:bg-white border-zinc-200 shadow-sm">
                                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="p-8 bg-zinc-50 border-t border-zinc-100 flex gap-4 sticky bottom-0">
                    <button @click="rejectingIds = [selectedSub.id]; rejectModalOpen = true; detailModalOpen = false" 
                            class="flex-1 py-4 bg-white border border-zinc-200 text-red-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-red-50 hover:border-red-100 transition-all">
                        Tolak Usulan
                    </button>
                    <button @click="toggleItem(selectedSub.id); detailModalOpen = false" 
                            class="flex-[2] py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg transition-all flex items-center justify-center gap-2"
                            :class="selectedItems.includes(selectedSub?.id) ? 'bg-zinc-900 text-white shadow-zinc-900/20' : 'bg-red-600 text-white shadow-red-600/20 hover:bg-red-700'">
                        <span class="material-symbols-outlined text-sm" x-text="selectedItems.includes(selectedSub?.id) ? 'check_circle' : 'add_circle'"></span>
                        <span x-text="selectedItems.includes(selectedSub?.id) ? 'Terpilih untuk Merge' : 'Pilih untuk Di-Merge'"></span>
                    </button>
                </div>
            </div>
        </div>
    </template>

    <form x-ref="mergeForm" action="{{ route('admin-coordinator.blueprint.initiate') }}" method="POST" class="hidden">
        @csrf
        <template x-for="id in selectedItems" :key="id">
            <input type="hidden" name="selected_ids[]" :value="id">
        </template>
    </form>
</div>

<script id="submissions-data" type="application/json">@json($submissions)</script>
<script id="org-levels-data" type="application/json">@json($orgLevels)</script>
<script id="organizations-data" type="application/json">@json($organizations)</script>

<script>
    function mergingHub() {
        return {
            submissions: JSON.parse(document.getElementById('submissions-data').textContent),
            orgLevels: JSON.parse(document.getElementById('org-levels-data').textContent),
            organizations: JSON.parse(document.getElementById('organizations-data').textContent),
            companies: @json($companies),
            categories: @json($categories),
            selectedItems: [],
            currentPage: 1,
            perPage: 10,
            perPageOpen: false,
            searchQuery: '',
            showFilterModal: false,
            detailModalOpen: false,
            rejectModalOpen: false,
            selectedSub: null,
            rejectingIds: [],
            rejectReason: '',
            sortColumn: 'id',
            sortDirection: 'desc',
            
            get selectedParentCategories() {
                let cats = this.selectedItems.map(id => {
                    let sub = this.submissions.find(s => s.id === id);
                    return sub ? sub.parent_category : null;
                }).filter(Boolean);
                return [...new Set(cats)];
            },
            get canMerge() {
                return this.selectedParentCategories.length <= 1;
            },

            // Participant Pagination in Modal
            participantSearch: '',
            participantPage: 1,
            participantPerPage: 5,

            get filteredParticipants() {
                if (!this.selectedSub || !this.selectedSub.participants_list) return [];
                let q = this.participantSearch.toLowerCase();
                return this.selectedSub.participants_list.filter(p => 
                    p.name.toLowerCase().includes(q) || p.nik.toLowerCase().includes(q) || p.position.toLowerCase().includes(q)
                );
            },

            get paginatedParticipants() {
                let start = (this.participantPage - 1) * this.participantPerPage;
                return this.filteredParticipants.slice(start, start + this.participantPerPage);
            },

            get totalParticipantPages() {
                return Math.ceil(this.filteredParticipants.length / this.participantPerPage) || 1;
            },
            
            companies: @json($companies),
            orgLevels: @json($orgLevels),
            organizations: @json($organizations),
            proposers: @json($proposers),
            categories: @json($categories),
            selectedFilters: { company_id: '', selectedOrgLevels: {}, proposers: [], categories: [], urgencies: [], statuses: [] },
            tempFilters: { company_id: '', selectedOrgLevels: {}, proposers: [], categories: [], urgencies: [], statuses: [] },
            
            init() {
                this.$watch('searchQuery', () => this.currentPage = 1);
                this.$watch('perPage', () => this.currentPage = 1);
                
                // Reset and Initialize levels when company changes
                this.$watch('tempFilters.company_id', (newCompanyId) => {
                    this.tempFilters.selectedOrgLevels = {};
                    if (newCompanyId) {
                        // Pre-initialize empty arrays for all levels of this company
                        this.orgLevels.filter(l => l.company_id == newCompanyId).forEach(l => {
                            this.tempFilters.selectedOrgLevels[l.id] = [];
                        });
                    }
                });
            },

            get filteredItems() {
                var self = this;
                return this.submissions.filter(function(s) {
                    if (self.searchQuery) {
                        var q = self.searchQuery.toLowerCase();
                        if (!(s.id.toLowerCase().includes(q) || s.title.toLowerCase().includes(q) || s.proposer_name.toLowerCase().includes(q))) return false;
                    }
                    if (self.selectedFilters.company_id && s.company_id != self.selectedFilters.company_id) return false;
                    
                    // Filter by deep organization hierarchy
                    let levels = Object.keys(self.selectedFilters.selectedOrgLevels).sort((a, b) => b - a);
                    for (let levelId of levels) {
                        let selectedIds = self.selectedFilters.selectedOrgLevels[levelId];
                        if (selectedIds && selectedIds.length > 0) {
                            // If multi-selected, check if submission's organization is in the selected IDs
                            if (!selectedIds.includes(s.organization_id.toString())) return false;
                            break; // Only check the most specific level
                        }
                    }

                    if (self.selectedFilters.proposers.length && !self.selectedFilters.proposers.includes(s.proposer_name)) return false;
                    if (self.selectedFilters.categories.length && !self.selectedFilters.categories.includes(s.category)) return false;
                    if (self.selectedFilters.urgencies.length && !self.selectedFilters.urgencies.map(u => u.toLowerCase()).includes((s.urgency || '').toLowerCase())) return false;
                    if (self.selectedFilters.statuses.length && !self.selectedFilters.statuses.map(st => st.toLowerCase()).includes((s.status || '').toLowerCase())) return false;
                    return true;
                });
            },

            get paginatedItems() {
                var start = (this.currentPage - 1) * parseInt(this.perPage);
                return this.filteredItems.slice(start, start + parseInt(this.perPage));
            },

            get totalPages() { return Math.ceil(this.filteredItems.length / parseInt(this.perPage)) || 1; },
            get rangeText() {
                if (this.filteredItems.length === 0) return '0';
                var start = (this.currentPage - 1) * parseInt(this.perPage) + 1;
                var end = Math.min(this.currentPage * parseInt(this.perPage), this.filteredItems.length);
                return start + ' - ' + end;
            },
            get pageNumbers() {
                var total = this.totalPages;
                var pages = [];
                for (var i = 1; i <= total; i++) pages.push(i);
                return pages;
            },
            goToPage(p) { this.currentPage = p; },
            prevPage() { if (this.currentPage > 1) this.currentPage--; },
            nextPage() { if (this.currentPage < this.totalPages) this.currentPage++; },
            toggleAll(checked) { this.selectedItems = checked ? this.paginatedItems.map(function(s) { return s.id }) : []; },
            
            toggleFilter(key, val) {
                if (this.tempFilters[key].includes(val)) this.tempFilters[key] = this.tempFilters[key].filter(v => v !== val);
                else this.tempFilters[key].push(val);
            },

            getAvailableLevels() {
                if (!this.tempFilters.company_id) return [];
                return this.orgLevels
                    .filter(l => l.company_id == this.tempFilters.company_id)
                    .sort((a, b) => a.order - b.order);
            },

            getOrganizationsForLevel(levelId, parentIds) {
                if (!levelId) return [];
                return this.organizations.filter(o => {
                    let matchLevel = o.org_level_id == levelId;
                    if (parentIds && parentIds.length > 0) {
                        return matchLevel && parentIds.includes(o.parent_id.toString());
                    }
                    return matchLevel;
                });
            },

            handleLevelChange(levelId) {
                // When a parent level changes, reset all children levels
                let levels = this.getAvailableLevels();
                let currentLevelIndex = levels.findIndex(l => l.id == levelId);
                
                if (currentLevelIndex !== -1) {
                    for (let i = currentLevelIndex + 1; i < levels.length; i++) {
                        let childLevel = levels[i];
                        this.tempFilters.selectedOrgLevels[childLevel.id] = [];
                    }
                }
            },

            openFilterModal() {
                this.tempFilters = JSON.parse(JSON.stringify(this.selectedFilters));
                this.showFilterModal = true;
            },
            applyFilters() { this.selectedFilters = JSON.parse(JSON.stringify(this.tempFilters)); this.showFilterModal = false; this.currentPage = 1; },
            resetFilters() { 
                this.selectedFilters = { company_id: '', selectedOrgLevels: {}, proposers: [], categories: [], urgencies: [], statuses: [] }; 
                this.tempFilters = JSON.parse(JSON.stringify(this.selectedFilters)); 
                this.showFilterModal = false; 
                this.currentPage = 1; 
            },
            get activeFilterCount() {
                let count = 0;
                if (this.selectedFilters.company_id) count++;
                count += this.selectedFilters.categories.length + this.selectedFilters.urgencies.length + this.selectedFilters.statuses.length;
                return count;
            },
            getCategoryColor(cat) {
                const map = { 'Maintenance Management': 'bg-red-600', 'Design & Engineering': 'bg-orange-500', 'Clinker Production': 'bg-emerald-500', 'Research & Development': 'bg-blue-500' };
                return map[cat] || 'bg-zinc-400';
            },
            openDetailModal(sub) { 
                this.selectedSub = sub; 
                this.detailModalOpen = true; 
                this.participantSearch = '';
                this.participantPage = 1;
            },
            toggleItem(id) {
                if (this.selectedItems.includes(id)) {
                    this.selectedItems = this.selectedItems.filter(i => i !== id);
                } else {
                    this.selectedItems.push(id);
                }
            },
            showRejectModal(ids) { this.rejectingIds = ids; this.rejectReason = ''; this.rejectModalOpen = true; },
            confirmReject() { alert(`Berhasil menolak ${this.rejectingIds.length} usulan.`); this.rejectModalOpen = false; this.selectedItems = []; },
            initiateMerge() { this.$refs.mergeForm.submit(); }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e21d2433; border-radius: 10px; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
