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

<body class="bg-background text-on-background min-h-screen flex antialiased">
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
                <form method="POST" action="{{ route('admin.login.submit') }}" class="flex flex-col gap-5 w-full">
                    @csrf
                    <!-- Email Input (No-Line Style) -->
                    <div>
                        <div class="relative flex items-center bg-primary/5 rounded-xl px-4 py-3 focus-within:bg-primary/10 transition-colors @error('email') ring-2 ring-red-500 @enderror">
                            <span class="material-symbols-outlined text-primary/70 mr-3" style="">mail</span>
                            <input class="w-full bg-transparent border-none focus:ring-0 text-on-surface placeholder:text-on-surface-variant/70 font-medium text-lg p-0" id="corporate-email" name="email" value="{{ old('email') }}" placeholder="Corporate Email" required="" type="email" autofocus />
                        </div>
                        @error('email')
                        <p class="text-red-500 text-sm mt-1 ml-2 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Password Input -->
                    <div>
                        <div class="relative flex items-center bg-primary/5 rounded-xl px-4 py-3 focus-within:bg-primary/10 transition-colors @error('password') ring-2 ring-red-500 @enderror">
                            <span class="material-symbols-outlined text-primary/70 mr-3" style="">lock</span>
                            <input class="w-full bg-transparent border-none focus:ring-0 text-on-surface placeholder:text-on-surface-variant/70 font-medium text-lg p-0" id="security-token" name="password" placeholder="Security Token / Password" required="" type="password" />
                        </div>
                        @error('password')
                        <p class="text-red-500 text-sm mt-1 ml-2 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Forgot Link -->
                    <div class="flex justify-end mt-2 mb-6">
                        <a class="text-sm font-bold text-primary hover:text-primary-container transition-colors" href="#" style="">Forgot Access Details?</a>
                    </div>
                    <!-- Primary Action -->
                    <button class="w-full bg-primary text-on-primary font-bold text-lg py-4 rounded-xl shadow-xl shadow-primary/30 hover:shadow-2xl hover:shadow-primary/40 active:scale-[0.98] transition-all duration-200 tracking-wide" style="" type="submit">
                        Authenticate Portal
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
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&amp;w=2070&amp;auto=format&amp;fit=crop')] bg-cover bg-center" data-alt="Massive industrial rotary kiln inside a cement manufacturing plant with dramatic warm lighting, steel structures, and sparks flying, conveying heavy industry and precision scale"></div>
                <!-- Radial gradient for depth per design system -->
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.1),transparent)] z-10"></div>
                <!-- Linear gradient for text legibility -->
                <div class="absolute inset-0 bg-gradient-to-t from-tertiary via-tertiary/80 to-transparent z-10"></div>
            </div>
            <!-- Floating Status Badge -->
            <!-- Content Cluster (Z-20) -->
            <div class="relative z-20 w-full max-w-4xl text-on-primary">
                <h2 class="text-5xl lg:text-7xl font-black tracking-tighter leading-[0.95] mb-6 drop-shadow-xl" style="">
                    Operational<br />Integrity &amp;<br />Precision.
                </h2>
                <p class="text-xl lg:text-2xl text-on-primary/80 font-medium max-w-2xl mb-16 leading-relaxed" style="">
                    Managing the intellectual assets of South East Asia's cement giant.
                </p>
                <!-- Feature Cards (Glassmorphism) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full">
                    <!-- Card 1 -->
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-6 lg:p-8 flex items-start gap-4 hover:bg-white/10 transition-colors cursor-default">
                        <div class="p-3 bg-primary/20 rounded-xl text-primary mt-1">
                            <span class="material-symbols-outlined material-fill text-3xl" style="">database</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg lg:text-xl mb-1 text-white" style="">Data Governance</h3>
                            <p class="text-sm text-white/60" style="">Centralized control of enterprise learning telemetry and compliance metrics.</p>
                        </div>
                    </div>
                    <!-- Card 2 -->
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl p-6 lg:p-8 flex items-start gap-4 hover:bg-white/10 transition-colors cursor-default">
                        <div class="p-3 bg-primary/20 rounded-xl text-primary mt-1">
                            <span class="material-symbols-outlined material-fill text-3xl" style="">account_tree</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg lg:text-xl mb-1 text-white" style="">Curriculum Oversight</h3>
                            <p class="text-sm text-white/60" style="">Strategic alignment of technical training paths with industrial requirements.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>