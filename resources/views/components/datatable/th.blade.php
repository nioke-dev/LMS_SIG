@props([
    'column' => null,
    'sortable' => []
])

@php
    $isSortable = in_array($column, $sortable);
@endphp

<th @if($isSortable) @click="sortBy('{{ $column }}')" @endif class="px-8 py-5 text-[10px] font-black text-zinc-400 uppercase tracking-widest @if($isSortable) cursor-pointer select-none hover:text-primary transition-colors group/th @endif {{ $attributes->get('class') }}">
    <div class="flex items-center gap-2 {{ $attributes->get('align') === 'right' ? 'justify-end' : '' }}">
        <span>{{ $slot }}</span>
        @if($isSortable)
            <span class="material-symbols-outlined text-sm text-zinc-300 group-hover/th:text-primary transition-colors" :class="{
                'text-primary font-bold': sortCol === '{{ $column }}',
                'rotate-180': sortCol === '{{ $column }}' && sortDir === 'desc'
            }">arrow_upward</span>
        @endif
    </div>
</th>
