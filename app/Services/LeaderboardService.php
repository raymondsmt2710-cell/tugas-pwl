<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LeaderboardService
{
    private function getPeriodStart(string $period): ?Carbon
    {
        return match ($period) {
            'weekly' => now()->startOfWeek(),
            'monthly' => now()->startOfMonth(),
            'yearly' => now()->startOfYear(),
            default => null, // all-time
        };
    }

    private function cacheTtl(string $period): int
    {
        return match ($period) {
            'weekly' => 600,    // 10 minutes
            'monthly' => 1800,  // 30 minutes
            'yearly' => 3600,   // 1 hour
            default => 3600,    // 1 hour
        };
    }

    /**
     * Top Donors — ranked by total donation amount.
     */
    public function topDonors(string $period = 'all', int $limit = 10): array
    {
        $key = "leaderboard:donors:{$period}:{$limit}";

        return Cache::remember($key, $this->cacheTtl($period), function () use ($period, $limit) {
            $query = Donation::where('payment_status', 'paid')
                ->where('is_anonymous', false)
                ->whereNotNull('id_user');

            $start = $this->getPeriodStart($period);
            if ($start) {
                $query->where('paid_at', '>=', $start);
            }

            return $query->select('id_user', DB::raw('SUM(donation_amount) as total_amount'), DB::raw('COUNT(*) as donation_count'))
                ->groupBy('id_user')
                ->orderByDesc('total_amount')
                ->limit($limit)
                ->get()
                ->map(function ($row) {
                    $user = User::find($row->id_user);
                    if (!$user) return null;
                    return [
                        'user_name' => $user->full_name,
                        'username' => $user->username,
                        'avatar' => $user->profile_photo_url,
                        'total_amount' => (float) $row->total_amount,
                        'donation_count' => (int) $row->donation_count,
                    ];
                })
                ->filter()
                ->values()
                ->toArray();
        });
    }

    /**
     * Top Campaigns — ranked by collected amount.
     */
    public function topCampaigns(string $period = 'all', int $limit = 10): array
    {
        $key = "leaderboard:campaigns:{$period}:{$limit}";

        return Cache::remember($key, $this->cacheTtl($period), function () use ($period, $limit) {
            $query = Campaign::where('status', 'approved');

            if ($period !== 'all') {
                $start = $this->getPeriodStart($period);
                if ($start) {
                    $query->where('created_at', '>=', $start);
                }
            }

            return $query->with(['user', 'category'])
                ->orderByDesc('collected_amount')
                ->limit($limit)
                ->get()
                ->map(fn ($c) => [
                    'title' => $c->title,
                    'slug' => $c->slug,
                    'banner_image' => $c->banner_image,
                    'collected_amount' => (float) $c->collected_amount,
                    'donor_count' => $c->donations()->where('payment_status', 'paid')->count(),
                    'progress' => $c->progress_percentage,
                    'creator_name' => $c->user->full_name ?? '',
                ])
                ->toArray();
        });
    }

    /**
     * Top Creators — ranked by total funds raised across all campaigns.
     */
    public function topCreators(string $period = 'all', int $limit = 10): array
    {
        $key = "leaderboard:creators:{$period}:{$limit}";

        return Cache::remember($key, $this->cacheTtl($period), function () use ($period, $limit) {
            $query = Campaign::where('status', 'approved');

            if ($period !== 'all') {
                $start = $this->getPeriodStart($period);
                if ($start) {
                    $query->where('created_at', '>=', $start);
                }
            }

            return $query->select('id_user', DB::raw('SUM(collected_amount) as total_raised'), DB::raw('COUNT(*) as campaign_count'))
                ->groupBy('id_user')
                ->orderByDesc('total_raised')
                ->limit($limit)
                ->get()
                ->map(function ($row) {
                    $user = User::find($row->id_user);
                    if (!$user) return null;
                    return [
                        'user_name' => $user->full_name,
                        'username' => $user->username,
                        'avatar' => $user->profile_photo_url,
                        'total_raised' => (float) $row->total_raised,
                        'campaign_count' => (int) $row->campaign_count,
                    ];
                })
                ->filter()
                ->values()
                ->toArray();
        });
    }
}
