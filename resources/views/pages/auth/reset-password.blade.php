<x-layouts::auth title="Setup / Reset Password">
    <div class="flex flex-col gap-6">
        <x-auth-header title="Setup atau Reset Password" description="Silakan masukkan password baru Anda untuk mengaktifkan akses ke sistem." />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Email Address -->
            <flux:input
                name="email"
                value="{{ request('email') }}"
                label="Email ID Anda"
                type="email"
                required
                readonly
                autocomplete="email"
                class="opacity-70 cursor-not-allowed"
            />

            <!-- Password -->
            <flux:input
                name="password"
                label="Password Baru"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Masukkan kombinasi sandi yang kuat"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                label="Konfirmasi Password"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Ulangi sandi baru Anda"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="reset-password-button">
                    Simpan Password Baru
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>
