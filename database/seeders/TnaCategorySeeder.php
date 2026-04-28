<?php

namespace Database\Seeders;

use App\Models\TnaCategory;
use Illuminate\Database\Seeder;

class TnaCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
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
        ];

        foreach ($categories as $category) {
            TnaCategory::create($category);
        }
    }
}
