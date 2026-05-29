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
        $channels = ['database'];

        // Check user email notification preference
        $settings = $notifiable->getSettings();
        if ($settings->notify_donation_received) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Donasi Baru Diterima - AutoPahala')
            ->greeting('Halo ' . $notifiable->full_name . '!')
            ->line($this->donation->display_name . ' berdonasi ' . $this->donation->formatted_amount . ' untuk kampanye Anda.')
            ->action('Lihat Kampanye', url('/campaigns/' . ($this->donation->campaign->slug ?? '')))
            ->line('Terima kasih telah menggunakan AutoPahala.');
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
