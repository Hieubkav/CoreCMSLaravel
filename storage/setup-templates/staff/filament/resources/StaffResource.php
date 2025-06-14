<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StaffResource\Pages;
use App\Models\Staff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Nhân viên';

    protected static ?string $modelLabel = 'Nhân viên';

    protected static ?string $pluralModelLabel = 'Nhân viên';

    protected static ?string $navigationGroup = 'Quản lý';

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
                                            ->label('Họ và tên')
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
                                            ->unique(Staff::class, 'slug', ignoreRecord: true)
                                            ->helperText('URL thân thiện cho trang nhân viên'),
                                    ]),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('position')
                                            ->label('Chức vụ')
                                            ->options(Staff::getPositionOptions())
                                            ->searchable()
                                            ->preload(),

                                        Forms\Components\TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->maxLength(255),
                                    ]),

                                Forms\Components\TextInput::make('phone')
                                    ->label('Số điện thoại')
                                    ->tel()
                                    ->maxLength(20),

                                Forms\Components\RichEditor::make('description')
                                    ->label('Mô tả / Tiểu sử')
                                    ->columnSpanFull(),

                                \App\Actions\File\CreateFilamentImageUpload::run(
                                    field: 'image',
                                    label: 'Ảnh đại diện',
                                    directory: 'staff/avatars',
                                    maxWidth: 800,
                                    maxHeight: 800,
                                    maxSize: 3072,
                                    helperText: 'Ảnh đại diện nhân viên, tỷ lệ vuông khuyến nghị. Tối đa 3MB.',
                                    aspectRatios: ['1:1', '4:3', '3:4']
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

                        Forms\Components\Tabs\Tab::make('Mạng xã hội')
                            ->schema([
                                Forms\Components\Repeater::make('social_links')
                                    ->label('Liên kết mạng xã hội')
                                    ->schema([
                                        Forms\Components\Select::make('platform')
                                            ->label('Nền tảng')
                                            ->options(Staff::getSocialPlatforms())
                                            ->required(),
                                        
                                        Forms\Components\TextInput::make('url')
                                            ->label('Đường dẫn')
                                            ->url()
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->collapsible()
                                    ->defaultItems(0),
                            ]),

                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('seo_title')
                                    ->label('SEO Title')
                                    ->maxLength(60)
                                    ->helperText('Để trống để tự động sử dụng: Tên - Chức vụ'),

                                Forms\Components\Textarea::make('seo_description')
                                    ->label('SEO Description')
                                    ->maxLength(160)
                                    ->rows(3)
                                    ->helperText('Để trống để tự động tạo từ mô tả'),

                                \App\Actions\File\CreateFilamentImageUpload::run(
                                    field: 'og_image',
                                    label: 'OG Image',
                                    directory: 'staff/og-images',
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
                    ->width(60)
                    ->height(60)
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Họ và tên')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('position')
                    ->label('Chức vụ')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Điện thoại')
                    ->searchable()
                    ->copyable()
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
                Tables\Filters\SelectFilter::make('position')
                    ->label('Chức vụ')
                    ->options(Staff::getPositionOptions()),

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
            'index' => Pages\ListStaff::route('/'),
            'create' => Pages\CreateStaff::route('/create'),
            'edit' => Pages\EditStaff::route('/{record}/edit'),
        ];
    }
}
