<?php

namespace App\Notifications;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CampaignUpdatePosted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Campaign $campaign,
        public string $updateTitle
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'campaign_update',
            'title' => 'Update Kampanye',
            'message' => 'Kampanye "' . $this->campaign->title . '" memposting update baru: ' . $this->updateTitle,
            'campaign_id' => $this->campaign->id_campaign,
            'campaign_slug' => $this->campaign->slug,
            'update_title' => $this->updateTitle,
        ];
    }
}
