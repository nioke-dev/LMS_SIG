{{-- 
    ============================================================
    DATE RANGE FILTER COMPONENT (Litepicker Edition)
    ============================================================
--}}
@props([
    'showTime' => false,
    'showTimezone' => false,
    'placeholder' => 'Pilih Tanggal'
])

<div x-data="dateRangeFilter()" 
     @reset-date-filter.window="resetAll()"
     x-init="init()" class="relative w-full md:w-auto" @click.away="open = false">
    {{-- Main Filter Button --}}
    <button type="button" @click="toggle()"
        class="w-full md:w-fit min-w-[220px] px-5 py-3 bg-white border border-zinc-100 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] text-zinc-600 flex items-center justify-between gap-x-4 shadow-sm hover:border-primary/30 transition-all">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-lg text-zinc-400">calendar_today</span>
            <span x-text="dateLabel || '{{ $placeholder }}'" class="truncate"></span>
        </div>
        <span class="material-symbols-outlined text-xl transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
    </button>

    {{-- Dropdown Panel --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         class="absolute z-[100] mt-2 bg-white border border-zinc-100 rounded-[2.5rem] shadow-2xl shadow-zinc-200/50 overflow-hidden flex flex-col md:flex-row w-screen max-w-[95vw] md:w-[880px] lg:w-[920px] right-0 md:left-auto"
         style="display: none;">
        
        {{-- Left Sidebar: Presets --}}
        <div class="w-full md:w-44 bg-zinc-50/50 border-r border-zinc-50 p-6 flex flex-col gap-2 shrink-0">
            <template x-for="p in presets" :key="p.id">
                <button @click="applyPreset(p)" 
                        type="button"
                        class="w-full text-left px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                        :class="activePreset === p.id ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-zinc-500 hover:bg-zinc-100 hover:text-primary'">
                    <span x-text="p.label"></span>
                </button>
            </template>
        </div>

        {{-- Right Content: Calendar & Controls --}}
        <div class="flex-1 p-8">
            {{-- Top Controls --}}
            <div class="flex items-center justify-between mb-8 pb-6 border-b border-zinc-50 gap-8">
                <div class="flex items-center gap-8 shrink-0">
                    <div class="flex items-center gap-3 {{ $showTime ? '' : 'opacity-30' }}">
                        <span class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Include Time</span>
                        <div class="w-8 h-4 bg-zinc-200 rounded-full relative">
                            <div class="absolute left-0.5 top-0.5 w-3 h-3 bg-white rounded-full"></div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 {{ $showTimezone ? '' : 'opacity-30' }}">
                        <span class="material-symbols-outlined text-lg text-zinc-400">language</span>
                        <div class="px-2 py-1 bg-zinc-100 rounded-lg text-[8px] font-black text-zinc-500 uppercase">EDT</div>
                    </div>
                </div>

                <div class="flex items-center gap-2 px-3 py-1.5 bg-zinc-50 border border-zinc-100 rounded-xl shrink-0">
                    <span class="material-symbols-outlined text-sm text-zinc-400">event</span>
                    <span class="text-[10px] font-black text-zinc-600" x-text="formatRangeDisplay()"></span>
                </div>
            </div>

            {{-- Litepicker Container --}}
            <div class="litepicker-container" x-ref="container"></div>

            {{-- Footer Actions --}}
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-zinc-50 gap-4">
                <div class="text-[10px] font-black text-zinc-400 uppercase tracking-widest truncate">
                    Range: <span class="text-primary" x-text="diffDays">0</span> days selected
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <button type="button" @click="reset()" class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-zinc-400 hover:text-zinc-600 transition-colors">Cancel</button>
                    <button type="button" @click="apply()" class="px-6 py-3 bg-primary text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 hover:shadow-primary/40 active:scale-95 transition-all">Apply Range</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --litepicker-button-prev-month-color: #a1a1aa;
            --litepicker-button-next-month-color: #a1a1aa;
            --litepicker-button-prev-month-color-hover: #e21d24;
            --litepicker-button-next-month-color-hover: #e21d24;
            --litepicker-day-color-hover: #e21d24;
            --litepicker-is-start-color-bg: #e21d24;
            --litepicker-is-end-color-bg: #e21d24;
            --litepicker-is-in-range-color-bg: rgba(226, 29, 36, 0.05);
            --litepicker-is-in-range-color: #e21d24;
            --litepicker-month-header-color: #18181b;
            --litepicker-container-width: 600px;
        }

        .litepicker {
            font-family: inherit !important;
        }
        .litepicker .container__main {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
        }
        .litepicker .container__months {
            box-shadow: none !important;
            background: transparent !important;
            border: none !important;
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            justify-content: center !important;
            align-items: flex-start !important;
            gap: 50px !important;
            width: 100% !important;
        }
        .litepicker .month-item {
            flex-shrink: 0 !important;
            width: 280px !important;
        }
        .litepicker .month-item-header {
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            font-size: 13px !important;
        }
        .litepicker .month-item-weekdays-row > div {
            font-weight: 900 !important;
            font-size: 10px !important;
            color: #a1a1aa !important;
            text-transform: uppercase !important;
        }
        .litepicker .day-item {
            border-radius: 12px !important;
            font-weight: 700 !important;
            font-size: 12px !important;
            color: #3f3f46 !important;
        }
        .litepicker .day-item.is-start-date, 
        .litepicker .day-item.is-end-date {
            box-shadow: 0 10px 15px -3px rgba(226, 29, 36, 0.2) !important;
            font-weight: 900 !important;
        }
    </style>
</div>

<script>
    function dateRangeFilter() {
        return {
            open: false,
            dateLabel: '',
            startDate: null,
            endDate: null,
            diffDays: 0,
            activePreset: null,
            picker: null,
            presets: [
                { id: 'today', label: 'Today', days: 0 },
                { id: 'yesterday', label: 'Yesterday', days: -1 },
                { id: 'this-week', label: 'This Week', days: 7 },
                { id: 'this-month', label: 'This Month', days: 30 },
                { id: 'this-year', label: 'This Year', days: 365 },
            ],

            resetAll() {
                this.startDate = null;
                this.endDate = null;
                this.dateLabel = '';
                this.diffDays = 0;
                this.activePreset = null;
                if (this.picker) {
                    this.picker.clearSelection();
                }
            },

            init() {},

            toggle() {
                this.open = !this.open;
                if (this.open && !this.picker) {
                    this.$nextTick(() => {
                        this.picker = new Litepicker({
                            element: this.$refs.container,
                            elementEnd: null,
                            parentEl: this.$refs.container,
                            firstDay: 0,
                            format: 'YYYY-MM-DD',
                            numberOfMonths: 2,
                            numberOfColumns: 2,
                            singleMode: false,
                            inlineMode: true,
                            setup: (picker) => {
                                picker.on('selected', (date1, date2) => {
                                    this.startDate = date1.format('YYYY-MM-DD');
                                    this.endDate = date2.format('YYYY-MM-DD');
                                    this.calculateDiff(date1, date2);
                                    this.activePreset = null;
                                });
                            }
                        });
                    });
                }
            },

            applyPreset(preset) {
                this.activePreset = preset.id;
                const end = new Date();
                const start = new Date();
                
                if (preset.id === 'yesterday') {
                    start.setDate(start.getDate() - 1);
                    end.setDate(end.getDate() - 1);
                } else if (preset.id === 'this-week') {
                    start.setDate(start.getDate() - start.getDay());
                } else if (preset.id === 'this-month') {
                    start.setDate(1);
                } else if (preset.id === 'this-year') {
                    start.setMonth(0, 1);
                }

                this.picker.setDateRange(start, end);
                this.startDate = this.picker.getStartDate().format('YYYY-MM-DD');
                this.endDate = this.picker.getEndDate().format('YYYY-MM-DD');
                this.calculateDiff(this.picker.getStartDate(), this.picker.getEndDate());
            },

            calculateDiff(d1, d2) {
                const diffTime = Math.abs(d2.toJSDate() - d1.toJSDate());
                this.diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            },

            formatRangeDisplay() {
                if (!this.startDate) return 'None Selected';
                return `${this.startDate} - ${this.endDate}`;
            },

            apply() {
                if (this.startDate && this.endDate) {
                    this.dateLabel = this.formatRangeDisplay();
                    this.$dispatch('date-range-updated', {
                        start: this.startDate,
                        end: this.endDate
                    });
                    this.open = false;
                } else {
                    Alert.warning('Pilih Rentang Tanggal', 'Mohon pilih tanggal mulai dan tanggal selesai.');
                }
            },

            reset() {
                this.startDate = null;
                this.endDate = null;
                this.dateLabel = '';
                this.diffDays = 0;
                this.activePreset = null;
                this.picker.clearSelection();
                this.$dispatch('date-range-updated', { start: null, end: null });
                this.open = false;
            }
        }
    }
</script>
