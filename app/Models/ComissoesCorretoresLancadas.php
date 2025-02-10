<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComissoesCorretoresLancadas extends Model
{
    protected $connection = 'tenant';

    protected $table = 'comissoes_corretores_lancadas';

    protected $fillable = [
        'comissoes_id',
        'parcela',
        'data',
        'valor',
        'valor_pago',
        'desconto',
        'porcentagem_paga',
        'status_financeiro',
        'status_gerente',
        'status_apto_pagar',
        'status_comissao',
        'finalizado',
        'data_antecipacao',
        'data_baixa',
        'data_baixa_gerente',
        'data_baixa_finalizado',
        'documento_gerador',
        'estorno',
        'data_baixa_estorno',
        'cancelados',
        'atual',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'valor_pago' => 'decimal:2',
        'desconto' => 'decimal:2',
        'porcentagem_paga' => 'decimal:2',
        'parcela' => 'integer',
        'status_financeiro' => 'boolean',
        'status_gerente' => 'boolean',
        'status_apto_pagar' => 'boolean',
        'status_comissao' => 'boolean',
        'finalizado' => 'boolean',
        'estorno' => 'boolean',
        'cancelados' => 'boolean',
        'atual' => 'boolean',
    ];

    public function comissao()
    {
        return $this->belongsTo(Comissoes::class,'comissoes_id','id');
    }
}
