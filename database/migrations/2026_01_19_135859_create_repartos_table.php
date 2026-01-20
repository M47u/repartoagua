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
        Schema::create('repartos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('repartidor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 8, 2);
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['pendiente', 'entregado'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamp('entregado_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repartos');
    }
};
