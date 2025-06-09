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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('sku')->unique();

            // Pricing
            $table->decimal('price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();

            // Inventory
            $table->integer('stock_quantity')->default(0);
            $table->boolean('manage_stock')->default(true);
            $table->boolean('in_stock')->default(true);
            $table->integer('low_stock_threshold')->default(5);

            // Physical properties
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();

            // Images
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();

            // Product type & status
            $table->enum('type', ['simple', 'variable', 'grouped', 'external'])->default('simple');
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_digital')->default(false);

            // SEO & Meta
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('og_image')->nullable();
            $table->json('meta_data')->nullable();

            // Stats
            $table->integer('view_count')->default(0);
            $table->integer('order_count')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('review_count')->default(0);

            $table->integer('order')->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');

            // Indexes
            $table->index(['category_id', 'status']);
            $table->index(['status', 'is_featured']);
            $table->index(['status', 'order']);
            $table->index('sku');
            $table->index('slug');
            $table->index('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
