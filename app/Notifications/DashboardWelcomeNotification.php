<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class DashboardWelcomeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'format' => 'filament',
            'title' => 'Welcome to Core Framework!',
            'body' => 'Dashboard has been updated with beautiful interface and optimized performance.',
            'icon' => 'heroicon-o-sparkles',
            'iconColor' => 'success',
            'actions' => [
                [
                    'name' => 'view_courses',
                    'label' => 'View Courses',
                    'url' => route('filament.admin.resources.courses.index'),
                ],
            ],
        ];
    }

    /**
     * Get the Filament notification representation of the notification.
     */
    public function toFilament(): FilamentNotification
    {
        return FilamentNotification::make()
            ->title('Welcome to Core Framework!')
            ->body('Dashboard has been updated with beautiful interface and optimized performance.')
            ->icon('heroicon-o-sparkles')
            ->iconColor('success')
            ->actions([
                \Filament\Notifications\Actions\Action::make('view_courses')
                    ->label('View Courses')
                    ->url(route('filament.admin.resources.courses.index')),
            ]);
    }
}
