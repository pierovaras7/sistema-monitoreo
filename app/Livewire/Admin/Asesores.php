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

    public function guardar()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email',
            'dni' => 'required|string|string|max:255',
            'telefono' => 'required|string|string|max:255',
        ]);

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


    public function abrirModal()
    {

        $this->limpiar();
        $this->modoEdicion = false;
        $this->modalAsesor = true;
    }


    public function editar($id)
    {
        $asesor = Asesor::findOrFail($id);
        $this->asesorId = $asesor->id;
        $this->name = $asesor->user->name;
        $this->email = $asesor->user->email;
        $this->dni = $asesor->dni;
        $this->telefono = $asesor->telefono;
        $this->modoEdicion = true;
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
