<?php

namespace App\Filament\Exports;

use App\Models\Sale;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class SaleExporter extends Exporter
{
    protected static ?string $model = Sale::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('user_id'),
            ExportColumn::make('customer_id'),
            ExportColumn::make('quantity'),
            ExportColumn::make('sub_total_price'),
            ExportColumn::make('discount'),
            ExportColumn::make('tax_amount'),
            ExportColumn::make('total_price'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('method_payment_id'),
            ExportColumn::make('voucher_type_id'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your sale export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
