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
        Schema::create('tabelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administradora_id')->constrained('administradoras')->onDelete('cascade');
            $table->foreignId('tabela_origens_id')->constrained('tabela_origens')->onDelete('cascade');
            $table->foreignId('plano_id')->constrained('planos')->onDelete('cascade');
            $table->foreignId('acomodacao_id')->constrained('acomodacoes')->onDelete('cascade');
            $table->foreignId('faixa_etaria_id')->constrained('faixa_etarias')->onDelete('cascade');
            $table->boolean('coparticipacao')->default(0); // 0 = Sem Coparticipação, 1 = Com Coparticipação
            $table->boolean('odonto')->default(0); // 0 = Sem Odonto, 1 = Com Odonto
            $table->decimal('valor', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabelas');
    }
};
