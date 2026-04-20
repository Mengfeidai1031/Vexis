<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('abreviatura', 10);
            $table->string('cif', 10)->unique();
            $table->string('domicilio', 255);
            $table->string('codigo_postal', 5)->nullable();
            $table->string('telefono', 15);
            $table->timestamps();

            $table->index('abreviatura');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
