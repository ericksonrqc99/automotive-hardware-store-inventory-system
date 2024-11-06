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
use App\Services\UserService;
use App\Services\PosService;

class POS extends Page implements HasForms
{
    use InteractsWithForms;
    use HasPageShield;

    // custom properties
    public ?array $data = [];

    private ?array $listProductsShoppingCart = [];

    public ?int $method_payment_id;

    public ?int $voucher_type_id;

    private $userService;

    private $posService;

    //filament properties
    protected static ?string $model = \App\Models\User::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static string $view = 'filament.pages.p-o-s';

    //methods 


    public function __construct()
    {
        $this->userService = app(UserService::class);
        $this->posService = app(PosService::class);
    }

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
                TextInput::make('pos-customer_ndocument')
                    ->label('Número de documento del cliente')
                    ->required()
                    ->live(debounce: 1000)
                    ->afterStateUpdated(
                        fn($state, $set) => $this->getUser($state, $set)
                    )->suffixActions([
                        Action::Make("reset-form")->action(fn(Set $set) => $this->actionResetPos($set))->icon('heroicon-o-trash'),
                    ])->autofocus()->numeric(),
                TextInput::make('pos-customer_name')->label('Cliente')->disabled()->hintActions([
                    Action::make('Crear cliente')->icon('heroicon-m-plus')->form([
                        TextInput::make('name')->label('Nombre')->required(),
                        TextInput::make('email')->label('Correo')->email()->required(),
                        TextInput::make('ndocument')->label('Número de documento')->maxLength(19)->numeric()->required(),
                    ])->action(
                        fn($data, $set) => $this->actionCreateUser($data, $set)
                    ),
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

    // actions
    private function actionResetPos($set)
    {
        $this->posService->resetCustomer($set, $this->form);
    }

    private function actionCreateUser($data, Set $set)
    {
        try {
            // set ndocument with password
            $data['password'] = bcrypt($data['ndocument']);
            $newUser = $this->userService->createUser($data);
            if (!empty($newUser)) {
                $this->posService->setUser($newUser, $set);
            };
        } catch (\Throwable $th) {
            Notification::make()
                ->title('Error al crear el cliente')
                ->body($th->getMessage())
                ->send();
        }
    }


    private function getUser($state, $set)
    {
        try {
            $user = $this->userService->findUserByDocument($state);
            if (!empty($user)) {
                $this->posService->setUser($user, $set);
                $this->posService->resetSale();
                $this->dispatch('pos-reset_sale');
            } else {
                session()->forget('pos-customer_id');
                $set('pos-customer_name', 'Cliente no encontrado');
            }
        } catch (\Throwable $th) {
            Notification::make()
                ->title('Error al buscar el cliente')
                ->body($th->getMessage())
                ->send();
        }
    }

    #[On('pos-reset_form_customer')]
    public function resetFormCustomer()
    {
        $this->form->fill();
        session()->forget('pos-customer_id');
    }
}
