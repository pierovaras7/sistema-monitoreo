<?php

namespace App\Livewire\Admin;

use App\Models\Asistencia;
use App\Models\Sesion;
use Livewire\Component;

class DetalleSesion extends Component
{
    public $sesionId;
    public $sesion;

    public function mount($sesionId)
    {
        $this->sesionId = $sesionId;
    }

    public function render()
    {
        $this->sesion = Sesion::with(['asesor', 'aula'])->findOrFail($this->sesionId);
        return view('livewire.admin.detalle-sesion', [
            'sesion' => $this->sesion,
        ]);
    }

    public function actualizarAsistencia($id, $valor)
    {
        $asistencia = Asistencia::find($id);
        $asistencia->asistio = $valor;
        $asistencia->save();
    }

}