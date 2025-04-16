<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    //
    protected $table = 'asistencias'; // No es necesario si la tabla sigue la convención de plural

    // Atributos que pueden ser asignados masivamente
    protected $fillable = [
        'sesiones_id',  // Relación con la tabla 'sesiones'
        'alumno_id',    // Relación con la tabla 'alumnos'
        'asistio',      // Información sobre si asistió o no
    ];
}
