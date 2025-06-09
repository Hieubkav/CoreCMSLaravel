<?php

namespace App\Generated\Filament\Resources\MenuItemResource\Pages;

use App\Generated\Filament\Resources\MenuItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMenuItems extends ListRecords
{
    protected static string $resource = MenuItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tạo menu mới'),
        ];
    }
}
