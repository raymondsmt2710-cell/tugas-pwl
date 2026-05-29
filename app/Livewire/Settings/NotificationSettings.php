<?php

namespace App\Livewire\Settings;

use App\Models\UserSetting;
use Livewire\Component;

class NotificationSettings extends Component
{
    public bool $notify_donation_received = true;
    public bool $notify_campaign_approved = true;
    public bool $notify_withdrawal_approved = true;

    public function mount(): void
    {
        $settings = auth()->user()->getSettings();
        $this->notify_donation_received = (bool) $settings->notify_donation_received;
        $this->notify_campaign_approved = (bool) $settings->notify_campaign_approved;
        $this->notify_withdrawal_approved = (bool) $settings->notify_withdrawal_approved;
    }

    public function updatedNotifyDonationReceived(): void
    {
        $this->saveSettings();
    }

    public function updatedNotifyCampaignApproved(): void
    {
        $this->saveSettings();
    }

    public function updatedNotifyWithdrawalApproved(): void
    {
        $this->saveSettings();
    }

    private function saveSettings(): void
    {
        UserSetting::where('user_id', auth()->user()->id_user)->update([
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
