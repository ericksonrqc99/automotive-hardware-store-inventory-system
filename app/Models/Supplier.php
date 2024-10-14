<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'contact', 'phone', 'country_id', 'state_id', 'city_id', 'address', 'email', 'status_id'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brands_has_suppliers');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function status()
    {
        return $this->belongsTo(StatusType::class, 'status_id');
    }
}
