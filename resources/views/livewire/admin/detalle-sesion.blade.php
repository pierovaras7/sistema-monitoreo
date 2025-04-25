<div class="custom-px">
    <x-slot name="header">
            <div class="flex flex-row justify-between items-center">
                <h1 class="font-semibold text-3xl text-gray-800 leading-tight">
                    {{ $sesion->titulo }} {{$titulo}}
                </h1>
                
                    
                <div x-data="{ open: false }"      x-on:abrir-modal.window="open = true"> <!-- Escuchar el evento 'abrir-modal' de Livewire -->

    <!-- Título y botón de edición -->
    <div class="flex flex-row justify-between items-center">
        <h1 class="font-semibold text-3xl text-gray-800 leading-tight">
            {{ $sesion->titulo }}
        </h1>
        <button @click="$wire.call('editar')" 
                class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-md shadow">
            Editar
        </button>
    </div>

    <!-- Modal -->
    <div x-show="open" x-transition class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.away="open = false"
             class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-lg">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Editar Sesión</h2>
            <form wire:submit.prevent="actualizarSesion">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-white">Título</label>
                        <input type="text" wire:model="titulo"
                               class="w-full mt-1 p-2 border rounded dark:bg-gray-700 dark:text-white" />
                        @error('titulo') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md shadow">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

            </div>
            
    
            <p class="text-md text-gray-600 my-2">
                <b>Inicio: </b> {{ \Carbon\Carbon::parse($sesion->fecha_inicio)->format('d/m/Y H:i') }}
            </p>
            <p class="text-md text-gray-600 my-2">
                <b>Fin: </b> {{ \Carbon\Carbon::parse($sesion->fecha_fin)->format('d/m/Y H:i') }}
            </p>
    
            @if($sesion->link_reunion)
                <!-- Aquí puedes mantener tu bloque del link con copiar -->
                <div class="mt-2">[...Tu bloque de link con botón copiar aquí...]</div>
            @endif            
    
        
         
        </div>
        
        </div>
    </x-slot>
    
    

    <!-- Notificación -->
    @if (session()->has('message'))
        <div x-show="showNotification"
            x-transition
            class="fixed bottom-5 right-5 bg-green-500 text-white p-4 rounded-lg shadow-lg flex items-center gap-2 z-50"
        >
            <!-- Ícono SVG de éxito -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4 -4M12 22c5.523 0 10 -4.477 10 -10S17.523 2 12 2S2 6.477 2 12s4.477 10 10 10z" />
            </svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-8 mb-4 uppercase">
        Detalle de Asistencia
    </h2>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-gray-500 dark:text-gray-400 text-center">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th rowspan="2" class="px-4 py-3">#</th>
                    <th colspan="3" class="px-6 py-3">Alumno</th>
                    <th colspan="2" class="px-6 py-3">Asistencia</th>
                    <th rowspan="2" colspan="2" class="px-6 py-3">Observaciones</th>
                </tr>
                <tr>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('nombre')">
                        DNI
                        {{-- @if($sortField === 'nombre')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif --}}
                    </th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('instituciones_id')">
                        NOMBRE COMPLETO
                        {{-- @if($sortField === 'instituciones_id')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif --}}
                    </th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('instituciones_id')">
                        TELEFONO
                        {{-- @if($sortField === 'instituciones_id')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif --}}
                    </th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('fecha_inicio')">
                        ASISTIO
                    </th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('fecha_fin')">
                        FALTA
                    </th>
                    </tr>
                </thead>
            <tbody>
                @foreach ($sesion->asistencias as $index => $asistencia)
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                        <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $index + 1 }}
                        </td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $asistencia->alumno->dni }}
                        </th>
                        <td class="px-6 py-4">
                        
                            {{ $asistencia->alumno->nombre }}
                        </td>
                        <td class="px-6 py-4">
                        
                            {{ $asistencia->alumno->telefono }}
                        </td>
                        <td class="px-6 py-4">
                            <input type="radio"
                                name="asistencia_{{ $asistencia->id }}"
                                class="form-radio text-green-600"
                                wire:change="actualizarAsistencia({{ $asistencia->id }}, true)"
                                {{ $asistencia->asistio ? 'checked' : '' }}>
                        </td>
                        <td class="px-6 py-4">
                            <input type="radio"
                                name="asistencia_{{ $asistencia->id }}"
                                class="form-radio text-red-600"
                                wire:change="actualizarAsistencia({{ $asistencia->id }}, false)"
                                {{ !$asistencia->asistio ? 'checked' : '' }}>
                        </td>

                        <td class="px-6 py-4">
                            {{ $asistencia->observaciones }}sads
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Paginación -->
        @if($sesion->asistencias->count() > 15)
            <div class="p-4">
                {{ $sesion->asistencias->links() }}
            </div>
        @endif
    </div>
</div>