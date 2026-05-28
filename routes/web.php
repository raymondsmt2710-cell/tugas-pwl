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
| Public & Homepage Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Email Verified Success Page
Route::get('/email/verified', function () {
    return view('auth.email-verified');
})->middleware('auth')->name('email.verified');

Route::view('/about', 'about', ['title' => 'About - Autopahala'])->name('about');
Route::view('/contact', 'contact', ['title' => 'Contact - Autopahala'])->name('contact');
Route::view('/faq', 'faq', ['title' => 'FAQ - Autopahala'])->name('faq');


/*
|--------------------------------------------------------------------------
| OAuth / Socialite Routes
|--------------------------------------------------------------------------
*/
Route::get('/auth/{provider}', [SocialiteController::class, 'redirectToProvider'])
    ->where('provider', 'google')
    ->name('social.login');

Route::get('/auth/{provider}/callback', [SocialiteController::class, 'handleProviderCallback'])
    ->where('provider', 'google');


/*
|--------------------------------------------------------------------------
| Midtrans Webhook (no CSRF, no auth)
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle'])
    ->name('midtrans.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard - redirect to home
    Route::get('/dashboard', function () {
        return redirect('/');
    })->name('dashboard');

    // Campaign Management
    Route::get('/my-campaigns', [CampaignController::class, 'myCampaigns'])->name('campaigns.my');
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaign.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaign.store');
    Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaign.edit');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaign.update');
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaign.destroy');
    Route::post('/campaigns/{campaign}/submit', [CampaignController::class, 'submit'])->name('campaign.submit');

    // Donation History (authenticated)
    Route::get('/my-donations', [DonationController::class, 'history'])->name('donations.history');

    // Withdrawal Management
    Route::get('/withdrawals/create', [\App\Http\Controllers\WithdrawalController::class, 'create'])->name('withdrawals.create');
    Route::post('/withdrawals', [\App\Http\Controllers\WithdrawalController::class, 'store'])->name('withdrawals.store');
    Route::get('/withdrawals/history', [\App\Http\Controllers\WithdrawalController::class, 'history'])->name('withdrawals.history');
    Route::post('/withdrawals/{withdrawal}/cancel', [\App\Http\Controllers\WithdrawalController::class, 'cancel'])->name('withdrawals.cancel');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');

    // Follow
    Route::post('/follow/{user}', [\App\Http\Controllers\FollowController::class, 'toggle'])->name('follow.toggle');
});


/*
|--------------------------------------------------------------------------
| Donation Routes (public - guests can donate)
|--------------------------------------------------------------------------
*/
Route::get('/campaigns/{slug}/donate', [DonationController::class, 'create'])->name('donation.create');
Route::post('/campaigns/{slug}/donate', [DonationController::class, 'store'])->name('donation.store');
Route::get('/campaigns/{slug}/donors', [DonationController::class, 'donors'])->name('donation.donors');
Route::get('/donations/{orderId}/finish', [DonationController::class, 'finish'])->name('donation.finish');
Route::get('/donations/{orderId}/track', [DonationController::class, 'track'])->name('donation.track');


/*
|--------------------------------------------------------------------------
| Public Campaign Routes
|--------------------------------------------------------------------------
*/
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{slug}', [CampaignController::class, 'show'])->name('campaigns.show');


/*
|--------------------------------------------------------------------------
| Public Profile Route
|--------------------------------------------------------------------------
*/
Route::get('/@{username}', [ProfileController::class, 'show'])->name('profile.show.public');
