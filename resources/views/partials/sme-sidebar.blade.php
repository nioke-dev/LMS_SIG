{{-- Subject Matter Expert Sidebar Menu --}}
<div x-show="sidebarOpen" class="mt-8 mb-2 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-[2px]" x-transition>
    Overview
</div>

<x-backoffice.menu-item 
    title="Dashboard" 
    icon="dashboard" 
    :href="route('sme.dashboard')" 
    :active="request()->routeIs('sme.dashboard')" 
/>

<div x-show="sidebarOpen" class="mt-8 mb-2 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-[2px]" x-transition>
    Penugasan & Validasi
</div>

<x-backoffice.menu-item 
    title="Review Blueprint" 
    icon="architecture" 
    :href="route('sme.blueprint.index')" 
    :active="request()->routeIs('sme.blueprint.index', 'sme.blueprint.show')" 
/>

<x-backoffice.menu-item 
    title="Revisi Materi" 
    icon="rule_folder" 
    :href="route('sme.revision.index')" 
    :active="request()->routeIs('sme.revision.index')" 
/>

<div x-show="sidebarOpen" class="mt-8 mb-2 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-[2px]" x-transition>
    Produksi & Kurikulum
</div>

<x-backoffice.menu-item 
    title="Masterclass Builder" 
    icon="dashboard_customize" 
    :href="route('sme.masterclass.index')" 
    :active="request()->routeIs('sme.masterclass.index', 'sme.masterclass.curriculum')" 
/>

<div x-show="sidebarOpen" class="mt-8 mb-2 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-[2px]" x-transition>
    Arsip & Riwayat
</div>

<x-backoffice.menu-item 
    title="Modul Divalidasi" 
    icon="verified" 
    :href="route('sme.validated.index')" 
    :active="request()->routeIs('sme.validated.index')" 
/>
