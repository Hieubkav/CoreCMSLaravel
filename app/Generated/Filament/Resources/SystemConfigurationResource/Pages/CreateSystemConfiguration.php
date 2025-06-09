<?php

namespace App\Generated\Filament\Resources\SystemConfigurationResource\Pages;

use App\Generated\Filament\Resources\SystemConfigurationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSystemConfiguration extends CreateRecord
{
    protected static string $resource = SystemConfigurationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // If this is set as active, deactivate all others
        if ($data['is_active'] ?? false) {
            \App\Generated\Models\SystemConfiguration::query()
                ->update(['is_active' => false]);
        }

        return $data;
    }
}
