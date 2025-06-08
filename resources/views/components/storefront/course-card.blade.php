@php
    // Xử lý dữ liệu từ ViewServiceProvider hoặc fallback query
    $latestCourse = null;
    if (isset($category->latest_course)) {
        $latestCourse = $category->latest_course;
    } elseif ($category->relationLoaded('courses') && $category->courses->isNotEmpty()) {
        $latestCourse = $category->courses->first();
    }
@endphp

@if($latestCourse)
<!-- Course Card - Minimalist Design with Subtle Shadow -->
<article class="group relative bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-gray-100 hover:border-red-100"
         role="article"
         aria-labelledby="course-{{ $latestCourse->id }}-title">

    <!-- Category Header - Clean & Modern -->
    <header class="relative bg-gradient-to-r from-red-100 to-white p-8 shadow-lg">
        <div class="space-y-2">
            <h3 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $category->name }}</h3>
            @if($category->description)
                <p class="text-gray-600 text-base leading-relaxed">{{ Str::limit($category->description, 60) }}</p>
            @endif
        </div>
    </header>

    <!-- Course Image - Modern & Clean -->
    <div class="h-56 bg-gray-50 relative overflow-hidden">
        @if($latestCourse->thumbnail)
            <img src="{{ asset('storage/' . $latestCourse->thumbnail) }}"
                 alt="{{ $latestCourse->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                 loading="lazy"
                 onerror="handleImageError(this)">
        @else
            <div class="w-full h-full bg-gradient-to-br from-red-50 via-white to-red-50 flex items-center justify-center">
                <div class="text-center space-y-3">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-graduation-cap text-2xl text-red-500"></i>
                    </div>
                    <p class="text-sm text-gray-600 font-medium px-4">{{ Str::limit($latestCourse->title, 30) }}</p>
                </div>
            </div>
        @endif

        <!-- Badge "Mới nhất" - Elegant Design -->
        <div class="absolute top-4 right-4 bg-red-600 text-white text-xs font-bold px-4 py-2 rounded-full">
            MỚI NHẤT
        </div>
    </div>

    <!-- Course Content - Modern & Spacious -->
    <div class="p-8 space-y-6 shadow-lg">
        <!-- Course Title -->
        <h4 id="course-{{ $latestCourse->id }}-title"
           class="text-xl font-bold text-gray-900 line-clamp-2 leading-tight group-hover:text-red-600 transition-colors duration-300">
            {{ $latestCourse->title }}
        </h4>

        <!-- Course Description -->
        <p class="text-gray-600 text-base leading-relaxed line-clamp-3">
            {{ $latestCourse->seo_description ?? Str::limit(strip_tags($latestCourse->description ?? ''), 120) }}
        </p>

        <!-- Action Buttons - Clean Design -->
        <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <a href="{{ route('courses.show', $latestCourse->slug) }}"
               class="flex-1 inline-flex items-center justify-center px-6 py-4 bg-gray-50 hover:bg-gray-100 text-gray-700 hover:text-gray-900 text-base font-medium rounded-2xl transition-all duration-300 hover:shadow-md"
               aria-label="Xem chi tiết khóa học {{ $latestCourse->title }}">
                <span>Xem chi tiết</span>
            </a>

            @if($latestCourse->gg_form)
                <a href="{{ $latestCourse->gg_form }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="flex-1 inline-flex items-center justify-center px-6 py-4 bg-red-600 hover:bg-red-700 text-white text-base font-semibold rounded-2xl transition-all duration-300 hover:shadow-lg hover:shadow-red-200"
                   aria-label="Đăng ký khóa học {{ $latestCourse->title }}">
                    <span>Đăng ký ngay</span>
                </a>
            @endif
        </div>

        <!-- Category Link - Elegant -->
        <div class="pt-6 border-t border-gray-100">
            <a href="{{ route('courses.cat-category', $category->slug) }}"
               class="inline-flex items-center justify-center w-full px-6 py-4 bg-white hover:bg-red-50 text-red-600 hover:text-red-700 text-base font-semibold rounded-2xl transition-all duration-300 border-2 border-red-100 hover:border-red-200"
               aria-label="Xem tất cả khóa học trong danh mục {{ $category->name }}">
                <span>Xem tất cả {{ $category->name }}</span>
            </a>
        </div>
    </div>
</article>

{{-- Global handler sẽ tự động xử lý .course-card-image --}}
@endif
