<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Producto::create([
            'nombre' => 'Bidón 20L',
            'descripcion' => 'Bidón de agua purificada de 20 litros',
            'precio_base' => 40.00,
            'activo' => true,
        ]);
    }
}
