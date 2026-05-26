<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mecanicos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('apellidos', 150);
            $table->string('especialidad', 100)->nullable();
            $table->foreignId('taller_id')->constrained('talleres')->cascadeOnDelete();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['taller_id', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mecanicos');
    }
};
