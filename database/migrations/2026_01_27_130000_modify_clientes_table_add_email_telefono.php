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
            // Hacer DNI nullable y quitar unique (por si hay conflictos)
            $table->string('dni', 10)->nullable()->change();
            
            // Añadir email y teléfono como requeridos
            $table->string('email')->after('dni');
            $table->string('telefono', 20)->after('email');
        });
        
        // Quitar la restricción unique del DNI
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropUnique(['dni']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['email', 'telefono']);
            $table->string('dni', 10)->nullable(false)->unique()->change();
        });
    }
};
