<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();

            // Seller & Category
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();

            // Title & Details
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->enum('condition', ['new', 'used', 'refurbished'])->default('used');
            $table->json('images')->nullable();
            $table->string('location')->nullable();

            // Pricing & Bidding
            $table->decimal('starting_bid', 12, 2)->default(0.00);
            $table->decimal('reserve_price', 12, 2)->default(0.00);
            $table->decimal('current_bid', 12, 2)->default(0.00);
            $table->decimal('min_increment', 12, 2)->default(5.00);
            $table->unsignedInteger('bid_count')->default(0);

            // Timings
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();

            // Status & Approval
            $table->enum('status', ['draft', 'pending', 'active', 'ended', 'cancelled', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            // Winner
            $table->foreignId('winner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('winning_bid', 12, 2)->nullable();
            $table->enum('payment_status', ['unpaid', 'paid', 'forfeited'])->default('unpaid');

            // Metrics
            $table->unsignedInteger('view_count')->default(0);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
