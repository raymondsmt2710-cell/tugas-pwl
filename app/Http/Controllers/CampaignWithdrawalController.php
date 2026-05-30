<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Withdrawal;

class CampaignWithdrawalController extends Controller
{
    public function index(string $slug)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();

        $withdrawals = Withdrawal::forCampaign($campaign->id_campaign)
            ->where('status', 'paid')
            ->latest('paid_at')
            ->paginate(15);

        return view('campaigns.withdrawals', compact('campaign', 'withdrawals'));
    }
}
