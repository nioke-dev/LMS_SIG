@extends('layouts.backoffice')

@section('sidebar-nav')
    @include('partials.ac-sidebar')
@endsection

@section('title', 'Category Approval Board')

@section('content')
<div x-data="categoryApprovalBoard()" class="pb-32 relative" x-cloak>
    {{-- Header Section --}}
    <div class="mb-8">
        <h1 class="text-4xl font-black text-zinc-900 leading-tight tracking-tight mb-2">Category Approval Board</h1>
        <p class="text-zinc-500 font-medium max-w-2xl leading-relaxed text-xs">Kelola dan validasi usulan penambahan kategori taksonomi baru untuk menjaga integritas data sistem.</p>
    </div>

    {{-- Top Summary Cards Grid (2 Cards matching screenshot) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        {{-- Card 1: Pending Requests --}}
        <div class="bg-white rounded-[2rem] p-8 border border-zinc-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-all">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-2">Menunggu Persetujuan</p>
                <div class="flex items-baseline gap-3">
                    <h3 class="text-5xl font-black text-primary tracking-tight">12</h3>
                    <span class="bg-primary/10 text-primary text-[10px] font-black px-3 py-1 rounded-full border border-primary/20">+3 today</span>
                </div>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-primary/5 text-primary flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                <span class="material-symbols-outlined text-3xl">pending_actions</span>
            </div>
        </div>

        {{-- Card 2: Active Categories --}}
        <div class="bg-white rounded-[2rem] p-8 border border-zinc-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-all">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-2">Total Kategori Aktif</p>
                <div class="flex items-baseline gap-3">
                    <h3 class="text-5xl font-black text-zinc-900 tracking-tight">45</h3>
                    <span class="text-xs font-bold text-zinc-500">Standardized Units</span>
                </div>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-zinc-50 text-zinc-400 flex items-center justify-center group-hover:scale-110 transition-transform duration-500 border border-zinc-100">
                <span class="material-symbols-outlined text-3xl">inventory_2</span>
            </div>
        </div>
    </div>

    {{-- Main Table Container Card --}}
    <div class="bg-white rounded-[2.5rem] border border-zinc-100 shadow-sm overflow-hidden">
        {{-- Card Header --}}
        <div class="p-8 border-b border-zinc-100 bg-zinc-50/50 space-y-6">
            {{-- Top Row: Dynamic Title & Icon --}}
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-white shadow-sm border border-zinc-100 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-2xl" x-text="activeTab === 'pending' ? 'list_alt' : 'category'"></span>
                </div>
                <div>
                    <h2 class="text-lg font-black text-zinc-900 tracking-tight" x-text="activeTab === 'pending' ? 'Daftar Pengajuan Kategori' : 'Daftar Kategori'"></h2>
                    <p class="text-[10px] text-zinc-400 font-bold mt-0.5" x-text="activeTab === 'pending' ? 'Daftar antrean pengajuan kategori taksonomi baru dari para SME' : 'Daftar lengkap kategori yang telah disahkan dan digunakan dalam sistem'"></p>
                </div>
            </div>

            {{-- Bottom Row: Tabs & Right Actions --}}
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pt-2 border-t border-zinc-200/40">
                {{-- Tabs Button --}}
                <div class="flex items-center gap-2 bg-zinc-100 p-1.5 rounded-2xl border border-zinc-200/60 self-start">
                    <button @click="activeTab = 'pending'; pendingPage = 1" :class="activeTab === 'pending' ? 'bg-primary text-white font-black shadow-lg shadow-primary/20' : 'text-zinc-600 font-bold hover:bg-zinc-200/50'" class="px-5 py-2.5 rounded-xl text-xs transition-all flex items-center gap-2">
                        <span>Menunggu Keputusan (12)</span>
                    </button>
                    <button @click="activeTab = 'active'; activePage = 1" :class="activeTab === 'active' ? 'bg-primary text-white font-black shadow-lg shadow-primary/20' : 'text-zinc-600 font-bold hover:bg-zinc-200/50'" class="px-5 py-2.5 rounded-xl text-xs transition-all flex items-center gap-2">
                        <span>Daftar Kategori (45)</span>
                    </button>
                </div>

                {{-- Right Header Actions --}}
                <div class="flex items-center gap-3 self-end lg:self-auto w-full lg:w-auto justify-end">
                    {{-- Search Bar --}}
                    <div class="relative w-full sm:w-64">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400 text-lg pointer-events-none">search</span>
                        <input type="text" x-model="search" placeholder="Cari kategori..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-zinc-200/80 rounded-xl text-xs font-bold text-zinc-800 placeholder-zinc-400 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-sm">
                    </div>

                    {{-- Filter Button --}}
                    <button @click="openFilterModal()" class="flex items-center gap-2 px-5 py-2.5 bg-white border border-zinc-200/80 text-zinc-600 font-black text-xs rounded-xl hover:border-red-600/30 hover:bg-zinc-50 transition-all shadow-sm group shrink-0">
                        <span class="material-symbols-outlined text-base group-hover:text-red-600 transition-colors">tune</span>
                        Filter
                        <template x-if="activeFilterCount > 0">
                            <span class="w-4 h-4 bg-primary text-white rounded-full flex items-center justify-center text-[9px]" x-text="activeFilterCount"></span>
                        </template>
                    </button>

                    {{-- Manual Create Button (Only in Active Tab) --}}
                    <button x-show="activeTab === 'active'" @click="openAddMainCategory()" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="px-5 py-2.5 bg-primary text-white font-black text-xs rounded-xl shadow-lg shadow-primary/20 hover:shadow-primary/40 hover:bg-red-600 transition-all flex items-center gap-2 shrink-0">
                        <span class="material-symbols-outlined text-sm">add</span>
                        Buat Kategori Utama
                    </button>
                </div>
            </div>
        </div>

        {{-- ==========================================
             TAB 1: PENDING REQUESTS
             ========================================== --}}
        <div x-show="activeTab === 'pending'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1600px]">
                    <thead>
                        <tr class="border-b border-zinc-100 bg-zinc-50/30">
                            <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-400">Nama Kategori Usulan</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-400">Urgensi</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-400">Diajukan Oleh</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-400">Tanggal</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-400">Alasan Pengajuan</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-400">Status</th>
                            <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        <template x-for="item in paginatedPending" :key="item.id">
                            <tr class="hover:bg-zinc-50/30 transition-colors group">
                                <td class="px-8 py-5">
                                    <h4 class="text-sm font-black text-zinc-900 group-hover:text-primary transition-colors" x-text="item.name"></h4>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <template x-if="item.urgency_level === 'High'">
                                        <span class="px-3 py-1.5 bg-red-100 text-red-700 border border-red-200 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 shadow-sm w-max">
                                            <span class="material-symbols-outlined text-xs">emergency</span>
                                            HIGH
                                        </span>
                                    </template>
                                    <template x-if="item.urgency_level === 'Medium'">
                                        <span class="px-3 py-1.5 bg-amber-100 text-amber-700 border border-amber-200 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 shadow-sm w-max">
                                            <span class="material-symbols-outlined text-xs">schedule</span>
                                            MEDIUM
                                        </span>
                                    </template>
                                    <template x-if="item.urgency_level?.includes('Low')">
                                        <span class="px-3 py-1.5 bg-blue-100 text-blue-700 border border-blue-200 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 shadow-sm w-max">
                                            <span class="material-symbols-outlined text-xs">low_priority</span>
                                            LOW
                                        </span>
                                    </template>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-primary/10 text-primary font-black text-xs flex items-center justify-center border border-primary/20" x-text="item.submitted_by_initial"></div>
                                        <div>
                                            <p class="text-xs font-bold text-zinc-800" x-text="item.submitted_by_name"></p>
                                            <p class="text-[10px] text-zinc-400 font-bold" x-text="item.submitted_by_dept"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-xs font-bold text-zinc-600" x-text="item.date"></span>
                                </td>
                                <td class="px-6 py-5 max-w-xs">
                                    <div class="border-l-2 border-primary pl-3 py-1 my-1 bg-primary/[0.02] rounded-r-lg">
                                        <p class="text-xs text-zinc-500 font-medium italic line-clamp-2" x-text="`&quot;${item.reason}&quot;`"></p>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <template x-if="item.status === 'pending'">
                                        <span class="bg-amber-100 text-amber-700 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-1 border border-amber-200 w-max">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            MENUNGGU
                                        </span>
                                    </template>
                                    <template x-if="item.status === 'approved'">
                                        <span class="bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-1 border border-emerald-200 w-max">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            DISETUJUI
                                        </span>
                                    </template>
                                    <template x-if="item.status === 'rejected'">
                                        <span class="bg-zinc-100 text-zinc-500 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-1 border border-zinc-200 w-max">
                                            <span class="w-1.5 h-1.5 rounded-full bg-zinc-400"></span>
                                            DITOLAK
                                        </span>
                                    </template>
                                </td>
                                <td class="px-8 py-5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        {{-- Detail Button --}}
                                        <button @click="openDetail(item, 'pending')" class="w-8 h-8 rounded-lg bg-zinc-50 text-zinc-500 hover:bg-zinc-900 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Lihat Detail">
                                            <span class="material-symbols-outlined text-lg">visibility</span>
                                        </button>

                                        {{-- Action Buttons based on status --}}
                                        <template x-if="item.status === 'pending'">
                                            <div class="flex items-center gap-1.5">
                                                <button @click="openApproveModal(item)" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Setujui">
                                                    <span class="material-symbols-outlined text-lg">check</span>
                                                </button>
                                                <button @click="reject(item.id)" class="w-8 h-8 rounded-lg bg-red-50 text-primary hover:bg-primary hover:text-white flex items-center justify-center transition-all shadow-sm" title="Tolak">
                                                    <span class="material-symbols-outlined text-lg">close</span>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        {{-- Empty State --}}
                        <tr x-show="filteredPending.length === 0" x-cloak>
                            <td colspan="6" class="px-8 py-16 text-center bg-zinc-50/30">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="w-16 h-16 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-400 shadow-inner">
                                        <span class="material-symbols-outlined text-3xl">fact_check</span>
                                    </div>
                                    <p class="text-sm font-bold text-zinc-500">Tidak ada pengajuan kategori yang ditemukan</p>
                                    <p class="text-xs text-zinc-400 max-w-sm">Coba sesuaikan kata kunci pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            <div class="p-8 border-t border-zinc-100 flex flex-col sm:flex-row items-center justify-between gap-6 bg-zinc-50/30">
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto justify-between sm:justify-start">
                    <div class="flex items-center gap-2" x-data="{ openPerPage: false }">
                        <span class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Tampilkan:</span>
                        <div class="relative" @click.away="openPerPage = false">
                            <button type="button" @click="openPerPage = !openPerPage" class="flex items-center gap-3 px-4 py-2 bg-white border border-zinc-200 hover:border-zinc-300 rounded-xl text-xs font-bold text-zinc-700 shadow-sm transition-all group focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                                <span x-text="`${pendingPerPage} baris`"></span>
                                <span class="material-symbols-outlined text-zinc-400 group-hover:text-primary transition-transform duration-300 text-sm" :class="openPerPage ? 'rotate-180' : ''">expand_more</span>
                            </button>

                            {{-- Premium Dropup Menu --}}
                            <div x-show="openPerPage" 
                                 x-transition:enter="transition ease-out duration-200" 
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-2" 
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                                 class="absolute left-0 bottom-full mb-2 w-36 bg-white rounded-2xl shadow-2xl border border-zinc-100 py-2 z-[100] overflow-hidden" 
                                 x-cloak>
                                <template x-for="val in [5, 10, 25, 50]" :key="val">
                                    <button type="button" @click="pendingPerPage = val; pendingPage = 1; openPerPage = false" class="w-full px-4 py-2.5 text-left text-xs font-bold transition-all flex items-center justify-between group hover:bg-zinc-50" :class="pendingPerPage === val ? 'text-primary bg-primary/[0.03]' : 'text-zinc-600'">
                                        <span x-text="`${val} baris`"></span>
                                        <span class="material-symbols-outlined text-primary text-sm opacity-0 group-hover:opacity-100 transition-opacity" :class="pendingPerPage === val ? 'opacity-100' : ''">check</span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="text-[10px] font-black uppercase tracking-widest text-zinc-400 hidden sm:block">•</div>
                    <div class="text-[10px] font-black uppercase tracking-widest text-zinc-400">
                        SHOWING <span class="text-zinc-900" x-text="Math.min(filteredPending.length, pendingPerPage)"></span> OF <span class="text-zinc-900" x-text="filteredPending.length"></span> PENDING REQUESTS
                    </div>
                </div>

                {{-- Page Navigation Controls --}}
                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <button @click="if(pendingPage > 1) pendingPage--" :disabled="pendingPage === 1" class="px-5 py-2.5 bg-white border border-zinc-200 text-zinc-600 rounded-xl text-xs font-black shadow-sm hover:bg-zinc-50 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        Previous
                    </button>
                    <span class="text-xs font-black text-zinc-500 px-2" x-text="`Hal ${pendingPage} dari ${totalPendingPages}`"></span>
                    <button @click="if(pendingPage < totalPendingPages) pendingPage++" :disabled="pendingPage === totalPendingPages" class="px-5 py-2.5 bg-primary text-white rounded-xl text-xs font-black shadow-lg shadow-primary/20 hover:shadow-primary/40 hover:bg-red-600 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        Next
                    </button>
                </div>
            </div>
        </div>

        {{-- ==========================================
             TAB 2: ACTIVE CATEGORIES
             ========================================== --}}
        <div x-show="activeTab === 'active'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1400px]">
                    <thead>
                        <tr class="border-b border-zinc-100 bg-zinc-50/30">
                            <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-400">Nama Kategori Aktif</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-400">Total Blueprint/Kursus</th>
                            <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-400 text-right">Status</th>
                        </tr>
                    </thead>
                    {{-- Loop x-for over paginatedActive, creating a separate tbody for each parent category --}}
                    <template x-for="item in paginatedActive" :key="item.id">
                        <tbody x-data="{ expanded: true }" class="divide-y divide-zinc-100 border-b border-zinc-100">
                            {{-- Parent Row --}}
                            <tr class="hover:bg-zinc-50/50 transition-colors group bg-zinc-50/20">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <button @click="expanded = !expanded" class="w-7 h-7 rounded-lg bg-white border border-zinc-200 shadow-sm flex items-center justify-center text-zinc-500 hover:text-primary transition-all">
                                            <span class="material-symbols-outlined text-sm transition-transform duration-300" :class="expanded ? 'rotate-90' : ''">chevron_right</span>
                                        </button>
                                        <div class="w-10 h-10 rounded-2xl bg-primary/10 text-primary flex items-center justify-center border border-primary/20 shrink-0">
                                            <span class="material-symbols-outlined text-xl">folder_open</span>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h4 :class="item.is_legacy ? 'text-zinc-400 line-through' : 'text-zinc-900 group-hover:text-primary'" class="text-sm font-black transition-colors" x-text="item.name"></h4>
                                                <span class="px-2 py-0.5 rounded-md bg-zinc-200 text-zinc-700 text-[9px] font-black uppercase tracking-widest border border-zinc-300/60" x-text="`RUMPUN UTAMA • ${item.children ? item.children.length : 0} SUB`"></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="space-y-0.5">
                                        <p class="text-xs font-bold text-zinc-800" x-text="item.total_blueprints"></p>
                                        <template x-if="item.badge">
                                            <span :class="item.is_legacy ? 'text-zinc-400' : 'text-primary'" class="text-[10px] font-black block" x-text="item.badge"></span>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-3">
                                        {{-- Add Sub-Category Button --}}
                                        <button @click="openAddSubCategory(item)" class="px-3 py-1.5 bg-white border border-zinc-200 hover:border-primary hover:bg-primary hover:text-white text-zinc-600 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all flex items-center gap-1 shadow-sm" title="Tambah Sub-Kategori di bawah rumpun ini">
                                            <span class="material-symbols-outlined text-xs">add</span>
                                            Sub-Kategori
                                        </button>

                                        {{-- Toggle Switch --}}
                                        <button @click="toggleStatus(item)" :class="item.is_active ? 'bg-primary' : 'bg-zinc-200'" class="w-11 h-6 rounded-full p-1 transition-colors relative focus:outline-none shadow-inner" :title="item.is_active ? 'Nonaktifkan Kategori' : 'Aktifkan Kategori'">
                                            <span :class="item.is_active ? 'translate-x-5 bg-white' : 'translate-x-0 bg-white'" class="w-4 h-4 rounded-full shadow-md block transition-transform duration-300"></span>
                                        </button>

                                        {{-- Detail Button --}}
                                        <button @click="openDetail(item, 'active')" class="w-8 h-8 rounded-lg bg-zinc-50 text-zinc-500 hover:bg-zinc-900 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Lihat Detail">
                                            <span class="material-symbols-outlined text-lg">visibility</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Children Rows --}}
                            <template x-if="item.children && item.children.length > 0">
                                <template x-for="child in item.children" :key="child.id">
                                    <tr x-show="expanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="hover:bg-zinc-50/30 transition-colors group bg-white">
                                        <td class="px-8 py-4 pl-16">
                                            <div class="flex items-center gap-3">
                                                <span class="text-zinc-300 font-mono">└─</span>
                                                <div class="w-8 h-8 rounded-xl bg-zinc-100 text-zinc-500 flex items-center justify-center border border-zinc-200 shrink-0">
                                                    <span class="material-symbols-outlined text-base">subdirectory_arrow_right</span>
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <h4 :class="child.is_legacy ? 'text-zinc-400 line-through' : 'text-zinc-800 group-hover:text-primary'" class="text-xs font-black transition-colors" x-text="child.name"></h4>
                                                        <span class="px-2 py-0.5 rounded-md bg-primary/10 text-primary text-[9px] font-black uppercase tracking-widest border border-primary/20">SUB-KATEGORI</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="space-y-0.5">
                                                <p class="text-xs font-bold text-zinc-700" x-text="child.total_blueprints"></p>
                                                <template x-if="child.badge">
                                                    <span :class="child.is_legacy ? 'text-zinc-400' : 'text-primary'" class="text-[10px] font-black block" x-text="child.badge"></span>
                                                </template>
                                            </div>
                                        </td>
                                        <td class="px-8 py-4 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-3">
                                                {{-- Toggle Switch --}}
                                                <button @click="toggleStatus(child)" :class="child.is_active ? 'bg-primary' : 'bg-zinc-200'" class="w-10 h-5 rounded-full p-1 transition-colors relative focus:outline-none shadow-inner" :title="child.is_active ? 'Nonaktifkan Sub-Kategori' : 'Aktifkan Sub-Kategori'">
                                                    <span :class="child.is_active ? 'translate-x-5 bg-white' : 'translate-x-0 bg-white'" class="w-3 h-3 rounded-full shadow-md block transition-transform duration-300"></span>
                                                </button>

                                                {{-- Detail Button --}}
                                                <button @click="openDetail(child, 'active')" class="w-7 h-7 rounded-lg bg-zinc-50 text-zinc-500 hover:bg-zinc-900 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Lihat Detail">
                                                    <span class="material-symbols-outlined text-base">visibility</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                        </tbody>
                    </template>

                    {{-- Empty State --}}
                    <tbody x-show="filteredActive.length === 0" x-cloak>
                        <tr>
                            <td colspan="3" class="px-8 py-16 text-center bg-zinc-50/30">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="w-16 h-16 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-400 shadow-inner">
                                        <span class="material-symbols-outlined text-3xl">category</span>
                                    </div>
                                    <p class="text-sm font-bold text-zinc-500">Tidak ada kategori aktif yang ditemukan</p>
                                    <p class="text-xs text-zinc-400 max-w-sm">Coba sesuaikan kata kunci pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            <div class="p-8 border-t border-zinc-100 flex flex-col sm:flex-row items-center justify-between gap-6 bg-zinc-50/30">
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto justify-between sm:justify-start">
                    <div class="flex items-center gap-2" x-data="{ openPerPageActive: false }">
                        <span class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Tampilkan:</span>
                        <div class="relative" @click.away="openPerPageActive = false">
                            <button type="button" @click="openPerPageActive = !openPerPageActive" class="flex items-center gap-3 px-4 py-2 bg-white border border-zinc-200 hover:border-zinc-300 rounded-xl text-xs font-bold text-zinc-700 shadow-sm transition-all group focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                                <span x-text="`${activePerPage} baris`"></span>
                                <span class="material-symbols-outlined text-zinc-400 group-hover:text-primary transition-transform duration-300 text-sm" :class="openPerPageActive ? 'rotate-180' : ''">expand_more</span>
                            </button>

                            {{-- Premium Dropup Menu --}}
                            <div x-show="openPerPageActive" 
                                 x-transition:enter="transition ease-out duration-200" 
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-2" 
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                                 class="absolute left-0 bottom-full mb-2 w-36 bg-white rounded-2xl shadow-2xl border border-zinc-100 py-2 z-[100] overflow-hidden" 
                                 x-cloak>
                                <template x-for="val in [5, 10, 25, 50]" :key="val">
                                    <button type="button" @click="activePerPage = val; activePage = 1; openPerPageActive = false" class="w-full px-4 py-2.5 text-left text-xs font-bold transition-all flex items-center justify-between group hover:bg-zinc-50" :class="activePerPage === val ? 'text-primary bg-primary/[0.03]' : 'text-zinc-600'">
                                        <span x-text="`${val} baris`"></span>
                                        <span class="material-symbols-outlined text-primary text-sm opacity-0 group-hover:opacity-100 transition-opacity" :class="activePerPage === val ? 'opacity-100' : ''">check</span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="text-[10px] font-black uppercase tracking-widest text-zinc-400 hidden sm:block">•</div>
                    <div class="text-[10px] font-black uppercase tracking-widest text-zinc-400">
                        SHOWING <span class="text-zinc-900" x-text="Math.min(filteredActive.length, activePerPage)"></span> OF <span class="text-zinc-900" x-text="filteredActive.length"></span> ACTIVE KATEGORI
                    </div>
                </div>

                {{-- Page Navigation Controls --}}
                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <button @click="if(activePage > 1) activePage--" :disabled="activePage === 1" class="px-5 py-2.5 bg-white border border-zinc-200 text-zinc-600 rounded-xl text-xs font-black shadow-sm hover:bg-zinc-50 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        Previous
                    </button>
                    <span class="text-xs font-black text-zinc-500 px-2" x-text="`Hal ${activePage} dari ${totalActivePages}`"></span>
                    <button @click="if(activePage < totalActivePages) activePage++" :disabled="activePage === totalActivePages" class="px-5 py-2.5 bg-primary text-white rounded-xl text-xs font-black shadow-lg shadow-primary/20 hover:shadow-primary/40 hover:bg-red-600 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ==========================================
         DETAIL MODAL (PREMIUM VIEW)
         ========================================== --}}
    <template x-teleport="body">
        <div x-show="detailModalOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-zinc-900/60 backdrop-blur-sm"
             @click.self="detailModalOpen = false"
             style="display: none;">
            
            <div x-show="detailModalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="bg-white w-full max-w-3xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] border border-zinc-100">
                
                {{-- Modal Header --}}
                <div class="p-8 border-b border-zinc-100 flex justify-between items-start bg-zinc-50/50 shrink-0">
                    <div class="flex gap-5 w-full items-center">
                        <div class="w-14 h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center shadow-sm border border-primary/20 flex-shrink-0">
                            <span class="material-symbols-outlined text-3xl" x-text="detailType === 'pending' ? 'fact_check' : 'category'"></span>
                        </div>
                        <div class="flex-1 pr-4">
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] bg-zinc-200 text-zinc-700 px-2.5 py-0.5 rounded-md border border-zinc-300/60" x-text="selectedItem?.id"></span>
                                <span class="text-xs font-bold text-zinc-300">•</span>
                                <span class="text-xs font-bold text-zinc-500" x-text="detailType === 'pending' ? 'Pengajuan Kategori Baru' : 'Kategori Aktif Sistem'"></span>
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-zinc-900 leading-tight" x-text="selectedItem?.name"></h2>
                        </div>
                        <button @click="detailModalOpen = false" class="w-10 h-10 rounded-full bg-white border border-zinc-200 text-zinc-400 flex items-center justify-center hover:bg-zinc-100 hover:text-zinc-900 transition-all shadow-sm flex-shrink-0 self-start">
                            <span class="material-symbols-outlined text-xl">close</span>
                        </button>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="p-8 space-y-6 overflow-y-auto flex-1 no-scrollbar bg-background">
                    <template x-if="detailType === 'pending'">
                        <div class="space-y-6">
                            {{-- Submitter Info & Urgency --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                {{-- Submitter Card (Takes 2 columns so the hierarchy has PLENTY of room!) --}}
                                <div class="md:col-span-2 bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm space-y-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-full bg-primary/10 text-primary font-black text-base flex items-center justify-center border border-primary/20 shrink-0">
                                            <span x-text="selectedItem?.submitted_by_initial"></span>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-0.5">Diajukan Oleh</p>
                                            <h4 class="text-base font-black text-zinc-900" x-text="selectedItem?.submitted_by_name"></h4>
                                        </div>
                                    </div>
                                    
                                    {{-- Hierarchy Box --}}
                                    <div class="bg-zinc-50 p-4 rounded-2xl border border-zinc-200/60 space-y-2.5">
                                        {{-- OpCo / Perusahaan --}}
                                        <div class="flex items-center gap-2.5 text-xs font-bold text-primary">
                                            <span class="material-symbols-outlined text-base">apartment</span>
                                            <span x-text="selectedItem?.submitted_by_dept"></span>
                                        </div>
                                        {{-- Dynamic Org Path from Database --}}
                                        <template x-if="selectedItem?.org_path && selectedItem.org_path.length > 0">
                                            <div class="space-y-2.5">
                                                <template x-for="(org, index) in selectedItem.org_path" :key="index">
                                                    <div class="flex items-center gap-2.5 text-xs pl-3 border-l-2 border-zinc-300"
                                                         :style="'margin-left: ' + (index * 12 + 8) + 'px'">
                                                        <span class="material-symbols-outlined text-sm text-zinc-400" x-text="org.level === 'Unit' ? 'workspaces' : (org.level === 'Department' ? 'badge' : 'account_tree')"></span>
                                                        <span class="font-bold text-zinc-400" x-text="org.level + ':'"></span>
                                                        <span class="font-bold text-zinc-700" x-text="org.name"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                {{-- Meta Card (Takes 1 column on the right: Tanggal & Urgensi stacked vertically!) --}}
                                <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm flex flex-col justify-between gap-6">
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-1.5">Tanggal Pengajuan</p>
                                        <p class="text-xs font-bold text-zinc-800 flex items-center gap-2 bg-zinc-50 px-3.5 py-2.5 rounded-2xl border border-zinc-200/60 w-max">
                                            <span class="material-symbols-outlined text-sm text-primary">calendar_today</span>
                                            <span x-text="selectedItem?.date"></span>
                                        </p>
                                    </div>
                                    <hr class="border-zinc-100 my-2">
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-2">Tingkat Urgensi</p>
                                        <template x-if="selectedItem?.urgency_level === 'High'">
                                            <span class="px-4 py-2 bg-red-100 text-red-700 border border-red-200 rounded-2xl text-xs font-black uppercase tracking-wider flex items-center gap-2 shadow-sm w-max">
                                                <span class="material-symbols-outlined text-base">emergency</span>
                                                High
                                            </span>
                                        </template>
                                        <template x-if="selectedItem?.urgency_level === 'Medium'">
                                            <span class="px-4 py-2 bg-amber-100 text-amber-700 border border-amber-200 rounded-2xl text-xs font-black uppercase tracking-wider flex items-center gap-2 shadow-sm w-max">
                                                <span class="material-symbols-outlined text-base">schedule</span>
                                                Medium
                                            </span>
                                        </template>
                                        <template x-if="selectedItem?.urgency_level?.includes('Low')">
                                            <span class="px-4 py-2 bg-blue-100 text-blue-700 border border-blue-200 rounded-2xl text-xs font-black uppercase tracking-wider flex items-center gap-2 shadow-sm w-max">
                                                <span class="material-symbols-outlined text-base">low_priority</span>
                                                Reguler
                                            </span>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            {{-- Deskripsi / Ruang Lingkup --}}
                            <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm space-y-3">
                                <h3 class="text-xs font-black uppercase tracking-widest text-zinc-900 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base text-primary">category</span>
                                    Deskripsi / Ruang Lingkup
                                </h3>
                                <div class="p-4 bg-zinc-50 rounded-2xl border border-zinc-100">
                                    <p class="text-sm text-zinc-600 leading-relaxed font-medium" x-text="selectedItem?.description || 'Tidak ada deskripsi ruang lingkup yang dicantumkan.'"></p>
                                </div>
                            </div>

                            {{-- Alasan Pengajuan --}}
                            <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm space-y-3">
                                <h3 class="text-xs font-black uppercase tracking-widest text-zinc-900 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base text-primary">help</span>
                                    Alasan Pengajuan
                                </h3>
                                <div class="p-4 bg-zinc-50 rounded-2xl border-l-4 border-primary">
                                    <p class="text-sm text-zinc-600 leading-relaxed font-medium italic" x-text="`&quot;${selectedItem?.reason || 'Tidak ada alasan khusus.'}&quot;`"></p>
                                </div>
                            </div>

                            {{-- Catatan Penolakan / Feedback --}}
                            <template x-if="detailType === 'pending' && selectedItem?.status === 'rejected'">
                                <div class="bg-red-50 p-6 rounded-3xl border border-red-100 shadow-sm space-y-3">
                                    <h3 class="text-xs font-black uppercase tracking-widest text-red-800 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-base text-red-600">feedback</span>
                                        Feedback / Alasan Penolakan
                                    </h3>
                                    <div class="p-4 bg-white rounded-2xl border border-red-200/60">
                                        <p class="text-sm text-red-700 leading-relaxed font-bold" x-text="selectedItem?.feedback || 'Tidak ada catatan penolakan yang dicantumkan.'"></p>
                                    </div>
                                </div>
                            </template>

                            {{-- Dokumen Pendukung --}}
                            <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm space-y-4">
                                <h3 class="text-xs font-black uppercase tracking-widest text-zinc-900 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base text-primary">folder_open</span>
                                    Dokumen Pendukung
                                </h3>
                                <template x-if="selectedItem?.documents && selectedItem.documents.length > 0">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <template x-for="(doc, index) in selectedItem.documents" :key="index">
                                            <div class="p-4 bg-zinc-50 border border-zinc-200/80 rounded-2xl flex items-center justify-between gap-4 group hover:bg-zinc-100/80 transition-all">
                                                <div class="flex items-center gap-3 overflow-hidden">
                                                    <div class="w-10 h-10 rounded-xl bg-white border border-zinc-200 flex items-center justify-center shadow-sm shrink-0"
                                                         :class="doc.name.toLowerCase().endsWith('.pdf') ? 'text-red-500' : 'text-emerald-600'">
                                                        <span class="material-symbols-outlined text-xl" x-text="doc.name.toLowerCase().endsWith('.pdf') ? 'picture_as_pdf' : 'description'"></span>
                                                    </div>
                                                    <div class="overflow-hidden">
                                                        <p class="text-xs font-bold text-zinc-800 truncate" x-text="doc.name"></p>
                                                        <p class="text-[10px] text-zinc-500 font-medium" x-text="doc.size"></p>
                                                    </div>
                                                </div>
                                                <div class="shrink-0 flex items-center gap-2">
                                                    <template x-if="doc.name.toLowerCase().endsWith('.pdf')">
                                                        <a :href="doc.url || '#'" target="_blank" class="px-3.5 py-2 bg-zinc-900 hover:bg-zinc-800 active:scale-95 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm flex items-center gap-1.5 no-underline">
                                                            <span class="material-symbols-outlined text-xs">open_in_new</span>
                                                            Preview
                                                        </a>
                                                    </template>
                                                    <template x-if="!doc.name.toLowerCase().endsWith('.pdf')">
                                                        <a :href="doc.url || '#'" :download="doc.name" class="px-3.5 py-2 bg-zinc-900 hover:bg-zinc-800 active:scale-95 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm flex items-center gap-1.5 no-underline">
                                                            <span class="material-symbols-outlined text-xs">download</span>
                                                            Unduh
                                                        </a>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="!selectedItem?.documents || selectedItem.documents.length === 0">
                                    <div class="p-6 bg-zinc-50 rounded-2xl border border-dashed border-zinc-200 text-center">
                                        <p class="text-xs text-zinc-400 font-medium">Tidak ada dokumen pendukung yang dilampirkan pada pengajuan ini.</p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="detailType === 'active'">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Total Blueprint Card --}}
                                <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm flex flex-col justify-center">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-1">Total Blueprint Terdaftar</span>
                                    <span class="text-lg font-black text-zinc-800" x-text="selectedItem?.total_blueprints"></span>
                                    <template x-if="selectedItem?.badge">
                                        <span class="text-primary text-[10px] font-black mt-1 block" x-text="selectedItem?.badge"></span>
                                    </template>
                                </div>

                                {{-- Status Operasional Card --}}
                                <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm flex items-center justify-between gap-4">
                                    <div>
                                        <h3 class="text-xs font-black uppercase tracking-widest text-zinc-900 mb-1">Status Operasional</h3>
                                        <p class="text-[11px] text-zinc-500 font-medium">Ketersediaan untuk inisiasi blueprint.</p>
                                    </div>
                                    <span :class="selectedItem?.is_active ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-zinc-100 text-zinc-500 border-zinc-200'" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest border flex items-center gap-2 shrink-0">
                                        <span :class="selectedItem?.is_active ? 'bg-emerald-500' : 'bg-zinc-400'" class="w-2 h-2 rounded-full shadow-sm"></span>
                                        <span x-text="selectedItem?.is_active ? 'Aktif' : 'Nonaktif'"></span>
                                    </span>
                                </div>
                            </div>

                            {{-- Deskripsi / Ruang Lingkup --}}
                            <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm space-y-3">
                                <h3 class="text-xs font-black uppercase tracking-widest text-zinc-900 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base text-primary">category</span>
                                    Deskripsi / Ruang Lingkup Kompetensi
                                </h3>
                                <div class="p-4 bg-zinc-50 rounded-2xl border border-zinc-100">
                                    <p class="text-sm text-zinc-600 leading-relaxed font-medium" x-text="selectedItem?.description || 'Tidak ada deskripsi ruang lingkup yang dicantumkan.'"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Modal Footer --}}
                <div class="p-6 bg-zinc-50 border-t border-zinc-100 flex gap-4 justify-end items-center">
                    <template x-if="detailType === 'pending' && selectedItem?.status === 'pending'">
                        <div class="flex items-center gap-3 w-full justify-between">
                            <button @click="reject(selectedItem?.id)" class="px-6 py-3.5 bg-red-50 border border-red-200 text-red-600 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-primary hover:text-white transition-all shadow-sm flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">close</span>
                                Tolak Pengajuan
                            </button>
                            <button @click="openApproveModal(selectedItem)" class="px-8 py-3.5 bg-emerald-600 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20 flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">check</span>
                                Setujui Kategori
                            </button>
                        </div>
                    </template>
                    <template x-if="detailType === 'active' || selectedItem?.status !== 'pending'">
                        <button @click="detailModalOpen = false" class="px-8 py-3.5 bg-zinc-900 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-primary transition-all shadow-lg shadow-zinc-900/20 ml-auto">
                            Tutup Detail
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </template>

    {{-- ==========================================
         APPROVE MODAL (PREMIUM VIEW)
         ========================================== --}}
    <template x-teleport="body">
        <div x-show="approveModalOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-zinc-900/60 backdrop-blur-sm"
             @click.self="approveModalOpen = false"
             style="display: none;">
            
            <div x-show="approveModalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="bg-white w-full max-w-3xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] border border-zinc-100">
                
                {{-- Modal Header --}}
                <div class="p-8 border-b border-zinc-100 flex justify-between items-start bg-zinc-50/50 shrink-0">
                    <div class="flex gap-5 w-full items-center">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-lg shadow-emerald-600/20 flex-shrink-0">
                            <span class="material-symbols-outlined text-3xl">fact_check</span>
                        </div>
                        <div class="flex-1 pr-4">
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] bg-zinc-200 text-zinc-700 px-2.5 py-0.5 rounded-md border border-zinc-300/60" x-text="approveForm.id"></span>
                                <span class="text-xs font-bold text-zinc-300">•</span>
                                <span class="text-xs font-bold text-zinc-500">Validasi & Persetujuan Kategori</span>
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-zinc-900 leading-tight" x-text="approveForm.name"></h2>
                        </div>
                        <button @click="approveModalOpen = false" class="w-10 h-10 rounded-full bg-white border border-zinc-200 text-zinc-400 flex items-center justify-center hover:bg-zinc-100 hover:text-zinc-900 transition-all shadow-sm flex-shrink-0 self-start">
                            <span class="material-symbols-outlined text-xl">close</span>
                        </button>
                    </div>
                </div>

                {{-- Modal Form Body --}}
                <div class="p-8 space-y-6 overflow-y-auto flex-1 no-scrollbar bg-background">
                    {{-- Input Rumpun Induk Kategori --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-black uppercase tracking-widest text-zinc-700">
                            Rumpun Induk Kategori (Parent Category) <span class="text-primary">*</span>
                        </label>
                        <select x-model="approveForm.parent" class="w-full px-4 py-3.5 bg-white border border-zinc-200 rounded-2xl text-xs font-bold text-zinc-800 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-sm">
                            <option value="" disabled>Pilih Rumpun Induk Kategori...</option>
                            <option value="Tidak Ada (Jadikan Kategori Utama Baru)" class="font-black text-primary">➕ [ Rumpun Utama Baru ] - Jadikan Kategori Utama Mandiri</option>
                            <template x-for="cat in activeCategories.filter(c => !c.is_legacy)" :key="cat.id">
                                <option :value="cat.name" x-text="cat.name"></option>
                            </template>
                        </select>
                        <p class="text-[10px] text-zinc-400 font-medium">Tentukan apakah kategori ini bernaung di bawah rumpun eksisting atau berdiri sendiri sebagai kategori utama baru.</p>
                    </div>

                    {{-- Input Alasan Persetujuan --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-black uppercase tracking-widest text-zinc-700">
                            Alasan Persetujuan & Catatan Verifikasi <span class="text-primary">*</span>
                        </label>
                        <textarea x-model="approveForm.reason" rows="4" placeholder="Jelaskan alasan menyetujui usulan ini, pertimbangan silabus, atau kesesuaian dengan kebutuhan operasional SIG..." class="w-full px-4 py-3.5 bg-white border border-zinc-200 rounded-2xl text-xs font-medium text-zinc-800 placeholder-zinc-400 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-sm"></textarea>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="p-6 bg-zinc-50 border-t border-zinc-100 flex gap-4 justify-end items-center">
                    <button @click="approveModalOpen = false" :disabled="approveLoading" class="px-8 py-3.5 bg-white border border-zinc-200 text-zinc-600 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-zinc-100 transition-all shadow-sm disabled:opacity-50">
                        Batal
                    </button>
                    <button @click="submitApprove()" :disabled="approveLoading" class="px-8 py-3.5 bg-emerald-600 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20 flex items-center gap-2 disabled:opacity-50">
                        <template x-if="approveLoading">
                            <span class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                        </template>
                        <template x-if="!approveLoading">
                            <span class="material-symbols-outlined text-sm">check</span>
                        </template>
                        <span>Setujui Kategori</span>
                    </button>
                </div>
            </div>
        </div>
    </template>

    {{-- ==========================================
         MANUAL CREATE MODAL (PREMIUM VIEW)
         ========================================== --}}
    <template x-teleport="body">
        <div x-show="manualModalOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-zinc-900/60 backdrop-blur-sm"
             @click.self="manualModalOpen = false"
             style="display: none;">
            
            <div x-show="manualModalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="bg-white w-full max-w-3xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] border border-zinc-100">
                
                {{-- Modal Header --}}
                <div class="p-8 border-b border-zinc-100 flex justify-between items-start bg-zinc-50/50 shrink-0">
                    <div class="flex gap-5 w-full items-center">
                        <div class="w-14 h-14 rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/20 flex-shrink-0">
                            <span class="material-symbols-outlined text-3xl">add_box</span>
                        </div>
                        <div class="flex-1 pr-4">
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] bg-zinc-200 text-zinc-700 px-2.5 py-0.5 rounded-md border border-zinc-300/60" x-text="manualModalMode === 'main' ? 'KATEGORI UTAMA' : 'SUB-KATEGORI'"></span>
                                <span class="text-xs font-bold text-zinc-300">•</span>
                                <span class="text-xs font-bold text-zinc-500">Pendaftaran Langsung</span>
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-zinc-900 leading-tight" x-text="manualModalMode === 'main' ? 'Buat Kategori Utama Baru' : 'Buat Sub-Kategori Baru'"></h2>
                        </div>
                        <button @click="manualModalOpen = false" class="w-10 h-10 rounded-full bg-white border border-zinc-200 text-zinc-400 flex items-center justify-center hover:bg-zinc-100 hover:text-zinc-900 transition-all shadow-sm flex-shrink-0 self-start">
                            <span class="material-symbols-outlined text-xl">close</span>
                        </button>
                    </div>
                </div>

                {{-- Modal Form Body --}}
                <div class="p-8 space-y-6 overflow-y-auto flex-1 no-scrollbar bg-background">
                    {{-- Input Nama Kategori --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-black uppercase tracking-widest text-zinc-700">
                            Nama <span x-text="manualModalMode === 'main' ? 'Kategori Utama' : 'Sub-Kategori'"></span> <span class="text-primary">*</span>
                        </label>
                        <input type="text" x-model="manualForm.name" :placeholder="manualModalMode === 'main' ? 'Contoh: Advanced Robotics & Automation' : 'Contoh: Predictive Maintenance dengan AI'" class="w-full px-4 py-3.5 bg-white border border-zinc-200 rounded-2xl text-xs font-bold text-zinc-800 placeholder-zinc-400 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-sm">
                        <p class="text-[10px] text-zinc-400 font-medium" x-text="manualModalMode === 'main' ? 'Gunakan penamaan yang mencerminkan payung besar rumpun kompetensi.' : 'Gunakan penamaan spesifik di bawah rumpun induk yang dipilih.'"></p>
                    </div>

                    {{-- Input Alasan / Deskripsi --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-black uppercase tracking-widest text-zinc-700">
                            Alasan & Deskripsi Kompetensi <span class="text-primary">*</span>
                        </label>
                        <textarea x-model="manualForm.reason" rows="4" placeholder="Jelaskan urgensi, cakupan materi, atau latar belakang pembentukan kategori ini..." class="w-full px-4 py-3.5 bg-white border border-zinc-200 rounded-2xl text-xs font-medium text-zinc-800 placeholder-zinc-400 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-sm"></textarea>
                    </div>

                    {{-- Input Pilihan Induk Kategori (Hanya Muncul di Mode Sub-Kategori) --}}
                    <div class="space-y-2" x-show="manualModalMode === 'sub'">
                        <label class="block text-xs font-black uppercase tracking-widest text-zinc-700">
                            Induk Kategori (Parent Category)
                        </label>
                        <input type="text" x-model="manualForm.parent" disabled class="w-full px-4 py-3.5 bg-zinc-100 border border-zinc-200 rounded-2xl text-xs font-bold text-zinc-600 shadow-inner cursor-not-allowed">
                        <p class="text-[10px] text-zinc-400 font-medium">Sub-kategori ini akan otomatis bernaung di bawah rumpun induk di atas.</p>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="p-6 bg-zinc-50 border-t border-zinc-100 flex gap-4 justify-end items-center">
                    <button @click="manualModalOpen = false" :disabled="manualLoading" class="px-8 py-3.5 bg-white border border-zinc-200 text-zinc-600 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-zinc-100 transition-all shadow-sm disabled:opacity-50">
                        Batal
                    </button>
                    <button @click="submitManual()" :disabled="manualLoading" class="px-8 py-3.5 bg-primary text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-red-600 transition-all shadow-lg shadow-primary/20 flex items-center gap-2 disabled:opacity-50">
                        <template x-if="manualLoading">
                            <span class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                        </template>
                        <template x-if="!manualLoading">
                            <span class="material-symbols-outlined text-sm">save</span>
                        </template>
                        <span>Simpan Kategori</span>
                    </button>
                </div>
            </div>
        </div>
    </template>

    {{-- ==========================================
         FILTER MODAL (PREMIUM VIEW)
         ========================================== --}}
    <template x-teleport="body">
        <div x-show="showFilterModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-zinc-900/60 backdrop-blur-sm"
             @click.self="showFilterModal = false"
             style="display: none;">
            
            <div x-show="showFilterModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] border border-zinc-100">
                
                {{-- Modal Header --}}
                <div class="p-8 border-b border-zinc-100 flex justify-between items-center bg-zinc-50/50 shrink-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center shadow-inner">
                            <span class="material-symbols-outlined text-2xl">tune</span>
                        </div>
                        <div>
                            <h2 class="text-xl font-black tracking-tight text-zinc-900">Filter Pengajuan Kategori</h2>
                            <p class="text-[10px] text-zinc-400 font-bold mt-0.5">Saring data berdasarkan OpCo, Status, dan Tingkat Urgensi</p>
                        </div>
                    </div>
                    <button @click="showFilterModal = false" class="w-10 h-10 rounded-full bg-white border border-zinc-200 text-zinc-400 flex items-center justify-center hover:bg-zinc-100 hover:text-zinc-900 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-8 space-y-8 overflow-y-auto flex-1 no-scrollbar bg-background">
                    {{-- Filter Nama Pengusul (Searchable Multi-Select) --}}
                    <div class="space-y-3" x-data="{ open: false, search: '' }" @click.outside="open = false">
                        <label class="block text-xs font-black uppercase tracking-widest text-zinc-700 flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-base text-primary">person_search</span>
                                Nama Pengusul
                            </span>
                            <template x-if="tempFilters.proposers.length > 0">
                                <button @click.stop="tempFilters.proposers = []" class="text-[10px] text-primary hover:underline font-bold lowercase tracking-normal">reset</button>
                            </template>
                        </label>
                        <div class="relative">
                            <button @click="open = !open" type="button" 
                                    class="w-full p-4 bg-zinc-50 border border-zinc-200/80 rounded-2xl text-xs font-bold text-left flex items-center justify-between hover:border-primary/40 hover:bg-zinc-50/50 transition-all shadow-sm"
                                    :class="tempFilters.proposers.length ? 'border-primary ring-1 ring-primary bg-primary/[0.02]' : ''">
                                <span class="truncate text-zinc-800" x-text="tempFilters.proposers.length ? tempFilters.proposers.length + ' Pengusul Dipilih (' + tempFilters.proposers.join(', ') + ')' : 'Semua Pengusul (Pilih Pengusul)'"></span>
                                <span class="material-symbols-outlined text-zinc-400 transition-transform shrink-0" :class="open ? 'rotate-180' : ''">expand_more</span>
                            </button>
                            
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute z-[60] w-full mt-2 bg-white rounded-2xl shadow-xl border border-zinc-200 overflow-hidden">
                                <div class="p-3 border-b border-zinc-100 bg-zinc-50/50 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-zinc-400 text-sm pl-1">search</span>
                                    <input type="text" x-model="search" placeholder="Cari nama pengusul..." 
                                           class="w-full bg-transparent border-none text-xs font-bold text-zinc-800 placeholder-zinc-400 outline-none focus:ring-0 p-1">
                                </div>
                                <div class="max-h-52 overflow-y-auto divide-y divide-zinc-100 custom-scrollbar">
                                    <template x-for="proposer in getAvailableProposers().filter(p => p.toLowerCase().includes(search.toLowerCase()))" :key="proposer">
                                        <label class="flex items-center gap-3 p-3.5 hover:bg-zinc-50 transition-colors cursor-pointer">
                                            <input type="checkbox" :value="proposer" x-model="tempFilters.proposers" 
                                                   class="w-4 h-4 rounded border-zinc-300 text-primary focus:ring-primary">
                                            <span class="text-xs font-bold text-zinc-700" x-text="proposer"></span>
                                        </label>
                                    </template>
                                    <template x-if="getAvailableProposers().filter(p => p.toLowerCase().includes(search.toLowerCase())).length === 0">
                                        <div class="p-4 text-center text-xs text-zinc-400 font-medium italic">Tidak ada pengusul ditemukan</div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Filter Perusahaan (OpCo) (Searchable Single-Select) --}}
                    <div class="space-y-3" x-data="{ open: false, search: '' }" @click.outside="open = false">
                        <label class="block text-xs font-black uppercase tracking-widest text-zinc-700 flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-base text-primary">apartment</span>
                                Perusahaan (OpCo)
                            </span>
                            <template x-if="tempFilters.company_id">
                                <button @click.stop="tempFilters.company_id = ''" class="text-[10px] text-primary hover:underline font-bold lowercase tracking-normal">reset</button>
                            </template>
                        </label>
                        <div class="relative">
                            <button @click="open = !open" type="button" 
                                    class="w-full p-4 bg-zinc-50 border border-zinc-200/80 rounded-2xl text-xs font-bold text-left flex items-center justify-between hover:border-primary/40 hover:bg-zinc-50/50 transition-all shadow-sm"
                                    :class="tempFilters.company_id ? 'border-primary ring-1 ring-primary bg-primary/[0.02]' : ''">
                                <span class="truncate text-zinc-800" x-text="tempFilters.company_id ? companies.find(c => c.id == tempFilters.company_id)?.name : 'Semua Perusahaan (Pilih Perusahaan)'"></span>
                                <span class="material-symbols-outlined text-zinc-400 transition-transform shrink-0" :class="open ? 'rotate-180' : ''">expand_more</span>
                            </button>
                            
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute z-[60] w-full mt-2 bg-white rounded-2xl shadow-xl border border-zinc-200 overflow-hidden">
                                <div class="p-3 border-b border-zinc-100 bg-zinc-50/50 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-zinc-400 text-sm pl-1">search</span>
                                    <input type="text" x-model="search" placeholder="Cari perusahaan..." 
                                           class="w-full bg-transparent border-none text-xs font-bold text-zinc-800 placeholder-zinc-400 outline-none focus:ring-0 p-1">
                                </div>
                                <div class="max-h-52 overflow-y-auto divide-y divide-zinc-100 custom-scrollbar">
                                    <button @click="tempFilters.company_id = ''; open = false; search = ''" type="button"
                                            class="w-full p-3.5 text-left text-xs font-bold text-zinc-600 hover:bg-zinc-50 transition-colors flex items-center justify-between">
                                        <span>Semua Perusahaan</span>
                                        <span class="material-symbols-outlined text-sm text-zinc-400" x-show="!tempFilters.company_id">check</span>
                                    </button>
                                    <template x-for="comp in companies.filter(c => c.name.toLowerCase().includes(search.toLowerCase()))" :key="comp.id">
                                        <button @click="tempFilters.company_id = comp.id; open = false; search = ''" type="button"
                                                class="w-full p-3.5 text-left text-xs font-bold text-zinc-800 hover:bg-zinc-50 transition-colors flex items-center justify-between">
                                            <span x-text="comp.name"></span>
                                            <span class="material-symbols-outlined text-sm text-primary" x-show="tempFilters.company_id == comp.id">check</span>
                                        </button>
                                    </template>
                                    <template x-if="companies.filter(c => c.name.toLowerCase().includes(search.toLowerCase())).length === 0">
                                        <div class="p-4 text-center text-xs text-zinc-400 font-medium italic">Tidak ada perusahaan ditemukan</div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Organizational Levels (Searchable Multi-Select) --}}
                    <template x-for="(level, index) in getAvailableLevels().filter(l => l.order > 0)" :key="level.id">
                        <div class="space-y-3" x-data="{ open: false, search: '' }" @click.outside="open = false">
                            <label class="block text-xs font-black uppercase tracking-widest text-zinc-700 flex items-center justify-between">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base text-primary" x-text="level.order === 4 ? 'workspaces' : (level.order === 3 ? 'badge' : 'account_tree')"></span>
                                    <span x-text="level.name"></span>
                                </span>
                                <template x-if="tempFilters.selectedOrgLevels[level.id] && tempFilters.selectedOrgLevels[level.id].length > 0">
                                    <button @click.stop="tempFilters.selectedOrgLevels[level.id] = []; handleLevelChange(level.id)" class="text-[10px] text-primary hover:underline font-bold lowercase tracking-normal">reset</button>
                                </template>
                            </label>
                            <div class="relative">
                                <button @click="open = !open" type="button" 
                                        :disabled="index > 0 && (!tempFilters.selectedOrgLevels[getAvailableLevels().filter(l => l.order > 0)[index-1].id] || tempFilters.selectedOrgLevels[getAvailableLevels().filter(l => l.order > 0)[index-1].id].length === 0)"
                                        class="w-full p-4 bg-zinc-50 border border-zinc-200/80 rounded-2xl text-xs font-bold text-left flex items-center justify-between hover:border-primary/40 hover:bg-zinc-50/50 transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                        :class="tempFilters.selectedOrgLevels[level.id]?.length ? 'border-primary ring-1 ring-primary bg-primary/[0.02]' : ''">
                                    <span class="truncate text-zinc-800" x-text="index > 0 && (!tempFilters.selectedOrgLevels[getAvailableLevels().filter(l => l.order > 0)[index-1].id] || tempFilters.selectedOrgLevels[getAvailableLevels().filter(l => l.order > 0)[index-1].id].length === 0) ? 'Pilih ' + getAvailableLevels().filter(l => l.order > 0)[index-1].name + ' terlebih dahulu' : (tempFilters.selectedOrgLevels[level.id]?.length ? tempFilters.selectedOrgLevels[level.id].length + ' ' + level.name + ' Dipilih' : 'Semua ' + level.name + ' (Pilih ' + level.name + ')')"></span>
                                    <span class="material-symbols-outlined text-zinc-400 transition-transform shrink-0" :class="open ? 'rotate-180' : ''">expand_more</span>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" 
                                     class="absolute z-[60] w-full mt-2 bg-white rounded-2xl shadow-xl border border-zinc-200 overflow-hidden">
                                    <div class="p-3 border-b border-zinc-100 bg-zinc-50/50 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-zinc-400 text-sm pl-1">search</span>
                                        <input type="text" x-model="search" :placeholder="'Cari ' + level.name + '...'" 
                                               class="w-full bg-transparent border-none text-xs font-bold text-zinc-800 placeholder-zinc-400 outline-none focus:ring-0 p-1">
                                    </div>
                                    <div class="max-h-52 overflow-y-auto divide-y divide-zinc-100 custom-scrollbar">
                                        <template x-for="org in getOrganizationsForLevel(level.id, index === 0 ? null : tempFilters.selectedOrgLevels[getAvailableLevels().filter(l => l.order > 0)[index-1].id]).filter(o => o.name.toLowerCase().includes(search.toLowerCase()))" :key="org.id">
                                            <label class="flex items-center gap-3 p-3.5 hover:bg-zinc-50 transition-colors cursor-pointer">
                                                <input type="checkbox" :value="org.id" x-model="tempFilters.selectedOrgLevels[level.id]" 
                                                       @change="handleLevelChange(level.id)"
                                                       class="w-4 h-4 rounded border-zinc-300 text-primary focus:ring-primary">
                                                <span class="text-xs font-bold text-zinc-700" x-text="org.name"></span>
                                            </label>
                                        </template>
                                        <template x-if="getOrganizationsForLevel(level.id, index === 0 ? null : tempFilters.selectedOrgLevels[getAvailableLevels().filter(l => l.order > 0)[index-1].id]).filter(o => o.name.toLowerCase().includes(search.toLowerCase())).length === 0">
                                            <div class="p-4 text-center text-xs text-zinc-400 font-medium italic" x-text="'Tidak ada ' + level.name + ' ditemukan'"></div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Filter Status --}}
                    <div class="space-y-3">
                        <label class="block text-xs font-black uppercase tracking-widest text-zinc-700 flex items-center gap-2">
                            <span class="material-symbols-outlined text-base text-primary">rule</span>
                            Status Pengajuan
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                            <template x-for="status in availableStatuses" :key="status.id">
                                <label class="flex items-center gap-3 p-3.5 bg-white border border-zinc-200/80 rounded-2xl cursor-pointer hover:border-primary/40 hover:bg-zinc-50/50 transition-all shadow-sm"
                                       :class="tempFilters.statuses.includes(status.id) ? 'border-primary ring-1 ring-primary bg-primary/[0.02]' : ''">
                                    <input type="checkbox" :value="status.id" @change="toggleFilter('statuses', status.id)" :checked="tempFilters.statuses.includes(status.id)" class="w-4 h-4 text-primary border-zinc-300 rounded focus:ring-primary">
                                    <span class="text-xs font-bold text-zinc-800" x-text="status.label"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    {{-- Filter Urgensi --}}
                    <div class="space-y-3">
                        <label class="block text-xs font-black uppercase tracking-widest text-zinc-700 flex items-center gap-2">
                            <span class="material-symbols-outlined text-base text-primary">priority</span>
                            Tingkat Urgensi
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                            <template x-for="urgency in availableUrgencies" :key="urgency.id">
                                <label class="flex items-center gap-3 p-3.5 bg-white border border-zinc-200/80 rounded-2xl cursor-pointer hover:border-primary/40 hover:bg-zinc-50/50 transition-all shadow-sm"
                                       :class="tempFilters.urgencies.includes(urgency.id) ? 'border-primary ring-1 ring-primary bg-primary/[0.02]' : ''">
                                    <input type="checkbox" :value="urgency.id" @change="toggleFilter('urgencies', urgency.id)" :checked="tempFilters.urgencies.includes(urgency.id)" class="w-4 h-4 text-primary border-zinc-300 rounded focus:ring-primary">
                                    <span class="text-xs font-bold text-zinc-800" x-text="urgency.label"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="p-6 bg-zinc-50 border-t border-zinc-100 flex gap-4 justify-between items-center shrink-0">
                    <button @click="resetFilters()" class="px-6 py-3.5 bg-white border border-zinc-200 text-zinc-600 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-zinc-100 transition-all shadow-sm">
                        Reset Filter
                    </button>
                    <button @click="applyFilters()" class="px-8 py-3.5 bg-primary text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-red-600 transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">filter_list</span>
                        <span>Terapkan Filter</span>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
function categoryApprovalBoard() {
    return {
        activeTab: 'pending',
        search: '',
        pendingRequests: @json($pendingRequests),
        activeCategories: @json($activeCategories),
        smes: @json($smes),
        companies: @json($companies),
        orgLevels: @json($orgLevels),
        organizations: @json($organizations),

        pendingPage: 1,
        pendingPerPage: 5,

        activePage: 1,
        activePerPage: 5,

        detailModalOpen: false,
        detailType: 'pending',
        selectedItem: null,

        manualModalOpen: false,
        manualModalMode: 'main',
        manualForm: {
            name: '',
            reason: '',
            parent: '',
            sme: ''
        },
        manualLoading: false,

        approveModalOpen: false,
        approveForm: {
            id: '',
            name: '',
            parent: '',
            reason: ''
        },
        approveLoading: false,

        showFilterModal: false,
        selectedFilters: {
            proposers: [],
            company_id: '',
            selectedOrgLevels: {},
            statuses: [],
            urgencies: []
        },
        tempFilters: {
            proposers: [],
            company_id: '',
            selectedOrgLevels: {},
            statuses: [],
            urgencies: []
        },
        availableStatuses: [
            { id: 'pending', label: 'Menunggu' },
            { id: 'approved', label: 'Disetujui' },
            { id: 'rejected', label: 'Ditolak' }
        ],
        availableUrgencies: [
            { id: 'High', label: 'High' },
            { id: 'Medium', label: 'Medium' },
            { id: 'Low', label: 'Low' }
        ],

        getAvailableProposers() {
            const list = this.pendingRequests.map(item => item.submitted_by_name).filter(Boolean);
            return [...new Set(list)].sort();
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
                    return matchLevel && parentIds.some(pid => pid == o.parent_id);
                }
                return matchLevel;
            });
        },
        handleLevelChange(levelId) {
            let levels = this.getAvailableLevels();
            let currentLevelIndex = levels.findIndex(l => l.id == levelId);
            
            if (currentLevelIndex !== -1) {
                for (let i = currentLevelIndex + 1; i < levels.length; i++) {
                    let childLevel = levels[i];
                    this.tempFilters.selectedOrgLevels[childLevel.id] = [];
                }
            }
        },

        init() {
            this.$watch('search', () => {
                this.pendingPage = 1;
                this.activePage = 1;
            });
            this.$watch('activeTab', () => {
                this.search = '';
            });
            this.$watch('tempFilters.company_id', (newCompanyId) => {
                this.tempFilters.selectedOrgLevels = {};
                if (newCompanyId) {
                    this.orgLevels.filter(l => l.company_id == newCompanyId).forEach(l => {
                        this.tempFilters.selectedOrgLevels[l.id] = [];
                    });
                }
            });
        },

        openFilterModal() {
            this.tempFilters = JSON.parse(JSON.stringify(this.selectedFilters));
            this.showFilterModal = true;
        },
        toggleFilter(key, val) {
            if (this.tempFilters[key].includes(val)) {
                this.tempFilters[key] = this.tempFilters[key].filter(v => v !== val);
            } else {
                this.tempFilters[key].push(val);
            }
        },
        applyFilters() {
            this.selectedFilters = JSON.parse(JSON.stringify(this.tempFilters));
            this.showFilterModal = false;
            this.pendingPage = 1;
        },
        resetFilters() {
            this.selectedFilters = { proposers: [], company_id: '', selectedOrgLevels: {}, statuses: [], urgencies: [] };
            this.tempFilters = JSON.parse(JSON.stringify(this.selectedFilters));
            this.showFilterModal = false;
            this.pendingPage = 1;
        },
        get activeFilterCount() {
            let count = 0;
            if (this.selectedFilters.proposers.length > 0) count += this.selectedFilters.proposers.length;
            if (this.selectedFilters.company_id) count++;
            Object.values(this.selectedFilters.selectedOrgLevels).forEach(arr => {
                if (arr && arr.length > 0) count += arr.length;
            });
            if (this.selectedFilters.statuses.length > 0) count += this.selectedFilters.statuses.length;
            if (this.selectedFilters.urgencies.length > 0) count += this.selectedFilters.urgencies.length;
            return count;
        },

        get filteredPending() {
            return this.pendingRequests.filter(item => {
                const matchSearch = item.name.toLowerCase().includes(this.search.toLowerCase()) ||
                       item.submitted_by_name.toLowerCase().includes(this.search.toLowerCase()) ||
                       item.reason.toLowerCase().includes(this.search.toLowerCase());
                
                const matchProposer = this.selectedFilters.proposers.length === 0 || this.selectedFilters.proposers.includes(item.submitted_by_name);
                const matchCompany = !this.selectedFilters.company_id || item.company_id == this.selectedFilters.company_id;
                
                let matchOrg = true;
                let levels = Object.keys(this.selectedFilters.selectedOrgLevels).sort((a, b) => b - a);
                for (let levelId of levels) {
                    let selectedIds = this.selectedFilters.selectedOrgLevels[levelId];
                    if (selectedIds && selectedIds.length > 0) {
                        if (!item.org_path_ids || !item.org_path_ids.some(id => selectedIds.some(sid => sid == id))) {
                            matchOrg = false;
                        }
                        break;
                    }
                }

                const matchStatus = this.selectedFilters.statuses.length === 0 || this.selectedFilters.statuses.includes(item.status);
                const matchUrgency = this.selectedFilters.urgencies.length === 0 || this.selectedFilters.urgencies.some(u => item.urgency_level?.includes(u));

                return matchSearch && matchProposer && matchCompany && matchOrg && matchStatus && matchUrgency;
            });
        },

        get paginatedPending() {
            const start = (this.pendingPage - 1) * this.pendingPerPage;
            return this.filteredPending.slice(start, start + this.pendingPerPage);
        },

        get totalPendingPages() {
            return Math.ceil(this.filteredPending.length / this.pendingPerPage) || 1;
        },

        get filteredActive() {
            return this.activeCategories.filter(item => {
                const matchParent = item.name.toLowerCase().includes(this.search.toLowerCase());
                const matchChild = item.children && item.children.some(child => child.name.toLowerCase().includes(this.search.toLowerCase()));
                return matchParent || matchChild;
            });
        },

        get paginatedActive() {
            const start = (this.activePage - 1) * this.activePerPage;
            return this.filteredActive.slice(start, start + this.activePerPage);
        },

        get totalActivePages() {
            return Math.ceil(this.filteredActive.length / this.activePerPage) || 1;
        },

        openDetail(item, type) {
            this.selectedItem = item;
            this.detailType = type;
            this.detailModalOpen = true;
        },

        openAddMainCategory() {
            this.manualForm = {
                name: '',
                reason: '',
                parent: '',
                sme: ''
            };
            this.manualModalMode = 'main';
            this.manualModalOpen = true;
        },

        openAddSubCategory(parentItem) {
            this.manualForm = {
                name: '',
                reason: '',
                parent: parentItem.name,
                sme: ''
            };
            this.manualModalMode = 'sub';
            this.manualModalOpen = true;
        },

        openApproveModal(item) {
            if (!item) return;
            this.approveForm = {
                id: item.id,
                name: item.name,
                parent: '',
                reason: ''
            };
            this.approveModalOpen = true;
        },

        async submitApprove() {
            if (!this.approveForm.parent || !this.approveForm.reason.trim()) {
                Alert.error('Validasi Gagal', 'Rumpun induk kategori dan alasan persetujuan wajib diisi!');
                return;
            }
            this.approveLoading = true;
            try {
                const res = await axios.post(`/admin-coordinator/category-approval/${this.approveForm.id}/approve`, this.approveForm);
                if (res.data.success) {
                    const item = this.pendingRequests.find(i => i.id === this.approveForm.id);
                    if (item) {
                        item.status = 'approved';
                        item.approved_reason = this.approveForm.reason;
                        item.parent_category = this.approveForm.parent;

                        const newCat = {
                            id: 'CAT-' + this.approveForm.id.replace('REQ-CAT-', ''),
                            name: item.name,
                            description: item.reason + ' (Catatan Persetujuan: ' + this.approveForm.reason + ')',
                            total_blueprints: '0 Blueprint',
                            badge: '(BARU DISAHKAN)',
                            sme_count: '1 Akses Pakar',
                            date: 'Hari Ini',
                            is_active: true,
                            is_legacy: false
                        };

                        if (this.approveForm.parent && this.approveForm.parent !== 'Tidak Ada (Jadikan Kategori Utama Baru)') {
                            const parentObj = this.activeCategories.find(c => c.name === this.approveForm.parent);
                            if (parentObj) {
                                if (!parentObj.children) parentObj.children = [];
                                parentObj.children.unshift(newCat);
                            } else {
                                this.activeCategories.unshift(newCat);
                            }
                        } else {
                            this.activeCategories.unshift(newCat);
                        }
                    }
                    if (this.selectedItem && this.selectedItem.id === this.approveForm.id) {
                        this.detailModalOpen = false;
                    }
                    this.approveModalOpen = false;
                    Alert.success('Disetujui!', res.data.message);
                }
            } catch (e) {
                Alert.error('Gagal', 'Terjadi kesalahan saat menyetujui kategori.');
            } finally {
                this.approveLoading = false;
            }
        },

        async reject(id) {
            const { value: feedback } = await Swal.fire({
                title: 'Tolak Pengajuan',
                input: 'textarea',
                inputLabel: 'Alasan Penolakan / Feedback untuk SME',
                inputPlaceholder: 'Tuliskan alasan penolakan secara detail agar pengusul dapat memahaminya...',
                inputValidator: (value) => {
                    if (!value || !value.trim()) {
                        return 'Alasan penolakan wajib diisi!';
                    }
                },
                showCancelButton: true,
                confirmButtonColor: '#e21d24',
                cancelButtonColor: '#f4f4f5',
                confirmButtonText: 'Tolak Pengajuan',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-[2.5rem]',
                    confirmButton: 'rounded-xl px-10 py-4 font-bold uppercase tracking-tight text-white',
                    cancelButton: 'rounded-xl px-10 py-4 font-bold uppercase tracking-tight text-zinc-600'
                }
            });

            if (feedback) {
                try {
                    const res = await axios.post(`/admin-coordinator/category-approval/${id}/reject`, { feedback });
                    if (res.data.success) {
                        const item = this.pendingRequests.find(i => i.id === id);
                        if (item) {
                            item.status = 'rejected';
                            item.feedback = feedback;
                        }
                        if (this.selectedItem && this.selectedItem.id === id) {
                            this.detailModalOpen = false;
                        }
                        Alert.success('Ditolak!', res.data.message);
                    }
                } catch (e) {
                    Alert.error('Gagal', 'Terjadi kesalahan saat menolak kategori.');
                }
            }
        },

        async toggleStatus(item) {
            const isDeactivatingParent = item.is_active && item.children && item.children.length > 0;
            const actionText = item.is_active ? 'nonaktifkan' : 'aktifkan';
            const warningText = isDeactivatingParent 
                ? `PERHATIAN: Menonaktifkan Rumpun Utama ini juga akan otomatis menonaktifkan seluruh (${item.children.length}) sub-kategori di bawahnya!` 
                : `Apakah Anda yakin ingin meng-${actionText} kategori ini?`;

            const result = await Alert.confirm(`Konfirmasi Status`, warningText);
            if (result.isConfirmed) {
                try {
                    const res = await axios.post(`/admin-coordinator/category-approval/${item.id}/toggle`);
                    if (res.data.success) {
                        item.is_active = !item.is_active;
                        if (!item.is_active && item.children && item.children.length > 0) {
                            item.children.forEach(child => {
                                child.is_active = false;
                            });
                        }
                        Alert.success('Berhasil!', isDeactivatingParent ? 'Rumpun utama beserta seluruh sub-kategorinya berhasil dinonaktifkan.' : res.data.message);
                    }
                } catch (e) {
                    Alert.error('Gagal', 'Terjadi kesalahan memperbarui status.');
                }
            }
        },

        async submitManual() {
            if (!this.manualForm.name || !this.manualForm.reason) {
                Alert.error('Validasi Gagal', 'Nama kategori dan alasan/deskripsi wajib diisi.');
                return;
            }
            this.manualLoading = true;
            try {
                const res = await axios.post('/admin-coordinator/category-approval/store', this.manualForm);
                if (res.data.success) {
                    if (this.manualForm.parent) {
                        const parentObj = this.activeCategories.find(c => c.name === this.manualForm.parent);
                        if (parentObj) {
                            if (!parentObj.children) parentObj.children = [];
                            parentObj.children.unshift(res.data.category);
                        } else {
                            this.activeCategories.unshift(res.data.category);
                        }
                    } else {
                        this.activeCategories.unshift(res.data.category);
                    }
                    this.manualModalOpen = false;
                    this.manualForm = { name: '', reason: '', parent: '', sme: '' };
                    Alert.success('Berhasil!', res.data.message);
                }
            } catch (e) {
                Alert.error('Gagal', 'Terjadi kesalahan saat menyimpan kategori.');
            } finally {
                this.manualLoading = false;
            }
        }
    }
}
</script>
@endsection
