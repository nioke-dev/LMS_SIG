@extends('layouts.backoffice')

@section('sidebar-nav')
    @include('partials.lc-sidebar')
@endsection

@section('title', 'Dashboard Learning Coordinator')

@section('page-title', 'Overview Dashboard')

@section('content')
<div class="space-y-8">
    {{-- Header Section --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-on-surface tracking-tight uppercase">
                Selamat Datang, <span class="text-primary">{{ explode(' ', auth()->user()->name)[0] }}!</span> 👋
            </h1>
            <p class="text-on-surface-variant font-medium mt-1">Berikut adalah ringkasan usulan pelatihan (TNA) Anda.</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('learning-coordinator.buat-usulan') }}" class="flex items-center gap-2 px-6 py-3.5 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all active:scale-95">
                <span class="material-symbols-outlined text-xl">add</span>
                Buat Usulan Baru
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Total --}}
        <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm hover:shadow-xl hover:shadow-zinc-200/50 transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-zinc-50 rounded-2xl flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined">description</span>
                </div>
                <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Total Usulan</span>
            </div>
            <div class="text-4xl font-black text-on-surface tracking-tighter">{{ $stats['total'] }}</div>
            <p class="text-xs text-zinc-400 font-medium mt-1">Seluruh Periode</p>
        </div>

        {{-- Review --}}
        <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm hover:shadow-xl hover:shadow-zinc-200/50 transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center group-hover:bg-orange-500 group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined">pending_actions</span>
                </div>
                <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Review</span>
            </div>
            <div class="text-4xl font-black text-on-surface tracking-tighter text-orange-500">{{ $stats['review'] }}</div>
            <p class="text-xs text-zinc-400 font-medium mt-1">Menunggu Persetujuan</p>
        </div>

        {{-- Approved --}}
        <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm hover:shadow-xl hover:shadow-zinc-200/50 transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center group-hover:bg-green-500 group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined">check_circle</span>
                </div>
                <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Approved</span>
            </div>
            <div class="text-4xl font-black text-on-surface tracking-tighter text-green-500">{{ $stats['approved'] }}</div>
            <p class="text-xs text-zinc-400 font-medium mt-1">Siap Dilaksanakan</p>
        </div>

        {{-- Draft --}}
        <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm hover:shadow-xl hover:shadow-zinc-200/50 transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-zinc-50 text-zinc-400 rounded-2xl flex items-center justify-center group-hover:bg-zinc-800 group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined">edit_note</span>
                </div>
                <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Draft</span>
            </div>
            <div class="text-4xl font-black text-on-surface tracking-tighter">{{ $stats['draft'] }}</div>
            <p class="text-xs text-zinc-400 font-medium mt-1">Belum Dikirim</p>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Trend Chart --}}
        <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm transition-all duration-500 flex flex-col">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-sm font-black text-on-surface tracking-widest uppercase mb-1">Trend Pengajuan TNA</h2>
                    <p class="text-xs text-zinc-400 font-medium">Data 6 bulan terakhir</p>
                </div>
                <div class="flex items-center gap-2 text-zinc-400">
                    <span class="w-2.5 h-2.5 bg-primary rounded-full shadow-lg shadow-primary/30"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Usulan</span>
                </div>
            </div>
            <div id="trendChart" class="flex-grow w-full"></div>
        </div>

        {{-- Status Distribution Chart --}}
        <div class="bg-white p-8 rounded-[2.5rem] border border-zinc-100 shadow-sm flex flex-col transition-all duration-500">
            <h2 class="text-sm font-black text-on-surface tracking-widest uppercase mb-8 text-center">Distribusi Status</h2>
            <div id="statusChart" class="min-h-[300px] flex items-center justify-center w-full"></div>
            <div class="mt-auto grid grid-cols-2 gap-4">
                <div class="p-4 rounded-2xl bg-zinc-50 border border-zinc-100 text-center">
                    <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">Approved</p>
                    <p class="text-lg font-black text-green-500">{{ $stats['approved'] }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-zinc-50 border border-zinc-100 text-center">
                    <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">Review</p>
                    <p class="text-lg font-black text-orange-500">{{ $stats['review'] }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-zinc-50 border border-zinc-100 text-center">
                    <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">Draft</p>
                    <p class="text-lg font-black text-zinc-500">{{ $stats['draft'] }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-zinc-50 border border-zinc-100 text-center">
                    <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">Rejected</p>
                    <p class="text-lg font-black text-red-500">{{ $stats['rejected'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Table Recent Submissions --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-black text-on-surface tracking-tight uppercase">Usulan Terbaru</h2>
                <a href="{{ route('learning-coordinator.daftar-usulan') }}" class="text-sm font-bold text-primary hover:underline">Lihat Semua</a>
            </div>

            <div class="bg-white rounded-3xl border border-zinc-100 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-zinc-50/50 border-b border-zinc-100">
                            <th class="px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">ID TNA</th>
                            <th class="px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Pelatihan</th>
                            <th class="px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @forelse($recentSubmissions as $submission)
                            <tr class="hover:bg-zinc-50/30 transition-colors">
                                <td class="px-6 py-5 font-mono text-xs text-zinc-400">{{ $submission->id }}</td>
                                <td class="px-6 py-5">
                                    <div class="font-bold text-on-surface tracking-tight">{{ $submission->title }}</div>
                                    <div class="text-xs text-zinc-400 font-medium">{{ $submission->category }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex justify-center">
                                        @php
                                            $statusStyles = [
                                                'draft' => 'bg-zinc-100 text-zinc-500',
                                                'review' => 'bg-orange-50 text-orange-500',
                                                'approved' => 'bg-green-50 text-green-500',
                                                'rejected' => 'bg-red-50 text-red-500',
                                            ];
                                            $style = $statusStyles[$submission->status] ?? $statusStyles['draft'];
                                        @endphp
                                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-tighter {{ $style }}">
                                            {{ $submission->status }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-right text-xs font-bold text-zinc-500">
                                    {{ $submission->date }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <span class="material-symbols-outlined text-4xl text-zinc-200 mb-2">inventory_2</span>
                                    <p class="text-zinc-400 font-medium italic">Belum ada usulan yang dibuat.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="space-y-6">
            <div class="bg-white p-8 rounded-3xl border border-zinc-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16"></div>
                <h2 class="text-sm font-black text-on-surface tracking-widest uppercase mb-8 flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-primary rounded-full"></span>
                    Informasi Learning Coordinator
                </h2>
                
                <div class="space-y-4">
                    <p class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-4">Struktur Organisasi</p>
                    
                    <div class="relative space-y-4">
                        @php
                            $orgPath = auth()->user()->getOrganizationPath();
                        @endphp
                        
                        @forelse($orgPath as $index => $org)
                            <div class="relative flex items-start gap-4">
                                {{-- Vertical Line Connector --}}
                                @if(!$loop->last)
                                    <div class="absolute left-[15px] top-8 w-[2px] h-8 bg-zinc-100"></div>
                                @endif
                                
                                <div class="w-8 h-8 rounded-lg {{ $loop->first ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-zinc-50 text-zinc-400 border border-zinc-100' }} flex items-center justify-center shrink-0 z-10">
                                    <span class="material-symbols-outlined text-sm">
                                        {{ $loop->first ? 'domain' : ($loop->last ? 'account_tree' : 'lan') }}
                                    </span>
                                </div>
                                
                                <div class="pt-1">
                                    <p class="text-[9px] font-black text-zinc-400 uppercase tracking-widest leading-none mb-1">
                                        {{ $org->level->name ?? 'Unit' }}
                                    </p>
                                    <p class="text-xs font-bold text-on-surface leading-tight">{{ $org->name }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 rounded-2xl bg-zinc-50 border border-dashed border-zinc-200 text-center">
                                <p class="text-[10px] font-bold text-zinc-400 italic">Data organisasi belum di-setup.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-10 pt-6 border-t border-zinc-50">
                        <div class="flex items-center gap-3 p-4 rounded-2xl bg-primary/5 border border-primary/10">
                            <div class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center">
                                <span class="material-symbols-outlined text-xl">verified_user</span>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-primary uppercase tracking-widest leading-none mb-1">Role System</p>
                                <p class="text-xs font-black text-zinc-900">Learning Coordinator</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to init charts with slight delay to ensure layout is ready
        setTimeout(() => {
            initCharts();
            // Trigger a resize event to be extra sure
            window.dispatchEvent(new Event('resize'));
        }, 500);

        function initCharts() {
            // --- Trend Chart (Area) ---
        const trendOptions = {
            series: [{
                name: 'Usulan TNA',
                data: [31, 40, 28, 51, 42, 109, 100]
            }],
            chart: {
                height: 450,
                width: '100%',
                type: 'area',
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                sparkline: { enabled: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3, colors: ['#e21d24'] },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100, 100, 100],
                    colorStops: [
                        { offset: 0, color: "#e21d24", opacity: 0.4 },
                        { offset: 100, color: "#e21d24", opacity: 0 }
                    ]
                }
            },
            xaxis: {
                categories: ['Nov', 'Des', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei'],
                axisBorder: { show: false },
                axisTicks: { show: false },
                tooltip: { enabled: false },
                labels: {
                    show: true,
                    offsetY: 0,
                    style: { 
                        colors: '#94a3b8', 
                        fontWeight: 600,
                        fontSize: '10px'
                    }
                }
            },
            yaxis: { 
                show: true,
                labels: {
                    style: {
                        colors: '#94a3b8',
                        fontWeight: 600,
                        fontSize: '10px'
                    }
                }
            },
            grid: { 
                borderColor: '#f1f1f1', 
                strokeDashArray: 4,
                padding: { 
                    left: 15, 
                    right: 15,
                    top: 0,
                    bottom: 20
                }
            },
            tooltip: { 
                x: { format: 'dd/mm/yy' },
                theme: 'light'
            }
        };

        const trendChart = new ApexCharts(document.querySelector("#trendChart"), trendOptions);
        trendChart.render();

        // --- Status Chart (Donut) ---
        const statusOptions = {
            series: [
                {{ $stats['approved'] ?? 0 }}, 
                {{ $stats['review'] ?? 0 }}, 
                {{ $stats['draft'] ?? 0 }}, 
                {{ $stats['rejected'] ?? 0 }}
            ],
            chart: {
                type: 'donut',
                height: 300,
                width: '100%',
                fontFamily: 'Inter, sans-serif'
            },
            labels: ['Approved', 'Review', 'Draft', 'Rejected'],
            colors: ['#22c55e', '#f97316', '#94a3b8', '#ef4444'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'TOTAL',
                                fontSize: '12px',
                                fontWeight: 900,
                                color: '#94a3b8',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            legend: { show: false },
            stroke: { width: 0 }
        };

        const statusChart = new ApexCharts(document.querySelector("#statusChart"), statusOptions);
        statusChart.render();
        }
    });
</script>
@endsection
