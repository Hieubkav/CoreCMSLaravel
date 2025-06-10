<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'subject',
        'html_content',
        'text_content',
        'type',
        'category',
        'from_email',
        'from_name',
        'reply_to',
        'cc_emails',
        'bcc_emails',
        'available_variables',
        'required_variables',
        'attachments',
        'layout_template',
        'styling_options',
        'is_automated',
        'trigger_conditions',
        'send_delay_minutes',
        'language',
        'parent_template_id',
        'status',
        'version',
        'is_default',
        'order',
    ];

    protected $casts = [
        'cc_emails' => 'array',
        'bcc_emails' => 'array',
        'available_variables' => 'array',
        'required_variables' => 'array',
        'attachments' => 'array',
        'styling_options' => 'array',
        'is_automated' => 'boolean',
        'trigger_conditions' => 'array',
        'send_delay_minutes' => 'integer',
        'parent_template_id' => 'integer',
        'is_default' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Quan hệ với parent template
     */
    public function parentTemplate()
    {
        return $this->belongsTo(EmailTemplate::class, 'parent_template_id');
    }

    /**
     * Quan hệ với child templates (translations)
     */
    public function childTemplates()
    {
        return $this->hasMany(EmailTemplate::class, 'parent_template_id');
    }

    /**
     * Quan hệ với notification settings
     */
    public function notificationSettings()
    {
        return $this->hasMany(NotificationSetting::class, 'email_template_id');
    }

    /**
     * Scope cho templates active
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
     * Scope cho default templates
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope theo language
     */
    public function scopeByLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    /**
     * Lấy danh sách template types
     */
    public static function getTemplateTypes(): array
    {
        return [
            'order_confirmation' => 'Xác nhận đơn hàng',
            'order_shipped' => 'Đơn hàng đã gửi',
            'order_delivered' => 'Đơn hàng đã giao',
            'order_cancelled' => 'Đơn hàng đã hủy',
            'payment_received' => 'Thanh toán thành công',
            'payment_failed' => 'Thanh toán thất bại',
            'refund_processed' => 'Hoàn tiền thành công',
            'user_welcome' => 'Chào mừng người dùng',
            'user_verification' => 'Xác thực tài khoản',
            'password_reset' => 'Đặt lại mật khẩu',
            'newsletter' => 'Bản tin',
            'promotional' => 'Khuyến mãi',
            'notification' => 'Thông báo',
            'contact_form' => 'Liên hệ',
            'support_ticket' => 'Hỗ trợ',
            'custom' => 'Tùy chỉnh',
        ];
    }

    /**
     * Lấy type label
     */
    public function getTypeLabelAttribute(): string
    {
        return static::getTemplateTypes()[$this->type] ?? $this->type;
    }

    /**
     * Render template với variables
     */
    public function render(array $variables = []): array
    {
        $subject = $this->renderContent($this->subject, $variables);
        $htmlContent = $this->renderContent($this->html_content, $variables);
        $textContent = $this->text_content ? $this->renderContent($this->text_content, $variables) : null;

        return [
            'subject' => $subject,
            'html_content' => $htmlContent,
            'text_content' => $textContent,
            'from_email' => $this->from_email,
            'from_name' => $this->from_name,
            'reply_to' => $this->reply_to,
            'cc_emails' => $this->cc_emails,
            'bcc_emails' => $this->bcc_emails,
        ];
    }

    /**
     * Render content với variables
     */
    private function renderContent(string $content, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $placeholder = '{{' . $key . '}}';
            $content = str_replace($placeholder, $value, $content);
        }

        // Handle conditional blocks
        $content = $this->renderConditionals($content, $variables);

        return $content;
    }

    /**
     * Render conditional blocks
     */
    private function renderConditionals(string $content, array $variables): string
    {
        // Handle {{#if variable}} ... {{/if}} blocks
        $pattern = '/\{\{#if\s+(\w+)\}\}(.*?)\{\{\/if\}\}/s';
        
        return preg_replace_callback($pattern, function ($matches) use ($variables) {
            $variable = $matches[1];
            $block = $matches[2];
            
            if (isset($variables[$variable]) && $variables[$variable]) {
                return $this->renderContent($block, $variables);
            }
            
            return '';
        }, $content);
    }

    /**
     * Validate required variables
     */
    public function validateVariables(array $variables): array
    {
        $missing = [];
        
        if ($this->required_variables) {
            foreach ($this->required_variables as $required) {
                if (!isset($variables[$required])) {
                    $missing[] = $required;
                }
            }
        }

        return $missing;
    }

    /**
     * Get default template for type
     */
    public static function getDefaultForType(string $type, string $language = 'vi'): ?self
    {
        return static::where('type', $type)
                    ->where('language', $language)
                    ->where('is_default', true)
                    ->where('status', 'active')
                    ->first();
    }

    /**
     * Clone template
     */
    public function cloneTemplate(string $newName, ?string $newLanguage = null): self
    {
        $clone = $this->replicate();
        $clone->name = $newName;
        $clone->slug = \Str::slug($newName);
        $clone->language = $newLanguage ?: $this->language;
        $clone->is_default = false;
        $clone->parent_template_id = $this->id;
        $clone->save();

        return $clone;
    }

    /**
     * Send email using this template
     */
    public function sendEmail(string $to, array $variables = [], array $options = []): bool
    {
        try {
            $missing = $this->validateVariables($variables);
            if (!empty($missing)) {
                throw new \Exception('Missing required variables: ' . implode(', ', $missing));
            }

            $rendered = $this->render($variables);

            // Use Laravel Mail to send
            \Mail::send([], [], function ($message) use ($to, $rendered, $options) {
                $message->to($to)
                       ->subject($rendered['subject'])
                       ->html($rendered['html_content']);

                if ($rendered['text_content']) {
                    $message->text($rendered['text_content']);
                }

                if ($rendered['from_email']) {
                    $message->from($rendered['from_email'], $rendered['from_name']);
                }

                if ($rendered['reply_to']) {
                    $message->replyTo($rendered['reply_to']);
                }

                if ($rendered['cc_emails']) {
                    $message->cc($rendered['cc_emails']);
                }

                if ($rendered['bcc_emails']) {
                    $message->bcc($rendered['bcc_emails']);
                }

                // Handle attachments
                if (isset($options['attachments'])) {
                    foreach ($options['attachments'] as $attachment) {
                        $message->attach($attachment);
                    }
                }
            });

            return true;

        } catch (\Exception $e) {
            \Log::error('Email template send failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Route key name
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
