@extends('layouts.shop')

@section('title', 'Trang không tìm thấy - 404')
@section('description', 'Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển. Vui lòng quay lại trang chủ hoặc tìm kiếm nội dung khác.')

@section('content')
<div class="min-h-screen bg-white flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg mx-auto text-center">
        <!-- 404 Simple -->
        <div class="mb-8">
            <h1 class="text-8xl sm:text-9xl font-bold text-red-600 mb-4 font-montserrat">
                404
            </h1>
        </div>

        <!-- Error Message -->
        <div class="mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4 font-montserrat">
                Trang không tìm thấy
            </h2>
            <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
            <a href="{{ route('storeFront') }}"
               class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                Về trang chủ
            </a>

            <button onclick="history.back()"
                    class="inline-flex items-center px-6 py-3 bg-white text-red-600 font-medium rounded-lg border border-red-600 hover:bg-red-50 transition-colors duration-200">
                Quay lại
            </button>
        </div>

        <!-- Quick Links -->
        <div class="border-t border-gray-200 pt-8">
            <p class="text-gray-600 mb-4">Hoặc bạn có thể thử:</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('courses.index') }}"
                   class="text-red-600 hover:text-red-700 underline">
                    Khóa học
                </a>
                <a href="{{ route('posts.index') }}"
                   class="text-red-600 hover:text-red-700 underline">
                    Bài viết
                </a>
                <a href="{{ route('posts.news') }}"
                   class="text-red-600 hover:text-red-700 underline">
                    Tin tức
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
