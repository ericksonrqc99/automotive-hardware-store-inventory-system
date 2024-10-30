<?php

namespace App\Filament\Resources\SaleResource\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class SaleTimesOverview extends ChartWidget
{
    use HasWidgetShield;
    protected static ?string $heading = 'Ventas a lo Largo del Tiempo';

    public ?string $filter = 'monthly'; // Valor por defecto

    protected function getFilters(): ?array
    {
        return [
            'daily' => 'Ventas Diarias',
            'monthly' => 'Ventas Mensuales',
            'quarterly' => 'Ventas Trimestrales',
            'yearly' => 'Ventas Anuales',
        ];
    }

    protected function getData(): array
    {
        $datasets = [];
        $labels = [];

        switch ($this->filter) {
            case 'all':
                $dailySales = $this->getDailySales();
                $monthlySales = $this->getMonthlySales();
                $quarterlySales = $this->getQuarterlySales();
                $yearlySales = $this->getYearlySales();

                $datasets = [
                    [
                        'label' => 'Ventas Diarias',
                        'data' => $dailySales['totals'],
                        'borderColor' => 'rgba(75, 192, 192, 1)',
                        'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                        'fill' => true,
                    ],
                    [
                        'label' => 'Ventas Mensuales',
                        'data' => $monthlySales['totals'],
                        'borderColor' => 'rgba(255, 99, 132, 1)',
                        'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                        'fill' => true,
                    ],
                    [
                        'label' => 'Ventas Trimestrales',
                        'data' => $quarterlySales['totals'],
                        'borderColor' => 'rgba(54, 162, 235, 1)',
                        'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                        'fill' => true,
                    ],
                    [
                        'label' => 'Ventas Anuales',
                        'data' => $yearlySales['totals'],
                        'borderColor' => 'rgba(255, 206, 86, 1)',
                        'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
                        'fill' => true,
                    ],
                ];
                $labels = $dailySales['periods'];
                break;

            case 'daily':
                $dailySales = $this->getDailySales();
                $datasets = [[
                    'label' => 'Ventas Diarias',
                    'data' => $dailySales['totals'],
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'fill' => true,
                ]];
                $labels = $dailySales['periods'];
                break;

            case 'monthly':
                $monthlySales = $this->getMonthlySales();
                $datasets = [[
                    'label' => 'Ventas Mensuales',
                    'data' => $monthlySales['totals'],
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'fill' => true,
                ]];
                $labels = $monthlySales['periods'];
                break;

            case 'quarterly':
                $quarterlySales = $this->getQuarterlySales();
                $datasets = [[
                    'label' => 'Ventas Trimestrales',
                    'data' => $quarterlySales['totals'],
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'fill' => true,
                ]];
                $labels = $quarterlySales['periods'];
                break;

            case 'yearly':
                $yearlySales = $this->getYearlySales();
                $datasets = [[
                    'label' => 'Ventas Anuales',
                    'data' => $yearlySales['totals'],
                    'borderColor' => 'rgba(255, 206, 86, 1)',
                    'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
                    'fill' => true,
                ]];
                $labels = $yearlySales['periods'];
                break;
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    private function getDailySales(): array
    {
        $sales = DB::table('sales')
            ->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total_sales')
            ])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $periods = [];
        $totals = [];

        foreach ($sales as $sale) {
            $periods[] = Carbon::parse($sale->date)->format('Y-m-d');
            $totals[] = (float) $sale->total_sales;
        }

        return [
            'periods' => $periods,
            'totals' => $totals,
        ];
    }

    private function getMonthlySales(): array
    {
        $sales = DB::table('sales')
            ->select([
                DB::raw('DATE_FORMAT(created_at, "%Y-%m-01") as date'),
                DB::raw('SUM(total_price) as total_sales')
            ])
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-01")'))
            ->orderBy('date')
            ->get();

        $periods = [];
        $totals = [];

        foreach ($sales as $sale) {
            $periods[] = Carbon::parse($sale->date)->format('Y-m');
            $totals[] = (float) $sale->total_sales;
        }

        return [
            'periods' => $periods,
            'totals' => $totals,
        ];
    }

    private function getQuarterlySales(): array
    {
        $sales = DB::table('sales')
            ->select([
                DB::raw('YEAR(created_at) as year'),
                DB::raw('QUARTER(created_at) as quarter'),
                DB::raw('SUM(total_price) as total_sales')
            ])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('QUARTER(created_at)'))
            ->orderBy('year')
            ->orderBy('quarter')
            ->get();

        $periods = [];
        $totals = [];

        foreach ($sales as $sale) {
            $periods[] = "Q{$sale->quarter} {$sale->year}";
            $totals[] = (float) $sale->total_sales;
        }

        return [
            'periods' => $periods,
            'totals' => $totals,
        ];
    }

    private function getYearlySales(): array
    {
        $sales = DB::table('sales')
            ->select([
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_price) as total_sales')
            ])
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year')
            ->get();

        $periods = [];
        $totals = [];

        foreach ($sales as $sale) {
            $periods[] = (string) $sale->year;
            $totals[] = (float) $sale->total_sales;
        }

        return [
            'periods' => $periods,
            'totals' => $totals,
        ];
    }
    protected function getType(): string
    {
        return 'line';
    }
}
