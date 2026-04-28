@extends('layouts.backoffice')

@section('sidebar-nav')
@include('partials.lc-sidebar')
@endsection

@section('title', 'Daftar Usulan TNA')

@section('page-title', 'Management Usulan')

@section('content')
<div x-data="tnaList()" 
     @date-range-updated.window="filterStartDate = $event.detail.start; filterEndDate = $event.detail.end; console.log('Date Updated:', $event.detail)"
     x-init="$watch('searchQuery', () => resetPage()); $watch('filterCategory', () => resetPage()); $watch('filterStatus', () => resetPage()); $watch('filterStartDate', () => resetPage()); $watch('filterEndDate', () => resetPage())" 
     class="space-y-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-on-surface tracking-tight uppercase">
                Daftar <span class="text-primary">Usulan TNA</span>
            </h1>
            <p class="text-on-surface-variant font-medium mt-1">Kelola dan pantau status pengajuan pelatihan departemen Anda.</p>
        </div>
        <div class="flex gap-4">
            <a :href="exportUrl" class="flex items-center gap-2 px-6 py-3.5 bg-white border border-zinc-200 text-zinc-600 font-bold rounded-2xl shadow-sm hover:bg-zinc-50 transition-all active:scale-95">
                <span class="material-symbols-outlined text-xl">download</span>
                Export Excel
            </a>
            <a href="{{ route('learning-coordinator.buat-usulan') }}" class="flex items-center gap-2 px-6 py-3.5 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all active:scale-95">
                <span class="material-symbols-outlined text-xl">add</span>
                Buat Usulan
            </a>
        </div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white p-4 rounded-3xl border border-zinc-100 shadow-sm flex flex-col md:flex-row gap-4 items-center">

        {{-- Search Input --}}
        <div class="relative flex-1 w-full">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400">search</span>
            <input type="text" x-model="searchQuery" placeholder="Cari ID TNA atau Informasi Pelatihan..."
                class="w-full pl-12 pr-4 py-3 bg-zinc-50 border-zinc-100 rounded-2xl text-sm font-medium focus:bg-white focus:ring-4 focus:ring-primary/10 transition-all outline-none">
        </div>

        {{-- Dropdowns --}}
        <div class="flex flex-wrap md:flex-nowrap gap-3 w-full md:w-auto">

            {{-- Category Dropdown --}}
            <div class="relative w-full md:w-auto" @click.away="catOpen = false">
                <button type="button" @click="catOpen = !catOpen; $nextTick(() => { if(catOpen) $refs.catSearch.focus() })"
                    class="w-full md:w-fit min-w-[160px] px-5 py-3 bg-white border border-zinc-100 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] text-zinc-600 flex items-center justify-between gap-x-4 shadow-sm hover:border-primary/30 transition-all">
                    <span x-text="selectedCategory || 'Semua Kategori'" class="truncate"></span>
                    <span class="material-symbols-outlined text-xl transition-transform duration-300" :class="catOpen ? 'rotate-180' : ''">expand_more</span>
                </button>

                <div x-show="catOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="absolute z-50 w-64 mt-1.5 bg-white border border-zinc-100 rounded-2xl shadow-2xl overflow-hidden" style="display: none;">
                    <div class="p-3 border-b border-zinc-50 bg-zinc-50/50" @click.stop>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-lg">search</span>
                            <input type="text" x-ref="catSearch" x-model="catSearchQuery" placeholder="Cari kategori..."
                                class="w-full pl-10 pr-4 py-2 bg-white border border-zinc-100 rounded-xl text-[10px] font-bold uppercase tracking-widest outline-none focus:border-primary/30 transition-all">
                        </div>
                    </div>
                    <div class="max-h-[250px] overflow-y-auto py-2">
                        <div @click="filterCategory = ''; selectedCategory = ''; catOpen = false; catSearchQuery = ''"
                            class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-zinc-400 hover:bg-zinc-50 hover:text-primary cursor-pointer transition-colors"
                            :class="filterCategory === '' ? 'bg-primary/5 text-primary' : ''">
                            Semua Kategori
                        </div>
                        <template x-for="cat in filteredCategoryOptions" :key="cat">
                            <div @click="filterCategory = cat; selectedCategory = cat; catOpen = false; catSearchQuery = ''"
                                class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-zinc-600 hover:bg-primary/5 hover:text-primary cursor-pointer transition-colors"
                                :class="filterCategory === cat ? 'bg-primary/5 text-primary' : ''">
                                <span x-text="cat"></span>
                            </div>
                        </template>
                        <div x-show="filteredCategoryOptions.length === 0" class="px-6 py-6 text-center">
                            <p class="text-[10px] font-bold text-zinc-400 italic">Tidak ditemukan</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Date Range Filter --}}
            <x-tna.date-range-filter />

            {{-- Status Dropdown --}}
            <div class="relative w-full md:w-auto" @click.away="statusOpen = false">
                <button type="button" @click="statusOpen = !statusOpen"
                    class="w-full md:w-fit min-w-[140px] px-5 py-3 bg-white border border-zinc-100 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] text-zinc-600 flex items-center justify-between gap-x-4 shadow-sm hover:border-primary/30 transition-all">
                    <span x-text="selectedStatusLabel || 'Semua Status'" class="truncate"></span>
                    <span class="material-symbols-outlined text-xl transition-transform duration-300" :class="statusOpen ? 'rotate-180' : ''">expand_more</span>
                </button>

                <div x-show="statusOpen" x-transition class="absolute z-50 w-48 mt-1.5 bg-white border border-zinc-100 rounded-2xl shadow-2xl overflow-hidden py-2 right-0" style="display: none;">
                    <div @click="filterStatus = ''; selectedStatusLabel = ''; statusOpen = false"
                        class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-zinc-400 hover:bg-zinc-50 hover:text-primary cursor-pointer transition-colors"
                        :class="filterStatus === '' ? 'bg-primary/5 text-primary' : ''">
                        Semua Status
                    </div>
                    <template x-for="s in statusOptions" :key="s.value">
                        <div @click="filterStatus = s.value; selectedStatusLabel = s.label; statusOpen = false"
                            class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-zinc-600 hover:bg-primary/5 hover:text-primary cursor-pointer transition-colors"
                            :class="filterStatus === s.value ? 'bg-primary/5 text-primary' : ''">
                            <span x-text="s.label"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Toolbar: Entries Selector + Info --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 px-2">
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-zinc-400">Tampilkan</span>
            <div class="relative" @click.away="perPageOpen = false">
                <button @click="perPageOpen = !perPageOpen" type="button"
                    class="px-4 py-2 bg-white border border-zinc-100 rounded-xl text-xs font-black text-zinc-700 flex items-center gap-2 shadow-sm hover:border-primary/30 transition-all">
                    <span x-text="perPage"></span>
                    <span class="material-symbols-outlined text-lg transition-transform duration-200" :class="perPageOpen ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="perPageOpen" x-transition
                     class="absolute z-40 w-24 mt-1 bg-white border border-zinc-100 rounded-xl shadow-xl overflow-hidden py-1" style="display: none;">
                    <template x-for="opt in perPageOptions" :key="opt">
                        <div @click="changePerPage(opt); perPageOpen = false"
                             class="px-4 py-2 text-xs font-bold text-zinc-600 hover:bg-primary/5 hover:text-primary cursor-pointer transition-colors text-center"
                             :class="perPage === opt ? 'bg-primary/5 text-primary' : ''">
                            <span x-text="opt"></span>
                        </div>
                    </template>
                </div>
            </div>
            <span class="text-xs font-bold text-zinc-400">data per halaman</span>
        </div>

        <p class="text-xs font-bold text-zinc-400">
            Menampilkan <span class="text-zinc-700" x-text="rangeText"></span> usulan
        </p>
    </div>

    {{-- Submissions Table --}}
    <div class="bg-white rounded-[2.5rem] border border-zinc-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left min-w-[1000px]">
                <thead>
                    <tr class="bg-zinc-50/50 border-b border-zinc-100">
                        {{-- Sortable: ID TNA --}}
                        <th @click="toggleSort('id')" class="px-8 py-5 text-[10px] font-black text-zinc-400 uppercase tracking-widest cursor-pointer hover:text-zinc-600 transition-colors select-none w-[120px]">
                            <div class="flex items-center gap-1.5">
                                ID TNA
                                <span class="material-symbols-outlined text-sm transition-all"
                                      :class="{
                                          'text-primary rotate-0': sortState('id') === 'asc',
                                          'text-primary rotate-180': sortState('id') === 'desc',
                                          'text-zinc-300': sortState('id') === 'none'
                                      }">arrow_upward</span>
                            </div>
                        </th>
                        {{-- Sortable: Title --}}
                        <th @click="toggleSort('title')" class="px-8 py-5 text-[10px] font-black text-zinc-400 uppercase tracking-widest cursor-pointer hover:text-zinc-600 transition-colors select-none">
                            <div class="flex items-center gap-1.5">
                                Informasi Pelatihan
                                <span class="material-symbols-outlined text-sm transition-all"
                                      :class="{
                                          'text-primary rotate-0': sortState('title') === 'asc',
                                          'text-primary rotate-180': sortState('title') === 'desc',
                                          'text-zinc-300': sortState('title') === 'none'
                                      }">arrow_upward</span>
                            </div>
                        </th>
                        {{-- Sortable: Category --}}
                        <th @click="toggleSort('category')" class="px-8 py-5 text-[10px] font-black text-zinc-400 uppercase tracking-widest cursor-pointer hover:text-zinc-600 transition-colors select-none w-[180px]">
                            <div class="flex items-center gap-1.5">
                                Kategori
                                <span class="material-symbols-outlined text-sm transition-all"
                                      :class="{
                                          'text-primary rotate-0': sortState('category') === 'asc',
                                          'text-primary rotate-180': sortState('category') === 'desc',
                                          'text-zinc-300': sortState('category') === 'none'
                                      }">arrow_upward</span>
                            </div>
                        </th>
                        {{-- Sortable: Status --}}
                        <th @click="toggleSort('status')" class="px-8 py-5 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center cursor-pointer hover:text-zinc-600 transition-colors select-none w-[150px]">
                            <div class="flex items-center justify-center gap-1.5">
                                Status
                                <span class="material-symbols-outlined text-sm transition-all"
                                      :class="{
                                          'text-primary rotate-0': sortState('status') === 'asc',
                                          'text-primary rotate-180': sortState('status') === 'desc',
                                          'text-zinc-300': sortState('status') === 'none'
                                      }">arrow_upward</span>
                            </div>
                        </th>
                        <th class="px-8 py-5 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right w-[180px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    <template x-for="sub in paginatedItems" :key="sub.id">
                        <tr class="hover:bg-zinc-50/30 transition-colors group">
                            <td class="px-8 py-6 font-mono text-xs text-zinc-400" x-text="sub.id"></td>
                            <td class="px-8 py-6">
                                <div class="font-bold text-on-surface tracking-tight group-hover:text-primary transition-colors max-w-md line-clamp-2" x-text="sub.title"></div>
                                <div class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest mt-1" x-text="sub.date"></div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-xs font-bold text-zinc-600 bg-zinc-100 px-3 py-1 rounded-lg" x-text="sub.category"></span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-center">
                                    <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-tighter text-center min-w-[100px]"
                                          :class="statusBadge(sub.status)" x-text="sub.status"></span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-2 items-center">
                                    {{-- Detail Action --}}
                                    <button @click="openDetailModal(sub)" title="Lihat Detail" class="w-10 h-10 rounded-xl bg-zinc-50 text-zinc-400 flex items-center justify-center hover:bg-zinc-200 hover:text-zinc-700 transition-all">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </button>
    
                                    {{-- Resume Action (For Drafts) --}}
                                    <template x-if="sub.status === 'draft'">
                                        <a :href="editUrl(sub.id)" class="px-4 py-2 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-primary hover:text-white transition-all whitespace-nowrap">
                                            Lanjutkan
                                        </a>
                                    </template>
    
                                    {{-- Edit Action (Hide if draft, disable if approved/rejected) --}}
                                    <a :href="editUrl(sub.id)" 
                                       title="Ubah Usulan"
                                       x-show="sub.status !== 'draft'"
                                       :class="sub.status === 'approved' || sub.status === 'rejected' ? 'opacity-30 cursor-not-allowed pointer-events-none' : 'hover:bg-primary hover:text-white'"
                                       class="w-10 h-10 rounded-xl bg-zinc-50 text-zinc-400 flex items-center justify-center transition-all">
                                        <span class="material-symbols-outlined text-xl">edit</span>
                                    </a>
    
                                    {{-- Delete Action (Disable if approved/rejected) --}}
                                    <button title="Hapus Usulan"
                                            @click="confirmDelete(sub.id)"
                                            :class="sub.status === 'approved' || sub.status === 'rejected' ? 'opacity-30 cursor-not-allowed pointer-events-none' : 'hover:bg-red-500 hover:text-white'"
                                            class="w-10 h-10 rounded-xl bg-zinc-50 text-zinc-400 flex items-center justify-center transition-all">
                                        <span class="material-symbols-outlined text-xl">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
    
                    {{-- Empty State --}}
                    <tr x-show="filteredItems.length === 0">
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-zinc-50 rounded-full flex items-center justify-center mb-4">
                                    <span class="material-symbols-outlined text-4xl text-zinc-200">inventory_2</span>
                                </div>
                                <p class="text-zinc-400 font-bold italic tracking-tight">Tidak ada usulan yang cocok dengan filter.</p>
                                <button @click="searchQuery = ''; filterCategory = ''; filterStatus = ''; selectedCategory = ''; selectedStatusLabel = ''; filterStartDate = null; filterEndDate = null; $dispatch('reset-date-filter')"
                                        class="mt-4 text-primary font-black text-xs uppercase hover:underline">
                                    Reset Semua Filter
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>

    {{-- Pagination Controls --}}
    <div x-show="totalPages > 1" class="flex items-center justify-center gap-2 pt-2">
        {{-- Prev --}}
        <button @click="prevPage()" :disabled="currentPage === 1"
                class="w-10 h-10 rounded-xl flex items-center justify-center text-zinc-400 transition-all"
                :class="currentPage === 1 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-zinc-100 hover:text-zinc-700'">
            <span class="material-symbols-outlined text-xl">chevron_left</span>
        </button>

        {{-- Page Numbers --}}
        <template x-for="page in pageNumbers" :key="'p'+page">
            <button @click="goToPage(page)"
                    class="min-w-[40px] h-10 rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center transition-all"
                    :class="page === currentPage 
                        ? 'bg-primary text-white shadow-lg shadow-primary/20' 
                        : (page === '...' ? 'cursor-default text-zinc-300' : 'text-zinc-500 hover:bg-zinc-100 hover:text-zinc-700')"
                    :disabled="page === '...'">
                <span x-text="page"></span>
            </button>
        </template>

        {{-- Next --}}
        <button @click="nextPage()" :disabled="currentPage === totalPages"
                class="w-10 h-10 rounded-xl flex items-center justify-center text-zinc-400 transition-all"
                :class="currentPage === totalPages ? 'opacity-30 cursor-not-allowed' : 'hover:bg-zinc-100 hover:text-zinc-700'">
            <span class="material-symbols-outlined text-xl">chevron_right</span>
        </button>
    </div>

    {{-- Detail Modal --}}
    <template x-teleport="body">
        <div x-show="detailModalOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-zinc-900/60 backdrop-blur-md"
             style="display: none;"
             @click.self="detailModalOpen = false">
            
            <div x-show="detailModalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                
                {{-- Modal Header --}}
                <div class="relative p-8 pb-4 flex justify-between items-start shrink-0">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-primary bg-primary/5 px-3 py-1 rounded-full" x-text="selectedSub?.id"></span>
                        <h2 class="text-2xl font-black text-on-surface mt-3 leading-tight" x-text="selectedSub?.title"></h2>
                    </div>
                    <button @click="detailModalOpen = false" class="w-10 h-10 rounded-full bg-zinc-50 text-zinc-400 flex items-center justify-center hover:bg-zinc-100 hover:text-zinc-900 transition-all">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                {{-- Modal Body (Scrollable) --}}
                <div class="p-8 pt-4 space-y-8 overflow-y-auto flex-1 no-scrollbar">
                    {{-- Status & Info Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-4 bg-zinc-50 rounded-2xl">
                            <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">Status</p>
                            <span class="text-[10px] font-black uppercase px-2 py-1 rounded-lg" :class="statusBadge(selectedSub?.status)" x-text="selectedSub?.status"></span>
                        </div>
                        <div class="p-4 bg-zinc-50 rounded-2xl">
                            <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">Kategori</p>
                            <p class="text-xs font-bold text-zinc-700" x-text="selectedSub?.category"></p>
                        </div>
                        <div class="p-4 bg-zinc-50 rounded-2xl">
                            <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">Urgensi</p>
                            <p class="text-xs font-bold text-zinc-700" x-text="selectedSub?.urgency"></p>
                        </div>
                        <div class="p-4 bg-zinc-50 rounded-2xl">
                            <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">Peserta</p>
                            <p class="text-xs font-bold text-zinc-700" x-text="(selectedSub?.participants || 0) + ' Orang'"></p>
                        </div>
                    </div>

                    {{-- Admin Feedback (Only if approved/rejected and has feedback) --}}
                    <template x-if="selectedSub?.admin_feedback">
                        <div class="p-6 rounded-3xl border-2 border-dashed" 
                             :class="selectedSub?.status === 'approved' ? 'bg-green-50/50 border-green-200' : 'bg-red-50/50 border-red-200'">
                            <h3 class="text-[10px] font-black uppercase tracking-widest mb-3 flex items-center gap-2"
                                :class="selectedSub?.status === 'approved' ? 'text-green-600' : 'text-red-600'">
                                <span class="material-symbols-outlined text-sm">chat_bubble</span>
                                Feedback Admin Coordinator
                            </h3>
                            <p class="text-sm font-medium leading-relaxed" 
                               :class="selectedSub?.status === 'approved' ? 'text-green-800' : 'text-red-800'"
                               x-text="selectedSub?.admin_feedback"></p>
                        </div>
                    </template>

                    {{-- Description --}}
                    <div class="space-y-3">
                        <h3 class="text-sm font-black text-zinc-700 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                            Deskripsi Usulan
                        </h3>
                        <div class="p-6 bg-zinc-50 rounded-3xl border border-zinc-100">
                            <p class="text-sm text-zinc-600 leading-relaxed font-medium italic" x-text="selectedSub?.description || 'Tidak ada deskripsi tambahan.'"></p>
                        </div>
                    </div>

                    {{-- Participants List --}}
                    <div class="space-y-4">
                        <h3 class="text-sm font-black text-zinc-700 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                            Daftar Peserta
                        </h3>
                        <div class="bg-white rounded-3xl border border-zinc-100 overflow-hidden">
                            <table class="w-full text-left">
                                <thead class="bg-zinc-50">
                                    <tr>
                                        <th class="px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Nama Peserta</th>
                                        <th class="px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">NIK</th>
                                        <th class="px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Jabatan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-50">
                                    <template x-for="p in selectedSub?.participants_list" :key="p.nik">
                                        <tr>
                                            <td class="px-6 py-4 text-xs font-bold text-zinc-700" x-text="p.name"></td>
                                            <td class="px-6 py-4 text-xs font-mono text-zinc-400" x-text="p.nik"></td>
                                            <td class="px-6 py-4 text-xs font-bold text-zinc-500 text-right" x-text="p.position"></td>
                                        </tr>
                                    </template>
                                    <template x-if="!selectedSub?.participants_list || selectedSub?.participants_list.length === 0">
                                        <tr>
                                            <td colspan="3" class="px-6 py-8 text-center text-xs text-zinc-400 italic">Belum ada daftar peserta.</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="p-8 pt-4 flex gap-3 shrink-0 border-t border-zinc-50">
                    <template x-if="selectedSub?.status === 'draft'">
                        <a :href="editUrl(selectedSub.id)" class="flex-1 flex items-center justify-center gap-2 py-4 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all active:scale-95">
                            <span class="material-symbols-outlined text-xl">edit_note</span>
                            Lanjutkan Pengisian
                        </a>
                    </template>
                    <button @click="detailModalOpen = false" 
                            class="flex-1 py-4 bg-zinc-100 text-zinc-600 font-bold rounded-2xl hover:bg-zinc-200 transition-all active:scale-95">
                        Tutup Detail
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    function tnaList() {
        return {
            // ====== DataTable: Pagination & Sorting ======
            currentPage: 1,
            perPage: 5,
            perPageOptions: [5, 10, 25, 50],
            perPageOpen: false,
            sortColumn: '',
            sortDirection: 'asc',

            // ====== Modal State ======
            detailModalOpen: false,
            selectedSub: null,

            // ====== Data ======
            allSubmissions: @js($submissions),
            allCategories: @js($categories),
            statusOptions: [
                { value: 'draft', label: 'Draft' },
                { value: 'review', label: 'Reviewing' },
                { value: 'approved', label: 'Approved' },
                { value: 'rejected', label: 'Rejected' },
            ],

            // ====== Filter State ======
            searchQuery: '',
            filterCategory: '',
            filterStatus: '',
            filterStartDate: null,
            filterEndDate: null,

            // ====== Dropdown State ======
            catOpen: false,
            statusOpen: false,
            catSearchQuery: '',
            selectedCategory: '',
            selectedStatusLabel: '',

            // ====== Computed: Filtered Category Options ======
            get filteredCategoryOptions() {
                if (this.catSearchQuery === '') return this.allCategories;
                return this.allCategories.filter(c => c.toLowerCase().includes(this.catSearchQuery.toLowerCase()));
            },

            // ====== Computed: Filtered Items (search + category + status) ======
            get filteredItems() {
                return this.allSubmissions.filter(sub => {
                    if (this.searchQuery) {
                        const q = this.searchQuery.toLowerCase();
                        if (!sub.id.toLowerCase().includes(q) && !sub.title.toLowerCase().includes(q)) return false;
                    }
                    if (this.filterCategory && sub.category !== this.filterCategory) return false;
                    if (this.filterStatus && sub.status !== this.filterStatus) return false;
                    
                    // Date Range Filter
                    if (this.filterStartDate && this.filterEndDate) {
                        // Priority: sub.created_at (raw ISO) > sub.date (formatted)
                        const rawDate = sub.created_at || sub.date;
                        if (!rawDate) return false;

                        const subDate = new Date(rawDate);
                        const start = new Date(this.filterStartDate);
                        const end = new Date(this.filterEndDate);
                        
                        // Reset time for accurate date-only comparison
                        subDate.setHours(0, 0, 0, 0);
                        start.setHours(0, 0, 0, 0);
                        end.setHours(0, 0, 0, 0);
                        
                        if (subDate.getTime() < start.getTime() || subDate.getTime() > end.getTime()) return false;
                    }
                    
                    return true;
                });
            },

            // ====== Computed: Sorted Items ======
            get sortedItems() {
                const data = [...this.filteredItems];
                if (!this.sortColumn) return data;
                return data.sort((a, b) => {
                    let valA = a[this.sortColumn] ?? '';
                    let valB = b[this.sortColumn] ?? '';
                    if (typeof valA === 'string') valA = valA.toLowerCase();
                    if (typeof valB === 'string') valB = valB.toLowerCase();
                    if (valA < valB) return this.sortDirection === 'asc' ? -1 : 1;
                    if (valA > valB) return this.sortDirection === 'asc' ? 1 : -1;
                    return 0;
                });
            },

            // ====== Computed: Paginated Items (final render data) ======
            get paginatedItems() {
                const start = (this.currentPage - 1) * this.perPage;
                return this.sortedItems.slice(start, start + this.perPage);
            },

            // ====== Computed: Total Pages ======
            get totalPages() {
                return Math.ceil(this.sortedItems.length / this.perPage) || 1;
            },

            // ====== Computed: Range Text ======
            get rangeText() {
                const total = this.sortedItems.length;
                if (total === 0) return '0 dari 0';
                const start = (this.currentPage - 1) * this.perPage + 1;
                const end = Math.min(this.currentPage * this.perPage, total);
                return `${start}-${end} dari ${total}`;
            },

            // ====== Computed: Page Numbers ======
            get pageNumbers() {
                const total = this.totalPages;
                const current = this.currentPage;
                const pages = [];
                if (total <= 7) {
                    for (let i = 1; i <= total; i++) pages.push(i);
                } else {
                    pages.push(1);
                    if (current > 3) pages.push('...');
                    const start = Math.max(2, current - 1);
                    const end = Math.min(total - 1, current + 1);
                    for (let i = start; i <= end; i++) pages.push(i);
                    if (current < total - 2) pages.push('...');
                    pages.push(total);
                }
                return pages;
            },

            // ====== Computed: Export URL with active filters ======
            get exportUrl() {
                const params = new URLSearchParams();
                if (this.searchQuery) params.append('search', this.searchQuery);
                if (this.filterCategory) params.append('category', this.filterCategory);
                if (this.filterStatus) params.append('status', this.filterStatus);
                if (this.filterStartDate) params.append('start_date', this.filterStartDate);
                if (this.filterEndDate) params.append('end_date', this.filterEndDate);
                
                const baseUrl = "{{ route('learning-coordinator.export-tna') }}";
                return baseUrl + (params.toString() ? '?' + params.toString() : '');
            },

            // ====== Actions ======
            toggleSort(column) {
                if (this.sortColumn === column) {
                    if (this.sortDirection === 'asc') {
                        this.sortDirection = 'desc';
                    } else {
                        this.sortColumn = '';
                        this.sortDirection = 'asc';
                    }
                } else {
                    this.sortColumn = column;
                    this.sortDirection = 'asc';
                }
                this.currentPage = 1;
            },

            sortState(column) {
                if (this.sortColumn !== column) return 'none';
                return this.sortDirection;
            },

            goToPage(page) {
                if (page === '...' || page < 1 || page > this.totalPages) return;
                this.currentPage = page;
            },
            prevPage() { if (this.currentPage > 1) this.currentPage--; },
            nextPage() { if (this.currentPage < this.totalPages) this.currentPage++; },
            changePerPage(val) { this.perPage = parseInt(val); this.currentPage = 1; },
            resetPage() { this.currentPage = 1; },

            openDetailModal(sub) {
                this.selectedSub = sub;
                this.detailModalOpen = true;
            },

            confirmDelete(id) {
                Alert.confirm('Hapus Usulan?', 'Data yang dihapus tidak dapat dikembalikan. Lanjutkan?')
                    .then((result) => {
                        if (result.isConfirmed) {
                            this.deleteSubmission(id);
                        }
                    });
            },

            deleteSubmission(id) {
                axios({
                    method: 'DELETE',
                    url: `{{ url('learning-coordinator/tna') }}/${id}`,
                    data: { _token: '{{ csrf_token() }}' }
                })
                .then(res => {
                    if(res.data.success) {
                        this.allSubmissions = this.allSubmissions.filter(s => s.id !== id);
                        Alert.success('Terhapus!', 'Usulan berhasil dihapus dari sistem.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Alert.error('Gagal!', 'Terjadi kesalahan saat menghapus data.');
                });
            },

            // ====== Helpers ======
            statusBadge(status) {
                const map = {
                    draft: 'bg-zinc-100 text-zinc-500',
                    review: 'bg-orange-50 text-orange-500',
                    approved: 'bg-green-50 text-green-500',
                    rejected: 'bg-red-50 text-red-500',
                };
                return map[status] || map['draft'];
            },

            editUrl(id) {
                return `{{ url('learning-coordinator/tna') }}/${id}/edit`;
            },

            detailUrl(id) {
                return `{{ url('learning-coordinator/tna') }}/${id}`;
            }
        }
    }
</script>
@endsection