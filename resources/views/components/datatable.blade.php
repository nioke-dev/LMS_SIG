@props([
    'data',
    'sortable' => []
])

<div class="space-y-4" x-data="{
    perPage: new URLSearchParams(window.location.search).get('per_page') || '5',
    sortCol: new URLSearchParams(window.location.search).get('sort') || 'created_at',
    sortDir: new URLSearchParams(window.location.search).get('dir') || 'desc',

    changePerPage() {
        let url = new URL(window.location.href);
        url.searchParams.set('per_page', this.perPage);
        url.searchParams.set('page', 1); // Reset ke halaman 1 saat ganti per_page
        window.location.href = url.toString();
    },

    sortBy(column) {
        if (!{{ json_encode($sortable) }}.includes(column)) return;
        
        let url = new URL(window.location.href);
        if (this.sortCol === column) {
            this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortCol = column;
            this.sortDir = 'asc';
        }
        url.searchParams.set('sort', this.sortCol);
        url.searchParams.set('dir', this.sortDir);
        window.location.href = url.toString();
    }
}">
    {{-- Top Controls: Per Page Selection & Info (Outside the Card) --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-4 py-2">
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <span class="text-xs font-bold text-zinc-500">Tampilkan</span>
            <select x-model="perPage" @change="changePerPage" class="px-4 py-2 bg-white border border-zinc-200 rounded-xl text-xs font-bold text-zinc-700 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all cursor-pointer shadow-sm">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-xs font-bold text-zinc-500">entri per halaman</span>
        </div>

        <div class="text-xs font-bold text-zinc-500 w-full sm:w-auto text-right">
            @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                Menampilkan {{ $data->firstItem() ?? 0 }} sampai {{ $data->lastItem() ?? 0 }} dari total {{ $data->total() }} entri
            @else
                Menampilkan total {{ count($data) }} entri
            @endif
        </div>
    </div>

    {{-- Main Table Container --}}
    <div class="bg-white rounded-[2.5rem] border border-zinc-100 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-zinc-50/50 border-b border-zinc-100">
                    @if(isset($headers))
                        {{ $headers }}
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50">
                {{ $slot }}
            </tbody>
        </table>

        {{-- Pagination Footer --}}
        @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator && $data->hasPages())
            <div class="p-8 bg-white border-t border-zinc-100 flex items-center justify-between">
                {{ $data->links('components.datatable.pagination') }}
            </div>
        @endif
    </div>
</div>
