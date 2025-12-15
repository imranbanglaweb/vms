<?php
// app/Notifications/TestPushNotification.php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use NotificationChannels\WebPush\WebPushMessage;

class TestPushNotification extends Notification
{
    protected $title;
    protected $message;
    protected $link;
    protected $type;

    public function __construct($title, $message = null, $type = 'info', $link = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->link = $link;
    }

    public function via($notifiable)
    {
        return ['webpush', 'database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'link' => $this->link,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
       return (new WebPushMessage)
        ->title('VMS Notification')
        ->body('Web push is working successfully')
        ->icon('/icon.png')
        ->action('Open App', url('/dashboard'));
    }
}

