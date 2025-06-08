<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Course;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SearchBar extends Component
{

    public $query = '';
    public $results = [];
    public $showResults = false;
    public $isMobile = false;

    public function mount($isMobile = false)
    {
        $this->isMobile = $isMobile;
    }

    public function updatedQuery()
    {
        if (strlen($this->query) >= 1) {
            $this->showResults = true;
            $this->search();
        } else {
            $this->results = [];
            $this->showResults = false;
        }

        // Force re-render
        $this->dispatch('search-updated');
    }

    public function search()
    {
        $cacheKey = 'search_' . md5($this->query);

        $this->results = Cache::remember($cacheKey, 300, function () {
            // Tìm kiếm bài viết
            $posts = Post::where('status', 'active')
                ->where('title', 'like', '%' . $this->query . '%')
                ->with(['images' => function($query) {
                    $query->where('status', 'active')->orderBy('order');
                }])
                ->take(5)
                ->get()
                ->map(function ($post) {
                    // Ưu tiên thumbnail, nếu không có thì lấy ảnh đầu tiên
                    $image = $post->thumbnail ?? $post->images->first()?->image_link ?? null;

                    return [
                        'type' => 'post',
                        'id' => $post->id,
                        'title' => $post->title,
                        'url' => route('posts.show', $post->slug),
                        'image' => $image,
                        'excerpt' => Str::limit(strip_tags($post->content), 80)
                    ];
                });

            // Tìm kiếm khóa học
            $courses = Course::where('status', 'active')
                ->where('title', 'like', '%' . $this->query . '%')
                ->with(['images' => function($query) {
                    $query->where('status', 'active')->orderBy('order');
                }])
                ->take(5)
                ->get()
                ->map(function ($course) {
                    // Ưu tiên thumbnail, nếu không có thì lấy ảnh đầu tiên
                    $image = $course->thumbnail ?? $course->images->first()?->image_link ?? null;

                    return [
                        'type' => 'course',
                        'id' => $course->id,
                        'title' => $course->title,
                        'url' => route('courses.show', $course->slug),
                        'image' => $image,
                        'price' => $course->price ? number_format($course->price, 0, ',', '.') . 'đ' : null,
                        'level' => $course->level,
                        'duration' => $course->duration_hours ? $course->duration_hours . ' giờ' : null
                    ];
                });

            return $posts->concat($courses)->take(8)->toArray();
        });
    }

    public function hideResults()
    {
        // Delay để cho phép click vào kết quả
        $this->dispatch('hide-results-delayed');
    }

    public function performSearch()
    {
        if (!empty($this->query)) {
            return redirect()->route('search.posts', ['q' => $this->query]);
        }
    }

    public function render()
    {
        return view('livewire.public.search-bar');
    }
}