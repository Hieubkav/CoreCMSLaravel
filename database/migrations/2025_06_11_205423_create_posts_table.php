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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->string('thumbnail')->nullable();
            $table->enum('post_type', ['tin_tuc', 'dich_vu', 'trang_don'])->default('tin_tuc');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('order')->default(0);
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('og_image')->nullable();
            $table->boolean('is_hot')->default(false);
            $table->integer('view_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('post_category_id')->nullable()->constrained('post_categories')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            
            $table->index(['status', 'published_at']);
            $table->index(['post_type', 'status']);
            $table->index(['is_hot', 'status']);
            $table->index('slug');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
