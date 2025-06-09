{{-- Toast Notifications Livewire Component --}}
<div class="fixed top-4 right-4 z-50 space-y-3" style="max-width: 400px;">
    @foreach($notifications as $notification)
        <div x-data="{ show: true }" 
             x-show="show"
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="bg-white rounded-lg shadow-lg border-l-4 border-{{ $this->getColorForType($notification['type']) }}-500 p-4 max-w-sm w-full">
            
            <div class="flex items-start">
                {{-- Icon --}}
                <div class="flex-shrink-0">
                    <i class="{{ $this->getIconForType($notification['type']) }} text-{{ $this->getColorForType($notification['type']) }}-500 text-lg"></i>
                </div>
                
                {{-- Content --}}
                <div class="ml-3 flex-1">
                    @if($notification['title'])
                        <h4 class="text-sm font-medium text-gray-900 mb-1">
                            {{ $notification['title'] }}
                        </h4>
                    @endif
                    
                    <p class="text-sm text-gray-700">
                        {{ $notification['message'] }}
                    </p>
                    
                    {{-- Timestamp --}}
                    <p class="text-xs text-gray-500 mt-1">
                        {{ \Carbon\Carbon::parse($notification['timestamp'])->diffForHumans() }}
                    </p>
                </div>
                
                {{-- Close Button --}}
                <div class="ml-4 flex-shrink-0">
                    <button wire:click="removeNotification('{{ $notification['id'] }}')"
                            @click="show = false"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition-colors">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
            
            {{-- Progress Bar (for auto-hide) --}}
            @if(!$notification['persistent'])
                <div class="mt-3 bg-gray-200 rounded-full h-1">
                    <div class="bg-{{ $this->getColorForType($notification['type']) }}-500 h-1 rounded-full animate-shrink" 
                         style="animation-duration: {{ $autoHideDelay }}ms;"></div>
                </div>
            @endif
        </div>
    @endforeach
    
    {{-- Clear All Button (when multiple notifications) --}}
    @if(count($notifications) > 1)
        <div class="text-center">
            <button wire:click="clearAll" 
                    class="text-xs text-gray-500 hover:text-gray-700 underline">
                Xóa tất cả thông báo
            </button>
        </div>
    @endif
</div>

{{-- Auto-hide Script --}}
<script>
document.addEventListener('livewire:init', function() {
    Livewire.on('autoHideToast', (id, delay) => {
        setTimeout(() => {
            Livewire.dispatch('hideToast', { id: id });
        }, delay);
    });
});

// Global toast helper functions
window.showToast = function(type, message, title = '', persistent = false) {
    Livewire.dispatch('showToast', { 
        type: type, 
        message: message, 
        title: title, 
        persistent: persistent 
    });
};

window.showSuccessToast = function(message, title = 'Thành công') {
    window.showToast('success', message, title);
};

window.showErrorToast = function(message, title = 'Lỗi') {
    window.showToast('error', message, title);
};

window.showWarningToast = function(message, title = 'Cảnh báo') {
    window.showToast('warning', message, title);
};

window.showInfoToast = function(message, title = 'Thông tin') {
    window.showToast('info', message, title);
};
</script>

{{-- CSS Animations --}}
<style>
@keyframes shrink {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}

.animate-shrink {
    animation: shrink linear forwards;
}

/* Toast notification styles for different types */
.toast-success {
    @apply border-green-500;
}

.toast-error {
    @apply border-red-500;
}

.toast-warning {
    @apply border-yellow-500;
}

.toast-info {
    @apply border-blue-500;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .toast-container {
        left: 1rem;
        right: 1rem;
        top: 1rem;
    }
    
    .toast-notification {
        max-width: none;
    }
}
</style>
