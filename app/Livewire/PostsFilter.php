<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class PostsFilter extends Component
{
    public $search = '';
    public $category_id = '';
    public $sort = 'newest';
    public $perPage = 12;
    public $loadedPosts = [];
    public $hasMorePosts = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'category_id' => ['except' => ''],
        'sort' => ['except' => 'newest'],
    ];

    public function mount()
    {
        $this->search = request('search', '');
        $this->category_id = request('category_id', '');
        $this->sort = request('sort', 'newest');
        $this->loadPosts();
    }

    public function updatedSearch()
    {
        $this->resetPosts();
    }

    public function updatedCategoryId()
    {
        $this->resetPosts();
    }

    public function updatedSort()
    {
        $this->resetPosts();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->category_id = '';
        $this->sort = 'newest';
        $this->resetPosts();
    }

    public function loadMore()
    {
        $this->perPage += 12;
        $this->loadPosts();
    }

    private function resetPosts()
    {
        $this->perPage = 12;
        $this->loadedPosts = [];
        $this->hasMorePosts = true;
        $this->loadPosts();
    }

    private function loadPosts()
    {
        $query = $this->getQuery();

        $posts = $query->take($this->perPage)->get();
        $this->loadedPosts = $posts;

        // Check if there are more posts
        $totalPosts = $this->getQuery()->count();
        $this->hasMorePosts = $posts->count() < $totalPosts;
    }

    private function getQuery()
    {
        $query = Post::where('status', 'active')
            ->with(['category:id,name,slug', 'images' => function($query) {
                $query->where('status', 'active')->orderBy('order')->limit(1);
            }])
            ->select(['id', 'title', 'content', 'slug', 'thumbnail', 'category_id', 'created_at', 'order']);

        // Lọc theo danh mục
        if ($this->category_id) {
            $query->where('category_id', $this->category_id);
        }

        // Tìm kiếm theo từ khóa với tối ưu performance
        if ($this->search) {
            $searchTerm = trim($this->search);
            if (strlen($searchTerm) >= 2) { // Chỉ tìm kiếm khi có ít nhất 2 ký tự
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('content', 'like', "%{$searchTerm}%");
                });
            }
        }

        // Sắp xếp
        switch ($this->sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('order')->orderBy('created_at', 'desc');
                break;
        }

        return $query;
    }

    public function render()
    {
        $totalPosts = $this->getQuery()->count();

        return view('livewire.posts-filter', [
            'posts' => collect($this->loadedPosts),
            'totalPosts' => $totalPosts
        ]);
    }
}
