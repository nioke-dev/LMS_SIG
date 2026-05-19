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
                <button @click="filterOpen = true" class="relative flex items-center gap-2 px-6 py-3.5 bg-zinc-900 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-red-600 transition-all shadow-lg">
                    <span class="material-symbols-outlined text-lg">tune</span>
                    Filter
                    {{-- Badge --}}
                    <span x-show="activeFilterCount > 0" x-text="activeFilterCount" class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white text-[8px] font-black flex items-center justify-center rounded-full border-2 border-white" x-cloak></span>
                </button>
                <button class="flex items-center gap-2 px-6 py-3.5 bg-white border border-zinc-200 text-zinc-900 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-zinc-50 transition-all shadow-sm">
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
                                    <a :href="'{{ url('admin-coordinator/blueprint') }}/' + bp.id + '/edit'" class="w-8 h-8 rounded-lg bg-zinc-50 text-zinc-400 flex items-center justify-center hover:bg-zinc-900 hover:text-white transition-all shadow-sm" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <button @click="confirmDelete(bp.id)" class="w-8 h-8 rounded-lg bg-zinc-50 text-zinc-400 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Hapus">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    {{-- Empty State --}}
                    <tr x-show="filteredBlueprints.length === 0" x-cloak>
                        <td colspan="7" class="px-6 py-12 text-center bg-zinc-50/30">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mb-4">
                                    <span class="material-symbols-outlined text-3xl text-red-500">search_off</span>
                                </div>
                                <h3 class="text-sm font-black text-zinc-900 uppercase tracking-widest mb-1">Blueprint Tidak Ditemukan</h3>
                                <p class="text-xs text-zinc-500 font-medium mb-4">Tidak ada data blueprint yang sesuai dengan kriteria pencarian atau filter Anda.</p>
                                <button @click="resetFilters()" class="px-6 py-2.5 bg-zinc-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-red-600 transition-all shadow-md">
                                    Reset Filter
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

    {{-- Detail Modal --}}
    <template x-teleport="body">
        <div x-show="detailModalOpen" class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-zinc-900/60 backdrop-blur-sm" x-cloak>
            <div @click.away="detailModalOpen = false" class="bg-white w-full max-w-5xl rounded-[2rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                
                {{-- Modal Header --}}
                <div class="p-8 border-b border-zinc-100 flex justify-between items-start bg-zinc-50/50">
                    <div class="flex gap-5 w-full">
                        <div class="w-16 h-16 rounded-2xl bg-zinc-900 text-white flex items-center justify-center shadow-xl flex-shrink-0">
                            <span class="material-symbols-outlined text-3xl">architecture</span>
                        </div>
                        <div class="flex-1 pr-8">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-3 py-1 bg-zinc-200 text-zinc-600 text-[9px] font-black uppercase tracking-widest rounded-md" x-text="selectedBp?.id"></span>
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border"
                                      :class="{
                                          'bg-emerald-50 text-emerald-600 border-emerald-100': selectedBp?.status_type === 'approved',
                                          'bg-amber-50 text-amber-600 border-amber-100': selectedBp?.status_type === 'drafting',
                                          'bg-blue-50 text-blue-600 border-blue-100': selectedBp?.status_type === 'pending'
                                      }" x-text="selectedBp?.status"></span>

                                <div class="ml-auto flex items-center gap-2 bg-white px-3 py-1 rounded-md border border-zinc-200 shadow-sm">
                                    <span class="material-symbols-outlined text-[14px] text-zinc-400">calendar_today</span>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-zinc-600">Target: <span x-text="selectedBp?.deadline" class="text-zinc-900"></span></span>
                                </div>
                            </div>
                            <h2 class="text-xl font-black uppercase tracking-tight text-zinc-900 leading-tight mb-2" x-text="selectedBp?.title"></h2>
                        </div>
                    </div>
                    <button @click="detailModalOpen = false" class="w-10 h-10 rounded-full bg-white border border-zinc-200 text-zinc-400 flex items-center justify-center hover:bg-zinc-100 hover:text-zinc-900 transition-all shadow-sm flex-shrink-0">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>

                {{-- Modal Body - Dense Information --}}
                <div class="p-8 overflow-y-auto custom-scrollbar flex-1 bg-white">
                    <div class="grid grid-cols-3 gap-8">
                        {{-- Left Column (Wider) --}}
                        <div class="col-span-2 space-y-8">
                            
                            {{-- Catatan Review CLD --}}
                            <template x-if="selectedBp?.cld_review_notes">
                                <div>
                                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 mb-3 border-l-4 border-amber-500 pl-3">Catatan Review Learning Administrator</h3>
                                    <div class="p-6 bg-amber-50 rounded-2xl border border-amber-200 text-amber-900">
                                        <div class="flex items-start gap-3">
                                            <span class="material-symbols-outlined text-amber-700 text-xl flex-shrink-0 mt-0.5">rate_review</span>
                                            <div class="text-xs leading-relaxed font-medium italic" x-text="selectedBp.cld_review_notes"></div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            {{-- Course Objective --}}
                            <div>
                                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 mb-3 border-l-4 border-red-600 pl-3">Course Objective</h3>
                                <div class="p-6 bg-zinc-50 rounded-2xl border border-zinc-100 min-h-[100px]">
                                    <div class="prose prose-sm prose-zinc max-w-none" x-html="selectedBp?.course_objective || '<p class=\'text-xs text-zinc-500 font-medium italic\'>Course Objective belum didefinisikan.</p>'"></div>
                                </div>
                            </div>

                            {{-- Course Content --}}
                            <div>
                                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 mb-3 border-l-4 border-red-600 pl-3">Course Content</h3>
                                <div class="p-6 bg-zinc-50 rounded-2xl border border-zinc-100 min-h-[150px]">
                                    <div class="prose prose-sm prose-zinc max-w-none" x-html="selectedBp?.course_content || '<p class=\'text-xs text-zinc-500 font-medium italic\'>Course Content belum didefinisikan.</p>'"></div>
                                </div>
                            </div>

                            {{-- Komposisi Kategori --}}
                            <div>
                                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 mb-3 border-l-4 border-red-600 pl-3">Kategori Blueprint & Usulan</h3>
                                <div class="p-6 bg-zinc-50 rounded-2xl border border-zinc-100">
                                    <div class="mb-5">
                                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Kategori Utama Blueprint</p>
                                        <div class="group relative inline-flex">
                                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 rounded-lg border border-red-100 cursor-help">
                                                <span class="material-symbols-outlined text-sm">category</span>
                                                <span class="text-xs font-bold" x-text="selectedBp?.category || 'Belum Ditentukan'"></span>
                                                <span class="material-symbols-outlined text-[10px] ml-1">info</span>
                                            </div>
                                            <div class="absolute left-0 top-full mt-2 w-80 p-4 bg-zinc-900 text-white text-xs rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-[1010]">
                                                <p class="font-bold text-[10px] text-zinc-400 uppercase tracking-widest mb-1.5" x-text="selectedBp?.category"></p>
                                                <p class="text-zinc-200 font-medium leading-relaxed">Kategori utama yang mewadahi seluruh usulan TNA yang dilebur di dalam blueprint ini.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-t border-zinc-200/60 pt-5">
                                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-3">Komposisi Kategori TNA yang Di-merge (<span x-text="selectedBp?.merged_tna_count || 0"></span> TNA)</p>
                                        <div class="flex flex-wrap gap-2">
                                            <template x-for="(cat, index) in (selectedBp?.merged_tna_categories || [])" :key="index">
                                                <div class="group relative inline-flex">
                                                    <span class="px-3 py-1.5 bg-white border border-zinc-200 text-zinc-600 text-[10px] font-black uppercase tracking-widest rounded-md cursor-help flex items-center gap-1 hover:bg-zinc-50 transition-colors">
                                                        <span x-text="cat.name || cat"></span>
                                                        <span class="material-symbols-outlined text-[10px] text-zinc-400" x-show="cat.description">info</span>
                                                    </span>
                                                    <div x-show="cat.description" class="absolute left-0 top-full mt-2 w-72 p-3 bg-zinc-900 text-white text-[11px] rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-[1010]">
                                                        <p class="font-medium leading-relaxed" x-text="cat.description"></p>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SME Profile (Expanded to fill space) --}}
                            <div>
                                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 mb-3 border-l-4 border-red-600 pl-3">Subject Matter Expert (SME)</h3>
                                <div class="p-6 bg-zinc-50 rounded-2xl border border-zinc-100 flex flex-col md:flex-row gap-6 items-start">
                                    <div class="flex items-center gap-4 min-w-[250px]">
                                        <img :src="selectedBp?.sme?.avatar" class="w-14 h-14 rounded-full border-2 border-white shadow-md">
                                        <div>
                                            <p class="text-sm font-black text-zinc-900 uppercase tracking-tight" x-text="selectedBp?.sme?.name"></p>
                                            <p class="text-[11px] font-bold text-zinc-500 mt-0.5" x-text="selectedBp?.sme?.position || 'Subject Matter Expert'"></p>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-px md:h-16 bg-zinc-200/60 hidden md:block"></div>
                                    <div class="w-full">
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="px-2.5 py-1 bg-amber-100 text-amber-700 text-[9px] font-black uppercase tracking-widest rounded-md">Beban: <span x-text="selectedBp?.sme?.active_classes"></span> Kelas Aktif</span>
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-black uppercase tracking-widest text-zinc-400 mb-2">Riwayat Mengajar</p>
                                            <ul class="space-y-1.5 grid grid-cols-1 lg:grid-cols-2 gap-x-4">
                                                <template x-for="(history, index) in (selectedBp?.sme?.teaching_history || [])" :key="index">
                                                    <li class="flex items-start gap-2">
                                                        <span class="material-symbols-outlined text-[14px] text-emerald-500">history</span>
                                                        <span class="text-[11px] font-medium text-zinc-600 leading-tight" x-text="history"></span>
                                                    </li>
                                                </template>
                                                <template x-if="!(selectedBp?.sme?.teaching_history && selectedBp.sme.teaching_history.length)">
                                                    <li class="text-[11px] font-medium text-zinc-400 italic">Belum ada riwayat mengajar</li>
                                                </template>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column (Narrower) --}}
                        <div class="col-span-1 space-y-8">
                            

                            {{-- Instruksi Khusus SME --}}
                            <div>
                                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 mb-3 border-l-4 border-amber-500 pl-3">Instruksi Khusus SME</h3>
                                <div class="p-5 bg-amber-50 rounded-xl border border-amber-100">
                                    <p class="text-xs text-amber-700 font-medium leading-relaxed italic" x-text="selectedBp?.sme_instructions || 'Tidak ada instruksi khusus.'"></p>
                                </div>
                            </div>

                            {{-- Target Distribusi --}}
                            <div>
                                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 mb-3 border-l-4 border-zinc-900 pl-3">Target Distribusi</h3>
                                <div class="p-4 bg-zinc-50 border border-zinc-100 rounded-xl flex items-center gap-3 mb-2">
                                    <span class="material-symbols-outlined text-red-600" x-text="selectedBp?.distribution === 'public' ? 'public' : 'corporate_fare'"></span>
                                    <div>
                                        <p class="text-xs font-black text-zinc-900 uppercase tracking-widest" x-text="selectedBp?.distribution === 'public' ? 'Public Ready' : 'Internal Only'"></p>
                                    </div>
                                </div>
                                <p class="text-[11px] text-zinc-500 font-medium leading-relaxed bg-zinc-50 p-4 rounded-xl border border-zinc-100 italic" x-text="selectedBp?.distribution_note || 'Tidak ada catatan rasionalisasi.'"></p>
                            </div>

                            {{-- Kebutuhan Workshop --}}
                            <div>
                                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 mb-3 border-l-4 border-zinc-900 pl-3">Kebutuhan Workshop Praktik</h3>
                                <div class="p-4 rounded-xl border flex items-start gap-3" :class="selectedBp?.need_workshop ? 'bg-red-50/50 border-red-100' : 'bg-zinc-50 border-zinc-100'">
                                    <span class="material-symbols-outlined" :class="selectedBp?.need_workshop ? 'text-red-600' : 'text-zinc-400'" x-text="selectedBp?.need_workshop ? 'construction' : 'block'"></span>
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-widest mb-1" :class="selectedBp?.need_workshop ? 'text-red-700' : 'text-zinc-500'" x-text="selectedBp?.need_workshop ? 'Memerlukan Workshop' : 'Tanpa Workshop'"></p>
                                        <p x-show="selectedBp?.need_workshop" class="text-[11px] text-red-600/80 font-medium leading-relaxed" x-text="selectedBp?.workshop_note"></p>
                                    </div>
                                </div>
                            </div>

                            {{-- Merged TNA Stats --}}
                            <div class="p-5 bg-red-50 rounded-2xl border border-red-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white text-red-600 flex items-center justify-center shadow-sm">
                                        <span class="material-symbols-outlined">library_books</span>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-widest text-red-400 mb-0.5">Total Usulan Di-Merge</p>
                                        <p class="text-lg font-black text-red-600"><span x-text="selectedBp?.merged_tna_count || 0"></span> <span class="text-xs font-bold text-red-500">Usulan TNA</span></p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    
                    {{-- Participant List (Internal Only) --}}
                    <div class="mt-8 border-t border-zinc-100 pt-8" 
                         x-show="selectedBp?.participants && selectedBp.participants.length > 0"
                         x-data="{
                             searchPart: '',
                             perPagePart: 5,
                             currentPagePart: 1,
                             get filteredParticipants() {
                                 if (!selectedBp?.participants) return [];
                                 let p = selectedBp.participants;
                                 if (this.searchPart !== '') {
                                     p = p.filter(item => 
                                         item.name.toLowerCase().includes(this.searchPart.toLowerCase()) || 
                                         item.nik.toLowerCase().includes(this.searchPart.toLowerCase()) ||
                                         item.department.toLowerCase().includes(this.searchPart.toLowerCase()) ||
                                         item.position.toLowerCase().includes(this.searchPart.toLowerCase())
                                     );
                                 }
                                 return p;
                             },
                             get paginatedParticipants() {
                                 let start = (this.currentPagePart - 1) * this.perPagePart;
                                 return this.filteredParticipants.slice(start, start + this.perPagePart);
                             },
                             get totalPagesPart() {
                                 return Math.ceil(this.filteredParticipants.length / this.perPagePart) || 1;
                             }
                         }"
                         x-init="$watch('selectedBp', value => { searchPart = ''; currentPagePart = 1; })"
                    >
                        <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">groups</span>
                            Daftar Peserta
                        </h3>
                        
                        <!-- Table Controls -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Tampil</span>
                                <select x-model.number="perPagePart" @change="currentPagePart = 1" class="border border-zinc-200 rounded-lg text-xs font-medium text-zinc-700 pl-3 pr-8 py-1.5 focus:ring-red-500 focus:border-red-500 outline-none cursor-pointer">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                                <span class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Data</span>
                            </div>
                            
                            <div class="relative w-64">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-sm">search</span>
                                <input type="text" x-model="searchPart" @input="currentPagePart = 1" placeholder="Cari nama, NIK, atau jabatan..." class="w-full pl-9 pr-4 py-2 text-xs border border-zinc-200 rounded-lg focus:ring-red-500 focus:border-red-500 outline-none">
                            </div>
                        </div>

                        <div class="border border-zinc-200 rounded-xl overflow-hidden mb-4">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-zinc-50 border-b border-zinc-200">
                                        <th class="py-3 px-4 text-[10px] font-black text-zinc-500 uppercase tracking-widest w-12">No</th>
                                        <th class="py-3 px-4 text-[10px] font-black text-zinc-500 uppercase tracking-widest">Nama Peserta</th>
                                        <th class="py-3 px-4 text-[10px] font-black text-zinc-500 uppercase tracking-widest">NIK</th>
                                        <th class="py-3 px-4 text-[10px] font-black text-zinc-500 uppercase tracking-widest">Jabatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(participant, index) in paginatedParticipants" :key="index">
                                        <tr class="border-b border-zinc-100 last:border-0 hover:bg-zinc-50/50 transition-colors">
                                            <td class="py-3 px-4 text-xs font-medium text-zinc-500" x-text="((currentPagePart - 1) * perPagePart) + index + 1"></td>
                                            <td class="py-3 px-4 text-xs font-bold text-zinc-900" x-text="participant.name"></td>
                                            <td class="py-3 px-4 text-xs font-medium text-zinc-600" x-text="participant.nik"></td>
                                            <td class="py-3 px-4 text-xs font-medium text-zinc-600" x-text="participant.position"></td>
                                        </tr>
                                    </template>
                                    <template x-if="filteredParticipants.length === 0">
                                        <tr>
                                            <td colspan="4" class="py-8 text-center text-zinc-400 text-xs italic">
                                                Tidak ada peserta yang ditemukan.
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Controls -->
                        <div class="flex items-center justify-between" x-show="filteredParticipants.length > 0">
                            <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">
                                Menampilkan <span x-text="((currentPagePart - 1) * perPagePart) + 1" class="text-zinc-900"></span> - 
                                <span x-text="Math.min(currentPagePart * perPagePart, filteredParticipants.length)" class="text-zinc-900"></span> dari 
                                <span x-text="filteredParticipants.length" class="text-zinc-900"></span> data
                            </p>
                            <div class="flex items-center gap-1">
                                <button @click="if(currentPagePart > 1) currentPagePart--" :disabled="currentPagePart === 1" class="w-8 h-8 flex items-center justify-center rounded-lg border border-zinc-200 text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                    <span class="material-symbols-outlined text-[14px]">chevron_left</span>
                                </button>
                                <span class="px-3 text-xs font-bold text-zinc-700">
                                    <span x-text="currentPagePart"></span> / <span x-text="totalPagesPart"></span>
                                </span>
                                <button @click="if(currentPagePart < totalPagesPart) currentPagePart++" :disabled="currentPagePart === totalPagesPart" class="w-8 h-8 flex items-center justify-center rounded-lg border border-zinc-200 text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="p-6 bg-zinc-50 border-t border-zinc-100 flex gap-4 justify-end items-center">
                    <button @click="confirmDelete(selectedBp.id)" class="px-6 py-3.5 bg-red-50 border border-red-200 text-red-600 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm flex items-center gap-2 mr-auto">
                        <span class="material-symbols-outlined text-sm">delete</span>
                        Hapus Blueprint
                    </button>
                    <button @click="remindSme(selectedBp)" class="px-6 py-3.5 bg-amber-50 border border-amber-200 text-amber-700 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-amber-600 hover:text-white transition-all shadow-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">notifications_active</span>
                        Kirim Pengingat SME
                    </button>
                    <button @click="detailModalOpen = false" class="px-8 py-3.5 bg-white border border-zinc-200 text-zinc-600 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-zinc-100 transition-all shadow-sm">
                        Tutup
                    </button>
                    <a :href="'{{ url('admin-coordinator/blueprint') }}/' + selectedBp?.id + '/edit'" class="px-8 py-3.5 bg-zinc-900 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-red-600 transition-all shadow-lg flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">edit_document</span>
                        Edit Blueprint
                    </a>
                </div>
            </div>
        </div>
    </template>

    {{-- Filter Sidebar Modal --}}
    <template x-teleport="body">
        <div x-show="filterOpen" class="fixed inset-0 z-[1000] flex justify-end" x-cloak>
            <div x-show="filterOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="transition-opacity ease-linear duration-300" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-zinc-900/60 backdrop-blur-sm" @click="filterOpen = false"></div>

            <div x-show="filterOpen" 
                 x-transition:enter="transition ease-in-out duration-300 transform" 
                 x-transition:enter-start="translate-x-full" 
                 x-transition:enter-end="translate-x-0" 
                 x-transition:leave="transition ease-in-out duration-300 transform" 
                 x-transition:leave-start="translate-x-0" 
                 x-transition:leave-end="translate-x-full" 
                 class="relative w-full max-w-md bg-white h-full shadow-2xl flex flex-col border-l border-zinc-100">
                 
                {{-- Header --}}
                <div class="px-8 py-6 border-b border-zinc-100 flex justify-between items-center bg-zinc-50/50">
                    <div>
                        <h2 class="text-lg font-black uppercase tracking-tight text-zinc-900">Filter Data</h2>
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mt-1">Saring hasil pencarian</p>
                    </div>
                    <button @click="filterOpen = false" class="w-10 h-10 rounded-full bg-white border border-zinc-200 text-zinc-400 flex items-center justify-center hover:bg-zinc-100 hover:text-zinc-900 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>

                {{-- Filter Body --}}
                <div class="flex-1 overflow-y-auto p-8 space-y-8 custom-scrollbar">
                    
                    {{-- Status Filter --}}
                    <div class="space-y-4">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">Status Blueprint</label>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex items-center justify-center">
                                    <input type="radio" x-model="tempFilters.status" value="all" class="peer appearance-none w-5 h-5 border-2 border-zinc-300 rounded-full checked:border-red-600 checked:border-[6px] transition-all">
                                </div>
                                <span class="text-xs font-bold text-zinc-600 group-hover:text-zinc-900 transition-colors uppercase tracking-widest">Semua Status</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex items-center justify-center">
                                    <input type="radio" x-model="tempFilters.status" value="drafting" class="peer appearance-none w-5 h-5 border-2 border-zinc-300 rounded-full checked:border-red-600 checked:border-[6px] transition-all">
                                </div>
                                <span class="text-xs font-bold text-zinc-600 group-hover:text-zinc-900 transition-colors uppercase tracking-widest">Drafting</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex items-center justify-center">
                                    <input type="radio" x-model="tempFilters.status" value="pending" class="peer appearance-none w-5 h-5 border-2 border-zinc-300 rounded-full checked:border-red-600 checked:border-[6px] transition-all">
                                </div>
                                <span class="text-xs font-bold text-zinc-600 group-hover:text-zinc-900 transition-colors uppercase tracking-widest">Pending Approval</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex items-center justify-center">
                                    <input type="radio" x-model="tempFilters.status" value="approved" class="peer appearance-none w-5 h-5 border-2 border-zinc-300 rounded-full checked:border-red-600 checked:border-[6px] transition-all">
                                </div>
                                <span class="text-xs font-bold text-zinc-600 group-hover:text-zinc-900 transition-colors uppercase tracking-widest">Approved</span>
                            </label>
                        </div>
                    </div>

                    {{-- Category Filter --}}
                    <div class="relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-4">Kategori</label>
                        <button @click="open = !open" type="button" 
                                class="w-full p-4 bg-zinc-50 rounded-2xl text-xs font-bold text-left flex items-center justify-between hover:ring-4 hover:ring-red-600/5 transition-all">
                            <span x-text="tempFilters.category.length ? tempFilters.category.length + ' Kategori dipilih' : 'Semua Kategori'"></span>
                            <span class="material-symbols-outlined text-zinc-400 transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        
                        <div x-show="open" x-transition 
                             class="absolute z-[60] w-full mt-2 bg-white rounded-2xl shadow-2xl border border-zinc-100 overflow-hidden">
                            <div class="p-3 border-b border-zinc-50">
                                <input type="text" x-model="search" placeholder="Cari kategori..." 
                                       class="w-full p-3 bg-zinc-50 border-none rounded-xl text-xs font-bold outline-none focus:ring-0">
                            </div>
                            <div class="max-h-60 overflow-y-auto no-scrollbar">
                                <template x-for="cat in uniqueCategories.filter(c => c.toLowerCase().includes(search.toLowerCase()))" :key="cat">
                                    <label class="flex items-center gap-3 p-4 hover:bg-zinc-50 transition-colors cursor-pointer border-b border-zinc-50">
                                        <input type="checkbox" :value="cat" x-model="tempFilters.category" 
                                               class="w-4 h-4 rounded border-zinc-300 text-red-600 focus:ring-red-600/20">
                                        <span class="text-xs font-bold text-zinc-700" x-text="cat"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- SME Filter --}}
                    <div class="relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-4">Assigned SME</label>
                        <button @click="open = !open" type="button" 
                                class="w-full p-4 bg-zinc-50 rounded-2xl text-xs font-bold text-left flex items-center justify-between hover:ring-4 hover:ring-red-600/5 transition-all">
                            <span x-text="tempFilters.sme.length ? tempFilters.sme.length + ' SME dipilih' : 'Semua SME'"></span>
                            <span class="material-symbols-outlined text-zinc-400 transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        
                        <div x-show="open" x-transition 
                             class="absolute z-[60] w-full mt-2 bg-white rounded-2xl shadow-2xl border border-zinc-100 overflow-hidden">
                            <div class="p-3 border-b border-zinc-50">
                                <input type="text" x-model="search" placeholder="Cari SME..." 
                                       class="w-full p-3 bg-zinc-50 border-none rounded-xl text-xs font-bold outline-none focus:ring-0">
                            </div>
                            <div class="max-h-60 overflow-y-auto no-scrollbar">
                                <template x-for="s in uniqueSmes.filter(sme => sme.toLowerCase().includes(search.toLowerCase()))" :key="s">
                                    <label class="flex items-center gap-3 p-4 hover:bg-zinc-50 transition-colors cursor-pointer border-b border-zinc-50">
                                        <input type="checkbox" :value="s" x-model="tempFilters.sme" 
                                               class="w-4 h-4 rounded border-zinc-300 text-red-600 focus:ring-red-600/20">
                                        <span class="text-xs font-bold text-zinc-700" x-text="s"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="p-6 border-t border-zinc-100 bg-white grid grid-cols-2 gap-4">
                    <button @click="resetFilters()" class="px-6 py-4 bg-zinc-50 text-zinc-500 font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-zinc-100 transition-all">
                        Reset
                    </button>
                    <button @click="applyFilters()" class="px-6 py-4 bg-red-600 text-white font-black text-[10px] uppercase tracking-widest rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-600/20">
                        Terapkan
                    </button>
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
            filterOpen: false,
            filters: {
                status: 'all',
                category: [],
                sme: []
            },
            tempFilters: {
                status: 'all',
                category: [],
                sme: []
            },
            currentPage: 1,
            perPage: 10,
            perPageOpen: false,
            detailModalOpen: false,
            selectedBp: null,

            init() {
                this.$watch('search', () => this.currentPage = 1);
                this.$watch('perPage', () => this.currentPage = 1);
                this.$watch('filterOpen', (open) => {
                    if (open) {
                        this.tempFilters = JSON.parse(JSON.stringify(this.filters));
                    }
                });
            },

            openDetailModal(bp) {
                this.selectedBp = bp;
                this.detailModalOpen = true;
            },

            confirmDelete(id) {
                this.deleteBlueprint(id);
            },

            async deleteBlueprint(id) {
                const result = await Alert.confirm('Hapus Blueprint?', 'Data blueprint yang dihapus tidak dapat dikembalikan.');
                if (result.isConfirmed) {
                    try {
                        const response = await axios.delete(`/admin-coordinator/blueprint/${id}`);
                        if(response.data.success) {
                            this.blueprints = this.blueprints.filter(b => b.id !== id);
                            if (this.selectedBp && this.selectedBp.id === id) {
                                this.detailModalOpen = false;
                                this.selectedBp = null;
                            }
                            Alert.success('Terhapus!', response.data.message || 'Blueprint berhasil dihapus.');
                        }
                    }
                    catch (err) {
                        console.error(err);
                        Alert.error('Gagal!', 'Terjadi kesalahan saat menghapus blueprint.');
                    }
                }
            },

            async remindSme(bp) {
                if (!bp) return;
                try {
                    const response = await axios.post(`/admin-coordinator/blueprint/${bp.id}/remind`, {
                        reminder_setting: bp.reminder_setting || 'H-3'
                    });
                    if (response.data.success) {
                        Alert.success('Pengingat Terkirim!', response.data.message);
                    } else {
                        Alert.error('Gagal', response.data.message || 'Gagal mengirim pengingat.');
                    }
                } catch (err) {
                    console.error(err);
                    Alert.error('Gagal', 'Terjadi kesalahan saat mengirim pengingat ke SME.');
                }
            },

            get uniqueCategories() {
                const cats = this.blueprints.map(bp => bp.category);
                return [...new Set(cats)].sort();
            },

            get uniqueSmes() {
                const smes = this.blueprints.map(bp => bp.sme.name);
                return [...new Set(smes)].sort();
            },

            get activeFilterCount() {
                let count = 0;
                if (this.filters.status !== 'all') count++;
                if (this.filters.category.length > 0) count++;
                if (this.filters.sme.length > 0) count++;
                return count;
            },

            get filteredBlueprints() {
                return this.blueprints.filter(bp => {
                    const searchLower = this.search.toLowerCase();
                    const matchSearch = bp.title.toLowerCase().includes(searchLower) || 
                                       bp.id.toLowerCase().includes(searchLower);
                    
                    const matchStatus = this.filters.status === 'all' || bp.status_type.toLowerCase() === this.filters.status.toLowerCase();
                    const matchCategory = this.filters.category.length === 0 || this.filters.category.includes(bp.category);
                    const matchSme = this.filters.sme.length === 0 || this.filters.sme.includes(bp.sme.name);

                    return matchSearch && matchStatus && matchCategory && matchSme;
                });
            },

            applyFilters() {
                this.filters = JSON.parse(JSON.stringify(this.tempFilters));
                this.filterOpen = false;
                this.currentPage = 1;
            },

            resetFilters() {
                this.tempFilters = {
                    status: 'all',
                    category: [],
                    sme: []
                };
                this.filters = {
                    status: 'all',
                    category: [],
                    sme: []
                };
                this.search = '';
                this.currentPage = 1;
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
    /* Multi-level Numbering & Bullets for Rich Editor */
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
</style>
@endsection
