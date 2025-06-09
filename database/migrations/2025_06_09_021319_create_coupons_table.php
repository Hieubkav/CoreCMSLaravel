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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();

            // Discount settings
            $table->enum('type', ['fixed', 'percentage'])->default('percentage');
            $table->decimal('value', 12, 2); // Amount or percentage
            $table->decimal('minimum_amount', 12, 2)->nullable(); // Minimum order amount
            $table->decimal('maximum_discount', 12, 2)->nullable(); // Maximum discount amount

            // Usage limits
            $table->integer('usage_limit')->nullable(); // Total usage limit
            $table->integer('usage_limit_per_user')->nullable(); // Per user limit
            $table->integer('used_count')->default(0); // Current usage count

            // Date restrictions
            $table->datetime('starts_at')->nullable();
            $table->datetime('expires_at')->nullable();

            // Product/Category restrictions
            $table->json('applicable_products')->nullable(); // Product IDs
            $table->json('applicable_categories')->nullable(); // Category IDs
            $table->json('excluded_products')->nullable(); // Excluded product IDs
            $table->json('excluded_categories')->nullable(); // Excluded category IDs

            // User restrictions
            $table->json('applicable_users')->nullable(); // User IDs or emails
            $table->boolean('first_order_only')->default(false);

            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->integer('order')->default(0);

            $table->timestamps();

            // Indexes
            $table->index('code');
            $table->index(['status', 'starts_at', 'expires_at']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
