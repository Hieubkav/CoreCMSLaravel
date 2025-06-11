@extends('layouts.shop')

@section('title', $post->seo_title ?: $post->title . ' - ' . ($globalSettings->site_name ?? 'Website'))

@section('meta')
    <meta name="description" content="{{ $post->seo_description ?: Str::limit(strip_tags($post->content), 160) }}">
    <meta property="og:title" content="{{ $post->seo_title ?: $post->title }}">
    <meta property="og:description" content="{{ $post->seo_description ?: Str::limit(strip_tags($post->content), 160) }}">
    <meta property="og:image" content="{{ $post->og_image ? Storage::url($post->og_image) : ($post->thumbnail ? Storage::url($post->thumbnail) : asset('images/default-og.jpg')) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="article">
    <meta property="article:published_time" content="{{ $post->published_at->toISOString() }}">
    @if($post->category)
        <meta property="article:section" content="{{ $post->category->name }}">
    @endif
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('storeFront') }}" class="text-gray-500 hover:text-red-600">
                            <i class="fas fa-home"></i>
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
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-700 truncate max-w-xs">{{ $post->title }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Main Article -->
        <article class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Featured Image -->
            @if($post->thumbnail)
                <div class="aspect-w-16 aspect-h-9 relative">
                    <img src="{{ Storage::url($post->thumbnail) }}" 
                         alt="{{ $post->title }}"
                         class="w-full h-64 md:h-96 object-cover">
                    @if($post->is_hot)
                        <div class="absolute top-4 left-4">
                            <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                Nổi bật
                            </span>
                        </div>
                    @endif
                </div>
            @endif

            <div class="p-6 md:p-8">
                <!-- Article Meta -->
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-6">
                    @if($post->category)
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">
                            {{ $post->category->name }}
                        </span>
                    @endif
                    
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full">
                        {{ ucfirst(str_replace('_', ' ', $post->post_type)) }}
                    </span>
                    
                    <div class="flex items-center">
                        <i class="fas fa-calendar mr-1"></i>
                        <time datetime="{{ $post->published_at->format('Y-m-d') }}">
                            {{ $post->published_at->format('d/m/Y') }}
                        </time>
                    </div>
                    
                    <div class="flex items-center">
                        <i class="fas fa-eye mr-1"></i>
                        {{ number_format($post->view_count) }} lượt xem
                    </div>

                    @if($post->author)
                        <div class="flex items-center">
                            <i class="fas fa-user mr-1"></i>
                            {{ $post->author->name }}
                        </div>
                    @endif
                </div>

                <!-- Article Title -->
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight">
                    {{ $post->title }}
                </h1>

                <!-- Article Content -->
                <div class="prose prose-lg max-w-none">
                    {!! $post->content !!}
                </div>

                <!-- Article Images Gallery -->
                @if($post->images && $post->images->count() > 0)
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Hình ảnh liên quan</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($post->images as $image)
                                <div class="aspect-w-1 aspect-h-1 relative overflow-hidden rounded-lg">
                                    <img src="{{ Storage::url($image->image_path) }}" 
                                         alt="{{ $image->alt_text ?: $post->title }}"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300 cursor-pointer"
                                         onclick="openImageModal('{{ Storage::url($image->image_path) }}', '{{ $image->alt_text ?: $post->title }}')">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Share Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Chia sẻ bài viết</h3>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                           target="_blank"
                           class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fab fa-facebook-f mr-2"></i>Facebook
                        </a>
                        
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" 
                           target="_blank"
                           class="flex items-center px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors">
                            <i class="fab fa-twitter mr-2"></i>Twitter
                        </a>
                        
                        <button onclick="copyToClipboard('{{ url()->current() }}')"
                                class="flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-link mr-2"></i>Copy Link
                        </button>
                    </div>
                </div>
            </div>
        </article>

        <!-- Related Posts -->
        @if($relatedPosts && $relatedPosts->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Bài viết liên quan</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $relatedPost)
                        <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                            @if($relatedPost->thumbnail)
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ Storage::url($relatedPost->thumbnail) }}" 
                                         alt="{{ $relatedPost->title }}"
                                         class="w-full h-48 object-cover hover:scale-105 transition-transform duration-300">
                                </div>
                            @endif
                            
                            <div class="p-4">
                                <div class="text-xs text-gray-500 mb-2">
                                    {{ $relatedPost->published_at->format('d/m/Y') }}
                                </div>
                                
                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                    <a href="{{ route('blog.show', $relatedPost->slug) }}" 
                                       class="hover:text-red-600 transition-colors">
                                        {{ $relatedPost->title }}
                                    </a>
                                </h3>
                                
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

        <!-- Back to Blog -->
        <div class="mt-8 text-center">
            <a href="{{ route('blog.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại Blog
            </a>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50" onclick="closeImageModal()">
    <div class="max-w-4xl max-h-full p-4">
        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.prose {
    color: #374151;
    line-height: 1.75;
}

.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
    color: #111827;
    font-weight: 600;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.prose p {
    margin-bottom: 1.25rem;
}

.prose img {
    border-radius: 0.5rem;
    margin: 1.5rem 0;
}

.prose blockquote {
    border-left: 4px solid #ef4444;
    padding-left: 1rem;
    font-style: italic;
    color: #6b7280;
}
</style>

<script>
function openImageModal(src, alt) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    modalImage.src = src;
    modalImage.alt = alt;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Đã copy link vào clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endsection
