<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$v = App\Models\User::where('email', 'vendor@gmail.com')->first();
echo "User ID: {$v->id}\n";
echo "Role: {$v->role}\n";
echo "Status: {$v->status}\n";

$vp = $v->vendorProfile;
if ($vp) {
    echo "Vendor Profile ID: {$vp->id}\n";
    echo "Approval Status: {$vp->approval_status}\n";
} else {
    echo "No Vendor Profile found!\n";
}
