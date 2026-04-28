<!DOCTYPE html>

<html class="light scroll-smooth" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>&lt;span style="color: #000000;"&gt;SIG&lt;/span&gt; &lt;span style="color: #e21d24;"&gt;Academy&lt;/span&gt; - Pusat Pembelajaran Industri Semen &amp; Konstruksi</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#e21d24",
                        "background-light": "#f8f6f6",
                        "background-dark": "#211112",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        },
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            },
                        }
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

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        header.scrolled {
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            border-color: rgba(226, 29, 36, 0.2);
        }

        .hero-parallax {
            transition: transform 0.2s ease-out;
        }

        .play-button-ripple::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: white;
            border-radius: 50%;
            animation: ripple 2s infinite;
            opacity: 0;
            z-index: -1;
        }

        @keyframes ripple {
            0% {
                transform: scale(1);
                opacity: 0.4;
            }

            100% {
                transform: scale(2);
                opacity: 0;
            }
        }
    </style>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display transition-colors">
    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-50 w-full border-b border-transparent bg-background-light/80 backdrop-blur-md dark:bg-background-dark/80 transition-all duration-300" id="main-header">
        <div class="container mx-auto flex h-16 items-center justify-between px-4 lg:px-10">
            <div class="flex items-center gap-8">
                <div class="flex items-center gap-4">
                    <img alt="SIG Logo" class="h-8 w-auto" src="https://lh3.googleusercontent.com/aida/ADBb0ug4uVZk-JZLTgVAd_lj0IsvQAyEuWGSelzoVgm3RDq3UYHR15iAf7CBoVcc9cB11kfbJ9GXbQjr-S5M4XGVpjHLu-iy2GTfoBOoTXYi6ToEm6aMZgQtwTHTh3o8v7URI0rFMdHkssz32ZNSkuXA40OaNkqxkVTCpIiiaPfmB1P0KdRJ9Yb3_ylDcoVcxaJjJfq0O74fUffV7mInUZAfc_HruzlG2rAmy3C9IB7VzBeB4Ap9uJovnZglzvEAN7vEyiLHJ9eC0SENsgM" />
                    <div class="h-6 w-px bg-slate-300 dark:bg-slate-700"></div>
                    <img alt="SIG Academy Logo" class="h-8 w-auto" src="https://lh3.googleusercontent.com/aida/ADBb0ugeLIX8ctggH1yk9SWaSmEiZ32tLWnMyvG7OnToOqNT6sU2NZsPHkIEa-FoAhWV3ZT-0gT9oRJ9-9KOibp7qMzJuOGlw8zWuVeyV19WVbclCVH0Gux6YpgZjg4ciqAeus_rgh_zE3e_459j6LraOf3R-6sBioYIen-qgzvCE5yHsJgPTtIoXE_a0DVhRxDb9PU91iOHRpix0uCRGRkzQ5_aOVQYOqGJ6p31DZ8qwCvZCmbZSKZuwhvUg-A0Wxe-lQdHa7DZVWJkzEI" />
                </div>
                <nav class="hidden items-center gap-6 lg:flex">
                    <a class="text-sm font-semibold hover:text-primary transition-colors relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-primary after:transition-all" href="#">Katalog Kelas</a>
                    <a class="text-sm font-semibold hover:text-primary transition-colors relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-primary after:transition-all" href="#">Tentang Kami</a>
                    <a class="text-sm font-semibold hover:text-primary transition-colors relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-primary after:transition-all" href="#">Sertifikasi</a>
                    <a class="text-sm font-semibold hover:text-primary transition-colors relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-primary after:transition-all" href="#">Bantuan</a>
                </nav>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden md:flex relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                        <span class="material-symbols-outlined text-lg">search</span>
                    </div>
                    <input class="h-10 w-64 rounded-lg border-none bg-primary/5 pl-10 text-sm focus:ring-2 focus:ring-primary/20 transition-all focus:w-80" placeholder="Cari keahlian industri..." type="text" />
                </div>
                @auth
                <a href="{{ url('/dashboard') }}" class="bg-primary text-white px-5 py-2 rounded-lg text-sm font-bold shadow-lg shadow-primary/20 hover:shadow-primary/40 hover:-translate-y-0.5 active:translate-y-0 transition-all text-center">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="hidden md:block text-sm font-bold px-4 py-2 hover:text-primary transition-colors hover:scale-105 active:scale-95 transition-transform" wire:navigate>Masuk</a>
                <a href="{{ route('register') }}" class="bg-primary text-white px-5 py-2 rounded-lg text-sm font-bold shadow-lg shadow-primary/20 hover:shadow-primary/40 hover:-translate-y-0.5 active:translate-y-0 transition-all text-center" wire:navigate>Daftar</a>
                @endauth
            </div>
        </div>
    </header>
    <main>
        <!-- Hero Section -->
        <section class="relative overflow-hidden pt-12 pb-20 lg:pt-24 lg:pb-32">
            <div class="container mx-auto px-4 lg:px-10">
                <div class="flex flex-col items-center gap-12 lg:flex-row">
                    <div class="flex flex-col gap-8 lg:w-1/2 animate-fade-in-up">
                        <div class="inline-flex items-center rounded-full bg-primary/10 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-primary animate-pulse-slow">
                            Platform Pelatihan Resmi SIG
                        </div>
                        <h1 class="text-5xl font-black leading-[1.1] tracking-tight text-slate-900 dark:text-slate-100 lg:text-7xl">
                            Kuasai Keahlian Industri Bersama <span class="text-primary inline-block hover:scale-110 transition-transform cursor-default">Ahlinya</span>
                        </h1>
                        <p class="text-lg leading-relaxed text-slate-600 dark:text-slate-400 lg:text-xl max-w-xl">Materi pelatihan internal PT Semen Indonesia kini hadir untuk publik melalui <span class="font-bold text-black">SIG</span> <span class="font-bold text-primary">Academy</span>. Belajar langsung dari praktisi industri semen dan konstruksi kelas dunia.</p>
                        <div class="flex flex-wrap gap-4">
                            <button class="group bg-primary text-white px-8 py-4 rounded-xl text-lg font-bold shadow-xl shadow-primary/30 hover:shadow-primary/50 hover:scale-[1.05] active:scale-95 transition-all flex items-center gap-2 overflow-hidden relative">
                                <span class="relative z-10">Lihat Katalog Kelas</span>
                                <span class="material-symbols-outlined relative z-10 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                                <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                            </button>
                            <button class="border-2 border-primary/20 bg-transparent px-8 py-4 rounded-xl text-lg font-bold hover:bg-primary hover:text-white hover:border-primary hover:scale-[1.05] active:scale-95 transition-all">
                                Jelajahi Jalur Belajar
                            </button>
                        </div>
                        <div class="flex items-center gap-4 pt-4">
                            <div class="flex -space-x-3">
                                <div class="h-10 w-10 rounded-full border-2 border-white bg-slate-200 hover:z-10 hover:scale-110 transition-transform" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCOp50_ow5ODNjxQ8ZD8_ZW5o5gWVugWsNN1Kq3HMho50Cn1Qbxs4T59QTeHHg-yzIY74ij3hB4pE1oGPEmeGQ-wKSyQrC__77mnhTK6eWw2iqgx4fQAh5KZan5t5FprTj7GT8ZWbBgbVMSwS97_ez0ULtP-Bc2xRdAarL96IogejsBzzw9sO-am9TI2ccwEg1RU6CL5W0i57JUUhEBppcSHu7pKyzV9HhZJ4oTUlDRSg_RCbHhvsWOGa1rk1-FCmhO1m6-Xl3YEH68'); background-size: cover;"></div>
                                <div class="h-10 w-10 rounded-full border-2 border-white bg-slate-200 hover:z-10 hover:scale-110 transition-transform" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuC4Xzoq7re6Zw-6lPhJVnkvB8zcTUYYaJSDwpYoxk0-gZ1eVp0ZYjOQJxXqtSBWeBVUn1ANW2POH0bLj4B9bXmfh2A8BgNu4S3xV0BmFvQjgt2sZ8fmIE4O13gWJpim7IxyBsxmmoIaM_NOkptJu5CcnOXLwcEB-geOWfRle17_DxGM2OLM1-euU8Cat4cREt_Vb8STmW0KK-kF9nCgaVf8nbv_mQexmnQ_75BV3yUWEt6G2EvVQ0mvGgQf1cSW9X7gu1lnGf6h5SmW'); background-size: cover;"></div>
                                <div class="h-10 w-10 rounded-full border-2 border-white bg-slate-200 hover:z-10 hover:scale-110 transition-transform" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBNh3mP0MDq1rmP3eMkfZuaSBqBaCeJbxSZTkzFgq8O5sz7ASw2DzrH8DjrlN9eWrAOUN3GfiBIHrqvfqAjlor6svvhRMkQxQCjcOfOKEblAaquC-aCh-YBWahr54CKWSveeZVpeU93DNMlZtJi4kPZwimepN-LME_SOWcxIEKL_bxMbK2yxUZZRSv89lqJ6x6UydyCwU46Ig_SbIrRNxSLKVval4ZUpizd9Xfl29jdnWXU0Z1--QrHMZkCJJkYPtaA4Q0iMwC0WFjh'); background-size: cover;"></div>
                            </div>
                            <p class="text-sm font-medium text-slate-500">Bergabung dengan 10.000+ profesional lainnya</p>
                        </div>
                    </div>
                    <div class="relative lg:w-1/2 reveal hero-parallax" id="hero-image-container">
                        <div class="absolute -inset-4 rounded-[2rem] bg-gradient-to-tr from-primary/20 to-transparent blur-3xl animate-pulse"></div>
                        <div class="relative aspect-video w-full overflow-hidden rounded-[2rem] shadow-2xl shadow-primary/10 group cursor-pointer mb-6">
                            <video id="heroVideo" loop playsinline class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" poster="https://unair.ac.id/wp-content/uploads/2022/01/Foto-by-Reoublika-co-id.jpg">
                                <source src="{{ asset('video/videoplayback_sig.mp4') }}" type="video/mp4">
                            </video>
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300" onclick="toggleHeroVideo()">
                                <div class="play-button-ripple relative h-20 w-20 rounded-full bg-white flex items-center justify-center text-primary shadow-2xl cursor-pointer hover:scale-110 transition-transform">
                                    <span id="heroVideoIcon" class="material-symbols-outlined text-4xl fill-1">play_arrow</span>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white p-5 shadow-xl shadow-primary/5 border border-slate-100 dark:bg-background-dark dark:border-slate-800 transition-transform hover:-translate-y-1 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center text-primary animate-float">
                                    <span class="material-symbols-outlined text-2xl">workspace_premium</span>
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-primary">Sertifikasi Global</p>
                                    <p class="text-sm font-bold text-slate-800 dark:text-slate-100">Diakui Oleh Pemimpin Industri Global</p>
                                </div>
                            </div>
                            <div class="hidden sm:block text-slate-100 dark:text-slate-800">
                                <span class="material-symbols-outlined text-5xl">verified</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Value Propositions -->
        <section class="bg-primary/5 py-24 dark:bg-primary/10 relative overflow-hidden">
            <div class="container mx-auto px-4 lg:px-10">
                <div class="mb-16 text-center reveal">
                    <h2 class="text-4xl font-black tracking-tight text-slate-900 dark:text-slate-100">Standar Keunggulan Kami</h2>
                    <p class="mt-4 text-slate-600 dark:text-slate-400">Belajar dengan standar industri global dari pemimpin pasar semen di Asia Tenggara.</p>
                </div>
                <div class="grid gap-8 md:grid-cols-3">
                    <div class="group reveal flex flex-col gap-6 rounded-3xl border border-primary/10 bg-white p-8 shadow-sm transition-all hover:shadow-2xl hover:-translate-y-2 dark:bg-background-dark">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300 group-hover:rotate-12">
                            <span class="material-symbols-outlined text-4xl">manufacturing</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 transition-colors group-hover:text-primary">Kurikulum Industri Teruji</h3>
                            <p class="mt-3 leading-relaxed text-slate-600 dark:text-slate-400">
                                Materi disusun berdasarkan standar operasional nyata di pabrik SIG yang telah teruji selama puluhan tahun efisiensinya.
                            </p>
                        </div>
                    </div>
                    <div class="group reveal flex flex-col gap-6 rounded-3xl border border-primary/10 bg-white p-8 shadow-sm transition-all hover:shadow-2xl hover:-translate-y-2 dark:bg-background-dark" style="transition-delay: 100ms;">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300 group-hover:rotate-12">
                            <span class="material-symbols-outlined text-4xl">engineering</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 transition-colors group-hover:text-primary">Mentor Praktisi SIG</h3>
                            <p class="mt-3 leading-relaxed text-slate-600 dark:text-slate-400">
                                Diajar langsung oleh tenaga ahli yang berpengalaman menangani proyek konstruksi skala nasional dan internasional.
                            </p>
                        </div>
                    </div>
                    <div class="group reveal flex flex-col gap-6 rounded-3xl border border-primary/10 bg-white p-8 shadow-sm transition-all hover:shadow-2xl hover:-translate-y-2 dark:bg-background-dark" style="transition-delay: 200ms;">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300 group-hover:rotate-12">
                            <span class="material-symbols-outlined text-4xl">verified</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 transition-colors group-hover:text-primary">Sertifikasi Resmi SIG</h3>
                            <p class="mt-3 leading-relaxed text-slate-600 dark:text-slate-400">
                                Dapatkan sertifikat kompetensi yang diakui secara luas oleh mitra strategis SIG di sektor konstruksi global.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Why Learn Section -->
        <section class="py-24">
            <div class="container mx-auto px-4 lg:px-10">
                <div class="flex flex-col gap-16 lg:flex-row lg:items-center">
                    <div class="lg:w-1/2 reveal">
                        <div class="grid grid-cols-2 gap-4">
                            <img alt="Civil construction site workers" class="rounded-3xl h-56 w-full object-cover shadow-lg hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuASqOUw3HtB04IUQTNMpZ7yR-d4ZISVGU96qNX54pQJyqjChqcSDonNCHRJpAbZ6DZRdPrdMMZxTiJNQBXHVKEzjB8K7Hd0-QoBKHaRbosZ4Yzht-6Ffdzm8D0p0mdwp0XZnCdW526r5t8LRwci51j4P83qoxCpRRlz6IGCAmIeH4sI2M7wLls_1BrTQ-F7KbNDq3BsNJCzl4_8sgiPtfPjc7VXZAvFCxgKjRIJBnwlOuFpd30YsB6-okIDiZPlu8d_hXXTg7fVHJLJ" />
                            <img alt="Industrial laboratory researcher" class="rounded-3xl h-56 w-full object-cover shadow-lg hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCsaVgLk0HYaIVw3w7ENkVulEKYwrxzLhwyIlrctBgi2Oyay4Kw7Z-arXQNQ2bI2J2TLdkQPul20xlxv6i0Di84hKQWmsu_P-1KRPsEm5oF6Bdu-GTIAchAEFThetVOkVbpBLBUuK6DL8wqQBldrh46M6S3ipceZJHzu8ZkGhdlUBIGEReX5PuCcE0q4M41cRjJxDdC8YVboKnKuvx7lUpoN4FrqupwpW-7hngyvogbWdXtnoc7UfeMp3cca1DNN3aR7-IuP_3oWK2o" />
                            <img alt="Modern cement plant architecture" class="rounded-3xl h-56 w-full object-cover shadow-lg hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD8qN-cRoErdsE94hAxF389YBFVJvkJKqOJNwxR3f1hG0rkbJiZx50LQgRorIl0zdu0omi7kgZ__FAPv2fkNwy5gij79V58sGxpL1aC0-TNcS7zEosUQEcQs8ItjU_xmaF-PGqyCDtPRy9kD8SlIIeGQz-OcR1j4Zks1IPdw8c3X-SG0TPkTwL_wFE8VaodF0Gqo0RFBYd3qIpzuRsbuqXhIkHkKSrcm5MC8_V_cB89NPGDX98JnrP0fct5lyycQ0hTuD0jQ8-Kc7OJ" />
                            <img alt="Collaborative meeting environment" class="rounded-3xl h-56 w-full object-cover shadow-lg hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBhjR3GbLkpKQ6kEvwPIPX80K8cDJwCg0SnMAX2C68TG3be1L8bcREfUGxASgsv5MnfmnyeaPvqcjOC9-auaLyBq-B03deLMlmbbTL4ThAxvDxheVVEGNFUM3cGnOOdLtXU04JUCPXe7gN-ssdJpNhtTQ9DI9ngfVQU-0GcjeuTzp-glatCtwMiJYuZTXkGXZTBRxGUjHkN5UbIylwe1h2oc_9ZaNNcmyPBoSnemTLEwZGxjE3xUZp5h5dpSL8Jkj012kXUEOAM_alB" />
                        </div>
                    </div>
                    <div class="lg:w-1/2 reveal" style="transition-delay: 200ms;">
                        <h2 class="text-4xl font-black tracking-tight text-slate-900 dark:text-slate-100 mb-8">Mengapa Belajar di <span class="text-black">SIG</span> <span class="text-primary">Academy</span>?</h2>
                        <div class="space-y-6 text-lg leading-relaxed text-slate-600 dark:text-slate-400">
                            <p>
                                Selama puluhan tahun, sistem pembelajaran kami hanya dapat diakses secara internal untuk memastikan standar kualitas tinggi yang tak tertandingi di seluruh unit operasional PT Semen Indonesia.
                            </p>
                            <p class="font-semibold text-primary">Kini, kami membuka pintu bagi publik melalui <span class="text-black">SIG</span> <span class="text-primary">Academy</span>—insinyur, mahasiswa, dan profesional konstruksi—untuk mengakses pengetahuan eksklusif ini.</p>
                            <p>Kami percaya bahwa demokratisasi pengetahuan industri adalah kunci untuk memajukan industri semen dan konstruksi nasional. Melalui komersialisasi materi pelatihan terbaik kami di <span class="font-bold text-black">SIG</span> <span class="font-bold text-primary">Academy</span>, kami berkomitmen untuk melahirkan generasi ahli konstruksi baru yang kompeten dan siap bersaing di kancah global.</p>
                        </div>
                        <div class="mt-10 grid grid-cols-2 gap-8 border-t border-primary/10 pt-10">
                            <div class="group">
                                <p class="text-4xl font-black text-primary transition-transform group-hover:scale-110 origin-left inline-block" data-count="50">0</p><span class="text-4xl font-black text-primary">+</span>
                                <p class="text-sm font-bold uppercase tracking-wider text-slate-500">Tahun Pengalaman</p>
                            </div>
                            <div class="group">
                                <p class="text-4xl font-black text-primary transition-transform group-hover:scale-110 origin-left inline-block" data-count="200">0</p><span class="text-4xl font-black text-primary">+</span>
                                <p class="text-sm font-bold uppercase tracking-wider text-slate-500">Modul Spesialis</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- CTA Section -->
        <section class="py-20">
            <div class="container mx-auto px-4 lg:px-10">
                <div class="reveal relative overflow-hidden rounded-[3rem] bg-primary px-8 py-16 text-center text-white lg:py-24 group">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.2),transparent)] transition-transform duration-1000 group-hover:scale-125"></div>
                    <div class="relative z-10 flex flex-col items-center gap-8">
                        <h2 class="text-4xl font-black tracking-tight lg:text-6xl">Siap Meningkatkan Karier Anda?</h2>
                        <p class="max-w-2xl text-lg text-white/90 lg:text-xl">Dapatkan akses ke perpustakaan kursus terlengkap di industri semen dan konstruksi melalui <span class="font-bold text-black">SIG</span> <span class="font-bold text-white">Academy</span> hari ini.</p>
                        <div class="flex flex-wrap justify-center gap-4">
                            @auth
                            <a href="{{ url('/dashboard') }}" class="inline-block rounded-full bg-white px-10 py-4 text-lg font-black text-primary shadow-xl transition-all hover:scale-110 hover:shadow-white/20 active:scale-95">
                                Akses Dashboard
                            </a>
                            @else
                            <a href="{{ route('register') }}" class="inline-block rounded-full bg-white px-10 py-4 text-lg font-black text-primary shadow-xl transition-all hover:scale-110 hover:shadow-white/20 active:scale-95" wire:navigate>
                                Mulai Belajar Sekarang
                            </a>
                            @endauth
                            <button class="rounded-full border-2 border-white/30 px-10 py-4 text-lg font-bold text-white backdrop-blur-sm transition-all hover:bg-white hover:text-primary active:scale-95">
                                Konsultasi Korporasi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- Footer -->
    <footer class="border-t border-primary/10 bg-white py-16 dark:bg-background-dark">
        <div class="container mx-auto px-4 lg:px-10">
            <div class="grid gap-12 lg:grid-cols-4">
                <div class="col-span-1 lg:col-span-1">
                    <div class="flex items-center gap-4 mb-6">
                        <img alt="SIG Logo" class="h-6 w-auto" src="https://lh3.googleusercontent.com/aida/ADBb0ug4uVZk-JZLTgVAd_lj0IsvQAyEuWGSelzoVgm3RDq3UYHR15iAf7CBoVcc9cB11kfbJ9GXbQjr-S5M4XGVpjHLu-iy2GTfoBOoTXYi6ToEm6aMZgQtwTHTh3o8v7URI0rFMdHkssz32ZNSkuXA40OaNkqxkVTCpIiiaPfmB1P0KdRJ9Yb3_ylDcoVcxaJjJfq0O74fUffV7mInUZAfc_HruzlG2rAmy3C9IB7VzBeB4Ap9uJovnZglzvEAN7vEyiLHJ9eC0SENsgM" />
                        <div class="h-4 w-px bg-slate-300 dark:bg-slate-700"></div>
                        <img alt="SIG Academy Logo" class="h-6 w-auto" src="https://lh3.googleusercontent.com/aida/ADBb0ugeLIX8ctggH1yk9SWaSmEiZ32tLWnMyvG7OnToOqNT6sU2NZsPHkIEa-FoAhWV3ZT-0gT9oRJ9-9KOibp7qMzJuOGlw8zWuVeyV19WVbclCVH0Gux6YpgZjg4ciqAeus_rgh_zE3e_459j6LraOf3R-6sBioYIen-qgzvCE5yHsJgPTtIoXE_a0DVhRxDb9PU91iOHRpix0uCRGRkzQ5_aOVQYOqGJ6p31DZ8qwCvZCmbZSKZuwhvUg-A0Wxe-lQdHa7DZVWJkzEI" />
                    </div>
                    <p class="text-sm leading-relaxed text-slate-500 dark:text-slate-400">Platform edukasi resmi PT Semen Indonesia (Persero) Tbk. Berdedikasi untuk mencetak tenaga ahli konstruksi masa depan melalui <span class="font-bold text-black">SIG</span> <span class="font-bold text-primary">Academy</span>.</p>
                    <div class="mt-6 flex gap-4">
                        <a class="text-slate-400 hover:text-primary transition-all hover:scale-125" href="#"><span class="material-symbols-outlined">social_leaderboard</span></a>
                        <a class="text-slate-400 hover:text-primary transition-all hover:scale-125" href="#"><span class="material-symbols-outlined">share</span></a>
                        <a class="text-slate-400 hover:text-primary transition-all hover:scale-125" href="#"><span class="material-symbols-outlined">video_library</span></a>
                    </div>
                </div>
                <div>
                    <h4 class="mb-6 text-sm font-bold uppercase tracking-widest text-slate-900 dark:text-slate-100">Jelajahi</h4>
                    <ul class="space-y-4 text-sm text-slate-500 dark:text-slate-400">
                        <li><a class="hover:text-primary transition-colors hover:translate-x-1 inline-block" href="#">Katalog Kursus</a></li>
                        <li><a class="hover:text-primary transition-colors hover:translate-x-1 inline-block" href="#">Jalur Sertifikasi</a></li>
                        <li><a class="hover:text-primary transition-colors hover:translate-x-1 inline-block" href="#">Daftar Mentor</a></li>
                        <li><a class="hover:text-primary transition-colors hover:translate-x-1 inline-block" href="#">Webinar Mendatang</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-6 text-sm font-bold uppercase tracking-widest text-slate-900 dark:text-slate-100">Perusahaan</h4>
                    <ul class="space-y-4 text-sm text-slate-500 dark:text-slate-400">
                        <li><a class="hover:text-primary transition-colors hover:translate-x-1 inline-block" href="#">Tentang Kami</a></li>
                        <li><a class="hover:text-primary transition-colors hover:translate-x-1 inline-block" href="#">Pusat Karir</a></li>
                        <li><a class="hover:text-primary transition-colors hover:translate-x-1 inline-block" href="#">Investor Relations</a></li>
                        <li><a class="hover:text-primary transition-colors hover:translate-x-1 inline-block" href="#">ESG Commitment</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-6 text-sm font-bold uppercase tracking-widest text-slate-900 dark:text-slate-100">Bantuan</h4>
                    <ul class="space-y-4 text-sm text-slate-500 dark:text-slate-400">
                        <li><a class="hover:text-primary transition-colors hover:translate-x-1 inline-block" href="#">Pusat Bantuan</a></li>
                        <li><a class="hover:text-primary transition-colors hover:translate-x-1 inline-block" href="#">Kontak Kami</a></li>
                        <li><a class="hover:text-primary transition-colors hover:translate-x-1 inline-block" href="#">Kebijakan Privasi</a></li>
                        <li><a class="hover:text-primary transition-colors hover:translate-x-1 inline-block" href="#">Syarat &amp; Ketentuan</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-16 border-t border-primary/5 pt-8 flex flex-col items-center justify-between gap-4 md:flex-row">
                <p class="text-xs text-slate-400">© 2024 PT Semen Indonesia (Persero) Tbk. Hak cipta dilindungi undang-undang. <span class="font-bold text-black">SIG</span> <span class="font-bold text-primary">Academy</span> merupakan bagian dari pusat keunggulan pembelajaran kami.</p>
                <div class="flex items-center gap-6">
                    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-200 text-slate-500 shadow-sm transition-all hover:bg-primary hover:text-white hover:-translate-y-1 hover:shadow-md dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-primary" aria-label="Kembali ke atas">
                        <span class="material-symbols-outlined">arrow_upward</span>
                    </button>
                </div>
            </div>
        </div>
    </footer>
    <script>
        // Header shadow on scroll
        window.addEventListener('scroll', () => {
            const header = document.getElementById('main-header');
            if (window.scrollY > 20) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Reveal elements on scroll
        const revealElements = () => {
            const reveals = document.querySelectorAll('.reveal');
            reveals.forEach(element => {
                const windowHeight = window.innerHeight;
                const revealTop = element.getBoundingClientRect().top;
                const revealPoint = 150;
                if (revealTop < windowHeight - revealPoint) {
                    element.classList.add('active');

                    // Trigger counters if they exist in this reveal
                    const counters = element.querySelectorAll('[data-count]');
                    counters.forEach(counter => {
                        if (!counter.dataset.started) {
                            animateCounter(counter);
                            counter.dataset.started = 'true';
                        }
                    });
                }
            });
        };

        // Counter animation
        const animateCounter = (el) => {
            const target = parseInt(el.dataset.count);
            let count = 0;
            const duration = 2000; // 2 seconds
            const stepTime = Math.abs(Math.floor(duration / target));

            const timer = setInterval(() => {
                count += 1;
                el.innerText = count;
                if (count >= target) {
                    el.innerText = target;
                    clearInterval(timer);
                }
            }, stepTime);
        };

        // Video Play/Pause Toggle
        let pauseTimeout;
        window.toggleHeroVideo = function() {
            const video = document.getElementById('heroVideo');
            const icon = document.getElementById('heroVideoIcon');

            if (video.paused) {
                clearTimeout(pauseTimeout);
                video.play();
                icon.innerText = 'pause';
            } else {
                video.pause();
                icon.innerText = 'play_arrow';

                // Revert ke gambar statis (poster) jika dipause > 5 detik
                pauseTimeout = setTimeout(() => {
                    video.load();
                    icon.innerText = 'play_arrow';
                }, 5000);
            }
        };

        // Auto-pause video when scrolled out of view
        window.addEventListener('load', () => {
            const videoElement = document.getElementById('heroVideo');
            if (!videoElement) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    const icon = document.getElementById('heroVideoIcon');
                    // Jika video scroll out of view dan sedang terputar, auto-pause
                    if (!entry.isIntersecting && !videoElement.paused) {
                        videoElement.pause();
                        icon.innerText = 'play_arrow';

                        // Picu logic kembali ke cover 5 detik sama seperti manual pause
                        clearTimeout(pauseTimeout);
                        pauseTimeout = setTimeout(() => {
                            videoElement.load();
                            icon.innerText = 'play_arrow';
                        }, 5000);
                    }
                });
            }, {
                threshold: 0.1
            });

            observer.observe(videoElement);
        });

        // Parallax effect for hero
        document.addEventListener('mousemove', (e) => {
            const heroImg = document.getElementById('hero-image-container');
            if (heroImg) {
                const xAxis = (window.innerWidth / 2 - e.pageX) / 50;
                const yAxis = (window.innerHeight / 2 - e.pageY) / 50;
                heroImg.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
            }
        });

        window.addEventListener('scroll', revealElements);
        window.addEventListener('load', revealElements);

        // SweetAlert: Access Denied Notification
        @if(session('access_denied'))
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak',
                    text: '{{ session('access_denied') }}',
                    confirmButtonColor: '#e21d24',
                    confirmButtonText: 'Saya Mengerti',
                    backdrop: 'rgba(15,23,42,0.6)',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl font-bold',
                    }
                });
            });
        @endif
    </script>
</body>

</html>