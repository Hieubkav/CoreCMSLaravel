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
        Schema::create('automation_workflows', function (Blueprint $table) {
            $table->id();
            
            // Basic information
            $table->string('name')->comment('Tên workflow');
            $table->text('description')->nullable()->comment('Mô tả');
            
            // Trigger configuration
            $table->enum('trigger_type', ['manual', 'scheduled', 'event', 'webhook', 'database', 'file', 'email', 'api', 'user_action', 'system'])->default('manual')->comment('Loại trigger');
            $table->json('trigger_conditions')->nullable()->comment('Điều kiện trigger');
            
            // Actions and schedule
            $table->json('actions')->comment('Danh sách hành động');
            $table->json('schedule')->nullable()->comment('Lịch trình thực thi');
            
            // Status and configuration
            $table->boolean('is_active')->default(true)->comment('Đang hoạt động');
            $table->boolean('is_recurring')->default(false)->comment('Lặp lại');
            $table->tinyInteger('priority')->default(5)->comment('Độ ưu tiên (1-10)');
            $table->integer('max_executions')->nullable()->comment('Số lần thực thi tối đa');
            
            // Execution statistics
            $table->integer('execution_count')->default(0)->comment('Số lần đã thực thi');
            $table->timestamp('last_executed_at')->nullable()->comment('Lần thực thi cuối');
            $table->timestamp('next_execution_at')->nullable()->comment('Lần thực thi tiếp theo');
            $table->integer('success_count')->default(0)->comment('Số lần thành công');
            $table->integer('failure_count')->default(0)->comment('Số lần thất bại');
            $table->decimal('average_execution_time', 8, 4)->default(0)->comment('Thời gian thực thi trung bình (giây)');
            
            // Creator and metadata
            $table->unsignedBigInteger('created_by')->nullable()->comment('Người tạo');
            $table->json('tags')->nullable()->comment('Thẻ phân loại');
            $table->json('metadata')->nullable()->comment('Metadata bổ sung');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index('trigger_type');
            $table->index('is_active');
            $table->index('is_recurring');
            $table->index('priority');
            $table->index('next_execution_at');
            $table->index('created_by');
            $table->index(['is_active', 'is_recurring']);
            $table->index(['is_active', 'next_execution_at']);
            $table->index(['trigger_type', 'is_active']);
            
            // Foreign key constraint
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('automation_workflows');
    }
};
