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
        // Primero, limpiar registros corruptos o duplicados que puedan existir
        \DB::table('user_restrictions')
            ->whereNull('restriction_type')
            ->whereNull('restriction_value')
            ->delete();
        
        // Eliminar duplicados basados en user_id, restriction_type y restriction_value
        \DB::statement('
            DELETE r1 FROM user_restrictions r1
            INNER JOIN user_restrictions r2 
            WHERE r1.id > r2.id 
            AND r1.user_id = r2.user_id 
            AND r1.restriction_type = r2.restriction_type 
            AND r1.restriction_value = r2.restriction_value
        ');

        Schema::table('user_restrictions', function (Blueprint $table) {
            // Eliminar índices y constraint antiguos si existen
            try {
                $table->dropUnique('user_restriction_unique');
            } catch (\Exception $e) {
                // El índice puede no existir
            }
            
            try {
                $table->dropIndex(['restriction_type', 'restriction_value']);
            } catch (\Exception $e) {
                // El índice puede no existir
            }
        });

        // Verificar si las columnas antiguas existen antes de eliminarlas
        $columns = \DB::select('SHOW COLUMNS FROM user_restrictions');
        $hasOldColumns = collect($columns)->pluck('Field')->contains('restriction_type');
        
        if ($hasOldColumns) {
            Schema::table('user_restrictions', function (Blueprint $table) {
                // Eliminar columnas antiguas
                $table->dropColumn(['restriction_type', 'restriction_value']);
            });
        }

        // Verificar si las columnas nuevas ya existen
        $hasNewColumns = collect($columns)->pluck('Field')->contains('restrictable_type');
        
        if (!$hasNewColumns) {
            Schema::table('user_restrictions', function (Blueprint $table) {
                // Añadir columnas polimórficas
                $table->morphs('restrictable'); // Crea restrictable_type y restrictable_id
            });
        }

        // Limpiar registros con valores NULL en las nuevas columnas antes de crear el índice
        \DB::table('user_restrictions')
            ->whereNull('restrictable_type')
            ->orWhereNull('restrictable_id')
            ->delete();

        // Eliminar duplicados en las nuevas columnas
        \DB::statement('
            DELETE r1 FROM user_restrictions r1
            INNER JOIN user_restrictions r2 
            WHERE r1.id > r2.id 
            AND r1.user_id = r2.user_id 
            AND r1.restrictable_type = r2.restrictable_type 
            AND r1.restrictable_id = r2.restrictable_id
        ');

        Schema::table('user_restrictions', function (Blueprint $table) {
            // Verificar si el índice único ya existe
            $indexes = \DB::select("SHOW INDEX FROM user_restrictions WHERE Key_name = 'user_restrictable_unique'");
            
            if (empty($indexes)) {
                // Nuevo índice único para evitar duplicados
                $table->unique(['user_id', 'restrictable_type', 'restrictable_id'], 'user_restrictable_unique');
            }
            
            // Verificar si el índice de búsqueda ya existe
            $searchIndexes = \DB::select("SHOW INDEX FROM user_restrictions WHERE Key_name LIKE 'user_restrictions_restrictable%'");
            
            if (empty($searchIndexes)) {
                // Índice para búsquedas rápidas
                $table->index(['restrictable_type', 'restrictable_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_restrictions', function (Blueprint $table) {
            // Eliminar índices y columnas polimórficas
            $table->dropUnique('user_restrictable_unique');
            $table->dropIndex(['restrictable_type', 'restrictable_id']);
            $table->dropMorphs('restrictable');
        });

        Schema::table('user_restrictions', function (Blueprint $table) {
            // Restaurar columnas antiguas
            $table->string('restriction_type', 50);
            $table->unsignedBigInteger('restriction_value');
            
            // Restaurar índices
            $table->unique(['user_id', 'restriction_type', 'restriction_value'], 'user_restriction_unique');
            $table->index(['restriction_type', 'restriction_value']);
        });
    }
};
