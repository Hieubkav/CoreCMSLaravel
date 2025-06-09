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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();

            // Product info at time of order (snapshot)
            $table->string('product_name');
            $table->string('product_sku');
            $table->text('product_description')->nullable();
            $table->string('product_image')->nullable();

            // Variant info (if applicable)
            $table->string('variant_name')->nullable();
            $table->json('variant_attributes')->nullable(); // Color: Red, Size: Large

            // Pricing and quantity
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);

            // Additional options
            $table->json('product_options')->nullable(); // Custom options, personalization

            $table->timestamps();

            // Foreign keys
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('set null');

            // Indexes
            $table->index('order_id');
            $table->index(['product_id', 'variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
