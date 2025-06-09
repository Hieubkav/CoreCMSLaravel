<?php

namespace App\Filament\Admin\Pages;

use App\Models\WebDesign;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;

class ManageWebDesign extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static string $view = 'filament.admin.pages.manage-web-design';

    public ?array $data = [];

    protected static ?string $navigationLabel = 'Quản lý Giao diện';

    public function mount(): void
    {
        $webDesign = WebDesign::getInstance();
        $this->form->fill($webDesign->toArray());
    }

    protected static ?string $title = 'Quản lý Giao diện Trang chủ';

    protected static ?string $navigationGroup = 'Cấu hình Website';

    protected static ?int $navigationSort = 1;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Quick Stats Section
                Section::make('Tổng quan')
                    ->description('Thống kê nhanh về các section trang chủ')
                    ->schema([
                        Placeholder::make('stats')
                            ->content(function () {
                                $webDesign = WebDesign::getInstance();
                                $enabledCount = $webDesign->getEnabledSectionsCount();
                                $totalCount = $webDesign->getTotalSectionsCount();
                                
                                return view('filament.admin.components.web-design-quick-stats', [
                                    'enabledCount' => $enabledCount,
                                    'totalCount' => $totalCount,
                                    'webDesign' => $webDesign
                                ]);
                            })
                    ])
                    ->collapsible(),

                // Main Configuration Tabs
                Tabs::make('Sections')
                    ->tabs([
                        // Hero Banner Tab
                        Tabs\Tab::make('Hero Banner')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Grid::make(2)->schema([
                                    Toggle::make('hero_banner_enabled')
                                        ->label('Hiển thị Hero Banner')
                                        ->default(true),
                                    
                                    TextInput::make('hero_banner_order')
                                        ->label('Thứ tự hiển thị')
                                        ->numeric()
                                        ->default(1)
                                        ->minValue(1)
                                        ->maxValue(10),
                                ]),
                                
                                TextInput::make('hero_banner_title')
                                    ->label('Tiêu đề tùy chỉnh')
                                    ->placeholder('Để trống sẽ sử dụng tiêu đề mặc định'),
                                
                                Textarea::make('hero_banner_description')
                                    ->label('Mô tả tùy chỉnh')
                                    ->placeholder('Để trống sẽ sử dụng mô tả mặc định')
                                    ->rows(3),
                                
                                Select::make('hero_banner_bg_color')
                                    ->label('Màu nền')
                                    ->options([
                                        'bg-white' => 'Trắng',
                                        'bg-gray-50' => 'Xám nhạt',
                                        'bg-red-50' => 'Đỏ nhạt',
                                        'bg-blue-50' => 'Xanh dương nhạt',
                                        'bg-green-50' => 'Xanh lá nhạt',
                                        'bg-yellow-50' => 'Vàng nhạt',
                                        'bg-purple-50' => 'Tím nhạt',
                                        'bg-pink-50' => 'Hồng nhạt',
                                    ])
                                    ->default('bg-white'),
                            ]),

                        // Courses Overview Tab
                        Tabs\Tab::make('Khóa học')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                Grid::make(2)->schema([
                                    Toggle::make('courses_overview_enabled')
                                        ->label('Hiển thị Tổng quan Khóa học')
                                        ->default(true),
                                    
                                    TextInput::make('courses_overview_order')
                                        ->label('Thứ tự hiển thị')
                                        ->numeric()
                                        ->default(2)
                                        ->minValue(1)
                                        ->maxValue(10),
                                ]),
                                
                                TextInput::make('courses_overview_title')
                                    ->label('Tiêu đề')
                                    ->default('Khóa học nổi bật'),
                                
                                Textarea::make('courses_overview_description')
                                    ->label('Mô tả')
                                    ->rows(3),
                                
                                Select::make('courses_overview_bg_color')
                                    ->label('Màu nền')
                                    ->options([
                                        'bg-white' => 'Trắng',
                                        'bg-gray-50' => 'Xám nhạt',
                                        'bg-red-50' => 'Đỏ nhạt',
                                        'bg-blue-50' => 'Xanh dương nhạt',
                                        'bg-green-50' => 'Xanh lá nhạt',
                                        'bg-yellow-50' => 'Vàng nhạt',
                                        'bg-purple-50' => 'Tím nhạt',
                                        'bg-pink-50' => 'Hồng nhạt',
                                    ])
                                    ->default('bg-gray-50'),
                            ]),

                        // Album Timeline Tab
                        Tabs\Tab::make('Album')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Grid::make(2)->schema([
                                    Toggle::make('album_timeline_enabled')
                                        ->label('Hiển thị Album Timeline')
                                        ->default(true),
                                    
                                    TextInput::make('album_timeline_order')
                                        ->label('Thứ tự hiển thị')
                                        ->numeric()
                                        ->default(3)
                                        ->minValue(1)
                                        ->maxValue(10),
                                ]),
                                
                                TextInput::make('album_timeline_title')
                                    ->label('Tiêu đề')
                                    ->default('Hình ảnh hoạt động'),
                                
                                Textarea::make('album_timeline_description')
                                    ->label('Mô tả')
                                    ->rows(3),
                                
                                Select::make('album_timeline_bg_color')
                                    ->label('Màu nền')
                                    ->options([
                                        'bg-white' => 'Trắng',
                                        'bg-gray-50' => 'Xám nhạt',
                                        'bg-red-50' => 'Đỏ nhạt',
                                        'bg-blue-50' => 'Xanh dương nhạt',
                                        'bg-green-50' => 'Xanh lá nhạt',
                                        'bg-yellow-50' => 'Vàng nhạt',
                                        'bg-purple-50' => 'Tím nhạt',
                                        'bg-pink-50' => 'Hồng nhạt',
                                    ])
                                    ->default('bg-white'),
                            ]),

                        // Testimonials Tab
                        Tabs\Tab::make('Đánh giá')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->schema([
                                Grid::make(2)->schema([
                                    Toggle::make('testimonials_enabled')
                                        ->label('Hiển thị Đánh giá Học viên')
                                        ->default(true),
                                    
                                    TextInput::make('testimonials_order')
                                        ->label('Thứ tự hiển thị')
                                        ->numeric()
                                        ->default(6)
                                        ->minValue(1)
                                        ->maxValue(10),
                                ]),
                                
                                TextInput::make('testimonials_title')
                                    ->label('Tiêu đề')
                                    ->default('Đánh giá học viên'),
                                
                                Textarea::make('testimonials_description')
                                    ->label('Mô tả')
                                    ->rows(3),
                                
                                Select::make('testimonials_bg_color')
                                    ->label('Màu nền')
                                    ->options([
                                        'bg-white' => 'Trắng',
                                        'bg-gray-50' => 'Xám nhạt',
                                        'bg-red-50' => 'Đỏ nhạt',
                                        'bg-blue-50' => 'Xanh dương nhạt',
                                        'bg-green-50' => 'Xanh lá nhạt',
                                        'bg-yellow-50' => 'Vàng nhạt',
                                        'bg-purple-50' => 'Tím nhạt',
                                        'bg-pink-50' => 'Hồng nhạt',
                                    ])
                                    ->default('bg-red-50'),
                            ]),

                        // FAQ Tab
                        Tabs\Tab::make('FAQ')
                            ->icon('heroicon-o-question-mark-circle')
                            ->schema([
                                Grid::make(2)->schema([
                                    Toggle::make('faq_enabled')
                                        ->label('Hiển thị FAQ')
                                        ->default(true),

                                    TextInput::make('faq_order')
                                        ->label('Thứ tự hiển thị')
                                        ->numeric()
                                        ->default(7)
                                        ->minValue(1)
                                        ->maxValue(10),
                                ]),

                                TextInput::make('faq_title')
                                    ->label('Tiêu đề')
                                    ->default('Câu hỏi thường gặp'),

                                Textarea::make('faq_description')
                                    ->label('Mô tả')
                                    ->rows(3),

                                Select::make('faq_bg_color')
                                    ->label('Màu nền')
                                    ->options([
                                        'bg-white' => 'Trắng',
                                        'bg-gray-50' => 'Xám nhạt',
                                        'bg-red-50' => 'Đỏ nhạt',
                                        'bg-blue-50' => 'Xanh dương nhạt',
                                        'bg-green-50' => 'Xanh lá nhạt',
                                        'bg-yellow-50' => 'Vàng nhạt',
                                        'bg-purple-50' => 'Tím nhạt',
                                        'bg-pink-50' => 'Hồng nhạt',
                                    ])
                                    ->default('bg-white'),
                            ]),

                        // Partners Tab
                        Tabs\Tab::make('Đối tác')
                            ->icon('heroicon-o-building-office-2')
                            ->schema([
                                Grid::make(2)->schema([
                                    Toggle::make('partners_enabled')
                                        ->label('Hiển thị Đối tác')
                                        ->default(true),

                                    TextInput::make('partners_order')
                                        ->label('Thứ tự hiển thị')
                                        ->numeric()
                                        ->default(8)
                                        ->minValue(1)
                                        ->maxValue(10),
                                ]),

                                TextInput::make('partners_title')
                                    ->label('Tiêu đề')
                                    ->default('Đối tác'),

                                Textarea::make('partners_description')
                                    ->label('Mô tả')
                                    ->rows(3),

                                Select::make('partners_bg_color')
                                    ->label('Màu nền')
                                    ->options([
                                        'bg-white' => 'Trắng',
                                        'bg-gray-50' => 'Xám nhạt',
                                        'bg-red-50' => 'Đỏ nhạt',
                                        'bg-blue-50' => 'Xanh dương nhạt',
                                        'bg-green-50' => 'Xanh lá nhạt',
                                        'bg-yellow-50' => 'Vàng nhạt',
                                        'bg-purple-50' => 'Tím nhạt',
                                        'bg-pink-50' => 'Hồng nhạt',
                                    ])
                                    ->default('bg-gray-50'),
                            ]),

                        // Blog Posts Tab
                        Tabs\Tab::make('Blog')
                            ->icon('heroicon-o-newspaper')
                            ->schema([
                                Grid::make(2)->schema([
                                    Toggle::make('blog_posts_enabled')
                                        ->label('Hiển thị Tin tức Blog')
                                        ->default(true),

                                    TextInput::make('blog_posts_order')
                                        ->label('Thứ tự hiển thị')
                                        ->numeric()
                                        ->default(9)
                                        ->minValue(1)
                                        ->maxValue(10),
                                ]),

                                TextInput::make('blog_posts_title')
                                    ->label('Tiêu đề')
                                    ->default('Tin tức mới nhất'),

                                Textarea::make('blog_posts_description')
                                    ->label('Mô tả')
                                    ->rows(3),

                                Select::make('blog_posts_bg_color')
                                    ->label('Màu nền')
                                    ->options([
                                        'bg-white' => 'Trắng',
                                        'bg-gray-50' => 'Xám nhạt',
                                        'bg-red-50' => 'Đỏ nhạt',
                                        'bg-blue-50' => 'Xanh dương nhạt',
                                        'bg-green-50' => 'Xanh lá nhạt',
                                        'bg-yellow-50' => 'Vàng nhạt',
                                        'bg-purple-50' => 'Tím nhạt',
                                        'bg-pink-50' => 'Hồng nhạt',
                                    ])
                                    ->default('bg-white'),
                            ]),

                        // Homepage CTA Tab
                        Tabs\Tab::make('Call to Action')
                            ->icon('heroicon-o-megaphone')
                            ->schema([
                                Grid::make(2)->schema([
                                    Toggle::make('homepage_cta_enabled')
                                        ->label('Hiển thị Call to Action')
                                        ->default(true),

                                    TextInput::make('homepage_cta_order')
                                        ->label('Thứ tự hiển thị')
                                        ->numeric()
                                        ->default(10)
                                        ->minValue(1)
                                        ->maxValue(10),
                                ]),

                                TextInput::make('homepage_cta_title')
                                    ->label('Tiêu đề')
                                    ->default('Bắt đầu học ngay hôm nay'),

                                Textarea::make('homepage_cta_description')
                                    ->label('Mô tả')
                                    ->default('Tham gia cùng hàng nghìn học viên đã tin tưởng chúng tôi')
                                    ->rows(3),

                                Grid::make(2)->schema([
                                    TextInput::make('homepage_cta_primary_button_text')
                                        ->label('Text nút chính')
                                        ->default('Xem khóa học'),

                                    TextInput::make('homepage_cta_primary_button_link')
                                        ->label('Link nút chính')
                                        ->default('/courses'),
                                ]),

                                Grid::make(2)->schema([
                                    TextInput::make('homepage_cta_secondary_button_text')
                                        ->label('Text nút phụ')
                                        ->default('Đăng ký ngay'),

                                    TextInput::make('homepage_cta_secondary_button_link')
                                        ->label('Link nút phụ')
                                        ->default('/register'),
                                ]),

                                Select::make('homepage_cta_bg_color')
                                    ->label('Màu nền')
                                    ->options([
                                        'bg-red-600' => 'Đỏ đậm',
                                        'bg-blue-600' => 'Xanh dương đậm',
                                        'bg-green-600' => 'Xanh lá đậm',
                                        'bg-purple-600' => 'Tím đậm',
                                        'bg-gray-800' => 'Xám đậm',
                                        'bg-indigo-600' => 'Indigo đậm',
                                    ])
                                    ->default('bg-red-600'),
                            ]),

                        // Global Settings Tab
                        Tabs\Tab::make('Cài đặt chung')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Section::make('Hiệu ứng và Animation')
                                    ->schema([
                                        Toggle::make('animations_enabled')
                                            ->label('Bật hiệu ứng animation')
                                            ->default(true)
                                            ->helperText('Bật/tắt tất cả hiệu ứng animation trên trang chủ'),

                                        Select::make('animation_speed')
                                            ->label('Tốc độ animation')
                                            ->options([
                                                'slow' => 'Chậm',
                                                'normal' => 'Bình thường',
                                                'fast' => 'Nhanh',
                                            ])
                                            ->default('normal')
                                            ->visible(fn ($get) => $get('animations_enabled')),

                                        Select::make('enabled_animations')
                                            ->label('Loại hiệu ứng')
                                            ->multiple()
                                            ->options([
                                                'fade' => 'Fade In/Out',
                                                'slide' => 'Slide',
                                                'scale' => 'Scale',
                                                'bounce' => 'Bounce',
                                                'rotate' => 'Rotate',
                                            ])
                                            ->default(['fade', 'slide'])
                                            ->visible(fn ($get) => $get('animations_enabled'))
                                            ->helperText('Chọn các loại hiệu ứng muốn sử dụng'),
                                    ]),

                                Section::make('Tối ưu hóa')
                                    ->schema([
                                        Toggle::make('lazy_loading_enabled')
                                            ->label('Bật Lazy Loading')
                                            ->default(true)
                                            ->helperText('Tải hình ảnh khi cần thiết để tăng tốc độ trang'),

                                        TextInput::make('cache_duration')
                                            ->label('Thời gian cache (giây)')
                                            ->numeric()
                                            ->default(3600)
                                            ->minValue(300)
                                            ->maxValue(86400)
                                            ->helperText('Thời gian lưu cache cho các section (300-86400 giây)'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('clearCache')
                ->label('Xóa Cache')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->action(function () {
                    Cache::forget('web_design_settings');
                    Cache::forget('homepage_sections');
                    Cache::forget('homepage_layout');
                    
                    Notification::make()
                        ->title('Đã xóa cache thành công!')
                        ->success()
                        ->send();
                }),
                
            Actions\Action::make('previewHomepage')
                ->label('Xem trước Trang chủ')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url('/', shouldOpenInNewTab: true),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $webDesign = WebDesign::getInstance();
        $webDesign->update($data);

        Notification::make()
            ->success()
            ->title('Đã lưu cài đặt giao diện!')
            ->body('Cache đã được tự động xóa để áp dụng thay đổi.')
            ->send();
    }

    protected function getSavedNotification(): ?\Filament\Notifications\Notification
    {
        return Notification::make()
            ->success()
            ->title('Đã lưu cài đặt giao diện!')
            ->body('Cache đã được tự động xóa để áp dụng thay đổi.');
    }
}
