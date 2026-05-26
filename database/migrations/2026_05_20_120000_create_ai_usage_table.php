<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_usage', function (Blueprint $table) {
            $table->id();
            $table->enum('provider', ['chatbot', 'pretasacion'])->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('tokens_in')->default(0);
            $table->unsignedInteger('tokens_out')->default(0);
            $table->unsignedSmallInteger('http_status')->nullable();
            $table->boolean('ok')->default(true);
            $table->string('endpoint', 100)->nullable();
            $table->text('error')->nullable();
            $table->timestamps();

            $table->index(['provider', 'created_at']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_usage');
    }
};
