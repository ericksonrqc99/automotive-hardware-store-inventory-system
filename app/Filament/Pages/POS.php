<?php

namespace App\Filament\Pages;

use App\Models\User;
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

class POS extends Page implements HasForms
{

    use InteractsWithForms;


    public ?array $data = [];

    protected static ?string $model = \App\Models\User::class;

    public array $listProductsShoppingCart = [];

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static string $view = 'filament.pages.p-o-s';

    public function mount()
    {
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
                        }
                    )->suffixActions([
                        Action::Make("reset-form")->action(fn(Set $set) => $this->resetFormCustomer($set))->icon('heroicon-o-trash'),
                    ])->autofocus()->numeric(),
                TextInput::make('customer-name')->label('Cliente')->disabled()->hintActions([
                    Action::make('Crear cliente')->icon('heroicon-m-plus')->form([
                        TextInput::make('name')->label('Nombre')->required(),
                        TextInput::make('email')->label('Correo')->email()->required(),
                        TextInput::make('ndocument')->label('Número de documento')->maxLength(19)->required(),
                    ])->action(function ($data, Set $set) {
                        try {
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
                    }),
                ]),
            ])->columns(2),
        ])->statePath('data');
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
    public function resetFormCustomer(Set $set)
    {
        $this->form->fill();
        session()->forget('pos-customer_id');
    }
}
