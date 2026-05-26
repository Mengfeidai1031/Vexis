<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talleres', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('codigo', 20)->unique();
            $table->string('domicilio', 255);
            $table->string('codigo_postal', 5)->nullable();
            $table->string('localidad', 100)->nullable();
            $table->string('isla', 50)->nullable();
            $table->string('telefono', 15)->nullable();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('centro_id')->constrained('centros')->cascadeOnDelete();
            $table->foreignId('marca_id')->nullable()->constrained('marcas')->nullOnDelete();
            $table->unsignedSmallInteger('capacidad_diaria')->default(10);
            $table->boolean('activo')->default(true);
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['empresa_id', 'centro_id']);
            $table->index(['isla', 'activo']);
            $table->index('marca_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talleres');
    }
};
