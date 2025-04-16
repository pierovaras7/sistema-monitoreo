<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{

    // Nombre de la tabla
    protected $table = 'instituciones'; // Si no sigue la convención, asignamos el nombre explícitamente.

    // Atributos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',  // Nombre de la institución
    ];

     // Relación con la tabla Aula
    public function aulas()
    {
        return $this->hasMany(Aula::class, 'instituciones_id');
    }

    // Relación con la tabla Asesor
    public function asesores()
    {
        return $this->hasMany(Asesor::class, 'instituciones_id');
    }
}
