<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Filament\Admin\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Đơn hàng';

    protected static ?string $modelLabel = 'Đơn hàng';

    protected static ?string $pluralModelLabel = 'Đơn hàng';

    protected static ?string $navigationGroup = 'E-commerce';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('user_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('customer_email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_phone')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('customer_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('billing_first_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('billing_last_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('billing_company')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('billing_address_1')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('billing_address_2')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('billing_city')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('billing_state')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('billing_postcode')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('billing_country')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('ship_to_different_address')
                    ->required(),
                Forms\Components\TextInput::make('shipping_first_name')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('shipping_last_name')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('shipping_company')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('shipping_address_1')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('shipping_address_2')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('shipping_city')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('shipping_state')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('shipping_postcode')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('shipping_country')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('subtotal')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tax_amount')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('shipping_amount')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('discount_amount')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('total')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('payment_status')
                    ->required(),
                Forms\Components\TextInput::make('payment_method')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('transaction_id')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('meta_data')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing_first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing_last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing_company')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing_address_1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing_address_2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing_city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing_state')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing_postcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing_country')
                    ->searchable(),
                Tables\Columns\IconColumn::make('ship_to_different_address')
                    ->boolean(),
                Tables\Columns\TextColumn::make('shipping_first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_company')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_address_1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_address_2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_state')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_postcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_country')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shipping_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('payment_status'),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
