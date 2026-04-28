{{-- Learning Coordinator Sidebar Menu --}}
<div x-show="sidebarOpen" class="mt-8 mb-2 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-[2px]" x-transition>
    Overview
</div>

<x-backoffice.menu-item 
    title="Dashboard" 
    icon="dashboard" 
    :href="route('learning-coordinator.dashboard')" 
    :active="request()->routeIs('learning-coordinator.dashboard')" 
/>

<div x-show="sidebarOpen" class="mt-8 mb-2 px-4 text-[10px] font-black text-zinc-400 uppercase tracking-[2px]" x-transition>
    TNA Management
</div>

<x-backoffice.menu-item 
    title="Daftar Usulan" 
    icon="Format_List_Bulleted" 
    :href="route('learning-coordinator.daftar-usulan')" 
    :active="request()->routeIs('learning-coordinator.daftar-usulan') || request()->routeIs('learning-coordinator.tna.edit')" 
/>

<x-backoffice.menu-item 
    title="Buat Usulan Baru" 
    icon="add_box" 
    :href="route('learning-coordinator.buat-usulan')" 
    :active="request()->routeIs('learning-coordinator.buat-usulan')" 
/>

{{-- Removed Lainnya section as requested --}}
