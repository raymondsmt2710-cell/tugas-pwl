<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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