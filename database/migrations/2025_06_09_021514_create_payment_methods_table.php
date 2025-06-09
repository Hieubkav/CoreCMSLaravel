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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // FontAwesome or image

            // Payment gateway settings
            $table->enum('type', [
                'cash_on_delivery', 'bank_transfer', 'credit_card',
                'paypal', 'stripe', 'vnpay', 'momo', 'zalopay'
            ])->default('cash_on_delivery');

            // Gateway configuration (encrypted)
            $table->json('gateway_config')->nullable(); // API keys, merchant IDs, etc.

            // Fees
            $table->decimal('fixed_fee', 10, 2)->default(0);
            $table->decimal('percentage_fee', 5, 2)->default(0); // Percentage fee
            $table->decimal('min_fee', 10, 2)->default(0);
            $table->decimal('max_fee', 10, 2)->nullable();

            // Restrictions
            $table->decimal('min_amount', 10, 2)->nullable();
            $table->decimal('max_amount', 10, 2)->nullable();
            $table->json('allowed_countries')->nullable();
            $table->json('allowed_currencies')->nullable();

            // Settings
            $table->boolean('requires_verification')->default(false);
            $table->text('instructions')->nullable(); // Payment instructions for customers
            $table->string('redirect_url')->nullable(); // For external gateways

            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->integer('order')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['status', 'order']);
            $table->index('slug');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
