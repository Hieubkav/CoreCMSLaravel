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
        Schema::create('system_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Tên cấu hình');
            $table->text('description')->nullable()->comment('Mô tả cấu hình');

            // Theme Configuration
            $table->enum('theme_mode', ['light', 'dark', 'auto'])->default('light')->comment('Chế độ theme');
            $table->string('primary_color', 7)->default('#dc2626')->comment('Màu chính');
            $table->string('secondary_color', 7)->default('#6b7280')->comment('Màu phụ');
            $table->string('accent_color', 7)->default('#3b82f6')->comment('Màu nhấn');

            // Typography
            $table->string('font_family')->default('Inter')->comment('Font chữ');
            $table->enum('font_size', ['sm', 'base', 'lg', 'xl'])->default('base')->comment('Kích thước chữ');

            // Design Style
            $table->enum('design_style', ['minimalist', 'modern', 'classic', 'creative', 'corporate', 'elegant'])->default('minimalist')->comment('Phong cách thiết kế');

            // Assets
            $table->string('favicon')->nullable()->comment('Favicon path');
            $table->string('logo')->nullable()->comment('Logo path');

            // System Configuration
            $table->json('error_pages')->nullable()->comment('Cấu hình trang lỗi');
            $table->enum('icon_system', ['fontawesome', 'heroicons', 'feather', 'tabler', 'phosphor'])->default('fontawesome')->comment('Hệ thống icon');
            $table->json('analytics_config')->nullable()->comment('Cấu hình analytics');

            // Custom Code
            $table->longText('custom_css')->nullable()->comment('CSS tùy chỉnh');
            $table->longText('custom_js')->nullable()->comment('JavaScript tùy chỉnh');

            // Status & Order
            $table->boolean('is_active')->default(true)->comment('Trạng thái kích hoạt');
            $table->integer('order')->default(0)->comment('Thứ tự sắp xếp');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['is_active', 'order']);
            $table->index('theme_mode');
            $table->index('design_style');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_configurations');
    }
};
