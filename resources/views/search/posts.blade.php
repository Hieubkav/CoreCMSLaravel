@extends('layouts.shop')

@section('title', 'Tìm kiếm: ' . $query)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            Kết quả tìm kiếm
        </h1>
        @if(!empty($query))
            <p class="text-gray-600 dark:text-gray-400">
                Tìm kiếm cho: "<span class="font-semibold text-red-600 dark:text-red-400">{{ $query }}</span>"
            </p>
        @endif
    </div>

    @if(!empty($query))
        <!-- Results Summary -->
        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
                @if(isset($posts) && $posts->count() > 0)
                    <span class="flex items-center">
                        <i class="fas fa-newspaper mr-2 text-blue-500"></i>
                        {{ $posts->total() }} bài viết
                    </span>
                @endif
                @if(isset($courses) && $courses->count() > 0)
                    <span class="flex items-center">
                        <i class="fas fa-graduation-cap mr-2 text-red-500"></i>
                        {{ $courses->total() }} khóa học
                    </span>
                @endif
            </div>
        </div>

        <!-- Bài viết Results -->
        @if(isset($posts) && $posts->count() > 0)
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <i class="fas fa-newspaper mr-3 text-blue-500"></i>
                    Bài viết
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                        <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                            <div class="aspect-video overflow-hidden bg-gray-100 dark:bg-gray-700">
                                @if($post->thumbnail || $post->images->count() > 0)
                                    <img src="{{ asset('storage/' . ($post->thumbnail ?? $post->images->first()->image_link)) }}"
                                         alt="{{ $post->title }}"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <!-- Fallback UI khi ảnh lỗi -->
                                    <div class="w-full h-full bg-gradient-to-br from-blue-100 to-blue-200 dark:from-gray-600 dark:to-gray-700 items-center justify-center" style="display: none;">
                                        <div class="text-center">
                                            <i class="fas fa-newspaper text-blue-500 dark:text-blue-400 text-4xl mb-2"></i>
                                            <div class="text-sm text-blue-600 dark:text-blue-400 font-medium">Bài viết</div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Fallback UI khi không có ảnh -->
                                    <div class="w-full h-full bg-gradient-to-br from-blue-100 to-blue-200 dark:from-gray-600 dark:to-gray-700 flex items-center justify-center">
                                        <div class="text-center">
                                            <i class="fas fa-newspaper text-blue-500 dark:text-blue-400 text-4xl mb-2"></i>
                                            <div class="text-sm text-blue-600 dark:text-blue-400 font-medium">Bài viết</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                    <a href="{{ route('posts.show', $post->slug) }}" class="hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                
                                @if($post->content)
                                    <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-3 mb-4">
                                        {{ Str::limit(strip_tags($post->content), 120) }}
                                    </p>
                                @endif
                                
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        <i class="fas fa-newspaper mr-1"></i>
                                        Bài viết
                                    </span>
                                    
                                    <a href="{{ route('posts.show', $post->slug) }}" 
                                       class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm font-medium">
                                        Xem chi tiết →
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                
                <!-- Pagination for posts -->
                @if($posts->hasPages())
                    <div class="mt-8">
                        {{ $posts->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        @endif

        <!-- Khóa học Results -->
        @if(isset($courses) && $courses->count() > 0)
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <i class="fas fa-graduation-cap mr-3 text-red-500"></i>
                    Khóa học
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($courses as $course)
                        <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                            <div class="aspect-video overflow-hidden bg-gray-100 dark:bg-gray-700">
                                @if($course->thumbnail || $course->images->count() > 0)
                                    <img src="{{ asset('storage/' . ($course->thumbnail ?? $course->images->first()->image_link)) }}"
                                         alt="{{ $course->title }}"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <!-- Fallback UI khi ảnh lỗi -->
                                    <div class="w-full h-full bg-gradient-to-br from-red-100 to-red-200 dark:from-gray-600 dark:to-gray-700 items-center justify-center" style="display: none;">
                                        <div class="text-center">
                                            <i class="fas fa-graduation-cap text-red-500 dark:text-red-400 text-4xl mb-2"></i>
                                            <div class="text-sm text-red-600 dark:text-red-400 font-medium">Khóa học</div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Fallback UI khi không có ảnh -->
                                    <div class="w-full h-full bg-gradient-to-br from-red-100 to-red-200 dark:from-gray-600 dark:to-gray-700 flex items-center justify-center">
                                        <div class="text-center">
                                            <i class="fas fa-graduation-cap text-red-500 dark:text-red-400 text-4xl mb-2"></i>
                                            <div class="text-sm text-red-600 dark:text-red-400 font-medium">Khóa học</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                    <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                        {{ $course->title }}
                                    </a>
                                </h3>
                                
                                @if($course->description)
                                    <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-3 mb-4">
                                        {{ Str::limit(strip_tags($course->description), 120) }}
                                    </p>
                                @endif
                                
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                        @if($course->level)
                                            <span class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ ucfirst($course->level) }}</span>
                                        @endif
                                        @if($course->duration_hours)
                                            <span>{{ $course->duration_hours }} giờ</span>
                                        @endif
                                    </div>
                                    
                                    @if($course->price)
                                        <span class="text-red-600 dark:text-red-400 font-semibold">
                                            {{ number_format($course->price, 0, ',', '.') }}đ
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        <i class="fas fa-graduation-cap mr-1"></i>
                                        Khóa học
                                    </span>
                                    
                                    <a href="{{ route('courses.show', $course->slug) }}" 
                                       class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm font-medium">
                                        Xem chi tiết →
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                
                <!-- Pagination for courses -->
                @if($courses->hasPages())
                    <div class="mt-8">
                        {{ $courses->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        @endif

        <!-- No Results -->
        @if((!isset($posts) || $posts->count() == 0) && (!isset($courses) || $courses->count() == 0))
            <div class="text-center py-12">
                <div class="text-gray-400 dark:text-gray-500 mb-4">
                    <i class="fas fa-search text-6xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                    Không tìm thấy kết quả
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Không có bài viết hoặc khóa học nào phù hợp với từ khóa "<span class="font-semibold">{{ $query }}</span>"
                </p>
                <a href="{{ route('storeFront') }}" 
                   class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Về trang chủ
                </a>
            </div>
        @endif
    @else
        <!-- Empty search -->
        <div class="text-center py-12">
            <div class="text-gray-400 dark:text-gray-500 mb-4">
                <i class="fas fa-search text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                Nhập từ khóa để tìm kiếm
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
                Tìm kiếm bài viết và khóa học theo từ khóa của bạn
            </p>
        </div>
    @endif
</div>
@endsection
