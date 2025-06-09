<?php

namespace App\Generated\Filament\Resources\WebsiteSettingsResource\Pages;

use App\Generated\Filament\Resources\WebsiteSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebsiteSettings extends ListRecords
{
    protected static string $resource = WebsiteSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tạo cài đặt mới'),
        ];
    }
}
