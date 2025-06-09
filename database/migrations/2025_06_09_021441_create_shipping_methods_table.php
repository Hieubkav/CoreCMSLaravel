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
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Pricing
            $table->enum('cost_type', ['fixed', 'weight_based', 'free', 'calculated'])->default('fixed');
            $table->decimal('cost', 10, 2)->default(0);
            $table->decimal('free_shipping_threshold', 10, 2)->nullable(); // Free shipping over amount

            // Weight-based pricing
            $table->decimal('cost_per_kg', 10, 2)->nullable();
            $table->decimal('base_cost', 10, 2)->nullable();

            // Delivery time
            $table->integer('min_delivery_days')->nullable();
            $table->integer('max_delivery_days')->nullable();
            $table->string('delivery_time_text')->nullable(); // "2-3 business days"

            // Restrictions
            $table->json('allowed_countries')->nullable(); // Country codes
            $table->json('excluded_countries')->nullable();
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->decimal('max_order_amount', 10, 2)->nullable();
            $table->decimal('max_weight', 8, 2)->nullable();

            // Settings
            $table->boolean('requires_address')->default(true);
            $table->boolean('is_pickup')->default(false); // Store pickup
            $table->string('pickup_address')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('order')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['status', 'order']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
