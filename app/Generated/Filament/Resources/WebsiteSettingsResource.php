<?php

namespace App\Generated\Filament\Resources;

use App\Generated\Filament\Resources\WebsiteSettingsResource\Pages;
use App\Generated\Models\WebsiteSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class WebsiteSettingsResource extends Resource
{
    protected static ?string $model = WebsiteSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    
    protected static ?string $navigationLabel = 'Cài đặt Website';
    
    protected static ?string $modelLabel = 'Cài đặt Website';
    
    protected static ?string $pluralModelLabel = 'Cài đặt Website';

    protected static ?string $navigationGroup = 'Hệ thống';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Cài đặt Website')
                    ->tabs([
                        // Tab 1: Thông tin cơ bản
                        Tabs\Tab::make('Thông tin cơ bản')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->label('Tên website')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('VD: Core Framework'),
                                        
                                        TextInput::make('site_tagline')
                                            ->label('Slogan')
                                            ->maxLength(255)
                                            ->placeholder('VD: Framework mạnh mẽ cho mọi dự án'),
                                    ]),
                                
                                Textarea::make('site_description')
                                    ->label('Mô tả website')
                                    ->rows(3)
                                    ->placeholder('Mô tả ngắn gọn về website của bạn'),
                                
                                Textarea::make('site_keywords')
                                    ->label('Từ khóa SEO')
                                    ->rows(2)
                                    ->placeholder('Các từ khóa cách nhau bằng dấu phẩy'),
                                
                                Grid::make(2)
                                    ->schema([
                                        FileUpload::make('site_logo')
                                            ->label('Logo website')
                                            ->image()
                                            ->directory('logos')
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/svg+xml'])
                                            ->maxSize(2048),
                                        
                                        FileUpload::make('site_favicon')
                                            ->label('Favicon')
                                            ->image()
                                            ->directory('favicons')
                                            ->acceptedFileTypes(['image/x-icon', 'image/png'])
                                            ->maxSize(512),
                                    ]),
                            ]),

                        // Tab 2: Thông tin liên hệ
                        Tabs\Tab::make('Thông tin liên hệ')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('contact_email')
                                            ->label('Email liên hệ')
                                            ->email()
                                            ->placeholder('info@example.com'),
                                        
                                        TextInput::make('contact_phone')
                                            ->label('Số điện thoại')
                                            ->tel()
                                            ->placeholder('+84 123 456 789'),
                                    ]),
                                
                                Textarea::make('contact_address')
                                    ->label('Địa chỉ')
                                    ->rows(3)
                                    ->placeholder('Địa chỉ đầy đủ của công ty'),
                                
                                Textarea::make('contact_working_hours')
                                    ->label('Giờ làm việc')
                                    ->rows(3)
                                    ->placeholder('VD: Thứ 2 - Thứ 6: 8:00 - 17:00'),
                            ]),

                        // Tab 3: Mạng xã hội
                        Tabs\Tab::make('Mạng xã hội')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('social_facebook')
                                            ->label('Facebook')
                                            ->url()
                                            ->placeholder('https://facebook.com/yourpage'),
                                        
                                        TextInput::make('social_twitter')
                                            ->label('Twitter')
                                            ->url()
                                            ->placeholder('https://twitter.com/yourhandle'),
                                        
                                        TextInput::make('social_instagram')
                                            ->label('Instagram')
                                            ->url()
                                            ->placeholder('https://instagram.com/yourhandle'),
                                        
                                        TextInput::make('social_youtube')
                                            ->label('YouTube')
                                            ->url()
                                            ->placeholder('https://youtube.com/yourchannel'),
                                        
                                        TextInput::make('social_linkedin')
                                            ->label('LinkedIn')
                                            ->url()
                                            ->placeholder('https://linkedin.com/company/yourcompany'),
                                        
                                        TextInput::make('social_tiktok')
                                            ->label('TikTok')
                                            ->url()
                                            ->placeholder('https://tiktok.com/@yourhandle'),
                                    ]),
                            ]),

                        // Tab 4: SEO & Analytics
                        Tabs\Tab::make('SEO & Analytics')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                Section::make('SEO Settings')
                                    ->schema([
                                        TextInput::make('seo_title_template')
                                            ->label('Template tiêu đề SEO')
                                            ->placeholder('{title} - {site_name}')
                                            ->helperText('Sử dụng {title} và {site_name} làm placeholder'),
                                        
                                        TextInput::make('seo_description_template')
                                            ->label('Template mô tả SEO')
                                            ->placeholder('{description} | {site_name}')
                                            ->helperText('Sử dụng {description} và {site_name} làm placeholder'),
                                        
                                        FileUpload::make('seo_og_image')
                                            ->label('Ảnh Open Graph mặc định')
                                            ->image()
                                            ->directory('seo')
                                            ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                            ->maxSize(2048),
                                    ]),
                                
                                Section::make('Analytics')
                                    ->schema([
                                        TextInput::make('analytics_google_id')
                                            ->label('Google Analytics ID')
                                            ->placeholder('G-XXXXXXXXXX'),
                                        
                                        TextInput::make('analytics_facebook_pixel')
                                            ->label('Facebook Pixel ID')
                                            ->placeholder('123456789012345'),
                                        
                                        TextInput::make('analytics_gtm_id')
                                            ->label('Google Tag Manager ID')
                                            ->placeholder('GTM-XXXXXXX'),
                                    ]),
                            ]),

                        // Tab 5: Email Settings
                        Tabs\Tab::make('Cài đặt Email')
                            ->icon('heroicon-o-envelope')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('smtp_host')
                                            ->label('SMTP Host')
                                            ->placeholder('smtp.gmail.com'),
                                        
                                        TextInput::make('smtp_port')
                                            ->label('SMTP Port')
                                            ->numeric()
                                            ->default(587),
                                        
                                        TextInput::make('smtp_username')
                                            ->label('SMTP Username')
                                            ->placeholder('your-email@gmail.com'),
                                        
                                        TextInput::make('smtp_password')
                                            ->label('SMTP Password')
                                            ->password()
                                            ->placeholder('Mật khẩu ứng dụng'),
                                        
                                        Select::make('smtp_encryption')
                                            ->label('Mã hóa')
                                            ->options(WebsiteSettings::getSmtpEncryptionOptions())
                                            ->default('tls'),
                                        
                                        TextInput::make('smtp_from_address')
                                            ->label('Email gửi đi')
                                            ->email()
                                            ->placeholder('noreply@yoursite.com'),
                                        
                                        TextInput::make('smtp_from_name')
                                            ->label('Tên người gửi')
                                            ->placeholder('Your Website Name'),
                                    ]),
                            ]),

                        // Tab 6: Localization
                        Tabs\Tab::make('Bản địa hóa')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        Select::make('currency_code')
                                            ->label('Tiền tệ')
                                            ->options(WebsiteSettings::getCurrencyOptions())
                                            ->default('VND'),
                                        
                                        Select::make('timezone')
                                            ->label('Múi giờ')
                                            ->options(WebsiteSettings::getTimezoneOptions())
                                            ->default('Asia/Ho_Chi_Minh'),
                                        
                                        Select::make('language')
                                            ->label('Ngôn ngữ')
                                            ->options(WebsiteSettings::getLanguageOptions())
                                            ->default('vi'),
                                        
                                        Select::make('date_format')
                                            ->label('Định dạng ngày')
                                            ->options(WebsiteSettings::getDateFormatOptions())
                                            ->default('d/m/Y'),
                                        
                                        Select::make('time_format')
                                            ->label('Định dạng giờ')
                                            ->options(WebsiteSettings::getTimeFormatOptions())
                                            ->default('H:i'),
                                    ]),
                            ]),

                        // Tab 7: System Settings
                        Tabs\Tab::make('Hệ thống')
                            ->icon('heroicon-o-server')
                            ->schema([
                                Section::make('Cài đặt chung')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('items_per_page')
                                                    ->label('Số item mỗi trang')
                                                    ->numeric()
                                                    ->default(12)
                                                    ->minValue(1)
                                                    ->maxValue(100),
                                                
                                                TextInput::make('max_upload_size')
                                                    ->label('Kích thước upload tối đa (KB)')
                                                    ->numeric()
                                                    ->default(10240)
                                                    ->helperText('1024 KB = 1 MB'),
                                            ]),
                                        
                                        TagsInput::make('allowed_file_types')
                                            ->label('Loại file được phép upload')
                                            ->placeholder('jpg, png, pdf, doc...')
                                            ->suggestions(['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx']),
                                    ]),
                                
                                Section::make('Backup')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Select::make('backup_frequency')
                                                    ->label('Tần suất backup')
                                                    ->options(WebsiteSettings::getBackupFrequencyOptions())
                                                    ->default('weekly'),
                                                
                                                TextInput::make('backup_retention_days')
                                                    ->label('Số ngày lưu backup')
                                                    ->numeric()
                                                    ->default(30)
                                                    ->minValue(1),
                                            ]),
                                    ]),
                            ]),

                        // Tab 8: Performance
                        Tabs\Tab::make('Hiệu suất')
                            ->icon('heroicon-o-bolt')
                            ->schema([
                                Section::make('Cache Settings')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Toggle::make('cache_enabled')
                                                    ->label('Bật cache')
                                                    ->default(true),
                                                
                                                TextInput::make('cache_duration')
                                                    ->label('Thời gian cache (giây)')
                                                    ->numeric()
                                                    ->default(3600)
                                                    ->helperText('3600 giây = 1 giờ'),
                                            ]),
                                    ]),
                                
                                Section::make('Optimization')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Toggle::make('compression_enabled')
                                                    ->label('Bật nén GZIP')
                                                    ->default(true),
                                                
                                                Toggle::make('lazy_loading')
                                                    ->label('Lazy loading ảnh')
                                                    ->default(true),
                                                
                                                Toggle::make('minify_css')
                                                    ->label('Minify CSS')
                                                    ->default(false),
                                                
                                                Toggle::make('minify_js')
                                                    ->label('Minify JavaScript')
                                                    ->default(false),
                                            ]),
                                        
                                        TextInput::make('cdn_url')
                                            ->label('CDN URL')
                                            ->url()
                                            ->placeholder('https://cdn.yoursite.com'),
                                    ]),
                            ]),

                        // Tab 9: Maintenance
                        Tabs\Tab::make('Bảo trì')
                            ->icon('heroicon-o-wrench-screwdriver')
                            ->schema([
                                Toggle::make('maintenance_mode')
                                    ->label('Chế độ bảo trì')
                                    ->helperText('Khi bật, chỉ admin có thể truy cập website'),
                                
                                Textarea::make('maintenance_message')
                                    ->label('Thông báo bảo trì')
                                    ->rows(3)
                                    ->placeholder('Website đang được bảo trì. Vui lòng quay lại sau.'),
                                
                                TagsInput::make('maintenance_allowed_ips')
                                    ->label('IP được phép truy cập')
                                    ->placeholder('127.0.0.1, 192.168.1.1...')
                                    ->helperText('Danh sách IP được phép truy cập khi bảo trì'),
                            ]),

                        // Tab 10: Custom Code
                        Tabs\Tab::make('Code tùy chỉnh')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Textarea::make('custom_css')
                                    ->label('CSS tùy chỉnh')
                                    ->rows(8)
                                    ->placeholder('/* CSS tùy chỉnh của bạn */'),
                                
                                Textarea::make('custom_js')
                                    ->label('JavaScript tùy chỉnh')
                                    ->rows(8)
                                    ->placeholder('// JavaScript tùy chỉnh của bạn'),
                                
                                Textarea::make('custom_head_code')
                                    ->label('Code trong <head>')
                                    ->rows(5)
                                    ->placeholder('<!-- Code sẽ được chèn vào <head> -->'),
                                
                                Textarea::make('custom_footer_code')
                                    ->label('Code trước </body>')
                                    ->rows(5)
                                    ->placeholder('<!-- Code sẽ được chèn trước </body> -->'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')
                    ->label('STT')
                    ->sortable()
                    ->width(60),

                ImageColumn::make('site_logo')
                    ->label('Logo')
                    ->width(60)
                    ->height(40),

                TextColumn::make('site_name')
                    ->label('Tên website')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('site_tagline')
                    ->label('Slogan')
                    ->limit(50)
                    ->toggleable(),

                TextColumn::make('contact_email')
                    ->label('Email')
                    ->toggleable(),

                TextColumn::make('contact_phone')
                    ->label('Điện thoại')
                    ->toggleable(),

                ToggleColumn::make('maintenance_mode')
                    ->label('Bảo trì'),

                ToggleColumn::make('cache_enabled')
                    ->label('Cache'),

                ToggleColumn::make('is_active')
                    ->label('Kích hoạt'),

                TextColumn::make('created_at')
                    ->label('Tạo lúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Trạng thái'),

                Tables\Filters\TernaryFilter::make('maintenance_mode')
                    ->label('Chế độ bảo trì'),

                Tables\Filters\TernaryFilter::make('cache_enabled')
                    ->label('Cache'),
            ])
            ->actions([
                Action::make('activate')
                    ->label('Kích hoạt')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (WebsiteSettings $record) {
                        $record->setActive();

                        Notification::make()
                            ->title('Đã kích hoạt cài đặt')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (WebsiteSettings $record) => !$record->is_active),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order')
            ->reorderable('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebsiteSettings::route('/'),
            'create' => Pages\CreateWebsiteSettings::route('/create'),
            'edit' => Pages\EditWebsiteSettings::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $activeCount = static::getModel()::where('is_active', true)->count();
        return $activeCount > 0 ? (string) $activeCount : null;
    }
}
