<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Check if the user has the learning administrator role.
     */
    public function isLearningAdministrator(): bool
    {
        return $this->role === 'learning_administrator';
    }

    /**
     * Check if the user has the learning coordinator role.
     */
    public function isLearningCoordinator(): bool
    {
        return $this->role === 'learning_coordinator';
    }

    /**
     * Check if the user has the admin coordinator role.
     */
    public function isAdminCoordinator(): bool
    {
        return $this->role === 'admin_coordinator';
    }

    /**
     * Check if the user has the SME role.
     */
    public function isSME(): bool
    {
        return $this->role === 'sme';
    }

    /**
     * Check if the user has the employee role.
     */
    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    /**
     * Check if the user has the public role.
     */
    public function isPublic(): bool
    {
        return $this->role === 'public';
    }

    /**
     * Check if the user has the helpdesk admin role.
     */
    public function isHelpdeskAdmin(): bool
    {
        return $this->role === 'helpdesk_admin';
    }
}
