<div>
    {{$this->createAction}}
    @if ($this->successSale)
    {{$this->viewInvoiceAction}}

    @endif
    <x-filament-actions::modals />
</div>