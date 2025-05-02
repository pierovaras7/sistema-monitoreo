<?php

use App\Livewire\Admin\Alumnos;
use App\Livewire\Admin\Asesores;
use App\Livewire\Admin\Asistencias;
use App\Livewire\Admin\Aulas;
use App\Livewire\Admin\DetalleAula;
use App\Livewire\Admin\DetalleSesion;
use App\Livewire\Admin\Instituciones;
use App\Livewire\Admin\Programas;
use App\Models\Asistencia;
use App\Models\Sesion;
use Barryvdh\DomPDF\Facade\Pdf;
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

Route::middleware(['auth', 'role:asesor'])->group(function () {
    Route::get('/asesor/aulas', Aulas::class)->name('asesor.aulas');
    Route::get('/asesor/detalle-aula/{aulaId}', DetalleAula::class)->name('asesor.detalle-aula');
    Route::get('/asesor/sesiones/{sesionId}', DetalleSesion::class)->name('asesor.detalle-sesion');
});

Route::get('/admin/sesiones/{sesion}/pdf', function ($sesion) {
    $sesion = Sesion::findOrFail($sesion); // Obtener la sesión
    $asistencias = Asistencia::where('sesiones_id', $sesion->id)->get(); // Obtener asistencias relacionadas

    $pdf = Pdf::loadView('pdf.asistencias', compact('asistencias', 'sesion'));

    return $pdf->stream('asistencias.pdf'); // Para abrir en el navegador en vez de descargar
})->name('sesion.pdf');


Route::get('/asistencia/{uuid}', Asistencias::class)->name('asistencia.temporal');

