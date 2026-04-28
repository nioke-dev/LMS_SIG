<!DOCTYPE html>

<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Daftar Akun - SIG Academy</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800;900&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-tint": "#e21d24",
                        "surface-container-low": "#f1eeee",
                        "primary-fixed-dim": "#ffb4ac",
                        "inverse-on-surface": "#f8fafc",
                        "surface-bright": "#ffffff",
                        "tertiary": "#0f172a",
                        "inverse-primary": "#ffb4ac",
                        "surface": "#f8f6f6",
                        "on-surface-variant": "#475569",
                        "on-primary": "#ffffff",
                        "error-container": "#ffdad6",
                        "background": "#f8f6f6",
                        "secondary-fixed-dim": "#c6c6c6",
                        "on-primary-container": "#ffffff",
                        "primary": "#e21d24",
                        "tertiary-container": "#1e293b",
                        "surface-container": "#ebe8e8",
                        "on-tertiary-fixed": "#101a2d",
                        "on-secondary-container": "#f8f6f6",
                        "on-error-container": "#410002",
                        "surface-container-lowest": "#ffffff",
                        "secondary-fixed": "#e2e2e2",
                        "tertiary-fixed": "#dbe2f9",
                        "surface-dim": "#e5e2e2",
                        "outline": "#e21d241a",
                        "on-surface": "#0f172a",
                        "surface-variant": "#f1eeee",
                        "secondary-container": "#331d1e",
                        "error": "#ba1a1a",
                        "secondary": "#211112",
                        "on-tertiary-container": "#f1f5f9",
                        "on-secondary-fixed": "#1b1b1b",
                        "tertiary-fixed-dim": "#bfc6dc",
                        "primary-container": "#e21d24",
                        "on-primary-fixed-variant": "#93000a",
                        "primary-fixed": "#ffdad6",
                        "on-tertiary-fixed-variant": "#3f4759",
                        "on-secondary": "#ffffff",
                        "on-error": "#ffffff",
                        "on-secondary-fixed-variant": "#484646",
                        "outline-variant": "#e21d2433",
                        "surface-container-high": "#e5e2e2",
                        "on-tertiary": "#ffffff",
                        "on-background": "#0f172a",
                        "inverse-surface": "#1e293b"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
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
        body {
            font-family: 'Inter', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>

<body class="bg-surface text-on-surface min-h-screen">
    <main class="flex min-h-screen">
        <!-- Left Side: Registration Form -->
        <section class="w-full lg:w-1/2 flex flex-col p-8 md:p-16 xl:p-24 bg-surface-bright">
            <!-- Brand Logo Cluster -->
            <div class="mb-14 flex items-center">
                <img alt="SIG Corporate Logo" class="h-12 w-auto" src="https://lh3.googleusercontent.com/aida/ADBb0ug4uVZk-JZLTgVAd_lj0IsvQAyEuWGSelzoVgm3RDq3UYHR15iAf7CBoVcc9cB11kfbJ9GXbQjr-S5M4XGVpjHLu-iy2GTfoBOoTXYi6ToEm6aMZgQtwTHTh3o8v7URI0rFMdHkssz32ZNSkuXA40OaNkqxkVTCpIiiaPfmB1P0KdRJ9Yb3_ylDcoVcxaJjJfq0O74fUffV7mInUZAfc_HruzlG2rAmy3C9IB7VzBeB4Ap9uJovnZglzvEAN7vEyiLHJ9eC0SENsgM" />
                <div class="h-10 w-px bg-slate-200 mx-6"></div>
                <img alt="SIG Academy Logo" class="h-10 w-auto" src="https://lh3.googleusercontent.com/aida/ADBb0ugeLIX8ctggH1yk9SWaSmEiZ32tLWnMyvG7OnToOqNT6sU2NZsPHkIEa-FoAhWV3ZT-0gT9oRJ9-9KOibp7qMzJuOGlw8zWuVeyV19WVbclCVH0Gux6YpgZjg4ciqAeus_rgh_zE3e_459j6LraOf3R-6sBioYIen-qgzvCE5yHsJgPTtIoXE_a0DVhRxDb9PU91iOHRpix0uCRGRkzQ5_aOVQYOqGJ6p31DZ8qwCvZCmbZSKZuwhvUg-A0Wxe-lQdHa7DZVWJkzEI" />
            </div>
            <div class="max-w-md w-full mx-auto lg:mx-0">
                @livewire('auth.register-identifier')
            </div>
        </section>
        <!-- Right Side: Visual Hero -->
        <section class="hidden lg:flex w-1/2 relative overflow-hidden bg-zinc-950">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <img alt="High Quality Industrial Cement Factory" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB34fA8I5LVsSYUE8c29lwrr-qvia-TCKrF9KM2KRa0A2blbpOBDLR-A46m3znzQ6DdSudtEKDENL0VKyjMaQ-jMnzsIGHHvEiqcXGHmHTRqZzOLkg85vQFaSO5NLx45v2ERKVNUNNvUbqhL1aUsesJekDI_HVyeOrChPx0sT2zrEueqdXwsZupLcCazwhR_TkZpZBfdW7oD6LsvXBOTM0VkXPP972l52rulzqcLA5gLP71G73gfRBp9S2vISB_7Ke4Omd32eRSHlVe" />
                <!-- Dark Crimson Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-br from-primary/90 via-black/80 to-black/95 mix-blend-multiply"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80"></div>
            </div>
            <!-- Content Container -->
            <div class="relative z-10 w-full h-full flex flex-col p-16">
                <!-- Top Content Group - Badge at top -->
                <div class="self-start">
                    <div class="bg-white/10 backdrop-blur-md border border-white/10 px-4 py-2 rounded-full inline-flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                        <span class="text-xs font-bold text-white uppercase tracking-widest">Industry 4.0 Ready</span>
                    </div>
                </div>
                <!-- Bottom Content Group -->
                <div class="mt-auto">
                    <!-- Main Slogan & Description -->
                    <div class="max-w-xl mb-12">
                        <h2 class="text-6xl xl:text-7xl font-black text-white leading-[1.1] tracking-tighter mb-6">
                            Bangun Masa Depan <span class="text-primary">Industri</span>
                        </h2>
                        <p class="text-xl text-zinc-300 font-medium leading-relaxed border-l-4 border-primary pl-6 opacity-80">
                            Menghadirkan standar pelatihan teknis kelas dunia untuk menciptakan tenaga ahli yang presisi dan tersertifikasi secara internasional.
                        </p>
                    </div>
                    <!-- Feature Cards -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-black/40 backdrop-blur-xl p-6 rounded-2xl border border-white/10 flex flex-col gap-2 group hover:border-primary/50 transition-colors">
                            <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings: 'FILL' 1;">engineering</span>
                            <h3 class="text-white font-bold text-sm">Technical Mastery</h3>
                            <p class="text-zinc-400 text-xs">Kurikulum berbasis standar industri global.</p>
                        </div>
                        <div class="bg-black/40 backdrop-blur-xl p-6 rounded-2xl border border-white/10 flex flex-col gap-2 group hover:border-primary/50 transition-colors">
                            <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                            <h3 class="text-white font-bold text-sm">ISO Compliance</h3>
                            <p class="text-zinc-400 text-xs">Sertifikasi yang diakui oleh otoritas internasional.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Decorative Elements -->
            <div class="absolute top-24 right-24 w-40 h-40 border-r border-t border-primary/20 pointer-events-none"></div>
        </section>
    </main>
</body>

</html>