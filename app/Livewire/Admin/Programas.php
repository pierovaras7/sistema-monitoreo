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
        $programas = Programa::with(['institucion', 'aulas.asesor'])
                    ->where('nombre', 'like', '%' . $this->search . '%')
                    ->where('active', true)
                    ->orderBy($this->sortField, $this->sortDirection)
                    ->paginate(15);


        return view('livewire.admin.programas', compact('programas'));
    }

    // Reglas de validación y mensajes personalizados en un solo lugar
    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'periodo' => 'required|string|max:255',
            'instituciones_id' => 'required|exists:instituciones,id',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre del programa es obligatorio.',
            'nombre.string' => 'El nombre del programa debe ser una cadena de texto.',
            'nombre.max' => 'El nombre del programa no debe exceder los 255 caracteres.',
            
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            
            'fecha_fin.required' => 'La fecha de finalización es obligatoria.',
            'fecha_fin.date' => 'La fecha de finalización debe ser una fecha válida.',
            'fecha_fin.after_or_equal' => 'La fecha de finalización debe ser igual o posterior a la fecha de inicio.',
            
            'periodo.required' => 'El periodo es obligatorio.',
            'periodo.string' => 'El periodo debe ser una cadena de texto.',
            'periodo.max' => 'El periodo no debe exceder los 255 caracteres.',
            
            'instituciones_id.required' => 'La institución es obligatoria.',
            'instituciones_id.exists' => 'La institución seleccionada no es válida.',
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

    public function abrirModalAgregar()
    {
        $this->resetErrorBag();
        $this->limpiar();
        $this->modoEdicion = false;
        $this->modalPrograma = true; // si estás usando una propiedad para el modal
    }

    public function editar($id)
    {
        $this->resetErrorBag();
        $programa = Programa::findOrFail($id);
        $this->programaId = $programa->id;
        $this->instituciones_id = $programa->instituciones_id;
        $this->nombre = $programa->nombre;
        $this->fecha_inicio = Carbon::parse($programa->fecha_inicio)->format('Y-m-d');
        $this->fecha_fin = Carbon::parse($programa->fecha_fin)->format('Y-m-d');
        $this->periodo = $programa->periodo;
       
        // Cambiar a modo de edición
        $this->modoEdicion = true;

    }

    public function confirmarEliminacion($id)
    {
        $this->programaId = $id;
        $this->modalPrograma = true; // Abrir el modal
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

    public $aulasDelPrograma = [];

    public function verAulas($programaId)
    {
        $this->aulasDelPrograma = Programa::with('aulas.asesor')->find($programaId)?->aulas ?? [];
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