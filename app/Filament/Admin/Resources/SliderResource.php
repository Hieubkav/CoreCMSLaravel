<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SliderResource\Pages;
use App\Models\Slider;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Slider';

    protected static ?string $modelLabel = 'Slider';

    protected static ?string $pluralModelLabel = 'Sliders';

    protected static ?string $navigationGroup = 'Nội dung Trang chủ';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin Slider')
                    ->schema([
                        TextInput::make('title')
                            ->label('Tiêu đề')
                            ->maxLength(255)
                            ->helperText('Tiêu đề hiển thị trên slider'),

                        Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Mô tả ngắn gọn về slider'),

                        TextInput::make('link')
                            ->label('Đường dẫn')
                            ->url()
                            ->maxLength(255)
                            ->helperText('URL khi click vào slider (tùy chọn)'),

                        TextInput::make('button_text')
                            ->label('Text nút bấm')
                            ->maxLength(100)
                            ->helperText('Text hiển thị trên nút call-to-action'),
                    ])->columns(2),

                Section::make('Hình ảnh')
                    ->schema([
                        FileUpload::make('image_link')
                            ->label('Hình ảnh slider')
                            ->image()
                            ->directory('sliders')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '21:9',
                            ])
                            ->maxSize(2048)
                            ->required()
                            ->helperText('Tải lên hình ảnh slider (tỷ lệ 16:9 hoặc 21:9)'),

                        TextInput::make('alt_text')
                            ->label('Alt text')
                            ->maxLength(255)
                            ->helperText('Mô tả ảnh cho SEO và accessibility'),
                    ]),

                Section::make('Cài đặt')
                    ->schema([
                        Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'active' => 'Hiển thị',
                                'inactive' => 'Ẩn',
                            ])
                            ->default('active')
                            ->required(),

                        TextInput::make('order')
                            ->label('Thứ tự hiển thị')
                            ->numeric()
                            ->default(0)
                            ->helperText('Số nhỏ hơn sẽ hiển thị trước'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->width(80),

                ImageColumn::make('image_link')
                    ->label('Hình ảnh')
                    ->size(60)
                    ->square(),

                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record): ?string => $record->description),

                TextColumn::make('link')
                    ->label('Đường dẫn')
                    ->searchable()
                    ->limit(50)
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Hiển thị',
                        'inactive' => 'Ẩn',
                        default => $state,
                    }),

                TextColumn::make('updated_at')
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
            ->emptyStateHeading('Chưa có slider nào')
            ->emptyStateDescription('Thêm slider đầu tiên để bắt đầu.')
            ->emptyStateIcon('heroicon-o-photo');
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
