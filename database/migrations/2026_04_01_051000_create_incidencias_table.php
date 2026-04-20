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
            $table->string('codigo_incidencia', 30)->unique();
            $table->string('titulo', 200);
            $table->text('descripcion');
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tecnico_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('prioridad', ['baja', 'media', 'alta', 'critica'])->default('media');
            $table->enum('estado', ['abierta', 'en_progreso', 'resuelta', 'cerrada'])->default('abierta');
            $table->text('comentario_tecnico')->nullable();
            $table->timestamp('fecha_apertura')->useCurrent();
            $table->timestamp('fecha_cierre')->nullable();
            $table->timestamps();

            $table->index(['estado', 'prioridad']);
            $table->index(['usuario_id', 'estado']);
            $table->index(['tecnico_id', 'estado']);
        });

        Schema::create('incidencia_archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incidencia_id')->constrained('incidencias')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('ruta', 500);
            $table->string('nombre_original', 255);
            $table->enum('tipo', ['usuario', 'tecnico'])->default('usuario');
            $table->timestamps();

            $table->index(['incidencia_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidencia_archivos');
        Schema::dropIfExists('incidencias');
    }
};
