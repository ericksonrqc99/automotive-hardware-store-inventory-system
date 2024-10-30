<?php

namespace App\Filament\Pages;

use App\Models\MethodPayment;
use App\Models\User;
use App\Models\VoucherType;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Select;

class POS extends Page implements HasForms
{

    use InteractsWithForms;
    use HasPageShield;


    public ?array $data = [];

    protected static ?string $model = \App\Models\User::class;

    public array $listProductsShoppingCart = [];

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static string $view = 'filament.pages.p-o-s';


    public ?int $method_payment_id;
    public ?int $voucher_type_id;

    public function mount()
    {
        session()->forget('pos-customer_id');
        // where like efectivo
        $this->method_payment_id = MethodPayment::where('name', 'LIKE', "%efectivo%")->first()->id;
        if (!empty($this->method_payment_id)) {
            session(['pos-method_payment_id' => $this->method_payment_id]);
        }
        $this->voucher_type_id = VoucherType::where('name', 'LIKE', "%boleta%")->first()->id;
        if (!empty($this->voucher_type_id)) {
            session(['pos-voucher_type_id' => $this->voucher_type_id]);
        }

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Información del cliente')->columns(2)->schema([
                TextInput::make('customer-ndocument')
                    ->label('Número de documento del cliente')
                    ->required()
                    ->live(debounce: 1000)
                    ->afterStateUpdated(
                        function ($state, $set) {
                            $this->updateCustomerName($state, $set);
                            $this->dispatch('pos-reset_sale');
                        }
                    )->suffixActions([
                        Action::Make("reset-form")->action(fn(Set $set) => $this->resetFormCustomer($set))->icon('heroicon-o-trash'),
                    ])->autofocus()->numeric(),
                TextInput::make('customer-name')->label('Cliente')->disabled()->hintActions([
                    Action::make('Crear cliente')->icon('heroicon-m-plus')->form([
                        TextInput::make('name')->label('Nombre')->required(),
                        TextInput::make('email')->label('Correo')->email()->required(),
                        TextInput::make('ndocument')->label('Número de documento')->maxLength(19)->required(),
                    ])->action(fn($data, Set $set) => $this->showNewUser($data, $set)),
                ]),
                Select::make('method_payment_id')
                    ->options(fn(): array => \App\Models\MethodPayment::all()->pluck('name', 'id')->all())
                    ->default($this->method_payment_id)
                    ->label('Método de pago')
                    ->live()
                    ->afterStateUpdated(
                        function ($state) {
                            session(['pos-method_payment_id' => $state]);
                        }
                    ),
                Select::make('voucher_type_id')
                    ->options(fn(): array => \App\Models\VoucherType::all()->pluck('name', 'id')->all())
                    ->default($this->method_payment_id)
                    ->label('Tipo de comprobante')
                    ->live()
                    ->afterStateUpdated(
                        function ($state) {
                            session(['pos-voucher_type_id' => $state]);
                        }
                    ),
            ])->columns(4),
        ])->statePath('data');
    }


    #[On('pos-reset_sale')]
    public function resetSale()
    {
        session()->forget('pos-current_sale');
        session()->forget('pos-current_sale_details');
        session()->forget('pos-voucher_type_id');
        session()->forget('pos-method_payment_id');
    }


    private function showNewUser($data, Set $set)
    {
        try {
            //set password as ndocument
            $data['password'] = bcrypt($data['ndocument']);
            User::create($data);
            $set('customer-name', $data['name']);
            $set('customer-ndocument', $data['ndocument']);
            Notification::make()
                ->title('Saved')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->icon('heroicon-o-x-circle')
                ->body($e->getMessage())
                ->send();
        }
    }


    private function updateCustomerName($state, $set)
    {
        $customer = User::where('ndocument', $state)->first();
        if ($customer) {
            session(['pos-customer_id' => $customer->id]);
            $set('customer-name', $customer->name);
        } else {
            session()->forget('pos-customer_id');
            $set('customer-name', 'Cliente no encontrado');
        }
    }

    #[On('reset-pos-form-customer')]
    public function resetFormCustomer()
    {
        $this->form->fill();
        session()->forget('pos-customer_id');
    }
}
