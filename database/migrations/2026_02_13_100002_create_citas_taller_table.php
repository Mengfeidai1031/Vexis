<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas_taller', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mecanico_id')->constrained('mecanicos')->cascadeOnDelete();
            $table->foreignId('taller_id')->constrained('talleres')->cascadeOnDelete();
            $table->foreignId('marca_id')->nullable()->constrained('marcas')->nullOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('cliente_nombre', 200);
            $table->string('vehiculo_info', 200)->nullable();
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin')->nullable();
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['pendiente', 'confirmada', 'en_curso', 'completada', 'cancelada'])->default('pendiente');
            $table->timestamps();

            $table->index(['fecha', 'mecanico_id']);
            $table->index(['taller_id', 'fecha']);
            $table->index(['empresa_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas_taller');
    }
};
