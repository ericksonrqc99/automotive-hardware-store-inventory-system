<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;



class CharacteristicsRelationManager extends RelationManager
{
    protected static string $relationship = 'characteristics';

    protected static ?string $label = 'Característica';

    protected static ?string $plurasLabel = 'Características';


    protected static ?string $title = 'Características';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('value')
                    ->label('Característica')
                    ->required()
                    ->maxLength(100),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Característica')
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Valor')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()->label(__('Agregar'))->form(fn(AttachAction $action): array => [
                    $action->getRecordSelect()->options(\App\Models\Characteristic::all()->pluck('name', 'id')),
                    Forms\Components\TextInput::make('value')->label(__('Valor'))->required(),
                ]),
            ])
            ->actions([
                DetachAction::make()->label('Eliminar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
