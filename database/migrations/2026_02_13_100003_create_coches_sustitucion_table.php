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
            $table->foreignId('marca_id')->constrained('marcas')->cascadeOnDelete();
            $table->foreignId('taller_id')->constrained('talleres')->cascadeOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->boolean('disponible')->default(true);
            $table->string('color', 50)->nullable();
            $table->unsignedSmallInteger('anio')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['taller_id', 'disponible']);
            $table->index('empresa_id');
        });

        Schema::create('reservas_sustitucion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coche_id')->constrained('coches_sustitucion')->cascadeOnDelete();
            $table->string('cliente_nombre', 200);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', ['reservado', 'entregado', 'devuelto', 'cancelado'])->default('reservado');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['coche_id', 'estado']);
            $table->index(['fecha_inicio', 'fecha_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas_sustitucion');
        Schema::dropIfExists('coches_sustitucion');
    }
};
