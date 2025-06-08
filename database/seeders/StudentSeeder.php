<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("👨‍🎓 Creating sample students...");

        $students = [
            [
                "name" => "Sample Student",
                "email" => "student@example.com",
                "phone" => "0123456789",
                "status" => "active"
            ]
        ];

        foreach ($students as $studentData) {
            Student::create($studentData);
        }

        $this->command->info("✅ Created " . count($students) . " students");
    }
}
