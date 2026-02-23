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
            $table->foreignId('mecanico_id')->constrained('mecanicos')->onDelete('cascade');
            $table->foreignId('taller_id')->constrained('talleres')->onDelete('cascade');
            $table->foreignId('marca_id')->nullable()->constrained('marcas')->onDelete('set null');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->string('cliente_nombre', 255);
            $table->string('vehiculo_info', 255)->nullable();
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin')->nullable();
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['pendiente', 'confirmada', 'en_curso', 'completada', 'cancelada'])->default('pendiente');
            $table->timestamps();

            $table->index(['fecha', 'mecanico_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas_taller');
    }
};
