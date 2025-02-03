<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function index()
    {
        return view('clientes.index');
    }

    public function listar()
    {
            $user_id = auth()->user()->id;
            // Consulta base
            $query = DB::connection('tenant')->table('comissoes_corretores_lancadas')
                ->join('comissoes', 'comissoes.id', '=', 'comissoes_corretores_lancadas.comissoes_id')
                ->join('contratos', 'contratos.id', '=', 'comissoes.contrato_id')
                ->join('clientes', 'clientes.id', '=', 'contratos.cliente_id')
                ->join('users', 'users.id', '=', 'clientes.user_id')
                ->join('estagio_financeiros', 'estagio_financeiros.id', '=', 'contratos.financeiro_id')

                ->leftJoin('cliente_estagiario', 'cliente_estagiario.cliente_id', '=', 'clientes.id')
                ->leftJoin('users as estagiarios', 'estagiarios.id', '=', 'cliente_estagiario.user_id')

                ->select(
                    DB::raw("DATE_FORMAT(contratos.created_at, '%d/%m/%Y') as data"),
                    'contratos.codigo_externo as orcamento',
                    'users.name as corretor',
                    'clientes.nome as cliente',
                    'clientes.cpf as cpf',
                    'clientes.user_id as user_id',
                    'clientes.quantidade_vidas as quantidade_vidas',
                    'contratos.valor_plano as valor_plano',
                    'contratos.valor_adesao as valor_adesao',
                    'clientes.cateirinha as carteirinha',
                    'contratos.created_at as data_contrato',
                    'contratos.data_vigencia as data_vigencia',
                    'contratos.data_boleto as data_boleto',
                    'contratos.codigo_externo as codigo_externo',
                    'contratos.id',
                    'estagio_financeiros.nome as parcelas',
                    DB::raw("(SELECT DATE_FORMAT(data, '%d/%m/%Y') FROM comissoes_corretores_lancadas WHERE comissoes_id = comissoes.id AND status_financeiro = 0 ORDER BY data ASC LIMIT 1) as vencimento"),
                    DB::raw("DATE_FORMAT(clientes.data_nascimento, '%d/%m/%Y') as data_nascimento"),
                    DB::raw("COALESCE(estagiarios.name, users.name) as estagiario"),
                    'clientes.celular as fone',
                    'clientes.email as email',
                    'clientes.cidade as cidade',
                    'clientes.bairro as bairro',
                    'clientes.uf as uf',
                    'clientes.rua as rua',
                    'clientes.cep as cep',
                    'clientes.complemento as complemento',
                    DB::raw("
                    CASE
                    WHEN (SELECT data
                          FROM comissoes_corretores_lancadas
                          WHERE comissoes_id = comissoes.id AND status_financeiro = 0
                          ORDER BY data ASC LIMIT 1) < CURDATE()
                          AND estagio_financeiros.id != 10
                    THEN 'Atrasado'
                    ELSE 'Aprovado'
                 END AS status
                    ")
                );


            $query->where('contratos.plano_id', 1);
            $query->where('clientes.user_id',$user_id);
            $query->groupBy('comissoes_corretores_lancadas.comissoes_id');


            return response()->json($query->get());
    }


    public function listarColetivo()
    {
        $user_id = auth()->user()->id;
        $query = DB::connection('tenant')->table('comissoes_corretores_lancadas')
            ->select(
                DB::raw("DATE_FORMAT(contratos.created_at,'%d/%m/%Y') as data"),
                DB::raw("DATE_FORMAT(contratos.created_at,'%Y-%m-%d') as data_contrato"),
                'contratos.codigo_externo as orcamento',
                'users.name as corretor',
                'clientes.nome as cliente',
                'clientes.desconto_operadora as desconto_operadora',
                'clientes.quantidade_parcelas as quantidade_parcelas',
                'clientes.cpf as cpf',
                'clientes.quantidade_vidas as quantidade_vidas',
                'contratos.valor_plano as valor_plano',
                'contratos.id',
                'estagio_financeiros.nome as parcelas',
                'administradoras.nome as administradora',
                'estagio_financeiros.nome as status',
                DB::raw("CASE
                        WHEN comissoes_corretores_lancadas.data < CURDATE() AND estagio_financeiros.id != 10
                        THEN 'Atrasado'
                        ELSE 'Aprovado'
                    END AS resposta"),
                'clientes.data_nascimento as nascimento',
                'clientes.celular as fone',
                'clientes.email as email',
                'clientes.cidade as cidade',
                'clientes.bairro as bairro',
                'clientes.rua as rua',
                'clientes.cep as cep',
                'clientes.uf as uf',
                'clientes.complemento as complemento',
                'contratos.valor_adesao as valor_adesao',
                'contratos.desconto_corretora as desconto_corretora',
                'contratos.desconto_corretor as desconto_corretor',
                'comissoes_corretores_lancadas.status_financeiro as financeiro_id',
                DB::raw("DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') as vencimento")
            )
            ->join('comissoes', 'comissoes.id', '=', 'comissoes_corretores_lancadas.comissoes_id')
            ->join('contratos', 'contratos.id', '=', 'comissoes.contrato_id')
            ->join('clientes', 'clientes.id', '=', 'contratos.cliente_id')
            ->join('users', 'users.id', '=', 'clientes.user_id')
            ->join(DB::raw('grupoamerica.administradoras'), 'administradoras.id', '=', 'contratos.administradora_id')
            ->join('estagio_financeiros', 'estagio_financeiros.id', '=', 'contratos.financeiro_id')
            ->where(function($query) {
                $query->where('status_financeiro', 0)
                    ->orWhere(function($query) {
                        $query->where('status_financeiro', 1)
                            ->where('parcela', 7);
                    });
            })
            ->where('clientes.user_id',$user_id)
            ->where('contratos.plano_id', 3)
            ->groupBy('contratos.id');

        return response()->json($query->get());
    }

    public function listarEmpresarial(Request $request)
    {
        $user_id = auth()->user()->id;
        $query = DB::connection('tenant')->table('comissoes_corretores_lancadas')
            ->select(
                DB::raw("DATE_FORMAT(contrato_empresarial.created_at,'%d/%m/%Y') as created_at"),
                'contrato_empresarial.codigo_externo as codigo_externo',
                'users.name as usuario',
                'contrato_empresarial.razao_social',
                'contrato_empresarial.cnpj',
                'contrato_empresarial.quantidade_vidas',
                'contrato_empresarial.valor_plano',
                'contrato_empresarial.data_analise',
                'contrato_empresarial.email as email',
                'contrato_empresarial.celular as fone',
                'contrato_empresarial.cidade as cidade',
                'contrato_empresarial.uf as uf',
                'planos.nome as plano',
                DB::raw("DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') as vencimento"),
                'estagio_financeiros.nome as status',
                'contrato_empresarial.id as id',
                DB::raw("CASE
                        WHEN comissoes_corretores_lancadas.data < CURDATE() AND estagio_financeiros.id != 10 THEN 'Atrasado'
                        ELSE 'Aprovado'
                    END AS resposta"),
                'contrato_empresarial.valor_plano_saude as valor_saude',
                'contrato_empresarial.valor_plano_odonto as valor_odonto',
                'contrato_empresarial.codigo_saude as codigo_saude',
                'contrato_empresarial.codigo_odonto as codigo_odonto',
                'contrato_empresarial.codigo_corretora as codigo_corretora',
                'contrato_empresarial.senha_cliente as senha_cliente',
                'contrato_empresarial.taxa_adesao as taxa_adesao',
                'contrato_empresarial.valor_total as valor_total',
                'contrato_empresarial.valor_boleto as valor_boleto',
                'contrato_empresarial.vencimento_boleto as vencimento_boleto',
                'contrato_empresarial.data_boleto as data_boleto',
                'tabela_origens.nome as tabela_origens',
                'contrato_empresarial.responsavel as responsavel',
                'contrato_empresarial.plano_contrado as plano_contrado'
            )
            ->join('comissoes', 'comissoes.id', '=', 'comissoes_corretores_lancadas.comissoes_id')
            ->join('contrato_empresarial', 'contrato_empresarial.id', '=', 'comissoes.contrato_empresarial_id')
            ->join('users', 'users.id', '=', 'comissoes.user_id')
            ->join(DB::raw('grupoamerica.planos'), 'planos.id', '=', 'comissoes.plano_id')
            ->join('estagio_financeiros', 'estagio_financeiros.id', '=', 'contrato_empresarial.financeiro_id')
            ->join(DB::raw('grupoamerica.tabela_origens'), 'tabela_origens.id', '=', 'comissoes.plano_id')
            ->where(function($query) {
                $query->where('comissoes.empresarial', 1)
                    ->where(function($query) {
                        $query->where('comissoes_corretores_lancadas.status_financeiro', 0)
                            ->orWhere(function($query) {
                                $query->where('comissoes_corretores_lancadas.status_financeiro', 1)
                                    ->where('comissoes_corretores_lancadas.parcela', 6);
                            });
                    });
            })
            ->where('contrato_empresarial.user_id', $user_id)
            ->groupBy('comissoes_corretores_lancadas.comissoes_id');
        return response()->json($query->get());
    }




}
