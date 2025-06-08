<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instructor;

class InstructorSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("👨‍🏫 Creating sample instructors...");

        $instructors = [
            [
                "name" => "John Doe",
                "slug" => "john-doe",
                "bio" => "Experienced web developer with 10+ years in the industry.",
                "avatar" => "instructors/john-doe.webp",
                "email" => "john@example.com",
                "phone" => "0123456789",
                "specialization" => "Web Development",
                "experience_years" => 10,
                "status" => "active",
                "order" => 1
            ],
            [
                "name" => "Jane Smith",
                "slug" => "jane-smith", 
                "bio" => "Laravel expert and full-stack developer.",
                "avatar" => "instructors/jane-smith.webp",
                "email" => "jane@example.com",
                "phone" => "0123456790",
                "specialization" => "Laravel Development",
                "experience_years" => 8,
                "status" => "active",
                "order" => 2
            ]
        ];

        foreach ($instructors as $instructorData) {
            Instructor::updateOrCreate(
                ["slug" => $instructorData["slug"]],
                $instructorData
            );
        }

        $this->command->info("✅ Created " . count($instructors) . " instructors");
    }
}
