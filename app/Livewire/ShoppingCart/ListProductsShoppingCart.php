<?php

namespace App\Livewire\ShoppingCart;

use App\Models\Product;
use App\Models\ShoppingCart;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Illuminate\Database\Query\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Livewire\Attributes\On;

class ListProductsShoppingCart extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public array $listProductsShoppingCart = [];

    //state of form
    public ?array $data = [];

    protected static ?string $model = ShoppingCart::class;

    public $currency = 'S/.';


    public function mount()
    {
        ShoppingCart::truncate();
    }

    protected $listeners = [
        'refresh-table-list-products-pos' => '$refresh'
    ];

    #[On('add-product-to-shoppingcart')]
    public function addToCart(array $product)
    {
        $productFound = ShoppingCart::find($product['id']);
        if (empty($productFound)) {
            if ($product['stock'] <= 0) {
                Notification::make()
                    ->title('El producto no tiene stock')
                    ->send();
            } else {
                $this->createProduct($product);
            }
        } else {
            $this->addQuantity($productFound);
        }
    }

    private function addQuantity(ShoppingCart $cartProduct, $quantity = 1)
    {
        $product = Product::find($cartProduct->id);

        if (!$product) {
            Notification::make()
                ->title('Producto no encontrado')
                ->send();
            return;
        }

        if (!$this->validateStock($product, $cartProduct->quantity + $quantity)) {
            return;
        }
        $cartProduct['quantity'] = $cartProduct['quantity'] + $quantity;
        $cartProduct['total_price'] = $cartProduct['price'] * $cartProduct['quantity'];
        $cartProduct->save();
    }



    public function removeQuantity(ShoppingCart $cartProduct, $quantity = 1)
    {
        if ($cartProduct['quantity'] > 1) {
            $cartProduct['quantity'] = $cartProduct['quantity'] - $quantity;
            $cartProduct['total_price'] = $cartProduct['price'] * $cartProduct['quantity'];
            $cartProduct->save();
        } else {
            $cartProduct->delete();
        }
        $this->dispatch('$refresh');
    }

    private function createProduct($product)
    {
        $product['quantity'] = 1;
        $product['total_price'] = $product['price'];
        ShoppingCart::create($product);
        $this->dispatch('refresh-table-list-products-pos');
    }


    #[On('reset-shopping-cart')]
    public function resetShoppingCart()
    {
        ShoppingCart::truncate();
    }

    public function validateStock($product, $quantity)
    {
        if ($product['stock'] < $quantity) {
            Notification::make()
                ->title('La cantidad solicitada excede el stock disponible')
                ->send();
            return false;
        }
        return true;
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(1)->schema([
                Select::make('search')->placeholder('Ingrese el nombre del producto')
                    ->searchable()
                    ->options(fn() => Product::all()->pluck('name', 'id')->all())
                    ->live()
                    ->afterStateUpdated(
                        fn(Select $component) => $component
                            ->getContainer()
                            ->getComponent('quantity_key')
                            ->getChildComponentContainer()
                            ->fill()
                    )->native(false)->optionsLimit(10)->createOptionAction(
                        fn(Action $action) => $action->modalWidth('3xl'),
                    ),
                TextInput::make('quantity')->label('Cantidad')->key('quantity_key'),
            ])
        ])->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table->emptyStateHeading(__('No hay productos en el carrito'))->emptyStateIcon('heroicon-s-shopping-cart')
            ->columns([
                TextColumn::make('name')->label('Nombre'),
                TextColumn::make('quantity')->label(__('Cantidad'))->summarize(Sum::make()->label('Total')),
                TextColumn::make('measurementUnit.name')->label(__('Medida')),
                TextColumn::make('total_price')
                    ->money($currency = $this->currency)
                    ->label(__('Precio'))
                    ->summarize([
                        Sum::make()->label('SubTotal')
                            ->money($currency = $this->currency),
                        Summarizer::make('IGV')
                            ->using(fn(Builder $query): string => $query->sum('total_price') * 0.18)
                            ->label('IGV')
                            ->money($currency = $this->currency),
                        Summarizer::make('Total')
                            ->using(fn(Builder $query) => $query->sum('total_price') * 1.18)
                            ->label('Total')
                            ->money($currency = $this->currency),
                    ])
            ])->query(
                ShoppingCart::query()
            )
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('remove-product-to-cart')
                    ->label(__('Quitar'))
                    ->icon('heroicon-s-minus')
                    ->iconButton()->action(fn(ShoppingCart $product) => $this->removeQuantity($product))->color('danger'),
                Tables\Actions\Action::make('edit-quantity-product-to-cart')
                    ->label(__('Editar cantidad'))
                    ->icon('heroicon-s-pencil-square')
                    ->iconButton()
                    ->form([
                        TextInput::make('quantity')
                            ->label('Cantidad')
                            ->numeric()
                            ->required(),
                    ])
                    ->action(function (ShoppingCart $record, array $data) {
                        // Obtener la cantidad en stock del producto asociado
                        $product = Product::find($record->id);
                        if (!$product) {
                            Notification::make()
                                ->title('Producto no encontrado')
                                ->send();
                            return;
                        }

                        if (!$this->validateStock($product, $data['quantity'])) {
                            return;
                        };
                        // Si la cantidad es válida, actualiza el registro
                        if ($data['quantity'] == 0) {
                            $record->delete();
                            return;
                        }
                        $record->update(['quantity' => $data['quantity']]);
                        $record->update(['total_price' => $record->price * $data['quantity']]);
                        $this->dispatch('refresh-table-list-products-pos'); // Refresca la tabla para actualizar los resúmenes
                    })->modalSubmitActionLabel(__('Actualizar')),
            ])
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
