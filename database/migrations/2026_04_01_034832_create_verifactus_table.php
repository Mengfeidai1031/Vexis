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
            $table->string('codigo_registro', 40)->unique();
            $table->string('numero_serie_factura', 40)->nullable();
            $table->string('fecha_expedicion', 20)->nullable();
            $table->foreignId('factura_id')->constrained('facturas')->cascadeOnDelete();
            $table->string('hash_registro', 64);
            $table->string('huella', 64)->nullable();
            $table->string('url_qr', 500)->nullable();
            $table->string('csv_aeat', 50)->nullable();
            $table->string('hash_anterior', 64)->nullable();
            $table->dateTime('fecha_registro');
            $table->enum('estado', ['registrado', 'enviado', 'aceptado', 'aceptado_errores', 'rechazado', 'anulado'])->default('registrado');
            $table->enum('tipo_operacion', ['alta', 'anulacion'])->default('alta');
            $table->enum('tipo_factura', ['F1', 'F2', 'F3', 'R1', 'R2', 'R3', 'R4', 'R5'])->default('F1');
            $table->boolean('factura_simplificada')->default(false);
            $table->string('clave_regimen', 2)->default('01');
            $table->string('descripcion_operacion', 255)->nullable();
            $table->string('nif_emisor', 15)->nullable();
            $table->string('nombre_emisor', 150)->nullable();
            $table->string('nif_destinatario', 15)->nullable();
            $table->string('nombre_destinatario', 200)->nullable();
            $table->decimal('importe_total', 12, 2)->default(0);
            $table->decimal('base_imponible', 12, 2)->default(0);
            $table->decimal('cuota_tributaria', 12, 2)->default(0);
            $table->decimal('tipo_impositivo', 5, 2)->default(21);
            $table->json('respuesta_aeat')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('id_factura_rectificada', 40)->nullable();
            $table->string('sistema_informatico', 50)->default('VEXIS');
            $table->string('version_sistema', 20)->default('1.0.0');
            $table->timestamps();

            $table->index(['factura_id', 'estado']);
            $table->index(['fecha_registro', 'estado']);
            $table->index('tipo_operacion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifactus');
    }
};
