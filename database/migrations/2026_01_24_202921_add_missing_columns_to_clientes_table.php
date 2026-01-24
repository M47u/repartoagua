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
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('apellido')->nullable()->after('nombre');
            $table->string('email')->nullable()->unique()->after('telefono');
            $table->string('colonia')->nullable()->after('direccion');
            $table->string('ciudad')->nullable()->after('colonia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['apellido', 'email', 'colonia', 'ciudad']);
        });
    }
};
