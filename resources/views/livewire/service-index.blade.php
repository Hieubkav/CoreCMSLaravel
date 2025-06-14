<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    {{ $globalSettings->service_title ?? 'Dịch vụ của chúng tôi' }}
                </h1>
                @if($globalSettings->service_description ?? '')
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        {{ $globalSettings->service_description }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Filters -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bộ lọc</h3>
                    
                    <div class="space-y-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                Tìm kiếm
                            </label>
                            <input type="text" 
                                   wire:model.live.debounce.300ms="search"
                                   id="search"
                                   placeholder="Tên dịch vụ, mô tả..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        </div>

                        <!-- Categories -->
                        @if($categories->count() > 0)
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Danh mục
                            </label>
                            <select wire:model.live="category" 
                                    id="category"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Tất cả danh mục</option>
                                @foreach($categories as $key => $name)
                                    <option value="{{ $key }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Price Range -->
                        <div>
                            <label for="priceRange" class="block text-sm font-medium text-gray-700 mb-2">
                                Khoảng giá
                            </label>
                            <select wire:model.live="priceRange" 
                                    id="priceRange"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Tất cả mức giá</option>
                                @foreach($priceRanges as $key => $name)
                                    <option value="{{ $key }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Featured -->
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   wire:model.live="featured"
                                   id="featured"
                                   class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500">
                            <label for="featured" class="ml-2 text-sm text-gray-700">
                                Chỉ dịch vụ nổi bật
                            </label>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">
                                Sắp xếp
                            </label>
                            <select wire:model.live="sort" 
                                    id="sort"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                @foreach($sortOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if($search || $category || $priceRange || $featured || $sort !== 'order')
                            <button wire:click="clearFilters" 
                                    class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 transition-colors">
                                <i class="fas fa-times mr-2"></i>Xóa bộ lọc
                            </button>
                        @endif
                    </div>

                    <!-- Featured Services Sidebar -->
                    @if($featuredServices->count() > 0 && !$featured)
                    <div class="mt-8 pt-6 border-t">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Dịch vụ nổi bật</h4>
                        <div class="space-y-4">
                            @foreach($featuredServices as $featuredService)
                                <div class="border rounded-lg p-3 hover:shadow-md transition-shadow">
                                    <div class="flex space-x-3">
                                        <img src="{{ $featuredService->image_url }}" 
                                             alt="{{ $featuredService->name }}"
                                             class="w-16 h-16 object-cover rounded">
                                        <div class="flex-1 min-w-0">
                                            <h5 class="text-sm font-medium text-gray-900 line-clamp-2">
                                                <a href="{{ route('services.show', $featuredService->slug) }}"
                                                   class="hover:text-red-600">
                                                    {{ $featuredService->name }}
                                                </a>
                                            </h5>
                                            <p class="text-xs text-red-600 font-medium mt-1">
                                                {{ $featuredService->formatted_price }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Services Grid -->
            <div class="lg:col-span-3">
                <!-- Loading Indicator -->
                <div wire:loading class="text-center py-4">
                    <div class="inline-flex items-center px-4 py-2 bg-white rounded-lg shadow-sm">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-gray-600">Đang tải...</span>
                    </div>
                </div>

                <div wire:loading.remove>
                    @if($services->count() > 0)
                        <!-- Results Info -->
                        <div class="flex items-center justify-between mb-6">
                            <p class="text-gray-600">
                                Hiển thị {{ $services->firstItem() }}-{{ $services->lastItem() }} 
                                trong tổng số {{ $services->total() }} dịch vụ
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($services as $service)
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                                    <!-- Service Image -->
                                    <div class="relative">
                                        <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                                            <img src="{{ $service->image_url }}" 
                                                 alt="{{ $service->name }}"
                                                 class="w-full h-48 object-cover">
                                        </div>
                                        
                                        @if($service->is_featured)
                                            <div class="absolute top-3 left-3">
                                                <span class="bg-red-600 text-white px-2 py-1 rounded-full text-xs font-medium">
                                                    <i class="fas fa-star mr-1"></i>Nổi bật
                                                </span>
                                            </div>
                                        @endif

                                        <div class="absolute top-3 right-3">
                                            <span class="bg-white text-red-600 px-2 py-1 rounded-full text-sm font-bold shadow-md">
                                                {{ $service->formatted_price }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Service Info -->
                                    <div class="p-6">
                                        <div class="flex items-center gap-2 mb-2">
                                            @if($service->category)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-xs">
                                                    {{ $service->category_name }}
                                                </span>
                                            @endif
                                            @if($service->duration)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs">
                                                    <i class="fas fa-clock mr-1"></i>{{ $service->duration_name }}
                                                </span>
                                            @endif
                                        </div>

                                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                            <a href="{{ route('services.show', $service->slug) }}"
                                               class="hover:text-red-600 transition-colors">
                                                {{ $service->name }}
                                            </a>
                                        </h3>
                                        
                                        @if($service->short_description)
                                            <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                                {{ $service->short_description }}
                                            </p>
                                        @endif

                                        <!-- Features Preview -->
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
                                        
                                        <!-- Action Buttons -->
                                        <div class="flex items-center justify-between">
                                            <div class="text-lg font-bold text-red-600">
                                                {{ $service->formatted_price }}
                                            </div>
                                            <a href="{{ route('services.show', $service->slug) }}"
                                               class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                                Xem chi tiết
                                                <i class="fas fa-arrow-right ml-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $services->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-search text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Không tìm thấy dịch vụ</h3>
                            <p class="text-gray-600 mb-4">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
                            <button wire:click="clearFilters" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Xem tất cả dịch vụ
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
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
    </div>
</div>
