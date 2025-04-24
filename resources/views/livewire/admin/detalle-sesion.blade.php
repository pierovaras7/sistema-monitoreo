<div class="custom-px">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            REGISTRO ASISTENCIA - {{ $sesion->titulo }}
        </h2>
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

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg my-2">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 text-center">
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
                        ALUMNO
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