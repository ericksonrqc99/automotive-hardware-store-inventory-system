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
use Filament\Tables\Enums\ActionsPosition;
use Livewire\Attributes\On;

class ListProducts extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;


    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->limit(25)
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Precio')
                    ->money($currency  = 'S/.'),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Marca')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Imagen'),
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
            ])->paginated([3]);
    }

    public function addToCart(Product $product)
    {
        $this->dispatch('add-product-to-shoppingcart', $product);
    }


    public function render(): View
    {
        return view('livewire.products.list-products');
    }

    #[On('refresh-table-list-products-pos')]
    public function refreshTable()
    {
        $this->resetTable();
    }
}
