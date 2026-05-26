<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_factura', 40)->unique();
            $table->string('numero_serie', 20)->nullable();
            $table->foreignId('venta_id')->nullable()->constrained('ventas')->nullOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('centro_id')->constrained('centros')->cascadeOnDelete();
            $table->foreignId('marca_id')->nullable()->constrained('marcas')->nullOnDelete();
            $table->foreignId('emisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha_factura');
            $table->date('fecha_vencimiento')->nullable();
            $table->text('concepto')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('iva_porcentaje', 5, 2)->default(21);
            $table->decimal('iva_importe', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->enum('estado', ['emitida', 'pagada', 'vencida', 'anulada'])->default('emitida');
            $table->enum('tipo_factura', ['F1', 'F2', 'F3', 'R1', 'R2', 'R3', 'R4', 'R5'])->default('F1');
            $table->string('clave_regimen_iva', 2)->default('01');
            $table->boolean('factura_simplificada')->default(false);
            $table->text('observaciones')->nullable();
            $table->string('pdf_path', 500)->nullable();
            $table->timestamps();

            $table->index(['empresa_id', 'estado']);
            $table->index(['fecha_factura', 'empresa_id']);
            $table->index('cliente_id');
            $table->index('venta_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
