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

            // Hierarchical structure
            $table->unsignedBigInteger('parent_id')->nullable()->comment('Menu cha');

            // Basic information
            $table->string('title')->comment('Tiêu đề menu');
            $table->string('slug')->nullable()->comment('Slug URL');
            $table->string('url')->nullable()->comment('URL tùy chỉnh');
            $table->string('route_name')->nullable()->comment('Tên route Laravel');

            // Display options
            $table->string('icon')->nullable()->comment('Icon class (Font Awesome)');
            $table->text('description')->nullable()->comment('Mô tả menu');
            $table->enum('target', ['_self', '_blank', '_parent', '_top'])->default('_self')->comment('Target link');
            $table->string('css_class')->nullable()->comment('CSS class tùy chỉnh');

            // Menu organization
            $table->enum('menu_location', ['main', 'header', 'footer', 'sidebar', 'mobile', 'breadcrumb'])->default('main')->comment('Vị trí menu');
            $table->boolean('is_active')->default(true)->comment('Trạng thái kích hoạt');
            $table->boolean('is_featured')->default(false)->comment('Menu nổi bật');
            $table->integer('order')->default(0)->comment('Thứ tự sắp xếp');

            // SEO fields
            $table->string('meta_title')->nullable()->comment('SEO title');
            $table->text('meta_description')->nullable()->comment('SEO description');

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['parent_id', 'menu_location', 'order']);
            $table->index(['is_active', 'menu_location']);
            $table->index(['is_featured', 'menu_location']);
            $table->index('slug');

            // Foreign key constraint
            $table->foreign('parent_id')->references('id')->on('menu_items')->onDelete('cascade');
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
