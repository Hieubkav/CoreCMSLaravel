{{--
    Simple Storefront Section Component
    Standardize layout cho tất cả sections
--}}

<section class="storefront-section {{ $bgColor }} {{ $animationClass }}">
    <div class="page-container py-8 md:py-12 lg:py-16">
        
        @if($title || $description)
            <!-- Section Header -->
            <div class="text-center mb-8 md:mb-12">
                @if($title)
                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-4 heading">
                        {{ $title }}
                    </h2>
                @endif
                
                @if($description)
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                        {{ $description }}
                    </p>
                @endif
            </div>
        @endif

        @if($hasData)
            <!-- Section Content -->
            <div class="section-content">
                {{ $slot }}
            </div>
        @else
            <!-- Empty State với tone đỏ-trắng -->
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 bg-red-50 rounded-full flex items-center justify-center">
                    <i class="{{ $emptyIcon }} text-2xl text-red-300"></i>
                </div>
                <p class="text-gray-500 text-lg">{{ $emptyMessage }}</p>
            </div>
        @endif
    </div>
</section>
