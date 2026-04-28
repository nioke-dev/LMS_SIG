<?php

namespace App\DTOs;

use App\Models\User;

class ParticipantDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $nik,
        public string $email,
        public string $jabatan,
        public string $initials,
        public string $avatarClass,
        public string $dept,
        public string $phone = '-',
        public string $joinDate = '01/2024'
    ) {}

    /**
     * Factory method to create DTO from User model
     */
    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            nik: (string) $user->nik,
            email: $user->email,
            jabatan: $user->position ?? 'Karyawan',
            initials: self::generateInitials($user->name),
            avatarClass: self::getAvatarClass($user->id),
            dept: $user->organization->name ?? 'Unknown',
            phone: '08' . rand(10, 99) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999), // Mock phone for now
            joinDate: '01/2024'
        );
    }

    /**
     * Logic for generating initials (Encapsulated)
     */
    private static function generateInitials(string $name): string
    {
        $words = explode(' ', $name);
        $initials = '';
        foreach ($words as $w) {
            $initials .= strtoupper($w[0]);
        }
        return substr($initials, 0, 2);
    }

    /**
     * Logic for deterministic avatar color based on ID (Encapsulated)
     */
    private static function getAvatarClass(int $id): string
    {
        $colors = [
            'bg-red-100 text-red-600', 'bg-blue-100 text-blue-600', 'bg-emerald-100 text-emerald-600',
            'bg-amber-100 text-amber-700', 'bg-violet-100 text-violet-600', 'bg-pink-100 text-pink-600',
            'bg-cyan-100 text-cyan-700', 'bg-orange-100 text-orange-600', 'bg-teal-100 text-teal-700', 'bg-indigo-100 text-indigo-600',
        ];
        return $colors[$id % count($colors)];
    }

    /**
     * Convert to array for frontend/JSON
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'nik' => $this->nik,
            'email' => $this->email,
            'jabatan' => $this->jabatan,
            'initials' => $this->initials,
            'avatarClass' => $this->avatarClass,
            'dept' => $this->dept,
            'phone' => $this->phone,
            'joinDate' => $this->joinDate,
        ];
    }
}
