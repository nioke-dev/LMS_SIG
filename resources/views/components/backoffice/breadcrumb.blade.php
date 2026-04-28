{{-- ============================================================
     BREADCRUMB COMPONENT — Organization Hierarchy Path
     Displays: Company > Department > Unit > Location
     Reusable across all management dashboards.

     Props:
     - $title (string) : Page heading below breadcrumb
     ============================================================ --}}

@props(['title' => ''])

<div class="flex flex-col space-y-2">
    <nav class="flex items-center gap-2 text-[10px] sm:text-xs font-bold tracking-wider uppercase">
        @if(Auth::user()->company)
            <span class="text-zinc-400">{{ Auth::user()->company }}</span>
        @endif
        @if(Auth::user()->department)
            <span class="material-symbols-outlined text-zinc-300 text-sm">chevron_right</span>
            <span class="text-zinc-400">{{ Auth::user()->department }}</span>
        @endif
        @if(Auth::user()->unit)
            <span class="material-symbols-outlined text-zinc-300 text-sm">chevron_right</span>
            <span class="text-primary font-bold">{{ Auth::user()->unit }}</span>
        @endif
        @if(Auth::user()->work_location)
            <span class="material-symbols-outlined text-zinc-300 text-sm">chevron_right</span>
            <span class="text-zinc-400">{{ Auth::user()->work_location }}</span>
        @endif
    </nav>
    @if($title)
        <h2 class="text-3xl font-black text-on-surface uppercase tracking-tight">{{ $title }}</h2>
    @endif
</div>
