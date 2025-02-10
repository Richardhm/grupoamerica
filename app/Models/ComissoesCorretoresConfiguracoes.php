<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComissoesCorretoresConfiguracoes extends Model
{
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
