<?php

namespace App\Filament\Resources\SaleResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;


class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('quantity')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Producto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Cantidad'),
            ])
            ->filters([
                //
            ]);
    }
}
