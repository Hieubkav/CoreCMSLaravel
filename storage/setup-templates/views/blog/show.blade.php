@extends('layouts.shop')

@section('title', $post->seo_title ?: $post->title)
@section('description', $post->seo_description ?: Str::limit(strip_tags($post->content), 160))

@push('meta')
    <meta property="og:title" content="{{ $post->seo_title ?: $post->title }}">
    <meta property="og:description" content="{{ $post->seo_description ?: Str::limit(strip_tags($post->content), 160) }}">
    <meta property="og:image" content="{{ $post->og_image ? Storage::url($post->og_image) : ($post->thumbnail ? Storage::url($post->thumbnail) : '') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="article">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $post->seo_title ?: $post->title }}">
    <meta name="twitter:description" content="{{ $post->seo_description ?: Str::limit(strip_tags($post->content), 160) }}">
    <meta name="twitter:image" content="{{ $post->og_image ? Storage::url($post->og_image) : ($post->thumbnail ? Storage::url($post->thumbnail) : '') }}">
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ url('/') }}" class="text-gray-500 hover:text-red-600">
                            <i class="fas fa-home mr-2"></i>Trang chủ
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('blog.index') }}" class="text-gray-500 hover:text-red-600">Blog</a>
                        </div>
                    </li>
                    @if($post->category)
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('blog.category', $post->category->slug) }}" class="text-gray-500 hover:text-red-600">
                                {{ $post->category->name }}
                            </a>
                        </div>
                    </li>
                    @endif
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-700 font-medium">{{ Str::limit($post->title, 50) }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Post Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <x-post-detail :post="$post" />
        </div>

        <!-- Related Posts -->
        @if($relatedPosts && $relatedPosts->count() > 0)
        <div class="mt-12">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Bài viết liên quan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedPosts as $relatedPost)
                    <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                        @if($relatedPost->thumbnail)
                            <div class="aspect-w-16 aspect-h-9 relative overflow-hidden">
                                <img src="{{ Storage::url($relatedPost->thumbnail) }}" 
                                     alt="{{ $relatedPost->title }}"
                                     class="w-full h-48 object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                        @endif
                        
                        <div class="p-4">
                            <div class="flex items-center text-xs text-gray-500 mb-2">
                                @if($relatedPost->category)
                                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded mr-2">
                                        {{ $relatedPost->category->name }}
                                    </span>
                                @endif
                                <time datetime="{{ $relatedPost->published_at->format('Y-m-d') }}">
                                    {{ $relatedPost->published_at->format('d/m/Y') }}
                                </time>
                            </div>
                            
                            <h4 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                <a href="{{ route('blog.show', $relatedPost->slug) }}" 
                                   class="hover:text-red-600 transition-colors">
                                    {{ $relatedPost->title }}
                                </a>
                            </h4>
                            
                            <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                                {{ Str::limit(strip_tags($relatedPost->content), 100) }}
                            </p>
                            
                            <a href="{{ route('blog.show', $relatedPost->slug) }}" 
                               class="text-red-600 hover:text-red-700 text-sm font-medium">
                                Đọc thêm →
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
function openImageModal(src, alt) {
    // Simple image modal implementation
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
    modal.onclick = () => modal.remove();
    
    const img = document.createElement('img');
    img.src = src;
    img.alt = alt;
    img.className = 'max-w-full max-h-full object-contain';
    
    modal.appendChild(img);
    document.body.appendChild(modal);
}
</script>
@endsection
