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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 flex items-center justify-center h-screen text-md font-sans text-gray-900 antialiased">
        <div class="w-full max-w-4xl bg-white shadow-lg rounded-lg overflow-hidden flex md:min-h-[600px]">
            <!-- Imagen -->
            <div class="relative hidden md:block md:w-1/2">
                <img src="/img/banner-monitoreo.jpg" alt="Imagen Login" class="w-full h-full object-cover object-[80%_center]">
            </div>
            <!-- Formulario -->
            <div class="w-full md:w-1/2 p-8 flex flex-col justify-center bg-white shadow-md sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
