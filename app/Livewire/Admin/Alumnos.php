<?php

namespace App\Livewire\Admin;

use App\Models\Alumno;
use Livewire\Component;

class Alumnos extends Component
{
    public $nombre, $dni, $telefono, $alumnoId;
    public $modoEdicion = false;
    public $modalAlumno;
    public string $search = '';
    public $sortField = 'nombre'; // Por defecto, ordenamos por nombre
    public $sortDirection = 'asc'; // Ascendente por defecto

    public function render()
    {
        $alumnos = Alumno::where('nombre', 'like', '%' . $this->search . '%')
            ->where('active', true)
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.admin.alumnos',compact('alumnos'));
    }

    public function guardar()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'dni' => 'required|string|max:8',
            'telefono' => 'required|string|max:9',
        ]);

        $data = $this->only(['nombre', 'dni', 'telefono']);

        if ($this->modoEdicion) {
            $alumno = Alumno::find($this->alumnoId);
            $alumno->update($data);
            session()->flash('message', 'Alumno actualizado correctamente.');
        } else {
            Alumno::create($data);
            session()->flash('message', 'Alumno creado correctamente.');
        }

        $this->limpiar();
        $this->dispatch('alumno-changed', 'Alumno guardado con éxito!');
    }

    public function abrirModal()
    {

        $this->limpiar();
        $this->modoEdicion = false;
        $this->modalAlumno = true;
    }


    public function editar($id)
    {
        $alumno = Alumno::findOrFail($id);
        $this->alumnoId = $alumno->id;
        $this->nombre = $alumno->nombre;
        $this->dni = $alumno->dni;
        $this->telefono = $alumno->telefono;
        $this->modoEdicion = true;
    }

    public function eliminar($id)
    {
        // Encuentra el programa por ID
        $alumno = Alumno::findOrFail($id);

        // Realiza el borrado lógico
        $alumno->active = false;
        $alumno->save();

        // Mensaje de notificación
        session()->flash('message', 'Alumno eliminado correctamente.');

        // Limpiar datos si es necesario (opcional)
        $this->dispatch('alumno-changed', 'Alumno eliminado con éxito!');
    }


    public function limpiar()
    {
        $this->reset(['nombre', 'dni', 'telefono']);
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
