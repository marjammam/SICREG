<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('usuario')->insert([
            'nombreApellido' => 'Administrador',
            'email'          => 'admin@admin',
            'usuario'        => 'admin',
            'password'       => Hash::make('admin'),
            'rol'            => 'ADMINISTRADOR',
            'estado'         => 'ACTIVO',
        ]);
    }
}
