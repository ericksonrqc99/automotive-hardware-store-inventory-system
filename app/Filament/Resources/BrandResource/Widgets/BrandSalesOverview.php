<?php

namespace App\Filament\Resources\BrandResource\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class BrandSalesOverview extends ChartWidget
{

    use HasWidgetShield;

    protected static ?string $heading = 'Top 5 Marcas con más ventas';

    protected function getData(): array
    {
        // Obtener las marcas de productos y la suma de ventas por cada una
        $salesByBrand = DB::table('brands')
            ->join('products', 'brands.id', '=', 'products.brand_id')
            ->join('sales_details', 'products.id', '=', 'sales_details.product_id')
            ->join('sales', 'sales.id', '=', 'sales_details.sale_id')
            ->select('brands.name as brand', DB::raw('SUM(sales_details.quantity) as total_sales'))
            ->groupBy('brands.id', 'brands.name')
            ->orderBy('total_sales', 'desc')
            ->get();

        // Extraer las marcas y las ventas totales
        $brands = $salesByBrand->pluck('brand')->toArray();
        $sales = $salesByBrand->pluck('total_sales')->toArray();

        // Retornar los datos para la gráfica de pastel
        return [
            'datasets' => [
                [
                    'label' => 'Ventas por Marca',
                    'data' => $sales,  // Cantidades de ventas por marca
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $brands, // Nombres de las marcas
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
