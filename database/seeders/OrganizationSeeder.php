<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\OrgLevel;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Parent Company (Holding)
        $holding = Company::create([
            'name' => 'PT Semen Indonesia (Persero) Tbk',
            'description' => 'Holding Company SIG',
        ]);

        // 2. Define Levels for Holding (0 to 4)
        $levels = [
            ['name' => 'Company', 'order' => 0],
            ['name' => 'Direktorat', 'order' => 1],
            ['name' => 'Group of', 'order' => 2],
            ['name' => 'Department', 'order' => 3],
            ['name' => 'Unit', 'order' => 4],
        ];

        $levelModels = [];
        foreach ($levels as $l) {
            $levelModels[$l['order']] = OrgLevel::create([
                'company_id' => $holding->id,
                'name' => $l['name'],
                'order' => $l['order'],
            ]);
        }

        // 3. Create actual Nodes (Organizations)
        
        // Level 0: PT Semen Indonesia (Persero) Tbk
        $sig = Organization::create([
            'code' => 'SIG-LVL0',
            'name' => 'PT Semen Indonesia (Persero) Tbk',
            'org_level_id' => $levelModels[0]->id,
            'parent_id' => null,
        ]);

        // Level 1: Core Directorates for SIG
        $dirUtama = Organization::create([
            'code' => 'SIG-DIR-UTAMA',
            'name' => 'Direktorat Utama',
            'org_level_id' => $levelModels[1]->id,
            'parent_id' => $sig->id,
        ]);

        $dirWakilUtama = Organization::create([
            'code' => 'SIG-DIR-WUTAMA',
            'name' => 'Direktorat Wakil Direktur Utama',
            'org_level_id' => $levelModels[1]->id,
            'parent_id' => $sig->id,
        ]);

        $dirBisnis = Organization::create([
            'code' => 'SIG-DIR-BISNIS',
            'name' => 'Direktorat Pengembangan Bisnis dan Strategi',
            'org_level_id' => $levelModels[1]->id,
            'parent_id' => $sig->id,
        ]);

        $dirSales = Organization::create([
            'code' => 'SIG-DIR-SALES',
            'name' => 'Direktorat Sales dan Marketing',
            'org_level_id' => $levelModels[1]->id,
            'parent_id' => $sig->id,
        ]);

        $dirOps = Organization::create([
            'code' => 'SIG-DIR-OPS',
            'name' => 'Direktorat Operasi',
            'org_level_id' => $levelModels[1]->id,
            'parent_id' => $sig->id,
        ]);

        $dirKeuangan = Organization::create([
            'code' => 'SIG-DIR-KEU',
            'name' => 'Direktorat Keuangan dan Manajemen Risiko',
            'org_level_id' => $levelModels[1]->id,
            'parent_id' => $sig->id,
        ]);

        $dirHC = Organization::create([
            'code' => 'SIG-DIR-HC',
            'name' => 'Direktorat Human Capital',
            'org_level_id' => $levelModels[1]->id,
            'parent_id' => $sig->id,
        ]);

        // Level 2: Group of Operational Human Capital (Under Dir HC)
        $grpOHC = Organization::create([
            'code' => 'SIG-GRP-OHC',
            'name' => 'Group of Operational Human Capital',
            'org_level_id' => $levelModels[2]->id,
            'parent_id' => $dirHC->id,
        ]);

        // Level 3: Department of Corporate Learning & Development (Under Group OHC)
        $deptCLD = Organization::create([
            'code' => 'SIG-DEPT-CLD',
            'name' => 'Department of Corporate Learning & Development',
            'org_level_id' => $levelModels[3]->id,
            'parent_id' => $grpOHC->id,
        ]);

        // Level 4: Unit of Competency and Learning Design (Under Dept CLD)
        $unitCLD = Organization::create([
            'code' => 'SIG-UNIT-CLD',
            'name' => 'Unit of Competency and Learning Design',
            'org_level_id' => $levelModels[4]->id,
            'parent_id' => $deptCLD->id,
        ]);

        // --- Operations Branch Example ---
        
        // Level 2: Group of Plant Operation (Under Dir Ops)
        $grpPlant = Organization::create([
            'code' => 'SIG-GRP-PLANT',
            'name' => 'Group of Plant Operation',
            'org_level_id' => $levelModels[2]->id,
            'parent_id' => $dirOps->id,
        ]);
        
        // Level 3: Department of Production (Under Group Plant)
        $deptProd = Organization::create([
            'code' => 'SIG-DEPT-PROD',
            'name' => 'Department of Production',
            'org_level_id' => $levelModels[3]->id,
            'parent_id' => $grpPlant->id,
        ]);

        // Level 4: Unit of Production Tuban 1
        $unitProd = Organization::create([
            'code' => 'SIG-UNIT-PROD1',
            'name' => 'Unit of Production Tuban 1',
            'org_level_id' => $levelModels[4]->id,
            'parent_id' => $deptProd->id,
        ]);

        // ============================================================
        // 4. CREATE SUBSIDIARIES (ANAK PERUSAHAAN)
        // ============================================================
        
        $subsidiaries = [
            ['name' => 'PT Semen Padang', 'code' => 'SP'],
            ['name' => 'PT Semen Gresik', 'code' => 'SG'],
            ['name' => 'PT Semen Tonasa', 'code' => 'ST'],
            ['name' => 'Thang Long Cement Joint Stock Company (TLCC)', 'code' => 'TLCC'],
            ['name' => 'PT Sinergi Mitra Investama', 'code' => 'SMI'],
            ['name' => 'PT Semen Indonesia Beton', 'code' => 'SIB'],
            ['name' => 'PT United Tractors Semen Gresik (UTSG)', 'code' => 'UTSG'],
            ['name' => 'PT Industri Kemasan Semen Gresik', 'code' => 'IKSG'],
            ['name' => 'PT Kawasan Industri Gresik', 'code' => 'KIG'],
            ['name' => 'PT Semen Kupang Indonesia', 'code' => 'SKI'],
            ['name' => 'PT Semen Indonesia Industri Bangunan', 'code' => 'SIIB'],
            ['name' => 'PT Solusi Bangun Indonesia Tbk', 'code' => 'SBI'],
            ['name' => 'PT Semen Indonesia Aceh', 'code' => 'SIA'],
            ['name' => 'PT Sinergi Informatika Semen Indonesia', 'code' => 'SISI'],
            ['name' => 'PT Semen Indonesia Internasional', 'code' => 'SII'],
            ['name' => 'PT Semen Indonesia Logistik', 'code' => 'SILOG'],
            ['name' => 'PT Semen Baturaja Tbk', 'code' => 'SMBR'],
        ];

        foreach ($subsidiaries as $subData) {
            // Create Company
            $subCompany = Company::create([
                'name' => $subData['name'],
                'description' => 'Subsidiary of SIG',
            ]);

            // Define 3 Levels for Subsidiary: Company (0), Department (1), Unit (2)
            $subLevelSetup = [
                ['name' => 'Company', 'order' => 0],
                ['name' => 'Department', 'order' => 1],
                ['name' => 'Unit', 'order' => 2],
            ];

            $subLevelModels = [];
            foreach ($subLevelSetup as $sl) {
                $subLevelModels[$sl['order']] = OrgLevel::create([
                    'company_id' => $subCompany->id,
                    'name' => $sl['name'],
                    'order' => $sl['order'],
                ]);
            }

            // Create Organization Nodes for this Subsidiary
            
            // Level 0: The Company itself as a node
            $subNode0 = Organization::create([
                'code' => $subData['code'] . '-LVL0',
                'name' => $subData['name'],
                'org_level_id' => $subLevelModels[0]->id,
                'parent_id' => null,
            ]);

            // Level 1: Sample Department
            $subNode1 = Organization::create([
                'code' => $subData['code'] . '-DEPT-SAMPLE',
                'name' => 'Department of ' . $subData['name'] . ' Operations',
                'org_level_id' => $subLevelModels[1]->id,
                'parent_id' => $subNode0->id,
            ]);

            // Level 2: Sample Unit
            $subNode2 = Organization::create([
                'code' => $subData['code'] . '-UNIT-SAMPLE',
                'name' => 'Unit of ' . $subData['name'] . ' Support',
                'org_level_id' => $subLevelModels[2]->id,
                'parent_id' => $subNode1->id,
            ]);
        }
    }
}
