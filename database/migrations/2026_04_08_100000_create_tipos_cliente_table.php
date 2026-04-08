<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_cliente', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->string('slug', 120)->unique();
            $table->string('descripcion', 255)->nullable();
            $table->string('color', 9)->default('#33AADD');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->foreignId('tipo_cliente_id')->nullable()->after('empresa_id')
                ->constrained('tipos_cliente')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign(['tipo_cliente_id']);
            $table->dropColumn('tipo_cliente_id');
        });
        Schema::dropIfExists('tipos_cliente');
    }
};
