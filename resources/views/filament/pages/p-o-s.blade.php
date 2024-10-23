<x-filament-panels::page>
    {{$this->form}}
    <div style="display: flex; gap: 10px;">
        <div style="width: 50%;">
            <h2>Productos</h2>
            @livewire('products.list-products')
        </div>
        <div style="width: 50%;">
            <h2>Carrito</h2>
            @livewire('shopping-cart.list-products-shopping-cart')
        </div>
    </div>

    @livewire('sales.manage-sale')
</x-filament-panels::page>