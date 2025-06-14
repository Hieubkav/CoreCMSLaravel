@props(['order' => 1])

@php
    $hotPost = null;
    $recentPosts = collect();
    $globalSettings = null;

    // Kiểm tra xem model Post có tồn tại không
    if (class_exists('App\Models\Post')) {
        try {
            // Lấy bài viết nổi bật (is_hot = true) và bài viết mới nhất
            $hotPost = \App\Models\Post::where('status', 'active')
                ->where('is_hot', true)
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->orderBy('published_at', 'desc')
                ->first();

            $recentPosts = \App\Models\Post::where('status', 'active')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->when($hotPost, function($query) use ($hotPost) {
                    return $query->where('id', '!=', $hotPost->id);
                })
                ->orderBy('published_at', 'desc')
                ->limit(3)
                ->get();

            // Nếu không có bài viết nổi bật, lấy bài viết mới nhất làm bài chính
            if (!$hotPost && $recentPosts->count() > 0) {
                $hotPost = $recentPosts->first();
                $recentPosts = $recentPosts->skip(1);
            }
        } catch (\Exception $e) {
            $hotPost = null;
            $recentPosts = collect();
        }
    }

    // Lấy global settings
    try {
        $globalSettings = \App\Models\Setting::first();
    } catch (\Exception $e) {
        $globalSettings = null;
    }
@endphp

@if($hotPost || $recentPosts->count() > 0)
<section class="py-16 bg-gray-50" style="order: {{ $order }};">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                {{ $globalSettings->blog_title ?? 'Tin tức & Blog' }}
            </h2>
            @if($globalSettings->blog_description ?? '')
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ $globalSettings->blog_description }}
                </p>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Bài viết chính (Featured Post) -->
            @if($hotPost)
            <div class="lg:col-span-1">
                <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="aspect-w-16 aspect-h-9 relative overflow-hidden">
                        @if($hotPost->thumbnail)
                            <img src="{{ Storage::url($hotPost->thumbnail) }}"
                                 alt="{{ $hotPost->title }}"
                                 class="w-full h-64 object-cover hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-64 bg-gradient-to-br from-red-100 to-red-200 flex items-center justify-center">
                                <i class="fas fa-newspaper text-4xl text-red-400"></i>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4">
                            <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                Nổi bật
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            @if($hotPost->category)
                                <a href="{{ route('blog.category', $hotPost->category->slug) }}"
                                   class="bg-gray-100 text-gray-700 px-2 py-1 rounded-md mr-3 hover:bg-gray-200 transition-colors">
                                    {{ $hotPost->category->name }}
                                </a>
                            @endif
                            <time datetime="{{ $hotPost->published_at->format('Y-m-d') }}">
                                {{ $hotPost->published_at->format('d/m/Y') }}
                            </time>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                            <a href="{{ route('blog.show', $hotPost->slug) }}"
                               class="hover:text-red-600 transition-colors">
                                {{ $hotPost->title }}
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            {{ Str::limit(strip_tags($hotPost->content), 150) }}
                        </p>
                        
                        <div class="flex items-center justify-between">
                            <a href="{{ route('blog.show', $hotPost->slug) }}"
                               class="inline-flex items-center text-red-600 hover:text-red-700 font-medium">
                                Đọc thêm
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                            
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-eye mr-1"></i>
                                {{ number_format($hotPost->view_count) }}
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            @endif

            <!-- 3 bài viết mới nhất -->
            <div class="lg:col-span-1">
                <div class="space-y-6">
                    @foreach($recentPosts as $post)
                    <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="flex">
                            <div class="flex-shrink-0 w-32 h-24 relative overflow-hidden">
                                @if($post->thumbnail)
                                    <img src="{{ Storage::url($post->thumbnail) }}"
                                         alt="{{ $post->title }}"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <i class="fas fa-file-alt text-xl text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-1 p-4">
                                <div class="flex items-center text-xs text-gray-500 mb-2">
                                    @if($post->category)
                                        <a href="{{ route('blog.category', $post->category->slug) }}"
                                           class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs mr-2 hover:bg-gray-200 transition-colors">
                                            {{ $post->category->name }}
                                        </a>
                                    @endif
                                    <time datetime="{{ $post->published_at->format('Y-m-d') }}">
                                        {{ $post->published_at->format('d/m/Y') }}
                                    </time>
                                </div>
                                
                                <h4 class="font-semibold text-gray-900 mb-2 line-clamp-2 text-sm">
                                    <a href="{{ route('blog.show', $post->slug) }}"
                                       class="hover:text-red-600 transition-colors">
                                        {{ $post->title }}
                                    </a>
                                </h4>
                                
                                <p class="text-gray-600 text-xs line-clamp-2 mb-2">
                                    {{ Str::limit(strip_tags($post->content), 80) }}
                                </p>
                                
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('blog.show', $post->slug) }}"
                                       class="text-red-600 hover:text-red-700 text-xs font-medium">
                                        Đọc thêm →
                                    </a>
                                    
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-eye mr-1"></i>
                                        {{ number_format($post->view_count) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- View All Button -->
        <div class="text-center mt-12">
            <a href="{{ route('blog.index') }}"
               class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                Xem tất cả bài viết
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

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

.aspect-w-16 {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
}

.aspect-w-16 > * {
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
}
</style>
