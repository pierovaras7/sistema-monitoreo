<?php

use App\Livewire\Admin\Alumnos;
use App\Livewire\Admin\Asesores;
use App\Livewire\Admin\Asistencias;
use App\Livewire\Admin\Aulas;
use App\Livewire\Admin\DetalleAula;
use App\Livewire\Admin\DetalleSesion;
use App\Livewire\Admin\Instituciones;
use App\Livewire\Admin\Programas;
use Illuminate\Support\Facades\Route;



Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/asesores', Asesores::class)->name('admin.asesores');
    Route::get('/admin/programas', Programas::class)->name('admin.programas');
    Route::get('/admin/aulas', Aulas::class)->name('admin.aulas');
    Route::get('/admin/detalle-aula/{aulaId}', DetalleAula::class)->name('admin.detalle-aula');

    Route::get('/admin/instituciones', Instituciones::class)->name('admin.instituciones');
    Route::get('/admin/alumnos', Alumnos::class)->name('admin.alumnos');
    Route::get('/admin/sesiones/{sesionId}', DetalleSesion::class)->name('admin.detalle-sesion');
    
});

Route::get('/asistencia/{uuid}', Asistencias::class)->name('asistencia.temporal');


// Route::get('/asistencias/{uuid}', function ($uuid) {
//     // Buscar la sesión por el campo link_asistencia
//     $sesion = Sesion::where('link_asistencia', $uuid)->firstOrFail();

//     $now = Carbon::now('America/Lima');

//     if (!$now->between($sesion->fecha_inicio, $sesion->fecha_fin)) {
//         abort(403, 'Esta sesión no está activa en este momento.');
//     }

//     return view('livewire.asistencia', compact('sesion'));
// })->name('asistencia.temporal');


