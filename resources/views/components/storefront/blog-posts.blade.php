@php
    // KISS: Lấy dữ liệu từ ViewServiceProvider (latestPosts) hoặc fallback
    $postsData = $latestPosts ?? collect();

    // Fallback: nếu ViewServiceProvider chưa có dữ liệu
    if ($postsData->isEmpty()) {
        try {
            $postsData = \App\Models\Post::where('status', 'active')
                ->with(['category:id,name,slug'])
                ->orderBy('order')
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();
        } catch (\Exception $e) {
            $postsData = collect();
        }
    }

    // Tách bài viết chính (đầu tiên) và bài viết phụ (3 bài còn lại)
    $mainPost = $postsData->first();
    $subPosts = $postsData->skip(1)->take(3);
@endphp

@if($postsData->count() > 0)
<!-- Layout đỏ-trắng minimalism: 1 bài lớn trên + 3 bài nhỏ dưới -->
<div class="space-y-8">
    @if($mainPost)
    <!-- Bài viết chính - Dòng trên -->
    <div class="main-post-card rounded-xl border border-red-100 overflow-hidden shadow-lg">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Thumbnail lớn -->
            <div class="relative">
                @if($mainPost->thumbnail)
                    <img
                        src="{{ asset('storage/' . $mainPost->thumbnail) }}"
                        alt="{{ $mainPost->title }}"
                        class="w-full h-64 lg:h-80 object-cover"
                        loading="lazy"
                        onerror="handleImageError(this)">
                @else
                    <!-- Default icon -->
                    <div class="w-full h-64 lg:h-80 bg-red-50 flex items-center justify-center">
                        <i class="fas fa-newspaper text-5xl text-red-300"></i>
                    </div>
                @endif
            </div>

            <!-- Content lớn -->
            <div class="p-6 lg:p-8 flex flex-col justify-center">
                <!-- Category & Date -->
                <div class="flex items-center gap-3 mb-4">
                    @if($mainPost->category)
                    <span class="category-badge bg-gradient-to-r from-red-500 to-red-600 text-white text-sm px-4 py-2 rounded-full font-semibold shadow-sm">
                        {{ $mainPost->category->name }}
                    </span>
                    @endif
                    @if($mainPost->created_at)
                    <span class="text-sm text-gray-500">
                        {{ $mainPost->created_at->format('d/m/Y') }}
                    </span>
                    @endif
                </div>

                <!-- Title lớn -->
                @if($mainPost->slug)
                <a href="{{ route('posts.show', $mainPost->slug) }}">
                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4 hover:text-red-600 transition-colors line-clamp-3">
                        {{ $mainPost->title }}
                    </h2>
                </a>
                @else
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4 line-clamp-3">{{ $mainPost->title }}</h2>
                @endif

                <!-- Excerpt lớn -->
                @if($mainPost->content)
                <p class="text-gray-600 mb-6 line-clamp-4 text-lg leading-relaxed">
                    {{ Str::limit(strip_tags($mainPost->content), 200) }}
                </p>
                @endif

                <!-- Read more button lớn -->
                @if($mainPost->slug)
                <a href="{{ route('posts.show', $mainPost->slug) }}"
                   class="inline-flex items-center bg-red-600 text-white px-6 py-3 rounded-lg font-semibold text-lg hover:bg-red-700 transition-all duration-300 shadow-md hover:shadow-lg group">
                    Đọc tiếp
                    <i class="fas fa-arrow-right ml-3 text-sm transform group-hover:translate-x-1 transition-transform"></i>
                </a>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- 3 bài viết phụ - Dòng dưới -->
    @if($subPosts->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($subPosts as $post)
        <div class="sub-post-card bg-white rounded-lg border border-gray-100 overflow-hidden shadow-sm hover:border-red-200 hover:shadow-md transition-all duration-300">
            <!-- Thumbnail nhỏ -->
            @if($post->thumbnail)
                <img
                    src="{{ asset('storage/' . $post->thumbnail) }}"
                    alt="{{ $post->title }}"
                    class="w-full h-48 object-cover"
                    loading="lazy"
                    onerror="handleImageError(this)">
            @else
                <!-- Default icon -->
                <div class="w-full h-48 bg-red-50 flex items-center justify-center">
                    <i class="fas fa-newspaper text-2xl text-red-300"></i>
                </div>
            @endif

            <!-- Content nhỏ -->
            <div class="p-4">
                <!-- Category & Date -->
                <div class="flex items-center gap-2 mb-3">
                    @if($post->category)
                    <span class="category-badge bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full font-medium hover:bg-red-50 hover:text-red-700">
                        {{ $post->category->name }}
                    </span>
                    @endif
                    @if($post->created_at)
                    <span class="text-xs text-gray-500">
                        {{ $post->created_at->format('d/m/Y') }}
                    </span>
                    @endif
                </div>

                <!-- Title nhỏ -->
                @if($post->slug)
                <a href="{{ route('posts.show', $post->slug) }}">
                    <h3 class="font-semibold text-gray-900 mb-2 hover:text-red-600 transition-colors line-clamp-2">
                        {{ $post->title }}
                    </h3>
                </a>
                @else
                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $post->title }}</h3>
                @endif

                <!-- Excerpt nhỏ -->
                @if($post->content)
                <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                    {{ Str::limit(strip_tags($post->content), 100) }}
                </p>
                @endif

                <!-- Read more link nhỏ -->
                @if($post->slug)
                <a href="{{ route('posts.show', $post->slug) }}"
                   class="text-red-600 text-sm font-medium hover:text-red-700 transition-colors">
                    Đọc tiếp →
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Nút xem tất cả với hiệu ứng đẹp - Layout 2 dòng -->
<div class="text-center mt-12 pt-8 border-t border-gray-100">
    <div class="max-w-md mx-auto">
        <h3 class="text-lg font-semibold text-gray-900 mb-3">Khám phá thêm nhiều bài viết</h3>
        <p class="text-gray-600 text-sm mb-6">Cập nhật kiến thức Excel, Core và kỹ năng văn phòng mới nhất</p>
        <a href="{{ route('posts.index') }}"
           class="cta-button inline-flex items-center text-white px-8 py-4 rounded-xl font-semibold text-lg relative overflow-hidden group shadow-lg">
            <i class="fas fa-newspaper mr-3 transform group-hover:scale-110 transition-transform duration-300"></i>
            <span class="relative z-10">Xem tất cả bài viết</span>
            <i class="fas fa-arrow-right ml-3 transform group-hover:translate-x-1 transition-transform duration-300"></i>
        </a>
    </div>
</div>
@else
<!-- Fallback UI đẹp mắt cho layout 2 dòng -->
<div class="text-center py-16">
    <!-- Icon lớn -->
    <div class="w-24 h-24 bg-gradient-to-br from-red-50 to-red-100 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm">
        <i class="fas fa-newspaper text-3xl text-red-400"></i>
    </div>

    <!-- Tiêu đề -->
    <h3 class="text-xl font-semibold text-gray-900 mb-3">Chưa có bài viết nào</h3>
    <p class="text-gray-600 mb-8 max-w-md mx-auto">
        Hiện tại chưa có bài viết nào được xuất bản. Hãy quay lại sau hoặc khám phá các nội dung khác.
    </p>

    <!-- Action buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('posts.index') }}"
           class="inline-flex items-center bg-red-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-red-700 transition-colors shadow-md">
            <i class="fas fa-search mr-2"></i>
            Khám phá bài viết
        </a>
        <a href="{{ route('courses.index') }}"
           class="inline-flex items-center border-2 border-red-600 text-red-600 px-6 py-3 rounded-lg font-medium hover:bg-red-600 hover:text-white transition-colors">
            <i class="fas fa-graduation-cap mr-2"></i>
            Xem khóa học
        </a>
    </div>
</div>
@endif

{{-- Enhanced CSS với Tailwind và hiệu ứng đẹp mắt --}}
@push('styles')
<style>
.line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.line-clamp-3 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}

/* Hiệu ứng hover cho bài viết chính - Layout 2 dòng */
.main-post-card {
    background: linear-gradient(135deg, #ffffff 0%, #fef7f7 100%);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.main-post-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px -12px rgba(239, 68, 68, 0.3);
}

/* Hiệu ứng hover cho bài viết phụ - Layout grid 3 cột */
.sub-post-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.sub-post-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.15);
}

/* Hiệu ứng cho category badge */
.category-badge {
    transition: all 0.2s ease-in-out;
}

.category-badge:hover {
    transform: scale(1.05);
}

/* Hiệu ứng cho nút CTA */
.cta-button {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px 0 rgba(220, 38, 38, 0.3);
}

.cta-button:hover {
    background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px 0 rgba(220, 38, 38, 0.4);
}

/* Responsive adjustments cho layout 2 dòng */
@media (max-width: 768px) {
    .main-post-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -8px rgba(239, 68, 68, 0.2);
    }

    .sub-post-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px -3px rgba(0, 0, 0, 0.1);
    }
}

/* Tối ưu cho tablet */
@media (min-width: 768px) and (max-width: 1024px) {
    .main-post-card .grid {
        grid-template-columns: 1fr;
    }

    .sub-post-card {
        margin-bottom: 1rem;
    }
}
</style>
@endpush

{{-- KISS: Đơn giản hóa JavaScript, bỏ IntersectionObserver gây conflict --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Hiệu ứng cho main post - Xuất hiện trước
    const mainPost = document.querySelector('.main-post-card');
    if (mainPost) {
        mainPost.style.opacity = '0';
        mainPost.style.transform = 'translateY(40px)';
        mainPost.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';

        setTimeout(() => {
            mainPost.style.opacity = '1';
            mainPost.style.transform = 'translateY(0)';
        }, 100);
    }

    // Hiệu ứng stagger animation cho 3 bài viết phụ - Xuất hiện sau main post
    const subPosts = document.querySelectorAll('.sub-post-card');
    subPosts.forEach((post, index) => {
        post.style.opacity = '0';
        post.style.transform = 'translateY(30px)';
        post.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';

        setTimeout(() => {
            post.style.opacity = '1';
            post.style.transform = 'translateY(0)';
        }, 600 + (index * 150)); // Bắt đầu sau khi main post xuất hiện
    });

    // Smooth scroll cho nút "Xem tất cả"
    const ctaButton = document.querySelector('.cta-button');
    if (ctaButton) {
        ctaButton.addEventListener('click', function(e) {
            // Thêm hiệu ứng ripple
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    }

    // Hiệu ứng hover cho category badges
    const categoryBadges = document.querySelectorAll('.category-badge');
    categoryBadges.forEach(badge => {
        badge.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });

        badge.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>

<style>
.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: scale(0);
    animation: ripple-animation 0.6s linear;
    pointer-events: none;
}

@keyframes ripple-animation {
    to {
        transform: scale(4);
        opacity: 0;
    }
}
</style>
@endpush

{{-- Global handler sẽ tự động xử lý .blog-image --}}