@extends('layouts.shop')

@section('title', $service->seo_title ?: ($service->name . ' - Dịch vụ chuyên nghiệp'))
@section('description', $service->seo_description ?: Str::limit(strip_tags($service->short_description ?: $service->description), 160))

@push('meta')
    <meta property="og:title" content="{{ $service->seo_title ?: ($service->name . ' - Dịch vụ chuyên nghiệp') }}">
    <meta property="og:description" content="{{ $service->seo_description ?: Str::limit(strip_tags($service->short_description ?: $service->description), 160) }}">
    <meta property="og:image" content="{{ $service->og_image_url ?: $service->image_url }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="product">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $service->seo_title ?: ($service->name . ' - Dịch vụ chuyên nghiệp') }}">
    <meta name="twitter:description" content="{{ $service->seo_description ?: Str::limit(strip_tags($service->short_description ?: $service->description), 160) }}">
    <meta name="twitter:image" content="{{ $service->og_image_url ?: $service->image_url }}">

    @if($service->meta_keywords)
        <meta name="keywords" content="{{ $service->meta_keywords }}">
    @endif
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ url('/') }}" class="text-gray-500 hover:text-red-600">
                            <i class="fas fa-home mr-2"></i>Trang chủ
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('services.index') }}" class="text-gray-500 hover:text-red-600">Dịch vụ</a>
                        </div>
                    </li>
                    @if($service->category)
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('services.category', $service->category) }}" class="text-gray-500 hover:text-red-600">
                                {{ $service->category_name }}
                            </a>
                        </div>
                    </li>
                    @endif
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-700 font-medium">{{ $service->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Service Detail -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <!-- Service Header -->
                    <div class="relative">
                        <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                            <img src="{{ $service->image_url }}" 
                                 alt="{{ $service->name }}"
                                 class="w-full h-64 md:h-80 object-cover">
                        </div>
                        
                        @if($service->is_featured)
                            <div class="absolute top-4 left-4">
                                <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-star mr-1"></i>Nổi bật
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Service Info -->
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-4">
                            <div class="flex-1">
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $service->name }}</h1>
                                
                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-4">
                                    @if($service->category)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800">
                                            <i class="fas fa-tag mr-1"></i>
                                            {{ $service->category_name }}
                                        </span>
                                    @endif
                                    
                                    @if($service->duration)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $service->duration_name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <div class="text-3xl font-bold text-red-600 mb-2">
                                    {{ $service->formatted_price }}
                                </div>
                                @if($service->price)
                                    <p class="text-sm text-gray-500">Giá chưa bao gồm VAT</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Short Description -->
                        @if($service->short_description)
                            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                <p class="text-gray-700 leading-relaxed">{{ $service->short_description }}</p>
                            </div>
                        @endif
                        
                        <!-- Description -->
                        @if($service->description)
                            <div class="prose prose-lg max-w-none mb-8">
                                {!! $service->description !!}
                            </div>
                        @endif
                        
                        <!-- Features -->
                        @if($service->hasFeatures())
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Tính năng bao gồm</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($service->features as $feature)
                                        <div class="flex items-start space-x-3 p-3 rounded-lg {{ $feature['included'] ? 'bg-green-50' : 'bg-gray-50' }}">
                                            <div class="flex-shrink-0 mt-1">
                                                @if($feature['included'])
                                                    <i class="fas fa-check-circle text-green-600"></i>
                                                @else
                                                    <i class="fas fa-times-circle text-gray-400"></i>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-medium {{ $feature['included'] ? 'text-green-900' : 'text-gray-700' }}">
                                                    {{ $feature['name'] }}
                                                </h4>
                                                @if(!empty($feature['description']))
                                                    <p class="text-sm {{ $feature['included'] ? 'text-green-700' : 'text-gray-600' }} mt-1">
                                                        {{ $feature['description'] }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Gallery Images -->
                        @if($service->galleryImages && $service->galleryImages->count() > 0)
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Hình ảnh dự án</h3>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach($service->galleryImages as $image)
                                        <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden cursor-pointer hover:opacity-90 transition-opacity"
                                             onclick="openImageModal('{{ $image->image_url }}', '{{ $image->alt_text ?: $service->name }}')">
                                            <img src="{{ $image->image_url }}" 
                                                 alt="{{ $image->alt_text ?: $service->name }}"
                                                 class="w-full h-full object-cover">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- CTA Section -->
                        <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-lg p-6 text-center">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Quan tâm đến dịch vụ này?</h3>
                            <p class="text-gray-600 mb-4">Liên hệ với chúng tôi để được tư vấn chi tiết và báo giá</p>
                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                <a href="tel:{{ $globalSettings->phone ?? '' }}" 
                                   class="inline-flex items-center justify-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                                    <i class="fas fa-phone mr-2"></i>Gọi ngay
                                </a>
                                <a href="mailto:{{ $globalSettings->email ?? '' }}" 
                                   class="inline-flex items-center justify-center px-6 py-3 border border-red-600 text-red-600 font-medium rounded-lg hover:bg-red-50 transition-colors">
                                    <i class="fas fa-envelope mr-2"></i>Gửi email
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="space-y-6">
                    <!-- Contact Card -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Liên hệ tư vấn</h3>
                        <div class="space-y-3">
                            @if($globalSettings->phone ?? '')
                                <a href="tel:{{ $globalSettings->phone }}" 
                                   class="flex items-center text-gray-600 hover:text-red-600 transition-colors">
                                    <i class="fas fa-phone w-5 mr-3"></i>
                                    {{ $globalSettings->phone }}
                                </a>
                            @endif
                            @if($globalSettings->email ?? '')
                                <a href="mailto:{{ $globalSettings->email }}" 
                                   class="flex items-center text-gray-600 hover:text-red-600 transition-colors">
                                    <i class="fas fa-envelope w-5 mr-3"></i>
                                    {{ $globalSettings->email }}
                                </a>
                            @endif
                            @if($globalSettings->address ?? '')
                                <div class="flex items-start text-gray-600">
                                    <i class="fas fa-map-marker-alt w-5 mr-3 mt-1"></i>
                                    <span>{{ $globalSettings->address }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Service Info -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin dịch vụ</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Giá:</span>
                                <span class="font-medium text-red-600">{{ $service->formatted_price }}</span>
                            </div>
                            @if($service->duration)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Thời gian:</span>
                                    <span class="font-medium">{{ $service->duration_name }}</span>
                                </div>
                            @endif
                            @if($service->category)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Danh mục:</span>
                                    <span class="font-medium">{{ $service->category_name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Services -->
        @if($relatedServices && $relatedServices->count() > 0)
        <div class="mt-12">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Dịch vụ liên quan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedServices as $relatedService)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                        <div class="aspect-w-16 aspect-h-9">
                            <img src="{{ $relatedService->image_url }}" 
                                 alt="{{ $relatedService->name }}"
                                 class="w-full h-48 object-cover">
                        </div>
                        
                        <div class="p-6">
                            <h4 class="font-semibold text-gray-900 mb-2">
                                <a href="{{ route('service.show', $relatedService->slug) }}" 
                                   class="hover:text-red-600 transition-colors">
                                    {{ $relatedService->name }}
                                </a>
                            </h4>
                            
                            @if($relatedService->short_description)
                                <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                                    {{ $relatedService->short_description }}
                                </p>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <span class="text-red-600 font-semibold">{{ $relatedService->formatted_price }}</span>
                                <a href="{{ route('service.show', $relatedService->slug) }}" 
                                   class="text-red-600 hover:text-red-700 text-sm font-medium">
                                    Xem chi tiết →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
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

.aspect-w-16 {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
}

.aspect-w-1 {
    position: relative;
    padding-bottom: 100%; /* 1:1 aspect ratio */
}

.aspect-w-16 > *, .aspect-w-1 > * {
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
}

.prose {
    color: #374151;
}

.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
    color: #111827;
    font-weight: 600;
}

.prose h2 {
    font-size: 1.5em;
    margin-top: 2em;
    margin-bottom: 1em;
}

.prose h3 {
    font-size: 1.25em;
    margin-top: 1.6em;
    margin-bottom: 0.6em;
}

.prose p {
    margin-top: 1.25em;
    margin-bottom: 1.25em;
}

.prose ul, .prose ol {
    margin-top: 1.25em;
    margin-bottom: 1.25em;
    padding-left: 1.625em;
}

.prose li {
    margin-top: 0.5em;
    margin-bottom: 0.5em;
}
</style>

<script>
function openImageModal(src, alt) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4';
    modal.onclick = () => modal.remove();
    
    const img = document.createElement('img');
    img.src = src;
    img.alt = alt;
    img.className = 'max-w-full max-h-full object-contain rounded-lg';
    
    modal.appendChild(img);
    document.body.appendChild(modal);
}
</script>
@endsection
