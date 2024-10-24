<?php

namespace App\Providers\Filament;

use App\Filament\Resources\SupplierResource;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\BrandResource;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\SaleResource;
use App\Filament\Resources\UserResource\Widgets\UsersSalesChart;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;

class DashboardPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->profile($isSimple = false)
            ->id('dashboard')
            ->path('dashboard')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,

            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                SupplierResource\Widgets\SupplierOverview::class,
                SaleResource\Widgets\SaleTimesOverview::class,
                UserResource\Widgets\UsersSalesChart::class,
                CategoryResource\Widgets\CategorySalesOverview::class,
                BrandResource\Widgets\BrandSalesOverview::class,
                ProductResource\Widgets\ProductsStockOverview::class,
            ])->profile()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])->brandName('RyM')->plugin(
                FilamentFullCalendarPlugin::make()
                    ->schedulerLicenseKey('')
                    ->selectable()
                    ->editable()
                    ->timezone('America/Lima')
                    // ->locale()
                    // ->plugins()
                    // ->config()
            );;
    }
}
