<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Public & Homepage Routes (from branch: homepage)
|--------------------------------------------------------------------------
*/
// Menggunakan HomeController dari branch homepage untuk halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/about', 'about', [
    'title' => 'About - Autopahala',
])->name('about');

Route::view('/contact', 'contact', [
    'title' => 'Contact - Autopahala',
])->name('contact');

Route::view('/faq', 'faq', [
    'title' => 'FAQ - Autopahala',
])->name('faq');


/*
|--------------------------------------------------------------------------
| OAuth / Socialite Routes (from branch: main)
|--------------------------------------------------------------------------
*/
Route::get('/auth/{provider}', [SocialiteController::class, 'redirectToProvider'])->name('social.login');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'handleProviderCallback']);


/*
|--------------------------------------------------------------------------
| Authenticated Dashboard Routes (from branch: main)
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        // Admin langsung diarahkan ke panel Filament
        if (auth()->user()->role === 'admin') {
            return redirect('/admin');
        }

        return view('dashboard');
    })->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| Public Profile Route (from branch: main)
|--------------------------------------------------------------------------
*/
Route::get('/@{username}', [ProfileController::class, 'show'])->name('profile.show.public');