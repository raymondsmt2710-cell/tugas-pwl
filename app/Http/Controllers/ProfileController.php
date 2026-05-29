<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $settings = $user->getSettings();

        // Determine if the viewer is the profile owner
        $isOwner = auth()->check() && auth()->user()->id_user === $user->id_user;

        // If profile is private and viewer is not the owner, show private page
        if (!$settings->show_profile_publicly && !$isOwner) {
            return view('profile.private', ['user' => $user]);
        }

        // Get user's approved/active campaigns
        $campaigns = $user->campaigns()
            ->where('status', 'approved')
            ->with('category')
            ->latest()
            ->get();

        // Get donations
        $donationsQuery = $user->donations()
            ->with('campaign')
            ->latest()
            ->take(10);

        if (!$isOwner) {
            $donationsQuery->where('payment_status', 'paid')
                ->where('is_anonymous', false);
        }

        $donations = $donationsQuery->get();

        // Stats
        $totalDonationsReceived = $user->campaigns()->sum('collected_amount');
        $campaignCount = $user->campaigns()->count();

        // Respect privacy settings for follower/following counts
        $followersCount = $settings->show_followers_count || $isOwner ? $user->followers()->count() : null;
        $followingCount = $settings->show_following_count || $isOwner ? $user->following()->count() : null;

        $isFollowing = auth()->check() ? auth()->user()->isFollowing($user) : false;

        return view('profile.public-show', [
            'user' => $user,
            'campaigns' => $campaigns,
            'donations' => $donations,
            'totalDonationsReceived' => $totalDonationsReceived,
            'campaignCount' => $campaignCount,
            'followersCount' => $followersCount,
            'followingCount' => $followingCount,
            'isFollowing' => $isFollowing,
            'isOwner' => $isOwner,
        ]);
    }
}
