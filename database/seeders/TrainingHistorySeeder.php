<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TrainingHistory;

class TrainingHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $smes = User::whereNotNull('organization_id')->take(6)->get();

        $histories = [
            [
                [
                    'training_name' => 'Pelatihan Ahli Kiln Tuban Angkatan IV',
                    'type' => 'In-House Training',
                    'date' => '12-14 Maret 2024',
                    'rating' => 4.9,
                    'participants_count' => 28,
                    'eval_predicate' => 'Predikat: Sangat Memuaskan'
                ],
                [
                    'training_name' => 'Workshop Troubleshooting Raw Mill',
                    'type' => 'Workshop Operasional',
                    'date' => '05-07 Oktober 2023',
                    'rating' => 4.8,
                    'participants_count' => 35,
                    'eval_predicate' => 'Predikat: Sangat Memuaskan'
                ]
            ],
            [
                [
                    'training_name' => 'Executive Leadership Development Program',
                    'type' => 'Executive Program',
                    'date' => '20-22 Mei 2024',
                    'rating' => 4.9,
                    'participants_count' => 18,
                    'eval_predicate' => 'Predikat: Sangat Memuaskan'
                ],
                [
                    'training_name' => 'Agile Culture & Change Management',
                    'type' => 'Public Training',
                    'date' => '10-12 November 2023',
                    'rating' => 4.7,
                    'participants_count' => 42,
                    'eval_predicate' => 'Predikat: Memuaskan'
                ]
            ],
            [
                [
                    'training_name' => 'Sistem Manajemen Keselamatan Pertambangan (SMKP)',
                    'type' => 'In-House Training',
                    'date' => '02-04 April 2024',
                    'rating' => 4.8,
                    'participants_count' => 30,
                    'eval_predicate' => 'Predikat: Sangat Memuaskan'
                ],
                [
                    'training_name' => 'Audit Internal K3 & Lingkungan ISO 14001',
                    'type' => 'Workshop Operasional',
                    'date' => '15-17 Agustus 2023',
                    'rating' => 4.9,
                    'participants_count' => 24,
                    'eval_predicate' => 'Predikat: Sangat Memuaskan'
                ]
            ],
            [
                [
                    'training_name' => 'Supply Chain & Logistics Optimization',
                    'type' => 'Executive Program',
                    'date' => '10-12 Juni 2024',
                    'rating' => 4.8,
                    'participants_count' => 20,
                    'eval_predicate' => 'Predikat: Sangat Memuaskan'
                ],
                [
                    'training_name' => 'Warehouse Inventory Control Masterclass',
                    'type' => 'Public Training',
                    'date' => '20-22 September 2023',
                    'rating' => 4.6,
                    'participants_count' => 38,
                    'eval_predicate' => 'Predikat: Memuaskan'
                ]
            ],
            [
                [
                    'training_name' => 'Advanced PLC & SCADA Programming',
                    'type' => 'In-House Training',
                    'date' => '05-08 Mei 2024',
                    'rating' => 4.9,
                    'participants_count' => 15,
                    'eval_predicate' => 'Predikat: Sangat Memuaskan'
                ],
                [
                    'training_name' => 'Industrial Automation & Robotics Maintenance',
                    'type' => 'Workshop Operasional',
                    'date' => '12-14 Desember 2023',
                    'rating' => 4.8,
                    'participants_count' => 22,
                    'eval_predicate' => 'Predikat: Sangat Memuaskan'
                ]
            ],
            [
                [
                    'training_name' => 'Strategic Human Capital Management',
                    'type' => 'Executive Program',
                    'date' => '01-03 Juli 2024',
                    'rating' => 4.9,
                    'participants_count' => 16,
                    'eval_predicate' => 'Predikat: Sangat Memuaskan'
                ],
                [
                    'training_name' => 'Effective KPI Alignment & Performance Appraisal',
                    'type' => 'Public Training',
                    'date' => '14-16 November 2023',
                    'rating' => 4.7,
                    'participants_count' => 45,
                    'eval_predicate' => 'Predikat: Memuaskan'
                ]
            ],
        ];

        foreach ($smes as $index => $sme) {
            $userHistories = $histories[$index % count($histories)];
            foreach ($userHistories as $item) {
                $item['user_id'] = $sme->id;
                TrainingHistory::firstOrCreate([
                    'user_id' => $sme->id,
                    'training_name' => $item['training_name']
                ], $item);
            }
        }
    }
}
