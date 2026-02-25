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
        Schema::table('repartos', function (Blueprint $table) {
            // Verificar si las columnas no existen antes de agregarlas
            if (!Schema::hasColumn('repartos', 'fecha_programada')) {
                $table->date('fecha_programada')->nullable()->after('fecha');
            }
            if (!Schema::hasColumn('repartos', 'fecha_entrega')) {
                $table->date('fecha_entrega')->nullable()->after('fecha_programada');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repartos', function (Blueprint $table) {
            if (Schema::hasColumn('repartos', 'fecha_entrega')) {
                $table->dropColumn('fecha_entrega');
            }
            if (Schema::hasColumn('repartos', 'fecha_programada')) {
                $table->dropColumn('fecha_programada');
            }
        });
    }
};
