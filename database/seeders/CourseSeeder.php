<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CatCourse;
use App\Models\Instructor;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("📚 Creating sample courses...");

        // Get categories and instructors
        $categories = CatCourse::pluck("id", "slug")->toArray();
        $instructors = Instructor::pluck("id", "slug")->toArray();

        // Sample courses data
        $courses = [
            [
                "title" => "Introduction to Web Development",
                "description" => "<p>Learn the fundamentals of web development from scratch.</p>",
                "slug" => "introduction-to-web-development",
                "thumbnail" => "courses/web-development-intro.webp",
                "price" => 299000,
                "compare_price" => 399000,
                "duration_hours" => 40,
                "level" => "beginner",
                "status" => "active",
                "is_featured" => true,
                "order" => 1,
                "max_students" => 50,
                "requirements" => json_encode(["Basic computer skills", "Internet connection"]),
                "what_you_learn" => json_encode(["HTML & CSS", "JavaScript basics", "Responsive design"]),
                "cat_course_id" => $categories["web-development"] ?? null,
                "instructor_id" => $instructors["john-doe"] ?? null,
            ],
            [
                "title" => "Advanced Laravel Development",
                "description" => "<p>Master advanced Laravel concepts and best practices.</p>",
                "slug" => "advanced-laravel-development",
                "thumbnail" => "courses/laravel-advanced.webp",
                "price" => 599000,
                "compare_price" => 799000,
                "duration_hours" => 60,
                "level" => "advanced",
                "status" => "active",
                "is_featured" => true,
                "order" => 2,
                "max_students" => 30,
                "requirements" => json_encode(["PHP knowledge", "Laravel basics"]),
                "what_you_learn" => json_encode(["Advanced Eloquent", "API development", "Testing"]),
                "cat_course_id" => $categories["backend-development"] ?? null,
                "instructor_id" => $instructors["jane-smith"] ?? null,
            ]
        ];

        foreach ($courses as $courseData) {
            Course::updateOrCreate(
                ["slug" => $courseData["slug"]],
                $courseData
            );
        }

        $this->command->info("✅ Created " . count($courses) . " sample courses");
    }
}
