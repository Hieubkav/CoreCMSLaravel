<?php

namespace App\Generated\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemConfiguration extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'theme_mode',
        'primary_color',
        'secondary_color',
        'accent_color',
        'font_family',
        'font_size',
        'design_style',
        'favicon',
        'logo',
        'error_pages',
        'icon_system',
        'analytics_config',
        'custom_css',
        'custom_js',
        'is_active',
        'order',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'error_pages' => 'array',
        'analytics_config' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Default values for attributes.
     */
    protected $attributes = [
        'theme_mode' => 'light',
        'primary_color' => '#dc2626',
        'secondary_color' => '#6b7280',
        'accent_color' => '#3b82f6',
        'font_family' => 'Inter',
        'font_size' => 'base',
        'design_style' => 'minimalist',
        'icon_system' => 'fontawesome',
        'is_active' => true,
        'order' => 0,
    ];

    /**
     * Theme mode options
     */
    public static function getThemeModes(): array
    {
        return [
            'light' => 'Sáng',
            'dark' => 'Tối',
            'auto' => 'Tự động',
        ];
    }

    /**
     * Font family options
     */
    public static function getFontFamilies(): array
    {
        return [
            'Inter' => 'Inter (Modern)',
            'Roboto' => 'Roboto (Google)',
            'Open Sans' => 'Open Sans (Clean)',
            'Lato' => 'Lato (Friendly)',
            'Montserrat' => 'Montserrat (Elegant)',
            'Poppins' => 'Poppins (Rounded)',
            'Nunito' => 'Nunito (Soft)',
            'Source Sans Pro' => 'Source Sans Pro (Professional)',
        ];
    }

    /**
     * Font size options
     */
    public static function getFontSizes(): array
    {
        return [
            'sm' => 'Nhỏ (14px)',
            'base' => 'Trung bình (16px)',
            'lg' => 'Lớn (18px)',
            'xl' => 'Rất lớn (20px)',
        ];
    }

    /**
     * Design style options
     */
    public static function getDesignStyles(): array
    {
        return [
            'minimalist' => 'Tối giản',
            'modern' => 'Hiện đại',
            'classic' => 'Cổ điển',
            'creative' => 'Sáng tạo',
            'corporate' => 'Doanh nghiệp',
            'elegant' => 'Thanh lịch',
        ];
    }

    /**
     * Icon system options
     */
    public static function getIconSystems(): array
    {
        return [
            'fontawesome' => 'Font Awesome',
            'heroicons' => 'Heroicons',
            'feather' => 'Feather Icons',
            'tabler' => 'Tabler Icons',
            'phosphor' => 'Phosphor Icons',
        ];
    }

    /**
     * Get active configuration
     */
    public static function getActive(): ?self
    {
        return static::where('is_active', true)
            ->orderBy('order')
            ->first();
    }

    /**
     * Set as active configuration
     */
    public function setActive(): bool
    {
        // Deactivate all other configurations
        static::where('id', '!=', $this->id)->update(['is_active' => false]);
        
        // Activate this configuration
        return $this->update(['is_active' => true]);
    }

    /**
     * Get CSS variables for theme
     */
    public function getCssVariables(): array
    {
        return [
            '--color-primary' => $this->primary_color,
            '--color-secondary' => $this->secondary_color,
            '--color-accent' => $this->accent_color,
            '--font-family' => $this->font_family,
            '--font-size-base' => $this->getFontSizeValue(),
        ];
    }

    /**
     * Get font size value in pixels
     */
    private function getFontSizeValue(): string
    {
        $sizes = [
            'sm' => '14px',
            'base' => '16px',
            'lg' => '18px',
            'xl' => '20px',
        ];

        return $sizes[$this->font_size] ?? '16px';
    }

    /**
     * Get analytics configuration
     */
    public function getAnalyticsConfig(): array
    {
        return $this->analytics_config ?? [
            'google_analytics' => null,
            'google_tag_manager' => null,
            'facebook_pixel' => null,
            'hotjar' => null,
            'custom_scripts' => null,
        ];
    }

    /**
     * Get error pages configuration
     */
    public function getErrorPagesConfig(): array
    {
        return $this->error_pages ?? [
            '404' => [
                'title' => 'Trang không tìm thấy',
                'message' => 'Xin lỗi, trang bạn đang tìm kiếm không tồn tại.',
                'show_search' => true,
                'show_home_link' => true,
            ],
            '500' => [
                'title' => 'Lỗi máy chủ',
                'message' => 'Đã xảy ra lỗi không mong muốn. Vui lòng thử lại sau.',
                'show_contact' => true,
            ],
            '503' => [
                'title' => 'Bảo trì hệ thống',
                'message' => 'Website đang được bảo trì. Vui lòng quay lại sau.',
                'show_countdown' => false,
            ],
        ];
    }

    /**
     * Scope for active configurations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered configurations
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    /**
     * Get favicon URL
     */
    public function getFaviconUrlAttribute(): ?string
    {
        return $this->favicon ? asset('storage/' . $this->favicon) : null;
    }

    /**
     * Get logo URL
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-increment order for new records
        static::creating(function ($model) {
            if (is_null($model->order)) {
                $model->order = static::max('order') + 1;
            }
        });
    }
}
