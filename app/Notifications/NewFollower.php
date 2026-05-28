<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewFollower extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $follower) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'new_follower',
            'title' => 'Pengikut Baru',
            'message' => $this->follower->full_name . ' mulai mengikuti Anda.',
            'follower_id' => $this->follower->id_user,
            'follower_username' => $this->follower->username,
            'follower_name' => $this->follower->full_name,
        ];
    }
}
