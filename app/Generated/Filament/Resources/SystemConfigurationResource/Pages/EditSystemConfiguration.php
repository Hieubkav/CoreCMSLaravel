<?php

namespace App\Generated\Filament\Resources\SystemConfigurationResource\Pages;

use App\Generated\Filament\Resources\SystemConfigurationResource;
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // If this is set as active, deactivate all others
        if ($data['is_active'] ?? false) {
            \App\Generated\Models\SystemConfiguration::query()
                ->where('id', '!=', $this->record->id)
                ->update(['is_active' => false]);
        }

        return $data;
    }
}
