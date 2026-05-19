<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            OrganizationSeeder::class,
            UserSeeder::class,
            TnaCategorySeeder::class,
            TnaSubmissionSeeder::class,
            TnaScenarioSeeder::class,
            TrainingHistorySeeder::class,
            TrainingBlueprintSeeder::class,
        ]);
    }
}
