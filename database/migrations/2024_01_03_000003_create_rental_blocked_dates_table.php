<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * rental_blocked_dates — Vendor manually blocks dates (equipment away, maintenance, etc.)
 * Combined with rental_bookings, these two tables power the availability calendar.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_listing_id')->constrained()->cascadeOnDelete();
            $table->date('blocked_date');
            $table->string('reason')->nullable();  // e.g. "Maintenance", "Personal use"
            $table->timestamps();

            $table->unique(['rental_listing_id', 'blocked_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_blocked_dates');
    }
};
