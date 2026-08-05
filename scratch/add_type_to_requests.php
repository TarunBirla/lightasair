<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

if (!Illuminate\Support\Facades\Schema::hasColumn('requests', 'product_type')) {
    Illuminate\Support\Facades\Schema::table('requests', function ($table) {
        $table->string('product_type')->nullable()->default('sell');
    });
    echo "Added product_type to requests table.\n";
} else {
    echo "product_type column already exists.\n";
}
