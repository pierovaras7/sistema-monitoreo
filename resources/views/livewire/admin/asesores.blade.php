<div class="p-4">
    <h1 class="text-xl font-bold mb-4">Gestión de Asesores</h1>

    <button wire:click="create" class="bg-green-600 text-white px-4 py-2 rounded mb-4">
        Agregar Asesor
    </button>

    <!-- Tabla de Asesores -->
    <table class="w-full text-left">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>DNI</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asesores as $asesor)
                <tr>
                    <td>{{ $asesor->user->name }}</td>
                    <td>{{ $asesor->user->email }}</td>
                    <td>{{ $asesor->dni }}</td>
                    <td>{{ $asesor->telefono }}</td>
                    <td>
                        <button wire:click="edit({{ $asesor->id }})" class="text-blue-600">Editar</button>
                        <button wire:click="delete({{ $asesor->id }})" class="text-red-600 ml-2">Eliminar</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal para Crear/Editar Asesor -->
    <div 
    x-data 
    x-show="$wire.formMode !== 'none'" 
    @keydown.escape.window="$wire.set('formMode', 'none')" 
    x-transition 
    class="fixed inset-0 flex items-center justify-center z-50 bg-gray-800 bg-opacity-50"
>
    <div class="bg-white p-6 rounded-lg w-full sm:w-96">
        <h2 class="text-2xl font-bold mb-4">
            <span x-text="$wire.formMode === 'create' ? 'Agregar Asesor' : 'Editar Asesor'"></span>
        </h2>

        <form wire:submit.prevent="{{ $formMode === 'create' ? 'store' : 'update' }}">
            <input type="text" wire:model="nombre" placeholder="Nombre" class="mb-2 w-full" />
            <input type="email" wire:model="email" placeholder="Correo" class="mb-2 w-full" />
            <input type="text" wire:model="telefono" placeholder="Teléfono" class="mb-2 w-full" />
            <input type="text" wire:model="dni" placeholder="DNI" class="mb-2 w-full" />

            <div class="flex justify-end mt-4">
                <button type="button" @click="$wire.set('formMode', 'none')" class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2">Cancelar</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    {{ $formMode === 'create' ? 'Agregar' : 'Actualizar' }}
                </button>
            </div>
        </form>
    </div>
</div>



</div>
