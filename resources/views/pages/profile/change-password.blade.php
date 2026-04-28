@extends('layouts.backoffice')

@section('title', 'Ganti Kata Sandi')

@section('sidebar-nav')
    @include('partials.lc-sidebar')
@endsection

@section('content')
<div class="max-w-[1200px] mx-auto space-y-12 pb-20">
    {{-- Page Header --}}
    <div class="space-y-4">
        <h1 class="text-6xl font-black text-zinc-900 tracking-tighter leading-tight">Ganti Kata Sandi</h1>
        <p class="text-zinc-500 text-lg max-w-2xl leading-relaxed">Kelola keamanan akun Anda dengan memperbarui kredensial secara berkala demi menjaga integritas data operasional.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
        {{-- Left Column: Form --}}
        <div x-data="{ 
            password: '',
            confirmPassword: '',
            get strength() {
                if (this.password.length === 0) return 0;
                let score = 0;
                if (this.password.length >= 8) score++;
                if (/[A-Z]/.test(this.password)) score++;
                if (/[0-9]/.test(this.password)) score++;
                if (/[^A-Za-z0-9]/.test(this.password)) score++;
                return score === 0 ? 1 : score;
            },
            get strengthText() {
                const texts = ['', 'Sangat Lemah', 'Lemah', 'Sedang', 'Sangat Kuat'];
                return texts[this.strength];
            },
            get strengthClass() {
                const colors = ['', 'text-red-500', 'text-orange-500', 'text-yellow-500', 'text-emerald-500'];
                return colors[this.strength];
            },
            get passwordsMatch() {
                if (this.confirmPassword.length === 0) return true;
                return this.password === this.confirmPassword;
            }
        }" class="lg:col-span-2 bg-white rounded-[4rem] shadow-sm border border-zinc-100 p-16 space-y-12 relative overflow-hidden">
            {{-- Aesthetic Gradient Blur --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>

            <div class="space-y-10 relative z-10">
                {{-- Kata Sandi Saat Ini --}}
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] ml-1">Kata Sandi Saat Ini</label>
                    <div class="relative group">
                        <input type="password" placeholder="••••••••" class="w-full px-8 py-5 bg-zinc-50 border border-zinc-100 rounded-[2rem] focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all font-bold tracking-widest text-lg text-zinc-900">
                        <button type="button" class="absolute right-6 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined">visibility</span>
                        </button>
                    </div>
                </div>

                {{-- Kata Sandi Baru --}}
                <div class="space-y-6">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] ml-1">Kata Sandi Baru</label>
                        <div class="relative group">
                            <input x-model="password" type="password" placeholder="••••••••" class="w-full px-8 py-5 bg-zinc-50 border border-zinc-100 rounded-[2rem] focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all font-bold tracking-widest text-lg text-zinc-900">
                            <button type="button" class="absolute right-6 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                        </div>
                    </div>

                    {{-- Strength Indicator --}}
                    <div class="space-y-3">
                        <div class="flex justify-between items-center px-1">
                            <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Kekuatan Password</span>
                            <span class="text-[10px] font-black uppercase tracking-widest transition-all duration-300" :class="strengthClass" x-text="strengthText"></span>
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            <div class="h-1.5 rounded-full transition-all duration-500" :class="strength >= 1 ? 'bg-red-500 shadow-sm shadow-red-500/20' : 'bg-zinc-100'"></div>
                            <div class="h-1.5 rounded-full transition-all duration-500" :class="strength >= 2 ? 'bg-orange-500 shadow-sm shadow-orange-500/20' : 'bg-zinc-100'"></div>
                            <div class="h-1.5 rounded-full transition-all duration-500" :class="strength >= 3 ? 'bg-yellow-500 shadow-sm shadow-yellow-500/20' : 'bg-zinc-100'"></div>
                            <div class="h-1.5 rounded-full transition-all duration-500" :class="strength >= 4 ? 'bg-emerald-500 shadow-sm shadow-emerald-500/20' : 'bg-zinc-100'"></div>
                        </div>
                        <div class="flex items-center gap-2 mt-2 px-1 text-zinc-400">
                            <span class="material-symbols-outlined text-sm">info</span>
                            <p class="text-[11px] font-medium italic">Minimal 8 karakter, kombinasi angka, huruf besar, dan simbol.</p>
                        </div>
                    </div>
                </div>

                {{-- Konfirmasi Kata Sandi Baru --}}
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] ml-1">Konfirmasi Kata Sandi Baru</label>
                    <div class="relative group">
                        <input x-model="confirmPassword" type="password" placeholder="••••••••" 
                               class="w-full px-8 py-5 bg-zinc-50 border rounded-[2rem] focus:ring-4 focus:ring-primary/10 outline-none transition-all font-bold tracking-widest text-lg text-zinc-900"
                               :class="passwordsMatch ? 'border-zinc-100 focus:border-primary' : 'border-red-500 focus:border-red-500 bg-red-50/10'">
                        <button type="button" class="absolute right-6 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined">visibility</span>
                        </button>
                    </div>
                    
                    {{-- Error Message --}}
                    <div x-show="!passwordsMatch" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="flex items-center gap-2 px-1 text-red-500" style="display: none;">
                        <span class="material-symbols-outlined text-sm">warning</span>
                        <p class="text-[11px] font-black uppercase tracking-widest">Konfirmasi password tidak cocok!</p>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="pt-6">
                    <button class="w-full py-6 bg-red-600 text-white rounded-[2rem] text-sm font-black uppercase tracking-[0.2em] shadow-2xl shadow-red-600/30 hover:bg-red-700 transition-all active:scale-[0.98] flex items-center justify-center gap-3 group">
                        Perbarui Kata Sandi
                        <span class="material-symbols-outlined text-xl group-hover:scale-125 transition-transform">shield</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Right Column: Side Info --}}
        <div class="space-y-10">
            {{-- Dark Security Card --}}
            <div class="bg-[#1A1110] rounded-[3.5rem] p-12 text-white relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-red-600/20 to-transparent opacity-50 group-hover:opacity-70 transition-opacity"></div>
                
                {{-- Decorative Shapes --}}
                <div class="absolute bottom-0 right-0 w-32 h-32 bg-white/5 rounded-tl-[4rem] translate-y-8 translate-x-8"></div>

                <div class="relative z-10 space-y-8">
                    <div class="w-14 h-14 rounded-2xl bg-red-600/20 border border-red-600/30 flex items-center justify-center text-red-500 shadow-xl shadow-red-600/20">
                        <span class="material-symbols-outlined text-3xl">verified</span>
                    </div>
                    <div class="space-y-4">
                        <h3 class="text-3xl font-black tracking-tight leading-tight">Keamanan Berlapis</h3>
                        <p class="text-zinc-400 text-sm leading-relaxed">Sistem kami menggunakan enkripsi standar industri untuk memastikan kredensial Anda tetap aman dan tidak dapat dibaca oleh pihak manapun.</p>
                    </div>
                    <a href="#" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-red-500 hover:text-red-400 transition-colors group/link">
                        Pelajari Kebijakan Privasi
                        <span class="material-symbols-outlined text-sm group-hover/link:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
            </div>

            {{-- Tips Card --}}
            <div class="bg-zinc-50 rounded-[3.5rem] p-12 border border-zinc-100 space-y-10 shadow-sm">
                <h4 class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em]">Tips Keamanan</h4>
                
                <div class="space-y-10">
                    <div class="flex gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-zinc-900 shrink-0">
                            <span class="material-symbols-outlined">update</span>
                        </div>
                        <div class="space-y-1">
                            <p class="font-bold text-zinc-900 text-sm">Ganti Secara Berkala</p>
                            <p class="text-xs text-zinc-500 leading-relaxed">Disarankan untuk mengganti password setiap 90 hari.</p>
                        </div>
                    </div>

                    <div class="flex gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-zinc-900 shrink-0">
                            <span class="material-symbols-outlined">no_accounts</span>
                        </div>
                        <div class="space-y-1">
                            <p class="font-bold text-zinc-900 text-sm">Hindari Informasi Publik</p>
                            <p class="text-xs text-zinc-500 leading-relaxed">Jangan gunakan tanggal lahir atau nama anggota keluarga.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer Actions --}}
    <div class="pt-10 border-t border-zinc-100 flex flex-col md:flex-row justify-between items-center gap-8">
        <div class="flex items-center gap-3 text-zinc-400 group cursor-help">
            <span class="material-symbols-outlined text-lg">help</span>
            <p class="text-xs font-medium">Butuh bantuan teknis? Hubungi <span class="text-zinc-600 font-bold group-hover:text-primary transition-colors">Help Center</span></p>
        </div>
        
        <div class="flex items-center gap-4">
            <button class="px-8 py-4 bg-zinc-100 text-zinc-600 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-zinc-200 transition-all">
                Lupa Sandi?
            </button>
            <a href="{{ route('learning-coordinator.profile') }}" class="px-8 py-4 bg-white border border-zinc-200 text-zinc-900 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:border-zinc-900 transition-all">
                Batalkan
            </a>
        </div>
    </div>
</div>
@endsection
