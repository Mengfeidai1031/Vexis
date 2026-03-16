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
            $table->string('nombre_equipo', 100);
            $table->string('tipo', 50)->default('Portátil');
            $table->string('ubicacion', 255)->nullable();
            $table->foreignId('centro_id')->nullable()->constrained('centros')->onDelete('set null');
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->onDelete('set null');
            $table->string('direccion_ip', 45)->nullable();
            $table->string('direccion_mac', 17)->nullable();
            $table->string('sistema_operativo', 100)->nullable();
            $table->string('version_so', 10)->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('naming_pcs');
    }
};
