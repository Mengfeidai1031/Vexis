<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->decimal('subtotal', 12, 2)->nullable()->after('precio_final');
            $table->string('impuesto_nombre', 10)->default('IGIC')->after('subtotal');
            $table->decimal('impuesto_porcentaje', 5, 2)->default(7)->after('impuesto_nombre');
            $table->decimal('impuesto_importe', 12, 2)->nullable()->after('impuesto_porcentaje');
            $table->decimal('total', 12, 2)->nullable()->after('impuesto_importe');
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'impuesto_nombre', 'impuesto_porcentaje', 'impuesto_importe', 'total']);
        });
    }
};
