<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelCar extends Model
{
    use HasFactory;

    protected $table = 'model_cars';

    protected $fillable = [
        'name',
        'brand_id',
        'year',
        'status_id',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_has_model_cars');
    }

    public function status()
    {
        return $this->belongsTo(StatusType::class, 'status_id');
    }
}
