<?php

namespace App\Filament\Resources\Generated\Filament\Resources\PostCategoryResource\Pages;

use App\Filament\Resources\Generated\Filament\Resources\PostCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPostCategories extends ListRecords
{
    protected static string $resource = PostCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
