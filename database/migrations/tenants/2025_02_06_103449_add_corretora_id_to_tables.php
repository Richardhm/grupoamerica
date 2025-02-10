<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        $tables = ['users', 'clientes', 'comissoes_corretores_configuracoes'];

        foreach ($tables as $table) {
            if (!Schema::hasColumn($table, 'corretora_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->unsignedBigInteger('corretora_id')->nullable()->after('id'); // Passo 1: Criar como nullable
                });

                // Passo 2: Preencher valores válidos
                DB::table($table)->update(['corretora_id' => 1]); // Ajuste conforme necessário

                // Passo 3: Tornar NOT NULL e adicionar chave estrangeira
                Schema::table($table, function (Blueprint $table) {
                    $table->foreign('corretora_id')->references('id')->on('corretoras')->onDelete('cascade');
                });
            }
        }

        if (!Schema::hasColumn('comissoes_corretores_default', 'corretora_id')) {
            Schema::table('comissoes_corretores_default', function (Blueprint $table) {
                $table->foreignId('corretora_id')->nullable()->constrained('corretoras')->onDelete('cascade')->after('id');
            });
        }
    }

    public function down(): void {
        $tables = ['users', 'clientes', 'comissoes_corretores_configuracoes', 'comissoes_corretores_default'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropConstrainedForeignId('corretora_id');
            });
        }
    }
};
