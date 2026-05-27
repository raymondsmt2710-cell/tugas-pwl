<?php

namespace App\Console\Commands;

use App\Services\DonationService;
use Illuminate\Console\Command;

class SyncMidtransTransactions extends Command
{
    protected $signature = 'midtrans:sync {--minutes=30 : Sync donations older than X minutes}';

    protected $description = 'Synchronize pending donation statuses with Midtrans API';

    public function handle(DonationService $donationService): int
    {
        $minutes = (int) $this->option('minutes');

        $this->info("Syncing pending donations older than {$minutes} minutes...");

        $results = $donationService->syncStalePendingDonations($minutes);

        $this->info("Sync complete:");
        $this->line("  Total synced: {$results['synced']}");
        $this->line("  Paid: {$results['paid']}");
        $this->line("  Expired: {$results['expired']}");
        $this->line("  Failed: {$results['failed']}");

        return self::SUCCESS;
    }
}
