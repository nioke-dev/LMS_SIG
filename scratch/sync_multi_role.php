<?php

use App\Models\User;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = User::where('email', 'nurul.mustofa@sig.co.id')->first();

if ($user) {
    $user->update([
        'roles' => [
            User::ROLE_LEARNING_COORDINATOR,
            User::ROLE_ADMIN_COORDINATOR
        ]
    ]);
    echo "Multi-role berhasil ditambahkan untuk: " . $user->email . "\n";
    echo "Roles aktif: " . implode(', ', $user->roles) . "\n";
} else {
    echo "User tidak ditemukan.\n";
}
