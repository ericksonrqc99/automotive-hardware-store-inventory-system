<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Services\ProductService;
use App\Services\SaleService;
use App\Services\UserService;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected SaleService $saleService;
    protected ProductService $productService;
    protected UserService $userService;


    public function __construct()
    {
        $this->saleService = app(SaleService::class);
        $this->productService = app(ProductService::class);
        $this->userService = app(UserService::class);
    }



    protected function getStats(): array
    {
        return [
            Stat::make(__('Total de ventas'), $this->saleService->getCountSales())
                ->color('success')
                ->icon('heroicon-o-currency-dollar'),
            Stat::make(__('Total de productos'), $this->productService->getCountProducts())
                ->color('info')
                ->icon('heroicon-o-shopping-cart'),
            Stat::make(__('Total de usuarios'), $this->userService->getCountUsers())
                ->color('warning')
                ->icon('heroicon-o-user-group'),
        ];
    }
}
