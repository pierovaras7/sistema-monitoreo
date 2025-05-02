<div>
    <div 
    x-data="{ open: false }" 
    x-on:abrir-modal-errores.window="open = true" 
    @keydown.escape.window="open = false"
    >
        <div 
            x-show="open" 
            x-cloak
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
        >
            <div 
                class="bg-white rounded-lg p-4 max-w-lg w-full flex flex-col" 
                style="height: 400px;"
            >
                <h2 class="text-lg font-semibold mb-4">Errores de importación</h2>

                <div class="overflow-y-auto flex-1 pr-2">
                    <ul class="list-disc pl-5 text-sm text-red-600 space-y-2">
                        @php
                            $erroresPorFila = collect($errores)->groupBy('fila');
                        @endphp

                        @foreach ($erroresPorFila as $fila => $erroresFila)
                            <li>
                                <strong>Fila {{ $fila }}:</strong>
                                @php
                                    $mensajes = $erroresFila->map(function ($e) {
                                        $campo = ucfirst($e['campo']);
                                        $valor = $e['valores'][$e['campo']] ?? 'N/A';
                                        return "" . implode(', ', $e['errores']) . " (Valor: {$valor})";
                                    })->implode('; ');
                                @endphp
                                {{ $mensajes }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-4 text-right">
                    <button 
                        @click="open = false" 
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ open: false, message: '' }" x-on:mostrar-modal-exito.window="open = true; message = $event.detail.message">
        <div x-show="open" x-cloak x-transition class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-4 max-w-lg w-full">
                <h2 class="text-lg font-semibold mb-4 text-green-600">¡Éxito!</h2>
                <p class="text-sm">Alumnos importados correctamente.</p>
                <div class="mt-4 text-right">
                    <button @click="open = false" class="px-4 py-2 bg-green-500 text-white rounded">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    
    
    

    {{$archivo}}

    <!-- Formulario para subir el archivo -->
    <form>
        <div class="flex items-center">
            <label for="file-upload" class="bg-green-600 text-white py-2 px-4 rounded-md cursor-pointer text-sm flex gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 24 24">
                    <path d="M 12 3 L 2 5 L 2 19 L 12 21 L 12 3 z M 14 5 L 14 7 L 16 7 L 16 9 L 14 9 L 14 11 L 16 11 L 16 13 L 14 13 L 14 15 L 16 15 L 16 17 L 14 17 L 14 19 L 21 19 C 21.552 19 22 18.552 22 18 L 22 6 C 22 5.448 21.552 5 21 5 L 14 5 z M 18 7 L 20 7 L 20 9 L 18 9 L 18 7 z M 4.1757812 8.296875 L 5.953125 8.296875 L 6.8769531 10.511719 C 6.9519531 10.692719 7.0084063 10.902625 7.0664062 11.140625 L 7.0917969 11.140625 C 7.1247969 10.997625 7.1919688 10.779141 7.2929688 10.494141 L 8.3222656 8.296875 L 9.9433594 8.296875 L 8.0078125 11.966797 L 10 15.703125 L 8.2714844 15.703125 L 7.1582031 13.289062 C 7.1162031 13.204062 7.0663906 13.032922 7.0253906 12.794922 L 7.0097656 12.794922 C 6.9847656 12.908922 6.934375 13.079594 6.859375 13.308594 L 5.7363281 15.703125 L 4 15.703125 L 6.0605469 11.996094 L 4.1757812 8.296875 z M 18 11 L 20 11 L 20 13 L 18 13 L 18 11 z M 18 15 L 20 15 L 20 17 L 18 17 L 18 15 z"></path>
                </svg>
                Importar
            </label>
            <!-- Input de archivo con wire:model y wire:change -->
            <input id="file-upload" type="file" wire:model="archivo"  class="hidden" />
            @error('archivo') 
                <span class="text-red-500 text-xs">{{ $message }}</span> 
            @enderror
        </div>
    </form>
</div>

