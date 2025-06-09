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
        Schema::create('advanced_searches', function (Blueprint $table) {
            $table->id();
            
            // Search query and user info
            $table->text('query')->comment('Từ khóa tìm kiếm');
            $table->unsignedBigInteger('user_id')->nullable()->comment('ID người dùng');
            $table->string('ip_address', 45)->comment('Địa chỉ IP');
            $table->text('user_agent')->nullable()->comment('User agent');
            
            // Search parameters
            $table->integer('results_count')->default(0)->comment('Số kết quả');
            $table->enum('search_type', ['general', 'posts', 'products', 'courses', 'users', 'categories', 'tags', 'files', 'advanced'])->default('general')->comment('Loại tìm kiếm');
            $table->json('filters')->nullable()->comment('Bộ lọc tìm kiếm');
            $table->enum('sort_by', ['relevance', 'date', 'title', 'views', 'popularity', 'rating', 'price', 'alphabetical'])->default('relevance')->comment('Sắp xếp theo');
            $table->enum('sort_direction', ['asc', 'desc'])->default('desc')->comment('Hướng sắp xếp');
            $table->integer('page')->default(1)->comment('Trang hiện tại');
            $table->integer('per_page')->default(20)->comment('Số item mỗi trang');
            
            // Performance and results
            $table->float('execution_time', 8, 4)->nullable()->comment('Thời gian thực thi (giây)');
            $table->boolean('has_results')->default(false)->comment('Có kết quả');
            
            // Click tracking
            $table->unsignedBigInteger('clicked_result_id')->nullable()->comment('ID kết quả được click');
            $table->string('clicked_result_type')->nullable()->comment('Loại kết quả được click');
            
            // Session and context
            $table->string('session_id')->nullable()->comment('Session ID');
            $table->text('referer')->nullable()->comment('Trang giới thiệu');
            $table->string('language', 5)->default('vi')->comment('Ngôn ngữ');
            $table->enum('device_type', ['desktop', 'mobile', 'tablet', 'bot'])->default('desktop')->comment('Loại thiết bị');
            $table->json('location')->nullable()->comment('Thông tin vị trí');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index('query');
            $table->index('user_id');
            $table->index('ip_address');
            $table->index('search_type');
            $table->index('has_results');
            $table->index('device_type');
            $table->index('language');
            $table->index('created_at');
            $table->index(['query', 'has_results']);
            $table->index(['search_type', 'created_at']);
            $table->index(['user_id', 'created_at']);
            
            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advanced_searches');
    }
};
