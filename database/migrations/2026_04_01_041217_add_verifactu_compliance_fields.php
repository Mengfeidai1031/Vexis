<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add compliance fields to verifactus
        Schema::table('verifactus', function (Blueprint $table) {
            $table->string('tipo_factura')->default('F1')->after('tipo_operacion'); // F1=normal, F2=simplificada, R1-R5=rectificativas
            $table->string('clave_regimen')->default('01')->after('tipo_factura'); // 01=General, 02=Exportación, etc.
            $table->string('descripcion_operacion')->nullable()->after('clave_regimen');
            $table->decimal('base_imponible', 12, 2)->default(0)->after('importe_total');
            $table->decimal('cuota_tributaria', 12, 2)->default(0)->after('base_imponible');
            $table->decimal('tipo_impositivo', 5, 2)->default(21)->after('cuota_tributaria');
            $table->string('nif_destinatario')->nullable()->after('nombre_emisor');
            $table->string('nombre_destinatario')->nullable()->after('nif_destinatario');
            $table->string('numero_serie_factura')->nullable()->after('codigo_registro'); // Actual invoice number
            $table->string('fecha_expedicion')->nullable()->after('numero_serie_factura');
            $table->string('huella')->nullable()->after('hash_registro'); // Fingerprint for QR
            $table->string('url_qr', 500)->nullable()->after('huella');
            $table->string('csv_aeat')->nullable()->after('url_qr'); // Código Seguro Verificación
            $table->string('id_factura_rectificada')->nullable()->after('csv_aeat'); // For rectification
            $table->boolean('factura_simplificada')->default(false)->after('tipo_factura');
            $table->string('sistema_informatico')->default('VEXIS')->after('observaciones');
            $table->string('version_sistema')->default('1.0.0')->after('sistema_informatico');
        });

        // Add fiscal fields to facturas
        Schema::table('facturas', function (Blueprint $table) {
            $table->string('tipo_factura')->default('F1')->after('estado'); // F1, F2, R1-R5
            $table->string('clave_regimen_iva')->default('01')->after('tipo_factura'); // 01=General
            $table->boolean('factura_simplificada')->default(false)->after('clave_regimen_iva');
            $table->string('numero_serie')->nullable()->after('codigo_factura'); // Serie de facturación
        });
    }

    public function down(): void
    {
        Schema::table('verifactus', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_factura', 'clave_regimen', 'descripcion_operacion',
                'base_imponible', 'cuota_tributaria', 'tipo_impositivo',
                'nif_destinatario', 'nombre_destinatario', 'numero_serie_factura',
                'fecha_expedicion', 'huella', 'url_qr', 'csv_aeat',
                'id_factura_rectificada', 'factura_simplificada',
                'sistema_informatico', 'version_sistema',
            ]);
        });

        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn(['tipo_factura', 'clave_regimen_iva', 'factura_simplificada', 'numero_serie']);
        });
    }
};
