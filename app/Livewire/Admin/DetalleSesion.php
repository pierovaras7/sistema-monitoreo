<?php

namespace App\Livewire\Admin;

use App\Models\Asistencia;
use App\Models\Sesion;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Livewire\Component;

class DetalleSesion extends Component
{
    public $sesionId;
    public Sesion $sesion;
    public $titulo, $fecha_inicio, $fecha_fin, $link_reunion, $link_asistencia;
    public $modalSesion = false;
    public $modoEdicion = false;

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

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
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
        ], [
            'link_reunion.url' => 'El enlace de reunión debe ser una URL válida. Ejemplo: https://tureunion.com',
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
        $this->dispatch('cerrar-modal');
        // Este evento puede ser usado para cerrar el modal en el frontend

        // Opcional: Mensaje flash de éxito
        session()->flash('success', 'La sesión se ha actualizado correctamente.');
    }

    public function actualizarAsistencia($asistenciaId, $asistio)
    {
        // Buscar la asistencia por su ID
        $asistencia = Asistencia::find($asistenciaId);

        if (!$asistencia) {
            // Si no se encuentra la asistencia, lanzamos un mensaje de error
            session()->flash('error', 'Asistencia no encontrada.');
            return;
        }

        // Actualizamos el estado de asistencia
        $asistencia->asistio = $asistio;
        $asistencia->save();

        // Mensaje de éxito
        $this->dispatch('asistenciaActualizada');
    }

    public function guardarObservacion($id, $observacion)
    {
        $asistencia = Asistencia::find($id);

        if ($asistencia) {
            // Actualizamos la observación
            $asistencia->observacion = $observacion;
            $asistencia->save();
        }

        $this->dispatch('observacionActualizada');
    }


    public function abrirModalAgregar()
    {
        $this->resetErrorBag();
        $this->modoEdicion = true;
        $this->modalSesion = true;

        // Cargar los datos actuales de la sesión
        $this->titulo = $this->sesion->titulo;
        $this->fecha_inicio = Carbon::parse($this->sesion->fecha_inicio)->format('Y-m-d\TH:i');
        $this->fecha_fin = Carbon::parse($this->sesion->fecha_fin)->format('Y-m-d\TH:i');
        $this->link_reunion = $this->sesion->link_reunion;
        $this->link_asistencia = $this->sesion->link_asistencia;
    }


    public function limpiar()
    {
        $this->reset(['titulo', 'fecha_inicio', 'fecha_fin', 'link_reunion']);
    }


    public function render()
    {
        $asistencias = Asistencia::where('sesiones_id', $this->sesionId)
            ->paginate(10);

        return view('livewire.admin.detalle-sesion', [
            'asistencias' => $asistencias,
        ]);
    }
}
