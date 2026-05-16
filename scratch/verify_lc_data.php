<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TnaSubmission;
use App\DTOs\TnaSubmissionDTO;

echo "=== VERIFIKASI DATA LC ===\n";
echo "Total Usulan: " . TnaSubmission::count() . "\n";
echo "Status Review: " . TnaSubmission::where('status', 'review')->count() . "\n";
echo "Status Approved: " . TnaSubmission::where('status', 'approved')->count() . "\n";
echo "Status Draft: " . TnaSubmission::where('status', 'draft')->count() . "\n";
echo "Status Rejected: " . TnaSubmission::where('status', 'rejected')->count() . "\n";

echo "\n=== INTEGRITY CHECK (DTO) ===\n";
$latest = TnaSubmission::latest()->first();
if ($latest) {
    try {
        $dto = TnaSubmissionDTO::fromModel($latest);
        echo "✅ DTO mapping berhasil untuk ID: " . $dto->id . "\n";
        echo "   Judul: " . $dto->title . "\n";
        echo "   Tanggal: " . $dto->date . "\n";
    } catch (\Exception $e) {
        echo "❌ DTO mapping GAGAL: " . $e->getMessage() . "\n";
    }
} else {
    echo "⚠️ Tidak ada data usulan untuk dicek.\n";
}

echo "\n=== VERIFIKASI SEEDER SCENARIO ===\n";
$scenarioIds = ['TNA-2024-DRAFT-001', 'TNA-2024-REV-001', 'TNA-2024-APP-001', 'TNA-2024-REJ-001'];
foreach ($scenarioIds as $id) {
    $exists = TnaSubmission::where('id', $id)->exists();
    echo ($exists ? "✅ " : "❌ ") . "Scenario ID: $id " . ($exists ? "Ditemukan" : "TIDAK Ditemukan") . "\n";
}
