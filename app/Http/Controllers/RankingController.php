<?php

namespace App\Http\Controllers;

use App\Events\RankingUpdated;
use App\Models\RankingDiario;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Concessionaria;
use App\Models\ContratoEmpresarial;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    public function rankingVerificarCorretor(Request $request)
    {
        $user = $request->user_id;
        $dados = RankingDiario::whereDate("data","=",Carbon::today()->toDateString())->where("user_id",$user)->first();


        return [
          "individual" => $dados->vidas_individual,
          "coletivo" => $dados->vidas_coletivo,
          "empresarial" => $dados->vidas_empresarial

        ];


    }



    public function atualizarRankingDiario(Request $request)
    {
        //return $request->all();
        $venda = RankingDiario::where("user_id",$request->user_id)->whereRaw("date(NOW()) = data")->first();

        $venda->vidas_individual = $request->vidas_individual;
        $venda->vidas_coletivo = $request->vidas_coletivo;
        $venda->vidas_empresarial = $request->vidas_empresarial;
        $venda->save();

        $ranking = RankingDiario
            ::select('ranking_diario.id','user_id','nome','vidas_individual','vidas_coletivo','vidas_empresarial','users.image')
            ->whereRaw("data = DATE(NOW())")
            ->join("users","users.id","=","ranking_diario.user_id")
            ->orderByRaw('vidas_individual + vidas_coletivo + vidas_empresarial DESC') // Ordena pela soma das vidas
            ->get();

        $user = \DB::table("ranking_diario")
            ->join("users", "users.id", "=", "ranking_diario.user_id")
            ->select("users.image", "users.name", \DB::raw("vidas_individual + vidas_coletivo + vidas_empresarial as total"))
            ->where("ranking_diario.user_id", $venda->user_id)
            ->whereRaw("DATE(ranking_diario.data) = CURDATE()")
            ->first();

        broadcast(new RankingUpdated($ranking, $user));

        return response()->json([
            'ranking' => $ranking,
            'venda' => $user
        ]);

    }

    public function editarHistorico(Request $request)
    {
        $alt = RankingDiario::find($request->id);
        $data = $alt->data;
        $alt->vidas_individual = $request->individual;
        $alt->vidas_coletivo = $request->coletivo;
        $alt->vidas_empresarial = $request->empresarial;
        $alt->save();
        return response()->json(['success' => true, 'message' => 'Histórico atualizado com sucesso.']);
    }




    public function historico(Request $request)
    {

        $dataAtual = $request->input('data');
        $retroceder = $request->input('retroceder');
        $avancar = $request->input('avancar');


        if (!$dataAtual) {
            // Pega a data mais recente no banco
            $dataAtual = DB::table('ranking_diario')->max('data');
        } elseif ($retroceder == 'true') {
            // Retrocede para a data anterior
            $dataAtual = DB::table('ranking_diario')
                ->where('data', '<', $dataAtual)
                ->max('data');
        } elseif ($avancar == 'true') {

            // Avança para a data seguinte
            $dataAtual = DB::table('ranking_diario')
                ->where('data', '>', $dataAtual)
                ->min('data');
        }

        if (!$dataAtual) {
            return response()->json(['dados' => [], 'data_atual' => null]);
        }

        $dados = DB::table('ranking_diario')
            ->join('corretoras', 'ranking_diario.corretora_id', '=', 'corretoras.id')

            ->select('ranking_diario.*', 'corretoras.nome as nome_corretora')
            ->where('ranking_diario.data', '=', $dataAtual)
            ->orderBy('ranking_diario.data', 'desc')
            ->get();

        return response()->json(['dados' => $dados, 'data_atual' => $dataAtual]);
    }

    public function filtragem(Request $request)
    {
        $concessionarias = Concessionaria::all();
        $corretora = $request->input('corretora');
        $corretora_id = null;
        $meta = 0;

        if($corretora == "concessi") {
            $corretora_id = null;
        } else if($corretora == "grupoamerica") {
            $corretora_id = null;
        } else if($corretora == "america") {
            $corretora_id = 2;
        } else if($corretora == "vivaz") {
            $corretora_id = 1;
        }
        $corretoraFilter = $corretora_id ? " AND comissoes.corretora_id = {$corretora_id}" : "";

        if($corretora == "concessi") {
            $ranking = DB::select("
                SELECT id,nome,imagem,
                meta_individual + meta_super_simples + meta_pme + meta_adesao AS meta_total,
                individual + super_simples + pme + adesao AS total_vidas,
                individual as individual,
                super_simples as super_simples,
                pme as pme,
                adesao as adesao,
                ROUND(((individual + super_simples + pme + adesao) /
                (meta_individual + meta_super_simples + meta_pme + meta_adesao)) * 100, 2) AS porcentagem_vendas
                FROM concessionarias
                where id != 17
                ORDER BY total_vidas DESC;
            ");
            $podium = view('ranking.podium-concessionarias',[
                'ranking' => $ranking
            ])->render();
            $ranking = view('ranking.ranking-concessionarias',[
                'ranking' => $ranking,
                'corretora' => $corretora
            ])->render();

            $totals = DB::select("
                SELECT
                    3050 AS meta_total,
                    SUM(individual) AS total_individual,
                    SUM(super_simples) AS total_super_simples,
                    SUM(pme) AS total_pme,
                    SUM(adesao) AS total_adesao,
                    SUM(individual) + SUM(super_simples) + SUM(pme) + SUM(adesao) AS total_vidas,
                    (SUM(individual) + SUM(super_simples) + SUM(pme) + SUM(adesao)) / NULLIF(3050, 0) * 100 AS porcentagem_geral
                FROM
                    concessionarias;
            ");

            return [
                'meta' => 0,
                'podium' => $podium,
                'ranking' => $ranking,
                'totals' => $totals,
                '$corretora' => $corretora,
                'concessionarias' => $concessionarias
            ];
        } else if($corretora == "grupo") {
            $ranking = DB::connection('tenant')->select(
                "
                      SELECT
                        users.name as corretor,
                        users.image as imagem,
                        -- Verifica se o corretor é Parceiro ou CLT
                        CASE
                            WHEN users.clt = 1 THEN 'CLT'
                            ELSE 'Parceiro'
                        END as tipo_contratacao,
                        -- Quantidade individual
                        SUM(CASE
                            WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0
                            THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                            FROM clientes
                               INNER JOIN contratos ON contratos.cliente_id = clientes.id
                            WHERE contratos.id = comissoes.contrato_id
                                AND contratos.plano_id = 1
                                AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                        ELSE 0
                        END) as quantidade_individual,
                        -- Quantidade coletivo
                        SUM(CASE
                            WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0
                            THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                            FROM clientes
                               INNER JOIN contratos ON contratos.cliente_id = clientes.id
                            WHERE contratos.id = comissoes.contrato_id
                                AND contratos.plano_id = 3
                                AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                            ELSE 0
                        END) as quantidade_coletivo,
                        -- Quantidade empresarial
                        SUM(CASE
                            WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1
                            THEN (SELECT IFNULL(SUM(contrato_empresarial.quantidade_vidas), 0)
                            FROM contrato_empresarial
                            WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id
                                AND contrato_empresarial.plano_id = 5
                                AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                            ELSE 0
                        END) as quantidade_empresarial,
                        -- Valor total
                        SUM(CASE
                            WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0
                            THEN contratos.valor_plano
                            WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0
                                THEN contratos.valor_plano
                            WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1
                                THEN contrato_empresarial.valor_plano
                            ELSE 0
                        END) as valor_total,
                        -- Quantidade total de vidas (soma das 3 colunas anteriores)
                        SUM(CASE
                            WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0
                            THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                            FROM clientes
                               INNER JOIN contratos ON contratos.cliente_id = clientes.id
                            WHERE contratos.id = comissoes.contrato_id
                                AND contratos.plano_id = 1
                                AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                            WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0
                                THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                                FROM clientes
                                INNER JOIN contratos ON contratos.cliente_id = clientes.id
                            WHERE contratos.id = comissoes.contrato_id
                                AND contratos.plano_id = 3
                                AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                            WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1
                                THEN (SELECT IFNULL(SUM(contrato_empresarial.quantidade_vidas), 0)
                                FROM contrato_empresarial
                            WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id
                                AND contrato_empresarial.plano_id = 5
                                AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                            ELSE 0
                        END) as quantidade_vidas,
                        corretoras.nome as corretora
                    FROM comissoes
                        INNER JOIN users ON users.id = comissoes.user_id
                        INNER JOIN corretoras ON users.corretora_id = corretoras.id
                        LEFT JOIN contratos ON contratos.id = comissoes.contrato_id
                        LEFT JOIN contrato_empresarial ON contrato_empresarial.id = comissoes.contrato_empresarial_id
                        LEFT JOIN clientes ON clientes.id = contratos.cliente_id
                        -- Junção com a tabela metas para pegar as metas do corretor
                        -- Filtro para corretora específica
                        WHERE comissoes.plano_id IN (1, 3, 5) AND (comissoes.user_id != 198 AND comissoes.user_id != 269)
                    AND (
                        (users.ativo = 1)
                        OR (users.ativo = 2 AND (
                            (comissoes.plano_id IN (1, 3) AND clientes.quantidade_vidas >= 1
                            AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                            AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                        ) OR (
                            comissoes.plano_id = 5 AND contrato_empresarial.quantidade_vidas >= 1
                            AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                            AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                        )
                    )
                    GROUP BY comissoes.user_id
                    ORDER BY quantidade_vidas DESC;
                "
            );
            $podium = view('ranking.podium',[
                'ranking' => $ranking,
                'corretora' => $corretora,
                'corretora_id' => $corretora_id
            ])->render();
            $ranking = view('ranking.ranking',[
                'ranking' => $ranking,
                'corretora' => $corretora
            ])->render();
            $totals = DB::connection('tenant')->select("SELECT
                SUM(
                    CASE WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0 THEN
                        (
                        SELECT IFNULL(SUM(clientes.quantidade_vidas), 0) FROM clientes
                        INNER JOIN contratos ON contratos.cliente_id = clientes.id WHERE contratos.id = comissoes.contrato_id AND contratos.plano_id = 1
                        AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                        AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE())
                        ) ELSE 0 END)
                        as total_individual,
                        SUM(CASE WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0 THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                        FROM clientes INNER JOIN contratos ON contratos.cliente_id = clientes.id WHERE contratos.id = comissoes.contrato_id AND contratos.plano_id = 3
                        AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                        AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE())

                        ) ELSE 0 END) as total_coletivo,
                SUM( CASE WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1 THEN(SELECT IFNULL(SUM(contrato_empresarial.quantidade_vidas), 0)
                                 FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id AND contrato_empresarial.plano_id = 5
                                 AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                                 AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                                 ELSE 0 END) as total_empresarial
                FROM comissoes
                WHERE comissoes.plano_id IN (1, 3, 5)
            ");

            return [
                'meta' => 944,
                'podium' => $podium,
                'ranking' => $ranking,
                'totals' => $totals,
                'corretora' => $corretora,
                'concessionarias' => $concessionarias
            ];












        } else if($corretora == "america") {

            $ranking = DB::connection('tenant')->select(
                "
                      SELECT
                        users.name as corretor,
                        users.image as imagem,
                        -- Verifica se o corretor é Parceiro ou CLT
                        CASE
                            WHEN users.clt = 1 THEN 'CLT'
                            ELSE 'Parceiro'
                        END as tipo_contratacao,
                        -- Quantidade individual
                        SUM(CASE
                            WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0
                            THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                            FROM clientes
                               INNER JOIN contratos ON contratos.cliente_id = clientes.id
                            WHERE contratos.id = comissoes.contrato_id
                                AND contratos.plano_id = 1
                                AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                        ELSE 0
                        END) as quantidade_individual,
                        -- Quantidade coletivo
                        SUM(CASE
                            WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0
                            THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                            FROM clientes
                               INNER JOIN contratos ON contratos.cliente_id = clientes.id
                            WHERE contratos.id = comissoes.contrato_id
                                AND contratos.plano_id = 3
                                AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                            ELSE 0
                        END) as quantidade_coletivo,
                        -- Quantidade empresarial
                        SUM(CASE
                            WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1
                            THEN (SELECT IFNULL(SUM(contrato_empresarial.quantidade_vidas), 0)
                            FROM contrato_empresarial
                            WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id
                                AND contrato_empresarial.plano_id = 5
                                AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                            ELSE 0
                        END) as quantidade_empresarial,
                        -- Valor total
                        SUM(CASE
                            WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0
                            THEN contratos.valor_plano
                            WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0
                                THEN contratos.valor_plano
                            WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1
                                THEN contrato_empresarial.valor_plano
                            ELSE 0
                        END) as valor_total,
                        -- Quantidade total de vidas (soma das 3 colunas anteriores)
                        SUM(CASE
                            WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0
                            THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                            FROM clientes
                               INNER JOIN contratos ON contratos.cliente_id = clientes.id
                            WHERE contratos.id = comissoes.contrato_id
                                AND contratos.plano_id = 1
                                AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                            WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0
                                THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                                FROM clientes
                                INNER JOIN contratos ON contratos.cliente_id = clientes.id
                            WHERE contratos.id = comissoes.contrato_id
                                AND contratos.plano_id = 3
                                AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                            WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1
                                THEN (SELECT IFNULL(SUM(contrato_empresarial.quantidade_vidas), 0)
                                FROM contrato_empresarial
                            WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id
                                AND contrato_empresarial.plano_id = 5
                                AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                            ELSE 0
                        END) as quantidade_vidas,
                        corretoras.nome as corretora
                    FROM comissoes
                        INNER JOIN users ON users.id = comissoes.user_id
                        INNER JOIN corretoras ON users.corretora_id = corretoras.id
                        LEFT JOIN contratos ON contratos.id = comissoes.contrato_id
                        LEFT JOIN contrato_empresarial ON contrato_empresarial.id = comissoes.contrato_empresarial_id
                        LEFT JOIN clientes ON clientes.id = contratos.cliente_id
                        -- Junção com a tabela metas para pegar as metas do corretor
                        -- Filtro para corretora específica
                        WHERE comissoes.plano_id IN (1, 3, 5) AND (comissoes.user_id != 198 AND comissoes.user_id != 269)
                    AND (
                        (users.ativo = 1)
                        OR (users.ativo = 2 AND (
                            (comissoes.plano_id IN (1, 3) AND clientes.quantidade_vidas >= 1
                            AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                            AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                        ) OR (
                            comissoes.plano_id = 5 AND contrato_empresarial.quantidade_vidas >= 1
                            AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                            AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                        )
                    )
                        {$corretoraFilter}
                    GROUP BY comissoes.user_id
                    ORDER BY quantidade_vidas DESC;
                "
            );
            $podium = view('ranking.podium',[
                'ranking' => $ranking,
                'corretora' => $corretora,
                'corretora_id' => $corretora_id
            ])->render();
            $ranking = view('ranking.ranking',[
                'ranking' => $ranking,
                'corretora' => $corretora
            ])->render();
            $totals = DB::connection('tenant')->select("SELECT
                SUM(
                    CASE WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0 THEN
                        (
                        SELECT IFNULL(SUM(clientes.quantidade_vidas), 0) FROM clientes
                        INNER JOIN contratos ON contratos.cliente_id = clientes.id WHERE contratos.id = comissoes.contrato_id AND contratos.plano_id = 1
                        AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                        AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE())
                        ) ELSE 0 END)
                        as total_individual,
                        SUM(CASE WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0 THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                        FROM clientes INNER JOIN contratos ON contratos.cliente_id = clientes.id WHERE contratos.id = comissoes.contrato_id AND contratos.plano_id = 3
                        AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                        AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE())

                        ) ELSE 0 END) as total_coletivo,
                SUM( CASE WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1 THEN(SELECT IFNULL(SUM(contrato_empresarial.quantidade_vidas), 0)
                                 FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id AND contrato_empresarial.plano_id = 5
                                 AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                                 AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                                 ELSE 0 END) as total_empresarial
                FROM comissoes
                WHERE comissoes.plano_id IN (1, 3, 5)" . ($corretora_id ? " AND comissoes.corretora_id = {$corretora_id}" : "") . "
            ");

            return [
                'meta' => 0,
                'podium' => $podium,
                'ranking' => $ranking,
                'totals' => $totals,
                'corretora' => $corretora,
                'concessionarias' => $concessionarias
            ];









        } else if($corretora == "vivaz") {
            $ranking = DB::connection('tenant')->select(
                "
                      SELECT
                        users.name as corretor,
                        users.image as imagem,
                        -- Verifica se o corretor é Parceiro ou CLT
                        CASE
                            WHEN users.clt = 1 THEN 'CLT'
                            ELSE 'Parceiro'
                        END as tipo_contratacao,
                        -- Quantidade individual
                        SUM(CASE
                            WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0
                            THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                            FROM clientes
                               INNER JOIN contratos ON contratos.cliente_id = clientes.id
                            WHERE contratos.id = comissoes.contrato_id
                                AND contratos.plano_id = 1
                                AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                        ELSE 0
                        END) as quantidade_individual,
                        -- Quantidade coletivo
                        SUM(CASE
                            WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0
                            THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                            FROM clientes
                               INNER JOIN contratos ON contratos.cliente_id = clientes.id
                            WHERE contratos.id = comissoes.contrato_id
                                AND contratos.plano_id = 3
                                AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                            ELSE 0
                        END) as quantidade_coletivo,
                        -- Quantidade empresarial
                        SUM(CASE
                            WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1
                            THEN (SELECT IFNULL(SUM(contrato_empresarial.quantidade_vidas), 0)
                            FROM contrato_empresarial
                            WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id
                                AND contrato_empresarial.plano_id = 5
                                AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                            ELSE 0
                        END) as quantidade_empresarial,
                        -- Valor total
                        SUM(CASE
                            WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0
                            THEN contratos.valor_plano
                            WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0
                                THEN contratos.valor_plano
                            WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1
                                THEN contrato_empresarial.valor_plano
                            ELSE 0
                        END) as valor_total,
                        -- Quantidade total de vidas (soma das 3 colunas anteriores)
                        SUM(CASE
                            WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0
                            THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                            FROM clientes
                               INNER JOIN contratos ON contratos.cliente_id = clientes.id
                            WHERE contratos.id = comissoes.contrato_id
                                AND contratos.plano_id = 1
                                AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                            WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0
                                THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                                FROM clientes
                                INNER JOIN contratos ON contratos.cliente_id = clientes.id
                            WHERE contratos.id = comissoes.contrato_id
                                AND contratos.plano_id = 3
                                AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                            WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1
                                THEN (SELECT IFNULL(SUM(contrato_empresarial.quantidade_vidas), 0)
                                FROM contrato_empresarial
                            WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id
                                AND contrato_empresarial.plano_id = 5
                                AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                            ELSE 0
                        END) as quantidade_vidas,
                        corretoras.nome as corretora
                    FROM comissoes
                        INNER JOIN users ON users.id = comissoes.user_id
                        INNER JOIN corretoras ON users.corretora_id = corretoras.id
                        LEFT JOIN contratos ON contratos.id = comissoes.contrato_id
                        LEFT JOIN contrato_empresarial ON contrato_empresarial.id = comissoes.contrato_empresarial_id
                        LEFT JOIN clientes ON clientes.id = contratos.cliente_id
                        -- Junção com a tabela metas para pegar as metas do corretor
                        -- Filtro para corretora específica
                        WHERE comissoes.plano_id IN (1, 3, 5) AND (comissoes.user_id != 198 AND comissoes.user_id != 269)
                    AND (
                        (users.ativo = 1)
                        OR (users.ativo = 2 AND (
                            (comissoes.plano_id IN (1, 3) AND clientes.quantidade_vidas >= 1
                            AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                            AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                        ) OR (
                            comissoes.plano_id = 5 AND contrato_empresarial.quantidade_vidas >= 1
                            AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                            AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                        )
                    )
                        {$corretoraFilter}
                    GROUP BY comissoes.user_id
                    ORDER BY quantidade_vidas DESC;
                "
            );
            $podium = view('ranking.podium',[
                'ranking' => $ranking,
                'corretora' => $corretora,
                'corretora_id' => $corretora_id
            ])->render();
            $ranking = view('ranking.ranking',[
                'ranking' => $ranking,
                'corretora' => $corretora
            ])->render();
            $totals = DB::connection('tenant')->select("SELECT
                SUM(
                    CASE WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0 THEN
                        (
                        SELECT IFNULL(SUM(clientes.quantidade_vidas), 0) FROM clientes
                        INNER JOIN contratos ON contratos.cliente_id = clientes.id WHERE contratos.id = comissoes.contrato_id AND contratos.plano_id = 1
                        AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                        AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE())
                        ) ELSE 0 END)
                        as total_individual,
                        SUM(CASE WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0 THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                        FROM clientes INNER JOIN contratos ON contratos.cliente_id = clientes.id WHERE contratos.id = comissoes.contrato_id AND contratos.plano_id = 3
                        AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                        AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE())

                        ) ELSE 0 END) as total_coletivo,
                SUM( CASE WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1 THEN(SELECT IFNULL(SUM(contrato_empresarial.quantidade_vidas), 0)
                                 FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id AND contrato_empresarial.plano_id = 5
                                 AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                                 AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                                 ELSE 0 END) as total_empresarial
                FROM comissoes
                WHERE comissoes.plano_id IN (1, 3, 5)" . ($corretora_id ? " AND comissoes.corretora_id = {$corretora_id}" : "") . "
            ");

            return [
                'meta' => 0,
                'podium' => $podium,
                'ranking' => $ranking,
                'totals' => $totals,
                'corretora' => $corretora,
                'concessionarias' => $concessionarias
            ];


        }


    }








    public function filtragemOld(Request $request)
    {

        $concessionarias = Concessionaria::all();
        $corretora = $request->input('corretora');

        $corretora_id = null;
        $meta = 0;
        if ($corretora == 'accert') {
            $corretora_id = 1;
            $meta = 200;
        } elseif ($corretora == 'innove') {
            $corretora_id = 2;
            $meta = 220;
        } elseif($corretora == 'vivaz') {
            $corretora_id = null;
            $meta = 472;
        } elseif ($corretora == 'estrela' || $corretora == "concessi" || $corretora == "diario") {
            // Não filtrar, traz todos os dados
            $corretora_id = null;
        }
        $corretoraFilter = $corretora_id ? " AND comissoes.corretora_id = {$corretora_id}" : "";
        if($corretora != "estrela" && $corretora != "concessi" && $corretora != "diario" && $corretora != "semanal" && $corretora != "vivaz") {

            $ranking = DB::select(
                "
                    SELECT
                        users.name as corretor,
                        users.image as imagem,

                    -- Verifica se o corretor é Parceiro ou CLT
                    IF(EXISTS(
                        SELECT 1
                        FROM comissoes_corretores_configuracoes
                        WHERE comissoes_corretores_configuracoes.user_id = users.id
                    ), 'Parceiro', 'CLT') as tipo_contratacao,

                    -- Quantidade individual
                    SUM(CASE
                            WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0
                                THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                                      FROM clientes
                                               INNER JOIN contratos ON contratos.cliente_id = clientes.id
                                      WHERE contratos.id = comissoes.contrato_id
                                        AND contratos.plano_id = 1
                                        AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                        AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                            ELSE 0
                        END) as quantidade_individual,

                    -- Quantidade coletivo
                    SUM(CASE
                            WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0
                                THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                                      FROM clientes
                                               INNER JOIN contratos ON contratos.cliente_id = clientes.id
                                      WHERE contratos.id = comissoes.contrato_id
                                        AND contratos.plano_id = 3
                                        AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                        AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                            ELSE 0
                        END) as quantidade_coletivo,

                -- Quantidade empresarial
                SUM(CASE
                        WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1
                            THEN (SELECT IFNULL(SUM(contrato_empresarial.quantidade_vidas), 0)
                                  FROM contrato_empresarial
                                  WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id
                                    AND contrato_empresarial.plano_id = 5
                                    AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                                    AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                        ELSE 0
                    END) as quantidade_empresarial,

                -- Valor total
                SUM(CASE
                        WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0
                            THEN contratos.valor_plano
                        WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0
                            THEN contratos.valor_plano
                        WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1
                            THEN contrato_empresarial.valor_plano
                        ELSE 0
                    END) as valor_total,

                    -- Quantidade total de vidas (soma das 3 colunas anteriores)
                    SUM(CASE
                            WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0
                                THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                                      FROM clientes
                                               INNER JOIN contratos ON contratos.cliente_id = clientes.id
                                      WHERE contratos.id = comissoes.contrato_id
                                        AND contratos.plano_id = 1
                                        AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                        AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                            WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0
                                THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                                      FROM clientes
                                               INNER JOIN contratos ON contratos.cliente_id = clientes.id
                                      WHERE contratos.id = comissoes.contrato_id
                                        AND contratos.plano_id = 3
                                        AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                        AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                            WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1
                                THEN (SELECT IFNULL(SUM(contrato_empresarial.quantidade_vidas), 0)
                                      FROM contrato_empresarial
                                      WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id
                                        AND contrato_empresarial.plano_id = 5
                                        AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                                        AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                            ELSE 0
                        END) as quantidade_vidas,

                        corretoras.nome as corretora,

                        -- Metas do corretor
                        COALESCE(metas.individual, 0) + COALESCE(metas.coletivo, 0) + COALESCE(metas.super_simples, 0) as total_meta

                        FROM comissoes
                                 INNER JOIN users ON users.id = comissoes.user_id
                                 INNER JOIN corretoras ON users.corretora_id = corretoras.id
                                 LEFT JOIN contratos ON contratos.id = comissoes.contrato_id
                                 LEFT JOIN contrato_empresarial ON contrato_empresarial.id = comissoes.contrato_empresarial_id
                                 LEFT JOIN clientes ON clientes.id = contratos.cliente_id

                        -- Junção com a tabela metas para pegar as metas do corretor
                                 LEFT JOIN metas ON metas.user_id = users.id

                        -- Filtro para corretora específica
                        WHERE comissoes.plano_id IN (1, 3, 5) AND (comissoes.user_id != 198 AND comissoes.user_id != 269)
                        AND (
                              (users.ativo = 1)
                              OR (users.ativo = 2 AND (
                                  (comissoes.plano_id IN (1, 3) AND clientes.quantidade_vidas >= 1
                                      AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                      AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))

                                  )
                                  OR (
                                    comissoes.plano_id = 5 AND contrato_empresarial.quantidade_vidas >= 1
                                    AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                                    AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                                  )

                              )
                         {$corretoraFilter}
                        GROUP BY comissoes.user_id
                        ORDER BY quantidade_vidas DESC;
                "
            );
            $podium = view('ranking.podium',[
                'ranking' => $ranking,
                'corretora' => $corretora
            ])->render();
            $ranking = view('ranking.ranking',[
                'ranking' => $ranking,
                'corretora' => $corretora
            ])->render();
            $totals = DB::select("SELECT
                SUM(
                    CASE WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0 THEN
                        (
                        SELECT IFNULL(SUM(clientes.quantidade_vidas), 0) FROM clientes
                        INNER JOIN contratos ON contratos.cliente_id = clientes.id WHERE contratos.id = comissoes.contrato_id AND contratos.plano_id = 1
                        AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                        AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE())
                        ) ELSE 0 END)
                        as total_individual,
                        SUM(CASE WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0 THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                        FROM clientes INNER JOIN contratos ON contratos.cliente_id = clientes.id WHERE contratos.id = comissoes.contrato_id AND contratos.plano_id = 3
                        AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                        AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE())

                        ) ELSE 0 END) as total_coletivo,
                SUM( CASE WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1 THEN(SELECT IFNULL(SUM(contrato_empresarial.quantidade_vidas), 0)
                                 FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id AND contrato_empresarial.plano_id = 5
                                 AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                                 AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                                 ELSE 0 END) as total_empresarial
                FROM comissoes
                WHERE comissoes.plano_id IN (1, 3, 5) " . ($corretora_id ? " AND comissoes.corretora_id = {$corretora_id}" : "") . "
            ");

            return [
                'meta' => $meta,
                'podium' => $podium,
                'ranking' => $ranking,
                'totals' => $totals,
                'corretora' => $corretora,
                'concessionarias' => $concessionarias
            ];

        } else if($corretora == "vivaz") {

            $ranking = DB::select(
                "
                    SELECT
                        users.name as corretor,
                        users.image as imagem,

                    -- Verifica se o corretor é Parceiro ou CLT
                    IF(EXISTS(
                        SELECT 1
                        FROM comissoes_corretores_configuracoes
                        WHERE comissoes_corretores_configuracoes.user_id = users.id
                    ), 'Parceiro', 'CLT') as tipo_contratacao,

                    -- Quantidade individual
                    SUM(CASE
                            WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0
                                THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                                      FROM clientes
                                               INNER JOIN contratos ON contratos.cliente_id = clientes.id
                                      WHERE contratos.id = comissoes.contrato_id
                                        AND contratos.plano_id = 1
                                        AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                        AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                            ELSE 0
                        END) as quantidade_individual,

                    -- Quantidade coletivo
                    SUM(CASE
                            WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0
                                THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                                      FROM clientes
                                               INNER JOIN contratos ON contratos.cliente_id = clientes.id
                                      WHERE contratos.id = comissoes.contrato_id
                                        AND contratos.plano_id = 3
                                        AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                        AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                            ELSE 0
                        END) as quantidade_coletivo,

                -- Quantidade empresarial
                SUM(CASE
                        WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1
                            THEN (SELECT IFNULL(SUM(contrato_empresarial.quantidade_vidas), 0)
                                  FROM contrato_empresarial
                                  WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id
                                    AND contrato_empresarial.plano_id = 5
                                    AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                                    AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                        ELSE 0
                    END) as quantidade_empresarial,

                -- Valor total
                SUM(CASE
                        WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0
                            THEN contratos.valor_plano
                        WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0
                            THEN contratos.valor_plano
                        WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1
                            THEN contrato_empresarial.valor_plano
                        ELSE 0
                    END) as valor_total,

                    -- Quantidade total de vidas (soma das 3 colunas anteriores)
                    SUM(CASE
                            WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0
                                THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                                      FROM clientes
                                               INNER JOIN contratos ON contratos.cliente_id = clientes.id
                                      WHERE contratos.id = comissoes.contrato_id
                                        AND contratos.plano_id = 1
                                        AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                        AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                            WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0
                                THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                                      FROM clientes
                                               INNER JOIN contratos ON contratos.cliente_id = clientes.id
                                      WHERE contratos.id = comissoes.contrato_id
                                        AND contratos.plano_id = 3
                                        AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                        AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))
                            WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1
                                THEN (SELECT IFNULL(SUM(contrato_empresarial.quantidade_vidas), 0)
                                      FROM contrato_empresarial
                                      WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id
                                        AND contrato_empresarial.plano_id = 5
                                        AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                                        AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                            ELSE 0
                        END) as quantidade_vidas,
                        corretoras.nome as corretora,
                        -- Metas do corretor porque
                        -- não esta dando certo quando for 2 tem quantidade_vidas tem que ser maior ou igual a 1

                        COALESCE(metas.individual, 0) + COALESCE(metas.coletivo, 0) + COALESCE(metas.super_simples, 0) as total_meta
                        FROM comissoes
                                 INNER JOIN users ON users.id = comissoes.user_id
                                 INNER JOIN corretoras ON users.corretora_id = corretoras.id
                                 LEFT JOIN contratos ON contratos.id = comissoes.contrato_id
                                 LEFT JOIN contrato_empresarial ON contrato_empresarial.id = comissoes.contrato_empresarial_id
                                 LEFT JOIN clientes ON clientes.id = contratos.cliente_id
                        -- Junção com a tabela metas para pegar as metas do corretor
                                 LEFT JOIN metas ON metas.user_id = users.id
                        -- Filtro para corretora específica
                        WHERE comissoes.plano_id IN (1, 3, 5) AND (comissoes.user_id != 198 AND comissoes.user_id != 269)
                        AND (
                              (users.ativo = 1)
                              OR (users.ativo = 2 AND (
                                  (comissoes.plano_id IN (1, 3) AND clientes.quantidade_vidas >= 1
                                      AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                                      AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE()))

                                  )
                                  OR (
                                    comissoes.plano_id = 5 AND contrato_empresarial.quantidade_vidas >= 1
                                    AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                                    AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                                  )

                              )

                        GROUP BY comissoes.user_id
                        ORDER BY quantidade_vidas DESC;
                "
            );
            $podium = view('ranking.podium',[
                'ranking' => $ranking,
                'corretora' => $corretora
            ])->render();
            $ranking = view('ranking.ranking',[
                'ranking' => $ranking,
                'corretora' => $corretora
            ])->render();
            $totals = DB::select("SELECT
                SUM(
                    CASE WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0 THEN
                        (
                        SELECT IFNULL(SUM(clientes.quantidade_vidas), 0) FROM clientes
                        INNER JOIN contratos ON contratos.cliente_id = clientes.id WHERE contratos.id = comissoes.contrato_id AND contratos.plano_id = 1
                        AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                        AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE())
                        ) ELSE 0 END)
                        as total_individual,
                        SUM(CASE WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0 THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0)
                        FROM clientes INNER JOIN contratos ON contratos.cliente_id = clientes.id WHERE contratos.id = comissoes.contrato_id AND contratos.plano_id = 3
                        AND MONTH(contratos.created_at) = MONTH(CURRENT_DATE())
                        AND YEAR(contratos.created_at) = YEAR(CURRENT_DATE())
                        ) ELSE 0 END) as total_coletivo,
                SUM( CASE WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1 THEN(SELECT IFNULL(SUM(contrato_empresarial.quantidade_vidas), 0)
                                 FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id AND contrato_empresarial.plano_id = 5
                                 AND MONTH(contrato_empresarial.created_at) = MONTH(CURRENT_DATE())
                                 AND YEAR(contrato_empresarial.created_at) = YEAR(CURRENT_DATE()))
                                 ELSE 0 END) as total_empresarial
                FROM comissoes
                WHERE comissoes.plano_id IN (1, 3, 5)" . ($corretora_id ? " AND comissoes.corretora_id = {$corretora_id}" : "") . "
            ");

            return [
                'meta' => $meta,
                'podium' => $podium,
                'ranking' => $ranking,
                'totals' => $totals,
                'corretora' => $corretora,
                'concessionarias' => $concessionarias
            ];


        } else if($corretora == "diario") {

            $corretora_id = auth()->user()->corretora_id;
            $ranking = DB::connection('tenant')->select("
                SELECT
                    users.name AS corretor,
                    users.image AS imagem,
                    IF(EXISTS(
                        SELECT 1
                        FROM comissoes_corretores_configuracoes
                        WHERE comissoes_corretores_configuracoes.user_id = users.id
                    ), 'Parceiro', 'CLT') AS tipo_contratacao,
                    SUM(vidas_individual) AS quantidade_individual,
                    SUM(vidas_coletivo) AS quantidade_coletivo,
                    SUM(vidas_empresarial) AS quantidade_empresarial,
                    0 AS valor_total,
                    1 AS total_meta,
                    SUM(vidas_individual + vidas_coletivo + vidas_empresarial) AS quantidade_vidas,
                    corretoras.nome AS corretora

                        FROM ranking_diario
                        INNER JOIN users ON users.id = ranking_diario.user_id
                        INNER JOIN corretoras ON corretoras.id = ranking_diario.corretora_id
                        WHERE ranking_diario.corretora_id = 1
                        AND ranking_diario.data = DATE(NOW())
                        AND (
                            (users.ativo = 1)
                            OR (users.ativo = 2 AND (vidas_individual + vidas_coletivo + vidas_empresarial) >= 1)
                        )
                        GROUP BY ranking_diario.user_id, ranking_diario.data
                        ORDER BY quantidade_vidas DESC;
            ");
            $podium = view('ranking.podium',[
                'ranking' => $ranking,
                'corretora' => $corretora
            ])->render();
            $ranking = view('ranking.ranking',[
                'ranking' => $ranking,
                'corretora' => $corretora
            ])->render();
            $totals = DB::connection('tenant')->select("
                SELECT
                    SUM(vidas_individual) AS total_individual,
                    SUM(vidas_coletivo) AS total_coletivo,
                    SUM(vidas_empresarial) AS total_empresarial,
                    27 AS meta
                 FROM ranking_diario
                 WHERE DATA = date(NOW())
            ");
            return [
                'meta' => $meta,
                'podium' => $podium,
                'ranking' => $ranking,
                'totals' => $totals,
                'concessionarias' => $concessionarias
            ];
        } else if($corretora == "estrela") {
            $ranking = DB::select("
                SELECT
                    users.name AS corretor,
                    users.image AS imagem,
                    COALESCE(SUM(IF(MONTH(contratos.created_at) = 7, clientes.quantidade_vidas, 0)),0) AS janeiro,
                    COALESCE(SUM(IF(MONTH(contratos.created_at) = 8, clientes.quantidade_vidas, 0)),0) AS fevereiro,
                    COALESCE(SUM(IF(MONTH(contratos.created_at) = 9, clientes.quantidade_vidas, 0)),0) AS marco,
                    COALESCE(SUM(IF(MONTH(contratos.created_at) = 10, clientes.quantidade_vidas, 0)),0) AS abril,
                    COALESCE(SUM(IF(MONTH(contratos.created_at) = 11, clientes.quantidade_vidas, 0)),0) AS maio,
                    COALESCE(SUM(IF(MONTH(contratos.created_at) = 12, clientes.quantidade_vidas, 0)),0) AS junho,
                    COALESCE(SUM(clientes.quantidade_vidas), 0) AS quantidade_vidas,
                    COALESCE(SUM(contratos.valor_adesao), 0) AS valor,
            CASE
                WHEN SUM(clientes.quantidade_vidas) >= 150 AND SUM(clientes.quantidade_vidas) <= 190 THEN 'tres_estrelas'
                WHEN SUM(clientes.quantidade_vidas) >= 191 AND SUM(clientes.quantidade_vidas) <= 250 THEN 'quatro_estrelas'
                WHEN SUM(clientes.quantidade_vidas) > 250 THEN 'cinco_estrelas'
                ELSE 'nao_classificado'
            END AS status,
            CASE
                WHEN SUM(clientes.quantidade_vidas) >= 150 AND SUM(clientes.quantidade_vidas) <= 190 THEN 191 - SUM(clientes.quantidade_vidas)
                WHEN SUM(clientes.quantidade_vidas) >= 191 AND SUM(clientes.quantidade_vidas) <= 250 THEN 251 - SUM(clientes.quantidade_vidas)
                WHEN SUM(clientes.quantidade_vidas) > 250 THEN 'Atingiu a meta'
                ELSE 150 - SUM(clientes.quantidade_vidas)
            END AS falta
        FROM comissoes
        INNER JOIN users ON users.id = comissoes.user_id
        INNER JOIN contratos ON contratos.id = comissoes.contrato_id
        INNER JOIN clientes ON clientes.id = contratos.cliente_id
        WHERE contratos.created_at BETWEEN '2025-01-01' AND '2025-06-30' AND contratos.plano_id = 1 AND users.ativo != 2
        GROUP BY comissoes.user_id, users.name, users.image
        ORDER BY quantidade_vidas DESC
            ");
            $podium = view('ranking.podium',[
                'ranking' => $ranking,
                'corretora' => "Estrela"
            ])->render();
            $ranking = view('ranking.ranking-estrela',[
                'ranking' => $ranking,
                'corretora' => $corretora
            ])->render();
            $totals = DB::select("SELECT
                SUM(CASE WHEN comissoes.plano_id = 1 AND comissoes.empresarial = 0 THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0) FROM clientes INNER JOIN contratos ON contratos.cliente_id = clientes.id WHERE contratos.id = comissoes.contrato_id AND contratos.plano_id = 1) ELSE 0 END) as total_individual,
                SUM(CASE WHEN comissoes.plano_id = 3 AND comissoes.empresarial = 0 THEN (SELECT IFNULL(SUM(clientes.quantidade_vidas), 0) FROM clientes INNER JOIN contratos ON contratos.cliente_id = clientes.id WHERE contratos.id = comissoes.contrato_id AND contratos.plano_id = 3) ELSE 0 END) as total_coletivo,
                SUM( CASE WHEN comissoes.plano_id = 5 AND comissoes.empresarial = 1 THEN(SELECT IFNULL(SUM(contrato_empresarial.quantidade_vidas), 0) FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id AND contrato_empresarial.plano_id = 5)ELSE 0 END) as total_empresarial
                FROM comissoes
                WHERE comissoes.plano_id IN (1, 3, 5)
                AND (comissoes.user_id != 198 AND comissoes.user_id != 269)
            ");

            return [
                'meta' => 0,
                'podium' => $podium,
                'ranking' => $ranking,
                'totals' => $totals,
                'corretora' => $corretora,
                'concessionarias' => $concessionarias
            ];

        } else if($corretora == "semanal") {
            $corretora_id = auth()->user()->corretora_id;
            $ranking = DB::select("
                SELECT
                    users.name AS corretor,
                    users.image AS imagem,
                    IF(EXISTS(
                      SELECT 1
                      FROM comissoes_corretores_configuracoes
                      WHERE comissoes_corretores_configuracoes.user_id = users.id
                   ), 'Parceiro', 'CLT') as tipo_contratacao,
                   SUM(vidas_individual) AS quantidade_individual,
                   SUM(vidas_coletivo) AS quantidade_coletivo,
                   SUM(vidas_empresarial) AS quantidade_empresarial,
                   0 as valor_total,
                   1 as total_meta,
                   SUM(vidas_individual + vidas_coletivo + vidas_empresarial) AS quantidade_vidas,
                   corretoras.nome as corretora

                FROM ranking_diario
                INNER JOIN users ON users.id = ranking_diario.user_id
                INNER JOIN corretoras ON corretoras.id = ranking_diario.corretora_id
                    WHERE ranking_diario.corretora_id = :corretora_id
                    AND ranking_diario.data BETWEEN
                        -- Primeira data da semana (segunda-feira)
                        DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
                        AND
                        -- Última data da semana (sexta-feira)
                        DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) - 4 DAY)
                        AND (
                            (users.ativo = 1)
                            OR (users.ativo = 2 AND (vidas_individual + vidas_coletivo + vidas_empresarial) >= 1)
                        )
                GROUP BY ranking_diario.user_id

                ORDER BY quantidade_vidas DESC
            ", ['corretora_id' => $corretora_id]);
            $podium = view('ranking.podium', [
                'ranking' => $ranking,
                'corretora' => $corretora
            ])->render();
            $ranking = view('ranking.ranking', [
                'ranking' => $ranking,
                'corretora' => $corretora
            ])->render();
            $totals = DB::select("
                SELECT
                    SUM(vidas_individual) AS total_individual,
                    SUM(vidas_coletivo) AS total_coletivo,
                    SUM(vidas_empresarial) AS total_empresarial,
                    27 AS meta
                 FROM ranking_diario
                 WHERE DATA BETWEEN
                     DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
                     AND
                     DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) - 4 DAY)

            ");
            return [
                'meta' => $meta,
                'podium' => $podium,
                'ranking' => $ranking,
                'totals' => $totals,
                'corretora' => $corretora,
                'concessionarias' => $concessionarias
            ];
        } else if($corretora == "concessi") {

            $ranking = DB::select("
                SELECT id,nome,imagem,
                meta_individual + meta_super_simples + meta_pme + meta_adesao AS meta_total,
                individual + super_simples + pme + adesao AS total_vidas,
                individual as individual,
                super_simples as super_simples,
                pme as pme,
                adesao as adesao,
                ROUND(((individual + super_simples + pme + adesao) /
                (meta_individual + meta_super_simples + meta_pme + meta_adesao)) * 100, 2) AS porcentagem_vendas
                FROM concessionarias
                where id != 17
                ORDER BY total_vidas DESC;
            ");
            $podium = view('ranking.podium-concessionarias',[
                'ranking' => $ranking
            ])->render();
            $ranking = view('ranking.ranking-concessionarias',[
                'ranking' => $ranking,
                'corretora' => $corretora
            ])->render();

            $totals = DB::select("
                SELECT
                    3096 as meta_total,
                    SUM(individual) AS total_individual,
                    SUM(super_simples) AS total_super_simples,
                    SUM(pme) AS total_pme,
                    SUM(adesao) AS total_adesao,
                    SUM(meta_individual) + SUM(meta_super_simples) + SUM(meta_pme) + SUM(meta_adesao) AS total_meta,
                    SUM(individual) + SUM(super_simples) + SUM(pme) + SUM(adesao) AS total_vidas,
                    (SUM(individual) + SUM(super_simples) + SUM(pme) + SUM(adesao)) /
                    NULLIF(SUM(meta_individual) + SUM(meta_super_simples) + SUM(meta_pme) + SUM(meta_adesao), 0) * 100 AS porcentagem_geral
                FROM
                    concessionarias;
            ");

            return [
                'meta' => 0,
                'podium' => $podium,
                'ranking' => $ranking,
                'totals' => $totals,
                '$corretora' => $corretora,
                'concessionarias' => $concessionarias
            ];
        }
    }

    public function index(Request $request)
    {
        $hoje = \Carbon\Carbon::today();

        $corretores = User::where("corretora_id",1)->where('ativo',1)->get();

        $meta_ranking_diario = count($corretores);

        foreach ($corretores as $corretor) {
            // Verifica se há um registro para o corretor na data atual
            $registro = RankingDiario::on('tenant')
                ->where('corretora_id', auth()->user()->corretora_id)
                ->whereDate('data',$hoje)
                ->where("nome","like","%{$corretor->name}%")
                ->count();

            // Se não houver registro, cria um novo com valores zerados
            if ($registro == 0) {
                RankingDiario::create([
                    'nome' => $corretor->name,
                    'corretora_id' => $corretor->corretora_id,
                    'vidas_individual' => 0,
                    'vidas_coletivo' => 0,
                    'vidas_empresarial' => 0,
                    'user_id' => $corretor->id,
                    'data' => $hoje,
                ]);
            }
        }

        $ranking = "";
        $totals = "";

        $vendasDiarias = RankingDiario::whereDate('data', $hoje)
            ->get()
            ->sortByDesc(function($venda) {
                return $venda->vidas_individual + $venda->vidas_coletivo + $venda->vidas_empresarial;
            });



        $ranking = DB::connection('tenant')->select("
                SELECT
                    users.name AS corretor,
                    users.image AS imagem,
                    IF(EXISTS(
                      SELECT 1
                      FROM comissoes_corretores_configuracoes
                      WHERE comissoes_corretores_configuracoes.user_id = users.id
                   ), 'Parceiro', 'CLT') as tipo_contratacao,
                   SUM(vidas_individual) AS quantidade_individual,
                   SUM(vidas_coletivo) AS quantidade_coletivo,
                   SUM(vidas_empresarial) AS quantidade_empresarial,
                   0 as valor_total,
                   1 as total_meta,
                   SUM(vidas_individual + vidas_coletivo + vidas_empresarial) AS quantidade_vidas,
                   corretoras.nome as corretora

                FROM ranking_diario
                INNER JOIN users ON users.id = ranking_diario.user_id
                INNER JOIN corretoras ON corretoras.id = ranking_diario.corretora_id
                    where ranking_diario.corretora_id = 1
                    AND ranking_diario.data = DATE(NOW())
                GROUP BY ranking_diario.user_id,ranking_diario.data
                ORDER BY quantidade_vidas DESC
            ");




        $totals = DB::connection('tenant')->select("
            SELECT
                SUM(vidas_individual) AS total_individual,
                SUM(vidas_coletivo) AS total_coletivo,
                SUM(vidas_empresarial) AS total_empresarial,
                27 AS meta
             FROM ranking_diario
             WHERE DATA = date(NOW())
        ");


        $concessionarias = Concessionaria::all();




        return view('ranking.index',[
            'liderAtual' => $vendasDiarias->first()->nome,
            'ranking' => $ranking,
            'totals' => $totals,
            'concessionarias' => $concessionarias,
            'vendasDiarias' => $vendasDiarias,
            'meta_ranking_diario' => $meta_ranking_diario,

        ]);
    }

    public function cadastrarConcessionaria(Request $request)
    {

        $concessionarias = $request->input('concessionarias');

        // Itera sobre cada concessionária e salva as alterações
        foreach ($concessionarias as $concessionariaData) {
            // Encontra a concessionária pelo ID
            $concessionaria = Concessionaria::find($concessionariaData['id']);

            // Atualiza os campos da concessionária
            if ($concessionaria) {
                $concessionaria->meta_individual = $concessionariaData['meta_individual'];
                $concessionaria->individual = $concessionariaData['individual'];

                $concessionaria->meta_super_simples = $concessionariaData['meta_super_simples'];
                $concessionaria->super_simples = $concessionariaData['super_simples'];

                $concessionaria->meta_pme = $concessionariaData['meta_pme'];
                $concessionaria->pme = $concessionariaData['pme'];

                $concessionaria->meta_adesao = $concessionariaData['meta_adesao'];
                $concessionaria->adesao = $concessionariaData['adesao'];

                // Salva a concessionária no banco de dados
                $concessionaria->save();
            }
        }

        // Retorna uma resposta de sucesso
        return response()->json(['success' => true]);
    }
}
