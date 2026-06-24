<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function unread()
    {
        // Get unread notifications
        $notifications = Notification::whereNull('read_at')
            ->latest()
            ->take(5) // Limit to 5 for dropdown
            ->get();

        $count = Notification::whereNull('read_at')->count();

        return response()->json([
            'count' => $count,
            'notifications' => $notifications
        ]);
    }

    public function markRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        if (is_null($notification->read_at)) {
            $notification->update(['read_at' => now()]);
        }

        return response()->json(['success' => true]);
    }

    public function markAllRead()
    {
        Notification::whereNull('read_at')->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
