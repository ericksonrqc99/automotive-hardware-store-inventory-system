<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function getCountProducts(): int
    {
        try {
            return Product::count();
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
}
