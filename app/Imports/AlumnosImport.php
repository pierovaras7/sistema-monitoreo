<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\AlumnoAula;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AlumnosImport implements ToModel, WithValidation, SkipsOnFailure, WithHeadingRow
{
    use SkipsFailures;
    protected $isFirstRow = true; // Variable para verificar la primera fila
    protected $aulaId;

    // Constructor para recibir el id del aula
    public function __construct($aulaId)
    {
        $this->aulaId = $aulaId;
    }
  
    public function model(array $row)
    {

        $alumno = Alumno::create([
            'dni' => $row['dni'],    // Usando las claves del encabezado
            'nombre' => $row['nombre'],
            'telefono' => $row['telefono']
        ]);

        // Crear el registro en AlumnoAula con el id del alumno y el id del aula
        AlumnoAula::create([
            'alumno_id' => $alumno->id,  // ID del alumno recién creado
            'aula_id' => $this->aulaId   // El aula que fue pasado al importar
        ]);

        return $alumno;
    }

    public function rules(): array
    {
        return [
            '*.dni' => ['required', 'digits:8', 'unique:alumnos,dni'],
            '*.nombre' => ['required', 'string'],
            '*.telefono' => ['required', 'digits:9'],
        ];
    }


    public function customValidationMessages()
    {
        return [
            '*.dni.required' => 'El DNI es obligatorio.',
            '*.dni.digits' => 'El DNI debe tener exactamente 8 dígitos.',
            '*.dni.unique' => 'El DNI ya ha sido registrado.',
            '*.nombre.required' => 'El nombre es obligatorio.',
            '*.nombre.string' => 'El nombre debe ser un texto.',
            '*.telefono.required' => 'El teléfono es obligatorio.',
            '*.telefono.digits' => 'El teléfono debe tener exactamente 9 digitos.',
        ];
    }
}
