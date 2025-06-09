{{-- Search Suggestions Livewire Component --}}
<div class="relative" x-data="{ focused: false }" @click.away="$wire.hideSuggestions()">
    <div class="relative">
        <input type="text" 
               wire:model.live.debounce.300ms="query"
               @focus="focused = true; $wire.set('showSuggestions', true)"
               @keydown.arrow-up.prevent="$wire.navigateUp()"
               @keydown.arrow-down.prevent="$wire.navigateDown()"
               @keydown.enter.prevent="$wire.selectCurrent()"
               @keydown.escape="$wire.hideSuggestions()"
               placeholder="Tìm kiếm..."
               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-colors">
        
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-search text-gray-400"></i>
        </div>
        
        @if($query)
            <button wire:click="$set('query', '')" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        @endif
    </div>

    {{-- Suggestions Dropdown --}}
    @if($showSuggestions && !empty($suggestions))
        <div class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-96 overflow-y-auto">
            @foreach($suggestions as $index => $suggestion)
                <div wire:click="selectSuggestion({{ $index }})"
                     class="flex items-center px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 {{ $selectedIndex === $index ? 'bg-red-50' : '' }}">
                    
                    {{-- Thumbnail/Icon --}}
                    <div class="flex-shrink-0 mr-3">
                        @if($suggestion['image'])
                            <img src="{{ $suggestion['image'] }}" 
                                 alt="{{ $suggestion['title'] }}"
                                 class="w-10 h-10 object-cover rounded">
                        @else
                            <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center">
                                <i class="{{ $suggestion['icon'] }} text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-gray-900 truncate">
                            {{ $suggestion['title'] }}
                        </div>
                        <div class="text-xs text-gray-500 capitalize">
                            {{ $suggestion['type'] === 'post' ? 'Bài viết' : 
                               ($suggestion['type'] === 'product' ? 'Sản phẩm' : 
                               ($suggestion['type'] === 'staff' ? 'Nhân viên' : 'Trang')) }}
                        </div>
                    </div>
                    
                    {{-- Arrow --}}
                    <div class="flex-shrink-0 ml-2">
                        <i class="fas fa-arrow-right text-gray-300 text-xs"></i>
                    </div>
                </div>
            @endforeach
            
            {{-- View All Results --}}
            @if(strlen($query) >= 2)
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                    <button wire:click="performSearch()" 
                            class="w-full text-left text-sm text-red-600 hover:text-red-700 font-medium">
                        <i class="fas fa-search mr-2"></i>
                        Xem tất cả kết quả cho "{{ $query }}"
                    </button>
                </div>
            @endif
        </div>
    @endif

    {{-- No Results --}}
    @if($showSuggestions && strlen($query) >= 2 && empty($suggestions))
        <div class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
            <div class="px-4 py-6 text-center">
                <i class="fas fa-search text-gray-300 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">Không tìm thấy kết quả nào cho "{{ $query }}"</p>
                <button wire:click="performSearch()" 
                        class="mt-2 text-red-600 hover:text-red-700 text-sm font-medium">
                    Tìm kiếm nâng cao
                </button>
            </div>
        </div>
    @endif

    {{-- Loading State --}}
    <div wire:loading wire:target="query" 
         class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
        <div class="px-4 py-3 flex items-center">
            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-red-600 mr-3"></div>
            <span class="text-sm text-gray-600">Đang tìm kiếm...</span>
        </div>
    </div>
</div>

{{-- Keyboard Navigation Hint --}}
@if($showSuggestions && !empty($suggestions))
    <div class="text-xs text-gray-400 mt-1 hidden md:block">
        Sử dụng ↑↓ để điều hướng, Enter để chọn, Esc để đóng
    </div>
@endif
