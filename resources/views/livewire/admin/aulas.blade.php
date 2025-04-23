<div class="custom-px">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Aulas') }}
        </h2>
    </x-slot>

    <div x-data="{ modalAula: false, showNotification: false, timeout: null }"
        x-on:aula-changed.window="modalAula = false; showNotification = true; clearTimeout(timeout); timeout = setTimeout(() => showNotification = false, 3000)">
        <div class="flex flex-col sm:flex-row sm:space-x-4">
            <!-- Botón para abrir el modal -->
            <button @click="$wire.call('abrirModalAgregar').then(() => modalAula = true)"
                class="my-5 block text-white text-xs md:text-md bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 sm:w-1/6 flex items-center justify-center space-x-2"
                type="button">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Agregar Aula
            </button>
            <div class="flex items-center sm:w-5/6">
                <label for="simple-search" class="sr-only">Search</label>
                <div class="relative w-full">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6 m-1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>

                    </div>
                    <input type="text" id="simple-search" wire:model.live="search"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mx-2"
                        placeholder="Buscar Aula" required />
                </div>
            </div>

        </div>
        <!-- Modal -->
        <div x-show="modalAula" x-transition
            class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 flex items-center justify-center z-50"
            style="display: none;" @keydown.escape.window="modalAula = false" @click.self="modalAula = false">
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
                            @click="modalAula = false">
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
                            <div x-data="programaSelect()" class="relative col-span-2">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seleccionar
                                    Programa</label>

                                <button @click="open = !open" type="button"
                                    class="w-full text-left bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg p-2.5">
                                    <span x-text="selectedName"></span>
                                </button>

                                <div x-show="open" @click.away="open = false"
                                    class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg">
                                    <input type="text" x-model="search" placeholder="Buscar programa..."
                                        class="w-full p-2 text-xs border-b dark:bg-gray-700 dark:text-white">
                                    <ul class="max-h-60 overflow-y-auto">
                                        <template x-for="p in filtered" :key="p.id">
                                            <li @click="select(p.id)"
                                                class="px-4 py-2 cursor-pointer hover:bg-blue-100 dark:hover:bg-gray-600">
                                                <span x-text="p.nombre" class="text-sm"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>

                                @error('programas_id')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="codigo"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Codigo</label>
                                <input wire:model.live="codigo" type="codigo" name="codigo" id="codigo"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Ingrese el codigo del aula.">
                                @error('codigo')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror

                            </div>

                            <div x-data="asesorSelect()" class="relative col-span-2">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Asignar
                                    Asesor</label>

                                <button @click="open = !open" type="button"
                                    class="w-full text-left bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg p-2.5">
                                    <span x-text="selectedName"></span>
                                </button>

                                <div x-show="open" @click.away="open = false"
                                    class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg">
                                    <input type="text" x-model="search" placeholder="Buscar asesor..."
                                        class="w-full p-2 text-xs border-b dark:bg-gray-700 dark:text-white">
                                    <ul class="max-h-60 overflow-y-auto">
                                        <template x-for="a in filtered" :key="a.id">
                                            <li @click="select(a.id, a.nombre)"
                                                class="px-4 py-2 cursor-pointer hover:bg-blue-100 dark:hover:bg-gray-600">
                                                <span x-text="a.nombre" class="text-sm"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>

                                @error('asesores_id')
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

        <div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg my-2">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 text-center">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('codigo')">
                                CODIGO
                                @if ($sortField === 'codigo')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('programas_id')">
                                PROGRAMA
                                @if ($sortField === 'programas_id')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('asesores_id')">
                                ASESOR
                                @if ($sortField === 'asesores_id')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('asesores_id')">
                                ALUMNOS
                                @if ($sortField === 'asesores_id')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th scope="col" class="px-6 py-3">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aulas as $a)
                            <tr
                                class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $a->codigo }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $a->programa->nombre }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $a->asesor->nombre }}
                                </td>
                                <td class="px-6 py-4">
                                    20
                                </td>
                                <td class="px-6 py-4 flex items-center gap-3 justify-center">
                                    <!-- Botón Editar -->
                                    <a href="{{ route('admin.detalle-aula', ['aulaId' => $a->id]) }}"
                                        class="text-green-600 dark:text-green-400 hover:text-green-700"
                                        title="Ver Detalles">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm7.5 0a9.005 9.005 0 0 1-16.737 4.5H2.25a.75.75 0 0 1-.75-.75V12a.75.75 0 0 1 .75-.75h3.513A9.005 9.005 0 0 1 12 3a9.005 9.005 0 0 1 8.987 8.25H21.75a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-.75.75h-3.513A9.005 9.005 0 0 1 22.5 12Z" />
                                        </svg>
                                    </a>
                                    <button
                                        @click="$wire.call('editar', {{ $a->id }}).then(() => modalAula = true)"
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
                                                    <button wire:click="eliminar({{ $a->id }})"
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
                @if ($aulas->count() > 15)
                    <div class="p-4">
                        {{ $aulas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>


</div>

<script>
    function asesorSelect() {
        return {
            open: false,
            search: '',
            selectedId: @entangle('asesores_id').live,
            asesores: @js($asesores),
            select(id, nombre) {
                this.selectedId = id;
                this.open = false;
            },
            get filtered() {
                if (this.search === '') return this.asesores;
                return this.asesores.filter(a => a.nombre.toLowerCase().includes(this.search.toLowerCase()));
            },

            get selectedName() {
                const found = this.asesores.find(a => a.id === this.selectedId);
                return found ? found.nombre : 'Elige un asesor';
            }
        }
    }

    function programaSelect() {
        return {
            open: false,
            search: '',
            selectedId: @entangle('programas_id').live,
            programas: @js($programas),
            get filtered() {
                if (this.search === '') return this.programas;
                return this.programas.filter(p => p.nombre.toLowerCase().includes(this.search.toLowerCase()));
            },
            select(id) {
                this.selectedId = id;
                this.open = false;
                Livewire.dispatch('input', {
                    name: 'programas_id',
                    value: id
                });
            },
            get selectedName() {
                const found = this.programas.find(p => p.id === this.selectedId);
                return found ? found.nombre : 'Elige un programa';
            }
        }
    }
</script>
