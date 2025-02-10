<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    // Usa a conexão tenant dinâmica
    protected $connection = 'tenant';

    protected $table = 'contratos';

    protected $fillable = [
        'cliente_id',
        'administradora_id',
        'acomodacao_id',
        'tabela_origens_id',
        'plano_id',
        'financeiro_id',
        'coparticipacao',
        'odonto',
        'codigo_externo',
        'data_vigencia',
        'data_boleto',
        'data_baixa',
        'valor_adesao',
        'valor_plano',
        'desconto_corretora',
        'desconto_corretor',
        'data_analise',
        'data_emissao',
        'estorno',
        'data_baixa_estorno'
    ];

    protected $casts = [
        'data_vigencia' => 'date',
        'data_boleto' => 'date',
        'data_baixa' => 'date',
        'data_analise' => 'date',
        'data_emissao' => 'date',
        'data_baixa_estorno' => 'date',
        'estorno' => 'boolean',
        'coparticipacao' => 'boolean',
        'odonto' => 'boolean'
    ];

    public function administradora()
    {
        return $this->belongsTo(Administradora::class);
    }

    public function financeiro()
    {
        return $this->belongsTo(EstagioFinanceiro::class,'financeiro_id','id');
    }

    public function cidade()
    {
        return $this->belongsTo(TabelaOrigens::class,'tabela_origens_id','id');
    }

    public function acomodacao()
    {
        return $this->belongsTo(Acomodacao::class);
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class);
    }

    public function clientes()
    {
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }

    public function comissao()
    {
        return $this->hasOne(Comissoes::class);
    }

    public function somarCotacaoFaixaEtaria()
    {
        return $this->hasMany(CotacaoFaixaEtaria::class)
            ->selectRaw("cotacao_faixa_etarias.contrato_id,sum(cotacao_faixa_etarias.quantidade) as soma")
            ->groupBy("cotacao_faixa_etarias.contrato_id");
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }








}
