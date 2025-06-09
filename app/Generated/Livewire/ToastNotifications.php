<?php

namespace App\Generated\Livewire;

use Livewire\Component;

class ToastNotifications extends Component
{
    public array $notifications = [];
    public int $maxNotifications = 5;
    public int $autoHideDelay = 5000; // milliseconds

    protected $listeners = [
        'showToast' => 'addNotification',
        'hideToast' => 'removeNotification',
        'clearAllToasts' => 'clearAll',
    ];

    public function addNotification($type = 'info', $message = '', $title = '', $persistent = false)
    {
        $id = uniqid();
        
        $notification = [
            'id' => $id,
            'type' => $type, // success, error, warning, info
            'title' => $title,
            'message' => $message,
            'persistent' => $persistent,
            'timestamp' => now()->toISOString(),
        ];

        // Add to beginning of array
        array_unshift($this->notifications, $notification);

        // Limit number of notifications
        if (count($this->notifications) > $this->maxNotifications) {
            $this->notifications = array_slice($this->notifications, 0, $this->maxNotifications);
        }

        // Auto-hide non-persistent notifications
        if (!$persistent) {
            $this->dispatch('autoHideToast', $id, $this->autoHideDelay);
        }
    }

    public function removeNotification($id)
    {
        $this->notifications = array_filter($this->notifications, function ($notification) use ($id) {
            return $notification['id'] !== $id;
        });
        
        // Re-index array
        $this->notifications = array_values($this->notifications);
    }

    public function clearAll()
    {
        $this->notifications = [];
    }

    public function getIconForType($type): string
    {
        return match($type) {
            'success' => 'fas fa-check-circle',
            'error' => 'fas fa-exclamation-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'info' => 'fas fa-info-circle',
            default => 'fas fa-bell',
        };
    }

    public function getColorForType($type): string
    {
        return match($type) {
            'success' => 'green',
            'error' => 'red',
            'warning' => 'yellow',
            'info' => 'blue',
            default => 'gray',
        };
    }

    public function render()
    {
        return view('livewire.generated.toast-notifications');
    }
}
