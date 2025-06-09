<?php

namespace App\Generated\Filament\Resources\WebDesignResource\Pages;

use App\Generated\Filament\Resources\WebDesignResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebDesigns extends ListRecords
{
    protected static string $resource = WebDesignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tạo thiết kế mới'),
        ];
    }
}
