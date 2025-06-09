<?php

namespace App\Generated\Filament\Resources;

use App\Generated\Filament\Resources\SystemConfigurationResource\Pages;
use App\Generated\Models\SystemConfiguration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\KeyValue;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class SystemConfigurationResource extends Resource
{
    protected static ?string $model = SystemConfiguration::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    
    protected static ?string $navigationLabel = 'Cấu hình hệ thống';
    
    protected static ?string $modelLabel = 'Cấu hình hệ thống';
    
    protected static ?string $pluralModelLabel = 'Cấu hình hệ thống';

    protected static ?string $navigationGroup = 'Hệ thống';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Cấu hình hệ thống')
                    ->tabs([
                        // Tab 1: Thông tin cơ bản
                        Tabs\Tab::make('Thông tin cơ bản')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Tên cấu hình')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('VD: Cấu hình mặc định'),
                                        
                                        Toggle::make('is_active')
                                            ->label('Kích hoạt')
                                            ->default(true)
                                            ->helperText('Chỉ một cấu hình có thể được kích hoạt tại một thời điểm'),
                                    ]),
                                
                                Textarea::make('description')
                                    ->label('Mô tả')
                                    ->rows(3)
                                    ->placeholder('Mô tả ngắn về cấu hình này'),
                                
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        TextInput::make('order')
                                            ->label('Thứ tự')
                                            ->numeric()
                                            ->default(0)
                                            ->helperText('Thứ tự hiển thị (số nhỏ hơn sẽ hiển thị trước)'),
                                    ]),
                            ]),

                        // Tab 2: Theme & Colors
                        Tabs\Tab::make('Theme & Màu sắc')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Select::make('theme_mode')
                                            ->label('Chế độ theme')
                                            ->options(SystemConfiguration::getThemeModes())
                                            ->default('light')
                                            ->required(),
                                        
                                        Select::make('design_style')
                                            ->label('Phong cách thiết kế')
                                            ->options(SystemConfiguration::getDesignStyles())
                                            ->default('minimalist')
                                            ->required(),
                                    ]),
                                
                                Forms\Components\Section::make('Màu sắc')
                                    ->schema([
                                        Forms\Components\Grid::make(3)
                                            ->schema([
                                                ColorPicker::make('primary_color')
                                                    ->label('Màu chính')
                                                    ->default('#dc2626')
                                                    ->required(),
                                                
                                                ColorPicker::make('secondary_color')
                                                    ->label('Màu phụ')
                                                    ->default('#6b7280')
                                                    ->required(),
                                                
                                                ColorPicker::make('accent_color')
                                                    ->label('Màu nhấn')
                                                    ->default('#3b82f6')
                                                    ->required(),
                                            ]),
                                    ]),
                            ]),

                        // Tab 3: Typography
                        Tabs\Tab::make('Typography')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Select::make('font_family')
                                            ->label('Font chữ')
                                            ->options(SystemConfiguration::getFontFamilies())
                                            ->default('Inter')
                                            ->required(),
                                        
                                        Select::make('font_size')
                                            ->label('Kích thước chữ')
                                            ->options(SystemConfiguration::getFontSizes())
                                            ->default('base')
                                            ->required(),
                                    ]),
                                
                                Select::make('icon_system')
                                    ->label('Hệ thống icon')
                                    ->options(SystemConfiguration::getIconSystems())
                                    ->default('fontawesome')
                                    ->required()
                                    ->helperText('Chọn bộ icon sẽ sử dụng trong toàn bộ website'),
                            ]),

                        // Tab 4: Assets
                        Tabs\Tab::make('Assets')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        FileUpload::make('favicon')
                                            ->label('Favicon')
                                            ->image()
                                            ->directory('system/favicon')
                                            ->acceptedFileTypes(['image/x-icon', 'image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'])
                                            ->maxSize(2048)
                                            ->helperText('Kích thước khuyến nghị: 32x32px hoặc 64x64px. Định dạng: ICO, PNG, JPG, SVG'),
                                        
                                        FileUpload::make('logo')
                                            ->label('Logo')
                                            ->image()
                                            ->directory('system/logo')
                                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'])
                                            ->maxSize(5120)
                                            ->helperText('Logo chính của website. Định dạng: PNG, JPG, SVG'),
                                    ]),
                            ]),

                        // Tab 5: Error Pages
                        Tabs\Tab::make('Trang lỗi')
                            ->icon('heroicon-o-exclamation-triangle')
                            ->schema([
                                KeyValue::make('error_pages')
                                    ->label('Cấu hình trang lỗi')
                                    ->keyLabel('Mã lỗi')
                                    ->valueLabel('Cấu hình')
                                    ->default([
                                        '404' => 'Trang không tìm thấy',
                                        '500' => 'Lỗi máy chủ',
                                        '503' => 'Bảo trì hệ thống',
                                    ])
                                    ->helperText('Cấu hình nội dung hiển thị cho các trang lỗi'),
                            ]),

                        // Tab 6: Analytics
                        Tabs\Tab::make('Analytics')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                KeyValue::make('analytics_config')
                                    ->label('Cấu hình Analytics')
                                    ->keyLabel('Dịch vụ')
                                    ->valueLabel('Mã tracking')
                                    ->default([
                                        'google_analytics' => '',
                                        'google_tag_manager' => '',
                                        'facebook_pixel' => '',
                                        'hotjar' => '',
                                    ])
                                    ->helperText('Nhập mã tracking cho các dịch vụ analytics'),
                            ]),

                        // Tab 7: Custom Code
                        Tabs\Tab::make('Custom Code')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Textarea::make('custom_css')
                                    ->label('CSS tùy chỉnh')
                                    ->rows(10)
                                    ->placeholder('/* CSS tùy chỉnh của bạn */')
                                    ->helperText('CSS này sẽ được thêm vào tất cả các trang'),
                                
                                Textarea::make('custom_js')
                                    ->label('JavaScript tùy chỉnh')
                                    ->rows(10)
                                    ->placeholder('// JavaScript tùy chỉnh của bạn')
                                    ->helperText('JavaScript này sẽ được thêm vào tất cả các trang'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('STT')
                    ->sortable()
                    ->width(60),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên cấu hình')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('theme_mode')
                    ->label('Theme')
                    ->colors([
                        'primary' => 'light',
                        'warning' => 'dark',
                        'success' => 'auto',
                    ]),
                
                Tables\Columns\TextColumn::make('design_style')
                    ->label('Phong cách')
                    ->badge(),
                
                Tables\Columns\ColorColumn::make('primary_color')
                    ->label('Màu chính'),
                
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Kích hoạt')
                    ->beforeStateUpdated(function ($record, $state) {
                        if ($state) {
                            // Deactivate all other configurations
                            SystemConfiguration::where('id', '!=', $record->id)
                                ->update(['is_active' => false]);
                        }
                    }),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tạo lúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('theme_mode')
                    ->label('Chế độ theme')
                    ->options(SystemConfiguration::getThemeModes()),
                
                Tables\Filters\SelectFilter::make('design_style')
                    ->label('Phong cách')
                    ->options(SystemConfiguration::getDesignStyles()),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Trạng thái'),
            ])
            ->actions([
                Action::make('activate')
                    ->label('Kích hoạt')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => !$record->is_active)
                    ->action(function ($record) {
                        $record->setActive();
                        Notification::make()
                            ->title('Đã kích hoạt cấu hình')
                            ->success()
                            ->send();
                    }),
                
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
            'index' => Pages\ListSystemConfigurations::route('/'),
            'create' => Pages\CreateSystemConfiguration::route('/create'),
            'edit' => Pages\EditSystemConfiguration::route('/{record}/edit'),
        ];
    }
}
