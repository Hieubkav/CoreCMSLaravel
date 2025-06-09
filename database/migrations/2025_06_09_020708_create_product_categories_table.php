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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable(); // FontAwesome class
            $table->json('meta_data')->nullable(); // Flexible metadata
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('order')->default(0);

            // SEO fields
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('og_image')->nullable();

            $table->timestamps();

            // Foreign key
            $table->foreign('parent_id')->references('id')->on('product_categories')->onDelete('cascade');

            // Indexes
            $table->index(['parent_id', 'order']);
            $table->index(['status', 'order']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
