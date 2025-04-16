<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Crear los roles utilizando las columnas correctas
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'asesor', 'guard_name' => 'web']);
    }
    
}
