<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        // Get user's approved/active campaigns
        $campaigns = $user->campaigns()
            ->where('status', 'approved')
            ->with('category')
            ->latest()
            ->get();

        // Determine if the viewer is the profile owner
        $isOwner = auth()->check() && auth()->user()->id_user === $user->id_user;

        // Get donations:
        // - Owner sees ALL their donations (including anonymous, all statuses)
        // - Public visitors only see paid, non-anonymous donations
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

        return view('profile.public-show', [
            'user' => $user,
            'campaigns' => $campaigns,
            'donations' => $donations,
            'totalDonationsReceived' => $totalDonationsReceived,
            'campaignCount' => $campaignCount,
            'isOwner' => $isOwner,
        ]);
    }
}
