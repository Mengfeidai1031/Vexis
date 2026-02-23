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
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('marca_id')->nullable()->constrained('marcas')->onDelete('set null');
            $table->foreignId('tasador_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('vehiculo_marca', 100);
            $table->string('vehiculo_modelo', 150);
            $table->integer('vehiculo_anio');
            $table->integer('kilometraje');
            $table->string('matricula', 10)->nullable();
            $table->string('combustible', 50)->nullable();
            $table->enum('estado_vehiculo', ['excelente', 'bueno', 'regular', 'malo'])->default('bueno');
            $table->decimal('valor_estimado', 12, 2)->nullable();
            $table->decimal('valor_final', 12, 2)->nullable();
            $table->enum('estado', ['pendiente', 'valorada', 'aceptada', 'rechazada'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->date('fecha_tasacion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasaciones');
    }
};
