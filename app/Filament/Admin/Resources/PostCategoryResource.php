<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PostCategoryResource\Pages;
use App\Models\CatPost;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostCategoryResource extends Resource
{

    protected static ?string $model = CatPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationLabel = 'Danh mục bài viết';

    protected static ?string $modelLabel = 'danh mục bài viết';

    protected static ?string $pluralModelLabel = 'danh mục bài viết';

    protected static ?string $navigationGroup = 'Quản lý nội dung';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Thông tin danh mục')
                    ->tabs([
                        Tabs\Tab::make('Thông tin cơ bản')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Tên danh mục')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state))),

                                TextInput::make('slug')
                                    ->label('Đường dẫn')
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->rules(['alpha_dash'])
                                    ->helperText('Để trống để tự động tạo từ tên danh mục'),

                                Textarea::make('description')
                                    ->label('Mô tả')
                                    ->rows(3)
                                    ->columnSpanFull(),

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

                        Tabs\Tab::make('SEO & Tối ưu hóa')
                            ->schema([
                                TextInput::make('seo_title')
                                    ->label('Tiêu đề SEO')
                                    ->maxLength(255)
                                    ->helperText('Để trống để tự động tạo từ tên danh mục'),

                                Textarea::make('seo_description')
                                    ->label('Mô tả SEO')
                                    ->rows(3)
                                    ->maxLength(160)
                                    ->helperText('Để trống để tự động tạo từ mô tả danh mục'),

                                TextInput::make('og_image_link')
                                    ->label('Ảnh OG (Open Graph)')
                                    ->url()
                                    ->helperText('Để trống để sử dụng ảnh mặc định'),
                            ])->columns(1),
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

                TextColumn::make('name')
                    ->label('Tên danh mục')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('slug')
                    ->label('Đường dẫn')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Đã sao chép!')
                    ->color('gray'),

                TextColumn::make('posts_count')
                    ->label('Số bài viết')
                    ->counts('posts')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                SelectColumn::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hiển thị',
                        'inactive' => 'Ẩn',
                    ])
                    ->selectablePlaceholder(false),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
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
                Tables\Actions\Action::make('view_frontend')
                    ->label('Xem trên website')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) => route('posts.category', $record->slug))
                    ->openUrlInNewTab(),
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
            // RelationManagers\PostsRelationManager::class, // Tạm thời comment để tránh lỗi
        ];
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
