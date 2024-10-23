<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use App\Models\ShoppingCart;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpParser\ErrorHandler\Throwing;
use Throwable;

class ManageSale extends Component implements HasForms, HasActions
{

    use InteractsWithActions;
    use InteractsWithForms;



    public function createAction(): Action
    {
        return Action::make('create')
            ->label(__('Realizar venta'))
            ->action(function () {
                $data = [
                    'user_id' => Auth::id(),
                    'customer_id' => session('pos-customer_id'),
                    'sub_total_price' => ShoppingCart::sum('total_price'),
                    'tax_amount' => ShoppingCart::sum('total_price') * 0.18,
                    'total_price' => ShoppingCart::sum('total_price') + (ShoppingCart::sum('total_price') * 0.18),
                    'quantity' => ShoppingCart::sum('quantity'),
                    'discount' => 0,
                ];
                $sale = $this->generateSale($data);
                if (!$sale) {
                    return;
                }
                $this->generateSaleDetails($sale);
                // clear shopping cart
                $this->dispatch('reset-shopping-cart');
                // send notification
                Notification::make()
                    ->title(__('Venta realizada'))
                    ->success()
                    ->send();
                // delete customer to session
                $this->dispatch('reset-pos-form-customer');
            });
    }

    private function generateSale($data): ?Sale
    {
        try {
            //get customer_id from session
            $customer_id = $data['customer_id'];
            // get all productsShoppingCart in shopping cart
            $productsShoppingCart = ShoppingCart::all();

            // validate customer_id and productsShoppingCart
            if (empty($customer_id)) {
                throw new \Exception(__('No se ha seleccionado un cliente'));
            }
            if ($productsShoppingCart->isEmpty()) {
                throw new \Exception(__('No hay productos en el carrito'));
            }
            //create sale
            $sale = Sale::create($data);

            return $sale;
        } catch (\Throwable $th) {
            Notification::make()
                ->title(__($th->getMessage()))
                ->send();
            return null;
        }
    }

    private function generateSaleDetails(Sale $sale)
    {
        try {
            // create sale details
            $shoppingCart = ShoppingCart::all();

            // attach productsShoppingCart to sale
            $productsToSync = $shoppingCart->map(function ($product) {
                return [
                    'product_id' => $product->id,
                    'quantity' => $product->quantity,
                ];
            })->toArray();
            $sale->products()->sync($productsToSync);
        } catch (\Throwable $th) {
            Notification::make()
                ->title(__($th->getMessage()))
                ->send();
        }
    }

    public function render()
    {
        return view('livewire.sales.manage-sale');
    }
}
