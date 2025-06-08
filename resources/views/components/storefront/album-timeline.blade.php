
@php
    // Lấy dữ liệu albums từ ViewServiceProvider hoặc tạo collection rỗng
    $albums = $albums ?? collect();
    $isLoading = !isset($albums) || $albums === null;
@endphp

@if($albums->isNotEmpty())
<div class="max-w-6xl mx-auto px-3 sm:px-4 lg:px-6">
    <!-- Timeline Container -->
    <div class="relative max-w-5xl mx-auto">
        <!-- Timeline Line - Desktop -->
        <div class="absolute left-1/2 transform -translate-x-1/2 w-px bg-red-200 h-full hidden lg:block"></div>

        <!-- Timeline Line - Mobile -->
        <div class="absolute left-4 top-0 w-px bg-red-200 h-full lg:hidden"></div>

        <!-- Timeline Items -->
        <div class="space-y-8 sm:space-y-12 lg:space-y-16">
            @foreach($albums as $index => $album)
            @php
                $isEven = $index % 2 === 0;
                $animationDelay = $index * 0.3;
            @endphp

            <!-- Timeline Item - Responsive Layout -->
            <div class="relative grid lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8 items-center timeline-item pl-8 sm:pl-10 lg:pl-0">

                <!-- Timeline Dot - Desktop -->
                <div class="absolute left-1/2 transform -translate-x-1/2 w-2.5 h-2.5 bg-red-500 rounded-full z-20 hidden lg:block timeline-dot"></div>

                <!-- Timeline Dot - Mobile -->
                <div class="absolute left-3 transform -translate-x-1/2 w-2 h-2 bg-red-500 rounded-full z-20 lg:hidden timeline-dot"></div>

                <!-- Album Content Side -->
                <div class="{{ $isEven ? 'lg:order-1' : 'lg:order-2' }}">
                    <div class="bg-white border border-red-100 hover:border-red-200 hover:shadow-md transition-all duration-300 group p-3 sm:p-4 lg:p-5 rounded-lg">

                        <!-- Date Badge & Order - Responsive -->
                        <div class="mb-3 flex items-center gap-2 flex-wrap">
                            <span class="inline-block px-2 py-1 bg-red-50 text-red-600 text-xs font-medium rounded">
                                {{ $album->published_date ? $album->published_date->format('M Y') : 'Chưa xuất bản' }}
                            </span>
                            <span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded">
                                #{{ $album->order ?? 0 }}
                            </span>
                        </div>

                        <!-- Album Title -->
                        <h3 class="text-base sm:text-lg lg:text-xl font-light text-gray-900 mb-2 sm:mb-3 leading-snug">
                            {{ $album->title }}
                        </h3>

                        <!-- Description -->
                        <p class="text-gray-600 mb-3 sm:mb-4 leading-relaxed font-light text-sm">
                            {{ Str::limit($album->description, 100) }}
                        </p>

                        <!-- PDF Info - Responsive -->
                        @if($album->pdf_file)
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-2.5 bg-red-50 border-l-3 border-red-500 gap-2">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 bg-red-500 rounded flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-file-pdf text-white text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-medium text-gray-900">Tài liệu PDF</div>
                                        @if($album->total_pages)
                                            <div class="text-xs text-gray-500">{{ $album->total_pages }} trang</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex space-x-2 text-xs">
                                    <button onclick="openPDFViewer('{{ $album->pdf_url }}', '{{ $album->title }}', {{ $album->id }})"
                                            class="text-red-600 hover:text-red-700 font-medium transition-colors">
                                        Xem
                                    </button>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ $album->pdf_url }}"
                                       download="{{ $album->title }}.pdf"
                                       onclick="incrementDownload({{ $album->id }})"
                                       class="text-red-600 hover:text-red-700 font-medium transition-colors">
                                        Tải về
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Images Side -->
                <div class="{{ $isEven ? 'lg:order-2' : 'lg:order-1' }}">
                    @if($album->images && $album->images->count() > 0)
                        <!-- Creative Image Layout -->
                        <div class="relative">
                            <!-- Main Featured Image -->
                            @php $featuredImage = $album->images->where('is_featured', true)->first() ?? $album->images->first(); @endphp
                            <!-- KISS: Click để mở ảnh trong tab mới -->
                            <div class="relative group cursor-pointer" onclick="window.open('{{ $featuredImage->image_url }}', '_blank')" data-album="{{ $album->id }}">
                                <div class="aspect-[4/3] overflow-hidden bg-gray-50 border border-red-100 group-hover:border-red-200 group-hover:shadow-md transition-all duration-300 rounded-lg">
                                    <!-- KISS: Ảnh đơn giản -->
                                    <img src="{{ $featuredImage->image_url }}"
                                         alt="{{ $featuredImage->alt_text ?? $album->title }}"
                                         class="w-full h-full object-cover"
                                         loading="lazy"
                                         onerror="handleImageError(this)">
                                </div>
                                <!-- Hover overlay -->
                                <div class="absolute inset-0 bg-red-500/0 group-hover:bg-red-500/5 transition-colors duration-300 rounded-lg"></div>
                                <!-- Hover icon -->
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="w-10 h-10 bg-white/90 rounded-full flex items-center justify-center shadow-md">
                                        <i class="fas fa-search-plus text-red-500 text-sm"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Small Images Grid - Responsive -->
                            @if($album->images->count() > 1)
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-1.5 sm:gap-2 mt-2 sm:mt-3">
                                    @foreach($album->images->skip(1)->take(4) as $image)
                                        <!-- KISS: Click để mở ảnh trong tab mới -->
                                        <div class="relative group cursor-pointer aspect-square" onclick="window.open('{{ $image->image_url }}', '_blank')" data-album="{{ $album->id }}">
                                            <div class="w-full h-full overflow-hidden bg-gray-50 border border-red-100 rounded group-hover:border-red-200 transition-all duration-300">
                                                @if($image && $image->image_url)
                                                    <!-- KISS: Ảnh đơn giản -->
                                                    <img src="{{ $image->image_url }}"
                                                         alt="{{ $image->alt_text ?? $album->title }}"
                                                         class="w-full h-full object-cover"
                                                         loading="lazy"
                                                         onerror="handleImageError(this)">
                                                @else
                                                    <!-- No image placeholder -->
                                                    <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center">
                                                        <i class="fas fa-image text-red-300 text-sm"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <!-- More indicator -->
                                            @if($loop->last && $album->images->count() > 5)
                                                <div class="absolute inset-0 bg-red-500/80 flex items-center justify-center rounded">
                                                    <span class="text-white font-medium text-xs">+{{ $album->images->count() - 5 }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Image count badge -->
                            <div class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm px-2 py-1 text-xs text-gray-600 font-medium rounded-full">
                                {{ $album->images->count() }} ảnh
                            </div>
                        </div>
                    @else
                        <!-- Fallback when no images -->
                        <div class="aspect-[4/3] bg-red-50 border border-red-100 flex items-center justify-center rounded-lg">
                            <div class="text-center">
                                <i class="fas fa-images text-red-300 text-3xl mb-2"></i>
                                <p class="text-red-400 font-light text-sm">Chưa có hình ảnh</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>



<!-- PDF Viewer Modal - Tối ưu z-index và responsive -->
<div id="pdfModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[9999] hidden overflow-hidden">
    <div class="flex items-center justify-center min-h-screen p-2 sm:p-3">
        <div class="bg-white rounded-lg sm:rounded-xl shadow-2xl w-full max-w-6xl h-[95vh] sm:h-[90vh] flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between p-3 sm:p-4 border-b border-gray-200 flex-shrink-0">
                <h3 id="pdfTitle" class="text-base sm:text-lg font-bold text-gray-900 truncate pr-4"></h3>
                <button onclick="closePDFViewer()"
                        class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full p-1.5 transition-all duration-200 flex-shrink-0">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <!-- Content -->
            <div class="flex-1 p-3 sm:p-4 overflow-hidden">
                <iframe id="pdfFrame" class="w-full h-full rounded border border-gray-200 bg-gray-50"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- KISS: Không cần modal gallery phức tạp -->

@else
<!-- Empty State - Minimalist -->
<div class="max-w-5xl mx-auto px-3 sm:px-4 lg:px-6">
    <!-- Header Section - Minimalist -->
    <div class="text-center mb-12">
        <div class="w-12 h-1 bg-gray-300 mx-auto mb-6"></div>
        <h2 class="text-2xl md:text-3xl font-light text-gray-900 mb-3 tracking-wide">
            Timeline
        </h2>
        <p class="text-gray-500 max-w-md mx-auto font-light text-sm">
            Hành trình học tập qua thời gian
        </p>
    </div>

    <!-- Empty Timeline -->
    <div class="relative max-w-4xl mx-auto">
        <!-- Timeline Line - Desktop -->
        <div class="absolute left-1/2 transform -translate-x-1/2 w-px bg-gray-200 h-64 hidden lg:block"></div>

        <!-- Timeline Line - Mobile -->
        <div class="absolute left-4 top-0 w-px bg-gray-200 h-64 lg:hidden"></div>

        <!-- Empty State Content -->
        <div class="relative grid lg:grid-cols-2 gap-8 items-center pl-10 lg:pl-0">
            <!-- Timeline Dot - Desktop -->
            <div class="absolute left-1/2 transform -translate-x-1/2 w-2.5 h-2.5 bg-gray-300 rounded-full z-20 hidden lg:block"></div>

            <!-- Timeline Dot - Mobile -->
            <div class="absolute left-3 transform -translate-x-1/2 w-2.5 h-2.5 bg-gray-300 rounded-full z-20 lg:hidden"></div>

            <!-- Empty Content -->
            <div class="lg:order-1">
                <div class="bg-white border border-gray-100 p-5 rounded-lg">
                    <div class="mb-4">
                        <span class="inline-block px-2 py-1 bg-gray-50 text-gray-500 text-xs font-medium rounded">
                            Chưa có dữ liệu
                        </span>
                    </div>

                    <h3 class="text-lg font-light text-gray-900 mb-3 leading-snug">
                        Timeline trống
                    </h3>

                    <p class="text-gray-600 mb-4 leading-relaxed font-light text-sm">
                        Các album khóa học sẽ sớm được thêm vào timeline này.
                    </p>
                </div>
            </div>

            <!-- Empty Image -->
            <div class="lg:order-2">
                <div class="aspect-[4/3] bg-gray-50 border border-gray-100 flex items-center justify-center rounded-lg">
                    <div class="text-center">
                        <i class="fas fa-images text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-400 font-light text-sm">Chưa có hình ảnh</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to action - Minimalist -->
        <div class="text-center mt-12">
            <div class="flex justify-center space-x-6 text-sm">
                <a href="{{ route('courses.index') }}"
                   class="text-red-500 hover:text-red-600 font-medium transition-colors">
                    Khám phá khóa học
                </a>
                <a href="{{ route('posts.index') }}"
                   class="text-gray-500 hover:text-gray-600 font-medium transition-colors">
                    Đọc tin tức
                </a>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
// KISS: JavaScript đơn giản cho PDF viewer
function openPDFViewer(pdfUrl, title, albumId) {
    const modal = document.getElementById('pdfModal');
    const pdfTitle = document.getElementById('pdfTitle');
    const pdfFrame = document.getElementById('pdfFrame');

    if (pdfTitle) pdfTitle.textContent = title;
    if (pdfFrame) pdfFrame.src = pdfUrl;
    if (modal) modal.classList.remove('hidden');
}

function closePDFViewer() {
    const modal = document.getElementById('pdfModal');
    const pdfFrame = document.getElementById('pdfFrame');

    if (modal) modal.classList.add('hidden');
    if (pdfFrame) pdfFrame.src = '';
}

// KISS: Gallery đơn giản - chỉ hiển thị ảnh
function openImageGallery(albumId, startIndex = 0) {
    // Đơn giản: chỉ mở ảnh trong tab mới
    const images = document.querySelectorAll(`[data-album="${albumId}"] img`);
    if (images[startIndex]) {
        window.open(images[startIndex].src, '_blank');
    }
}

// KISS: Chỉ giữ keyboard navigation đơn giản
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePDFViewer();
    }
});
</script>
@endpush

{{-- KISS: Không cần global handler phức tạp --}}

<style>
/* KISS: CSS đơn giản, không animation phức tạp */

/* KISS: Responsive cải thiện cho mobile */
@media (max-width: 640px) {
    .timeline-item {
        padding-left: 2rem; /* 32px */
    }

    .timeline-dot {
        left: 0.5rem; /* 8px */
    }

    /* Giảm khoảng cách giữa các timeline item */
    .space-y-8 > * + * {
        margin-top: 1.5rem; /* 24px */
    }
}

@media (max-width: 480px) {
    .timeline-item {
        padding-left: 1.75rem; /* 28px */
    }

    .timeline-dot {
        left: 0.375rem; /* 6px */
    }

    /* Giảm thêm khoảng cách cho mobile nhỏ */
    .space-y-8 > * + * {
        margin-top: 1.25rem; /* 20px */
    }
}
</style>
