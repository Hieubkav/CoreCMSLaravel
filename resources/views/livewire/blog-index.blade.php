<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    {{ $globalSettings->blog_title ?? 'Blog' }}
                </h1>
                @if($globalSettings->blog_description ?? '')
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        {{ $globalSettings->blog_description }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Filters -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bộ lọc</h3>
                    
                    <div class="space-y-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                Tìm kiếm
                            </label>
                            <input type="text" 
                                   wire:model.live.debounce.300ms="search"
                                   id="search"
                                   placeholder="Nhập từ khóa..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        </div>

                        <!-- Categories -->
                        @if($categories->count() > 0)
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Danh mục
                            </label>
                            <select wire:model.live="category" 
                                    id="category"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Tất cả danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}">
                                        {{ $category->name }} ({{ $category->posts_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Post Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Loại bài viết
                            </label>
                            <select wire:model.live="type" 
                                    id="type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Tất cả loại</option>
                                <option value="tin_tuc">Tin tức</option>
                                <option value="dich_vu">Dịch vụ</option>
                                <option value="trang_don">Trang đơn</option>
                            </select>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">
                                Sắp xếp
                            </label>
                            <select wire:model.live="sort" 
                                    id="sort"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="latest">Mới nhất</option>
                                <option value="oldest">Cũ nhất</option>
                                <option value="popular">Phổ biến nhất</option>
                                <option value="title">Theo tên A-Z</option>
                            </select>
                        </div>

                        @if($search || $category || $type || $sort !== 'latest')
                            <button wire:click="clearFilters" 
                                    class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 transition-colors">
                                <i class="fas fa-times mr-2"></i>Xóa bộ lọc
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Posts Grid -->
            <div class="lg:col-span-3">
                <!-- Loading Indicator -->
                <div wire:loading class="text-center py-4">
                    <div class="inline-flex items-center px-4 py-2 bg-white rounded-lg shadow-sm">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-gray-600">Đang tải...</span>
                    </div>
                </div>

                <div wire:loading.remove>
                    @if($posts->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($posts as $post)
                                <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                                    @if($post->thumbnail)
                                        <div class="aspect-w-16 aspect-h-9 relative overflow-hidden">
                                            <img src="{{ Storage::url($post->thumbnail) }}" 
                                                 alt="{{ $post->title }}"
                                                 class="w-full h-48 object-cover hover:scale-105 transition-transform duration-300">
                                            @if($post->is_hot)
                                                <div class="absolute top-2 left-2">
                                                    <span class="bg-red-600 text-white px-2 py-1 rounded-full text-xs font-medium">
                                                        Nổi bật
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <div class="p-4">
                                        <div class="flex items-center text-xs text-gray-500 mb-2">
                                            @if($post->category)
                                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded mr-2">
                                                    {{ $post->category->name }}
                                                </span>
                                            @endif
                                            <time datetime="{{ $post->published_at->format('Y-m-d') }}">
                                                {{ $post->published_at->format('d/m/Y') }}
                                            </time>
                                        </div>
                                        
                                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                            <a href="{{ route('blog.show', $post->slug) }}" 
                                               class="hover:text-red-600 transition-colors">
                                                {{ $post->title }}
                                            </a>
                                        </h3>
                                        
                                        <p class="text-gray-600 text-sm line-clamp-3 mb-3">
                                            {{ Str::limit(strip_tags($post->content), 120) }}
                                        </p>
                                        
                                        <div class="flex items-center justify-between">
                                            <a href="{{ route('blog.show', $post->slug) }}" 
                                               class="text-red-600 hover:text-red-700 text-sm font-medium">
                                                Đọc thêm →
                                            </a>
                                            
                                            <div class="flex items-center text-xs text-gray-500">
                                                <i class="fas fa-eye mr-1"></i>
                                                {{ number_format($post->view_count) }}
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-search text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Không tìm thấy bài viết</h3>
                            <p class="text-gray-600 mb-4">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
                            <button wire:click="clearFilters" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Quay lại tất cả bài viết
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        </style>
    </div>
</div>
