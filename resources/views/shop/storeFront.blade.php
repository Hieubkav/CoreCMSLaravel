@extends('layouts.shop')

@section('content')
<div class="min-h-screen">
    <!-- Modern Hero Section -->
    <section class="relative overflow-hidden py-24 lg:py-32">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-indigo-50 to-slate-100"></div>
        <div class="relative container mx-auto px-6 text-center">
            <div class="max-w-4xl mx-auto">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-3xl flex items-center justify-center mx-auto mb-8">
                    <span class="text-4xl">🚀</span>
                </div>
                <h1 class="text-6xl lg:text-7xl font-light text-slate-800 mb-8 tracking-tight">
                    Welcome to <span class="font-semibold text-blue-600">Core Framework</span>
                </h1>
                <p class="text-xl lg:text-2xl text-slate-600 mb-12 max-w-3xl mx-auto leading-relaxed">
                    A powerful, flexible Laravel-based framework for building modern web applications
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#features" class="inline-flex items-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-2xl hover:bg-blue-700 transition-all duration-200">
                        Get Started
                    </a>
                    <a href="/admin" class="inline-flex items-center px-8 py-4 bg-slate-100 text-slate-700 font-semibold rounded-2xl hover:bg-slate-200 transition-all duration-200 border border-slate-200">
                        Admin Panel
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Modern Features Section -->
    <section id="features" class="py-24 lg:py-32">
        <div class="container mx-auto px-6">
            <div class="text-center mb-20">
                <h2 class="text-5xl font-light text-slate-800 mb-6 tracking-tight">Core Features</h2>
                <p class="text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
                    Everything you need to build modern web applications
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                <div class="bg-white p-10 rounded-3xl shadow-lg text-center border border-slate-100 group hover:shadow-xl transition-all duration-300">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="text-blue-600 text-2xl">🚀</span>
                    </div>
                    <h3 class="text-2xl font-semibold text-slate-800 mb-4">Fast & Modern</h3>
                    <p class="text-slate-600 leading-relaxed">Built with Laravel and modern PHP practices for optimal performance</p>
                </div>

                <div class="bg-white p-10 rounded-3xl shadow-lg text-center border border-slate-100 group hover:shadow-xl transition-all duration-300">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-100 to-green-100 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="text-emerald-600 text-2xl">⚙️</span>
                    </div>
                    <h3 class="text-2xl font-semibold text-slate-800 mb-4">Highly Configurable</h3>
                    <p class="text-slate-600 leading-relaxed">Easily customize and extend to fit your specific needs</p>
                </div>

                <div class="bg-white p-10 rounded-3xl shadow-lg text-center border border-slate-100 group hover:shadow-xl transition-all duration-300">
                    <div class="w-20 h-20 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="text-indigo-600 text-2xl">🛡️</span>
                    </div>
                    <h3 class="text-2xl font-semibold text-slate-800 mb-4">Secure & Reliable</h3>
                    <p class="text-slate-600 leading-relaxed">Built-in security features and best practices</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Section -->
    @if(class_exists('App\Models\Service'))
        <x-service-section
            title="Dịch vụ nổi bật"
            subtitle="Chúng tôi cung cấp các dịch vụ chuyên nghiệp với chất lượng cao"
            :limit="6" />
    @endif

    <!-- Staff Section -->
    @if(class_exists('App\Models\Staff'))
        <x-staff-section
            title="Đội ngũ chuyên gia"
            subtitle="Gặp gỡ những chuyên gia tài năng của chúng tôi"
            :limit="4" />
    @endif

    <!-- Blog Section -->
    @if(class_exists('App\Models\Post') && file_exists(resource_path('views/components/blog-section.blade.php')))
        <x-blog-section :order="1" />
    @endif

    <!-- Modern CTA Section -->
    <section class="py-24 lg:py-32 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-indigo-50 to-slate-100"></div>
        <div class="relative container mx-auto px-6 text-center">
            <div class="max-w-4xl mx-auto">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-soft">
                    <i class="fas fa-play text-blue-600 text-2xl"></i>
                </div>
                <h2 class="text-5xl font-light text-slate-800 mb-8 tracking-tight">Ready to Get Started?</h2>
                <p class="text-xl text-slate-600 mb-12 max-w-3xl mx-auto leading-relaxed">
                    Start building amazing applications with Core Framework today
                </p>
                <a href="{{ route('setup.index') }}" class="inline-flex items-center px-10 py-4 bg-blue-600 text-white font-semibold rounded-2xl hover:bg-blue-700 transition-all duration-200 shadow-soft hover:shadow-hover transform hover:-translate-y-0.5">
                    <i class="fas fa-rocket mr-3"></i>
                    Start Setup
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
