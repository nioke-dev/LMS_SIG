<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TnaSubmission;
use App\Models\User;
use Carbon\Carbon;

class TnaScenarioSeeder extends Seeder
{
    /**
     * Run the database seeds to create a realistic TNA scenario.
     */
    public function run(): void
    {
        // Get some real employee IDs for participants
        $employees = User::where('role', User::ROLE_EMPLOYEE)->pluck('id')->toArray();
        
        $scenarios = [
            // 1. DRAFT SCENARIO (Masih dalam tahap konsep oleh LC)
            [
                'id' => 'TNA-2024-DRAFT-001',
                'title' => '[Draft] Sertifikasi Pemeliharaan Trafo Tegangan Tinggi',
                'submission_date' => Carbon::now()->subDays(2),
                'category' => 'Maintenance',
                'urgency' => 'High',
                'status' => 'draft',
                'description' => 'Draft usulan untuk sertifikasi teknisi elektrikal di Unit Maintenance Tuban. Fokus pada standar keselamatan terbaru.',
                'participants' => 3,
                'participants_list' => array_slice($employees, 0, 3),
                'documents' => [],
            ],

            // 2. REVIEW SCENARIO (Baru dikirim LC, sedang menunggu AC)
            [
                'id' => 'TNA-2024-REV-001',
                'title' => 'Optimalisasi Rantai Pasok Logistik Semen Padang',
                'submission_date' => Carbon::now()->subDays(5),
                'category' => 'Logistik',
                'urgency' => 'Medium',
                'status' => 'review',
                'description' => 'Pelatihan strategis untuk tim logistik guna meminimalkan biaya distribusi di wilayah Sumatera Barat.',
                'participants' => 5,
                'participants_list' => array_slice($employees, 3, 5),
                'documents' => [
                    ['name' => 'Analisis_Biaya_Logistik_2024.pdf', 'type' => 'pdf', 'size' => '1.5 MB']
                ],
            ],

            // 3. APPROVED SCENARIO (Sudah disetujui AC)
            [
                'id' => 'TNA-2024-APP-001',
                'title' => 'Workshop Digital Transformation & AI Implementation',
                'submission_date' => Carbon::now()->subMonth(),
                'category' => 'IT & Digital',
                'urgency' => 'High',
                'status' => 'approved',
                'description' => 'Implementasi teknologi AI untuk monitoring kualitas klinker secara real-time.',
                'participants' => 4,
                'participants_list' => array_slice($employees, 8, 4),
                'feedback' => 'Sangat sejalan dengan inisiatif digitalisasi korporat. Mohon koordinasi dengan tim SISI untuk teknisnya.',
                'feedback_by' => 'Andi Prasetyo',
                'documents' => [
                    ['name' => 'Roadmap_Digital_SIG.pdf', 'type' => 'pdf', 'size' => '3.2 MB']
                ],
            ],

            // 4. REJECTED SCENARIO (Ditolak oleh AC dengan alasan)
            [
                'id' => 'TNA-2024-REJ-001',
                'title' => 'Outbound Team Building ke Bali',
                'submission_date' => Carbon::now()->subDays(10),
                'category' => 'Soft Skills',
                'urgency' => 'Low',
                'status' => 'rejected',
                'description' => 'Kegiatan penyegaran untuk meningkatkan kekompakan tim setelah target Q1 tercapai.',
                'participants' => 10,
                'participants_list' => array_slice($employees, 12, 10),
                'feedback' => 'Maaf, usulan ditolak karena kebijakan efisiensi biaya perjalanan dinas. Disarankan untuk mengadakan team building di area kerja masing-masing (lokal).',
                'feedback_by' => 'Andi Prasetyo',
                'documents' => [],
            ],

            // 5. REVIEW SCENARIO (Sertifikasi K3 - Urgent)
            [
                'id' => 'TNA-2024-REV-002',
                'title' => 'Recertification Auditor Internal ISO 45001',
                'submission_date' => Carbon::now()->subDay(),
                'category' => 'K3 & LH',
                'urgency' => 'High',
                'status' => 'review',
                'description' => 'Sertifikasi ulang wajib untuk tim auditor internal guna mempertahankan standar keselamatan kerja internasional.',
                'participants' => 2,
                'participants_list' => array_slice($employees, 0, 2),
                'documents' => [
                    ['name' => 'Sertifikat_Lama_Expired.pdf', 'type' => 'pdf', 'size' => '800 KB']
                ],
            ],
        ];

        foreach ($scenarios as $item) {
            TnaSubmission::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
