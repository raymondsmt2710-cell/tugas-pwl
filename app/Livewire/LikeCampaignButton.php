<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\CampaignLike;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LikeCampaignButton extends Component
{
    public Campaign $campaign;
    public bool $liked = false;
    public int $likesCount = 0;

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign;
        $this->likesCount = $campaign->likes()->count();
        $this->liked = Auth::check() ? $campaign->isLikedByUser(Auth::id()) : false;
    }

    public function toggleLike()
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'));
        }

        $userId = Auth::id();
        $like = CampaignLike::where('id_campaign', $this->campaign->id_campaign)
            ->where('id_user', $userId)
            ->first();

        if ($like) {
            $like->delete();
            $this->liked = false;
            $this->likesCount = max(0, $this->likesCount - 1);
        } else {
            CampaignLike::create([
                'id_campaign' => $this->campaign->id_campaign,
                'id_user' => $userId,
            ]);
            $this->liked = true;
            $this->likesCount++;
        }
    }

    public function render()
    {
        return view('livewire.like-campaign-button');
    }
}
