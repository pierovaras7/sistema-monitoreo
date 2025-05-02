<?php

namespace App\Livewire;

use App\Imports\AlumnosImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class SubirExcel extends Component
{
    use WithFileUploads;

    public $archivo;
    public $aulaId;  // Propiedad para almacenar el ID del aula

    // Validación del archivo
    protected $rules = [
        'archivo' => 'required|file|mimes:xlsx,xls',
        'aulaId' => 'required|integer', // Validar que el aulaId sea un valor entero
    ];

    public function mount($aulaId)
    {
        $this->aulaId = $aulaId; // Asigna el valor de aulaId a la propiedad
    }

    public function updatedArchivo()
    {
        // Verificar si hay un archivo cargado
        if ($this->archivo) {
            // Aquí puedes procesar el archivo, por ejemplo:
            $this->subirArchivo($this->aulaId);
        }
    }


    // Método para subir y procesar el archivo Excel
    public function subirArchivo($aulaId)
    {

        $this->validate();

        // Procesar el archivo Excel usando el importador
        Excel::import(new AlumnosImport($aulaId), $this->archivo);

        session()->flash('message', 'Alumnos importados correctamente.');

        // Resetear el archivo después de subirlo
        $this->reset('archivo');
    }

    public function render()
    {
        return view('livewire.subir-excel');
    }
}