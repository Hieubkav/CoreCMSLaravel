<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visitor;
use App\Models\Course;
use Carbon\Carbon;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách khóa học
        $courses = Course::where('status', 'active')->take(5)->get();

        if ($courses->isEmpty()) {
            $this->command->info('Không có khóa học nào để tạo dữ liệu visitor');
            return;
        }

        // Tạo dữ liệu visitor cho 7 ngày gần nhất
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            // Số lượt truy cập ngẫu nhiên cho mỗi ngày
            $dailyVisits = rand(20, 100);

            for ($j = 0; $j < $dailyVisits; $j++) {
                // IP ngẫu nhiên
                $ip = rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255);

                // User agent ngẫu nhiên
                $userAgents = [
                    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                    'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36',
                    'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X)',
                    'Mozilla/5.0 (Android 11; Mobile; rv:68.0) Gecko/68.0',
                ];

                // URL ngẫu nhiên
                $urls = [
                    'http://localhost:8000',
                    'http://localhost:8000/courses',
                    'http://localhost:8000/posts',
                    'http://localhost:8000/about',
                ];

                // Thêm URL khóa học
                if (rand(1, 3) === 1 && $courses->isNotEmpty()) {
                    $course = $courses->random();
                    $urls[] = "http://localhost:8000/courses/{$course->slug}";
                }

                $courseId = null;
                $url = $urls[array_rand($urls)];

                // Nếu là trang khóa học, set course_id
                if (strpos($url, '/courses/') !== false && $courses->isNotEmpty()) {
                    $courseId = $courses->random()->id;
                }

                // Thời gian ngẫu nhiên trong ngày
                $visitedAt = $date->copy()->addHours(rand(0, 23))->addMinutes(rand(0, 59));

                Visitor::create([
                    'ip_address' => $ip,
                    'user_agent' => $userAgents[array_rand($userAgents)],
                    'url' => $url,
                    'course_id' => $courseId,
                    'session_id' => 'session_' . uniqid(),
                    'visited_at' => $visitedAt,
                    'created_at' => $visitedAt,
                    'updated_at' => $visitedAt,
                ]);
            }
        }

        $this->command->info('Đã tạo dữ liệu visitor mẫu thành công!');
    }
}
