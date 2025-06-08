<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\CatCourse;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;

class CourseList extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $level = '';
    public $sort = 'order';
    public $perPage = 12;

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'level' => ['except' => ''],
        'sort' => ['except' => 'order'],
    ];

    public function mount()
    {
        // Initialize from request if available
        $this->search = request('search', '');
        $this->category = request('category', '');
        $this->level = request('level', '');
        $this->sort = request('sort', 'order');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedLevel()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'category', 'level']);
        $this->resetPage();
    }

    public function getCourses()
    {
        $query = Course::with(['courseCategory', 'instructor', 'images' => function($q) {
            $q->where('status', 'active')->orderBy('is_main', 'desc')->orderBy('order');
        }])
        ->where('status', 'active');

        // Apply filters
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%")
                  ->orWhereHas('instructor', function($subQ) {
                      $subQ->where('name', 'like', "%{$this->search}%");
                  });
            });
        }

        if (!empty($this->category)) {
            $query->whereHas('courseCategory', function($q) {
                $q->where('slug', $this->category);
            });
        }

        if (!empty($this->level)) {
            $query->where('level', $this->level);
        }

        // Apply sorting
        switch ($this->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->withCount('students')->orderBy('students_count', 'desc');
                break;
            default:
                $query->orderBy('is_featured', 'desc')->orderBy('order', 'asc');
        }

        return $query->paginate($this->perPage);
    }

    public function getCategories()
    {
        return Cache::remember('course_categories', 3600, function() {
            return CatCourse::where('status', 'active')
                ->whereHas('courses', function($q) {
                    $q->where('status', 'active');
                })
                ->withCount(['courses' => function($q) {
                    $q->where('status', 'active');
                }])
                ->orderBy('order')
                ->get();
        });
    }

    public function getStats()
    {
        return Cache::remember('course_stats', 1800, function() {
            return [
                'total' => Course::where('status', 'active')->count(),
                'levels' => Course::where('status', 'active')
                    ->selectRaw('level, count(*) as count')
                    ->groupBy('level')
                    ->pluck('count', 'level'),
            ];
        });
    }

    public function render()
    {
        $courses = $this->getCourses();
        $categories = $this->getCategories();
        $stats = $this->getStats();

        return view('livewire.course-list', compact('courses', 'categories', 'stats'));
    }
}
