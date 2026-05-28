<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CommentReply extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $replier,
        public string $campaignTitle,
        public string $campaignSlug,
        public string $replyPreview
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'comment_reply',
            'title' => 'Balasan Komentar',
            'message' => $this->replier->full_name . ' membalas komentar Anda di "' . $this->campaignTitle . '".',
            'replier_id' => $this->replier->id_user,
            'replier_name' => $this->replier->full_name,
            'campaign_slug' => $this->campaignSlug,
            'reply_preview' => $this->replyPreview,
        ];
    }
}
