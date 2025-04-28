<?php

namespace App\Livewire\Admin;

use App\Models\Alumno;
use App\Models\AlumnoAula;
use App\Models\Asistencia;
use App\Models\Sesion;
use Livewire\Component;

class Asistencias extends Component
{
    public $sesionId, $dni,$titulo;

    public function mount($sesionId)
    {
        $this->sesionId = $sesionId;

        $sesion=Sesion::findOrFail($sesionId);
        $this->titulo=$sesion->titulo;

    }


    public function render()
    {
        return view('livewire.admin.asistencias');
    }

    public function guardarAsistencia()
    {
        // Buscar alumno y sesión en una sola instrucción si es posible
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

        // Verificar si aún se puede registrar asistencia
        if (now()->gt($sesion->fecha_fin)) {
            session()->flash('error', 'El tiempo para registrar asistencia ha finalizado.');
            $this->dispatch('asistenciaGuardada');
            return;
        }

        // Registrar o actualizar la asistencia
        Asistencia::updateOrCreate(
            ['sesiones_id' => $sesion->id, 'alumno_id' => $alumno->id],
            ['asistio' => true] // 'asistio'=>1
        );

        session()->flash('message', 'Asistencia registrada correctamente.');
        $this->dispatch('asistenciaGuardada');

        $this->reset('dni'); 
    }
}