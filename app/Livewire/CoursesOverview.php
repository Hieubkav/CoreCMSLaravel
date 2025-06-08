<?php

namespace App\Livewire;

use App\Models\CatCourse;
use App\Models\Course;
use App\Models\Student;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CoursesOverview extends Component
{
    public $isLoaded = false;
    public $courseCategoriesData;
    public $realStats = [];

    public function mount()
    {
        $this->loadCoursesData();
    }

    public function loadCoursesData()
    {
        try {
            // Query đơn giản thay thế service phức tạp
            $this->courseCategoriesData = Cache::remember('courses_overview_simple', 300, function () {
                return CatCourse::where('status', 'active')
                    ->whereHas('courses', function($query) {
                        $query->where('status', 'active');
                    })
                    ->with(['courses' => function($query) {
                        $query->where('status', 'active')
                              ->with(['instructor:id,name'])
                              ->select([
                                  'id', 'title', 'slug', 'thumbnail', 'description',
                                  'cat_course_id', 'instructor_id', 'price', 'level',
                                  'duration_hours', 'start_date', 'created_at', 'order'
                              ])
                              ->orderBy('created_at', 'desc')
                              ->take(1); // Chỉ lấy 1 khóa học mới nhất
                    }])
                    ->select(['id', 'name', 'slug', 'description', 'order'])
                    ->orderBy('order')
                    ->get();
            });

            // Stats đơn giản
            $this->realStats = Cache::remember('course_stats_simple', 300, function () {
                return [
                    'students' => Student::where('status', 'active')->count() ?: 28,
                    'courses' => Course::where('status', 'active')->count() ?: 6,
                    'satisfaction_rate' => 32,
                    'experience_years' => 3
                ];
            });

            $this->isLoaded = true;
        } catch (\Exception $e) {
            Log::error('CoursesOverview Error: ' . $e->getMessage());
            $this->courseCategoriesData = collect();
            $this->realStats = [
                'students' => 28,
                'courses' => 6,
                'satisfaction_rate' => 32,
                'experience_years' => 3
            ];
            $this->isLoaded = true;
        }
    }

    public function refreshData()
    {
        $this->isLoaded = false;
        Cache::forget('courses_overview_simple');
        Cache::forget('course_stats_simple');
        $this->loadCoursesData();
        $this->dispatch('courses-refreshed');
    }

    public function render()
    {
        return view('livewire.courses-overview');
    }
}
