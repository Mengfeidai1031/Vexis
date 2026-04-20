<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_venta', 30)->unique();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->cascadeOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('centro_id')->constrained('centros')->cascadeOnDelete();
            $table->foreignId('marca_id')->nullable()->constrained('marcas')->nullOnDelete();
            $table->foreignId('vendedor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('precio_venta', 12, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('precio_final', 12, 2);
            $table->decimal('subtotal', 12, 2)->nullable();
            $table->enum('impuesto_nombre', ['IGIC', 'IVA'])->default('IGIC');
            $table->decimal('impuesto_porcentaje', 5, 2)->default(7);
            $table->decimal('impuesto_importe', 12, 2)->nullable();
            $table->decimal('total', 12, 2)->nullable();
            $table->enum('forma_pago', ['contado', 'financiado', 'leasing', 'renting'])->default('contado');
            $table->enum('estado', ['reservada', 'pendiente_entrega', 'entregada', 'cancelada'])->default('reservada');
            $table->date('fecha_venta');
            $table->date('fecha_entrega')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['empresa_id', 'estado']);
            $table->index(['fecha_venta', 'empresa_id']);
            $table->index(['vendedor_id', 'fecha_venta']);
            $table->index('marca_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
