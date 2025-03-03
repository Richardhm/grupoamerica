<?php

namespace App\Http\Controllers;

use App\Jobs\MudarStatusParaNaoPagoJob;
use App\Models\Administradora;
use App\Models\Comissoes;
use App\Models\Contrato;

use App\Models\Odonto;
use App\Models\TabelaOrigens;
use App\Models\Administradora as Administradoras;
use App\Models\ContratoEmpresarial;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Plano as Planos;
use App\Models\Acomodacao;
use App\Models\User;
use App\Models\Dependente as Dependentes;
use App\Models\ComissoesCorretoresDefault;
use App\Models\MotivoCancelado as MotivoCancelados;
use App\Models\Corretora;
use App\Models\ComissoesCorretoresLancadas;


use App\Models\FolhaMes;
use App\Models\FolhaPagamento;
use App\Models\ValoresCorretoresLancados;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf as PDFFile;
use App\Jobs\ProcessarPagamentoJob;

class GerenteController extends Controller
{
    public function tabelaVazia()
    {
        return [];
    }

    public function totalizarMes(Request $request)
    {

        $corretora_id = auth()->user()->corretora_id;
        $mes = $request->mes;
        $ano = $request->ano;
        $dados = DB::connection('tenant')->table('valores_corretores_lancadas')
            ->selectRaw("SUM(valor_comissao) as total_comissao")
            ->selectRaw("SUM(valor_salario) as total_salario")
            ->selectRaw("SUM(valor_premiacao) as valor_premiacao")
            ->selectRaw("SUM(valor_desconto) as valor_desconto")
            ->selectRaw("SUM(valor_estorno) as valor_estorno")
            ->selectRaw("SUM(valor_total) as total_mes")
            ->whereMonth("data", $mes)
            ->whereYear("data", $ano)
            ->where("corretora_id",auth()->user()->corretora_id)
            ->first();


        $dados->total_comissao = number_format($dados->total_comissao, 2, ',', '.');
        $dados->total_salario = number_format($dados->total_salario, 2, ',', '.');
        $dados->valor_premiacao = number_format($dados->valor_premiacao, 2, ',', '.');
        $dados->valor_desconto = number_format($dados->valor_desconto, 2, ',', '.');
        $dados->valor_estorno = number_format($dados->valor_estorno, 2, ',', '.');
        $dados->total_mes = number_format($dados->total_mes, 2, ',', '.');


        $total_individual_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->whereHas('comissao',function($query){
                $query->where("plano_id",1);
                $query->where("corretora_id",auth()->user()->corretora_id);
            })->count();


        $total_coletivo_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado","=",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->whereHas('comissao',function($query){
                $query->where("plano_id",3);
                $query->where("corretora_id",auth()->user()->corretora_id);
            })->count();

        $total_empresarial_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->whereHas('comissao',function($query){
                $query->where("plano_id","!=",1);
                $query->where("plano_id","!=",3);
                $query->where("corretora_id",auth()->user()->corretora_id);
            })->count();


        $total_empresarial = DB::connection('tenant')->select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_empresarial_valor FROM (
            SELECT SUM(valor) AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1

            AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            AND year(data_baixa_finalizado) = {$ano} AND corretora_id = {$corretora_id}
            ) AS plano1,
            (
            SELECT SUM(valor) AS total_plano3 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes}
            AND year(data_baixa_finalizado) = {$ano} AND corretora_id = {$corretora_id}
            ) AS plano3
        ")[0]->total_empresarial_valor;


        $total_individual = DB::connection('tenant')->select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_individual_valor FROM (
            SELECT
                    SUM(valor)
                    AS total_plano1
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            AND year(data_baixa_finalizado) = {$ano} AND corretora_id = {$corretora_id}
            ) AS plano1,
            (
            SELECT
                SUM(valor)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes}
            AND year(data_baixa_finalizado) = {$ano} AND corretora_id = {$corretora_id}
            ) AS plano3;
        ")[0]->total_individual_valor;



        $total_coletivo = DB::connection('tenant')->select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_coletivo_valor FROM (
            SELECT
                SUM(valor)
                AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            AND year(data_baixa_finalizado) = {$ano} AND corretora_id = {$corretora_id}
            ) AS plano1,
            (
            SELECT
                SUM(valor)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes}
            AND year(data_baixa_finalizado) = {$ano} AND corretora_id = {$corretora_id}
            ) AS plano3
        ")[0]->total_coletivo_valor;



        return [
            "dados" => $dados,
            "total_individual_quantidade" => $total_individual_quantidade,
            "total_coletivo_quantidade" => $total_coletivo_quantidade,
            "total_empresarial_quantidade" => $total_empresarial_quantidade,
            "total_empresarial" => number_format($total_empresarial,2,",","."),
            "total_individual" => number_format($total_individual,2,",","."),
            "total_coletivo" => number_format($total_coletivo,2,",",".")
        ];

    }

    public function montarTabelaMesModal(Request $request)
    {
        $mes = $request->mes;
        $users = DB::connection('tenant')->table('valores_corretores_lancadas')
            ->selectRaw("(select name from users where users.id = valores_corretores_lancadas.user_id) as user,valor_comissao,valor_salario,valor_premiacao")
            ->selectRaw("valor_total as total")
            ->whereMonth("data",$mes)
            ->where("corretora_id",auth()->user()->corretora_id)
            ->get();
        return view('gerente.table-modal',[
            "dados" => $users
        ]);
    }



    public function index()
    {
        $corretora_id = auth()->user()->corretora_id;

        // Dados do mês aberto
        $folha_aberto = $this->getFolhaAberto($corretora_id);
        $mes_aberto = $folha_aberto->first()->mes ?? null;
        $mes = $mes_aberto ? date('m', strtotime($mes_aberto)) : 0;
        $ano = $mes_aberto ? date('Y', strtotime($mes_aberto)) : 0;

        // Dados totais do mês
        $dados_totais = $this->getDadosTotais($mes, $ano, $corretora_id);

        // Totais de comissões por tipo (empresarial, individual, coletivo)
        $totais_comissoes = $this->getTotaisComissoes($mes, $ano, $corretora_id);

        // Dados de usuários aptos a pagar
        $users_apto_apagar = $this->getUsersAptoApagar($mes, $ano, $corretora_id);

        // Dados de contratos (geral, individual, coletivo, empresarial)
        $dados_contratos = $this->getDadosContratos($corretora_id, $mes, $ano);


        // Dados de comissões a receber e recebidas
        $dados_comissoes = $this->getDadosComissoes($corretora_id);

        // Dados de administradoras
        $administradoras_mes = $this->getAdministradorasMes($corretora_id);

        // Dados adicionais
        $quantidade_geral = $this->getQuantidadeGeral($corretora_id);
        $total_valor_geral = $this->getTotalValorGeral($corretora_id);
        $quantidade_vidas_geral = $this->getQuantidadeVidasGeral($corretora_id);

        return view('gerente.index', [
            "administradoras_coletivo" => Administradora::whereRaw("nome NOT LIKE '%hapvida%'")->get(),
            "planos_empresarial" => Planos::where("empresarial", 1)->get(),
            "status_disabled" => is_null($mes_aberto),
            "quat_comissao_a_receber" => $dados_comissoes->a_receber->quantidade,
            "quat_comissao_recebido" => $dados_comissoes->recebidas->quantidade,
            "valor_quat_comissao_a_receber" => $dados_comissoes->a_receber->valor,
            "valor_quat_comissao_recebido" => $dados_comissoes->recebidas->valor,
            "datas_select" => $this->getDatasSelect(),
            "total_mes_comissao" => $dados_totais->total_mes,
            "administradoras_mes" => $administradoras_mes,
            "administradoras" => Administradora::orderBy('id', 'desc')->get(),
            "users" => $this->getUsers($corretora_id,$mes,$ano),
            "users_apto_apagar" => $users_apto_apagar,
            "mes" => $mes,

            "quantidade_geral" => $quantidade_geral,
            "total_valor_geral" => $total_valor_geral,
            "quantidade_vidas_geral" => $quantidade_vidas_geral,

            "total_quantidade_recebidos" => $dados_contratos->geral->quantidade_recebidos + 0,
            "total_valor_recebidos" => $dados_contratos->geral->valor_recebidos + 0,
            "quantidade_vidas_recebidas" => $dados_contratos->geral->quantidade_vidas_recebidas + 0,

            "total_quantidade_a_receber" => $dados_contratos->geral->quantidade_a_receber,
            "total_valor_a_receber" => $dados_contratos->geral->valor_a_receber,
            "quantidade_vidas_a_receber" => $dados_contratos->geral->quantidade_vidas_a_receber + 0,

            "qtd_atrasado" => $dados_contratos->geral->qtd_atrasado + 0,
            "qtd_atrasado_valor" => $dados_contratos->geral->qtd_atrasado_valor + 0,
            "qtd_atrasado_quantidade_vidas" => $dados_contratos->geral->qtd_atrasado_quantidade_vidas + 0,

            "qtd_finalizado" => $dados_contratos->geral->qtd_finalizado + 0,
            "quantidade_valor_finalizado" => $dados_contratos->geral->quantidade_valor_finalizado + 0,
            "qtd_finalizado_quantidade_vidas" => $dados_contratos->geral->qtd_finalizado_quantidade_vidas + 0,

            "qtd_cancelado" => $dados_contratos->geral->qtd_cancelado + 0,
            "quantidade_valor_cancelado" => $dados_contratos->geral->quantidade_valor_cancelado + 0,
            "qtd_cancelado_quantidade_vidas" => $dados_contratos->geral->qtd_cancelado_quantidade_vidas + 0,

            'total_empresarial_quantidade' => $totais_comissoes->total_empresarial_quantidade,
            'total_individual_quantidade' => $totais_comissoes->total_individual_quantidade,
            'total_coletivo_quantidade' => $totais_comissoes->total_coletivo_quantidade,

            'total_empresarial' => number_format($totais_comissoes->total_empresarial, 2, ",", "."),
            'total_individual' => number_format($totais_comissoes->total_individual, 2, ",", "."),
            'total_coletivo' => number_format($totais_comissoes->total_coletivo, 2, ",", "."),

            'total_estorno' => 0,

            'total_comissao' => number_format($totais_comissoes->total_individual + $totais_comissoes->total_coletivo + $totais_comissoes->total_empresarial, 2, ",", "."),
            'total_salario' => $dados_totais->total_salario,
            'total_premiacao' => $dados_totais->valor_premiacao,
            'total_desconto' => $dados_totais->valor_desconto,
            'estorno_geral' => $dados_totais->valor_estorno,
            'total_mes' => $dados_totais->total_mes,

            /************************* Individual *******************************/
            "quantidade_vidas_geral_individual" => $dados_contratos->individual->quantidade_vidas_geral,
            "total_valor_geral_individual" => $dados_contratos->individual->total_valor_geral,

            "quantidade_individual_geral" => $dados_contratos->individual->quantidade_geral,
            "total_valor_geral_individual" => $dados_contratos->individual->total_valor_geral,
            "total_quantidade_recebidos_individual" => $dados_contratos->individual->quantidade_recebidos,
            "total_valor_recebidos_individual" => $dados_contratos->individual->valor_recebidos,
            "quantidade_vidas_recebidas_individual" => $dados_contratos->individual->quantidade_vidas_recebidas,

            "total_quantidade_a_receber_individual" => $dados_contratos->individual->quantidade_a_receber,
            "total_valor_a_receber_individual" => $dados_contratos->individual->valor_a_receber,
            "quantidade_vidas_a_receber_individual" => $dados_contratos->individual->quantidade_vidas_a_receber,
            "qtd_atrasado_individual" => $dados_contratos->individual->qtd_atrasado,
            "qtd_atrasado_valor_individual" => $dados_contratos->individual->qtd_atrasado_valor,
            "qtd_atrasado_quantidade_vidas_individual" => $dados_contratos->individual->qtd_atrasado_quantidade_vidas,
            "qtd_cancelado_individual" => $dados_contratos->individual->qtd_cancelado,
            "quantidade_valor_cancelado_individual" => $dados_contratos->individual->quantidade_valor_cancelado,
            "qtd_cancelado_quantidade_vidas_individual" => $dados_contratos->individual->qtd_cancelado_quantidade_vidas,
            "qtd_finalizado_individual" => $dados_contratos->individual->qtd_finalizado,
            "quantidade_valor_finalizado_individual" => $dados_contratos->individual->quantidade_valor_finalizado,
            "qtd_finalizado_quantidade_vidas_individual" => $dados_contratos->individual->qtd_finalizado_quantidade_vidas,

            /********************************************Coletivo */
            "quantidade_coletivo_geral" => $dados_contratos->coletivo->quantidade_geral,
            "total_valor_geral_coletivo" => $dados_contratos->coletivo->total_valor_geral,
            "total_quantidade_recebidos_coletivo" => $dados_contratos->coletivo->quantidade_recebidos,
            "quantidade_vidas_geral_coletivo" => $dados_contratos->coletivo->quantidade_vidas_geral,
            "total_valor_recebidos_coletivo" => $dados_contratos->coletivo->valor_recebidos,
            "quantidade_vidas_recebidas_coletivo" => $dados_contratos->coletivo->quantidade_vidas_recebidas,
            "total_quantidade_a_receber_coletivo" => $dados_contratos->coletivo->quantidade_a_receber,
            "total_valor_a_receber_coletivo" => $dados_contratos->coletivo->valor_a_receber,
            "quantidade_vidas_a_receber_coletivo" => $dados_contratos->coletivo->quantidade_vidas_a_receber,
            "qtd_atrasado_coletivo" => $dados_contratos->coletivo->qtd_atrasado,
            "qtd_atrasado_valor_coletivo" => $dados_contratos->coletivo->qtd_atrasado_valor,
            "qtd_atrasado_quantidade_vidas_coletivo" => $dados_contratos->coletivo->qtd_atrasado_quantidade_vidas,
            "qtd_finalizado_coletivo" => $dados_contratos->coletivo->qtd_finalizado,
            "quantidade_valor_finalizado_coletivo" => $dados_contratos->coletivo->quantidade_valor_finalizado,
            "qtd_finalizado_quantidade_vidas_coletivo" => $dados_contratos->coletivo->qtd_finalizado_quantidade_vidas,
            "qtd_cancelado_coletivo" => $dados_contratos->coletivo->qtd_cancelado,
            "quantidade_valor_cancelado_coletivo" => $dados_contratos->coletivo->quantidade_valor_cancelado,
            "qtd_cancelado_quantidade_vidas_coletivo" => $dados_contratos->coletivo->qtd_cancelado_quantidade_vidas,

            /***************** Empresarial ***********************/
            "quantidade_empresarial_geral" => $dados_contratos->empresarial->quantidade_geral ?? 0,
            "total_valor_geral_empresarial" => $dados_contratos->empresarial->total_valor_geral ?? 0,
            "quantidade_vidas_geral_empresarial" => $dados_contratos->empresarial->quantidade_vidas_geral ?? 0,
            "total_quantidade_recebidos_empresarial" => $dados_contratos->empresarial->quantidade_recebidos ?? 0,
            "total_valor_recebidos_empresarial" => $dados_contratos->empresarial->valor_recebidos ?? 0,
            "quantidade_vidas_recebidas_empresarial" => $dados_contratos->empresarial->quantidade_vidas_recebidas ?? 0,
            "total_quantidade_a_receber_empresarial" => $dados_contratos->empresarial->quantidade_a_receber ?? 0,
            "total_valor_a_receber_empresarial" => $dados_contratos->empresarial->valor_a_receber ?? 0,
            "quantidade_vidas_a_receber_empresarial" => $dados_contratos->empresarial->quantidade_vidas_a_receber ?? 0,
            'qtd_atrasado_empresarial' => $dados_contratos->empresarial->qtd_atrasado ?? 0,
            "qtd_atrasado_valor_empresarial" => $dados_contratos->empresarial->qtd_atrasado_valor ?? 0,
            "qtd_atrasado_quantidade_vidas_empresarial" => $dados_contratos->empresarial->qtd_atrasado_quantidade_vidas ?? 0,
            "qtd_finalizado_empresarial" => $dados_contratos->empresarial->qtd_finalizado ?? 0,
            "quantidade_valor_finalizado_empresarial" => $dados_contratos->empresarial->quantidade_valor_finalizado ?? 0,
            "qtd_finalizado_quantidade_vidas_empresarial" => $dados_contratos->empresarial->qtd_finalizado_quantidade_vidas ?? 0,
            "qtd_cancelado_empresarial" => $dados_contratos->empresarial->qtd_cancelado ?? 0,
            "quantidade_valor_cancelado_empresarial" => $dados_contratos->empresarial->quantidade_valor_cancelado ?? 0,
            "qtd_cancelado_quantidade_vidas_empresarial" => $dados_contratos->empresarial->qtd_cancelado_quantidade_vidas ?? 0,
        ]);
    }

    private function getFolhaAberto($corretora_id)
    {
        return DB::connection('tenant')
            ->table('folha_mes')
            ->where("status", 0)
            ->where("corretora_id", $corretora_id)
            ->get();
    }

    private function getDadosTotais($mes, $ano, $corretora_id)
    {
        return DB::connection('tenant')
            ->table('valores_corretores_lancadas')
            ->selectRaw("SUM(valor_comissao) as total_comissao")
            ->selectRaw("SUM(valor_salario) as total_salario")
            ->selectRaw("SUM(valor_premiacao) as valor_premiacao")
            ->selectRaw("SUM(valor_desconto) as valor_desconto")
            ->selectRaw("SUM(valor_estorno) as valor_estorno")
            ->selectRaw("SUM(valor_total) as total_mes")
            ->whereMonth("data", $mes)
            ->whereYear("data", $ano)
            ->where("corretora_id", $corretora_id)
            ->first();
    }

    private function getTotaisComissoes($mes, $ano, $corretora_id)
    {
        return (object) [
            'total_empresarial_quantidade' => $this->getTotalComissoesPorTipo($corretora_id, $mes, $ano, 5)->quantidade,
            'total_individual_quantidade' => $this->getTotalComissoesPorTipo($corretora_id, $mes, $ano, 1)->quantidade,
            'total_coletivo_quantidade' => $this->getTotalComissoesPorTipo($corretora_id, $mes, $ano, 3)->quantidade,
            'total_empresarial' => $this->getTotalComissoesPorTipo($corretora_id, $mes, $ano, 5)->total,
            'total_individual' => $this->getTotalComissoesPorTipo($corretora_id, $mes, $ano, 1)->total,
            'total_coletivo' => $this->getTotalComissoesPorTipo($corretora_id, $mes, $ano, 3)->total,
        ];
    }

    private function getTotalComissoesPorTipo($corretora_id, $mes, $ano, $plano_id)
    {
        return DB::connection('tenant')
            ->table('comissoes_corretores_lancadas as ccl')
            ->join('comissoes as c', 'c.id', '=', 'ccl.comissoes_id')
            ->selectRaw('SUM(ccl.valor) as total, COUNT(c.id) as quantidade')
            ->where('c.plano_id', $plano_id)
            ->where('c.corretora_id', $corretora_id)
            ->where('ccl.status_apto_pagar', 1)
            ->whereMonth('ccl.data_baixa_finalizado', $mes)
            ->whereYear('ccl.data_baixa_finalizado', $ano)
            ->first();
    }

    private function getUsersAptoApagar($mes, $ano, $corretora_id)
    {
        return DB::connection('tenant')
            ->table('users')
            ->whereIn('id', function ($query) use ($mes, $ano) {
                $query->select('user_id')
                    ->from('valores_corretores_lancadas')
                    ->whereMonth('data', $mes)
                    ->whereYear('data', $ano);
            })
            ->where("corretora_id", $corretora_id)
            ->selectRaw("id as user_id, name as user")
            ->selectRaw("
        (select valor_total
         from " . DB::connection('tenant')->getTablePrefix() . "valores_corretores_lancadas
         where valores_corretores_lancadas.user_id = users.id
         and month(data) = ?
         and year(data) = ?
        ) as total
    ", [$mes, $ano]) // Passando os valores de forma segura

            ->orderBy('name')
            ->get();
    }

    private function getDadosContratos($corretora_id, $mes, $ano)
    {
        return (object) [
            'geral' => $this->getDadosContratosPorTipo($corretora_id, $mes, $ano),
            'individual' => $this->getDadosContratosPorTipo($corretora_id, $mes, $ano, 1),
            'coletivo' => $this->getDadosContratosPorTipo($corretora_id, $mes, $ano, 3),
            'empresarial' => $this->getDadosContratosEmpresarial($corretora_id, $mes, $ano),
        ];
    }

    private function getDadosContratosPorTipo($corretora_id, $mes, $ano, $plano_id = null)
    {
        $query = DB::connection('tenant')
            ->table('contratos')
            ->join('comissoes', 'contratos.id', '=', 'comissoes.contrato_id')
            ->join('comissoes_corretores_lancadas', 'comissoes.id', '=', 'comissoes_corretores_lancadas.comissoes_id')
            ->join('clientes', 'contratos.cliente_id', '=', 'clientes.id')
            ->where('comissoes_corretores_lancadas.status_financeiro', 1)
            ->where('comissoes_corretores_lancadas.status_gerente', 1)
            ->where('comissoes_corretores_lancadas.valor', '!=', 0)
            ->where('clientes.corretora_id', $corretora_id);


        if ($plano_id) {
            $query->where('contratos.plano_id', $plano_id);
        }


        return (object) [
            'quantidade_geral' => $query->count(),
            'total_valor_geral' => $query->sum('contratos.valor_plano'),
            'quantidade_vidas_geral' => $query->sum('clientes.quantidade_vidas'),
            'quantidade_recebidos' => $query->where('comissoes_corretores_lancadas.status_financeiro', 1)
                ->where('comissoes_corretores_lancadas.status_gerente', 1)
                ->count(),
            'valor_recebidos' => $query->where('comissoes_corretores_lancadas.status_financeiro', 1)
                ->where('comissoes_corretores_lancadas.status_gerente', 1)
                ->sum('contratos.valor_plano'),
            'quantidade_vidas_recebidas' => $query->where('comissoes_corretores_lancadas.status_financeiro', 1)
                ->where('comissoes_corretores_lancadas.status_gerente', 1)
                ->sum('clientes.quantidade_vidas'),
            'quantidade_a_receber' => $query->where('comissoes_corretores_lancadas.status_financeiro', 1)
                ->where('comissoes_corretores_lancadas.status_gerente', 0)
                ->count(),
            'valor_a_receber' => $query->where('comissoes_corretores_lancadas.status_financeiro', 1)
                ->where('comissoes_corretores_lancadas.status_gerente', 0)
                ->sum('contratos.valor_plano'),
            'quantidade_vidas_a_receber' => $query->where('comissoes_corretores_lancadas.status_financeiro', 1)
                ->where('comissoes_corretores_lancadas.status_gerente', 0)
                ->sum('clientes.quantidade_vidas'),
            'qtd_atrasado' => $query->whereIn('contratos.financeiro_id', [3, 4, 5, 6, 7, 8, 9, 10])
                ->whereRaw('comissoes_corretores_lancadas.DATA < CURDATE()')
                ->whereRaw('comissoes_corretores_lancadas.data_baixa IS NULL')
                ->count(),
            'qtd_atrasado_valor' => $query->whereIn('contratos.financeiro_id', [3, 4, 5, 6, 7, 8, 9, 10])
                ->whereRaw('comissoes_corretores_lancadas.DATA < CURDATE()')
                ->whereRaw('comissoes_corretores_lancadas.data_baixa IS NULL')
                ->sum('contratos.valor_plano'),
            'qtd_atrasado_quantidade_vidas' => $query->whereIn('contratos.financeiro_id', [3, 4, 5, 6, 7, 8, 9, 10])
                ->whereRaw('comissoes_corretores_lancadas.DATA < CURDATE()')
                ->whereRaw('comissoes_corretores_lancadas.data_baixa IS NULL')
                ->sum('clientes.quantidade_vidas'),
            'qtd_finalizado' => $query->where('contratos.financeiro_id', 11)
                ->count(),
            'quantidade_valor_finalizado' => $query->where('contratos.financeiro_id', 11)
                ->sum('contratos.valor_plano'),
            'qtd_finalizado_quantidade_vidas' => $query->where('contratos.financeiro_id', 11)
                ->sum('clientes.quantidade_vidas'),
            'qtd_cancelado' => $query->where('contratos.financeiro_id', 12)
                ->count(),
            'quantidade_valor_cancelado' => $query->where('contratos.financeiro_id', 12)
                ->sum('contratos.valor_plano'),
            'qtd_cancelado_quantidade_vidas' => $query->where('contratos.financeiro_id', 12)
                ->sum('clientes.quantidade_vidas'),
        ];
    }

    private function getDadosContratosEmpresarial($corretora_id, $mes, $ano)
    {
        $dados = DB::connection('tenant')
            ->table('contrato_empresarial')
            ->join('comissoes', 'contrato_empresarial.id', '=', 'comissoes.contrato_empresarial_id')
            ->join('comissoes_corretores_lancadas', 'comissoes.id', '=', 'comissoes_corretores_lancadas.comissoes_id')

            ->where('comissoes_corretores_lancadas.status_financeiro', 1)
            ->where('comissoes_corretores_lancadas.status_gerente', 1)
            ->where('comissoes_corretores_lancadas.valor', '!=', 0)
            ->where('contrato_empresarial.corretora_id', $corretora_id)
            ->selectRaw('
                COUNT(*) as quantidade_geral,
                if(SUM(valor_total)>=1,SUM(valor_total),0) as total_geral,
                if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as quantidade_vidas_geral'
            )->first();

    }

    private function getDadosComissoes($corretora_id)
    {
        return (object) [
            'a_receber' => DB::connection('tenant')
                ->table('comissoes_corretores_lancadas')
                ->join('comissoes', 'comissoes.id', '=', 'comissoes_corretores_lancadas.comissoes_id')
                ->join('contratos', 'contratos.id', '=', 'comissoes.contrato_id')
                ->join('clientes', 'clientes.id', '=', 'contratos.cliente_id')
                ->where('comissoes_corretores_lancadas.status_financeiro', 1)
                ->where('comissoes_corretores_lancadas.status_gerente', 0)
                ->where('comissoes_corretores_lancadas.valor', '!=', 0)
                ->where('clientes.corretora_id', $corretora_id)
                ->selectRaw('COUNT(*) as quantidade, SUM(comissoes_corretores_lancadas.valor) as valor')
                ->first(),
            'recebidas' => DB::connection('tenant')
                ->table('comissoes_corretores_lancadas')
                ->join('comissoes', 'comissoes.id', '=', 'comissoes_corretores_lancadas.comissoes_id')
                ->join('contratos', 'contratos.id', '=', 'comissoes.contrato_id')
                ->join('clientes', 'clientes.id', '=', 'contratos.cliente_id')
                ->where('comissoes_corretores_lancadas.status_financeiro', 1)
                ->where('comissoes_corretores_lancadas.status_gerente', 1)
                ->where('comissoes_corretores_lancadas.valor', '!=', 0)
                ->where('clientes.corretora_id', $corretora_id)
                ->selectRaw('COUNT(*) as quantidade, SUM(comissoes_corretores_lancadas.valor) as valor')
                ->first(),
        ];
    }

    private function getAdministradorasMes($corretora_id)
    {
        return DB::connection('tenant')
            ->table('comissoes_corretores_lancadas as cc')
            ->join('comissoes as c', 'c.id', '=', 'cc.comissoes_id')
            ->selectRaw('SUM(cc.valor) AS total, (SELECT nome FROM grupoamerica.administradoras WHERE id = c.administradora_id) AS administradora')
            ->where('cc.status_financeiro', 1)
            ->where('cc.status_gerente', 1)
            ->whereRaw('MONTH(cc.data) = MONTH(NOW())')
            ->whereRaw('YEAR(cc.data) = YEAR(NOW())')
            ->where('c.corretora_id', $corretora_id)
            ->groupBy('c.administradora_id')
            ->get();
    }

    private function getQuantidadeGeral($corretora_id)
    {
        return DB::connection('tenant')
            ->table('clientes')
            ->where('corretora_id', $corretora_id)
            ->count();
    }

    private function getTotalValorGeral($corretora_id)
    {
        return DB::connection('tenant')
            ->table('clientes')
            ->join('contratos', 'clientes.id', '=', 'contratos.cliente_id')
            ->where('clientes.corretora_id', $corretora_id)
            ->sum('contratos.valor_plano');
    }

    private function getQuantidadeVidasGeral($corretora_id)
    {
        return DB::connection('tenant')
            ->table('clientes')
            ->where('corretora_id', $corretora_id)
            ->sum('quantidade_vidas');
    }

    private function getDatasSelect()
    {
        return DB::connection('tenant')
            ->table('comissoes_corretores_lancadas')
            ->where('status_financeiro', 1)
            ->where('status_gerente', 1)
            ->groupBy(DB::raw('MONTH(data_baixa_gerente)'))
            ->pluck('data_baixa_gerente');
    }

    private function getUsers($corretora_id,$mes,$ano)
    {
        return DB::connection('tenant')->select("
            SELECT users.id AS id, users.name AS name
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            INNER JOIN users ON users.id = comissoes.user_id
            WHERE (status_financeiro = 1 or status_gerente = 1) and finalizado != 1 and valor != 0 and users.corretora_id = {$corretora_id} and users.id NOT IN
            (SELECT user_id FROM valores_corretores_lancadas WHERE MONTH(data) = {$mes} AND YEAR(data) = {$ano})
            GROUP BY users.id, users.name
            ORDER BY users.name
        ");
    }






    public function listarGerenteCadastrados(Request $request)
    {
        if($request->ajax()) {
            $cadastrados = DB::select("
                select
                    case when empresarial = 1 then
                        (select razao_social from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
                    else
                        (select nome from clientes where id = ((select cliente_id from contratos where contratos.id = comissoes.contrato_id)))
                    end as cliente,
                    (select nome from administradoras where administradoras.id = comissoes.administradora_id) as administradora,
                    (select name from users where users.id = comissoes.user_id) as corretor,
                    (select nome from planos where planos.id = comissoes.plano_id) as plano,
                    case when empresarial = 1 then
                       contrato_empresarial_id
                    else
                       contrato_id
                    end as contrato_id,
                    (comissoes.plano_id) as plano_id
                from comissoes
            ");
            return $cadastrados;
        }
    }

    public function mudarSalario(Request $request)
    {
        $param = ValoresCorretoresLancados::where("user_id",$request->user_id)->whereMonth("data",$request->mes);
        if($param->count() == 1) {
            ValoresCorretoresLancados::where("user_id",$request->user_id)->whereMonth("data",$request->mes)
                ->update([
                    "valor_comissao" => $request->comissao,
                    "valor_salario" => $request->salario,
                    "valor_premiacao" => $request->premiacao,
                    "valor_estorno" => $request->estorno,
                    "valor_desconto" => $request->desconto,
                    "valor_total" => $request->total
                ]);
        } else {
            $ano = date('Y');
            $co = new ValoresCorretoresLancados();
            $co->user_id = $request->user_id;
            $co->data = date($ano."-".$request->mes."-01");
            $co->valor_comissao = $request->comissao;
            $co->valor_salario = $request->salario;
            $co->valor_premiacao = $request->premiacao;
            $co->valor_total = $request->total;
            $co->valor_desconto = $request->desconto;
            $co->valor_estorno = $request->estorno;
            $co->save();
        }




    }

    public function mudarPremiacao(Request $request)
    {
        $param = ValoresCorretoresLancados::where("user_id",$request->user_id)->whereMonth("data",$request->mes);
        if($param->count() == 1) {
            ValoresCorretoresLancados::where("user_id",$request->user_id)->whereMonth("data",$request->mes)
                ->update([
                    "valor_comissao" => $request->comissao,
                    "valor_salario" => $request->salario,
                    "valor_premiacao" => $request->premiacao,
                    "valor_estorno" => $request->estorno,
                    "valor_desconto" => $request->desconto,
                    "valor_total" => $request->total
                ]);
        } else {
            $ano = date('Y');
            $co = new ValoresCorretoresLancados();
            $co->user_id = $request->user_id;
            $co->data = date($ano."-".$request->mes."-01");
            $co->valor_comissao = $request->comissao;
            $co->valor_salario = $request->salario;
            $co->valor_premiacao = $request->premiacao;
            $co->valor_total = $request->total;
            $co->valor_desconto = $request->desconto;
            $co->valor_estorno = $request->estorno;
            $co->save();
        }
    }











    public function estornoColetivo(Request $request)
    {

        $id = $request->id;

        $contratos = DB::connection('tenant')->select("
            select
                (select nome from grupoamerica.administradoras where administradoras.id = comissoes.administradora_id) as administradora,
                date_format((comissoes_corretores_lancadas.data),'%d/%m/%Y') as data,
                (contratos.codigo_externo) as codigo,
                (select nome from clientes where clientes.id = contratos.cliente_id) as cliente,
                (comissoes_corretores_lancadas.parcela) as parcela,
                (contratos.valor_plano) as valor,
                (comissoes_corretores_lancadas.valor) as total_estorno,
                contratos.id,
                comissoes.id as comissoes_id,
                comissoes.plano_id as plano,
                cancelados,
                comissoes_corretores_lancadas.id as id_lancadas
                from comissoes_corretores_lancadas
                inner join comissoes on comissoes.id = comissoes_corretores_lancadas.comissoes_id
                inner join contratos on contratos.id = comissoes.contrato_id
                where
                comissoes.plano_id = 3
                and comissoes_corretores_lancadas.valor != 0
                and comissoes_corretores_lancadas.estorno = 0
                and comissoes_corretores_lancadas.cancelados = 0
                and comissoes_corretores_lancadas.data_baixa_estorno IS NULL
                and contratos.financeiro_id = 12
                and
                exists (select * from `clientes` where `contratos`.`cliente_id` = `clientes`.`id` and `user_id` = ${id});
        ");

        return response()->json($contratos);
    }


    public function cadastrarHistoricoFolhaMes(Request $request)
    {

        $date = \DateTime::createFromFormat('Y-m-d', $request->data);
        $formattedDate = $date->format('Y-m-d');
        $corretora_id = auth()->user()->corretora_id;

        $mes = date("m",strtotime($formattedDate));
        $ano = date("Y",strtotime($formattedDate));

        $users = DB::connection('tenant')->table('valores_corretores_lancadas')
            ->selectRaw("(SELECT NAME FROM users WHERE users.id = valores_corretores_lancadas.user_id) AS user,user_id")
            ->selectRaw("valor_total AS total")
            ->whereMonth("data",$mes)
            ->whereYear("data",$ano)
            ->where("corretora_id",$corretora_id)
            ->groupBy("user_id")
            ->get();


        $valores = DB::connection('tenant')->table('valores_corretores_lancadas')
            ->selectRaw("FORMAT(SUM(valor_comissao),2) AS comissao")
            ->selectRaw("FORMAT(SUM(valor_salario),2) AS salario")
            ->selectRaw("FORMAT(SUM(valor_premiacao),2) AS premiacao")
            ->selectRaw("FORMAT(SUM(valor_comissao+valor_salario+valor_premiacao),2) AS total")
            ->selectRaw("LPAD(MONTH(data), 2, '0') AS mes")
            ->whereMonth("data",$mes)
            ->whereYear("data",$ano)
            ->where("corretora_id",$corretora_id)
            ->first();

        $users_select = DB::connection('tenant')->table('valores_corretores_lancadas')
            ->selectRaw("(SELECT NAME FROM users WHERE users.id = valores_corretores_lancadas.user_id) AS name,user_id as id")
            ->whereMonth("data",$mes)
            ->whereYear("data",$ano)
            ->where("corretora_id",$corretora_id)
            ->groupBy("user_id")
            ->get();


        $dados = DB::connection('tenant')->table('valores_corretores_lancadas')
            ->selectRaw("FORMAT(sum(valor_comissao),2) as total_comissao")
            ->selectRaw("FORMAT(sum(valor_salario),2) as total_salario")
            ->selectRaw("FORMAT(sum(valor_premiacao),2) as valor_premiacao")
            ->selectRaw("FORMAT(sum(valor_desconto),2) as valor_desconto")
            ->selectRaw("FORMAT(sum(valor_total),2) as total_mes")
            ->whereMonth("data",$mes)
            ->whereYear("data",$ano)
            ->where("corretora_id",$corretora_id)
            ->first();

        $total_individual_quantidade = DB::connection('tenant')->table('comissoes_corretores_lancadas')
            ->join('comissoes','comissoes.id',"comissoes_corretores_lancadas.comissoes_id")
            ->where('comissoes.plano_id',1)
            ->where('comissoes.corretora_id',$corretora_id)
            ->where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->count();


        $total_coletivo_quantidade = DB::connection('tenant')->table('comissoes_corretores_lancadas')
            ->join('comissoes','comissoes.id',"comissoes_corretores_lancadas.comissoes_id")
            ->where('comissoes.plano_id',3)
            ->where('comissoes.corretora_id',$corretora_id)
            ->where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->count();

        $total_empresarial_quantidade = DB::connection('tenant')->table('comissoes_corretores_lancadas')
            ->join('comissoes','comissoes.id',"comissoes_corretores_lancadas.comissoes_id")
            ->where('comissoes.plano_id',"!=",3)
            ->where("comissoes.plano_id","!=",1)
            ->where('comissoes.corretora_id',$corretora_id)
            ->where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->count();

        $total_empresarial = DB::connection('tenant')->select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_empresarial_valor FROM (
            SELECT SUM(valor) AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id != 3 AND comissoes.corretora_id = {$corretora_id} AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            AND year(data_baixa_finalizado) = {$ano}
            ) AS plano1,
            (
            SELECT SUM(valor) AS total_plano3 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id != 3 AND comissoes.corretora_id = {$corretora_id} AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes} AND
                 year(data_baixa_finalizado) = {$ano}
            ) AS plano3
        ")[0]->total_empresarial_valor;

        $total_individual = DB::connection('tenant')->select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_individual_valor FROM (
            SELECT
                    SUM(valor)
                    AS total_plano1
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes.corretora_id = {$corretora_id} AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            AND year(data_baixa_finalizado) = {$ano}
            ) AS plano1,
            (
            SELECT
                SUM(valor)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes.corretora_id = {$corretora_id} AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes} AND year(data_baixa_finalizado) = {$ano}
            ) AS plano3;
        ")[0]->total_individual_valor;

        $total_coletivo = DB::connection('tenant')->select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_coletivo_valor FROM (
            SELECT
                SUM(valor)
                AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes.corretora_id = {$corretora_id} AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes} AND year(data_baixa_finalizado) = {$ano}
            ) AS plano1,
            (
            SELECT
                SUM(valor)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes.corretora_id = {$corretora_id} AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes} AND year(data_baixa_finalizado) = {$ano}
            ) AS plano3
        ")[0]->total_coletivo_valor;

        $total_estorno = DB::connection('tenant')->table('comissoes_corretores_lancadas')
            ->whereMonth('data_baixa_estorno',$mes)
            ->join('comissoes','comissoes.id',"=","comissoes_corretores_lancadas.comissoes_id")
            ->whereYear('data_baixa_estorno',$ano)
            ->where('comissoes.corretora_id',$corretora_id)
            ->where('estorno',1)->selectRaw("if(sum(valor)>0,sum(valor),0) as estorno")->first()->estorno;




        return [
            "view" => view('gerente.list-users-pdf-historico',[
                "users" => $users
            ])->render(),
            "dados" => $dados,
            "users" => $users_select,
            "valores" => $valores,
            "total_individual_quantidade" => $total_individual_quantidade,
            "total_coletivo_quantidade" => $total_coletivo_quantidade,
            "total_empresarial_quantidade" => $total_empresarial_quantidade,
            "total_empresarial" => $total_empresarial,
            "total_individual" => $total_individual,
            "total_coletivo" => $total_coletivo,
            "total_estorno" => $total_estorno
        ];



    }




    /*

    public function cadastrarHistoricoFolhaMes(Request $request)
    {
        $date = \DateTime::createFromFormat('Y-m-d', $request->data);
        $formattedDate = $date->format('Y-m-d');

        $mes = date("m",strtotime($formattedDate));
        $ano = date("Y",strtotime($formattedDate));

        $users = DB::table('valores_corretores_lancados')
                ->selectRaw("(SELECT NAME FROM users WHERE users.id = valores_corretores_lancados.user_id) AS user,user_id")
                ->selectRaw("valor_total AS total")
                ->whereMonth("data",$mes)
                ->groupBy("user_id")
                ->get();


        $valores = DB::table('valores_corretores_lancados')
                ->selectRaw("FORMAT(SUM(valor_comissao),2) AS comissao")
                ->selectRaw("FORMAT(SUM(valor_salario),2) AS salario")
                ->selectRaw("FORMAT(SUM(valor_premiacao),2) AS premiacao")
                ->selectRaw("FORMAT(SUM(valor_comissao+valor_salario+valor_premiacao),2) AS total")
            ->selectRaw("LPAD(MONTH(data), 2, '0') AS mes")
            ->whereRaw("MONTH(data) = ${mes}")
            ->first();


        $users_select = DB::table('valores_corretores_lancados')
        ->selectRaw("(SELECT NAME FROM users WHERE users.id = valores_corretores_lancados.user_id) AS name,user_id as id")
        ->whereMonth("data",$mes)
        ->groupBy("user_id")
        ->get();


        $dados = DB::table('valores_corretores_lancados')
            ->selectRaw("FORMAT(sum(valor_comissao),2) as total_comissao")
            ->selectRaw("FORMAT(sum(valor_salario),2) as total_salario")
            ->selectRaw("FORMAT(sum(valor_premiacao),2) as valor_premiacao")
            ->selectRaw("FORMAT(sum(valor_desconto),2) as valor_desconto")
            ->selectRaw("FORMAT(sum(valor_total),2) as total_mes")
            ->whereMonth("data",$mes)
            ->first();


        $total_individual_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereHas('comissao',function($query){
                $query->where("plano_id",1);
            })->count();


        $total_coletivo_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereHas('comissao',function($query){
                $query->where("plano_id",3);
            })->count();

        $total_empresarial_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereHas('comissao',function($query){
                $query->where("plano_id","!=",1);
                $query->where("plano_id","!=",3);
            })->count();

        $total_empresarial = DB::select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_empresarial_valor FROM (
            SELECT SUM(valor) AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = 03
            ) AS plano1,
            (
            SELECT SUM(valor) AS total_plano3 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = 03
            ) AS plano3
        ")[0]->total_empresarial_valor;

        $total_individual = DB::select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_individual_valor FROM (
            SELECT
                    SUM(valor)
                    AS total_plano1
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            ) AS plano1,
            (
            SELECT
                SUM(valor)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes}
            ) AS plano3;
        ")[0]->total_individual_valor;

        $total_coletivo = DB::select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_coletivo_valor FROM (
            SELECT
                SUM(valor)
                AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            ) AS plano1,
            (
            SELECT
                SUM(valor)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes}
            ) AS plano3
        ")[0]->total_coletivo_valor;

        $total_estorno = ComissoesCorretoresLancadas::whereMonth('data_baixa_estorno',$mes)->where('estorno',1)->selectRaw("if(sum(valor)>0,sum(valor),0) as estorno")->first()->estorno;




        return [
            "view" => view('admin.pages.gerente.list-users-pdf-historico',[
                "users" => $users
            ])->render(),
            "dados" => $dados,
            "users" => $users_select,
            "valores" => $valores,
            "total_individual_quantidade" => $total_individual_quantidade,
            "total_coletivo_quantidade" => $total_coletivo_quantidade,
            "total_empresarial_quantidade" => $total_empresarial_quantidade,
            "total_empresarial" => $total_empresarial,
            "total_individual" => $total_individual,
            "total_coletivo" => $total_coletivo,
            "total_estorno" => $total_estorno
        ];


    }
    */




    public function cadastrarFolhaMes(Request $request)
    {

        $date = \DateTime::createFromFormat('Y-m-d', $request->data);
        $formattedDate = $date->format('Y-m-d');

        $mes = date("m",strtotime($formattedDate));
        $ano = date("Y",strtotime($formattedDate));
        $corretora_id = auth()->user()->corretora_id;
        $folha = FolhaMes::whereMonth("mes",$mes)->where("corretora_id",auth()->user()->corretora_id)->whereYear("mes",$ano)->count();
        if($folha == 0) {
            $folha = new FolhaMes();
            $folha->mes = $formattedDate;
            $folha->corretora_id = auth()->user()->corretora_id;
            $folha->save();
            $users_select = DB::connection('tenant')->select("
                SELECT users.id AS id, users.name AS name
                FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                INNER JOIN users ON users.id = comissoes.user_id
                WHERE users.corretora_id = {$corretora_id} AND (status_financeiro = 1 or status_gerente = 1)
                  and finalizado != 1 and valor != 0 and users.id NOT IN (SELECT user_id FROM valores_corretores_lancadas WHERE MONTH(data) = {$mes} AND YEAR(data) = {$ano})
                GROUP BY users.id, users.name
                ORDER BY users.name
            ");
            return [
                "resposta" => "cadastrado",
                "users_select" => $users_select
            ];
        } else {

            $users = DB::connection('tenant')->table('valores_corretores_lancadas')
                ->selectRaw("(SELECT NAME FROM users WHERE users.id = valores_corretores_lancados.user_id) AS user,user_id")
                ->selectRaw("valor_total AS total")
                ->whereMonth("data",$mes)
                ->whereYear("data",$ano)
                ->groupBy("user_id")
                ->get();

            $valores = DB::connection('tenant')->table('valores_corretores_lancadas')
                ->selectRaw("FORMAT(SUM(valor_comissao),2) AS comissao")
                ->selectRaw("FORMAT(SUM(valor_salario),2) AS salario")
                ->selectRaw("FORMAT(SUM(valor_premiacao),2) AS premiacao")
                ->selectRaw("FORMAT(SUM(valor_comissao+valor_salario+valor_premiacao),2) AS total")
                ->selectRaw("LPAD(MONTH(data), 2, '0') AS mes")
                ->whereRaw("MONTH(data) = ${mes} AND YEAR(data) = ${ano}")
                ->first();

            $users_select = DB::connection('tenant')->table('valores_corretores_lancadas')
                ->selectRaw("(SELECT NAME FROM users WHERE users.id = valores_corretores_lancados.user_id) AS name,user_id as id")
                ->whereMonth("data",$mes)
                ->whereYear("data",$ano)
                ->groupBy("user_id")
                ->get();



            $dados = DB::connection('tenant')->table('valores_corretores_lancadas')
                ->selectRaw("FORMAT(sum(valor_comissao),2) as total_comissao")
                ->selectRaw("FORMAT(sum(valor_salario),2) as total_salario")
                ->selectRaw("FORMAT(sum(valor_premiacao),2) as valor_premiacao")
                ->selectRaw("FORMAT(sum(valor_desconto),2) as valor_desconto")
                ->selectRaw("FORMAT(sum(valor_total),2) as total_mes")
                ->whereMonth("data",$mes)
                ->whereYear("data",$ano)
                ->first();


            $total_individual_quantidade = ComissoesCorretoresLancadas
                ::where("status_financeiro",1)
                ->where("status_apto_pagar",1)
                //->where("finalizado",1)
                ->whereMonth("data_baixa_finalizado",$mes)
                ->whereYear("data_baixa_finalizado",$ano)
                ->whereHas('comissao',function($query){
                    $query->where("plano_id",1);
                })->count();


            $total_coletivo_quantidade = ComissoesCorretoresLancadas
                ::where("status_financeiro",1)
                ->where("status_apto_pagar",1)
                //->where("finalizado","=",1)
                ->whereMonth("data_baixa_finalizado",$mes)
                ->whereYear("data_baixa_finalizado",$ano)
                ->whereHas('comissao',function($query){
                    $query->where("plano_id",3);
                })->count();

            $total_empresarial_quantidade = ComissoesCorretoresLancadas
                ::where("status_financeiro",1)
                ->where("status_apto_pagar",1)
                ->whereMonth("data_baixa_finalizado",$mes)
                ->whereYear("data_baixa_finalizado",$ano)
                ->whereHas('comissao',function($query){
                    $query->where("plano_id","!=",1);
                    $query->where("plano_id","!=",3);
                })->count();


            $total_empresarial = DB::select("
                SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_empresarial_valor FROM (
                SELECT SUM(valor) AS total_plano1 FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = ${mes}
                AND year(data_baixa_finalizado) = ${ano}
                ) AS plano1,
                (
                SELECT SUM(valor) AS total_plano3 FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = ${mes}
                AND year(data_baixa_finalizado) = ${ano}
                ) AS plano3
            ")[0]->total_empresarial_valor;


            $total_individual = DB::select("
                SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_individual_valor FROM (
                SELECT
                        SUM(valor)
                        AS total_plano1
                FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and
                      month(data_baixa_finalizado) = {$mes} AND year(data_baixa_finalizado) = {$ano}
                ) AS plano1,
                (
                SELECT
                    SUM(valor)
                    AS total_plano3
                FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes} AND year(data_baixa_finalizado) = {$ano}
                ) AS plano3;
            ")[0]->total_individual_valor;



            $total_coletivo = DB::select("
                SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_coletivo_valor FROM (
                SELECT
                    SUM(valor)
                    AS total_plano1 FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
                AND year(data_baixa_finalizado) = {$ano}
                ) AS plano1,
                (
                SELECT
                    SUM(valor)
                    AS total_plano3
                FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes} AND year(data_baixa_finalizado) = {$ano}
                ) AS plano3
            ")[0]->total_coletivo_valor;













            return [
                "view" => view('gerente.list-users-pdf',[
                    "users" => $users
                ])->render(),
                "dados" => $dados,
                "users" => $users_select,
                "valores" => $valores,
                "total_individual_quantidade" => $total_individual_quantidade,
                "total_coletivo_quantidade" => $total_coletivo_quantidade,
                "total_empresarial_quantidade" => $total_empresarial_quantidade,
                "total_empresarial" => $total_empresarial,
                "total_individual" => $total_individual,
                "total_coletivo" => $total_coletivo


            ];






        }






    }

    public function geralFolhaMesEspecifica(Request $request)
    {
        $mes = $request->mes;
        $dados = DB::table('valores_corretores_lancados')
            ->selectRaw("(SELECT NAME FROM users WHERE users.id = valores_corretores_lancados.user_id) AS user")
            ->selectRaw("valor_comissao,valor_salario,valor_premiacao")
            ->selectRaw("(valor_comissao+valor_salario+valor_premiacao) AS total")
            ->whereRaw("MONTH(data) = ${mes}")
            ->get();


        $meses = [
            '01'=>"Janeiro",
            '02'=>"Fevereiro",
            '03'=>"Março",
            '04'=>"Abril",
            '05'=>"Maio",
            '06'=>"Junho",
            '07'=>"Julho",
            '08'=>"Agosto",
            '09'=>"Setembro",
            '10'=>"Outubro",
            '11'=>"Novembro",
            '12'=>"Dezembro"
        ];

        $mes_folha = $meses[$mes];


        $pdf = PDFFile::loadView('admin.pages.gerente.pdf-folha-mes-geral',[
            "dados" => $dados,
            "mes" => $mes_folha
        ]);

        $nome_pdf = "teste_pdf";
        return $pdf->download($nome_pdf);






    }



    public function pegarTodososDados(Request $request)
    {
        $ano = $request->campo_ano != "todos" ? $request->campo_ano : false;
        $mes = $request->campo_mes != "todos" ? $request->campo_mes : false;
        $id = $request->campo_cor  != "todos" ? $request->campo_cor : false;


        /** QUANTIDADE GERAL */
        $quantidade_sem_empresaria_geral = Contrato::whereHas('clientes',function($query) use($id){
            if($id) {
                $query->where("user_id",$id);
            }

        })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->count();

        $quantidade_com_empresaria_geral = ContratoEmpresarial
            ::where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->count();
        $quantidade_geral = $quantidade_sem_empresaria_geral + $quantidade_com_empresaria_geral;
        /** FIM QUANTIDADE GERAL */

        /** VALOR GERAL */
        $total_sem_empresa_valor_geral = Contrato::whereHas("clientes",function($query) use($id){
            if($id) {
                $query->where("user_id",$id);
            }

        })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(SUM(valor_plano)>0,SUM(valor_plano),0) as total_geral")
            ->first()
            ->total_geral;

        $total_com_empresa_valor_geral = ContratoEmpresarial
            ::where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(valor_total)>0,sum(valor_total),0) as valor_total")
            ->first()
            ->valor_total;

        $total_valor_geral = $total_sem_empresa_valor_geral + $total_com_empresa_valor_geral;
        /** FIM VALOR GERAL */

        /** QUANTIDADE vidas GERAL */
        $quantidade_sem_empresa_vidas_geral =
            Cliente
                ::where(function($query)use($id){
                    if($id) {
                        $query->where("user_id",$id);
                    }
                })
                ->where(function($query) use($ano,$mes){
                    if($ano) {
                        $query->whereYear('created_at',$ano);
                    }
                    if($mes) {
                        $query->whereMonth('created_at',$mes);
                    }
                })
                ->selectRaw("if(SUM(quantidade_vidas)>0,SUM(quantidade_vidas),0) as quantidade_vidas")
                ->first()
                ->quantidade_vidas;

        $quantidade_com_empresa_vidas_geral = ContratoEmpresarial
            ::where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) as quantidade_vidas")
            ->first()
            ->quantidade_vidas;
        $quantidade_geral_vidas = $quantidade_sem_empresa_vidas_geral + $quantidade_com_empresa_vidas_geral;
        /** FIM QUANTIDADE vidas GERAL */


        /*** QUANTIDADE Recebidos */
        $total_quantidade_recebidos = Contrato::whereHas("clientes",function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",1);
                $query->where("status_gerente",1);
                $query->where("valor","!=",0);
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->count();


        $quantidade_recebidas_empresarial = ContratoEmpresarial
            ::where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }

            })
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",1);
                $query->where("status_gerente",1);
                $query->where("valor","!=",0);
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->count();

        $total_geral_recebidas = $total_quantidade_recebidos + $quantidade_recebidas_empresarial;


        /*** FIM quantidade Recebidos */



        /*** Valor Total a Recebidos */
        $total_valor_recebidos = Contrato::whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }

        })
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",1);
                $query->where("status_gerente",1);
                $query->where("valor","!=",0);
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as total_valor_plano")
            ->first()
            ->total_valor_plano;

        $total_valor_recebidos_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("sum(valor_total) as total_valor_plano")
            ->first()
            ->total_valor_plano;
        $total_geral_recebidos_valor = $total_valor_recebidos + $total_valor_recebidos_empresarial;
        /*** FIM Valor Total a Recebidos */

        /*****Qunatidade de Vidas a Recebidos */
        $quantidade_vidas_recebidas = Cliente
            ::where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->whereHas('contrato.comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",1);
                $query->where("status_gerente",1);
                $query->where("valor","!=",0);
            })
            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
            ->first()
            ->total_quantidade_vidas_recebidas;

        $quantidade_vidas_recebidas_empresarial = ContratoEmpresarial
            ::whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",1);
                $query->where("status_gerente",1);
                $query->where("valor","!=",0);
            })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
            ->first()
            ->total_quantidade_vidas_recebidas;

        $quantidade_vidas_recebidas_geral = $quantidade_vidas_recebidas + $quantidade_vidas_recebidas_empresarial;

        /*****Qunatidade de Vidas a Recebidos */


        /********Quantidade a Receber Geral */
        $total_quantidade_a_receber = Contrato
            ::whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",1);
                $query->where("status_gerente",0);
                $query->where("valor","!=",0);
            })
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->count();

        $total_quantidade_a_receber_empresarial = ContratoEmpresarial
            ::whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",1);
                $query->where("status_gerente",0);
                $query->where("valor","!=",0);
            })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->count();

        $total_quantidade_a_receber_geral = $total_quantidade_a_receber + $total_quantidade_a_receber_empresarial;

        /********FIM Quantidade a Receber Geral */


        /*******Valor A Receber Geral */
        $total_valor_a_receber = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as total_valor_plano")
            ->first()
            ->total_valor_plano;

        $total_valor_a_receber_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(valor_total)>=1,sum(valor_total),0) as total_valor_plano")->first()->total_valor_plano;
        $total_valor_a_receber_geral = $total_valor_a_receber + $total_valor_a_receber_empresarial;
        /*******FIM Valor A Receber Geral */


        /*******QUANTIDADe DE VIDAS A RECEBER GERAL */
        $quantidade_vidas_a_receber = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")->first()->total_quantidade_vidas_recebidas;

        $quantidade_vidas_a_receber_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
            ->first()
            ->total_quantidade_vidas_recebidas;

        $quantidade_vidas_a_receber_geral = $quantidade_vidas_a_receber +  $quantidade_vidas_a_receber_empresarial;
        /*******FIM QUANTIDADe DE VIDAS A RECEBER GERAL */


        /****Quantidade Atrasada de Geral */
        $qtd_atrasado = Contrato::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where('user_id',$id);
                }

            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->count();

        $qtd_atrasado_empresarial = ContratoEmpresarial::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->count();

        $quantidade_atrasado_geral = $qtd_atrasado + $qtd_atrasado_empresarial;
        /****FIM Quantidade Atrasada de Geral */

        /****Valor Atrasada de Geral */
        $qtd_atrasado_valor = Contrato
            ::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->whereRaw("DATA < CURDATE()");
                $query->whereRaw("data_baixa IS NULL");
                $query->groupBy("comissoes_id");
            })
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where('user_id',$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("sum(valor_plano) as total_valor_plano")
            ->first()
            ->total_valor_plano;

        $qtd_atrasado_valor_empresarial = ContratoEmpresarial
            ::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->whereRaw("DATA < CURDATE()");
                $query->whereRaw("data_baixa IS NULL");
                $query->groupBy("comissoes_id");
            })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("sum(valor_total) as total_valor_plano")->first()->total_valor_plano;

        $qtd_atrasado_valor_geral = $qtd_atrasado_valor + $qtd_atrasado_valor_empresarial;
        /****FIM Valor Atrasada de Geral */

        /****Vidas Atrasada de Geral */
        $qtd_atrasado_quantidade_vidas = Cliente::
        whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
            ->whereHas('contrato',function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }

                $query->whereIn('financeiro_id',[3,4,5,6,7,8,9,10]);
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_atrasadas")
            ->first()
            ->total_quantidade_vidas_atrasadas;

        $qtd_atrasado_quantidade_vidas_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_atrasadas")->first()->total_quantidade_vidas_atrasadas;

        $qtd_atrasado_quantidade_vidas_geral = $qtd_atrasado_quantidade_vidas + $qtd_atrasado_quantidade_vidas_empresarial;
        /****Vidas Atrasada de Geral */






        /** Quantidade de Finalizado Geral */
        $qtd_finalizado = Contrato
            ::where("financeiro_id",11)
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->count();

        $qtd_finalizado_empresarial = ContratoEmpresarial::where("financeiro_id",11)
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->count();
        $qtd_finalizado_geral = $qtd_finalizado + $qtd_finalizado_empresarial;
        /** FIM Quantidade de Finalizado Geral */

        /** Valor de Finalizado Geral */
        $quantidade_valor_finalizado = Contrato::where("financeiro_id",11)
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }

            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_finalizado")->first()->valor_total_finalizado;

        $quantidade_valor_finalizado_empresarial = ContratoEmpresarial::where("financeiro_id",11)
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(valor_total)>=1,sum(valor_total),0) as valor_total_finalizado")->first()->valor_total_finalizado;

        $quantidade_geral_finalizado = $quantidade_valor_finalizado + $quantidade_valor_finalizado_empresarial;
        /** FIM Valor de Finalizado Geral */

        /** Valor de Finalizado Geral */
        $qtd_finalizado_quantidade_vidas = Cliente::whereHas('contrato',function($query){
            $query->where("financeiro_id",11);

        })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_finalizadas")->first()->total_quantidade_vidas_finalizadas;

        $qtd_finalizado_quantidade_vidas_empresarial = ContratoEmpresarial::where("financeiro_id",11)
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_finalizadas")->first()->total_quantidade_vidas_finalizadas;

        $quantidade_finalizado_quantidade_vidas_geral = $qtd_finalizado_quantidade_vidas + $qtd_finalizado_quantidade_vidas_empresarial;
        /** FIM Valor de Finalizado Geral */


        /**** Quantiade de Cancelados */
        $qtd_cancelado = Contrato::where("financeiro_id",12)
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }

            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->count();

        $qtd_cancelado_empresarial = ContratoEmpresarial::where("financeiro_id",12)
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->count();

        $quantidade_geral_cancelado = $qtd_cancelado + $qtd_cancelado_empresarial;
        /**** FIM Quantiade de Cancelados */

        /**** Valor de Cancelados */
        $quantidade_valor_cancelado_valor = Contrato::where("financeiro_id",12)
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_cancelado")->first()->valor_total_cancelado;

        $quantidade_valor_cancelado_empresarial = ContratoEmpresarial::where("financeiro_id",12)
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(valor_total)>=1,sum(valor_total),0) as valor_total_cancelado")->first()->valor_total_cancelado;

        $quantidade_geral_cancelado_valor = $quantidade_valor_cancelado_valor + $quantidade_valor_cancelado_empresarial;
        /**** FIM Valor de Cancelados */

        /**** Quantidade de Vidas de Cancelados */
        $qtd_cancelado_quantidade_vidas = Cliente::whereHas('contrato',function($query){
            $query->where("financeiro_id",12);

        })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_cancelado")->first()->total_quantidade_vidas_cancelado;

        $qtd_cancelado_quantidade_vidas_empresarial = ContratoEmpresarial::where("financeiro_id",12)->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_cancelado")->first()->total_quantidade_vidas_cancelado;

        $quantidade_cancelado_vidas_geral = $qtd_cancelado_quantidade_vidas + $qtd_cancelado_quantidade_vidas_empresarial;
        /**** FIM Quantidade de Vidas de Cancelados */



        //FIM Geral

        //Individual

        $quantidade_individual_geral = Contrato::where("plano_id",1)
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }

            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->count();



        $total_valor_geral_individual = Contrato::where("plano_id",1)
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }

            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("SUM(valor_plano) as total_geral")->first()->total_geral;

        $quantidade_vidas_geral_individual = Cliente::whereHas('contrato',function($query) use($ano,$mes){
            $query->where("plano_id",1);
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }

            })

            ->selectRaw("if(SUM(quantidade_vidas)>0,SUM(quantidade_vidas),0) as quantidade_vidas")->first()->quantidade_vidas;


        $total_quantidade_recebidos_individual = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where("plano_id",1)
            ->count();

        $total_valor_recebidos_individual = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where("plano_id",1)
            ->selectRaw("if(sum(valor_plano)>0,sum(valor_plano),0) as total_valor_plano")
            ->first()
            ->total_valor_plano;

        $quantidade_vidas_recebidas_individual = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })

            ->whereHas('contrato',function($query)use($ano,$mes){
                $query->where("plano_id",1);
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
            ->first()
            ->total_quantidade_vidas_recebidas;

        $total_quantidade_a_receber_individual = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }

            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where("plano_id",1)
            ->count();

        $total_valor_a_receber_individual = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }

            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where("plano_id",1)
            ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as total_valor_plano")->first()->total_valor_plano;

        $quantidade_vidas_a_receber_individual = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })

            ->whereHas('contrato',function($query)use($ano,$mes){
                $query->where("plano_id",1);
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
            ->first()
            ->total_quantidade_vidas_recebidas;

        $qtd_atrasado_individual = Contrato::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
            ->whereHas('clientes',function($query)use($id){
                $query->whereRaw('cateirinha IS NOT NULL');
                if($id) {
                    $query->where("user_id",$id);
                }

            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where("plano_id",1)
            ->count();

        $qtd_atrasado_valor_individual = Contrato::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
            ->whereHas('clientes',function($query)use($id){
                $query->whereRaw('cateirinha IS NOT NULL');
                if($id) {
                    $query->where("user_id",$id);
                }

            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where("plano_id",1)
            ->selectRaw("sum(valor_plano) as total_valor_plano")->first()->total_valor_plano;

        $qtd_atrasado_quantidade_vidas_individual = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
            ->whereHas('contrato',function($query)use($ano,$mes){
                $query->where("plano_id",1);
                $query->whereIn("financeiro_id",[3,4,5,6,7,8,9,10]);
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_atrasadas")->first()->total_quantidade_vidas_atrasadas;

        $qtd_finalizado_individual = Contrato::where("financeiro_id",11)->where('plano_id',1)
            ->whereHas('clientes',function($query)use($id){
                $query->whereRaw('cateirinha IS NOT NULL');
                if($id) {
                    $query->where("user_id",$id);
                }

            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->count();

        $quantidade_valor_finalizado_individual = Contrato::where("financeiro_id",11)
            ->whereHas('clientes',function($query)use($id){
                $query->whereRaw('cateirinha IS NOT NULL');
                if($id) {
                    $query->where("user_id",$id);
                }

            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where('plano_id',1)
            ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_finalizado")->first()->valor_total_finalizado;

        $qtd_finalizado_quantidade_vidas_individual = Cliente::whereHas('contrato',function($query)use($mes,$ano){
            $query->where("financeiro_id",11);
            $query->where("plano_id",1);
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_finalizadas")->first()->total_quantidade_vidas_finalizadas;

        $qtd_cancelado_individual = Contrato::where("financeiro_id",12)
            ->whereHas('clientes',function($query)use($id){
                $query->whereRaw('cateirinha IS NOT NULL');
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where('plano_id',1)
            ->count();

        $quantidade_valor_cancelado_individual = Contrato::where("financeiro_id",12)->where('plano_id',1)
            ->whereHas('clientes',function($query)use($id){
                $query->whereRaw('cateirinha IS NOT NULL');
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_cancelado")->first()->valor_total_cancelado;

        $qtd_cancelado_quantidade_vidas_individual = Cliente::whereHas('contrato',function($query)use($mes,$ano){
            $query->where("financeiro_id",12);
            $query->where("plano_id",1);
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })

            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_cancelado")->first()->total_quantidade_vidas_cancelado;





        //Fim Individual

        //Coletivo

        $quantidade_coletivo_geral     = Contrato::where("plano_id",3)
            ->whereHas("clientes",function($query) use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->count();

        $total_valor_geral_coletivo = Contrato::where("plano_id",3)
            ->whereHas("clientes",function($query) use($id){
                if($id) {
                    $query->where("user_id",$id);
                }

            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(SUM(valor_plano)>0,SUM(valor_plano),0) as total_geral")
            ->first()
            ->total_geral;

        $quantidade_vidas_geral_coletivo = Cliente::whereHas('contrato',function($query)use($ano,$mes){
            $query->where("plano_id",3);
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->selectRaw("if(SUM(quantidade_vidas)>0,SUM(quantidade_vidas),0) as quantidade_vidas")
            ->first()
            ->quantidade_vidas;


        $total_quantidade_recebidos_coletivo = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
            ->whereHas("clientes",function($query) use($id){
                if($id) {
                    $query->where("user_id",$id);
                }

            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where("plano_id",3)
            ->count();

        $total_valor_recebidos_coletivo = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
            ->whereHas("clientes",function($query) use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where("plano_id",3)
            ->selectRaw("sum(valor_plano) as total_valor_plano")
            ->first()
            ->total_valor_plano;

        $quantidade_vidas_recebidas_coletivo = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->whereHas('contrato',function($query)use($ano,$mes){
                $query->where("plano_id",3);
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
            ->first()
            ->total_quantidade_vidas_recebidas;

        $total_quantidade_a_receber_coletivo = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
            ->whereHas("clientes",function($query) use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where("plano_id",3)
            ->count();

        $total_valor_a_receber_coletivo = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
            ->whereHas("clientes",function($query) use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where("plano_id",3)
            ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as total_valor_plano")->first()->total_valor_plano;

        $quantidade_vidas_a_receber_coletivo = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
            ->whereHas('contrato',function($query)use($ano,$mes){
                $query->where("plano_id",3);
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where(function($query) use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
            ->first()
            ->total_quantidade_vidas_recebidas;



        $qtd_atrasado_coletivo = Contrato::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where('user_id',$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where("plano_id",3)
            ->count();

        $qtd_atrasado_valor_coletivo = Contrato::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where('user_id',$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where("plano_id",3)
            ->selectRaw("sum(valor_plano) as total_valor_plano")->first()->total_valor_plano;

        $qtd_atrasado_quantidade_vidas_coletivo = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
            ->whereHas('contrato',function($query)use($ano,$mes){
                $query->where("plano_id",3);
                $query->whereIn("financeiro_id",[3,4,5,6,7,8,9,10]);
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_atrasadas")
            ->first()
            ->total_quantidade_vidas_atrasadas;


        $qtd_finalizado_coletivo = Contrato::where("financeiro_id",11)
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where('user_id',$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where('plano_id',3)
            ->count();

        $quantidade_valor_finalizado_coletivo = Contrato::where("financeiro_id",11)->where('plano_id',3)
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where('user_id',$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_finalizado")->first()->valor_total_finalizado;

        $qtd_finalizado_quantidade_vidas_coletivo = Cliente::whereHas('contrato',function($query)use($ano,$mes){
            $query->where("financeiro_id",11);
            $query->where("plano_id",3);
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
            ->where(function($query)use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_finalizadas")->first()->total_quantidade_vidas_finalizadas;

        $qtd_cancelado_coletivo = Contrato::where("financeiro_id",12)
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where('user_id',$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->where('plano_id',3)
            ->count();

        $quantidade_valor_cancelado_coletivo = Contrato::where("financeiro_id",12)->where('plano_id',3)
            ->whereHas('clientes',function($query)use($id){
                if($id) {
                    $query->where('user_id',$id);
                }
            })
            ->where(function($query) use($ano,$mes){
                if($ano) {
                    $query->whereYear('created_at',$ano);
                }
                if($mes) {
                    $query->whereMonth('created_at',$mes);
                }
            })
            ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_cancelado")->first()->valor_total_cancelado;

        $qtd_cancelado_quantidade_vidas_coletivo = Cliente::whereHas('contrato',function($query)use($ano,$mes){
            $query->where("financeiro_id",12);
            $query->where("plano_id",3);
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
            ->where(function($query) use($id){
                if($id) {
                    $query->where("user_id",$id);
                }
            })
            ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_cancelado")
            ->first()
            ->total_quantidade_vidas_cancelado;


        return [

            "quantidade_geral" => $quantidade_geral,
            "total_valor_geral" => number_format($total_valor_geral,2,",","."),
            "quantidade_geral_vidas" => $quantidade_geral_vidas,

            "total_geral_recebidas" => $total_geral_recebidas,
            "total_geral_recebidos_valor" => number_format($total_geral_recebidos_valor,2,",","."),
            "quantidade_vidas_recebidas_geral" => $quantidade_vidas_recebidas_geral,

            "total_quantidade_a_receber_geral" => $total_quantidade_a_receber_geral,
            "total_valor_a_receber_geral" => number_format($total_valor_a_receber_geral,2,",","."),
            "quantidade_vidas_a_receber_geral" => $quantidade_vidas_a_receber_geral,

            "quantidade_atrasado_geral" => $quantidade_atrasado_geral,
            "quantidade_atrasado_valor_geral" => number_format($qtd_atrasado_valor_geral,2,",","."),
            "qtd_atrasado_quantidade_vidas_geral" => $qtd_atrasado_quantidade_vidas_geral,

            "quantidade_finalizado_geral" => $qtd_finalizado_geral,
            "quantidade_geral_finalizado" => number_format($quantidade_geral_finalizado,2,",","."),
            "quantidade_finalizado_quantidade_vidas_geral" => $quantidade_finalizado_quantidade_vidas_geral,

            "quantidade_geral_cancelado" => $quantidade_geral_cancelado,
            "quantidade_geral_cancelado_valor" => number_format($quantidade_geral_cancelado_valor,2,",","."),
            "quantidade_cancelado_vidas_geral" => $quantidade_cancelado_vidas_geral,

            /****INdividual */

            "quantidade_individual_geral" => $quantidade_individual_geral,
            "total_valor_geral_individual" => number_format($total_valor_geral_individual,2,",","."),
            "quantidade_vidas_geral_individual" => $quantidade_vidas_geral_individual,

            "total_quantidade_recebidos_individual" => $total_quantidade_recebidos_individual,
            "total_valor_recebidos_individual" => number_format($total_valor_recebidos_individual,2,",","."),
            "quantidade_vidas_recebidas_individual" => $quantidade_vidas_recebidas_individual,

            "total_quantidade_a_receber_individual" => $total_quantidade_a_receber_individual,
            "total_valor_a_receber_individual" => number_format($total_valor_a_receber_individual,2,",","."),
            "quantidade_vidas_a_receber_individual" => $quantidade_vidas_a_receber_individual,

            "qtd_atrasado_individual" => $qtd_atrasado_individual,
            "qtd_atrasado_valor_individual" => number_format($qtd_atrasado_valor_individual,2,",","."),
            "qtd_atrasado_quantidade_vidas_individual" => $qtd_atrasado_quantidade_vidas_individual,

            "qtd_finalizado_individual" => $qtd_finalizado_individual,
            "quantidade_valor_finalizado_individual" => $quantidade_valor_finalizado_individual,
            "qtd_finalizado_quantidade_vidas_individual" => $qtd_finalizado_quantidade_vidas_individual,

            "qtd_cancelado_individual" => $qtd_cancelado_individual,
            "quantidade_valor_cancelado_individual" => $quantidade_valor_cancelado_individual,
            "qtd_cancelado_quantidade_vidas_individual" => $qtd_cancelado_quantidade_vidas_individual,

            //////////Coletivo
            'quantidade_coletivo_geral' => $quantidade_coletivo_geral,

            'total_valor_geral_coletivo' => number_format($total_valor_geral_coletivo,2,",","."),

            'quantidade_vidas_geral_coletivo' => $quantidade_vidas_geral_coletivo,

            'total_quantidade_recebidos_coletivo' => $total_quantidade_recebidos_coletivo,
            'total_valor_recebidos_coletivo' => number_format($total_valor_recebidos_coletivo,2,",","."),
            'quantidade_vidas_recebidas_coletivo' => $quantidade_vidas_recebidas_coletivo,

            'total_quantidade_a_receber_coletivo' => $total_quantidade_a_receber_coletivo,
            'total_valor_a_receber_coletivo' => number_format($total_valor_a_receber_coletivo,2,",","."),
            'quantidade_vidas_a_receber_coletivo' => $quantidade_vidas_a_receber_coletivo,

            'qtd_atrasado_coletivo' => $qtd_atrasado_coletivo,
            'qtd_atrasado_valor_coletivo' => number_format($qtd_atrasado_valor_coletivo,2,",","."),
            'qtd_atrasado_quantidade_vidas_coletivo' => $qtd_atrasado_quantidade_vidas_coletivo,

            'qtd_finalizado_coletivo' => $qtd_finalizado_coletivo,
            'quantidade_valor_finalizado_coletivo' => number_format($quantidade_valor_finalizado_coletivo,2,",","."),
            'qtd_finalizado_quantidade_vidas_coletivo' => $qtd_finalizado_quantidade_vidas_coletivo,

            'qtd_cancelado_coletivo' => $qtd_cancelado_coletivo,
            'quantidade_valor_cancelado_coletivo' => number_format($quantidade_valor_cancelado_coletivo,2,",","."),
            'qtd_cancelado_quantidade_vidas_coletivo' => $qtd_cancelado_quantidade_vidas_coletivo,

            ///Empresarial

            "quantidade_com_empresaria_geral" => $quantidade_com_empresaria_geral,
            "total_com_empresa_valor_geral" => number_format($total_com_empresa_valor_geral,2,",","."),
            "quantidade_com_empresa_vidas_geral" => $quantidade_com_empresa_vidas_geral,

            "quantidade_recebidas_empresarial" => $quantidade_recebidas_empresarial,
            "total_valor_recebidos_empresarial" =>  number_format($total_valor_recebidos_empresarial,2,",","."),
            "quantidade_vidas_recebidas_empresarial" => $quantidade_vidas_recebidas_empresarial,


            "total_quantidade_a_receber_empresarial" => $total_quantidade_a_receber_empresarial,
            "total_valor_a_receber_empresarial" => number_format($total_valor_a_receber_empresarial,2,",","."),
            "quantidade_vidas_a_receber_empresarial" => $quantidade_vidas_a_receber_empresarial,

            "qtd_atrasado_empresarial" => $qtd_atrasado_empresarial,
            "qtd_atrasado_valor_empresarial" => number_format($qtd_atrasado_valor_empresarial,2,",","."),
            "qtd_atrasado_quantidade_vidas_empresarial" => $qtd_atrasado_quantidade_vidas_empresarial,

            "qtd_finalizado_empresarial" => $qtd_finalizado_empresarial,
            "quantidade_valor_finalizado_empresarial" => number_format($quantidade_valor_finalizado_empresarial,2,",","."),
            "qtd_finalizado_quantidade_vidas_empresarial" => $qtd_finalizado_quantidade_vidas_empresarial,

            "qtd_cancelado_empresarial" => $qtd_cancelado_empresarial,
            "quantidade_valor_cancelado_empresarial" => number_format($quantidade_valor_cancelado_empresarial,2,",","."),
            "qtd_cancelado_quantidade_vidas_empresarial" => $qtd_cancelado_quantidade_vidas_empresarial



        ];

    }


    public function showDetalhesDadosTodosAll($id_estagio)
    {
        $estagio = 0;

        switch($id_estagio) {

            case 1:
                $quantidade = Contrato::count();
                $valor      = Contrato::selectRaw("SUM(valor_plano) as total_geral")->first()->total_geral;
                $vidas      = Cliente::selectRaw("SUM(quantidade_vidas) as quantidade_vidas")->first()->quantidade_vidas;
                $quantidade_empresarial_geral  = ContratoEmpresarial::count();
                $quantidade_vidas_geral_empresarial = ContratoEmpresarial::selectRaw("sum(quantidade_vidas) as quantidade_vidas")->first()->quantidade_vidas;

                $quantidade_total = $quantidade + $quantidade_empresarial_geral;
                $valor_total = DB::select("select (select sum(valor_plano) from contratos) + (select sum(valor_plano) from contrato_empresarial) as total_soma_formatado")[0]->total_soma_formatado;
                $vidas_total = $vidas + $quantidade_vidas_geral_empresarial;
                $estagio = 1;
                break;

            case 2:
                $total_quantidade_recebidos = Contrato::whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",1);
                    $query->where("valor","!=",0);
                })->count();

                $total_valor_recebidos = Contrato::whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",1);
                    $query->where("valor","!=",0);
                })->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as total_valor_plano")->first()->total_valor_plano;

                $quantidade_vidas_recebidas = Cliente
                    ::whereHas('contrato',function($query){
                        $query->where('plano_id',1);
                    })
                    ->whereHas('contrato.comissao.comissoesLancadas',function($query){
                        $query->where("status_financeiro",1);
                        $query->where("status_gerente",1);
                        $query->where("valor","!=",0);
                    })
                    ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
                    ->first()
                    ->total_quantidade_vidas_recebidas;


                $total_quantidade_recebidos_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",1);
                    $query->where("valor","!=",0);
                })
                    ->count();


                $total_valor_recebidos_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",1);
                    $query->where("valor","!=",0);
                })
                    ->selectRaw("sum(valor_total) as total_valor_plano")
                    ->first()
                    ->total_valor_plano;

                $quantidade_vidas_recebidas_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",1);
                    $query->where("valor","!=",0);
                })
                    ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
                    ->first()
                    ->total_quantidade_vidas_recebidas;

                $quantidade_total = $total_quantidade_recebidos + $total_quantidade_recebidos_empresarial;
                $valor_total = $total_valor_recebidos + $total_valor_recebidos_empresarial;
                $vidas_total = $quantidade_vidas_recebidas + $quantidade_vidas_recebidas_empresarial;
                $estagio = 2;
                break;

            case 3:

                $total_quantidade_a_receber = Contrato::whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",0);
                    $query->where("valor","!=",0);
                })->count();

                $total_valor_a_receber = Contrato::whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",0);
                    $query->where("valor","!=",0);
                })
                    ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as total_valor_plano")->first()->total_valor_plano;

                $quantidade_vidas_a_receber = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",0);
                    $query->where("valor","!=",0);
                })->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")->first()->total_quantidade_vidas_recebidas;

                $total_quantidade_a_receber_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",0);
                    $query->where("valor","!=",0);
                })
                    ->count();

                $total_valor_a_receber_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",0);
                    $query->where("valor","!=",0);
                })
                    ->selectRaw("if(sum(valor_total)>=1,sum(valor_total),0) as total_valor_plano")->first()->total_valor_plano;

                $quantidade_vidas_a_receber_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",0);
                    $query->where("valor","!=",0);
                })
                    ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
                    ->first()
                    ->total_quantidade_vidas_recebidas;

                $quantidade_total = $total_quantidade_a_receber + $total_quantidade_a_receber_empresarial;
                $valor_total = $total_valor_a_receber + $total_valor_a_receber_empresarial;
                $vidas_total = $quantidade_vidas_a_receber + $quantidade_vidas_a_receber_empresarial;
                $estagio = 3;

                break;

            case 4:

                $qtd_atrasado = Contrato::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
                    ->whereHas('comissao.comissoesLancadas',function($query){
                        $query->whereRaw("DATA < CURDATE()");
                        $query->whereRaw("data_baixa IS NULL");
                        $query->groupBy("comissoes_id");
                    })->count();


                $qtd_atrasado_valor = Contrato
                    ::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
                    ->whereHas('comissao.comissoesLancadas',function($query){
                        $query->whereRaw("DATA < CURDATE()");
                        $query->whereRaw("data_baixa IS NULL");
                        $query->groupBy("comissoes_id");
                    })->selectRaw("sum(valor_plano) as total_valor_plano")->first()->total_valor_plano;



                $qtd_atrasado_quantidade_vidas = Cliente
                    ::whereHas('contrato.comissao.comissoesLancadas',function($query){
                        $query->whereRaw("DATA < CURDATE()");
                        $query->whereRaw("data_baixa IS NULL");
                        $query->groupBy("comissoes_id");
                    })->whereHas('contrato',function($query){
                        $query->whereIn("financeiro_id",[3,4,5,6,7,8,9,10]);
                    })
                    ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_atrasadas")->first()->total_quantidade_vidas_atrasadas;

                $qtd_atrasado_empresarial = ContratoEmpresarial
                    ::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
                    ->whereHas('comissao.comissoesLancadas',function($query){
                        $query->whereRaw("DATA < CURDATE()");
                        $query->whereRaw("data_baixa IS NULL");
                        $query->groupBy("comissoes_id");
                    })->count();



                $qtd_atrasado_valor_empresarial = ContratoEmpresarial
                    ::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
                    ->whereHas('comissao.comissoesLancadas',function($query){
                        $query->whereRaw("DATA < CURDATE()");
                        $query->whereRaw("data_baixa IS NULL");
                        $query->groupBy("comissoes_id");
                    })->selectRaw("sum(valor_total) as total_valor_plano")->first()->total_valor_plano;



                $qtd_atrasado_quantidade_vidas_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
                    $query->whereRaw("DATA < CURDATE()");
                    $query->whereRaw("data_baixa IS NULL");
                    $query->groupBy("comissoes_id");
                })
                    ->whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
                    ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_atrasadas")->first()->total_quantidade_vidas_atrasadas;

                $quantidade_total = $qtd_atrasado + $qtd_atrasado_empresarial;
                $valor_total = $qtd_atrasado_valor + $qtd_atrasado_valor_empresarial;
                $vidas_total = $qtd_atrasado_quantidade_vidas + $qtd_atrasado_quantidade_vidas_empresarial;
                $estagio = 4;

                break;

            case 5:

                $qtd_cancelado = Contrato::where("financeiro_id",12)->count();
                $quantidade_valor_cancelado = Contrato::where("financeiro_id",12)
                    ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_cancelado")->first()->valor_total_cancelado;
                $qtd_cancelado_quantidade_vidas = Cliente::whereHas('contrato',function($query){
                    $query->where("financeiro_id",12);
                })->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_cancelado")->first()->total_quantidade_vidas_cancelado;
                $qtd_cancelado_empresarial = ContratoEmpresarial::where("financeiro_id",12)->count();

                $quantidade_valor_cancelado_empresarial = ContratoEmpresarial::where("financeiro_id",12)
                    ->selectRaw("if(sum(valor_total)>=1,sum(valor_total),0) as valor_total_cancelado")->first()->valor_total_cancelado;

                $qtd_cancelado_quantidade_vidas_empresarial = ContratoEmpresarial::where("financeiro_id",12)
                    ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_cancelado")->first()->total_quantidade_vidas_cancelado;

                $quantidade_total = $qtd_cancelado + $qtd_cancelado_empresarial;
                $valor_total = $quantidade_valor_cancelado + $quantidade_valor_cancelado_empresarial;
                $vidas_total = $qtd_cancelado_quantidade_vidas + $qtd_cancelado_quantidade_vidas_empresarial;
                $estagio = 5;

                break;


            case 6:

                break;
        }
        return view('admin.pages.gerente.detalhe-card-todos',[
            "quantidade" => $quantidade_total,
            "valor" => $valor_total,
            "vidas" => $vidas_total,
            "estagio" => $estagio
        ]);
    }







    public function verDetalheCard($id_plano="all",$id_tipo="alll",$ano="all",$mes="all",$corretor="all")
    {
        return view('admin.pages.gerente.detalhe-card',[
            "id_plano" => $id_plano,
            "id_tipo" => $id_tipo,
            "ano" => $ano,
            "mes" => $mes,
            "corretor" => $corretor
        ]);
    }

    public function showDetalheCard($id_plano,$id_tipo,$ano,$mes,$corretor)
    {
        //$id_plano = $id_plano == "all" ? null : $id_plano;
        //$id_tipo = $id_tipo == "all" ? null : $id_tipo;
        $ano = $ano == "all" ? null : $ano;
        $mes = $mes == "all" ? null : $mes;
        $corretor = $corretor == "all" ? null : $corretor;








        if($id_plano == 1) {
            switch($id_tipo) {
                case 1:
                    $contratos = Contrato
                        ::where("plano_id",1)
                        //->whereIn('financeiro_id',[1,2,3,4,5,6,7,8,9,10])
                        ->whereHas('clientes',function($query)use($corretor){
                            //$query->whereRaw("cateirinha IS NOT NULL");
                            if($corretor) {
                                $query->where("user_id",$corretor);
                            }
                        })
                        ->where(function($query)use($ano,$mes){
                            if($ano) {
                                $query->whereYear('created_at',$ano);
                            }
                            if($mes) {
                                $query->whereMonth('created_at',$mes);
                            }
                        })


                        // ->whereHas('comissao.ultimaComissaoPaga',function($query){
                        //     $query->whereYear("data",2022);
                        //     $query->whereMonth('data','08');
                        // })
                        ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                        ->orderBy("id","desc")
                        ->get();
                    return $contratos;

                    break;
                case 2:

                    $contratos = Contrato
                        ::where("plano_id",1)
                        ->whereHas('clientes',function($query)use($corretor){
                            $query->whereRaw("cateirinha IS NOT NULL");
                            if($corretor) {
                                $query->where("user_id",$corretor);
                            }
                        })
                        ->where(function($query)use($ano,$mes){
                            if($ano) {
                                $query->whereYear('created_at',$ano);
                            }
                            if($mes) {
                                $query->whereMonth('created_at',$mes);
                            }
                        })
                        ->whereHas('comissao.comissoesLancadas',function($query){
                            $query->where("status_financeiro",1);
                            $query->where("status_gerente",1);
                            $query->where("valor","!=",0);
                        })
                        ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                        ->orderBy("id","desc")
                        ->get();
                    return $contratos;



                    break;
                case 3:
                    $contratos = Contrato
                        ::where("plano_id",1)
                        ->whereHas('clientes',function($query)use($corretor){
                            $query->whereRaw("cateirinha IS NOT NULL");
                            if($corretor) {
                                $query->where("user_id",$corretor);
                            }
                        })
                        ->where(function($query)use($ano,$mes){
                            if($ano) {
                                $query->whereYear('created_at',$ano);
                            }
                            if($mes) {
                                $query->whereMonth('created_at',$mes);
                            }
                        })
                        ->whereHas('comissao.comissoesLancadas',function($query){
                            $query->where("status_financeiro",1);
                            $query->where("status_gerente",0);
                            $query->where("valor","!=",0);
                        })
                        ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                        ->orderBy("id","desc")
                        ->get();
                    return $contratos;
                    break;
                case 4:
                    $contratos = Contrato
                        ::where("plano_id",1)
                        //->where("financeiro_id","!=",12)
                        ->whereHas('comissao.comissoesLancadas',function($query){
                            $query->whereRaw("DATA < CURDATE()");
                            //$query->whereRaw("valor > 0");
                            $query->whereRaw("data_baixa IS NULL");
                            $query->groupBy("comissoes_id");
                        })
                        ->where(function($query)use($ano,$mes){
                            if($ano) {
                                $query->whereYear('created_at',$ano);
                            }
                            if($mes) {
                                $query->whereMonth('created_at',$mes);
                            }
                        })
                        ->whereHas('clientes',function($query)use($corretor){
                            //$query->whereRaw('cateirinha IS NOT NULL');
                            if($corretor) {
                                $query->where("user_id",$corretor);
                            }
                        })
                        ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                        ->get();

                    return $contratos;


                    break;
                case 5:

                    $contratos = Contrato
                        ::where("financeiro_id",12)
                        ->where("plano_id",1)
                        ->where(function($query)use($ano,$mes){
                            if($ano) {
                                $query->whereYear('created_at',$ano);
                            }
                            if($mes) {
                                $query->whereMonth('created_at',$mes);
                            }
                        })
                        ->whereHas('clientes',function($query)use($corretor){
                            if($corretor) {
                                $query->where("user_id",$corretor);
                            }
                        })
                        ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                        ->get();

                    return $contratos;


                    break;
                case 6:

                    $contratos = Contrato
                        ::where("financeiro_id",11)
                        ->where("plano_id",1)
                        ->where(function($query)use($ano,$mes){
                            if($ano) {
                                $query->whereYear('created_at',$ano);
                            }
                            if($mes) {
                                $query->whereMonth('created_at',$mes);
                            }
                        })
                        ->whereHas('clientes',function($query)use($corretor){
                            if($corretor) {
                                $query->where("user_id",$corretor);
                            }
                        })
                        ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                        ->get();

                    return $contratos;

                    break;
                default:
                    return [];
                    break;
            }


        } else if($id_plano == 2) {
            switch($id_tipo) {
                case 1:
                    $contratos = Contrato
                        ::where("plano_id",3)
                        ->whereHas('clientes',function($query)use($corretor){
                            if($corretor) {
                                $query->where("user_id",$corretor);
                            }
                        })
                        ->where(function($query)use($ano,$mes){
                            if($ano) {
                                $query->whereYear('created_at',$ano);
                            }
                            if($mes) {
                                $query->whereMonth('created_at',$mes);
                            }
                        })

                        ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                        ->orderBy("id","desc")
                        ->get();
                    return $contratos;

                    break;
                case 2:

                    $contratos = Contrato
                        ::where("plano_id",3)
                        ->whereHas('clientes',function($query)use($corretor){
                            if($corretor) {
                                $query->where("user_id",$corretor);
                            }
                        })
                        ->where(function($query)use($ano,$mes){
                            if($ano) {
                                $query->whereYear('created_at',$ano);
                            }
                            if($mes) {
                                $query->whereMonth('created_at',$mes);
                            }
                        })
                        ->whereHas('comissao.comissoesLancadas',function($query){
                            $query->where("status_financeiro",1);
                            $query->where("status_gerente",1);
                            $query->where("valor","!=",0);
                        })
                        ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                        ->orderBy("id","desc")
                        ->get();
                    return $contratos;



                    break;
                case 3:
                    $contratos = Contrato
                        ::where("plano_id",3)
                        ->whereHas('clientes',function($query)use($corretor){
                            if($corretor) {
                                $query->where("user_id",$corretor);
                            }
                        })
                        ->where(function($query)use($ano,$mes){
                            if($ano) {
                                $query->whereYear('created_at',$ano);
                            }
                            if($mes) {
                                $query->whereMonth('created_at',$mes);
                            }
                        })
                        ->whereHas('comissao.comissoesLancadas',function($query){
                            $query->where("status_financeiro",1);
                            $query->where("status_gerente",0);
                            $query->where("valor","!=",0);
                        })
                        ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                        ->orderBy("id","desc")
                        ->get();
                    return $contratos;
                    break;
                case 4:
                    $contratos = Contrato
                        ::where("plano_id",3)
                        ->whereHas('clientes',function($query)use($corretor){
                            if($corretor) {
                                $query->where("user_id",$corretor);
                            }
                        })
                        ->where(function($query)use($ano,$mes){
                            if($ano) {
                                $query->whereYear('created_at',$ano);
                            }
                            if($mes) {
                                $query->whereMonth('created_at',$mes);
                            }
                        })
                        ->where("financeiro_id","!=",12)
                        ->whereHas('comissao.comissoesLancadas',function($query){
                            $query->whereRaw("DATA < CURDATE()");
                            //$query->whereRaw("valor > 0");
                            $query->whereRaw("data_baixa IS NULL");
                            $query->groupBy("comissoes_id");
                        })
                        ->whereHas('clientes',function($query){
                            $query->whereRaw('cateirinha IS NOT NULL');
                        })
                        ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                        ->get();

                    return $contratos;


                    break;
                case 5:

                    $contratos = Contrato
                        ::where("financeiro_id",12)
                        ->whereHas('clientes',function($query)use($corretor){
                            if($corretor) {
                                $query->where("user_id",$corretor);
                            }
                        })
                        ->where(function($query)use($ano,$mes){
                            if($ano) {
                                $query->whereYear('created_at',$ano);
                            }
                            if($mes) {
                                $query->whereMonth('created_at',$mes);
                            }
                        })
                        ->where("plano_id",3)
                        ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                        ->get();

                    return $contratos;


                    break;
                case 6:

                    $contratos = Contrato
                        ::where("financeiro_id",11)
                        ->whereHas('clientes',function($query)use($corretor){
                            if($corretor) {
                                $query->where("user_id",$corretor);
                            }
                        })
                        ->where(function($query)use($ano,$mes){
                            if($ano) {
                                $query->whereYear('created_at',$ano);
                            }
                            if($mes) {
                                $query->whereMonth('created_at',$mes);
                            }
                        })
                        ->where("plano_id",3)
                        ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                        ->get();

                    return $contratos;






                    break;
                default:
                    return [];
                    break;
            }
        } else if($id_plano == 3) {
            switch($id_tipo) {
                case 1:
                    return [];

                    break;
                case 2:
                    return [];

                    break;
                case 3:
                    return [];
                    break;
                case 4:
                    return [];
                    break;
                case 5:
                    return [];
                    break;
                case 6:
                    return [];
                    break;
                default:
                    return [];
                    break;
            }
        }
    }

    public function infoCorretorHistorico(Request $request)
    {
        $id = $request->id;
        $mes = $request->mes;

        $total_individual_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth('data_baixa_finalizado',$mes)
            ->whereHas('comissao',function($query) use($id){
                $query->where("plano_id",1);
                $query->where("user_id",$id);
            })->count();

        $total_empresarial_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth('data_baixa_finalizado',$mes)
            ->whereHas('comissao',function($query) use($id){
                $query->where("plano_id","!=",1);
                $query->where("plano_id","!=",3);
                $query->where("user_id",$id);
            })->count();

        $total_coletivo_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth('data_baixa_finalizado',$mes)
            ->whereHas('comissao',function($query)use($id){
                $query->where("plano_id",3);
                $query->where("user_id",$id);
            })->count();

        $total_individual = DB::select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_individual_valor FROM (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor THEN valor ELSE valor END), 0)
                    AS total_plano1
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            AND user_id = {$id}
            ) AS plano1,
            (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor THEN valor ELSE valor END), 0)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes}
            AND user_id = {$id}
            ) AS plano3;
        ")[0]->total_individual_valor;


        $total_empresarial = DB::select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_empresarial_valor FROM (
            SELECT SUM(valor) AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            AND user_id = {$id}
            ) AS plano1,
            (
            SELECT SUM(valor) AS total_plano3 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes}
            AND user_id = {$id}
            ) AS plano3
        ")[0]->total_empresarial_valor;

        $total_coletivo = DB::select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_coletivo_valor FROM (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor THEN valor ELSE valor END), 0)
                AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes} AND user_id = {$id}
            ) AS plano1,
            (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor THEN valor ELSE valor END), 0)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes} AND user_id = {$id}
            ) AS plano3
        ")[0]->total_coletivo_valor;


        $valores = ValoresCorretoresLancados::whereMonth('data',$mes)->where("user_id",$id);

        $va = $valores->first();
        $salario = $va->valor_salario;
        $premiacao = $va->valor_premiacao;
        $comissao = $va->valor_comissao;
        $desconto = $va->valor_desconto;
        $total = $va->valor_total;
        $estorno = $va->valor_estorno;


        return [
            "total_individual_quantidade" => $total_individual_quantidade,
            "total_empresarial_quantidade" => $total_empresarial_quantidade,
            "total_coletivo_quantidade" => $total_coletivo_quantidade,
            "total_individual" => $total_individual,
            "total_empresarial" => $total_empresarial,
            "total_coletivo" => $total_coletivo,

            "total_comissao" =>  number_format($comissao,2,",","."),
            "total_salario" =>  number_format($salario,2,",","."),
            "total_premiacao" =>  number_format($premiacao,2,",","."),

            "desconto" =>  number_format($desconto,2,",","."),
            "total" =>  number_format($total,2,",","."),
            "estorno" => number_format($estorno,2,",",".")

        ];

    }








    public function infoCorretor(Request $request)
    {
        $corretora_id = auth()->user()->corretora_id;
        $premiacao_cad = str_replace([".",","],["","."], $request->premiacao);
        $salario_cad = str_replace([".",","],["","."], $request->salario);
        $total_cad   = str_replace([".",","],["","."], $request->total);

        ValoresCorretoresLancados
            ::where("user_id",$request->user_id)
            ->whereMonth("data",$request->mes)
            ->whereYear("data",$request->ano)
            ->update(["valor_premiacao"=>$premiacao_cad,"valor_total"=>$total_cad,"valor_salario"=>$salario_cad,'corretora_id' => auth()->user()->corretora_id]);

        $id = $request->id;
        $mes = $request->mes;
        $ano = $request->ano;
        $salario = 0;
        $premiacao = 0;
        $comissao = 0;
        $desconto = 0;
        $total = 0;
        $estorno = 0;
        $valor_individual_a_receber = DB::connection('tenant')->select("
            SELECT
            COUNT(*) AS total
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            INNER JOIN contratos ON contratos.id = comissoes.contrato_id
            WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND
            comissoes_corretores_lancadas.status_gerente = 0 AND
            comissoes_corretores_lancadas.status_apto_pagar != 1 AND
            comissoes.user_id = {$id} AND contratos.financeiro_id != 12 AND comissoes_corretores_lancadas.valor != 0 AND comissoes.plano_id = 1 AND comissoes.corretora_id = {$corretora_id}
        ")[0]->total;

        $total_empresarial_quantidade_estorno = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where('data_baixa_estorno',null)
            ->where('valor',"!=",0)
            ->whereHas('comissao',function($query) use($id,$corretora_id){
                $query->where("plano_id","!=",1);
                $query->where("plano_id","!=",3);
                $query->where("user_id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->whereHas('comissao.contrato_empresarial',function($query){
                $query->where("financeiro_id",12);
            })
            ->count();

        $total_coletivo_quantidade_estorno = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where('data_baixa_estorno',null)
            ->where('valor',"!=",0)
            ->whereHas('comissao',function($query) use($id,$corretora_id){
                $query->where("plano_id","=",3);

                $query->where("user_id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->whereHas('comissao.contrato',function($query){
                $query->where("financeiro_id",12);
            })
            ->count();

        $total_individual_quantidade_estorno = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where('data_baixa_estorno',null)
            ->where('valor',"!=",0)
            ->whereHas('comissao',function($query) use($id,$corretora_id){
                $query->where("plano_id","=",1);

                $query->where("user_id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->whereHas('comissao.contrato',function($query){
                $query->where("financeiro_id",12);
            })
            ->count();






        $valor_coletivo_a_receber = DB::connection('tenant')->select("
            SELECT
            COUNT(*) AS total
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            INNER JOIN contratos ON contratos.id = comissoes.contrato_id
            WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND
            comissoes_corretores_lancadas.status_gerente = 0 AND
            comissoes_corretores_lancadas.status_apto_pagar != 1 AND contratos.financeiro_id != 12 AND
            comissoes.user_id = {$id} AND comissoes_corretores_lancadas.valor != 0 AND comissoes.plano_id = 3 AND comissoes.corretora_id = {$corretora_id}
        ")[0]->total;



        $valor_empresarial_a_receber = DB::connection('tenant')->select("
            SELECT
            COUNT(*) AS total
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            INNER JOIN contrato_empresarial ON comissoes.contrato_empresarial_id = contrato_empresarial.id
            WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND
            comissoes_corretores_lancadas.status_gerente = 0 AND
            comissoes_corretores_lancadas.status_apto_pagar != 1 AND
            comissoes.user_id = {$id} AND comissoes_corretores_lancadas.valor != 0 AND contrato_empresarial.corretora_id = {$corretora_id} AND contrato_empresarial.financeiro_id != 12
        ")[0]->total;




        $total_individual_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth('data_baixa_finalizado',$mes)
            ->whereYear('data_baixa_finalizado',$ano)
            ->whereHas('comissao',function($query) use($id,$corretora_id){
                $query->where("plano_id",1);
                $query->where("user_id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->whereHas('comissao.contrato',function($query){
                $query->where("financeiro_id","!=",12);
            })
            ->count();



        $total_empresarial_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth('data_baixa_finalizado',$mes)
            ->whereYear('data_baixa_finalizado',$ano)
            ->whereHas('comissao',function($query) use($id,$corretora_id){
                $query->where("plano_id","!=",1);
                $query->where("plano_id","!=",3);
                $query->where("user_id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->whereHas('comissao.contrato_empresarial',function($query){
                $query->where("financeiro_id","!=",12);
            })
            ->count();


        $total_empresarial_quantidade_estorno = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            //->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->where('data_baixa_estorno',null)
            ->where('valor',"!=",0)
            ->whereHas('comissao',function($query) use($id,$corretora_id){
                $query->where("plano_id","!=",1);
                $query->where("plano_id","!=",3);
                $query->where("user_id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->whereHas('comissao.contrato_empresarial',function($query){
                $query->where("financeiro_id",12);
            })
            ->count();






        $total_coletivo_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth('data_baixa_finalizado',$mes)
            ->whereYear('data_baixa_finalizado',$ano)
            ->whereHas('comissao',function($query)use($id,$corretora_id){
                $query->where("plano_id",3);
                $query->where("user_id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->whereHas('comissao.contrato',function($query){
                $query->where("financeiro_id","!=",12);
            })
            ->count();







        $total_individual = DB::connection('tenant')->select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_individual_valor FROM (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor THEN valor_pago ELSE valor END), 0)
                    AS total_plano1
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes} AND year(data_baixa_finalizado) = {$ano}
            AND user_id = {$id} AND comissoes.corretora_id = {$corretora_id}
            ) AS plano1,
            (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor THEN valor_pago ELSE valor END), 0)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes} AND year(data_baixa_finalizado) = {$ano}
            AND user_id = {$id} AND comissoes.corretora_id = {$corretora_id}
            ) AS plano3;
        ")[0]->total_individual_valor;



        $total_empresarial = DB::connection('tenant')->select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_empresarial_valor FROM (
            SELECT SUM(valor) AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
                AND year(data_baixa_finalizado) = {$ano}
            AND user_id = {$id} AND comissoes.corretora_id = {$corretora_id}
            ) AS plano1,
            (
            SELECT SUM(valor) AS total_plano3 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes}
                and year(data_baixa_estorno) = {$ano}
            AND user_id = {$id} AND comissoes.corretora_id = {$corretora_id}
            ) AS plano3
        ")[0]->total_empresarial_valor;



        $total_coletivo = DB::connection('tenant')->select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_coletivo_valor FROM (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor_pago THEN valor_pago ELSE valor END), 0)
                AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            and year(data_baixa_finalizado) = {$ano} AND user_id = {$id} AND comissoes.corretora_id = {$corretora_id}
            ) AS plano1,
            (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor_pago THEN valor_pago ELSE valor END), 0)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes}
            and year(data_baixa_estorno) = {$ano} AND user_id = {$id} AND comissoes.corretora_id = {$corretora_id}
            ) AS plano3
        ")[0]->total_coletivo_valor;



        if($comissao == 0 && ($total_coletivo > 0 || $total_individual > 0 || $total_empresarial > 0)) {
            $comissao = $total_coletivo + $total_individual + $total_empresarial;
        }

        $ids_confirmados = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->whereHas('comissao.user',function($query) use($id,$corretora_id){
                $query->where("id",$id);
                $query->where("corretora_id",$corretora_id);

            })
            ->selectRaw("GROUP_CONCAT(id) as ids")
            ->first()
            ->ids;



        $desconto = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->whereHas('comissao.user',function($query)  use($id,$corretora_id){
                $query->where("id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->selectRaw("if(SUM(desconto)>0,SUM(desconto),0) AS total")
            ->first()
            ->total;

        $valores = ValoresCorretoresLancados::whereMonth('data',$mes)->where('corretora_id',auth()->user()->corretora_id)->whereYear("data",$ano)->where("user_id",$id);


        if($valores->count() == 1) {
            $va = $valores->first();
            $salario = $va->valor_salario;
            $premiacao = $va->valor_premiacao;
            $comissao = $va->valor_comissao;
            $desconto = $va->valor_desconto;
            $total = $va->valor_total;
            $estorno = $va->valor_estorno;
        } else {
            $desconto = ComissoesCorretoresLancadas
                ::where("status_financeiro",1)
                ->where("status_apto_pagar",1)
                ->whereMonth("data_baixa_finalizado",$mes)
                ->whereYear("data_baixa_finalizado",$ano)
                ->whereHas('comissao.user',function($query) use($id,$corretora_id){
                    $query->where("id",$id);
                    $query->where("corretora_id",$corretora_id);
                })
                ->selectRaw("if(SUM(desconto)>0,SUM(desconto),0) AS total")
                ->first()
                ->total;

            $total = $comissao - $desconto;
        }



        $users = DB::connection('tenant')->select("
            select name as user,users.id as user_id,valor_total as total from
            valores_corretores_lancadas
            inner join users on users.id = valores_corretores_lancadas.user_id
            where MONTH(data) = {$mes} AND YEAR(data) = {$ano} AND users.corretora_id = {$corretora_id} order by users.name
        ");





        $usuarios = DB::connection('tenant')->select("
            SELECT users.id AS id, users.name AS name
            FROM comissoes_corretores_lancadas
                     INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                     INNER JOIN users ON users.id = comissoes.user_id
            WHERE (status_financeiro = 1 or status_gerente = 1) AND users.corretora_id = {$corretora_id}
              and finalizado != 1 and valor != 0 and users.id NOT IN (SELECT user_id FROM valores_corretores_lancadas
              WHERE MONTH(data) = {$mes} AND YEAR(data) = {$ano})
            GROUP BY users.id, users.name
            ORDER BY users.name;
         ");



        return [
            "total_empresarial_quantidade_estorno" => $total_empresarial_quantidade_estorno,
            "total_coletivo_quantidade_estorno" => $total_coletivo_quantidade_estorno,
            "total_individual_quantidade" => $total_individual_quantidade,
            "total_coletivo_quantidade" => $total_coletivo_quantidade,
            "total_empresarial_quantidade" => $total_empresarial_quantidade,
            "total_individual_quantidade_estorno" => $total_individual_quantidade_estorno,
            "total_empresarial_quantidade_estorno" => $total_empresarial_quantidade_estorno,
            "total_individual" => number_format($total_individual,2,",","."),
            "total_coletivo" => number_format($total_coletivo,2,",","."),
            "total_empresarial" => number_format($total_empresarial,2,",","."),
            "total_comissao" =>  number_format($comissao,2,",","."),
            "total_salario" =>  number_format($salario,2,",","."),
            "total_premiacao" =>  number_format($premiacao,2,",","."),
            "id_confirmados" => $ids_confirmados,
            "desconto" =>  number_format($desconto,2,",","."),
            "total" =>  number_format($total,2,",","."),
            "estorno" => number_format($estorno,2,",","."),
            "view" => view('gerente.list-users-pdf',[
                "users" => $users
            ])->render(),
            "usuarios" => $usuarios,
            "valor_individual_a_receber" => $valor_individual_a_receber,
            "valor_coletivo_a_receber" => $valor_coletivo_a_receber,
            "valor_empresarial_a_receber" => $valor_empresarial_a_receber
        ];


    }


    public function gerenteModalColetivo()
    {
        $contrato_id = request()->id;
        $contrato = Contrato::with(['cliente','cliente.user','comissao','comissao.comissoesLancadas','administradora'])->find($contrato_id);

        return view('gerente.modal-coletivo-gerente',[
           'contrato' => $contrato
        ]);


    }







    public function gerenteModalIndividual()
    {
        $contrato_id = request()->id;
        $contrato = Contrato::with(['cliente','cliente.user','comissao','comissao.comissoesLancadas'])->find($contrato_id);

        return view('gerente.modal-individual-gerente',[
            'contrato' => $contrato
        ]);
    }







    public function pegarTodosMesCorrente(Request $request) {
        $mes = $request->mes;
        $ano = $request->ano;
        $corretora_id = auth()->user()->corretora_id;
        $users = DB::connection('tenant')->select("
            select name as user,users.id as user_id,valor_total as total from
            valores_corretores_lancadas
            inner join users on users.id = valores_corretores_lancadas.user_id
            where valores_corretores_lancadas.corretora_id = {$corretora_id} AND MONTH(data) = {$mes} AND YEAR(data) = {$ano} order by users.name
        ");
        return [
            "view" => view('gerente.list-users-pdf',[
                "users" => $users
            ])->render(),
        ];
    }




    public function showTodosDetalheCard($estagio)
    {

        if($estagio == 1) {
            $dados = DB::select("
                select
                    case when comissoes.empresarial then
                        date_format(contrato_empresarial.created_at,'%d/%m/%Y')
                    else
                        date_format(contratos.created_at,'%d/%m/%Y')
                    end as data,
                    case when comissoes.empresarial then
                        contrato_empresarial.codigo_externo
                    else
                        contratos.codigo_externo
                    end as orcamento,
                    users.name as corretor,
                    case when comissoes.empresarial then
                        contrato_empresarial.razao_social
                    else
                        (select nome from clientes where clientes.id = contratos.cliente_id)
                    end as cliente,
                    case when comissoes.empresarial then
                        contrato_empresarial.cnpj
                    else
                        (select cpf from clientes where clientes.id = contratos.cliente_id)
                    end as documento,
                    case when comissoes.empresarial then
                        contrato_empresarial.quantidade_vidas
                    else
                        (select quantidade_vidas from clientes where clientes.id = contratos.cliente_id)
                    end as vidas,
                    case when comissoes.empresarial then
                        contrato_empresarial.valor_plano
                    else
                        contratos.valor_plano
                    end as valor,
                    comissoes.plano_id as plano,
                    planos.nome as plano_nome,
                    case when comissoes.empresarial then
                        contrato_empresarial.id
                    else
                        contratos.id
                    end as id
                from comissoes
                    inner join users on users.id = comissoes.user_id
                    inner join planos on planos.id = comissoes.plano_id
                    left join contratos on contratos.id = comissoes.contrato_id
                    left join contrato_empresarial on contrato_empresarial.id = comissoes.contrato_empresarial_id
                order by comissoes.created_at
            ");
            return $dados;
        } else if($estagio == 2) {
            $dados = Comissoes
                ::whereHas('comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",1);
                    $query->where("valor","!=",0);
                })
                ->with(['contrato','contrato.financeiro','contrato_empresarial','contrato_empresarial.financeiro','user','contrato.clientes','comissaoAtualFinanceiro','ultimaComissaoPaga'])->get();
            return $dados;
        } else if($estagio == 3) {
            $dados = DB::select("
                select
                    case when comissoes.empresarial then
                        date_format(contrato_empresarial.created_at,'%d/%m/%Y')
                    else
                        date_format(contratos.created_at,'%d/%m/%Y')
                    end as data,
                    case when comissoes.empresarial then
                        contrato_empresarial.codigo_externo
                    else
                        contratos.codigo_externo
                    end as orcamento,
                    users.name as corretor,
                    case when comissoes.empresarial then
                        contrato_empresarial.razao_social
                    else
                        (select nome from clientes where clientes.id = contratos.cliente_id)
                    end as cliente,
                    case when comissoes.empresarial then
                        contrato_empresarial.cnpj
                    else
                        (select cpf from clientes where clientes.id = contratos.cliente_id)
                    end as documento,
                    case when comissoes.empresarial then
                        contrato_empresarial.quantidade_vidas
                    else
                        (select quantidade_vidas from clientes where clientes.id = contratos.cliente_id)
                    end as vidas,
                    case when comissoes.empresarial then
                        contrato_empresarial.valor_plano
                    else
                        contratos.valor_plano
                    end as valor,
                    comissoes.plano_id as plano,
                    planos.nome as plano_nome,
                    case when comissoes.empresarial then
                        contrato_empresarial.id
                    else
                        contratos.id
                    end as id
                from comissoes
                    inner join users on users.id = comissoes.user_id
                    inner join planos on planos.id = comissoes.plano_id
                    left join contratos on contratos.id = comissoes.contrato_id
                    left join contrato_empresarial on contrato_empresarial.id = comissoes.contrato_empresarial_id

                where
                order by comissoes.created_at
            ");
            return $dados;









            $dados = Comissoes
                ::whereHas('comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",0);
                    $query->where("valor","!=",0);
                })
                ->with(['contrato','contrato.financeiro','contrato_empresarial','contrato_empresarial.financeiro','user','contrato.clientes','comissaoAtualFinanceiro','ultimaComissaoPaga'])->get();
            return $dados;
        } else if($estagio == 4) {
            $dados = Comissoes
                ::whereHas('comissoesLancadas',function($query){
                    $query->whereRaw("DATA < CURDATE()");
                    $query->whereRaw("data_baixa IS NULL");
                    $query->groupBy("comissoes_id");
                })
                ->with(['contrato','contrato.financeiro','contrato_empresarial','contrato_empresarial.financeiro','user','contrato.clientes','comissaoAtualFinanceiro','ultimaComissaoPaga'])
                ->get();

            return $dados;
        } else if($estagio == 5) {
            $dados = [];
            return $dados;
        } else if($estagio == 6) {
            $dados = [];
            return $dados;
        } else {
            $dados = [];
            return $dados;
        }
    }

    public function concluidos()
    {
        $dados = DB::select(
            "
            SELECT
            (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
            (SELECT NAME FROM users WHERE users.id = comissoes.user_id) AS corretor,
            (SELECT nome FROM planos WHERE planos.id = comissoes.plano_id) AS plano,
            (SELECT nome FROM tabela_origens WHERE tabela_origens.id = comissoes.tabela_origens_id) AS tabela_origens,
            comissoes_corretores_lancadas.data as vencimento,
            case when empresarial then
                (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (SELECT nome FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
            END AS cliente,
            case when empresarial then
                (SELECT codigo_externo FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id)
            END AS codigo_externo,
            case when empresarial then
                (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
            (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
            END AS valor,
            comissoes.id AS comissao
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE status_financeiro = 1 AND status_gerente = 1 AND valor != 0
            GROUP BY comissao
                ");

        return $dados;
    }




    public function listagem(Request $request)
    {

        if ($request->ajax()) {
            $cacheKey = 'listagemNaoConcluidosParcela';
            $tempoDeExpiracao = 60;

            $resultado = Cache::remember($cacheKey, $tempoDeExpiracao, function () {

                return DB::select('
                    SELECT
                    administradoras.nome AS administradora,
                    users.name AS corretor,
                    planos.nome AS plano,
                    tabela_origens.nome AS tabela_origens,
                    comissoes_corretores_lancadas.data as vencimento,
                        case when comissoes.empresarial then
                            (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                            else
                            (SELECT nome FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
                        END AS cliente,
                        case when comissoes.empresarial then
                            (SELECT codigo_externo FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                            else
                            (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id)
                        END AS codigo_externo,
                        case when comissoes.empresarial then
                            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                        else
                            (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                        END AS valor,
                    comissoes.id AS comissao FROM comissoes_corretores_lancadas
                    INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
//                    INNER JOIN comissoes_corretora_lancadas ON comissoes_corretora_lancadas.comissoes_id = comissoes.id
                    INNER JOIN administradoras ON administradoras.id = comissoes.administradora_id
                    INNER JOIN users ON users.id = comissoes.user_id
                    INNER JOIN planos ON planos.id = comissoes.plano_id
                    INNER JOIN tabela_origens ON tabela_origens.id = comissoes.tabela_origens_id
                    WHERE (comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_gerente = 0 AND comissoes_corretores_lancadas.valor != 0)
//                    or (comissoes_corretora_lancadas.status_financeiro = 1 AND comissoes_corretora_lancadas.status_gerente = 0 AND comissoes_corretora_lancadas.valor != 0)
                    GROUP BY comissao

                ');
            });
            return response()->json($resultado);

        }










        return [];
    }

    public function listarcontratos()
    {
        $dados = DB::select(
            "
            SELECT
            (SELECT nome FROM administradoras WHERE administradoras.id = contratos.administradora_id) AS administradora,
            (SELECT NAME FROM users WHERE users.id = clientes.user_id) AS corretor,
            clientes.nome AS cliente,
            (contratos.codigo_externo) AS codigo_externo,
            (SELECT nome FROM planos WHERE planos.id = contratos.plano_id) AS plano,
            (contratos.valor_plano) AS valor,
            (contratos.created_at) AS data_contrato,
            (SELECT nome FROM tabela_origens WHERE tabela_origens.id = contratos.tabela_origens_id) AS origem,
            (contratos.id) AS detalhe
            FROM clientes
            INNER JOIN contratos ON contratos.cliente_id = clientes.id
            "
        );
        return $dados;
    }

    public function listarcontratosDetalhe($id)
    {
        $contrato = Contrato::where("id",$id)
            ->with(['comissao','comissao.comissoesLancadasCorretora','comissao.comissoesLancadas','clientes','clientes.user'])
            ->first();
        return view('admin.pages.gerente.contrato',[
            "dados" => $contrato
        ]);
    }



    public function listarComissao($id)
    {
        $user = User::find($id);
        $comissao_valor = DB::select(
            "
                SELECT
                SUM(valor) as total
                FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND
                comissoes_corretores_lancadas.status_gerente = 1 AND comissoes_corretores_lancadas.status_comissao = 0 AND
                MONTH(comissoes_corretores_lancadas.data) = MONTH(NOW()) AND
                comissoes.user_id = $id
            "
        );

        $ids_confirmados = ComissoesCorretoresLancadas::where("status_financeiro",1)->where("status_apto_pagar",1)->selectRaw("GROUP_CONCAT(id) as ids")->first()->ids;

        $total_individual = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereHas('comissao',function($query) use($id){
                $query->where("plano_id",1);
                $query->where("user_id",$id);
            })->selectRaw("if(sum(valor)>0,sum(valor),0) as total_individual")->first()->total_individual;

        $total_coletivo = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereHas('comissao',function($query)use($id){
                $query->where("plano_id",3);
                $query->where("user_id",$id);
            })->selectRaw("if(sum(valor)>0,sum(valor),0) as total_coletivo")->first()->total_coletivo;


        $total_individual_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereHas('comissao',function($query) use($id){
                $query->where("plano_id",1);
                $query->where("user_id",$id);
            })->count();

        $total_coletivo_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereHas('comissao',function($query)use($id){
                $query->where("plano_id",3);
                $query->where("user_id",$id);
            })->count();

        $total_a_pagar = $total_individual + $total_coletivo;

        // $dados = DB::select("
        //     SELECT
        //     comissoes_id,
        //     (SELECT administradora_id FROM comissoes WHERE comissoes.id = comissoes_corretores_lancadas.comissoes_id) AS administradora,
        //     (SELECT nome FROM administradoras WHERE administradoras.id = (SELECT administradora_id FROM comissoes WHERE comissoes.id = comissoes_corretores_lancadas.comissoes_id)) AS nome_administradora,
        //     parcela,data,valor

        //     FROM comissoes_corretores_lancadas
        //     WHERE status_financeiro = 1 AND status_gerente = 1 ORDER BY nome_administradora,parcela
        // ");

        // $inicial = $dados[0]->nome_administradora;

        return view('admin.pages.gerente.comissao',[
            "usuario" => $user->name,
            "id" => $user->id,
            "total_comissao" => $comissao_valor[0]->total,
            "total_individual" => $total_individual,
            "total_coletivo" => $total_coletivo,
            "total_individual_quantidade" => $total_individual_quantidade,
            "total_coletivo_quantidade" => $total_coletivo_quantidade,
            "total_a_pagar" => $total_a_pagar,
            "ids_confirmados" => $ids_confirmados
        ]);



    }




//    private function recalcularValoresMesAno($user_id,$mes, $ano)
//    {
//        $data_comissao = date($ano . '-' . $mes . '-01');
//
//        // Obter todos os user_id que possuem comissões aptas no período
//        $corretores = ComissoesCorretoresLancadas::whereMonth('data_baixa_finalizado', $mes)
//            ->whereYear('data_baixa_finalizado', $ano)
//            ->where('status_apto_pagar', 1)
//            ->where('status_comissao', 1)
//            ->where('finalizado', 1)
//            ->pluck('user_id') // Somente os IDs únicos
//            ->unique();
//
//        // Iterar sobre cada corretor para recalcular os valores
//        foreach ($corretores as $user_id) {
//            // Obter as comissões do corretor
//            $comissoes = ComissoesCorretoresLancadas::where('user_id', $user_id)
//                ->whereMonth('data_baixa_finalizado', $mes)
//                ->whereYear('data_baixa_finalizado', $ano)
//                ->where('status_apto_pagar', 1)
//                ->where('status_comissao', 1)
//                ->where('finalizado', 1)
//                ->get();
//
//            // Calcular os valores somados
//            $valor_comissao = $comissoes->sum('valor_comissao');
//            $valor_desconto = $comissoes->sum('valor_desconto');
//            $valor_total = $valor_comissao - $valor_desconto;
//
//            // Atualizar ou criar registro na tabela ValoresCorretoresLancados
//            $valores = ValoresCorretoresLancados::firstOrNew([
//                'user_id' => $user_id,
//                'corretora_id' => auth()->user()->corretora_id,
//                'data' => $data_comissao,
//            ]);
//
//            $valores->valor_comissao = $valor_comissao;
//            $valores->valor_desconto = $valor_desconto;
//            $valores->valor_total = $valor_total;
//            $valores->save();
//        }
//    }


    private function recalcularValoresMesAno($user_id_atual, $mes, $ano)
    {
        // Data base para referência
        $data_comissao = date("$ano-$mes-01");
        // Buscar todos os user_ids relacionados, exceto o atual
        $corretores = ComissoesCorretoresLancadas::whereMonth('data_baixa_finalizado', $mes)
            ->whereYear('data_baixa_finalizado', $ano)
            ->where('status_apto_pagar', 1)
            ->where('status_comissao', 1)
            ->where('finalizado', 1)
            ->join('comissoes','comissoes.id','=','comissoes_corretores_lancadas.comissoes_id')
            ->whereHas('comissao', function ($query) use ($user_id_atual) {
                $query->where('comissoes.user_id', '!=', $user_id_atual); // Excluir o corretor atual
            })
            ->where("comissoes.corretora_id",auth()->user()->corretora_id)
            ->pluck('comissoes.user_id') // Obter os user_ids únicos
            ->unique();



        // Iterar por cada user_id e recalcular os valores
        foreach ($corretores as $user_id) {
            $dadosAgrupados = DB::select("
                WITH descontos_calculados AS (
                SELECT
                    comissoes_corretores_lancadas.valor AS valor_comissao,
                    CASE
                        WHEN comissoes.empresarial THEN 0
                        ELSE COALESCE((
                                          SELECT desconto_corretor
                                          FROM contratos
                                          WHERE contratos.id = comissoes.contrato_id
                                      ), 0)
                        END AS desconto
                FROM comissoes_corretores_lancadas
                         INNER JOIN comissoes
                                    ON comissoes_corretores_lancadas.comissoes_id = comissoes.id
                WHERE

                    comissoes_corretores_lancadas.status_financeiro = 1
                  AND comissoes_corretores_lancadas.status_apto_pagar = 1
                  AND comissoes.user_id = {$user_id}
                  AND MONTH(comissoes_corretores_lancadas.data_baixa_finalizado) = {$mes}
                  AND YEAR(comissoes_corretores_lancadas.data_baixa_finalizado) = {$ano}
            )
            SELECT
                SUM(valor_comissao) AS valor_comissao,
                SUM(desconto) AS desconto,
                SUM(valor_comissao - desconto) AS valor_total
            FROM descontos_calculados;


            ")[0];

            // Se não houver dados, pular para o próximo user_id
            if (!$dadosAgrupados) {
                continue;
            }

            // Criar ou atualizar os valores na tabela ValoresCorretoresLancados
            ValoresCorretoresLancados::updateOrCreate(
                [
                    'user_id' => $user_id,
                    'corretora_id' => auth()->user()->corretora_id,
                    'data' => $data_comissao,
                ],
                [
                    'valor_comissao' => $dadosAgrupados->valor_comissao,
                    'valor_desconto' => $dadosAgrupados->desconto,
                    'valor_total' => $dadosAgrupados->valor_total,
                ]
            );
        }

        //return true; // Operação concluída
    }

    public function aptarPagamento(Request $request)
    {
        $corretora_id = User::find($request->user_id)->corretora_id;
        $id_comissao = $request->id;
        $user_id = $request->user_id;
        $mes = $request->mes;
        $ano = $request->ano;
        $data_comissao = date($ano."-".$mes."-01");
        // Atualiza a comissão do corretor
        $co = ComissoesCorretoresLancadas::on('tenant')->where("id", $request->id)->first();
        $co->status_apto_pagar = 1;
        $co->status_comissao = 1;
        $co->finalizado = 1;
        $co->data_baixa_finalizado = $data_comissao;
        if(!$co->save()) return "error";

        $va = ValoresCorretoresLancados::on('tenant')
            ->where("user_id", $request->user_id)
            ->whereMonth('data', $request->mes)
            ->whereYear('data', $request->ano)
            ->where("corretora_id",$corretora_id)
            ->first();

        if (!$va) {

            $converter = fn($valor) => (float) str_replace(['.', ','], ['', '.'], $valor);
            $va = new ValoresCorretoresLancados();
            $va->user_id = $user_id;
            $va->corretora_id = $corretora_id;
            $va->valor_comissao = $request->comissao;
            $va->valor_salario = $converter($request->salario);
            $va->valor_premiacao = $converter($request->premiacao);
            $va->valor_desconto = $converter($request->desconto);
            $va->valor_estorno = $converter($request->estorno);
            $va->data = $data_comissao;
            $va->valor_total =
                ($va->valor_comissao +
                    $va->valor_salario +
                    $va->valor_premiacao) -
                ($va->valor_desconto +
                    $va->valor_estorno);
            $va->save();
            $id_folha_mes = FolhaMes::whereMonth("mes",$mes)->where("corretora_id",$corretora_id)->whereYear("mes",$ano)->first()->id;
            // Cria registro na folha de pagamento
            $folha = new FolhaPagamento();
            $folha->folha_mes_id = $id_folha_mes; // Substitua pelo id correto
            $folha->valores_corretores_lancados_id = $va->id;
            $folha->save();
        } else {

            $alt = ValoresCorretoresLancados::on('tenant')
                ->where("user_id", $request->user_id)
                ->whereMonth('data', $request->mes)
                ->whereYear('data', $request->ano)
                ->where("corretora_id",$corretora_id)
                ->first();
            $converter = fn($valor) => (float) str_replace(['.', ','], ['', '.'], $valor);
            $alt->valor_comissao += $request->comissao;
            $alt->valor_salario += $converter($request->salario);
            $alt->valor_premiacao += $converter($request->premiacao);
            $alt->valor_desconto += $converter($request->desconto);
            //$alt->valor_estorno += $converter($request->estorno);

            $alt->valor_total =
                ($alt->valor_comissao +
                    $alt->valor_salario +
                    $alt->valor_premiacao) -
                ($alt->valor_desconto +
                    $alt->valor_estorno);

            $alt->save();


        }

        return $this->infoCorretorUp($user_id,$corretora_id,$mes,$ano);
    }

    public function mudarStatusParaNaoPago(Request $request)
    {

        $corretora_id = User::find($request->user_id)->corretora_id;
        $ca = ComissoesCorretoresLancadas::on('tenant')->where("id", $request->id)->first();
        $ca->status_apto_pagar = 0;
        $ca->status_comissao = 0;
        $ca->finalizado = 0;
        $ca->data_baixa_finalizado = null;
        $ca->data_antecipacao = null;
        $ca->save();

        $valoresCorretores = ValoresCorretoresLancados
            ::where("user_id", $request->user_id)
            ->whereMonth("data", $request->mes)
            ->whereYear("data", $request->ano)
            ->where("corretora_id",$corretora_id)
            ->first();

        if ($valoresCorretores) {
            $converter = fn($valor) => (float) $valor;
            $valoresCorretores->valor_comissao -= $converter($request->comissao);
            //$valoresCorretores->valor_salario -= $converter($request->salario);
            //$valoresCorretores->valor_premiacao -= $converter($request->premiacao);
            $valoresCorretores->valor_desconto -= $converter($request->desconto);
            //$valoresCorretores->valor_estorno -= $converter($request->estorno);

            $valoresCorretores->valor_total =
                ($valoresCorretores->valor_comissao +
                $valoresCorretores->valor_salario +
                $valoresCorretores->valor_premiacao) -
                ($valoresCorretores->valor_desconto +
                $valoresCorretores->valor_estorno);

            // Remove registro se todos valores forem zerados
            if ($valoresCorretores->valor_total == 0 &&
                $valoresCorretores->valor_comissao == 0 &&
                $valoresCorretores->valor_salario == 0 &&
                $valoresCorretores->valor_premiacao == 0) {
                $valoresCorretores->delete();
            } else {
                $valoresCorretores->save();
            }


        }

        return $this->infoCorretorUp($request->user_id,$corretora_id,$request->mes,$request->ano);
    }

    public function contratoEstorno(Request $request)
    {
        $corretora_id = User::find($request->user_id)->corretora_id;
        $ano = $request->ano;
        $cc = ComissoesCorretoresLancadas::where("id",$request->id_parcela)->first();
        $cc->estorno = 1;
        $cc->data_baixa_estorno = date($ano."-".$request->mes."-01");
        $plano = Comissoes::find($cc->comissoes_id)->plano_id;
        if(!$cc->save()) return "error";
        $va = ValoresCorretoresLancados::where("user_id",$request->user_id)->whereMonth("data",$request->mes)->whereYear("data",$ano);
        if($va->count() == 1) {

            $converter = fn($valor) => (float) str_replace(['.', ','], ['', '.'], $valor);

            $alt = $va->first();
            $alt->valor_estorno += $request->valor;
            $alt->valor_total -= $request->valor;
            if(!$alt->save()) return "error";
        } else {
            $ca = new ValoresCorretoresLancados();
            $ca->valor_comissao = 0 - $request->valor;
            $ca->user_id = $request->user_id;
            $ca->valor_total = 0 - $request->valor;
            $ca->valor_estorno = $request->valor;
            $ca->data = date($ano."-".$request->mes."-01");
            if(!$ca->save()) return "error";
        }
        return $this->infoCorretorUp($request->user_id,$corretora_id,$request->mes,$ano);
    }












    public function infoCorretorUp($id,$corretora_id,$mes,$ano)
    {
        //$corretora_id = auth()->user()->corretora_id;
//        $premiacao_cad = str_replace([".",","],["","."], $request->premiacao);
//        $salario_cad = str_replace([".",","],["","."], $request->salario);
//        $total_cad   = str_replace([".",","],["","."], $request->total);
//
//        ValoresCorretoresLancados
//            ::where("user_id",$request->user_id)
//            ->whereMonth("data",$request->mes)
//            ->whereYear("data",$request->ano)
//            ->update(["valor_premiacao"=>$premiacao_cad,"valor_total"=>$total_cad,"valor_salario"=>$salario_cad,'corretora_id' => auth()->user()->corretora_id]);

//        $id = $request->id;
//        $mes = $request->mes;
//        $ano = $request->ano;
        $salario = 0;
        $premiacao = 0;
        $comissao = 0;
        $desconto = 0;
        $total = 0;
        $estorno = 0;
        $valor_individual_a_receber = DB::connection('tenant')->select("
            SELECT
            COUNT(*) AS total
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            INNER JOIN contratos ON contratos.id = comissoes.contrato_id
            WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND
            comissoes_corretores_lancadas.status_gerente = 0 AND
            comissoes_corretores_lancadas.status_apto_pagar != 1 AND contratos.financeiro_id != 12 AND
            comissoes.user_id = {$id} AND comissoes_corretores_lancadas.valor != 0 AND comissoes.plano_id = 1 AND comissoes.corretora_id = {$corretora_id}
        ")[0]->total;

        $valor_coletivo_a_receber = DB::connection('tenant')->select("
            SELECT
            COUNT(*) AS total
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            INNER JOIN contratos ON contratos.id = comissoes.contrato_id
            WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND
            comissoes_corretores_lancadas.status_gerente = 0 AND
            comissoes_corretores_lancadas.status_apto_pagar != 1 AND contratos.financeiro_id != 12 AND
            comissoes.user_id = {$id} AND comissoes_corretores_lancadas.valor != 0 AND comissoes.plano_id = 3 AND comissoes.corretora_id = {$corretora_id}
        ")[0]->total;




        /******Geral ********************************/

        $total_individual_quantidade_geral = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->whereHas('comissao',function($query) use($id,$corretora_id){
                $query->where("plano_id",1);
                $query->where("corretora_id",$corretora_id);
            })->count();

        $total_individual_geral = DB::connection('tenant')->select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_individual_valor FROM (
            SELECT SUM(valor) AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes.corretora_id = {$corretora_id} AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            and year(data_baixa_finalizado) = {$ano}
            ) AS plano1,
            (
            SELECT SUM(valor) AS total_plano3 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes.corretora_id = {$corretora_id} AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes}
            and year(data_baixa_finalizado) = {$ano}
            ) AS plano3;
        ")[0]->total_individual_valor;

        $total_coletivo_quantidade_geral = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereMonth('data_baixa_finalizado',$mes)
            ->whereYear('data_baixa_finalizado',$ano)
            ->whereHas('comissao',function($query)use($id,$corretora_id){
                $query->where("plano_id",3);

                $query->where("corretora_id",$corretora_id);
            })
            ->whereHas('comissao.contrato',function($query){
                $query->where("financeiro_id","!=",12);
            })
            ->count();

        $total_coletivo_geral = DB::connection('tenant')->select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_coletivo_valor FROM (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor_pago THEN valor_pago ELSE valor END), 0)
                AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            and year(data_baixa_finalizado) = {$ano} AND comissoes.corretora_id = {$corretora_id}
            ) AS plano1,
            (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor_pago THEN valor_pago ELSE valor END), 0)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes}
            and year(data_baixa_estorno) = {$ano} AND comissoes.corretora_id = {$corretora_id}
            ) AS plano3
        ")[0]->total_coletivo_valor;

        $total_empresarial_quantidade_geral = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereMonth('data_baixa_finalizado',$mes)
            ->whereYear('data_baixa_finalizado',$ano)
            ->whereHas('comissao',function($query) use($corretora_id){
                $query->where("plano_id","!=",1);
                $query->where("plano_id","!=",3);
                $query->where("corretora_id",$corretora_id);
            })
            ->count();

        $total_empresarial_geral = DB::connection('tenant')->select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_empresarial_valor FROM (
            SELECT SUM(valor) AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
                AND year(data_baixa_finalizado) = {$ano}
            AND comissoes.corretora_id = {$corretora_id}
            ) AS plano1,
            (
            SELECT SUM(valor) AS total_plano3 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes}
                and year(data_baixa_estorno) = {$ano}
            AND comissoes.corretora_id = {$corretora_id}
            ) AS plano3
        ")[0]->total_empresarial_valor;

        $valores_geral = DB::connection('tenant')->select("
            SELECT
	            SUM(valor_comissao) AS comissao,
	            SUM(valor_salario) AS salario,
	            SUM(valor_premiacao) AS premiacao,
	            SUM(valor_estorno) AS estorno,
	            SUM(valor_desconto) AS desconto,
	            SUM(valor_total) AS total
                FROM valores_corretores_lancadas WHERE MONTH(DATA) = {$mes} AND YEAR(DATA) = {$ano} AND corretora_id = {$corretora_id}
        ")[0];



        /*******Fim Geral***************************/






        $total_empresarial_quantidade_estorno = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where('data_baixa_estorno',null)
            ->where('valor',"!=",0)
            ->whereHas('comissao',function($query) use($id,$corretora_id){
                $query->where("plano_id","!=",1);
                $query->where("plano_id","!=",3);
                $query->where("user_id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->whereHas('comissao.contrato_empresarial',function($query){
                $query->where("financeiro_id",12);
            })
            ->count();

        $total_coletivo_quantidade_estorno = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where('data_baixa_estorno',null)
            ->where('valor',"!=",0)
            ->whereHas('comissao',function($query) use($id,$corretora_id){
                $query->where("plano_id","=",3);

                $query->where("user_id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->whereHas('comissao.contrato',function($query){
                $query->where("financeiro_id",12);
            })
            ->count();

        $total_individual_quantidade_estorno = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where('data_baixa_estorno',null)
            ->where('valor',"!=",0)
            ->whereHas('comissao',function($query) use($id,$corretora_id){
                $query->where("plano_id","=",1);

                $query->where("user_id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->whereHas('comissao.contrato',function($query){
                $query->where("financeiro_id",12);
            })
            ->count();


        $valor_empresarial_a_receber = DB::connection('tenant')->select("
            SELECT
            COUNT(*) AS total
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            INNER JOIN contrato_empresarial ON comissoes.contrato_empresarial_id = contrato_empresarial.id
            WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND
            comissoes_corretores_lancadas.status_gerente = 0 AND
            comissoes_corretores_lancadas.status_apto_pagar != 1 AND
            comissoes.user_id = {$id} AND comissoes_corretores_lancadas.valor != 0 AND contrato_empresarial.corretora_id = {$corretora_id} AND contrato_empresarial.financeiro_id != 12
        ")[0]->total;

        $total_empresarial_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth('data_baixa_finalizado',$mes)
            ->whereYear('data_baixa_finalizado',$ano)
            ->whereHas('comissao',function($query) use($id,$corretora_id){
                $query->where("plano_id","!=",1);
                $query->where("plano_id","!=",3);
                $query->where("user_id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->count();


        $total_empresarial_quantidade_estorno = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            //->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->where('data_baixa_estorno',null)
            ->where('valor',"!=",0)
            ->whereHas('comissao',function($query) use($id,$corretora_id){
                $query->where("plano_id","!=",1);
                $query->where("plano_id","!=",3);
                $query->where("user_id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->whereHas('comissao.contrato_empresarial',function($query){
                $query->where("financeiro_id",12);
            })
            ->count();






        $total_coletivo_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth('data_baixa_finalizado',$mes)
            ->whereYear('data_baixa_finalizado',$ano)
            ->whereHas('comissao',function($query)use($id,$corretora_id){
                $query->where("plano_id",3);
                $query->where("user_id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->whereHas('comissao.contrato',function($query){
                $query->where("financeiro_id","!=",12);
            })
            ->count();

        $total_individual_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth('data_baixa_finalizado',$mes)
            ->whereYear('data_baixa_finalizado',$ano)
            ->whereHas('comissao',function($query) use($id,$corretora_id){
                $query->where("plano_id",1);
                $query->where("user_id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->whereHas('comissao.contrato',function($query){
                $query->where("financeiro_id","!=",12);
            })
            ->count();





        $total_individual = DB::connection('tenant')->select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_individual_valor FROM (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor THEN valor ELSE valor_pago END), 0)
                    AS total_plano1
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes} AND year(data_baixa_finalizado) = {$ano}
            AND user_id = {$id} AND comissoes.corretora_id = {$corretora_id}
            ) AS plano1,
            (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor THEN valor ELSE valor_pago END), 0)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes} AND year(data_baixa_finalizado) = {$ano}
            AND user_id = {$id} AND comissoes.corretora_id = {$corretora_id}
            ) AS plano3;
        ")[0]->total_individual_valor;



        $total_empresarial = DB::connection('tenant')->select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_empresarial_valor FROM (
            SELECT SUM(valor) AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
                AND year(data_baixa_finalizado) = {$ano}
            AND user_id = {$id} AND comissoes.corretora_id = {$corretora_id}
            ) AS plano1,
            (
            SELECT SUM(valor) AS total_plano3 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes}
                and year(data_baixa_estorno) = {$ano}
            AND user_id = {$id} AND comissoes.corretora_id = {$corretora_id}
            ) AS plano3
        ")[0]->total_empresarial_valor;



        $total_coletivo = DB::connection('tenant')->select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_coletivo_valor FROM (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor_pago THEN valor_pago ELSE valor END), 0)
                AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            and year(data_baixa_finalizado) = {$ano} AND user_id = {$id} AND comissoes.corretora_id = {$corretora_id}
            ) AS plano1,
            (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor_pago THEN valor_pago ELSE valor END), 0)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes}
            and year(data_baixa_estorno) = {$ano} AND user_id = {$id} AND comissoes.corretora_id = {$corretora_id}
            ) AS plano3
        ")[0]->total_coletivo_valor;



        if($comissao == 0 && ($total_coletivo > 0 || $total_individual > 0 || $total_empresarial > 0)) {
            $comissao = $total_coletivo + $total_individual + $total_empresarial;
        }

        $ids_confirmados = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->whereHas('comissao.user',function($query) use($id,$corretora_id){
                $query->where("id",$id);
                $query->where("corretora_id",$corretora_id);

            })
            ->selectRaw("GROUP_CONCAT(id) as ids")
            ->first()
            ->ids;



        $desconto = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->whereHas('comissao.user',function($query)  use($id,$corretora_id){
                $query->where("id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->selectRaw("if(SUM(desconto)>0,SUM(desconto),0) AS total")
            ->first()
            ->total;

        $valores = ValoresCorretoresLancados::whereMonth('data',$mes)->where('corretora_id',auth()->user()->corretora_id)->whereYear("data",$ano)->where("user_id",$id);


        if($valores->count() == 1) {
            $va = $valores->first();
            $salario = $va->valor_salario;
            $premiacao = $va->valor_premiacao;
            $comissao = $va->valor_comissao;
            $desconto = $va->valor_desconto;
            $total = $va->valor_total;
            $estorno = $va->valor_estorno;
        } else {
            $desconto = ComissoesCorretoresLancadas
                ::where("status_financeiro",1)
                ->where("status_apto_pagar",1)
                ->whereMonth("data_baixa_finalizado",$mes)
                ->whereYear("data_baixa_finalizado",$ano)
                ->whereHas('comissao.user',function($query) use($id,$corretora_id){
                    $query->where("id",$id);
                    $query->where("corretora_id",$corretora_id);
                })
                ->selectRaw("if(SUM(desconto)>0,SUM(desconto),0) AS total")
                ->first()
                ->total;

            $total = $comissao - $desconto;
        }



        $users = DB::connection('tenant')->select("
            select name as user,users.id as user_id,valor_total as total from
            valores_corretores_lancadas
            inner join users on users.id = valores_corretores_lancadas.user_id
            where MONTH(data) = {$mes} AND YEAR(data) = {$ano} AND users.corretora_id = {$corretora_id} order by users.name
        ");





        $usuarios = DB::connection('tenant')->select("
            SELECT users.id AS id, users.name AS name
            FROM comissoes_corretores_lancadas
                     INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                     INNER JOIN users ON users.id = comissoes.user_id
            WHERE (status_financeiro = 1 or status_gerente = 1) AND users.corretora_id = {$corretora_id}
              and finalizado != 1 and valor != 0 and users.id NOT IN (SELECT user_id FROM valores_corretores_lancadas
              WHERE MONTH(data) = {$mes} AND YEAR(data) = {$ano})
            GROUP BY users.id, users.name
            ORDER BY users.name;
         ");



        return [
            "total_empresarial_quantidade_estorno" => $total_empresarial_quantidade_estorno,
            "total_coletivo_quantidade_estorno" => $total_coletivo_quantidade_estorno,
            "total_individual_quantidade" => $total_individual_quantidade,
            "total_coletivo_quantidade" => $total_coletivo_quantidade,
            "total_empresarial_quantidade" => $total_empresarial_quantidade,
            "total_individual_quantidade_estorno" => $total_individual_quantidade_estorno,
            "total_empresarial_quantidade_estorno" => $total_empresarial_quantidade_estorno,
            "total_individual" => number_format($total_individual,2,",","."),
            "total_coletivo" => number_format($total_coletivo,2,",","."),
            "total_empresarial" => number_format($total_empresarial,2,",","."),
            "total_comissao" =>  number_format($comissao,2,",","."),
            "total_salario" =>  number_format($salario,2,",","."),
            "total_premiacao" =>  number_format($premiacao,2,",","."),
            "id_confirmados" => $ids_confirmados,
            "desconto" =>  number_format($desconto,2,",","."),
            "total" =>  number_format($total,2,",","."),
            "estorno" => number_format($estorno,2,",","."),
            "view" => view('gerente.list-users-pdf',[
                "users" => $users
            ])->render(),
            "usuarios" => $usuarios,
            "valores_geral" => $valores_geral,
            "valor_individual_a_receber" => $valor_individual_a_receber,
            "valor_coletivo_a_receber" => $valor_coletivo_a_receber,
            "valor_empresarial_a_receber" => $valor_empresarial_a_receber,
            "total_individual_quantidade_geral" => $total_individual_quantidade_geral,
            "total_individual_geral" => $total_individual_geral,
            "total_coletivo_quantidade_geral" => $total_coletivo_quantidade_geral,
            "total_coletivo_geral" => $total_coletivo_geral,
            "total_empresarial_quantidade_geral" => $total_empresarial_quantidade_geral,
            "total_empresarial_geral" => $total_empresarial_geral
        ];


    }




    public function aptarPagamentoOld(Request $request)
    {

        // Dados a serem enviados para o Job
        $dados = [
            'id' => $request->id,
            'user_id' => $request->user_id,
            'mes' => $request->mes,
            'ano' => $request->ano,
            'comissao' => $request->comissao,
            'salario' => str_replace([".",","],["","."], $request->salario),
            'premiacao' => str_replace([".",","],["","."], $request->premiacao),
            'total' => str_replace([".",","],["","."], $request->total),
            'desconto' => $request->desconto,
            'estorno' => str_replace([".",","],["","."], $request->estorno),
        ];
        $corretora_id = auth()->user()->corretora_id;
        return $dados;
        try {
            ProcessarPagamentoJob::dispatchSync($dados, $corretora_id); // Processa imediatamente
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }


        // Despacha o Job
        //ProcessarPagamentoJob::dispatch($dados,$corretora_id);

        //return response()->json(['message' => 'Pagamento processado com sucesso.']);
    }

    public function comissaoListagemConfirmadasMesEspecifico(Request $request)
    {
        $corretora_id = auth()->user()->corretora_id;
        $mes = $request->mes;
        $ano = $request->ano;
        $id = $request->id;
        $valores = ValoresCorretoresLancados::whereMonth('data',$mes)->whereYear('data',$ano)->where("user_id",$id);
        $salario = 0;
        $premiacao = 0;
        $comissao = 0;
        $desconto = 0;
        $total = 0;
        $estorno = 0;

        $total_empresarial_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->whereHas('comissao',function($query)use($id){
                $query->where("plano_id","!=",1);
                $query->where("plano_id","!=",3);
                $query->where("user_id",$id);
            })->count();

        $total_empresarial = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->whereHas('comissao',function($query) use($id){
                $query->where("plano_id","!=",1);
                $query->where("plano_id","!=",3);
                $query->where("user_id",$id);
            })->selectRaw("if(sum(valor)>0,sum(valor),0) as total_coletivo")->first()->total_coletivo;

        $total_empresarial_quantidade_estorno = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            //->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->where('data_baixa_estorno',null)
            ->where('valor',"!=",0)
            ->whereHas('comissao',function($query) use($id,$corretora_id){
                $query->where("plano_id","!=",1);
                $query->where("plano_id","!=",3);
                $query->where("user_id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->whereHas('comissao.contrato_empresarial',function($query){
                $query->where("financeiro_id",12);
            })
            ->count();

        $total_coletivo_quantidade_estorno = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            //->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->where('data_baixa_estorno',null)
            ->where('valor',"!=",0)
            ->whereHas('comissao',function($query) use($id,$corretora_id){
                $query->where("plano_id","=",3);

                $query->where("user_id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->whereHas('comissao.contrato',function($query){
                $query->where("financeiro_id",12);
            })
            ->count();

        $total_individual_quantidade_estorno = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where('data_baixa_estorno',null)
            ->where('valor',"!=",0)
            ->whereHas('comissao',function($query) use($id,$corretora_id){
                $query->where("plano_id","=",1);

                $query->where("user_id",$id);
                $query->where("corretora_id",$corretora_id);
            })
            ->whereHas('comissao.contrato',function($query){
                $query->where("financeiro_id",12);
            })
            ->count();




        if($valores->count() != 0) {
            $dados = $valores->first();
            $total = number_format($dados->valor_total,2,",",".");
            $salario = number_format($dados->valor_salario,2,",",".");
            $premiacao = number_format($dados->valor_premiacao,2,",",".");
            $comissao = number_format($dados->valor_comissao,2,",",".");
            $desconto = number_format($dados->valor_desconto,2,",",".");
            $estorno = number_format($dados->valor_estorno,2,",",".");
        }

        $total_individual_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->whereHas('comissao',function($query) use($id){
                $query->where("plano_id",1);
                $query->where("user_id",$id);
            })->count();

        $total_coletivo_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado","=",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->whereHas('comissao',function($query)use($id){
                $query->where("plano_id",3);
                $query->where("user_id",$id);
            })->count();

        $total_individual = DB::connection('tenant')->select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_individual_valor FROM (
            SELECT SUM(valor) AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes.user_id = {$id} AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            and year(data_baixa_finalizado) = {$ano}
            ) AS plano1,
            (
            SELECT SUM(valor) AS total_plano3 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes.user_id = {$id} AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes}
            and year(data_baixa_finalizado) = {$ano}
            ) AS plano3;
        ")[0]->total_individual_valor;

        $total_coletivo = DB::connection('tenant')->select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_coletivo_valor FROM (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor_pago THEN valor_pago ELSE valor END), 0)
                    AS total_plano1
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes.user_id = {$id} AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            and year(data_baixa_finalizado) = {$ano}
            ) AS plano1,
            (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor_pago THEN valor_pago ELSE valor END), 0)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes.user_id = {$id} AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes}
            and year(data_baixa_finalizado) = {$ano}
            ) AS plano3
        ")[0]->total_coletivo_valor;

        $total_comissao = $total_individual + $total_coletivo;

        $ids_confirmados = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->whereHas('comissao.user',function($query) use($id){
                $query->where("id",$id);
            })
            ->selectRaw("GROUP_CONCAT(id) as ids")
            ->first()
            ->ids;

        $valor_individual_a_receber = DB::connection('tenant')->select("
            SELECT
            COUNT(*) AS total
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            INNER JOIN contratos on contratos.id = comissoes.contrato_id
            WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND
            comissoes_corretores_lancadas.status_gerente = 0 AND
            comissoes_corretores_lancadas.status_apto_pagar != 1 AND financeiro_id != 12 AND
            comissoes.user_id = {$id} AND comissoes_corretores_lancadas.valor != 0 AND comissoes.plano_id = 1
        ")[0]->total;

        $valor_coletivo_a_receber = DB::connection('tenant')->select("
            SELECT
            COUNT(*) AS total
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            INNER JOIN contratos on contratos.id = comissoes.contrato_id
            WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND
            comissoes_corretores_lancadas.status_gerente = 0 AND
            comissoes_corretores_lancadas.status_apto_pagar != 1 AND financeiro_id != 12 AND
            comissoes.user_id = {$id} AND comissoes_corretores_lancadas.valor != 0 AND comissoes.plano_id = 3
        ")[0]->total;

        $valor_empresarial_a_receber = DB::connection('tenant')->select("
            SELECT
            COUNT(*) AS total
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            INNER JOIN contrato_empresarial ON comissoes.contrato_empresarial_id = contrato_empresarial.id
            WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND
            comissoes_corretores_lancadas.status_gerente = 0 AND
            comissoes_corretores_lancadas.status_apto_pagar != 1 AND financeiro_id != 12 AND
            comissoes.user_id = {$id} AND comissoes_corretores_lancadas.valor != 0
        ")[0]->total;



        return [
            "valor_individual_a_receber" => $valor_individual_a_receber,
            "valor_coletivo_a_receber" => $valor_coletivo_a_receber,
            "valor_empresarial_a_receber" => $valor_empresarial_a_receber,
            "total_individual_quantidade" => $total_individual_quantidade,
            "total_coletivo_quantidade" => $total_coletivo_quantidade,
            "total_empresarial_quantidade" => $total_empresarial_quantidade,
            "total_empresarial_quantidade_estorno" => $total_empresarial_quantidade_estorno,
            "total_individual_quantidade_estorno" => $total_individual_quantidade_estorno,
            "total_coletivo_quantidade_estorno" => $total_coletivo_quantidade_estorno,
            "total_individual" => number_format($total_individual,2,",","."),
            "total_coletivo" => number_format($total_coletivo,2,",","."),
            "total_empresarial" => number_format($total_empresarial,2,",","."),
            "total_comissao" =>  number_format($total_comissao,2,",","."),
            "id_confirmados" => $ids_confirmados,
            "salario" => $salario,
            "comissao" => $comissao,
            "premiacao" => $premiacao,
            "desconto" => $desconto,
            "total" => $total,
            "estorno" => $estorno
        ];
    }


    public function estornoIndividual(Request $request)
    {
        $id = $request->id;
        $contratos = DB::connection('tenant')->select("
            select
    (select nome from grupoamerica.administradoras where administradoras.id = comissoes.administradora_id) as administradora,
    date_format((comissoes_corretores_lancadas.data),'%d/%m/%Y') as data,
    (contratos.codigo_externo) as codigo,
    (select nome from clientes where clientes.id = contratos.cliente_id) as cliente,
    (comissoes_corretores_lancadas.parcela) as parcela,
    (contratos.valor_plano) as valor,
    (comissoes_corretores_lancadas.valor) as total_estorno,
    contratos.id,
    comissoes.id as comissoes_id,
    comissoes.plano_id as plano,
    comissoes_corretores_lancadas.id as id_lancadas,
    cancelados
 from comissoes_corretores_lancadas
inner join comissoes on comissoes.id = comissoes_corretores_lancadas.comissoes_id
inner join contratos on contratos.id = comissoes.contrato_id
where
comissoes.plano_id = 1
and comissoes_corretores_lancadas.valor != 0
and comissoes_corretores_lancadas.estorno = 0
and comissoes_corretores_lancadas.cancelados = 0
and comissoes_corretores_lancadas.data_baixa_estorno IS NULL
  and contratos.financeiro_id = 12
  and
    exists (select * from `clientes` where `contratos`.`cliente_id` = `clientes`.`id` and `user_id` = ${id});
        ");
        return response()->json($contratos);
    }


    public function estornoEmpresarial(Request $request)
    {
        $id = $request->id;
        $contratos = DB::connection('tenant')->select("
            select
    ('Hapvida') as administradora,
    date_format(comissoes_corretores_lancadas.data,'%d/%m/%Y') as data,
    (contrato_empresarial.codigo_externo) as codigo,
    (razao_social) as cliente,
    parcela as parcela,
    (valor_plano) as valor,
    valor as total_estorno,
    contrato_empresarial.id,
    contrato_empresarial.plano_id as plano,
    comissoes.id as comissoes_id,
    comissoes_corretores_lancadas.id as id_lancadas
    from comissoes_corretores_lancadas
    inner join comissoes on comissoes.id = comissoes_corretores_lancadas.comissoes_id
    inner join contrato_empresarial on contrato_empresarial.id = comissoes.contrato_empresarial_id
    where contrato_empresarial.financeiro_id = 12 and contrato_empresarial.user_id = {$id} and cancelados = 0 and valor != 0 and comissoes_corretores_lancadas.estorno = 0;

        ");
        return response()->json($contratos);
    }


    public function comissaoListagemConfirmadasMesFechado(Request $request)
    {
        $mes = $request->mes;
        $ano = $request->ano;
        $plano = $request->plano;
        $corretora_id = auth()->user()->corretora_id;
        if($plano != 0) {
            $dados = DB::connection('tenant')->select("
    SELECT
    (SELECT nome FROM grupoamerica.administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
    (select name from users where users.id = comissoes.user_id) as corretor,
    DATE_FORMAT(contratos.created_at,'%d/%m/%Y') as created_at,
    contratos.codigo_externo as codigo,
    (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))) as cliente,

    comissoes_corretores_lancadas.parcela,
    (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id) as valor_plano,
    (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id) as codigo_externo,

    DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS vencimento,
    DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y') as data_baixa,
    if(
                (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                (SELECT valor FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
        ,
                (SELECT valor FROM comissoes_corretores_default WHERE
                        comissoes_corretores_default.plano_id = comissoes.plano_id AND
                        comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_default.corretora_id = comissoes.corretora_id AND
                        comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
        ) AS porcentagem,

    comissoes_corretores_lancadas.valor AS valor,
    (select nome from grupoamerica.planos where planos.id = comissoes.plano_id) as plano_nome,

    (comissoes.plano_id) AS plano,
    (SELECT if(quantidade_vidas >=1,quantidade_vidas,0) FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
    COALESCE((SELECT FORMAT(desconto_corretor, 2) FROM contratos WHERE contratos.id = comissoes.contrato_id AND comissoes_corretores_lancadas.id = (
		SELECT cc.id
FROM comissoes_corretores_lancadas cc
WHERE cc.comissoes_id = comissoes.id
		AND cc.valor != 0
ORDER BY cc.id
LIMIT 1
	)),0.00)
AS desconto,


    comissoes_corretores_lancadas.id,
    comissoes_corretores_lancadas.comissoes_id,
    contratos.id as contrato_id

FROM comissoes_corretores_lancadas
         INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
         INNER JOIN contratos ON comissoes.contrato_id = contratos.id
WHERE
        comissoes.corretora_id = {$corretora_id} AND
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        MONTH(data_baixa_finalizado) = {$mes} AND YEAR(data_baixa_finalizado) = {$ano} AND comissoes.plano_id = {$plano}
ORDER BY comissoes.administradora_id
        ");
        } else {
            $dados = DB::connection('tenant')->select("
        SELECT
        (SELECT nome FROM grupoamerica.administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
        DATE_FORMAT(contrato_empresarial.created_at,'%d/%m/%Y') as created_at,
        contrato_empresarial.codigo_externo as codigo,
        (contrato_empresarial.razao_social) as cliente,
        (select name from users where users.id = contrato_empresarial.user_id) as corretor,
        (select nome from grupoamerica.planos where contrato_empresarial.plano_id = planos.id) as plano_nome,
        comissoes_corretores_lancadas.parcela,
        contrato_empresarial.cnpj as codigo_externo,
        (contrato_empresarial.valor_plano) as valor_plano,
        DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS vencimento,
        DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y') as data_baixa,
           if(
                (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                (SELECT valor FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
        ,
                (SELECT valor FROM comissoes_corretores_default WHERE
                        comissoes_corretores_default.plano_id = comissoes.plano_id AND
                        comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_default.corretora_id = comissoes.corretora_id AND
                        comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
        ) AS porcentagem,
    if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS valor,

    (comissoes.plano_id) AS plano,
    (quantidade_vidas) AS quantidade_vidas,
    CASE
        WHEN contrato_empresarial.desconto_corretor IS NOT NULL THEN contrato_empresarial.desconto_corretor
        ELSE comissoes_corretores_lancadas.desconto
        END AS desconto,
    comissoes_corretores_lancadas.id,
    comissoes_corretores_lancadas.comissoes_id,
    contrato_empresarial.id as contrato_id
        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contrato_empresarial ON comissoes.contrato_empresarial_id = contrato_empresarial.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1
        AND
        contrato_empresarial.corretora_id = {$corretora_id} AND
        month(data_baixa_finalizado) = {$mes} AND YEAR(data_baixa_finalizado) = {$ano} AND valor != 0 AND comissoes.plano_id != 1 AND comissoes.plano_id != 3 ORDER BY comissoes.administradora_id
        ");
        }



        return $dados;


    }




    public function comissaoListagemConfirmadas(Request $request)
    {

        if($request->mes) {
            $id = $request->id;
            $mes = $request->mes;
            $ano = $request->ano;
            $dados = DB::connection('tenant')->select("
SELECT
    (SELECT nome FROM grupoamerica.administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
    DATE_FORMAT(contratos.created_at,'%d/%m/%Y') as created_at,
    contratos.codigo_externo as codigo,
    contratos.codigo_externo as codigo_externo,
    (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))) as cliente,
    comissoes_corretores_lancadas.parcela,
    (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id) as valor_plano,
    DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS vencimento,
    DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y') as data_baixa,
    CASE
    WHEN comissoes_corretores_lancadas.porcentagem_paga IS NOT NULL
        THEN comissoes_corretores_lancadas.porcentagem_paga
    WHEN (SELECT clt FROM users WHERE users.id = comissoes.user_id) = 1 THEN
        (SELECT valor FROM comissoes_corretores_default
         WHERE
             comissoes_corretores_default.plano_id = comissoes.plano_id AND
             comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
             comissoes_corretores_default.corretora_id = comissoes.corretora_id AND
             comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
    WHEN EXISTS (
        SELECT 1 FROM comissoes_corretores_configuracoes
        WHERE
            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela AND
            comissoes_corretores_configuracoes.corretora_id = comissoes.corretora_id
    ) THEN
        (SELECT valor FROM comissoes_corretores_configuracoes
         WHERE
             comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
             comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
             comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
             comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela AND
             comissoes_corretores_configuracoes.corretora_id = comissoes.corretora_id)
    ELSE
        (SELECT valor FROM comissoes_corretores_configuracoes
         WHERE
             comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
             comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
             comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela AND
             comissoes_corretores_configuracoes.corretora_id = comissoes.corretora_id AND
             comissoes_corretores_configuracoes.user_id IS NULL)
END AS porcentagem,
    /*if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS valor,*/
        (comissoes_corretores_lancadas.valor) as valor,
        (comissoes.plano_id) AS plano,
         (SELECT nome FROM grupoamerica.planos where comissoes.plano_id = planos.id) as plano_nome,
         (SELECT name FROM users WHERE users.id = comissoes.user_id) as corretor,
    (SELECT if(quantidade_vidas >=1,quantidade_vidas,0) FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
    CASE
        WHEN contratos.desconto_corretor IS NOT NULL THEN contratos.desconto_corretor
        ELSE comissoes_corretores_lancadas.desconto
    END AS desconto,


    comissoes_corretores_lancadas.id,
    comissoes_corretores_lancadas.comissoes_id,
    contratos.id as contrato_id

        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contratos ON comissoes.contrato_id = contratos.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        comissoes.user_id = {$id} AND MONTH(data_baixa_finalizado) = {$mes} AND YEAR(data_baixa_finalizado) = {$ano} AND comissoes.plano_id = 1
        ORDER BY comissoes.administradora_id
        ");
        } else {
            $id = $request->id;
            $dados = DB::connection('tenant')->select("
        SELECT
    (SELECT nome FROM grupoamerica.administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
    DATE_FORMAT(contratos.created_at,'%d/%m/%Y') as created_at,
    contratos.codigo_externo as codigo,
    (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))) as cliente,
    comissoes_corretores_lancadas.parcela,
    (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id) as valor_plano,
    DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS vencimento,
    DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y') as data_baixa,
    CASE
    WHEN comissoes_corretores_lancadas.porcentagem_paga IS NOT NULL
        THEN comissoes_corretores_lancadas.porcentagem_paga
    WHEN (SELECT clt FROM users WHERE users.id = comissoes.user_id) = 1 THEN
        (SELECT valor FROM comissoes_corretores_default
         WHERE
             comissoes_corretores_default.plano_id = comissoes.plano_id AND
             comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
             comissoes_corretores_default.corretora_id = comissoes.corretora_id AND
             comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
    WHEN EXISTS (
        SELECT 1 FROM comissoes_corretores_configuracoes
        WHERE
            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela AND
            comissoes_corretores_configuracoes.corretora_id = comissoes.corretora_id
    ) THEN
        (SELECT valor FROM comissoes_corretores_configuracoes
         WHERE
             comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
             comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
             comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
             comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela AND
             comissoes_corretores_configuracoes.corretora_id = comissoes.corretora_id)
    ELSE
        (SELECT valor FROM comissoes_corretores_configuracoes
         WHERE
             comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
             comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
             comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela AND
             comissoes_corretores_configuracoes.corretora_id = comissoes.corretora_id)
END AS porcentagem,
    if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS valor,

    (comissoes.plano_id) AS plano,
    (SELECT if(quantidade_vidas >=1,quantidade_vidas,0) FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
    CASE
        WHEN contratos.desconto_corretor IS NOT NULL THEN contratos.desconto_corretor
        ELSE comissoes_corretores_lancadas.desconto
    END AS desconto,


    comissoes_corretores_lancadas.id,
    comissoes_corretores_lancadas.comissoes_id,
    contratos.id as contrato_id
        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        comissoes.user_id = {$id} AND plano_id = 1 AND comissoes_corretores_lancadas.finalizado != 1
        ORDER BY comissoes.administradora_id
        ");
        }
        return $dados;
    }
    /*
        public function comissaoListagemConfirmadasEmpresarial(Request $request)
        {
            $id = $request->id;
            $dados = DB::select("
            SELECT
            (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
            (comissoes.plano_id) AS plano,
            comissoes_corretores_lancadas.data_antecipacao as data_antecipacao,
                case when comissoes.empresarial then
                                   (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                                   ELSE
                                   (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
                           END AS cliente,
                           DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
                           if(
                            comissoes_corretores_lancadas.data_baixa_gerente,
                            DATE_FORMAT(comissoes_corretores_lancadas.data_baixa_gerente,'%d/%m/%Y'),
                            DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y')
                        ) AS data_baixa_gerente,

                           case when empresarial then
                                (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                  else
                          (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                        END AS valor_plano_contratado,
                           comissoes_corretores_lancadas.valor AS comissao_esperada,
                           if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS comissao_recebida,
                        comissoes_corretores_lancadas.id,
                        comissoes_corretores_lancadas.comissoes_id,
                        comissoes_corretores_lancadas.parcela
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE
            comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
            comissoes.user_id = {$id} AND valor != 0 AND plano_id = 3
            ORDER BY comissoes.administradora_id
            ");

            return $dados;
        }
    */

    public function comissaoListagemConfirmadasEmpresarial(Request $request)
    {
        $corretora_id = auth()->user()->corretora_id;
        $id = $request->id;
        if($request->mes) {
            $mes = $request->mes;
            $ano = $request->ano;
            $dados = DB::connection('tenant')->select("
            select
            (SELECT nome FROM grupoamerica.planos WHERE planos.id = comissoes.plano_id) AS administradora,
    DATE_FORMAT(contrato_empresarial.created_at,'%d/%m/%Y') as created_at,
    contrato_empresarial.codigo_externo as codigo,
    contrato_empresarial.codigo_externo as codigo_externo,
    (contrato_empresarial.razao_social) as cliente,
    (SELECT nome FROM grupoamerica.planos WHERE comissoes.plano_id = planos.id) as plano_nome,
    (SELECT name FROM users WHERE contrato_empresarial.user_id = users.id) as corretor,
    comissoes_corretores_lancadas.parcela,
    (contrato_empresarial.valor_plano) as valor_plano,
    DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS vencimento,
    DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y') as data_baixa,
    CASE
                    WHEN comissoes_corretores_lancadas.porcentagem_paga != '' THEN comissoes_corretores_lancadas.porcentagem_paga
                    WHEN (
                        SELECT COUNT(*)
                        FROM comissoes_corretores_configuracoes
                        WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela
                    ) > 0 THEN (
                        SELECT valor
                        FROM comissoes_corretores_configuracoes
                        WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela
                    )
                    ELSE (
                        SELECT valor
                        FROM comissoes_corretores_default
                        WHERE
                            comissoes_corretores_default.plano_id = comissoes.plano_id AND
                            comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_default.corretora_id = {$corretora_id} AND
                            comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela
                    )
                    END AS porcentagem,
    if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS valor,
    (comissoes.plano_id) AS plano,
    (quantidade_vidas) AS quantidade_vidas,
    CASE
        WHEN contrato_empresarial.desconto_corretor IS NOT NULL THEN contrato_empresarial.desconto_corretor
        ELSE comissoes_corretores_lancadas.desconto
        END AS desconto,
    comissoes_corretores_lancadas.id,
    comissoes_corretores_lancadas.comissoes_id,
    contrato_empresarial.id as contrato_id
        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contrato_empresarial ON comissoes.contrato_empresarial_id = contrato_empresarial.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        comissoes.user_id = {$id} AND month(data_baixa_finalizado) = {$mes} AND year(data_baixa_finalizado) = {$ano} AND valor != 0 AND comissoes.plano_id != 1 AND comissoes.plano_id != 3 ORDER BY comissoes.administradora_id
        ");
        } else {
            $dados = DB::connection('tenant')->select("
            select
            (SELECT nome FROM grupoamerica.administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
    DATE_FORMAT(contrato_empresarial.created_at,'%d/%m/%Y') as created_at,
    contrato_empresarial.codigo_externo as codigo,
    (contrato_empresarial.razao_social) as cliente,
    (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id) as valor_plano,
    DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS vencimento,
    DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y') as data_baixa,
    if(
                (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                (SELECT valor FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
        ,
                (SELECT valor FROM comissoes_corretores_default WHERE
                        comissoes_corretores_default.plano_id = comissoes.plano_id AND
                        comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_default.corretora_id = {$corretora_id} AND
                        comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
        ) AS porcentagem,
    if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS valor,
    (comissoes.plano_id) AS plano,
    (SELECT if(quantidade_vidas >=1,quantidade_vidas,0) FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
    CASE
        WHEN contratos.desconto_corretor IS NOT NULL THEN contratos.desconto_corretor
        ELSE comissoes_corretores_lancadas.desconto
        END AS desconto,
    comissoes_corretores_lancadas.id,
    comissoes_corretores_lancadas.comissoes_id,
    contratos.id as contrato_id
        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contrato_empresarial ON comissoes.contrato_empresarial_id = contrato_empresarial.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        comissoes.user_id = {$id} AND valor != 0 AND comissoes.plano_id != 1 AND comissoes.plano_id != 3 ORDER BY comissoes.administradora_id
        ");
        }
        return $dados;
    }

    public function gerenteBuscarHistorico(Request $request)
    {
        $mes = $request->mes;
        $dados = DB::select("
            select
                (administradoras.nome) as administradora,
                case when comissoes.empresarial = 1 then
                    DATE_FORMAT(contrato_empresarial.created_at,'%d/%m/%Y')
                else
                    DATE_FORMAT(contratos.created_at,'%d/%m/%Y')
                end as data,
                case when comissoes.empresarial = 1 then
                    contrato_empresarial.codigo_externo
                else
                   contratos.codigo_externo
                end as codigo_externo,
                case when comissoes.empresarial = 1 then
                    contrato_empresarial.razao_social
                else
                    (select nome from clientes where clientes.id = contratos.cliente_id)
                end as cliente,
    comissoes_corretores_lancadas.parcela,
    users.name as corretor,
    comissoes_corretores_lancadas.valor,
    case when comissoes.empresarial = 1 then
        contrato_empresarial.valor_plano
    else
        contratos.valor_plano
    end as valor_plano,

    if(
                (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                (SELECT valor FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
        ,
                (SELECT valor FROM comissoes_corretores_default WHERE
                        comissoes_corretores_default.plano_id = comissoes.plano_id AND
                        comissoes_corretores_default.administradora_id = comissoes.administradora_id AND

                        comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
        ) AS porcentagem,
        planos.nome as plano,
        comissoes_corretores_lancadas.data_baixa_finalizado
from comissoes_corretores_lancadas
         inner join comissoes on comissoes.id = comissoes_corretores_lancadas.comissoes_id
         inner join administradoras on administradoras.id = comissoes.administradora_id
         inner join planos on planos.id = comissoes.plano_id
         inner join users on users.id = comissoes.user_id
         left join contratos on contratos.id = comissoes.contrato_id
         left join contrato_empresarial on contrato_empresarial.id = comissoes.contrato_empresarial_id
where comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND month(data_baixa_finalizado) = {$mes} and valor != 0
        ");


        $total_empresarial_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->where("finalizado",1)
            ->where("valor","!=",0)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereHas('comissao',function($query){
                $query->where("plano_id","!=",1);
                $query->where("plano_id","!=",3);
            })->count();

        $total_empresarial = DB::select("
                SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_empresarial_valor FROM (
                SELECT
                    COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor_pago THEN valor_pago ELSE valor END), 0)
                        AS total_plano1 FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
                ) AS plano1,
                (
                SELECT
                    COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor_pago THEN valor_pago ELSE valor END), 0)
                        AS total_plano3 FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes}
                ) AS plano3
            ")[0]->total_empresarial_valor;




        $total_individual_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->where("finalizado",1)
            ->where("valor","!=",0)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereHas('comissao',function($query){
                $query->where("plano_id",1);
            })->count();


        $total_coletivo_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->where("finalizado","=",1)
            ->where("valor","!=",0)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereHas('comissao',function($query){
                $query->where("plano_id",3);
            })->count();

        $total_individual = DB::select("
                SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_individual_valor FROM (
                SELECT SUM(valor) AS total_plano1 FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
                ) AS plano1,
                (
                SELECT SUM(valor) AS total_plano3 FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes}
                ) AS plano3;
            ")[0]->total_individual_valor;




        $total_coletivo = DB::select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_coletivo_valor FROM (
            SELECT
                COALESCE(SUM(comissoes_corretores_lancadas.valor), 0)
                    AS total_plano1
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            ) AS plano1,
            (
            SELECT
                COALESCE(SUM(comissoes_corretores_lancadas.valor), 0)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes}
            ) AS plano3
        ")[0]->total_coletivo_valor;



        $valores = DB::select("select
            FORMAT(sum(valor_comissao),2,'de_DE') as comissao,
            FORMAT(sum(valor_salario),2,'de_DE') as salario,
            FORMAT(sum(valor_premiacao),2,'de_DE') as premiacao,
            FORMAT(sum(valor_total),2,'de_DE') as total,
            FORMAT(sum(valor_desconto),2,'de_DE') as desconto,
            FORMAT(sum(valor_estorno),2,'de_DE') as estorno
            from valores_corretores_lancados where month(data) = {$mes}");


        return [
            "valores" => $valores[0],
            "data" => $dados,
            "total_empresarial_quantidade" => $total_empresarial_quantidade,
            "total_individual_quantidade" => $total_individual_quantidade,
            "total_coletivo_quantidade" => $total_coletivo_quantidade,
            "total_empresarial" => $total_empresarial,
            "total_individual" => $total_individual,
            "total_coletivo" => $total_coletivo
        ];
    }


    public function salarioUserHistorico(Request $request)
    {
        $user = $request->user;

        $user = User::where('name', 'like', '%' . $user . '%')->first()->id;
        $dados = ValoresCorretoresLancados::where("user_id",$user)->whereMonth("data",$request->mes)->first();


        return response()->json($dados);
    }





    public function comissaoListagemConfirmadasColetivo(Request $request)
    {
        $id = $request->id;
        if($request->mes) {
            $mes = $request->mes;
            $ano = $request->ano;
            $dados = DB::connection('tenant')->select("
        SELECT
    (SELECT nome FROM grupoamerica.administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
    DATE_FORMAT(contratos.created_at,'%d/%m/%Y') as created_at,
    contratos.codigo_externo as codigo,
    contratos.codigo_externo as codigo_externo,
    (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))) as cliente,
    comissoes_corretores_lancadas.parcela,
    (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id) as valor_plano,
    DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS vencimento,
    DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y') as data_baixa,
    CASE
                    WHEN comissoes_corretores_lancadas.porcentagem_paga != '' THEN comissoes_corretores_lancadas.porcentagem_paga
                    WHEN (
                        SELECT COUNT(*)
                        FROM comissoes_corretores_configuracoes
                        WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela
                    ) > 0 THEN (
                        SELECT valor
                        FROM comissoes_corretores_configuracoes
                        WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela
                    )
                    ELSE (
                        SELECT valor
                        FROM comissoes_corretores_default
                        WHERE
                            comissoes_corretores_default.plano_id = comissoes.plano_id AND
                            comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_default.corretora_id = comissoes.corretora_id AND
                            comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela
                    )
                    END AS porcentagem,
    comissoes_corretores_lancadas.valor AS valor,

    (comissoes.plano_id) AS plano,
    (SELECT nome FROM grupoamerica.planos WHERE planos.id = comissoes.plano_id) as plano_nome,
    (SELECT name FROM users WHERE users.id = comissoes.user_id) as corretor,
    (SELECT if(quantidade_vidas >=1,quantidade_vidas,0) FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
    COALESCE((SELECT FORMAT(desconto_corretor, 2) FROM contratos WHERE contratos.id = comissoes.contrato_id AND comissoes_corretores_lancadas.id = (
		SELECT cc.id
FROM comissoes_corretores_lancadas cc
WHERE cc.comissoes_id = comissoes.id
		AND cc.valor != 0
ORDER BY cc.id
LIMIT 1
	)),0.00)
AS desconto,


    comissoes_corretores_lancadas.id,
    comissoes_corretores_lancadas.comissoes_id,
    contratos.id as contrato_id
    from comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contratos ON comissoes.contrato_id = contratos.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        comissoes.user_id = {$id} AND month(data_baixa_finalizado) = {$mes} AND year(data_baixa_finalizado) = {$ano}  AND comissoes.plano_id = 3
        ORDER BY comissoes.administradora_id
        ");
        } else {
            $dados = DB::select("
        SELECT
    (SELECT nome FROM grupoamerica.administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
    DATE_FORMAT(contratos.created_at,'%d/%m/%Y') as created_at,
    contratos.codigo_externo as codigo,
    (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))) as cliente,
    comissoes_corretores_lancadas.parcela,
    (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id) as valor_plano,
    DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS vencimento,
    DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y') as data_baixa,
    CASE
                    WHEN comissoes_corretores_lancadas.porcentagem_paga != '' THEN comissoes_corretores_lancadas.porcentagem_paga
                    WHEN (
                        SELECT COUNT(*)
                        FROM comissoes_corretores_configuracoes
                        WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela
                    ) > 0 THEN (
                        SELECT valor
                        FROM comissoes_corretores_configuracoes
                        WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela
                    )
                    ELSE (
                        SELECT valor
                        FROM comissoes_corretores_default
                        WHERE
                            comissoes_corretores_default.plano_id = comissoes.plano_id AND
                            comissoes_corretores_default.administradora_id = comissoes.administradora_id AND

                            comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela
                    )
                    END AS porcentagem,
    comissoes_corretores_lancadas.valor AS valor,

    (comissoes.plano_id) AS plano,
    (SELECT if(quantidade_vidas >=1,quantidade_vidas,0) FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
    COALESCE((SELECT FORMAT(desconto_corretor, 2) FROM contratos WHERE contratos.id = comissoes.contrato_id AND comissoes_corretores_lancadas.id = (
		SELECT cc.id
FROM comissoes_corretores_lancadas cc
WHERE cc.comissoes_id = comissoes.id
		AND cc.valor != 0
ORDER BY cc.id
LIMIT 1
	)),0.00)
AS desconto,
    comissoes_corretores_lancadas.id,
    comissoes_corretores_lancadas.comissoes_id,
    contratos.id as contrato_id
    from comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contratos ON comissoes.contrato_id = contratos.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        comissoes.user_id = {$id}  AND comissoes.plano_id = 3
        ORDER BY comissoes.administradora_id
        ");
        }
        return $dados;
    }


















    /*
    public function comissaoListagemConfirmadasColetivo(Request $request)
    {
        $id = $request->id;

        if($request->mes) {
            $mes = $request->mes;
            $dados = DB::select("
        SELECT
        (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
        (comissoes.plano_id) AS plano,
        comissoes_corretores_lancadas.data_antecipacao as data_antecipacao,
            case when comissoes.empresarial then
                               (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                               ELSE
                               (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
                       END AS cliente,
                       DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
                       if(
                        comissoes_corretores_lancadas.data_baixa_gerente,
                        DATE_FORMAT(comissoes_corretores_lancadas.data_baixa_gerente,'%d/%m/%Y'),
                        DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y')
                    ) AS data_baixa_gerente,
                    case when empresarial then
                    (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                    COALESCE((SELECT FORMAT(desconto_corretor, 2) FROM contratos WHERE contratos.id = comissoes.contrato_id AND comissoes_corretores_lancadas.id = (
                            SELECT cc.id
                    FROM comissoes_corretores_lancadas cc
                    WHERE cc.comissoes_id = comissoes.id
                            AND cc.valor != 0
                    ORDER BY cc.id
                    LIMIT 1
                        )),0.00)
                    END AS desconto,
                       case when empresarial then
                            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                      (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                    END AS valor_plano_contratado,
                       comissoes_corretores_lancadas.valor AS comissao_esperada,
                       if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS comissao_recebida,
                    comissoes_corretores_lancadas.id,
                    comissoes_corretores_lancadas.comissoes_id,
                    comissoes_corretores_lancadas.parcela
        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contratos ON comissoes.contrato_id = contratos.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        comissoes.user_id = {$id} AND month(data_baixa_finalizado) = {$mes} AND valor != 0 AND comissoes.plano_id = 3
        ORDER BY comissoes.administradora_id
        ");
        } else {
            $dados = DB::select("
        SELECT
        (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
        (comissoes.plano_id) AS plano,
        comissoes_corretores_lancadas.data_antecipacao as data_antecipacao,
            case when comissoes.empresarial then
                               (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                               ELSE
                               (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
                       END AS cliente,
                       DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
                       if(
                        comissoes_corretores_lancadas.data_baixa_gerente,
                        DATE_FORMAT(comissoes_corretores_lancadas.data_baixa_gerente,'%d/%m/%Y'),
                        DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y')
                    ) AS data_baixa_gerente,

                    case when empresarial then
                    (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                    COALESCE((SELECT FORMAT(desconto_corretor, 2) FROM contratos WHERE contratos.id = comissoes.contrato_id AND comissoes_corretores_lancadas.id = (
                            SELECT cc.id
                    FROM comissoes_corretores_lancadas cc
                    WHERE cc.comissoes_id = comissoes.id
                            AND cc.valor != 0
                    ORDER BY cc.id
                    LIMIT 1
                        )),0.00)
                    END AS desconto,





                       case when empresarial then
                            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                      (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                    END AS valor_plano_contratado,
                       comissoes_corretores_lancadas.valor AS comissao_esperada,
                       if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS comissao_recebida,
                    comissoes_corretores_lancadas.id,
                    comissoes_corretores_lancadas.comissoes_id,
                    comissoes_corretores_lancadas.parcela
        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contratos ON comissoes.contrato_id = contratos.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        comissoes.user_id = {$id} AND valor != 0 AND comissoes.plano_id = 3
        ORDER BY comissoes.administradora_id
        ");
        }
        return $dados;
    }
    */

    public function gerenteChangeValorPlano(Request $request)
    {

        $valor = str_replace([".",","],["","."], $request->valor);//110
        $id = $request->id;
        $porcentagem = $request->porcentagem;//50





        $contrato = Contrato::where('id',Comissoes::where("id",ComissoesCorretoresLancadas::find($id)->comissoes_id)->first()->contrato_id)->first();

        $contrato->valor_plano = $valor;
        $contrato->save();

        $comissa_lancada = ComissoesCorretoresLancadas::where("id",$id)->first();





//        $comissa_lancada->valor = ($porcentagem / 100) * $valor;
//        $comissa_lancada->save();

        return $comissa_lancada;

    }



    public function comissaoMesAtual(Request $request)
    {
        $id = $request->id;
        $dados = DB::connection('tenant')->select("
        SELECT
        (SELECT nome FROM grupoamerica.administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
        comissoes_corretores_lancadas.created_at AS data_criacao,
        contratos.codigo_externo AS orcamento,
        (SELECT quantidade_vidas FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
        (SELECT plano_id FROM comissoes WHERE comissoes_corretores_lancadas.comissoes_id = comissoes.id) AS plano,
        comissoes_corretores_lancadas.data_antecipacao as data_antecipacao,
                       case when comissoes.empresarial then
                               (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                               ELSE
                               (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
                       END AS cliente,
                       DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
                       DATE_FORMAT(comissoes_corretores_lancadas.data_baixa_gerente,'%d/%m/%Y') AS data_baixa_gerente,

                       case when empresarial then
                            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                      (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                    END AS valor_plano_contratado,

                     case when empresarial then
                            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                      (SELECT desconto_corretor FROM contratos WHERE contratos.id = comissoes.contrato_id)
                    END AS desconto,
                       comissoes_corretores_lancadas.valor AS comissao_esperada,
                       if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS comissao_recebida,
                    comissoes_corretores_lancadas.id,
                    comissoes_corretores_lancadas.comissoes_id,
                    comissoes_corretores_lancadas.parcela,
                    if(
                        (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                            (SELECT valor FROM comissoes_corretores_configuracoes WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
                            ,
                            (SELECT valor FROM comissoes_corretores_default WHERE
                            comissoes_corretores_default.plano_id = comissoes.plano_id AND
                            comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
                        )
                    AS porcentagem_parcela_corretor,

                if(
                        (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                            (SELECT id FROM comissoes_corretores_configuracoes WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
                            ,
                            (SELECT id FROM comissoes_corretores_default WHERE
                            comissoes_corretores_default.plano_id = comissoes.plano_id AND
                            comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
                        )
                    AS id_porcentagem_parcela_corretor,
                    porcentagem_paga,
                    contratos.id as contrato_id

        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contratos ON comissoes.contrato_id = contratos.id
        WHERE
        (comissoes_corretores_lancadas.status_financeiro = 1 AND (comissoes_corretores_lancadas.status_gerente = 1 OR comissoes_corretores_lancadas.status_apto_pagar = 1)) AND
        comissoes.user_id = {$id}  AND valor != 0 AND status_comissao = 0 AND contratos.plano_id = 1 AND comissoes_corretores_lancadas.status_apto_pagar != 1
        ORDER BY comissoes.administradora_id
        ");

        return $dados;
    }

    public function zerarTabelas()
    {
        return [];
    }





    public function recebidasColetivo(Request $request)
    {
        $id = $request->id;
        $dados = DB::select("
        SELECT
        (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
        contratos.created_at AS data_criacao,
        comissoes.plano_id as plano,
        contratos.codigo_externo AS orcamento,
        (SELECT quantidade_vidas FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
        contratos.codigo_externo AS orcamento,
        comissoes_corretores_lancadas.data_antecipacao as data_antecipacao,
                       case when comissoes.empresarial then
                               (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                               ELSE
                               (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
                       END AS cliente,
                       DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
                       DATE_FORMAT(comissoes_corretores_lancadas.data_baixa_gerente,'%d/%m/%Y') AS data_baixa_gerente,

                       case when empresarial then
                            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                      (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                    END AS valor_plano_contratado,
                         case when empresarial then
                            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                      (SELECT desconto_corretor FROM contratos WHERE contratos.id = comissoes.contrato_id)
                    END AS desconto,
                       comissoes_corretores_lancadas.valor AS comissao_esperada,
                       if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS comissao_recebida,
                    comissoes_corretores_lancadas.id,
                    comissoes_corretores_lancadas.comissoes_id,
                    comissoes_corretores_lancadas.parcela,
                    if(
                        (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                            (SELECT valor FROM comissoes_corretores_configuracoes WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
                            ,
                            (SELECT valor FROM comissoes_corretores_default WHERE
                            comissoes_corretores_default.plano_id = comissoes.plano_id AND
                            comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
                        )
                    AS porcentagem_parcela_corretor,
            contratos.id as contrato_id
        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contratos ON comissoes.contrato_id = contratos.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_gerente = 1 AND comissoes_corretores_lancadas.status_apto_pagar != 1 AND
        comissoes.user_id = {$id}  AND valor != 0 AND status_comissao = 0 AND contratos.plano_id = 3 AND comissoes_corretores_lancadas.status_apto_pagar != 1
        ORDER BY comissoes.administradora_id
        ");

        return $dados;
    }

    public function recebidoEmpresarial(Request $request)
    {
        $id = $request->id;
        $dados = DB::select("
        SELECT
        (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
        comissoes_corretores_lancadas.created_at AS data_criacao,
        comissoes.plano_id as plano,
        contrato_empresarial.codigo_externo AS orcamento,
        contrato_empresarial.quantidade_vidas AS quantidade_vidas,
        comissoes_corretores_lancadas.data_antecipacao as data_antecipacao,
		case when comissoes.empresarial then
            (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
            ELSE
            (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
        END AS cliente,
                       DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
																							DATE_FORMAT(comissoes_corretores_lancadas.data_baixa_gerente,'%d/%m/%Y') AS data_baixa_gerente,
                       case when empresarial then
                            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                      (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                    END AS valor_plano_contratado,

																						 comissoes_corretores_lancadas.valor AS comissao_esperada,
                       if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS comissao_recebida,

																				comissoes_corretores_lancadas.id,

																				comissoes_corretores_lancadas.comissoes_id,
                    comissoes_corretores_lancadas.parcela,

                    if(
                        (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                            (SELECT valor FROM comissoes_corretores_configuracoes WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
                            ,
                            (SELECT valor FROM comissoes_corretores_default WHERE
                            comissoes_corretores_default.plano_id = comissoes.plano_id AND
                            comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
                        )  AS porcentagem_parcela_corretor,
            contrato_empresarial.id as contrato_id


        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contrato_empresarial ON comissoes.contrato_empresarial_id = contrato_empresarial.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND
								comissoes_corretores_lancadas.status_gerente = 1 AND
								comissoes_corretores_lancadas.status_apto_pagar != 1 AND
        comissoes.user_id = {$id}  AND comissoes_corretores_lancadas.valor != 0 AND
								status_comissao = 0 AND
								comissoes_corretores_lancadas.status_apto_pagar != 1
        ORDER BY comissoes.administradora_id
        ");
        return $dados;
    }






    public function comissaoMesDiferente(Request $request)
    {

        $id = $request->id;
        $dados = DB::connection('tenant')->select("
                SELECT
                comissoes_corretores_lancadas.id,
                comissoes_corretores_lancadas.parcela,
                contratos.created_at AS data_criacao,
                contratos.codigo_externo AS orcamento,
                DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
                comissoes_corretores_lancadas.valor,
                (SELECT if(quantidade_vidas >=1,quantidade_vidas,0) FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
                comissoes_corretores_lancadas.data_baixa as data_baixa,
                (SELECT plano_id FROM comissoes WHERE comissoes_corretores_lancadas.comissoes_id = comissoes.id) AS plano,

                case when empresarial then
                (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                END AS valor_plano_contratado,



               CASE
    WHEN comissoes_corretores_lancadas.porcentagem_paga IS NOT NULL
        THEN comissoes_corretores_lancadas.porcentagem_paga
    WHEN (SELECT clt FROM users WHERE users.id = comissoes.user_id) = 1 THEN
        (SELECT valor FROM comissoes_corretores_default
         WHERE
             comissoes_corretores_default.plano_id = comissoes.plano_id AND
             comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
             comissoes_corretores_default.corretora_id = comissoes.corretora_id AND
             comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
    WHEN EXISTS (
        SELECT 1 FROM comissoes_corretores_configuracoes
        WHERE
            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela AND
            comissoes_corretores_configuracoes.corretora_id = comissoes.corretora_id
    ) THEN
        (SELECT valor FROM comissoes_corretores_configuracoes
         WHERE
             comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
             comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
             comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
             comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela AND
             comissoes_corretores_configuracoes.corretora_id = comissoes.corretora_id)
    ELSE
        (SELECT valor FROM comissoes_corretores_configuracoes
         WHERE
             comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
             comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
             comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela AND
             comissoes_corretores_configuracoes.corretora_id = comissoes.corretora_id AND
			 comissoes_corretores_configuracoes.user_id IS NULL)
END AS porcentagem_parcela_corretor,


                case when empresarial then
   				    (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
   	            ELSE
				    (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
                END AS cliente,

                case when empresarial then
                (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (SELECT desconto_corretor FROM contratos WHERE contratos.id = comissoes.contrato_id)
                END AS desconto,

                (SELECT nome FROM grupoamerica.administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,

                contratos.id as contrato_id

                FROM comissoes_corretores_lancadas

                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                INNER JOIN contratos ON comissoes.contrato_id = contratos.id

                WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND
                comissoes_corretores_lancadas.status_gerente = 0 AND
                comissoes_corretores_lancadas.status_apto_pagar != 1 AND
                comissoes.user_id = {$id} AND contratos.financeiro_id != 12  AND comissoes_corretores_lancadas.valor != 0 AND contratos.plano_id = 1
                ORDER BY comissoes.administradora_id
        ");
        return $dados;
    }

    public function coletivoAReceber(Request $request)
    {
        $id = $request->id;
        $dados = DB::connection('tenant')->select("
                SELECT
                comissoes_corretores_lancadas.id,
                comissoes_corretores_lancadas.parcela,
                contratos.created_at AS data_criacao,
                comissoes_corretores_lancadas.data_baixa as data_baixa,
                contratos.codigo_externo AS orcamento,
                CASE
    WHEN comissoes_corretores_lancadas.porcentagem_paga IS NOT NULL
        THEN comissoes_corretores_lancadas.porcentagem_paga
    WHEN (SELECT clt FROM users WHERE users.id = comissoes.user_id) = 1 THEN
        (SELECT valor FROM comissoes_corretores_default
         WHERE
             comissoes_corretores_default.plano_id = comissoes.plano_id AND
             comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
             comissoes_corretores_default.corretora_id = comissoes.corretora_id AND
             comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
    WHEN EXISTS (
        SELECT 1 FROM comissoes_corretores_configuracoes
        WHERE
            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela AND
            comissoes_corretores_configuracoes.corretora_id = comissoes.corretora_id
    ) THEN
        (SELECT valor FROM comissoes_corretores_configuracoes
         WHERE
             comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
             comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
             comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
             comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela AND
             comissoes_corretores_configuracoes.corretora_id = comissoes.corretora_id)
    ELSE
        (SELECT valor FROM comissoes_corretores_configuracoes
         WHERE
             comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
             comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
             comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela AND
             comissoes_corretores_configuracoes.corretora_id = comissoes.corretora_id)
END AS porcentagem_parcela_corretor,

                case when empresarial then
                (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                END AS valor_plano_contratado,
                case when empresarial then
                    (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                    COALESCE((SELECT FORMAT(desconto_corretor, 2) FROM contratos WHERE contratos.id = comissoes.contrato_id AND comissoes_corretores_lancadas.id = (
                            SELECT cc.id
                    FROM comissoes_corretores_lancadas cc
                    WHERE cc.comissoes_id = comissoes.id
                            AND cc.valor != 0
                    ORDER BY cc.id
                    LIMIT 1
                        )),0.00)
                    END AS desconto,
                DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
                comissoes_corretores_lancadas.valor,
                (SELECT quantidade_vidas FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
                (SELECT plano_id FROM comissoes WHERE comissoes_corretores_lancadas.comissoes_id = comissoes.id) AS plano,
                case when empresarial then
   				    (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
   	            ELSE
				    (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
                END AS cliente,
                (SELECT nome FROM grupoamerica.administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
                contratos.id as contrato_id
                FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                INNER JOIN contratos ON comissoes.contrato_id = contratos.id
                WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND
                comissoes_corretores_lancadas.status_gerente = 0 AND
                comissoes_corretores_lancadas.status_apto_pagar != 1 AND contratos.financeiro_id != 12 AND
                comissoes.user_id = {$id} AND comissoes_corretores_lancadas.valor != 0 AND contratos.plano_id = 3
                ORDER BY comissoes.administradora_id
        ");
        return $dados;
    }

    public function aplicarDescontoCorretor(Request $request)
    {
        $id = $request->id;
        $desconto = str_replace([".",","],["","."],$request->porcentagem);
        $ca = ComissoesCorretoresLancadas::where("id",$id)->first();
        $comissao_id = $ca->comissoes_id;
        $comissao = Comissoes::where("id",$comissao_id)->first();
        if($comissao->empresarial == 0) {
            $contrato = Contrato::find($comissao->contrato_id);
            $contrato->desconto_corretor = $desconto;
            $contrato->save();
        } else {
            $contrato_empresarial = ContratoEmpresarial::find($comissao->contrato_empresarial_id);
            $contrato_empresarial->desconto_corretor = $desconto;
            $contrato_empresarial->save();
        }



        $ca->desconto = $desconto;
        $ca->save();
        return true;
    }

    public function empresarialAReceber(Request $request)
    {
        $corretora_id  = auth()->user()->corretora_id;
        $id = $request->id;
        $dados = DB::connection('tenant')->select("
        SELECT
            comissoes_corretores_lancadas.id,
            comissoes_corretores_lancadas.parcela,
            comissoes_corretores_lancadas.created_at as data_criacao,
            comissoes_corretores_lancadas.data_baixa as data_baixa,
            contrato_empresarial.codigo_externo AS orcamento,
            case when comissoes.empresarial = 1 then
            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
            (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
            END AS valor_plano_contratado,
            desconto_corretor as desconto,
            DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
            comissoes_corretores_lancadas.valor,
            contrato_empresarial.quantidade_vidas AS quantidade_vidas,
                    contrato_empresarial.plano_id AS plano,
                    CASE
    WHEN comissoes_corretores_lancadas.porcentagem_paga IS NOT NULL
        THEN comissoes_corretores_lancadas.porcentagem_paga
    WHEN (SELECT clt FROM users WHERE users.id = comissoes.user_id) = 1 THEN
        (SELECT valor FROM comissoes_corretores_default
         WHERE
             comissoes_corretores_default.plano_id = comissoes.plano_id AND
             comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
             comissoes_corretores_default.corretora_id = comissoes.corretora_id AND
             comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
    WHEN EXISTS (
        SELECT 1 FROM comissoes_corretores_configuracoes
        WHERE
            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela AND
            comissoes_corretores_configuracoes.corretora_id = comissoes.corretora_id
    ) THEN
        (SELECT valor FROM comissoes_corretores_configuracoes
         WHERE
             comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
             comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
             comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
             comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela AND
             comissoes_corretores_configuracoes.corretora_id = comissoes.corretora_id)
    ELSE
        (SELECT valor FROM comissoes_corretores_configuracoes
         WHERE
             comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
             comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
             comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela AND
             comissoes_corretores_configuracoes.corretora_id = comissoes.corretora_id)
END AS  porcentagem_parcela_corretor,

            contrato_empresarial.id as contrato_id,
            case when comissoes.empresarial = 1 then
                    (SELECT razao_social FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
            ELSE
                    (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
            END AS cliente,
            (SELECT nome FROM grupoamerica.planos WHERE planos.id = comissoes.plano_id) AS administradora
                    FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            INNER JOIN contrato_empresarial ON comissoes.contrato_empresarial_id = contrato_empresarial.id
            WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND
            comissoes_corretores_lancadas.status_gerente = 0 AND contrato_empresarial.financeiro_id != 12 AND
            comissoes_corretores_lancadas.status_apto_pagar != 1 AND
            comissoes.user_id = {$id} AND comissoes.corretora_id = {$corretora_id} AND comissoes_corretores_lancadas.valor != 0
            ORDER BY comissoes.administradora_id
        ");
        return $dados;
    }

    public function criarPdfPagamento()
    {
        $dados = Administradoras::with(['comissao','comissao.comissoesLancadasCorretoraQuantidade'])->get();
        $logo = 'data:image/png;base64,'.base64_encode(file_get_contents(public_path("storage/logo-certa.jpg")));
        return view('pages.gerente.pdf',[
            "dados" => $dados,
            "logo" => $logo
        ]);
    }

    public function finalizarPagamento(Request $request)
    {
        $mes = $request->mes;
        $ano = date("Y");
        $dia = date("d");
        $data = date($ano."-".$mes."-01");
        $data_comissao = date($ano."-".$mes."-01");
        $idFolhaMes = FolhaMes::whereMonth("mes",$mes)->first()->id;
        $ids = explode(",",$request->id);
        DB::table('comissoes_corretores_lancadas')
            ->whereIn('id', $ids)
            ->update(['status_comissao'=>1,"data_baixa_finalizado"=>$data_comissao]);
        $comissao = str_replace([".",","],["","."], $request->comissao);
        $salario = str_replace([".",","],["","."],$request->salario);
        $premiacao = str_replace([".",","],["","."],$request->premiacao);
        $desconto = str_replace([".",","],["","."],$request->desconto);
        $estorno = str_replace([".",","],["","."],$request->estorno);
        $total = str_replace([".",","],["","."],$request->total);
        $existe_valores_lancados = ValoresCorretoresLancados::whereMonth("data",$mes)->where("user_id",$request->user_id);
        if($existe_valores_lancados->count() > 0) {
            $valores_corretores_lancados = $existe_valores_lancados->first();
            $valores_corretores_lancados->valor_comissao = $comissao;
            $valores_corretores_lancados->valor_salario = $salario;
            $valores_corretores_lancados->valor_premiacao = $premiacao;
            $valores_corretores_lancados->valor_desconto = $desconto;
            $valores_corretores_lancados->valor_total = $total;
            $valores_corretores_lancados->valor_estorno = $estorno;
            $valores_corretores_lancados->save();
        } else {
            $valores_corretores_lancados = new ValoresCorretoresLancados();
            $valores_corretores_lancados->user_id = $request->user_id;
            $valores_corretores_lancados->data = $data;
            $valores_corretores_lancados->valor_comissao = $comissao;
            $valores_corretores_lancados->valor_salario = $salario;
            $valores_corretores_lancados->valor_premiacao = $premiacao;
            $valores_corretores_lancados->valor_desconto = $desconto;
            $valores_corretores_lancados->valor_estorno = $estorno;
            $valores_corretores_lancados->valor_total = $total;
            $valores_corretores_lancados->save();
        }


        $folha_existe = FolhaPagamento
            ::where("folha_mes_id",$idFolhaMes)
            ->where("valores_corretores_lancados_id",$valores_corretores_lancados->id);
        if($folha_existe->count() == 0) {
            $folhaPagamento = new FolhaPagamento();
            $folhaPagamento->folha_mes_id = $idFolhaMes;
            $folhaPagamento->valores_corretores_lancados_id = $valores_corretores_lancados->id;
            $folhaPagamento->save();
        }




        $users = DB::table('valores_corretores_lancados')
            ->selectRaw("(SELECT NAME FROM users WHERE users.id = valores_corretores_lancados.user_id) AS user,user_id")
            ->selectRaw("valor_total AS total")
            ->whereMonth('data',$mes)
            ->groupBy("user_id")
            ->get();

        $usuarios = DB::table('users')
            ->where('ativo',1)
            ->whereNotIn('id', function($query) use($mes) {
                $query->select('user_id')
                    ->from('valores_corretores_lancados')
                    ->whereMonth('data',$mes);
            })
            ->orderBy("name")
            ->get();


        return [
            'view' => view('gerente.list-users-pdf',[
                "users" => $users
            ])->render(),
            'users_aptos' => $usuarios
        ];




    }

    public function pagamentoMesFinalizado(Request $request)
    {
        $ano = $request->ano;
        $mes = $request->mes;
        $mes = FolhaMes::whereMonth("mes",$mes)->whereYear("mes",$ano)->where('corretora_id',auth()->user()->corretora_id)->where("status",0);
        if($mes->count() == 1) {
            $alt = $mes->first();
            $alt->status = 1;
            $alt->save();
            $dados = DB::connection('tenant')->table("comissoes_corretores_lancadas")
                ->join('comissoes','comissoes.id',"=","comissoes_corretores_lancadas.comissoes_id")
                ->where('status_financeiro', 1)
                ->where('status_apto_pagar',1)
                ->where('status_comissao',1)
                ->where('comissoes.corretora_id',auth()->user()->corretora_id)
                ->update(['finalizado' => 1]);

                DB::connection('tenant')->table('odonto')
                ->whereMonth('created_at','=',$request->mes)
                ->whereYear('created_at','=',$ano)
                ->update(['pagou' => 1]);

            return true;
        } else {
            return "sem_mes";
        }
    }

    public function criarPDFUserHistorico(Request $request)
    {
        $mes = $request->mes;
        $ano = $request->ano;
        $id = $request->user_id;
        $meses = [
            '01'=>"Janeiro",
            '02'=>"Fevereiro",
            '03'=>"Março",
            '04'=>"Abril",
            '05'=>"Maio",
            '06'=>"Junho",
            '07'=>"Julho",
            '08'=>"Agosto",
            '09'=>"Setembro",
            '10'=>"Outubro",
            '11'=>"Novembro",
            '12'=>"Dezembro"
        ];

        $mes_folha = $meses[$mes];
        $user = User::where("id",$request->user_id)->first()->name;
        $dados = ValoresCorretoresLancados::whereMonth("data",$mes)->whereYear("data",$ano)->where("user_id",$request->user_id)->first();

        $comissao = $dados->valor_comissao;
        $salario = $dados->valor_salario;
        $premiacao = $dados->valor_premiacao;

        $total = $dados->valor_total;
        $desconto = $dados->valor_desconto;
        $estorno = $dados->valor_estorno;

        $logo = "";
//        if(Corretora::first()->logo) {
//            $img_logo = Corretora::first()->logo;
//            $logo = 'data:image/png;base64,'.base64_encode(file_get_contents(public_path("storage/".$img_logo)));
//        }

        //$ids = explode("|",$request->ids);

        //DB::table("comissoes_corretores_lancadas")->whereIn('id', $ids)->update(['finalizado' => 1]);

        $individual = DB::connection('tenant')->select("
        SELECT

        (comissoes_corretores_lancadas.data) as created_at,
        (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id) as codigo_externo,
            case when comissoes.empresarial then
                               (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                               ELSE
                               (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
                       END AS cliente,
                       DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
                       if(
                        comissoes_corretores_lancadas.data_baixa_gerente,
                        DATE_FORMAT(comissoes_corretores_lancadas.data_baixa_gerente,'%d/%m/%Y'),
                        DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y')
                    ) AS data_baixa_gerente,

                       case when empresarial then
                            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                      (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                    END AS valor_plano_contratado,
                       comissoes_corretores_lancadas.valor AS comissao,
                    comissoes_corretores_lancadas.parcela,
                    case when empresarial then
                    (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                    COALESCE((SELECT FORMAT(desconto_corretor, 2) FROM contratos WHERE contratos.id = comissoes.contrato_id AND comissoes_corretores_lancadas.id = (
                            SELECT cc.id
                    FROM comissoes_corretores_lancadas cc
                    WHERE cc.comissoes_id = comissoes.id
                            AND cc.valor != 0
                    ORDER BY cc.id
                    LIMIT 1
                        )),0.00)
                    END AS desconto

        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contratos ON comissoes.contrato_id = contratos.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        comissoes.user_id = {$id} AND MONTH(data_baixa_finalizado) = {$mes} AND YEAR(data_baixa_finalizado) = {$ano} AND valor != 0 AND comissoes.plano_id = 1
        ORDER BY comissoes.administradora_id
        ");



        $coletivo = DB::connection('tenant')->select("
        SELECT
        (SELECT nome FROM grupoamerica.administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,

            case when comissoes.empresarial then
                               (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                               ELSE
                               (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
                       END AS cliente,
            (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id) as codigo_externo,
            (comissoes_corretores_lancadas.data) as created_at,
                       DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
                       if(
                        comissoes_corretores_lancadas.data_baixa_gerente,
                        DATE_FORMAT(comissoes_corretores_lancadas.data_baixa_gerente,'%d/%m/%Y'),
                        DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y')
                    ) AS data_baixa_gerente,
                    comissoes_corretores_lancadas.desconto AS desconto,
                       case when empresarial then
                            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                      (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                    END AS valor_plano_contratado,
                       comissoes_corretores_lancadas.valor AS comissao_esperada,
                       if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS comissao_recebida,

                    comissoes_corretores_lancadas.parcela
        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contratos ON comissoes.contrato_id = contratos.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        comissoes.user_id = {$id} AND month(data_baixa_finalizado) = {$mes} AND YEAR(data_baixa_finalizado) = {$ano} AND valor != 0 AND comissoes.plano_id = 3
        ORDER BY comissoes.administradora_id
        ");



        $empresarial = DB::connection('tenant')->select("
        SELECT
            (SELECT razao_social FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id) as cliente,
            (SELECT codigo_externo FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id) as codigo_externo,
            DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
            (SELECT desconto_corretor FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id) as desconto,
            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id) as valor_plano_contratado,
            comissoes_corretores_lancadas.valor AS comissao,
            comissoes_corretores_lancadas.parcela
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            INNER JOIN contrato_empresarial ON comissoes.contrato_empresarial_id = contrato_empresarial.id
            WHERE
            comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
            comissoes.user_id = {$id} AND month(data_baixa_finalizado) = {$mes} AND YEAR(data_baixa_finalizado) = {$ano} AND valor != 0 AND comissoes.plano_id != 1 AND comissoes.plano_id != 3 ORDER BY comissoes.administradora_id
        ");




        $estorno_table = DB::connection('tenant')->select(
            "select
            (select nome from grupoamerica.administradoras where administradoras.id = comissoes.administradora_id) as administradora,
            case when comissoes.empresarial then
                (select razao_social from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (select nome from clientes where clientes.id = (select cliente_id from contratos where contratos.id = comissoes.contrato_id))
            end as cliente,
            (select SUBSTRING_INDEX(nome,' ',1) from grupoamerica.planos where planos.id = comissoes.plano_id) as plano,
            DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
            case when comissoes.empresarial then
                (select valor_plano from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (select valor_plano from contratos where contratos.id = comissoes.contrato_id)
            end as valor,
            (comissoes_corretores_lancadas.valor) as total_estorno,
            case when comissoes.empresarial then
                (select codigo_externo from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (select codigo_externo from contratos where contratos.id = comissoes.contrato_id)
            end as contrato,
            (comissoes_corretores_lancadas.parcela) as parcela
            from comissoes_corretores_lancadas inner join comissoes on comissoes.id = comissoes_corretores_lancadas.comissoes_id
            where comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes} AND YEAR(data_baixa_finalizado) = {$ano} and comissoes.user_id = {$id}"
        );

        $primeiroDia = date('d/m/Y', strtotime($ano.'-' . $mes . '-01'));
        $ultimoDia = date('t/m/Y', strtotime($ano.'-' . $mes . '-01'));

        $pdf = PDFFile::loadView('gerente.pdf-folha-historico',[
            "individual" => $individual,
            "coletivo" => $coletivo,
            "empresarial" => $empresarial,
            "ano" => $ano,
            "meses" => $mes_folha,
            "salario" => $salario,
            "premiacao" => $premiacao,
            "comissao" => $comissao,
            "total" => $total,
            "logo" => $logo,
            "primeiro_dia" => $primeiroDia,
            "ultimo_dia" => $ultimoDia,
            "user" => $user,
            "desconto" => $desconto,
            "estorno" => $estorno,
            "estorno_table" => $estorno_table
        ]);


        $nome = Str::slug($user,"_");
        $mes_folha_nome = Str::slug($mes_folha);


        $nome_pdf = "folha_" . mb_convert_case($nome, MB_CASE_LOWER, "UTF-8") . "_" . $mes_folha_nome . "_" . date('d') . "_" . date('m') . "_" . date('s') . ".pdf";
        $response = $pdf->stream($nome_pdf, ['Attachment' => false]);
        $response->headers->set('Content-Disposition', 'inline; filename="' . $nome_pdf . '"');
        return $response;



    }

    public function criarPDFUser(Request $request)
    {
        $coletivo_valores = isset($request->coletivo_valores) && count($request->coletivo_valores) > 0 ? implode(",",$request->coletivo_valores) : 'null';
        $empresar_valores = isset($request->empresarial_valores) && count($request->empresarial_valores) > 0 ? implode(",",$request->empresarial_valores) : 'null';
        $mes = $request->mes;
        $id = $request->user_id;
        $ano = $request->ano;
        $meses = [
            '01'=>"Janeiro",
            '02'=>"Fevereiro",
            '03'=>"Março",
            '04'=>"Abril",
            '05'=>"Maio",
            '06'=>"Junho",
            '07'=>"Julho",
            '08'=>"Agosto",
            '09'=>"Setembro",
            '10'=>"Outubro",
            '11'=>"Novembro",
            '12'=>"Dezembro"
        ];


        $mes_folha = $meses[$mes];
        $user = User::where("id",$request->user_id)->first()->name;
        $dados = ValoresCorretoresLancados::whereMonth("data",$mes)->whereYear("data",$ano)->where("user_id",$request->user_id)->first();
        $comissao = $dados->valor_comissao;
        $salario = $dados->valor_salario;
        $premiacao = $dados->valor_premiacao;

        $total = $dados->valor_total;
        $desconto = $dados->valor_desconto;



        $logo = "";
//        if(Corretora::first()->logo) {
//            $img_logo = Corretora::first()->logo;
//            $logo = 'data:image/png;base64,'.base64_encode(file_get_contents(public_path("storage/".$img_logo)));
//        }

        //$ids = explode("|",$request->ids);

        //DB::table("comissoes_corretores_lancadas")->whereIn('id', $ids)->update(['finalizado' => 1]);

        $individual = DB::connection('tenant')->select("
        SELECT
        (comissoes_corretores_lancadas.data) as created_at,
        (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id) as codigo_externo,
            case when comissoes.empresarial then
                    (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                    ELSE
                    (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
                    END AS cliente,
                    DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
                    if(
                    comissoes_corretores_lancadas.data_baixa_gerente,
                    DATE_FORMAT(comissoes_corretores_lancadas.data_baixa_gerente,'%d/%m/%Y'),
                    DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y')
                    ) AS data_baixa_gerente,
                       case when empresarial then
                            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                      (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                    END AS valor_plano_contratado,
                       comissoes_corretores_lancadas.valor AS comissao,
                    comissoes_corretores_lancadas.parcela,
                    case when empresarial then
                    (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                    COALESCE((SELECT FORMAT(desconto_corretor, 2) FROM contratos WHERE contratos.id = comissoes.contrato_id AND comissoes_corretores_lancadas.id = (
                            SELECT cc.id
                    FROM comissoes_corretores_lancadas cc
                    WHERE cc.comissoes_id = comissoes.id
                            AND cc.valor != 0
                    ORDER BY cc.id
                    LIMIT 1
                        )),0.00)
                    END AS desconto
        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contratos ON comissoes.contrato_id = contratos.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        comissoes.user_id = {$id} AND MONTH(data_baixa_finalizado) = {$mes} AND YEAR(data_baixa_finalizado) = {$ano} AND valor != 0 AND comissoes.plano_id = 1
        ORDER BY comissoes.administradora_id
        ");



        $coletivo = DB::connection('tenant')->select("
        SELECT
        (SELECT nome FROM grupoamerica.administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
            case when comissoes.empresarial then
                               (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                               ELSE
                               (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
                       END AS cliente,
            (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id) as codigo_externo,
            (comissoes_corretores_lancadas.data) as created_at,
                       DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
                       if(
                        comissoes_corretores_lancadas.data_baixa_gerente,
                        DATE_FORMAT(comissoes_corretores_lancadas.data_baixa_gerente,'%d/%m/%Y'),
                        DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y')
                    ) AS data_baixa_gerente,

                    case when empresarial then
                     (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                    COALESCE((SELECT FORMAT(desconto_corretor, 2) FROM contratos WHERE contratos.id = comissoes.contrato_id AND comissoes_corretores_lancadas.id = (
                            SELECT cc.id
                    FROM comissoes_corretores_lancadas cc
                    WHERE cc.comissoes_id = comissoes.id
                            AND cc.valor != 0
                    ORDER BY cc.id
                    LIMIT 1
                        )),0.00)
                    END AS desconto,
                       case when empresarial then
                            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                      (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                    END AS valor_plano_contratado,
                       comissoes_corretores_lancadas.valor AS comissao_esperada,
                       if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS comissao_recebida,

                    comissoes_corretores_lancadas.parcela
        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contratos ON comissoes.contrato_id = contratos.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        comissoes.user_id = {$id} AND month(data_baixa_finalizado) = {$mes} AND YEAR(data_baixa_finalizado) = {$ano} AND valor != 0 AND comissoes.plano_id = 3
        ORDER BY comissoes.administradora_id
        ");


        $empresarial = DB::connection('tenant')->select("
        SELECT
            (SELECT razao_social FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id) as cliente,
            (SELECT codigo_externo FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id) as codigo_externo,
            DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
            (SELECT desconto_corretor FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id) as desconto,
            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id) as valor_plano_contratado,
            comissoes_corretores_lancadas.valor AS comissao,
            comissoes_corretores_lancadas.parcela
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            INNER JOIN contrato_empresarial ON comissoes.contrato_empresarial_id = contrato_empresarial.id
            WHERE
            comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
            comissoes.user_id = {$id} AND month(data_baixa_finalizado) = {$mes} AND YEAR(data_baixa_finalizado) = {$ano} AND valor != 0 ORDER BY comissoes.administradora_id
        ");




        $estorno_table = DB::connection('tenant')->select(
            "select
            (select nome from grupoamerica.administradoras where administradoras.id = comissoes.administradora_id) as administradora,
            case when comissoes.empresarial then
                (select razao_social from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (select nome from clientes where clientes.id = (select cliente_id from contratos where contratos.id = comissoes.contrato_id))
            end as cliente,
            (select SUBSTRING_INDEX(nome,' ',1) from grupoamerica.planos where planos.id = comissoes.plano_id) as plano,
            DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
            case when comissoes.empresarial then
                (select valor_plano from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (select valor_plano from contratos where contratos.id = comissoes.contrato_id)
            end as valor,
            (comissoes_corretores_lancadas.valor) as total_estorno,
            case when comissoes.empresarial then
                (select codigo_externo from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (select codigo_externo from contratos where contratos.id = comissoes.contrato_id)
            end as contrato,
            (comissoes_corretores_lancadas.parcela) as parcela
            from comissoes_corretores_lancadas inner join comissoes on comissoes.id = comissoes_corretores_lancadas.comissoes_id
            where comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes} AND YEAR(data_baixa_estorno) = {$ano} and comissoes.user_id = {$id}"
        );




        $primeiroDia = date('d/m/Y', strtotime($ano.'-' . $mes . '-01'));
        $ultimoDia = date('t/m/Y', strtotime($ano.'-' . $mes . '-01'));

        $boolean_individual = $request->individual == "true" ? 1 : 0;
        $boolean_coletivo = $request->coletivo == "true" ? 1 : 0;
        $boolean_empresarial = $request->empresarial  == "true" ? 1 : 0;

        $estorno = 0;
        if($estorno_table && $boolean_coletivo) {
            $estorno = $dados->valor_estorno;
        }

        $total_individual = DB::connection('tenant')->select("SELECT SUM((comissoes_corretores_lancadas.valor)) AS total FROM comissoes_corretores_lancadas INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND comissoes.user_id = ${id} AND MONTH(data_baixa_finalizado) = {$mes} AND YEAR(data_baixa_finalizado) = {$ano} AND valor != 0 AND comissoes.plano_id = 1")[0]->total;
        //$total_coletivo = DB::select("SELECT SUM((comissoes_corretores_lancadas.valor)) AS total FROM comissoes_corretores_lancadas INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND comissoes.user_id = {$id} AND MONTH(data_baixa_finalizado) = {$mes} AND valor != 0 AND comissoes.plano_id = 3 AND comissoes.administradora_id IN(".$coletivo_valores.")")[0]->total;
        $total_coletivo = DB::connection('tenant')->select("SELECT SUM((comissoes_corretores_lancadas.valor)) AS total FROM comissoes_corretores_lancadas INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND comissoes.user_id = {$id} AND MONTH(data_baixa_finalizado) = {$mes} AND YEAR(data_baixa_finalizado) = {$ano} AND valor != 0 AND comissoes.plano_id = 3")[0]->total;
        //$total_empresarial = DB::select("SELECT	SUM((comissoes_corretores_lancadas.valor)) AS total FROM comissoes_corretores_lancadas INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND comissoes.user_id = {$id} AND MONTH(data_baixa_finalizado) = {$mes} AND valor != 0 AND comissoes.plano_id IN(".$empresar_valores.")")[0]->total;
        $total_empresarial = DB::connection('tenant')->select("SELECT	SUM((comissoes_corretores_lancadas.valor)) AS total FROM comissoes_corretores_lancadas INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND comissoes.user_id = {$id} AND MONTH(data_baixa_finalizado) = {$mes} AND YEAR(data_baixa_finalizado) = {$ano} AND valor != 0 AND comissoes.plano_id != 1 AND comissoes.plano_id != 3")[0]->total;

        $total =   floatval($total_individual) + floatval($total_coletivo) +  floatval($total_empresarial);


        $total_coletivo_desconto = DB::connection('tenant')->select("
        SELECT SUM(
            COALESCE(
                (
                    SELECT FORMAT(desconto_corretor, 2)
                    FROM contratos
                    WHERE contratos.id = comissoes.contrato_id AND comissoes_corretores_lancadas.id = (
                        SELECT cc.id
                        FROM comissoes_corretores_lancadas cc
                        WHERE cc.comissoes_id = comissoes.id AND cc.valor != 0

                        LIMIT 1
                    )
                ),
                0.00
            )
        ) AS total
        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        WHERE
            comissoes_corretores_lancadas.status_financeiro = 1 AND
            comissoes_corretores_lancadas.status_apto_pagar = 1 AND
            comissoes.user_id = {$id} AND
            MONTH(data_baixa_finalizado) = {$mes} AND
            YEAR(data_baixa_finalizado) = {$ano}
            AND
            valor != 0 AND
            comissoes.plano_id = 3")[0]->total;




        $total_individual_desconto = 0;
        $total_empresarial_desconto = DB::connection('tenant')->select("SELECT SUM((SELECT desconto_corretor FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)) AS total FROM comissoes_corretores_lancadas INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND comissoes.user_id = {$id} AND month(data_baixa_finalizado) = {$mes} AND YEAR(data_baixa_finalizado) = {$ano} AND valor != 0 AND comissoes.plano_id != 1 AND comissoes.plano_id != 3 ORDER BY comissoes.administradora_id")[0]->total;

        $total_desconto = $total_coletivo_desconto + $total_individual_desconto + $total_empresarial_desconto;

        $odonto = Odonto::where('user_id', $id)
            ->whereNull('pagou')
            ->sum('comissao');

        $odonto_list = Odonto::where('user_id', $id)
            ->whereNull('pagou')
            ->get();




        if($boolean_individual && $boolean_coletivo && $boolean_empresarial) {
            $comissao = $dados->valor_comissao;
            $total = $dados->valor_total + floatval($odonto);
            $desconto = $dados->valor_desconto;
        } elseif($boolean_individual && !$boolean_coletivo && !$boolean_empresarial) {
            $comissao = floatval($total_individual)  + floatval($odonto);
            $desconto = 0;
            $total = $comissao - $desconto;
        } elseif($boolean_individual && $boolean_coletivo && !$boolean_empresarial) {
            $comissao = floatval($total_individual) + floatval($total_coletivo)  + floatval($odonto);
            $desconto = floatval($total_coletivo_desconto) + floatval($total_individual_desconto);
            $total = $comissao - $desconto;
        } elseif($boolean_individual && !$boolean_coletivo && $boolean_empresarial) {
            $comissao = floatval($total_individual) + floatval($total_empresarial)  + floatval($odonto);
            $desconto = floatval($total_individual_desconto) + floatval($total_empresarial_desconto);
            $total = $comissao - $desconto;
        } elseif(!$boolean_individual && $boolean_coletivo && !$boolean_empresarial) {
            $comissao = floatval($total_coletivo)  + floatval($odonto);
            $desconto = floatval($total_coletivo_desconto);
            $total = $comissao - $desconto;
        } elseif(!$boolean_individual && $boolean_coletivo && $boolean_empresarial) {
            $comissao = floatval($total_coletivo) + floatval($total_empresarial)  + floatval($odonto);
            $desconto = floatval($total_coletivo_desconto) + floatval($total_empresarial_desconto);
            $total = $comissao - $desconto;
        } elseif($boolean_individual && $boolean_coletivo && !$boolean_empresarial) {
            $comissao = floatval($total_coletivo) + floatval($total_individual)  + floatval($odonto);
            $desconto = floatval($total_individual_desconto) + floatval($total_coletivo_desconto);
            $total = $comissao - $desconto;
        } elseif(!$boolean_individual && !$boolean_coletivo && $boolean_empresarial) {
            $comissao = floatval($total_empresarial)  + floatval($odonto);
            $desconto = floatval($total_empresarial_desconto);
            $total = $comissao - $desconto;
        } else {

        }



        $pdf = PDFFile::loadView('gerente.pdf-folha',[
            "individual" => $individual,
            "ano" => $ano,
            "coletivo" => $coletivo,
            "empresarial" => $empresarial,
            "meses" => $mes_folha,
            "salario" => $salario,
            "premiacao" => $premiacao,
            "comissao" => $comissao,
            "total" => $total,
            "logo" => $logo,
            "primeiro_dia" => $primeiroDia,
            "ultimo_dia" => $ultimoDia,
            "user" => $user,
            "desconto" => $desconto,
            "estorno" => $estorno,
            "estorno_table" => $estorno_table,
            "tipo" => $request->tipo,
            "boolean_individual" => $boolean_individual,
            "boolean_coletivo" => $boolean_coletivo,
            "boolean_empresarial" => $boolean_empresarial,
            "odonto" => $odonto,
            "odonto_list" => $odonto_list
        ]);


        $nome = Str::slug($user,"_");
        $mes_folha_nome = Str::slug($mes_folha);


        $nome_pdf = "folha_" . mb_convert_case($nome, MB_CASE_LOWER, "UTF-8") . "_" . $mes_folha_nome . "_" . date('d') . "_" . date('m') . "_" . date('s') . ".pdf";
        $response = $pdf->stream($nome_pdf, ['Attachment' => false]);
        $response->headers->set('Content-Disposition', 'inline; filename="' . $nome_pdf . '"');
        return $response;

    }


    public function geralEstornoMes(Request $request)
    {
        $mes = $request->mes;

        $estorno = DB::select(
            "select
            (select nome from administradoras where administradoras.id = comissoes.administradora_id) as administradora,
            case when comissoes.empresarial then
                (select razao_social from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (select nome from clientes where clientes.id = (select cliente_id from contratos where contratos.id = comissoes.contrato_id))
            end as cliente,
            (select SUBSTRING_INDEX(nome,' ',1) from planos where planos.id = comissoes.plano_id) as plano,
            date_format(comissoes_corretores_lancadas.data,'%d/%m/%Y') as data,
            (comissoes_corretores_lancadas.id) as id_lancadas,
            case when comissoes.empresarial then
                (select valor_plano from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (select valor_plano from contratos where contratos.id = comissoes.contrato_id)
            end as valor,
            (comissoes_corretores_lancadas.valor) as total_estorno,
            case when comissoes.empresarial then
                (select codigo_externo from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (select codigo_externo from contratos where contratos.id = comissoes.contrato_id)
            end as contrato,
            (comissoes.id) as id,
            (comissoes_corretores_lancadas.parcela) as parcela
            from comissoes_corretores_lancadas inner join comissoes on comissoes.id = comissoes_corretores_lancadas.comissoes_id
            where comissoes_corretores_lancadas.estorno = 1 and month(comissoes_corretores_lancadas.data_baixa_estorno) = {$mes}"

        );

        return response()->json($estorno);




    }






    public function geralEstorno(Request $request)
    {
        $id = $request->id;
        $mes = $request->mes;

        $estorno = DB::connection('tenant')->select(
            "select
            (select nome from grupoamerica.administradoras where administradoras.id = comissoes.administradora_id) as administradora,
            case when comissoes.empresarial then
                (select razao_social from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (select nome from clientes where clientes.id = (select cliente_id from contratos where contratos.id = comissoes.contrato_id))
            end as cliente,
            (select SUBSTRING_INDEX(nome,' ',1) from grupoamerica.planos where planos.id = comissoes.plano_id) as plano,
            date_format(comissoes_corretores_lancadas.data,'%d/%m/%Y') as data,
            (comissoes_corretores_lancadas.id) as id_lancadas,
            case when comissoes.empresarial then
                (select valor_plano from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (select valor_plano from contratos where contratos.id = comissoes.contrato_id)
            end as valor,
            (comissoes_corretores_lancadas.valor) as total_estorno,
            case when comissoes.empresarial then
                (select codigo_externo from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (select codigo_externo from contratos where contratos.id = comissoes.contrato_id)
            end as contrato,
            (comissoes.id) as id,
            (comissoes_corretores_lancadas.parcela) as parcela
            from comissoes_corretores_lancadas inner join comissoes on comissoes.id = comissoes_corretores_lancadas.comissoes_id
            where comissoes_corretores_lancadas.estorno = 1 and comissoes.user_id = {$id}"

        );

        return response()->json($estorno);

    }

    public function estornoVoltar(Request $request)
    {
        $corretora_id = User::find($request->user_id)->corretora_id;
        $valor_estorno = 0;
        $valor_total = 0;
        $user_id = $request->user_id;
        $mes = $request->mes;
        $ano = $request->ano;
        $id = $request->id;

        $valor = str_replace([".",","],["","."],$request->valor);
        $va = ValoresCorretoresLancados::where("user_id",$user_id)->whereMonth("data",$mes)->whereYear("data",$ano)->first();
        $valor_estorno = $va->valor_estorno - $valor;
        $valor_total = $va->valor_total + $valor;
        $va->valor_estorno = $valor_estorno;
        $va->valor_total = $valor_total;
        if(!$va->save()) return "error";

        $co = ComissoesCorretoresLancadas::where("id",$id)->first();
        $co->data_baixa_estorno = null;
        $co->estorno = 0;
        if(!$co->save()) return "error";

        return $this->infoCorretorUp($request->user_id,$corretora_id,$request->mes,$ano);

    }





    public function estornoVoltarOld(Request $request)
    {
        $valor_estorno = 0;
        $valor_total = 0;
        $user_id = $request->user_id;
        $mes = $request->mes;
        $ano = $request->ano;
        $id = $request->id;

        $valor = str_replace([".",","],["","."],$request->valor);
        $va = ValoresCorretoresLancados::where("user_id",$user_id)->whereMonth("data",$mes)->whereYear("data",$ano)->first();
        $valor_estorno = $va->valor_estorno - $valor;
        $valor_total = $va->valor_total + $valor;
        $va->valor_estorno = $valor_estorno;
        $va->valor_total = $valor_total;
        if(!$va->save()) return "error";

        $co = ComissoesCorretoresLancadas::where("id",$id)->first();
        $comissao_id = $co->comissoes_id;
        $plano = Comissoes::find($comissao_id)->plano_id;
        $co->data_baixa_estorno = null;
        $co->estorno = 0;
        if(!$co->save()) return "error";



        return [
            "valor_estorno" => number_format($valor_estorno,2,",","."),
            "valor_total" => number_format($valor_total,2,",","."),
            "plano" => $plano
        ];
    }






    public function listagemRecebido()
    {
//        $dados = DB::select(
//            "
//            SELECT
//                (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
//                (SELECT NAME FROM users WHERE users.id = comissoes.user_id) AS corretor,
//                (SELECT nome FROM planos WHERE planos.id = comissoes.plano_id) AS plano,
//                case when empresarial then
//                    (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
//                    else
//                    (SELECT nome FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
//                END AS cliente,
//                    (SELECT nome FROM tabela_origens WHERE tabela_origens.id = comissoes.tabela_origens_id) AS tabela_origens,
//                case when empresarial then
//                    (SELECT codigo_externo FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
//                else
//                (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id) END AS codigo_externo,
//                parcela,
//                valor,
//                data_baixa,
//                comissoes_corretora_lancadas.data as vencimento,
//                comissoes.id
//                FROM comissoes_corretora_lancadas
//                INNER JOIN comissoes ON comissoes.id = comissoes_corretora_lancadas.comissoes_id
//                WHERE status_financeiro = 1 AND status_gerente = 1
//            "
//        );
        $dados = null;
        return $dados;
    }

    public function detalhePagos($id)
    {
        $comissao = Comissoes::find($id);
        $dados = DB::select("
            SELECT
            comissoes_corretores_lancadas.parcela,
            comissoes_corretores_lancadas.id AS id_corretor_comissao,
            comissoes_corretora_lancadas.id AS id_corretora,
            (SELECT NAME FROM users WHERE users.id = comissoes.user_id) AS nome_corretor,
            (SELECT id FROM users WHERE users.id = comissoes.user_id) AS id_corretor,
            if(comissoes_corretora_lancadas.valor_pago,comissoes_corretora_lancadas.valor_pago,0) AS valor_pago,
            if(comissoes_corretora_lancadas.porcentagem_paga,comissoes_corretora_lancadas.porcentagem_paga,0) AS porcentagem_paga,
            case when empresarial then
                (SELECT codigo_externo FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id)
           END AS codigo_externo,
            comissoes_corretores_lancadas.data AS vencimento,
            case when empresarial then
                (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
            END AS valor_plano_contratado,
            comissoes_corretores_lancadas.data_baixa AS data_baixa,
                (SELECT valor FROM comissoes_corretora_configuracoes  WHERE  plano_id = comissoes.plano_id AND  administradora_id = comissoes.administradora_id AND
                tabela_origens_id = comissoes.tabela_origens_id AND parcela = comissoes_corretora_lancadas.parcela) AS porcentagem_parcela_corretora,
                (SELECT id FROM comissoes_corretora_configuracoes WHERE  plano_id = comissoes.plano_id AND administradora_id = comissoes.administradora_id AND
                tabela_origens_id = comissoes.tabela_origens_id AND parcela = comissoes_corretora_lancadas.parcela) AS porcentagem_parcela_corretora_id,
                comissoes_corretora_lancadas.valor AS comissao_valor_corretora,
                if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,0) as comissao_valor_pago_corretor,
                if(comissoes_corretores_lancadas.porcentagem_paga,comissoes_corretores_lancadas.porcentagem_paga,0) as comissao_porcentagem_pago_corretor,
                comissoes_corretores_lancadas.valor AS comissao_valor_corretor,
                if(
                        (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                            (SELECT valor FROM comissoes_corretores_configuracoes WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
                            ,
                            (SELECT valor FROM comissoes_corretores_default WHERE
                            comissoes_corretores_default.plano_id = comissoes.plano_id AND
                            comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
                        )
                    AS porcentagem_parcela_corretores,
                    if(
                            (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                                (SELECT id FROM comissoes_corretores_configuracoes WHERE
                                comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                                comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                                comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                                comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                                comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
                                ,
                                (SELECT id FROM comissoes_corretores_default WHERE
                                comissoes_corretores_default.plano_id = comissoes.plano_id AND
                                comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                                comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                                comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
                            )
                            AS porcentagem_parcela_corretor_id,
                            case when empresarial then
                                (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                            else
                                (SELECT nome FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
                            END AS cliente,
                            case when empresarial then
                                (SELECT cnpj FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                            else
                                (SELECT cpf FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
                            END AS cliente_cpf
                            FROM comissoes_corretores_lancadas
                            INNER JOIN comissoes_corretora_lancadas ON comissoes_corretora_lancadas.parcela = comissoes_corretores_lancadas.parcela
                            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                            WHERE comissoes_corretores_lancadas.comissoes_id = $id AND comissoes_corretora_lancadas.comissoes_id = $id AND comissoes_corretores_lancadas.status_financeiro = 1 AND
                            comissoes_corretores_lancadas.status_gerente = 1
                            AND
                                (comissoes_corretores_lancadas.valor != 0 OR comissoes_corretora_lancadas.valor != 0)
                            GROUP BY comissoes_corretores_lancadas.parcela
                    ");

        $desconto_corretora = 0;
        $desconto_corretor = 0;
        $comissao = Comissoes::find($id);
        if($comissao->empresarial == 1) {
            $id = $comissao->contrato_empresarial_id;
            $desconto_corretora = ContratoEmpresarial::find($id)->desconto_corretora;
            $desconto_corretor = ContratoEmpresarial::find($id)->desconto_corretor;
        } else {
            $id = $comissao->contrato_id;
            $desconto_corretora = Contrato::find($id)->desconto_corretora;
            $desconto_corretor = Contrato::find($id)->desconto_corretor;
        }

        return view('admin.pages.gerente.detalhe-pagos',[
            'dados' => $dados,
            "cliente" => isset($dados[0]->cliente) && !empty($dados[0]->cliente) ? $dados[0]->cliente : "",
            "cpf" => isset($dados[0]->cliente_cpf) && !empty($dados[0]->cliente_cpf) ? $dados[0]->cliente_cpf : "",
            "valor_plano" => isset($dados[0]->valor_plano_contratado) && !empty($dados[0]->valor_plano_contratado) ? $dados[0]->valor_plano_contratado : "",
            "valor_corretora" => isset($dados[0]->comissao_valor_corretora) && !empty($dados[0]->comissao_valor_corretora) ? $dados[0]->comissao_valor_corretora : "",
            "desconto_corretora" => $desconto_corretora,
            "desconto_corretor" => $desconto_corretor
        ]);
    }



    public function detalhe($id)
    {
        $dados = DB::select("
       SELECT
       comissoes_corretores_lancadas.parcela,
       comissoes_corretores_lancadas.id AS id_corretor_comissao,
       comissoes_corretora_lancadas.id AS id_corretora,
       (SELECT NAME FROM users WHERE users.id = comissoes.user_id) AS nome_corretor,
       (SELECT id FROM users WHERE users.id = comissoes.user_id) AS id_corretor,
       if(comissoes_corretora_lancadas.valor_pago,comissoes_corretora_lancadas.valor_pago,0) AS valor_pago,
       if(comissoes_corretora_lancadas.porcentagem_paga,comissoes_corretora_lancadas.porcentagem_paga,0) AS porcentagem_paga,
       case when empresarial then
           (SELECT codigo_externo FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
       else
           (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id)
           END AS codigo_externo,
    comissoes_corretores_lancadas.data AS vencimento,
    case when empresarial then
(SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
  else
  (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
END AS valor_plano_contratado,
comissoes_corretores_lancadas.data_baixa AS data_baixa,
    (SELECT valor FROM comissoes_corretora_configuracoes  WHERE  plano_id = comissoes.plano_id AND  administradora_id = comissoes.administradora_id AND
     tabela_origens_id = comissoes.tabela_origens_id AND parcela = comissoes_corretora_lancadas.parcela) AS porcentagem_parcela_corretora,

    (SELECT id FROM comissoes_corretora_configuracoes WHERE  plano_id = comissoes.plano_id AND administradora_id = comissoes.administradora_id AND
    tabela_origens_id = comissoes.tabela_origens_id AND parcela = comissoes_corretora_lancadas.parcela) AS porcentagem_parcela_corretora_id,

    comissoes_corretora_lancadas.valor AS comissao_valor_corretora,

    if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,0) as comissao_valor_pago_corretor,
    if(comissoes_corretores_lancadas.porcentagem_paga,comissoes_corretores_lancadas.porcentagem_paga,0) as comissao_porcentagem_pago_corretor,

        comissoes_corretores_lancadas.valor AS comissao_valor_corretor,

             if(
                    (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                    comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                    comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                    comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                    comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                    comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                        (SELECT valor FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
                        ,
                        (SELECT valor FROM comissoes_corretores_default WHERE
                        comissoes_corretores_default.plano_id = comissoes.plano_id AND
                        comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
                    )
                AS porcentagem_parcela_corretores,


              if(
                    (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                    comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                    comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                    comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                    comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                    comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                        (SELECT id FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
                        ,
                        (SELECT id FROM comissoes_corretores_default WHERE
                        comissoes_corretores_default.plano_id = comissoes.plano_id AND
                        comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
                    )
                    AS porcentagem_parcela_corretor_id,
       case when empresarial then
     (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
  else
     (SELECT nome FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
  END AS cliente,
  case when empresarial then
     (SELECT cnpj FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
  else
     (SELECT cpf FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
  END AS cliente_cpf
  FROM comissoes_corretores_lancadas
  INNER JOIN comissoes_corretora_lancadas ON comissoes_corretora_lancadas.parcela = comissoes_corretores_lancadas.parcela
  INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
  WHERE comissoes_corretores_lancadas.comissoes_id = $id AND comissoes_corretora_lancadas.comissoes_id = $id AND comissoes_corretores_lancadas.status_financeiro = 1 AND
  comissoes_corretores_lancadas.status_gerente = 0
  AND
    (comissoes_corretores_lancadas.valor != 0 OR comissoes_corretora_lancadas.valor != 0)
  GROUP BY comissoes_corretores_lancadas.parcela
       ");

        $desconto_corretora = 0;
        $desconto_corretor = 0;
        $comissao = Comissoes::find($id);

        if($comissao->empresarial == 1) {

            $id = $comissao->contrato_empresarial_id;
            $desconto_corretora = ContratoEmpresarial::find($id)->desconto_corretora;
            $desconto_corretor = ContratoEmpresarial::find($id)->desconto_corretor;

        } else {

            $id = $comissao->contrato_id;
            $desconto_corretora = Contrato::find($id)->desconto_corretora;
            $desconto_corretor = Contrato::find($id)->desconto_corretor;

        }

        return view('admin.pages.gerente.detalhe',[
            "dados" => $dados,
            "cliente" => isset($dados[0]->cliente) && !empty($dados[0]->cliente) ? $dados[0]->cliente : "",
            "cpf" => isset($dados[0]->cliente_cpf) && !empty($dados[0]->cliente_cpf) ? $dados[0]->cliente_cpf : "",
            "valor_plano" => isset($dados[0]->valor_plano_contratado) && !empty($dados[0]->valor_plano_contratado) ? $dados[0]->valor_plano_contratado : "",
            "valor_corretora" => isset($dados[0]->comissao_valor_corretora) && !empty($dados[0]->comissao_valor_corretora) ? $dados[0]->comissao_valor_corretora : "",
            "desconto_corretora" => $desconto_corretora,
            "desconto_corretor" => $desconto_corretor


        ]);





    }

    public function mudarComissaoCorretora(Request $request)
    {








    }


    public function mudarComissaoCorretor(Request $request)
    {

        if($request->acao == "porcentagem") {

            $valor_plano = floatval($request->valor_plano);
            $porcentagem = floatval($request->valor);
            $resultado = ($valor_plano * $porcentagem) / 100;

            $id = $request->id;

            $alt = ComissoesCorretoresLancadas::where("id",$request->default_corretor)->first();
            if($porcentagem == 0) {
                $contrato = Contrato::find(Comissoes::find($alt->comissoes_id)->contrato_id);
                $contrato->desconto_corretor = 0;
                $contrato->save();
            }



            $alt->valor = $resultado;
            $alt->porcentagem_paga = $request->valor;

            if($alt->save()) {

                return [
                    "valor" => number_format($resultado,2,",","."),
                    "porcentagem" => $request->valor
                ];

            } else {
                return "error";
            }



        } else {
            // $id = $request->id;
            // $valor = str_replace([".",","],["","."],$request->valor);

            // $valor_plano = $request->valor_plano;
            // $porcentagem = floor(($valor / $valor_plano) * 100);
            // $alt = ComissoesCorretoresLancadas::where("id",$id)->first();
            // $alt->valor = $valor;

            // $alt->porcentagem_paga = $porcentagem;
            // if($alt->save()) {
            //     return $porcentagem;
            // } else {
            //     return "error";
            // }

        }







    }

    public function mudarComissaoCorretorGerente(Request $request)
    {

        $id = $request->id;
        $valor = str_replace([".",","],["","."],$request->valor);
        $valor_plano =  str_replace(["R$ ",".",","],["","","."],$request->valor_plano);
        $porcentagem = round(($valor / $valor_plano) * 100,2);
        $alt = ComissoesCorretoresLancadas::where("id",$id)->first();

        $contrato = Contrato::find(Comissoes::find($alt->comissoes_id)->contrato_id);
        if($valor == 0) {
            $contrato->desconto_corretor = 0;
            $contrato->save();
        }

        $alt->valor = $valor;
        $alt->porcentagem_paga = $porcentagem;
        $alt->save();
        return [
            "valor" => number_format($valor,2,",","."),
            "porcentagem" => $porcentagem
        ];

    }

    public function administradoraPagouComissaoPagos(Request $request)
    {
        $corretor = $request->corretor;
        $corretora = $request->corretora;

        $alt_corretor = ComissoesCorretoresLancadas::where("id",$corretor)->first();
        $alt_corretor->status_gerente = 0;
        $alt_corretor->data_baixa_gerente = null;
        $alt_corretor->save();


//        $alt_corretora = ComissoesCorretoraLancadas::where("id",$corretora)->first();
//        $alt_corretora->status_gerente = 0;
//        $alt_corretora->data_baixa_gerente = null;
//        $alt_corretora->save();

        return "sucesso";
    }




    public function administradoraPagouComissao(Request $request)
    {
        $corretor = $request->corretor;
        $corretora = $request->corretora;

        $alt_corretor = ComissoesCorretoresLancadas::where("id",$corretor)->first();
        $alt_corretor->status_gerente = 1;
        $alt_corretor->data_baixa_gerente = date('Y-m-d');
        $alt_corretor->save();


//        $alt_corretora = ComissoesCorretoraLancadas::where("id",$corretora)->first();
//        $alt_corretora->status_gerente = 1;
//        $alt_corretora->data_baixa_gerente = date('Y-m-d');
//        $alt_corretora->save();

        return "sucesso";
    }





    public function mudarStatus(Request $request)
    {
        $id = $request->id;
        if($request->corretora) {
//            $comissao = ComissoesCorretoraLancadas::where("id",$id)->first();
//            $comissao->status_gerente = 1;
//            if($comissao->save()) {
//                return "sucesso";
//            } else {
//                return "error";
//            }
        } else {
            $comissao = ComissoesCorretoresLancadas::where("id",$id)->first();
            $comissao->status_gerente = 1;
            if($comissao->save()) {
                return "sucesso";
            } else {
                return "error";
            }
        }
        //$comissao =





    }
    /*
    public function listarUserComissoesAll()
    {
        $users = DB::select(
            "SELECT id,name,
            (SELECT if(SUM(valor)>0,SUM(valor),0) FROM comissoes_corretores_lancadas WHERE status_financeiro = 1 AND status_gerente = 1
             AND comissoes_id
            IN(SELECT id FROM comissoes WHERE user_id = users.id AND comissoes.administradora_id = 1)) AS valor_allcare,

            (SELECT if(SUM(valor)>0,SUM(valor),0) FROM comissoes_corretores_lancadas WHERE status_financeiro = 1 AND status_gerente = 1
             AND comissoes_id
            IN(SELECT id FROM comissoes WHERE user_id = users.id AND comissoes.administradora_id = 2)) AS valor_alter,

												(SELECT if(SUM(valor)>0,SUM(valor),0) FROM comissoes_corretores_lancadas WHERE status_financeiro = 1 AND status_gerente = 1
             AND comissoes_id
            IN(SELECT id FROM comissoes WHERE user_id = users.id AND comissoes.administradora_id = 3)) AS valor_qualicorp,

            (SELECT if(SUM(valor)>0,SUM(valor),0) FROM comissoes_corretores_lancadas WHERE status_financeiro = 1 AND status_gerente = 1
             AND comissoes_id
            IN(SELECT id FROM comissoes WHERE user_id = users.id AND comissoes.administradora_id = 4)) AS valor_hapvida,

            (SELECT if(SUM(valor)>0,SUM(valor),0) FROM comissoes_corretores_lancadas WHERE status_financeiro = 1 AND status_gerente = 1
             AND comissoes_id
            IN(SELECT id FROM comissoes WHERE user_id = users.id)) AS valor,

            (SELECT COUNT(*) FROM comissoes_corretores_lancadas WHERE status_financeiro = 1 AND status_gerente = 1 AND status_comissao = 1
		    						 AND comissoes_id
            IN(SELECT id FROM comissoes WHERE user_id = users.id)) AS status

            FROM users WHERE cargo_id IS NOT NULL"
        );

        return $users;
    }

    */

}
