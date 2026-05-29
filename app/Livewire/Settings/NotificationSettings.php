<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class NotificationSettings extends Component
{
    public bool $notify_donation_received = true;
    public bool $notify_campaign_approved = true;
    public bool $notify_withdrawal_approved = true;

    public function mount(): void
    {
        $settings = auth()->user()->getSettings();
        $this->notify_donation_received = $settings->notify_donation_received;
        $this->notify_campaign_approved = $settings->notify_campaign_approved;
        $this->notify_withdrawal_approved = $settings->notify_withdrawal_approved;
    }

    public function updated($property): void
    {
        $settings = auth()->user()->getSettings();
        $settings->update([
            'notify_donation_received' => $this->notify_donation_received,
            'notify_campaign_approved' => $this->notify_campaign_approved,
            'notify_withdrawal_approved' => $this->notify_withdrawal_approved,
        ]);
    }

    public function render()
    {
        return view('livewire.settings.notification-settings');
    }
}
