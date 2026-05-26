<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repartos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_reparto', 30)->unique();
            $table->foreignId('stock_id')->constrained('stocks')->cascadeOnDelete();
            $table->foreignId('almacen_origen_id')->constrained('almacenes')->cascadeOnDelete();
            $table->foreignId('almacen_destino_id')->nullable()->constrained('almacenes')->nullOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('centro_id')->constrained('centros')->cascadeOnDelete();
            $table->unsignedInteger('cantidad');
            $table->enum('estado', ['pendiente', 'en_transito', 'entregado', 'cancelado'])->default('pendiente');
            $table->date('fecha_solicitud');
            $table->date('fecha_entrega')->nullable();
            $table->string('solicitado_por', 150)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['empresa_id', 'estado']);
            $table->index(['fecha_solicitud', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repartos');
    }
};
