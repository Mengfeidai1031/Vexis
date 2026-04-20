<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('apellidos', 150);
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('tipo_cliente_id')->nullable()->constrained('tipos_cliente')->nullOnDelete();
            $table->string('dni', 15)->nullable();
            $table->string('email', 150);
            $table->string('telefono', 20);
            $table->string('domicilio', 255);
            $table->string('codigo_postal', 5);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['empresa_id', 'tipo_cliente_id']);
            $table->index('dni');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
