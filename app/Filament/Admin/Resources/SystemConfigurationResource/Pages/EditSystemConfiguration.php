<?php

namespace App\Filament\Admin\Resources\SystemConfigurationResource\Pages;

use App\Filament\Admin\Resources\SystemConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSystemConfiguration extends EditRecord
{
    protected static string $resource = SystemConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
