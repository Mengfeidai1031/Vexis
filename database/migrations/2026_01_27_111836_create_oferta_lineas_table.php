<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oferta_lineas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oferta_cabecera_id')->constrained('oferta_cabeceras')->cascadeOnDelete();
            $table->enum('tipo', ['opciones', 'descuento', 'accesorios']);
            $table->string('descripcion', 255);
            $table->decimal('precio', 12, 2);
            $table->timestamps();

            $table->index(['oferta_cabecera_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oferta_lineas');
    }
};
