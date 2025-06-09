<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StaffResource\Pages;
use App\Models\Staff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Nhân viên';

    protected static ?string $modelLabel = 'Nhân viên';

    protected static ?string $pluralModelLabel = 'Nhân viên';

    protected static ?string $navigationGroup = 'Quản lý Nội dung';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Thông tin Nhân viên')
                    ->tabs([
                        Tabs\Tab::make('Thông tin Cơ bản')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Section::make('Thông tin Cá nhân')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Họ và tên')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (string $context, $state, Forms\Set $set) {
                                                if ($context === 'create') {
                                                    $set('slug', \Illuminate\Support\Str::slug($state));
                                                }
                                            }),

                                        TextInput::make('slug')
                                            ->label('Slug (URL)')
                                            ->maxLength(255)
                                            ->helperText('Để trống để tự động tạo từ tên')
                                            ->unique(Staff::class, 'slug', ignoreRecord: true),

                                        Select::make('position')
                                            ->label('Chức vụ')
                                            ->options(Staff::getPositionOptions())
                                            ->required()
                                            ->searchable(),

                                        TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->maxLength(255),

                                        TextInput::make('phone')
                                            ->label('Số điện thoại')
                                            ->tel()
                                            ->maxLength(255),
                                    ])->columns(2),

                                Section::make('Ảnh đại diện')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->label('Ảnh đại diện')
                                            ->image()
                                            ->directory('staff/avatars')
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '1:1',
                                                '4:3',
                                            ])
                                            ->maxSize(2048)
                                            ->helperText('Tải lên ảnh đại diện (tối đa 2MB)'),
                                    ]),

                                Section::make('Mô tả')
                                    ->schema([
                                        RichEditor::make('description')
                                            ->label('Mô tả chi tiết')
                                            ->toolbarButtons([
                                                'bold',
                                                'italic',
                                                'underline',
                                                'bulletList',
                                                'orderedList',
                                                'link',
                                            ])
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tabs\Tab::make('Mạng xã hội')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Section::make('Liên kết Mạng xã hội')
                                    ->schema([
                                        Repeater::make('social_links')
                                            ->label('Liên kết mạng xã hội')
                                            ->schema([
                                                Select::make('platform')
                                                    ->label('Nền tảng')
                                                    ->options(Staff::getSocialPlatforms())
                                                    ->required(),

                                                TextInput::make('url')
                                                    ->label('Đường dẫn')
                                                    ->url()
                                                    ->required(),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(0)
                                            ->addActionLabel('Thêm liên kết')
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['platform'] ?? null),
                                    ]),
                            ]),

                        Tabs\Tab::make('SEO & Cài đặt')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Section::make('Cài đặt Hiển thị')
                                    ->schema([
                                        Select::make('status')
                                            ->label('Trạng thái')
                                            ->options([
                                                'active' => 'Hiển thị',
                                                'hidden' => 'Ẩn',
                                            ])
                                            ->default('active')
                                            ->required(),

                                        TextInput::make('order')
                                            ->label('Thứ tự hiển thị')
                                            ->numeric()
                                            ->default(0)
                                            ->helperText('Số nhỏ hơn sẽ hiển thị trước'),
                                    ])->columns(2),

                                Section::make('Tối ưu SEO')
                                    ->schema([
                                        TextInput::make('seo_title')
                                            ->label('Tiêu đề SEO')
                                            ->maxLength(60)
                                            ->helperText('Để trống để tự động tạo từ tên và chức vụ'),

                                        Textarea::make('seo_description')
                                            ->label('Mô tả SEO')
                                            ->maxLength(160)
                                            ->rows(3)
                                            ->helperText('Để trống để tự động tạo từ mô tả'),

                                        FileUpload::make('og_image')
                                            ->label('Ảnh chia sẻ mạng xã hội')
                                            ->image()
                                            ->directory('staff/og-images')
                                            ->maxSize(1024)
                                            ->helperText('Ảnh hiển thị khi chia sẻ trên mạng xã hội (1200x630px)'),
                                    ]),
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
                    ->label('Thứ tự')
                    ->sortable()
                    ->width(80),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Ảnh')
                    ->circular()
                    ->size(50),

                Tables\Columns\TextColumn::make('name')
                    ->label('Họ và tên')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('position')
                    ->label('Chức vụ')
                    ->searchable()
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
                        'hidden' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Hiển thị',
                        'hidden' => 'Ẩn',
                        default => $state,
                    }),

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
                        'hidden' => 'Ẩn',
                    ]),

                Tables\Filters\SelectFilter::make('position')
                    ->label('Chức vụ')
                    ->options(Staff::getPositionOptions()),
            ])
            ->actions([
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
            ->reorderable('order')
            ->defaultSort('order')
            ->emptyStateHeading('Chưa có nhân viên nào')
            ->emptyStateDescription('Thêm nhân viên đầu tiên để bắt đầu.')
            ->emptyStateIcon('heroicon-o-user-group');
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
            'index' => Pages\ListStaff::route('/'),
            'create' => Pages\CreateStaff::route('/create'),
            'edit' => Pages\EditStaff::route('/{record}/edit'),
        ];
    }
}
