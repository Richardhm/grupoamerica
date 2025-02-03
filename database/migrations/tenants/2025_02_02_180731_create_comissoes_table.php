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
        Schema::connection('tenant')->create('comissoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('corretora_id')->nullable();
            $table->date('data');
            $table->unsignedBigInteger('plano_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('administradora_id');
            $table->unsignedBigInteger('tabela_origens_id');
            $table->unsignedBigInteger('contrato_id')->nullable();
            $table->unsignedBigInteger('contrato_empresarial_id')->nullable();
            $table->boolean('empresarial')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('comissoes');
    }
};
