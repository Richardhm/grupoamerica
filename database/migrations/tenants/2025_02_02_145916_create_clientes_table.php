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
        Schema::connection('tenant')->create('clientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nome', 255)->nullable();
            $table->string('cidade', 255)->nullable();
            $table->string('celular', 255)->nullable();
            $table->string('telefone', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('cpf', 255)->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('cep', 255)->nullable();
            $table->string('rua', 255)->nullable();
            $table->string('bairro', 255)->nullable();
            $table->string('complemento', 255)->nullable();
            $table->string('uf', 255)->nullable();
            $table->string('cnpj', 255)->nullable();
            $table->boolean('pessoa_fisica')->nullable();
            $table->boolean('pessoa_juridica')->nullable();
            $table->string('codigo_externo', 50)->nullable();
            $table->boolean('dependente')->nullable();
            $table->string('nm_plano', 255)->nullable();
            $table->string('numero_registro_plano', 255)->nullable();
            $table->string('rede_plano', 255)->nullable();
            $table->string('tipo_acomodacao_plano', 255)->nullable();
            $table->string('segmentacao_plano', 255)->nullable();
            $table->string('cateirinha', 100)->nullable();
            $table->integer('quantidade_vidas')->nullable();
            $table->boolean('dados')->default(0);
            $table->date('baixa')->nullable();
            $table->string('desconto_operadora', 255)->nullable();
            $table->integer('quantidade_parcelas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('clientes');
    }
};
