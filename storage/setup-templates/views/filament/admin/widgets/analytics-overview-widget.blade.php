<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between">
                <span>📊 Thống kê Visitor Realtime</span>
                @if($stats['enabled'])
                    <x-filament::button 
                        size="sm" 
                        color="danger"
                        wire:click="resetStats"
                        wire:confirm="Bạn có chắc muốn reset tất cả thống kê visitor?"
                    >
                        🔄 Reset Stats
                    </x-filament::button>
                @endif
            </div>
        </x-slot>

        @if($stats['enabled'])
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Unique Visitors Hôm Nay -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Unique Visitors Hôm Nay</p>
                            <p class="text-2xl font-bold">{{ number_format($stats['today_unique']) }}</p>
                        </div>
                        <div class="text-blue-200">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Visits Hôm Nay -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Total Visits Hôm Nay</p>
                            <p class="text-2xl font-bold">{{ number_format($stats['today_total']) }}</p>
                        </div>
                        <div class="text-green-200">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Unique Visitors -->
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Total Unique Visitors</p>
                            <p class="text-2xl font-bold">{{ number_format($stats['total_unique']) }}</p>
                        </div>
                        <div class="text-purple-200">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Visits -->
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Total Visits</p>
                            <p class="text-2xl font-bold">{{ number_format($stats['total_visits']) }}</p>
                        </div>
                        <div class="text-orange-200">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin tracking -->
            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <span>🔄 Auto-refresh mỗi 5 giây</span>
                    <span>⏱️ Tracking interval: {{ $tracking_interval }} giây</span>
                    <span>📅 {{ now()->format('d/m/Y H:i:s') }}</span>
                </div>
            </div>
        @else
            <div class="text-center py-8">
                <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Visitor Analytics Chưa Được Bật</h3>
                <p class="text-gray-500 mb-4">Bật visitor analytics trong cài đặt admin để xem thống kê realtime.</p>
                <x-filament::button 
                    tag="a" 
                    href="{{ route('filament.admin.resources.admin-configurations.index') }}"
                    size="sm"
                >
                    Đi đến Cài Đặt
                </x-filament::button>
            </div>
        @endif
    </x-filament::section>

    <script>
        // Listen for reset event
        window.addEventListener('visitor-stats-reset', () => {
            // Refresh widget sau khi reset
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        });
    </script>
</x-filament-widgets::widget>
