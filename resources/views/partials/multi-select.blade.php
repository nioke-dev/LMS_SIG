<div class="relative w-full" x-data="{ 
    isOpen: false,
    searchTerm: '',
    get filteredOptions() {
        if (!this.searchTerm) return items;
        return items.filter(i => i.name.toLowerCase().includes(this.searchTerm.toLowerCase()));
    }
}">
    {{-- Trigger --}}
    <button @click="isOpen = !isOpen" type="button" 
        class="w-full px-4 py-3 bg-white border border-zinc-100 rounded-xl text-xs font-bold text-zinc-700 flex items-center justify-between shadow-sm hover:border-red-600/30 transition-all">
        <span class="truncate pr-4" :class="(isSingle ? selected : selected.length) ? 'text-zinc-900' : 'text-zinc-400'" x-text="getLabel()"></span>
        <span class="material-symbols-outlined text-lg transition-transform duration-200" :class="isOpen ? 'rotate-180' : ''">expand_more</span>
    </button>

    {{-- Dropdown --}}
    <div x-show="isOpen" 
         @click.away="isOpen = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="absolute z-[120] w-full mt-2 bg-white border border-zinc-100 rounded-2xl shadow-2xl overflow-hidden py-2"
         style="display: none;">
        
        {{-- Search Input inside Dropdown --}}
        <div class="px-3 pb-2 mb-2 border-b border-zinc-50">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-sm">search</span>
                <input type="text" x-model="searchTerm" placeholder="Cari..." 
                    class="w-full pl-9 pr-4 py-2 bg-zinc-50 border-none rounded-xl text-xs font-medium focus:ring-2 focus:ring-red-600/10 outline-none">
            </div>
        </div>

        {{-- Options --}}
        <div class="max-h-60 overflow-y-auto custom-scrollbar px-1">
            <template x-for="item in filteredOptions" :key="item.id">
                <div @click="toggleItem(item.id); if(isSingle) isOpen = false;" 
                     class="flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer transition-all"
                     :class="isSelected(item.id) ? 'bg-red-50' : 'hover:bg-zinc-50'">
                    
                    {{-- Radio/Checkbox Icon --}}
                    <div class="w-4 h-4 rounded border flex items-center justify-center transition-all"
                         :class="[
                            isSelected(item.id) ? 'bg-red-600 border-red-600' : 'border-zinc-200 bg-white',
                            isSingle ? 'rounded-full' : 'rounded'
                         ]">
                        <span x-show="isSelected(item.id)" class="material-symbols-outlined text-[10px] text-white font-black">check</span>
                    </div>

                    <span class="text-xs font-bold transition-colors" 
                          :class="isSelected(item.id) ? 'text-red-600' : 'text-zinc-600'" 
                          x-text="item.name"></span>
                </div>
            </template>
            
            <template x-if="filteredOptions.length === 0">
                <div class="px-4 py-8 text-center">
                    <span class="material-symbols-outlined text-zinc-300 text-3xl mb-2">search_off</span>
                    <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Tidak ditemukan</p>
                </div>
            </template>
        </div>
    </div>
</div>
