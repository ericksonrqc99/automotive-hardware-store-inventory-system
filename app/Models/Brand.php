<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status_id', 'description'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function suppliers()     
    {
        return $this->belongsToMany(Supplier::class, 'brands_has_suppliers');
    }

    public function status():BelongsTo
    {
        return $this->belongsTo(StatusType::class);
    }
    
}
