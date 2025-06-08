@extends('layouts.shop')

@section('title', $category->name . ' - Core Framework')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Category Header -->
    <section class="bg-white py-12 border-b">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ $category->name }}</h1>
                @if($category->description)
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">{{ $category->description }}</p>
                @endif
                <div class="mt-4">
                    <span class="text-gray-500">{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }} found</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Posts Grid -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            @if($posts->isNotEmpty())
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach($posts as $post)
                    <article class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                        @if($post->thumbnail)
                        <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                        @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-3xl"></i>
                        </div>
                        @endif
                        
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-3 line-clamp-2">{{ $post->title }}</h3>
                            <p class="text-gray-600 mb-4 line-clamp-3">{!! Str::limit(strip_tags($post->content), 120) !!}</p>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">{{ $post->created_at->format('M d, Y') }}</span>
                                <a href="{{ route('posts.show', $post->slug) }}" class="text-blue-600 font-semibold hover:text-blue-800 transition">
                                    Read More →
                                </a>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-newspaper text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Posts Found</h3>
                    <p class="text-gray-500">There are no posts in this category yet.</p>
                    <div class="mt-6">
                        <a href="{{ route('posts.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                            View All Posts
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
