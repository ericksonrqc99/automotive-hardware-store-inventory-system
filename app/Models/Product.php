<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sku', 'code', 'description', 'price', 'cost', 'minimum_stock', 'stock', 'alert_stock_id', 'generic_use', 'brand_id', 'supplier_id', 'measurement_unit_id', 'status_id'];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function measurementUnit()
    {
        return $this->belongsTo(MeasurementUnit::class, 'measurement_unit_id');
    }

    public function characteristics()
    {
        return $this->belongsToMany(Characteristic::class, 'products_has_characteristics', 'product_id', 'characteristic_id')
            ->withPivot('value');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_has_products', 'product_id', 'category_id');
    }

    public function modelCars()
    {
        return $this->belongsToMany(ModelCar::class, 'products_has_model_cars', 'product_id', 'model_car_id');
    }

    public function status()
    {
        return $this->belongsTo(StatusType::class, 'status_id');
    }
    
    public function alertStock()
    {
        return $this->belongsTo(StatusType::class, 'alert_stock_id');
    }
}
