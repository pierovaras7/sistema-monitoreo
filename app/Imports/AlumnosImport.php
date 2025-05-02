<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\AlumnoAula;
use Maatwebsite\Excel\Concerns\ToModel;

class AlumnosImport implements ToModel
{
    protected $isFirstRow = true; // Variable para verificar la primera fila
    protected $aulaId;

    // Constructor para recibir el id del aula
    public function __construct($aulaId)
    {
        $this->aulaId = $aulaId;
    }
  
    public function model(array $row)
    {
        // Ignorar la primera fila
        if ($this->isFirstRow) {
            $this->isFirstRow = false;
            return null; // No hacer nada con la primera fila
        }

        // Crear el nuevo alumno
        $alumno = Alumno::create([
            'dni' => $row[0], 
            'nombre' => $row[1],
            'telefono' => $row[2]
        ]);

        // Crear el registro en AlumnoAula con el id del alumno y el id del aula
        AlumnoAula::create([
            'alumno_id' => $alumno->id,  // ID del alumno recién creado
            'aula_id' => $this->aulaId   // El aula que fue pasado al importar
        ]);

        return $alumno;
    }
}
