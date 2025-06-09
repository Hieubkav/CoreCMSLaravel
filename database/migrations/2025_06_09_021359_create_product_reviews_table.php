<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id')->nullable(); // Null for guest reviews
            $table->unsignedBigInteger('order_id')->nullable(); // Link to order (verified purchase)

            // Review content
            $table->string('reviewer_name');
            $table->string('reviewer_email');
            $table->integer('rating'); // 1-5 stars
            $table->string('title')->nullable();
            $table->text('content');

            // Review metadata
            $table->boolean('is_verified_purchase')->default(false);
            $table->json('helpful_votes')->nullable(); // User IDs who found it helpful
            $table->integer('helpful_count')->default(0);

            // Moderation
            $table->enum('status', ['pending', 'approved', 'rejected', 'spam'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();

            // Images/attachments
            $table->json('images')->nullable(); // Review images

            $table->timestamps();

            // Foreign keys
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');

            // Indexes
            $table->index(['product_id', 'status']);
            $table->index(['user_id', 'product_id']);
            $table->index('rating');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
