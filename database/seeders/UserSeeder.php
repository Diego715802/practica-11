<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tu cuenta oficial de Administrador (Acceso total)
        User::create([
            'name' => 'Juan Diego',
            'email' => 'admin@tienda.com',
            'password' => Hash::make('password123'),
            'rol' => 'admin'
        ]);

        // 2. Cuenta de Editor (Puede crear/editar, pero NO eliminar)
        User::create([
            'name' => 'Editor Prueba',
            'email' => 'editor@tienda.com',
            'password' => Hash::make('password123'),
            'rol' => 'editor'
        ]);

        // 3. Cuenta de Cliente (Solo puede ver el catálogo)
        User::create([
            'name' => 'Cliente Prueba',
            'email' => 'cliente@tienda.com',
            'password' => Hash::make('password123'),
            'rol' => 'cliente'
        ]);
    }
}
