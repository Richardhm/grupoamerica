<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContratoEmpresarial extends Model
{
    protected $connection = 'tenant';
    protected $table = 'contrato_empresarial'; // Nome da tabela

    protected $fillable = [
        'data_analise',
        'desconto_corretora',
        'desconto_corretor',
        'plano_id',
        'tabela_origens_id',
        'user_id',
        'financeiro_id',
        'data_baixa',
        'codigo_corretora',
        'codigo_vendedor',
        'cnpj',
        'razao_social',
        'quantidade_vidas',
        'taxa_adesao',
        'valor_plano',
        'valor_total',
        'vencimento_boleto',
        'valor_boleto',
        'codigo_cliente',
        'senha_cliente',
        'valor_plano_odonto',
        'valor_plano_saude',
        'codigo_saude',
        'codigo_odonto',
        'codigo_externo',
        'data_boleto',
        'responsavel',
        'telefone',
        'celular',
        'email',
        'cidade',
        'uf',
        'plano_contrado',
        'desconto_operadora',
        'quantidade_parcelas',
        'corretora_id'
    ];

    protected $dates = [
        'data_analise',
        'data_baixa',
        'vencimento_boleto',
        'data_boleto',
        'created_at',
        'updated_at',
    ];

    /**
     * Relacionamento com o usuário (corretor)
     */
    public function corretor()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Relacionamento com a tabela de planos
     */
    public function plano()
    {
        return $this->belongsTo(\App\Models\Tenants\Plano::class, 'plano_id');
    }

    /**
     * Relacionamento com a tabela de financeiro
     */
    public function financeiro()
    {
        return $this->belongsTo(EstagioFinanceiro::class,'financeiro_id','id');
    }

    public function comissao()
    {
        return $this->hasOne(Comissoes::class);
    }
}
