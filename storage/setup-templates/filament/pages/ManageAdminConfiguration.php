<?php

namespace App\Filament\Admin\Pages;

use App\Models\AdminConfiguration;
use App\Traits\HandlesFileUploadFields;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ManageAdminConfiguration extends Page
{
    use HandlesFileUploadFields;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Cấu hình Admin';
    protected static ?string $title = 'Quản lý Cấu hình Admin Dashboard';
    protected static ?int $navigationSort = 95;
    protected static ?string $navigationGroup = 'Cài đặt';

    protected static string $view = 'filament.admin.pages.manage-admin-configuration';

    public ?array $data = [];

    public function mount(): void
    {
        $config = AdminConfiguration::current();

        if ($config && $config->exists) {
            $this->data = $config->toArray();
        } else {
            // Sử dụng cấu hình mặc định
            $this->data = $this->getDefaultConfig();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('AdminConfiguration')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Giao diện Admin')
                            ->schema([
                                Forms\Components\Section::make('Màu sắc Admin Panel')
                                    ->schema([
                                        Forms\Components\ColorPicker::make('admin_primary_color')
                                            ->label('Màu chính Admin')
                                            ->default('#1f2937')
                                            ->helperText('Màu chính cho admin panel (sidebar, header)'),

                                        Forms\Components\ColorPicker::make('admin_secondary_color')
                                            ->label('Màu phụ Admin')
                                            ->default('#374151')
                                            ->helperText('Màu phụ cho các elements trong admin'),
                                    ])->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Analytics & Tracking')
                            ->schema([
                                Forms\Components\Section::make('Thống kê truy cập')
                                    ->schema([
                                        Forms\Components\Toggle::make('visitor_analytics_enabled')
                                            ->label('Bật Analytics & Tracking')
                                            ->default(false)
                                            ->helperText('Hiển thị widgets thống kê visitor trên dashboard')
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                if (!$state) {
                                                    // Tắt các tính năng liên quan khi tắt analytics
                                                    $set('enable_visitor_tracking', false);
                                                }
                                            }),

                                        Forms\Components\Toggle::make('enable_visitor_tracking')
                                            ->label('Theo dõi chi tiết visitor')
                                            ->default(false)
                                            ->helperText('Lưu thông tin chi tiết về visitor (IP, User Agent, etc.)')
                                            ->visible(fn (callable $get) => $get('visitor_analytics_enabled')),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Tối ưu hiệu suất')
                            ->schema([
                                Forms\Components\Section::make('Cache & Performance')
                                    ->schema([
                                        Forms\Components\Toggle::make('query_cache')
                                            ->label('Query Cache')
                                            ->default(true)
                                            ->helperText('Cache các query database để tăng tốc'),

                                        Forms\Components\Toggle::make('eager_loading')
                                            ->label('Eager Loading')
                                            ->default(true)
                                            ->helperText('Tối ưu N+1 query problem'),

                                        Forms\Components\Toggle::make('asset_optimization')
                                            ->label('Asset Optimization')
                                            ->default(true)
                                            ->helperText('Minify CSS/JS và optimize assets'),

                                        Forms\Components\Select::make('cache_duration')
                                            ->label('Thời gian cache (giây)')
                                            ->options([
                                                60 => '1 phút',
                                                300 => '5 phút',
                                                600 => '10 phút',
                                                1800 => '30 phút',
                                                3600 => '1 giờ',
                                            ])
                                            ->default(300)
                                            ->required(),

                                        Forms\Components\Select::make('pagination_size')
                                            ->label('Số record mỗi trang')
                                            ->options([
                                                10 => '10 records',
                                                25 => '25 records',
                                                50 => '50 records',
                                                100 => '100 records',
                                            ])
                                            ->default(25)
                                            ->required(),
                                    ])->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Xử lý hình ảnh')
                            ->schema([
                                Forms\Components\Section::make('Image Processing')
                                    ->schema([
                                        Forms\Components\Select::make('webp_quality')
                                            ->label('Chất lượng WebP (%)')
                                            ->options([
                                                70 => '70% (Nhỏ nhất)',
                                                80 => '80% (Tốt)',
                                                90 => '90% (Rất tốt)',
                                                95 => '95% (Tuyệt vời)',
                                                100 => '100% (Lossless)',
                                            ])
                                            ->default(95)
                                            ->required()
                                            ->helperText('Chất lượng khi convert ảnh sang WebP'),

                                        Forms\Components\TextInput::make('max_width')
                                            ->label('Chiều rộng tối đa (px)')
                                            ->numeric()
                                            ->default(1920)
                                            ->required()
                                            ->minValue(800)
                                            ->maxValue(4000)
                                            ->helperText('Resize ảnh nếu vượt quá kích thước này'),

                                        Forms\Components\TextInput::make('max_height')
                                            ->label('Chiều cao tối đa (px)')
                                            ->numeric()
                                            ->default(1080)
                                            ->required()
                                            ->minValue(600)
                                            ->maxValue(3000)
                                            ->helperText('Resize ảnh nếu vượt quá kích thước này'),
                                    ])->columns(3),
                            ]),

                        Forms\Components\Tabs\Tab::make('SEO Configuration')
                            ->schema([
                                Forms\Components\Section::make('SEO Settings')
                                    ->schema([
                                        Forms\Components\Toggle::make('seo_auto_generate')
                                            ->label('Tự động sinh SEO')
                                            ->default(true)
                                            ->helperText('Tự động tạo meta title, description từ nội dung'),

                                        Forms\Components\Textarea::make('default_description')
                                            ->label('Mô tả mặc định')
                                            ->default('Powered by Core Framework')
                                            ->rows(3)
                                            ->maxLength(160)
                                            ->helperText('Mô tả mặc định khi không có SEO description'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Lưu cấu hình')
                ->action('save'),
            Action::make('reset')
                ->label('Reset về mặc định')
                ->color('gray')
                ->action('resetToDefault')
                ->requiresConfirmation(),
            Action::make('clear_cache')
                ->label('Xóa Cache')
                ->color('warning')
                ->action('clearCache')
                ->requiresConfirmation(),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Tạo hoặc cập nhật cấu hình
        $config = AdminConfiguration::updateOrCreateConfig($data);

        // Clear cache sau khi lưu
        $this->clearSystemCache();

        Notification::make()
            ->title('Đã lưu cấu hình admin thành công!')
            ->success()
            ->send();
    }

    public function resetToDefault(): void
    {
        $defaultData = $this->getDefaultConfig();
        $this->data = $defaultData;
        
        // Cập nhật form với dữ liệu mặc định
        $this->form->fill($defaultData);

        Notification::make()
            ->title('Đã reset về cấu hình mặc định!')
            ->success()
            ->send();
    }

    public function clearCache(): void
    {
        $this->clearSystemCache();

        Notification::make()
            ->title('Đã xóa cache thành công!')
            ->success()
            ->send();
    }

    private function getDefaultConfig(): array
    {
        return [
            'admin_primary_color' => '#1f2937',
            'admin_secondary_color' => '#374151',
            'visitor_analytics_enabled' => false,
            'enable_visitor_tracking' => false,
            'query_cache' => true,
            'eager_loading' => true,
            'asset_optimization' => true,
            'cache_duration' => 300,
            'pagination_size' => 25,
            'webp_quality' => 95,
            'max_width' => 1920,
            'max_height' => 1080,
            'seo_auto_generate' => true,
            'default_description' => 'Powered by Core Framework',
        ];
    }

    private function clearSystemCache(): void
    {
        try {
            // Clear Laravel cache
            \Illuminate\Support\Facades\Cache::flush();
            
            // Clear visitor stats cache
            if (class_exists(\App\Actions\System\GetVisitorStats::class)) {
                \App\Actions\System\GetVisitorStats::clearCache();
            }
            
            // Clear config cache
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            
        } catch (\Exception $e) {
            // Silent fail
        }
    }
}
