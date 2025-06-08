{{--
    🎨 Hero Banner Slider Component - Core Framework
    Clean version without syntax errors
--}}

@php
    use Illuminate\Support\Facades\Storage;
    use App\Models\Slider;

    $activeSliders = isset($sliders) && !empty($sliders) ? $sliders : collect();

    if ($activeSliders->isEmpty()) {
        $activeSliders = Slider::where('status', 'active')->orderBy('order')->get();
    }
@endphp

{{-- Preload all slider images for better performance --}}
@foreach($activeSliders as $slider)
    @if($slider->image_link)
        <link rel="preload" as="image" href="{{ Storage::url($slider->image_link) }}">
    @endif
@endforeach

{{-- Skeleton Loading - Elegant & Smart --}}
<div id="hero-skeleton" class="relative overflow-hidden bg-gradient-to-br from-gray-50 via-gray-100 to-gray-50 h-[300px] sm:h-[350px] md:h-[450px] lg:h-[500px] xl:h-[550px] rounded-b-2xl">
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="text-center space-y-4">
            <!-- Logo placeholder -->
            <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-red-200 to-red-300 animate-pulse flex items-center justify-center">
                <div class="w-8 h-8 bg-white/50 rounded-lg"></div>
            </div>
            <!-- Title placeholder -->
            <div class="space-y-2">
                <div class="h-4 w-48 mx-auto rounded-full bg-gradient-to-r from-gray-200 to-gray-300 animate-pulse"></div>
                <div class="h-3 w-32 mx-auto rounded-full bg-gradient-to-r from-gray-200 to-gray-300 animate-pulse"></div>
            </div>
            <!-- Button placeholder -->
            <div class="h-8 w-24 mx-auto rounded-lg bg-gradient-to-r from-red-200 to-red-300 animate-pulse"></div>
        </div>
    </div>
    <!-- Decorative elements -->
    <div class="absolute top-4 left-4 w-2 h-2 bg-red-200 rounded-full animate-pulse"></div>
    <div class="absolute top-8 right-6 w-1 h-1 bg-red-300 rounded-full animate-pulse"></div>
    <div class="absolute bottom-6 left-8 w-1.5 h-1.5 bg-red-200 rounded-full animate-pulse"></div>
</div>

{{-- Main Hero Banner - Simplified without Alpine.js --}}
@if($activeSliders->count() > 0)
<section class="hero-banner-section relative overflow-hidden">

    <div class="relative hero-carousel">

        {{-- Mobile Slides - Elegant Height --}}
        <div class="md:hidden overflow-hidden relative h-[300px] sm:h-[350px] rounded-b-2xl shadow-lg">
            @foreach($activeSliders as $index => $slider)
                <div class="absolute inset-0 {{ $index === 0 ? 'block' : 'hidden' }}" data-slide="{{ $index }}">

                    {{-- 🖼️ Smart Image Container - Giữ ảnh toàn vẹn --}}
                    @if($slider->image_link)
                        <div class="hero-image-container w-full h-full overflow-hidden rounded-b-xl"
                             data-image-url="{{ Storage::url($slider->image_link) }}">
                            {{-- Ảnh chính với object-contain để giữ toàn vẹn --}}
                            <img src="{{ Storage::url($slider->image_link) }}"
                                 alt="{{ $slider->alt_text ?: ($slider->title ? $slider->title . ' - Core Framework' : 'Banner Core Framework') }}"
                                 class="hero-image-main w-full h-full object-contain relative z-20"
                                 loading="eager"
                                 onload="this.style.opacity='1'; handleImageAspectRatio(this)"
                                 onerror="this.style.display='none'"
                                 style="opacity: 1; transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease;">

                            {{-- Dynamic color background sẽ được tạo bởi JavaScript --}}
                            <div class="hero-color-background absolute inset-0 z-10 transition-all duration-1000"></div>

                            {{-- Refined subtle gradient overlay --}}
                            <div class="absolute inset-0 z-30 bg-gradient-to-t from-black/15 via-transparent to-black/8"></div>
                        </div>
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-20 h-20 mx-auto mb-4 bg-white/20 rounded-2xl flex items-center justify-center">
                                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                                    </svg>
                                </div>
                                <span class="text-white text-xl font-bold">{{ $slider->title ?? 'Core Framework' }}</span>
                                <p class="text-white/80 text-sm mt-2">Khóa học chuyên nghiệp</p>
                            </div>
                        </div>
                    @endif

                    {{-- Content Overlay - Góc phải dưới, nhỏ gọn & xuyên thấu --}}
                    @if($slider->title || $slider->description)
                    <div class="absolute bottom-3 right-3 z-40 max-w-xs">
                        <div class="bg-black/20 backdrop-blur-sm rounded-lg p-3 border border-white/10 shadow-lg">
                            @if($slider->title)
                                <h2 class="text-white text-xs font-semibold mb-1 leading-tight line-clamp-1 drop-shadow-sm">{{ $slider->title }}</h2>
                            @endif

                            @if($slider->description)
                                <p class="text-white/90 text-xs mb-2 leading-snug line-clamp-1 drop-shadow-sm">{{ $slider->description }}</p>
                            @endif

                            @if($slider->link)
                                <a href="{{ $slider->link }}"
                                   class="inline-flex items-center bg-white/20 hover:bg-white/30 text-white px-2 py-1 text-xs font-medium rounded-md transition-all duration-300 backdrop-blur-sm border border-white/20">
                                    <span>Chi tiết</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Desktop Slides - Premium Height --}}
        <div class="hidden md:block overflow-hidden relative h-[450px] lg:h-[500px] xl:h-[550px] rounded-b-2xl shadow-xl">
            @foreach($activeSliders as $index => $slider)
                <div class="absolute inset-0 {{ $index === 0 ? 'block' : 'hidden' }}" data-slide="{{ $index }}">

                    {{-- 🖼️ Desktop Smart Image Container - Giữ ảnh toàn vẹn --}}
                    @if($slider->image_link)
                        <div class="hero-image-container w-full h-full overflow-hidden rounded-b-xl"
                             data-image-url="{{ Storage::url($slider->image_link) }}">
                            {{-- Ảnh chính với object-cover để fill container --}}
                            <img src="{{ Storage::url($slider->image_link) }}"
                                 alt="{{ $slider->alt_text ?: ($slider->title ? $slider->title . ' - Core Framework' : 'Banner Core Framework') }}"
                                 class="hero-image-main w-full h-full object-contain relative z-20"
                                 loading="eager"
                                 onload="this.style.opacity='1'; handleImageAspectRatio(this)"
                                 onerror="this.style.display='none'"
                                 style="opacity: 1; transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease;">

                            {{-- Dynamic color background sẽ được tạo bởi JavaScript --}}
                            <div class="hero-color-background absolute inset-0 z-10 transition-all duration-1000"></div>

                            {{-- Refined subtle gradient overlay --}}
                            <div class="absolute inset-0 z-30 bg-gradient-to-r from-black/12 via-transparent to-black/12"></div>
                        </div>
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-24 h-24 mx-auto mb-6 bg-white/20 rounded-3xl flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                                    </svg>
                                </div>
                                <span class="text-white text-3xl font-bold">{{ $slider->title ?? 'Core Framework' }}</span>
                                <p class="text-white/80 text-xl mt-3">Khóa học chuyên nghiệp</p>
                            </div>
                        </div>
                    @endif

                    {{-- Content Overlay - Desktop góc phải dưới, nhỏ gọn & xuyên thấu --}}
                    @if($slider->title || $slider->description)
                    <div class="absolute bottom-4 right-4 z-40 max-w-sm">
                        <div class="bg-black/25 backdrop-blur-md rounded-xl p-4 border border-white/15 shadow-xl">
                            @if($slider->title)
                                <h1 class="text-white text-sm lg:text-base font-bold mb-2 leading-tight line-clamp-1 drop-shadow-md">{{ $slider->title }}</h1>
                            @endif

                            @if($slider->description)
                                <p class="text-white/95 text-xs lg:text-sm mb-3 leading-relaxed line-clamp-2 drop-shadow-sm">{{ $slider->description }}</p>
                            @endif

                            @if($slider->link)
                                <a href="{{ $slider->link }}"
                                   class="inline-flex items-center bg-white/25 hover:bg-white/35 text-white px-3 py-1.5 lg:px-4 lg:py-2 text-xs lg:text-sm font-semibold rounded-lg transition-all duration-300 backdrop-blur-sm border border-white/25 hover:border-white/40">
                                    <span>Chi tiết</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 lg:h-4 lg:w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Navigation Controls - Premium Design với tối ưu vị trí --}}
        @if($activeSliders->count() > 1)
        <div class="absolute inset-x-0 top-1/2 transform -translate-y-1/2 flex items-center justify-between px-4 md:px-6 z-50 pointer-events-none">
            <button onclick="prevSlide()" class="group p-2.5 md:p-3 rounded-xl md:rounded-2xl bg-white/85 backdrop-blur-md text-gray-700 border border-white/40 shadow-lg transition-all duration-300 hover:bg-white hover:scale-105 hover:shadow-xl pointer-events-auto" aria-label="Slide trước">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 transition-transform duration-300 group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button onclick="nextSlide()" class="group p-2.5 md:p-3 rounded-xl md:rounded-2xl bg-white/85 backdrop-blur-md text-gray-700 border border-white/40 shadow-lg transition-all duration-300 hover:bg-white hover:scale-105 hover:shadow-xl pointer-events-auto" aria-label="Slide tiếp theo">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        {{-- Indicators - Modern Design - Đặt ở góc dưới bên trái để không đè lên content --}}
        <div class="absolute bottom-4 left-4 z-50">
            <div class="flex items-center gap-2 bg-black/20 backdrop-blur-sm rounded-full px-3 py-2 border border-white/10">
                @foreach($activeSliders as $index => $slider)
                    <button onclick="goToSlide({{ $index }})" class="transition-all duration-300 focus:outline-none {{ $index === 0 ? 'w-6 h-2 bg-white rounded-full shadow-lg' : 'w-2 h-2 bg-white/60 hover:bg-white/80 rounded-full' }}" data-indicator="{{ $index }}" aria-label="Đi đến slide {{ $index + 1 }}">
                    </button>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endif

<style>
    /* 🎨 Tối ưu Hero Carousel với tone đỏ-trắng minimalist */
    .hero-carousel {
        background: linear-gradient(135deg, #fafafa 0%, #f3f4f6 50%, #fafafa 100%);
        position: relative;
    }

    /* � Text utilities cho minimalist design */
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

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

    /* 🖼️ Smart Image Display - Elegant & Intelligent Design */
    .hero-image-container {
        position: relative;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #fafafa 0%, #f8fafc 50%, #f1f5f9 100%);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0 0 1rem 1rem;
    }

    .hero-image-main {
        width: 100%;
        height: 100%;
        object-fit: contain !important;
        object-position: center;
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: opacity, transform;
        filter: contrast(1.05) saturate(1.1);
    }

    .hero-color-background {
        background: linear-gradient(135deg, #fafafa 0%, #f3f4f6 100%);
        transition: background 0.8s ease-out;
    }

    /* 🌙 Dark mode support */
    .dark .hero-image-container,
    .dark .hero-color-background {
        background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
    }

    /* 📱 Responsive Design - Elegant Heights & Smart Interactions */
    @media (max-width: 767px) {
        .hero-image-main {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .hero-image-container {
            min-height: 300px;
            border-radius: 0 0 1rem 1rem;
        }

        .hero-banner-section {
            margin-bottom: 1rem;
        }
    }

    @media (min-width: 768px) {
        .hero-image-main {
            object-fit: contain;
        }

        .hero-image-main:hover {
            transform: scale(1.03) translateY(-2px);
            filter: contrast(1.08) saturate(1.15) brightness(1.02);
        }

        .hero-image-container {
            min-height: 450px;
            border-radius: 0 0 1.5rem 1.5rem;
        }

        .hero-banner-section {
            margin-bottom: 1.5rem;
        }
    }

    @media (min-width: 1024px) {
        .hero-image-container {
            min-height: 500px;
        }
    }

    @media (min-width: 1280px) {
        .hero-image-container {
            min-height: 550px;
            border-radius: 0 0 2rem 2rem;
        }

        .hero-banner-section {
            margin-bottom: 2rem;
        }
    }

    /* 🎭 Loading animation */
    .hero-image-main[style*="opacity: 0"] {
        animation: imageLoad 0.5s ease-out forwards;
    }

    @keyframes imageLoad {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* 🎨 Enhanced gradient overlays */
    .hero-gradient-overlay {
        background: linear-gradient(
            45deg,
            rgba(0, 0, 0, 0.1) 0%,
            transparent 30%,
            transparent 70%,
            rgba(0, 0, 0, 0.1) 100%
        );
    }

    /* 🔄 Smooth transitions */
    .hero-slide-transition {
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* 🎯 Special handling cho ảnh có tỷ lệ đặc biệt */
    .hero-image-main.loaded {
        opacity: 1 !important;
    }

    /* Xử lý ảnh rất rộng (panorama) - Scale to fit KHÔNG cắt */
    .hero-image-container.wide-image .hero-image-main {
        width: 100%;
        height: 100%;
        object-fit: contain; /* Scale ảnh panorama fit container, KHÔNG cắt */
    }

    /* Xử lý ảnh rất cao (portrait) - Scale to fit KHÔNG cắt */
    .hero-image-container.tall-image .hero-image-main {
        width: 100%;
        height: 100%;
        object-fit: contain; /* Scale ảnh portrait fit container, KHÔNG cắt */
    }

    /* 🚫 Error state styling với tone đỏ minimalist */
    .hero-image-container.image-error {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    }

    /* 🎨 Performance optimized styles */
    .hero-image-main.loaded {
        opacity: 1 !important;
        transform: translateZ(0); /* GPU acceleration */
    }

    /* 🌟 Loading state với color background */
    .hero-color-background.loading {
        animation: colorPulse 2s ease-in-out infinite;
    }

    @keyframes colorPulse {
        0%, 100% { opacity: 0.8; }
        50% { opacity: 1; }
    }

    /* 🎨 Elegant Section Styling */
    .hero-banner-section {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .hero-banner-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, rgba(239, 68, 68, 0.3) 50%, transparent 100%);
        z-index: 10;
    }

    .hero-banner-section::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent 0%, rgba(239, 68, 68, 0.2) 20%, rgba(239, 68, 68, 0.4) 50%, rgba(239, 68, 68, 0.2) 80%, transparent 100%);
        border-radius: 0 0 1rem 1rem;
        z-index: 10;
    }

    /* 🌟 Enhanced Hover Effects */
    .hero-image-container:hover .hero-image-main {
        filter: contrast(1.08) saturate(1.15) brightness(1.02);
    }

    .hero-image-container:hover .hero-color-background {
        opacity: 0.8 !important;
        transform: scale(1.02);
    }

    /* 📱 Mobile Optimizations */
    @media (max-width: 767px) {
        .hero-banner-section::after {
            height: 1px;
        }
    }

    /* 🎨 Content Overlay Enhancements - Góc phải dưới */
    .hero-banner-section .absolute.bottom-3,
    .hero-banner-section .absolute.bottom-4 {
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    .hero-banner-section .absolute.bottom-3 h2,
    .hero-banner-section .absolute.bottom-4 h1 {
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        font-weight: 700;
    }

    .hero-banner-section .absolute.bottom-3 p,
    .hero-banner-section .absolute.bottom-4 p {
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    .hero-banner-section .absolute.bottom-3 a,
    .hero-banner-section .absolute.bottom-4 a {
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .hero-banner-section .absolute.bottom-3 a:hover,
    .hero-banner-section .absolute.bottom-4 a:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }

    /* 🎯 Navigation Controls Optimization - Tránh đè lên nội dung */
    .hero-banner-section .absolute.bottom-4.left-4 {
        /* Indicators container - góc dưới trái */
        z-index: 60;
        pointer-events: auto;
    }

    .hero-banner-section .absolute.inset-x-0.top-1\/2 {
        /* Navigation arrows - giữa màn hình */
        z-index: 55;
        pointer-events: none; /* Chỉ cho phép click vào buttons */
    }

    .hero-banner-section .absolute.inset-x-0.top-1\/2 button {
        pointer-events: auto;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }

    /* 📱 Mobile Navigation Adjustments */
    @media (max-width: 767px) {
        .hero-banner-section .absolute.inset-x-0.top-1\/2 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .hero-banner-section .absolute.inset-x-0.top-1\/2 button {
            padding: 0.5rem;
        }

        .hero-banner-section .absolute.bottom-4.left-4 {
            bottom: 0.75rem;
            left: 0.75rem;
        }
    }

    /* 🎨 Enhanced Navigation Button Styles */
    .hero-banner-section button[onclick*="Slide"] {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform, background-color, box-shadow;
    }

    .hero-banner-section button[onclick*="Slide"]:hover {
        transform: scale(1.05);
        background-color: rgba(255, 255, 255, 0.95);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .hero-banner-section button[onclick*="Slide"]:active {
        transform: scale(0.98);
        transition-duration: 0.1s;
    }

    /* 🎯 Indicators Container Styling */
    .hero-banner-section .absolute.bottom-4.left-4 > div {
        transition: all 0.3s ease;
        will-change: transform, opacity;
    }

    .hero-banner-section .absolute.bottom-4.left-4:hover > div {
        transform: scale(1.05);
        background-color: rgba(0, 0, 0, 0.3);
    }

    /* 🌟 Smooth Indicator Transitions */
    .hero-banner-section button[data-indicator] {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: width, height, background-color;
    }

    .hero-banner-section button[data-indicator]:hover {
        background-color: rgba(255, 255, 255, 0.9) !important;
        transform: scale(1.1);
    }
</style>

<script>
// Simple Hero Slider without Alpine.js
let currentSlide = 0;
let totalSlides = {{ $activeSliders->count() }};
let sliderInterval = null;

function initHeroSlider() {
    // Hide skeleton after load
    setTimeout(() => {
        const skeleton = document.getElementById('hero-skeleton');
        if (skeleton) skeleton.style.display = 'none';
    }, 800);

    // Show first slide explicitly
    if (totalSlides > 0) {
        showSlide(0);
    }

    // Auto-slide if multiple slides
    if (totalSlides > 1) {
        sliderInterval = setInterval(() => nextSlide(), 6000);
    }
}

function showSlide(index) {
    // Hide all slides
    document.querySelectorAll('[data-slide]').forEach(slide => {
        slide.classList.add('hidden');
        slide.classList.remove('block');
    });

    // Show current slide
    document.querySelectorAll(`[data-slide="${index}"]`).forEach(slide => {
        slide.classList.remove('hidden');
        slide.classList.add('block');
    });

    // Update indicators with modern design - compact style
    document.querySelectorAll('[data-indicator]').forEach((indicator, i) => {
        if (i === index) {
            indicator.className = 'w-6 h-2 bg-white rounded-full shadow-lg transition-all duration-300 focus:outline-none';
        } else {
            indicator.className = 'w-2 h-2 bg-white/60 hover:bg-white/80 rounded-full transition-all duration-300 focus:outline-none';
        }
    });
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
}

function prevSlide() {
    currentSlide = (currentSlide + totalSlides - 1) % totalSlides;
    showSlide(currentSlide);
}

function goToSlide(index) {
    currentSlide = index;
    showSlide(currentSlide);

    // Reset auto-slide timer
    if (sliderInterval) {
        clearInterval(sliderInterval);
        sliderInterval = setInterval(() => nextSlide(), 6000);
    }
}

// Handle image aspect ratio - Scale to fit màn hình KHÔNG cắt ảnh
function handleImageAspectRatio(img) {
    const container = img.closest('.hero-image-container');
    if (!container) return;

    const containerWidth = container.offsetWidth;
    const containerHeight = container.offsetHeight;
    const containerRatio = containerWidth / containerHeight;

    const imageRatio = img.naturalWidth / img.naturalHeight;

    // Luôn sử dụng contain để hiển thị TOÀN BỘ ảnh, không cắt
    // object-fit: contain sẽ scale ảnh lên tối đa có thể mà vẫn giữ toàn bộ ảnh
    // Phần trống sẽ được fill bằng background color từ ảnh
    img.style.objectFit = 'contain';
    img.style.objectPosition = 'center';

    console.log(`📐 Image ${img.naturalWidth}x${img.naturalHeight} (ratio: ${imageRatio.toFixed(2)}) scaled to fit ${containerWidth}x${containerHeight} (ratio: ${containerRatio.toFixed(2)}) - NO CROP`);
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initHeroSlider);

// 🎨 Color Extraction Function - Tạo background từ màu ảnh
function extractColorsFromImage(img) {
    return new Promise((resolve) => {
        try {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            // Resize canvas nhỏ để tăng performance
            canvas.width = 50;
            canvas.height = 50;

            // Vẽ ảnh lên canvas
            ctx.drawImage(img, 0, 0, 50, 50);

            // Lấy pixel data
            const imageData = ctx.getImageData(0, 0, 50, 50);
            const data = imageData.data;

            // Tính màu trung bình
            let r = 0, g = 0, b = 0;
            const pixelCount = data.length / 4;

            for (let i = 0; i < data.length; i += 4) {
                r += data[i];
                g += data[i + 1];
                b += data[i + 2];
            }

            r = Math.floor(r / pixelCount);
            g = Math.floor(g / pixelCount);
            b = Math.floor(b / pixelCount);

            // Tạo màu sáng và tối hơn cho gradient
            const lighter = {
                r: Math.min(255, r + 30),
                g: Math.min(255, g + 30),
                b: Math.min(255, b + 30)
            };

            const darker = {
                r: Math.max(0, r - 30),
                g: Math.max(0, g - 30),
                b: Math.max(0, b - 30)
            };

            resolve({
                primary: `rgb(${r}, ${g}, ${b})`,
                lighter: `rgb(${lighter.r}, ${lighter.g}, ${lighter.b})`,
                darker: `rgb(${darker.r}, ${darker.g}, ${darker.b})`
            });

        } catch (error) {
            console.warn('Color extraction failed:', error);
            resolve({
                primary: '#f3f4f6',
                lighter: '#f9fafb',
                darker: '#e5e7eb'
            });
        }
    });
}

// 🖼️ Enhanced Image Handler với Color Extraction
function handleImageLoadWithColorExtraction(img) {
    // Fade in effect khi ảnh load xong
    img.style.opacity = '1';
    img.classList.add('loaded');

    // 🎯 Phân tích tỷ lệ ảnh
    const aspectRatio = img.naturalWidth / img.naturalHeight;
    const container = img.closest('.hero-image-container');

    if (container) {
        // Xóa loading state
        const colorBg = container.querySelector('.hero-color-background');
        if (colorBg) {
            colorBg.classList.remove('loading');
        }

        // 🎨 Extract colors và apply background
        extractColorsFromImage(img).then(colors => {
            if (colorBg) {
                const gradient = `linear-gradient(135deg, ${colors.lighter} 0%, ${colors.primary} 50%, ${colors.darker} 100%)`;
                colorBg.style.background = gradient;

                // Thêm subtle animation
                colorBg.style.opacity = '0.9';
            }

            console.log(`🎨 Colors extracted:`, colors);
        });

        // Áp dụng class dựa trên tỷ lệ ảnh
        container.classList.remove('wide-image', 'tall-image', 'square-image');
        if (aspectRatio > 2.5) {
            container.classList.add('wide-image');
        } else if (aspectRatio < 0.6) {
            container.classList.add('tall-image');
        } else {
            container.classList.add('square-image');
        }
    }

    console.log(`✅ Image loaded: ${img.naturalWidth}x${img.naturalHeight} (ratio: ${aspectRatio.toFixed(2)})`);
}

function handleImageError(img) {
    // Ẩn ảnh bị lỗi và hiển thị fallback
    img.style.display = 'none';

    // Tìm container và thêm class error
    const container = img.closest('.hero-image-container');
    if (container) {
        container.classList.add('image-error');

        // Tạo fallback content với tone đỏ minimalist
        const fallback = document.createElement('div');
        fallback.className = 'w-full h-full bg-gradient-to-br from-red-600 to-red-700 flex items-center justify-center';
        fallback.innerHTML = `
            <div class="text-center">
                <div class="w-20 h-20 mx-auto mb-4 bg-white/20 rounded-2xl flex items-center justify-center">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-white text-lg font-semibold">Không thể tải ảnh</span>
                <p class="text-white/80 text-sm mt-1">Core Framework</p>
            </div>
        `;
        container.appendChild(fallback);
    }
}

// 🎯 Enhanced Image Optimization với Color Extraction
document.addEventListener('DOMContentLoaded', function() {
    // Tối ưu tất cả ảnh hero
    const heroImages = document.querySelectorAll('.hero-image-main');

    heroImages.forEach(img => {
        // Thêm loading state cho color background
        const container = img.closest('.hero-image-container');
        const colorBg = container?.querySelector('.hero-color-background');
        if (colorBg) {
            colorBg.classList.add('loading');
        }

        // Nếu ảnh đã load
        if (img.complete && img.naturalHeight !== 0) {
            handleImageLoadWithColorExtraction(img);
        } else {
            // Thêm event listeners
            img.addEventListener('load', () => handleImageLoadWithColorExtraction(img));
            img.addEventListener('error', () => handleImageError(img));
        }
    });

    // Preload ảnh đầu tiên để cải thiện LCP
    const firstImage = document.querySelector('.hero-image-main');
    if (firstImage && firstImage.src) {
        const link = document.createElement('link');
        link.rel = 'preload';
        link.as = 'image';
        link.href = firstImage.src;
        document.head.appendChild(link);
    }

    // Tối ưu performance: Giảm animation nếu user prefer reduced motion
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        const style = document.createElement('style');
        style.textContent = `
            .hero-image-main, .hero-color-background {
                transition: none !important;
                animation: none !important;
            }
        `;
        document.head.appendChild(style);
    }
});
</script>
