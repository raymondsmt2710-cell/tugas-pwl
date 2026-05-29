<?php

namespace App\Notifications;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CampaignStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Campaign $campaign,
        public string $newStatus
    ) {}

    public function via($notifiable): array
    {
        $channels = ['database'];

        $settings = $notifiable->getSettings();
        if ($settings->notify_campaign_approved) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail($notifiable)
    {
        $statusLabels = [
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'completed' => 'Selesai',
        ];

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Status Kampanye Diperbarui - AutoPahala')
            ->greeting('Halo ' . $notifiable->full_name . '!')
            ->line('Kampanye "' . $this->campaign->title . '" telah ' . ($statusLabels[$this->newStatus] ?? 'diperbarui') . '.')
            ->action('Lihat Kampanye', url('/campaigns/' . $this->campaign->slug))
            ->line('Terima kasih telah menggunakan AutoPahala.');
    }

    public function toArray($notifiable): array
    {
        $statusMessages = [
            'approved' => 'Kampanye "' . $this->campaign->title . '" telah disetujui dan sekarang aktif.',
            'rejected' => 'Kampanye "' . $this->campaign->title . '" ditolak. Silakan edit dan ajukan ulang.',
            'completed' => 'Kampanye "' . $this->campaign->title . '" telah ditandai selesai.',
        ];

        return [
            'type' => 'campaign_status',
            'title' => 'Status Kampanye Diperbarui',
            'message' => $statusMessages[$this->newStatus] ?? 'Status kampanye berubah.',
            'campaign_id' => $this->campaign->id_campaign,
            'campaign_slug' => $this->campaign->slug,
            'status' => $this->newStatus,
        ];
    }
}
