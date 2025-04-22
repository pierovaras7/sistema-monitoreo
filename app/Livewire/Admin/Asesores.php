<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Asesor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Asesores extends Component
{
    public $name, $email, $dni, $telefono, $asesorId;
    public $modoEdicion = false;
    public $modalAsesor;
    public string $search = '';
    public $sortField = 'name'; // Por defecto, ordenamos por nombre
    public $sortDirection = 'asc'; // Ascendente por defecto

    public function render()
    {
        $userFields = ['name', 'email'];
        $asesorFields = ['telefono', 'dni'];

        // Construimos la query
        $query = Asesor::join('users', 'asesores.user_id', '=', 'users.id')
            ->where('asesores.active', true)
            ->where('users.name', 'like', '%' . $this->search . '%')
            ->select('asesores.*')
            ->with('user');

        // Elegimos correctamente el campo por el cual ordenar
        if (in_array($this->sortField, $userFields)) {
            $sortColumn = "users.{$this->sortField}";
        } elseif (in_array($this->sortField, $asesorFields)) {
            $sortColumn = "asesores.{$this->sortField}";
        } else {
            $sortColumn = "users.name"; // valor por defecto en caso de error
        }

        $asesores = $query->orderBy($sortColumn, $this->sortDirection)->paginate(15);


        return view('livewire.admin.asesores', compact('asesores'));
    }

    // Reglas de validación y mensajes personalizados en un solo lugar
    public function rules()
    {
        return [
            'name' => 'required|string|max:255||regex:/^[\pL\s]+$/u',
            'email' => 'required|email|max:255',
            'dni' => 'required|string|size:8|regex:/^[0-9]+$/', // Asumiendo DNI de 8 dígitos
            'telefono' => 'required|string|max:15|regex:/^[0-9]+$/', // Puedes ajustar el max según tus necesidades
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe exceder los 255 caracteres.',
            'name.regex' => 'El nombre solo debe contener letras y espacios.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe proporcionar un correo electrónico válido.',
            'email.max' => 'El correo electrónico no debe exceder los 255 caracteres.',

            'dni.required' => 'El DNI es obligatorio.',
            'dni.string' => 'El DNI debe ser una cadena de texto.',
            'dni.size' => 'El DNI debe tener exactamente 8 caracteres.',
            'dni.regex' => 'El DNI solo debe contener números.',

            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.string' => 'El teléfono debe ser una cadena de texto.',
            'telefono.max' => 'El teléfono no debe exceder los 15 caracteres.',
            'telefono.regex' => 'El teléfono solo debe contener números.',
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

        if ($this->modoEdicion) {
            $asesor = Asesor::with('user')->find($this->asesorId);
            if ($asesor) {
                // Actualiza datos del usuario
                $asesor->user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                ]);

                // Actualiza datos del asesor
                $asesor->update([
                    'telefono' => $this->telefono,
                    'dni' => $this->dni,
                ]);

                session()->flash('message', 'Asesor actualizado correctamente.');
            }
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt('password'), // Puedes personalizar esto
            ]);

            Asesor::create([
                'user_id' => $user->id,
                'telefono' => $this->telefono,
                'dni' => $this->dni,
                'active' => true, // Si es necesario por tu lógica
            ]);

            session()->flash('message', 'Asesor creado correctamente.');
        }

        $this->limpiar();
        $this->dispatch('asesor-changed', 'Asesor guardado con éxito!');
    }


    public function abrirModalAgregar()
    {
        $this->resetErrorBag();
        $this->limpiar();
        $this->modoEdicion = false;
        $this->modalAsesor = true; // si estás usando una propiedad para el modal
    }


    public function editar($id)
    {
        $this->resetErrorBag();
        $asesor = Asesor::findOrFail($id);
        $this->asesorId = $asesor->id;
        $this->name = $asesor->user->name;
        $this->email = $asesor->user->email;
        $this->dni = $asesor->dni;
        $this->telefono = $asesor->telefono;
        $this->modoEdicion = true;
    }

    public function confirmarEliminacion($id)
    {
        $this->asesorId = $id;
        $this->modalAsesor = true; // Abrir el modal
    }


    public function eliminar($id)
    {
        // Encuentra el programa por ID
        $asesor = Asesor::findOrFail($id);

        // Realiza el borrado lógico
        $asesor->active = false;
        $asesor->save();

        // Mensaje de notificación
        session()->flash('message', 'Asesor eliminado correctamente.');

        // Limpiar datos si es necesario (opcional)
        $this->dispatch('asesor-changed', 'Asesor eliminado con éxito!');
    }


    public function limpiar()
    {
        $this->reset(['name', 'email', 'dni', 'telefono']);
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
