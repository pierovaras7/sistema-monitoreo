<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    // Definir la tabla si no sigue la convención plural
    protected $table = 'alumnos'; // Si la tabla se llama 'alumnos', no es necesario definirla, pero si es otro nombre, hazlo aquí.

    // Atributos que pueden ser asignados masivamente
    protected $fillable = [
        'nombre',
        'aula_id',
    ];

    public function aulas()
    {
        return $this->belongsToMany(Aula::class, 'alumno_aula');
    }

}
