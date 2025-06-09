<?php

namespace App\Filament\Admin\Resources\SystemConfigurationResource\Pages;

use App\Filament\Admin\Resources\SystemConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSystemConfigurations extends ListRecords
{
    protected static string $resource = SystemConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
