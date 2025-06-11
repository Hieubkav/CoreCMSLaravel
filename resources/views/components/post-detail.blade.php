@props(['post'])

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

<style>
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
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Đã copy link vào clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
