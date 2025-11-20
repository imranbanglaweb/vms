<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        Notification::where('user_id', auth()->id())
            ->update(['is_read' => 1]);

        return view('admin.dashboard.notifications.index', compact('notifications'));
    }
}
