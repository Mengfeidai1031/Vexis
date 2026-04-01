<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifactus', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_registro')->unique();
            $table->foreignId('factura_id')->constrained('facturas')->cascadeOnDelete();
            $table->string('hash_registro', 64);
            $table->string('hash_anterior', 64)->nullable();
            $table->dateTime('fecha_registro');
            $table->string('estado')->default('registrado');
            $table->string('tipo_operacion')->default('emision');
            $table->string('nif_emisor')->nullable();
            $table->string('nombre_emisor')->nullable();
            $table->decimal('importe_total', 12, 2)->default(0);
            $table->json('respuesta_aeat')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifactus');
    }
};
