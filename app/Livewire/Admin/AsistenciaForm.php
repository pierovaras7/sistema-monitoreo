<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class AsistenciaForm extends Component
{
    public $dni;
    public $sesion;

    public function mount($sesion)
    {
        $this->sesion = $sesion;
    }

    public function guardarAsistencia()
    {
        $this->validate([
            'dni' => 'required|digits:8',
        ]);

        // Aquí puedes agregar la lógica de guardar asistencia en BD
        session()->flash('message', 'Asistencia registrada correctamente.');
    }

    public function render()
    {
        return view('livewire.admin.asistencia-form');
    }
}