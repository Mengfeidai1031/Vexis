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
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->onDelete('cascade');
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('centro_id')->constrained('centros')->onDelete('cascade');
            $table->foreignId('marca_id')->nullable()->constrained('marcas')->onDelete('set null');
            $table->foreignId('vendedor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('precio_venta', 12, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('precio_final', 12, 2);
            $table->enum('forma_pago', ['contado', 'financiado', 'leasing', 'renting'])->default('contado');
            $table->enum('estado', ['reservada', 'pendiente_entrega', 'entregada', 'cancelada'])->default('reservada');
            $table->date('fecha_venta');
            $table->date('fecha_entrega')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
