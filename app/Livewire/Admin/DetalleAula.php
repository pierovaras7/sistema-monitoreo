<?php

namespace App\Livewire\Admin;

use App\Models\Aula;
use App\Models\Sesion;
use Livewire\Component;

class DetalleAula extends Component
{
    public $aulaId;

    // Propiedades para sesiones
    public $titulo, $fecha_inicio, $fecha_fin, $modalSesion = false;

    public function mount($aulaId)
    {
        $this->aulaId = $aulaId;
    }
    
    public function render()
    {
        $aula = Aula::with(['sesiones' => function ($query) {
            $query->orderBy('fecha_inicio', 'desc');
        }])->findOrFail($this->aulaId);
        
        return view('livewire.admin.detalle-aula', [
            'aula' => $aula,
            'sesiones' => $aula->sesiones,
        ]);
    }

    
    public function abrirModalSesion()
    {
        $this->resetErrorBag();
        $this->limpiarSesion();
        $this->modalSesion = true; // si estás usando una propiedad para el modal
    }

     // Reglas de validación para sesiones
    protected $rulesSesion = [
        'titulo' => 'required|string|max:255',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
    ];

    protected $messagesSesion = [
        'titulo.required' => 'El título de la sesión es obligatorio.',
        'titulo.string' => 'El título debe ser una cadena de texto.',
        'titulo.max' => 'El título no puede tener más de 255 caracteres.',
    
        'fecha_inicio.required' => 'La fecha de inicio de la sesión es obligatoria.',
        'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
    
        'fecha_fin.required' => 'La fecha de fin de la sesión es obligatoria.',
        'fecha_fin.date' => 'La fecha de fin debe ser una fecha válida.',
        'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
    ];

    public function guardarSesion()
    {
        $this->validate($this->rulesSesion, $this->messagesSesion); 

        // Crear una nueva sesión asociada a la aula
        $sesion = new Sesion();
        $sesion->titulo = $this->titulo;
        $sesion->fecha_inicio = $this->fecha_inicio;
        $sesion->fecha_fin = $this->fecha_fin;
        $sesion->aula_id = $this->aulaId;
        $sesion->save();

        // Cerrar el modal y resetear los campos
        $this->modalSesion = false;
        $this->reset(['titulo', 'fecha_inicio', 'fecha_fin']);

        session()->flash('message', 'La sesión se ha guardado exitosamente.');

        $this->dispatch('sesion-changed');

    }

    // Método para limpiar los campos de la sesión
    public function limpiarSesion()
    {
        $this->titulo = '';
        $this->fecha_inicio = '';
        $this->fecha_fin = '';
    }

}
