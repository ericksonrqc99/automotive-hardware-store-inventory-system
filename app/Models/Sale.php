<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'customer_id', 'quantity', 'sub_total_price', 'discount', 'tax_amount', 'total_price'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'sales_details')
            ->withPivot(['quantity']);
    }
}
