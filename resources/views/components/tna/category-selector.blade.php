@props(['categories', 'selected' => null])

<div class="relative" x-data="{ 
    catOpen: false, 
    catSearch: '',
    selectedCat: @js($selected),
    hoveredCat: null,
    categories: @js($categories),
    
    get filteredCategories() {
        if (this.catSearch === '') return this.categories;
        return this.categories.filter(c => c.name.toLowerCase().includes(this.catSearch.toLowerCase()));
    },

    get activeDescription() {
        if (this.hoveredCat) {
            return this.categories.find(c => c.name === this.hoveredCat)?.desc;
        }
        if (this.selectedCat) {
            return this.categories.find(c => c.name === this.selectedCat)?.desc;
        }
        return null;
    },

    selectCategory(cat) {
        this.selectedCat = cat.name;
        this.catOpen = false;
        this.catSearch = '';
        this.$dispatch('category-selected', cat.name);
    }
}" 
x-effect="if(catOpen) { $nextTick(() => { $el.querySelector('.absolute').scrollIntoView({ behavior: 'smooth', block: 'end' }); }); }"
@click.away="catOpen = false">
    <input type="hidden" name="category" :value="selectedCat">
    
    <button type="button" @click="catOpen = !catOpen" 
            class="w-full flex items-center justify-between px-5 py-4 bg-zinc-50 border border-zinc-200 rounded-2xl hover:bg-white hover:border-primary transition-all group shadow-sm">
        <div class="flex items-center gap-3 min-w-0">
            <span class="material-symbols-outlined text-zinc-400 group-hover:text-primary transition-colors">category</span>
            <span class="text-sm font-bold text-on-surface truncate" x-text="selectedCat || 'Pilih Kategori Training'"></span>
        </div>
        <span class="material-symbols-outlined text-zinc-400 group-hover:text-primary transition-transform duration-300" :class="catOpen ? 'rotate-180' : ''">expand_more</span>
    </button>

    <div x-show="catOpen" 
         class="absolute z-[100] w-full mt-2 bg-white rounded-3xl shadow-2xl border border-zinc-100 overflow-hidden flex flex-col"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         style="display: none;">
        
        <div class="p-4 border-b border-zinc-50 bg-zinc-50/50">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-sm">search</span>
                <input type="text" x-model="catSearch" placeholder="Cari kategori..." 
                       class="w-full pl-9 pr-4 py-2 bg-white border-zinc-200 rounded-xl text-xs focus:ring-4 focus:ring-primary/10 transition-all italic">
            </div>
        </div>

        <div class="max-h-60 overflow-y-auto p-2 custom-scrollbar" @mouseleave="hoveredCat = null">
            <template x-for="cat in filteredCategories" :key="cat.id">
                <button type="button" 
                        @click="selectCategory(cat)"
                        @mouseenter="hoveredCat = cat.name"
                        class="w-full text-left p-4 rounded-xl hover:bg-primary/5 group transition-all flex items-center justify-between">
                    <div class="min-w-0 pr-4">
                        <p class="text-sm font-bold text-on-surface group-hover:text-primary transition-colors" x-text="cat.name"></p>
                    </div>
                    <span class="material-symbols-outlined text-primary opacity-0 group-hover:opacity-100 transition-all text-sm"
                          :class="selectedCat === cat.name ? 'opacity-100' : ''">check_circle</span>
                </button>
            </template>
        </div>

        {{-- Integrated Detail Box at the bottom of the list --}}
        <div class="p-5 bg-zinc-50 border-t border-zinc-100 min-h-[80px] flex items-center justify-center text-center">
            <div x-show="activeDescription"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">Detail Kategori</p>
                <p class="text-[11px] text-zinc-500 font-medium italic leading-relaxed" x-text="activeDescription"></p>
            </div>
            <div x-show="!activeDescription" class="text-[10px] text-zinc-300 font-bold italic">
                Sorot kategori untuk melihat detail
            </div>
        </div>
    </div>
</div>
