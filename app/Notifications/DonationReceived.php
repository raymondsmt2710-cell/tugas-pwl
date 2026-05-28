<?php

namespace App\Notifications;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class DonationReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Donation $donation) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'donation_received',
            'title' => 'Donasi Baru Diterima',
            'message' => ($this->donation->display_name) . ' berdonasi ' . $this->donation->formatted_amount . ' untuk kampanye Anda.',
            'campaign_id' => $this->donation->id_campaign,
            'campaign_slug' => $this->donation->campaign->slug ?? null,
            'donation_id' => $this->donation->id_donation,
            'amount' => $this->donation->donation_amount,
            'donor_name' => $this->donation->display_name,
        ];
    }
}
