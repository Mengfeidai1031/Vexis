<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasaciones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_tasacion', 30)->unique();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('marca_id')->nullable()->constrained('marcas')->nullOnDelete();
            $table->foreignId('tasador_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('vehiculo_marca', 80);
            $table->string('vehiculo_modelo', 120);
            $table->unsignedSmallInteger('vehiculo_anio');
            $table->unsignedInteger('kilometraje');
            $table->string('matricula', 10)->nullable();
            $table->enum('combustible', ['Gasolina', 'Diésel', 'Híbrido', 'Eléctrico', 'GLP'])->nullable();
            $table->enum('estado_vehiculo', ['excelente', 'bueno', 'regular', 'malo'])->default('bueno');
            $table->decimal('valor_estimado', 12, 2)->nullable();
            $table->decimal('valor_final', 12, 2)->nullable();
            $table->enum('estado', ['pendiente', 'valorada', 'aceptada', 'rechazada'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->date('fecha_tasacion');
            $table->timestamps();

            $table->index(['empresa_id', 'estado']);
            $table->index(['fecha_tasacion', 'empresa_id']);
            $table->index('tasador_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasaciones');
    }
};
