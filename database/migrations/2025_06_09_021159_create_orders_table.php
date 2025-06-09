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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('user_id')->nullable(); // Null for guest orders

            // Customer information
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->string('customer_name');

            // Billing address
            $table->string('billing_first_name');
            $table->string('billing_last_name');
            $table->string('billing_company')->nullable();
            $table->string('billing_address_1');
            $table->string('billing_address_2')->nullable();
            $table->string('billing_city');
            $table->string('billing_state');
            $table->string('billing_postcode');
            $table->string('billing_country');

            // Shipping address
            $table->boolean('ship_to_different_address')->default(false);
            $table->string('shipping_first_name')->nullable();
            $table->string('shipping_last_name')->nullable();
            $table->string('shipping_company')->nullable();
            $table->string('shipping_address_1')->nullable();
            $table->string('shipping_address_2')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_postcode')->nullable();
            $table->string('shipping_country')->nullable();

            // Order totals
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('shipping_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            // Order status and payment
            $table->enum('status', [
                'pending', 'processing', 'shipped', 'delivered',
                'cancelled', 'refunded', 'failed'
            ])->default('pending');
            $table->enum('payment_status', [
                'pending', 'paid', 'failed', 'refunded', 'partially_refunded'
            ])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();

            // Additional info
            $table->text('notes')->nullable();
            $table->json('meta_data')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index('order_number');
            $table->index(['user_id', 'status']);
            $table->index('status');
            $table->index('payment_status');
            $table->index('customer_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
