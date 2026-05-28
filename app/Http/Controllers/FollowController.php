<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    /**
     * Toggle follow/unfollow a user.
     */
    public function toggle(User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->id_user === $user->id_user) {
            return back();
        }

        if ($currentUser->isFollowing($user)) {
            $currentUser->unfollow($user);
            $message = 'Berhenti mengikuti ' . $user->full_name;
        } else {
            $currentUser->follow($user);
            $message = 'Anda sekarang mengikuti ' . $user->full_name;
        }

        return back()->with('success', $message);
    }
}
