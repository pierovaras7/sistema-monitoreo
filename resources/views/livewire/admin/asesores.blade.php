<div class="custom-px">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asesores') }}
        </h2>
    </x-slot>

    <div x-data="{ modalAsesor: false, showNotification: false, timeout: null }"
        x-on:programa-changed.window="modalAsesor = false; showNotification = true; clearTimeout(timeout); timeout = setTimeout(() => showNotification = false, 3000)">
        <div class="flex flex-col sm:flex-row sm:space-x-4">
            <!-- Botón para abrir el modal -->
            <button @click="$wire.call('abrirModalAgregar').then(() => modalAsesor = true)"
                class="my-5 block text-white text-xsmd:text-md bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 sm:w-1/6 flex items-center justify-center space-x-2"
                type="button">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Agregar Asesor
            </button>
            <div class="flex items-center sm:w-5/6">
                <label for="simple-search" class="sr-only">Search</label>
                <div class="relative w-full">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 4.5A2.5 2.5 0 0 1 6.5 7H20v13H6.5A2.5 2.5 0 0 1 4 17.5v-13z" />
                        </svg>
                    </div>
                    <input type="text" id="simple-search" wire:model.live="search"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Buscar Asesor" required />
                </div>
            </div>

        </div>
        <!-- Modal -->
        <div x-show="modalAsesor" x-transition
            class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 flex items-center justify-center z-50"
            style="display: none;" @keydown.escape.window="modalAsesor = false" @click.self="modalAsesor = false">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div wire:key="asesor-form-{{ $modoEdicion ? 'edit' : 'add' }}"
                    class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <!-- Modal header -->
                    <div
                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $modoEdicion ? 'Actualizar asesor' : 'Agregar asesor' }}
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            @click="modalAsesor = false">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form class="p-4 md:p-5" wire:submit.prevent="guardar" x-data="{ showError: false }">
                        <div class="grid gap-4 mb-4 grid-cols-2">
                            <!-- Nombre -->
                            <div class="col-span-2">
                                <label for="name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
                                <input wire:model.live="name" type="text" name="name" id="name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Ingrese el nombre del asesor.">
                                @error('name')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-span-2">
                                <label for="email"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                                <input wire:model.live="email" type="email" name="email" id="email"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Correo del asesor.">
                                @error('email')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- DNI -->
                            <div class="col-span-1">
                                <label for="dni"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">DNI</label>
                                <input wire:model.live="dni" type="text" name="dni" id="dni"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="DNI del asesor.">
                                @error('dni')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Teléfono -->
                            <div class="col-span-1">
                                <label for="telefono"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono</label>
                                <input wire:model.live="telefono" type="text" name="telefono" id="telefono"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Teléfono del asesor.">
                                @error('telefono')
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
        @if (session()->has('message'))
            <div x-show="showNotification" x-transition
                class="fixed bottom-5 right-5 bg-green-500 text-white p-4 rounded-lg shadow-lg z-50"
                x-text="'{{ session('message') }}'"></div>
        @endif

        <div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg my-2">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 text-center">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('name')">
                                NOMBRE DEL ASESOR
                                @if ($sortField === 'name')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('email')">
                                EMAIL
                                @if ($sortField === 'email')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('fecha_inicio')">
                                DNI
                                @if ($sortField === 'dni')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('fecha_fin')">
                                TELÈFONO
                                @if ($sortField === 'telefono')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th scope="col" class="px-6 py-3">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($asesores as $asesor)
                            <tr
                                class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $asesor->user->name }}
                                </th>
                                <td class="px-6 py-4">

                                    {{ $asesor->user->email }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $asesor->dni }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $asesor->telefono }}
                                </td>
                                <td class="px-6 py-4 flex items-center gap-3 justify-center">
                                    <!-- Botón Editar -->
                                    <button
                                        @click="$wire.call('editar', {{ $asesor->id }}).then(() => modalAsesor = true)"
                                        class="text-blue-600 dark:text-blue-500 hover:text-blue-700" title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>

                                    <!-- Botón Eliminar -->
                                    <!-- Botón de Mostrar Confirmación -->
                                    <div x-data="{ showModal: false }" class="flex justify-center">
                                        <!-- Botón que abre el modal -->
                                        <button @click="showModal = true"
                                            class="block bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                            type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>

                                        <!-- Modal de Confirmación de Eliminación -->
                                        <div x-show="showModal" x-transition
                                            @keydown.escape.window="showModal = false"
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                            <div @click.away="showModal = false"
                                                class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 w-full max-w-md">
                                                <button type="button" @click="showModal = false"
                                                    class="absolute top-2 right-2 text-gray-400 hover:text-gray-900 dark:hover:text-white">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293..."
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <p class="mb-4 text-gray-500 dark:text-gray-300 text-center">¿Estás
                                                    seguro de eliminar este registro?</p>

                                                <div class="flex justify-center gap-4">
                                                    <button @click="showModal = false"
                                                        class="py-2 px-3 text-sm font-medium text-gray-500 bg-white border rounded-lg hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                                                        No, cancelar
                                                    </button>
                                                    <button wire:click="eliminar({{ $asesor->id }})"
                                                        @click="showModal = false"
                                                        class="py-2 px-3 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 dark:bg-red-500">
                                                        Sí, estoy seguro
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Paginación -->
                @if ($asesores->count() > 15)
                    <div class="p-4">
                        {{ $asesores->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>


</div>
