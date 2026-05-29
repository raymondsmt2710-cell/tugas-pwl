<?php

namespace App\Notifications;

use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class WithdrawalStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Withdrawal $withdrawal,
        public string $newStatus
    ) {}

    public function via($notifiable): array
    {
        $channels = ['database'];

        $settings = $notifiable->getSettings();
        if ($settings->notify_withdrawal_approved) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail($notifiable)
    {
        $statusMessages = [
            'under_review' => 'sedang ditinjau oleh admin',
            'approved' => 'telah disetujui',
            'rejected' => 'ditolak',
            'paid' => 'telah dibayarkan ke rekening Anda',
        ];

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Status Penarikan Dana - AutoPahala')
            ->greeting('Halo ' . $notifiable->full_name . '!')
            ->line('Penarikan ' . $this->withdrawal->formatted_amount . ' ' . ($statusMessages[$this->newStatus] ?? 'diperbarui') . '.')
            ->action('Lihat Riwayat Penarikan', url('/withdrawals/history'))
            ->line('Terima kasih telah menggunakan AutoPahala.');
    }

    public function toArray($notifiable): array
    {
        $statusMessages = [
            'under_review' => 'Penarikan Anda sedang ditinjau oleh admin.',
            'approved' => 'Penarikan ' . $this->withdrawal->formatted_amount . ' telah disetujui.',
            'rejected' => 'Penarikan ' . $this->withdrawal->formatted_amount . ' ditolak.',
            'paid' => 'Penarikan ' . $this->withdrawal->formatted_amount . ' telah dibayarkan ke rekening Anda.',
        ];

        return [
            'type' => 'withdrawal_status',
            'title' => 'Status Penarikan Diperbarui',
            'message' => $statusMessages[$this->newStatus] ?? 'Status penarikan berubah.',
            'withdrawal_id' => $this->withdrawal->id_withdrawal,
            'status' => $this->newStatus,
            'amount' => $this->withdrawal->amount,
        ];
    }
}
