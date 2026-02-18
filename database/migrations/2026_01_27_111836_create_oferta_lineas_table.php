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
        Schema::create('oferta_lineas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('oferta_cabecera_id');
            $table->string('tipo', 255); // 'opciones', 'descuento', 'accesorios'
            $table->string('descripcion', 255);
            $table->double('precio', 10, 2);
            $table->timestamps();
        
            // Relación
            $table->foreign('oferta_cabecera_id')->references('id')->on('oferta_cabeceras')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oferta_lineas');
    }
};
