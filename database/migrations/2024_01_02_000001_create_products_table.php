<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Seller info
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();

            // Core details
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description')->nullable();

            // Condition: new | used | refurbished
            $table->enum('condition', ['new', 'used', 'refurbished'])->default('used');

            // Listing type: sell | rent | auction
            $table->enum('listing_type', ['sell', 'rent', 'auction'])->default('sell');

            // Pricing
            $table->decimal('price', 12, 2)->nullable();          // sell price
            $table->decimal('rental_price_day', 12, 2)->nullable(); // daily rental
            $table->decimal('rental_price_week', 12, 2)->nullable();
            $table->decimal('deposit_amount', 12, 2)->nullable();
            $table->decimal('reserve_price', 12, 2)->nullable();   // auction reserve

            // Stock
            $table->unsignedInteger('quantity')->default(1);
            $table->string('sku')->nullable()->unique();
            $table->string('brand')->nullable();
            $table->string('model_number')->nullable();
            $table->year('year_manufactured')->nullable();

            // Images stored as JSON array of paths
            $table->json('images')->nullable();

            // Delivery / collection
            $table->boolean('offers_shipping')->default(false);
            $table->boolean('offers_collection')->default(true);
            $table->string('location')->nullable();

            // Admin approval
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'sold', 'inactive'])
                  ->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            // Metrics
            $table->unsignedInteger('view_count')->default(0);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
