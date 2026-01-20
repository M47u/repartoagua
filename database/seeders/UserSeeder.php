<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@repartoagua.com',
            'password' => Hash::make('password'),
            'role' => 'administrador',
            'email_verified_at' => now(),
        ]);

        // Administrativo
        User::create([
            'name' => 'Administrativo',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'administrativo',
            'email_verified_at' => now(),
        ]);

        // Repartidores
        User::create([
            'name' => 'Repartidor 1',
            'email' => 'repartidor1@example.com',
            'password' => Hash::make('password'),
            'role' => 'repartidor',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Repartidor 2',
            'email' => 'repartidor2@example.com',
            'password' => Hash::make('password'),
            'role' => 'repartidor',
            'email_verified_at' => now(),
        ]);
    }
}
