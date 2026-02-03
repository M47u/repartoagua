<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Obtener el primer cliente
$cliente = App\Models\Cliente::first();

echo "ID: " . $cliente->id . PHP_EOL;
echo "Nombre: " . $cliente->nombre . PHP_EOL;
echo "Tipo (desde atributos): " . $cliente->getAttributes()['tipo_cliente'] . PHP_EOL;
echo "Tipo (desde modelo): " . $cliente->tipo_cliente . PHP_EOL;

// Intentar actualizar
echo "\n--- Intentando actualizar a 'empresa' ---\n";
$cliente->tipo_cliente = 'empresa';
$cliente->save();

echo "Tipo después de save: " . $cliente->tipo_cliente . PHP_EOL;
echo "Tipo en BD: " . $cliente->fresh()->tipo_cliente . PHP_EOL;

// Volver a hogar
echo "\n--- Intentando volver a 'hogar' ---\n";
$cliente->tipo_cliente = 'hogar';
$cliente->save();

echo "Tipo después de save: " . $cliente->tipo_cliente . PHP_EOL;
echo "Tipo en BD: " . $cliente->fresh()->tipo_cliente . PHP_EOL;
