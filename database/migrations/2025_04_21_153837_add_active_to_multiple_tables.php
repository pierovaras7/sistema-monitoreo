<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiveToMultipleTables extends Migration
{
    public function up()
    {
        Schema::table('alumnos', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });

        Schema::table('asesores', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });

        Schema::table('aulas', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });

        Schema::table('instituciones', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });

        Schema::table('programas', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });

        Schema::table('sesiones', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });
    }

    public function down()
    {
        Schema::table('alumnos', function (Blueprint $table) {
            $table->dropColumn('active');
        });

        Schema::table('asesores', function (Blueprint $table) {
            $table->dropColumn('active');
        });

        Schema::table('aulas', function (Blueprint $table) {
            $table->dropColumn('active');
        });

        Schema::table('instituciones', function (Blueprint $table) {
            $table->dropColumn('active');
        });

        Schema::table('programas', function (Blueprint $table) {
            $table->dropColumn('active');
        });

        Schema::table('sesiones', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
}
