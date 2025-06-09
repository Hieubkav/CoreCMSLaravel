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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Null for guest carts
            $table->string('session_id')->nullable(); // For guest carts
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable(); // If product has variants
            $table->integer('quantity');
            $table->decimal('price', 12, 2); // Price at time of adding to cart
            $table->json('product_options')->nullable(); // Custom options, gift messages, etc.

            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');

            // Indexes
            $table->index(['user_id', 'product_id']);
            $table->index(['session_id', 'product_id']);
            $table->index('created_at'); // For cleanup old guest carts
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
