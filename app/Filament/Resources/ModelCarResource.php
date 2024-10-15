<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModelCarResource\Pages;
use App\Filament\Resources\ModelCarResource\RelationManagers;
use App\Models\Brand;
use App\Models\ModelCar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModelCarResource extends Resource
{
    protected static ?string $model = ModelCar::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Modelo de Automovil';

    protected static ?string $pluralLabel = 'Modelos de Automoviles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Modelo'))
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('year')
                    ->label(__('Año'))
                    ->required(),
                Forms\Components\Select::make('brand_id')
                    ->label(__('Marca'))
                    ->relationship('brand', 'name')
                    ->required()
                    ->searchable()
                    ->options(Brand::all()->pluck('name', 'id')),
                Forms\Components\Select::make('status_id')
                    ->default(1)
                    ->relationship('status', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Modelo'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('year')
                    ->label(__('Año'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label(__('Marca'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status.name')
                    ->label(__('Estatus'))
                    ->color(fn(string $state): string => match ($state) {
                        'activo' => 'success',
                        'inactivo' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'activo' => 'heroicon-o-check-circle',
                        'inactivo' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Creado'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Actualizado'))
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModelCars::route('/'),
            'create' => Pages\CreateModelCar::route('/create'),
            'edit' => Pages\EditModelCar::route('/{record}/edit'),
        ];
    }
}
