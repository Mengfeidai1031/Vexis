<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('naming_pcs', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_equipo', 100)->unique();
            $table->enum('tipo', ['Portátil', 'Sobremesa'])->default('Portátil');
            $table->string('ubicacion', 150)->nullable();
            $table->foreignId('centro_id')->nullable()->constrained('centros')->nullOnDelete();
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->nullOnDelete();
            $table->string('direccion_ip', 45)->nullable();
            $table->string('direccion_mac', 17)->nullable();
            $table->string('sistema_operativo', 100)->nullable();
            $table->string('version_so', 10)->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['empresa_id', 'centro_id']);
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('naming_pcs');
    }
};
