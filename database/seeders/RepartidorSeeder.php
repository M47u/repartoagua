<?php

namespace Database\Seeders;

use App\Models\Repartidor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RepartidorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $repartidores = [
            ['nombre' => 'Pedro', 'apellido' => 'Sánchez', 'telefono' => '5598765432', 'licencia' => 'A12345678', 'vehiculo' => 'Nissan NP300 2020', 'activo' => true],
            ['nombre' => 'José', 'apellido' => 'Hernández', 'telefono' => '5587654321', 'licencia' => 'B23456789', 'vehiculo' => 'Toyota Hilux 2019', 'activo' => true],
            ['nombre' => 'Manuel', 'apellido' => 'González', 'telefono' => '5576543210', 'licencia' => 'C34567890', 'vehiculo' => 'Chevrolet Tornado 2021', 'activo' => true],
            ['nombre' => 'Francisco', 'apellido' => 'Díaz', 'telefono' => '5565432109', 'licencia' => 'D45678901', 'vehiculo' => 'Ford Ranger 2018', 'activo' => true],
            ['nombre' => 'Rafael', 'apellido' => 'Torres', 'telefono' => '5554321098', 'licencia' => 'E56789012', 'vehiculo' => 'RAM 700 2022', 'activo' => true],
        ];

        foreach ($repartidores as $repartidor) {
            Repartidor::create($repartidor);
        }
    }
}
