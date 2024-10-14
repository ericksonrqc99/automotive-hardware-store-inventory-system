<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesHasProducts extends Model
{
    use HasFactory;
    protected $table = 'categories_has_products';

    protected $fillable = [
        'category_id',
        'product_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
