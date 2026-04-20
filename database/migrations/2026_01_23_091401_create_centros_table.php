<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('centros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('direccion', 255);
            $table->string('provincia', 100);
            $table->string('municipio', 100);
            $table->timestamps();

            $table->index(['empresa_id', 'provincia']);
            $table->index('municipio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('centros');
    }
};
