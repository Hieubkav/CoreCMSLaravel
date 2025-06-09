<?php

namespace App\Generated\Filament\Resources;

use App\Generated\Filament\Resources\WebDesignResource\Pages;
use App\Generated\Models\WebDesign;
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
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class WebDesignResource extends Resource
{
    protected static ?string $model = WebDesign::class;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    
    protected static ?string $navigationLabel = 'Thiết kế Web';
    
    protected static ?string $modelLabel = 'Thiết kế Web';
    
    protected static ?string $pluralModelLabel = 'Thiết kế Web';

    protected static ?string $navigationGroup = 'Giao diện';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Thiết kế Web')
                    ->tabs([
                        // Tab 1: Thông tin cơ bản
                        Tabs\Tab::make('Thông tin cơ bản')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Tên thiết kế')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('VD: Modern Red Theme'),
                                        
                                        Select::make('theme_type')
                                            ->label('Loại theme')
                                            ->options(WebDesign::getThemeTypes())
                                            ->default('modern')
                                            ->required(),
                                    ]),
                                
                                Textarea::make('description')
                                    ->label('Mô tả')
                                    ->rows(3)
                                    ->placeholder('Mô tả về thiết kế này'),
                                
                                Grid::make(2)
                                    ->schema([
                                        Select::make('color_scheme')
                                            ->label('Chế độ màu')
                                            ->options(WebDesign::getColorSchemes())
                                            ->default('light')
                                            ->required(),
                                        
                                        FileUpload::make('preview_image')
                                            ->label('Ảnh preview')
                                            ->image()
                                            ->directory('design-previews')
                                            ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                            ->maxSize(2048),
                                    ]),
                            ]),

                        // Tab 2: Màu sắc
                        Tabs\Tab::make('Màu sắc')
                            ->icon('heroicon-o-swatch')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        ColorPicker::make('primary_color')
                                            ->label('Màu chính')
                                            ->default('#dc2626'),
                                        
                                        ColorPicker::make('secondary_color')
                                            ->label('Màu phụ')
                                            ->default('#6b7280'),
                                        
                                        ColorPicker::make('accent_color')
                                            ->label('Màu nhấn')
                                            ->default('#f59e0b'),
                                        
                                        ColorPicker::make('background_color')
                                            ->label('Màu nền')
                                            ->default('#ffffff'),
                                        
                                        ColorPicker::make('text_color')
                                            ->label('Màu chữ')
                                            ->default('#1f2937'),
                                        
                                        ColorPicker::make('link_color')
                                            ->label('Màu liên kết')
                                            ->default('#dc2626'),
                                        
                                        ColorPicker::make('border_color')
                                            ->label('Màu viền')
                                            ->default('#e5e7eb'),
                                    ]),
                            ]),

                        // Tab 3: Typography
                        Tabs\Tab::make('Typography')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('font_family_primary')
                                            ->label('Font chính')
                                            ->options(WebDesign::getFontFamilies())
                                            ->default('Inter')
                                            ->searchable(),
                                        
                                        Select::make('font_family_secondary')
                                            ->label('Font phụ')
                                            ->options(WebDesign::getFontFamilies())
                                            ->default('Inter')
                                            ->searchable(),
                                        
                                        TextInput::make('font_size_base')
                                            ->label('Kích thước font cơ bản (px)')
                                            ->numeric()
                                            ->default(16)
                                            ->minValue(12)
                                            ->maxValue(24),
                                        
                                        TextInput::make('font_weight_normal')
                                            ->label('Độ đậm font thường')
                                            ->numeric()
                                            ->default(400)
                                            ->minValue(100)
                                            ->maxValue(900)
                                            ->step(100),
                                        
                                        TextInput::make('font_weight_bold')
                                            ->label('Độ đậm font đậm')
                                            ->numeric()
                                            ->default(600)
                                            ->minValue(100)
                                            ->maxValue(900)
                                            ->step(100),
                                        
                                        TextInput::make('line_height')
                                            ->label('Chiều cao dòng')
                                            ->default('1.5')
                                            ->placeholder('1.5'),
                                        
                                        TextInput::make('letter_spacing')
                                            ->label('Khoảng cách chữ')
                                            ->default('0')
                                            ->placeholder('0px'),
                                    ]),
                            ]),

                        // Tab 4: Layout
                        Tabs\Tab::make('Layout')
                            ->icon('heroicon-o-squares-2x2')
                            ->schema([
                                Section::make('Layout Settings')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('layout_style')
                                                    ->label('Kiểu layout')
                                                    ->options(WebDesign::getLayoutStyles())
                                                    ->default('boxed'),
                                                
                                                TextInput::make('container_width')
                                                    ->label('Chiều rộng container (px)')
                                                    ->numeric()
                                                    ->default(1200)
                                                    ->minValue(800)
                                                    ->maxValue(1920),
                                                
                                                TextInput::make('grid_columns')
                                                    ->label('Số cột grid')
                                                    ->numeric()
                                                    ->default(12)
                                                    ->minValue(6)
                                                    ->maxValue(24),
                                            ]),
                                        
                                        Grid::make(4)
                                            ->schema([
                                                TextInput::make('header_height')
                                                    ->label('Chiều cao header (px)')
                                                    ->numeric()
                                                    ->default(80)
                                                    ->minValue(50)
                                                    ->maxValue(200),
                                                
                                                TextInput::make('footer_height')
                                                    ->label('Chiều cao footer (px)')
                                                    ->numeric()
                                                    ->default(200)
                                                    ->minValue(100)
                                                    ->maxValue(500),
                                                
                                                TextInput::make('sidebar_width')
                                                    ->label('Chiều rộng sidebar (px)')
                                                    ->numeric()
                                                    ->default(300)
                                                    ->minValue(200)
                                                    ->maxValue(500),
                                                
                                                TextInput::make('spacing_unit')
                                                    ->label('Đơn vị khoảng cách (px)')
                                                    ->numeric()
                                                    ->default(16)
                                                    ->minValue(8)
                                                    ->maxValue(32),
                                            ]),
                                    ]),
                                
                                Section::make('Responsive Breakpoints')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                TextInput::make('breakpoint_mobile')
                                                    ->label('Mobile (px)')
                                                    ->numeric()
                                                    ->default(640)
                                                    ->minValue(320)
                                                    ->maxValue(768),
                                                
                                                TextInput::make('breakpoint_tablet')
                                                    ->label('Tablet (px)')
                                                    ->numeric()
                                                    ->default(768)
                                                    ->minValue(640)
                                                    ->maxValue(1024),
                                                
                                                TextInput::make('breakpoint_desktop')
                                                    ->label('Desktop (px)')
                                                    ->numeric()
                                                    ->default(1024)
                                                    ->minValue(768)
                                                    ->maxValue(1920),
                                            ]),
                                    ]),
                            ]),

                        // Tab 5: Components
                        Tabs\Tab::make('Components')
                            ->icon('heroicon-o-cube')
                            ->schema([
                                Section::make('Hero Section')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Select::make('hero_style')
                                                    ->label('Kiểu hero')
                                                    ->options(WebDesign::getHeroStyles())
                                                    ->default('gradient'),
                                                
                                                TextInput::make('hero_height')
                                                    ->label('Chiều cao hero (px)')
                                                    ->numeric()
                                                    ->default(500)
                                                    ->minValue(300)
                                                    ->maxValue(800),
                                                
                                                Toggle::make('hero_overlay')
                                                    ->label('Overlay hero')
                                                    ->default(true),
                                                
                                                Select::make('hero_text_align')
                                                    ->label('Căn chỉnh text')
                                                    ->options([
                                                        'left' => 'Trái',
                                                        'center' => 'Giữa',
                                                        'right' => 'Phải',
                                                    ])
                                                    ->default('center'),
                                            ]),
                                    ]),
                                
                                Section::make('Buttons')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('button_style')
                                                    ->label('Kiểu button')
                                                    ->options(WebDesign::getButtonStyles())
                                                    ->default('solid'),
                                                
                                                Select::make('button_size')
                                                    ->label('Kích thước button')
                                                    ->options(WebDesign::getButtonSizes())
                                                    ->default('medium'),
                                                
                                                TextInput::make('button_radius')
                                                    ->label('Bo góc button (px)')
                                                    ->numeric()
                                                    ->default(8)
                                                    ->minValue(0)
                                                    ->maxValue(50),
                                            ]),
                                    ]),
                                
                                Section::make('Cards')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('card_style')
                                                    ->label('Kiểu card')
                                                    ->options(WebDesign::getCardStyles())
                                                    ->default('elevated'),
                                                
                                                TextInput::make('card_radius')
                                                    ->label('Bo góc card (px)')
                                                    ->numeric()
                                                    ->default(12)
                                                    ->minValue(0)
                                                    ->maxValue(50),
                                                
                                                TextInput::make('card_shadow')
                                                    ->label('Đổ bóng card')
                                                    ->default('0 4px 6px rgba(0, 0, 0, 0.1)')
                                                    ->placeholder('CSS box-shadow'),
                                            ]),
                                    ]),
                            ]),

                        // Tab 6: Navigation
                        Tabs\Tab::make('Navigation')
                            ->icon('heroicon-o-bars-3')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('navigation_style')
                                            ->label('Kiểu navigation')
                                            ->options(WebDesign::getNavigationStyles())
                                            ->default('horizontal'),
                                        
                                        Select::make('navigation_position')
                                            ->label('Vị trí navigation')
                                            ->options(WebDesign::getNavigationPositions())
                                            ->default('top'),
                                        
                                        ColorPicker::make('navigation_background')
                                            ->label('Nền navigation')
                                            ->default('transparent'),
                                        
                                        ColorPicker::make('footer_background')
                                            ->label('Nền footer')
                                            ->default('#1f2937'),
                                    ]),
                            ]),

                        // Tab 7: Effects
                        Tabs\Tab::make('Effects')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('border_radius')
                                            ->label('Bo góc chung (px)')
                                            ->numeric()
                                            ->default(8)
                                            ->minValue(0)
                                            ->maxValue(50),
                                        
                                        TextInput::make('box_shadow')
                                            ->label('Đổ bóng chung')
                                            ->default('0 1px 3px rgba(0, 0, 0, 0.1)')
                                            ->placeholder('CSS box-shadow'),
                                        
                                        TextInput::make('transition_duration')
                                            ->label('Thời gian chuyển đổi')
                                            ->default('200ms')
                                            ->placeholder('200ms'),
                                        
                                        Select::make('animation_style')
                                            ->label('Kiểu animation')
                                            ->options(WebDesign::getAnimationStyles())
                                            ->default('smooth'),
                                    ]),
                            ]),

                        // Tab 8: Custom Code
                        Tabs\Tab::make('Code tùy chỉnh')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Textarea::make('custom_css')
                                    ->label('CSS tùy chỉnh')
                                    ->rows(10)
                                    ->placeholder('/* CSS tùy chỉnh của bạn */'),
                                
                                Textarea::make('custom_js')
                                    ->label('JavaScript tùy chỉnh')
                                    ->rows(10)
                                    ->placeholder('// JavaScript tùy chỉnh của bạn'),
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

                ImageColumn::make('preview_image')
                    ->label('Preview')
                    ->width(80)
                    ->height(60),

                TextColumn::make('name')
                    ->label('Tên thiết kế')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('theme_type')
                    ->label('Loại theme')
                    ->badge()
                    ->colors([
                        'primary' => 'modern',
                        'success' => 'classic',
                        'warning' => 'minimalist',
                        'danger' => 'creative',
                        'info' => 'corporate',
                        'gray' => 'ecommerce',
                    ]),

                TextColumn::make('color_scheme')
                    ->label('Chế độ màu')
                    ->badge()
                    ->colors([
                        'primary' => 'light',
                        'gray' => 'dark',
                        'warning' => 'auto',
                    ]),

                TextColumn::make('primary_color')
                    ->label('Màu chính')
                    ->formatStateUsing(fn ($state) => "<div style='width: 20px; height: 20px; background-color: {$state}; border-radius: 4px; display: inline-block;'></div> {$state}")
                    ->html(),

                ToggleColumn::make('is_default')
                    ->label('Mặc định'),

                ToggleColumn::make('is_active')
                    ->label('Đang dùng'),

                TextColumn::make('created_at')
                    ->label('Tạo lúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('theme_type')
                    ->label('Loại theme')
                    ->options(WebDesign::getThemeTypes()),

                Tables\Filters\SelectFilter::make('color_scheme')
                    ->label('Chế độ màu')
                    ->options(WebDesign::getColorSchemes()),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Đang sử dụng'),

                Tables\Filters\TernaryFilter::make('is_default')
                    ->label('Mặc định'),
            ])
            ->actions([
                Action::make('preview')
                    ->label('Xem trước')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (WebDesign $record) => route('design.preview', $record))
                    ->openUrlInNewTab(),

                Action::make('activate')
                    ->label('Kích hoạt')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (WebDesign $record) {
                        $record->setActive();

                        Notification::make()
                            ->title('Đã kích hoạt thiết kế')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (WebDesign $record) => !$record->is_active),

                Action::make('setDefault')
                    ->label('Đặt mặc định')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->action(function (WebDesign $record) {
                        $record->setDefault();

                        Notification::make()
                            ->title('Đã đặt làm thiết kế mặc định')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (WebDesign $record) => !$record->is_default),

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
            'index' => Pages\ListWebDesigns::route('/'),
            'create' => Pages\CreateWebDesign::route('/create'),
            'edit' => Pages\EditWebDesign::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $activeCount = static::getModel()::where('is_active', true)->count();
        return $activeCount > 0 ? (string) $activeCount : null;
    }
}
