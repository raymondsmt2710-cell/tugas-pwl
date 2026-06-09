<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationList extends Component
{
    use WithPagination;

    public function markAsRead(string $id)
    {
        $user = Auth::user();
        if (!$user) return;

        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Dispatch notification updated to update NotificationBell navbar
        $this->dispatch('notification-updated');

        // Redirect based on type
        $url = $this->getRedirectUrl($notification->data);
        return $this->redirect($url);
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        if (!$user) return;

        $user->unreadNotifications->markAsRead();

        // Dispatch notification updated
        $this->dispatch('notification-updated');

        $this->dispatch('toast', message: 'Semua notifikasi ditandai sudah dibaca.', type: 'success');
    }

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

    public function render()
    {
        $user = Auth::user();
        $notifications = $user ? $user->notifications()->paginate(20) : collect();

        return view('livewire.notification-list', compact('notifications'));
    }
}
