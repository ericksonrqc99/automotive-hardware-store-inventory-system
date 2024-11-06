<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Support\Collection;

class ShoppingCartService
{

    /**
     * Get all products of the shopping cart
     *
     * @return \Illuminate\Database\Eloquent\Collection<ShoppingCart>
     */
    public function getAllProducts(): Collection
    {
        try {
            $allProducts = ShoppingCart::all();
            return $allProducts;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }


    /**
     * Reduce stock of products in the shopping cart
     *
     * @param Collection<ShoppingCart> $productsShoppingCart
     */

    public function reduceStock(Collection $productsShoppingCart)
    {
        try {
            $productsShoppingCart->each(function ($productShoppingCart) {
                $product = Product::find($productShoppingCart->id);
                $product->stock = $product->stock - $productShoppingCart->quantity;
                $product->save();
            });
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }



    public function isEmpty(): bool
    {
        try {
            return ShoppingCart::all()->isEmpty();
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
}
