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
            $table->string('codigo_reparto', 30);
            $table->foreignId('stock_id')->constrained('stocks')->onDelete('cascade');
            $table->foreignId('almacen_origen_id')->constrained('almacenes')->onDelete('cascade');
            $table->foreignId('almacen_destino_id')->nullable()->constrained('almacenes')->onDelete('set null');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('centro_id')->constrained('centros')->onDelete('cascade');
            $table->integer('cantidad');
            $table->enum('estado', ['pendiente', 'en_transito', 'entregado', 'cancelado'])->default('pendiente');
            $table->date('fecha_solicitud');
            $table->date('fecha_entrega')->nullable();
            $table->string('solicitado_por', 255)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index('codigo_reparto');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repartos');
    }
};
