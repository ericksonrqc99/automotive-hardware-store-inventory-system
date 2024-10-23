<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Manejo de productos';




    public static function getPluralLabel(): ?string
    {
        return __('Marcas');
    }

    public static function getLabel(): ?string
    {
        return __('Marca');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label(__('Nombre'))
                    ->required()
                    ->maxLength(100),
                Forms\Components\Select::make('status_id')->label(__('Departamento'))
                    ->relationship('status', 'name')
                    ->default(1)
                    ->required(),
                Forms\Components\Textarea::make('description')->label(__('Descripción'))
                    ->maxLength(255)->placeholder(__('Ingrese una descripción')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('Nombre'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status.name')->label(__('Departamento'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')->label(__('Descripción'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label(__('Creado'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->label(__('Actualizado'))
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
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
