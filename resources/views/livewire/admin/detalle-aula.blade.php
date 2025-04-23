<div class="custom-px">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Aulas') }}
        </h2>
    </x-slot>

    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h3 class="text-lg font-bold mb-2">Datos del Aula</h3>
        <p>Información detallada del aula aquí.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 mt-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-2">ALUMNOS</h3>
            <p>Contenido para la primera mitad.</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-2">SESIONES</h3>
            <p>Contenido para la segunda mitad.</p>
        </div>
    </div>
</div>
