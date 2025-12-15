<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class TestPushNotification extends Notification
{
    public function via($notifiable)
    {
        return ['webpush', 'database'];
    }

    public function toWebPush($notifiable, $notification)
    {
       return (new WebPushMessage)
            ->title('VMS Notification')
            ->body('Web push is working successfully')
            ->icon('/icon.png')
            ->action('Open App', url('/dashboard'));
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'VMS Notification',
            'message' => 'Web push saved in database',
        ];
    }
}
