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

#[Fillable(['name', 'nik', 'email', 'password', 'role', 'position', 'google_id', 'work_location', 'organization_id'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public const ROLE_LEARNING_ADMINISTRATOR = 'learning_administrator';

    public const ROLE_LEARNING_COORDINATOR = 'learning_coordinator';

    public const ROLE_ADMIN_COORDINATOR = 'admin_coordinator';

    public const ROLE_SME = 'sme';

    public const ROLE_EMPLOYEE = 'employee';

    public const ROLE_PUBLIC = 'public';

    public const ROLE_HELPDESK_ADMIN = 'helpdesk_admin';

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
     * Get the supported application roles.
     *
     * @return list<string>
     */
    public static function roles(): array
    {
        return [
            self::ROLE_LEARNING_ADMINISTRATOR,
            self::ROLE_LEARNING_COORDINATOR,
            self::ROLE_ADMIN_COORDINATOR,
            self::ROLE_SME,
            self::ROLE_EMPLOYEE,
            self::ROLE_PUBLIC,
            self::ROLE_HELPDESK_ADMIN,
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
        return $this->role === self::ROLE_LEARNING_ADMINISTRATOR;
    }

    /**
     * Check if the user has the learning coordinator role.
     */
    public function isLearningCoordinator(): bool
    {
        return $this->role === self::ROLE_LEARNING_COORDINATOR;
    }

    /**
     * Check if the user has the admin coordinator role.
     */
    public function isAdminCoordinator(): bool
    {
        return $this->role === self::ROLE_ADMIN_COORDINATOR;
    }

    /**
     * Check if the user has the SME role.
     */
    public function isSME(): bool
    {
        return $this->role === self::ROLE_SME;
    }

    /**
     * Check if the user has the employee role.
     */
    public function isEmployee(): bool
    {
        return $this->role === self::ROLE_EMPLOYEE;
    }

    /**
     * Check if the user has the public role.
     */
    public function isPublic(): bool
    {
        return $this->role === self::ROLE_PUBLIC;
    }

    /**
     * Check if the user has the helpdesk admin role.
     */
    public function isHelpdeskAdmin(): bool
    {
        return $this->role === self::ROLE_HELPDESK_ADMIN;
    }

    /**
     * Get the full organizational hierarchy path as a collection of Organization models.
     */
    public function getOrganizationPath()
    {
        if (!$this->organization) {
            return collect([]);
        }

        $path = collect([]);
        $current = $this->organization()->with('level')->first();
        
        while ($current) {
            $path->push($current);
            $current = $current->parent()->with('level')->first();
        }

        return $path->reverse()->values();
    }

    /**
     * Get the shortened organizational path (PT + Last 2 Levels).
     * Returns a collection of Organization models or "..." strings.
     */
    public function getShortOrganizationPath()
    {
        $fullPath = $this->getOrganizationPath();
        $count = $fullPath->count();

        if ($count <= 3) {
            return $fullPath;
        }

        return collect([
            $fullPath->first(),
            '...',
            $fullPath->get($count - 2),
            $fullPath->last()
        ]);
    }
}
