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
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

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
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sub_total_price')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('tax_amount')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->default(0.00),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Usuario'))
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label(__('Cliente'))
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('Cantidad'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sub_total_price')
                    ->label(__('Subtotal'))
                    ->money($currency = 'S/.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount')
                    ->label(__('Descuento'))
                    ->money($currency = 'S/.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax_amount')
                    ->label(__('Impuesto'))
                    ->money($currency = 'S/.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label(__('Total'))
                    ->money($currency = 'S/.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Creación'))
                    ->since()
                    ->dateTimeTooltip($format = 'd-m-Y H:i:s', $timezone = 'America/Lima')
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
                Tables\Actions\ViewAction::make(),
                Action::make('add-product-to-cart')
                    ->icon('heroicon-s-document')
                    ->label('PDF')
                    ->color('danger')
                    ->modalContent(function (Sale $record) {
                        $filename = 'ticket-sale-' . $record->id . '.pdf';
                        $path = 'pdfs/tickets/' . $filename;
                        if (Storage::exists($path)) {
                            $base64 = base64_encode(Storage::get($path));
                            return view('filament.components.pdf-viewer', [
                                'pdfData' => $base64
                            ]);
                        }
                    })->modalSubmitAction(false),
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
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
            'view' => Pages\ViewSale::route('/{record}'),
        ];
    }
}
