<?php

use App\Livewire\Admin\Asesores;
use App\Livewire\Admin\Aulas;
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
});