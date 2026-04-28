{{-- ============================================================
     SIDEBAR COMPONENT — Back-Office Layout
     Reusable across all management roles.
     
     Props:
     - $sidebarNav (slot) : Menu navigasi spesifik per role
     ============================================================ --}}

<aside class="h-screen fixed left-0 top-0 bg-white border-r border-zinc-100 flex flex-col z-[60] transition-all duration-500 ease-in-out overflow-hidden shadow-2xl shadow-zinc-200/50" 
       :class="sidebarOpen ? 'w-72 p-6' : 'w-20 p-3'">
    {{-- Logo Section --}}
    <div class="mb-10 flex items-center justify-center h-12 mt-2">
        <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
            <img alt="SIG Logo" class="h-9 w-auto" src="https://i.ibb.co.com/zTjcL4DX/logo-sig-latar-putih.png" />
        </div>
        <div x-show="!sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
            <img alt="SIG Logo" class="h-7 w-auto mx-auto" src="https://i.ibb.co.com/zTjcL4DX/logo-sig-latar-putih.png" />
        </div>
    </div>

    {{-- Role-Specific Navigation (injected via slot) --}}
    <nav class="flex-1 space-y-1">
        {{ $slot }}
    </nav>

    {{-- Bottom Section: Help Center & Minimize --}}
    <div class="pt-6 border-t border-zinc-100 space-y-1">
        <a class="flex items-center gap-4 px-4 py-3 text-zinc-500 hover:text-primary transition-all group rounded-xl" :class="!sidebarOpen ? 'justify-center' : ''" href="#" title="Help Center">
            <span class="material-symbols-outlined text-zinc-400 group-hover:text-primary">help</span>
            <span x-show="sidebarOpen" class="font-semibold text-[15px]">Help Center</span>
        </a>
        <button @click="sidebarOpen = !sidebarOpen" class="w-full flex items-center justify-center py-3 text-zinc-400 hover:text-primary hover:bg-zinc-50 transition-all group/toggle relative rounded-xl" title="">
            <span class="material-symbols-outlined transition-transform duration-300" x-text="sidebarOpen ? 'keyboard_double_arrow_left' : 'keyboard_double_arrow_right'">keyboard_double_arrow_left</span>
            <span class="absolute -top-8 left-0 px-2 py-1 bg-zinc-800 text-white text-[10px] font-bold rounded-md whitespace-nowrap opacity-0 group-hover/toggle:opacity-100 pointer-events-none transition-opacity z-[100]" x-text="sidebarOpen ? 'Minimize this sidebar' : 'Maximize this sidebar'"></span>
        </button>
    </div>
</aside>
