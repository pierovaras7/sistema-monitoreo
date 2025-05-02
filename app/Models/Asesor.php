<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asesor extends Model
{
    protected $table = 'asesores'; // Si la tabla se llama 'asesores', no es necesario definirla, pero si es otro nombre, hazlo aquí.

    // Atributos que pueden ser asignados masivamente
    protected $fillable = [
        'id',
        'user_id', // Para la relación con la tabla 'users'
        'nombre',
        'telefono',
        'dni',
    ];
    //
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function aulas() {
        return $this->hasMany(Aula::class);
    }
    
}
