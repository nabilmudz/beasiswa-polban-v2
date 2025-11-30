<?php

namespace App\Http\Controllers;
// NotificationController.php
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function getNotifData()
    {
        try {
            $notifikasi = Notifikasi::where('user_id', auth()->id())
                ->with('pengajuanBeasiswa.Beasiswa', 'pengajuanBeasiswa.Status')
                ->get();

            $unreadCount = Notifikasi::where('user_id', auth()->id())
                ->where('read', false)
                ->count();

            return response()->json(['notifications' => $notifikasi, 'unreadCount' => $unreadCount]);
        } catch (\Exception $e) {
            Log::error('Error fetching notifications: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function markAsRead($id)
    {
        try {
            $notification = Notifikasi::findOrFail($id);
            $notification->read = true; // Mark as read
            $notification->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error marking notification as read: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}