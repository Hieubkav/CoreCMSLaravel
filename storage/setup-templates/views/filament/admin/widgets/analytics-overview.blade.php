@php
    $data = $this->getViewData();
    $stats = $data['stats'] ?? [];
    $popularPosts = $data['popular_posts'] ?? [];
    $popularCourses = $data['popular_courses'] ?? [];
    $config = $data['config'] ?? null;
    $error = $data['error'] ?? null;
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Analytics Overview
            </div>
        </x-slot>

        @if($error)
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-red-800 text-sm">Lỗi: {{ $error }}</span>
                </div>
            </div>
        @else
            <!-- Quick Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-600 text-sm font-medium">Hôm nay</p>
                            <p class="text-2xl font-bold text-blue-900">{{ number_format($stats['today_unique'] ?? 0) }}</p>
                            <p class="text-blue-700 text-xs">Unique visitors</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-200 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-600 text-sm font-medium">Lượt xem</p>
                            <p class="text-2xl font-bold text-green-900">{{ number_format($stats['today_total'] ?? 0) }}</p>
                            <p class="text-green-700 text-xs">Hôm nay</p>
                        </div>
                        <div class="w-10 h-10 bg-green-200 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-600 text-sm font-medium">Tổng người</p>
                            <p class="text-2xl font-bold text-purple-900">{{ number_format($stats['total_unique'] ?? 0) }}</p>
                            <p class="text-purple-700 text-xs">Từ trước đến giờ</p>
                        </div>
                        <div class="w-10 h-10 bg-purple-200 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4 border border-orange-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-600 text-sm font-medium">Tổng lượt</p>
                            <p class="text-2xl font-bold text-orange-900">{{ number_format($stats['total_visits'] ?? 0) }}</p>
                            <p class="text-orange-700 text-xs">Từ trước đến giờ</p>
                        </div>
                        <div class="w-10 h-10 bg-orange-200 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Popular Content -->
            @if(count($popularPosts) > 0 || count($popularCourses) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Popular Posts -->
                    @if(count($popularPosts) > 0)
                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"></path>
                                </svg>
                                Bài viết phổ biến
                            </h4>
                            <div class="space-y-3">
                                @foreach($popularPosts as $post)
                                    <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50">
                                        @if(isset($post['thumbnail_link']) && $post['thumbnail_link'])
                                            <img src="{{ $post['thumbnail_link'] }}" alt="{{ $post['title'] }}" class="w-10 h-10 rounded object-cover">
                                        @else
                                            <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $post['title'] ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">{{ number_format($post['view_count'] ?? 0) }} lượt xem</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Popular Courses -->
                    @if(count($popularCourses) > 0)
                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                </svg>
                                Khóa học phổ biến
                            </h4>
                            <div class="space-y-3">
                                @foreach($popularCourses as $course)
                                    <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50">
                                        @if(isset($course['thumbnail_link']) && $course['thumbnail_link'])
                                            <img src="{{ $course['thumbnail_link'] }}" alt="{{ $course['title'] }}" class="w-10 h-10 rounded object-cover">
                                        @else
                                            <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $course['title'] ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">{{ number_format($course['view_count'] ?? 0) }} lượt xem</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Performance Info -->
            @if($config)
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>Cache: {{ $config->cache_duration }}s | Quality: {{ $config->webp_quality }}% | Max: {{ $config->max_width }}x{{ $config->max_height }}</span>
                        <span>Cập nhật: {{ now()->format('H:i:s') }}</span>
                    </div>
                </div>
            @endif
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
