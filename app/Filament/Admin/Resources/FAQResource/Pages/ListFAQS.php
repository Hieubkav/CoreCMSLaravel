<?php

namespace App\Filament\Admin\Resources\FAQResource\Pages;

use App\Filament\Admin\Resources\FAQResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFAQS extends ListRecords
{
    protected static string $resource = FAQResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
