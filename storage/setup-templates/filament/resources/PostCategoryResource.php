<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PostCategoryResource\Pages;
use App\Models\PostCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostCategoryResource extends Resource
{
    protected static ?string $model = PostCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationLabel = 'Danh mục bài viết';

    protected static ?string $modelLabel = 'Danh mục bài viết';

    protected static ?string $pluralModelLabel = 'Danh mục bài viết';

    protected static ?string $navigationGroup = 'Nội dung';

    protected static ?int $navigationSort = 2;

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
                                            ->label('Tên danh mục')
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
                                            ->unique(PostCategory::class, 'slug', ignoreRecord: true)
                                            ->helperText('URL thân thiện cho danh mục'),
                                    ]),

                                Forms\Components\Textarea::make('description')
                                    ->label('Mô tả')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                Forms\Components\FileUpload::make('image')
                                    ->label('Ảnh danh mục')
                                    ->image()
                                    ->directory('post-categories')
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                    ]),

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
                                            ->label('Thứ tự')
                                            ->numeric()
                                            ->default(0),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('seo_title')
                                    ->label('SEO Title')
                                    ->maxLength(60)
                                    ->helperText('Để trống để tự động sử dụng tên danh mục'),

                                Forms\Components\Textarea::make('seo_description')
                                    ->label('SEO Description')
                                    ->maxLength(160)
                                    ->rows(3)
                                    ->helperText('Để trống để tự động sử dụng mô tả danh mục'),

                                Forms\Components\FileUpload::make('og_image')
                                    ->label('OG Image')
                                    ->image()
                                    ->directory('post-categories/og-images')
                                    ->helperText('Ảnh hiển thị khi share trên mạng xã hội'),
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
                    ->label('Tên danh mục')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Đã copy slug!')
                    ->limit(30),

                Tables\Columns\TextColumn::make('posts_count')
                    ->label('Số bài viết')
                    ->counts('posts')
                    ->sortable(),

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

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hiển thị',
                        'inactive' => 'Ẩn',
                    ]),
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
            'index' => Pages\ListPostCategories::route('/'),
            'create' => Pages\CreatePostCategory::route('/create'),
            'edit' => Pages\EditPostCategory::route('/{record}/edit'),
        ];
    }
}
