<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    protected $table = 'programas'; // Si no sigue la convención, asignamos el nombre explícitamente.

    // Atributos que se pueden asignar masivamente
    protected $fillable = ['nombre', 'fecha_inicio', 'fecha_fin', 'periodo', 'instituciones_id','mencion'];

    public function institucion()
    {
        return $this->belongsTo(Institucion::class, 'instituciones_id');
    }

    public function aulas()
    {
        return $this->hasMany(Aula::class, 'programas_id')->where('active', true);
    }

}
