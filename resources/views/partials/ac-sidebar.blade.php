{{-- Admin Coordinator Sidebar Menu --}}
<div x-show="sidebarOpen" class="mt-8 mb-2 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-[2px]" x-transition>
    Utama
</div>

<x-backoffice.menu-item 
    title="Dashboard" 
    icon="dashboard" 
    :href="route('admin-coordinator.dashboard')" 
    :active="request()->routeIs('admin-coordinator.dashboard')" 
/>

<x-backoffice.menu-item 
    title="Merging Console" 
    icon="merge" 
    :href="route('admin-coordinator.merging-hub')" 
    :active="request()->routeIs('admin-coordinator.merging-hub')" 
/>

<x-backoffice.menu-item 
    title="Manajemen Blueprint" 
    icon="architecture" 
    :href="route('admin-coordinator.blueprint-directory')" 
    :active="request()->routeIs('admin-coordinator.blueprint-directory') || request()->routeIs('admin-coordinator.blueprint.initiate')" 
/>

<div x-show="sidebarOpen" class="mt-8 mb-2 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-[2px]" x-transition>
    Persetujuan
</div>

<x-backoffice.menu-item 
    title="Kategori Pelatihan" 
    icon="fact_check" 
    :href="route('admin-coordinator.category-approval')" 
    :active="request()->routeIs('admin-coordinator.category-approval')" 
/>

<x-backoffice.menu-item 
    title="Direktori SME" 
    icon="assignment_ind" 
    :href="route('admin-coordinator.sme-directory')" 
    :active="request()->routeIs('admin-coordinator.sme-directory')" 
/>
