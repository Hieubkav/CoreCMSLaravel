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
        Schema::create('web_designs', function (Blueprint $table) {
            $table->id();
            
            // Basic information
            $table->string('name')->comment('Tên thiết kế');
            $table->text('description')->nullable()->comment('Mô tả thiết kế');
            $table->enum('theme_type', ['modern', 'classic', 'minimalist', 'creative', 'corporate', 'ecommerce', 'blog', 'portfolio'])->default('modern')->comment('Loại theme');
            $table->enum('color_scheme', ['light', 'dark', 'auto'])->default('light')->comment('Chế độ màu');
            
            // Color settings
            $table->string('primary_color', 7)->default('#dc2626')->comment('Màu chính');
            $table->string('secondary_color', 7)->default('#6b7280')->comment('Màu phụ');
            $table->string('accent_color', 7)->default('#f59e0b')->comment('Màu nhấn');
            $table->string('background_color', 7)->default('#ffffff')->comment('Màu nền');
            $table->string('text_color', 7)->default('#1f2937')->comment('Màu chữ');
            $table->string('link_color', 7)->default('#dc2626')->comment('Màu liên kết');
            $table->string('border_color', 7)->default('#e5e7eb')->comment('Màu viền');
            
            // Typography settings
            $table->string('font_family_primary')->default('Inter')->comment('Font chính');
            $table->string('font_family_secondary')->default('Inter')->comment('Font phụ');
            $table->integer('font_size_base')->default(16)->comment('Kích thước font cơ bản (px)');
            $table->integer('font_weight_normal')->default(400)->comment('Độ đậm font thường');
            $table->integer('font_weight_bold')->default(600)->comment('Độ đậm font đậm');
            $table->string('line_height')->default('1.5')->comment('Chiều cao dòng');
            $table->string('letter_spacing')->default('0')->comment('Khoảng cách chữ');
            
            // Design elements
            $table->integer('border_radius')->default(8)->comment('Bo góc (px)');
            $table->string('box_shadow')->default('0 1px 3px rgba(0, 0, 0, 0.1)')->comment('Đổ bóng');
            $table->string('transition_duration')->default('200ms')->comment('Thời gian chuyển đổi');
            $table->enum('animation_style', ['none', 'smooth', 'bounce', 'elastic', 'fade', 'slide'])->default('smooth')->comment('Kiểu animation');
            
            // Layout settings
            $table->enum('layout_style', ['boxed', 'full-width', 'fluid'])->default('boxed')->comment('Kiểu layout');
            $table->integer('container_width')->default(1200)->comment('Chiều rộng container (px)');
            $table->integer('sidebar_width')->default(300)->comment('Chiều rộng sidebar (px)');
            $table->integer('header_height')->default(80)->comment('Chiều cao header (px)');
            $table->integer('footer_height')->default(200)->comment('Chiều cao footer (px)');
            $table->integer('spacing_unit')->default(16)->comment('Đơn vị khoảng cách (px)');
            $table->integer('grid_columns')->default(12)->comment('Số cột grid');
            
            // Responsive breakpoints
            $table->integer('breakpoint_mobile')->default(640)->comment('Breakpoint mobile (px)');
            $table->integer('breakpoint_tablet')->default(768)->comment('Breakpoint tablet (px)');
            $table->integer('breakpoint_desktop')->default(1024)->comment('Breakpoint desktop (px)');
            
            // Hero section
            $table->enum('hero_style', ['gradient', 'image', 'video', 'solid', 'pattern'])->default('gradient')->comment('Kiểu hero');
            $table->integer('hero_height')->default(500)->comment('Chiều cao hero (px)');
            $table->boolean('hero_overlay')->default(true)->comment('Overlay hero');
            $table->enum('hero_text_align', ['left', 'center', 'right'])->default('center')->comment('Căn chỉnh text hero');
            
            // Button styles
            $table->enum('button_style', ['solid', 'outlined', 'ghost', 'gradient'])->default('solid')->comment('Kiểu button');
            $table->enum('button_size', ['small', 'medium', 'large', 'extra-large'])->default('medium')->comment('Kích thước button');
            $table->integer('button_radius')->default(8)->comment('Bo góc button (px)');
            
            // Card styles
            $table->enum('card_style', ['flat', 'elevated', 'outlined', 'filled'])->default('elevated')->comment('Kiểu card');
            $table->string('card_shadow')->default('0 4px 6px rgba(0, 0, 0, 0.1)')->comment('Đổ bóng card');
            $table->integer('card_radius')->default(12)->comment('Bo góc card (px)');
            
            // Form styles
            $table->enum('form_style', ['modern', 'classic', 'minimal'])->default('modern')->comment('Kiểu form');
            $table->enum('input_style', ['outlined', 'filled', 'underlined'])->default('outlined')->comment('Kiểu input');
            $table->integer('input_radius')->default(8)->comment('Bo góc input (px)');
            
            // Navigation styles
            $table->enum('navigation_style', ['horizontal', 'vertical', 'mega', 'sidebar'])->default('horizontal')->comment('Kiểu navigation');
            $table->enum('navigation_position', ['top', 'bottom', 'left', 'right', 'sticky'])->default('top')->comment('Vị trí navigation');
            $table->string('navigation_background')->default('transparent')->comment('Nền navigation');
            
            // Footer styles
            $table->enum('footer_style', ['modern', 'classic', 'minimal'])->default('modern')->comment('Kiểu footer');
            $table->string('footer_background')->default('#1f2937')->comment('Nền footer');
            
            // Custom code
            $table->longText('custom_css')->nullable()->comment('CSS tùy chỉnh');
            $table->longText('custom_js')->nullable()->comment('JavaScript tùy chỉnh');
            
            // Preview
            $table->string('preview_image')->nullable()->comment('Ảnh preview');
            
            // Status and ordering
            $table->boolean('is_active')->default(true)->comment('Đang sử dụng');
            $table->boolean('is_default')->default(false)->comment('Mặc định');
            $table->integer('order')->default(0)->comment('Thứ tự sắp xếp');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index('is_active');
            $table->index(['is_active', 'order']);
            $table->index('is_default');
            $table->index('theme_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_designs');
    }
};
