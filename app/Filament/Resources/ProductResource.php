<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers\CharacteristicsRelationManager;
use App\Models\Brand;
use App\Models\Category;
use App\Models\MeasurementUnit;
use App\Models\MethodPayment;
use App\Models\ModelCar;
use App\Models\Product;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Manejo de productos';


    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getPluralLabel(): ?string
    {
        return __('Productos');
    }

    public static function getLabel(): ?string
    {
        return __('Producto');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('code')
                    ->label('Código interno')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('description')
                    ->label('Descripción')
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->label('Precio de venta')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('cost')
                    ->label('Costo de compra')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('minimum_stock')
                    ->label('Stock mínimo')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('stock')
                    ->label('Stock Actual')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('alert_stock_id')
                    ->label(__('Alerta de stock'))
                    ->default(1)
                    ->relationship('alertStock', 'name')
                    ->required(),
                Forms\Components\Select::make('status_id')
                    ->label('Estatus')
                    ->default(1)
                    ->relationship('status', 'name')
                    ->required(),
                Forms\Components\Select::make('brand_id')
                    ->label('Marca')
                    ->relationship('brand', 'name')
                    ->searchable()
                    ->options(
                        Brand::all()->pluck('name', 'id')
                    )
                    ->required(),
                Forms\Components\Select::make('supplier_id')
                    ->label('Proveedor')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->options(
                        Supplier::all()->pluck('name', 'id')
                    )
                    ->required(),
                Forms\Components\Select::make('measurement_unit_id')
                    ->label('Unidad de medida')
                    ->relationship('measurementUnit', 'name')
                    ->searchable()
                    ->options(MeasurementUnit::all()->pluck('name', 'id'))
                    ->required(),
                Forms\Components\Select::make('categories')
                    ->label('Categorias')
                    ->relationship('categories', 'name')
                    ->options(
                        Category::all()->pluck('name', 'id')
                    )
                    ->multiple()
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('modelCars')
                    ->label('Modelos de vehículos')
                    ->relationship('modelCars', 'name')
                    ->options(
                        ModelCar::all()->pluck('name', 'id')
                    )
                    ->multiple()
                    ->searchable()
                    ->required(),
                Forms\Components\FileUpload::make('image_url')
                    ->image()->imageEditor()->imageEditorMode(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Código interno')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('price')
                    ->label('Precio de venta')
                    ->money($curency = 'S/.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->label('Costo de compra')
                    ->money($curency = 'S/.')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image_url')->label('Imagen'),
                Tables\Columns\TextColumn::make('minimum_stock')
                    ->label('Stock mínimo')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock Actual')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alertStock.name')
                    ->label('Alerta de stock')
                    ->color(fn(string $state): string => match ($state) {
                        'activo' => 'success',
                        'inactivo' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'activo' => 'heroicon-o-check-circle',
                        'inactivo' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    }),
                Tables\Columns\TextColumn::make('status.name')
                    ->color(fn(string $state): string => match ($state) {
                        'activo' => 'success',
                        'inactivo' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'activo' => 'heroicon-o-check-circle',
                        'inactivo' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    }),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Marca')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Proveedor')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('measurementUnit.name')
                    ->label('Unidad de medida')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

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
            CharacteristicsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'view' => Pages\ViewProduct::route('/{record}'),
        ];
    }
}
