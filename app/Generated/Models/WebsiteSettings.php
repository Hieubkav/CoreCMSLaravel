<?php

namespace App\Generated\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class WebsiteSettings extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'site_name',
        'site_tagline',
        'site_description',
        'site_keywords',
        'site_logo',
        'site_favicon',
        'contact_email',
        'contact_phone',
        'contact_address',
        'contact_working_hours',
        'social_facebook',
        'social_twitter',
        'social_instagram',
        'social_youtube',
        'social_linkedin',
        'social_tiktok',
        'maintenance_mode',
        'maintenance_message',
        'maintenance_allowed_ips',
        'seo_title_template',
        'seo_description_template',
        'seo_og_image',
        'analytics_google_id',
        'analytics_facebook_pixel',
        'analytics_gtm_id',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'smtp_from_address',
        'smtp_from_name',
        'currency_code',
        'currency_symbol',
        'timezone',
        'date_format',
        'time_format',
        'language',
        'items_per_page',
        'max_upload_size',
        'allowed_file_types',
        'backup_frequency',
        'backup_retention_days',
        'cache_enabled',
        'cache_duration',
        'compression_enabled',
        'minify_css',
        'minify_js',
        'lazy_loading',
        'cdn_url',
        'custom_css',
        'custom_js',
        'custom_head_code',
        'custom_footer_code',
        'is_active',
        'order',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'maintenance_mode' => 'boolean',
        'maintenance_allowed_ips' => 'array',
        'allowed_file_types' => 'array',
        'cache_enabled' => 'boolean',
        'compression_enabled' => 'boolean',
        'minify_css' => 'boolean',
        'minify_js' => 'boolean',
        'lazy_loading' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
        'smtp_port' => 'integer',
        'max_upload_size' => 'integer',
        'backup_retention_days' => 'integer',
        'cache_duration' => 'integer',
        'items_per_page' => 'integer',
    ];

    /**
     * Default values for attributes.
     */
    protected $attributes = [
        'maintenance_mode' => false,
        'cache_enabled' => true,
        'compression_enabled' => true,
        'minify_css' => false,
        'minify_js' => false,
        'lazy_loading' => true,
        'is_active' => true,
        'order' => 0,
        'currency_code' => 'VND',
        'currency_symbol' => '₫',
        'timezone' => 'Asia/Ho_Chi_Minh',
        'date_format' => 'd/m/Y',
        'time_format' => 'H:i',
        'language' => 'vi',
        'items_per_page' => 12,
        'max_upload_size' => 10240, // 10MB in KB
        'backup_retention_days' => 30,
        'cache_duration' => 3600, // 1 hour
        'smtp_port' => 587,
        'smtp_encryption' => 'tls',
    ];

    /**
     * Currency options
     */
    public static function getCurrencyOptions(): array
    {
        return [
            'VND' => 'Việt Nam Đồng (₫)',
            'USD' => 'US Dollar ($)',
            'EUR' => 'Euro (€)',
            'GBP' => 'British Pound (£)',
            'JPY' => 'Japanese Yen (¥)',
            'CNY' => 'Chinese Yuan (¥)',
            'KRW' => 'Korean Won (₩)',
            'THB' => 'Thai Baht (฿)',
        ];
    }

    /**
     * Timezone options
     */
    public static function getTimezoneOptions(): array
    {
        return [
            'Asia/Ho_Chi_Minh' => 'Việt Nam (UTC+7)',
            'Asia/Bangkok' => 'Bangkok (UTC+7)',
            'Asia/Singapore' => 'Singapore (UTC+8)',
            'Asia/Tokyo' => 'Tokyo (UTC+9)',
            'Asia/Seoul' => 'Seoul (UTC+9)',
            'Asia/Shanghai' => 'Shanghai (UTC+8)',
            'Europe/London' => 'London (UTC+0)',
            'Europe/Paris' => 'Paris (UTC+1)',
            'America/New_York' => 'New York (UTC-5)',
            'America/Los_Angeles' => 'Los Angeles (UTC-8)',
        ];
    }

    /**
     * Language options
     */
    public static function getLanguageOptions(): array
    {
        return [
            'vi' => 'Tiếng Việt',
            'en' => 'English',
            'zh' => '中文',
            'ja' => '日本語',
            'ko' => '한국어',
            'th' => 'ไทย',
            'fr' => 'Français',
            'de' => 'Deutsch',
            'es' => 'Español',
        ];
    }

    /**
     * Date format options
     */
    public static function getDateFormatOptions(): array
    {
        return [
            'd/m/Y' => 'DD/MM/YYYY (31/12/2024)',
            'm/d/Y' => 'MM/DD/YYYY (12/31/2024)',
            'Y-m-d' => 'YYYY-MM-DD (2024-12-31)',
            'd-m-Y' => 'DD-MM-YYYY (31-12-2024)',
            'F j, Y' => 'Month DD, YYYY (December 31, 2024)',
            'j F Y' => 'DD Month YYYY (31 December 2024)',
        ];
    }

    /**
     * Time format options
     */
    public static function getTimeFormatOptions(): array
    {
        return [
            'H:i' => '24 giờ (14:30)',
            'h:i A' => '12 giờ (2:30 PM)',
            'H:i:s' => '24 giờ với giây (14:30:45)',
            'h:i:s A' => '12 giờ với giây (2:30:45 PM)',
        ];
    }

    /**
     * SMTP encryption options
     */
    public static function getSmtpEncryptionOptions(): array
    {
        return [
            'tls' => 'TLS',
            'ssl' => 'SSL',
            'none' => 'Không mã hóa',
        ];
    }

    /**
     * Backup frequency options
     */
    public static function getBackupFrequencyOptions(): array
    {
        return [
            'daily' => 'Hàng ngày',
            'weekly' => 'Hàng tuần',
            'monthly' => 'Hàng tháng',
            'manual' => 'Thủ công',
        ];
    }

    /**
     * Get active settings
     */
    public static function getActive(): ?self
    {
        return Cache::remember('website_settings_active', 3600, function () {
            return static::where('is_active', true)
                ->orderBy('order')
                ->first();
        });
    }

    /**
     * Set as active (deactivate others)
     */
    public function setActive(): bool
    {
        // Deactivate all other settings
        static::where('id', '!=', $this->id)->update(['is_active' => false]);
        
        // Activate this setting
        $result = $this->update(['is_active' => true]);
        
        // Clear cache
        $this->clearCache();
        
        return $result;
    }

    /**
     * Get setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $settings = static::getActive();
        
        if (!$settings) {
            return $default;
        }
        
        return $settings->getAttribute($key) ?? $default;
    }

    /**
     * Set setting value by key
     */
    public static function set(string $key, $value): bool
    {
        $settings = static::getActive();
        
        if (!$settings) {
            return false;
        }
        
        $result = $settings->update([$key => $value]);
        
        // Clear cache
        $settings->clearCache();
        
        return $result;
    }

    /**
     * Get all settings as array
     */
    public static function getAllSettings(): array
    {
        return Cache::remember('website_settings_all', 3600, function () {
            $settings = static::getActive();

            if (!$settings) {
                return [];
            }

            return $settings->toArray();
        });
    }

    /**
     * Get file type options
     */
    public static function getFileTypeOptions(): array
    {
        return [
            'jpg' => 'JPEG Images',
            'jpeg' => 'JPEG Images',
            'png' => 'PNG Images',
            'gif' => 'GIF Images',
            'webp' => 'WebP Images',
            'svg' => 'SVG Images',
            'pdf' => 'PDF Documents',
            'doc' => 'Word Documents',
            'docx' => 'Word Documents',
            'xls' => 'Excel Files',
            'xlsx' => 'Excel Files',
            'zip' => 'ZIP Archives',
            'rar' => 'RAR Archives',
            'mp4' => 'MP4 Videos',
            'mp3' => 'MP3 Audio',
        ];
    }

    /**
     * Get social media links
     */
    public function getSocialLinksAttribute(): array
    {
        return array_filter([
            'facebook' => $this->social_facebook,
            'twitter' => $this->social_twitter,
            'instagram' => $this->social_instagram,
            'youtube' => $this->social_youtube,
            'linkedin' => $this->social_linkedin,
            'tiktok' => $this->social_tiktok,
        ]);
    }

    /**
     * Get contact information
     */
    public function getContactInfoAttribute(): array
    {
        return array_filter([
            'email' => $this->contact_email,
            'phone' => $this->contact_phone,
            'address' => $this->contact_address,
            'working_hours' => $this->contact_working_hours,
        ]);
    }

    /**
     * Get analytics configuration
     */
    public function getAnalyticsConfigAttribute(): array
    {
        return array_filter([
            'google_analytics' => $this->analytics_google_id,
            'facebook_pixel' => $this->analytics_facebook_pixel,
            'google_tag_manager' => $this->analytics_gtm_id,
        ]);
    }

    /**
     * Clear settings cache
     */
    public function clearCache(): void
    {
        Cache::forget('website_settings_active');
        Cache::forget('website_settings_all');
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when settings are updated
        static::saved(function ($model) {
            $model->clearCache();
        });

        static::deleted(function ($model) {
            $model->clearCache();
        });

        // Auto-increment order for new records
        static::creating(function ($model) {
            if (is_null($model->order)) {
                $maxOrder = static::max('order');
                $model->order = ($maxOrder ?? 0) + 1;
            }
        });
    }
}
