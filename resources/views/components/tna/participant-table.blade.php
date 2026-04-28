@props(['participants', 'selected' => []])

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e4e4e7;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #d4d4d8;
    }
</style>

<div x-data="{ 
    participants: @js($participants).map(p => ({ 
        ...p, 
        showCard: false,
        history: [
            { name: 'Leadership Excellence Foundation', date: '12 Jan 2024', status: 'Selesai' },
            { name: 'Strategic Project Management', date: '05 Nov 2023', status: 'Selesai' },
            { name: 'Digital Transformation in Cement Industry', date: '20 Aug 2023', status: 'Selesai' }
        ]
    })),
    participantSearch: '',
    selectedIds: @js(collect($selected)->pluck('nik')->toArray()),
    visibleCount: 10,
    isModalOpen: false,
    detailParticipant: null,

    openDetail(p) {
        this.detailParticipant = p;
        this.isModalOpen = true;
    },

    closeDetail() {
        this.isModalOpen = false;
        this.detailParticipant = null;
    },
    
    get filteredParticipants() {
        if (!this.participantSearch) return this.participants;
        return this.participants.filter(p => 
            p.name.toLowerCase().includes(this.participantSearch.toLowerCase()) ||
            p.nik.toString().includes(this.participantSearch) ||
            p.jabatan.toLowerCase().includes(this.participantSearch.toLowerCase())
        );
    },
    
    get visibleParticipants() {
        return this.filteredParticipants.slice(0, this.visibleCount);
    },

    get selectedParticipants() {
        return this.participants.filter(p => this.selectedIds.includes(p.nik))
            .map(p => ({
                id: p.id,
                name: p.name,
                nik: p.nik,
                position: p.jabatan
            }));
    },

    toggleSelect(nik) {
        if (this.selectedIds.includes(nik)) {
            this.selectedIds = this.selectedIds.filter(id => id !== nik);
        } else {
            this.selectedIds.push(nik);
        }
        this.$dispatch('participants-updated', {
            count: this.selectedIds.length,
            list: this.selectedParticipants
        });
    },

    toggleAll() {
        if (this.isAllSelected) {
            this.selectedIds = [];
        } else {
            this.selectedIds = this.filteredParticipants.map(p => p.nik);
        }
        this.$dispatch('participants-updated', {
            count: this.selectedIds.length,
            list: this.selectedParticipants
        });
    },

    get isAllSelected() {
        return this.filteredParticipants.length > 0 && this.selectedIds.length === this.filteredParticipants.length;
    },

    get isIndeterminate() {
        return this.selectedIds.length > 0 && this.selectedIds.length < this.filteredParticipants.length;
    }
}" x-init="$watch('selectedIds', value => $dispatch('participants-updated', { count: value.length, list: selectedParticipants }))" class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center text-red-500 shadow-sm border border-red-100/50">
                <span class="material-symbols-outlined text-3xl">group</span>
            </div>
            <div>
                <h2 class="text-xl font-black text-on-surface tracking-tight uppercase">Daftar Peserta</h2>
                <div class="flex items-center gap-2 mt-1">
                    <div class="px-2 py-0.5 rounded-lg bg-red-50 text-red-500 text-[10px] font-black" x-text="selectedIds.length"></div>
                    <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Karyawan Terpilih</span>
                </div>
            </div>
        </div>
        
        <div class="relative w-full md:w-80">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 text-lg">search</span>
            <input type="text" x-model="participantSearch" placeholder="Cari NIK, Nama, atau Jabatan..." 
                   class="w-full pl-12 pr-4 py-3 bg-zinc-50 border-zinc-200 rounded-2xl text-xs focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all italic">
        </div>
    </div>

    <div class="bg-white border border-zinc-100 rounded-[2rem] shadow-sm relative">
        <table class="w-full text-left border-separate border-spacing-0">
            <thead>
                <tr class="bg-zinc-50/50 text-[10px] font-black text-zinc-400 uppercase tracking-widest">
                    <th class="px-6 py-4 w-12 border-b border-zinc-100 rounded-tl-[2rem]">
                        <input @click="toggleAll()" :checked="isAllSelected" :indeterminate="isIndeterminate" class="w-4 h-4 rounded-full border-zinc-300 text-primary focus:ring-primary/20 cursor-pointer" type="checkbox" />
                    </th>
                    <th class="px-6 py-4 border-b border-zinc-100">Informasi Karyawan</th>
                    <th class="px-6 py-4 border-b border-zinc-100">Jabatan</th>
                    <th class="px-6 py-4 text-center w-28 border-b border-zinc-100 rounded-tr-[2rem]">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50">
                <template x-for="p in visibleParticipants" :key="p.nik">
                    <tr class="hover:bg-zinc-50/30 transition-colors group cursor-pointer relative" 
                        @click="toggleSelect(p.nik)"
                        @mouseenter="p.showCard = true"
                        @mouseleave="p.showCard = false">
                        <td class="px-6 py-4">
                            {{-- Custom Red Circle Checkbox --}}
                            <div class="flex items-center justify-center">
                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
                                     :class="selectedIds.includes(p.nik) ? 'bg-red-500 border-red-500' : 'border-zinc-200'">
                                    <span x-show="selectedIds.includes(p.nik)" class="material-symbols-outlined text-white text-[14px] font-black">check</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 relative">
                            {{-- Hover Card code remains same --}}
                            <div x-show="p.showCard" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute bottom-full left-0 mb-2 w-60 bg-white rounded-2xl shadow-[0_15px_40px_rgba(0,0,0,0.12)] border border-zinc-100 z-[100] overflow-hidden pointer-events-none">
                                <div class="h-12 bg-gradient-to-r from-red-400 to-rose-500 relative">
                                    <template x-if="p.photo">
                                        <img :src="p.photo" class="absolute -bottom-4 left-4 w-10 h-10 rounded-xl bg-white border-2 border-white shadow-sm object-cover" />
                                    </template>
                                    <template x-if="!p.photo">
                                        <div class="absolute -bottom-4 left-4 w-10 h-10 rounded-xl bg-white border-2 border-white shadow-sm flex items-center justify-center font-black text-[10px]" :class="p.avatarClass" x-text="p.initials"></div>
                                    </template>
                                </div>
                                <div class="p-4 pt-6">
                                    <h4 class="text-sm font-black text-on-surface mb-3" x-text="p.name"></h4>
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2 text-zinc-500">
                                            <span class="material-symbols-outlined text-base">work</span>
                                            <span class="text-[10px] font-bold truncate" x-text="p.jabatan"></span>
                                        </div>
                                        <div class="flex items-center gap-2 text-zinc-400">
                                            <span class="material-symbols-outlined text-base">mail</span>
                                            <span class="text-[10px] font-medium truncate" x-text="p.email || (p.name.toLowerCase().replace(' ', '.') + '@sig.id')"></span>
                                        </div>
                                        <div class="flex items-center gap-2 text-zinc-400">
                                            <span class="material-symbols-outlined text-base">call</span>
                                            <span class="text-[10px] font-medium" x-text="p.phone || '0812-3456-7891'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <template x-if="p.photo">
                                    <img :src="p.photo" class="w-10 h-10 rounded-full shrink-0 shadow-sm border border-white object-cover" />
                                </template>
                                <template x-if="!p.photo">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-xs shrink-0 shadow-sm border border-white" :class="p.avatarClass" x-text="p.initials"></div>
                                </template>
                                <div class="min-w-0">
                                    <p class="font-bold text-on-surface text-[13px]" x-text="p.name"></p>
                                    <p class="text-[10px] text-zinc-400 font-bold tracking-tight" x-text="'NIK: ' + p.nik"></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs font-bold text-zinc-500" x-text="p.jabatan"></p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center">
                                <button type="button" @click.stop="openDetail(p)" 
                                        class="w-8 h-8 rounded-full flex items-center justify-center text-zinc-300 hover:text-primary hover:bg-primary/5 transition-all">
                                    <span class="material-symbols-outlined text-lg">info</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        {{-- No Results --}}
        <div x-show="filteredParticipants.length === 0" class="p-20 text-center">
            <span class="material-symbols-outlined text-4xl text-zinc-200 mb-4">person_search</span>
            <p class="text-sm font-bold text-zinc-400 italic">Data karyawan tidak ditemukan</p>
        </div>

        {{-- Load More (Screenshot Design) --}}
        <div class="py-8 flex justify-center" x-show="visibleCount < filteredParticipants.length">
            <button type="button" @click.stop="visibleCount += 5" 
                    class="flex items-center gap-2 text-[10px] font-black text-zinc-400 uppercase tracking-widest hover:text-zinc-600 transition-all group">
                <span x-text="'TAMPILKAN 5 PESERTA LAGI (' + (filteredParticipants.length - visibleCount) + ' TERSISA)'"></span>
                <span class="material-symbols-outlined text-sm transition-transform group-hover:translate-y-1">expand_more</span>
            </button>
        </div>
    </div>

    {{-- Detail Modal --}}
    <template x-teleport="body">
        <div x-show="isModalOpen" 
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-zinc-950/40 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="closeDetail()"
             x-cloak>
            
            <div class="bg-white w-full max-w-lg max-h-[85vh] rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col relative"
                 @click.away="closeDetail()"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                {{-- Fixed Modal Header --}}
                <div class="h-32 bg-gradient-to-br from-primary to-primary-dark shrink-0 relative">
                    <button @click="closeDetail()" class="absolute top-6 right-6 w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center hover:bg-white/30 transition-all z-10">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                    
                    <div class="absolute -bottom-12 left-10 flex items-end gap-6 z-10">
                        <template x-if="detailParticipant?.photo">
                            <img :src="detailParticipant.photo" class="w-24 h-24 rounded-[2rem] bg-white border-4 border-white shadow-xl object-cover" />
                        </template>
                        <template x-if="!detailParticipant?.photo">
                            <div class="w-24 h-24 rounded-[2rem] bg-white border-4 border-white shadow-xl flex items-center justify-center text-3xl font-black" :class="detailParticipant?.avatarClass" x-text="detailParticipant?.initials"></div>
                        </template>
                    </div>
                </div>

                {{-- Scrollable Modal Body --}}
                <div class="flex-1 overflow-y-auto custom-scrollbar p-10 pt-16">
                    <div class="mb-8">
                        <h3 class="text-2xl font-black text-on-surface tracking-tight" x-text="detailParticipant?.name"></h3>
                        <p class="text-zinc-400 font-bold tracking-widest uppercase text-[10px] mt-1" x-text="'NIK: ' + detailParticipant?.nik"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div>
                                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Jabatan</p>
                                <p class="text-sm font-bold text-on-surface" x-text="detailParticipant?.jabatan"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Departemen</p>
                                <p class="text-sm font-bold text-on-surface" x-text="detailParticipant?.dept"></p>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Email</p>
                                <p class="text-sm font-bold text-on-surface" x-text="detailParticipant?.email || (detailParticipant?.name.toLowerCase().replace(' ', '.') + '@sig.id')"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Telepon</p>
                                <p class="text-sm font-bold text-on-surface" x-text="detailParticipant?.phone || '0812-3456-7891'"></p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10">
                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-4">Riwayat Pelatihan (Selesai)</p>
                        <div class="space-y-3">
                            <template x-for="h in detailParticipant?.history" :key="h.name">
                                <div class="p-4 rounded-2xl bg-zinc-50 border border-zinc-100 flex items-center justify-between group hover:bg-white hover:shadow-md transition-all">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-on-surface truncate" x-text="h.name"></p>
                                        <p class="text-[9px] text-zinc-400 font-bold mt-0.5" x-text="h.date"></p>
                                    </div>
                                    <div class="flex items-center gap-1.5 px-2 py-1 rounded-lg bg-emerald-50 text-emerald-600">
                                        <span class="material-symbols-outlined text-[10px] font-black">check_circle</span>
                                        <span class="text-[9px] font-black uppercase tracking-tight" x-text="h.status"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Fixed Modal Footer --}}
                <div class="p-6 pt-0 shrink-0">
                    <button @click="closeDetail()" class="w-full py-4 bg-zinc-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-zinc-800 transition-all active:scale-95 shadow-lg shadow-zinc-200">
                        Tutup Detail
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
