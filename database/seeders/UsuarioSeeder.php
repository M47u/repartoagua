<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Administrador
        User::create([
            'name' => 'Admin',
            'apellido' => 'Sistema',
            'email' => 'admin@repartoagua.com',
            'password' => Hash::make('password'),
            'role' => 'administrador',
            'telefono' => '555-0001',
            'dni' => '12345678',
            'direccion' => 'Calle Principal 123',
            'ciudad' => 'Ciudad Principal',
            'fecha_ingreso' => now()->subYears(3),
            'activo' => true,
        ]);

        // Gerente
        User::create([
            'name' => 'Juan',
            'apellido' => 'Pérez',
            'email' => 'gerente@repartoagua.com',
            'password' => Hash::make('password'),
            'role' => 'gerente',
            'telefono' => '555-0002',
            'dni' => '23456789',
            'direccion' => 'Avenida Central 456',
            'ciudad' => 'Ciudad Principal',
            'fecha_ingreso' => now()->subYears(2),
            'fecha_nacimiento' => now()->subYears(35),
            'activo' => true,
        ]);

        // Administrativo
        User::create([
            'name' => 'María',
            'apellido' => 'González',
            'email' => 'admin1@repartoagua.com',
            'password' => Hash::make('password'),
            'role' => 'administrativo',
            'telefono' => '555-0003',
            'dni' => '34567890',
            'direccion' => 'Calle Comercio 789',
            'ciudad' => 'Ciudad Principal',
            'fecha_ingreso' => now()->subYear(),
            'fecha_nacimiento' => now()->subYears(28),
            'activo' => true,
        ]);

        // Choferes
        $choferes = [
            [
                'name' => 'Carlos',
                'apellido' => 'Rodríguez',
                'email' => 'chofer1@repartoagua.com',
                'dni' => '45678901',
                'telefono' => '555-1001',
            ],
            [
                'name' => 'Luis',
                'apellido' => 'Martínez',
                'email' => 'chofer2@repartoagua.com',
                'dni' => '56789012',
                'telefono' => '555-1002',
            ],
            [
                'name' => 'Roberto',
                'apellido' => 'Sánchez',
                'email' => 'chofer3@repartoagua.com',
                'dni' => '67890123',
                'telefono' => '555-1003',
            ],
        ];

        foreach ($choferes as $chofer) {
            User::create([
                'name' => $chofer['name'],
                'apellido' => $chofer['apellido'],
                'email' => $chofer['email'],
                'password' => Hash::make('password'),
                'role' => 'chofer',
                'telefono' => $chofer['telefono'],
                'dni' => $chofer['dni'],
                'direccion' => 'Zona Repartos',
                'ciudad' => 'Ciudad Principal',
                'fecha_ingreso' => now()->subMonths(rand(6, 18)),
                'fecha_nacimiento' => now()->subYears(rand(25, 45)),
                'activo' => true,
            ]);
        }

        // Repartidores
        $repartidores = [
            [
                'name' => 'Pedro',
                'apellido' => 'López',
                'email' => 'repartidor1@repartoagua.com',
                'dni' => '78901234',
                'telefono' => '555-2001',
            ],
            [
                'name' => 'José',
                'apellido' => 'García',
                'email' => 'repartidor2@repartoagua.com',
                'dni' => '89012345',
                'telefono' => '555-2002',
            ],
            [
                'name' => 'Miguel',
                'apellido' => 'Fernández',
                'email' => 'repartidor3@repartoagua.com',
                'dni' => '90123456',
                'telefono' => '555-2003',
            ],
        ];

        foreach ($repartidores as $repartidor) {
            User::create([
                'name' => $repartidor['name'],
                'apellido' => $repartidor['apellido'],
                'email' => $repartidor['email'],
                'password' => Hash::make('password'),
                'role' => 'repartidor',
                'telefono' => $repartidor['telefono'],
                'dni' => $repartidor['dni'],
                'direccion' => 'Zona Repartos',
                'ciudad' => 'Ciudad Principal',
                'fecha_ingreso' => now()->subMonths(rand(3, 12)),
                'fecha_nacimiento' => now()->subYears(rand(22, 40)),
                'activo' => true,
            ]);
        }
    }
}
