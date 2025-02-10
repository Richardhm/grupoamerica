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
        Schema::create('folha_pagamento', function (Blueprint $table) {
            $table->id();

            $table->foreignId('folha_mes_id')->references('id')
                 ->on('folha_mes')
                ->onDelete('cascade');

            // Chave estrangeira para valores_corretores_lancados
            $table->foreignId('valores_corretores_lancados_id')
                ->references('id')
                ->on('valores_corretores_lancadas')
                ->onDelete('cascade');


            $table->timestamps(); // Colunas created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folha_pagamento');
    }
};
