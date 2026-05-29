<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class PrivacySettings extends Component
{
    public bool $show_profile_publicly = true;
    public bool $show_followers_count = true;
    public bool $show_following_count = true;

    public function mount(): void
    {
        $settings = auth()->user()->getSettings();
        $this->show_profile_publicly = $settings->show_profile_publicly;
        $this->show_followers_count = $settings->show_followers_count;
        $this->show_following_count = $settings->show_following_count;
    }

    public function updated($property): void
    {
        $settings = auth()->user()->getSettings();
        $settings->update([
            'show_profile_publicly' => $this->show_profile_publicly,
            'show_followers_count' => $this->show_followers_count,
            'show_following_count' => $this->show_following_count,
        ]);
    }

    public function render()
    {
        return view('livewire.settings.privacy-settings');
    }
}
