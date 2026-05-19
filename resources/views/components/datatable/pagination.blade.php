@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between w-full">
        {{-- Mobile View (Previous / Next simple buttons) --}}
        <div class="flex justify-between flex-1 sm:hidden gap-2">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-zinc-400 bg-zinc-100 rounded-2xl cursor-not-allowed select-none">
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-zinc-700 bg-zinc-50 hover:bg-zinc-100 rounded-2xl transition-all shadow-sm active:scale-95">
                    Sebelumnya
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-zinc-700 bg-zinc-50 hover:bg-zinc-100 rounded-2xl transition-all shadow-sm active:scale-95">
                    Berikutnya
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-zinc-400 bg-zinc-100 rounded-2xl cursor-not-allowed select-none">
                    Berikutnya
                </span>
            @endif
        </div>

        {{-- Desktop View --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold text-zinc-500">
                    Menampilkan
                    <span class="font-black text-zinc-700">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="font-black text-zinc-700">{{ $paginator->lastItem() }}</span>
                    dari total
                    <span class="font-black text-zinc-700">{{ $paginator->total() }}</span>
                    entri
                </p>
            </div>

            <div>
                <ul class="flex items-center gap-1.5">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li>
                            <span class="w-10 h-10 flex items-center justify-center rounded-2xl bg-zinc-100 text-zinc-300 cursor-not-allowed select-none font-bold text-sm">
                                <span class="material-symbols-outlined text-base">chevron_left</span>
                            </span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-zinc-50 hover:bg-primary/10 text-zinc-600 hover:text-primary transition-all font-bold text-sm shadow-sm active:scale-95">
                                <span class="material-symbols-outlined text-base">chevron_left</span>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li>
                                <span class="w-10 h-10 flex items-center justify-center rounded-2xl bg-transparent text-zinc-400 select-none font-bold text-xs">
                                    {{ $element }}
                                </span>
                            </li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li>
                                        <span class="w-10 h-10 flex items-center justify-center rounded-2xl bg-primary text-white font-black text-xs shadow-lg shadow-primary/30 select-none">
                                            {{ $page }}
                                        </span>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-zinc-50 hover:bg-primary/10 text-zinc-600 hover:text-primary transition-all font-bold text-xs shadow-sm active:scale-95">
                                            {{ $page }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li>
                            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-zinc-50 hover:bg-primary/10 text-zinc-600 hover:text-primary transition-all font-bold text-sm shadow-sm active:scale-95">
                                <span class="material-symbols-outlined text-base">chevron_right</span>
                            </a>
                        </li>
                    @else
                        <li>
                            <span class="w-10 h-10 flex items-center justify-center rounded-2xl bg-zinc-100 text-zinc-300 cursor-not-allowed select-none font-bold text-sm">
                                <span class="material-symbols-outlined text-base">chevron_right</span>
                            </span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif
