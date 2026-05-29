<?php

namespace App\Http\Controllers;

use App\Services\LeaderboardService;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function __construct(
        protected LeaderboardService $leaderboardService
    ) {}

    public function index(Request $request)
    {
        $period = $request->input('period', 'all');
        $tab = $request->input('tab', 'donors');

        if (!in_array($period, ['weekly', 'monthly', 'yearly', 'all'])) {
            $period = 'all';
        }

        $topDonors = $this->leaderboardService->topDonors($period);
        $topCampaigns = $this->leaderboardService->topCampaigns($period);
        $topCreators = $this->leaderboardService->topCreators($period);

        return view('leaderboard', compact('topDonors', 'topCampaigns', 'topCreators', 'period', 'tab'));
    }
}
