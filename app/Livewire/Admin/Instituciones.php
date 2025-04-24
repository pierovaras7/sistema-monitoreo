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

    // Reglas de validación y mensajes personalizados en un solo lugar
    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255|regex:/^[\pL\s]+$/u',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre de la instituciòn es obligatorio.',
            'nombre.string' => 'El nombre de la instituciòn debe ser una cadena de texto.',
            'nombre.max' => 'El nombre de la instituciòn no debe exceder los 255 caracteres.',
            'nombre.regex' => 'El nombre solo debe contener letras y espacios.',
        ];
    }

    // Método único para validar cualquier campo actualizado
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function guardar()
    {
        $this->validate();

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

    public function abrirModalAgregar()
    {
        $this->resetErrorBag();
        $this->limpiar();
        $this->modoEdicion = false;
        $this->modalInstitucion = true; // si estás usando una propiedad para el modal
    }

    public function editar($id)
    {
        $this->resetErrorBag();
        $institucion = Institucion::findOrFail($id);
        $this->institucionId = $institucion->id;
        $this->nombre = $institucion->nombre;

        $this->modoEdicion = true;
    }

    public function confirmarEliminacion($id)
    {
        $this->institucionId = $id;
        $this->modalInstitucion = true; // Abrir el modal
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
