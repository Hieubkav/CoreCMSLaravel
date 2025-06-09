<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PostResource\Pages;
use App\Filament\Admin\Resources\PostResource\RelationManagers;
use App\Filament\Admin\Resources\PostCategoryResource;
use App\Models\Post;


use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PostResource extends Resource
{

    protected static ?string $model = Post::class;

    protected static ?string $modelLabel = 'bài viết';

    protected static ?string $pluralModelLabel = 'bài viết';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Quản lý nội dung';

    protected static ?string $navigationLabel = 'Bài viết';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Thông tin bài viết')
                    ->tabs([
                        Tabs\Tab::make('Thông tin cơ bản')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Tiêu đề')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state))),

                                TextInput::make('slug')
                                    ->label('Đường dẫn')
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->helperText('Để trống để tự động tạo từ tiêu đề')
                                    ->suffixAction(
                                        Action::make('generateSlug')
                                            ->icon('heroicon-m-link')
                                            ->tooltip('Tự động tạo từ tiêu đề')
                                            ->action(function (Set $set, Get $get) {
                                                $title = $get('title');
                                                if (!empty($title)) {
                                                    $set('slug', Str::slug($title));
                                                }
                                            })
                                    ),

                                Select::make('category_id')
                                    ->label('Danh mục')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->nullable()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label('Tên danh mục')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state))),
                                        TextInput::make('slug')
                                            ->label('Đường dẫn')
                                            ->required()
                                            ->maxLength(255),
                                    ]),

                                Select::make('post_type')
                                    ->label('Loại bài viết')
                                    ->options(Post::getPostTypeOptions())
                                    ->default('blog')
                                    ->required(),

                                TextInput::make('author_name')
                                    ->label('Tác giả')
                                    ->maxLength(255)
                                    ->default(auth()->user()?->name)
                                    ->helperText('Để trống để sử dụng tên người tạo'),

                                FileUpload::make('thumbnail')
                                    ->label('Hình đại diện')
                                    ->image()
                                    ->directory('posts/thumbnails')
                                    ->visibility('public')
                                    ->maxSize(5120)
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                    ])
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->saveUploadedFileUsing(function ($file, $get) {
                                        $title = $get('title') ?? 'post';
                                        $customName = 'post-' . $title;

                                        return \App\Actions\ConvertImageToWebp::run(
                                            $file,
                                            'posts/thumbnails',
                                            $customName,
                                            1200,
                                            630
                                        );
                                    })
                                    ->helperText('Ảnh sẽ được tự động chuyển sang WebP với tên SEO-friendly. Kích thước tối ưu: 1200x630px')
                                    ->imagePreviewHeight('200'),

                                DateTimePicker::make('published_at')
                                    ->label('Ngày xuất bản')
                                    ->default(now())
                                    ->helperText('Bài viết sẽ hiển thị từ thời điểm này'),

                                Toggle::make('is_featured')
                                    ->label('Bài viết nổi bật')
                                    ->default(false)
                                    ->helperText('Hiển thị trong danh sách bài viết nổi bật'),

                                TextInput::make('order')
                                    ->label('Thứ tự')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Số thứ tự hiển thị (càng nhỏ càng ưu tiên)'),

                                Select::make('status')
                                    ->label('Trạng thái')
                                    ->options([
                                        'active' => 'Hiển thị',
                                        'inactive' => 'Ẩn',
                                    ])
                                    ->default('active')
                                    ->required(),
                            ])->columns(2),

                        Tabs\Tab::make('Nội dung & SEO')
                            ->schema([
                                Textarea::make('excerpt')
                                    ->label('Tóm tắt')
                                    ->rows(3)
                                    ->maxLength(300)
                                    ->helperText('Mô tả ngắn gọn về bài viết (để trống để tự động tạo)')
                                    ->columnSpanFull(),

                                RichEditor::make('content')
                                    ->label('Nội dung chi tiết')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('posts')
                                    ->required()
                                    ->columnSpanFull(),

                                TagsInput::make('tags')
                                    ->label('Thẻ tag')
                                    ->helperText('Nhập các từ khóa liên quan, nhấn Enter để thêm')
                                    ->columnSpanFull(),

                                Section::make('SEO & Tối ưu hóa')
                                    ->description('Các trường SEO sẽ tự động tạo nếu để trống khi lưu')
                                    ->schema([
                                        TextInput::make('seo_title')
                                            ->label('Tiêu đề SEO')
                                            // ->maxLength(255)
                                            ->helperText('Để trống để tự động tạo từ tiêu đề bài viết'),

                                        Textarea::make('seo_description')
                                            ->label('Mô tả SEO')
                                            ->rows(3)
                                            // ->maxLength(160)
                                            ->helperText('Để trống để tự động tạo từ nội dung bài viết'),

                                        TextInput::make('og_image_link')
                                            ->label('Ảnh OG (Open Graph)')
                                            // ->url()
                                            ->helperText('Để trống để tự động sử dụng ảnh đại diện'),
                                    ])->columns(1)
                                    ->columnSpanFull(),
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
                    ->label('Thứ tự')
                    ->sortable()
                    ->alignCenter()
                    ->width('80px'),

                ImageColumn::make('thumbnail')
                    ->label('Ảnh')
                    ->circular()
                    ->size(50),

                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record): string =>
                        ($record->category ? "Danh mục: {$record->category->name}" : 'Chưa phân loại') .
                        ($record->author_name ? " • Tác giả: {$record->author_name}" : '')
                    ),

                TextColumn::make('post_type')
                    ->label('Loại')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'blog' => 'success',
                        'news' => 'info',
                        'page' => 'warning',
                        'policy' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => Post::getPostTypeOptions()[$state] ?? $state),

                ToggleColumn::make('is_featured')
                    ->label('Nổi bật')
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Hiển thị',
                        'inactive' => 'Ẩn',
                    })
                    ->width('100px'),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->reorderable('order')
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Danh mục'),

                Tables\Filters\SelectFilter::make('post_type')
                    ->label('Loại bài viết')
                    ->options(Post::getPostTypeOptions()),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Bài viết nổi bật')
                    ->placeholder('Tất cả')
                    ->trueLabel('Nổi bật')
                    ->falseLabel('Thường'),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hiển thị',
                        'inactive' => 'Ẩn',
                    ]),

                Tables\Filters\Filter::make('published_at')
                    ->form([
                        DateTimePicker::make('published_from')
                            ->label('Từ ngày'),
                        DateTimePicker::make('published_until')
                            ->label('Đến ngày'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['published_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '>=', $date),
                            )
                            ->when(
                                $data['published_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view_frontend')
                    ->label('Xem trên website')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) => route('posts.show', $record->slug))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('view_category')
                    ->label('Xem danh mục')
                    ->icon('heroicon-o-folder')
                    ->color('warning')
                    ->url(fn ($record) => $record->category ?
                        PostCategoryResource::getUrl('edit', ['record' => $record->category->id]) : null)
                    ->visible(fn ($record) => $record->category !== null),
                Tables\Actions\EditAction::make()
                    ->label('Sửa'),
                Tables\Actions\DeleteAction::make()
                    ->label('Xóa'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Xóa đã chọn'),
                ]),
            ])
            ->defaultSort('order', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PostImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'active')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    /**
     * Lấy danh sách cột cần thiết cho table
     */
    protected static function getTableColumns(): array
    {
        return [
            'id',
            'title',
            'slug',
            'type',
            'status',
            'is_featured',
            'order',
            'thumbnail',
            'category_id',
            'created_at'
        ];
    }

    /**
     * Lấy relationships cần thiết cho form
     */
    protected static function getFormRelationships(): array
    {
        return [
            'category' => function($query) {
                $query->select(['id', 'name', 'slug']);
            },
            'images' => function($query) {
                $query->where('status', 'active')
                      ->orderBy('order')
                      ->limit(10);
            }
        ];
    }

    /**
     * Lấy các cột có thể search
     */
    protected static function getSearchableColumns(): array
    {
        return ['title', 'content'];
    }

    /**
     * Tạo SEO title từ title gốc
     */
    public static function generateSeoTitle(string $title): string
    {
        // Giới hạn độ dài SEO title trong khoảng 50-60 ký tự
        $maxLength = 60;

        if (strlen($title) <= $maxLength) {
            return $title;
        }

        // Cắt ngắn tại từ cuối cùng để tránh cắt giữa từ
        $truncated = substr($title, 0, $maxLength - 3);
        $lastSpace = strrpos($truncated, ' ');

        if ($lastSpace !== false) {
            $truncated = substr($truncated, 0, $lastSpace);
        }

        return $truncated . '...';
    }

    /**
     * Tạo SEO description từ content
     */
    public static function generateSeoDescription(string $content): string
    {
        // Loại bỏ HTML tags
        $plainText = strip_tags($content);

        // Loại bỏ khoảng trắng thừa
        $plainText = preg_replace('/\s+/', ' ', trim($plainText));

        // Giới hạn độ dài SEO description trong khoảng 150-160 ký tự
        $maxLength = 155;

        if (strlen($plainText) <= $maxLength) {
            return $plainText;
        }

        // Cắt ngắn tại từ cuối cùng để tránh cắt giữa từ
        $truncated = substr($plainText, 0, $maxLength - 3);
        $lastSpace = strrpos($truncated, ' ');

        if ($lastSpace !== false) {
            $truncated = substr($truncated, 0, $lastSpace);
        }

        return $truncated . '...';
    }
}