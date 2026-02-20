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
        Schema::create('user_restrictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('restriction_type', 50); // empresa, centro, vehiculo, cliente, departamento
            $table->unsignedBigInteger('restriction_value'); // ID del registro
            $table->timestamps();
            
            // Índice único para evitar duplicados
            $table->unique(['user_id', 'restriction_type', 'restriction_value'], 'user_restriction_unique');
            
            // Índices para búsquedas rápidas
            $table->index('user_id');
            $table->index(['restriction_type', 'restriction_value']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_restrictions');
    }
};
