@php
    // Get main image
    $mainImage = $course->images()
        ->where('status', 'active')
        ->orderBy('is_main', 'desc')
        ->orderBy('order')
        ->first();
    
    if ($mainImage && \Illuminate\Support\Facades\Storage::disk('public')->exists($mainImage->image_link)) {
        $mainImageUrl = asset('storage/' . $mainImage->image_link);
    } elseif ($course->thumbnail && \Illuminate\Support\Facades\Storage::disk('public')->exists($course->thumbnail)) {
        $mainImageUrl = asset('storage/' . $course->thumbnail);
    } else {
        $mainImageUrl = asset('images/placeholder-course.jpg');
    }
    
    // Get short description
    $shortDescription = \Illuminate\Support\Str::limit(strip_tags($course->description), 120);
    
    // Get level badge color
    $levelBadgeColor = match($course->level) {
        'beginner' => 'bg-green-100 text-green-800',
        'intermediate' => 'bg-yellow-100 text-yellow-800',
        'advanced' => 'bg-red-100 text-red-800',
        default => 'bg-gray-100 text-gray-800'
    };
    
    // Get level text
    $levelText = match($course->level) {
        'beginner' => 'Cơ bản',
        'intermediate' => 'Trung cấp',
        'advanced' => 'Nâng cao',
        default => 'Không xác định'
    };
    
    // Get discount percentage
    $discountPercentage = 0;
    if ($course->compare_price && $course->compare_price > $course->price) {
        $discountPercentage = round((($course->compare_price - $course->price) / $course->compare_price) * 100);
    }
@endphp

<!-- Course Card Component với Fallback UI đẹp mắt -->
<article class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-red-200"
         role="article"
         aria-labelledby="course-{{ $course->id }}-title">

    <!-- Course Image với Fallback UI thông minh -->
    <div class="relative overflow-hidden aspect-[16/10] bg-gradient-to-br from-red-50 to-red-100">
        @if($mainImageUrl && $mainImageUrl !== asset('images/placeholder-course.jpg'))
            <img src="{{ $mainImageUrl }}"
                 alt="{{ $course->seo_title ?? $course->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                 loading="lazy">
        @endif

        <!-- Fallback UI khi không có ảnh hoặc ảnh lỗi -->
        <div class="absolute inset-0 flex flex-col items-center justify-center text-red-600 {{ $mainImageUrl && $mainImageUrl !== asset('images/placeholder-course.jpg') ? 'hidden' : 'flex' }}"
             id="fallback-{{ $course->id }}">
            <div class="text-center space-y-3">
                <i class="fas fa-graduation-cap text-4xl opacity-60"></i>
                <div class="space-y-1">
                    <span class="block text-sm font-semibold opacity-80 line-clamp-1">{{ Str::limit($course->title, 20) }}</span>
                    <span class="block text-xs opacity-60">{{ $levelText }}</span>
                    @if($course->instructor && $course->show_instructor)
                    <span class="block text-xs opacity-50">{{ Str::limit($course->instructor->name, 15) }}</span>
                    @endif
                </div>
            </div>

            <!-- Decorative elements -->
            <div class="absolute top-4 right-4 w-8 h-8 bg-red-200 rounded-full opacity-30"></div>
            <div class="absolute bottom-4 left-4 w-6 h-6 bg-red-300 rounded-full opacity-20"></div>
            <div class="absolute top-1/2 left-4 w-4 h-4 bg-red-400 rounded-full opacity-15"></div>
        </div>

        <!-- Course Level Badge -->
        @if($course->level)
        <div class="absolute top-4 left-4 z-10">
            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $levelBadgeColor }}">
                {{ $levelText }}
            </span>
        </div>
        @endif
    </div>

    <!-- Course Content -->
    <div class="p-6">
        <!-- Course Category -->
        @if($course->courseCategory)
        <div class="mb-3">
            <span class="inline-block px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded">
                {{ $course->courseCategory->name }}
            </span>
        </div>
        @endif

        <!-- Course Title -->
        <h3 id="course-{{ $course->id }}-title"
            class="font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-red-600 transition-colors duration-300 text-xl">
            <a href="{{ route('courses.show', $course->slug) }}" class="hover:underline">
                {{ $course->title }}
            </a>
        </h3>

        <!-- Course Description -->
        @if($shortDescription)
        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
            {{ $shortDescription }}
        </p>
        @endif

        <!-- Course Meta Info -->
        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 mb-4">
            @if($course->instructor && $course->show_instructor)
            <div class="flex items-center">
                <i class="fas fa-user-tie mr-1"></i>
                @if($course->instructor->slug)
                    <a href="{{ route('instructors.show', $course->instructor->slug) }}"
                       class="hover:text-red-600 transition-colors duration-300">
                        {{ $course->instructor->name }}
                    </a>
                @else
                    <span>{{ $course->instructor->name }}</span>
                @endif
            </div>
            @endif

            @if($course->duration_hours)
            <div class="flex items-center">
                <i class="fas fa-clock mr-1"></i>
                <span>{{ $course->duration_hours }}h</span>
            </div>
            @endif
        </div>

        <!-- Price and Action -->
        <div class="flex items-center justify-between">
            <!-- Price -->
            @if($course->show_price)
            <div class="flex items-center gap-2">
                @if($course->compare_price && $course->compare_price > $course->price)
                <span class="text-lg font-bold text-red-600">{{ number_format($course->price, 0, ',', '.') }}đ</span>
                <span class="text-sm text-gray-500 line-through">{{ number_format($course->compare_price, 0, ',', '.') }}đ</span>
                @if($discountPercentage > 0)
                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded">
                    -{{ $discountPercentage }}%
                </span>
                @endif
                @else
                <span class="text-lg font-bold text-red-600">{{ number_format($course->price, 0, ',', '.') }}đ</span>
                @endif
            </div>
            @else
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Liên hệ để biết giá</span>
            </div>
            @endif

            <!-- View Details Button -->
            <a href="{{ route('courses.show', $course->slug) }}"
               class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-300 flex items-center">
                <span>Chi tiết</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</article>
