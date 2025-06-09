<div class="relative" x-data="{ focused: false }">
    <!-- Search Input -->
    <div class="relative">
        <input
            type="text"
            wire:model.live.debounce.300ms="query"
            @focus="focused = true; $wire.showSuggestions = true"
            @blur="setTimeout(() => { focused = false; $wire.hideSuggestions() }, 200)"
            @keydown.arrow-up.prevent="$wire.navigateUp()"
            @keydown.arrow-down.prevent="$wire.navigateDown()"
            @keydown.enter.prevent="$wire.selectHighlighted()"
            @keydown.escape="$wire.hideSuggestions()"
            placeholder="Tìm kiếm bài viết, sản phẩm..."
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-colors"
        >

        <!-- Search Icon -->
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-search text-gray-400"></i>
        </div>

        <!-- Clear Button -->
        @if($query)
        <button
            wire:click="clearSearch"
            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
        >
            <i class="fas fa-times"></i>
        </button>
        @endif
    </div>

    <!-- Search Suggestions Dropdown -->
    @if($showSuggestions && count($suggestions) > 0)
    <div class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-96 overflow-y-auto">
        @foreach($suggestions as $index => $suggestion)
        <div
            wire:click="selectSuggestion({{ $index }})"
            class="flex items-center p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 {{ $selectedIndex === $index ? 'bg-red-50' : '' }}"
        >
            <!-- Thumbnail -->
            <div class="flex-shrink-0 w-12 h-12 mr-3">
                <img
                    src="{{ $suggestion['thumbnail'] }}"
                    alt="{{ $suggestion['title'] }}"
                    class="w-full h-full object-cover rounded"
                    loading="lazy"
                    onerror="this.src='{{ asset('images/placeholder.jpg') }}'"
                >
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <h4 class="text-sm font-medium text-gray-900 truncate">
                    {{ $suggestion['title'] }}
                </h4>

                @if($suggestion['excerpt'])
                <p class="text-xs text-gray-600 mt-1 line-clamp-2">
                    {{ $suggestion['excerpt'] }}
                </p>
                @endif

                <!-- Meta Info -->
                <div class="flex items-center justify-between mt-1 text-xs text-gray-500">
                    <div class="flex items-center">
                        @if($suggestion['category'])
                        <span class="bg-gray-100 px-2 py-1 rounded-full">
                            {{ $suggestion['category'] }}
                        </span>
                        @endif

                        <span class="ml-2 capitalize">
                            @if($suggestion['type'] === 'post')
                                <i class="fas fa-file-alt mr-1"></i>Bài viết
                            @elseif($suggestion['type'] === 'product')
                                <i class="fas fa-box mr-1"></i>Sản phẩm
                            @endif
                        </span>
                    </div>

                    @if($suggestion['type'] === 'product' && isset($suggestion['price']))
                    <span class="font-semibold text-red-600">
                        {{ $suggestion['price'] }}
                    </span>
                    @endif
                </div>
            </div>

            <!-- Arrow Icon -->
            <div class="flex-shrink-0 ml-2">
                <i class="fas fa-arrow-right text-gray-400"></i>
            </div>
        </div>
        @endforeach

        <!-- View All Results -->
        @if(count($suggestions) >= 8)
        <div class="p-3 border-t border-gray-200 bg-gray-50">
            <a
                href="{{ route('search', ['q' => $query]) }}"
                class="block text-center text-sm text-red-600 hover:text-red-700 font-medium"
            >
                <i class="fas fa-search mr-2"></i>
                Xem tất cả kết quả cho "{{ $query }}"
            </a>
        </div>
        @endif
    </div>
    @endif

    <!-- No Results -->
    @if($showSuggestions && $query && count($suggestions) === 0)
    <div class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50 p-4 text-center">
        <div class="text-gray-500">
            <i class="fas fa-search text-2xl mb-2"></i>
            <p class="text-sm">Không tìm thấy kết quả cho "{{ $query }}"</p>
            <a
                href="{{ route('search', ['q' => $query]) }}"
                class="inline-block mt-2 text-red-600 hover:text-red-700 text-sm font-medium"
            >
                Tìm kiếm nâng cao
            </a>
        </div>
    </div>
    @endif
</div>
