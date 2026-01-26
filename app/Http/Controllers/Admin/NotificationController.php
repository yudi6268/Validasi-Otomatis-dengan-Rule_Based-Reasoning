<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display notifications page
     */
    public function index()
    {
        $notifications = Notification::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Show form to create notification
     */
    public function create()
    {
        $users = User::orderBy('nama')->get();
        return view('admin.notifications.create', compact('users'));
    }

    /**
     * Store new notification
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_type' => 'required|in:all,specific',
            'user_id' => 'required_if:recipient_type,specific|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,danger'
        ]);

        if ($validated['recipient_type'] === 'all') {
            // Broadcast to all users
            Notification::create([
                'user_id' => null,
                'title' => $validated['title'],
                'message' => $validated['message'],
                'type' => $validated['type']
            ]);

            return redirect()->route('admin.notifications.index')
                ->with('success', 'Notifikasi berhasil dikirim ke semua pengguna');
        } else {
            // Send to specific user
            Notification::create([
                'user_id' => $validated['user_id'],
                'title' => $validated['title'],
                'message' => $validated['message'],
                'type' => $validated['type']
            ]);

            return redirect()->route('admin.notifications.index')
                ->with('success', 'Notifikasi berhasil dikirim');
        }
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil dihapus');
    }
}
