<?php

namespace Database\Seeders;

use App\Models\TrainingBlueprint;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TrainingBlueprintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Pastikan folder penyimpanan publik tna_documents tersedia
        $storageDir = storage_path('app/public/tna_documents');
        if (!file_exists($storageDir)) {
            mkdir($storageDir, 0777, true);
        }

        // a. Video MP4 Dummy (Mengambil dari videoplayback_sig.mp4 jika ada)
        $videoPath = $storageDir . '/dummy_video_masterclass.mp4';
        if (!file_exists($videoPath)) {
            $sourceVideo = public_path('video/videoplayback_sig.mp4');
            if (file_exists($sourceVideo)) {
                copy($sourceVideo, $videoPath);
            } else {
                file_put_contents($videoPath, 'DUMMY_MP4_CONTENT');
            }
        }

        // b. PDF Dummy
        $pdfPath = $storageDir . '/Panduan_Kalibrasi_Vibrasi.pdf';
        if (!file_exists($pdfPath)) {
            $sourcePdf = $storageDir . '/dxbKGz8X9CvhS8IbyFd0AamgF5c7DKVCDsUaRTI2.pdf';
            if (file_exists($sourcePdf)) {
                copy($sourcePdf, $pdfPath);
            } else {
                $minimalPdf = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R /Resources << /Font << /F1 4 0 R >> >> /MediaBox [0 0 612 792] /Contents 5 0 R >>\nendobj\n4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n5 0 obj\n<< /Length 44 >>\nstream\nBT\n/F1 24 Tf\n100 700 Td\n(LMS SIG - Panduan Kalibrasi) Tj\nET\nendstream\nendobj\ntrailer\n<< /Root 1 0 R >>\n%%EOF";
                file_put_contents($pdfPath, $minimalPdf);
            }
        }

        // c. Excel XLSX Dummy
        $xlsxPath = $storageDir . '/Worksheet_Heat_Balance.xlsx';
        if (!file_exists($xlsxPath)) {
            $sourceXlsx = $storageDir . '/unfmFNdQgEWEF8PbXJERBr4wKZKktP3uswkgJGZm.xlsx';
            if (file_exists($sourceXlsx)) {
                copy($sourceXlsx, $xlsxPath);
            } else {
                file_put_contents($xlsxPath, 'DUMMY_XLSX_CONTENT');
            }
        }

        // d. Word DOCX Dummy
        $docxPath = $storageDir . '/SOP_Inspeksi_Harian.docx';
        if (!file_exists($docxPath)) {
            $sourceDocx = $storageDir . '/DxFyKHzSjxsiMrmyuMo91jIfsZQ9BmEln7ip5T4K.docx';
            if (file_exists($sourceDocx)) {
                copy($sourceDocx, $docxPath);
            } else {
                file_put_contents($docxPath, 'DUMMY_DOCX_CONTENT');
            }
        }

        // e. ZIP Archive Dummy
        $zipPath = $storageDir . '/Dataset_Spektrum_Tuban4.zip';
        if (!file_exists($zipPath)) {
            file_put_contents($zipPath, base64_decode('UEsFBgAAAAAAAAAAAAAAAAAAAAAAAA=='));
        }

        // f. Image JPG Dummy
        $jpgPath = $storageDir . '/Diagram_Alur_Kiln.jpg';
        if (!file_exists($jpgPath)) {
            $sourceJpg = $storageDir . '/2Q9YLgltsj10QLWmt1rhol7e0ipyw0eCmbhJINaV.jpg';
            if (file_exists($sourceJpg)) {
                copy($sourceJpg, $jpgPath);
            } else {
                file_put_contents($jpgPath, 'DUMMY_JPG_CONTENT');
            }
        }

        // Get Nurul Mustofa as the primary SME for demo purposes
        $sme = User::where('email', 'nurul.mustofa@sig.id')->first() ?? User::first();

        $blueprints = [
            // Skenario A: Baru Ditugaskan (Assigned to SME) - Siap didemo untuk upload materi
            [
                'id' => 'BP-2024-001',
                'tna_submission_ids' => ['TNA-2024-GRS-001', 'TNA-2024-GRS-003'],
                'title' => 'Vibration Analysis Masterclass & Maintenance',
                'category' => 'Maintenance Management',
                'objective' => '<ul><li>Peserta mampu mengidentifikasi spektrum getaran pada mesin rotasi berkecepatan tinggi.</li><li>Peserta mampu melakukan balancing dan alignment dasar pada fan dan pompa industri semen.</li></ul>',
                'content' => '<ul><li>Bab 1: Dasar-dasar Getaran Mekanis dan Pengenalan Sensor Akselerometer.</li><li>Bab 2: Analisis Spektrum dan Deteksi Kerusakan Bearing/Gearbox.</li><li>Bab 3: Studi Kasus Kerusakan Kritis pada ID Fan Kiln Tuban 4.</li></ul>',
                'sme_id' => $sme->id,
                'sme_instructions' => 'Mohon fokuskan materi pada studi kasus riil kerusakan ID Fan Kiln Tuban 4 dan sertakan dataset spektrum getarannya untuk latihan peserta.',
                'need_workshop' => true,
                'workshop_note' => 'Dibutuhkan alat peraga simulator getaran (rotor kit) dan software analisis vibrasi.',
                'deadline' => Carbon::now()->addDays(2),
                'reminder_setting' => 'H-2',
                'reminder_frequency' => 2,
                'distribution' => 'internal',
                'rationalization' => 'Materi bersifat konfidensial karena menyangkut data riil keandalan peralatan pabrik Tuban.',
                'supporting_documents' => [
                    ['name' => 'ISO_10816_Vibration_Standard.pdf', 'url' => '#', 'size' => 2450000],
                    ['name' => 'Laporan_Insiden_Tuban4_2023.pdf', 'url' => '#', 'size' => 4120000]
                ],
                'status' => 'assigned_to_sme',
                'cld_review_notes' => null,
                'sme_submitted_materials' => null,
                'sme_submitted_templates' => null,
                'sme_submission_notes' => null,
            ],
            // Skenario B: Materi Terkirim (Material Submitted) - Siap didemo untuk review CLD
            [
                'id' => 'BP-2024-002',
                'tna_submission_ids' => ['TNA-2024-GRS-002'],
                'title' => 'Advanced Kiln Thermal Optimization',
                'category' => 'Clinker Production',
                'objective' => '<ul><li>Meningkatkan efisiensi termal pembakaran kiln hingga 3%.</li><li>Menjaga kestabilan coating dan kualitas klinker konstan.</li></ul>',
                'content' => '<ul><li>Bab 1: Termodinamika Sistem Kiln dan Pengenalan Burner Multi-Channel.</li><li>Bab 2: Manajemen Bahan Bakar Alternatif (AFR) dan Pengaruhnya terhadap Pembakaran.</li></ul>',
                'sme_id' => $sme->id,
                'sme_instructions' => 'Harap sertakan worksheet kalkulasi neraca panas (heat balance) dalam format Excel.',
                'need_workshop' => false,
                'workshop_note' => null,
                'deadline' => Carbon::now()->addDays(5),
                'reminder_setting' => 'H-3',
                'reminder_frequency' => 1,
                'distribution' => 'public',
                'rationalization' => 'Materi dapat dikomersialkan untuk B2B eksternal industri semen lainnya.',
                'supporting_documents' => [
                    ['name' => 'Kiln_Thermal_Manual.pdf', 'url' => '#', 'size' => 5200000]
                ],
                'status' => 'material_submitted',
                'cld_review_notes' => null,
                'sme_submitted_materials' => [
                    ['name' => 'Slide_Materi_Kiln_Optimization.pptx', 'url' => '#', 'size' => 15400000, 'uploaded_at' => Carbon::now()->subHours(4)->format('Y-m-d H:i'), 'description' => 'Materi presentasi utama lengkap dengan animasi aliran gas kiln.']
                ],
                'sme_submitted_templates' => [
                    ['name' => 'Worksheet_Heat_Balance_Tuban.xlsx', 'url' => '#', 'size' => 1800000, 'uploaded_at' => Carbon::now()->subHours(4)->format('Y-m-d H:i'), 'description' => 'File Excel untuk mendampingi praktik perhitungan di Slide 15.']
                ],
                'sme_submission_notes' => 'Seluruh slide dan worksheet sudah disesuaikan dengan parameter operasional terbaru SIG.',
            ],
            // Skenario C: Butuh Revisi (Revision Required) - Berisi catatan revisi dari CLD
            [
                'id' => 'BP-2024-003',
                'tna_submission_ids' => ['TNA-2024-GRS-005'],
                'title' => 'Safety Protocol for High-Temp Operations',
                'category' => 'Health & Safety',
                'objective' => '<ul><li>Mencapai zero accident pada pekerjaan pemeliharaan di area bersuhu tinggi.</li></ul>',
                'content' => '<ul><li>Bab 1: Identifikasi Bahaya Radiasi Panas dan Prosedur Isolasi Energi (LOTO).</li><li>Bab 2: Pemilihan dan Penggunaan APD Khusus Tahan Panas.</li></ul>',
                'sme_id' => $sme->id,
                'sme_instructions' => 'Pastikan menyertakan video demonstrasi pemakaian baju tahan panas (Aluminized Suit).',
                'need_workshop' => true,
                'workshop_note' => 'Simulasi pemakaian APD di ruang peraga.',
                'deadline' => Carbon::now()->subDays(1), // Overdue
                'reminder_setting' => 'H-1',
                'reminder_frequency' => 3,
                'distribution' => 'internal',
                'rationalization' => 'Kepatuhan K3 internal holding.',
                'supporting_documents' => [
                    ['name' => 'SIG_Safety_Golden_Rules.pdf', 'url' => '#', 'size' => 1200000]
                ],
                'status' => 'revision_required',
                'cld_review_notes' => 'Catatan dari Learning Administrator (CLD Studio): Mohon perbaiki kualitas resolusi pada Video Demonstrasi APD di Slide 8 karena agak buram saat diproyeksikan di layar studio besar.',
                'sme_submitted_materials' => [
                    ['name' => 'Materi_K3_High_Temp.pptx', 'url' => '#', 'size' => 8500000, 'uploaded_at' => Carbon::now()->subDays(2)->format('Y-m-d H:i'), 'description' => 'Slide presentasi K3 suhu tinggi.']
                ],
                'sme_submitted_templates' => [
                    ['name' => 'Video_Demo_APD_Suhu_Tinggi.mp4', 'url' => '#', 'size' => 45000000, 'uploaded_at' => Carbon::now()->subDays(2)->format('Y-m-d H:i'), 'description' => 'Video peragaan untuk diputar di Slide 8.']
                ],
                'sme_submission_notes' => 'Materi dan video peragaan sudah dilampirkan.',
            ],
            // Skenario D: Disetujui Tanpa Revisi (Studio Production) - Siap untuk Masterclass Curriculum Builder
            [
                'id' => 'BP-2024-004',
                'tna_submission_ids' => ['TNA-2024-GRS-004'],
                'title' => 'Preventive Maintenance untuk Raw Mill Tuban 1',
                'category' => 'Maintenance Management',
                'objective' => '<ul><li>Meningkatkan availability Raw Mill Tuban 1 di atas 95%.</li><li>Mengurangi unscheduled breakdown pada sistem hidrolik dan roller.</li></ul>',
                'content' => '<ul><li>Bab 1: Inspeksi Harian dan Parameter Operasional Roller Mill.</li><li>Bab 2: Trouble-shooting Sistem Hidrolik dan Pelumasan Raw Mill.</li></ul>',
                'sme_id' => $sme->id,
                'sme_instructions' => 'Fokuskan pada checklist inspeksi harian dan prosedur penggantian seal hidrolik.',
                'need_workshop' => true,
                'workshop_note' => 'Praktik bongkar pasang komponen hidrolik di workshop Tuban.',
                'deadline' => Carbon::now()->addDays(7),
                'reminder_setting' => 'H-3',
                'reminder_frequency' => 1,
                'distribution' => 'internal',
                'rationalization' => 'Materi spesifik peralatan pabrik Tuban 1.',
                'supporting_documents' => [
                    ['name' => 'Manual_Raw_Mill_Tuban1.pdf', 'url' => '#', 'size' => 7800000]
                ],
                'status' => 'studio_production',
                'cld_review_notes' => 'Catatan Learning Administrator (CLD Studio): Materi Sempurna. Kualitas slide presentasi dan checklist sangat rapi. Langsung dilanjutkan ke tahap Studio Shooting & Masterclass Curriculum Builder.',
                'sme_submitted_materials' => [
                    ['name' => 'Materi_Preventive_RawMill.pptx', 'url' => '#', 'size' => 12500000, 'uploaded_at' => Carbon::now()->subDays(1)->format('Y-m-d H:i'), 'description' => 'Materi presentasi lengkap dengan foto komponen Raw Mill Tuban 1.']
                ],
                'sme_submitted_templates' => [
                    ['name' => 'Checklist_Inspeksi_Harian.docx', 'url' => '#', 'size' => 1200000, 'uploaded_at' => Carbon::now()->subDays(1)->format('Y-m-d H:i'), 'description' => 'Formulir inspeksi untuk dibagikan ke peserta.']
                ],
                'sme_submission_notes' => 'Materi sudah mencakup checklist harian dan panduan troubleshooting praktis.',
                'curriculum_structure' => [
                    [
                        'id' => 'chapter-1',
                        'title' => 'DASAR FFT',
                        'summary' => 'Fokus pada pemahaman fundamental konversi domain waktu ke frekuensi. Menjelaskan parameter resolusi, windowing, dan aliasing yang kritikal bagi akurasi diagnosa awal teknisi di lapangan.',
                        'items' => [
                            [
                                'id' => 'item-1', 
                                'title' => '1.1 Prinsip Fundamental Getaran', 
                                'type' => 'video', 
                                'meta' => 'Video MP4 • 05:12 • 3.8 MB', 
                                'url' => '/storage/tna_documents/dummy_video_masterclass.mp4',
                                'videoPreviewUrl' => '/storage/tna_documents/dummy_video_masterclass.mp4',
                                'videoFilename' => 'dummy_video_masterclass.mp4',
                                'videoFilesize' => '3.8 MB',
                                'videoDuration' => '05:12',
                                'attachments' => [
                                    ['filename' => 'Panduan_Kalibrasi_Vibrasi.pdf', 'filesize' => '2.4 MB', 'url' => '/storage/tna_documents/Panduan_Kalibrasi_Vibrasi.pdf'],
                                    ['filename' => 'Worksheet_Heat_Balance.xlsx', 'filesize' => '1.8 MB', 'url' => '/storage/tna_documents/Worksheet_Heat_Balance.xlsx'],
                                    ['filename' => 'SOP_Inspeksi_Harian.docx', 'filesize' => '1.2 MB', 'url' => '/storage/tna_documents/SOP_Inspeksi_Harian.docx'],
                                    ['filename' => 'Dataset_Spektrum_Tuban4.zip', 'filesize' => '5.6 MB', 'url' => '/storage/tna_documents/Dataset_Spektrum_Tuban4.zip'],
                                    ['filename' => 'Diagram_Alur_Kiln.jpg', 'filesize' => '850 KB', 'url' => '/storage/tna_documents/Diagram_Alur_Kiln.jpg'],
                                ]
                            ],
                            [
                                'id' => 'item-2', 
                                'title' => '1.2 Kuis Evaluasi Dasar', 
                                'type' => 'quiz', 
                                'meta' => 'Kuis • 10 Pertanyaan (15 Menit)', 
                                'questions' => 10,
                                'durationMinutes' => 15,
                                'isInfinityDuration' => false,
                                'passingGrade' => 75,
                                'shuffle' => true,
                                'showCorrectAnswer' => true,
                                'summary' => 'Kuis evaluasi pemahaman mendalam terkait materi bab ini dengan sistem pengacakan otomatis LMS.',
                                'questionsList' => [
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Perhatikan grafik anomali vibrasi pada main drive kiln berikut ini. Berapakah batas normal vibrasi velocity saat operasi beban penuh menurut standar ISO 10816-3?</p>',
                                        'contentBlocks' => [
                                            ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80']
                                        ],
                                        'options' => [
                                            ['text' => '4.5 mm/s', 'image' => ''],
                                            ['text' => '7.1 mm/s', 'image' => ''],
                                            ['text' => '12.0 mm/s', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 1,
                                        'correctFeedback' => 'Jawaban Anda tepat! Batas normal vibrasi velocity pada kondisi beban penuh untuk mesin grup 1 kelas berat adalah 7.1 mm/s sesuai standar ISO 10816-3.',
                                        'incorrectFeedback' => 'Jawaban Anda kurang tepat. Silakan tinjau kembali manual operasional kiln bagian spesifikasi getaran main drive serta tabel standar ISO 10816-3.',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ],
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Apa fungsi utama dari penerapan analisis spektrum Fast Fourier Transform (FFT) pada pemantauan motor fan kiln?</p>',
                                        'contentBlocks' => [],
                                        'options' => [
                                            ['text' => 'Mengubah sinyal getaran dari domain waktu ke domain frekuensi untuk mengidentifikasi sumber spesifik kerusakan mesin', 'image' => ''],
                                            ['text' => 'Menurunkan temperatur pelumas pada bearing casing secara otomatis', 'image' => ''],
                                            ['text' => 'Meningkatkan kecepatan putaran impeler ID Fan melebihi kapasitas desain', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 0,
                                        'correctFeedback' => 'Benar sekali! Analisis FFT berfungsi mengonversi sinyal domain waktu ke frekuensi, sehingga teknisi dapat membedakan unbalance, misalignment, atau kerusakan bearing berdasarkan puncak frekuensinya.',
                                        'incorrectFeedback' => 'Jawaban kurang tepat. FFT tidak memodifikasi parameter fisik seperti suhu atau kecepatan putar, melainkan merupakan metode pengolahan sinyal diagnostik.',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ],
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Jenis sensor vibrasi manakah yang paling tepat dan sensitif digunakan untuk mengukur getaran frekuensi tinggi pada rolling element bearing?</p>',
                                        'contentBlocks' => [],
                                        'options' => [
                                            ['text' => 'Proximity Eddy Current Probe', 'image' => ''],
                                            ['text' => 'Piezoelectric Accelerometer', 'image' => ''],
                                            ['text' => 'Seismic Velocity Transducer', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 1,
                                        'correctFeedback' => 'Tepat! Piezoelectric Accelerometer memiliki respon frekuensi yang sangat tinggi (hingga melebihi 10 kHz), menjadikannya pilihan utama untuk mendeteksi frekuensi cacat bearing (BPFO, BPFI, BSF).',
                                        'incorrectFeedback' => 'Kurang tepat. Proximity probe lebih cocok untuk poros turbin (frekuensi rendah/displacement), sedangkan velocity transducer memiliki batasan di frekuensi tinggi (di atas 1000 Hz).',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ],
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Indikasi utama terjadinya fenomena unbalance pada impeler ID Fan kiln berdasarkan spektrum getaran FFT adalah...</p>',
                                        'contentBlocks' => [],
                                        'options' => [
                                            ['text' => 'Munculnya puncak dominan pada frekuensi 1X RPM (1X running speed) dengan amplitudo tinggi arah radial', 'image' => ''],
                                            ['text' => 'Munculnya sub-harmonik pada frekuensi 0.5X RPM', 'image' => ''],
                                            ['text' => 'Puncak harmonik yang merata dari 1X hingga 10X RPM', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 0,
                                        'correctFeedback' => 'Jawaban Anda benar. Unbalance selalu menghasilkan gaya sentrifugal yang bermanifestasi sebagai getaran sinusoidal murni pada frekuensi 1X putaran poros (1X RPM) di arah radial.',
                                        'incorrectFeedback' => 'Salah. Frekuensi 0.5X RPM biasanya mengindikasikan oil whirl pada journal bearing, sedangkan harmonik tinggi mengindikasikan looseness (kelonggaran mekanis).',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ],
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Parameter getaran utama yang diukur oleh proximity probe pada turbin generator pabrik semen adalah...</p>',
                                        'contentBlocks' => [],
                                        'options' => [
                                            ['text' => 'Akselerasi (g) dari casing mesin', 'image' => ''],
                                            ['text' => 'Kecepatan getaran (mm/s) pada pondasi', 'image' => ''],
                                            ['text' => 'Perpindahan relatif (displacement dalam mikron) antara poros berputar dan journal bearing', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 2,
                                        'correctFeedback' => 'Sangat tepat! Proximity probe mengukur displacement (jarak relatif) poros terhadap bantalan, yang sangat krusial untuk memantau ketebalan film pelumas dan pergerakan poros turbin.',
                                        'incorrectFeedback' => 'Kurang tepat. Proximity probe dipasang non-kontak untuk mengukur pergerakan poros langsung (displacement), bukan getaran casing (akselerasi/kecepatan).',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ],
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Langkah awal yang wajib dilakukan teknisi sebelum memasang sensor akselerometer dengan metode magnetic base pada area bearing adalah...</p>',
                                        'contentBlocks' => [],
                                        'options' => [
                                            ['text' => 'Mengoleskan pelumas gemuk (grease) dalam jumlah sangat banyak di seluruh permukaan sensor', 'image' => ''],
                                            ['text' => 'Membersihkan permukaan ukur dari kotoran, kerak semen, atau cat mengelupas agar kontak magnet menempel sempurna', 'image' => ''],
                                            ['text' => 'Memanaskan sensor menggunakan heat gun hingga menyamai suhu casing', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 1,
                                        'correctFeedback' => 'Benar! Kontak mekanis yang solid. sangat penting. Kotoran atau kerak semen akan menciptakan celah udara (air gap) yang meredam transmisi frekuensi tinggi dan menurunkan frekuensi resonansi mounting.',
                                        'incorrectFeedback' => 'Salah. Pemanasan sensor tidak diperlukan dan bisa merusak kristal piezoelektrik. Membersihkan permukaan adalah prosedur standar yang mutlak.',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ],
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Penyebab utama munculnya frekuensi dominan 2X RPM pada spektrum getaran motor pompa hidrolik raw mill disertai getaran aksial yang tinggi adalah...</p>',
                                        'contentBlocks' => [],
                                        'options' => [
                                            ['text' => 'Misalignment (ketidaksejajaran poros) antara motor dan pompa', 'image' => ''],
                                            ['text' => 'Kavitasi pada sudu pompa hidrolik', 'image' => ''],
                                            ['text' => 'Kekurangan oli pelumas pada tangki reservoir', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 0,
                                        'correctFeedback' => 'Tepat sekali! Angular misalignment secara khas menghasilkan getaran aksial yang kuat pada frekuensi 1X dan 2X RPM akibat gaya lentur kopling yang terjadi dua kali per putaran.',
                                        'incorrectFeedback' => 'Kurang tepat. Kavitasi menghasilkan spektrum getaran acak (random noise) di frekuensi tinggi, bukan puncak diskrit di 2X RPM.',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ],
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Berapakah standar toleransi temperatur operasi maksimal pada bearing support casing rotary kiln sebelum memicu alarm interlock di central control room (CCR)?</p>',
                                        'contentBlocks' => [],
                                        'options' => [
                                            ['text' => '65°C', 'image' => ''],
                                            ['text' => '85°C', 'image' => ''],
                                            ['text' => '120°C', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 1,
                                        'correctFeedback' => 'Benar. Sesuai standar operasional pemeliharaan SIG, suhu bearing di atas 85°C mengindikasikan potensi kegagalan pelumasan atau beban berlebih yang memerlukan tindakan inspeksi segera.',
                                        'incorrectFeedback' => 'Jawaban salah. Suhu 65°C masih dalam rentang normal operasi, sedangkan 120°C sudah memasuki fase kerusakan fatal (babbitt metal meleleh).',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ],
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Bagaimanakah pengaruh fenomena soft foot (kaki motor tidak menapak rata) terhadap pembacaan vibrasi motor drive belt conveyor clinker?</p>',
                                        'contentBlocks' => [],
                                        'options' => [
                                            ['text' => 'Meningkatkan amplitudo getaran pada frekuensi 1X dan 2X frekuensi jala-jala listrik (line frequency 50Hz / 100Hz) akibat distorsi air gap motor', 'image' => ''],
                                            ['text' => 'Menghilangkan seluruh getaran mekanis pada poros', 'image' => ''],
                                            ['text' => 'Menyebabkan sabuk conveyor tergelincir (slip) secara langsung', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 0,
                                        'correctFeedback' => 'Luar biasa! Soft foot menyebabkan distorsi pada frame motor saat baut dikencangkan, yang memicu ketidakseimbangan medan magnet (dynamic/static air gap eccentricity), menghasilkan getaran pada 2X line frequency (100 Hz).',
                                        'incorrectFeedback' => 'Kurang tepat. Soft foot adalah masalah struktural/kelistrikan pada motor, bukan penyebab langsung slip pada sabuk conveyor.',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ],
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Metode analisis getaran manakah yang paling efektif untuk mendeteksi kerusakan awal pada roda gigi (gear mesh frequency) di dalam gearbox ball mill?</p>',
                                        'contentBlocks' => [],
                                        'options' => [
                                            ['text' => 'Analisis Time Waveform dan Demodulasi (Enveloping / PeakValue)', 'image' => ''],
                                            ['text' => 'Pengukuran Overall Velocity murni tanpa filter', 'image' => ''],
                                            ['text' => 'Pengecekan visual pada indikator level oli eksternal', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 0,
                                        'correctFeedback' => 'Sangat tepat! Teknik demodulasi (enveloping) menyaring frekuensi rendah dan memperkuat sinyal impak frekuensi tinggi dari cacat roda gigi, sehingga cacat awal dapat terdeteksi jauh sebelum amplitudo overall meningkat.',
                                        'incorrectFeedback' => 'Salah. Pengukuran overall velocity sering kali tidak sensitif terhadap energi impak kecil dari cacat roda gigi awal yang tertutup oleh getaran rotasi besar ball mill.',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ]
                                ]
                            ],
                            [
                                'id' => 'item-3', 
                                'title' => '1.3 Instalasi Sensor dan Proximity', 
                                'type' => 'video', 
                                'meta' => 'Video MP4 • 05:12 • 3.8 MB', 
                                'url' => '/storage/tna_documents/dummy_video_masterclass.mp4',
                                'videoPreviewUrl' => '/storage/tna_documents/dummy_video_masterclass.mp4',
                                'videoFilename' => 'dummy_video_masterclass.mp4',
                                'videoFilesize' => '3.8 MB',
                                'videoDuration' => '05:12',
                                'attachments' => [
                                    ['filename' => 'Panduan_Instalasi_Sensor_Proximity.pdf', 'filesize' => '3.8 MB', 'url' => '/storage/tna_documents/Panduan_Kalibrasi_Vibrasi.pdf'],
                                    ['filename' => 'Worksheet_Heat_Balance.xlsx', 'filesize' => '1.8 MB', 'url' => '/storage/tna_documents/Worksheet_Heat_Balance.xlsx'],
                                    ['filename' => 'SOP_Inspeksi_Harian.docx', 'filesize' => '1.2 MB', 'url' => '/storage/tna_documents/SOP_Inspeksi_Harian.docx'],
                                    ['filename' => 'Dataset_Spektrum_Tuban4.zip', 'filesize' => '5.6 MB', 'url' => '/storage/tna_documents/Dataset_Spektrum_Tuban4.zip'],
                                    ['filename' => 'Diagram_Alur_Kiln.jpg', 'filesize' => '850 KB', 'url' => '/storage/tna_documents/Diagram_Alur_Kiln.jpg'],
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => 'chapter-2',
                        'title' => 'STUDI KASUS KILN',
                        'summary' => 'Analisa anomali temperatur dan getaran pada unit tanur semen (Kiln).',
                        'items' => []
                    ]
                ],
            ],
            // Skenario E: Disetujui Setelah Revisi (Studio Production) - Siap untuk Masterclass Curriculum Builder
            [
                'id' => 'BP-2024-005',
                'tna_submission_ids' => ['TNA-2024-GRS-006'],
                'title' => 'Strategi Penghematan Energi Sistem Penggilingan Semen',
                'category' => 'Energy Management',
                'objective' => '<ul><li>Menurunkan konsumsi energi spesifik (kWh/ton semen) sebesar 5%.</li><li>Mengoptimalkan fungsi separator dan sistem sirkulasi udara di Finish Mill.</li></ul>',
                'content' => '<ul><li>Bab 1: Audit Energi dan Identifikasi Pemborosan Daya pada Finish Mill.</li><li>Bab 2: Optimasi Pengaturan Klasifikasi Separator Berbasis Kurva Tromp.</li></ul>',
                'sme_id' => $sme->id,
                'sme_instructions' => 'Sertakan contoh kurva Tromp dari hasil pengujian aktual di pabrik Gresik atau Tuban.',
                'need_workshop' => false,
                'workshop_note' => null,
                'deadline' => Carbon::now()->addDays(10),
                'reminder_setting' => 'H-3',
                'reminder_frequency' => 1,
                'distribution' => 'public',
                'rationalization' => 'Materi best practice manajemen energi yang dapat di-sharing ke industri luar.',
                'supporting_documents' => [
                    ['name' => 'Pedoman_Efisiensi_Energi_Kemenperin.pdf', 'url' => '#', 'size' => 4500000]
                ],
                'status' => 'studio_production',
                'cld_review_notes' => 'Catatan Learning Administrator (CLD Studio): Revisi Kurva Tromp dan penambahan studi kasus efisiensi energi sudah sangat jelas dan memenuhi standar resolusi studio. Disetujui untuk lanjut ke Masterclass Curriculum Builder.',
                'sme_submitted_materials' => [
                    ['name' => 'Materi_Efisiensi_Energi_Mill.pptx', 'url' => '#', 'size' => 14200000, 'uploaded_at' => Carbon::now()->subHours(12)->format('Y-m-d H:i'), 'description' => 'Slide presentasi yang sudah direvisi dengan penambahan Kurva Tromp aktual.']
                ],
                'sme_submitted_templates' => [
                    ['name' => 'Kalkulator_Efisiensi_Separator.xlsx', 'url' => '#', 'size' => 2100000, 'uploaded_at' => Carbon::now()->subHours(12)->format('Y-m-d H:i'), 'description' => 'Spreadsheet simulasi efisiensi separator.']
                ],
                'sme_submission_notes' => 'Revisi kurva Tromp dari pabrik Tuban sudah dimasukkan ke dalam slide 18 dan 19.',
                'curriculum_structure' => [
                    [
                        'id' => 'chapter-1',
                        'title' => 'DASAR FFT',
                        'summary' => 'Fokus pada pemahaman fundamental konversi domain waktu ke frekuensi. Menjelaskan parameter resolusi, windowing, dan aliasing yang kritikal bagi akurasi diagnosa awal teknisi di lapangan.',
                        'items' => [
                            [
                                'id' => 'item-1', 
                                'title' => '1.1 Prinsip Fundamental Getaran', 
                                'type' => 'video', 
                                'meta' => 'Video MP4 • 05:12 • 3.8 MB', 
                                'url' => '/storage/tna_documents/dummy_video_masterclass.mp4',
                                'videoPreviewUrl' => '/storage/tna_documents/dummy_video_masterclass.mp4',
                                'videoFilename' => 'dummy_video_masterclass.mp4',
                                'videoFilesize' => '3.8 MB',
                                'videoDuration' => '05:12',
                                'attachments' => [
                                    ['filename' => 'Panduan_Kalibrasi_Vibrasi.pdf', 'filesize' => '2.4 MB', 'url' => '/storage/tna_documents/Panduan_Kalibrasi_Vibrasi.pdf'],
                                    ['filename' => 'Worksheet_Heat_Balance.xlsx', 'filesize' => '1.8 MB', 'url' => '/storage/tna_documents/Worksheet_Heat_Balance.xlsx'],
                                    ['filename' => 'SOP_Inspeksi_Harian.docx', 'filesize' => '1.2 MB', 'url' => '/storage/tna_documents/SOP_Inspeksi_Harian.docx'],
                                    ['filename' => 'Dataset_Spektrum_Tuban4.zip', 'filesize' => '5.6 MB', 'url' => '/storage/tna_documents/Dataset_Spektrum_Tuban4.zip'],
                                    ['filename' => 'Diagram_Alur_Kiln.jpg', 'filesize' => '850 KB', 'url' => '/storage/tna_documents/Diagram_Alur_Kiln.jpg'],
                                ]
                            ],
                            [
                                'id' => 'item-2', 
                                'title' => '1.2 Kuis Evaluasi Dasar', 
                                'type' => 'quiz', 
                                'meta' => 'Kuis • 10 Pertanyaan (15 Menit)', 
                                'questions' => 10,
                                'durationMinutes' => 15,
                                'isInfinityDuration' => false,
                                'passingGrade' => 75,
                                'shuffle' => true,
                                'showCorrectAnswer' => true,
                                'summary' => 'Kuis evaluasi pemahaman mendalam terkait materi bab ini dengan sistem pengacakan otomatis LMS.',
                                'questionsList' => [
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Perhatikan grafik anomali vibrasi pada main drive kiln berikut ini. Berapakah batas normal vibrasi velocity saat operasi beban penuh menurut standar ISO 10816-3?</p>',
                                        'contentBlocks' => [
                                            ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80']
                                        ],
                                        'options' => [
                                            ['text' => '4.5 mm/s', 'image' => ''],
                                            ['text' => '7.1 mm/s', 'image' => ''],
                                            ['text' => '12.0 mm/s', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 1,
                                        'correctFeedback' => 'Jawaban Anda tepat! Batas normal vibrasi velocity pada kondisi beban penuh untuk mesin grup 1 kelas berat adalah 7.1 mm/s sesuai standar ISO 10816-3.',
                                        'incorrectFeedback' => 'Jawaban Anda kurang tepat. Silakan tinjau kembali manual operasional kiln bagian spesifikasi getaran main drive serta tabel standar ISO 10816-3.',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ],
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Apa fungsi utama dari penerapan analisis spektrum Fast Fourier Transform (FFT) pada pemantauan motor fan kiln?</p>',
                                        'contentBlocks' => [],
                                        'options' => [
                                            ['text' => 'Mengubah sinyal getaran dari domain waktu ke domain frekuensi untuk mengidentifikasi sumber spesifik kerusakan mesin', 'image' => ''],
                                            ['text' => 'Menurunkan temperatur pelumas pada bearing casing secara otomatis', 'image' => ''],
                                            ['text' => 'Meningkatkan kecepatan putaran impeler ID Fan melebihi kapasitas desain', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 0,
                                        'correctFeedback' => 'Benar sekali! Analisis FFT berfungsi mengonversi sinyal domain waktu ke frekuensi, sehingga teknisi dapat membedakan unbalance, misalignment, atau kerusakan bearing berdasarkan puncak frekuensinya.',
                                        'incorrectFeedback' => 'Jawaban kurang tepat. FFT tidak memodifikasi parameter fisik seperti suhu atau kecepatan putar, melainkan merupakan metode pengolahan sinyal diagnostik.',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ],
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Jenis sensor vibrasi manakah yang paling tepat dan sensitif digunakan untuk mengukur getaran frekuensi. tinggi pada rolling element bearing?</p>',
                                        'contentBlocks' => [],
                                        'options' => [
                                            ['text' => 'Proximity Eddy Current Probe', 'image' => ''],
                                            ['text' => 'Piezoelectric Accelerometer', 'image' => ''],
                                            ['text' => 'Seismic Velocity Transducer', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 1,
                                        'correctFeedback' => 'Tepat! Piezoelectric Accelerometer memiliki respon frekuensi yang sangat tinggi (hingga melebihi 10 kHz), menjadikannya pilihan utama untuk mendeteksi frekuensi cacat bearing (BPFO, BPFI, BSF).',
                                        'incorrectFeedback' => 'Kurang tepat. Proximity probe lebih cocok untuk poros turbin (frekuensi rendah/displacement), sedangkan velocity transducer memiliki batasan di frekuensi tinggi (di atas 1000 Hz).',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ],
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Indikasi utama terjadinya fenomena unbalance pada impeler ID Fan kiln berdasarkan spektrum getaran FFT adalah...</p>',
                                        'contentBlocks' => [],
                                        'options' => [
                                            ['text' => 'Munculnya puncak dominan pada frekuensi 1X RPM (1X running speed) dengan amplitudo tinggi arah radial', 'image' => ''],
                                            ['text' => 'Munculnya sub-harmonik pada frekuensi 0.5X RPM', 'image' => ''],
                                            ['text' => 'Puncak harmonik yang merata dari 1X hingga 10X RPM', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 0,
                                        'correctFeedback' => 'Jawaban Anda benar. Unbalance selalu menghasilkan gaya sentrifugal yang bermanifestasi sebagai getaran sinusoidal murni pada frekuensi 1X putaran poros (1X RPM) di arah radial.',
                                        'incorrectFeedback' => 'Salah. Frekuensi 0.5X RPM biasanya mengindikasikan oil whirl pada journal bearing, sedangkan harmonik tinggi mengindikasikan looseness (kelonggaran mekanis).',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ],
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Parameter getaran utama yang diukur oleh proximity probe pada turbin generator pabrik semen adalah...</p>',
                                        'contentBlocks' => [],
                                        'options' => [
                                            ['text' => 'Akselerasi (g) dari casing mesin', 'image' => ''],
                                            ['text' => 'Kecepatan getaran (mm/s) pada pondasi', 'image' => ''],
                                            ['text' => 'Perpindahan relatif (displacement dalam mikron) antara poros berputar dan journal bearing', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 2,
                                        'correctFeedback' => 'Sangat tepat! Proximity probe mengukur displacement (jarak relatif) poros terhadap bantalan, yang sangat krusial untuk memantau ketebalan film pelumas dan pergerakan poros turbin.',
                                        'incorrectFeedback' => 'Kurang tepat. Proximity probe dipasang non-kontak untuk mengukur pergerakan poros langsung (displacement), bukan getaran casing (akselerasi/kecepatan).',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ],
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Langkah awal yang wajib dilakukan teknisi sebelum memasang sensor akselerometer dengan metode magnetic base pada area bearing adalah...</p>',
                                        'contentBlocks' => [],
                                        'options' => [
                                            ['text' => 'Mengoleskan pelumas gemuk (grease) dalam jumlah sangat banyak di seluruh permukaan sensor', 'image' => ''],
                                            ['text' => 'Membersihkan permukaan ukur dari kotoran, kerak semen, atau cat mengelupas agar kontak magnet menempel sempurna', 'image' => ''],
                                            ['text' => 'Memanaskan sensor menggunakan heat gun hingga menyamai suhu casing', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 1,
                                        'correctFeedback' => 'Benar! Kontak mekanis yang solid sangat penting. Kotoran atau kerak semen akan menciptakan celah udara (air gap) yang meredam transmisi frekuensi tinggi dan menurunkan frekuensi resonansi mounting.',
                                        'incorrectFeedback' => 'Salah. Pemanasan sensor tidak diperlukan dan bisa merusak kristal piezoelektrik. Membersihkan permukaan adalah prosedur standar yang mutlak.',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ],
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Penyebab utama munculnya frekuensi dominan 2X RPM pada spektrum getaran motor pompa hidrolik raw mill disertai getaran aksial yang tinggi adalah...</p>',
                                        'contentBlocks' => [],
                                        'options' => [
                                            ['text' => 'Misalignment (ketidaksejajaran poros) antara motor dan pompa', 'image' => ''],
                                            ['text' => 'Kavitasi pada sudu pompa hidrolik', 'image' => ''],
                                            ['text' => 'Kekurangan oli pelumas pada tangki reservoir', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 0,
                                        'correctFeedback' => 'Tepat sekali! Angular misalignment secara khas menghasilkan getaran aksial yang kuat pada frekuensi 1X dan 2X RPM akibat gaya lentur kopling yang terjadi dua kali per putaran.',
                                        'incorrectFeedback' => 'Kurang tepat. Kavitasi menghasilkan spektrum getaran acak (random noise) di frekuensi tinggi, bukan puncak diskrit di 2X RPM.',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ],
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Berapakah standar toleransi temperatur operasi maksimal pada bearing support casing rotary kiln sebelum memicu alarm interlock di central control room (CCR)?</p>',
                                        'contentBlocks' => [],
                                        'options' => [
                                            ['text' => '65°C', 'image' => ''],
                                            ['text' => '85°C', 'image' => ''],
                                            ['text' => '120°C', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 1,
                                        'correctFeedback' => 'Benar. Sesuai standar operasional pemeliharaan SIG, suhu bearing di atas 85°C mengindikasikan potensi kegagalan pelumasan atau beban berlebih yang memerlukan tindakan inspeksi segera.',
                                        'incorrectFeedback' => 'Jawaban salah. Suhu 65°C masih dalam rentang normal operasi, sedangkan 120°C sudah memasuki. fase kerusakan fatal (babbitt metal meleleh).',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ],
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Bagaimanakah pengaruh fenomena soft foot (kaki motor tidak menapak rata) terhadap pembacaan vibrasi motor drive belt conveyor clinker?</p>',
                                        'contentBlocks' => [],
                                        'options' => [
                                            ['text' => 'Meningkatkan amplitudo getaran pada frekuensi 1X dan 2X frekuensi jala-jala listrik (line frequency 50Hz / 100Hz) akibat distorsi air gap motor', 'image' => ''],
                                            ['text' => 'Menghilangkan seluruh getaran mekanis pada poros', 'image' => ''],
                                            ['text' => 'Menyebabkan sabuk conveyor tergelincir (slip) secara langsung', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 0,
                                        'correctFeedback' => 'Luar biasa! Soft foot menyebabkan distorsi pada frame motor saat baut dikencangkan, yang memicu ketidakseimbangan medan magnet (dynamic/static air gap eccentricity), menghasilkan getaran pada 2X line frequency (100 Hz).',
                                        'incorrectFeedback' => 'Kurang tepat. Soft foot adalah masalah struktural/kelistrikan pada motor, bukan penyebab langsung slip pada sabuk conveyor.',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ],
                                    [
                                        'type' => 'multiple_choice',
                                        'questionText' => '<p>Metode analisis getaran manakah yang paling efektif untuk mendeteksi kerusakan awal pada roda gigi (gear mesh frequency) di dalam gearbox ball mill?</p>',
                                        'contentBlocks' => [],
                                        'options' => [
                                            ['text' => 'Analisis Time Waveform dan Demodulasi (Enveloping / PeakValue)', 'image' => ''],
                                            ['text' => 'Pengukuran Overall Velocity murni tanpa filter', 'image' => ''],
                                            ['text' => 'Pengecekan visual pada indikator level oli eksternal', 'image' => '']
                                        ],
                                        'correctOptionIndex' => 0,
                                        'correctFeedback' => 'Sangat tepat! Teknik demodulasi (enveloping) menyaring frekuensi rendah dan memperkuat sinyal impak frekuensi tinggi dari cacat roda gigi, sehingga cacat awal dapat terdeteksi jauh sebelum amplitudo overall meningkat.',
                                        'incorrectFeedback' => 'Salah. Pengukuran overall velocity sering kali tidak sensitif terhadap energi impak kecil dari cacat roda gigi awal yang tertutup oleh getaran rotasi besar ball mill.',
                                        'randomizeOptions' => true,
                                        'hasCorrectFeedback' => true,
                                        'hasIncorrectFeedback' => true
                                    ]
                                ]
                            ],
                            [
                                'id' => 'item-3', 
                                'title' => '1.3 Instalasi Sensor dan Proximity', 
                                'type' => 'video', 
                                'meta' => 'Video MP4 • 05:12 • 3.8 MB', 
                                'url' => '/storage/tna_documents/dummy_video_masterclass.mp4',
                                'videoPreviewUrl' => '/storage/tna_documents/dummy_video_masterclass.mp4',
                                'videoFilename' => 'dummy_video_masterclass.mp4',
                                'videoFilesize' => '3.8 MB',
                                'videoDuration' => '05:12',
                                'attachments' => [
                                    ['filename' => 'Panduan_Instalasi_Sensor_Proximity.pdf', 'filesize' => '3.8 MB', 'url' => '/storage/tna_documents/Panduan_Kalibrasi_Vibrasi.pdf'],
                                    ['filename' => 'Worksheet_Heat_Balance.xlsx', 'filesize' => '1.8 MB', 'url' => '/storage/tna_documents/Worksheet_Heat_Balance.xlsx'],
                                    ['filename' => 'SOP_Inspeksi_Harian.docx', 'filesize' => '1.2 MB', 'url' => '/storage/tna_documents/SOP_Inspeksi_Harian.docx'],
                                    ['filename' => 'Dataset_Spektrum_Tuban4.zip', 'filesize' => '5.6 MB', 'url' => '/storage/tna_documents/Dataset_Spektrum_Tuban4.zip'],
                                    ['filename' => 'Diagram_Alur_Kiln.jpg', 'filesize' => '850 KB', 'url' => '/storage/tna_documents/Diagram_Alur_Kiln.jpg'],
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => 'chapter-2',
                        'title' => 'STUDI KASUS KILN',
                        'summary' => 'Analisa anomali temperatur dan getaran pada unit tanur semen (Kiln).',
                        'items' => []
                    ]
                ],
            ],
            // Skenario F: Modul Divalidasi / Dirilis (Released) - Siap didemo untuk Arsip Modul Divalidasi
            [
                'id' => 'BP-2024-006',
                'tna_submission_ids' => ['TNA-2024-GRS-007'],
                'title' => 'Sistem Otomasi dan Pengendalian Mutu Klinker Terintegrasi',
                'category' => 'Quality Control',
                'objective' => '<ul><li>Memastikan standar deviasi kuat tekan semen (blaine & strength) konsisten di angka 0.5 MPa.</li><li>Mengoperasikan sistem X-Ray Fluorescence (XRF) online secara mandiri.</li></ul>',
                'content' => '<ul><li>Bab 1: Kalibrasi Instrumen XRF dan Analisis Komposisi Oksida Klinker.</li><li>Bab 2: Otomasi Dosis Gypsum dan Batu Kapur pada Penggilingan Akhir.</li></ul>',
                'sme_id' => $sme->id,
                'sme_instructions' => 'Materi telah melalui seluruh tahap validasi dan telah digunakan dalam pelatihan batch 1.',
                'need_workshop' => true,
                'workshop_note' => 'Praktik di laboratorium Quality Assurance Tuban.',
                'deadline' => Carbon::now()->subDays(30),
                'reminder_setting' => 'H-7',
                'reminder_frequency' => 1,
                'distribution' => 'public',
                'rationalization' => 'Materi standar kompetensi wajib bagi seluruh analis kimia SIG.',
                'supporting_documents' => [
                    ['name' => 'SOP_Pengujian_XRF_Tuban.pdf', 'url' => '#', 'size' => 3400000]
                ],
                'status' => 'released',
                'cld_review_notes' => 'Catatan Learning Administrator: Modul luar biasa, telah divalidasi penuh oleh tim VP Human Capital dan dirilis resmi ke katalog SIG Academy.',
                'sme_submitted_materials' => [
                    ['name' => 'Materi_Otomasi_QC_Klinker.pptx', 'url' => '#', 'size' => 18500000, 'uploaded_at' => Carbon::now()->subDays(35)->format('Y-m-d H:i'), 'description' => 'Materi presentasi final yang divalidasi.']
                ],
                'sme_submitted_templates' => [
                    ['name' => 'Log_Kalibrasi_XRF.xlsx', 'url' => '#', 'size' => 1500000, 'uploaded_at' => Carbon::now()->subDays(35)->format('Y-m-d H:i'), 'description' => 'Template log sheet kalibrasi.']
                ],
                'sme_submission_notes' => 'Seluruh modul telah disesuaikan dengan standar akreditasi KAN ISO/IEC 17025.',
                'curriculum_structure' => [
                    [
                        'id' => 'chapter-1',
                        'title' => 'PENGUJIAN XRF',
                        'summary' => 'Panduan pengujian dan analisis komposisi oksida pada sampel klinker.',
                        'items' => [
                            [
                                'id' => 'item-1', 
                                'title' => '1.1 Operasional XRF Online', 
                                'type' => 'video', 
                                'meta' => 'Video MP4 • 08:15 • 12.5 MB', 
                                'url' => '/storage/tna_documents/dummy_video_masterclass.mp4',
                                'videoPreviewUrl' => '/storage/tna_documents/dummy_video_masterclass.mp4',
                                'videoFilename' => 'dummy_video_masterclass.mp4',
                                'videoFilesize' => '12.5 MB',
                                'videoDuration' => '08:15',
                                'attachments' => [
                                    ['filename' => 'SOP_Pengujian_XRF_Tuban.pdf', 'filesize' => '3.4 MB', 'url' => '/storage/tna_documents/SOP_Pengujian_XRF_Tuban.pdf'],
                                ]
                            ]
                        ]
                    ]
                ],
            ],
        ];

        foreach ($blueprints as $bp) {
            TrainingBlueprint::updateOrCreate(['id' => $bp['id']], $bp);
        }
    }
}
