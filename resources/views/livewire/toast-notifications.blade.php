<!-- Toast Notifications Container -->
<div class="fixed top-4 right-4 z-50 space-y-2" x-data="toastManager()">
    @foreach($notifications as $notification)
        @php
            $colors = $this->getNotificationColors($notification['type']);
            $icon = $this->getNotificationIcon($notification['type']);
        @endphp

        <div
            x-data="{
                show: false,
                id: '{{ $notification['id'] }}',
                duration: {{ $notification['duration'] }}
            }"
            x-init="
                show = true;
                if (duration > 0) {
                    setTimeout(() => {
                        show = false;
                        setTimeout(() => $wire.removeNotification(id), 300);
                    }, duration);
                }
            "
            x-show="show"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="max-w-sm w-full {{ $colors['bg'] }} {{ $colors['border'] }} border rounded-lg shadow-lg pointer-events-auto"
        >
            <div class="p-4">
                <div class="flex items-start">
                    <!-- Icon -->
                    <div class="flex-shrink-0">
                        <i class="{{ $icon }} {{ $colors['icon'] }} text-xl"></i>
                    </div>

                    <!-- Content -->
                    <div class="ml-3 w-0 flex-1">
                        @if($notification['title'])
                        <p class="text-sm font-medium {{ $colors['title'] }}">
                            {{ $notification['title'] }}
                        </p>
                        @endif

                        @if($notification['message'])
                        <p class="mt-1 text-sm {{ $colors['message'] }}">
                            {{ $notification['message'] }}
                        </p>
                        @endif

                        <!-- Actions -->
                        @if(!empty($notification['actions']))
                        <div class="mt-3 flex space-x-2">
                            @foreach($notification['actions'] as $action)
                            <button
                                wire:click="executeAction('{{ $notification['id'] }}', {{ json_encode($action) }})"
                                class="text-sm font-medium {{ $colors['title'] }} hover:{{ $colors['message'] }} transition-colors"
                            >
                                {{ $action['label'] }}
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- Close Button -->
                    @if($notification['dismissible'])
                    <div class="ml-4 flex-shrink-0 flex">
                        <button
                            @click="show = false; setTimeout(() => $wire.removeNotification(id), 300)"
                            class="{{ $colors['message'] }} hover:{{ $colors['title'] }} transition-colors"
                        >
                            <span class="sr-only">Đóng</span>
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Progress Bar (for timed notifications) -->
            @if($notification['duration'] > 0)
            <div class="h-1 {{ $colors['bg'] }} rounded-b-lg overflow-hidden">
                <div
                    class="h-full {{ str_replace('50', '200', $colors['bg']) }} transition-all ease-linear"
                    x-data="{ width: 100 }"
                    x-init="
                        setTimeout(() => {
                            width = 0;
                        }, 100);
                    "
                    :style="`width: ${width}%; transition-duration: ${duration}ms`"
                ></div>
            </div>
            @endif
        </div>
    @endforeach

    <!-- Clear All Button (when multiple notifications) -->
    @if(count($notifications) > 1)
    <div class="text-center mt-2">
        <button
            wire:click="clearAll"
            class="text-xs text-gray-500 hover:text-gray-700 transition-colors"
        >
            <i class="fas fa-times mr-1"></i>
            Xóa tất cả
        </button>
    </div>
    @endif
</div>
