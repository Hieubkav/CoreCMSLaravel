<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">

    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="description" content="{{ $description ?? "Core Framework - Build Better Applications" }}">
    <meta name="keywords" content="Core Framework, Laravel, PHP, Web Development">
    <meta name="robots" content="all">
    <meta property="og:title" content="@yield('title', isset($settings) && $settings ? $settings->site_name : 'Core Framework')">
    <meta property="og:description" content="@yield('description', isset($settings) && $settings ? $settings->seo_description : 'Core Framework - Build Better Applications')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ isset($settings) && $settings && $settings->og_image_link ? asset('storage/' . $settings->og_image_link) : asset('images/logo.png') }}">
    <!-- Default JSON-LD Structured Data -->
    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "{{ isset($settings) && $settings ? $settings->site_name : 'Core Framework' }}",
  "description": "{{ isset($settings) && $settings ? $settings->seo_description : 'Core Framework - Build Better Applications' }}",
  "url": "{{ url('/') }}"
}
</script>
    <meta name="revisit-after" content="1 day">
    <meta name="HandheldFriendly" content="true">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="author" content="Manh Hieu">
    <meta name="theme-color" content="#b91c1c">

    <!-- KISS: Không preload để tránh lỗi 404 -->

    <!-- Preconnects & DNS prefetch -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <!-- Critical CSS inline -->
    @if(function_exists('criticalCss'))
        {!! criticalCss() !!}
    @else
        <!-- Fallback critical CSS -->
        <style>
            body{font-family:ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif}
            .container{max-width:1200px;margin:0 auto;padding:0 1rem}
            .btn{display:inline-flex;align-items:center;padding:0.5rem 1rem;border-radius:0.375rem;font-weight:500;transition:all 0.2s}
            .btn-primary{background-color:#3b82f6;color:white}
            .btn-primary:hover{background-color:#2563eb}
        </style>
    @endif

    <!-- Font Awesome CDN - Production ready -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Alpine.js CDN - For interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome Fallback CSS -->
    <style>
        /* Ensure Font Awesome icons display correctly */
        .fa, .fas, .far, .fab, .fal, .fad {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "Font Awesome 6 Brands", "FontAwesome" !important;
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            display: inline-block;
        }

        .fas { font-weight: 900; }
        .far { font-weight: 400; }
        .fab {
            font-family: "Font Awesome 6 Brands" !important;
            font-weight: 400;
        }

        /* Fallback for missing icons */
        .fa:before, .fas:before, .far:before, .fab:before {
            content: "\f03e"; /* fa-image as fallback */
        }

        /* Specific icons used in fallbacks */
        .fa-graduation-cap:before { content: "\f19d"; }
        .fa-newspaper:before { content: "\f1ea"; }
        .fa-handshake:before { content: "\f2b5"; }
        .fa-images:before { content: "\f302"; }
        .fa-user:before { content: "\f007"; }
        .fa-folder:before { content: "\f07b"; }
        .fa-chalkboard-teacher:before { content: "\f51c"; }
        .fa-image:before { content: "\f03e"; }
    </style>

    <!-- Modern Fonts -->
    @if(function_exists('deferNonCriticalCss'))
        {!! deferNonCriticalCss('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap') !!}
        {!! deferNonCriticalCss('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css') !!}
    @else
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
    @endif

    <!-- Favicon -->
    <link rel="icon" href="{{ isset($settings) && $settings && $settings->favicon_link ? asset('storage/' . $settings->favicon_link) : asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ isset($settings) && $settings && $settings->favicon_link ? asset('storage/' . $settings->favicon_link) : asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ isset($settings) && $settings && $settings->favicon_link ? asset('storage/' . $settings->favicon_link) : asset('images/favicon.ico') }}">

    <title>{{ $title ?? "Core Framework" }}</title>

    <!-- Structured Data -->
    @if(isset($seoData) && isset($seoData['structuredData']))
    <script type="application/ld+json">
    {!! json_encode($seoData['structuredData'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @endif

    <style>
        :root {
            /* Modern color palette với tone xanh dương-trắng-xám minimalist */
            --primary: #2563eb;
            --primary-light: #3b82f6;
            --primary-dark: #1d4ed8;
            --primary-darker: #1e40af;
            --secondary: #1e293b;
            --light: #fafafa;
            --gray-25: #fafafa;
            --gray-light: #f1f5f9;
            --blue-25: #f8fafc;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;

            /* Spacing scale tối ưu */
            --section-padding-sm: 1.5rem;
            --section-padding-md: 2rem;
            --section-padding-lg: 3rem;
            --container-padding: 1rem;
        }

        [x-cloak] {
            display: none !important;
        }

        html {
            scroll-behavior: smooth;
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            color: #475569;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        h1, h2, h3, h4, h5, h6, .heading {
            font-family: 'Inter', system-ui, sans-serif;
            color: #1e293b;
        }

        .glass-card {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.8);
        }

        .shadow-soft {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .shadow-hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .section-transition {
            transition: all 0.5s ease-in-out;
        }

        .section-transition:hover {
            transform: translateY(-5px);
        }

        .text-primary {
            color: var(--primary);
        }

        .bg-primary {
            background-color: var(--primary);
        }

        .transition-transform {
            transition-property: transform;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }

        .hover-up:hover {
            transform: translateY(-5px);
        }

        .page-container {
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
            padding-left: var(--container-padding);
            padding-right: var(--container-padding);
        }

        /* Tối ưu animations với performance cao */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        /* Responsive container padding */
        @media (min-width: 640px) {
            .page-container {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }

        @media (min-width: 1024px) {
            .page-container {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }

        /* Enhanced Product Card Animations */
        .product-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            @apply glass-card rounded-2xl shadow-soft border border-slate-100;
        }

        .product-card:hover {
            transform: translateY(-8px);
            @apply shadow-hover border-slate-200;
        }

        .product-image {
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-card:hover .product-image {
            transform: scale(1.1);
        }

        /* Gradient Animations */
        .gradient-animate {
            background-size: 200% 200%;
            animation: gradientShift 3s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Badge Animations */
        .badge-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .8; }
        }

        /* Loading Skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Aspect Ratio Utilities */
        .aspect-w-1 {
            position: relative;
            padding-bottom: calc(var(--tw-aspect-h) / var(--tw-aspect-w) * 100%);
            --tw-aspect-w: 1;
        }

        .aspect-h-1 {
            --tw-aspect-h: 1;
        }

        .aspect-w-1 > * {
            position: absolute;
            height: 100%;
            width: 100%;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        /* Line Clamp Utilities */
        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .line-clamp-3 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
        }

        /* Prose Styles */
        .prose {
            color: #374151;
            max-width: none;
        }

        .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
            color: #111827;
            font-weight: 600;
            font-family: 'Montserrat', system-ui, sans-serif;
        }

        .prose p {
            margin-top: 1.25em;
            margin-bottom: 1.25em;
        }

        .prose-red a {
            color: var(--primary);
        }

        .prose-red a:hover {
            color: var(--primary-dark);
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }

        .pagination .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination .page-link:hover {
            background-color: #f3f4f6;
            border-color: var(--primary);
            color: var(--primary);
        }

        .pagination .page-link.active {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .pagination .page-link.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Filter Sidebar */
        .filter-sidebar-desktop {
            position: static !important;
            width: auto !important;
            height: auto !important;
            background: transparent !important;
            z-index: auto !important;
            transition: none !important;
            overflow-y: visible !important;
        }

        /* Mobile Filter Toggle */
        @media (max-width: 1023px) {
            .filter-sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100vh;
                background: white;
                z-index: 50;
                transition: left 0.3s ease;
                overflow-y: auto;
            }

            .filter-sidebar.active {
                left: 0;
            }

            .filter-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                z-index: 40;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }

            .filter-overlay.active {
                opacity: 1;
                visibility: visible;
            }
        }

        @media (min-width: 1024px) {
            .filter-sidebar {
                position: static !important;
                width: auto !important;
                height: auto !important;
                background: transparent !important;
                z-index: auto !important;
                transition: none !important;
                overflow-y: visible !important;
                left: auto !important;
            }
        }
    </style>

    @filamentStyles
    @livewireStyles
    @vite('resources/css/app.css')

    <!-- Simple Storefront CSS - Clean & Minimal -->
    <link rel="stylesheet" href="{{ asset('css/simple-storefront.css') }}">

    @stack('styles')

</head>

<body class="antialiased min-h-screen flex flex-col">
    <!-- Preloader - Optimized -->
    <div id="page-preloader" class="fixed inset-0 bg-white z-50 flex items-center justify-center transition-opacity duration-500">
        <div class="loader-content flex flex-col items-center">
            @if(isset($settings) && $settings && $settings->logo_link)
                <img src="{{ asset('storage/' . $settings->logo_link) }}" alt="{{ isset($settings) && $settings ? $settings->site_name : config('app.name') }}" class="h-16 w-auto mb-4 animate-pulse" loading="eager">
            @elseif(isset($settings) && $settings && $settings->placeholder_image)
                <img src="{{ asset('storage/' . $settings->placeholder_image) }}" alt="{{ isset($settings) && $settings ? $settings->site_name : config('app.name') }}" class="h-16 w-auto mb-4 animate-pulse" loading="eager">
            @else
                <div class="h-16 w-16 mb-4 bg-red-600 rounded-lg flex items-center justify-center animate-pulse">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5z"/>
                    </svg>
                </div>
            @endif
            <div class="w-32 h-1 bg-gradient-to-r from-red-700 to-red-500 rounded-full animate-pulse"></div>
            <p class="text-sm text-gray-600 mt-2 animate-pulse">{{ isset($settings) && $settings ? $settings->site_name : 'Đang tải...' }}</p>
        </div>
    </div>

    <!-- Top Navigation Bar -->
    @include('components.public.subnav')

    <!-- Main Navigation -->
    @include('components.public.navbar-simple')

    <!-- Main Content -->
    <main class="flex-grow bg-white overflow-hidden">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.public.footer')

    <!-- Action Buttons -->
    @include('components.public.speedial')

    <!-- Toast Notifications -->
    <div id="toast-notifications"></div>

    <!-- Critical Scripts -->
    @filamentScripts
    @livewireScripts

    <!-- KISS: Không dùng Vite để tránh lỗi 404 -->
    @if(file_exists(public_path('build/assets/app-UxdMiINA.js')))
        <script src="{{ asset('build/assets/app-UxdMiINA.js') }}"></script>
    @endif
    @if(file_exists(public_path('build/assets/app-GEV_umWj.css')))
        <link rel="stylesheet" href="{{ asset('build/assets/app-GEV_umWj.css') }}">
    @endif

    {{-- KISS: Bỏ performance.css phức tạp gây conflict với lazy loading --}}

    <!-- 🎯 KISS: Cực kỳ đơn giản - chỉ ẩn ảnh lỗi -->
    <script>
    // KISS: Chỉ ẩn ảnh lỗi, không có fallback UI gì cả
    function handleImageError(img) {
        console.log('🖼️ Image error - hiding:', img.src);
        img.style.display = 'none';

        // Ẩn luôn container nếu cần
        const container = img.closest('.relative');
        if (container && container.children.length === 1) {
            container.style.display = 'none';
        }
    }
    </script>

    @stack('scripts')

    <script>
        // Optimized preloader - hide faster
        document.addEventListener('DOMContentLoaded', function() {
            const preloader = document.getElementById('page-preloader');
            if (preloader) {
                // Hide preloader immediately when DOM is ready
                preloader.style.opacity = 0;
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 300);
            }

            // KISS: Bỏ scroll animations phức tạp để tránh conflict với lazy loading
        });

        // Hide preloader on window load as fallback
        window.addEventListener('load', function() {
            const preloader = document.getElementById('page-preloader');
            if (preloader && preloader.style.display !== 'none') {
                preloader.style.opacity = 0;
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 200);
            }
        });
    </script>
</body>
</html>
