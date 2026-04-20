<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campanias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->foreignId('marca_id')->constrained('marcas')->cascadeOnDelete();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();

            $table->index(['marca_id', 'activa']);
            $table->index(['fecha_inicio', 'fecha_fin']);
        });

        Schema::create('campania_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campania_id')->constrained('campanias')->cascadeOnDelete();
            $table->string('ruta', 500);
            $table->string('nombre_original', 255);
            $table->unsignedSmallInteger('orden')->default(0);
            $table->timestamps();

            $table->index(['campania_id', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campania_fotos');
        Schema::dropIfExists('campanias');
    }
};
