<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TestNotification implements ShouldBroadcast
{
    use SerializesModels;

    public $message;

    public function __construct($msg)
    {
        $this->message = $msg;
    }

    public function broadcastOn()
    {
        return new Channel('dashboard');
    }
}
