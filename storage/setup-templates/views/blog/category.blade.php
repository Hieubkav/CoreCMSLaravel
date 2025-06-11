@extends('layouts.shop')

@section('title', $category->seo_title ?: ($category->name . ' - Blog'))
@section('description', $category->seo_description ?: $category->description)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Category Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
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
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-700 font-medium">{{ $category->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    {{ $category->name }}
                </h1>
                @if($category->description)
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        {{ $category->description }}
                    </p>
                @endif
                <div class="mt-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <i class="fas fa-newspaper mr-2"></i>
                        {{ $posts->total() }} bài viết
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded mr-2">
                                    {{ $category->name }}
                                </span>
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
                {{ $posts->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-folder-open text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có bài viết nào</h3>
                <p class="text-gray-600 mb-4">Danh mục này chưa có bài viết nào được xuất bản</p>
                <a href="{{ route('blog.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại tất cả bài viết
                </a>
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

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
