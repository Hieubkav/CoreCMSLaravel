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
        Schema::create('analytics_reportings', function (Blueprint $table) {
            $table->id();
            
            // Metric information
            $table->string('metric_name')->comment('Tên metric');
            $table->decimal('metric_value', 15, 4)->default(0)->comment('Giá trị metric');
            $table->enum('metric_type', ['counter', 'gauge', 'timer', 'percentage', 'currency', 'event'])->default('counter')->comment('Loại metric');
            $table->string('dimension')->nullable()->comment('Chiều dữ liệu');
            
            // Time dimensions
            $table->date('date')->comment('Ngày');
            $table->tinyInteger('hour')->nullable()->comment('Giờ (0-23)');
            
            // User and session info
            $table->unsignedBigInteger('user_id')->nullable()->comment('ID người dùng');
            $table->string('session_id')->nullable()->comment('Session ID');
            $table->string('ip_address', 45)->comment('Địa chỉ IP');
            $table->text('user_agent')->nullable()->comment('User agent');
            $table->text('referer')->nullable()->comment('Trang giới thiệu');
            $table->text('page_url')->nullable()->comment('URL trang');
            
            // Event tracking
            $table->enum('event_category', ['page_view', 'user_interaction', 'form_submission', 'download', 'video', 'social', 'ecommerce', 'search', 'navigation', 'error', 'performance', 'custom'])->nullable()->comment('Danh mục sự kiện');
            $table->string('event_action')->nullable()->comment('Hành động sự kiện');
            $table->string('event_label')->nullable()->comment('Nhãn sự kiện');
            $table->json('custom_data')->nullable()->comment('Dữ liệu tùy chỉnh');
            
            // Device and location info
            $table->enum('device_type', ['desktop', 'mobile', 'tablet'])->default('desktop')->comment('Loại thiết bị');
            $table->string('browser')->nullable()->comment('Trình duyệt');
            $table->string('os')->nullable()->comment('Hệ điều hành');
            $table->string('country', 2)->default('VN')->comment('Quốc gia');
            $table->string('city')->nullable()->comment('Thành phố');
            $table->string('language', 5)->default('vi')->comment('Ngôn ngữ');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index('metric_name');
            $table->index('metric_type');
            $table->index(['date', 'hour']);
            $table->index('user_id');
            $table->index('session_id');
            $table->index('ip_address');
            $table->index('event_category');
            $table->index('device_type');
            $table->index('country');
            $table->index('language');
            $table->index('created_at');
            $table->index(['metric_name', 'date']);
            $table->index(['event_category', 'event_action']);
            $table->index(['date', 'metric_name']);
            
            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_reportings');
    }
};
