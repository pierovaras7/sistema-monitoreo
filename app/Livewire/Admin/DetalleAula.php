<?php

namespace App\Livewire\Admin;

use App\Models\Alumno;
use App\Models\AlumnoAula;
use App\Models\Asistencia;
use App\Models\Aula;
use App\Models\Sesion;
use Livewire\Component;
use Illuminate\Support\Str;

class DetalleAula extends Component
{
    public $aulaId, $nombre, $dni, $telefono, $alumnoId;
    public $modoEdicionAlumno = false;
    public $modalAlumno;
    public string $search = '';
    public $sortField = 'nombre'; // Por defecto, ordenamos por nombre
    public $sortDirection = 'asc'; // Ascendente por defecto



    // Propiedades para sesiones
    public $titulo, $fecha_inicio, $fecha_fin, $link_reunion, $link_asistencia, $modalSesion = false;

    public function mount($aulaId)
    {
        $this->aulaId = $aulaId;
    }


    public function render()
    {
        $alumnos = Alumno::where('nombre', 'like', '%' . $this->search . '%')
            ->where('active', true)
            ->whereHas('aulas', function ($query) {
                $query->where('aula_id', $this->aulaId);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $aula = Aula::with(['sesiones' => function ($query) {
            $query->orderBy('fecha_inicio', 'desc');
        }])->findOrFail($this->aulaId);

        return view('livewire.admin.detalle-aula', compact('alumnos', 'aula'));
    }




    protected function rulesAlumno()
    {
        $dniRule = 'required|string|size:8|regex:/^[0-9]+$/|unique:alumnos,dni';

        if ($this->modoEdicionAlumno && $this->alumnoId) {
            // Excluir el DNI actual del alumno en edición
            $dniRule = 'required|string|size:8|regex:/^[0-9]+$/|unique:alumnos,dni,' . $this->alumnoId;
        }

        return [
            'nombre' => 'required|string|max:255|regex:/^[\pL\s]+$/u',
            'dni' => $dniRule,
            'telefono' => 'required|string|max:15|regex:/^[0-9]+$/',
        ];
    }


    protected $messagesAlumno = [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.string' => 'El nombre debe ser una cadena de texto.',
        'nombre.max' => 'El nombre no debe exceder los 255 caracteres.',
        'nombre.regex' => 'El nombre solo debe contener letras y espacios.',

        'dni.required' => 'El DNI es obligatorio.',
        'dni.string' => 'El DNI debe ser una cadena de texto.',
        'dni.size' => 'El DNI debe tener exactamente 8 caracteres.',
        'dni.regex' => 'El DNI solo debe contener números.',
        'dni.unique' => 'Este DNI ya está registrado en el sistema.',

        'telefono.required' => 'El teléfono es obligatorio.',
        'telefono.string' => 'El teléfono debe ser una cadena de texto.',
        'telefono.max' => 'El teléfono no debe exceder los 15 caracteres.',
        'telefono.regex' => 'El teléfono solo debe contener números.',
    ];

    public function updatedAlumno($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function guardarAlumno()
    {
        $this->validate($this->rulesAlumno(), $this->messagesAlumno);

        $data = $this->only(['nombre', 'dni', 'telefono']);

        if ($this->modoEdicionAlumno) {
            $alumno = Alumno::find($this->alumnoId);
            $alumno->update($data);
            session()->flash('message', 'Alumno actualizado correctamente.');
        } else {
            // Crear alumno
            $alumno = Alumno::create($data);

            // Relación con el aula
            AlumnoAula::create([
                'alumno_id' => $alumno->id,
                'aula_id' => $this->aulaId,
            ]);

            // Registrar asistencias por sesiones pasadas
            $this->registrarAsistenciasFaltantes($alumno);

            session()->flash('message', 'Alumno creado correctamente y asistencias registradas.');
        }

        $this->limpiarAlumno();
        $this->dispatch('alumno-changed', 'Alumno guardado con éxito!');
    }

    public function registrarAsistenciasFaltantes($alumno)
    {
        $sesionesPasadas = Sesion::where('aula_id', $this->aulaId)
            ->where('fecha_fin', '<=', $alumno->created_at)
            ->get();

        foreach ($sesionesPasadas as $sesion) {
            $yaExiste = Asistencia::where('sesiones_id', $sesion->id)
                ->where('alumno_id', $alumno->id)
                ->exists();

            if (!$yaExiste) {
                Asistencia::create([
                    'sesiones_id' => $sesion->id,
                    'alumno_id' => $alumno->id,
                    'asistio' => 0, // Falta
                ]);
            }
        }
    }

    public function abrirModalAgregarAlumno()
    {
        $this->resetErrorBag();
        $this->limpiarAlumno();
        $this->modoEdicionAlumno = false;
        $this->modalAlumno = true; // si estás usando una propiedad para el modal
    }


    public function editarAlumno($id)
    {
        $alumno = Alumno::findOrFail($id);
        $this->alumnoId = $alumno->id;
        $this->nombre = $alumno->nombre;
        $this->dni = $alumno->dni;
        $this->telefono = $alumno->telefono;
        $this->modoEdicionAlumno = true;
    }

    public function confirmarEliminacionAlumno($id)
    {
        $this->alumnoId = $id;
        $this->modalAlumno = true; // Abrir el modal
    }

    public function eliminarAlumno($id)
    {
        // Elimina la relación en la tabla pivote
        AlumnoAula::where('alumno_id', $id)
            ->where('aula_id', $this->aulaId)
            ->delete();

        // Realiza el borrado lógico
        $alumno = Alumno::findOrFail($id);
        $alumno->active = false;
        $alumno->save();

        // Mensaje de notificación
        session()->flash('message', 'Alumno eliminado correctamente.');

        // Evento Livewire para actualizar vista
        $this->dispatch('alumno-changed', 'Alumno eliminado con éxito!');
    }

    public function limpiarAlumno()
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
        $aula = Aula::with(['sesiones' => function ($query) {
            $query->orderBy('fecha_inicio', 'desc');
        }])->findOrFail($this->aulaId);

        return view('livewire.admin.detalle-aula', [
            'aula' => $aula,
            'sesiones' => $aula->sesiones,
        ]);
    }


    public function abrirModalSesion()
    {
        $this->resetErrorBag();
        $this->limpiarSesion();
        $this->modalSesion = true; // si estás usando una propiedad para el modal
    }

    // Reglas de validación para sesiones
    protected $rulesSesion = [
        'titulo' => 'required|string|max:255',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        'link_reunion' => 'nullable|url',
    ];

    protected $messagesSesion = [
        'titulo.required' => 'El título de la sesión es obligatorio.',
        'titulo.string' => 'El título debe ser una cadena de texto.',
        'titulo.max' => 'El título no puede tener más de 255 caracteres.',

        'fecha_inicio.required' => 'La fecha de inicio de la sesión es obligatoria.',
        'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',

        'fecha_fin.required' => 'La fecha de fin de la sesión es obligatoria.',
        'fecha_fin.date' => 'La fecha de fin debe ser una fecha válida.',
        'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',

        'link_reunion.url' => 'El link de la reunión debe ser una URL válida. Ejemplo: https://meet.google.com',

    ];

    public function guardarSesion()
    {
        $this->validate($this->rulesSesion, $this->messagesSesion);

        // Crear la sesión
        $sesion = new Sesion();
        $sesion->titulo = $this->titulo;
        $sesion->fecha_inicio = $this->fecha_inicio;
        $sesion->fecha_fin = $this->fecha_fin;
        $sesion->aula_id = $this->aulaId;
        $sesion->link_reunion = $this->link_reunion;
        $sesion->link_asistencia = Str::random(32); // Genera un token único
        $sesion->save();

        // Obtener los alumnos del aula
        $aula = Aula::with('alumnos')->find($this->aulaId);

        // Crear un registro de asistencia por cada alumno
        foreach ($aula->alumnos as $alumno) {
            Asistencia::create([
                'sesiones_id' => $sesion->id,
                'alumno_id' => $alumno->id,
                'asistio' => false, // o null si lo llenan después
                'observacion' => null,
            ]);
        }

        // Cerrar modal, resetear y notificar
        $this->modalSesion = false;
        $this->reset(['titulo', 'fecha_inicio', 'fecha_fin']);

        session()->flash('message', 'La sesión y asistencias se han creado correctamente.');
        $this->dispatch('sesion-changed');
    }

    // Método para limpiar los campos de la sesión
    public function limpiarSesion()
    {
        $this->titulo = '';
        $this->fecha_inicio = '';
        $this->fecha_fin = '';
    }
}
