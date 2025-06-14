<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;

class BlogIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $type = '';
    public $sort = 'latest';
    public $perPage = 9;

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'type' => ['except' => ''],
        'sort' => ['except' => 'latest'],
    ];

    public function mount()
    {
        $this->search = request('search', '');
        $this->category = request('category', '');
        $this->type = request('type', '');
        $this->sort = request('sort', 'latest');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedType()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'category', 'type', 'sort']);
        $this->resetPage();
    }

    public function getPostsProperty()
    {
        $query = Post::with(['category', 'author'])
            ->where('status', 'active')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        // Filter by category
        if ($this->category) {
            $categoryModel = PostCategory::where('slug', $this->category)->first();
            if ($categoryModel) {
                $query->where('post_category_id', $categoryModel->id);
            }
        }

        // Filter by post type
        if ($this->type) {
            $query->where('post_type', $this->type);
        }

        // Search
        if ($this->search) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Sort
        switch ($this->sort) {
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            default: // latest
                $query->orderBy('published_at', 'desc');
                break;
        }

        return $query->paginate($this->perPage);
    }

    public function getCategoriesProperty()
    {
        return Cache::remember('post_categories_active', 3600, function() {
            return PostCategory::where('status', 'active')
                ->withCount('posts')
                ->orderBy('order', 'asc')
                ->get();
        });
    }

    public function getGlobalSettingsProperty()
    {
        return Cache::remember('global_settings', 3600, function () {
            try {
                return Setting::first();
            } catch (\Exception $e) {
                return null;
            }
        });
    }

    public function render()
    {
        return view('livewire.blog-index', [
            'posts' => $this->posts,
            'categories' => $this->categories,
            'globalSettings' => $this->globalSettings,
        ]);
    }
}
