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
        Schema::table('posts', function (Blueprint $table) {
            // Thêm các fields mới cho blog module
            $table->text('excerpt')->nullable()->after('content');
            $table->string('featured_image')->nullable()->after('thumbnail');
            $table->enum('post_type', ['news', 'page', 'policy', 'blog'])->default('blog')->after('status');
            $table->timestamp('published_at')->nullable()->after('created_at');
            $table->string('author_name')->nullable()->after('category_id');
            $table->json('tags')->nullable()->after('author_name');
            $table->boolean('is_featured')->default(false)->after('tags');
            $table->integer('view_count')->default(0)->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'excerpt',
                'featured_image',
                'post_type',
                'published_at',
                'author_name',
                'tags',
                'is_featured',
                'view_count'
            ]);
        });
    }
};
