<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComissaoCorretorConfiguracao extends Model
{
    // Usa a conexão tenant dinâmica
    protected $connection = 'tenant';

    protected $table = 'comissoes_corretores_configuracoes';

    protected $fillable = [
        'plano_id',
        'user_id',
        'administradora_id',
        'tabela_origens_id',
        'valor',
        'parcela'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'parcela' => 'integer',
    ];
}
