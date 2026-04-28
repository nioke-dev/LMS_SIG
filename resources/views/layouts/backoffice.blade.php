{{-- ============================================================
     MASTER LAYOUT — Back-Office
     Shared layout for ALL back-office management roles.

     Sections:
     - @section('title')           : Page title
     - @section('sidebar-nav')     : Role-specific sidebar menu
     - @section('page-title')      : Breadcrumb heading
     - @section('content')         : Main page content
     - @section('scripts')         : Page-specific JS (optional)
     ============================================================ --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>SIG Academy | @yield('title', 'Back-Office')</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    {{-- AlpineJS --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Axios --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Litepicker --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css">
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

    {{-- ApexCharts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    {{-- Global Alert Helper --}}
    <script>
        const Alert = {
            confirm: (title, text, icon = 'warning') => {
                return Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: '#e21d24',
                    cancelButtonColor: '#f4f4f5',
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-[2.5rem]',
                        confirmButton: 'rounded-xl px-10 py-4 font-bold uppercase tracking-tight',
                        cancelButton: 'rounded-xl px-10 py-4 font-bold uppercase tracking-tight text-zinc-600'
                    }
                });
            },
            success: (title, text) => {
                return Swal.fire({
                    title: title,
                    text: text,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-[2.5rem]' }
                });
            },
            error: (title, text) => {
                return Swal.fire({
                    title: title,
                    text: text,
                    icon: 'error',
                    confirmButtonColor: '#e21d24',
                    customClass: { popup: 'rounded-[2.5rem]' }
                });
            },
            warning: (title, text) => {
                return Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    confirmButtonColor: '#e21d24',
                    customClass: { popup: 'rounded-[2.5rem]' }
                });
            }
        };
    </script>

    {{-- Google Fonts & Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    {{-- Design System Tokens --}}
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-primary-fixed-variant": "#93000a",
                        "tertiary-container": "#1e293b",
                        "surface-dim": "#e5e2e2",
                        "error": "#ba1a1a",
                        "surface-container-lowest": "#ffffff",
                        "secondary-container": "#331d1e",
                        "on-secondary": "#ffffff",
                        "surface-bright": "#ffffff",
                        "on-error": "#ffffff",
                        "on-secondary-fixed": "#1b1b1b",
                        "on-surface": "#0f172a",
                        "primary": "#e21d24",
                        "background": "#f8f6f6",
                        "tertiary": "#0f172a",
                        "surface-container-high": "#e5e2e2",
                        "on-tertiary-fixed": "#101a2d",
                        "on-secondary-fixed-variant": "#484646",
                        "on-primary-fixed": "#410002",
                        "surface-container": "#ebe8e8",
                        "primary-container": "#e21d24",
                        "tertiary-fixed-dim": "#bfc6dc",
                        "on-tertiary-container": "#f1f5f9",
                        "on-primary": "#ffffff",
                        "surface-variant": "#f1eeee",
                        "surface-tint": "#e21d24",
                        "surface-container-highest": "#dfdbdb",
                        "on-surface-variant": "#475569",
                        "secondary": "#211112",
                        "outline-variant": "#e21d2433",
                        "primary-fixed": "#ffdad6",
                        "on-error-container": "#410002",
                        "on-background": "#0f172a",
                        "on-tertiary-fixed-variant": "#3f4759",
                        "on-secondary-container": "#f8f6f6",
                        "inverse-surface": "#1e293b",
                        "secondary-fixed-dim": "#c6c6c6",
                        "tertiary-fixed": "#dbe2f9",
                        "outline": "#e21d241a",
                        "inverse-primary": "#ffb4ac",
                        "surface-container-low": "#f1eeee",
                        "on-primary-container": "#ffffff",
                        "inverse-on-surface": "#f8fafc",
                        "error-container": "#ffdad6",
                        "surface": "#f8f6f6",
                        "secondary-fixed": "#e2e2e2",
                        "primary-fixed-dim": "#ffb4ac",
                        "on-tertiary": "#ffffff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.5rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "3xl": "1.5rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Inter"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                },
            },
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .modal-overlay { backdrop-filter: blur(4px); }
    </style>

    {{-- Local Tippy.js (Offline Ready) --}}
    <link rel="stylesheet" href="{{ asset('vendor/tippy/tippy.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/tippy/scale-extreme.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/tippy/custom-tippy.css') }}">
    <script src="{{ asset('vendor/tippy/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/tippy/tippy-bundle.umd.js') }}"></script>
    <script>
        console.log('Tippy loaded:', typeof tippy !== 'undefined' ? 'YES' : 'NO');
    </script>

    @stack('styles')
</head><body class="bg-background text-on-surface flex min-h-screen relative" x-data="{ sidebarOpen: true, showLogoutModal: false, roleModalOpen: false, emailModalOpen: false }" :class="showLogoutModal || roleModalOpen || emailModalOpen ? 'overflow-hidden' : ''">

    {{-- Global Overlays --}}
    <div class="fixed inset-0 z-[100] pointer-events-none">
        {{-- Logout Confirmation Modal --}}
        <div x-show="showLogoutModal"
             style="display: none;"
             class="absolute inset-0 bg-zinc-900/40 pointer-events-auto modal-overlay flex items-center justify-center p-4">
            <div x-show="showLogoutModal"
                 @click.outside="showLogoutModal = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
                <div class="p-8 text-center">
                    <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-outlined text-red-500 text-4xl">logout</span>
                    </div>
                    <h3 class="text-xl font-black text-on-surface mb-2 uppercase tracking-tight">Konfirmasi Keluar</h3>
                    <p class="text-on-surface-variant font-medium">Apakah Anda yakin ingin mengakhiri sesi?</p>
                </div>
                <div class="grid grid-cols-2 gap-4 p-8 pt-0">
                    <button @click="showLogoutModal = false" class="py-3.5 rounded-xl border-2 border-zinc-100 font-bold text-zinc-600 hover:bg-zinc-50 transition-all active:scale-95">
                        Batal
                    </button>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full py-3.5 rounded-xl bg-primary text-white font-bold shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all active:scale-95">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Role Switcher Modal --}}
        <div x-show="roleModalOpen" 
             style="display: none;"
             class="absolute inset-0 bg-zinc-900/40 pointer-events-auto modal-overlay flex items-center justify-center p-6">
            
            <div @click.outside="roleModalOpen = false" 
                 class="bg-white rounded-[3rem] w-full max-w-4xl overflow-hidden shadow-2xl transition-all"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                
                <div class="p-12 pb-8 flex justify-between items-start">
                    <div>
                        <h2 class="text-4xl font-black text-zinc-900 tracking-tight">Pilih Akses Anda</h2>
                        <p class="text-zinc-500 mt-2 text-lg">Sesuaikan tampilan dan fitur berdasarkan tanggung jawab peran Anda.</p>
                    </div>
                    <button @click="roleModalOpen = false" class="w-12 h-12 rounded-full bg-zinc-50 flex items-center justify-center text-zinc-400 hover:bg-zinc-100 hover:text-zinc-600 transition-all">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="px-12 pb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-zinc-50 rounded-[2.5rem] p-8 border border-zinc-100 group hover:border-primary/20 hover:bg-white hover:shadow-xl transition-all">
                        <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-red-500 shadow-sm mb-8 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">person</span>
                        </div>
                        <h3 class="text-2xl font-black text-zinc-900 mb-4">Employee</h3>
                        <p class="text-zinc-500 text-sm leading-relaxed mb-10">Akses sebagai pembelajar untuk melihat materi, tugas, dan progres pelatihan pribadi.</p>
                        <button class="w-full py-4 bg-white border border-zinc-200 rounded-2xl text-xs font-black uppercase tracking-widest text-red-500 hover:bg-red-50 hover:border-red-200 transition-all">
                            Gunakan Peran Ini
                        </button>
                    </div>

                    <div class="relative bg-white rounded-[2.5rem] p-8 border-2 border-red-500 shadow-xl shadow-red-500/5">
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-red-500 text-white px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">
                            Sedang Digunakan
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-red-500 flex items-center justify-center text-white shadow-lg shadow-red-500/20 mb-8">
                            <span class="material-symbols-outlined text-3xl">manage_accounts</span>
                        </div>
                        <h3 class="text-2xl font-black text-zinc-900 mb-4">Learning Coordinator</h3>
                        <p class="text-zinc-500 text-sm leading-relaxed mb-10">Akses kontrol penuh untuk mengelola kurikulum, memantau tim, dan validasi sertifikasi.</p>
                        <button class="w-full py-4 bg-red-50 rounded-2xl text-xs font-black uppercase tracking-widest text-red-500 cursor-default">
                            Peran Aktif
                        </button>
                    </div>

                    <div class="bg-zinc-50 rounded-[2.5rem] p-8 border border-zinc-100 group hover:border-primary/20 hover:bg-white hover:shadow-xl transition-all">
                        <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-red-500 shadow-sm mb-8 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">edit_note</span>
                        </div>
                        <h3 class="text-2xl font-black text-zinc-900 mb-4">SME</h3>
                        <p class="text-zinc-500 text-sm leading-relaxed mb-10">Akses sebagai pembuat konten. Kelola modul teknis dan tinjau standar materi industri.</p>
                        <button class="w-full py-4 bg-white border border-zinc-200 rounded-2xl text-xs font-black uppercase tracking-widest text-red-500 hover:bg-red-50 hover:border-red-200 transition-all">
                            Gunakan Peran Ini
                        </button>
                    </div>
                </div>

                <div class="px-12 py-8 bg-zinc-50 flex justify-end">
                    <button @click="roleModalOpen = false" class="text-sm font-bold text-zinc-400 hover:text-zinc-900 transition-all">Batal</button>
                </div>
            </div>
        </div>

        {{-- Update Email Modal --}}
        <div x-show="emailModalOpen" 
             style="display: none;"
             class="absolute inset-0 bg-zinc-900/40 pointer-events-auto modal-overlay flex items-center justify-center p-6">
            
            <div @click.outside="emailModalOpen = false" 
                 class="bg-white rounded-[3.5rem] w-full max-w-xl overflow-hidden shadow-2xl transition-all"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-8">
                
                <div class="p-12">
                    <div class="flex justify-between items-start mb-10">
                        <div class="w-16 h-16 rounded-3xl bg-primary/5 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-3xl">mail</span>
                        </div>
                        <button @click="emailModalOpen = false" class="w-10 h-10 rounded-full bg-zinc-50 flex items-center justify-center text-zinc-400 hover:bg-zinc-100 transition-all">
                            <span class="material-symbols-outlined text-xl">close</span>
                        </button>
                    </div>

                    <h3 class="text-3xl font-black text-zinc-900 tracking-tight mb-4">Perbarui Alamat Email</h3>
                    <p class="text-zinc-500 mb-10 leading-relaxed text-sm">Masukkan alamat email baru Anda untuk menerima notifikasi sistem dan laporan kegiatan belajar.</p>

                    <div class="space-y-6">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest ml-1">Email Saat Ini</p>
                            <div class="px-6 py-4 bg-zinc-50 border border-zinc-100 rounded-2xl text-zinc-400 font-bold text-sm">
                                {{ Auth::user()->email ?? 'user@sig.co.id' }}
                            </div>
                        </div>

                        <div class="space-y-3 text-left">
                            <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest ml-1">Email Baru</label>
                            <input type="email" placeholder="Masukkan email baru..." class="w-full px-6 py-4 bg-white border border-zinc-200 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all font-bold text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-12">
                        <button @click="emailModalOpen = false" class="py-4 rounded-2xl border border-zinc-200 text-sm font-bold text-zinc-400 hover:bg-zinc-50 transition-all">Batal</button>
                        <button @click="emailModalOpen = false" class="py-4 rounded-2xl bg-primary text-white text-sm font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:shadow-primary/40 transition-all active:scale-95">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== SIDEBAR ========== --}}
    <x-backoffice.sidebar>
        @yield('sidebar-nav')
    </x-backoffice.sidebar>

    {{-- ========== MAIN WORKSPACE ========== --}}
    <main class="flex-1 flex flex-col min-w-0 bg-background overflow-x-hidden transition-all duration-300" :class="sidebarOpen ? 'ml-72' : 'ml-20'">

        {{-- Navbar --}}
        <x-backoffice.navbar />

        {{-- Content Canvas --}}
        <div class="pt-28 p-10 space-y-8 max-w-[1400px] mx-auto w-full">

            {{-- Page Content --}}
            @yield('content')
        </div>
    </main>

    {{-- Page-specific scripts --}}
    @yield('scripts')

    {{-- ========== GLOBAL TOAST NOTIFICATION ========== --}}
    <div x-data="toastSystem()" @toast.window="addToast($event.detail)" class="fixed top-6 right-6 z-[200] flex flex-col gap-3 pointer-events-none" style="max-width: 400px;">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-8"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 translate-x-8"
                 class="pointer-events-auto bg-white rounded-2xl shadow-2xl border border-zinc-100 overflow-hidden">
                <div class="flex items-start gap-3 p-4">
                    <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center"
                         :class="toast.type === 'success' ? 'bg-green-50' : toast.type === 'warning' ? 'bg-orange-50' : toast.type === 'error' ? 'bg-red-50' : 'bg-blue-50'">
                        <span class="material-symbols-outlined text-xl"
                              :class="toast.type === 'success' ? 'text-green-500' : toast.type === 'warning' ? 'text-orange-500' : toast.type === 'error' ? 'text-red-500' : 'text-blue-500'"
                              x-text="toast.type === 'success' ? 'check_circle' : toast.type === 'warning' ? 'warning' : toast.type === 'error' ? 'error' : 'info'"
                              style="font-variation-settings: 'FILL' 1;"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-black text-zinc-900 tracking-tight" x-text="toast.title"></p>
                        <p class="text-xs text-zinc-500 mt-0.5 leading-relaxed" x-text="toast.message"></p>
                    </div>
                    <button @click="removeToast(toast.id)" class="shrink-0 text-zinc-300 hover:text-zinc-500 transition-colors">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                </div>
                <div class="h-1 w-full bg-zinc-50">
                    <div class="h-full transition-all duration-100 ease-linear rounded-full"
                         :class="toast.type === 'success' ? 'bg-green-400' : toast.type === 'warning' ? 'bg-orange-400' : toast.type === 'error' ? 'bg-red-400' : 'bg-blue-400'"
                         :style="`width: ${toast.progress}%`"></div>
                </div>
            </div>
        </template>
    </div>
    <script>
        function toastSystem() {
            return {
                toasts: [],
                addToast(detail) {
                    const id = Date.now();
                    const toast = { id, ...detail, visible: true, progress: 100 };
                    this.toasts.push(toast);
                    const duration = 4000;
                    const step = 50;
                    const decrement = (step / duration) * 100;
                    const interval = setInterval(() => {
                        const t = this.toasts.find(t => t.id === id);
                        if (!t) { clearInterval(interval); return; }
                        t.progress = Math.max(0, t.progress - decrement);
                        if (t.progress <= 0) { clearInterval(interval); this.removeToast(id); }
                    }, step);
                },
                removeToast(id) {
                    const t = this.toasts.find(t => t.id === id);
                    if (t) t.visible = false;
                    setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== id); }, 300);
                }
            };
        }
    </script>
</body>
</html>
