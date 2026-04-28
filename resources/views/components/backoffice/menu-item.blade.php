@props([
    'title',
    'icon',
    'href' => '#',
    'active' => false,
])

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => 'flex items-center gap-4 px-4 py-3 transition-all group rounded-xl ' . ($active ? 'bg-red-50 text-primary font-bold' : 'text-zinc-500 hover:text-primary hover:bg-zinc-50')]) }}
   :class="!sidebarOpen ? 'justify-center' : ''"
   title="{{ $title }}">
    
    <span class="material-symbols-outlined transition-colors {{ $active ? 'text-primary' : 'text-zinc-400 group-hover:text-primary' }}"
          style="{{ $active ? "font-variation-settings: 'FILL' 1;" : '' }}">
        {{ $icon }}
    </span>
    
    <span x-show="sidebarOpen" 
          class="text-[15px] whitespace-nowrap overflow-hidden transition-all duration-300">
        {{ $title }}
    </span>

    @if($active)
        <div x-show="sidebarOpen" class="ml-auto w-1.5 h-1.5 rounded-full bg-primary shadow-sm shadow-primary/50"></div>
    @endif
</a>
