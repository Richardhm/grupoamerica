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
        Schema::create('contrato_empresarial', function (Blueprint $table) {
            $table->id();
            $table->date('data_analise')->nullable();
            $table->decimal('desconto_corretora', 10, 2)->unsigned()->default(0.00);
            $table->decimal('desconto_corretor', 10, 2)->unsigned()->default(0.00);
            $table->unsignedBigInteger('plano_id');
            $table->unsignedBigInteger('tabela_origens_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('financeiro_id');
            $table->date('data_baixa');
            $table->string('codigo_corretora');
            $table->string('codigo_vendedor');
            $table->string('cnpj');
            $table->string('razao_social');
            $table->integer('quantidade_vidas');
            $table->decimal('taxa_adesao', 10, 2);
            $table->decimal('valor_plano', 10, 2);
            $table->decimal('valor_total', 10, 2);
            $table->date('vencimento_boleto');
            $table->decimal('valor_boleto', 10, 2);
            $table->string('codigo_cliente')->nullable();
            $table->string('senha_cliente')->nullable();
            $table->decimal('valor_plano_odonto', 10, 2);
            $table->decimal('valor_plano_saude', 10, 2);
            $table->string('codigo_saude', 150)->nullable();
            $table->string('codigo_odonto', 150)->nullable();
            $table->string('codigo_externo', 50);
            $table->date('data_boleto');
            $table->string('responsavel');
            $table->string('telefone')->nullable();
            $table->string('celular');
            $table->string('email');
            $table->string('cidade');
            $table->string('uf');
            $table->integer('plano_contrado');
            $table->string('desconto_operadora', 50)->default('0');
            $table->integer('quantidade_parcelas')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('contrato_empresarial');
    }
};
