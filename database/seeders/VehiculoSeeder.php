<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehiculo;
use App\Models\User;

class VehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehiculos = [
            [
                'placa' => 'ABC-123',
                'marca' => 'Toyota',
                'modelo' => 'Hilux',
                'año' => 2022,
                'color' => 'Blanco',
                'tipo' => 'camioneta',
                'capacidad_carga' => 1000,
                'capacidad_bidones' => 50,
                'estado' => 'disponible',
            ],
            [
                'placa' => 'DEF-456',
                'marca' => 'Nissan',
                'modelo' => 'Frontier',
                'año' => 2021,
                'color' => 'Azul',
                'tipo' => 'camioneta',
                'capacidad_carga' => 900,
                'capacidad_bidones' => 45,
                'estado' => 'disponible',
            ],
            [
                'placa' => 'GHI-789',
                'marca' => 'Mitsubishi',
                'modelo' => 'L200',
                'año' => 2023,
                'color' => 'Negro',
                'tipo' => 'camioneta',
                'capacidad_carga' => 1100,
                'capacidad_bidones' => 55,
                'estado' => 'disponible',
            ],
            [
                'placa' => 'JKL-012',
                'marca' => 'Isuzu',
                'modelo' => 'NLR',
                'año' => 2020,
                'color' => 'Blanco',
                'tipo' => 'camion',
                'capacidad_carga' => 3000,
                'capacidad_bidones' => 150,
                'estado' => 'disponible',
            ],
            [
                'placa' => 'MNO-345',
                'marca' => 'Ford',
                'modelo' => 'Ranger',
                'año' => 2019,
                'color' => 'Gris',
                'tipo' => 'camioneta',
                'capacidad_carga' => 850,
                'capacidad_bidones' => 40,
                'estado' => 'mantenimiento',
            ],
        ];

        foreach ($vehiculos as $vehiculoData) {
            $vehiculo = Vehiculo::create([
                'placa' => $vehiculoData['placa'],
                'marca' => $vehiculoData['marca'],
                'modelo' => $vehiculoData['modelo'],
                'año' => $vehiculoData['año'],
                'color' => $vehiculoData['color'],
                'tipo' => $vehiculoData['tipo'],
                'capacidad_carga' => $vehiculoData['capacidad_carga'],
                'capacidad_bidones' => $vehiculoData['capacidad_bidones'],
                'numero_motor' => 'MOT-' . strtoupper(substr(md5($vehiculoData['placa']), 0, 10)),
                'numero_chasis' => 'CHA-' . strtoupper(substr(md5($vehiculoData['placa'] . 'chasis'), 0, 10)),
                'fecha_compra' => now()->subYears(rand(1, 4)),
                'fecha_ultimo_mantenimiento' => now()->subMonths(rand(1, 3)),
                'fecha_proximo_mantenimiento' => now()->addMonths(rand(1, 3)),
                'kilometraje' => rand(10000, 80000),
                'estado' => $vehiculoData['estado'],
                'activo' => true,
            ]);
        }

        // Asignar choferes a los primeros 3 vehículos
        $choferes = User::where('role', 'chofer')->take(3)->get();
        $vehiculosDisponibles = Vehiculo::where('estado', 'disponible')->take(3)->get();

        foreach ($choferes as $index => $chofer) {
            if (isset($vehiculosDisponibles[$index])) {
                $vehiculosDisponibles[$index]->choferes()->attach($chofer->id, [
                    'fecha_asignacion' => now()->subMonths(rand(1, 6)),
                    'asignacion_activa' => true,
                ]);
                
                // Actualizar estado del vehículo
                $vehiculosDisponibles[$index]->update(['estado' => 'en_uso']);
            }
        }
    }
}
