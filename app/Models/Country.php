<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'iso3',
        'numeric_code',
        'iso2',
        'phonecode',
        'capital',
        'currency',
        'currency_name',
        'currency_symbol',
        'tld',
        'native',
        'region',
        'region_id',
        'subregion',
        'subregion_id',
        'nationality',
        'timezones',
        'translations',
        'latitude',
        'longitude',
        'emoji',
        'emojiU',
        'flag',
        'wikiDataId',
    ];


    public function states()
    {
        return $this->hasMany(State::class);
    }

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'timezones' => 'array',
        'translations' => 'array',
        'flag' => 'boolean',
    ];
}
