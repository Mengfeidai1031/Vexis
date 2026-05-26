<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculo_historial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('accion', 50);
            $table->string('campo', 80)->nullable();
            $table->string('valor_anterior', 255)->nullable();
            $table->string('valor_nuevo', 255)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['vehiculo_id', 'created_at']);
        });

        Schema::create('vehiculo_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('tipo', ['ficha_tecnica', 'itv', 'permiso_circulacion', 'seguro', 'contrato', 'otro'])->default('otro');
            $table->string('nombre_original', 255);
            $table->string('ruta', 500);
            $table->string('mime', 100)->nullable();
            $table->unsignedInteger('tamano_bytes')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['vehiculo_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculo_documentos');
        Schema::dropIfExists('vehiculo_historial');
    }
};
