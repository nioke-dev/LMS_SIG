<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$email = 'nurul.mustofa@sig.co.id';
$user = User::where('email', $email)->first();

if ($user) {
    echo "User: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Primary Role: " . $user->role . "\n";
    echo "Roles (JSON): " . json_encode($user->roles) . "\n";
} else {
    echo "User not found!\n";
}
