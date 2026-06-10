<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use App\Models\User;
use App\Models\Banner;
use App\Models\HowItWork;
use App\Models\SiteSetting;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        // Stats
        $totalCampaigns = Campaign::whereIn('status', ['approved', 'goal_reached'])->count();
        $totalDonors = Donation::where('payment_status', 'paid')->distinct('donor_email')->count('donor_email');
        $totalRaised = Donation::where('payment_status', 'paid')->sum('donation_amount');

        // Leaderboard top 3
        $leaderboard = app(\App\Services\LeaderboardService::class);
        $topDonors = array_slice($leaderboard->topDonors('all', 3), 0, 3);
        $topCampaigns = array_slice($leaderboard->topCampaigns('all', 3), 0, 3);
        $topCreators = array_slice($leaderboard->topCreators('all', 3), 0, 3);

        // Content
        $banners = Banner::where('is_active', true)->orderBy('order', 'asc')->get();
        $howItWorks = HowItWork::where('is_active', true)->orderBy('step_number', 'asc')->get();
        $siteSetting = SiteSetting::first();

        return view('home', [
            'title' => ($siteSetting->site_name ?? 'Autopahala') . ' - Platform Crowdfunding',
            'categories' => $categories,
            'totalCampaigns' => $totalCampaigns,
            'totalDonors' => $totalDonors,
            'totalRaised' => $totalRaised,
            'topDonors' => $topDonors,
            'topCampaigns' => $topCampaigns,
            'topCreators' => $topCreators,
            'banners' => $banners,
            'howItWorks' => $howItWorks,
            'siteSetting' => $siteSetting,
        ]);
    }
}
