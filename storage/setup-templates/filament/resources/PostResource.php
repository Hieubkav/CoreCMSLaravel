<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PostResource\Pages;
use App\Models\Post;
use App\Models\PostCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Bài viết';

    protected static ?string $modelLabel = 'Bài viết';

    protected static ?string $pluralModelLabel = 'Bài viết';

    protected static ?string $navigationGroup = 'Nội dung';

    protected static ?int $navigationSort = 1;

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
                                        Forms\Components\TextInput::make('title')
                                            ->label('Tiêu đề')
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
                                            ->unique(Post::class, 'slug', ignoreRecord: true)
                                            ->helperText('URL thân thiện cho bài viết'),
                                    ]),

                                Forms\Components\Select::make('post_category_id')
                                    ->label('Danh mục')
                                    ->options(PostCategory::active()->ordered()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\Select::make('post_type')
                                    ->label('Loại bài viết')
                                    ->options(Post::getPostTypeOptions())
                                    ->required()
                                    ->default('tin_tuc'),

                                Forms\Components\RichEditor::make('content')
                                    ->label('Nội dung')
                                    ->required()
                                    ->columnSpanFull(),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\FileUpload::make('thumbnail')
                                            ->label('Ảnh đại diện')
                                            ->image()
                                            ->directory('posts/thumbnails')
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '16:9',
                                                '4:3',
                                                '1:1',
                                            ]),

                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->label('Thời gian xuất bản')
                                            ->default(now()),
                                    ]),

                                Forms\Components\Grid::make(3)
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

                                        Forms\Components\Toggle::make('is_hot')
                                            ->label('Bài viết nổi bật')
                                            ->helperText('Hiển thị trong blog section'),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('seo_title')
                                    ->label('SEO Title')
                                    ->maxLength(60)
                                    ->helperText('Để trống để tự động sử dụng tiêu đề bài viết'),

                                Forms\Components\Textarea::make('seo_description')
                                    ->label('SEO Description')
                                    ->maxLength(160)
                                    ->rows(3)
                                    ->helperText('Để trống để tự động tạo từ nội dung'),

                                Forms\Components\FileUpload::make('og_image')
                                    ->label('OG Image')
                                    ->image()
                                    ->directory('posts/og-images')
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

                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Ảnh')
                    ->width(80)
                    ->height(60),

                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make('post_type')
                    ->label('Loại')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tin_tuc' => 'info',
                        'dich_vu' => 'success',
                        'trang_don' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('is_hot')
                    ->label('Nổi bật')
                    ->boolean(),

                Tables\Columns\TextColumn::make('view_count')
                    ->label('Lượt xem')
                    ->sortable()
                    ->numeric(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Xuất bản')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tạo lúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('post_category_id')
                    ->label('Danh mục')
                    ->options(PostCategory::active()->ordered()->pluck('name', 'id')),

                Tables\Filters\SelectFilter::make('post_type')
                    ->label('Loại bài viết')
                    ->options(Post::getPostTypeOptions()),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hiển thị',
                        'inactive' => 'Ẩn',
                    ]),

                Tables\Filters\Filter::make('is_hot')
                    ->label('Bài viết nổi bật')
                    ->query(fn (Builder $query): Builder => $query->where('is_hot', true)),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
