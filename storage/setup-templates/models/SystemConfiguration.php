<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'theme_mode',
        'primary_color',
        'secondary_color',
        'accent_color',
        'primary_font',
        'secondary_font',
        'tertiary_font',
        'design_style',
        'error_pages',
        'icon_system',
        'admin_primary_color',
        'admin_secondary_color',
        'visitor_analytics_enabled',
        'favicon_path',
        'status',
        'order',
    ];

    protected $casts = [
        'error_pages' => 'array',
        'visitor_analytics_enabled' => 'boolean',
    ];

    /**
     * Lấy cấu hình hệ thống hiện tại
     */
    public static function current(): ?self
    {
        return static::where('status', 'active')->first();
    }

    /**
     * Lấy hoặc tạo cấu hình mặc định
     */
    public static function getOrCreateDefault(): self
    {
        $config = static::current();
        
        if (!$config) {
            $config = static::create([
                'theme_mode' => 'light_only',
                'primary_color' => '#dc2626',
                'secondary_color' => '#ffffff',
                'accent_color' => '#f3f4f6',
                'primary_font' => 'Inter',
                'secondary_font' => 'Inter',
                'tertiary_font' => 'Inter',
                'design_style' => 'minimalism',
                'error_pages' => ['404', '500'],
                'icon_system' => 'fontawesome',
                'admin_primary_color' => '#dc2626',
                'admin_secondary_color' => '#374151',
                'visitor_analytics_enabled' => false,
                'status' => 'active',
                'order' => 0,
            ]);
        }
        
        return $config;
    }

    /**
     * Lấy danh sách theme modes có thể chọn
     */
    public static function getThemeModeOptions(): array
    {
        return [
            'light_only' => 'Chỉ sáng',
            'dark_only' => 'Chỉ tối',
            'both' => 'Cả hai (có toggle)',
            'none' => 'Không theme'
        ];
    }

    /**
     * Lấy danh sách fonts có thể chọn
     */
    public static function getFontOptions(): array
    {
        return [
            'Inter' => 'Inter (Hiện đại)',
            'Roboto' => 'Roboto (Google)',
            'Open Sans' => 'Open Sans (Dễ đọc)',
            'Poppins' => 'Poppins (Thân thiện)',
            'Nunito' => 'Nunito (Mềm mại)'
        ];
    }

    /**
     * Lấy danh sách design styles
     */
    public static function getDesignStyleOptions(): array
    {
        return [
            'minimalism' => 'Tối giản (Minimalism)',
            'glassmorphism' => 'Kính mờ (Glassmorphism)',
            'modern' => 'Hiện đại (Modern)',
            'classic' => 'Cổ điển (Classic)'
        ];
    }

    /**
     * Lấy danh sách icon systems
     */
    public static function getIconSystemOptions(): array
    {
        return [
            'fontawesome' => 'Font Awesome (Phong phú)',
            'heroicons' => 'Heroicons (Đơn giản)'
        ];
    }

    /**
     * Lấy danh sách error pages có thể tạo
     */
    public static function getErrorPageOptions(): array
    {
        return [
            '404' => 'Trang không tìm thấy (404)',
            '500' => 'Lỗi server (500)',
            'maintenance' => 'Bảo trì hệ thống',
            'offline' => 'Offline/Không có mạng'
        ];
    }

    /**
     * Kiểm tra error page có được bật không
     */
    public function hasErrorPage(string $type): bool
    {
        return in_array($type, $this->error_pages ?? []);
    }

    /**
     * Lấy CSS variables cho theme
     */
    public function getCssVariables(): array
    {
        return [
            '--primary-color' => $this->primary_color,
            '--secondary-color' => $this->secondary_color,
            '--accent-color' => $this->accent_color,
            '--primary-font' => $this->primary_font,
            '--secondary-font' => $this->secondary_font,
            '--tertiary-font' => $this->tertiary_font,
        ];
    }

    /**
     * Lấy Tailwind config cho theme
     */
    public function getTailwindConfig(): array
    {
        return [
            'colors' => [
                'primary' => $this->primary_color,
                'secondary' => $this->secondary_color,
                'accent' => $this->accent_color,
            ],
            'fontFamily' => [
                'primary' => [$this->primary_font, 'sans-serif'],
                'secondary' => [$this->secondary_font, 'sans-serif'],
                'tertiary' => [$this->tertiary_font, 'sans-serif'],
            ]
        ];
    }
}
