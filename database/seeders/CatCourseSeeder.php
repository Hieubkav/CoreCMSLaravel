<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CatCourse;

class CatCourseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("📂 Creating course categories...");

        $categories = [
            [
                "name" => "Web Development",
                "slug" => "web-development",
                "description" => "Learn web development technologies",
                "thumbnail" => "categories/web-development.webp",
                "status" => "active",
                "order" => 1
            ],
            [
                "name" => "Backend Development", 
                "slug" => "backend-development",
                "description" => "Server-side development courses",
                "thumbnail" => "categories/backend-development.webp",
                "status" => "active",
                "order" => 2
            ]
        ];

        foreach ($categories as $categoryData) {
            CatCourse::updateOrCreate(
                ["slug" => $categoryData["slug"]],
                $categoryData
            );
        }

        $this->command->info("✅ Created " . count($categories) . " categories");
    }
}
