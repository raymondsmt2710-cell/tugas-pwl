<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
*/

// Sync pending Midtrans transactions every 15 minutes
Schedule::command('midtrans:sync --minutes=30')->everyFifteenMinutes();

// Expire very old pending donations (24h+) every hour
Schedule::command('midtrans:sync --minutes=1440')->hourly();
