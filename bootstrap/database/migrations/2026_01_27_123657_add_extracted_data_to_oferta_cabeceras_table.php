<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('oferta_cabeceras', function (Blueprint $table) {
            // Datos extraídos del PDF
            $table->string('cliente_nombre_pdf')->nullable()->after('fecha');
            $table->string('cliente_dni_pdf')->nullable()->after('cliente_nombre_pdf');
            $table->string('vehiculo_modelo_pdf')->nullable()->after('cliente_dni_pdf');
            $table->string('vehiculo_chasis_pdf')->nullable()->after('vehiculo_modelo_pdf');
            
            // Datos de cálculo
            $table->decimal('base_imponible', 10, 2)->nullable()->after('pdf_path');
            $table->decimal('impuestos', 10, 2)->nullable()->after('base_imponible');
            $table->decimal('total_sin_impuestos', 10, 2)->nullable()->after('impuestos');
            $table->decimal('total_con_impuestos', 10, 2)->nullable()->after('total_sin_impuestos');
        });
    }

    public function down(): void
    {
        Schema::table('oferta_cabeceras', function (Blueprint $table) {
            $table->dropColumn([
                'cliente_nombre_pdf',
                'cliente_dni_pdf',
                'vehiculo_modelo_pdf',
                'vehiculo_chasis_pdf',
                'base_imponible',
                'impuestos',
                'total_sin_impuestos',
                'total_con_impuestos',
            ]);
        });
    }
};