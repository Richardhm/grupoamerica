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
        Schema::create('carencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plano_id')->constrained('planos')->onDelete('cascade');
            $table->foreignId('tabela_origens_id')->constrained('tabela_origens')->onDelete('cascade');
            $table->integer('tempo')->comment('Tempo da carência em dias');
            $table->text('detalhe')->nullable()->comment('Detalhamento da carência');
            $table->string('frase')->nullable()->comment('Frase informativa sobre a carência');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carencias');
    }
};
