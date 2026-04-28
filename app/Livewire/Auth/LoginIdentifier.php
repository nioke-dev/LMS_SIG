<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginIdentifier extends Component
{
    public $email = '';
    public $password = '';
    public $step = 1; // 1: Email, 2: Password, 3: Success Sent
    public $remember = false;
    public $statusMessage = '';

    protected $rules = [
        'email' => 'required|email',
    ];

    public function checkEmail()
    {
        $this->validateOnly('email');

        $user = User::where('email', $this->email)->first();

        if ($user) {
            // Deteksi Karyawan Belum Setup Password (null)
            if (is_null($user->password)) {
                // Eksekusi Fortify broker mengirim email reset/setup password
                Password::broker()->sendResetLink(['email' => $this->email]);

                $this->step = 3;
                $this->statusMessage = "Email Karyawan Anda terdaftar, namun belum memiliki password. Tautan (link) untuk melakukan Setup Password telah kami kirim ke " . $this->email;
                return;
            }

            // Pengguna normal yang sudah punya password
            $this->step = 2;
        } else {
            // User Tidak Ditemukan
            if (Str::endsWith($this->email, '@sig.id') || Str::endsWith($this->email, '@student.polinema.ac.id')) {
                // Karyawan tapi belum di-load oleh HR
                $this->addError('email', 'Email Karyawan Anda belum terdaftar oleh sistem. Silahkan hubungi Learning Administrator atau Admin Helpdesk.');
            } else {
                // Publik yang belum ada akunnya
                $this->addError('email', 'Email ini belum terdaftar. Silakan Registrasi terlebih dahulu.');
            }
        }
    }

    public function loginUser()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            $role = Auth::user()->role;

            // Pengamanan Role-Based (Learner Portal)
            $managementRoles = ['learning_administrator', 'learning_coordinator', 'admin_coordinator', 'sme', 'helpdesk_admin'];
            if (in_array($role, $managementRoles)) {
                Auth::logout();
                return redirect()->route('admin.login')->withErrors(['email' => 'Harap gunakan Portal Management khusus untuk akses staf teknis.']);
            }

            // Redirect Karyawan & Publik secara elegan
            return redirect()->intended($role === 'employee' ? '/employee/dashboard' : '/dashboard');
        }

        $this->addError('password', 'Kata sandi yang Anda masukkan salah.');
    }

    public function backToEmail()
    {
        $this->step = 1;
        $this->password = '';
    }

    public function render()
    {
        return view('livewire.auth.login-identifier');
    }
}
