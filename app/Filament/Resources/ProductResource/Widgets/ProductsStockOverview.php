<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class ProductsStockOverview extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Inventario por Nivel de Stock';

    protected static ?string $maxHeight = '200px';


    protected function getData(): array
    {
        // Definir los niveles de stock
        $lowStockThreshold = 10;    // Stock bajo
        $mediumStockThreshold = 50;  // Stock medio

        // Obtener el conteo de productos por nivel de stock
        $inventoryLevels = DB::table('products')
            ->select(
                DB::raw("SUM(CASE WHEN stock < $lowStockThreshold THEN 1 ELSE 0 END) as low_stock"),
                DB::raw("SUM(CASE WHEN stock >= $lowStockThreshold AND stock < $mediumStockThreshold THEN 1 ELSE 0 END) as medium_stock"),
                DB::raw("SUM(CASE WHEN stock >= $mediumStockThreshold THEN 1 ELSE 0 END) as high_stock")
            )
            ->first();

        // Extraer los datos
        $levels = ['Bajo', 'Medio', 'Alto'];
        $quantities = [
            $inventoryLevels->low_stock,
            $inventoryLevels->medium_stock,
            $inventoryLevels->high_stock
        ];

        // Retornar los datos para la gráfica de barras
        return [
            'datasets' => [
                [
                    'label' => 'Inventario por Nivel de Stock',
                    'data' => $quantities,  // Cantidades de productos por nivel de stock
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $levels, // Nombres de los niveles de stock
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
