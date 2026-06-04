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

        // Hanya tampilkan campaign yang sudah disetujui admin (approved & goal_reached)
        // Berlaku untuk semua orang termasuk pemilik profil sendiri
        $campaigns = $user->campaigns()
            ->whereIn('status', ['approved', 'goal_reached'])
            ->with('category')
            ->withCount('likes')
            ->latest()
            ->get();

        $likedIds = auth()->check()
            ? \App\Models\CampaignLike::where('id_user', auth()->id())
                ->whereIn('id_campaign', $campaigns->pluck('id_campaign'))
                ->pluck('id_campaign')
                ->toArray()
            : [];

        // Liked campaigns (kampanye yang disukai oleh user ini)
        $likedCampaigns = collect();
        $userComments = collect();
        if ($isOwner && auth()->check()) {
            $likedCampaignIds = \App\Models\CampaignLike::where('id_user', $user->id_user)
                ->pluck('id_campaign')
                ->toArray();

            $likedCampaigns = \App\Models\Campaign::whereIn('id_campaign', $likedCampaignIds)
                ->whereIn('status', ['approved', 'goal_reached'])
                ->with('category')
                ->withCount('likes')
                ->latest()
                ->get();

            $userComments = \App\Models\CampaignComment::where('id_user', $user->id_user)
                ->with(['campaign' => function ($q) {
                    $q->with('category')->withCount('likes');
                }])
                ->latest()
                ->get();
        }

        // Stats
        $totalDonationsReceived = $user->campaigns()->sum('collected_amount');
        $campaignCount = $user->campaigns()->count();

        // Respect privacy settings for follower/following counts + lists
        $showFollowers = $settings->show_followers_count || $isOwner;
        $showFollowing = $settings->show_following_count || $isOwner;

        $followersCount = $showFollowers ? $user->followers()->count() : null;
        $followingCount = $showFollowing ? $user->following()->count() : null;

        // Load actual lists (only if allowed to see)
        $followersList = $showFollowers ? $user->followers()->get() : collect();
        $followingList = $showFollowing ? $user->following()->get() : collect();

        $isFollowing = auth()->check() ? auth()->user()->isFollowing($user) : false;

        return view('profile.public-show', [
            'user' => $user,
            'campaigns' => $campaigns,
            'likedIds' => $likedIds,
            'likedCampaigns' => $likedCampaigns,
            'userComments' => $userComments,
            'totalDonationsReceived' => $totalDonationsReceived,
            'campaignCount' => $campaignCount,
            'followersCount' => $followersCount,
            'followingCount' => $followingCount,
            'followersList' => $followersList,
            'followingList' => $followingList,
            'showFollowers' => $showFollowers,
            'showFollowing' => $showFollowing,
            'isFollowing' => $isFollowing,
            'isOwner' => $isOwner,
            'settings' => $settings,
        ]);
    }
}
