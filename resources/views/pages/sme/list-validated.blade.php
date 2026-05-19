@extends('layouts.backoffice')

@section('sidebar-nav')
    @include('partials.sme-sidebar')
@endsection

@section('title', 'Daftar Modul Divalidasi & Arsip')

@section('page-title', 'Modul Divalidasi')

@section('content')
<div class="space-y-8" x-data="{
    searchQuery: '',
    selectedCategory: 'all',
    
    filterBlueprint(title, category) {
        let matchSearch = title.toLowerCase().includes(this.searchQuery.toLowerCase());
        let matchCat = this.selectedCategory === 'all' || category === this.selectedCategory;
        return matchSearch && matchCat;
    }
}">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0 border border-blue-100">
                <span class="material-symbols-outlined text-3xl">verified</span>
            </div>
            <div>
                <h1 class="text-2xl font-black text-on-surface tracking-tight uppercase">Daftar Modul Divalidasi & Arsip</h1>
                <p class="text-xs font-bold text-zinc-500 mt-1">Daftar silabus dan modul pembelajaran yang telah berhasil divalidasi, disetujui, dan dirilis ke dalam katalog SIG Academy.</p>
            </div>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('sme.dashboard') }}" class="px-6 py-3 bg-zinc-100 text-zinc-600 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-zinc-200 transition-all flex items-center gap-2 flex-shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-sm">dashboard</span>
                SME Dashboard
            </a>
        </div>
    </div>

    {{-- Filter & Search Bar --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-6 bg-white p-6 rounded-[2rem] border border-zinc-100 shadow-sm">
        {{-- Search Input --}}
        <div class="relative w-full md:w-96">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 text-lg">search</span>
            <input type="text" x-model="searchQuery" placeholder="Cari judul blueprint atau kode..." class="w-full pl-11 pr-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-xs font-medium text-zinc-800 placeholder-zinc-400 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
        </div>

        {{-- Category Dropdown --}}
        <div class="flex items-center gap-3 w-full md:w-auto">
            <span class="text-xs font-bold text-zinc-500 flex-shrink-0">Filter Kategori:</span>
            <select x-model="selectedCategory" class="w-full md:w-auto px-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-xs font-bold text-zinc-700 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all cursor-pointer">
                <option value="all">Semua Kategori</option>
                @php
                    $categories = collect($blueprints ?? [])->pluck('category')->unique();
                @endphp
                @foreach($categories as $cat)
                <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Blueprints Table Card --}}
    <div class="bg-white rounded-[2.5rem] border border-zinc-100 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-zinc-50/50 border-b border-zinc-100">
                    <th class="px-8 py-5 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Informasi Blueprint</th>
                    <th class="px-8 py-5 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Kategori & Status</th>
                    <th class="px-8 py-5 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Tanggal Rilis</th>
                    <th class="px-8 py-5 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50">
                @forelse($blueprints ?? [] as $bp)
                <tr x-show="filterBlueprint('{{ addslashes($bp->title . ' ' . $bp->id) }}', '{{ addslashes($bp->category) }}')" x-transition class="hover:bg-zinc-50/30 transition-colors group cursor-default">
                    <td class="px-8 py-6 w-5/12">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:bg-primary/10 group-hover:text-primary transition-colors flex-shrink-0 shadow-sm border border-blue-100 group-hover:border-primary/20">
                                <span class="material-symbols-outlined text-2xl">verified</span>
                            </div>
                            <div>
                                <p class="font-black text-on-surface group-hover:text-primary transition-colors leading-tight text-sm uppercase">{{ $bp->title }}</p>
                                <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest mt-1">Kode: <span class="text-zinc-600">{{ $bp->id }}</span> • Divalidasi Penuh</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-black uppercase tracking-wider text-blue-600">{{ $bp->category }}</span>
                            <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-0.5 bg-blue-50 text-blue-600 border border-blue-200 rounded-full w-max">
                                {{ strtoupper(str_replace('_', ' ', $bp->status)) }}
                            </span>
                        </div>
                    </td>
                    <td class="px-8 py-6 whitespace-nowrap">
                        <div class="flex items-center gap-2 text-xs font-bold text-zinc-600">
                            <span class="material-symbols-outlined text-base text-zinc-400">calendar_month</span>
                            {{ $bp->created_at ? \Carbon\Carbon::parse($bp->created_at)->translatedFormat('d F Y') : '10 Mei 2026' }}
                        </div>
                    </td>
                    <td class="px-8 py-6 text-right whitespace-nowrap">
                        <a href="{{ route('sme.blueprint.show', $bp->id) }}" class="inline-block px-6 py-3 bg-zinc-800 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-zinc-900 shadow-lg shadow-zinc-800/20 hover:shadow-zinc-800/30 transition-all active:scale-95 flex items-center gap-1.5 w-max ml-auto">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                            Lihat Silabus
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-12 text-center text-zinc-400 text-xs italic bg-zinc-50/50">
                        Belum ada modul yang berstatus divalidasi atau dirilis saat ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
