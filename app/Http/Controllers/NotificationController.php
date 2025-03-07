<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Ambil notifikasi yang belum dibaca
    public function getNotifications()
    {
        $userId = Auth::user()->id;

        $notifications = Notifikasi::where('users_id', $userId)
            ->where('status', 'belum')
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications
        ]);
    }

    // Tandai notifikasi sebagai sudah dibaca
    public function readNotifications()
    {
        $userId = Auth::id();

        Notifikasi::where('users_id', $userId)
            ->where('status', 'belum')
            ->update(['status' => 'sudah']);

        return response()->json(['success' => true]);
    }
}
