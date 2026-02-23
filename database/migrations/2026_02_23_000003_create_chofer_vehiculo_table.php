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
        Schema::create('chofer_vehiculo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->restrictOnDelete();
            $table->date('fecha_asignacion')->nullable();
            $table->date('fecha_desasignacion')->nullable();
            $table->boolean('asignacion_activa')->default(true);
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índice único para evitar duplicados activos
            $table->unique(['user_id', 'vehiculo_id', 'asignacion_activa'], 'chofer_vehiculo_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chofer_vehiculo');
    }
};
