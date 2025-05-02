    <div x-data="{ modalSesion: false, showNotification: false, timeout: null }"
        x-on:cerrar-modal.window="modalSesion = false; showNotification = true; clearTimeout(timeout); timeout = setTimeout(() => showNotification = false, 3000)">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 custom-px">
            <div class="grid grid-cols-1">
                <!-- Columna 1, Fila 1: Título y botón -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 col-span-2 md:col-span-1">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $sesion->titulo }}
                    </h1>
                    <button @click="$wire.call('abrirModalAgregar').then(() => modalSesion = true)"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 rounded-lg dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800 transition-all"
                        type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Editar
                    </button>
                </div>

                <!-- Columna 2, Fila 1: Fechas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <p class="text-md text-gray-600">
                        <b>Inicio: </b> {{ \Carbon\Carbon::parse($sesion->fecha_inicio)->format('d/m/Y H:i') }}
                    </p>
                    <p class="text-md text-gray-600">
                        <b>Fin: </b> {{ \Carbon\Carbon::parse($sesion->fecha_fin)->format('d/m/Y H:i') }}
                    </p>

                    <!-- Columna 1, Fila 2: Primer bloque de URL -->
                    @if($sesion->link_reunion)
                    <div class="w-full max-w-sm mb-6">
                        <label for="link_reunion" class="text-sm font-medium text-gray-900 dark:text-white mb-2 block">URL de Reunión:</label>
                        <div class="flex items-center relative">
                            <span class="shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-s-lg dark:bg-gray-600 dark:text-white dark:border-gray-600">URL</span>
                            <input id="link_reunion" type="text" readonly disabled
                                class="bg-gray-50 border border-e-0 border-gray-300 text-gray-500 dark:text-gray-400 text-sm border-s-0 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                value="{{ $link_reunion }}" />
                            <div class="relative">
                                <button data-copy="link_reunion"
                                    class="copy-btn shrink-0 z-10 inline-flex items-center py-3 px-4 text-sm font-medium text-white bg-blue-700 rounded-e-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700"
                                    type="button">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 18 20">
                                        <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z" />
                                    </svg>
                                </button>
                                <div class="tooltip absolute bottom-full mb-2  text-white bg-black text-xs rounded px-2 py-1 hidden">
                                    Copiado!
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- Columna 2, Fila 2: Segundo bloque de URL duplicado -->
                    
                    
                    <div class="w-full max-w-sm">
                        <label for="link_asistencia" class="text-sm font-medium text-gray-900 dark:text-white mb-2 block">Link Asistencia (Compartir con Alumnos):</label>
                        <div class="flex items-center relative">
                            <span class="shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-s-lg dark:bg-gray-600 dark:text-white dark:border-gray-600">URL</span>
                            <input id="link_asistencia" type="text" readonly disabled
                                class="bg-gray-50 border border-e-0 border-gray-300 text-gray-500 dark:text-gray-400 text-sm border-s-0 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                value="{{ 'http://127.0.0.1:8000/asistencia/' . $sesion->link_asistencia }}" />
                            <button data-copy="link_asistencia"
                                class="copy-btn shrink-0 z-10 inline-flex items-center py-3 px-4 text-sm font-medium text-white bg-blue-700 rounded-e-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700"
                                type="button">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 18 20">
                                    <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z" />
                                </svg>
                            </button>
                            <div class="tooltip absolute bottom-full mb-2  text-white bg-black text-xs rounded px-2 py-1 hidden">
                                Copiado!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div x-show="modalSesion" x-transition
            class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 flex items-center justify-center z-50"
            style="display: none;" @keydown.escape.window="modalSesion = false" @click.self="modalSesion = false">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div wire:key="aula-form-{{ $modoEdicion ? 'edit' : 'add' }}"
                    class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <!-- Modal header -->
                    <div
                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $modoEdicion ? 'Actualizar aula' : 'Agregar aula' }}
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            @click="modalSesion = false">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form class="p-4 md:p-5" wire:submit.prevent="actualizarSesion" x-data="{ showError: false }">
                        <div class="grid gap-4 mb-4 grid-cols-2">

                            <!-- Campo Título -->
                            <div class="col-span-2">
                                <label for="titulo"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Título</label>
                                <input wire:model.live="titulo" type="text" id="titulo"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Ingrese el título">
                                @error('titulo')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Campo Fecha Inicio -->
                            <div class="col-span-2 sm:col-span-1">
                                <label for="fecha_inicio"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha
                                    Inicio</label>
                                <input wire:model.live="fecha_inicio" type="datetime-local" id="fecha_inicio"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('fecha_inicio')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Campo Fecha Fin -->
                            <div class="col-span-2 sm:col-span-1">
                                <label for="fecha_fin"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha
                                    Fin</label>
                                <input wire:model.live="fecha_fin" type="datetime-local" id="fecha_fin"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('fecha_fin')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Campo Link de Reunión (Opcional) -->
                            <div class="col-span-2">
                                <label for="link_reunion"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Link de Reunión
                                    (opcional)</label>
                                <input wire:model.defer="link_reunion" type="url" id="link_reunion"
                                    placeholder="https://tureunion.com/link"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                        focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5
                                        dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('link_reunion')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $modoEdicion ? 'Actualizar' : 'Guardar' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Notificación -->
        @if (session()->has('success'))
            <div x-show="showNotification" x-transition
                class="fixed bottom-5 right-5 bg-green-500 text-white p-4 rounded-lg shadow-lg flex items-center gap-2 z-50">
                <!-- Ícono SVG de éxito -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="white" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2l4 -4M12 22c5.523 0 10 -4.477 10 -10S17.523 2 12 2S2 6.477 2 12s4.477 10 10 10z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif


        <div class="custom-px">

            <!-- Notificación -->
            @if (session()->has('message'))
                <div x-show="showNotification" x-transition
                    class="fixed bottom-5 right-5 bg-green-500 text-white p-4 rounded-lg shadow-lg flex items-center gap-2 z-50">
                    <!-- Ícono SVG de éxito -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="white" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2l4 -4M12 22c5.523 0 10 -4.477 10 -10S17.523 2 12 2S2 6.477 2 12s4.477 10 10 10z" />
                    </svg>
                    <span>{{ session('message') }}</span>
                </div>
            @endif

            <div class="flex items-center justify-between mt-8 mb-4">
                <h2 class="font-semibold text-xl text-gray-800 uppercase">
                    Detalle de Asistencia
                </h2>
                <a href="{{ route('sesion.pdf', ['sesion' => $sesionId]) }}" target="_blank" class="flex gap-2 px-4 py-2 bg-red-500 text-white rounded hover:bg-blue-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    Ver PDF
                </a>
            </div>


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
                                {{-- @if ($sortField === 'nombre')
                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                    @endif --}}
                            </th>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('instituciones_id')">
                                NOMBRE COMPLETO
                                {{-- @if ($sortField === 'instituciones_id')
                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                    @endif --}}
                            </th>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('instituciones_id')">
                                TELEFONO
                                {{-- @if ($sortField === 'instituciones_id')
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
                        @foreach ($asistencias as $index => $asistencia)
                            <tr
                                class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                                <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $index + 1 }}
                                </td>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $asistencia->alumno->dni }}
                                </th>
                                <td class="px-6 py-4">

                                    {{ $asistencia->alumno->nombre }}
                                </td>
                                <td class="px-6 py-4">

                                    {{ $asistencia->alumno->telefono }}
                                </td>
                                <td class="px-6 py-4">
                                    <!-- Radio button para 'Asistió' -->
                                    <input type="radio" name="asistencia_{{ $asistencia->id }}"
                                        class="form-radio text-green-600"
                                        wire:change="actualizarAsistencia({{ $asistencia->id }}, true)" 
                                        {{ $asistencia->asistio ? 'checked' : '' }}>
                                </td>
                                <td class="px-6 py-4">
                                    <!-- Radio button para 'No Asistió' -->
                                    <input type="radio" name="asistencia_{{ $asistencia->id }}"
                                        class="form-radio text-red-600"
                                        wire:change="actualizarAsistencia({{ $asistencia->id }}, false)" 
                                        {{ !$asistencia->asistio ? 'checked' : '' }}>
                                </td>
                                

                                <td class="px-6 py-4">
                                    <div x-data="{ observacion: '{{ $asistencia->observacion }}', timeout: null }">
                                        <textarea x-model="observacion" 
                                                  x-on:input="clearTimeout(timeout); 
                                                             timeout = setTimeout(() => { 
                                                                 @this.call('guardarObservacion', {{ $asistencia->id }}, observacion); 
                                                             }, 2000)" 
                                                  class="form-textarea w-full p-2 border rounded-md text-xs" 
                                                  rows="3">
                                        </textarea>
                                    </div>
                                </td>
                                                               
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Paginación -->
                <div class="p-4">
                    {{ $asistencias->links() }}
                </div>
            </div>
        </div>
        <div x-data="{ showMessage: false, message: 'La asistencia se ha actualizado correctamente.' }"
            x-init="
                @this.on('asistenciaActualizada', (message) => {
                    showMessage = true;
                    setTimeout(() => {
                        showMessage = false;
                    }, 1000); 
                });
            ">
            <!-- Mensaje de confirmación -->
            <div x-show="showMessage" class="fixed bottom-4 right-4 bg-green-500 text-white py-2 px-4 rounded-md shadow-md">
                <span x-text="message"></span>
            </div>
        </div>
        <div x-data="{ showMessageO: false, messageO: 'La observacion se ha realizado correctamente.' }"
            x-init="
                @this.on('observacionActualizada', (messageO) => {
                    showMessageO = true;
                    setTimeout(() => {
                        showMessageO = false;
                    }, 1000); 
                });
            ">
            <!-- Mensaje de confirmación -->
            <div x-show="showMessageO" class="fixed bottom-4 right-4 bg-green-500 text-white py-2 px-4 rounded-md shadow-md">
                <span x-text="messageO"></span>
            </div>
        </div>

    </div>
    


</div>

<script>
    document.querySelectorAll('.copy-btn').forEach(button => {
        button.addEventListener('click', () => {
            const inputId = button.getAttribute('data-copy');
            const input = document.getElementById(inputId);
            const tooltip = button.parentElement.querySelector('.tooltip');

            navigator.clipboard.writeText(input.value).then(() => {
                tooltip.classList.remove('hidden');
                setTimeout(() => {
                    tooltip.classList.add('hidden');
                }, 2000);
            }).catch(err => {
                console.error('Error al copiar:', err);
            });
        });
    });

</script>
