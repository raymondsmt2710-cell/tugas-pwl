<?php

namespace App\Livewire;

use App\Services\LeaderboardService;
use Livewire\Attributes\Url;
use Livewire\Component;

class Leaderboard extends Component
{
    #[Url(keep: true)]
    public string $period = 'all';

    #[Url(keep: true)]
    public string $tab = 'donors';

    public function setPeriod(string $period)
    {
        if (in_array($period, ['weekly', 'monthly', 'yearly', 'all'])) {
            $this->period = $period;
        }
    }

    public function setTab(string $tab)
    {
        if (in_array($tab, ['donors', 'campaigns', 'creators'])) {
            $this->tab = $tab;
        }
    }

    public function render(LeaderboardService $leaderboardService)
    {
        $topDonors = $leaderboardService->topDonors($this->period);
        $topCampaigns = $leaderboardService->topCampaigns($this->period);
        $topCreators = $leaderboardService->topCreators($this->period);

        return view('livewire.leaderboard', compact('topDonors', 'topCampaigns', 'topCreators'));
    }
}
