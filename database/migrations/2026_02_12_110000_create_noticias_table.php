<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('noticias', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255);
            $table->text('contenido');
            $table->string('imagen_url', 500)->nullable();
            $table->enum('categoria', ['general', 'empresa', 'comercial', 'rrhh', 'tecnologia'])->default('general');
            $table->boolean('destacada')->default(false);
            $table->boolean('publicada')->default(true);
            $table->foreignId('autor_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('fecha_publicacion')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('noticias');
    }
};
