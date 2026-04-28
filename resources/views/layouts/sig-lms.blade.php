<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIG LMS')</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar-icon {
            @apply w-10 h-10 flex items-center justify-center rounded-xl transition-all duration-200 cursor-pointer;
        }
        .sidebar-icon.active {
            @apply bg-red-600 text-white shadow-lg shadow-red-200;
        }
        .sidebar-icon:not(.active) {
            @apply text-zinc-400 hover:bg-zinc-100;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e4e4e7;
            border-radius: 10px;
        }
    </style>
    @yield('styles')
</head>
<body class="antialiased text-zinc-900">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-[70px] border-r border-zinc-100 bg-white flex flex-col items-center py-6 sticky top-0 h-screen z-50">
            <div class="mb-12">
                <span class="font-black text-xl tracking-tighter">SIG</span>
            </div>

            <nav class="flex flex-col gap-6 flex-1">
                <div class="sidebar-icon">
                    <span class="material-symbols-outlined">grid_view</span>
                </div>
                <div class="sidebar-icon">
                    <span class="material-symbols-outlined">assignment</span>
                </div>
                <div class="sidebar-icon active">
                    <span class="material-symbols-outlined">add</span>
                </div>
            </nav>

            <div class="sidebar-icon text-zinc-400">
                <span class="material-symbols-outlined">keyboard_double_arrow_right</span>
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            {{-- Header --}}
            <header class="h-20 bg-white border-b border-zinc-100 px-10 flex items-center justify-end gap-6">
                <button class="px-6 py-2 border border-red-500 text-red-500 text-sm font-bold rounded-full hover:bg-red-50 transition-colors">
                    Ubah Peran
                </button>
                <div class="w-10 h-10 flex items-center justify-center text-zinc-400 relative cursor-pointer">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </div>
                <div class="flex items-center gap-3 border-l border-zinc-100 pl-6 cursor-pointer">
                    <div class="text-right">
                        <p class="text-sm font-bold leading-tight">Nurul Mustofa</p>
                        <p class="text-[10px] text-zinc-400 font-medium">Learning Coordinator</p>
                    </div>
                    <div class="w-10 h-10 bg-red-100 text-red-600 rounded-full flex items-center justify-center font-bold text-sm">
                        NM
                    </div>
                </div>
            </header>

            {{-- Main Content --}}
            <main class="flex-1 p-10">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @yield('scripts')
</body>
</html>
