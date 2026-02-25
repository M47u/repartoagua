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
            // Agregar columnas faltantes si no existen
            if (!Schema::hasColumn('repartos', 'notas')) {
                $table->text('notas')->nullable()->after('estado');
            }
            if (!Schema::hasColumn('repartos', 'observaciones')) {
                $table->text('observaciones')->nullable()->after('notas');
            }
            if (!Schema::hasColumn('repartos', 'entregado_at')) {
                $table->timestamp('entregado_at')->nullable()->after('observaciones');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repartos', function (Blueprint $table) {
            if (Schema::hasColumn('repartos', 'entregado_at')) {
                $table->dropColumn('entregado_at');
            }
            if (Schema::hasColumn('repartos', 'observaciones')) {
                $table->dropColumn('observaciones');
            }
            if (Schema::hasColumn('repartos', 'notas')) {
                $table->dropColumn('notas');
            }
        });
    }
};
