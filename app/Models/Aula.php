<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    //
    protected $table = 'aulas'; // Si no sigue la convención, asignamos el nombre explícitamente.

    // Atributos que se pueden asignar masivamente
    protected $fillable = [
        'codigo',         // El código del aula
        'programas_id', // Relación con la tabla instituciones
        'asesores_id',    // Relación con la tabla asesores
        'active'
    ];

    public function programa()
    {
        return $this->belongsTo(Programa::class, 'programas_id');
    }

    // Relación con el modelo Asesor
    public function asesor()
    {
        return $this->belongsTo(Asesor::class, 'asesores_id');
    }

    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'alumno_aula');
    }

}
