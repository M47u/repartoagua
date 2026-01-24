<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = [
            ['nombre' => 'Juan', 'apellido' => 'García', 'telefono' => '5512345678', 'direccion' => 'Calle Juárez 123', 'colonia' => 'Centro', 'ciudad' => 'CDMX', 'email' => null, 'tipo_cliente' => 'hogar', 'precio_por_bidon' => 40.00, 'observaciones' => null, 'activo' => true],
            ['nombre' => 'María', 'apellido' => 'López', 'telefono' => '5523456789', 'direccion' => 'Av. Insurgentes 456', 'colonia' => 'Roma Norte', 'ciudad' => 'CDMX', 'email' => null, 'tipo_cliente' => 'hogar', 'precio_por_bidon' => 38.00, 'observaciones' => null, 'activo' => true],
            ['nombre' => 'Tacos El Buen Sabor', 'apellido' => '', 'telefono' => '5534567890', 'direccion' => 'Calle Morelos 789', 'colonia' => 'Condesa', 'ciudad' => 'CDMX', 'email' => null, 'tipo_cliente' => 'comercio', 'precio_por_bidon' => 35.00, 'observaciones' => 'Solicitan entrega temprano', 'activo' => true],
            ['nombre' => 'Restaurante La Hacienda', 'apellido' => '', 'telefono' => '5545678901', 'direccion' => 'Av. Reforma 321', 'colonia' => 'Polanco', 'ciudad' => 'CDMX', 'email' => null, 'tipo_cliente' => 'comercio', 'precio_por_bidon' => 36.00, 'observaciones' => null, 'activo' => true],
            ['nombre' => 'Corporativo TechSolutions', 'apellido' => 'SA', 'telefono' => '5556789012', 'direccion' => 'Calle Hidalgo 654', 'colonia' => 'Del Valle', 'ciudad' => 'CDMX', 'email' => 'contacto@techsolutions.com', 'tipo_cliente' => 'empresa', 'precio_por_bidon' => 32.00, 'observaciones' => 'Factura requerida', 'activo' => true],
            ['nombre' => 'Rosa', 'apellido' => 'Flores', 'telefono' => '5567890123', 'direccion' => 'Av. Universidad 987', 'colonia' => 'Coyoacán', 'ciudad' => 'CDMX', 'email' => null, 'tipo_cliente' => 'hogar', 'precio_por_bidon' => 40.00, 'observaciones' => null, 'activo' => true],
            ['nombre' => 'Gym FitLife', 'apellido' => '', 'telefono' => '5578901234', 'direccion' => 'Calle Madero 147', 'colonia' => 'Narvarte', 'ciudad' => 'CDMX', 'email' => 'info@fitlife.com', 'tipo_cliente' => 'comercio', 'precio_por_bidon' => 37.00, 'observaciones' => null, 'activo' => true],
            ['nombre' => 'Oficinas Grupo Alfa', 'apellido' => '', 'telefono' => '5589012345', 'direccion' => 'Av. Chapultepec 258', 'colonia' => 'Juárez', 'ciudad' => 'CDMX', 'email' => null, 'tipo_cliente' => 'empresa', 'precio_por_bidon' => 33.00, 'observaciones' => null, 'activo' => true],
            ['nombre' => 'Jorge', 'apellido' => 'Moreno', 'telefono' => '5590123456', 'direccion' => 'Calle 5 de Mayo 369', 'colonia' => 'Tlalpan', 'ciudad' => 'CDMX', 'email' => null, 'tipo_cliente' => 'hogar', 'precio_por_bidon' => 42.00, 'observaciones' => null, 'activo' => true],
            ['nombre' => 'Cafetería Aroma', 'apellido' => '', 'telefono' => '5501234567', 'direccion' => 'Av. Revolución 741', 'colonia' => 'San Ángel', 'ciudad' => 'CDMX', 'email' => null, 'tipo_cliente' => 'comercio', 'precio_por_bidon' => 38.00, 'observaciones' => 'Entrega por la tarde', 'activo' => true],
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }
    }
}
