<div class="relative">
    @if(!$isMobile)
        <!-- Desktop Search -->
        <div class="relative w-full">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg wire:loading.remove wire:target="search" class="h-5 w-5 text-gray-400 group-focus-within:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <svg wire:loading wire:target="search" class="animate-spin h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <input
                    type="text"
                    wire:model.live.debounce.150ms="query"
                    wire:focus="$set('showResults', true)"
                    wire:blur="hideResults"
                    placeholder="Tìm kiếm bài viết, khóa học..."
                    class="w-full py-3 pl-12 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 shadow-sm hover:shadow-md transition-all duration-200 text-sm"
                    autocomplete="off"
                    onkeydown="if(event.key==='Enter') @this.call('performSearch')">

                <button
                    wire:click="performSearch"
                    type="button"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg px-3 py-1.5 hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-sm hover:shadow-md text-sm font-medium disabled:opacity-50"
                    wire:loading.attr="disabled"
                    wire:target="performSearch">
                    <span wire:loading.remove wire:target="performSearch">Tìm</span>
                    <span wire:loading wire:target="performSearch" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-1 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Tìm
                    </span>
                </button>

                <!-- Search Results Dropdown -->
                @if($showResults && !empty($query) && strlen($query) >= 1)
                <div class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-600 z-50 max-h-96 overflow-y-auto"
                     onclick="event.stopPropagation()"
                     style="display: block !important;">

                    <!-- Loading state -->
                    <div wire:loading wire:target="search" class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center text-gray-500">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm">Đang tìm kiếm...</span>
                        </div>
                    </div>

                    @if(!empty($results))
                        <div class="py-1" wire:loading.remove wire:target="search">
                            @foreach($results as $result)
                                @if(is_array($result) && isset($result['url']) && isset($result['title']))
                                <a href="{{ $result['url'] }}"
                                   class="flex items-center px-3 py-2.5 hover:bg-red-50 dark:hover:bg-gray-700 transition-colors duration-200 group border-b border-gray-100 dark:border-gray-700 last:border-b-0"
                                   wire:click="$set('showResults', false)">

                                    @if(isset($result['image']) && $result['image'])
                                        <img src="{{ asset('storage/' . $result['image']) }}"
                                             alt="{{ $result['title'] }}"
                                             class="w-12 h-12 object-cover rounded-lg mr-3 flex-shrink-0 shadow-sm"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <!-- Fallback UI khi ảnh lỗi -->
                                        <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-red-200 dark:from-gray-600 dark:to-gray-700 rounded-lg mr-3 flex-shrink-0 items-center justify-center" style="display: none;">
                                            @if(isset($result['type']) && $result['type'] === 'course')
                                                <i class="fas fa-graduation-cap text-red-500 dark:text-red-400 text-lg"></i>
                                            @else
                                                <i class="fas fa-newspaper text-red-500 dark:text-red-400 text-lg"></i>
                                            @endif
                                        </div>
                                    @else
                                        <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-red-200 dark:from-gray-600 dark:to-gray-700 rounded-lg mr-3 flex-shrink-0 flex items-center justify-center">
                                            @if(isset($result['type']) && $result['type'] === 'course')
                                                <i class="fas fa-graduation-cap text-red-500 dark:text-red-400 text-lg"></i>
                                            @else
                                                <i class="fas fa-newspaper text-red-500 dark:text-red-400 text-lg"></i>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-gray-900 dark:text-white text-sm truncate">
                                            {{ $result['title'] }}
                                        </div>

                                        @if(isset($result['type']) && $result['type'] === 'course')
                                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                @if(isset($result['price']))
                                                    <span class="text-red-600 dark:text-red-400 font-semibold">{{ $result['price'] }}</span>
                                                @endif
                                                @if(isset($result['level']))
                                                    <span class="bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded">{{ ucfirst($result['level']) }}</span>
                                                @endif
                                                @if(isset($result['duration']))
                                                    <span>{{ $result['duration'] }}</span>
                                                @endif
                                            </div>
                                        @elseif(isset($result['type']) && $result['type'] === 'post' && isset($result['excerpt']))
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                                {{ $result['excerpt'] }}
                                            </div>
                                        @endif

                                        <div class="flex items-center mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ isset($result['type']) && $result['type'] === 'course' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                                @if(isset($result['type']) && $result['type'] === 'course')
                                                    <i class="fas fa-graduation-cap mr-1"></i>
                                                    Khóa học
                                                @else
                                                    <i class="fas fa-newspaper mr-1"></i>
                                                    Bài viết
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="ml-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <i class="fas fa-arrow-right text-gray-400 text-sm"></i>
                                    </div>
                                </a>
                                @endif
                            @endforeach

                            @if(count($results) >= 8)
                                <div class="border-t border-gray-200 dark:border-gray-600 px-4 py-2">
                                    <button wire:click="performSearch"
                                            class="text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium">
                                        Xem tất cả kết quả cho "{{ $query }}"
                                    </button>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- No results message -->
                        <div class="px-4 py-6 text-center" wire:loading.remove wire:target="search">
                            <div class="text-gray-400 dark:text-gray-500 mb-2">
                                <i class="fas fa-search text-2xl"></i>
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Không tìm thấy kết quả cho "<span class="font-medium">{{ $query }}</span>"
                            </div>
                            <button wire:click="performSearch"
                                    class="mt-2 text-xs text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                                Tìm kiếm nâng cao
                            </button>
                        </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    @else
        <!-- Mobile Search -->
        <div class="px-4 mb-3 mt-2">
            <div class="relative">
                <input
                    type="text"
                    wire:model.live.debounce.150ms="query"
                    wire:focus="$set('showResults', true)"
                    wire:blur="hideResults"
                    placeholder="Tìm kiếm bài viết, khóa học..."
                    class="w-full py-2 px-4 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-red-500 bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
                    autocomplete="off"
                    onkeydown="if(event.key==='Enter') @this.call('performSearch')">

                <button
                    wire:click="performSearch"
                    type="button"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-red-700 text-white rounded-full p-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- Mobile Search Results -->
                @if($showResults && !empty($results))
                <div class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 z-50 max-h-64 overflow-y-auto"
                     onclick="event.stopPropagation()"
                     style="display: block !important;">

                    @if(!empty($results))
                        <div class="py-1">
                            @foreach($results as $result)
                                @if(is_array($result) && isset($result['url']) && isset($result['title']))
                                <a href="{{ $result['url'] }}"
                                   class="flex items-center px-3 py-2 hover:bg-red-50 dark:hover:bg-gray-700 transition-colors"
                                   wire:click="$set('showResults', false)">

                                    @if(isset($result['image']) && $result['image'])
                                        <img src="{{ asset('storage/' . $result['image']) }}"
                                             alt="{{ $result['title'] }}"
                                             class="w-10 h-10 object-cover rounded-lg mr-3 flex-shrink-0 shadow-sm"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <!-- Fallback UI khi ảnh lỗi -->
                                        <div class="w-10 h-10 bg-gradient-to-br from-red-100 to-red-200 dark:from-gray-600 dark:to-gray-700 rounded-lg mr-3 flex-shrink-0 items-center justify-center" style="display: none;">
                                            @if(isset($result['type']) && $result['type'] === 'course')
                                                <i class="fas fa-graduation-cap text-red-500 dark:text-red-400"></i>
                                            @else
                                                <i class="fas fa-newspaper text-red-500 dark:text-red-400"></i>
                                            @endif
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-gradient-to-br from-red-100 to-red-200 dark:from-gray-600 dark:to-gray-700 rounded-lg mr-3 flex-shrink-0 flex items-center justify-center">
                                            @if(isset($result['type']) && $result['type'] === 'course')
                                                <i class="fas fa-graduation-cap text-red-500 dark:text-red-400"></i>
                                            @else
                                                <i class="fas fa-newspaper text-red-500 dark:text-red-400"></i>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $result['title'] }}
                                        </div>

                                        @if(isset($result['type']) && $result['type'] === 'course')
                                            <div class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                                                @if(isset($result['price']))
                                                    <span class="text-red-600 dark:text-red-400 font-semibold">{{ $result['price'] }}</span>
                                                @endif
                                                @if(isset($result['duration']))
                                                    <span>{{ $result['duration'] }}</span>
                                                @endif
                                            </div>
                                        @elseif(isset($result['type']) && $result['type'] === 'post' && isset($result['excerpt']))
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                {{ Str::limit($result['excerpt'], 50) }}
                                            </div>
                                        @endif

                                        <div class="flex items-center mt-1">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium {{ isset($result['type']) && $result['type'] === 'course' ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200' : 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' }}">
                                                {{ isset($result['type']) && $result['type'] === 'course' ? 'Khóa học' : 'Bài viết' }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('hide-results-delayed', () => {
                setTimeout(() => {
                    @this.set('showResults', false);
                }, 200);
            });
        });
    </script>
</div>
