<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>SIG Academy - Admin Portal</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800;900&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-primary-container": "#ffffff",
                        "on-secondary-fixed": "#1b1b1b",
                        "tertiary-fixed-dim": "#bfc6dc",
                        "secondary-container": "#331d1e",
                        "on-tertiary-container": "#f1f5f9",
                        "secondary-fixed": "#e2e2e2",
                        "on-secondary": "#ffffff",
                        "primary": "#e21d24",
                        "inverse-primary": "#ffb4ac",
                        "on-primary": "#ffffff",
                        "tertiary-container": "#1e293b",
                        "on-tertiary-fixed": "#101a2d",
                        "on-surface-variant": "#475569",
                        "primary-container": "#e21d24",
                        "inverse-on-surface": "#f8fafc",
                        "on-tertiary": "#ffffff",
                        "tertiary": "#0f172a",
                        "on-tertiary-fixed-variant": "#3f4759",
                        "error-container": "#ffdad6",
                        "primary-fixed": "#ffdad6",
                        "on-surface": "#0f172a",
                        "background": "#f8f6f6",
                        "surface-container": "#ebe8e8",
                        "secondary-fixed-dim": "#c6c6c6",
                        "surface": "#f8f6f6",
                        "on-error-container": "#410002",
                        "on-background": "#0f172a",
                        "outline": "#e21d241a",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f1eeee",
                        "on-secondary-fixed-variant": "#484646",
                        "surface-dim": "#e5e2e2",
                        "surface-tint": "#e21d24",
                        "error": "#ba1a1a",
                        "surface-bright": "#ffffff",
                        "inverse-surface": "#1e293b",
                        "on-error": "#ffffff",
                        "tertiary-fixed": "#dbe2f9",
                        "on-secondary-container": "#f8f6f6",
                        "surface-container-high": "#e5e2e2",
                        "on-primary-fixed": "#410002",
                        "primary-fixed-dim": "#ffb4ac",
                        "on-primary-fixed-variant": "#93000a",
                        "surface-container-highest": "#dfdbdb",
                        "secondary": "#211112",
                        "surface-variant": "#f1eeee",
                        "outline-variant": "#e21d2433"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {},
                    "fontFamily": {
                        "headline": ["Inter", "sans-serif"],
                        "body": ["Inter", "sans-serif"],
                        "label": ["Inter", "sans-serif"]
                    }
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .material-fill {
            font-variation-settings: 'FILL' 1;
        }
    </style>
</head>

<body class="bg-background text-on-background min-h-screen flex antialiased" x-data="loginSystem()">
    <!-- Split Screen Container -->
    <main class="flex w-full min-h-screen relative overflow-hidden">
        <!-- Left Panel: Authentication -->
        <section class="w-full md:w-[45%] lg:w-[40%] bg-surface-bright flex flex-col justify-between relative z-20 shadow-2xl py-8 px-6 sm:px-12 md:py-12 md:px-16 overflow-y-auto">
            <!-- Branding Header -->
            <header class="flex items-center justify-between mb-16">
                <div class="flex items-center gap-6">
                    <img alt="SIG Logo" class="h-8 md:h-10 w-auto object-contain" src="https://i.ibb.co.com/zTjcL4DX/logo-sig-latar-putih.png" style="" />
                    <div class="w-px h-8 bg-on-surface-variant/20"></div>
                    <img alt="SIG Academy Logo" class="h-10 md:h-12 w-auto object-contain" src="https://i.ibb.co.com/VbkGbQY/logo-SIG-ACADEMY.png" style="" />
                </div>
            </header>
            <!-- Login Form Area -->
            <div class="flex-1 flex flex-col justify-center max-w-md w-full mx-auto">
                <div class="mb-10">
                    <h1 class="text-4xl md:text-5xl font-black text-on-surface tracking-tighter mb-4 leading-tight" style="">Administrative<br />Access</h1>
                    <p class="text-on-surface-variant text-lg" style="">Secure login for everyone.</p>
                </div>
                
                <div x-show="errorMessage" x-transition class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
                    <p class="text-red-600 text-sm font-bold" x-text="errorMessage"></p>
                </div>

                <form @submit.prevent="submitLogin" class="flex flex-col gap-5 w-full">
                    @csrf
                    <!-- Email Input -->
                    <div>
                        <div class="relative flex items-center bg-primary/5 rounded-xl px-4 py-3 focus-within:bg-primary/10 transition-colors">
                            <span class="material-symbols-outlined text-primary/70 mr-3" style="">mail</span>
                            <input x-model="formData.email" class="w-full bg-transparent border-none focus:ring-0 text-on-surface placeholder:text-on-surface-variant/70 font-medium text-lg p-0" id="corporate-email" name="email" placeholder="Corporate Email" required="" type="email" autofocus />
                        </div>
                    </div>
                    <!-- Password Input -->
                    <div>
                        <div class="relative flex items-center bg-primary/5 rounded-xl px-4 py-3 focus-within:bg-primary/10 transition-colors">
                            <span class="material-symbols-outlined text-primary/70 mr-3" style="">lock</span>
                            <input x-model="formData.password" class="w-full bg-transparent border-none focus:ring-0 text-on-surface placeholder:text-on-surface-variant/70 font-medium text-lg p-0" id="security-token" name="password" placeholder="Security Token / Password" required="" type="password" />
                        </div>
                    </div>
                    <!-- Forgot Link -->
                    <div class="flex justify-end mt-2 mb-6">
                        <a class="text-sm font-bold text-primary hover:text-primary-container transition-colors" href="#" style="">Forgot Access Details?</a>
                    </div>
                    <!-- Primary Action -->
                    <button :disabled="loading" class="w-full bg-primary text-on-primary font-bold text-lg py-4 rounded-xl shadow-xl shadow-primary/30 hover:shadow-2xl hover:shadow-primary/40 active:scale-[0.98] disabled:opacity-50 transition-all duration-200 tracking-wide" style="" type="submit">
                        <span x-show="!loading">Authenticate Portal</span>
                        <span x-show="loading" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Verifying...
                        </span>
                    </button>
                </form>
            </div>
            <!-- Footer Return Link -->
            <footer class="mt-16">
                <a class="inline-flex items-center gap-2 text-sm font-bold text-on-surface-variant hover:text-primary transition-colors group uppercase tracking-widest" href="{{ route('home') }}" style="">
                    <span class="material-symbols-outlined text-lg group-hover:-translate-x-1 transition-transform" style="">arrow_back</span>
                </a>
            </footer>
        </section>
        <!-- Right Panel: Visual Authority -->
        <section class="hidden md:flex flex-1 relative bg-tertiary overflow-hidden items-end p-12 lg:p-24">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&amp;w=2070&amp;auto=format&amp;fit=crop')] bg-cover bg-center"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.1),transparent)] z-10"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-tertiary via-tertiary/80 to-transparent z-10"></div>
            </div>
            <div class="relative z-20 w-full max-w-4xl text-on-primary">
                <h2 class="text-5xl lg:text-7xl font-black tracking-tighter leading-[0.95] mb-6 drop-shadow-xl">
                    Operational<br />Integrity &amp;<br />Precision.
                </h2>
                <p class="text-xl lg:text-2xl text-on-primary/80 font-medium max-w-2xl mb-16 leading-relaxed">
                    Managing the intellectual assets of South East Asia's cement giant.
                </p>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full">
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-6 lg:p-8 flex items-start gap-4 hover:bg-white/10 transition-colors cursor-default">
                        <div class="p-3 bg-primary/20 rounded-xl text-primary mt-1">
                            <span class="material-symbols-outlined material-fill text-3xl">database</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg lg:text-xl mb-1 text-white">Data Governance</h3>
                            <p class="text-sm text-white/60">Centralized control of enterprise learning telemetry and compliance metrics.</p>
                        </div>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-6 lg:p-8 flex items-start gap-4 hover:bg-white/10 transition-colors cursor-default">
                        <div class="p-3 bg-primary/20 rounded-xl text-primary mt-1">
                            <span class="material-symbols-outlined material-fill text-3xl">account_tree</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg lg:text-xl mb-1 text-white">Curriculum Oversight</h3>
                            <p class="text-sm text-white/60">Strategic alignment of technical training paths with industrial requirements.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- ROLE SELECTION MODAL --}}
    <div x-show="showRoleModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-6 overflow-hidden" 
         style="display: none;">
        <div x-show="showRoleModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-zinc-900/60 backdrop-blur-sm" 
             @click="closeAndLogout"></div>
        
        <div x-show="showRoleModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-8"
             class="relative bg-zinc-50 rounded-[3.5rem] w-full max-w-5xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="p-12 pb-6 flex justify-between items-start shrink-0">
                <div>
                    <h2 class="text-4xl font-black text-zinc-900 tracking-tight">Pilih Akses Anda</h2>
                    <p class="text-zinc-500 mt-2 text-lg font-medium">Akun Anda memiliki beberapa tanggung jawab. Silakan pilih salah satu untuk melanjutkan.</p>
                </div>
                <button @click="closeAndLogout" class="w-14 h-14 rounded-full bg-white flex items-center justify-center text-zinc-400 hover:bg-red-50 hover:text-red-500 shadow-sm transition-all">
                    <span class="material-symbols-outlined text-3xl">close</span>
                </button>
            </div>

            {{-- Added py-8 and px-12 to grid container to prevent hover clipping --}}
            <div class="px-12 pb-12 overflow-y-auto no-scrollbar flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-8">
                    <template x-for="role in roles" :key="role">
                        <button @click="selectRole(role)" 
                            class="group relative bg-white p-8 rounded-[2.5rem] border-2 border-transparent hover:border-primary shadow-sm hover:shadow-2xl hover:-translate-y-4 transition-all duration-500 text-left">
                            
                            <div class="flex items-start gap-6 relative z-10">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white shadow-lg"
                                    :class="getRoleData(role).color">
                                    <span class="material-symbols-outlined text-3xl" x-text="getRoleData(role).icon"></span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-2xl font-black text-zinc-900 mb-2 group-hover:text-primary transition-colors" x-text="getRoleData(role).title"></h3>
                                    <p class="text-zinc-500 text-sm leading-relaxed mb-6" x-text="getRoleData(role).desc"></p>
                                    
                                    <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-primary">
                                        Pilih Peran Ini
                                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        function loginSystem() {
            return {
                formData: {
                    email: '',
                    password: '',
                    _token: '{{ csrf_token() }}'
                },
                loading: false,
                errorMessage: '',
                showRoleModal: false,
                roles: [],
                
                submitLogin() {
                    this.loading = true;
                    this.errorMessage = '';
                    
                    axios.post('{{ route("admin.login.submit") }}', this.formData)
                        .then(response => {
                            if (response.data.multiple_roles) {
                                this.roles = response.data.roles;
                                this.showRoleModal = true;
                                this.loading = false;
                            } else {
                                window.location.href = response.data.redirect || '/dashboard';
                            }
                        })
                        .catch(error => {
                            this.loading = false;
                            if (error.response && error.response.data.errors) {
                                this.errorMessage = Object.values(error.response.data.errors)[0][0];
                            } else {
                                this.errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                            }
                        });
                },

                closeAndLogout() {
                    this.showRoleModal = false;
                    // Logout in background so they aren't redirected to /dashboard next time
                    axios.post('{{ route("logout") }}', { _token: '{{ csrf_token() }}' })
                        .then(() => {
                            console.log('Session cleared on modal close');
                        });
                },

                selectRole(role) {
                    axios.post('{{ route("auth.select-role.submit") }}', {
                        role: role,
                        _token: '{{ csrf_token() }}'
                    })
                    .then(response => {
                        window.location.href = response.data.redirect || '/dashboard';
                    })
                    .catch(error => {
                        alert('Terjadi kesalahan saat memilih peran.');
                    });
                },

                getRoleData(role) {
                    const data = {
                        learning_coordinator: {
                            title: 'Learning Coordinator',
                            desc: 'Kelola kebutuhan pelatihan, analisis TNA, dan pantau progres usulan.',
                            icon: 'dynamic_form',
                            color: 'bg-blue-500'
                        },
                        admin_coordinator: {
                            title: 'Admin Coordinator',
                            desc: 'Review usulan pelatihan, kelola kurasi, dan koordinasi administrasi.',
                            icon: 'verified_user',
                            color: 'bg-emerald-500'
                        },
                        learning_administrator: {
                            title: 'Learning Administrator',
                            desc: 'Manajemen user, pengaturan sistem, dan platform utama.',
                            icon: 'settings_suggest',
                            color: 'bg-purple-500'
                        },
                        sme: {
                            title: 'SME',
                            desc: 'Review konten teknis dan berikan masukan ahli pada program.',
                            icon: 'psychology',
                            color: 'bg-amber-500'
                        }
                    };
                    return data[role] || {
                        title: role,
                        desc: 'Akses operasional dashboard.',
                        icon: 'dashboard',
                        color: 'bg-zinc-500'
                    };
                }
            }
        }
    </script>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</body>

</html>