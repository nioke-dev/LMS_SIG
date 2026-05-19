@extends('layouts.backoffice')

@section('sidebar-nav')
    @include('partials.sme-sidebar')
@endsection

@section('title', 'Dashboard Subject Matter Expert')

@section('page-title', 'SME Overview')

@section('content')
<div class="space-y-8">
    {{-- Welcome Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-on-surface tracking-tight uppercase">
                Selamat Datang, <span class="text-primary">{{ explode(' ', auth()->user()->name)[0] }}!</span>
            </h1>
            <p class="text-on-surface-variant font-medium mt-1">Tinjau blueprint pelatihan dan berikan validasi teknis pada modul pembelajaran SIG Academy.</p>
        </div>
        <div class="flex gap-4">
            <button class="flex items-center gap-2 px-6 py-3.5 bg-white border border-zinc-200 text-zinc-600 font-bold rounded-2xl shadow-sm hover:bg-zinc-50 transition-all active:scale-95">
                <span class="material-symbols-outlined text-xl">book</span>
                Panduan SME
            </button>
            <button class="flex items-center gap-2 px-6 py-3.5 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all active:scale-95">
                <span class="material-symbols-outlined text-xl">rate_review</span>
                Mulai Review Blueprint
            </button>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Total Assigned Blueprints --}}
        <div class="bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm flex items-start justify-between group hover:shadow-xl transition-all duration-500">
            <div>
                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-4">Blueprint Ditugaskan</p>
                <h3 class="text-4xl font-black text-on-surface group-hover:text-primary transition-colors">{{ $totalAssigned ?? 8 }}</h3>
                <p class="text-xs font-bold text-zinc-500 mt-2 flex items-center gap-1">
                    <span class="text-amber-500">{{ $waitingReview ?? 3 }} Menunggu Review</span>
                </p>
            </div>
            <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 group-hover:bg-amber-100 transition-colors duration-500">
                <span class="material-symbols-outlined text-3xl">architecture</span>
            </div>
        </div>

        {{-- Modul Divalidasi --}}
        <div class="bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm flex items-start justify-between group hover:shadow-xl transition-all duration-500">
            <div>
                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-4">Modul Divalidasi</p>
                <h3 class="text-4xl font-black text-on-surface group-hover:text-emerald-500 transition-colors">{{ $validatedCount ?? 24 }}</h3>
                <p class="text-xs font-bold text-zinc-500 mt-2 flex items-center gap-1 text-emerald-600">
                    Sesuai Standar Industri
                </p>
            </div>
            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 group-hover:bg-emerald-100 transition-colors duration-500">
                <span class="material-symbols-outlined text-3xl">verified</span>
            </div>
        </div>

        {{-- Feedback Diberikan --}}
        <div class="bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm flex items-start justify-between group hover:shadow-xl transition-all duration-500">
            <div>
                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-4">Catatan & Masukan</p>
                <h3 class="text-4xl font-black text-on-surface group-hover:text-blue-500 transition-colors">{{ $pendingApprovalCount ?? 42 }}</h3>
                <p class="text-xs font-bold text-zinc-500 mt-2 flex items-center gap-1">
                    Direkomendasikan ke CLD
                </p>
            </div>
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 group-hover:bg-blue-100 transition-colors duration-500">
                <span class="material-symbols-outlined text-3xl">rate_review</span>
            </div>
        </div>

        {{-- Rata-rata SLA --}}
        <div class="bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm flex items-start justify-between group hover:shadow-xl transition-all duration-500">
            <div>
                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-4">Rata-rata Waktu Review</p>
                <h3 class="text-4xl font-black text-on-surface group-hover:text-purple-500 transition-colors">1.8 <span class="text-lg font-bold">Hari</span></h3>
                <p class="text-xs font-bold text-zinc-500 mt-2 flex items-center gap-1 text-emerald-500">
                    Sangat Cepat & Efisien
                </p>
            </div>
            <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-500 group-hover:bg-purple-100 transition-colors duration-500">
                <span class="material-symbols-outlined text-3xl">timer</span>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Active Priority Tasks Hub --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between px-2">
                <h2 class="text-sm font-black text-on-surface-variant uppercase tracking-widest flex items-center gap-3">
                    <span class="w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse"></span>
                    Tugas Aktif & Pengingat Prioritas (Active Tasks)
                </h2>
                <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Wajib Tindak Lanjut</span>
            </div>
            
            <div class="space-y-4">
                @forelse($blueprints ?? [] as $bp)
                    @if($bp->status === 'revision_required')
                        {{-- Task Card: Revision Required (Urgent) --}}
                        <div class="bg-white rounded-[2.5rem] p-8 border-2 border-red-100 shadow-xl shadow-red-500/5 hover:border-red-200 transition-all duration-300 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 group relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-2 h-full bg-red-500"></div>
                            <div class="space-y-3 flex-1 pl-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-[10px] font-black bg-red-50 text-red-600 border border-red-200 uppercase tracking-wider">
                                        <span class="material-symbols-outlined text-xs">warning</span>
                                        Urgent: Butuh Perbaikan
                                    </span>
                                    <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Kode: {{ $bp->id }}</span>
                                </div>
                                <h3 class="text-lg font-black text-zinc-900 group-hover:text-red-600 transition-colors uppercase tracking-tight">{{ $bp->title }}</h3>
                                <p class="text-xs text-zinc-600 font-medium leading-relaxed">Terdapat catatan perbaikan dari Learning Administrator yang perlu segera disesuaikan sebelum batas waktu berakhir.</p>
                                <div class="flex items-center gap-4 text-[11px] font-bold text-red-500 pt-1">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">alarm</span>
                                        Mepet Deadline: {{ $bp->deadline ? \Carbon\Carbon::parse($bp->deadline)->translatedFormat('d F Y') : 'Segera' }}
                                    </span>
                                </div>
                            </div>
                            <div class="w-full md:w-auto shrink-0 flex flex-col items-stretch md:items-end gap-2 pt-4 md:pt-0 border-t md:border-t-0 border-zinc-100">
                                <a href="{{ route('sme.blueprint.show', $bp->id) }}" class="px-6 py-3.5 bg-amber-500 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-amber-600 shadow-lg shadow-amber-500/20 hover:shadow-amber-500/30 transition-all text-center active:scale-95 flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-sm">edit_document</span>
                                    Perbaiki Materi
                                </a>
                                <a href="{{ route('sme.revision.index') }}" class="text-[10px] font-bold text-zinc-400 hover:text-zinc-600 text-center block tracking-wider uppercase pt-1">Lihat Detail Catatan &rarr;</a>
                            </div>
                        </div>
                    @elseif($bp->status === 'assigned_to_sme')
                        {{-- Task Card: New Assignment --}}
                        <div class="bg-white rounded-[2.5rem] p-8 border border-zinc-100 shadow-lg shadow-zinc-200/20 hover:border-primary/30 transition-all duration-300 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 group relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-2 h-full bg-amber-500"></div>
                            <div class="space-y-3 flex-1 pl-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-[10px] font-black bg-amber-50 text-amber-600 border border-amber-200 uppercase tracking-wider">
                                        <span class="material-symbols-outlined text-xs">assignment</span>
                                        Tugas Baru Ditugaskan
                                    </span>
                                    <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Kode: {{ $bp->id }}</span>
                                </div>
                                <h3 class="text-lg font-black text-zinc-900 group-hover:text-primary transition-colors uppercase tracking-tight">{{ $bp->title }}</h3>
                                <p class="text-xs text-zinc-600 font-medium leading-relaxed">Anda ditugaskan oleh Admin Coordinator untuk meninjau blueprint pelatihan dan memberikan validasi teknis pada modul ini.</p>
                                <div class="flex items-center gap-4 text-[11px] font-bold text-zinc-500 pt-1">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">calendar_month</span>
                                        Tenggat: {{ $bp->deadline ? \Carbon\Carbon::parse($bp->deadline)->translatedFormat('d F Y') : '19 Mei 2026' }}
                                    </span>
                                </div>
                            </div>
                            <div class="w-full md:w-auto shrink-0 flex flex-col items-stretch md:items-end gap-2 pt-4 md:pt-0 border-t md:border-t-0 border-zinc-100">
                                <a href="{{ route('sme.blueprint.show', $bp->id) }}" class="px-6 py-3.5 bg-primary text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-primary-container shadow-lg shadow-primary/20 hover:shadow-primary/30 transition-all text-center active:scale-95 flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-sm">rate_review</span>
                                    Mulai Review
                                </a>
                                <a href="{{ route('sme.blueprint.index') }}" class="text-[10px] font-bold text-zinc-400 hover:text-zinc-600 text-center block tracking-wider uppercase pt-1">Buka Direktori &rarr;</a>
                            </div>
                        </div>
                    @elseif($bp->status === 'studio_production' || $bp->status === 'curriculum_submitted')
                        {{-- Task Card: Masterclass Builder --}}
                        <div class="bg-white rounded-[2.5rem] p-8 border border-zinc-100 shadow-lg shadow-zinc-200/20 hover:border-emerald-500/30 transition-all duration-300 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 group relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-2 h-full bg-emerald-500"></div>
                            <div class="space-y-3 flex-1 pl-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-[10px] font-black bg-emerald-50 text-emerald-600 border border-emerald-200 uppercase tracking-wider">
                                        <span class="material-symbols-outlined text-xs">dashboard_customize</span>
                                        Siap Masterclass Builder
                                    </span>
                                    <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Kode: {{ $bp->id }}</span>
                                </div>
                                <h3 class="text-lg font-black text-zinc-900 group-hover:text-emerald-600 transition-colors uppercase tracking-tight">{{ $bp->title }}</h3>
                                <p class="text-xs text-zinc-600 font-medium leading-relaxed">Materi telah disetujui sepenuhnya oleh Learning Administrator. Silakan masuk ke studio builder untuk menyusun video dan kuis.</p>
                                <div class="flex items-center gap-4 text-[11px] font-bold text-emerald-600 pt-1">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">check_circle</span>
                                        Persetujuan Selesai • Tahap Produksi
                                    </span>
                                </div>
                            </div>
                            <div class="w-full md:w-auto shrink-0 flex flex-col items-stretch md:items-end gap-2 pt-4 md:pt-0 border-t md:border-t-0 border-zinc-100">
                                <a href="{{ route('sme.masterclass.curriculum', $bp->id) }}" class="px-6 py-3.5 bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-700 shadow-lg shadow-emerald-600/20 hover:shadow-emerald-600/30 transition-all text-center active:scale-95 flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-sm">dashboard_customize</span>
                                    Buka Builder
                                </a>
                                <a href="{{ route('sme.masterclass.index') }}" class="text-[10px] font-bold text-zinc-400 hover:text-zinc-600 text-center block tracking-wider uppercase pt-1">Buka Daftar Masterclass &rarr;</a>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="bg-zinc-50 rounded-[2.5rem] p-12 text-center border border-zinc-100 space-y-4">
                        <div class="w-16 h-16 bg-zinc-100 text-zinc-400 rounded-2xl mx-auto flex items-center justify-center">
                            <span class="material-symbols-outlined text-3xl">task_alt</span>
                        </div>
                        <h3 class="text-base font-black text-zinc-700 uppercase tracking-tight">Tidak Ada Tugas Aktif</h3>
                        <p class="text-xs text-zinc-500 max-w-md mx-auto leading-relaxed">Semua blueprint dan penugasan telah selesai Anda tangani dengan baik. Anda dapat melihat riwayat modul di menu samping.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Activity Sidebar --}}
        <div class="space-y-4">
            <h2 class="text-sm font-black text-on-surface-variant uppercase tracking-widest flex items-center gap-3 px-2">
                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                Riwayat Validasi
            </h2>
            <div class="bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm space-y-6">
                @php
                    $activities = [
                        ['type' => 'approve', 'text' => 'Memvalidasi modul "Rotary Kiln Maintenance"', 'time' => 'Kemarin'],
                        ['type' => 'review', 'text' => 'Memberikan catatan perbaikan pada "K3 Pertambangan"', 'time' => '3 hari yang lalu'],
                        ['type' => 'approve', 'text' => 'Memvalidasi silabus "Otomasi RPA Keuangan"', 'time' => 'Minggu lalu'],
                        ['type' => 'approve', 'text' => 'Menyelesaikan review "Environmental Audit B3"', 'time' => '2 minggu yang lalu'],
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
