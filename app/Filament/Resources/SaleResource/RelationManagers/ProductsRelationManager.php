<?php

namespace App\Filament\Resources\SaleResource\RelationManagers;

use App\Models\Product;
use App\Models\Sale;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
