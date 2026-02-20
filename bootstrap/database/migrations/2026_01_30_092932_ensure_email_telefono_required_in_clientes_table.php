<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero, actualizar registros existentes con valores NULL
        DB::table('clientes')
            ->whereNull('email')
            ->update(['email' => 'sin-email@ejemplo.com']);
        
        DB::table('clientes')
            ->whereNull('telefono')
            ->update(['telefono' => '000000000']);
        
        // Luego, hacer los campos NOT NULL
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->string('telefono', 20)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('telefono', 20)->nullable()->change();
        });
    }
};
