<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsuarioSeeder::class, // Primero usuarios (incluye choferes)
            VehiculoSeeder::class, // Luego vehículos (asigna choferes)
            ClienteSeeder::class,
            ProductoSeeder::class,
        ]);
    }
}
