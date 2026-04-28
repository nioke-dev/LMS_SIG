<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $email = $googleUser->getEmail();
            
            $user = User::where('email', $email)->first();

            // If user exists in DB, evaluate their password setup status first
            if ($user) {
                // [PROTEKSI LAYER 4] Cegah Login SSO jika Password Belum di Setup!
                if (is_null($user->password)) {
                    // Panggil mesin pembuat dan pengirim Token Lupa Password bawaan Laravel
                    Password::broker()->sendResetLink(['email' => $user->email]);

                    return redirect('/login')->with('status', 'Sistem mendeteksi Akun Karyawan Anda belum diaktivasi. Tautan untuk Setup Kata Sandi telah kami kirimkan ke email Anda. Silakan cek kotak masuk Anda.');
                }

                // Update google_id if it's missing
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
                
                Auth::login($user);
                
                // Redirect based on role
                $role = $user->role;
                if (in_array($role, ['learning_administrator', 'learning_coordinator', 'admin_coordinator', 'sme', 'helpdesk_admin'])) {
                    return redirect()->intended('/admin/login');
                }
                
                return redirect()->intended($role === 'employee' ? '/employee/dashboard' : '/dashboard');
            }

            // User does NOT exist in DB. We have to evaluate their domain.
            if (Str::endsWith($email, '@sig.id') || Str::endsWith($email, '@student.polinema.ac.id')) {
                // Employee Domain Logic
                // We decided to reject because employees MUST be seeded/imported by HR/LA first with their proper unit/department context.
                return redirect('/login')->withErrors([
                    'email' => 'Karyawan harus terdaftar di sistem melalui sinkronisasi HR sebelum dapat mengakses sistem. Silakan hubungi Learning Coordinator Anda.',
                ]);
            } else {
                // Public Domain Logic - Auto Create Public Account
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $email,
                    'google_id' => $googleUser->getId(),
                    'role' => User::ROLE_PUBLIC,
                    'password' => bcrypt(Str::random(16)), // Required field, random password
                ]);

                Auth::login($newUser);

                return redirect()->intended('/dashboard');
            }

        } catch (\Exception $e) {
            return redirect('/login')->withErrors([
                'email' => 'Terjadi kesalahan saat login menggunakan Google. Pesan: ' . $e->getMessage(),
            ]);
        }
    }
}
