@extends('layouts.backoffice')

@section('sidebar-nav')
    @include('partials.ac-sidebar')
@endsection

@section('title', 'Dashboard Admin Coordinator')

@section('page-title', 'Overview')

@section('content')
<div class="space-y-8">
    {{-- Welcome Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-on-surface tracking-tight uppercase">
                Welcome back, <span class="text-primary">{{ explode(' ', auth()->user()->name)[0] }}!</span>
            </h1>
            <p class="text-on-surface-variant font-medium mt-1">Pantau usulan pelatihan dan kurasi katalog SIG Academy hari ini.</p>
        </div>
        <div class="flex gap-4">
            <button class="flex items-center gap-2 px-6 py-3.5 bg-white border border-zinc-200 text-zinc-600 font-bold rounded-2xl shadow-sm hover:bg-zinc-50 transition-all active:scale-95">
                <span class="material-symbols-outlined text-xl">analytics</span>
                Laporan PDF
            </button>
            <button class="flex items-center gap-2 px-6 py-3.5 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all active:scale-95">
                <span class="material-symbols-outlined text-xl">fact_check</span>
                Mulai Review
            </button>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Total Submissions --}}
        <div class="bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm flex items-start justify-between group hover:shadow-xl transition-all duration-500">
            <div>
                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-4">Total Usulan TNA</p>
                <h3 class="text-4xl font-black text-on-surface group-hover:text-primary transition-colors">128</h3>
                <p class="text-xs font-bold text-zinc-500 mt-2 flex items-center gap-1">
                    <span class="text-emerald-500">+12%</span> dibanding bulan lalu
                </p>
            </div>
            <div class="w-14 h-14 bg-zinc-50 rounded-2xl flex items-center justify-center text-zinc-400 group-hover:bg-primary/10 group-hover:text-primary transition-colors duration-500">
                <span class="material-symbols-outlined text-3xl">list_alt</span>
            </div>
        </div>

        {{-- Pending Review --}}
        <div class="bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm flex items-start justify-between group hover:shadow-xl transition-all duration-500">
            <div>
                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-4">Perlu Direview</p>
                <h3 class="text-4xl font-black text-on-surface group-hover:text-amber-500 transition-colors">12</h3>
                <p class="text-xs font-bold text-zinc-500 mt-2 flex items-center gap-1 text-amber-600">
                    Sangat Mendesak (3)
                </p>
            </div>
            <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 group-hover:bg-amber-100 transition-colors duration-500">
                <span class="material-symbols-outlined text-3xl">pending_actions</span>
            </div>
        </div>

        {{-- Approved --}}
        <div class="bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm flex items-start justify-between group hover:shadow-xl transition-all duration-500">
            <div>
                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-4">Disetujui</p>
                <h3 class="text-4xl font-black text-on-surface group-hover:text-emerald-500 transition-colors">94</h3>
                <p class="text-xs font-bold text-zinc-500 mt-2 flex items-center gap-1">
                    Sudah Masuk Kurasi
                </p>
            </div>
            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 group-hover:bg-emerald-100 transition-colors duration-500">
                <span class="material-symbols-outlined text-3xl">verified</span>
            </div>
        </div>

        {{-- Total Participants --}}
        <div class="bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm flex items-start justify-between group hover:shadow-xl transition-all duration-500">
            <div>
                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-4">Total Peserta</p>
                <h3 class="text-4xl font-black text-on-surface group-hover:text-blue-500 transition-colors">452</h3>
                <p class="text-xs font-bold text-zinc-500 mt-2 flex items-center gap-1">
                    Semua Departemen
                </p>
            </div>
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 group-hover:bg-blue-100 transition-colors duration-500">
                <span class="material-symbols-outlined text-3xl">groups</span>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Table: Recent Review Requests --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between px-2">
                <h2 class="text-sm font-black text-on-surface-variant uppercase tracking-widest flex items-center gap-3">
                    <span class="w-2 h-2 bg-primary rounded-full"></span>
                    Antrean Review Terbaru
                </h2>
                <a href="#" class="text-[10px] font-black text-primary uppercase tracking-widest hover:underline">Lihat Semua</a>
            </div>
            
            <div class="bg-white rounded-[2.5rem] border border-zinc-100 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-zinc-50/50 border-b border-zinc-100">
                            <th class="px-8 py-5 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Informasi Usulan</th>
                            <th class="px-8 py-5 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Pengefektifan</th>
                            <th class="px-8 py-5 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @for($i = 1; $i <= 5; $i++)
                        <tr class="hover:bg-zinc-50/30 transition-colors group cursor-default">
                            <td class="px-8 py-6">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-zinc-100 rounded-xl flex items-center justify-center text-zinc-400 group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-xl">description</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-on-surface group-hover:text-primary transition-colors leading-tight">Pelatihan Management Asset SIG Group - Tahap {{ $i }}</p>
                                        <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest mt-1">Diajukan oleh Learning Coordinator • 2h ago</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black uppercase tracking-tighter text-blue-600">Teknis Industri</span>
                                    <span class="text-xs font-bold text-zinc-500">12 Peserta</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <button class="px-5 py-2 bg-primary text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-primary-container shadow-lg shadow-primary/20 hover:shadow-primary/30 transition-all active:scale-95">
                                    Review
                                </button>
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Activity Sidebar --}}
        <div class="space-y-4">
            <h2 class="text-sm font-black text-on-surface-variant uppercase tracking-widest flex items-center gap-3 px-2">
                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                Log Aktivitas
            </h2>
            <div class="bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm space-y-6">
                @php
                    $activities = [
                        ['type' => 'approve', 'text' => 'Menyetujui usulan TNA "Digital Marketing"', 'time' => '10 menit yang lalu'],
                        ['type' => 'reject', 'text' => 'Menolak usulan TNA "Yoga Workshop"', 'time' => '1 jam yang lalu'],
                        ['type' => 'review', 'text' => 'Mulai mereview usulan "Cyber Security"', 'time' => '3 jam yang lalu'],
                        ['type' => 'system', 'text' => 'Berhasil memperbarui katalog kurasi', 'time' => 'Hari ini'],
                    ];
                @endphp
                @foreach($activities as $act)
                <div class="flex items-start gap-4">
                    <div class="w-2 h-2 rounded-full mt-2 {{ $act['type'] == 'approve' ? 'bg-emerald-500' : ($act['type'] == 'reject' ? 'bg-red-500' : 'bg-blue-500') }}"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-zinc-800 leading-snug">{{ $act['text'] }}</p>
                        <p class="text-[10px] text-zinc-400 mt-1 font-medium">{{ $act['time'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
