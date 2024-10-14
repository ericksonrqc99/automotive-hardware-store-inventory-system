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

    protected static ?string $label = 'Modelos de Carros';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Nombre'))
                    ->required()
                    ->maxLength(100),
                Forms\Components\DatePicker::make('year')
                    ->label(__('Año'))
                    ->format('y')   
                    ->required(),
                Forms\Components\Select::make('brand_id')
                    ->label(__('Marca'))
                    ->relationship('brand', 'name')
                    ->required()
                    ->searchable()->options(Brand::all()->pluck('name', 'id')),

                Forms\Components\Select::make('status_id')
                    ->relationship('status', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('year'),
                Tables\Columns\TextColumn::make('brand.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
