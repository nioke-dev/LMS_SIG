<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TnaSubmission;

class TnaSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 'TNA-2024-GRS-001',
                'title' => 'Strategic Leadership Excellence',
                'submission_date' => '2024-01-12',
                'category' => 'Leadership',
                'urgency' => 'High',
                'status' => 'approved',
                'description' => 'Pelatihan kepemimpinan strategis untuk level manajerial guna meningkatkan kemampuan decision-making dan strategic thinking di era transformasi digital.',
                'participants' => 2,
                'participants_list' => [3, 4],
                'feedback' => 'Program sangat relevan dengan KPI perusahaan tahun ini. Disetujui sepenuhnya.',
                'feedback_by' => 'Dr. Ir. H. Sucipto, M.M.',
                'documents' => [
                    ['name' => 'Gap_Analysis_Leadership.pdf', 'type' => 'pdf', 'size' => '2.4 MB'],
                    ['name' => 'Kurikulum_Leadership.xlsx', 'type' => 'xlsx', 'size' => '340 KB']
                ]
            ],
            [
                'id' => 'TNA-2024-GRS-002',
                'title' => 'Advanced Data Analytics for Cement Industry',
                'submission_date' => '2024-01-15',
                'category' => 'IT & Digital',
                'urgency' => 'High',
                'status' => 'review',
                'description' => 'Pelatihan analisis data lanjutan untuk optimasi proses produksi semen menggunakan tools Power BI dan Python.',
                'participants' => 3,
                'participants_list' => [5, 6, 7],
                'documents' => [
                    ['name' => 'Proposal_Data_Analytics.pdf', 'type' => 'pdf', 'size' => '1.8 MB']
                ]
            ],
            [
                'id' => 'TNA-2024-GRS-003',
                'title' => 'Health & Safety Protocols v2.0',
                'submission_date' => '2024-01-20',
                'category' => 'K3 & LH',
                'urgency' => 'High',
                'status' => 'rejected',
                'description' => 'Pembaruan protokol keselamatan kerja sesuai regulasi terbaru Kementerian Ketenagakerjaan.',
                'participants' => 2,
                'participants_list' => [8, 9],
                'documents' => [
                    ['name' => 'Regulasi_K3_2024.pdf', 'type' => 'pdf', 'size' => '5.1 MB']
                ],
                'feedback' => 'Usulan ditolak karena sudah ada pelatihan serupa yang dijadwalkan oleh Dept. K3 pusat di Q2 2024. Silakan koordinasi dengan Pak Hendra di SIG Pusat.',
                'feedback_by' => 'Ir. Bambang Sutrisno'
            ],
            [
                'id' => 'TNA-2024-GRS-004',
                'title' => 'Lean Manufacturing & Six Sigma Green Belt',
                'submission_date' => '2024-02-05',
                'category' => 'Produksi',
                'urgency' => 'Medium',
                'status' => 'approved',
                'description' => 'Sertifikasi Green Belt untuk supervisor produksi agar mampu menerapkan metodologi Lean dan Six Sigma.',
                'participants' => 2,
                'participants_list' => [10, 11],
                'feedback' => 'Disetujui. Fokuskan implementasi pada lini Grinding Mill.',
                'feedback_by' => 'Ir. Agus Wahyudi',
                'documents' => [
                    ['name' => 'Sertifikasi_SixSigma_Proposal.pdf', 'type' => 'pdf', 'size' => '3.2 MB'],
                    ['name' => 'Daftar_Kandidat.xlsx', 'type' => 'xlsx', 'size' => '128 KB']
                ]
            ],
            [
                'id' => 'TNA-2024-GRS-005',
                'title' => 'SAP S/4HANA Migration Training',
                'submission_date' => '2024-02-10',
                'category' => 'IT & Digital',
                'urgency' => 'High',
                'status' => 'approved',
                'description' => 'Pelatihan migrasi sistem ERP ke SAP S/4HANA untuk tim IT dan key users dari setiap departemen.',
                'participants' => 3,
                'participants_list' => [12, 13, 14],
                'feedback' => 'Sangat kritikal untuk kelancaran migrasi. Segera jadwalkan.',
                'feedback_by' => 'Dra. Siti Aminah, M.M.',
                'documents' => [
                    ['name' => 'SAP_Migration_Roadmap.pdf', 'type' => 'pdf', 'size' => '4.7 MB']
                ]
            ],
            [
                'id' => 'TNA-2024-GRS-006',
                'title' => 'Effective Communication & Presentation Skills',
                'submission_date' => '2024-02-18',
                'category' => 'Soft Skills',
                'urgency' => 'Low',
                'status' => 'review',
                'description' => 'Pelatihan kemampuan komunikasi efektif dan presentasi profesional untuk staf level junior.',
                'participants' => 2,
                'participants_list' => [15, 16],
                'documents' => []
            ],
            [
                'id' => 'TNA-2024-GRS-007',
                'title' => 'Environmental Management System ISO 14001',
                'submission_date' => '2024-03-01',
                'category' => 'K3 & LH',
                'urgency' => 'Medium',
                'status' => 'approved',
                'description' => 'Pelatihan sistem manajemen lingkungan sesuai standar ISO 14001:2015 untuk auditor internal.',
                'participants' => 2,
                'participants_list' => [17, 18],
                'feedback' => 'Penting untuk pemeliharaan sertifikat ISO perusahaan.',
                'feedback_by' => 'Ir. Bambang Sutrisno',
                'documents' => [
                    ['name' => 'ISO14001_Checklist.pdf', 'type' => 'pdf', 'size' => '890 KB']
                ]
            ],
            [
                'id' => 'TNA-2024-GRS-008',
                'title' => 'Project Management Professional (PMP) Prep',
                'submission_date' => '2024-03-10',
                'category' => 'Management',
                'urgency' => 'Medium',
                'status' => 'review',
                'description' => 'Kursus persiapan sertifikasi PMP dari PMI untuk project manager dan team lead.',
                'participants' => 2,
                'participants_list' => [19, 20],
                'documents' => [
                    ['name' => 'PMP_Syllabus.pdf', 'type' => 'pdf', 'size' => '1.2 MB']
                ]
            ],
            [
                'id' => 'TNA-2024-GRS-009',
                'title' => 'Cybersecurity Awareness & Best Practices',
                'submission_date' => '2024-03-15',
                'category' => 'IT & Digital',
                'urgency' => 'High',
                'status' => 'approved',
                'description' => 'Program awareness keamanan siber wajib untuk seluruh karyawan guna mencegah phishing dan data breach.',
                'participants' => 2,
                'participants_list' => [21, 22],
                'feedback' => 'Mandatory program. Koordinasikan dengan Dept. IT.',
                'feedback_by' => 'Dr. Ir. H. Sucipto, M.M.',
                'documents' => [
                    ['name' => 'Cybersec_Module.pdf', 'type' => 'pdf', 'size' => '6.3 MB'],
                    ['name' => 'Incident_Report_Q1.xlsx', 'type' => 'xlsx', 'size' => '220 KB']
                ]
            ],
            [
                'id' => 'TNA-2024-GRS-010',
                'title' => 'Predictive Maintenance using IoT Sensors',
                'submission_date' => '2024-03-22',
                'category' => 'Maintenance',
                'urgency' => 'High',
                'status' => 'review',
                'description' => 'Pelatihan teknik predictive maintenance menggunakan sensor IoT dan machine learning.',
                'participants' => 2,
                'participants_list' => [23, 24],
                'documents' => [
                    ['name' => 'IoT_Sensor_Specs.pdf', 'type' => 'pdf', 'size' => '2.1 MB']
                ]
            ],
            [
                'id' => 'TNA-2024-GRS-011',
                'title' => 'Financial Budgeting & Cost Control',
                'submission_date' => '2024-04-02',
                'category' => 'Finance',
                'urgency' => 'Medium',
                'status' => 'approved',
                'description' => 'Workshop pengelolaan anggaran dan kontrol biaya operasional untuk kepala seksi dan supervisor.',
                'participants' => 2,
                'participants_list' => [25, 26],
                'feedback' => 'Sangat diperlukan untuk efisiensi Q3 dan Q4.',
                'feedback_by' => 'Dra. Siti Aminah, M.M.',
                'documents' => [
                    ['name' => 'Budget_Template_2024.xlsx', 'type' => 'xlsx', 'size' => '450 KB']
                ]
            ],
            [
                'id' => 'TNA-2024-GRS-012',
                'title' => 'Warehouse Management & Inventory Optimization',
                'submission_date' => '2024-04-08',
                'category' => 'Logistik',
                'urgency' => 'Low',
                'status' => 'rejected',
                'description' => 'Pelatihan manajemen gudang dan optimasi inventori menggunakan metode ABC dan JIT.',
                'participants' => 2,
                'participants_list' => [27, 28],
                'documents' => [
                    ['name' => 'Inventory_Analysis.xlsx', 'type' => 'xlsx', 'size' => '780 KB']
                ],
                'feedback' => 'Budget pelatihan untuk Dept. Logistik sudah terpakai di Q1. Mohon diajukan kembali.',
                'feedback_by' => 'Dra. Siti Aminah, M.M.'
            ],
            [
                'id' => 'TNA-2024-GRS-013',
                'title' => 'Coaching & Mentoring for Line Managers',
                'submission_date' => '2024-04-15',
                'category' => 'Leadership',
                'urgency' => 'Medium',
                'status' => 'approved',
                'description' => 'Program coaching dan mentoring untuk line manager agar mampu membina dan mengembangkan tim.',
                'participants' => 2,
                'participants_list' => [29, 30],
                'feedback' => 'Program bagus untuk regenerasi kepemimpinan.',
                'feedback_by' => 'Dr. Ir. H. Sucipto, M.M.',
                'documents' => [
                    ['name' => 'Coaching_Framework.pdf', 'type' => 'pdf', 'size' => '1.5 MB']
                ]
            ],
            [
                'id' => 'TNA-2024-GRS-014',
                'title' => 'Quality Control Circle (QCC) Facilitation',
                'submission_date' => '2024-04-20',
                'category' => 'Quality',
                'urgency' => 'Low',
                'status' => 'review',
                'description' => 'Pelatihan fasilitasi QCC untuk meningkatkan partisipasi karyawan dalam continuous improvement.',
                'participants' => 2,
                'participants_list' => [3, 10],
                'documents' => []
            ],
            [
                'id' => 'TNA-2024-GRS-015',
                'title' => 'Electrical Safety & Arc Flash Prevention',
                'submission_date' => '2024-05-05',
                'category' => 'K3 & LH',
                'urgency' => 'High',
                'status' => 'approved',
                'description' => 'Pelatihan keselamatan kelistrikan dan pencegahan arc flash untuk teknisi dan engineer elektrikal.',
                'participants' => 2,
                'participants_list' => [4, 11],
                'feedback' => 'Sangat kritikal untuk keselamatan teknisi di lapangan.',
                'feedback_by' => 'Ir. Bambang Sutrisno',
                'documents' => [
                    ['name' => 'Arc_Flash_Standard.pdf', 'type' => 'pdf', 'size' => '3.8 MB']
                ]
            ],
            [
                'id' => 'TNA-2024-GRS-016',
                'title' => 'Business Process Reengineering (BPR)',
                'submission_date' => '2024-05-12',
                'category' => 'Management',
                'urgency' => 'Medium',
                'status' => 'review',
                'description' => 'Workshop rekayasa ulang proses bisnis untuk meningkatkan efisiensi operasional lintas departemen.',
                'participants' => 2,
                'participants_list' => [5, 12],
                'documents' => [
                    ['name' => 'BPR_Proposal.pdf', 'type' => 'pdf', 'size' => '2.6 MB']
                ]
            ],
            [
                'id' => 'TNA-2024-GRS-017',
                'title' => 'Advanced Excel & Power Query for Reporting',
                'submission_date' => '2024-05-20',
                'category' => 'IT & Digital',
                'urgency' => 'Low',
                'status' => 'approved',
                'description' => 'Pelatihan Excel lanjutan dan Power Query untuk automasi laporan bagi staf administrasi.',
                'participants' => 2,
                'participants_list' => [2, 13],
                'feedback' => 'Disetujui untuk meningkatkan produktivitas admin.',
                'feedback_by' => 'Dra. Siti Aminah, M.M.',
                'documents' => []
            ],
            [
                'id' => 'TNA-2024-GRS-018',
                'title' => 'Negotiation Skills for Procurement Team',
                'submission_date' => '2024-06-01',
                'category' => 'Soft Skills',
                'urgency' => 'Medium',
                'status' => 'rejected',
                'description' => 'Pelatihan teknik negosiasi untuk tim procurement agar mendapatkan deal terbaik dari vendor.',
                'participants' => 2,
                'participants_list' => [6, 14],
                'documents' => [
                    ['name' => 'Vendor_Benchmark.pdf', 'type' => 'pdf', 'size' => '1.1 MB']
                ],
                'feedback' => 'Materi ini sudah termasuk dalam onboarding.',
                'feedback_by' => 'Ir. Bambang Sutrisno'
            ],
            [
                'id' => 'TNA-2024-GRS-019',
                'title' => 'Cement Chemistry & Grinding Optimization',
                'submission_date' => '2024-06-10',
                'category' => 'Produksi',
                'urgency' => 'High',
                'status' => 'review',
                'description' => 'Pelatihan teknis kimia semen dan optimasi proses grinding untuk engineer proses.',
                'participants' => 2,
                'participants_list' => [7, 15],
                'documents' => [
                    ['name' => 'Grinding_Performance.xlsx', 'type' => 'xlsx', 'size' => '560 KB']
                ]
            ],
            [
                'id' => 'TNA-2024-GRS-020',
                'title' => 'Digital Marketing & Employer Branding',
                'submission_date' => '2024-06-18',
                'category' => 'Marketing',
                'urgency' => 'Low',
                'status' => 'approved',
                'description' => 'Pelatihan strategi digital marketing dan employer branding untuk tim HR dan Marketing.',
                'participants' => 2,
                'participants_list' => [8, 16],
                'feedback' => 'Program sangat inovatif. Silakan dieksekusi.',
                'feedback_by' => 'Dr. Ir. H. Sucipto, M.M.',
                'documents' => [
                    ['name' => 'Branding_Strategy.pdf', 'type' => 'pdf', 'size' => '2.0 MB']
                ]
            ],
        ];

        foreach ($data as $item) {
            TnaSubmission::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
