<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewComment extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $commenter,
        public string $campaignTitle,
        public string $campaignSlug,
        public string $commentPreview
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'new_comment',
            'title' => 'Komentar Baru',
            'message' => $this->commenter->full_name . ' mengomentari kampanye "' . $this->campaignTitle . '".',
            'commenter_id' => $this->commenter->id_user,
            'commenter_name' => $this->commenter->full_name,
            'campaign_slug' => $this->campaignSlug,
            'comment_preview' => $this->commentPreview,
        ];
    }
}
