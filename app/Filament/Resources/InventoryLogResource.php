<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryLogResource\Pages;
use App\Filament\Resources\InventoryLogResource\RelationManagers;
use App\Models\InventoryLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Stmt\Label;

class InventoryLogResource extends Resource
{
    protected static ?string $model = InventoryLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): ?string
    {
        return 'Logs';
    }

    public static function getLabel(): string
    {
        return 'Log de inventario';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Logs de inventario';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('action')
                    ->label(__('Acción'))
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->label(__('Cantidad'))
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('user_id')
                    ->label(__('Usuario'))
                    ->searchable()
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('product_id')
                    ->label(__('Producto'))
                    ->relationship('product', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('concept_id')
                    ->label(__('Concepto'))
                    ->relationship('concept', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\RichEditor::make('description')
                    ->label(__('Descripción'))
                    ->required()
                    ->maxLength(255)->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('action')->label(__('Acción')),

                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('Cantidad'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Usuario'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product_id')
                    ->label(__('id del producto'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('Producto'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('concept.name')
                    ->label(__('Concepto'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('Descripción'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Fecha de creación'))
                    ->dateTime($format = 'd-m-Y H:i:s', $timezone = 'America/Lima')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');;
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
            'index' => Pages\ListInventoryLogs::route('/'),
            'create' => Pages\CreateInventoryLog::route('/create'),
            'view' => Pages\ViewInventoryLog::route('/{record}'),
            'edit' => Pages\EditInventoryLog::route('/{record}/edit'),
        ];
    }
}
