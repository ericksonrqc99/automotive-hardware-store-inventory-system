<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\Supplier;
use Faker\Core\Color;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Manejo de proveedores';

    public static function getPluralLabel(): ?string
    {
        return __('Proveedores');
    }

    public static function getLabel(): ?string
    {
        return __('Proveedor');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Nombre'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact')
                    ->label(__('Contacto'))
                    ->required()
                    ->maxLength(100),
                Forms\Components\Select::make('country_id')
                    ->label(__('País'))
                    ->relationship('country', 'name')
                    ->afterStateUpdated(function (Set $set) {
                        $set('state_id', null);
                        $set('city_id', null);
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),
                Forms\Components\Select::make('state_id')
                    ->label(__('Departamento'))
                    ->preload()
                    ->searchable()
                    ->live()
                    ->options(fn(Get $get) => State::query()->where('country_id', $get('country_id'))->pluck('name', 'id'))
                    ->afterStateUpdated(function (Set $set) {
                        $set('city_id', null);
                    })
                    ->required(),
                Forms\Components\Select::make('city_id')
                    ->label(__('Ciudad'))
                    ->preload()
                    ->searchable()
                    ->options(fn(Get $get) => City::query()->where('state_id', $get('state_id'))->pluck('name', 'id'))
                    ->required(),
                Forms\Components\Select::make('status_id')
                    ->label(__('Estatus'))
                    ->default(1)
                    ->preload()
                    ->relationship('status', 'name')
                    ->required(),
                Forms\Components\TextInput::make('address')
                    ->label(__('Dirección'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label(__('Teléfono'))
                    ->tel()
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('email')
                    ->label(__('Correo electrónico'))
                    ->email()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Nombre'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact')
                    ->label(__('Contacto'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->label(__('País'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->label(__('Departamento'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->label(__('Ciudad'))
                    ->numeric()
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
                Tables\Columns\TextColumn::make('address')
                    ->label(__('Dirección'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('Teléfono'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('Correo electrónico'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Creado'))
                    ->dateTime($format = 'd/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Actualizado'))
                    ->dateTime($format = 'd/m/Y')
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
