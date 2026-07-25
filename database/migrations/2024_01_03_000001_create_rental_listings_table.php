<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * rental_listings — Vendor-owned equipment available for rent via marketplace.
 * Separate from the legacy `items` table so nothing breaks.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_listings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();   // vendor
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();

            // May optionally link to a marketplace Product record (if vendor listed via Sell module)
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();

            // Core
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->json('images')->nullable();

            // Equipment metadata
            $table->string('brand')->nullable();
            $table->string('model_number')->nullable();
            $table->year('year_manufactured')->nullable();
            $table->enum('condition', ['new', 'used', 'refurbished'])->default('used');

            // Pricing
            $table->decimal('price_per_day', 12, 2);
            $table->decimal('price_per_week', 12, 2)->nullable();
            $table->decimal('deposit_amount', 12, 2)->default(0);

            // Availability
            $table->unsignedInteger('total_qty')->default(1);        // how many units exist
            $table->unsignedInteger('available_qty')->default(1);    // currently free

            // Logistics
            $table->string('location')->nullable();
            $table->boolean('offers_delivery')->default(false);
            $table->decimal('delivery_fee', 8, 2)->default(0);

            // Minimum / Maximum rental period (days)
            $table->unsignedSmallInteger('min_rental_days')->default(1);
            $table->unsignedSmallInteger('max_rental_days')->nullable();

            // Admin approval
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'inactive'])
                  ->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->unsignedInteger('view_count')->default(0);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_listings');
    }
};
