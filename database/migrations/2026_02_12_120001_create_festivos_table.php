<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('festivos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->date('fecha');
            $table->string('municipio', 150)->nullable();
            $table->enum('ambito', ['nacional', 'autonomico', 'local'])->default('local');
            $table->integer('anio');
            $table->timestamps();

            $table->index(['fecha', 'municipio']);
            $table->index('anio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('festivos');
    }
};
