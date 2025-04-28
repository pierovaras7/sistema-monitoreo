<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    <!-- Livewire styles -->
    @livewireStyles

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="h-screen bg-gray-100">
        <!-- Header -->
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <img src="{{ asset($sesion->aula->programa->institucion === 'UPRIT' ? 'img/uprit.png' : 'img/upecen.png') }}"
                     alt="Logo Institución" class="h-12">
            </div>
            <div class="text-right">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $sesion->titulo }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Programa: {{ $sesion->aula->programa->nombre }} | Aula: {{ $sesion->aula->nombre }}
                </p>
            </div>
        </header>

        <!-- Page Content -->
        <div class="flex items-center justify-center bg-gray-100 dark:bg-gray-900"
            x-data="{ showNotification: false }"
            x-init="$wire.on('asistenciaGuardada', () => {
                showNotification = true;
                setTimeout(() => showNotification = false, 4000);
            })">
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
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                        clip-rule="evenodd"></path>
                            </svg>
                            Registrar Asistencia
                        </button>
                    </div>
                </form>
            </div>
            @if (session()->has('message') || session()->has('error'))
                <div x-show="showNotification" x-transition
                        class="fixed bottom-5 right-5 z-50 px-4 py-3 rounded-lg shadow-lg text-white flex items-center gap-2
                            {{ session()->has('error') ? 'bg-red-600' : 'bg-green-600' }}"
                        x-cloak>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="{{ session()->has('error') ? 'M6 18L18 6M6 6l12 12' : 'M9 12l2 2l4 -4M12 22c5.523 0 10 -4.477 10 -10S17.523 2 12 2S2 6.477 2 12s4.477 10 10 10z' }}" />
                    </svg>
                    <span>{{ session('message') ?? session('error') }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Livewire scripts -->
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>
