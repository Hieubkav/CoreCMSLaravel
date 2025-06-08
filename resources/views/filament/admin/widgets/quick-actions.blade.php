<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    🚀
                </span>
                Thao tác nhanh
            </div>
        </x-slot>

        <x-slot name="description">
            Các tác vụ thường dùng trong hệ thống
        </x-slot>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
            @foreach($actions as $action)
                <a href="{{ $action['url'] }}" 
                   @if($action['external'] ?? false) target="_blank" @endif
                   class="quick-action-item group">
                    <div class="w-12 h-12 bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-900/30 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <x-filament::icon 
                            :icon="$action['icon']" 
                            class="w-6 h-6 text-{{ $action['color'] }}-600 dark:text-{{ $action['color'] }}-400"
                        />
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 block text-center">
                        {{ $action['label'] }}
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block text-center">
                        {{ $action['description'] }}
                    </span>
                </a>
            @endforeach
        </div>
    </x-filament::section>

    <style>
        .quick-action-item {
            @apply flex flex-col items-center p-4 rounded-xl border border-gray-200 dark:border-gray-700;
            @apply bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700;
            @apply transition-all duration-200 ease-in-out;
            text-decoration: none;
        }

        .quick-action-item:hover {
            @apply shadow-md border-gray-300 dark:border-gray-600;
            transform: translateY(-1px);
        }

        .quick-action-item:active {
            transform: translateY(0);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .quick-action-item {
                @apply p-3;
            }
            
            .quick-action-item span {
                @apply text-xs;
            }
        }
    </style>
</x-filament-widgets::widget>
