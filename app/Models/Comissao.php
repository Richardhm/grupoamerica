<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comissao extends Model
{
    // Usa a conexão tenant dinâmica
    protected $connection = 'tenant';

    protected $table = 'comissoes';

    protected $fillable = [
        'corretora_id',
        'data',
        'plano_id',
        'user_id',
        'administradora_id',
        'tabela_origens_id',
        'contrato_id',
        'contrato_empresarial_id',
        'empresarial'
    ];

    protected $casts = [
        'data' => 'date',
        'empresarial' => 'boolean',
    ];
}
