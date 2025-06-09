<?php

namespace App\Generated\Filament\Resources\WebDesignResource\Pages;

use App\Generated\Filament\Resources\WebDesignResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWebDesign extends CreateRecord
{
    protected static string $resource = WebDesignResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set default name if not provided
        if (empty($data['name'])) {
            $data['name'] = ucfirst($data['theme_type'] ?? 'modern') . ' Theme';
        }

        return $data;
    }
}
