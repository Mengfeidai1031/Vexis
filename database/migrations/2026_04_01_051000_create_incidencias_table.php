<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_incidencia')->unique();
            $table->string('titulo');
            $table->text('descripcion');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tecnico_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('prioridad', ['baja', 'media', 'alta', 'critica'])->default('media');
            $table->enum('estado', ['abierta', 'en_progreso', 'resuelta', 'cerrada'])->default('abierta');
            $table->text('comentario_tecnico')->nullable();
            $table->timestamp('fecha_apertura')->useCurrent();
            $table->timestamp('fecha_cierre')->nullable();
            $table->timestamps();
        });

        Schema::create('incidencia_archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incidencia_id')->constrained('incidencias')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('ruta');
            $table->string('nombre_original');
            $table->enum('tipo', ['usuario', 'tecnico'])->default('usuario');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidencia_archivos');
        Schema::dropIfExists('incidencias');
    }
};
