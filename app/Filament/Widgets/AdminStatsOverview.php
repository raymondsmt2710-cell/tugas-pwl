<?php

namespace App\Filament\Widgets;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Withdrawal;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalDonations = Donation::where('payment_status', 'paid')->sum('donation_amount');
        $activeCampaigns = Campaign::whereIn('status', ['approved', 'goal_reached'])->count();
        $pendingCampaigns = Campaign::where('status', 'pending')->count();
        $pendingWithdrawals = Withdrawal::whereIn('status', ['pending', 'under_review'])->count();

        return [
            Stat::make('Total Donasi Terkumpul', 'Rp ' . number_format($totalDonations, 0, ',', '.'))
                ->description('Akumulasi donasi sukses tersalurkan')
                ->color('success'),

            Stat::make('Kampanye Aktif', $activeCampaigns)
                ->description('Kampanye sedang berjalan saat ini')
                ->color('info'),

            Stat::make('Butuh Verifikasi Kampanye', $pendingCampaigns)
                ->description('Kampanye menunggu persetujuan admin')
                ->color($pendingCampaigns > 0 ? 'warning' : 'success'),

            Stat::make('Penarikan Tertunda', $pendingWithdrawals)
                ->description('Pengajuan penarikan dana menunggu review')
                ->color($pendingWithdrawals > 0 ? 'danger' : 'success'),
        ];
    }
}
