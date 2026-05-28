<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Show all notifications.
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Redirect based on notification type
        $url = $this->getRedirectUrl($notification->data);

        return redirect($url);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    /**
     * Get unread count (for AJAX/API).
     */
    public function unreadCount()
    {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count(),
        ]);
    }

    /**
     * Determine redirect URL based on notification type.
     */
    private function getRedirectUrl(array $data): string
    {
        return match ($data['type'] ?? '') {
            'donation_received' => url('/campaigns/' . ($data['campaign_slug'] ?? '')),
            'new_follower' => url('/@' . ($data['follower_username'] ?? '')),
            'new_comment', 'comment_reply' => url('/campaigns/' . ($data['campaign_slug'] ?? '')),
            'withdrawal_status' => url('/withdrawals/history'),
            'campaign_status' => url('/campaigns/' . ($data['campaign_slug'] ?? '')),
            'campaign_update' => url('/campaigns/' . ($data['campaign_slug'] ?? '')),
            default => url('/'),
        };
    }
}
