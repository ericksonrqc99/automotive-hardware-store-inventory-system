<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status_id', 'description'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'categories_has_products', 'category_id', 'product_id');
    }

    public function status()
    {
        return $this->belongsTo(StatusType::class, 'status_id');
    }
}
