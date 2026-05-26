<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venta_conceptos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->cascadeOnDelete();
            $table->enum('tipo', ['extra', 'descuento']);
            $table->string('descripcion', 255);
            $table->decimal('importe', 12, 2);
            $table->timestamps();

            $table->index(['venta_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venta_conceptos');
    }
};
