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
        Schema::connection('tenant')->create('comissoes_corretores_lancadas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comissoes_id');
            $table->integer('parcela');
            $table->date('data')->nullable();
            $table->decimal('valor', 10, 2);
            $table->decimal('valor_pago', 10, 2)->nullable();
            $table->decimal('desconto', 10, 2)->default(0.00);
            $table->decimal('porcentagem_paga', 10, 2)->nullable();
            $table->tinyInteger('status_financeiro')->default(0);
            $table->tinyInteger('status_gerente')->default(0);
            $table->tinyInteger('status_apto_pagar')->default(0);
            $table->tinyInteger('status_comissao')->default(0);
            $table->tinyInteger('finalizado')->default(0);
            $table->date('data_antecipacao')->nullable();
            $table->date('data_baixa')->nullable();
            $table->date('data_baixa_gerente')->nullable();
            $table->date('data_baixa_finalizado')->nullable();
            $table->string('documento_gerador', 50)->nullable();
            $table->tinyInteger('estorno')->default(0);
            $table->date('data_baixa_estorno')->nullable();
            $table->tinyInteger('cancelados')->default(0);
            $table->tinyInteger('atual')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('comissoes_corretores_lancadas');
    }
};
