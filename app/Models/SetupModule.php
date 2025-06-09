<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\ModuleVisibilityService;

class SetupModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_name',
        'module_title',
        'module_description',
        'is_installed',
        'is_required',
        'configuration',
        'installed_at',
    ];

    protected $casts = [
        'is_installed' => 'boolean',
        'is_required' => 'boolean',
        'configuration' => 'array',
        'installed_at' => 'datetime',
    ];

    /**
     * Lấy danh sách tất cả modules có thể cài đặt
     */
    public static function getAvailableModules(): array
    {
        return [
            'system_configuration' => [
                'title' => 'Cấu hình Hệ thống',
                'description' => 'Quản lý theme, màu sắc, font chữ và cấu hình giao diện',
                'required' => true,
                'icon' => 'fas fa-cogs'
            ],
            'user_roles' => [
                'title' => 'Phân quyền Người dùng',
                'description' => 'Hệ thống roles và permissions với Spatie Laravel Permission',
                'required' => false,
                'icon' => 'fas fa-users-cog'
            ],
            'blog_posts' => [
                'title' => 'Blog & Bài viết',
                'description' => 'Hệ thống quản lý bài viết, tin tức và trang nội dung',
                'required' => false,
                'icon' => 'fas fa-blog'
            ],
            'staff_management' => [
                'title' => 'Quản lý Nhân sự',
                'description' => 'Quản lý thông tin nhân viên, vị trí và liên hệ',
                'required' => false,
                'icon' => 'fas fa-user-tie'
            ],
            'content_sections' => [
                'title' => 'Các Phần Nội dung',
                'description' => 'Gallery, FAQ, Testimonials, Services, Features, v.v.',
                'required' => false,
                'icon' => 'fas fa-th-large'
            ],
            'layout_components' => [
                'title' => 'Thành phần Giao diện',
                'description' => 'Menu động, tìm kiếm, thông báo và các component layout',
                'required' => true,
                'icon' => 'fas fa-layer-group'
            ],
            'ecommerce' => [
                'title' => 'Thương mại Điện tử',
                'description' => 'Hệ thống bán hàng hoàn chỉnh với sản phẩm, đơn hàng, thanh toán',
                'required' => false,
                'icon' => 'fas fa-shopping-cart'
            ],
            'settings_expansion' => [
                'title' => 'Mở rộng Cài đặt',
                'description' => 'Thêm các trường cấu hình website, SEO, liên hệ và mạng xã hội',
                'required' => true,
                'icon' => 'fas fa-sliders-h'
            ],
            'web_design_management' => [
                'title' => 'Quản lý Thiết kế Web',
                'description' => 'Điều khiển hiển thị và thứ tự các component trên trang chủ',
                'required' => true,
                'icon' => 'fas fa-paint-brush'
            ]
        ];
    }

    /**
     * Kiểm tra module đã được cài đặt chưa
     */
    public static function isModuleInstalled(string $moduleName): bool
    {
        return static::where('module_name', $moduleName)
            ->where('is_installed', true)
            ->exists();
    }

    /**
     * Đánh dấu module đã được cài đặt
     */
    public static function markAsInstalled(string $moduleName, array $configuration = []): void
    {
        static::updateOrCreate(
            ['module_name' => $moduleName],
            [
                'is_installed' => true,
                'configuration' => $configuration,
                'installed_at' => now(),
            ]
        );

        // Clear module visibility cache
        ModuleVisibilityService::clearCache();
    }

    /**
     * Lấy danh sách modules đã cài đặt
     */
    public static function getInstalledModules(): array
    {
        return static::where('is_installed', true)
            ->pluck('module_name')
            ->toArray();
    }
}
