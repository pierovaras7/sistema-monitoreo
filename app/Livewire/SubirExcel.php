<?php

namespace App\Livewire;

use App\Imports\AlumnosImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

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

    public $errores = [];

    // Método para subir y procesar el archivo Excel
    public function subirArchivo($aulaId)
    {

        $this->validate();

        try {
            $import = new AlumnosImport($aulaId);
            Excel::import($import, $this->archivo);
        
            if ($import->failures()->isNotEmpty()) {
                $this->errores = collect($import->failures())->map(function ($failure) {
                    return [
                        'fila' => $failure->row(),
                        'campo' => $failure->attribute(),
                        'errores' => $failure->errors(),
                        'valores' => $failure->values(),
                    ];
                })->toArray();
        
                $this->dispatch('abrir-modal-errores');
            } else {
                // Despachar el evento de éxito para mostrar un modal de éxito
                $this->dispatch('mostrar-modal-exito');
                $this->dispatch('AlumnosImportados');
            }
        } catch (ValidationException $e) {
            $this->errores = collect($e->failures())->map(function ($failure) {
                return [
                    'fila' => $failure->row(),
                    'campo' => $failure->attribute(),
                    'errores' => $failure->errors(),
                    'valores' => $failure->values(),
                ];
            })->toArray();
        
            $this->dispatch('abrir-modal-errores');
        }
        
    
        $this->reset('archivo');
    
    }

    public function render()
    {
        return view('livewire.subir-excel');
    }
}