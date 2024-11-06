<?php

namespace App\Livewire\Sales;

use App\Models\Concept;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\Sale;
use App\Models\ShoppingCart;
use App\Models\User;
use App\Services\InventoryLogService;
use App\Services\SaleService;
use App\Services\ShoppingCartService;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ManageSale extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public bool $successSale = false;
    private SaleService $saleService;
    private ShoppingCartService $shoppingCartService;
    private InventoryLogService $inventoryLogService;


    public function __construct()
    {
        $this->saleService = app(SaleService::class);
        $this->shoppingCartService = app(ShoppingCartService::class);
        $this->inventoryLogService = app(InventoryLogService::class);
    }

    public function render()
    {
        return view('livewire.sales.manage-sale');
    }


    // listeners
    #[On('pos-reset_sale')]
    public function changeSuccesSale(bool $successSale = false)
    {
        $this->successSale = $successSale;
    }


    //Actions 
    public function createAction(): Action
    {
        return Action::make('create')
            ->label(__('Realizar venta'))
            ->action(fn() => $this->actionCreateSale())->icon('heroicon-o-banknotes')->color('primary');
    }

    private function actionCreateSale()
    {
        try {
            // validate if exist products in shopping cart
            if ($this->shoppingCartService->isEmpty()) {
                throw new \Exception(__("No se encontraron productos en el carrito"));
            }
            // generate sale
            $sale = $this->generateSale();
            session(['pos-sale' => $sale]);
            // generate sale details
            $saleDetails = $this->generateSaleDetails($sale);
            // store log
            $logs = $saleDetails->map(function ($product) {
                $concept = 'venta';
                $concept_id = Concept::where('name', $concept)->first()->id;
                return [
                    'action' => 'out',
                    'quantity' => $product->quantity,
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'concept_id' => $concept_id,
                    'description' => 'Venta realizada por POS',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            });
            $this->inventoryLogService->storeInventoryLogs($logs->toArray());

            // delete all products of shopping cart
            $this->dispatch('pos-reset_shopping_cart');
            // reset customer
            $this->dispatch('pos-reset_form_customer');
            // send notification
            Notification::make()
                ->title(__('Venta realizada'))
                ->success()
                ->send();
            // clear form and delete customer_id of session
        } catch (\Throwable $th) {
            return Notification::make()
                ->title(__('Error al generar la venta'))
                ->body($th->getMessage())
                ->send();
        }
    }


    private function generateSale(): Sale
    {
        try {
            // data to create sale
            $data = [
                'user_id' => Auth::id(),
                'customer_id' => session('pos-customer_id'),
                'voucher_type_id' => session('pos-voucher_type_id'),
                'method_payment_id' => session('pos-method_payment_id'),
                'sub_total_price' => ShoppingCart::sum('total_price'),
                'tax_amount' => ShoppingCart::sum('total_price') * 0.18,
                'total_price' => ShoppingCart::sum('total_price') * 1.18,
                'quantity' => ShoppingCart::sum('quantity'),
                'discount' => 0,
            ];
            // store sale
            $sale = $this->saleService->storeSale($data);

            return $sale;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    private function generateSaleDetails(Sale $sale)
    {
        try {
            // get all products of shoppingcart
            $shoppingCart = $this->shoppingCartService->getAllProducts();
            session(['pos-sale_details' => $shoppingCart]);

            // reduce stock
            $this->shoppingCartService->reduceStock($shoppingCart);

            // attach productsShoppingCart to sale
            $productsToSync = $shoppingCart->map(function ($product) {
                return [
                    'product_id' => $product->id,
                    'quantity' => $product->quantity,
                ];
            })->toArray();

            $sale->products()->sync($productsToSync);

            $this->dispatch('refresh-table-list-products-pos');

            // notify alert stock to users
            $this->notifyAlertStock($sale);
            //storage voucher
            $this->storageVoucher();
            // successSale true for show ticket button
            $this->successSale = true;
            return $shoppingCart;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    public function viewInvoiceAction(): Action
    {
        return Action::make('viewInvoice')->label(__('Ver ticket'))
            ->modalContent($this->storageVoucher())->icon('heroicon-o-eye')->registerModalActions([
                Action::make('report')
                    ->requiresConfirmation()
            ])
            ->modalSubmitAction(false)
            ->color('success')
        ;
    }

    public function storageVoucher()
    {
        if (empty(session('pos-sale')) || empty(session('pos-sale_details'))) {
            return Notification::make()
                ->title(__('Parece que la venta o los detalles de la venta se han perdido'))
                ->send();
        }
        // make to name archive
        $filename = 'ticket-sale-' . session('pos-sale')->id . '.pdf';
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
                }, session('pos-sale_details')->toArray());
                $saleData = (object)[
                    'id' => session('pos-sale')->id,
                    'created_at' => session('pos-sale')->created_at,
                    'customer' => (object)[
                        'name' => User::find(session('pos-sale')->customer_id)->name,
                    ],
                    'items' => $items,
                    'subtotal' => session('pos-sale')->sub_total_price,
                    'tax' => session('pos-sale')->tax_amount,
                    'total' => session('pos-sale')->total_price,
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
    }

    public function notifyAlertStock(Sale $sale)
    {
        try {
            $products = $sale->products()->with('alertStock')->get();

            $products->each(function (Product $product) {
                // Obtén el modelo relacionado
                $alertStock = $product->alertStock;

                // Verifica si el modelo relacionado existe y tiene la propiedad `name`
                if (!empty($alertStock->name)) {
                    if ($alertStock->name === 'inactivo' || $alertStock->name === 'inactive') {
                        return;
                    }
                    if ($product->stock < $product->minimum_stock) {
                        // get all super_admin_users
                        $superAdminUsers = User::whereHas('roles', function ($query) {
                            $query->where('name', 'super_admin');
                        })->get();

                        $usersToNotify = collect([Auth::user()])->merge($superAdminUsers);

                        Notification::make()
                            ->title(__('El producto: ' . $product->name . ' está por debajo del stock mínimo'))
                            ->sendToDatabase($usersToNotify);
                    }
                }
            });
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
