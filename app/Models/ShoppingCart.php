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
        'quantity',
        'measurement_unit_id',
        'total_price',
    ];

    protected $rows = [];

    protected $schema = [
        'id' => 'integer',
        'name' => 'string',
        'price' => 'integer',
        'quantity' => 'integer',
        'measurement_unit_id' => 'integer',
        'total_price' => 'integer',
    ];


    // relations
    public function measurementUnit()
    {
        return $this->belongsTo(MeasurementUnit::class);
    }
}
