<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumnoAula extends Model
{
    //
    // Definir la tabla si no sigue la convención plural
    protected $table = 'alumno_aula'; // Si la tabla se llama 'alumnos', no es necesario definirla, pero si es otro nombre, hazlo aquí.

    // Atributos que pueden ser asignados masivamente
    protected $fillable = [
        'alumno_id',
        'aula_id',
    ];

    // Relación con Alumno
    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    // Relación con Aula
    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }
}
