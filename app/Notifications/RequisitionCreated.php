<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class RequisitionCreated extends Notification
{
     use Queueable;
    protected $title;
    protected $message;
    protected $link;
    protected $type;

    // public function __construct($title, $message = null, $type = 'info', $link = null)
    // {
    //     $this->title = $title;
    //     $this->message = $message;
    //     $this->type = $type;
    //     $this->link = $link;
    // }

    public function via($notifiable)
    {
//         try {
//     app(\Illuminate\Notifications\ChannelManager::class)->driver('webpush');
//     dd('webpush driver loaded');
// } catch (\Exception $e) {
//     dd($e->getMessage());
// }

        // dd( app(\Illuminate\Notifications\ChannelManager::class)->getDrivers() );
        // return ['webpush'];
         return [WebPushChannel::class];
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
