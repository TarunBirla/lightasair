<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * rental_bookings — Tracks customer bookings against rental_listings.
 * The legacy `bookings` table handles original Light As Air inventory.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_bookings', function (Blueprint $table) {
            $table->id();

            $table->string('booking_ref')->unique();                           // e.g. RB-20240724-0001
            $table->foreignId('rental_listing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete();

            // Dates
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedSmallInteger('total_days');

            // Pricing snapshot (locked at booking time)
            $table->decimal('price_per_day', 12, 2);
            $table->decimal('deposit_amount', 12, 2)->default(0);
            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->decimal('subtotal', 12, 2);      // price_per_day * qty * days
            $table->decimal('total_amount', 12, 2);  // subtotal + deposit + delivery

            $table->unsignedSmallInteger('qty')->default(1);

            // Delivery
            $table->boolean('requires_delivery')->default(false);
            $table->text('delivery_address')->nullable();

            // Status flow: pending → confirmed → active → returned → completed | cancelled
            $table->enum('status', [
                'pending', 'confirmed', 'active', 'returned', 'completed', 'cancelled'
            ])->default('pending');

            // Deposit tracking
            $table->enum('deposit_status', ['pending', 'held', 'refunded', 'forfeited'])->default('pending');
            $table->timestamp('deposit_refunded_at')->nullable();

            // Payment
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');

            $table->text('customer_notes')->nullable();
            $table->text('vendor_notes')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_bookings');
    }
};
