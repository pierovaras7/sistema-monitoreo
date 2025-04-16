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
        'fecha_hora',
        'aula_id',
        'user_id',
    ];

    // Relación con Aula
    public function aula()
    {
        return $this->belongsTo(Aula::class, 'aula_id');
    }

    // Relación con User (asesor)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con Asistencia
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'sesiones_id');
    }
}
