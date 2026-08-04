<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = App\Models\User::all();
foreach ($users as $u) {
    echo "ID: {$u->id} | Name: {$u->name} | Email: {$u->email} | Role: {$u->role} | Password 123456: " . (Illuminate\Support\Facades\Hash::check('123456', $u->password) ? 'YES' : 'NO') . "\n";
}
