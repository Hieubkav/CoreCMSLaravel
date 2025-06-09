<?php

namespace App\Generated\Filament\Resources\WebDesignResource\Pages;

use App\Generated\Filament\Resources\WebDesignResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditWebDesign extends EditRecord
{
    protected static string $resource = WebDesignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('activate')
                ->label('Kích hoạt thiết kế này')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function () {
                    $this->record->setActive();
                    
                    Notification::make()
                        ->title('Đã kích hoạt thiết kế')
                        ->body('Thiết kế này đã được kích hoạt và áp dụng cho toàn bộ website.')
                        ->success()
                        ->send();
                        
                    $this->redirect($this->getResource()::getUrl('index'));
                })
                ->visible(fn () => !$this->record->is_active),
            
            Actions\Action::make('setDefault')
                ->label('Đặt làm mặc định')
                ->icon('heroicon-o-star')
                ->color('warning')
                ->action(function () {
                    $this->record->setDefault();
                    
                    Notification::make()
                        ->title('Đã đặt làm thiết kế mặc định')
                        ->body('Thiết kế này sẽ được sử dụng cho các trang mới.')
                        ->success()
                        ->send();
                        
                    $this->redirect($this->getResource()::getUrl('index'));
                })
                ->visible(fn () => !$this->record->is_default),
            
            Actions\Action::make('preview')
                ->label('Xem trước')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url(fn () => route('design.preview', $this->record))
                ->openUrlInNewTab(),
            
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
            ->title('Đã lưu thiết kế')
            ->body('Cache đã được xóa và thiết kế mới đã được áp dụng.')
            ->success()
            ->send();
    }
}
