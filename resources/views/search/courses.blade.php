@extends('layouts.shop')

@section('title', 'Tìm kiếm khóa học: ' . $query)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center">
            <i class="fas fa-graduation-cap mr-3 text-red-500"></i>
            Tìm kiếm khóa học
        </h1>
        @if(!empty($query))
            <p class="text-gray-600 dark:text-gray-400">
                Tìm kiếm cho: "<span class="font-semibold text-red-600 dark:text-red-400">{{ $query }}</span>"
            </p>
        @endif
    </div>

    @if(!empty($query))
        <!-- Results Summary -->
        @if(isset($courses) && $courses->count() > 0)
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                <div class="flex items-center text-sm text-red-600 dark:text-red-400">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    Tìm thấy {{ $courses->total() }} khóa học
                </div>
            </div>
        @endif

        <!-- Courses Results -->
        @if(isset($courses) && $courses->count() > 0)
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
            
            <!-- Pagination -->
            @if($courses->hasPages())
                <div class="mt-8">
                    {{ $courses->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <!-- No Results -->
            <div class="text-center py-12">
                <div class="text-gray-400 dark:text-gray-500 mb-4">
                    <i class="fas fa-graduation-cap text-6xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                    Không tìm thấy khóa học
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Không có khóa học nào phù hợp với từ khóa "<span class="font-semibold">{{ $query }}</span>"
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('courses.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        Xem tất cả khóa học
                    </a>
                    <a href="{{ route('storeFront') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        Về trang chủ
                    </a>
                </div>
            </div>
        @endif
    @else
        <!-- Empty search -->
        <div class="text-center py-12">
            <div class="text-gray-400 dark:text-gray-500 mb-4">
                <i class="fas fa-search text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                Nhập từ khóa để tìm kiếm khóa học
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Tìm kiếm khóa học theo từ khóa của bạn
            </p>
            <a href="{{ route('courses.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-graduation-cap mr-2"></i>
                Xem tất cả khóa học
            </a>
        </div>
    @endif
</div>
@endsection
