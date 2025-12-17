<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequisitionCreated extends Notification
{
     use Queueable;

    protected $requisition;

    public function __construct($requisition)
    {
        $this->requisition = $requisition;
    }

    public function via($notifiable)
    {
        return ['webpush'];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('New Requisition Created')
            ->body('Requisition #' . $this->requisition->id . ' has been created')
            ->icon('/icon-192.png')
            ->data([
                'url' => url('/requisitions/' . $this->requisition->id)
            ])
            ->action('View', 'open_requisition');
    }
}
