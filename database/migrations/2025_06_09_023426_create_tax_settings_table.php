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
        Schema::create('tax_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Tax configuration
            $table->enum('type', ['percentage', 'fixed', 'compound'])->default('percentage');
            $table->decimal('rate', 8, 4); // Tax rate (percentage or fixed amount)
            $table->boolean('is_inclusive')->default(false); // Tax included in price or added

            // Geographic restrictions
            $table->json('applicable_countries')->nullable(); // Country codes
            $table->json('applicable_states')->nullable(); // State/province codes
            $table->json('applicable_cities')->nullable(); // City names
            $table->json('postal_codes')->nullable(); // Postal code ranges

            // Product/Category restrictions
            $table->json('applicable_product_categories')->nullable(); // Category IDs
            $table->json('excluded_product_categories')->nullable(); // Excluded category IDs
            $table->json('applicable_products')->nullable(); // Specific product IDs
            $table->json('excluded_products')->nullable(); // Excluded product IDs

            // Customer restrictions
            $table->enum('customer_type', ['all', 'individual', 'business'])->default('all');
            $table->json('applicable_user_groups')->nullable(); // User group IDs

            // Date restrictions
            $table->datetime('starts_at')->nullable();
            $table->datetime('expires_at')->nullable();

            // Priority and status
            $table->integer('priority')->default(0); // Higher number = higher priority
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('order')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['status', 'priority']);
            $table->index(['starts_at', 'expires_at']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_settings');
    }
};
