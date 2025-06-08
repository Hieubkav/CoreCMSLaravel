<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Post;

class SearchController extends Controller
{
    /**
     * Tìm kiếm khóa học
     */
    public function courses(Request $request)
    {
        $query = $request->get('q', '');
        $courses = collect([]);

        if (!empty($query)) {
            $courses = Course::where('status', 'active')
                ->where('title', 'like', '%' . $query . '%')
                ->with(['images' => function($q) {
                    $q->where('status', 'active')->orderBy('order');
                }])
                ->orderBy('order')
                ->paginate(12);
        }

        if ($request->ajax()) {
            return response()->json([
                'courses' => $courses,
                'total' => $courses->total() ?? 0
            ]);
        }

        return view('search.courses', [
            'query' => $query,
            'courses' => $courses
        ]);
    }
    
    /**
     * Tìm kiếm bài viết và khóa học
     */
    public function posts(Request $request)
    {
        $query = $request->get('q', '');
        $posts = collect([]);
        $courses = collect([]);

        if (!empty($query)) {
            // Tìm kiếm bài viết
            $posts = Post::where('status', 'active')
                ->where('title', 'like', '%' . $query . '%')
                ->with(['images' => function($q) {
                    $q->where('status', 'active')->orderBy('order');
                }])
                ->orderBy('order')
                ->paginate(8, ['*'], 'posts_page');

            // Tìm kiếm khóa học
            $courses = Course::where('status', 'active')
                ->where('title', 'like', '%' . $query . '%')
                ->with(['images' => function($q) {
                    $q->where('status', 'active')->orderBy('order');
                }])
                ->orderBy('order')
                ->paginate(8, ['*'], 'courses_page');
        }

        if ($request->ajax()) {
            return response()->json([
                'posts' => $posts,
                'courses' => $courses,
                'total' => ($posts->total() ?? 0) + ($courses->total() ?? 0)
            ]);
        }

        return view('search.posts', [
            'query' => $query,
            'posts' => $posts,
            'courses' => $courses
        ]);
    }
}
