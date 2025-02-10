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
        Schema::connection('tenant')->create('contratos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('administradora_id');
            $table->unsignedBigInteger('acomodacao_id')->nullable();
            $table->unsignedBigInteger('tabela_origens_id');
            $table->unsignedBigInteger('plano_id');
            $table->unsignedBigInteger('financeiro_id');
            $table->boolean('coparticipacao');
            $table->boolean('odonto');
            $table->string('codigo_externo')->unique();
            $table->date('data_vigencia')->nullable();
            $table->date('data_boleto')->nullable();
            $table->date('data_baixa')->nullable();
            $table->decimal('valor_adesao', 10, 2)->nullable();
            $table->decimal('valor_plano', 10, 2)->nullable();
            $table->decimal('desconto_corretora', 10, 2)->nullable()->default(0.00);
            $table->decimal('desconto_corretor', 10, 2)->nullable()->default(0.00);
            $table->date('data_analise')->nullable();
            $table->date('data_emissao')->nullable();
            $table->boolean('estorno')->default(false);
            $table->date('data_baixa_estorno')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('contratos');
    }
};
