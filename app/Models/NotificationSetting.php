<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'category',
        'email_enabled',
        'sms_enabled',
        'push_enabled',
        'database_enabled',
        'slack_enabled',
        'discord_enabled',
        'email_template_id',
        'email_recipients',
        'email_to_admin',
        'email_to_user',
        'sms_template',
        'sms_recipients',
        'push_title',
        'push_message',
        'push_icon',
        'push_action_url',
        'slack_webhook_url',
        'slack_channel',
        'discord_webhook_url',
        'trigger_conditions',
        'trigger_data',
        'require_user_opt_in',
        'frequency',
        'throttle_minutes',
        'max_per_day',
        'user_can_disable',
        'user_can_customize',
        'default_user_preferences',
        'priority',
        'group_key',
        'is_persistent',
        'status',
        'order',
    ];

    protected $casts = [
        'email_enabled' => 'boolean',
        'sms_enabled' => 'boolean',
        'push_enabled' => 'boolean',
        'database_enabled' => 'boolean',
        'slack_enabled' => 'boolean',
        'discord_enabled' => 'boolean',
        'email_template_id' => 'integer',
        'email_recipients' => 'array',
        'email_to_admin' => 'boolean',
        'email_to_user' => 'boolean',
        'sms_recipients' => 'array',
        'trigger_conditions' => 'array',
        'trigger_data' => 'array',
        'require_user_opt_in' => 'boolean',
        'throttle_minutes' => 'integer',
        'max_per_day' => 'integer',
        'user_can_disable' => 'boolean',
        'user_can_customize' => 'boolean',
        'default_user_preferences' => 'array',
        'is_persistent' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Quan hệ với email template
     */
    public function emailTemplate()
    {
        return $this->belongsTo(EmailTemplate::class, 'email_template_id');
    }

    /**
     * Scope cho notification settings active
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope sắp xếp theo order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    /**
     * Scope theo type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope theo category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope theo priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Lấy danh sách notification types
     */
    public static function getNotificationTypes(): array
    {
        return [
            'order_status' => 'Trạng thái đơn hàng',
            'payment_status' => 'Trạng thái thanh toán',
            'inventory_alert' => 'Cảnh báo tồn kho',
            'user_activity' => 'Hoạt động người dùng',
            'system_alert' => 'Cảnh báo hệ thống',
            'security_alert' => 'Cảnh báo bảo mật',
            'marketing' => 'Marketing',
            'newsletter' => 'Bản tin',
            'review_submitted' => 'Đánh giá mới',
            'contact_form' => 'Form liên hệ',
            'support_ticket' => 'Ticket hỗ trợ',
            'custom' => 'Tùy chỉnh',
        ];
    }

    /**
     * Lấy danh sách frequencies
     */
    public static function getFrequencies(): array
    {
        return [
            'immediate' => 'Ngay lập tức',
            'hourly' => 'Mỗi giờ',
            'daily' => 'Hàng ngày',
            'weekly' => 'Hàng tuần',
        ];
    }

    /**
     * Lấy danh sách priorities
     */
    public static function getPriorities(): array
    {
        return [
            'low' => 'Thấp',
            'normal' => 'Bình thường',
            'high' => 'Cao',
            'urgent' => 'Khẩn cấp',
        ];
    }

    /**
     * Lấy type label
     */
    public function getTypeLabelAttribute(): string
    {
        return static::getNotificationTypes()[$this->type] ?? $this->type;
    }

    /**
     * Lấy frequency label
     */
    public function getFrequencyLabelAttribute(): string
    {
        return static::getFrequencies()[$this->frequency] ?? $this->frequency;
    }

    /**
     * Lấy priority label
     */
    public function getPriorityLabelAttribute(): string
    {
        return static::getPriorities()[$this->priority] ?? $this->priority;
    }

    /**
     * Kiểm tra có thể gửi notification không
     */
    public function canSend(array $data = []): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        // Check trigger conditions
        if ($this->trigger_conditions && !$this->checkTriggerConditions($data)) {
            return false;
        }

        // Check throttling
        if ($this->throttle_minutes > 0 && $this->isThrottled()) {
            return false;
        }

        // Check daily limit
        if ($this->max_per_day && $this->getTodayCount() >= $this->max_per_day) {
            return false;
        }

        return true;
    }

    /**
     * Kiểm tra trigger conditions
     */
    private function checkTriggerConditions(array $data): bool
    {
        if (!$this->trigger_conditions) {
            return true;
        }

        foreach ($this->trigger_conditions as $condition) {
            $field = $condition['field'] ?? '';
            $operator = $condition['operator'] ?? '=';
            $value = $condition['value'] ?? '';

            $dataValue = data_get($data, $field);

            switch ($operator) {
                case '=':
                    if ($dataValue != $value) return false;
                    break;
                case '!=':
                    if ($dataValue == $value) return false;
                    break;
                case '>':
                    if ($dataValue <= $value) return false;
                    break;
                case '<':
                    if ($dataValue >= $value) return false;
                    break;
                case 'contains':
                    if (!str_contains($dataValue, $value)) return false;
                    break;
                case 'in':
                    if (!in_array($dataValue, (array) $value)) return false;
                    break;
            }
        }

        return true;
    }

    /**
     * Kiểm tra có bị throttle không
     */
    private function isThrottled(): bool
    {
        // This would check the last notification time for this setting
        // Implementation depends on how you track notification history
        return false;
    }

    /**
     * Lấy số lượng notification đã gửi hôm nay
     */
    private function getTodayCount(): int
    {
        // This would count notifications sent today for this setting
        // Implementation depends on how you track notification history
        return 0;
    }

    /**
     * Send notification
     */
    public function send(array $data = [], ?int $userId = null): array
    {
        $results = [];

        if (!$this->canSend($data)) {
            return [
                'success' => false,
                'message' => 'Notification cannot be sent due to conditions or throttling',
            ];
        }

        // Send email notification
        if ($this->email_enabled) {
            $results['email'] = $this->sendEmailNotification($data, $userId);
        }

        // Send SMS notification
        if ($this->sms_enabled) {
            $results['sms'] = $this->sendSmsNotification($data, $userId);
        }

        // Send push notification
        if ($this->push_enabled) {
            $results['push'] = $this->sendPushNotification($data, $userId);
        }

        // Save database notification
        if ($this->database_enabled) {
            $results['database'] = $this->saveDatabaseNotification($data, $userId);
        }

        // Send Slack notification
        if ($this->slack_enabled) {
            $results['slack'] = $this->sendSlackNotification($data);
        }

        // Send Discord notification
        if ($this->discord_enabled) {
            $results['discord'] = $this->sendDiscordNotification($data);
        }

        return [
            'success' => true,
            'results' => $results,
        ];
    }

    /**
     * Send email notification
     */
    private function sendEmailNotification(array $data, ?int $userId): bool
    {
        if (!$this->emailTemplate) {
            return false;
        }

        $recipients = [];

        if ($this->email_to_user && $userId) {
            $user = User::find($userId);
            if ($user) {
                $recipients[] = $user->email;
            }
        }

        if ($this->email_to_admin) {
            $adminEmail = config('mail.admin_email', 'admin@example.com');
            $recipients[] = $adminEmail;
        }

        if ($this->email_recipients) {
            $recipients = array_merge($recipients, $this->email_recipients);
        }

        $success = true;
        foreach (array_unique($recipients) as $email) {
            if (!$this->emailTemplate->sendEmail($email, $data)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Send SMS notification (placeholder)
     */
    private function sendSmsNotification(array $data, ?int $userId): bool
    {
        // Implement SMS sending logic
        return true;
    }

    /**
     * Send push notification (placeholder)
     */
    private function sendPushNotification(array $data, ?int $userId): bool
    {
        // Implement push notification logic
        return true;
    }

    /**
     * Save database notification (placeholder)
     */
    private function saveDatabaseNotification(array $data, ?int $userId): bool
    {
        // Implement database notification saving
        return true;
    }

    /**
     * Send Slack notification (placeholder)
     */
    private function sendSlackNotification(array $data): bool
    {
        // Implement Slack webhook logic
        return true;
    }

    /**
     * Send Discord notification (placeholder)
     */
    private function sendDiscordNotification(array $data): bool
    {
        // Implement Discord webhook logic
        return true;
    }

    /**
     * Route key name
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
