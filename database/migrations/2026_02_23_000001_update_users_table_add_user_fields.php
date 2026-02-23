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
        Schema::table('users', function (Blueprint $table) {
            // Cambiar el enum de role para incluir todos los roles
            $table->enum('role', ['administrador', 'administrativo', 'repartidor', 'chofer', 'gerente'])
                ->default('repartidor')
                ->change();
            
            // Agregar campos adicionales para usuarios
            $table->string('apellido')->nullable()->after('name');
            $table->string('telefono')->nullable()->after('email');
            $table->string('dni')->nullable()->unique()->after('telefono');
            $table->string('direccion')->nullable()->after('dni');
            $table->string('ciudad')->nullable()->after('direccion');
            $table->date('fecha_ingreso')->nullable()->after('ciudad');
            $table->date('fecha_nacimiento')->nullable()->after('fecha_ingreso');
            $table->text('observaciones')->nullable()->after('fecha_nacimiento');
            $table->boolean('activo')->default(true)->after('observaciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'apellido',
                'telefono',
                'dni',
                'direccion',
                'ciudad',
                'fecha_ingreso',
                'fecha_nacimiento',
                'observaciones',
                'activo'
            ]);
            
            // Restaurar el enum original
            $table->enum('role', ['administrador', 'administrativo', 'repartidor'])
                ->default('repartidor')
                ->change();
        });
    }
};
