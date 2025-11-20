<?php
use App\Models\Notification;

function sendNotification($toUser, $title, $message = null, $type = 'info', $link = null)
{
    return Notification::create([
        'user_id' => $toUser,
        'from_user_id' => auth()->id(),
        'title' => $title,
        'message' => $message,
        'type' => $type,
        'link' => $link,
    ]);
}
