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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('label');
            $table->enum('menu_type', [
                'custom_link',
                'post_category',
                'post_detail',
                'all_posts',
                'product_category',
                'product_detail',
                'all_products',
                'home'
            ])->default('custom_link');
            $table->string('link')->nullable();
            $table->string('icon')->nullable(); // FontAwesome class
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('order')->default(0);

            $table->timestamps();

            // Foreign key
            $table->foreign('parent_id')->references('id')->on('menu_items')->onDelete('cascade');

            // Indexes
            $table->index(['parent_id', 'order']);
            $table->index(['status', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
