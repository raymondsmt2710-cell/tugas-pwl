<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

// OAuth Routes
Route::get('/auth/{provider}', [SocialiteController::class, 'redirectToProvider'])->name('social.login');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'handleProviderCallback']);

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

// Public Profile Route
Route::get('/@{username}', [ProfileController::class, 'show'])->name('profile.show.public');
