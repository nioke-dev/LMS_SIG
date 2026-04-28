<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Dashboard Peserta - SIG Academy</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800;900&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .editorial-shadow {
            box-shadow: 0 20px 25px -5px rgba(226, 29, 36, 0.1), 0 8px 10px -6px rgba(226, 29, 36, 0.1);
        }
    </style>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-secondary": "#ffffff",
                        "inverse-surface": "#1e293b",
                        "surface-variant": "#f1eeee",
                        "error": "#ba1a1a",
                        "secondary": "#211112",
                        "primary-fixed-dim": "#ffb4ac",
                        "surface": "#f8f6f6",
                        "on-tertiary": "#ffffff",
                        "surface-container-highest": "#dfdbdb",
                        "inverse-primary": "#ffb4ac",
                        "surface-container-lowest": "#ffffff",
                        "on-secondary-container": "#f8f6f6",
                        "surface-container-high": "#e5e2e2",
                        "on-error": "#ffffff",
                        "surface-container-low": "#f1eeee",
                        "surface-container": "#ebe8e8",
                        "on-surface": "#0f172a",
                        "primary": "#e21d24",
                        "on-tertiary-container": "#f1f5f9",
                        "on-background": "#0f172a",
                        "secondary-fixed": "#e2e2e2",
                        "surface-tint": "#e21d24",
                        "on-primary-container": "#ffffff",
                        "tertiary-fixed-dim": "#bfc6dc",
                        "error-container": "#ffdad6",
                        "on-tertiary-fixed": "#101a2d",
                        "primary-fixed": "#ffdad6",
                        "tertiary-fixed": "#dbe2f9",
                        "inverse-on-surface": "#f8fafc",
                        "secondary-container": "#331d1e",
                        "tertiary": "#0f172a",
                        "on-primary": "#ffffff",
                        "secondary-fixed-dim": "#c6c6c6",
                        "primary-container": "#e21d24",
                        "outline-variant": "#e21d2433",
                        "outline": "#e21d241a",
                        "on-primary-fixed-variant": "#93000a",
                        "on-tertiary-fixed-variant": "#3f4759",
                        "on-surface-variant": "#475569",
                        "background": "#f8f6f6",
                        "on-primary-fixed": "#410002",
                        "surface-bright": "#ffffff",
                        "on-secondary-fixed": "#1b1b1b",
                        "surface-dim": "#e5e2e2",
                        "on-error-container": "#410002",
                        "on-secondary-fixed-variant": "#484646",
                        "tertiary-container": "#1e293b"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
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
</head>

<body class="bg-surface text-on-surface flex min-h-screen">
    <!-- SideNavBar (Shared Component) -->
    <aside class="hidden md:flex flex-col h-screen w-64 border-r-0 bg-white dark:bg-zinc-950 font-inter tracking-tight py-6 sticky top-0">
        <div class="px-6 mt-4 mb-10">
            <h1 class="text-2xl xl:text-3xl font-black tracking-tighter uppercase">
                <span class="text-surface-inverse dark:text-white">SIG</span>
                <span class="text-primary">Academy</span>
            </h1>
            <p class="text-[10px] uppercase tracking-[0.2em] text-zinc-400 font-bold mt-1.5 border-t border-red-600/10 pt-1.5 w-max">Pusat Pembelajaran</p>
        </div>
        <nav class="flex-1 px-4 space-y-1">
            <!-- Active Tab: Dashboard -->
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 dark:text-red-500 font-bold border-r-4 border-red-600 dark:border-red-500 bg-red-50/50 dark:bg-red-950/20 duration-200 ease-in-out" href="#">
                <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors duration-200 ease-in-out" href="#">
                <span class="material-symbols-outlined" data-icon="menu_book">menu_book</span>
                <span>Catalog</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors duration-200 ease-in-out" href="#">
                <span class="material-symbols-outlined" data-icon="school">school</span>
                <span>My Classes</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors duration-200 ease-in-out" href="#">
                <span class="material-symbols-outlined" data-icon="military_tech">military_tech</span>
                <span>Certificates</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors duration-200 ease-in-out" href="#">
                <span class="material-symbols-outlined" data-icon="settings">settings</span>
                <span>Settings</span>
            </a>
        </nav>
        <div class="px-6 pt-6 mt-6 border-t border-red-600/10">
            <button class="w-full bg-primary text-white font-bold py-3 rounded-xl shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                Upgrade to Pro
            </button>
        </div>
    </aside>
    <main class="flex-1 flex flex-col min-w-0">
        <!-- TopNavBar (Shared Component) -->
        <header class="flex justify-between items-center w-full px-8 h-16 sticky top-0 z-40 bg-white/90 dark:bg-zinc-950/90 backdrop-blur-md border-b border-red-600/10 shadow-sm">
            <div class="flex items-center gap-4 flex-1">
                <div class="relative w-full max-w-md">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-sm" data-icon="search">search</span>
                    <input class="w-full bg-surface-container-low border-none rounded-full py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-primary/20" placeholder="Search courses, certificates..." type="text" />
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex gap-4">
                    <button class="text-zinc-600 dark:text-zinc-400 hover:text-red-600 transition-all">
                        <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
                    </button>
                    <button class="text-zinc-600 dark:text-zinc-400 hover:text-red-600 transition-all">
                        <span class="material-symbols-outlined" data-icon="help">help</span>
                    </button>
                </div>
                <div class="h-8 w-[1px] bg-red-600/10"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-black text-on-surface">{{ auth()->user()->name ?? 'Budi Santoso' }}</p>
                        <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-wider">{{ auth()->check() ? ucfirst(auth()->user()->role) : 'Learner' }}</p>
                    </div>
                    <!-- Logout Dropdown Hack for Thesis Demo -->
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="w-10 h-10 rounded-full border-2 border-primary/20 object-cover overflow-hidden hover:border-primary cursor-pointer transition-all" title="Logout">
                            <img alt="Learner Profile" class="w-full h-full object-cover" data-alt="portrait of a professional young man" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAhQJqEKsRyzLq-7AAr_aIeTWVWR9XrntX_oZjrtHA1UV8z77trUYDVxs0o5h3HfR8VrijGmHMZIGZsi0Z2Bht4pWt16RRMtOTMcBZoRw4HQeCrIQ5COtFtvgLNavQD27akvbwknxkQ_kLZsej4K4mHfwhGVhZKT7qSA4jgM84Kz1ihpTqRC62_bQRy4Tzp6-AMuy514je6MwXd923SH2apKE1UOYGnicruakNp_Varsogdjf6Dblk-s2ba53ZnVme4L39MQSM1ebng" />
                        </button>
                    </form>
                </div>
            </div>
        </header>
        <!-- Content Area -->
        <div class="p-8 max-w-7xl mx-auto w-full space-y-12">
            <!-- Hero Welcome Section -->
            <section class="relative overflow-hidden rounded-[3rem] bg-secondary-container p-12 text-white">
                <div class="absolute top-0 right-0 w-1/2 h-full opacity-20 pointer-events-none">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(226,29,36,0.4),transparent)]"></div>
                </div>
                <div class="relative z-10 grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <span class="bg-primary/20 text-primary-fixed-dim px-4 py-1 rounded-full text-xs font-black tracking-widest uppercase mb-6 inline-block">Learning Overview</span>
                        <h2 class="text-[4.5rem] leading-[0.9] font-black tracking-tighter mb-4">Selamat Datang kembali, {{ auth()->check() ? explode(' ', auth()->user()->name)[0] : 'Budi' }}</h2>
                        <p class="text-zinc-400 text-lg max-w-md mb-8">Anda telah menyelesaikan 85% dari target belajar bulan ini. Pertahankan semangat Anda!</p>
                        <div class="flex gap-8">
                            <div class="bg-white/5 backdrop-blur-md p-6 rounded-3xl border border-white/10 min-w-[140px]">
                                <p class="text-4xl font-black text-primary mb-1">2</p>
                                <p class="text-xs font-bold uppercase tracking-widest text-zinc-400">Kelas Diikuti</p>
                            </div>
                            <div class="bg-white/5 backdrop-blur-md p-6 rounded-3xl border border-white/10 min-w-[140px]">
                                <p class="text-4xl font-black text-white mb-1">1</p>
                                <p class="text-xs font-bold uppercase tracking-widest text-zinc-400">Sertifikat</p>
                            </div>
                        </div>
                    </div>
                    <div class="hidden lg:block relative h-64">
                        <!-- Floating Glass Cards Decoration -->
                        <div class="absolute top-0 right-10 bg-white/10 backdrop-blur-xl p-6 rounded-3xl border border-white/20 shadow-2xl transform rotate-3">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white" data-icon="bolt" data-weight="fill" style="font-variation-settings: 'FILL' 1;">bolt</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold">Lanjutkan Belajar</p>
                                    <p class="text-[10px] text-zinc-400 uppercase">Semen Gresik Facility</p>
                                </div>
                            </div>
                        </div>
                        <div class="absolute bottom-4 left-20 bg-primary backdrop-blur-xl p-6 rounded-3xl border border-white/20 shadow-2xl transform -rotate-6">
                            <p class="text-2xl font-black text-white">85%</p>
                            <p class="text-[10px] text-red-100 uppercase tracking-widest">Monthly Goal</p>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Seksi 'Kelas Aktif' -->
            <section>
                <div class="flex justify-between items-end mb-8 px-2">
                    <div>
                        <span class="text-primary font-black uppercase tracking-[0.2em] text-[10px]">In Progress</span>
                        <h3 class="text-4xl font-black tracking-tighter">Kelas Aktif</h3>
                    </div>
                    <a class="text-sm font-bold text-zinc-500 hover:text-primary transition-colors flex items-center gap-2" href="#">
                        Semua Kelas <span class="material-symbols-outlined text-sm" data-icon="arrow_forward">arrow_forward</span>
                    </a>
                </div>
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Course Card 1 -->
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-primary/5 group hover:shadow-xl transition-all duration-300">
                        <div class="flex flex-col h-full">
                            <div class="flex justify-between items-start mb-6">
                                <div class="bg-zinc-100 p-4 rounded-2xl">
                                    <span class="material-symbols-outlined text-3xl text-primary" data-icon="precision_manufacturing">precision_manufacturing</span>
                                </div>
                                <span class="bg-primary/10 text-primary text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Technical</span>
                            </div>
                            <h4 class="text-2xl font-black tracking-tight mb-2">Optimasi Produksi Semen Fase Lanjut</h4>
                            <p class="text-zinc-500 text-sm mb-8">Modul 4: Integrasi Automasi PLC dalam Kiln Operasi</p>
                            <div class="mt-auto">
                                <div class="flex justify-between items-end mb-2">
                                    <span class="text-xs font-bold text-zinc-400">PROGRESS</span>
                                    <span class="text-lg font-black text-on-surface">65%</span>
                                </div>
                                <div class="h-3 w-full bg-zinc-100 rounded-full overflow-hidden mb-8">
                                    <div class="h-full bg-primary w-[65%] rounded-full"></div>
                                </div>
                                <button class="w-full py-4 bg-zinc-950 text-white font-bold rounded-2xl group-hover:bg-primary transition-colors duration-300 flex items-center justify-center gap-2">
                                    Lanjutkan <span class="material-symbols-outlined text-sm" data-icon="play_arrow" data-weight="fill" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Course Card 2 -->
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-primary/5 group hover:shadow-xl transition-all duration-300">
                        <div class="flex flex-col h-full">
                            <div class="flex justify-between items-start mb-6">
                                <div class="bg-zinc-100 p-4 rounded-2xl">
                                    <span class="material-symbols-outlined text-3xl text-primary" data-icon="security">security</span>
                                </div>
                                <span class="bg-primary/10 text-primary text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Compliance</span>
                            </div>
                            <h4 class="text-2xl font-black tracking-tight mb-2">Standar K3 Industri Manufaktur Berat</h4>
                            <p class="text-zinc-500 text-sm mb-8">Modul 1: Identifikasi Bahaya Lingkungan Kerja</p>
                            <div class="mt-auto">
                                <div class="flex justify-between items-end mb-2">
                                    <span class="text-xs font-bold text-zinc-400">PROGRESS</span>
                                    <span class="text-lg font-black text-on-surface">12%</span>
                                </div>
                                <div class="h-3 w-full bg-zinc-100 rounded-full overflow-hidden mb-8">
                                    <div class="h-full bg-primary w-[12%] rounded-full"></div>
                                </div>
                                <button class="w-full py-4 bg-zinc-950 text-white font-bold rounded-2xl group-hover:bg-primary transition-colors duration-300 flex items-center justify-center gap-2">
                                    Lanjutkan <span class="material-symbols-outlined text-sm" data-icon="play_arrow" data-weight="fill" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Seksi 'Rekomendasi Kelas' -->
            <section class="pb-12">
                <div class="flex flex-col mb-8 px-2">
                    <span class="text-primary font-black uppercase tracking-[0.2em] text-[10px]">Curated for You</span>
                    <h3 class="text-4xl font-black tracking-tighter">Rekomendasi Kelas</h3>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Recommend Card 1 -->
                    <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500 border border-transparent hover:border-primary/10 flex flex-col">
                        <div class="h-56 relative overflow-hidden">
                            <img alt="Industrial Production" class="w-full h-full object-cover transition-transform duration-700 hover:scale-110" data-alt="dramatic interior shot of a modern clean industrial facility with specialized machinery and warm orange safety lights" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC_kSKRG7gLe4MEWqArF-4D-P4RFPjjs0fevQkkdjoL0xFoH-uYAaRBFVAVUwbqMUR-0OIriURS3rsch3k81dCkOXZ4mqmWLg67QsLSwApFWmUalcor2zlqctYtneQPHf7gC9JK8PO-wqXEqofgQdHTdpw-Aw606qu2isZEJLbE96TqepyJZ53OdssHEXyqimHlY14qS1sDckBLu8Vq7ZVVDE62bjMTTrOqsaV7J3GpqmICJWcYoQcOUP17VmhSddfkYde9754xVAoV" />
                            <div class="absolute top-4 left-4">
                                <span class="bg-black/60 backdrop-blur-md text-white text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-widest">Mechanical</span>
                            </div>
                        </div>
                        <div class="p-8 flex flex-col flex-1">
                            <h4 class="text-xl font-extrabold tracking-tight mb-4">Pemeliharaan Preventif Mesin Giling</h4>
                            <div class="flex items-center gap-2 mb-8">
                                <span class="material-symbols-outlined text-zinc-400 text-lg" data-icon="timer">timer</span>
                                <span class="text-sm font-bold text-zinc-500 uppercase">12 Jam</span>
                                <span class="mx-2 text-zinc-300">•</span>
                                <span class="text-sm font-bold text-primary tracking-tighter">PRO</span>
                            </div>
                            <div class="mt-auto flex justify-between items-center pt-6 border-t border-zinc-100">
                                <span class="text-xl font-black text-on-surface">IDR 450.000</span>
                                <button class="w-12 h-12 bg-zinc-100 rounded-xl flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                                    <span class="material-symbols-outlined" data-icon="add">add</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Recommend Card 2 -->
                    <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500 border border-transparent hover:border-primary/10 flex flex-col">
                        <div class="h-56 relative overflow-hidden">
                            <img alt="Automation Lab" class="w-full h-full object-cover transition-transform duration-700 hover:scale-110" data-alt="close-up of electronic components and glowing lights on a high-tech automation circuit board in a dark laboratory" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBh_gVNNwQofGx1QWZ05EtNOWHLaUTx_mA6rCMCJHTUtl9GCefm7Qg5OfaUNbXBRzXD2pzGf_sgZJ0GIKnkJNrrauFR6kTA4CdbO31KqzCkhIOzen8Po1ZNV8_9zNjYZmRNv2h01txddVQbzKnfW7JB7Nk010lBtIleP4Vv3tLGIeXMbHeqlI0J3PQThLpvuUm7pFqUyZKLoBthRlO5dKIlpXx6YVm7Q3MtQQu0eLrq5S74ejpBUJ4HAS9TkAwllrNCG8z6ETj5LirM" />
                            <div class="absolute top-4 left-4">
                                <span class="bg-black/60 backdrop-blur-md text-white text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-widest">Automation</span>
                            </div>
                        </div>
                        <div class="p-8 flex flex-col flex-1">
                            <h4 class="text-xl font-extrabold tracking-tight mb-4">Dasar-Dasar PLC Scada untuk Industri</h4>
                            <div class="flex items-center gap-2 mb-8">
                                <span class="material-symbols-outlined text-zinc-400 text-lg" data-icon="timer">timer</span>
                                <span class="text-sm font-bold text-zinc-500 uppercase">18 Jam</span>
                                <span class="mx-2 text-zinc-300">•</span>
                                <span class="text-sm font-bold text-primary tracking-tighter">FREE</span>
                            </div>
                            <div class="mt-auto flex justify-between items-center pt-6 border-t border-zinc-100">
                                <span class="text-xl font-black text-on-surface">Gratis</span>
                                <button class="w-12 h-12 bg-zinc-100 rounded-xl flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                                    <span class="material-symbols-outlined" data-icon="add">add</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Recommend Card 3 -->
                    <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500 border border-transparent hover:border-primary/10 flex flex-col">
                        <div class="h-56 relative overflow-hidden">
                            <img alt="Sustainability" class="w-full h-full object-cover transition-transform duration-700 hover:scale-110" data-alt="aerial view of large white wind turbines and solar panels in a green field under a clear blue sky" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCJqaxo6QdIf1LLj32NaZ4UZaxREjbL29JjZXQj3d1B1LAjmek9X7Ff2QbdpZ2pFAQPqnNIwLJ9dTo87DQR80tzloqWIGbdDMLdg3Wu88KJ9K51V-gEkm3rcTjxCs00g8Y3skAxdW_VbLmb10GKu2XnVU0kNIK-OFeK8RlVwRaTKIgzZb2j_oof78VD303TQZpPi9jNev7qiarV8INaaTJWbyE0eINhbOzYw15XaI4dy5uwZ-ZrhlpeSxJpwU-geKunz_WTx8Njaqem" />
                            <div class="absolute top-4 left-4">
                                <span class="bg-black/60 backdrop-blur-md text-white text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-widest">Sustainability</span>
                            </div>
                        </div>
                        <div class="p-8 flex flex-col flex-1">
                            <h4 class="text-xl font-extrabold tracking-tight mb-4">Efisiensi Energi dalam Rantai Pasok</h4>
                            <div class="flex items-center gap-2 mb-8">
                                <span class="material-symbols-outlined text-zinc-400 text-lg" data-icon="timer">timer</span>
                                <span class="text-sm font-bold text-zinc-500 uppercase">8 Jam</span>
                                <span class="mx-2 text-zinc-300">•</span>
                                <span class="text-sm font-bold text-primary tracking-tighter">NEW</span>
                            </div>
                            <div class="mt-auto flex justify-between items-center pt-6 border-t border-zinc-100">
                                <span class="text-xl font-black text-on-surface">IDR 320.000</span>
                                <button class="w-12 h-12 bg-zinc-100 rounded-xl flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                                    <span class="material-symbols-outlined" data-icon="add">add</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <!-- BottomNavBar for Mobile (Shared Component Logic) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md flex justify-around items-center h-16 z-50 shadow-2xl">
        <a class="flex flex-col items-center text-primary font-bold" href="#">
            <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
            <span class="text-[10px]">Home</span>
        </a>
        <a class="flex flex-col items-center text-zinc-400" href="#">
            <span class="material-symbols-outlined" data-icon="menu_book">menu_book</span>
            <span class="text-[10px]">Catalog</span>
        </a>
        <a class="flex flex-col items-center text-zinc-400" href="#">
            <span class="material-symbols-outlined" data-icon="school">school</span>
            <span class="text-[10px]">Classes</span>
        </a>
        <a class="flex flex-col items-center text-zinc-400" href="#">
            <span class="material-symbols-outlined" data-icon="settings">settings</span>
            <span class="text-[10px]">Settings</span>
        </a>
    </nav>
</body>

</html>