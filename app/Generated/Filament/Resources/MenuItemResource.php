<?php

namespace App\Generated\Filament\Resources;

use App\Generated\Filament\Resources\MenuItemResource\Pages;
use App\Generated\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';
    
    protected static ?string $navigationLabel = 'Menu Items';
    
    protected static ?string $modelLabel = 'Menu Item';
    
    protected static ?string $pluralModelLabel = 'Menu Items';

    protected static ?string $navigationGroup = 'Layout';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin cơ bản')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->label('Tiêu đề')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('VD: Trang chủ'),
                                
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->maxLength(255)
                                    ->placeholder('Tự động tạo từ tiêu đề')
                                    ->helperText('Để trống để tự động tạo từ tiêu đề'),
                            ]),
                        
                        Grid::make(2)
                            ->schema([
                                Select::make('parent_id')
                                    ->label('Menu cha')
                                    ->options(function () {
                                        return MenuItem::whereNull('parent_id')
                                            ->pluck('title', 'id')
                                            ->toArray();
                                    })
                                    ->placeholder('Chọn menu cha (nếu có)')
                                    ->searchable(),
                                
                                Select::make('menu_location')
                                    ->label('Vị trí menu')
                                    ->options(MenuItem::getMenuLocations())
                                    ->default('main')
                                    ->required(),
                            ]),
                        
                        Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(3)
                            ->placeholder('Mô tả ngắn về menu item này'),
                    ]),

                Section::make('Liên kết')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('url')
                                    ->label('URL tùy chỉnh')
                                    ->url()
                                    ->placeholder('https://example.com hoặc /page')
                                    ->helperText('URL tuyệt đối hoặc tương đối'),
                                
                                TextInput::make('route_name')
                                    ->label('Tên route Laravel')
                                    ->placeholder('VD: home, about, contact')
                                    ->helperText('Ưu tiên hơn URL tùy chỉnh'),
                            ]),
                        
                        Grid::make(2)
                            ->schema([
                                Select::make('target')
                                    ->label('Target')
                                    ->options(MenuItem::getTargetOptions())
                                    ->default('_self')
                                    ->required(),
                                
                                TextInput::make('css_class')
                                    ->label('CSS Class')
                                    ->placeholder('custom-class another-class')
                                    ->helperText('CSS classes tùy chỉnh'),
                            ]),
                    ]),

                Section::make('Hiển thị')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('icon')
                                    ->label('Icon')
                                    ->options(MenuItem::getIconOptions())
                                    ->searchable()
                                    ->placeholder('Chọn icon'),
                                
                                Toggle::make('is_active')
                                    ->label('Kích hoạt')
                                    ->default(true),
                                
                                Toggle::make('is_featured')
                                    ->label('Nổi bật')
                                    ->default(false)
                                    ->helperText('Hiển thị trong menu nổi bật'),
                            ]),
                        
                        TextInput::make('order')
                            ->label('Thứ tự')
                            ->numeric()
                            ->default(0)
                            ->helperText('Số nhỏ hơn sẽ hiển thị trước'),
                    ]),

                Section::make('SEO')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('SEO Title')
                            ->maxLength(255)
                            ->placeholder('Tiêu đề SEO (tùy chọn)'),
                        
                        Textarea::make('meta_description')
                            ->label('SEO Description')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Mô tả SEO (tùy chọn)'),
                    ])
                    ->collapsible()
                    ->collapsed(),
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
                
                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                        $indent = str_repeat('— ', $record->getDepthLevel());
                        $icon = $record->icon ? "<i class=\"{$record->icon} mr-1\"></i>" : '';
                        return $indent . $icon . $state;
                    })
                    ->html(),
                
                TextColumn::make('menu_location')
                    ->label('Vị trí')
                    ->badge()
                    ->colors([
                        'primary' => 'main',
                        'success' => 'header',
                        'warning' => 'footer',
                        'danger' => 'sidebar',
                        'info' => 'mobile',
                        'gray' => 'breadcrumb',
                    ]),
                
                TextColumn::make('url')
                    ->label('URL')
                    ->limit(30)
                    ->tooltip(function ($record) {
                        return $record->full_url;
                    }),
                
                TextColumn::make('target')
                    ->label('Target')
                    ->badge()
                    ->colors([
                        'primary' => '_self',
                        'success' => '_blank',
                        'warning' => '_parent',
                        'danger' => '_top',
                    ]),
                
                ToggleColumn::make('is_featured')
                    ->label('Nổi bật'),
                
                ToggleColumn::make('is_active')
                    ->label('Kích hoạt'),
                
                TextColumn::make('created_at')
                    ->label('Tạo lúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('menu_location')
                    ->label('Vị trí menu')
                    ->options(MenuItem::getMenuLocations()),
                
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Menu cha')
                    ->options(function () {
                        return MenuItem::whereNull('parent_id')
                            ->pluck('title', 'id')
                            ->toArray();
                    }),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Trạng thái'),
                
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Nổi bật'),
            ])
            ->actions([
                Action::make('preview')
                    ->label('Xem trước')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) => $record->full_url)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->full_url !== '#'),
                
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->groups([
                Tables\Grouping\Group::make('menu_location')
                    ->label('Vị trí menu')
                    ->collapsible(),
                
                Tables\Grouping\Group::make('parent.title')
                    ->label('Menu cha')
                    ->collapsible(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
