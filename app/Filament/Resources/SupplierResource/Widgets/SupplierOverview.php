<?php

namespace App\Filament\Resources\SupplierResource\Widgets;

use Filament\Actions\Action;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SupplierOverview extends ChartWidget
{
    protected static ?string $heading = 'Top 5 Productos Más Vendidos';


    public $limit = 5; // Mostramos 5 por defecto


    protected function getData(): array
    {
        // Obtener los 5 productos más vendidos
        $topProducts = DB::table('products')
            ->join('sales_details', 'products.id', '=', 'sales_details.product_id')
            ->join('sales', 'sales.id', '=', 'sales_details.sale_id')
            ->select('products.name', DB::raw('SUM(sales_details.quantity) as total_quantity'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        // Extraer nombres y cantidades
        $labels = $topProducts->pluck('name')->toArray();
        $quantities = $topProducts->pluck('total_quantity')->toArray();



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
                    'label' => 'Cantidad Vendida',
                    'data' => $quantities,
                    'backgroundColor' => $colors, // Asignar colores a las barras
                    'borderColor' => $borderColors, // As
                ],
            ],
            'labels' => $labels,
        ];
    }

    public function loadMore()
    {
        // Incrementamos el límite de usuarios a mostrar
        $this->limit += 5;
    }

    // Definir la acción para cargar más usuarios
    protected function getActions(): array
    {
        return [
            Action::make('verMas')
                ->label('Ver más usuarios') // Título del botón
                ->action('loadMore') // Método que ejecuta
                ->icon('heroicon-o-users'), // Ícono (opcional)
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
