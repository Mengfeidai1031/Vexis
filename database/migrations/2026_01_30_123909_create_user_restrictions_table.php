<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_restrictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->morphs('restrictable');
            $table->timestamps();

            $table->unique(['user_id', 'restrictable_type', 'restrictable_id'], 'user_restrictable_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_restrictions');
    }
};
