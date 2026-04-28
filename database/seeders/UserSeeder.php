<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the users table with demo data.
     */
    public function run(): void
    {
        // Get organization nodes for linking
        $unitCLD = Organization::where('code', 'SIG-UNIT-CLD')->first();
        $unitProd = Organization::where('code', 'SIG-UNIT-PROD1')->first();

        // Common factory data
        $commonData = [
            'password' => null, // Default for employees
            'work_location' => 'Tuban',
        ];

        // 1. MAIN USERS (1-10)
        $users = [
            [
                'name'            => 'Nurul Mustofa',
                'nik'             => '19920001',
                'email'           => 'nurul.mustofa@sig.co.id',
                'role'            => User::ROLE_LEARNING_COORDINATOR,
                'position'        => 'Senior Manager of Competency & Learning Design',
                'organization_id' => $unitCLD?->id,
            ],
            [
                'name'            => 'Andi Prasetyo',
                'nik'             => '19920002',
                'email'           => 'andi.prasetyo@sig.co.id',
                'role'            => User::ROLE_LEARNING_ADMINISTRATOR,
                'position'        => 'Manager of Learning Development',
                'organization_id' => $unitCLD?->id,
            ],
            array_merge($commonData, [
                'name'            => 'Budi Santoso',
                'nik'             => '8901234',
                'email'           => 'budi.santoso@sig.id',
                'role'            => User::ROLE_EMPLOYEE,
                'position'        => 'Senior Manager of Talent Management',
                'organization_id' => $unitCLD?->id,
            ]),
            array_merge($commonData, [
                'name'            => 'Agung Setyawan',
                'nik'             => '8901235',
                'email'           => 'agung.setyawan@sig.id',
                'role'            => User::ROLE_EMPLOYEE,
                'position'        => 'Specialist Strategic Planning',
                'organization_id' => $unitCLD?->id,
            ]),
            array_merge($commonData, [
                'name'            => 'Rina Maharani',
                'nik'             => '8901236',
                'email'           => 'rina.maharani@sig.id',
                'role'            => User::ROLE_EMPLOYEE,
                'position'        => 'Officer Learning & Development',
                'organization_id' => $unitCLD?->id,
            ]),
            array_merge($commonData, [
                'name'            => 'Dewi Anggraini',
                'nik'             => '8901237',
                'email'           => 'dewi.anggraini@sig.id',
                'role'            => User::ROLE_EMPLOYEE,
                'position'        => 'Senior Specialist Competency',
                'organization_id' => $unitCLD?->id,
            ]),
            array_merge($commonData, [
                'name'            => 'Siti Nurhaliza',
                'nik'             => '8901238',
                'email'           => 'siti.nurhaliza@sig.id',
                'role'            => User::ROLE_EMPLOYEE,
                'position'        => 'Content Development Specialist',
                'organization_id' => $unitCLD?->id,
            ]),
            array_merge($commonData, [
                'name'            => 'Putri Rahayu',
                'nik'             => '8901239',
                'email'           => 'putri.rahayu@sig.id',
                'role'            => User::ROLE_EMPLOYEE,
                'position'        => 'Multimedia Designer Officer',
                'organization_id' => $unitCLD?->id,
            ]),
            array_merge($commonData, [
                'name'            => 'Kartika Sari',
                'nik'             => '8901240',
                'email'           => 'kartika.sari@sig.id',
                'role'            => User::ROLE_EMPLOYEE,
                'position'        => 'Evaluation & Impact Analyst',
                'organization_id' => $unitCLD?->id,
            ]),
            array_merge($commonData, [
                'name'            => 'Ratna Kusuma',
                'nik'             => '8901241',
                'email'           => 'ratna.kusuma@sig.id',
                'role'            => User::ROLE_EMPLOYEE,
                'position'        => 'Officer of Learning Administration',
                'organization_id' => $unitCLD?->id,
            ]),
        ];

        foreach ($users as $user) {
            User::factory()->create($user);
        }

        // 2. ADDITIONAL EMPLOYEES (11-30) to ensure enough participants exist
        User::factory()->count(20)->create([
            'role' => User::ROLE_EMPLOYEE,
            'organization_id' => $unitCLD?->id,
            'position' => 'Staff Learning Design'
        ]);

        // 3. PUBLIC USER
        User::factory()->create([
            'name'  => 'Eka Siswa Publik',
            'email' => 'eka@gmail.com',
            'role'  => User::ROLE_PUBLIC,
            'nik'   => '00000001',
            'position' => 'Umum / Eksternal',
        ]);
    }
}
