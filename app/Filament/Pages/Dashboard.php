<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Models\Donation;
use App\Models\Campaign;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends BaseDashboard
{
    protected string $view = 'filament.pages.dashboard';

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return null;
    }

    public function getTitle(): string
    {
        return '';
    }

    public function getViewData(): array
    {
        // 1. KPI Stats
        $totalDonations = Donation::where('payment_status', 'paid')->sum('donation_amount');
        $activeCampaigns = Campaign::whereIn('status', ['approved', 'goal_reached'])->where('campaign_status', 'active')->count();
        $pendingVerification = Campaign::where('status', 'pending')->count();
        $pendingWithdrawals = Withdrawal::whereIn('status', ['pending', 'under_review'])->sum('amount');
        $pendingWithdrawalsCount = Withdrawal::whereIn('status', ['pending', 'under_review'])->count();

        // Calculate dynamic growth rate compared to last month
        $thisMonthAmount = Donation::where('payment_status', 'paid')
            ->where(DB::raw('COALESCE(paid_at, created_at)'), '>=', now()->startOfMonth())
            ->sum('donation_amount');

        $lastMonthAmount = Donation::where('payment_status', 'paid')
            ->whereBetween(DB::raw('COALESCE(paid_at, created_at)'), [
                now()->subMonth()->startOfMonth(),
                now()->subMonth()->endOfMonth()
            ])
            ->sum('donation_amount');

        if ($lastMonthAmount > 0) {
            $donationGrowth = (($thisMonthAmount - $lastMonthAmount) / $lastMonthAmount) * 100;
        } else {
            $donationGrowth = $thisMonthAmount > 0 ? 100 : 0;
        }

        // 2. Donation Trend Chart (Last 7 Days)
        $chartData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartData->put($date, [
                'label' => now()->subDays($i)->translatedFormat('d M'),
                'amount' => 0,
            ]);
        }

        $donationsLast7Days = Donation::where('payment_status', 'paid')
            ->where('paid_at', '>=', now()->subDays(6)->startOfDay())
            ->select(DB::raw('DATE(paid_at) as date'), DB::raw('SUM(donation_amount) as total'))
            ->groupBy('date')
            ->get();

        foreach ($donationsLast7Days as $donation) {
            if ($chartData->has($donation->date)) {
                $chartData->put($donation->date, [
                    'label' => Carbon::parse($donation->date)->translatedFormat('d M'),
                    'amount' => (float)$donation->total,
                ]);
            }
        }

        // Generate SVG points
        $amounts = $chartData->pluck('amount')->all();
        $maxAmount = max($amounts) > 0 ? max($amounts) : 1000000;
        
        $svgPoints = [];
        $svgAreaPoints = [];
        $width = 500;
        $height = 120;
        $padding = 10;
        
        $pointsCount = count($amounts);
        $xStep = ($width - $padding * 2) / ($pointsCount - 1);
        
        $chartPoints = [];
        $index = 0;
        foreach ($chartData as $date => $data) {
            $x = $padding + ($index * $xStep);
            // Invert Y axis for SVG (0 is top)
            $y = $height - $padding - (($data['amount'] / $maxAmount) * ($height - $padding * 2));
            $chartPoints[] = ['x' => $x, 'y' => $y, 'label' => $data['label'], 'amount' => $data['amount']];
            $index++;
        }
        
        // Build SVG paths
        $linePath = '';
        $areaPath = '';
        if (count($chartPoints) > 0) {
            $linePath = 'M ' . $chartPoints[0]['x'] . ' ' . $chartPoints[0]['y'];
            for ($i = 1; $i < count($chartPoints); $i++) {
                $linePath .= ' L ' . $chartPoints[$i]['x'] . ' ' . $chartPoints[$i]['y'];
            }
            
            // Area path starts at the line path and closes at the bottom-right and bottom-left
            $areaPath = $linePath;
            $areaPath .= ' L ' . $chartPoints[count($chartPoints)-1]['x'] . ' ' . ($height - $padding);
            $areaPath .= ' L ' . $chartPoints[0]['x'] . ' ' . ($height - $padding);
            $areaPath .= ' Z';
        }

        return [
            'totalDonations' => $totalDonations,
            'activeCampaigns' => $activeCampaigns,
            'pendingVerification' => $pendingVerification,
            'pendingWithdrawals' => $pendingWithdrawals,
            'pendingWithdrawalsCount' => $pendingWithdrawalsCount,
            'chartPoints' => $chartPoints,
            'linePath' => $linePath,
            'areaPath' => $areaPath,
            'maxAmount' => $maxAmount,
            'donationGrowth' => $donationGrowth,
        ];
    }
}
