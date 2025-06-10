<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RoleResource\Pages;
use Spatie\Permission\Models\Role;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Vai trò';

    protected static ?string $modelLabel = 'vai trò';

    protected static ?string $pluralModelLabel = 'vai trò';

    protected static ?string $navigationGroup = 'Quản lý hệ thống';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin vai trò')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên vai trò')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        TextInput::make('guard_name')
                            ->label('Guard')
                            ->default('web')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(3)
                            ->maxLength(500),
                    ])->columns(2),

                Section::make('Quyền hạn')
                    ->schema([
                        Select::make('permissions')
                            ->label('Quyền hạn')
                            ->multiple()
                            ->relationship('permissions', 'name')
                            ->preload()
                            ->searchable()
                            ->helperText('Chọn các quyền hạn cho vai trò này'),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên vai trò')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('permissions_count')
                    ->label('Số quyền')
                    ->counts('permissions')
                    ->badge()
                    ->color('success'),

                TextColumn::make('users_count')
                    ->label('Số người dùng')
                    ->counts('users')
                    ->badge()
                    ->color('info'),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()
                    ->label('Xem'),
                EditAction::make()
                    ->label('Sửa'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Xóa đã chọn'),
                ]),
            ])
            ->defaultSort('name');
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'view' => Pages\ViewRole::route('/{record}'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
