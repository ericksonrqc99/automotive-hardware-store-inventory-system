<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class UsersSalesChart extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Top 5 mejores clientes';

    protected static ?string $maxHeight = '200px';


    protected function getData(): array
    {
        // Obtener los 5 clientes que más compran desde la tabla 'users'
        $topCustomers = DB::table('users')
            ->join('sales', 'users.id', '=', 'sales.customer_id')
            ->join('sales_details', 'sales.id', '=', 'sales_details.sale_id')
            ->select('users.name', DB::raw('SUM(sales_details.quantity) as total_purchases'))
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_purchases', 'desc')
            ->limit(10)
            ->get();

        // Extraer nombres de usuarios y cantidades compradas
        $labels = $topCustomers->pluck('name')->toArray();
        $quantities = $topCustomers->pluck('total_purchases')->toArray();


        // Definir colores personalizados para cada barra
        $colors = [
            'rgba(255, 99, 132, 0.2)', // Rojo
            'rgba(54, 162, 235, 0.2)', // Azul
            'rgba(255, 206, 86, 0.2)', // Amarillo
            'rgba(75, 192, 192, 0.2)', // Verde
            'rgba(153, 102, 255, 0.2)', // Morado
        ];

        // Bordes para cada barra (opcional)
        $borderColors = [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
        ];

        // Retornar los datos en formato de gráfico de barras
        return [
            'datasets' => [
                [
                    'label' => 'Cantidad Comprada',
                    'data' => $quantities,
                    'backgroundColor' => $colors, // Asignar colores a las barras
                    'borderColor' => $borderColors, // Asignar color de borde a las barras (opcional)
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
