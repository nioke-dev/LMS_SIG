@extends('layouts.backoffice')

@section('sidebar-nav')
    @include('partials.ac-sidebar')
@endsection

@section('title', 'Direktori Subject Matter Expert (SME)')

@section('content')
<div x-data="smeDirectoryBoard()" class="pb-32 relative" x-cloak>
    {{-- Header Section --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-4xl font-black text-zinc-900 leading-tight tracking-tight mb-2">Direktori Pakar (SME)</h1>
            <p class="text-zinc-500 font-medium max-w-2xl leading-relaxed text-xs">Pusat informasi, kualifikasi, dan rekam jejak Subject Matter Expert (SME) di seluruh lingkungan Semen Indonesia Group (SIG).</p>
        </div>
        <div class="flex items-center gap-3 self-start sm:self-center">
            <span class="bg-primary/10 text-primary border border-primary/20 px-4 py-2.5 rounded-2xl text-xs font-black flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-base">verified_user</span>
                <span>SIG Knowledge Repository</span>
            </span>
        </div>
    </div>

    {{-- Top Summary Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        {{-- Card 1: Total Pakar --}}
        <div class="bg-white rounded-[2rem] p-7 border border-zinc-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-all">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-2">Total Pakar Aktif</p>
                <div class="flex items-baseline gap-3">
                    <h3 class="text-4xl font-black text-zinc-900 tracking-tight" x-text="smes.length"></h3>
                    <span class="text-[10px] font-bold text-zinc-500">SMEs</span>
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-zinc-50 text-zinc-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-500 border border-zinc-100">
                <span class="material-symbols-outlined text-3xl">groups</span>
            </div>
        </div>

        {{-- Card 2: Available SMEs --}}
        <div class="bg-white rounded-[2rem] p-7 border border-zinc-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-all">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-2">Tersedia (Available)</p>
                <div class="flex items-baseline gap-3">
                    <h3 class="text-4xl font-black text-emerald-600 tracking-tight" x-text="smes.filter(s => s.status === 'Available').length"></h3>
                    <span class="bg-emerald-50 text-emerald-600 border border-emerald-200 text-[10px] font-black px-2.5 py-0.5 rounded-full">Siap Tugas</span>
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-500 border border-emerald-100">
                <span class="material-symbols-outlined text-3xl">check_circle</span>
            </div>
        </div>

        {{-- Card 3: Busy SMEs --}}
        <div class="bg-white rounded-[2rem] p-7 border border-zinc-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-all">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-2">Sedang Bertugas</p>
                <div class="flex items-baseline gap-3">
                    <h3 class="text-4xl font-black text-rose-600 tracking-tight" x-text="smes.filter(s => s.status === 'Busy').length"></h3>
                    <span class="bg-rose-50 text-rose-600 border border-rose-200 text-[10px] font-black px-2.5 py-0.5 rounded-full">Beban Penuh</span>
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-500 border border-rose-100">
                <span class="material-symbols-outlined text-3xl">work_history</span>
            </div>
        </div>
    </div>

    {{-- Filter Bar Container --}}
    <div class="bg-white rounded-[2.5rem] border border-zinc-100 shadow-sm p-6 mb-8 space-y-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            {{-- Search Bar --}}
            <div class="w-full md:w-96 relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 text-lg">search</span>
                <input type="text" x-model="search" placeholder="Cari nama, NIK, atau jabatan pakar..." 
                       class="w-full pl-11 pr-4 py-3.5 bg-zinc-50 border border-zinc-200/80 rounded-2xl text-xs font-bold text-zinc-800 placeholder-zinc-400 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
            </div>

            {{-- Quick Filter Pills --}}
            <div class="w-full md:w-auto flex flex-wrap items-center gap-2">
                <button @click="filterStatus = 'all'" 
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all border"
                        :class="filterStatus === 'all' ? 'bg-primary text-white border-primary shadow-sm' : 'bg-zinc-50 text-zinc-600 border-zinc-200 hover:bg-zinc-100'">
                    Semua Status
                </button>
                <button @click="filterStatus = 'Available'" 
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all border flex items-center gap-1.5"
                        :class="filterStatus === 'Available' ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-zinc-50 text-zinc-600 border-zinc-200 hover:bg-zinc-100'">
                    <span class="w-2 h-2 rounded-full" :class="filterStatus === 'Available' ? 'bg-white' : 'bg-emerald-500'"></span>
                    Available
                </button>
                <button @click="filterStatus = 'Busy'" 
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all border flex items-center gap-1.5"
                        :class="filterStatus === 'Busy' ? 'bg-rose-600 text-white border-rose-600 shadow-sm' : 'bg-zinc-50 text-zinc-600 border-zinc-200 hover:bg-zinc-100'">
                    <span class="w-2 h-2 rounded-full" :class="filterStatus === 'Busy' ? 'bg-white' : 'bg-rose-500'"></span>
                    Busy
                </button>
                <button @click="filterStatus = 'On Leave'" 
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all border flex items-center gap-1.5"
                        :class="filterStatus === 'On Leave' ? 'bg-amber-600 text-white border-amber-600 shadow-sm' : 'bg-zinc-50 text-zinc-600 border-zinc-200 hover:bg-zinc-100'">
                    <span class="w-2 h-2 rounded-full" :class="filterStatus === 'On Leave' ? 'bg-white' : 'bg-amber-500'"></span>
                    On Leave
                </button>
            </div>
        </div>

        {{-- Dropdown Filters Row --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 pt-4 border-t border-zinc-100">
            {{-- Filter Perusahaan (Custom Dropdown) --}}
            <div class="relative" @click.away="companyDropdownOpen = false">
                <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-2">Filter Perusahaan (OpCo)</label>
                <button @click="companyDropdownOpen = !companyDropdownOpen; rumpunDropdownOpen = false" type="button"
                        class="w-full p-3.5 bg-zinc-50 border border-zinc-200/80 rounded-2xl text-xs font-bold text-zinc-800 flex items-center justify-between hover:bg-zinc-100/80 transition-all focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary shadow-sm">
                    <span class="truncate" x-text="filterCompany ? companies.find(c => c.id == filterCompany)?.name : 'Semua Perusahaan'"></span>
                    <span class="material-symbols-outlined text-zinc-400 text-lg transition-transform duration-200" :class="companyDropdownOpen ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="companyDropdownOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute z-50 w-full mt-2 bg-white border border-zinc-100 rounded-2xl shadow-xl max-h-60 overflow-y-auto py-2 custom-scrollbar">
                    <div @click="filterCompany = ''; companyDropdownOpen = false; currentPage = 1"
                         class="px-4 py-3 text-xs font-bold text-zinc-700 hover:bg-primary/10 hover:text-primary cursor-pointer transition-colors flex items-center justify-between"
                         :class="filterCompany === '' ? 'bg-primary/10 text-primary font-black' : ''">
                        <span>Semua Perusahaan</span>
                        <span class="material-symbols-outlined text-base" x-show="filterCompany === ''">check</span>
                    </div>
                    <template x-for="comp in companies" :key="comp.id">
                        <div @click="filterCompany = comp.id; companyDropdownOpen = false; currentPage = 1"
                             class="px-4 py-3 text-xs font-bold text-zinc-700 hover:bg-primary/10 hover:text-primary cursor-pointer transition-colors flex items-center justify-between"
                             :class="filterCompany === comp.id ? 'bg-primary/10 text-primary font-black' : ''">
                            <span class="truncate" x-text="comp.name"></span>
                            <span class="material-symbols-outlined text-base" x-show="filterCompany === comp.id">check</span>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Filter Rumpun Ilmu (Custom Dropdown) --}}
            <div class="relative" @click.away="rumpunDropdownOpen = false">
                <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-2">Filter Rumpun Ilmu</label>
                <button @click="rumpunDropdownOpen = !rumpunDropdownOpen; companyDropdownOpen = false" type="button"
                        class="w-full p-3.5 bg-zinc-50 border border-zinc-200/80 rounded-2xl text-xs font-bold text-zinc-800 flex items-center justify-between hover:bg-zinc-100/80 transition-all focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary shadow-sm">
                    <span class="truncate" x-text="filterRumpun ? filterRumpun : 'Semua Rumpun Ilmu'"></span>
                    <span class="material-symbols-outlined text-zinc-400 text-lg transition-transform duration-200" :class="rumpunDropdownOpen ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="rumpunDropdownOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute z-50 w-full mt-2 bg-white border border-zinc-100 rounded-2xl shadow-xl max-h-60 overflow-y-auto py-2 custom-scrollbar">
                    <div @click="filterRumpun = ''; rumpunDropdownOpen = false; currentPage = 1"
                         class="px-4 py-3 text-xs font-bold text-zinc-700 hover:bg-primary/10 hover:text-primary cursor-pointer transition-colors flex items-center justify-between"
                         :class="filterRumpun === '' ? 'bg-primary/10 text-primary font-black' : ''">
                        <span>Semua Rumpun Ilmu</span>
                        <span class="material-symbols-outlined text-base" x-show="filterRumpun === ''">check</span>
                    </div>
                    <template x-for="r in rumpunList" :key="r">
                        <div @click="filterRumpun = r; rumpunDropdownOpen = false; currentPage = 1"
                             class="px-4 py-3 text-xs font-bold text-zinc-700 hover:bg-primary/10 hover:text-primary cursor-pointer transition-colors flex items-center justify-between"
                             :class="filterRumpun === r ? 'bg-primary/10 text-primary font-black' : ''">
                            <span class="truncate" x-text="r"></span>
                            <span class="material-symbols-outlined text-base" x-show="filterRumpun === r">check</span>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Reset Filters Button --}}
            <div class="flex items-end">
                <button @click="resetFilters()" class="w-full py-3.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 font-black text-xs rounded-2xl transition-all flex items-center justify-center gap-2 border border-zinc-200/80 shadow-sm">
                    <span class="material-symbols-outlined text-sm">refresh</span>
                    <span>Reset Semua Filter</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Table Header Controls (Pagination & Per Page) --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 px-4 mb-4">
        <div class="flex items-center gap-3">
            <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Tampilkan</span>
            <div class="relative" @click.away="perPageOpen = false">
                <button @click="perPageOpen = !perPageOpen" type="button"
                    class="px-3 py-1.5 bg-white border border-zinc-200 rounded-xl text-xs font-black text-zinc-700 flex items-center gap-2 shadow-sm hover:border-primary/30 transition-all">
                    <span x-text="perPage"></span>
                    <span class="material-symbols-outlined text-sm transition-transform duration-200" :class="perPageOpen ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="perPageOpen" x-transition
                     class="absolute z-40 w-20 mt-1 bg-white border border-zinc-100 rounded-2xl shadow-xl overflow-hidden py-1">
                    <template x-for="opt in [5, 10, 25, 50]" :key="opt">
                        <div @click="perPage = opt; perPageOpen = false; currentPage = 1"
                             class="px-3 py-2 text-xs font-black text-zinc-600 hover:bg-primary/10 hover:text-primary cursor-pointer transition-colors text-center"
                             :class="perPage === opt ? 'bg-primary/10 text-primary font-black' : ''">
                            <span x-text="opt"></span>
                        </div>
                    </template>
                </div>
            </div>
            <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">data per halaman</span>
        </div>

        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">
            Showing <span class="text-zinc-900" x-text="rangeText"></span> of <span class="text-zinc-900" x-text="filteredSmes.length.toLocaleString()"></span> Pakar
        </p>
    </div>

    {{-- SMEs Enterprise Table Container --}}
    <div class="bg-white rounded-[2.5rem] border border-zinc-100 shadow-sm overflow-hidden mb-8">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-zinc-50/75 border-b border-zinc-100 text-[10px] font-black uppercase tracking-widest text-zinc-400">
                        <th class="py-5 px-6 whitespace-nowrap">Pakar / NIK</th>
                        <th class="py-5 px-6 whitespace-nowrap">Perusahaan</th>
                        <th class="py-5 px-6 text-center whitespace-nowrap">Status & Beban</th>
                        <th class="py-5 px-6 text-center whitespace-nowrap">Rating</th>
                        <th class="py-5 px-6 text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 text-xs font-medium text-zinc-600">
                    <template x-for="sme in paginatedSmes" :key="sme.id">
                        <tr class="hover:bg-zinc-50/50 transition-colors group">
                            {{-- Pakar / NIK --}}
                            <td class="py-5 px-6 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <img :src="sme.avatar" :alt="sme.name" class="w-12 h-12 rounded-2xl object-cover border border-zinc-100 shadow-sm shrink-0 group-hover:scale-105 transition-transform">
                                    <div>
                                        <p class="text-[10px] font-black text-primary uppercase tracking-widest mb-0.5" x-text="'NIK: ' + sme.nik"></p>
                                        <h4 class="text-sm font-black text-zinc-900 group-hover:text-primary transition-colors" x-text="sme.name"></h4>
                                        <p class="text-[11px] font-bold text-zinc-500 mt-0.5" x-text="sme.position"></p>
                                    </div>
                                </div>
                            </td>

                            {{-- Perusahaan --}}
                            <td class="py-5 px-6 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-zinc-100/80 rounded-xl text-xs font-bold text-zinc-700">
                                    <span class="material-symbols-outlined text-sm text-zinc-400">apartment</span>
                                    <span x-text="sme.company_name"></span>
                                </span>
                            </td>

                            {{-- Status & Beban --}}
                            <td class="py-5 px-6 text-center">
                                <div class="flex flex-col items-center gap-1.5">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border inline-flex items-center gap-1 shadow-sm"
                                          :class="{
                                              'bg-emerald-50 text-emerald-600 border-emerald-200': sme.status === 'Available',
                                              'bg-rose-50 text-rose-600 border-rose-200': sme.status === 'Busy',
                                              'bg-amber-50 text-amber-600 border-amber-200': sme.status === 'On Leave'
                                          }">
                                        <span class="w-1.5 h-1.5 rounded-full"
                                              :class="{
                                                  'bg-emerald-500 animate-pulse': sme.status === 'Available',
                                                  'bg-rose-500': sme.status === 'Busy',
                                                  'bg-amber-500': sme.status === 'On Leave'
                                              }"></span>
                                        <span x-text="sme.status"></span>
                                    </span>
                                    <span class="text-[11px] font-black text-zinc-500 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">work</span>
                                        <span x-text="sme.load_count + ' Blueprint'"></span>
                                    </span>
                                </div>
                            </td>

                            {{-- Rating --}}
                            <td class="py-5 px-6 text-center">
                                <div class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50/50 border border-amber-100 rounded-xl text-amber-600 font-black text-xs">
                                    <span class="material-symbols-outlined text-sm">star</span>
                                    <span x-text="sme.rating"></span>
                                </div>
                            </td>

                            {{-- Aksi --}}
                            <td class="py-5 px-6 text-center">
                                <button @click.stop="openDetail(sme, 'overview')" class="px-4 py-2 bg-zinc-100 hover:bg-zinc-900 text-zinc-700 hover:text-white font-black text-xs rounded-xl shadow-sm hover:shadow-md transition-all duration-300 inline-flex items-center gap-1.5 group/btn">
                                    <span>Lihat Detail</span>
                                    <span class="material-symbols-outlined text-sm group-hover/btn:translate-x-0.5 transition-transform">open_in_new</span>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Table Footer / Summary & Pagination Controls --}}
        <div class="p-6 border-t border-zinc-100 bg-zinc-50/50 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-zinc-500 font-bold">
            <div>
                Menampilkan <span class="text-zinc-900 font-black" x-text="rangeText"></span> dari <span class="text-zinc-900 font-black" x-text="filteredSmes.length"></span> Subject Matter Expert
                <template x-if="filteredSmes.length < smes.length">
                    <span class="text-[11px] text-zinc-400 font-medium ml-1">(difilter dari total <span x-text="smes.length"></span> pakar)</span>
                </template>
            </div>
            
            {{-- Pagination Buttons --}}
            <div class="flex items-center gap-1.5">
                <button @click="prevPage()" :disabled="currentPage === 1" 
                    class="w-8 h-8 rounded-xl flex items-center justify-center text-zinc-500 hover:bg-white hover:shadow-sm disabled:opacity-20 transition-all border border-zinc-200/60 bg-zinc-100/50">
                    <span class="material-symbols-outlined text-base">chevron_left</span>
                </button>
                <template x-for="page in pageNumbers" :key="page">
                    <button @click="goToPage(page)" 
                            class="w-8 h-8 rounded-xl text-xs font-black transition-all flex items-center justify-center border"
                            :class="page === currentPage ? 'bg-primary text-white border-primary shadow-md shadow-primary/20' : 'text-zinc-600 hover:bg-white border-zinc-200/60 bg-zinc-100/50'"
                            x-text="page"></button>
                </template>
                <button @click="nextPage()" :disabled="currentPage === totalPages" 
                    class="w-8 h-8 rounded-xl flex items-center justify-center text-zinc-500 hover:bg-white hover:shadow-sm disabled:opacity-20 transition-all border border-zinc-200/60 bg-zinc-100/50">
                    <span class="material-symbols-outlined text-base">chevron_right</span>
                </button>
            </div>
        </div>

        {{-- Empty State --}}
        <template x-if="filteredSmes.length === 0">
            <div class="p-16 text-center space-y-4 bg-white border-t border-zinc-100 shadow-sm">
                <div class="w-20 h-20 bg-zinc-50 rounded-full flex items-center justify-center mx-auto text-zinc-300 border border-zinc-100">
                    <span class="material-symbols-outlined text-4xl">person_search</span>
                </div>
                <h3 class="text-xl font-black text-zinc-800 tracking-tight">Tidak Ada Pakar Ditemukan</h3>
                <p class="text-xs text-zinc-500 max-w-md mx-auto leading-relaxed">Pencarian dengan kata kunci atau kombinasi filter saat ini tidak cocok dengan data Subject Matter Expert mana pun di sistem.</p>
                <button @click="resetFilters()" class="px-6 py-3 bg-primary text-white font-black text-xs rounded-2xl shadow-lg shadow-primary/20 hover:bg-red-600 transition-all">
                    Reset Semua Filter
                </button>
            </div>
        </template>
    </div>

    {{-- DETAIL MODAL: RICH ENTERPRISE SME PROFILE --}}
    <template x-if="selectedSme">
        <div class="fixed inset-0 z-[70] flex items-center justify-center overflow-y-auto bg-zinc-900/60 backdrop-blur-sm p-4"
             @click.self="closeDetail()"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="relative w-full max-w-4xl bg-white rounded-[2.5rem] shadow-2xl border border-zinc-100 overflow-hidden flex flex-col max-h-[90vh]"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                
                {{-- Modal Header Banner --}}
                <div class="p-8 bg-white border-b border-zinc-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 relative shrink-0">
                    <div class="flex items-center gap-5 z-10">
                        <img :src="selectedSme.avatar" :alt="selectedSme.name" class="w-20 h-20 rounded-2xl object-cover border border-zinc-200 shadow-md shrink-0">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <span class="bg-primary text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-sm" x-text="selectedSme.nik"></span>
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border flex items-center gap-1.5"
                                      :class="{
                                          'bg-emerald-50 text-emerald-600 border-emerald-200': selectedSme.status === 'Available',
                                          'bg-rose-50 text-rose-600 border-rose-200': selectedSme.status === 'Busy',
                                          'bg-amber-50 text-amber-600 border-amber-200': selectedSme.status === 'On Leave'
                                      }">
                                    <span class="w-1.5 h-1.5 rounded-full"
                                          :class="{
                                              'bg-emerald-500 animate-pulse': selectedSme.status === 'Available',
                                              'bg-rose-500': selectedSme.status === 'Busy',
                                              'bg-amber-500': selectedSme.status === 'On Leave'
                                          }"></span>
                                    <span x-text="selectedSme.status"></span>
                                </span>
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-black text-zinc-900 tracking-tight" x-text="selectedSme.name"></h2>
                            <p class="text-zinc-500 font-bold text-xs mt-0.5" x-text="selectedSme.position"></p>
                        </div>
                    </div>

                    {{-- Close Button --}}
                    <button @click="closeDetail()" class="absolute top-6 right-6 w-10 h-10 rounded-full bg-zinc-100 hover:bg-zinc-200 text-zinc-500 hover:text-zinc-800 flex items-center justify-center transition-all z-10">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>

                {{-- Modal Navigation Tabs --}}
                <div class="flex border-b border-zinc-100 bg-zinc-50/50 px-8 gap-6 shrink-0 overflow-x-auto custom-scrollbar">
                    <button @click="modalTab = 'overview'" class="py-4 text-xs font-black uppercase tracking-wider border-b-2 transition-all shrink-0 flex items-center gap-2"
                            :class="modalTab === 'overview' ? 'border-primary text-primary' : 'border-transparent text-zinc-400 hover:text-zinc-600'">
                        <span class="material-symbols-outlined text-base">person</span>
                        <span>Informasi Umum</span>
                    </button>
                    <button @click="modalTab = 'skills'" class="py-4 text-xs font-black uppercase tracking-wider border-b-2 transition-all shrink-0 flex items-center gap-2"
                            :class="modalTab === 'skills' ? 'border-primary text-primary' : 'border-transparent text-zinc-400 hover:text-zinc-600'">
                        <span class="material-symbols-outlined text-base">military_tech</span>
                        <span>Riwayat Mengajar & Kategori</span>
                    </button>
                    <button @click="modalTab = 'projects'" class="py-4 text-xs font-black uppercase tracking-wider border-b-2 transition-all shrink-0 flex items-center gap-2"
                            :class="modalTab === 'projects' ? 'border-primary text-primary' : 'border-transparent text-zinc-400 hover:text-zinc-600'">
                        <span class="material-symbols-outlined text-base">work_history</span>
                        <span>Rekam Jejak Proyek</span>
                    </button>
                </div>

                {{-- Modal Body Content --}}
                <div class="p-8 overflow-y-auto flex-1 custom-scrollbar space-y-8 bg-white">
                    {{-- TAB 1: OVERVIEW --}}
                    <div x-show="modalTab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                        {{-- Kinerja & Rekam Jejak Cards --}}
                        <div>
                            <h3 class="text-xs font-black uppercase tracking-widest text-zinc-400 mb-4">Metrik Kinerja & Rekam Jejak Pakar</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <div class="bg-zinc-50 p-5 rounded-2xl border border-zinc-100 text-center">
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-1">Beban Saat Ini</span>
                                    <h4 class="text-2xl font-black text-zinc-900 tracking-tight mb-1" x-text="selectedSme.load_count + ' Blueprint'"></h4>
                                    <span class="text-[10px] font-bold text-zinc-500" x-text="selectedSme.status === 'Available' ? 'Kapasitas Tersedia' : 'Beban Tinggi'"></span>
                                </div>
                                <div class="bg-zinc-50 p-5 rounded-2xl border border-zinc-100 text-center">
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-1">Total Blueprint Rilis</span>
                                    <h4 class="text-2xl font-black text-primary tracking-tight mb-1" x-text="selectedSme.completed_blueprints + ' Blueprint'"></h4>
                                    <span class="text-[10px] font-bold text-zinc-500">Berhasil Rilis Pelatihan</span>
                                </div>
                                <div class="bg-zinc-50 p-5 rounded-2xl border border-zinc-100 text-center">
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-1">Total Pelatihan Tuntas</span>
                                    <h4 class="text-2xl font-black text-zinc-900 tracking-tight mb-1" x-text="selectedSme.completed_trainings + ' Pelatihan'"></h4>
                                    <span class="text-[10px] font-bold text-zinc-500">Tuntas Sebagai Pengajar</span>
                                </div>
                                <div class="bg-zinc-50 p-5 rounded-2xl border border-zinc-100 text-center">
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-1">Rating Review</span>
                                    <h4 class="text-2xl font-black text-amber-500 tracking-tight mb-1 flex items-center justify-center gap-1">
                                        <span class="material-symbols-outlined text-xl">star</span>
                                        <span x-text="selectedSme.rating"></span>
                                    </h4>
                                    <span class="text-[10px] font-bold text-zinc-500">Skala 5.0 Maks</span>
                                </div>
                            </div>
                        </div>

                        {{-- Lokasi & Kontak Kerja --}}
                        <div>
                            <h3 class="text-xs font-black uppercase tracking-widest text-zinc-400 mb-4">Informasi Penempatan & Kontak</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="flex items-start gap-4 p-5 bg-zinc-50 rounded-2xl border border-zinc-100">
                                    <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary shrink-0 border border-zinc-100">
                                        <span class="material-symbols-outlined text-2xl">apartment</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <span class="block text-[10px] font-black uppercase tracking-wider text-zinc-400 mb-1">Perusahaan</span>
                                        <h5 class="text-xs font-black text-zinc-800 mb-2" x-text="selectedSme.company_name"></h5>
                                        <div class="space-y-1.5 mt-2">
                                            <template x-for="(item, index) in selectedSme.hierarchy" :key="index">
                                                <div class="flex items-center gap-2 text-xs">
                                                    <span class="text-[9px] font-black px-1.5 py-0.5 bg-zinc-200/70 text-zinc-600 rounded uppercase tracking-wider shrink-0" x-text="item.level"></span>
                                                    <span class="font-bold text-zinc-700 truncate" x-text="item.name"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4 p-5 bg-zinc-50 rounded-2xl border border-zinc-100 self-start">
                                    <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary shrink-0 border border-zinc-100">
                                        <span class="material-symbols-outlined text-2xl">contact_mail</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <span class="block text-[10px] font-black uppercase tracking-wider text-zinc-400 mb-1">Kontak Resmi</span>
                                        <h5 class="text-xs font-black text-zinc-800 mb-1 truncate" x-text="selectedSme.email"></h5>
                                        <p class="text-xs font-medium text-zinc-500" x-text="selectedSme.phone"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 2: SKILLS & CERTIFICATIONS --}}
                    <div x-show="modalTab === 'skills'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                        {{-- Rumpun Ilmu & Keahlian Utama --}}
                        <div>
                            <h3 class="text-xs font-black uppercase tracking-widest text-zinc-400 mb-4">Riwayat Kategori</h3>
                            <template x-if="selectedSme.teaching_categories && selectedSme.teaching_categories.length > 0">
                                <div class="space-y-4">
                                    <template x-for="cat in selectedSme.teaching_categories" :key="cat.parent">
                                        <div class="p-5 bg-zinc-50 border border-zinc-100 rounded-2xl space-y-3 hover:border-primary/20 transition-all">
                                            <div class="flex items-center gap-2 pb-3 border-b border-zinc-200/60">
                                                <span class="material-symbols-outlined text-primary text-xl">category</span>
                                                <h4 class="text-xs font-black text-zinc-800 uppercase tracking-wider" x-text="cat.parent"></h4>
                                            </div>
                                            <div class="flex flex-wrap gap-2">
                                                <template x-for="topic in cat.topics" :key="topic">
                                                    <span class="bg-white border border-zinc-200 text-zinc-700 text-xs font-bold px-3.5 py-1.5 rounded-xl shadow-sm flex items-center gap-1.5">
                                                        <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                                        <span x-text="topic"></span>
                                                    </span>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!selectedSme.teaching_categories || selectedSme.teaching_categories.length === 0">
                                <div class="p-6 bg-zinc-50 border border-zinc-200/80 rounded-2xl text-center">
                                    <span class="material-symbols-outlined text-zinc-400 text-3xl mb-2 block">info</span>
                                    <h4 class="text-xs font-black text-zinc-700 mb-1">Belum Ada Riwayat Mengajar</h4>
                                    <p class="text-zinc-500 text-[11px] max-w-sm mx-auto">Pakar ini belum memiliki riwayat mengajar atau memfasilitasi kelas di LMS SIG sebelumnya.</p>
                                </div>
                            </template>
                        </div>

                        {{-- Jejak Mengajar & Ulasan Pelatihan --}}
                        <div>
                            <h3 class="text-xs font-black uppercase tracking-widest text-zinc-400 mb-4">Jejak Mengajar & Ulasan Pelatihan</h3>
                            <div class="space-y-4">
                                <template x-for="(history, hIdx) in selectedSme.teaching_history" :key="hIdx">
                                    <div class="p-5 bg-zinc-50 border border-zinc-100 rounded-2xl space-y-3 hover:border-primary/20 transition-all">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-zinc-200/60">
                                            <div>
                                                <span class="text-[10px] font-black px-2 py-0.5 bg-primary/10 text-primary rounded-full uppercase tracking-wider" x-text="history.type"></span>
                                                <h4 class="text-xs font-black text-zinc-900 mt-1.5" x-text="history.training_name"></h4>
                                                <span class="text-[11px] font-medium text-zinc-500 flex items-center gap-1 mt-0.5">
                                                    <span class="material-symbols-outlined text-xs">calendar_today</span>
                                                    <span x-text="history.date"></span>
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-1.5 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-xl self-start sm:self-auto shadow-sm">
                                                <span class="material-symbols-outlined text-amber-500 text-sm">star</span>
                                                <span class="text-xs font-black text-amber-700" x-text="history.rating"></span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4 text-[11px] text-zinc-600 pt-1">
                                            <div class="flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-zinc-400 text-sm">group</span>
                                                <span class="font-medium" x-text="history.participants_count + ' Peserta'"></span>
                                            </div>
                                            <span class="text-zinc-300">•</span>
                                            <div class="flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-emerald-500 text-sm">verified</span>
                                                <span class="font-medium text-emerald-700" x-text="history.eval_predicate"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 3: RELEASED BLUEPRINTS TRACK RECORD --}}
                    <div x-show="modalTab === 'projects'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                        <div class="flex items-center justify-between border-b border-zinc-100 pb-4">
                            <h3 class="text-xs font-black uppercase tracking-widest text-zinc-400">Riwayat Blueprint</h3>
                            <span class="text-xs font-bold text-zinc-500" x-text="selectedSme.completed_blueprints + ' Total Blueprint Rilis'"></span>
                        </div>

                        <div class="space-y-4">
                            <template x-for="bp in selectedSme.released_blueprints" :key="bp.title">
                                <div class="p-5 bg-zinc-50 border border-zinc-100 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-primary/20 transition-all shadow-sm">
                                    <div class="flex items-start gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 mt-0.5 border border-primary/10">
                                            <span class="material-symbols-outlined text-xl">menu_book</span>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-[10px] font-black px-2 py-0.5 bg-zinc-200/70 text-zinc-700 rounded-full uppercase tracking-wider" x-text="bp.category"></span>
                                            <h4 class="text-xs font-bold text-zinc-900 truncate mt-1.5" x-text="bp.title"></h4>
                                            <span class="text-[11px] font-medium text-zinc-500 flex items-center gap-1 mt-0.5">
                                                <span class="material-symbols-outlined text-xs">event_available</span>
                                                <span x-text="'Selesai & Rilis: ' + bp.release_date"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-xl self-start sm:self-auto shrink-0 shadow-sm">
                                        <span class="material-symbols-outlined text-emerald-500 text-sm">verified</span>
                                        <span class="text-xs font-black text-emerald-700 uppercase tracking-wider" x-text="bp.status"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
            </div>
        </div>
    </template>
</div>

<script>
function smeDirectoryBoard() {
    return {
        smes: @json($smes),
        companies: @json($companies),
        rumpunList: @json($rumpunList),
        
        search: '',
        filterStatus: 'all',
        filterCompany: '',
        filterRumpun: '',

        companyDropdownOpen: false,
        rumpunDropdownOpen: false,

        currentPage: 1,
        perPage: 10,
        perPageOpen: false,

        selectedSme: null,
        modalTab: 'overview',

        init() {
            this.$watch('search', () => this.currentPage = 1);
            this.$watch('filterStatus', () => this.currentPage = 1);
            this.$watch('filterCompany', () => this.currentPage = 1);
            this.$watch('filterRumpun', () => this.currentPage = 1);
            this.$watch('perPage', () => this.currentPage = 1);
        },

        get filteredSmes() {
            return this.smes.filter(sme => {
                const matchSearch = sme.name.toLowerCase().includes(this.search.toLowerCase()) ||
                       sme.nik.toLowerCase().includes(this.search.toLowerCase()) ||
                       sme.position.toLowerCase().includes(this.search.toLowerCase()) ||
                       sme.rumpun.toLowerCase().includes(this.search.toLowerCase());
                
                const matchStatus = this.filterStatus === 'all' || sme.status === this.filterStatus;
                const matchCompany = !this.filterCompany || sme.company_id == this.filterCompany;
                const matchRumpun = !this.filterRumpun || sme.rumpun === this.filterRumpun;

                return matchSearch && matchStatus && matchCompany && matchRumpun;
            });
        },

        get paginatedSmes() {
            const start = (this.currentPage - 1) * parseInt(this.perPage);
            return this.filteredSmes.slice(start, start + parseInt(this.perPage));
        },

        get totalPages() { 
            return Math.ceil(this.filteredSmes.length / parseInt(this.perPage)) || 1; 
        },

        get rangeText() {
            if (this.filteredSmes.length === 0) return '0';
            const start = (this.currentPage - 1) * parseInt(this.perPage) + 1;
            const end = Math.min(this.currentPage * parseInt(this.perPage), this.filteredSmes.length);
            return start + ' - ' + end;
        },

        get pageNumbers() {
            const total = this.totalPages;
            const pages = [];
            for (let i = 1; i <= total; i++) pages.push(i);
            return pages;
        },

        goToPage(p) { this.currentPage = p; },
        prevPage() { if (this.currentPage > 1) this.currentPage--; },
        nextPage() { if (this.currentPage < this.totalPages) this.currentPage++; },

        resetFilters() {
            this.search = '';
            this.filterStatus = 'all';
            this.filterCompany = '';
            this.filterRumpun = '';
            this.currentPage = 1;
        },

        openDetail(sme, tab = 'overview') {
            this.selectedSme = sme;
            this.modalTab = tab;
            document.body.style.overflow = 'hidden';
        },

        closeDetail() {
            this.selectedSme = null;
            document.body.style.overflow = '';
        },

        assignSme(sme) {
            alert(`Pakar ${sme.name} telah dipilih untuk penugasan blueprint. Mengalihkan ke halaman inisiasi...`);
            this.closeDetail();
            window.location.href = "{{ route('admin-coordinator.blueprint-directory') }}";
        }
    }
}
</script>
@endsection
