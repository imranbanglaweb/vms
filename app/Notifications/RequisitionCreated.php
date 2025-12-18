<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class RequisitionCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $requisition;

    public function __construct($requisition)
    {
        $this->requisition = $requisition;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title("New Requisition: {$this->requisition->requisition_number}")
            ->icon('/icon-192x192.png') // put your professional icon here
            ->body("A new requisition has been created by {$this->requisition->requested_by_name}. Click to view details.")
            ->action('View Requisition', route('requisitions.show', $this->requisition->id))
            ->data([
                'url' => route('requisitions.show', $this->requisition->id)
            ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => "New Requisition: {$this->requisition->requisition_number}",
            'message' => "A new requisition has been created by {$this->requisition->requested_by_name}.",
            'url' => route('requisitions.show', $this->requisition->id)
        ];
    }
}
