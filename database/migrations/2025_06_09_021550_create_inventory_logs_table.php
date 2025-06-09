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
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // Who made the change
            $table->unsignedBigInteger('order_id')->nullable(); // Related order (if applicable)

            // Stock change details
            $table->enum('type', [
                'sale', 'return', 'adjustment', 'restock',
                'damaged', 'expired', 'transfer', 'initial'
            ]);
            $table->integer('quantity_before');
            $table->integer('quantity_change'); // Can be negative
            $table->integer('quantity_after');

            // Additional info
            $table->string('reference')->nullable(); // Order number, adjustment reference, etc.
            $table->text('notes')->nullable();
            $table->json('meta_data')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');

            // Indexes
            $table->index(['product_id', 'variant_id']);
            $table->index(['type', 'created_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
