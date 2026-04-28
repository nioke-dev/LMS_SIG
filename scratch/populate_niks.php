<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

User::all()->each(function($u, $i) {
    $u->nik = '1000' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);
    $u->save();
});

echo "Updated " . User::count() . " users with NIKs.\n";
