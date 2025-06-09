<?php

namespace App\Generated\Filament\Resources\WebsiteSettingsResource\Pages;

use App\Generated\Filament\Resources\WebsiteSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditWebsiteSettings extends EditRecord
{
    protected static string $resource = WebsiteSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('activate')
                ->label('Kích hoạt cài đặt này')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function () {
                    $this->record->setActive();
                    
                    Notification::make()
                        ->title('Đã kích hoạt cài đặt')
                        ->body('Cài đặt này đã được kích hoạt và áp dụng cho toàn bộ website.')
                        ->success()
                        ->send();
                        
                    $this->redirect($this->getResource()::getUrl('index'));
                })
                ->visible(fn () => !$this->record->is_active),
            
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        // Clear cache after saving
        $this->record->clearCache();
        
        Notification::make()
            ->title('Đã lưu cài đặt')
            ->body('Cache đã được xóa và cài đặt mới đã được áp dụng.')
            ->success()
            ->send();
    }
}
