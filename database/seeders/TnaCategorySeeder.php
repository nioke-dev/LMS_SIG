<?php

namespace Database\Seeders;

use App\Models\TnaCategory;
use Illuminate\Database\Seeder;

class TnaCategorySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Parent Categories
        $parents = [
            ['name' => 'Technical Skills', 'description' => 'Pelatihan teknis spesifik untuk operasional pabrik dan pemeliharaan alat berat produksi semen.'],
            ['name' => 'Soft Skills', 'description' => 'Pengembangan kemampuan komunikasi, kepemimpinan tim, dan manajemen waktu yang efektif.'],
            ['name' => 'Safety & Compliance', 'description' => 'Standar keselamatan kerja K3, regulasi lingkungan, dan kepatuhan hukum korporasi.'],
            ['name' => 'Digital Transformation', 'description' => 'Pemahaman teknologi industri 4.0, IoT sensor, kecerdasan buatan, dan otomatisasi proses pabrik.'],
            ['name' => 'Supply Chain Management', 'description' => 'Manajemen logistik, efisiensi distribusi semen, dan optimasi jaringan rantai pasok.'],
            ['name' => 'Quality Control', 'description' => 'Pengendalian mutu produk semen, manajemen lab pengujian, dan standarisasi ISO.'],
            ['name' => 'Finance & Accounting', 'description' => 'Manajemen keuangan perusahaan, penyusunan anggaran, dan analisis efisiensi biaya produksi.'],
            ['name' => 'Human Resources', 'description' => 'Pengelolaan talenta karyawan, strategi rekrutmen, dan matriks evaluasi kinerja berkala.'],
            ['name' => 'Marketing & Sales', 'description' => 'Strategi pemasaran produk, ekspansi jaringan pelanggan B2B, dan pencapaian target penjualan.'],
            ['name' => 'Sustainability & ESG', 'description' => 'Inisiatif keberlanjutan, pengembangan energi hijau, pengurangan emisi karbon, dan program CSR.'],
            ['name' => 'Mining', 'description' => 'Eksplorasi, penambangan bahan baku semen, dan manajemen teknik pertambangan.'],
            ['name' => 'AI & RPA', 'description' => 'Kecerdasan buatan dan Robotic Process Automation untuk efisiensi bisnis.'],
        ];

        $createdParents = [];
        foreach ($parents as $parent) {
            $createdParents[$parent['name']] = TnaCategory::updateOrCreate(['name' => $parent['name']], $parent);
        }

        // 2. Child Categories (Sub-categories)
        $children = [
            // Children of Mining
            ['name' => 'Mining Engineering', 'description' => 'Teknik pertambangan, peledakan (blasting), dan geoteknik tambang batu kapur.', 'parent_id' => $createdParents['Mining']->id],
            ['name' => 'Heavy Equipment Operation', 'description' => 'Pengoperasian dan pemeliharaan dump truck serta ekskavator tambang.', 'parent_id' => $createdParents['Mining']->id],
            
            // Children of AI & RPA
            ['name' => 'Otomasi RPA', 'description' => 'Pengembangan bot RPA untuk otomatisasi input data keuangan dan logistik.', 'parent_id' => $createdParents['AI & RPA']->id],
            ['name' => 'Machine Learning & Analytics', 'description' => 'Analisis prediktif untuk pemeliharaan mesin pabrik (predictive maintenance).', 'parent_id' => $createdParents['AI & RPA']->id],
            
            // Children of Technical Skills
            ['name' => 'Rotary Kiln Maintenance', 'description' => 'Teknik perawatan dan perbaikan tanur putar (rotary kiln) produksi semen.', 'parent_id' => $createdParents['Technical Skills']->id],
            ['name' => 'Electrical & Instrumentation', 'description' => 'Sistem kelistrikan tegangan tinggi dan kalibrasi instrumen pabrik.', 'parent_id' => $createdParents['Technical Skills']->id],
            ['name' => 'Produksi', 'description' => 'Manajemen lini produksi semen, penggilingan clinker, dan efisiensi mesin pabrik.', 'parent_id' => $createdParents['Technical Skills']->id],
            ['name' => 'Maintenance', 'description' => 'Perawatan rutin, overhaul mesin pabrik, dan keandalan peralatan mekanikal.', 'parent_id' => $createdParents['Technical Skills']->id],
            
            // Children of Safety & Compliance
            ['name' => 'K3 Pertambangan', 'description' => 'Sertifikasi keselamatan dan kesehatan kerja khusus area pertambangan mineral.', 'parent_id' => $createdParents['Safety & Compliance']->id],
            ['name' => 'Environmental Audit', 'description' => 'Audit pengelolaan limbah B3 dan pemantauan kualitas emisi udara pabrik.', 'parent_id' => $createdParents['Safety & Compliance']->id],
            ['name' => 'K3 & LH', 'description' => 'Protokol keselamatan kerja umum dan tata kelola lingkungan hidup pabrik.', 'parent_id' => $createdParents['Safety & Compliance']->id],

            // Children of Digital Transformation
            ['name' => 'IoT Sensor Pabrik', 'description' => 'Implementasi sensor cerdas untuk memantau suhu dan getaran motor penggilingan.', 'parent_id' => $createdParents['Digital Transformation']->id],
            ['name' => 'IT & Digital', 'description' => 'Pengembangan infrastruktur teknologi informasi, ERP, dan keamanan siber.', 'parent_id' => $createdParents['Digital Transformation']->id],

            // Children of Human Resources
            ['name' => 'Talent Management', 'description' => 'Perencanaan suksesi kepemimpinan dan identifikasi key talent perusahaan.', 'parent_id' => $createdParents['Human Resources']->id],

            // Children of Soft Skills
            ['name' => 'Leadership', 'description' => 'Pengembangan kepemimpinan strategis, coaching, dan manajemen tim.', 'parent_id' => $createdParents['Soft Skills']->id],
            ['name' => 'Management', 'description' => 'Pengelolaan proyek, rekayasa proses bisnis, dan efisiensi manajerial.', 'parent_id' => $createdParents['Soft Skills']->id],
            ['name' => 'Communication & Presentation', 'description' => 'Teknik komunikasi efektif, negosiasi bisnis, dan presentasi profesional.', 'parent_id' => $createdParents['Soft Skills']->id],
            ['name' => 'Negotiation & Bargaining', 'description' => 'Kemampuan negosiasi pengadaan dan manajemen kontrak vendor.', 'parent_id' => $createdParents['Soft Skills']->id],

            // Children of Supply Chain Management
            ['name' => 'Logistik', 'description' => 'Manajemen pergudangan, optimasi inventori, dan distribusi rantai pasok semen.', 'parent_id' => $createdParents['Supply Chain Management']->id],

            // Children of Finance & Accounting
            ['name' => 'Finance', 'description' => 'Penyusunan anggaran, kontrol biaya operasional, dan analisis finansial.', 'parent_id' => $createdParents['Finance & Accounting']->id],

            // Children of Quality Control
            ['name' => 'Quality', 'description' => 'Pengendalian mutu laboratorium, gugus kendali mutu (QCC), dan kalibrasi standar.', 'parent_id' => $createdParents['Quality Control']->id],

            // Children of Marketing & Sales
            ['name' => 'Marketing', 'description' => 'Strategi pemasaran digital, employer branding, dan riset pasar semen.', 'parent_id' => $createdParents['Marketing & Sales']->id],
        ];

        foreach ($children as $child) {
            TnaCategory::updateOrCreate(['name' => $child['name']], $child);
        }
    }
}
