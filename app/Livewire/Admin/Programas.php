<?php

namespace App\Livewire\Admin;

use App\Models\Institucion;
use App\Models\Programa;
use Carbon\Carbon;
use Livewire\Component;

class Programas extends Component
{
    public $periodos = [];
    public $instituciones = [];
    public $nombre, $fecha_inicio, $fecha_fin, $periodo, $programaId;
    public $modoEdicion = false;
    public $instituciones_id;
    public $modalPrograma;
    public string $search = '';
    public $sortField = 'nombre'; // Por defecto, ordenamos por nombre
    public $sortDirection = 'asc'; // Ascendente por defecto



    public function mount()
    {
        
        // Puedes cargar periodos fijos o desde DB si los tienes en una tabla
        $this->periodos = ['2025-I', '2025-II', '2026-I'];

        // Desde la tabla instituciones
        $this->instituciones = Institucion::all();
    }

    public function render()
    {
        $programas = Programa::with('institucion')
                    ->where('nombre', 'like', '%' . $this->search . '%')
                    ->where('active', true)
                    ->orderBy($this->sortField, $this->sortDirection) // Ordena por el campo y dirección especificada
                    ->paginate(15);

        return view('livewire.admin.programas', compact('programas'));
    }


    public function guardar()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'periodo' => 'required|string|max:255',
            'instituciones_id' => 'required|exists:instituciones,id',
        ]);

        $data = $this->only(['nombre', 'fecha_inicio', 'fecha_fin', 'periodo', 'instituciones_id']);

        if ($this->modoEdicion) {
            $programa = Programa::find($this->programaId);
            $programa->update($data);
            session()->flash('message', 'Programa actualizado correctamente.');
        } else {
            Programa::create($data);
            session()->flash('message', 'Programa creado correctamente.');
        }

        $this->limpiar();
        $this->dispatch('programa-changed', '¡Programa guardado con éxito!');

    }


    public function editar($id)
    {
        $programa = Programa::findOrFail($id);
        $this->programaId = $programa->id;
        $this->instituciones_id = $programa->instituciones_id;
        $this->nombre = $programa->nombre;
        $this->fecha_inicio = Carbon::parse($programa->fecha_inicio)->format('Y-m-d');
        $this->fecha_fin = Carbon::parse($programa->fecha_fin)->format('Y-m-d');
        $this->periodo = $programa->periodo;
        $this->modoEdicion = true;
    }

    public function eliminar($id)
    {
        // Encuentra el programa por ID
        $programa = Programa::findOrFail($id);
        
        // Realiza el borrado lógico
        $programa->active = false;
        $programa->save();

        // Mensaje de notificación
        session()->flash('message', 'Programa eliminado correctamente.');

        // Limpiar datos si es necesario (opcional)
        $this->dispatch('programa-changed', '¡Programa eliminado con éxito!');
    }


    public function limpiar()
    {
        $this->reset(['nombre', 'fecha_inicio', 'fecha_fin', 'periodo', 'instituciones_id', 'programaId', 'modoEdicion']);
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