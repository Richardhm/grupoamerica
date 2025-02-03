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
        Schema::create('pdf', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plano_id')->constrained('planos')->onDelete('cascade');
            $table->foreignId('tabela_origens_id')->nullable()->constrained('tabela_origens')->onDelete('set null');

            $table->string('linha01')->nullable();
            $table->string('linha02')->nullable();
            $table->string('linha03')->nullable();

            $table->string('consultas_eletivas_total')->nullable();
            $table->string('consultas_de_urgencia_total')->nullable();
            $table->string('exames_simples_total')->nullable();
            $table->string('exames_complexos_total')->nullable();
            $table->string('terapias_especiais_total')->nullable();
            $table->string('demais_terapias_total')->nullable();
            $table->string('internacoes_total')->nullable();
            $table->string('cirurgia_total')->nullable();

            $table->string('consultas_eletivas_parcial')->nullable();
            $table->string('consultas_de_urgencia_parcial')->nullable();
            $table->string('exames_simples_parcial')->nullable();
            $table->string('exames_complexos_parcial')->nullable();
            $table->string('terapias_especiais_parcial')->nullable();
            $table->string('demais_terapias_parcial')->nullable();
            $table->string('internacoes_parcial')->nullable();
            $table->string('cirurgia_parcial')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pdf');
    }
};
