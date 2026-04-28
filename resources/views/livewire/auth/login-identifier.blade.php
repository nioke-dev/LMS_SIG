<div class="w-full">
    @if ($statusMessage || session('status') || session('error'))
    <div class="bg-error-container border border-error/20 p-4 rounded-xl mb-6">
        <span class="text-on-error-container text-sm font-medium">
            {{ $statusMessage ?: (session('status') ?: session('error')) }}
        </span>
    </div>
    <button wire:click="backToEmail" class="w-full py-4 text-sm font-bold text-primary hover:bg-primary/5 rounded-xl transition-colors mt-2">
        Kembali
    </button>
    @else
    <form wire:submit.prevent="{{ $step === 1 ? 'checkEmail' : 'loginUser' }}" class="space-y-6 block">
        @csrf

        <!-- Email Berada di Step 1 Maupun Step 2 -->
        <div class="space-y-1">
            <div class="flex justify-between items-center">
                <label class="text-[12px] font-bold text-primary uppercase tracking-widest block" for="email">Email</label>
                @if($step === 2)
                <a href="#" wire:click.prevent="backToEmail" class="text-[12px] font-bold text-primary hover:text-primary/80 transition-colors">Ganti Email?</a>
                @endif
            </div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-on-surface-variant">
                    <span class="material-symbols-outlined text-[20px]">mail</span>
                </div>
                <input wire:model="email" id="email" type="email" placeholder="name@example.com" required autofocus
                    class="w-full pl-11 py-4 bg-surface-container-low border-none rounded-xl focus:ring-2 focus:ring-primary/20 focus:bg-surface-bright transition-all text-on-surface font-medium {{ $step === 2 ? 'opacity-60 cursor-not-allowed' : '' }}"
                    {{ $step === 2 ? 'readonly' : '' }} />
            </div>
            @error('email') <span class="text-error text-xs font-bold">{{ $message }}</span> @enderror
        </div>

        @if($step === 1)
        <button class="w-full bg-primary text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2" type="submit">
            <span>Lanjutkan Akses</span>
            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </button>

        <div class="relative flex py-2 items-center mt-6">
            <div class="flex-grow border-t border-outline"></div>
            <span class="flex-shrink-0 mx-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Atau masuk dengan</span>
            <div class="flex-grow border-t border-outline"></div>
        </div>

        <a href="{{ route('auth.google') }}" class="w-full bg-surface-container-low hover:bg-surface-container-high text-on-surface font-bold py-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-3 mt-4">
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
            </svg>
            <span>Login With Google</span>
        </a>
        @endif

        @if($step === 2)
        <!-- Kolom Password Muncul Sebagai Tambahan -->
        <div class="space-y-1 mt-6 animate-fade-in-up">
            <div class="flex justify-between items-center">
                <label class="text-[12px] font-bold text-primary uppercase tracking-widest block" for="password">Security Code</label>
                @if (Route::has('password.request'))
                <a class="text-[12px] font-bold text-primary hover:text-primary/80 transition-colors" href="{{ route('password.request') }}" wire:navigate>Forgot Password?</a>
                @endif
            </div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-on-surface-variant">
                    <span class="material-symbols-outlined text-[20px]">lock</span>
                </div>
                <input wire:model.defer="password" id="password" type="password" placeholder="••••••••" required autofocus
                    class="w-full pl-11 py-4 bg-surface-container-low border-none rounded-xl focus:ring-2 focus:ring-primary/20 focus:bg-surface-bright transition-all text-on-surface font-medium" />
            </div>
            @error('password') <span class="text-error text-xs font-bold">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center mt-4">
            <input wire:model.defer="remember" class="w-5 h-5 text-primary bg-surface-container-low border-none rounded-md focus:ring-primary focus:ring-offset-0" id="remember" type="checkbox" />
            <label class="ml-3 text-sm font-medium text-on-surface-variant select-none" for="remember">Stay signed in for 30 days</label>
        </div>

        <button class="w-full bg-primary text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2 mt-6" type="submit">
            <span>Authenticate Portal</span>
            <span class="material-symbols-outlined text-[18px]">verified_user</span>
        </button>
        @endif
    </form>

    @if($step === 1 && Route::has('register'))
    <!-- Registration CTA -->
    <div class="mt-8 pt-6 border-t border-outline text-center">
        <p class="text-on-surface-variant text-sm font-medium">New to <span class="text-[#000000] font-black">SIG</span> <span class="text-primary font-black">Academy</span>?<br><a class="text-primary font-bold hover:underline" href="{{ route('register') }}" wire:navigate>Register your Account</a></p>
    </div>
    @endif
    @endif
</div>