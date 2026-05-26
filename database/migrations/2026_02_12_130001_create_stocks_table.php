<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('referencia', 50);
            $table->string('nombre_pieza', 200);
            $table->string('descripcion', 500)->nullable();
            $table->string('marca_pieza', 80)->nullable();
            $table->unsignedInteger('cantidad')->default(0);
            $table->unsignedInteger('stock_minimo')->default(1);
            $table->decimal('precio_unitario', 10, 2)->default(0);
            $table->string('ubicacion_almacen', 100)->nullable();
            $table->foreignId('almacen_id')->constrained('almacenes')->cascadeOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('centro_id')->constrained('centros')->cascadeOnDelete();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index('referencia');
            $table->index(['almacen_id', 'activo']);
            $table->index(['empresa_id', 'centro_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
