<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SystemConfigurationResource\Pages;
use App\Models\SystemConfiguration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;

class SystemConfigurationResource extends Resource
{
    protected static ?string $model = SystemConfiguration::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Cấu hình Hệ thống';

    protected static ?string $modelLabel = 'Cấu hình Hệ thống';

    protected static ?string $pluralModelLabel = 'Cấu hình Hệ thống';

    protected static ?string $navigationGroup = 'Hệ thống';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Cấu hình')
                    ->tabs([
                        Tabs\Tab::make('Giao diện & Theme')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                Section::make('Chế độ Theme')
                                    ->schema([
                                        Select::make('theme_mode')
                                            ->label('Chế độ Theme')
                                            ->options(SystemConfiguration::getThemeModeOptions())
                                            ->default('light_only')
                                            ->required()
                                            ->helperText('Chọn chế độ hiển thị theme cho website'),

                                        Select::make('design_style')
                                            ->label('Phong cách Thiết kế')
                                            ->options(SystemConfiguration::getDesignStyleOptions())
                                            ->default('minimalism')
                                            ->required()
                                            ->helperText('Phong cách tổng thể của giao diện'),
                                    ])->columns(2),

                                Section::make('Màu sắc')
                                    ->schema([
                                        ColorPicker::make('primary_color')
                                            ->label('Màu Chính')
                                            ->default('#dc2626')
                                            ->required()
                                            ->helperText('Màu chính của website (buttons, links, highlights)'),

                                        ColorPicker::make('secondary_color')
                                            ->label('Màu Phụ')
                                            ->default('#ffffff')
                                            ->required()
                                            ->helperText('Màu nền chính và các element phụ'),

                                        ColorPicker::make('accent_color')
                                            ->label('Màu Nhấn')
                                            ->default('#f3f4f6')
                                            ->required()
                                            ->helperText('Màu cho borders, backgrounds nhẹ'),
                                    ])->columns(3),

                                Section::make('Typography')
                                    ->schema([
                                        Select::make('primary_font')
                                            ->label('Font Chính')
                                            ->options(SystemConfiguration::getFontOptions())
                                            ->default('Inter')
                                            ->required()
                                            ->helperText('Font cho headings và text quan trọng'),

                                        Select::make('secondary_font')
                                            ->label('Font Phụ')
                                            ->options(SystemConfiguration::getFontOptions())
                                            ->default('Inter')
                                            ->required()
                                            ->helperText('Font cho body text'),

                                        Select::make('tertiary_font')
                                            ->label('Font Bổ sung')
                                            ->options(SystemConfiguration::getFontOptions())
                                            ->default('Inter')
                                            ->required()
                                            ->helperText('Font cho captions, small text'),
                                    ])->columns(3),
                            ]),

                        Tabs\Tab::make('Admin Panel & Tính năng')
                            ->icon('heroicon-o-squares-2x2')
                            ->schema([
                                Section::make('Màu sắc Admin Panel')
                                    ->schema([
                                        ColorPicker::make('admin_primary_color')
                                            ->label('Màu Chính Admin')
                                            ->default('#dc2626')
                                            ->required()
                                            ->helperText('Màu chính cho admin panel'),

                                        ColorPicker::make('admin_secondary_color')
                                            ->label('Màu Phụ Admin')
                                            ->default('#374151')
                                            ->required()
                                            ->helperText('Màu phụ cho admin panel'),
                                    ])->columns(2),

                                Section::make('Hệ thống & Tính năng')
                                    ->schema([
                                        Select::make('icon_system')
                                            ->label('Hệ thống Icon')
                                            ->options(SystemConfiguration::getIconSystemOptions())
                                            ->default('fontawesome')
                                            ->required()
                                            ->helperText('Chọn thư viện icon sử dụng'),

                                        Toggle::make('visitor_analytics_enabled')
                                            ->label('Bật Analytics')
                                            ->helperText('Theo dõi thống kê visitor'),

                                        CheckboxList::make('error_pages')
                                            ->label('Trang Lỗi')
                                            ->options(SystemConfiguration::getErrorPageOptions())
                                            ->helperText('Chọn các trang lỗi cần tạo')
                                            ->columns(2),
                                    ]),

                                Section::make('Favicon')
                                    ->schema([
                                        FileUpload::make('favicon_path')
                                            ->label('Favicon')
                                            ->image()
                                            ->acceptedFileTypes(['image/x-icon', 'image/png', 'image/jpeg'])
                                            ->maxSize(2048)
                                            ->directory('system/favicons')
                                            ->helperText('Upload file .ico, .png hoặc .jpg (tối đa 2MB)'),
                                    ]),
                            ]),

                        Tabs\Tab::make('Trạng thái')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Section::make('Cài đặt Hệ thống')
                                    ->schema([
                                        Select::make('status')
                                            ->label('Trạng thái')
                                            ->options([
                                                'active' => 'Kích hoạt',
                                                'inactive' => 'Tạm dừng'
                                            ])
                                            ->default('active')
                                            ->required(),

                                        Forms\Components\TextInput::make('order')
                                            ->label('Thứ tự')
                                            ->numeric()
                                            ->default(0)
                                            ->helperText('Thứ tự ưu tiên (số nhỏ hơn = ưu tiên cao hơn)'),
                                    ])->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('theme_mode')
                    ->label('Chế độ Theme')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'light_only' => 'warning',
                        'dark_only' => 'gray',
                        'both' => 'success',
                        'none' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\ColorColumn::make('primary_color')
                    ->label('Màu Chính'),

                Tables\Columns\TextColumn::make('design_style')
                    ->label('Phong cách')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('primary_font')
                    ->label('Font Chính'),

                Tables\Columns\TextColumn::make('icon_system')
                    ->label('Hệ thống Icon')
                    ->badge(),

                Tables\Columns\IconColumn::make('visitor_analytics_enabled')
                    ->label('Analytics')
                    ->boolean(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('theme_mode')
                    ->label('Chế độ Theme')
                    ->options(SystemConfiguration::getThemeModeOptions()),

                Tables\Filters\SelectFilter::make('design_style')
                    ->label('Phong cách')
                    ->options(SystemConfiguration::getDesignStyleOptions()),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Chỉnh sửa'),
            ])
            ->bulkActions([
                // Không cho phép xóa hàng loạt để tránh xóa nhầm cấu hình
            ])
            ->defaultSort('updated_at', 'desc')
            ->emptyStateHeading('Chưa có cấu hình hệ thống')
            ->emptyStateDescription('Tạo cấu hình đầu tiên để bắt đầu tùy chỉnh giao diện.')
            ->emptyStateIcon('heroicon-o-cog-6-tooth');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSystemConfigurations::route('/'),
            'create' => Pages\CreateSystemConfiguration::route('/create'),
            'edit' => Pages\EditSystemConfiguration::route('/{record}/edit'),
        ];
    }

    /**
     * Chỉ cho phép có 1 record duy nhất
     */
    public static function canCreate(): bool
    {
        return SystemConfiguration::count() === 0;
    }

    /**
     * Không cho phép xóa cấu hình hệ thống
     */
    public static function canDelete($record): bool
    {
        return false;
    }

    /**
     * Không cho phép xóa hàng loạt
     */
    public static function canDeleteAny(): bool
    {
        return false;
    }
}
