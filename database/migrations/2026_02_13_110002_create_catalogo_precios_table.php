<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalogo_precios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marca_id')->constrained('marcas')->onDelete('cascade');
            $table->string('modelo', 150);
            $table->string('version', 200)->nullable();
            $table->string('combustible', 50)->nullable();
            $table->integer('potencia_cv')->nullable();
            $table->decimal('precio_base', 12, 2);
            $table->decimal('precio_oferta', 12, 2)->nullable();
            $table->boolean('disponible')->default(true);
            $table->string('imagen_url', 500)->nullable();
            $table->integer('anio_modelo')->nullable();
            $table->timestamps();

            $table->index('marca_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalogo_precios');
    }
};
