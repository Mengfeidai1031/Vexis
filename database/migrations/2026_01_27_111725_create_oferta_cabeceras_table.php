<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oferta_cabeceras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('vehiculo_id')->nullable()->constrained('vehiculos')->nullOnDelete();
            $table->dateTime('fecha');

            // Datos extraídos del PDF de oferta
            $table->string('cliente_nombre_pdf', 255)->nullable();
            $table->string('cliente_dni_pdf', 15)->nullable();
            $table->string('vehiculo_modelo_pdf', 150)->nullable();
            $table->string('vehiculo_chasis_pdf', 17)->nullable();

            $table->string('pdf_path', 500)->nullable();

            // Totales de cálculo
            $table->decimal('base_imponible', 12, 2)->nullable();
            $table->decimal('impuestos', 12, 2)->nullable();
            $table->decimal('total_sin_impuestos', 12, 2)->nullable();
            $table->decimal('total_con_impuestos', 12, 2)->nullable();

            $table->timestamps();

            $table->index(['cliente_id', 'fecha']);
            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oferta_cabeceras');
    }
};
