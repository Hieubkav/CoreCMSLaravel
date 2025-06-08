<?php

namespace App\Filament\Admin\Resources\PostResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class PostImagesRelationManager extends RelationManager
{

    protected static string $relationship = 'images';

    protected static ?string $title = 'Hình ảnh bài viết';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->createGalleryUpload(
                    'image_link',
                    'Hình ảnh',
                    'posts/gallery',
                    800,
                    600
                )->required()->columnSpanFull(),

                TextInput::make('alt_text')
                    ->label('Alt text (SEO)')
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('order')
                    ->label('Thứ tự hiển thị')
                    ->integer()
                    ->default(0)
                    ->minValue(0)
                    ->columnSpan(1),

                Toggle::make('status')
                    ->label('Hiển thị')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
                    ->columnSpan(1),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('alt_text')
            ->columns([
                TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->width(80),

                ImageColumn::make('image_link')
                    ->label('Hình ảnh')
                    ->height(80)
                    ->width(120),

                TextColumn::make('alt_text')
                    ->label('Alt text')
                    ->limit(50)
                    ->searchable(),

                ToggleColumn::make('status')
                    ->label('Hiển thị')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Trạng thái hiển thị')
                    ->boolean()
                    ->trueLabel('Đang hiển thị')
                    ->falseLabel('Đã ẩn')
                    ->native(false),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Thêm hình ảnh'),
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
            ->defaultSort('order', 'asc')
            ->reorderable('order');
    }
}
