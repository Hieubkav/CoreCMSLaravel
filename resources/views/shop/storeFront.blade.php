@extends('layouts.shop')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-6">Welcome to Core Framework</h1>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                A powerful, flexible Laravel-based framework for building modern web applications
            </p>
            <div class="space-x-4">
                <a href="#features" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Get Started
                </a>
                <a href="{{ route('filament.admin.pages.dashboard') }}" class="border-2 border-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition">
                    Admin Panel
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Core Features</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Everything you need to build modern web applications
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-rocket text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Fast & Modern</h3>
                    <p class="text-gray-600">Built with Laravel and modern PHP practices for optimal performance</p>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-cogs text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Highly Configurable</h3>
                    <p class="text-gray-600">Easily customize and extend to fit your specific needs</p>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Secure & Reliable</h3>
                    <p class="text-gray-600">Built-in security features and best practices</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Posts Section -->
    @if(isset($latestPosts) && $latestPosts->isNotEmpty())
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Latest Posts</h2>
                <p class="text-xl text-gray-600">Stay updated with our latest content</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($latestPosts->take(3) as $post)
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
                        <a href="#" class="text-blue-600 font-semibold hover:text-blue-800 transition">
                            Read More →
                        </a>
                    </div>
                </article>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('filament.admin.pages.dashboard') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    Go to Admin Panel
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-6">Ready to Get Started?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Start building amazing applications with Core Framework today
            </p>
            <a href="{{ route('setup.index') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Start Setup
            </a>
        </div>
    </section>
</div>
@endsection
