<x-filament-panels::page>
    <div class="w-full">
        {{$this->form}}
        <div class="flex flex-row gap-4 py-4">
            <div class="w-1/2">
                @livewire('products.list-products')
            </div>
            <div class="w-1/2">
                @livewire('shopping-cart.list-products-shopping-cart')
            </div>
        </div>
        <div class="flex flex-row justify-center p-4">
            @livewire('sales.manage-sale')
        </div>
    </div>
</x-filament-panels::page>