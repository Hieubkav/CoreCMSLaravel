@extends('layouts.shop')

@section('title', $seoData['title'] ?? $post->title)
@section('description', $seoData['description'] ?? strip_tags($post->content))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Post Header -->
    <section class="bg-white py-12 border-b">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Breadcrumb -->
                <nav class="mb-6">
                    <ol class="flex items-center space-x-2 text-sm text-gray-500">
                        <li><a href="{{ route('storeFront') }}" class="hover:text-blue-600">Home</a></li>
                        <li><i class="fas fa-chevron-right text-xs"></i></li>
                        <li><a href="{{ route('posts.index') }}" class="hover:text-blue-600">Posts</a></li>
                        @if($post->category)
                        <li><i class="fas fa-chevron-right text-xs"></i></li>
                        <li><a href="{{ route('posts.category', $post->category->slug) }}" class="hover:text-blue-600">{{ $post->category->name }}</a></li>
                        @endif
                        <li><i class="fas fa-chevron-right text-xs"></i></li>
                        <li class="text-gray-700">{{ Str::limit($post->title, 30) }}</li>
                    </ol>
                </nav>

                <!-- Post Meta -->
                @if($post->category)
                <div class="mb-4">
                    <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded">
                        {{ $post->category->name }}
                    </span>
                </div>
                @endif

                <!-- Post Title -->
                <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ $post->title }}</h1>
                
                <!-- Post Date -->
                <div class="text-gray-500 mb-6">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Published on {{ $post->created_at->format('F d, Y') }}
                </div>
            </div>
        </div>
    </section>

    <!-- Post Content -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Featured Image -->
                @if($post->thumbnail)
                <div class="mb-8">
                    <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-64 md:h-96 object-cover rounded-lg shadow-lg">
                </div>
                @endif

                <!-- Post Content -->
                <div class="prose prose-lg max-w-none">
                    {!! $post->content !!}
                </div>
            </div>
        </div>
    </section>

    <!-- Related Posts -->
    @if($relatedPosts->isNotEmpty())
    <section class="py-12 bg-white border-t">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Related Posts</h2>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($relatedPosts as $relatedPost)
                    <article class="bg-gray-50 rounded-lg overflow-hidden hover:shadow-lg transition">
                        @if($relatedPost->thumbnail)
                        <img src="{{ asset('storage/' . $relatedPost->thumbnail) }}" alt="{{ $relatedPost->title }}" class="w-full h-40 object-cover">
                        @else
                        <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                        </div>
                        @endif
                        
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2 line-clamp-2">{{ $relatedPost->title }}</h3>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{!! Str::limit(strip_tags($relatedPost->content), 80) !!}</p>
                            <a href="{{ route('posts.show', $relatedPost->slug) }}" class="text-blue-600 font-semibold hover:text-blue-800 transition text-sm">
                                Read More →
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif
</div>
@endsection
