@extends('layouts.shop')

@section('title', $staff->seo_title ?: ($staff->name . ' - ' . $staff->position))
@section('description', $staff->seo_description ?: Str::limit(strip_tags($staff->description), 160))

@push('meta')
    <meta property="og:title" content="{{ $staff->seo_title ?: ($staff->name . ' - ' . $staff->position) }}">
    <meta property="og:description" content="{{ $staff->seo_description ?: Str::limit(strip_tags($staff->description), 160) }}">
    <meta property="og:image" content="{{ $staff->og_image_url ?: $staff->image_url }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="profile">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $staff->seo_title ?: ($staff->name . ' - ' . $staff->position) }}">
    <meta name="twitter:description" content="{{ $staff->seo_description ?: Str::limit(strip_tags($staff->description), 160) }}">
    <meta name="twitter:image" content="{{ $staff->og_image_url ?: $staff->image_url }}">
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
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
                            <a href="{{ route('staff.index') }}" class="text-gray-500 hover:text-red-600">Đội ngũ</a>
                        </div>
                    </li>
                    @if($staff->position)
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('staff.position', Str::slug($staff->position)) }}" class="text-gray-500 hover:text-red-600">
                                {{ $staff->position }}
                            </a>
                        </div>
                    </li>
                    @endif
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-700 font-medium">{{ $staff->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Staff Profile -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Header Section -->
            <div class="relative bg-gradient-to-r from-red-50 to-red-100 px-6 py-8">
                <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg">
                            <img src="{{ $staff->image_url }}" 
                                 alt="{{ $staff->name }}"
                                 class="w-full h-full object-cover">
                        </div>
                    </div>
                    
                    <!-- Info -->
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $staff->name }}</h1>
                        @if($staff->position)
                            <p class="text-xl text-red-600 font-medium mb-3">{{ $staff->position }}</p>
                        @endif
                        
                        <!-- Contact Info -->
                        <div class="flex flex-col sm:flex-row items-center justify-center md:justify-start space-y-2 sm:space-y-0 sm:space-x-6 text-gray-600">
                            @if($staff->email)
                                <a href="mailto:{{ $staff->email }}" class="flex items-center hover:text-red-600 transition-colors">
                                    <i class="fas fa-envelope mr-2"></i>
                                    {{ $staff->email }}
                                </a>
                            @endif
                            @if($staff->phone)
                                <a href="tel:{{ $staff->phone }}" class="flex items-center hover:text-red-600 transition-colors">
                                    <i class="fas fa-phone mr-2"></i>
                                    {{ $staff->phone }}
                                </a>
                            @endif
                        </div>
                        
                        <!-- Social Links -->
                        @if($staff->hasSocialLinks())
                            <div class="flex items-center justify-center md:justify-start space-x-4 mt-4">
                                @foreach($staff->social_links as $platform => $url)
                                    @if($url)
                                        <a href="{{ $url }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="text-gray-500 hover:text-red-600 transition-colors text-lg"
                                           title="{{ $platform }}">
                                            <i class="{{ \App\Models\Staff::getSocialIcon($platform) }}"></i>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            @if($staff->description)
                <div class="px-6 py-8">
                    <div class="prose prose-lg max-w-none">
                        {!! $staff->description !!}
                    </div>
                </div>
            @endif
            
            <!-- Gallery Images -->
            @if($staff->galleryImages && $staff->galleryImages->count() > 0)
                <div class="px-6 py-8 border-t">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Hình ảnh</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($staff->galleryImages as $image)
                            <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden cursor-pointer hover:opacity-90 transition-opacity"
                                 onclick="openImageModal('{{ $image->image_url }}', '{{ $image->alt_text ?: $staff->name }}')">
                                <img src="{{ $image->image_url }}" 
                                     alt="{{ $image->alt_text ?: $staff->name }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Related Staff -->
        @if($relatedStaff && $relatedStaff->count() > 0)
        <div class="mt-12">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Đồng nghiệp cùng bộ phận</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedStaff as $member)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                        <div class="p-6 text-center">
                            <div class="w-20 h-20 rounded-full overflow-hidden mx-auto mb-4">
                                <img src="{{ $member->image_url }}" 
                                     alt="{{ $member->name }}"
                                     class="w-full h-full object-cover">
                            </div>
                            
                            <h4 class="font-semibold text-gray-900 mb-1">
                                <a href="{{ route('staff.show', $member->slug) }}" 
                                   class="hover:text-red-600 transition-colors">
                                    {{ $member->name }}
                                </a>
                            </h4>
                            
                            @if($member->position)
                                <p class="text-red-600 text-sm mb-3">{{ $member->position }}</p>
                            @endif
                            
                            @if($member->description)
                                <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                                    {{ Str::limit(strip_tags($member->description), 80) }}
                                </p>
                            @endif
                            
                            <a href="{{ route('staff.show', $member->slug) }}" 
                               class="text-red-600 hover:text-red-700 text-sm font-medium">
                                Xem chi tiết →
                            </a>
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
