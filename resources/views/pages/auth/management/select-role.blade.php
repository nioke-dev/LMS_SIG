<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Pilih Peran - SIG Academy</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary": "#e21d24",
                        "on-surface": "#0f172a",
                        "background": "#f8f6f6",
                    }
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .material-symbols-rounded {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 48;
        }
    </style>
</head>
<body class="bg-background text-on-surface antialiased">
    <div class="min-h-screen flex items-center justify-center bg-zinc-50 p-6">
        <div class="max-w-4xl w-full">
            {{-- Header Section --}}
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-primary rounded-3xl shadow-2xl shadow-primary/30 mb-6 transform hover:rotate-12 transition-transform duration-500">
                    <span class="material-symbols-rounded text-white text-4xl">supervisor_account</span>
                </div>
                <h1 class="text-4xl font-black text-on-surface tracking-tighter mb-2">Selamat Datang Kembali!</h1>
                <p class="text-zinc-500 font-medium max-w-md mx-auto">Akun Anda terdaftar dengan beberapa peran. Silakan pilih peran yang ingin Anda gunakan untuk sesi ini.</p>
            </div>

            {{-- Role Cards Grid --}}
            <form action="{{ route('auth.select-role.submit') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach($roles as $role)
                        @php
                            $roleData = [
                                'learning_coordinator' => [
                                    'title' => 'Learning Coordinator',
                                    'desc' => 'Kelola kebutuhan pelatihan, analisis TNA, dan pantau progres usulan.',
                                    'icon' => 'dynamic_form',
                                    'color' => 'bg-blue-500'
                                ],
                                'admin_coordinator' => [
                                    'title' => 'Admin Coordinator',
                                    'desc' => 'Review usulan pelatihan, kelola kurasi, dan koordinasi administrasi.',
                                    'icon' => 'verified_user',
                                    'color' => 'bg-emerald-500'
                                ],
                                'learning_administrator' => [
                                    'title' => 'Learning Administrator',
                                    'desc' => 'Manajemen user, pengaturan sistem, dan tata kelola platform utama.',
                                    'icon' => 'settings_suggest',
                                    'color' => 'bg-purple-500'
                                ],
                                'sme' => [
                                    'title' => 'Subject Matter Expert',
                                    'desc' => 'Review konten teknis dan berikan masukan ahli pada program.',
                                    'icon' => 'psychology',
                                    'color' => 'bg-amber-500'
                                ],
                            ];
                            $data = $roleData[$role] ?? [
                                'title' => ucfirst(str_replace('_', ' ', $role)),
                                'desc' => 'Akses ke dashboard operasional sesuai tugas dan fungsi jabatan.',
                                'icon' => 'dashboard',
                                'color' => 'bg-zinc-500'
                            ];
                        @endphp

                        <button type="submit" name="role" value="{{ $role }}" 
                            class="group relative bg-white p-8 rounded-[2.5rem] border-2 border-transparent hover:border-primary shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 text-left overflow-hidden w-full">
                            
                            {{-- Background Decoration --}}
                            <div class="absolute -right-12 -bottom-12 w-48 h-48 {{ $data['color'] }} opacity-[0.03] rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                            
                            <div class="flex items-start gap-6 relative z-10">
                                <div class="w-16 h-16 {{ $data['color'] }} rounded-2xl flex items-center justify-center text-white shadow-xl {{ str_replace('bg-', 'shadow-', $data['color']) }}/20">
                                    <span class="material-symbols-rounded text-3xl">{{ $data['icon'] }}</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-black text-on-surface mb-2 group-hover:text-primary transition-colors">{{ $data['title'] }}</h3>
                                    <p class="text-zinc-500 text-sm leading-relaxed mb-6">{{ $data['desc'] }}</p>
                                    
                                    <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-primary opacity-0 group-hover:opacity-100 transition-all transform translate-x-4 group-hover:translate-x-0">
                                        Pilih Peran Ini
                                        <span class="material-symbols-rounded text-sm">arrow_forward</span>
                                    </div>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </form>

            {{-- Footer --}}
            <div class="text-center mt-12">
                <a href="{{ route('home') }}" class="text-sm font-black text-zinc-400 hover:text-primary uppercase tracking-widest transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-rounded text-sm">logout</span>
                    Batalkan dan Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>
