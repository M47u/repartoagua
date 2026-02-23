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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->timestamp('fecha_pago')->nullable();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->decimal('monto', 10, 2);
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'cuenta_corriente'])->default('efectivo');
            $table->string('referencia')->nullable();
            $table->text('notas')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('registrado_por')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
