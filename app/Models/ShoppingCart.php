<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use \Sushi\Sushi;

    protected $fillable = [
        'id',
        'name',
        'price',
        'measurement_unit_id',
        'sku',
        'code',
        'description',
        'brand_id',
        'quantity',
        'total_price',
    ];

    protected $rows = [];

    protected $schema = [
        'id' => 'integer',
        'name' => 'string',
        'price' => 'integer',
        'measurement_unit_id' => 'integer',
        'sku' => 'string',
        'brand_id' => 'integer',
        'quantity' => 'integer',
        'total_price' => 'integer',
        'code' => 'string',
        'description' => 'string'
    ];


    // relations
    public function measurementUnit()
    {
        return $this->belongsTo(MeasurementUnit::class);
    }
}
