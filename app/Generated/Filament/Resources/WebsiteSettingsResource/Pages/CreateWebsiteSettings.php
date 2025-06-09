<?php

namespace App\Generated\Filament\Resources\WebsiteSettingsResource\Pages;

use App\Generated\Filament\Resources\WebsiteSettingsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWebsiteSettings extends CreateRecord
{
    protected static string $resource = WebsiteSettingsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set default values if not provided
        if (empty($data['site_name'])) {
            $data['site_name'] = config('app.name', 'Core Framework');
        }

        // Set default file types if not provided
        if (empty($data['allowed_file_types'])) {
            $data['allowed_file_types'] = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx'];
        }

        return $data;
    }
}
