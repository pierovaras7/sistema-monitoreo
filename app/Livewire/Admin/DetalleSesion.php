<?php

namespace App\Livewire\Admin;

use App\Models\Sesion;
use Carbon\Carbon;
use Livewire\Component;

class DetalleSesion extends Component
{
    public $sesionId;
    public Sesion $sesion;
    public $titulo, $fecha_inicio, $fecha_fin, $link_reunion, $link_asistencia;

    // Al montar el componente, obtenemos la sesión por su ID
    public function mount($sesionId)
    {
        $this->sesionId = $sesionId;
        $this->sesion = Sesion::findOrFail($this->sesionId);
        $this->titulo = $this->sesion->titulo;
        $this->fecha_inicio = Carbon::parse($this->sesion->fecha_inicio)->format('Y-m-d\TH:i');
        $this->fecha_fin = Carbon::parse($this->sesion->fecha_fin)->format('Y-m-d\TH:i');
        $this->link_reunion = $this->sesion->link_reunion;
        $this->link_asistencia = $this->sesion->link_asistencia;
    }

    // Método para actualizar la sesión
    public function actualizarSesion()
    {
        // Validamos los campos
        $this->validate([
            'titulo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'link_reunion' => 'nullable|url',
        ]);

        // Actualizamos la sesión
        $this->sesion->update([
            'titulo' => $this->titulo,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'link_reunion' => $this->link_reunion,
            'link_asistencia' => $this->link_asistencia,
        ]);

        // Emitimos evento para cerrar el modal o notificar éxito
        $this->dispatchBrowserEvent('cerrar-modal'); // Este evento puede ser usado para cerrar el modal en el frontend

        // Opcional: Mensaje flash de éxito
        session()->flash('success', 'La sesión se ha actualizado correctamente.');
    }

    public function render()
    {
        return view('livewire.admin.detalle-sesion');
    }
}
