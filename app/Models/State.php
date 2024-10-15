<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'system_states';

    // Clave primaria
    protected $primaryKey = 'id';

    // Indicamos que la clave primaria no es un entero auto-incrementado si es el caso
    public $incrementing = true;

    // Tipo de clave primaria (unsigned mediumint)
    protected $keyType = 'int';

    // Campos que pueden ser asignados masivamente (mass-assignment)
    protected $fillable = [
        'name',
        'country_id',
        'country_code',
        'fips_code',
        'iso2',
        'type',
        'latitude',
        'longitude',
        'flag',
        'wikiDataId'
    ];

    // Campos que no pueden ser asignados masivamente (opcional, si usas $guarded en lugar de $fillable)
    // protected $guarded = [];

    // Desactivar timestamps si no usas created_at y updated_at
    // public $timestamps = false;

    // Casts para convertir los tipos de datos
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'flag' => 'boolean',
    ];

    // Relaciones

    // Relación con el modelo de Country
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    // Personalizar el formato de los timestamps (opcional)
    protected $dateFormat = 'Y-m-d H:i:s';

    // Relacionar con el nombre de las columnas created_at y updated_at si usas nombres diferentes
    // const CREATED_AT = 'creation_date';
    // const UPDATED_AT = 'last_update';

    // Si alguna columna utiliza la opción `DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP`
    public function setUpdatedAt($value)
    {
        $this->attributes['updated_at'] = now();
    }
}
