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
            $table->string('nombre', 255);
            $table->text('descripcion')->nullable();
            $table->foreignId('marca_id')->constrained('marcas')->onDelete('cascade');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });

        Schema::create('campania_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campania_id')->constrained('campanias')->onDelete('cascade');
            $table->string('ruta', 500);
            $table->string('nombre_original', 255);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campania_fotos');
        Schema::dropIfExists('campanias');
    }
};
