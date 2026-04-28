<div class="max-w-md w-full mx-auto lg:mx-0">
    <!-- Form Header -->
    <header class="mb-8">
        <h1 class="text-4xl font-extrabold tracking-tight text-on-surface mb-2">Buat Akun Anda</h1>
        <p class="text-on-surface-variant">Mulai perjalanan keahlian industri Anda hari ini.</p>
    </header>

    <!-- Alert Box: Karyawan Tidak Terdaftar -->
    @if ($employeeNotRegistered)
    <div class="mb-8 p-4 bg-amber-50 border-l-4 border-amber-400 flex gap-3 items-start animate-fade-in-up rounded-r-lg">
        <span class="material-symbols-outlined text-amber-600 mt-0.5" data-icon="warning">warning</span>
        <p class="text-sm text-amber-900 font-medium leading-tight">
            Akun karyawan Anda tidak terdaftar oleh sistem. Silahkan hubungi Learning Administrator atau Admin Helpdesk.
        </p>
    </div>
    @endif

    <!-- Alert Box: Karyawan Sudah Ada -->
    @if ($employeeExists)
    <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-400 flex gap-3 items-start animate-fade-in-up rounded-r-lg">
        <span class="material-symbols-outlined text-emerald-600 mt-0.5" data-icon="info">info</span>
        <div class="text-sm text-emerald-900 font-medium leading-tight w-full">
            <p>Akun ini sudah terdaftar. Silakan login menggunakan akun kantor Anda.</p>
            <a href="{{ route('login') }}" class="font-bold text-emerald-700 underline hover:text-emerald-800 mt-1.5 block">Login Sekarang &rarr;</a>
        </div>
    </div>
    @endif

    <!-- Alert Box: Publik Lama -->
    @if ($publicExists)
    <div class="mb-8 p-4 bg-error-container border-l-4 border-error/50 flex gap-3 items-start animate-fade-in-up rounded-r-lg">
        <span class="material-symbols-outlined text-error mt-0.5" data-icon="error">error</span>
        <div class="text-sm text-on-error-container font-medium leading-tight w-full">
            <p>Email publik ini sudah terdaftar. Anda tidak perlu membuat akun lagi.</p>
            <a href="{{ route('login') }}" class="font-bold text-error underline hover:text-error/80 mt-1.5 block">Login Sekarang &rarr;</a>
        </div>
    </div>
    @endif

    <!-- Registration Form -->
    <form wire:submit.prevent="registerUser" class="space-y-5">
        <!-- Full Name -->
        <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1">Full Name</label>
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant transition-colors group-focus-within:text-primary" data-icon="person">person</span>
                <input wire:model="name" class="w-full bg-surface-container border-none rounded-xl py-3.5 pl-12 pr-4 focus:ring-2 focus:ring-primary/20 transition-all placeholder:text-zinc-400" placeholder="Nama Lengkap sesuai KTP" type="text" />
            </div>
            @error('name') <span class="text-error text-xs font-bold ml-1">{{ $message }}</span> @enderror
        </div>
        <!-- Email Address -->
        <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1">Email Address</label>
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant transition-colors group-focus-within:text-primary" data-icon="mail">mail</span>
                <input wire:model.defer="email" class="w-full bg-surface-container border-none rounded-xl py-3.5 pl-12 pr-4 focus:ring-2 focus:ring-primary/20 transition-all placeholder:text-zinc-400" placeholder="nama@example.com" type="email" />
            </div>
            @error('email') <span class="text-error text-xs font-bold ml-1">{{ $message }}</span> @enderror
        </div>
        <!-- Password -->
        <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1">Password</label>
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant transition-colors group-focus-within:text-primary" data-icon="lock">lock</span>
                <input wire:model.defer="password" class="w-full bg-surface-container border-none rounded-xl py-3.5 pl-12 pr-4 focus:ring-2 focus:ring-primary/20 transition-all placeholder:text-zinc-400" placeholder="Minimal 8 karakter" type="password" />
            </div>
            @error('password') <span class="text-error text-xs font-bold ml-1">{{ $message }}</span> @enderror
        </div>
        <!-- Confirm Password -->
        <div class="space-y-1.5">
            <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1">Confirm Password</label>
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant transition-colors group-focus-within:text-primary" data-icon="lock">lock</span>
                <input wire:model.defer="password_confirmation" class="w-full bg-surface-container border-none rounded-xl py-3.5 pl-12 pr-4 focus:ring-2 focus:ring-primary/20 transition-all placeholder:text-zinc-400" placeholder="Ketik ulang password" type="password" />
            </div>
        </div>
        <!-- Terms Checkbox -->
        <label class="flex items-start gap-3 cursor-pointer group mt-2 pt-2">
            <div class="relative flex items-center mt-1">
                <input wire:model.defer="terms" class="rounded border-outline-variant text-primary focus:ring-primary/20 w-5 h-5 bg-surface transition-all" type="checkbox" />
            </div>
            <span class="text-sm text-on-surface-variant leading-tight">
                Saya menyetujui <a class="text-primary font-semibold hover:underline" href="#">Syarat &amp; Ketentuan</a> dan <a class="text-primary font-semibold hover:underline" href="#">Kebijakan Privasi</a>.
            </span>
        </label>
        @error('terms') <span class="text-error text-xs font-bold ml-1 block">{{ $message }}</span> @enderror

        <!-- Submit Button -->
        <button class="w-full bg-primary text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/20 hover:shadow-xl hover:shadow-primary/30 transition-all active:scale-[0.98] mt-4 flex items-center justify-center" type="submit">
            <span wire:loading.remove wire:target="registerUser">Daftar Akun Sekarang</span>
            <span wire:loading wire:target="registerUser">Memproses...</span>
        </button>
    </form>
    <!-- Login Redirect -->
    <footer class="mt-8 text-center lg:text-left">
        <p class="text-on-surface-variant text-sm">
            Sudah punya akun?
            <a class="text-primary font-bold hover:underline ml-1 transition-colors" href="{{ route('login') }}" wire:navigate>Login di sini</a>
        </p>
    </footer>
</div>