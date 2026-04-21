<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('chasis', 17)->unique();
            $table->string('matricula', 10)->nullable()->unique();
            $table->string('modelo', 100);
            $table->string('version', 150);
            $table->string('color_externo', 100);
            $table->string('color_interno', 100);
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('marca_id')->nullable()->constrained('marcas')->nullOnDelete();
            $table->enum('estado', ['disponible', 'reservado', 'vendido', 'taller', 'baja'])->default('disponible');
            $table->foreignId('responsable_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['empresa_id', 'marca_id']);
            $table->index(['empresa_id', 'estado']);
            $table->index('modelo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
