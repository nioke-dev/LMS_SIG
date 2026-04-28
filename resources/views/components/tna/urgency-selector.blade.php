@props(['selected' => 'Medium'])

<div class="relative" x-data="{ 
    open: false, 
    selected: @js($selected),
    options: [
        { value: 'Low', label: 'Low - Rutin', color: 'bg-emerald-500', bg: 'bg-emerald-50', text: 'text-emerald-600' },
        { value: 'Medium', label: 'Medium - Dibutuhkan Segera', color: 'bg-amber-500', bg: 'bg-amber-50', text: 'text-amber-600' },
        { value: 'High', label: 'High - Kritikal/Mendesak', color: 'bg-rose-500', bg: 'bg-rose-50', text: 'text-rose-600' }
    ],

    get selectedLabel() {
        return this.options.find(o => o.value === this.selected)?.label || 'Pilih Urgensi';
    },

    get selectedColor() {
        return this.options.find(o => o.value === this.selected)?.color || 'bg-zinc-400';
    },

    selectOption(opt) {
        this.selected = opt.value;
        this.open = false;
        this.$dispatch('urgency-selected', opt.value);
    }
}" @click.away="open = false">
    <input type="hidden" name="urgency" :value="selected">
    
    <button type="button" @click="open = !open" 
            class="w-full flex items-center justify-between px-6 py-4 bg-zinc-50 border border-zinc-200 rounded-2xl hover:bg-white hover:border-primary transition-all group shadow-sm">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-2 h-2 rounded-full shadow-sm" :class="selectedColor"></div>
            <span class="text-sm font-bold text-on-surface truncate" x-text="selectedLabel"></span>
        </div>
        <span class="material-symbols-outlined text-zinc-400 group-hover:text-primary transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
    </button>

    <div x-show="open" 
         class="absolute z-[100] w-full mt-2 bg-white rounded-3xl shadow-2xl border border-zinc-100 overflow-hidden p-2"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         style="display: none;">
        
        <template x-for="opt in options" :key="opt.value">
            <button type="button" @click="selectOption(opt)"
                    class="w-full text-left p-4 rounded-xl hover:bg-zinc-50 group transition-all flex items-center gap-3">
                <div class="w-2 h-2 rounded-full" :class="opt.color"></div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-on-surface group-hover:text-primary transition-colors" x-text="opt.label"></p>
                </div>
                <span class="material-symbols-outlined text-primary opacity-0 group-hover:opacity-100 transition-all text-sm"
                      :class="selected === opt.value ? 'opacity-100' : ''">check_circle</span>
            </button>
        </template>
    </div>
</div>
