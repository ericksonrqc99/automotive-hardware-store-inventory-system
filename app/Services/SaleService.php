<?php

namespace App\Services;

use App\Models\Sale;
use Illuminate\Support\Collection;

class SaleService
{
    public function __construct(private ShoppingCartService $shoppingCartService) {}

    public function storeSale(array $data): Sale
    {
        try {
            foreach ($data as $key => $value) {
                if ($value === null) {
                    throw new \Exception(__("El campo $key es requerido"));
                }
            }
            $sale = Sale::create($data);
            if (!$sale) {
                throw new \Exception(__("Error al crear la venta"));
            }
            return $sale;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
}
