<?php

namespace App\Livewire\Products;

use App\Models\Product;

use Filament\Tables\Actions\Action;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Enums\ActionsPosition;

class ListProducts extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;


    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('price')
                    ->money($currency = 'S/.'),
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('measurementUnit.name')
                    ->label(__('Unidad de medida'))
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('add-product-to-cart')
                    ->icon('heroicon-s-shopping-cart')
                    ->iconButton()->action(fn(Product $product) => $this->addToCart($product)),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ])->paginated([5]);
    }

    public function addToCart(Product $product)
    {
        $this->dispatch('add-product-to-shoppingcart', $product->toArray());
    }


    public function render(): View
    {
        return view('livewire.products.list-products');
    }
}
