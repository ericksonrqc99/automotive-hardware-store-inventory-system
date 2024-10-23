<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Filament\Resources\SaleResource\RelationManagers\ProductsRelationManager;
use App\Models\Sale;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public static function getPluralLabel(): ?string
    {
        return __('Ventas');
    }

    public static function getLabel(): ?string
    {
        return __('Venta');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->label('Cliente')
                    ->relationship(
                        name: 'customer',
                        titleAttribute: 'ndocument',
                    )
                    ->getOptionLabelFromRecordUsing(fn(User $record) => "{$record->name}")
                    ->searchable(['name', 'ndocument'])
                    ->required()->disabled(),
                // Forms\Components\TextInput::make('quantity')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\TextInput::make('sub_total_price')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\TextInput::make('discount')
                //     ->required()
                //     ->numeric()
                //     ->default(0.00),
                // Forms\Components\TextInput::make('tax_amount')
                //     ->required()
                //     ->numeric()
                //     ->default(0.00),
                // Forms\Components\TextInput::make('total_price')
                //     ->required()
                //     ->numeric()
                //     ->default(0.00),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Usuario'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label(__('Cliente'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('Cantidad'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sub_total_price')
                    ->label(__('Subtotal'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount')
                    ->label(__('Descuento'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax_amount')
                    ->label(__('Impuesto'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label(__('Total'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Creación'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Actualización'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [

            'index' => Pages\ListSales::route('/'),
            // 'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
