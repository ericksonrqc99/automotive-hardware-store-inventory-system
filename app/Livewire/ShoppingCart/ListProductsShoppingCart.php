<?php

namespace App\Livewire\ShoppingCart;

use App\Models\Product;
use App\Models\ShoppingCart;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Livewire\Attributes\On;

class ListProductsShoppingCart extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public array $listProductsShoppingCart = [];

    protected static ?string $model = ShoppingCart::class;

    public $currency = 'S/.';

    public function mount()
    {
        ShoppingCart::truncate();
    }


    #[On('add-product-to-shoppingcart')]
    public function addToCart($product)
    {
        $productFound = ShoppingCart::find($product['id']);
        if (empty($productFound)) {
            $this->createProduct($product);
        } else {
            $this->addQuantity($productFound);
        }
    }

    private function addQuantity(ShoppingCart $product, $quantity = 1)
    {
        $product['quantity'] = $product['quantity'] + $quantity;
        $product['total_price'] = $product['price'] * $product['quantity'];
        $product->save();
    }



    public function removeQuantity(ShoppingCart $product, $quantity = 1)
    {
        if ($product['quantity'] > 1) {
            $product['quantity'] = $product['quantity'] - $quantity;
            $product->save();
        } else {
            $product->delete();
        }
    }

    private function createProduct($product)
    {
        $product['quantity'] = 1;
        $product['total_price'] = $product['price'];
        ShoppingCart::create($product);
    }


    #[On('reset-shopping-cart')]
    public function resetShoppingCart()
    {
        ShoppingCart::truncate();
    }


    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nombre'),
                TextColumn::make('quantity')->label(__('Cantidad'))->summarize(Sum::make()->label('Total')),
                TextColumn::make('measurementUnit.name')->label(__('Medida')),
                TextColumn::make('total_price')
                    ->money($currency = $this->currency)
                    ->label(__('Precio'))
                    ->summarize(Sum::make()->label('Total')
                        ->money($currency = $this->currency)),
            ])->query(
                ShoppingCart::query()
            )
            ->filters([
                //
            ])
            ->actions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ])->paginated(false);
    }


    public function render()
    {
        return view('livewire.shopping-cart.list-products-shopping-cart');
    }
}
