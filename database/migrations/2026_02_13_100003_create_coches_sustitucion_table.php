<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coches_sustitucion', function (Blueprint $table) {
            $table->id();
            $table->string('matricula', 10)->unique();
            $table->string('modelo', 100);
            $table->foreignId('marca_id')->constrained('marcas')->onDelete('cascade');
            $table->foreignId('taller_id')->constrained('talleres')->onDelete('cascade');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->boolean('disponible')->default(true);
            $table->string('color', 50)->nullable();
            $table->integer('anio')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('reservas_sustitucion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coche_id')->constrained('coches_sustitucion')->onDelete('cascade');
            $table->string('cliente_nombre', 255);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', ['reservado', 'entregado', 'devuelto', 'cancelado'])->default('reservado');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas_sustitucion');
        Schema::dropIfExists('coches_sustitucion');
    }
};
