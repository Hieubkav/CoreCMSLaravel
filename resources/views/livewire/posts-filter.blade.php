<div>
    <!-- Mobile Filter Toggle Button -->
    <div class="lg:hidden mb-6">
        <button onclick="toggleSidebar()" class="w-full bg-red-600 text-white py-3 px-6 rounded-xl hover:bg-red-700 transition-colors font-medium font-open-sans flex items-center justify-center">
            <i class="fas fa-filter mr-2"></i>
            Bộ lọc & Tìm kiếm
        </button>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Desktop Sidebar Filters -->
        <aside class="hidden lg:block w-80 flex-shrink-0">
            <div class="space-y-6" id="desktop-filter-content">
                <!-- Search -->
                <div class="filter-card rounded-xl p-5">
                    <h3 class="text-base font-semibold text-gray-900 mb-3 font-montserrat">Tìm kiếm</h3>
                    <div class="relative">
                        <input type="text"
                               wire:model.live.debounce.200ms="search"
                               placeholder="Tìm kiếm bài viết, tin tức..."
                               class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 font-open-sans text-sm transition-all duration-200 hover:border-red-300">
                        <i class="fas fa-search absolute left-2.5 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="filter-card rounded-xl p-5">
                    <h3 class="text-base font-semibold text-gray-900 mb-3 font-montserrat">Danh mục</h3>
                    <div class="space-y-1.5">
                        <button wire:click="$set('category_id', '')"
                               class="filter-btn block w-full text-left px-3 py-2 rounded-lg font-open-sans text-sm {{ !$category_id ? 'active' : '' }}">
                            Tất cả danh mục
                        </button>
                        @php
                            $categories = \App\Models\CatPost::where('status', 'active')
                                ->withCount(['posts' => function($query) {
                                    $query->where('status', 'active');
                                }])
                                ->orderBy('order')
                                ->get();
                        @endphp
                        @foreach($categories as $category)
                            <button wire:click="$set('category_id', '{{ $category->id }}')"
                                   class="filter-btn block w-full text-left px-3 py-2 rounded-lg font-open-sans text-sm {{ $category_id == $category->id ? 'active' : '' }}">
                                {{ $category->name }}
                                <span class="text-xs text-gray-500 ml-1">({{ $category->posts_count }})</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Clear Filters -->
                @if($search || $category_id || $sort !== 'newest')
                <div class="filter-card rounded-xl p-5">
                    <button wire:click="clearFilters"
                           class="block w-full text-center px-3 py-2.5 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors font-medium font-open-sans text-sm">
                        <i class="fas fa-redo mr-2"></i>
                        Xóa bộ lọc
                    </button>
                </div>
                @endif
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1">
            <!-- Active Filters & Results Info -->
            <div class="mb-8 p-6 bg-white rounded-2xl shadow-lg">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <!-- Left: Results count and sort info -->
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center text-gray-600 font-open-sans">
                            <i class="fas fa-file-alt mr-2 text-gray-400"></i>
                            <span class="font-semibold text-gray-900">{{ $totalPosts }}</span> bài viết
                        </div>

                        @php
                            $sortOptions = [
                                'newest' => 'Mới nhất',
                                'oldest' => 'Cũ nhất'
                            ];
                        @endphp
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-500 font-open-sans">Sắp xếp:</label>
                            <select wire:model.live="sort"
                                    class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:ring-1 focus:ring-red-500 focus:border-red-500 bg-white">
                                @foreach($sortOptions as $sortKey => $sortName)
                                    <option value="{{ $sortKey }}">{{ $sortName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Right: Active filters and clear button -->
                    <div class="flex flex-wrap items-center gap-3">
                        @if($search || $category_id || $sort !== 'newest')
                            <!-- Active filters -->
                            @if($search)
                                <span class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-800 rounded-full text-xs font-medium font-open-sans">
                                    <i class="fas fa-search mr-1.5"></i>
                                    "{{ Str::limit($search, 20) }}"
                                </span>
                            @endif

                            @if($category_id)
                                @php
                                    $selectedCategory = \App\Models\CatPost::find($category_id);
                                @endphp
                                @if($selectedCategory)
                                <span class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-800 rounded-full text-xs font-medium font-open-sans">
                                    <i class="fas fa-folder mr-1.5"></i>
                                    {{ $selectedCategory->name }}
                                </span>
                                @endif
                            @endif

                            <!-- Clear filters button -->
                            <button wire:click="clearFilters"
                                   class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full text-xs font-medium font-open-sans hover:bg-gray-200 transition-colors">
                                <i class="fas fa-times mr-1.5"></i>
                                Xóa bộ lọc
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div wire:loading class="text-center py-8">
                <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg">
                    <i class="fas fa-spinner fa-spin mr-3"></i>
                    Đang tải...
                </div>
            </div>

            <!-- Posts Grid -->
            <div wire:loading.remove>
                @if($posts->count() > 0)
                    <!-- Grid thông minh tự điều chỉnh theo số lượng bài viết -->
                    @php
                        $postCount = $posts->count();
                        $gridClass = match(true) {
                            $postCount === 1 => 'grid grid-cols-1 max-w-2xl mx-auto',
                            $postCount === 2 => 'grid grid-cols-1 lg:grid-cols-2 max-w-5xl mx-auto',
                            $postCount >= 3 => 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4',
                            default => 'grid grid-cols-1 lg:grid-cols-2'
                        };
                    @endphp
                    <div class="{{ $gridClass }} gap-6 lg:gap-8 mb-16">
                        @foreach($posts as $post)
                            <article class="group">
                                <a href="{{ route('posts.show', $post->slug) }}" class="block">
                                    <div class="post-card bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl border border-gray-100 hover:border-red-100 transition-all duration-300 h-full flex flex-col">
                                        <!-- Post Image - Sử dụng component tái sử dụng -->
                                        <div class="relative overflow-hidden rounded-t-2xl flex-shrink-0">
                                            <x-image-fallback
                                                :model="$post"
                                                image-field="thumbnail"
                                                aspect-ratio="aspect-[16/9]"
                                                size="medium"
                                                :show-hover="true"
                                                :show-type="true" />
                                        </div>

                                        <!-- Post Content -->
                                        <div class="p-6 flex-grow flex flex-col">
                                            <!-- Category Badge -->
                                            @if($post->category)
                                            <div class="flex items-center mb-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 font-open-sans">
                                                    <i class="fas fa-folder mr-1"></i>
                                                    {{ $post->category->name }}
                                                </span>
                                            </div>
                                            @endif

                                            <!-- Title -->
                                            <h3 class="text-lg lg:text-xl font-bold text-gray-900 mb-3 group-hover:text-red-600 transition-colors font-montserrat line-clamp-2 flex-shrink-0">
                                                {{ $post->title }}
                                            </h3>

                                            <!-- Excerpt -->
                                            @if($post->content)
                                                <p class="text-gray-600 mb-4 font-open-sans line-clamp-3 text-sm lg:text-base flex-grow">
                                                    {{ Str::limit(strip_tags($post->content), 120) }}
                                                </p>
                                            @endif

                                            <!-- Meta Info -->
                                            <div class="flex items-center justify-between text-sm text-gray-500 font-open-sans mt-auto pt-2 border-t border-gray-100">
                                                <div class="flex items-center">
                                                    <i class="far fa-calendar mr-2"></i>
                                                    {{ $post->created_at->format('d/m/Y') }}
                                                </div>

                                                @if($post->category)
                                                    <div class="flex items-center">
                                                        <i class="fas fa-folder mr-2"></i>
                                                        {{ $post->category->name }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>

                    <!-- Load More Button -->
                    @if($hasMorePosts)
                        <div class="flex justify-center">
                            <button wire:click="loadMore"
                                   wire:loading.attr="disabled"
                                   class="inline-flex items-center px-8 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors font-medium font-open-sans">
                                <span wire:loading.remove wire:target="loadMore">
                                    <i class="fas fa-chevron-down mr-2"></i>
                                    Xem thêm bài viết
                                </span>
                                <span wire:loading wire:target="loadMore" class="flex items-center">
                                    <i class="fas fa-spinner fa-spin mr-3"></i>
                                    Đang tải...
                                </span>
                            </button>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 font-open-sans">
                                <i class="fas fa-check-circle mr-2"></i>
                                Đã hiển thị tất cả bài viết
                            </p>
                        </div>
                    @endif
                @else
                    <!-- Empty State - Minimalist Design -->
                    <div class="text-center py-20">
                        <div class="max-w-lg mx-auto">
                            <!-- Icon với gradient background -->
                            <div class="w-32 h-32 mx-auto mb-8 bg-gradient-to-br from-red-50 to-red-100 rounded-2xl flex items-center justify-center relative overflow-hidden">
                                <div class="absolute inset-0 opacity-10">
                                    <div class="w-full h-full" style="background-image: radial-gradient(circle at 25% 25%, rgba(220, 38, 38, 0.1) 2px, transparent 2px), radial-gradient(circle at 75% 75%, rgba(220, 38, 38, 0.1) 2px, transparent 2px); background-size: 20px 20px;"></div>
                                </div>
                                <i class="fas fa-search text-5xl text-red-300 relative z-10"></i>
                            </div>

                            <h3 class="text-2xl font-bold text-gray-900 mb-4 font-montserrat">Không tìm thấy bài viết</h3>
                            <p class="text-gray-600 mb-8 font-open-sans leading-relaxed">
                                @if($search || $category_id)
                                    Không có bài viết nào phù hợp với tiêu chí tìm kiếm của bạn.<br>
                                    Thử thay đổi bộ lọc hoặc từ khóa để xem thêm kết quả.
                                @else
                                    Hiện tại chưa có bài viết nào được đăng tải.<br>
                                    Vui lòng quay lại sau để xem nội dung mới.
                                @endif
                            </p>

                            @if($search || $category_id || $sort !== 'newest')
                                <button wire:click="clearFilters"
                                       class="inline-flex items-center px-8 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 hover:shadow-lg transition-all duration-300 font-medium font-open-sans">
                                    <i class="fas fa-redo mr-3"></i>
                                    Xem tất cả bài viết
                                </button>
                            @else
                                <a href="{{ route('storeFront') }}"
                                   class="inline-flex items-center px-8 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 hover:shadow-lg transition-all duration-300 font-medium font-open-sans">
                                    <i class="fas fa-home mr-3"></i>
                                    Về trang chủ
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Mobile Sidebar -->
    <div class="mobile-sidebar lg:hidden" onclick="toggleSidebar()">
        <div class="mobile-sidebar-content" onclick="event.stopPropagation()">
            <div class="p-6">
                <!-- Close button -->
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 font-montserrat">Bộ lọc</h3>
                    <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Mobile Filter Content (will be populated by JavaScript) -->
                <div id="mobile-filter-content"></div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Tối ưu responsive grid */
    @media (max-width: 640px) {
        .post-card {
            margin-bottom: 1rem;
        }

        .post-card .p-6 {
            padding: 1rem;
        }
    }

    /* Smooth transitions cho hover effects */
    .post-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .post-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Loading animation */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    /* Filter button styles */
    .filter-btn {
        transition: all 0.2s ease;
    }

    .filter-btn:hover {
        background-color: rgba(220, 38, 38, 0.05);
        color: #dc2626;
    }

    .filter-btn.active {
        background-color: #dc2626;
        color: white;
    }

    /* Mobile sidebar improvements */
    .mobile-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 50;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .mobile-sidebar.active {
        opacity: 1;
        visibility: visible;
    }

    .mobile-sidebar-content {
        position: absolute;
        top: 0;
        right: 0;
        height: 100%;
        width: 85%;
        max-width: 400px;
        background: white;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        overflow-y: auto;
    }

    .mobile-sidebar.active .mobile-sidebar-content {
        transform: translateX(0);
    }

    /* Responsive text sizing */
    @media (max-width: 768px) {
        .text-xl {
            font-size: 1.125rem;
        }

        .text-2xl {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function toggleSidebar() {
    const mobileSidebar = document.querySelector('.mobile-sidebar');
    mobileSidebar.classList.toggle('active');

    // Prevent body scroll when sidebar is open
    if (mobileSidebar.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}

// Copy filter content to mobile sidebar
document.addEventListener('DOMContentLoaded', function() {
    const desktopContent = document.getElementById('desktop-filter-content');
    const mobileContent = document.getElementById('mobile-filter-content');

    if (desktopContent && mobileContent) {
        mobileContent.innerHTML = desktopContent.innerHTML;
    }

    // Close sidebar when clicking outside
    document.addEventListener('click', function(e) {
        const mobileSidebar = document.querySelector('.mobile-sidebar');
        const sidebarContent = document.querySelector('.mobile-sidebar-content');

        if (mobileSidebar && mobileSidebar.classList.contains('active')) {
            if (!sidebarContent.contains(e.target) && !e.target.closest('[onclick*="toggleSidebar"]')) {
                toggleSidebar();
            }
        }
    });

    // Close sidebar on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const mobileSidebar = document.querySelector('.mobile-sidebar');
            if (mobileSidebar && mobileSidebar.classList.contains('active')) {
                toggleSidebar();
            }
        }
    });
});

// Smooth scroll to top when filters change
window.addEventListener('livewire:navigated', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
</script>
@endpush