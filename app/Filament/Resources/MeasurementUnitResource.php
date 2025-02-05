<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MeasurementUnitResource\Pages;
use App\Filament\Resources\MeasurementUnitResource\RelationManagers;
use App\Models\MeasurementUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class MeasurementUnitResource extends Resource
{
    protected static ?string $model = MeasurementUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manejo de productos';


    public static function getPluralLabel(): ?string
    {
        return __('Unidades de medida');
    }

    public static function getLabel(): ?string
    {
        return __('Unidad de medida');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Nombre'))
                    ->required()
                    ->maxLength(45),
                Forms\Components\TextInput::make('abbreviation')
                    ->label(__('Abreviatura'))
                    ->required()
                    ->maxLength(10),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Nombre'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('abbreviation')
                    ->label(__('Abreviatura'))
                    ->searchable(),
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListMeasurementUnits::route('/'),
            'create' => Pages\CreateMeasurementUnit::route('/create'),
            'edit' => Pages\EditMeasurementUnit::route('/{record}/edit'),
        ];
    }
}
