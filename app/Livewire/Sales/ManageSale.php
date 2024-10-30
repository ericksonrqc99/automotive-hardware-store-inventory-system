<?php

namespace App\Livewire\Sales;

use App\Models\Product;
use App\Models\Sale;
use App\Models\ShoppingCart;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;

use function PHPSTORM_META\map;

class ManageSale extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public bool $successSale = false;


    //Render this component
    public function render()
    {
        return view('livewire.sales.manage-sale');
    }

    //Actions 
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
            })->icon('heroicon-o-banknotes')->color('primary');
    }


    public function viewInvoiceAction(): Action
    {
        return Action::make('viewInvoice')->label(__('Ver ticket'))
            ->modalContent(function () {
                if (empty(session('pos-current_sale')) || empty(session('pos-current_sale_details'))) {
                    return Notification::make()
                        ->title(__('Parece que la venta o los detalles de la venta se han perdido'))
                        ->send();
                }
                // make to name archive
                $filename = 'ticket-sale-' . session('pos-current_sale')->id . '.pdf';
                $path = 'pdfs/tickets/' . $filename;

                // Verificar si el PDF ya existe
                if (!Storage::exists($path)) {
                    try {
                        $items  = array_map(function ($product) {
                            return (object)[
                                'product' => (object)[
                                    'name' => $product['name'],
                                    'sku' => $product['sku']
                                ],
                                'quantity' => $product['quantity'],
                                'price' => $product['price'],
                                'subtotal' => $product['total_price']
                            ];
                        }, session('pos-current_sale_details')->toArray());
                        $saleData = (object)[
                            'id' => session('pos-current_sale')->id,
                            'created_at' => session('pos-current_sale')->created_at,
                            'customer' => (object)[
                                'name' => User::find(session('pos-current_sale')->customer_id)->name,
                            ],
                            'items' => $items,
                            'subtotal' => session('pos-current_sale')->sub_total_price,
                            'tax' => session('pos-current_sale')->tax_amount,
                            'total' => session('pos-current_sale')->total_price,
                            'payment_method' => 'Efectivo',
                            'status' => __('completado')
                        ];
                        $pdf = PDF::loadView('ticket.ticket-layout', [
                            'sale' => $saleData
                        ])->setPaper('a4');
                    } catch (\Throwable $e) {
                        // Esto te mostrará el error específico en el log
                        dd($e);
                        return "Error: " . $e->getMessage();
                    }

                    Storage::put($path, $pdf->output());
                }

                // Convertir el PDF a base64 para mostrarlo en el iframe

                $base64 = base64_encode(Storage::get($path));

                // Retornar vista con el PDF embebido

                return view('filament.components.pdf-viewer', [
                    'pdfData' => $base64
                ]);
            })->icon('heroicon-o-eye')->registerModalActions([
                Action::make('report')
                    ->requiresConfirmation()
            ])
            ->modalSubmitAction(false)
            ->color('success')
        ;
    }

    //End Actions
    private function generateSale($data): ?Sale
    {
        try {
            // get method_payment_id from session
            $method_payment = session('pos-method_payment_id');
            $data['method_payment_id'] = $method_payment;
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
            session(['pos-current_sale' => $sale]);
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
            // get all sale details
            $shoppingCart = ShoppingCart::all();
            session(['pos-current_sale_details' => $shoppingCart]);
            // reduce stock
            $shoppingCart->each(function ($productShoppingCart) {
                $product = Product::find($productShoppingCart->id);
                $product->stock = $product->stock - $productShoppingCart->quantity;
                $product->save();
            });

            // attach productsShoppingCart to sale
            $productsToSync = $shoppingCart->map(function ($product) {
                return [
                    'product_id' => $product->id,
                    'quantity' => $product->quantity,
                ];
            })->toArray();
            $sale->products()->sync($productsToSync);
            $this->dispatch('refresh-table-list-products-pos');

            // successSale true for show ticket button
            $this->successSale = true;
        } catch (\Throwable $th) {
            Notification::make()
                ->title(__($th->getMessage()))
                ->send();
            return null;
        }
    }

    #[On('pos-reset_sale')]
    public function resetSale()
    {
        $this->successSale = false;
    }
}
