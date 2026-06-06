<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\View\PanelsRenderHook;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::hex('#20bdc4'),
                'gray' => Color::Zinc,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'danger' => Color::Rose,
            ])
            ->font('Plus Jakarta Sans')
            ->favicon(asset('images/favicon.jpeg'))
            ->brandLogo(new \Illuminate\Support\HtmlString('
                <div class="flex items-center gap-2" style="display: flex !important; align-items: center !important; gap: 8px !important; height: 100% !important;">
                    <img src="' . asset('images/logo-icon.jpeg') . '" alt="AutoPahala" style="height: 2rem !important; width: 2rem !important; object-fit: cover !important; border-radius: 0.5rem !important;" />
                    <span class="text-xl font-extrabold tracking-tight" style="font-family: \'Plus Jakarta Sans\', sans-serif; display: flex !important; align-items: center !important; gap: 4px !important; line-height: 1 !important;">
                        <span class="text-slate-900 dark:text-white">auto</span>
                        <span style="color: #20bdc4 !important;">pahala</span>
                    </span>
                </div>
            '))
            ->brandLogoHeight('2.5rem')
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): \Illuminate\Contracts\View\View => view('admin.custom-styles'),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                \App\Filament\Widgets\AdminStatsOverview::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}