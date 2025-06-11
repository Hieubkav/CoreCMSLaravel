<?php

namespace App\Filament\Admin\Pages;

use App\Models\FrontendConfiguration;
use App\Traits\HandlesFileUploadFields;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ManageFrontendConfiguration extends Page
{
    use HandlesFileUploadFields;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $navigationLabel = 'Cấu hình Frontend';
    protected static ?string $title = 'Quản lý Cấu hình Frontend';
    protected static ?int $navigationSort = 90;
    protected static ?string $navigationGroup = 'Cài đặt';

    protected static string $view = 'filament.admin.pages.manage-frontend-configuration';

    public ?array $data = [];

    public function mount(): void
    {
        $config = FrontendConfiguration::getActiveConfig();

        if ($config) {
            $this->data = $config->toArray();
        } else {
            // Sử dụng cấu hình mặc định
            $this->data = (array) FrontendConfiguration::getDefaultConfig();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('FrontendConfiguration')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Giao diện')
                            ->schema([
                                Forms\Components\Section::make('Theme Configuration')
                                    ->schema([
                                        Forms\Components\Select::make('theme_mode')
                                            ->label('Chế độ theme')
                                            ->options([
                                                'light' => 'Sáng',
                                                'dark' => 'Tối',
                                                'auto' => 'Tự động',
                                            ])
                                            ->default('light')
                                            ->required(),

                                        Forms\Components\Select::make('design_style')
                                            ->label('Phong cách thiết kế')
                                            ->options([
                                                'minimalist' => 'Tối giản',
                                                'modern' => 'Hiện đại',
                                                'classic' => 'Cổ điển',
                                            ])
                                            ->default('minimalist')
                                            ->required(),

                                        Forms\Components\Select::make('icon_system')
                                            ->label('Hệ thống icon')
                                            ->options([
                                                'fontawesome' => 'Font Awesome',
                                                'heroicons' => 'Heroicons',
                                                'custom' => 'Tùy chỉnh',
                                            ])
                                            ->default('fontawesome')
                                            ->required(),
                                    ])->columns(3),

                                Forms\Components\Section::make('Color Scheme')
                                    ->schema([
                                        Forms\Components\ColorPicker::make('primary_color')
                                            ->label('Màu chính')
                                            ->default('#dc2626'),

                                        Forms\Components\ColorPicker::make('secondary_color')
                                            ->label('Màu phụ')
                                            ->default('#f97316'),

                                        Forms\Components\ColorPicker::make('accent_color')
                                            ->label('Màu nhấn')
                                            ->default('#059669'),

                                        Forms\Components\ColorPicker::make('background_color')
                                            ->label('Màu nền')
                                            ->default('#ffffff'),

                                        Forms\Components\ColorPicker::make('text_color')
                                            ->label('Màu chữ')
                                            ->default('#1f2937'),
                                    ])->columns(3),
                            ]),

                        Forms\Components\Tabs\Tab::make('Typography')
                            ->schema([
                                Forms\Components\Section::make('Font Settings')
                                    ->schema([
                                        Forms\Components\Select::make('font_family')
                                            ->label('Font chữ')
                                            ->options([
                                                'Inter' => 'Inter',
                                                'Roboto' => 'Roboto',
                                                'Open Sans' => 'Open Sans',
                                                'Lato' => 'Lato',
                                                'Montserrat' => 'Montserrat',
                                            ])
                                            ->default('Inter')
                                            ->required(),

                                        Forms\Components\Select::make('font_size')
                                            ->label('Kích thước font')
                                            ->options([
                                                'sm' => 'Nhỏ',
                                                'base' => 'Trung bình',
                                                'lg' => 'Lớn',
                                                'xl' => 'Rất lớn',
                                            ])
                                            ->default('base')
                                            ->required(),

                                        Forms\Components\Select::make('font_weight')
                                            ->label('Độ đậm font')
                                            ->options([
                                                'light' => 'Nhẹ',
                                                'normal' => 'Bình thường',
                                                'medium' => 'Trung bình',
                                                'semibold' => 'Hơi đậm',
                                                'bold' => 'Đậm',
                                            ])
                                            ->default('normal')
                                            ->required(),
                                    ])->columns(3),
                            ]),

                        Forms\Components\Tabs\Tab::make('Layout')
                            ->schema([
                                Forms\Components\Section::make('Layout Settings')
                                    ->schema([
                                        Forms\Components\Select::make('container_width')
                                            ->label('Độ rộng container')
                                            ->options([
                                                'max-w-4xl' => 'Nhỏ (4xl)',
                                                'max-w-5xl' => 'Trung bình (5xl)',
                                                'max-w-6xl' => 'Lớn (6xl)',
                                                'max-w-7xl' => 'Rất lớn (7xl)',
                                                'max-w-9xl' => 'Cực lớn (9xl)',
                                            ])
                                            ->default('max-w-7xl')
                                            ->required(),

                                        Forms\Components\Toggle::make('enable_breadcrumbs')
                                            ->label('Hiển thị breadcrumbs')
                                            ->default(true),

                                        Forms\Components\Toggle::make('enable_back_to_top')
                                            ->label('Nút về đầu trang')
                                            ->default(true),

                                        Forms\Components\Toggle::make('enable_loading_spinner')
                                            ->label('Loading spinner')
                                            ->default(true),
                                    ])->columns(2),

                                Forms\Components\Section::make('Navigation')
                                    ->schema([
                                        Forms\Components\Toggle::make('sticky_navbar')
                                            ->label('Navbar dính')
                                            ->default(true),

                                        Forms\Components\Toggle::make('show_search_bar')
                                            ->label('Hiển thị thanh tìm kiếm')
                                            ->default(true),

                                        Forms\Components\Toggle::make('show_language_switcher')
                                            ->label('Chuyển đổi ngôn ngữ')
                                            ->default(false),

                                        Forms\Components\Select::make('menu_style')
                                            ->label('Kiểu menu')
                                            ->options([
                                                'horizontal' => 'Ngang',
                                                'vertical' => 'Dọc',
                                                'dropdown' => 'Dropdown',
                                            ])
                                            ->default('horizontal')
                                            ->required(),
                                    ])->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Performance')
                            ->schema([
                                Forms\Components\Section::make('Performance & SEO')
                                    ->schema([
                                        Forms\Components\Toggle::make('enable_lazy_loading')
                                            ->label('Lazy loading')
                                            ->default(true),

                                        Forms\Components\Toggle::make('enable_image_optimization')
                                            ->label('Tối ưu hóa ảnh')
                                            ->default(true),

                                        Forms\Components\Toggle::make('enable_minification')
                                            ->label('Minification')
                                            ->default(true),

                                        Forms\Components\Toggle::make('enable_caching')
                                            ->label('Caching')
                                            ->default(true),
                                    ])->columns(2),

                                Forms\Components\Section::make('Error Pages')
                                    ->schema([
                                        Forms\Components\CheckboxList::make('error_pages')
                                            ->label('Trang lỗi tùy chỉnh')
                                            ->options([
                                                '404' => 'Không tìm thấy (404)',
                                                '500' => 'Lỗi server (500)',
                                                '503' => 'Bảo trì (503)',
                                                'maintenance' => 'Bảo trì',
                                                'offline' => 'Offline',
                                            ])
                                            ->columns(2),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Custom Code')
                            ->schema([
                                Forms\Components\Section::make('Custom CSS/JS')
                                    ->schema([
                                        Forms\Components\Textarea::make('custom_css')
                                            ->label('CSS tùy chỉnh')
                                            ->rows(8)
                                            ->placeholder('/* CSS tùy chỉnh của bạn */'),

                                        Forms\Components\Textarea::make('custom_js')
                                            ->label('JavaScript tùy chỉnh')
                                            ->rows(8)
                                            ->placeholder('// JavaScript tùy chỉnh của bạn'),

                                        Forms\Components\Textarea::make('custom_head_tags')
                                            ->label('Thẻ head tùy chỉnh')
                                            ->rows(5)
                                            ->placeholder('<!-- Thẻ meta, script, style tùy chỉnh -->'),
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
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Tạo hoặc cập nhật cấu hình
        $config = FrontendConfiguration::updateOrCreateConfig($data);

        Notification::make()
            ->title('Đã lưu cấu hình frontend thành công!')
            ->success()
            ->send();
    }

    public function resetToDefault(): void
    {
        $defaultData = (array) FrontendConfiguration::getDefaultConfig();
        $this->data = $defaultData;
        
        // Cập nhật form với dữ liệu mặc định
        $this->form->fill($defaultData);

        Notification::make()
            ->title('Đã reset về cấu hình mặc định!')
            ->success()
            ->send();
    }
}
