<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("📝 Creating sample posts...");

        $posts = [
            [
                "title" => "Getting Started with Laravel",
                "slug" => "getting-started-with-laravel",
                "content" => "<p>Laravel is a powerful PHP framework for building modern web applications. This guide will help you get started with the basics.</p>",
                "thumbnail" => "posts/laravel-getting-started.webp",
                "status" => "active",
                "order" => 1,
                "seo_title" => "Getting Started with Laravel",
                "seo_description" => "Laravel is a powerful PHP framework...",
                "og_image_link" => url('storage/posts/laravel-getting-started.webp')
            ]
        ];

        foreach ($posts as $postData) {
            Post::updateOrCreate(
                ["slug" => $postData["slug"]],
                $postData
            );
        }

        $this->command->info("✅ Created " . count($posts) . " posts");
    }
}
