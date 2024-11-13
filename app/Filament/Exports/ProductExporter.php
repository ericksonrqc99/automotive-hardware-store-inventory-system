<?php

namespace App\Filament\Exports;

use App\Models\Product;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ProductExporter extends Exporter
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')
                ->label(__('Nombre')),
            ExportColumn::make('sku')
                ->label('SKU'),
            ExportColumn::make('code')
                ->label('Código'),
            ExportColumn::make('description')
                ->label('Descripción'),
            ExportColumn::make('price')
                ->label('Precio'),
            ExportColumn::make('cost')
                ->label('Costo'),
            ExportColumn::make('minimum_stock')
                ->label('Stock mínimo'),
            ExportColumn::make('stock'),
            ExportColumn::make('alertStock.name')
                ->label('Stock de alerta'),
            ExportColumn::make('status.name')
                ->label('Estado'),
            ExportColumn::make('brand.name')
                ->label('Marca'),
            ExportColumn::make('supplier.name')
                ->label('Proveedor'),
            ExportColumn::make('measurementUnit.name')
                ->label('Unidad de medida'),
            ExportColumn::make('created_at')
                ->label('Creacion'),
            ExportColumn::make('updated_at')
                ->label('Actualizacion'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = __('La exportación de su producto ha finalizado y ') . number_format($export->successful_rows) . ' ' . str(__('fila'))->plural($export->successful_rows) . ' fueron exportada(s).';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str(__('fila'))->plural($failedRowsCount) . ' no se pudo exportar';
        }

        return $body;
    }
}
