<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get unread notifications count
     */
    public function getUnreadCount()
    {
        $count = Notification::query()
                    ->where(function ($query) {
                        $query->where('user_id', Auth::id())
                              ->orWhereNull('user_id');
                    })
                    ->where('is_read', false)
                    ->count();
        
        return response()->json(['count' => $count]);
    }
    
    /**
     * Get all notifications for current user
     */
    public function index()
    {
        $notifications = Notification::query()
            ->where(function ($query) {
                $query->where('user_id', Auth::id())
                      ->orWhereNull('user_id');
            })
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
        return response()->json($notifications);
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::query()
                                   ->where('id', $id)
                                   ->where(function ($query) {
                                       $query->where('user_id', Auth::id())
                                             ->orWhereNull('user_id');
                                   })
                                   ->firstOrFail();

        if ($notification->user_id !== null) {
            $notification->markAsRead();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::query()
                   ->where('user_id', Auth::id())
                   ->where('is_read', false)
                   ->update([
                       'is_read' => true,
                       'read_at' => now()
                   ]);
        
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }
    
    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('user_id', Auth::id())
                                   ->where('id', $id)
                                   ->firstOrFail();
        
        $notification->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification deleted'
        ]);
    }
}
