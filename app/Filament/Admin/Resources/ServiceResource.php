<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Dịch vụ';

    protected static ?string $modelLabel = 'Dịch vụ';

    protected static ?string $pluralModelLabel = 'Dịch vụ';

    protected static ?string $navigationGroup = 'Quản lý';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Thông tin chính')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Tên dịch vụ')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (string $context, $state, Forms\Set $set) => 
                                                $context === 'create' ? $set('slug', Str::slug($state)) : null
                                            ),

                                        Forms\Components\TextInput::make('slug')
                                            ->label('Slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(Service::class, 'slug', ignoreRecord: true)
                                            ->helperText('URL thân thiện cho trang dịch vụ'),
                                    ]),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('category')
                                            ->label('Danh mục')
                                            ->options(Service::getCategories())
                                            ->searchable()
                                            ->preload(),

                                        Forms\Components\Select::make('duration')
                                            ->label('Thời gian thực hiện')
                                            ->options(Service::getDurationOptions())
                                            ->searchable()
                                            ->preload(),
                                    ]),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('price')
                                            ->label('Giá dịch vụ (VNĐ)')
                                            ->numeric()
                                            ->prefix('VNĐ')
                                            ->helperText('Để trống nếu giá liên hệ'),

                                        Forms\Components\Toggle::make('is_featured')
                                            ->label('Dịch vụ nổi bật')
                                            ->helperText('Hiển thị trong danh sách dịch vụ nổi bật'),
                                    ]),

                                Forms\Components\Textarea::make('short_description')
                                    ->label('Mô tả ngắn')
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->helperText('Mô tả ngắn gọn về dịch vụ (tối đa 500 ký tự)'),

                                Forms\Components\RichEditor::make('description')
                                    ->label('Mô tả chi tiết')
                                    ->columnSpanFull(),

                                \App\Actions\File\CreateFilamentImageUpload::run(
                                    field: 'image',
                                    label: 'Ảnh dịch vụ',
                                    directory: 'services/main',
                                    maxWidth: 1200,
                                    maxHeight: 800,
                                    maxSize: 3072,
                                    helperText: 'Ảnh đại diện cho dịch vụ. Tỷ lệ 3:2 khuyến nghị. Tối đa 3MB.',
                                    aspectRatios: ['3:2', '16:9', '4:3']
                                ),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->label('Trạng thái')
                                            ->options([
                                                'active' => 'Hiển thị',
                                                'inactive' => 'Ẩn',
                                            ])
                                            ->required()
                                            ->default('active'),

                                        Forms\Components\TextInput::make('order')
                                            ->label('Thứ tự hiển thị')
                                            ->numeric()
                                            ->default(0)
                                            ->helperText('Số nhỏ hiển thị trước'),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Tính năng')
                            ->schema([
                                Forms\Components\Repeater::make('features')
                                    ->label('Tính năng dịch vụ')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Tên tính năng')
                                            ->required(),
                                        
                                        Forms\Components\Textarea::make('description')
                                            ->label('Mô tả')
                                            ->rows(2),

                                        Forms\Components\Toggle::make('included')
                                            ->label('Bao gồm')
                                            ->default(true),
                                    ])
                                    ->columns(3)
                                    ->collapsible()
                                    ->defaultItems(0)
                                    ->addActionLabel('Thêm tính năng'),
                            ]),

                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('seo_title')
                                    ->label('SEO Title')
                                    ->maxLength(60)
                                    ->helperText('Để trống để tự động sử dụng: Tên dịch vụ - Dịch vụ chuyên nghiệp'),

                                Forms\Components\Textarea::make('seo_description')
                                    ->label('SEO Description')
                                    ->maxLength(160)
                                    ->rows(3)
                                    ->helperText('Để trống để tự động tạo từ mô tả ngắn'),

                                Forms\Components\Textarea::make('meta_keywords')
                                    ->label('Meta Keywords')
                                    ->rows(2)
                                    ->helperText('Các từ khóa liên quan, cách nhau bằng dấu phẩy'),

                                \App\Actions\File\CreateFilamentImageUpload::run(
                                    field: 'og_image',
                                    label: 'OG Image',
                                    directory: 'services/og-images',
                                    maxWidth: 1200,
                                    maxHeight: 630,
                                    maxSize: 3072,
                                    helperText: 'Ảnh hiển thị khi share trên mạng xã hội. Tỷ lệ 16:9 khuyến nghị.',
                                    aspectRatios: ['16:9', '4:3']
                                ),
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
                    ->label('#')
                    ->sortable()
                    ->width(50),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Ảnh')
                    ->width(80)
                    ->height(60),

                Tables\Columns\TextColumn::make('name')
                    ->label('Tên dịch vụ')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('category_name')
                    ->label('Danh mục')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('formatted_price')
                    ->label('Giá')
                    ->badge()
                    ->color(fn (string $state): string => 
                        $state === 'Liên hệ' ? 'warning' : 'success'
                    ),

                Tables\Columns\TextColumn::make('duration_name')
                    ->label('Thời gian')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Nổi bật')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tạo lúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Danh mục')
                    ->options(Service::getCategories()),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hiển thị',
                        'inactive' => 'Ẩn',
                    ]),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Dịch vụ nổi bật'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('order')
            ->defaultSort('order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
