@extends('layouts.backoffice')

@section('sidebar-nav')
    @include('partials.sme-sidebar')
@endsection

@section('title', 'Review Blueprint')

@section('page-title', 'Review Blueprint')

@section('content')
<div class="space-y-8" x-data="{
    searchQuery: new URLSearchParams(window.location.search).get('search') || '',
    selectedCategory: new URLSearchParams(window.location.search).get('category') || 'all',
    startDate: new URLSearchParams(window.location.search).get('start_date') || '',
    endDate: new URLSearchParams(window.location.search).get('end_date') || '',
    sortOrder: (new URLSearchParams(window.location.search).get('sort') === 'deadline' && new URLSearchParams(window.location.search).get('dir') === 'desc') ? 'desc' : 'asc',
    showFilters: new URLSearchParams(window.location.search).has('category') || new URLSearchParams(window.location.search).has('start_date') || new URLSearchParams(window.location.search).has('end_date'),

    applyFilters() {
        let url = new URL(window.location.href);
        if (this.searchQuery) url.searchParams.set('search', this.searchQuery);
        else url.searchParams.delete('search');

        if (this.selectedCategory && this.selectedCategory !== 'all') url.searchParams.set('category', this.selectedCategory);
        else url.searchParams.delete('category');

        if (this.startDate) url.searchParams.set('start_date', this.startDate);
        else url.searchParams.delete('start_date');

        if (this.endDate) url.searchParams.set('end_date', this.endDate);
        else url.searchParams.delete('end_date');

        if (this.sortOrder === 'desc') {
            url.searchParams.set('sort', 'deadline');
            url.searchParams.set('dir', 'desc');
        } else {
            url.searchParams.set('sort', 'deadline');
            url.searchParams.set('dir', 'asc');
        }

        url.searchParams.set('page', 1); // Reset ke halaman 1 saat filter berubah
        window.location.href = url.toString();
    }
}">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-primary/10 text-primary rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0">
                <span class="material-symbols-outlined text-3xl">architecture</span>
            </div>
            <div>
                <h1 class="text-2xl font-black text-on-surface tracking-tight uppercase">Review Blueprint</h1>
                <p class="text-xs font-bold text-zinc-500 mt-1">Tinjau spesifikasi kurikulum dan unggah materi pelatihan Anda.</p>
            </div>
        </div>
    </div>

    {{-- Filter & Search Bar Row --}}
    <div class="bg-white p-6 rounded-[2rem] border border-zinc-100 shadow-sm space-y-6">
        {{-- Top Bar: Search Input, Filter Toggle Button, Export Button --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            {{-- Search Input --}}
            <div class="relative w-full sm:w-96 flex-1">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 text-lg">search</span>
                <input type="text" x-model="searchQuery" @keydown.enter="applyFilters" @input.debounce.750ms="applyFilters" placeholder="Cari judul blueprint atau kode... (Tekan Enter)" class="w-full pl-11 pr-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-xs font-medium text-zinc-800 placeholder-zinc-400 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
            </div>

            {{-- Action Buttons: Filter Toggle & Export Daftar --}}
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end flex-shrink-0">
                {{-- Filter Toggle Button --}}
                <button type="button" @click="showFilters = !showFilters" :class="showFilters ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200'" class="px-6 py-3 text-xs font-black uppercase tracking-widest rounded-xl transition-all flex items-center gap-2 shadow-sm active:scale-95 cursor-pointer">
                    <span class="material-symbols-outlined text-sm">filter_list</span>
                    <span x-text="showFilters ? 'Tutup Filter' : 'Filter'"></span>
                </button>

                {{-- Export Daftar Button --}}
                <a :href="'{{ route('sme.blueprint.export') }}?search=' + encodeURIComponent(searchQuery) + '&category=' + encodeURIComponent(selectedCategory)" class="px-6 py-3 bg-zinc-100 text-zinc-600 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-zinc-200 transition-all flex items-center gap-2 shadow-sm cursor-pointer active:scale-95">
                    <span class="material-symbols-outlined text-sm">download</span>
                    Export Daftar
                </a>
            </div>
        </div>

        {{-- Collapsible Filter Panel --}}
        <div x-show="showFilters" x-transition x-cloak class="pt-6 border-t border-zinc-100 grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Category Filter Dropdown --}}
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-500 mb-2">Filter Kategori</label>
                <select x-model="selectedCategory" @change="applyFilters" class="w-full px-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-xs font-bold text-zinc-700 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all cursor-pointer">
                    <option value="all">Semua Kategori</option>
                    @foreach($categories ?? [] as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Actual Date Range Filter (startDate & endDate) --}}
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-500 mb-2">Rentang Tanggal Tenggat Waktu</label>
                <div class="flex items-center gap-2">
                    <input type="date" x-model="startDate" @change="applyFilters" class="w-full px-3 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-xs font-medium text-zinc-700 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all cursor-pointer">
                    <span class="text-zinc-400 text-xs font-bold">-</span>
                    <input type="date" x-model="endDate" @change="applyFilters" class="w-full px-3 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-xs font-medium text-zinc-700 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all cursor-pointer">
                </div>
            </div>

            {{-- Sort Order Dropdown --}}
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-zinc-500 mb-2">Urutan Tenggat Waktu</label>
                <select x-model="sortOrder" @change="applyFilters" class="w-full px-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-xs font-bold text-zinc-700 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all cursor-pointer">
                    <option value="asc">Deadline Terdekat</option>
                    <option value="desc">Deadline Terjauh</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Blueprints Table Card using Reusable Datatable Component --}}
    <x-datatable :data="$blueprints" :sortable="['title', 'category', 'status', 'deadline', 'created_at']">
        <x-slot name="headers">
            <x-datatable.th column="title" :sortable="['title', 'category', 'status', 'deadline', 'created_at']">Informasi Blueprint</x-datatable.th>
            <x-datatable.th column="category" :sortable="['title', 'category', 'status', 'deadline', 'created_at']">Kategori & Status</x-datatable.th>
            <x-datatable.th column="created_at" :sortable="['title', 'category', 'status', 'deadline', 'created_at']">Tanggal Ditugaskan</x-datatable.th>
            <x-datatable.th column="deadline" :sortable="['title', 'category', 'status', 'deadline', 'created_at']">Tenggat Waktu</x-datatable.th>
            <x-datatable.th align="right">Aksi</x-datatable.th>
        </x-slot>

        @forelse($blueprints as $bp)
            <tr class="hover:bg-zinc-50/30 transition-colors group cursor-default">
                <td class="px-8 py-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 group-hover:bg-primary/10 group-hover:text-primary transition-colors flex-shrink-0 shadow-sm border border-amber-100 group-hover:border-primary/20">
                            <span class="material-symbols-outlined text-2xl">architecture</span>
                        </div>
                        <div>
                            <p class="font-black text-on-surface group-hover:text-primary transition-colors leading-tight text-sm uppercase">{{ $bp->title }}</p>
                            <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest mt-1">Kode: <span class="text-zinc-600">{{ $bp->id }}</span> • Ditugaskan oleh Admin Coordinator</p>
                        </div>
                    </div>
                </td>
                <td class="px-8 py-6">
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-black uppercase tracking-wider text-blue-600">{{ $bp->category }}</span>
                        <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-0.5 bg-amber-50 text-amber-600 border border-amber-200 rounded-full w-max">{{ strtoupper(str_replace('_', ' ', $bp->status)) }}</span>
                    </div>
                </td>
                <td class="px-8 py-6">
                    <div class="flex items-center gap-2 text-xs font-bold text-zinc-600">
                        <span class="material-symbols-outlined text-base text-zinc-400">event_available</span>
                        <span>{{ $bp->created_at ? \Carbon\Carbon::parse($bp->created_at)->translatedFormat('d F Y') : '19 Mei 2026' }}</span>
                    </div>
                </td>
                <td class="px-8 py-6">
                    <div class="flex items-center gap-2 text-xs font-bold text-zinc-600">
                        <span class="material-symbols-outlined text-base text-zinc-400">calendar_month</span>
                        <span>{{ $bp->deadline ? \Carbon\Carbon::parse($bp->deadline)->translatedFormat('d F Y') : '19 Mei 2026' }}</span>
                    </div>
                </td>
                <td class="px-8 py-6 text-right">
                    <a href="{{ route('sme.blueprint.show', $bp->id) }}" class="inline-block px-6 py-3 bg-primary text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-primary-container shadow-lg shadow-primary/20 hover:shadow-primary/30 transition-all active:scale-95 flex items-center gap-1.5 w-max ml-auto">
                        <span class="material-symbols-outlined text-sm">rate_review</span>
                        Mulai Review
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-8 py-12 text-center text-zinc-400 text-xs italic bg-zinc-50/50">
                    Belum ada blueprint pelatihan yang sesuai dengan filter Anda.
                </td>
            </tr>
        @endforelse
    </x-datatable>
</div>
@endsection
