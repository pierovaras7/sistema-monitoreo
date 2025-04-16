<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Asesor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Asesores extends Component
{
    public $nombre, $email, $telefono, $dni, $asesor_id;
    public $formMode = 'none'; // Cambia a 'create' o 'edit' cuando corresponda

    public function create()
    {
        $this->resetForm();
        $this->formMode = 'create';
    }


    public function render()
    {
        return view('livewire.admin.asesores', [
            'asesores' => Asesor::with('user')->latest()->get()
        ]);
    }

    // Almacenar un nuevo asesor
    public function store()
    {
        $user = User::create([
            'name' => $this->nombre,
            'email' => $this->email,
            'password' => bcrypt('password') // Puedes agregar un campo de contraseña si lo deseas
        ]);

        Asesor::create([
            'user_id' => $user->id,
            'telefono' => $this->telefono,
            'dni' => $this->dni,
        ]);

        $this->resetForm();
        $this->emit('alert', 'Asesor agregado exitosamente.');
    }

    // Editar un asesor
    public function edit($id)
    {
        $asesor = Asesor::findOrFail($id);
        $this->nombre = $asesor->user->name;
        $this->email = $asesor->user->email;
        $this->telefono = $asesor->telefono;
        $this->dni = $asesor->dni;
        $this->asesor_id = $asesor->id;
        $this->formMode = 'edit'; // Cambiar el modo a editar
    }

    // Actualizar un asesor
    public function update()
    {
        $asesor = Asesor::findOrFail($this->asesor_id);
        $asesor->user->update([
            'name' => $this->nombre,
            'email' => $this->email,
        ]);
        $asesor->update([
            'telefono' => $this->telefono,
            'dni' => $this->dni,
        ]);

        $this->resetForm();
        $this->emit('alert', 'Asesor actualizado exitosamente.');
    }

    // Resetear el formulario
    private function resetForm()
    {
        $this->nombre = '';
        $this->email = '';
        $this->telefono = '';
        $this->dni = '';
        $this->asesor_id = null;
        $this->formMode = 'none';
    }

    // Eliminar un asesor
    public function delete($id)
    {
        Asesor::find($id)->delete();
        $this->emit('alert', 'Asesor eliminado exitosamente.');
    }
}

