<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alumno_aula', function (Blueprint $table) {
            $table->id(); // opcional
            $table->foreignId('alumno_id')->constrained()->onDelete('cascade');
            $table->foreignId('aula_id')->constrained()->onDelete('cascade');
            $table->timestamps(); // opcional, por si quieres registrar cuándo se asignó
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumno_aula');
    }
};
