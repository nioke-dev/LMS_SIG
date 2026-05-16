@extends('layouts.backoffice')

@section('sidebar-nav')
    @include('partials.ac-sidebar')
@endsection

@section('title', 'Blueprint Directory')

@section('content')
<div x-data="blueprintDirectory()" class="pb-32 relative" x-cloak>
    {{-- Header Section --}}
    <div class="mb-8">
        <h1 class="text-4xl font-black text-zinc-900 leading-tight tracking-tight mb-2 uppercase">Blueprint Directory</h1>
        <p class="text-zinc-500 font-medium max-w-2xl leading-relaxed text-xs">Pantau atau akses daftar pengembangan kurikulum (Curriculum Development Tracking).</p>
    </div>

    {{-- Top Stats Cards (KPIs) (Medium Scale) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-10">
        <div class="bg-red-600 rounded-[1.5rem] p-6 text-white relative overflow-hidden group shadow-lg shadow-red-600/20">
            <div class="absolute right-[-10px] bottom-[-10px] opacity-10 group-hover:scale-110 transition-transform duration-700 pointer-events-none">
                <span class="material-symbols-outlined text-[80px] font-light">architecture</span>
            </div>
            <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-80 mb-3">Total Blueprints</p>
            <h3 class="text-4xl font-black mb-3 tracking-tighter" x-text="stats.total_blueprints">0</h3>
            <div class="flex items-center gap-1.5 text-white/80">
                <span class="material-symbols-outlined text-[10px]">trending_up</span>
                <p class="text-[8px] font-bold uppercase tracking-widest">+12% from last quarter</p>
            </div>
        </div>

        <div class="bg-white rounded-[1.5rem] border border-zinc-100 p-6 shadow-sm relative group overflow-hidden">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 mb-3">Active SMEs</p>
            <h3 class="text-4xl font-black text-zinc-900 mb-3 tracking-tighter" x-text="stats.active_smes">0</h3>
            <div class="flex items-center gap-1.5 text-zinc-400">
                <span class="material-symbols-outlined text-[10px]">groups</span>
                <p class="text-[8px] font-bold uppercase tracking-widest">Across All Units</p>
            </div>
        </div>

        <div class="bg-zinc-900 rounded-[1.5rem] p-6 text-white relative overflow-hidden group shadow-lg shadow-zinc-900/20">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500 mb-3">Avg. Completion</p>
            <h3 class="text-4xl font-black text-white mb-4 tracking-tighter" x-text="stats.avg_completion + '%' ">0%</h3>
            <div class="w-full h-1 bg-zinc-800 rounded-full overflow-hidden">
                <div class="h-full bg-red-600 transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(226,29,36,0.5)]" :style="'width: ' + stats.avg_completion + '%'"></div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white p-5 rounded-[1.5rem] border border-zinc-100 shadow-sm mb-8">
        <div class="flex flex-col md:flex-row gap-4 items-center">
            <div class="relative flex-1 w-full group">
                <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-zinc-400 group-focus-within:text-red-600 transition-colors">search</span>
                <input type="text" x-model="search" placeholder="Cari Judul Blueprint atau ID..." 
                    class="w-full pl-14 pr-6 py-3.5 bg-zinc-50 border-none rounded-xl text-sm font-bold focus:ring-4 focus:ring-red-600/5 transition-all outline-none">
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto">
                <select x-model="filterStatus" class="px-4 py-3.5 bg-zinc-50 border-none rounded-xl text-[9px] font-black uppercase tracking-widest shadow-sm outline-none appearance-none cursor-pointer min-w-[140px]">
                    <option value="all">Status: Semua</option>
                    <option value="drafting">Drafting</option>
                    <option value="pending">Pending Approval</option>
                    <option value="approved">Approved</option>
                </select>

                <button class="flex items-center gap-2 px-6 py-3.5 bg-zinc-900 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-red-600 transition-all shadow-lg">
                    <span class="material-symbols-outlined text-lg">download</span>
                    Export
                </button>
            </div>
        </div>
    </div>

    {{-- Table Header Controls (OUTSIDE CARD) --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 px-4 mb-4 text-zinc-400">
        <div class="flex items-center gap-3">
            <span class="text-[10px] font-black uppercase tracking-widest">Tampilkan</span>
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
            <span class="text-[10px] font-black uppercase tracking-widest">data per halaman</span>
        </div>

        <p class="text-[10px] font-black uppercase tracking-widest">
            Showing <span class="text-zinc-900" x-text="rangeText"></span> of <span class="text-zinc-900" x-text="filteredBlueprints.length.toLocaleString()"></span> Blueprints
        </p>
    </div>

    {{-- Main Table Section --}}
    <div class="bg-white rounded-[1.5rem] border border-zinc-100 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[1300px]">
                <thead>
                    <tr class="bg-zinc-50/50">
                        <th class="px-6 py-4 text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] min-w-[160px]">ID Kurikulum</th>
                        <th class="px-4 py-4 text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] min-w-[300px]">Judul Kurikulum</th>
                        <th class="px-4 py-4 text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] min-w-[200px]">Kategori</th>
                        <th class="px-4 py-4 text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] min-w-[250px]">SME Tertugaskan</th>
                        <th class="px-4 py-4 text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] min-w-[150px]">Deadline</th>
                        <th class="px-4 py-4 text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] text-center min-w-[180px]">Status</th>
                        <th class="px-6 py-4 text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] text-right min-w-[150px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    <template x-for="bp in paginatedItems" :key="bp.id">
                        <tr class="group hover:bg-zinc-50/30 transition-colors">
                            <td class="px-6 py-5">
                                <span class="text-[10px] font-black text-red-600 tracking-tight" x-text="bp.id"></span>
                            </td>
                            <td class="px-4 py-5">
                                <span class="text-[12px] font-black text-zinc-900 group-hover:text-red-600 transition-colors" x-text="bp.title"></span>
                            </td>
                            <td class="px-4 py-5">
                                <span class="px-2.5 py-0.5 bg-zinc-100 text-zinc-500 text-[8px] font-black uppercase tracking-widest rounded-md" x-text="bp.category"></span>
                            </td>
                            <td class="px-4 py-5">
                                <div class="flex items-center gap-2">
                                    <img :src="'https://ui-avatars.com/api/?name=' + bp.sme.name + '&background=random'" class="w-7 h-7 rounded-full border border-zinc-100 shadow-sm">
                                    <span class="text-[10px] font-bold text-zinc-700" x-text="bp.sme.name"></span>
                                </div>
                            </td>
                            <td class="px-4 py-5 text-[10px] font-bold text-zinc-500" x-text="bp.deadline"></td>
                            <td class="px-4 py-5 text-center">
                                <div class="flex justify-center">
                                    <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border"
                                          :class="{
                                              'bg-emerald-50 text-emerald-600 border-emerald-100': bp.status_type === 'approved',
                                              'bg-amber-50 text-amber-600 border-amber-100': bp.status_type === 'drafting',
                                              'bg-blue-50 text-blue-600 border-blue-100': bp.status_type === 'pending'
                                          }" x-text="bp.status"></span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button @click="openDetailModal(bp)" class="w-8 h-8 rounded-lg bg-zinc-50 text-zinc-400 flex items-center justify-center hover:bg-zinc-900 hover:text-white transition-all shadow-sm" title="Detail">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </button>
                                    <button class="w-8 h-8 rounded-lg bg-zinc-50 text-zinc-400 flex items-center justify-center hover:bg-zinc-900 hover:text-white transition-all shadow-sm" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </button>
                                    <button class="w-8 h-8 rounded-lg bg-zinc-50 text-zinc-400 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Hapus">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
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

    {{-- Detail Modal --}}
    <template x-teleport="body">
        <div x-show="detailModalOpen" class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-zinc-900/60 backdrop-blur-sm" x-cloak>
            <div @click.away="detailModalOpen = false" class="bg-white w-full max-w-2xl rounded-[2rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <div class="p-8 border-b border-zinc-100 flex justify-between items-center bg-zinc-50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-zinc-900 text-white flex items-center justify-center shadow-lg">
                            <span class="material-symbols-outlined text-2xl">architecture</span>
                        </div>
                        <div>
                            <h2 class="text-sm font-black uppercase tracking-widest text-zinc-900" x-text="selectedBp?.title"></h2>
                            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mt-1" x-text="selectedBp?.id"></p>
                        </div>
                    </div>
                    <button @click="detailModalOpen = false" class="w-10 h-10 rounded-full bg-white text-zinc-400 flex items-center justify-center hover:text-zinc-900 transition-all shadow-sm">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="p-8 overflow-y-auto no-scrollbar space-y-8">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-6 bg-zinc-50 rounded-2xl border border-zinc-100">
                            <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">SME</p>
                            <p class="text-xs font-black text-zinc-900 uppercase" x-text="selectedBp?.sme?.name"></p>
                        </div>
                        <div class="p-6 bg-zinc-50 rounded-2xl border border-zinc-100">
                            <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Deadline</p>
                            <p class="text-xs font-black text-zinc-900 uppercase" x-text="selectedBp?.deadline"></p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">Deskripsi Kurikulum</h3>
                        <div class="p-6 bg-zinc-50 rounded-3xl border border-zinc-100 min-h-[100px]">
                            <p class="text-xs text-zinc-600 leading-relaxed font-medium italic" x-text="selectedBp?.description || 'Tidak ada deskripsi tambahan.'"></p>
                        </div>
                    </div>
                </div>
                <div class="p-8 bg-zinc-50 border-t border-zinc-100">
                    <button @click="detailModalOpen = false" class="w-full py-4 bg-white text-zinc-600 font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-zinc-900 hover:text-white transition-all shadow-sm">Tutup Detail</button>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    function blueprintDirectory() {
        return {
            blueprints: @json($blueprints),
            stats: @json($stats),
            search: '',
            filterStatus: 'all',
            currentPage: 1,
            perPage: 10,
            perPageOpen: false,
            detailModalOpen: false,
            selectedBp: null,

            init() {
                this.$watch('search', () => this.currentPage = 1);
                this.$watch('perPage', () => this.currentPage = 1);
            },

            get filteredBlueprints() {
                return this.blueprints.filter(bp => {
                    const matchSearch = bp.title.toLowerCase().includes(this.search.toLowerCase()) || 
                                       bp.id.toLowerCase().includes(this.search.toLowerCase());
                    const matchStatus = this.filterStatus === 'all' || bp.status_type === this.filterStatus;
                    return matchSearch && matchStatus;
                });
            },

            get paginatedItems() {
                var start = (this.currentPage - 1) * parseInt(this.perPage);
                return this.filteredBlueprints.slice(start, start + parseInt(this.perPage));
            },

            get totalPages() { return Math.ceil(this.filteredBlueprints.length / parseInt(this.perPage)) || 1; },
            get rangeText() {
                if (this.filteredBlueprints.length === 0) return '0';
                var start = (this.currentPage - 1) * parseInt(this.perPage) + 1;
                var end = Math.min(this.currentPage * parseInt(this.perPage), this.filteredBlueprints.length);
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
            openDetailModal(bp) { this.selectedBp = bp; this.detailModalOpen = true; }
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
