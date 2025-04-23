<div class="custom-px">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Aula {{ $aula->codigo }}
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

    <div class="bg-white rounded-lg shadow p-6 mt-6 ">
        <h3 class="text-lg font-bold mb-2">Datos del Aula</h3>
        <p>Información detallada del aula aquí.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 mt-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-2xl font-bold mb-2">ALUMNOS</h3>
            <p>Contenido para la primera mitad.</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold">SESIONES</h3>
                <div x-data="{ modalSesion: false, showNotification: false, timeout: null }" 
                x-on:sesion-changed.window="modalSesion = false; showNotification = true; clearTimeout(timeout); timeout = setTimeout(() => showNotification = false, 3000)">        
                    <div class="flex flex-col sm:flex-row sm:space-x-4">
                        <!-- Botón para abrir el modal -->
                        <button 
                            @click="$wire.call('abrirModalSesion').then(() => modalSesion = true)"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition flex flex-row items-center justify-center gap-2 text-sm" 
                            type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Nueva Sesion
                        </button>
                    </div>
                    <!-- Modal -->
                    <div x-show="modalSesion" 
                        x-transition 
                        class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 flex items-center justify-center z-50"
                        style="display: none;"
                        @keydown.escape.window="modalSesion = false"
                        @click.self="modalSesion = false"   
                        >
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <!-- Modal content -->
                            <div wire:key="sesion-form-add" class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                <!-- Modal header -->
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Crear Sesion                       
                                    </h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" @click="modalSesion = false">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <form class="p-4 md:p-5" wire:submit.prevent="guardarSesion" x-data>
                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                        <!-- Campo Título -->
                                        <div class="col-span-2">
                                            <label for="titulo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Título</label>
                                            <input wire:model.live="titulo" type="text" id="titulo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Ingrese el título">
                                            @error('titulo') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Campo Fecha Inicio -->
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="fecha_inicio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha Inicio</label>
                                            <input wire:model.live="fecha_inicio" type="datetime-local" id="fecha_inicio" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            @error('fecha_inicio') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Campo Fecha Fin -->
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="fecha_fin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha Fin</label>
                                            <input wire:model.live="fecha_fin" type="datetime-local" id="fecha_fin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            @error('fecha_fin') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                                            Guardar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="p-4 h-[700px] overflow-auto" > --}}

            <div class="p-4" >
                <ol class="relative border-s border-gray-200 dark:border-gray-700">                  
                    @forelse ($sesiones as $sesion)
                        <li class="mb-10 ms-4">
                            <div class="absolute w-3 h-3 bg-blue-500 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-900"></div>
                            <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">
                                {{ \Carbon\Carbon::parse($sesion->created_at)->format('M Y') }}
                            </time>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $sesion->titulo }}</h3>
                            <!-- Mostrar Fecha de Inicio y Fin -->
                            <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500 block">
                                Inicio: {{ \Carbon\Carbon::parse($sesion->fecha_inicio)->format('d M Y - h:i A') }} 
                            </time>
                            <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500 block">
                                Termino: {{ \Carbon\Carbon::parse($sesion->fecha_fin)->format('d M Y - h:i A') }}
                            </time>
                            <!-- Estado de la sesión -->
                            @php
                                $estado = '';
                                $fechaNow = \Carbon\Carbon::now();

                                if ($fechaNow->between($sesion->fecha_inicio, $sesion->fecha_fin)) {
                                    $estado = 'En transcurso';
                                } elseif ($fechaNow->gt($sesion->fecha_fin)) {
                                    $estado = 'Vencida';
                                } elseif ($fechaNow->lt($sesion->fecha_inicio)) {
                                    $estado = 'Programada';
                                }
                            @endphp

                            <div class="mt-2 text-sm font-semibold text-gray-500 dark:text-gray-400">
                                Estado: 
                                <span class="
                                    @if ($estado == 'En transcurso') text-green-600 @elseif ($estado == 'Vencida') text-red-600 @elseif ($estado == 'Programada') text-blue-600 @endif
                                ">
                                    {{ $estado }}
                                </span>
                            </div>
                        </li>
                    @empty
                        <li class="ms-4">
                            <p class="text-base font-medium text-gray-600 dark:text-gray-400">
                                No hay sesiones registradas para esta aula.
                            </p>
                        </li>
                    @endforelse
                </ol>
            </div>
        </div>
    </div>
</div>
