<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $asesorRole = Role::firstOrCreate(['name' => 'asesor', 'guard_name' => 'web']);

        // Crear usuarios
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@demo.com',
            'password' => Hash::make('admin123'),
        ]);
        $admin->assignRole($adminRole);

        $asesor = User::create([
            'name' => 'Asesor Juan',
            'email' => 'asesor@demo.com',
            'password' => Hash::make('asesor123'),
        ]);
        $asesor->assignRole($asesorRole);
    }
}

