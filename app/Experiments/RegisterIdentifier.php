<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class RegisterIdentifier extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $terms = false;

    // State Variables for Alerts
    public $employeeExists = false;
    public $employeeNotRegistered = false;
    public $publicExists = false;

    public function registerUser()
    {
        // Reset state
        $this->reset(['employeeExists', 'employeeNotRegistered', 'publicExists']);

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|same:password_confirmation',
            'terms' => 'accepted'
        ]);

        $userExists = User::where('email', $this->email)->first();

        // 1. Sniff if domain is Corporate
        if (Str::endsWith($this->email, '@sig.id') || Str::endsWith($this->email, '@student.polinema.ac.id') || Str::endsWith($this->email, '@sig-industrial.com')) {
            if ($userExists) {
                // Skenario 1: Karyawan sah
                $this->employeeExists = true;
                return;
            } else {
                // Skenario 2: Karyawan belum diinput HR
                $this->employeeNotRegistered = true;
                return;
            }
        }

        // 2. Public Registration
        if ($userExists) {
            // Skenario 4: Publik Lama (sudah ada)
            $this->publicExists = true;
            return;
        }

        // Skenario 3: Publik Baru
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'learner', // Default role for B2C external learner
        ]);

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register-identifier');
    }
}
