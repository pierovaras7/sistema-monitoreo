<?php

namespace App\Livewire\Admin;

use App\Models\Institucion;
use Livewire\Component;

class Instituciones extends Component
{

    public $nombre, $institucionId;
    public $modoEdicion = false;
    public $modalInstitucion;
    public string $search = '';
    public $sortField = 'nombre'; // Por defecto, ordenamos por nombre
    public $sortDirection = 'asc'; // Ascendente por defecto

    public function render()
    {

        $instituciones = Institucion::where('nombre', 'like', '%' . $this->search . '%')
            ->where('active', true)
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);


        return view('livewire.admin.instituciones', compact('instituciones'));
    }

    public function guardar()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $data = $this->only(['nombre']);

        if ($this->modoEdicion) {
            $institucion = Institucion::find($this->institucionId);
            $institucion->update($data);
            session()->flash('message', 'Institución actualizado correctamente.');
        } else {
            Institucion::create($data);
            session()->flash('message', 'Institución creado correctamente.');
        }

        $this->limpiar();
        $this->dispatch('institucion-changed', 'Institución guardado con éxito!');
    }

    public function limpiar()
    {
        $this->reset(['nombre']);
    }

    public function abrirModal(){

        $this->limpiar();
        $this->modoEdicion=false;
        $this->modalInstitucion=true;
    }

    public function editar($id)
    {
        $institucion = Institucion::findOrFail($id);
        $this->institucionId = $institucion->id;
        $this->nombre = $institucion->nombre;

        $this->modoEdicion = true;
    }

    public function eliminar($id)
    {
        // Encuentra el programa por ID
        $institucion = Institucion::findOrFail($id);

        // Realiza el borrado lógico
        $institucion->active = false;
        $institucion->save();

        // Mensaje de notificación
        session()->flash('message', 'Institución eliminado correctamente.');

        // Limpiar datos si es necesario (opcional)
        $this->dispatch('institucion-changed', 'Institución eliminado con éxito!');
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            // Si el campo es el mismo, alterna la dirección
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Si el campo es diferente, establece la ordenación ascendente
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
}
