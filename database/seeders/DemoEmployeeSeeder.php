<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DemoEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            // Dept of Talent Management (ID 4)
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad.fauzi@sig.id', 'org_id' => 4, 'unit' => 'Talent Management'],
            ['name' => 'Siti Aminah', 'email' => 'siti.aminah@sig.id', 'org_id' => 4, 'unit' => 'Talent Management'],
            ['name' => 'Bambang Sudarsono', 'email' => 'bambang.s@sig.id', 'org_id' => 4, 'unit' => 'Talent Management'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi.l@sig.id', 'org_id' => 4, 'unit' => 'Talent Management'],
            ['name' => 'Eko Prasetyo', 'email' => 'eko.p@sig.id', 'org_id' => 4, 'unit' => 'Talent Management'],

            // Unit of Competency Development (ID 5)
            ['name' => 'Ferry Irawan', 'email' => 'ferry.i@sig.id', 'org_id' => 5, 'unit' => 'Competency Dev'],
            ['name' => 'Gita Gutawa', 'email' => 'gita.g@sig.id', 'org_id' => 5, 'unit' => 'Competency Dev'],
            ['name' => 'Hendra Setiawan', 'email' => 'hendra.s@sig.id', 'org_id' => 5, 'unit' => 'Competency Dev'],
            ['name' => 'Indah Permata', 'email' => 'indah.p@sig.id', 'org_id' => 5, 'unit' => 'Competency Dev'],
            ['name' => 'Joko Widodo', 'email' => 'joko.w@sig.id', 'org_id' => 5, 'unit' => 'Competency Dev'],
        ];

        foreach ($employees as $emp) {
            User::updateOrCreate(
                ['email' => $emp['email']],
                [
                    'name' => $emp['name'],
                    'password' => Hash::make('password'),
                    'role' => User::ROLE_EMPLOYEE,
                    'organization_id' => $emp['org_id'],
                    'work_location' => 'Gresik',
                ]
            );
        }
    }
}
