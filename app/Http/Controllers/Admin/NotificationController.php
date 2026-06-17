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
            'user_ids' => 'required_if:recipient_type,specific|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'jenis' => 'required|in:laporan,perjanjian',
            'tahun' => 'required|integer|min:2000',
            'triwulan' => 'required_if:jenis,laporan|in:1,2,3,4',
            'tanggal_batas' => 'required|date',
            'type' => 'required|in:info,warning,danger',
            'pesan_tambahan' => 'nullable|string'
        ]);

        $twLabels = ['1' => 'I', '2' => 'II', '3' => 'III', '4' => 'IV'];
        $title = $validated['jenis'] === 'laporan'
            ? "Batas Laporan Kinerja Triwulan {$twLabels[$validated['triwulan']]} - {$validated['tahun']}"
            : "Batas Perjanjian Kinerja - {$validated['tahun']}";

        $message = $validated['jenis'] === 'laporan'
            ? "Segera selesaikan laporan kinerja Triwulan {$twLabels[$validated['triwulan']]} Tahun {$validated['tahun']} sebelum " . date('d M Y', strtotime($validated['tanggal_batas'])) . "."
            : "Segera buat perjanjian kinerja Tahun {$validated['tahun']} sebelum " . date('d M Y', strtotime($validated['tanggal_batas'])) . ".";

        if (!empty($validated['pesan_tambahan'])) {
            $message .= ' ' . $validated['pesan_tambahan'];
        }

        if ($validated['recipient_type'] === 'all') {
            // Broadcast to all users
            Notification::create([
                'user_id' => null,
                'title' => $title,
                'message' => $message,
                'type' => $validated['type'],
                'is_read' => false
            ]);

            return redirect()->route('admin.notifications.index')
                ->with('success', 'Notifikasi berhasil dikirim ke semua pengguna');
        }

        // Send to one or more specific users
        foreach ($validated['user_ids'] as $userId) {
            Notification::create([
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'type' => $validated['type'],
                'is_read' => false
            ]);
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil dikirim ke pengguna yang dipilih');
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
