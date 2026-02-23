<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa')->unique();
            $table->string('marca');
            $table->string('modelo');
            $table->year('año');
            $table->string('color')->nullable();
            $table->enum('tipo', ['camion', 'camioneta', 'auto', 'moto'])->default('camioneta');
            $table->integer('capacidad_carga')->nullable()->comment('Capacidad en kg');
            $table->integer('capacidad_bidones')->nullable()->comment('Cantidad de bidones');
            $table->string('numero_motor')->nullable();
            $table->string('numero_chasis')->nullable();
            $table->date('fecha_compra')->nullable();
            $table->date('fecha_ultimo_mantenimiento')->nullable();
            $table->date('fecha_proximo_mantenimiento')->nullable();
            $table->decimal('kilometraje', 10, 2)->nullable();
            $table->enum('estado', ['disponible', 'en_uso', 'mantenimiento', 'fuera_servicio'])->default('disponible');
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
