<?php

namespace App\Livewire;

use Livewire\Component;

class ToastNotifications extends Component
{
    public array $notifications = [];
    public int $maxNotifications = 5;
    public int $defaultDuration = 5000; // 5 seconds

    protected $listeners = [
        'showToast' => 'addNotification',
        'hideToast' => 'removeNotification',
        'clearAllToasts' => 'clearAll'
    ];

    /**
     * Thêm notification mới
     */
    public function addNotification(array $notification)
    {
        $notification = array_merge([
            'id' => uniqid(),
            'type' => 'info', // success, error, warning, info
            'title' => '',
            'message' => '',
            'duration' => $this->defaultDuration,
            'dismissible' => true,
            'actions' => [], // Array of action buttons
            'timestamp' => now()->toISOString()
        ], $notification);

        // Thêm vào đầu array
        array_unshift($this->notifications, $notification);

        // Giới hạn số lượng notifications
        if (count($this->notifications) > $this->maxNotifications) {
            $this->notifications = array_slice($this->notifications, 0, $this->maxNotifications);
        }

        // Auto remove nếu có duration
        if ($notification['duration'] > 0) {
            $this->dispatch('autoRemoveToast', [
                'id' => $notification['id'],
                'duration' => $notification['duration']
            ]);
        }
    }

    /**
     * Xóa notification
     */
    public function removeNotification(string $id)
    {
        $this->notifications = array_filter($this->notifications, function ($notification) use ($id) {
            return $notification['id'] !== $id;
        });

        // Re-index array
        $this->notifications = array_values($this->notifications);
    }

    /**
     * Xóa tất cả notifications
     */
    public function clearAll()
    {
        $this->notifications = [];
    }

    /**
     * Thực hiện action của notification
     */
    public function executeAction(string $notificationId, array $action)
    {
        // Tìm notification
        $notification = collect($this->notifications)->firstWhere('id', $notificationId);

        if (!$notification) {
            return;
        }

        // Thực hiện action dựa vào type
        switch ($action['type']) {
            case 'redirect':
                return redirect($action['url']);

            case 'emit':
                $this->dispatch($action['event'], $action['data'] ?? []);
                break;

            case 'refresh':
                return redirect()->refresh();

            case 'close':
                $this->removeNotification($notificationId);
                break;
        }

        // Auto close notification sau khi thực hiện action (nếu có flag)
        if ($action['autoClose'] ?? true) {
            $this->removeNotification($notificationId);
        }
    }

    /**
     * Lấy icon cho notification type
     */
    public function getNotificationIcon(string $type): string
    {
        return match($type) {
            'success' => 'fas fa-check-circle',
            'error' => 'fas fa-times-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'info' => 'fas fa-info-circle',
            default => 'fas fa-bell'
        };
    }

    /**
     * Lấy màu cho notification type
     */
    public function getNotificationColors(string $type): array
    {
        return match($type) {
            'success' => [
                'bg' => 'bg-green-50',
                'border' => 'border-green-200',
                'icon' => 'text-green-400',
                'title' => 'text-green-800',
                'message' => 'text-green-700'
            ],
            'error' => [
                'bg' => 'bg-red-50',
                'border' => 'border-red-200',
                'icon' => 'text-red-400',
                'title' => 'text-red-800',
                'message' => 'text-red-700'
            ],
            'warning' => [
                'bg' => 'bg-yellow-50',
                'border' => 'border-yellow-200',
                'icon' => 'text-yellow-400',
                'title' => 'text-yellow-800',
                'message' => 'text-yellow-700'
            ],
            'info' => [
                'bg' => 'bg-blue-50',
                'border' => 'border-blue-200',
                'icon' => 'text-blue-400',
                'title' => 'text-blue-800',
                'message' => 'text-blue-700'
            ],
            default => [
                'bg' => 'bg-gray-50',
                'border' => 'border-gray-200',
                'icon' => 'text-gray-400',
                'title' => 'text-gray-800',
                'message' => 'text-gray-700'
            ]
        };
    }

    public function render()
    {
        return view('livewire.toast-notifications');
    }
}
