<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'company_name')) {
                $table->string('company_name')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('role');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('is_verified');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('status');
            }
        });

        // Update role column to include all needed values (vendor if not already)
        // We use raw SQL to safely modify the enum
        try {
            \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('customer','vendor','admin','user') NOT NULL DEFAULT 'customer'");
        } catch (\Exception $e) {
            // Column may already have correct values
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = ['phone', 'company_name', 'is_verified', 'status', 'avatar'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
