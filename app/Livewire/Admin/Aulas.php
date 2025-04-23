<?php

namespace App\Livewire\Admin;

use App\Models\Aula;
use App\Models\Asesor;
use App\Models\Programa;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Aulas extends Component
{
    use WithPagination;

    public $codigo, $nombre, $asesores_id, $programas_id, $aulaId;
    public $asesores = [];
    public $programas = [];
    public $search = '';
    public $sortField = 'codigo';
    public $sortDirection = 'asc';
    public $modalAula = false;
    public $modoEdicion = false;

    public function mount()
    {
        $this->asesores = Asesor::where('active', true)->get();
        $this->programas = Programa::where('active', true)->get();
    }

    public function render()
    {
        $aulas = Aula::with('asesor')
            ->where('codigo', 'like', '%' . $this->search . '%')
            ->where('active', true)
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.admin.aulas', compact('aulas'));
    }

    public function rules()
    {
        return [
            'codigo' => [
                'required',
                Rule::unique('aulas')->where(function ($query) {
                    return $query->where('active', true);
                })->ignore($this->aulaId), // Ignora el actual en modo edición
            ],
            'asesores_id' => 'required|exists:asesores,id',
            'programas_id' => 'required|exists:programas,id',
        ];
    }


    public function messages()
    {
        return [
            'codigo.required' => 'El código del aula es obligatorio.',
            'codigo.unique' => 'Este código ya está registrado.',
            'asesores_id.required' => 'Debe seleccionar un asesor.',
            'asesores_id.exists' => 'El asesor seleccionado no es válido.',
            'programas_id.required' => 'Debe seleccionar un programa.',
            'programas_id.exists' => 'El programa seleccionado no es válido.'
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        if ($propertyName === 'asesores_id') {
            $this->validateOnly($propertyName);
        }
    }

    public function guardar()
    {
        $this->validate();

        $data = $this->only(['codigo', 'asesores_id', 'programas_id']);

        if ($this->modoEdicion) {
            Aula::find($this->aulaId)->update($data);
            session()->flash('message', 'Aula actualizada correctamente.');
        } else {
            // Verificamos si hay un aula INACTIVA con el mismo código
            $aulaExistente = Aula::where('codigo', $this->codigo)
                                ->where('active', false)
                                ->first();
    
            if ($aulaExistente) {
                $aulaExistente->update(array_merge($data, ['active' => true]));
                session()->flash('message', 'Aula reactivada correctamente.');
            } else {
                Aula::create(array_merge($data, ['active' => true]));
                session()->flash('message', 'Aula creada correctamente.');
            }
        }

        $this->limpiar();
        $this->dispatch('aula-changed');
    }

    public function abrirModalAgregar()
    {
        $this->resetErrorBag();
        $this->limpiar();
        $this->modoEdicion = false;
        $this->modalAula = true;
    }

    public function editar($id)
    {
        $this->resetErrorBag();

        $aula = Aula::findOrFail($id);
        $this->aulaId = $aula->id;
        $this->codigo = $aula->codigo;
        $this->nombre = $aula->nombre;
        $this->asesores_id = $aula->asesores_id;
        $this->programas_id = $aula->programas_id;
        $this->modoEdicion = true;
        $this->modalAula = true;
    }

    public function eliminar($id)
    {
        $aula = Aula::findOrFail($id);
        $aula->active = false;
        $aula->save();

        session()->flash('message', 'Aula eliminada correctamente.');
        $this->dispatch('aula-changed');
    }

    public function limpiar()
    {
        $this->reset(['codigo', 'nombre', 'asesores_id', 'programas_id','aulaId', 'modoEdicion']);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
}
