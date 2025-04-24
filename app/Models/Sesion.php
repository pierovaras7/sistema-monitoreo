<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sesion extends Model
{
    //
    protected $table = 'sesiones'; // Si no sigue la convención, asignamos el nombre explícitamente.

    // Atributos que se pueden asignar masivamente
    protected $fillable = [
        'titulo',
        'fecha_inicio',
        'fecha_fin',
        'aula_id',
    ];

    // Relación con Aula
    public function aula()
    {
        return $this->belongsTo(Aula::class, 'aula_id');
    }

    // Relación con Asesor 
    public function asesor()
    {
        return $this->belongsTo(Asesor::class, 'asesores_id');
    }

    // Relación con Asistencia
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'sesiones_id');
    }
}
