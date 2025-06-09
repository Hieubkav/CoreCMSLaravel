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
        Schema::create('multi_languages', function (Blueprint $table) {
            $table->id();
            
            // Translation key and value
            $table->string('key')->comment('Khóa dịch thuật');
            $table->string('language_code', 5)->comment('Mã ngôn ngữ (vi, en, zh, etc.)');
            $table->text('value')->comment('Giá trị dịch thuật');
            
            // Organization
            $table->string('group')->default('general')->comment('Nhóm dịch thuật');
            $table->text('description')->nullable()->comment('Mô tả');
            
            // Status and ordering
            $table->boolean('is_active')->default(true)->comment('Đang sử dụng');
            $table->boolean('is_default')->default(false)->comment('Ngôn ngữ mặc định');
            $table->integer('order')->default(0)->comment('Thứ tự sắp xếp');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index(['key', 'language_code']);
            $table->index(['language_code', 'group']);
            $table->index(['language_code', 'is_active']);
            $table->index('group');
            $table->index('is_active');
            $table->index('is_default');
            
            // Unique constraint for key + language combination
            $table->unique(['key', 'language_code'], 'unique_key_language');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multi_languages');
    }
};
