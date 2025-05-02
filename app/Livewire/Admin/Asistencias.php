<?php

namespace App\Livewire\Admin;

use App\Models\Alumno;
use App\Models\AlumnoAula;
use App\Models\Asistencia;
use App\Models\Sesion;
use Carbon\Carbon;
use Livewire\Component;

class Asistencias extends Component
{
    // Livewire\Asistencia.php
    public $sesionId;
    public $sesion;
    public $dni;

    protected $rules = [
        'dni' => 'required|digits:8',
    ];

    protected $messages = [
        'dni.required' => 'El campo DNI es obligatorio.',
        'dni.digits' => 'El DNI debe tener exactamente 8 dígitos.',
    ];
    
    public function mount($uuid)
    {
        
        $this->sesion = Sesion::where('link_asistencia', $uuid)->firstOrFail();
        $this->sesionId = $this->sesion->id;
        $now = Carbon::now('America/Lima');

        if (!$now->between($this->sesion->fecha_inicio, $this->sesion->fecha_fin)) {
            abort(403, 'Esta sesión no está activa en este momento.');
        }


    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    


    public function render()
    {
        return view('livewire.admin.asistencia')->layout('layouts.simple');
    }

    public function guardarAsistencia()
    {
        // Validación del DNI con reglas de Livewire
        $this->validate();
        // Buscar alumno y sesión
        $alumno = Alumno::where('dni', $this->dni)->first();
        $sesion = Sesion::find($this->sesionId);
    
        if (!$alumno || !$sesion) {
            session()->flash('error', 'Alumno o sesión no encontrada.');
            $this->dispatch('asistenciaGuardada');
            return;
        }
    
        // Verificar si el alumno pertenece al aula
        $perteneceAlAula = AlumnoAula::where([
            ['aula_id', $sesion->aula_id],
            ['alumno_id', $alumno->id],
        ])->exists();
    
        if (!$perteneceAlAula) {
            session()->flash('error', 'El alumno no pertenece a esta aula.');
            $this->dispatch('asistenciaGuardada');
            return;
        }
    
        // Verificar si ya existe la asistencia con asistio = true
        $asistencia = Asistencia::where([
            ['sesiones_id', $sesion->id],
            ['alumno_id', $alumno->id],
        ])->first();
    
        if ($asistencia && $asistencia->asistio) {
            session()->flash('info', "Hola {$alumno->nombre}, ya registraste tu asistencia.");
            $this->dispatch('asistenciaGuardada');
            return;
        }        
    
        // Registrar o actualizar la asistencia
        Asistencia::updateOrCreate(
            ['sesiones_id' => $sesion->id, 'alumno_id' => $alumno->id],
            ['asistio' => true]
        );
    
        session()->flash('message', "Hola {$alumno->nombre}, has registrado correctamente tu asistencia.");
        $this->dispatch('asistenciaGuardada');
    
        $this->reset('dni');
    }
    
}