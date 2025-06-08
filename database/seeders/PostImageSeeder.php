<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PostImage;
use App\Models\Post;

class PostImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🖼️ Tạo dữ liệu hình ảnh bài viết...');

        $posts = Post::all();
        $imageCount = 0;

        foreach ($posts as $post) {
            $numImages = rand(2, 5); // Mỗi bài viết có 2-5 ảnh
            
            for ($i = 1; $i <= $numImages; $i++) {
                PostImage::create([
                    'post_id' => $post->id,
                    'image_link' => "posts/{$post->slug}/image-{$i}.webp",
                    'alt_text' => "Hình ảnh {$i} - {$post->title}",
                    'order' => $i,
                    'status' => 'active',
                ]);
                $imageCount++;
            }
        }

        $this->command->info("✅ Đã tạo {$imageCount} hình ảnh cho " . $posts->count() . " bài viết");
    }
}
