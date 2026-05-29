<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        // Stats
        $totalCampaigns = Campaign::where('status', 'approved')->count();
        $totalDonors = Donation::where('payment_status', 'paid')->distinct('donor_email')->count('donor_email');
        $totalRaised = Donation::where('payment_status', 'paid')->sum('donation_amount');

        // Leaderboard top 3
        $leaderboard = app(\App\Services\LeaderboardService::class);
        $topDonors = array_slice($leaderboard->topDonors('all', 3), 0, 3);
        $topCampaigns = array_slice($leaderboard->topCampaigns('all', 3), 0, 3);
        $topCreators = array_slice($leaderboard->topCreators('all', 3), 0, 3);

        return view('home', [
            'title' => 'Autopahala - Platform Crowdfunding',
            'categories' => $categories,
            'totalCampaigns' => $totalCampaigns,
            'totalDonors' => $totalDonors,
            'totalRaised' => $totalRaised,
            'topDonors' => $topDonors,
            'topCampaigns' => $topCampaigns,
            'topCreators' => $topCreators,
        ]);
    }
}
