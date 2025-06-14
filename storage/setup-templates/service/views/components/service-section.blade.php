@php
    $services = \App\Models\Service::where('status', 'active')
        ->where('is_featured', true)
        ->with(['images'])
        ->ordered()
        ->limit($limit ?? 6)
        ->get();
@endphp

@if($services->count() > 0)
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ $title ?? 'Dịch vụ nổi bật' }}
            </h2>
            @if($subtitle ?? '')
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $service)
                <div class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-2">
                    <!-- Service Image -->
                    <div class="relative overflow-hidden">
                        <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                            <img src="{{ $service->image_url }}" 
                                 alt="{{ $service->name }}"
                                 class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        
                        <!-- Featured Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                <i class="fas fa-star mr-1"></i>Nổi bật
                            </span>
                        </div>

                        <!-- Price Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="bg-white text-red-600 px-3 py-1 rounded-full text-sm font-bold shadow-md">
                                {{ $service->formatted_price }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Service Content -->
                    <div class="p-6">
                        <!-- Category & Duration -->
                        <div class="flex items-center gap-2 mb-3">
                            @if($service->category)
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-medium">
                                    {{ $service->category_name }}
                                </span>
                            @endif
                            @if($service->duration)
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs font-medium">
                                    <i class="fas fa-clock mr-1"></i>{{ $service->duration_name }}
                                </span>
                            @endif
                        </div>

                        <!-- Service Title -->
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-red-600 transition-colors">
                            <a href="{{ route('services.show', $service->slug) }}">
                                {{ $service->name }}
                            </a>
                        </h3>
                        
                        <!-- Service Description -->
                        @if($service->short_description)
                            <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                {{ $service->short_description }}
                            </p>
                        @endif

                        <!-- Key Features -->
                        @if($service->hasFeatures())
                            <div class="mb-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach(collect($service->features)->where('included', true)->take(3) as $feature)
                                        <span class="inline-flex items-center px-2 py-1 rounded bg-green-50 text-green-700 text-xs">
                                            <i class="fas fa-check mr-1"></i>{{ $feature['name'] }}
                                        </span>
                                    @endforeach
                                    @if(collect($service->features)->where('included', true)->count() > 3)
                                        <span class="text-xs text-gray-500">
                                            +{{ collect($service->features)->where('included', true)->count() - 3 }} khác
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <!-- Action Section -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="text-lg font-bold text-red-600">
                                {{ $service->formatted_price }}
                            </div>
                            <a href="{{ route('services.show', $service->slug) }}"
                               class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors group">
                                Xem chi tiết
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- View All Button -->
        <div class="text-center mt-12">
            <a href="{{ route('services.index') }}"
               class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-medium rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                <i class="fas fa-th-large mr-2"></i>
                Xem tất cả dịch vụ
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

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

.aspect-w-16 {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
}

.aspect-w-16 > * {
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
}
</style>
@endif
