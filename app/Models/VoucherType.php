<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status_id',
    ];

    public function status()
    {
        return $this->belongsTo(StatusType::class, 'status_id');
    }
}
