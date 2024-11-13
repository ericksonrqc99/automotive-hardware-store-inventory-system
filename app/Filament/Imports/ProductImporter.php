<?php

namespace App\Filament\Imports;

use App\Models\Product;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Nombre')
                ->requiredMapping()
                ->validationAttribute('name')
                ->exampleHeader(__('nombre'))
                ->guess(['nombre'])
                ->rules(['required', 'max:100']),

            ImportColumn::make('sku')
                ->label('SKU')
                ->requiredMapping()
                ->validationAttribute('sku')
                ->exampleHeader('sku')
                ->guess(['sku'])
                ->rules(['required', 'max:100']),

            ImportColumn::make('code')
                ->label('Código')
                ->requiredMapping()
                ->validationAttribute('code')
                ->exampleHeader(__('codigo'))
                ->guess(['codigo'])
                ->rules(['required', 'max:100']),

            ImportColumn::make('description')
                ->label('Descripción')
                ->requiredMapping()
                ->validationAttribute('description')
                ->exampleHeader(__('descripcion'))
                ->guess(['descripcion'])
                ->rules(['max:255']),

            ImportColumn::make('price')
                ->label('Precio')
                ->requiredMapping()
                ->numeric()
                ->validationAttribute('price')
                ->exampleHeader(__('precio'))
                ->guess(['precio'])
                ->rules(['required', 'numeric']),

            ImportColumn::make('cost')
                ->label('Costo')
                ->requiredMapping()
                ->numeric()
                ->validationAttribute('cost')
                ->exampleHeader(__('costo'))
                ->guess(['costo'])
                ->rules(['required', 'numeric']),

            ImportColumn::make('minimum_stock')
                ->label('Stock Mínimo')
                ->requiredMapping()
                ->validationAttribute('minimum_stock')
                ->exampleHeader('stock_minimo')
                ->guess(['stock_minimo'])
                ->integer()
                ->rules(['required', 'integer']),

            ImportColumn::make('stock')
                ->label('Stock')
                ->requiredMapping()
                ->validationAttribute('stock')
                ->exampleHeader('stock')
                ->guess(['stock'])
                ->integer()
                ->rules(['required', 'integer']),

            ImportColumn::make('alert_stock')
                ->label(__('Alerta de Stock'))
                ->relationship(
                    resolveUsing: 'name'
                )
                ->requiredMapping()
                ->validationAttribute('alert_stock_id')
                ->exampleHeader(__('alerta_stock'))
                ->guess(['alerta_stock'])
                ->rules(['required']),

            ImportColumn::make('status')
                ->label(__('Estado'))
                ->relationship(
                    resolveUsing: 'name'
                )->requiredMapping()
                ->validationAttribute('status_id')
                ->exampleHeader(__('estado'))
                ->guess(['estado'])
                ->rules(['required']),

            ImportColumn::make('brand')
                ->label(__('Marca'))
                ->relationship(
                    resolveUsing: 'name'
                )
                ->requiredMapping()
                ->validationAttribute('brand_id')
                ->exampleHeader(__('marca'))
                ->guess(['marca'])

                ->rules(['required']),

            ImportColumn::make('supplier')
                ->label(__('Proveedor'))
                ->relationship(
                    resolveUsing: 'name'
                )
                ->requiredMapping()
                ->validationAttribute('supplier_id')
                ->exampleHeader(__('proveedor'))
                ->guess(['proveedor'])

                ->rules(['required']),

            ImportColumn::make('measurement_unit')
                ->label(__('Unidad de Medida'))
                ->relationship(
                    resolveUsing: 'name'
                )
                ->requiredMapping()
                ->validationAttribute('measurement_unit_id')
                ->exampleHeader(__('unidad_de_medida'))
                ->guess(['unidad_de_medida'])
                ->rules(['required']),
        ];
    }

    public function resolveRecord(): ?Product
    {
        return Product::firstOrNew([
            // Update existing records, matching them by `$this->data['column_name']`
            'sku' => $this->data['sku'],
            'code' => $this->data['code']
        ]);

        // return new Product();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your product import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
