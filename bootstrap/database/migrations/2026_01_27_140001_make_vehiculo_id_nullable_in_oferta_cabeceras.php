<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('oferta_cabeceras', function (Blueprint $table) {
            // Eliminar la foreign key existente
            $table->dropForeign(['vehiculo_id']);
        });

        Schema::table('oferta_cabeceras', function (Blueprint $table) {
            // Hacer vehiculo_id nullable
            $table->unsignedBigInteger('vehiculo_id')->nullable()->change();
            
            // Recrear la foreign key permitiendo null
            $table->foreign('vehiculo_id')
                  ->references('id')
                  ->on('vehiculos')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('oferta_cabeceras', function (Blueprint $table) {
            $table->dropForeign(['vehiculo_id']);
        });

        Schema::table('oferta_cabeceras', function (Blueprint $table) {
            $table->unsignedBigInteger('vehiculo_id')->nullable(false)->change();
            $table->foreign('vehiculo_id')
                  ->references('id')
                  ->on('vehiculos')
                  ->onDelete('cascade');
        });
    }
};
