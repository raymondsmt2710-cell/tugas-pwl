<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q', '');

        $campaigns = collect();
        $users = collect();

        if (strlen($query) >= 2) {
            $campaigns = Campaign::where('status', 'approved')
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('short_description', 'like', "%{$query}%");
                })
                ->with(['category', 'user'])
                ->take(12)
                ->get();

            $users = User::where('account_status', 'active')
                ->where(function ($q) use ($query) {
                    $q->where('full_name', 'like', "%{$query}%")
                      ->orWhere('username', 'like', "%{$query}%");
                })
                ->take(8)
                ->get();
        }

        return view('search', compact('query', 'campaigns', 'users'));
    }
}
