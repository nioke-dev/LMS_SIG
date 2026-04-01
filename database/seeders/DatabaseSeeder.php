<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Learning Administrator', 'email' => 'learning-admin@example.com', 'role' => User::ROLE_LEARNING_ADMINISTRATOR],
            ['name' => 'Learning Coordinator', 'email' => 'learning-coordinator@example.com', 'role' => User::ROLE_LEARNING_COORDINATOR],
            ['name' => 'Admin Coordinator', 'email' => 'admin-coordinator@example.com', 'role' => User::ROLE_ADMIN_COORDINATOR],
            ['name' => 'Subject Matter Expert', 'email' => 'sme@example.com', 'role' => User::ROLE_SME],
            ['name' => 'Employee User', 'email' => 'employee@example.com', 'role' => User::ROLE_EMPLOYEE],
            ['name' => 'Public User', 'email' => 'public@example.com', 'role' => User::ROLE_PUBLIC],
            ['name' => 'Helpdesk Administrator', 'email' => 'helpdesk@example.com', 'role' => User::ROLE_HELPDESK_ADMIN],
        ];

        foreach ($users as $user) {
            User::factory()->create($user);
        }
    }
}
