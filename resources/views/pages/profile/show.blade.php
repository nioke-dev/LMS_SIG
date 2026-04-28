@extends('layouts.backoffice')

@section('title', 'Profil Saya')

@section('sidebar-nav')
    @include('partials.lc-sidebar')
@endsection

@section('content')
<div class="max-w-[1200px] mx-auto space-y-12 pb-20">
    {{-- Profile Header Card --}}
    <div class="bg-white rounded-[4rem] shadow-sm border border-zinc-100 overflow-hidden relative">
        {{-- Banner Section --}}
        <div class="h-80 w-full relative overflow-hidden bg-zinc-900">
            <img src="{{ asset('assets/images/profile-banner.png') }}" alt="Profile Banner" class="w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-900/80 via-transparent to-transparent"></div>
            
            {{-- Floating Badge --}}
            <div class="absolute top-8 right-8 px-5 py-2.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_10px_rgba(52,211,153,0.8)]"></div>
                <span class="text-white text-[10px] font-black uppercase tracking-[0.2em]">Verified Employee</span>
            </div>
        </div>

        {{-- Profile Basic Info Bar --}}
        <div class="px-12 pb-12 flex flex-col lg:flex-row justify-between items-end gap-10">
            <div class="flex flex-col items-start w-full lg:w-auto">
                {{-- Avatar (Floating with negative margin) --}}
                <div class="relative -mt-24 mb-6 group">
                    <div class="w-44 h-44 rounded-[3.5rem] bg-white p-2 shadow-2xl transition-transform duration-500 group-hover:rotate-3">
                        <div class="w-full h-full rounded-[3rem] bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-white font-black text-5xl border-4 border-white relative overflow-hidden">
                            <span class="relative z-10 drop-shadow-lg">{{ $user->initials() }}</span>
                            <div class="absolute inset-0 opacity-30 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
                        </div>
                    </div>
                </div>

                {{-- Name & Badges --}}
                <div class="space-y-4">
                    <h1 class="text-5xl font-black text-zinc-900 tracking-tighter leading-tight">{{ $user->name }}</h1>
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="flex items-center gap-2 px-4 py-2 bg-primary/5 border border-primary/10 rounded-2xl">
                            <span class="material-symbols-outlined text-primary text-sm">verified_user</span>
                            <span class="text-primary text-[10px] font-black uppercase tracking-widest">{{ ucwords(str_replace('_', ' ', $user->role)) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 pb-2">
                <a href="{{ route('learning-coordinator.profile.change-password') }}" class="px-8 py-4 bg-zinc-900 text-white rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-zinc-800 transition-all shadow-xl shadow-zinc-900/20 active:scale-95 flex items-center gap-3 group">
                    <span class="material-symbols-outlined text-sm group-hover:scale-110 transition-transform">lock</span>
                    Ganti Password
                </a>
            </div>
        </div>
    </div>

    {{-- Profile Details Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        {{-- Personal Information --}}
        <div class="bg-white rounded-[3.5rem] shadow-sm border border-zinc-100 overflow-hidden">
            <div class="px-12 py-10 border-b border-zinc-50 flex justify-between items-center bg-zinc-50/50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white shadow-sm border border-zinc-100 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">badge</span>
                    </div>
                    <h3 class="text-xl font-black text-zinc-900 tracking-tight">Informasi Personal</h3>
                </div>
                <button @click="emailModalOpen = true" class="text-[10px] font-black uppercase tracking-widest text-primary hover:bg-primary/5 px-4 py-2 rounded-xl border border-primary/10 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">edit</span>
                    Perbarui Data
                </button>
            </div>
            <div class="p-12 space-y-10">
                <div class="grid grid-cols-1 gap-10">
                    <div class="space-y-2">
                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Nama Lengkap</p>
                        <p class="text-base font-bold text-zinc-900">{{ $user->name }}</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Nomor Induk Karyawan (NIK)</p>
                        <p class="text-base font-bold text-zinc-900">{{ $user->nik ?? '89230122' }}</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Alamat Email Resmi</p>
                        <p class="text-base font-bold text-zinc-900">{{ $user->email }}</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Nomor Telepon</p>
                        <p class="text-base font-bold text-zinc-900">{{ $user->phone ?? '+62 812-3456-7890' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Organization Information --}}
        <div class="bg-white rounded-[3.5rem] shadow-sm border border-zinc-100 overflow-hidden">
            <div class="px-12 py-10 border-b border-zinc-50 flex justify-between items-center bg-zinc-50/50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white shadow-sm border border-zinc-100 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">account_tree</span>
                    </div>
                    <h3 class="text-xl font-black text-zinc-900 tracking-tight">Struktur Organisasi</h3>
                </div>
            </div>
            <div class="p-12 space-y-10">
                <div class="grid grid-cols-1 gap-10">
                    <div class="space-y-2">
                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Level 1: Perusahaan</p>
                        <p class="text-base font-bold text-zinc-900">{{ $user->company ?? 'PT Semen Indonesia (Persero) Tbk' }}</p>
                    </div>

                    @if($user->directorate || $user->unit)
                    <div class="space-y-2">
                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Level 2: Direktorat / Unit Kerja</p>
                        <p class="text-base font-bold text-zinc-900">{{ $user->directorate ?? $user->unit ?? '-' }}</p>
                    </div>
                    @endif

                    @if($user->department)
                    <div class="space-y-2">
                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Level 3: Departemen</p>
                        <p class="text-base font-bold text-primary">{{ $user->department }}</p>
                    </div>
                    @endif

                    @if($user->work_location)
                    <div class="space-y-2">
                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Penempatan: Lokasi Kerja</p>
                        <p class="text-base font-bold text-zinc-900">{{ $user->work_location }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
