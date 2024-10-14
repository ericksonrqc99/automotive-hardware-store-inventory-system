<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'cities';

    // Clave primaria
    protected $primaryKey = 'id';

    // Indicamos que la clave primaria es auto-incrementada
    public $incrementing = true;

    // Tipo de clave primaria (unsigned mediumint)
    protected $keyType = 'int';

    // Campos que pueden ser asignados masivamente (mass-assignment)
    protected $fillable = [
        'name',
        'state_id',
        'state_code',
        'country_id',
        'country_code',
        'latitude',
        'longitude',
        'flag',
        'wikiDataId'
    ];

    // Casts para convertir los tipos de datos
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'flag' => 'boolean',
    ];

    // Relaciones

    // Relación con el modelo de State
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    // Relación con el modelo de Country
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
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
