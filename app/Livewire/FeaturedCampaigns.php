<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\CampaignLike;
use Livewire\Component;

class FeaturedCampaigns extends Component
{
    public function render()
    {
        $campaigns = Campaign::whereIn('status', ['approved', 'goal_reached'])
            ->where('end_date', '>', now())
            ->with(['category', 'user'])
            ->withCount('likes')
            ->latest()
            ->take(6)
            ->get();

        $likedIds = auth()->check()
            ? \App\Models\CampaignLike::where('id_user', auth()->id())
                ->whereIn('id_campaign', $campaigns->pluck('id_campaign'))
                ->pluck('id_campaign')
                ->toArray()
            : [];

        return view('livewire.featured-campaigns', compact('campaigns', 'likedIds'));
    }
}
