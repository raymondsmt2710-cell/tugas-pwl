<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \Filament\Auth\Http\Responses\Contracts\LogoutResponse::class,
            \App\Http\Responses\FilamentLogoutResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Custom mail from for all emails
        \Illuminate\Support\Facades\Mail::alwaysFrom('tubespwlkel999@gmail.com', 'AutoPahala');

        // Dynamically set Livewire asset URL to support subdirectory installations
        if (app()->environment('production')) {
            config(['livewire.asset_url' => null]);
        } elseif (!app()->runningInConsole()) {
            config(['livewire.asset_url' => request()->getBasePath() ?: null]);
        }

        // Share site settings with all views
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('site_settings')) {
                $siteSetting = \App\Models\SiteSetting::first();
                view()->share('siteSetting', $siteSetting);
            }
        } catch (\Exception $e) {
            // Ignore database connection failures during build/pre-migration phases
        }
    }
}
