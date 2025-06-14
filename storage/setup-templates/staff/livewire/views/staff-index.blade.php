<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    {{ $globalSettings->staff_title ?? 'Đội ngũ của chúng tôi' }}
                </h1>
                @if($globalSettings->staff_description ?? '')
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        {{ $globalSettings->staff_description }}
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
                                   placeholder="Tên, chức vụ..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        </div>

                        <!-- Positions -->
                        @if($positions->count() > 0)
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                                Chức vụ
                            </label>
                            <select wire:model.live="position" 
                                    id="position"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Tất cả chức vụ</option>
                                @foreach($positions as $pos)
                                    <option value="{{ $pos }}">{{ $pos }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

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

                        @if($search || $position || $sort !== 'order')
                            <button wire:click="clearFilters" 
                                    class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 transition-colors">
                                <i class="fas fa-times mr-2"></i>Xóa bộ lọc
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Staff Grid -->
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
                    @if($staff->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($staff as $member)
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                                    <!-- Avatar -->
                                    <div class="relative">
                                        <div class="aspect-w-1 aspect-h-1 bg-gray-100">
                                            <img src="{{ $member->image_url }}" 
                                                 alt="{{ $member->name }}"
                                                 class="w-full h-64 object-cover">
                                        </div>
                                        
                                        <!-- Social Links Overlay -->
                                        @if($member->hasSocialLinks())
                                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                            <div class="flex space-x-3">
                                                @foreach($member->social_links as $platform => $url)
                                                    @if($url)
                                                        <a href="{{ $url }}" 
                                                           target="_blank" 
                                                           rel="noopener noreferrer"
                                                           class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-700 hover:text-red-600 transition-colors"
                                                           title="{{ ucfirst($platform) }}">
                                                            <i class="{{ \App\Models\Staff::getSocialIcon($platform) }}"></i>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Info -->
                                    <div class="p-6 text-center">
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                                            <a href="{{ route('staff.show', $member->slug) }}" 
                                               class="hover:text-red-600 transition-colors">
                                                {{ $member->name }}
                                            </a>
                                        </h3>
                                        
                                        @if($member->position)
                                            <p class="text-red-600 font-medium mb-3">{{ $member->position }}</p>
                                        @endif
                                        
                                        @if($member->description)
                                            <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                                {{ Str::limit(strip_tags($member->description), 100) }}
                                            </p>
                                        @endif
                                        
                                        <!-- Contact Info -->
                                        <div class="space-y-2 text-sm text-gray-500 mb-4">
                                            @if($member->email)
                                                <div class="flex items-center justify-center">
                                                    <i class="fas fa-envelope mr-2"></i>
                                                    <a href="mailto:{{ $member->email }}" 
                                                       class="hover:text-red-600 transition-colors truncate">
                                                        {{ $member->email }}
                                                    </a>
                                                </div>
                                            @endif
                                            @if($member->phone)
                                                <div class="flex items-center justify-center">
                                                    <i class="fas fa-phone mr-2"></i>
                                                    <a href="tel:{{ $member->phone }}" 
                                                       class="hover:text-red-600 transition-colors">
                                                        {{ $member->phone }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- View Profile Button -->
                                        <a href="{{ route('staff.show', $member->slug) }}" 
                                           class="inline-flex items-center text-red-600 hover:text-red-700 font-medium text-sm">
                                            Xem chi tiết
                                            <i class="fas fa-arrow-right ml-2"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $staff->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-users text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Không tìm thấy nhân viên</h3>
                            <p class="text-gray-600 mb-4">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
                            <button wire:click="clearFilters" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Xem tất cả nhân viên
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <style>
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .aspect-w-1 {
            position: relative;
            padding-bottom: 100%; /* 1:1 aspect ratio */
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
        </style>
    </div>
</div>
