{{--
    Course Categories Component - Giao diện danh mục khóa học
    - Hiển thị tất cả danh mục khóa học theo order
    - Layout grid responsive giống như hình mẫu
    - Mỗi danh mục hiển thị: hình ảnh, tên, số lượng khóa học
    - Click vào sẽ dẫn đến trang danh mục đó
--}}

@php
    // Lấy tất cả danh mục khóa học từ ViewServiceProvider
    $courseCategoriesGrid = $courseCategoriesGrid ?? collect();
@endphp

@if($courseCategoriesGrid->isNotEmpty())
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Danh mục khóa học
        </h2>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Khám phá các danh mục khóa học đa dạng được thiết kế để phát triển kỹ năng và kiến thức của bạn
        </p>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 md:gap-6">
        @foreach($courseCategoriesGrid as $category)
            <div class="group">
                <a href="{{ route('courses.cat-category', $category->slug) }}"
                   class="block bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                    
                    <!-- Category Image -->
                    <div class="relative aspect-square overflow-hidden">
                        @if($category->image)
                            <img
                                data-src="{{ asset('storage/' . $category->image) }}"
                                alt="{{ $category->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 lazy-loading"
                                loading="lazy"
                                onerror="handleImageError(this)"
                                style="opacity: 0; transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease;">
                        @else
                            <!-- Placeholder với gradient màu thống nhất -->
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-red-50 to-red-100">
                                <i class="fas fa-graduation-cap text-3xl text-red-300"></i>
                                <!-- Backup SVG nếu cần
                                <svg class="w-12 h-12 text-red-300 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($category->icon == 'excel')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    @elseif($category->icon == 'calculator')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    @elseif($category->icon == 'users')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    @elseif($category->icon == 'computer')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    @elseif($category->icon == 'chart')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    @elseif($category->icon == 'heart')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    @elseif($category->icon == 'code')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    @elseif($category->icon == 'megaphone')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    @endif
                                </svg>
                                -->
                            </div>
                        @endif
                        
                        <!-- Course Count Badge -->
                        @if($category->courses_count > 0)
                            <div class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                                {{ $category->courses_count }}
                            </div>
                        @endif
                    </div>

                    <!-- Category Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 text-center text-sm md:text-base group-hover:text-red-600 transition-colors duration-200 line-clamp-2">
                            {{ $category->name }}
                        </h3>
                        
                        @if($category->description)
                            <p class="text-xs text-gray-500 text-center mt-1 line-clamp-2">
                                {{ Str::limit($category->description, 50) }}
                            </p>
                        @endif
                        
                        <!-- Course count text -->
                        <div class="text-center mt-2">
                            <span class="text-xs text-gray-400">
                                {{ $category->courses_count }} khóa học
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <!-- View All Courses Button -->
    <div class="text-center mt-12">
        <a 
            href="{{ route('courses.index') }}" 
            class="inline-flex items-center bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-3 px-8 rounded-lg transition-all duration-200 transform hover:scale-105"
        >
            Xem tất cả khóa học
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </a>
    </div>
</div>
@endif

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Responsive adjustments */
    @media (max-width: 640px) {
        .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    
    @media (min-width: 641px) and (max-width: 768px) {
        .md\:grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    
    @media (min-width: 769px) and (max-width: 1024px) {
        .lg\:grid-cols-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }
    
    @media (min-width: 1025px) {
        .xl\:grid-cols-6 {
            grid-template-columns: repeat(6, minmax(0, 1fr));
        }
    }
    
    /* Hover effects */
    .group:hover .group-hover\:scale-105 {
        transform: scale(1.05);
    }
    
    .group:hover .group-hover\:text-red-600 {
        color: #dc2626;
    }
</style>

{{-- Global handler sẽ tự động xử lý .category-image --}}
