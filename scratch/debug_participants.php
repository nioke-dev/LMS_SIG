<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Services\OrganizationService;

$lc = User::where('name', 'Nurul Mustofa')->first();
if (!$lc) {
    echo "User not found\n";
    exit;
}

$service = new OrganizationService();
$participants = $service->getParticipantsByLCScope($lc);

echo "LC: " . $lc->name . " (Org ID: " . $lc->organization_id . " - " . ($lc->organization ? $lc->organization->name : 'No Org') . ")\n";
echo "Participants Count: " . $participants->count() . "\n";
foreach ($participants->take(5) as $p) {
    echo "- " . $p->name . " (NIK: " . $p->nik . ")\n";
}
