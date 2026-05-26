<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Public & Homepage Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

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
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard - accessible by ALL authenticated users
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Campaign Management (authenticated users)
    Route::get('/my-campaigns', [CampaignController::class, 'myCampaigns'])->name('campaigns.my');
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaign.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaign.store');
    Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaign.edit');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaign.update');
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaign.destroy');
    Route::post('/campaigns/{campaign}/submit', [CampaignController::class, 'submit'])->name('campaign.submit');
});


/*
|--------------------------------------------------------------------------
| Public Campaign Routes (placed after auth routes so /campaigns/create is matched first)
|--------------------------------------------------------------------------
*/
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{slug}', [CampaignController::class, 'show'])->name('campaigns.show');


/*
|--------------------------------------------------------------------------
| Public Profile Route
|--------------------------------------------------------------------------
*/
Route::get('/@{username}', [ProfileController::class, 'show'])->name('profile.show.public');
