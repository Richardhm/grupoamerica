<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComissaoCorretorDefault extends Model
{
    // Usa a conexão tenant dinâmica
    protected $connection = 'tenant';

    protected $table = 'comissoes_corretores_default';

    protected $fillable = [
        'corretora_id',
        'plano_id',
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
