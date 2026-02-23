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
            $table->string('nombre_pieza', 255);
            $table->string('descripcion', 500)->nullable();
            $table->string('marca_pieza', 100)->nullable();
            $table->integer('cantidad')->default(0);
            $table->integer('stock_minimo')->default(1);
            $table->decimal('precio_unitario', 10, 2)->default(0);
            $table->string('ubicacion_almacen', 100)->nullable();
            $table->foreignId('almacen_id')->constrained('almacenes')->onDelete('cascade');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('centro_id')->constrained('centros')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index('referencia');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
