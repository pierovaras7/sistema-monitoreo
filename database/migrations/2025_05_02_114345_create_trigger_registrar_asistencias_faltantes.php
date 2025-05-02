<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER trigger_registrar_asistencias_faltantes
            AFTER INSERT ON alumno_aula
            FOR EACH ROW
            BEGIN
                INSERT INTO asistencias (sesiones_id, alumno_id, asistio, created_at, updated_at)
                SELECT s.id, NEW.alumno_id, 0, NOW(), NOW()
                FROM sesiones s
                LEFT JOIN asistencias a ON a.sesiones_id = s.id AND a.alumno_id = NEW.alumno_id
                WHERE s.aula_id = NEW.aula_id AND a.id IS NULL;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_registrar_asistencias_faltantes');
    }
};
