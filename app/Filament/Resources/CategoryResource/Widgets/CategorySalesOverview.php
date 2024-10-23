<?php

namespace App\Filament\Resources\CategoryResource\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CategorySalesOverview extends ChartWidget
{
    protected static ?string $heading = 'Top 5 Categorías Más Vendidas';


    protected function getData(): array
    {
        // Consulta con la tabla pivote para obtener las ventas agrupadas por categoría
        $salesByCategory = DB::table('categories')
            ->join('categories_has_products', 'categories.id', '=', 'categories_has_products.category_id')
            ->join('products', 'categories_has_products.product_id', '=', 'products.id')
            ->join('sales_details', 'products.id', '=', 'sales_details.product_id')
            ->join('sales', 'sales.id', '=', 'sales_details.sale_id')
            ->select('categories.name as category', DB::raw('SUM(sales_details.quantity) as total_sales'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sales', 'desc')
            ->limit(5)
            ->get();

        // Extraer las categorías y las ventas totales
        $categories = $salesByCategory->pluck('category')->toArray();
        $sales = $salesByCategory->pluck('total_sales')->toArray();

        // Retornar los datos para la gráfica de pastel
        return [
            'datasets' => [
                [
                    'label' => 'Ventas por Categoría',
                    'data' => $sales,  // Cantidades de ventas por categoría
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
            'labels' => $categories, // Nombres de las categorías
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
