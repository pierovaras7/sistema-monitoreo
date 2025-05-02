<div class="h-screen bg-gray-100 flex flex-col">
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <img src="{{ asset($sesion->aula->programa->institucion === 'UPRIT' ? 'img/uprit.jpeg' : 'img/upecen.png') }}"
                    alt="Logo Institución" class="h-44 w-full object-cover">
        </div>
        <div class="text-center space-y-3 py-3">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">{{ $sesion->titulo }}</h1>
            <p class="text-md text-gray-600 dark:text-gray-300">
                <b>Programa:</b> {{ $sesion->aula->programa->nombre }}
            </p>
            <p class="text-md text-gray-600 dark:text-gray-300">
                <b>Aula:</b> {{ $sesion->aula->codigo }}
            </p>
        </div>
    </header>
    <div class="flex-1 flex items-center justify-center py-10 relative overflow-hidden">
        <div class="absolute inset-0" style="background-image: url('{{ asset($sesion->aula->programa->institucion === 'UPRIT' ? 'img/uprit.jpeg' : 'img/upecen.png') }}'); background-size: cover; background-position: center; filter: blur(8px);" class="z-0"></div>
        <div class="relative z-10">
            <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-md rounded-xl p-6 space-y-6">
                <h2 class="text-center text-2xl font-bold text-gray-800 dark:text-white">
                    Registrar Asistencia
                </h2>

                <form wire:submit.prevent="guardarAsistencia" class="space-y-4">
                    <div>
                        
                        <label for="dni" class="block text-sm font-medium text-gray-700 dark:text-gray-300">DNI</label>
                        <input wire:model.live="dni" type="text" name="dni" id="dni"
                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-400 dark:focus:border-blue-400"
                            placeholder="Ingrese su DNI">
                        @error('dni')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
    
                    <div class="flex justify-center">
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg shadow focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                                    </svg>
                                    
                            Registrar Asistencia
                        </button>
                    </div>
                </form> 
            </div>
        </div>
    </div>
    @if (session()->has('message') || session()->has('error') || session()->has('info'))
    <div 
        x-data="{ showAlert: false, alertMessage: '', alertType: '' }"
        x-init="
            @if (session('message')) 
                showAlert = true;
                alertMessage = '{{ session('message') }}';
                alertType = 'success';
            @elseif (session('error'))
                showAlert = true;
                alertMessage = '{{ session('error') }}';
                alertType = 'error';
            @elseif (session('info'))
                showAlert = true;
                alertMessage = '{{ session('info') }}';
                alertType = 'info';
            @endif
        "
        x-show="showAlert" x-transition
        class="fixed inset-0 flex items-center justify-center z-50"
        @click.outside="showAlert = false"
    >
    
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    
        <!-- Alert Box -->
        <div 
            x-show="showAlert"
            :class="alertType === 'error' 
                ? 'bg-red-50 text-red-800 border-red-300'
                : alertType === 'info' 
                ? 'bg-blue-50 text-blue-800 border-blue-300'
                : 'bg-green-50 text-green-800 border-green-300'"
            class="p-4 border rounded-lg dark:text-white dark:bg-gray-800 dark:border-gray-800 relative max-w-lg w-full z-10"
        >
            <div class="flex items-center">
                <!-- Error Icon -->
                <svg x-show="alertType === 'error'" class="shrink-0 w-5 h-5 me-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
    
                <!-- Success Icon -->
                <svg x-show="alertType === 'success'" class="shrink-0 w-5 h-5 me-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2l4 -4M12 22c5.523 0 10 -4.477 10 -10S17.523 2 12 2S2 6.477 2 12s4.477 10 10 10z"/>
                </svg>
    
                <!-- Info Icon -->
                <svg x-show="alertType === 'info'" class="shrink-0 w-5 h-5 me-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm.75 15h-1.5v-6h1.5v6Zm0-8h-1.5V7h1.5v2Z"/>
                </svg>
    
                <span class="sr-only">Alert</span>
                <h3 class="text-lg font-medium" 
                    x-text="alertType === 'error' ? 'Error' : alertType === 'info' ? 'Información' : 'Éxito'">
                </h3>
            </div>
    
            <!-- Message Content -->
            <div class="mt-2 mb-4 text-sm">
                <span x-text="alertMessage"></span>
            </div>
    
            <!-- Close Button -->
            <div class="flex">
                <button 
                    type="button" 
                    @click="showAlert = false" 
                    :class="alertType === 'error' 
                        ? 'text-white bg-red-800 hover:bg-red-900 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800'
                        : alertType === 'info' 
                        ? 'text-white bg-blue-800 hover:bg-blue-900 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800'
                        : 'text-white bg-green-800 hover:bg-green-900 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800'"
                    class="font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cerrar
                </button>
            </div>
        </div>
    </div>
    @endif
    
</div>
