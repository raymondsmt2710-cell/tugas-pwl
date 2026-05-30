<?php

namespace App\Livewire;

use App\Models\Campaign;
use Livewire\Component;

class FeaturedCampaigns extends Component
{
    public function render()
    {
        $campaigns = Campaign::whereIn('status', ['approved', 'goal_reached'])
            ->where('end_date', '>', now())
            ->with(['category', 'user'])
            ->latest()
            ->take(6)
            ->get();

        return view('livewire.featured-campaigns', compact('campaigns'));
    }
}
