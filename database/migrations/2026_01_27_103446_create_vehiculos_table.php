<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('chasis', 17)->unique();
            $table->string('modelo', 255);
            $table->string('version', 255);
            $table->string('color_externo', 255);
            $table->string('color_interno', 255);
            $table->unsignedBigInteger('empresa_id');
            $table->timestamps();
        
            // Relación con empresas
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
