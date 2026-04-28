{{-- ============================================================
     NAVBAR COMPONENT — Back-Office Layout
     Top navigation bar with role switcher, notifications, and
     profile dropdown. Reusable across all management roles.
     ============================================================ --}}

<header class="fixed top-0 right-0 z-50 bg-white border-b border-zinc-100 flex justify-between items-center px-10 h-20 transition-all duration-300" :class="sidebarOpen ? 'w-[calc(100%-18rem)]' : 'w-[calc(100%-5rem)]'">
    <div class="flex items-center gap-4">
        <div class="hidden lg:flex items-center gap-2 px-4 py-2 bg-zinc-50 border border-zinc-100 rounded-2xl shadow-sm">
            <span class="material-symbols-outlined text-zinc-400 text-sm">corporate_fare</span>
            <div class="flex items-center gap-1.5 text-[10px] font-black tracking-tight overflow-hidden">
                @php $orgPath = Auth::user()->getShortOrganizationPath(); @endphp
                @foreach($orgPath as $index => $org)
                    @php 
                        $displayLabel = is_string($org) ? $org : $org->name; 
                    @endphp
                    <span class="{{ $index === $orgPath->count() - 1 ? 'text-primary' : 'text-zinc-400' }} uppercase truncate max-w-[120px]" 
                          data-tippy-content="{{ $displayLabel }}">
                        {{ $displayLabel }}
                    </span>
                    @if(!$loop->last)
                        <span class="text-zinc-300 shrink-0">/</span>
                    @endif
                @endforeach
                
                @if(Auth::user()->work_location)
                    <span class="text-zinc-300 shrink-0">/</span>
                    <span class="text-zinc-400 uppercase truncate max-w-[100px]" data-tippy-content="{{ Auth::user()->work_location }}">
                        {{ Auth::user()->work_location }}
                    </span>
                @endif
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    tippy('[data-tippy-content]', {
                        theme: 'light',
                        animation: 'scale-extreme',
                        arrow: true,
                    });
                });
            </script>
        </div>
    </div>
    <div class="flex items-center gap-6">
        {{-- Role Switcher --}}
        <button @click="roleModalOpen = true" class="border border-red-500 text-red-500 px-6 py-2 rounded-xl font-bold text-sm hover:bg-red-50 transition-all">
            Ubah Peran
        </button>

        {{-- Notifications --}}
        <div class="relative cursor-pointer">
            <span class="material-symbols-outlined text-zinc-500 text-2xl">notifications</span>
            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
        </div>

        {{-- Profile Dropdown --}}
        <div class="relative" x-data="{ profileOpen: false }">
            <div @click="profileOpen = !profileOpen" class="flex items-center gap-4 border-l border-zinc-100 pl-6 cursor-pointer hover:opacity-80 transition-opacity">
                <div class="text-right">
                    <p class="text-sm font-bold text-zinc-900 leading-tight">{{ Auth::user()->name }}</p>
                    <p class="text-[11px] text-zinc-400 font-medium">{{ ucwords(str_replace('_', ' ', Auth::user()->role)) }}</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-primary/10 flex items-center justify-center text-primary font-black text-sm">
                    {{ Auth::user()->initials() }}
                </div>
            </div>

            {{-- Profile Popup --}}
            <div x-show="profileOpen"
                 @click.outside="profileOpen = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                 style="display: none;"
                 class="absolute right-0 top-full mt-3 w-72 bg-white rounded-2xl shadow-2xl border border-zinc-100 overflow-hidden z-[70]">

                {{-- Profile Header --}}
                <div class="bg-gradient-to-br from-zinc-50 to-white p-6 flex flex-col items-center text-center border-b border-zinc-100">
                    <div class="relative mb-3">
                        <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary font-black text-xl border-2 border-white shadow-md">
                            {{ Auth::user()->initials() }}
                        </div>
                        <span class="absolute bottom-0 right-0 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white"></span>
                    </div>
                    <p class="text-base font-black text-on-surface">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-[0.15em] mt-1">{{ ucwords(str_replace('_', ' ', Auth::user()->role)) }}</p>
                </div>

                {{-- Menu Items --}}
                <div class="p-3 space-y-1">
                    <a href="{{ route('learning-coordinator.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-600 hover:bg-zinc-50 hover:text-on-surface transition-all group">
                        <span class="material-symbols-outlined text-zinc-400 group-hover:text-primary text-xl">person</span>
                        <span class="text-sm font-semibold">Detail Profil Saya</span>
                    </a>
                    <a href="{{ route('learning-coordinator.profile.change-password') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-600 hover:bg-zinc-50 hover:text-on-surface transition-all group">
                        <span class="material-symbols-outlined text-zinc-400 group-hover:text-primary text-xl">lock</span>
                        <span class="text-sm font-semibold">Ganti Kata Sandi</span>
                    </a>
                </div>

                {{-- Logout --}}
                <div class="p-3 border-t border-zinc-100">
                    <button type="button" @click="showLogoutModal = true; profileOpen = false" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 transition-all group">
                        <span class="material-symbols-outlined text-red-400 group-hover:text-red-600 text-xl">logout</span>
                        <span class="text-sm font-bold">Keluar dari Sesi</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>
