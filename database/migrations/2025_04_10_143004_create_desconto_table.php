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
        Schema::create('desconto', function (Blueprint $table) {
            $table->id(); // Cria a coluna 'id' como chave primária auto-incrementada

            // Cria a coluna 'tabela_origens_id' como unsignedBigInteger
            $table->unsignedBigInteger('tabela_origens_id');
            // Define a chave estrangeira para 'tabela_origens_id'
            $table->foreign('tabela_origens_id')
                ->references('id')
                ->on('tabela_origens')
                ->onDelete('cascade'); // Opcional: define o comportamento ao deletar

            // Cria a coluna 'plano_id' como unsignedBigInteger
            $table->unsignedBigInteger('plano_id');
            // Define a chave estrangeira para 'plano_id'
            $table->foreign('plano_id')
                ->references('id')
                ->on('planos')
                ->onDelete('cascade'); // Opcional: define o comportamento ao deletar

            $table->unsignedBigInteger('administradora_id');

            $table->foreign('administradora_id')
                ->references('id')
                ->on('administradoras')
                ->onDelete('cascade'); // ou set null se preferir


            // Cria a coluna 'valor' como decimal(10,2) e permite valores nulos
            $table->decimal('valor', 10, 2)->nullable();

            $table->timestamps(); // Cria as colunas 'created_at' e 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desconto');
    }
};
