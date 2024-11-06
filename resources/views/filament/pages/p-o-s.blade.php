<x-filament-panels::page>
    {{$this->form}}
    <div class="w-full">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="w-full lg:w-1/2">
                <h3 class="text-lg font-bold mb-2">{{__('Productos')}}</h3>
                <div class="w-full">
                    @livewire('products.list-products')
                </div>
            </div>
            <div class="w-full lg:w-1/2">
                <h3 class="text-lg font-bold mb-2">{{__('Carrito')}}</h3>
                <div class="w-full">
                    @livewire('shopping-cart.list-products-shopping-cart')
                </div>
            </div>
        </div>
        <div class="flex flex-row justify-center p-4">
            @livewire('sales.manage-sale')
        </div>
    </div>
</x-filament-panels::page>