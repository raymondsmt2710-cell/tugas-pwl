<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Midtrans Webhook (no CSRF, no auth, no redirect)
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle'])
    ->name('midtrans.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

/*
|--------------------------------------------------------------------------
| Public Routes Protected from Admin Users
|--------------------------------------------------------------------------
*/
Route::middleware([\App\Http\Middleware\RedirectAdmin::class])->group(function () {
    // Public & Homepage Routes
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Email Verified Success Page
    Route::get('/email/verified', function () {
        return view('auth.email-verified');
    })->middleware('auth')->name('email.verified');

    Route::view('/about', 'about', ['title' => 'About - Autopahala'])->name('about');
    Route::view('/contact', 'contact', ['title' => 'Contact - Autopahala'])->name('contact');
    Route::view('/faq', 'faq', ['title' => 'FAQ - Autopahala'])->name('faq');

    // OAuth / Socialite Routes
    Route::get('/auth/{provider}', [SocialiteController::class, 'redirectToProvider'])
        ->where('provider', 'google')
        ->name('social.login');

    Route::get('/auth/{provider}/callback', [SocialiteController::class, 'handleProviderCallback'])
        ->where('provider', 'google');

    // Public Campaign Routes
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');
    Route::get('/leaderboard', [\App\Http\Controllers\LeaderboardController::class, 'index'])->name('leaderboard');
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/{slug}', [CampaignController::class, 'show'])->name('campaigns.show');
    Route::get('/campaigns/{slug}/donors', [DonationController::class, 'donors'])->name('donation.donors');
    Route::get('/campaigns/{slug}/withdrawals', [\App\Http\Controllers\CampaignWithdrawalController::class, 'index'])->name('campaign.withdrawals');
    Route::get('/donations/{orderId}/track', [DonationController::class, 'track'])->name('donation.track');

    // Public Profile Route
    Route::get('/@{username}', [ProfileController::class, 'show'])->name('profile.show.public');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    \App\Http\Middleware\RedirectAdmin::class,
])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::view('/my-campaigns', 'dashboard');

    // Settings
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

    // Campaign Management
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaign.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaign.store');
    Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaign.edit');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaign.update');
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaign.destroy');
    Route::post('/campaigns/{campaign}/submit', [CampaignController::class, 'submit'])->name('campaign.submit');
    Route::post('/campaigns/{campaign}/close', [CampaignController::class, 'close'])->name('campaign.close');
    Route::post('/campaigns/{campaign}/cancel-close', [CampaignController::class, 'cancelClose'])->name('campaign.cancel-close');

    // Withdrawal Management
    Route::get('/withdrawals/create', [\App\Http\Controllers\WithdrawalController::class, 'create'])->name('withdrawals.create');
    Route::post('/withdrawals', [\App\Http\Controllers\WithdrawalController::class, 'store'])->name('withdrawals.store');
    Route::post('/withdrawals/{withdrawal}/cancel', [\App\Http\Controllers\WithdrawalController::class, 'cancel'])->name('withdrawals.cancel');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    // Notification Actions
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');

    // Follow
    Route::post('/follow/{user}', [\App\Http\Controllers\FollowController::class, 'toggle'])->name('follow.toggle');

    // Campaign Interactions (Like, Comment, Report)
    Route::post('/campaigns/{campaign}/like', [\App\Http\Controllers\CampaignInteractionController::class, 'toggleLike'])->name('campaigns.like');
    Route::post('/campaigns/{campaign}/comment', [\App\Http\Controllers\CampaignInteractionController::class, 'storeComment'])->name('campaigns.comment');
    Route::post('/campaigns/{campaign}/report', [\App\Http\Controllers\CampaignInteractionController::class, 'storeReport'])->name('campaigns.report');
});

/*
|--------------------------------------------------------------------------
| Donation Routes (Authenticated)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\RedirectAdmin::class])->group(function () {
    Route::get('/campaigns/{slug}/donate', [DonationController::class, 'create'])->name('donation.create');
    Route::post('/campaigns/{slug}/donate', [DonationController::class, 'store'])->name('donation.store');
    Route::get('/donations/{orderId}/finish', [DonationController::class, 'finish'])->name('donation.finish');
});