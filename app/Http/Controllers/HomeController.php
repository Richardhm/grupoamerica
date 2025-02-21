<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\ComissoesCorretoresLancadas;
use App\Models\Contrato;
use App\Models\Administradora;
use App\Models\ContratoEmpresarial;
use App\Models\TabelaOrigens;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class HomeController extends Controller
{
    public function dashboardMes(Request $request)
    {
        $mesAnoSelecionado = $request->mes_ano;

        // Extrai o mês e o ano do valor selecionado
        $mesAnoArray = explode("/", $mesAnoSelecionado);
        $mes = $mesAnoArray[0];
        $ano = $mesAnoArray[1];

        $startDate = date("Y-m-d", strtotime("first day of $ano-$mes"));
        $endDate = date("Y-m-d", strtotime("last day of $ano-$mes"));

        $total_coletivo_quantidade_vidas = Cliente::select("*")
            ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
            ->where("contratos.plano_id", 3)
            ->whereYear("contratos.created_at", $ano)
            ->whereMonth("contratos.created_at", $mes)
            ->sum('quantidade_vidas');

        $total_individual = Contrato
            ::where("plano_id",1)
            ->whereYear("contratos.created_at", $ano)
            ->whereMonth("contratos.created_at", $mes)
            ->sum('valor_adesao');

        $total_coletivo = Contrato::where("plano_id",3)
            ->whereYear("contratos.created_at", $ano)
            ->whereMonth("contratos.created_at", $mes)
            ->sum('valor_adesao');

        $total_ss = ContratoEmpresarial::where('plano_id',5)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereMonth("contrato_empresarial.created_at", $mes)
            ->sum('valor_plano');//SS

        $total_sindipao = ContratoEmpresarial::where('plano_id',6)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereMonth("contrato_empresarial.created_at", $mes)
            ->sum('valor_plano');//Sindipão

        $total_sindimaco = ContratoEmpresarial::where('plano_id',9)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereMonth("contrato_empresarial.created_at", $mes)
            ->sum('valor_plano');//Sindimaco
        $total_sincofarma = ContratoEmpresarial::where('plano_id',13)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereMonth("contrato_empresarial.created_at", $mes)
            ->sum('valor_plano');//Sincofarma

        $valor_adesao_col_ind = Contrato::
        whereMonth("created_at",$mes)
            ->whereYear("created_at",$ano)
            ->sum('valor_adesao');

        $valor_plano_empresar = ContratoEmpresarial::
        whereMonth("created_at",$mes)
            ->whereYear("created_at",$ano)
            ->sum('valor_plano');
        $total_valor = $valor_adesao_col_ind + $valor_plano_empresar;

        $total_individual_quantidade_vidas = Cliente::select("*")
            ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
            ->where("contratos.plano_id", 1)
            ->whereYear("contratos.created_at", $ano)
            ->whereMonth("contratos.created_at", $mes)
            ->sum('quantidade_vidas');

        $total_super_simples_quantidade_vidas = ContratoEmpresarial::where("plano_id",5)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereMonth("contrato_empresarial.created_at", $mes)
            ->sum('quantidade_vidas');

        $total_sindipao_quantidade_vidas = ContratoEmpresarial::where("plano_id",6)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereMonth("contrato_empresarial.created_at", $mes)
            ->sum('quantidade_vidas');

        $total_sindimaco_quantidade_vidas = ContratoEmpresarial::where("plano_id",9)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereMonth("contrato_empresarial.created_at", $mes)
            ->sum('quantidade_vidas');

        $total_sincofarma_quantidade_vidas = ContratoEmpresarial::where("plano_id",13)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereMonth("contrato_empresarial.created_at", $mes)
            ->sum('quantidade_vidas');

        $quantidade_vidas_mes = $total_coletivo_quantidade_vidas + $total_individual_quantidade_vidas + $total_super_simples_quantidade_vidas
            + $total_sindipao_quantidade_vidas + $total_sindimaco_quantidade_vidas + $total_sincofarma_quantidade_vidas;

        return [
            "total_coletivo_quantidade_vidas" => $total_coletivo_quantidade_vidas,
            "total_individual_quantidade_vidas" => $total_individual_quantidade_vidas,
            "total_super_simples_quantidade_vidas" => $total_super_simples_quantidade_vidas,
            "total_sindipao_quantidade_vidas" => $total_sindipao_quantidade_vidas,
            "total_sindimaco_quantidade_vidas" => $total_sindimaco_quantidade_vidas,
            "total_sincofarma_quantidade_vidas" => $total_sincofarma_quantidade_vidas,
            "quantidade_vidas_mes" => $quantidade_vidas_mes,

            "total_individual" => number_format($total_individual,2,",","."),
            "total_coletivo" => number_format($total_coletivo,2,",","."),
            "total_ss" => number_format($total_ss,2,",","."),
            "total_sindipao" => number_format($total_sindipao,2,",","."),
            "total_sindimaco" => number_format($total_sindimaco,2,",","."),
            "total_sincofarma" => number_format($total_sincofarma,2,",","."),
            "total_valor" => number_format($total_valor,2,",",".")
        ];
    }



    public function dashboardRankingSemestral(Request $request)
    {
        $semestreSelecionado = $request->valor;
        $semestre = explode("/",$semestreSelecionado);
        $ano = $semestre[1];
        if ($semestre[0] == 1) {
            // Primeiro semestre (de janeiro a junho)
            $startDate = $ano . "-01-01";
            $endDate = $ano . "-06-30";
        } else {
            // Segundo semestre (de julho a dezembro)
            $startDate = $ano . "-07-01";
            $endDate = $ano . "-12-31";
        }
        $ranking = DB::select(
            "
            select
            users.name as usuario,
            users.image AS imagem,
            (
                       (select if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) from clientes
							  INNER JOIN contratos ON contratos.cliente_id = clientes.id

							  where clientes.user_id = comissoes.user_id AND
							  contratos.created_at BETWEEN '$startDate' AND '$endDate')
                       +
                       (select if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) from contrato_empresarial

							  where contrato_empresarial.user_id = comissoes.user_id AND
							  contrato_empresarial.created_at BETWEEN '$startDate' AND '$endDate')
            ) as quantidade,
            (
                       (select if(sum(valor_adesao)>0,sum(valor_adesao),0) from clientes
							  INNER JOIN contratos ON contratos.cliente_id = clientes.id

							  where clientes.user_id = comissoes.user_id AND
							  contratos.created_at BETWEEN '$startDate' AND '$endDate')
                       +
                       (select if(sum(valor_plano)>0,sum(valor_plano),0) from contrato_empresarial

							  where contrato_empresarial.user_id = comissoes.user_id AND
							  contrato_empresarial.created_at BETWEEN '$startDate' AND '$endDate')
            ) as valor
            from comissoes

            inner join users on users.id = comissoes.user_id

            group by user_id order by quantidade desc
            "
        );
        $semestre_a = "$semestre[0]/$ano";
        return view('home.tabela-semestral',[
            "ranking" => $ranking,
            "semestre_as" => $semestre_a

        ]);

    }

    public function dashboardRankingano(Request $request)
    {
        $ano = $request->valor;
        $ranking = DB::select(
            "
            select
            users.name as usuario,
            users.image AS imagem,
            (
                       (select if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) from clientes
							  INNER JOIN contratos ON contratos.cliente_id = clientes.id
							  where clientes.user_id = comissoes.user_id AND
							  YEAR(contratos.created_at) = '$ano')
                       +
                       (select if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) from contrato_empresarial
							  where contrato_empresarial.user_id = comissoes.user_id AND
							  YEAR(contrato_empresarial.created_at) = '$ano')
            ) as quantidade,
            (
                       (select if(sum(valor_adesao)>0,sum(valor_adesao),0) from clientes
							  INNER JOIN contratos ON contratos.cliente_id = clientes.id

							  where clientes.user_id = comissoes.user_id AND
							  YEAR(contratos.created_at) = '$ano')
                       +
                       (select if(sum(valor_plano)>0,sum(valor_plano),0) from contrato_empresarial

							  where contrato_empresarial.user_id = comissoes.user_id AND
							  YEAR(contrato_empresarial.created_at) = '$ano')
            ) as valor
            from comissoes
            inner join users on users.id = comissoes.user_id

            group by user_id order by quantidade desc
            "
        );
        return view('home.tabela-ano',[
            "ranking" => $ranking,
            "ano" => $ano
        ]);
    }

    public function dashboardTabelaRankingmes(Request $request)
    {
        $mes_atual = explode("/",$request->valor)[0];
        $ano_atual = explode("/",$request->valor)[1];
        $dados = DB::select(
            "
            SELECT
                u.name AS user_name,
                SUM(CASE WHEN co.empresarial = 0 AND co.plano_id = 1 AND MONTH(c.created_at) = $mes_atual AND YEAR(c.created_at) = $ano_atual THEN cl.quantidade_vidas ELSE 0 END) AS individual,
                SUM(CASE WHEN co.empresarial = 0 AND co.plano_id = 3 AND MONTH(c.created_at) = $mes_atual AND YEAR(c.created_at) = $ano_atual THEN cl.quantidade_vidas ELSE 0 END) AS coletivo,
                SUM(CASE WHEN co.empresarial = 1 AND co.plano_id = 4 AND MONTH(ce.created_at) = $mes_atual AND YEAR(ce.created_at) = $ano_atual THEN ce.quantidade_vidas ELSE 0 END) AS pme,
                SUM(CASE WHEN co.empresarial = 1 AND co.plano_id = 5 AND MONTH(ce.created_at) = $mes_atual AND YEAR(ce.created_at) = $ano_atual THEN ce.quantidade_vidas ELSE 0 END) AS super_simples,
                SUM(CASE WHEN co.empresarial = 1 AND co.plano_id = 6 AND MONTH(ce.created_at) = $mes_atual AND YEAR(ce.created_at) = $ano_atual THEN ce.quantidade_vidas ELSE 0 END) AS sindipao,
                SUM(CASE WHEN co.empresarial = 1 AND co.plano_id = 9 AND MONTH(ce.created_at) = $mes_atual AND YEAR(ce.created_at) = $ano_atual THEN ce.quantidade_vidas ELSE 0 END) AS sindimaco,
                SUM(CASE WHEN co.empresarial = 1 AND co.plano_id = 13 AND MONTH(ce.created_at) = $mes_atual AND YEAR(ce.created_at) = $ano_atual THEN ce.quantidade_vidas ELSE 0 END) AS sincofarma,
                SUM(COALESCE(
                    CASE WHEN co.empresarial = 0 AND co.plano_id IN (1, 3) AND MONTH(c.created_at) = $mes_atual AND YEAR(c.created_at) = $ano_atual THEN cl.quantidade_vidas
                         WHEN co.empresarial = 1 AND co.plano_id IN (4, 5, 6, 9, 13) AND MONTH(ce.created_at) = $mes_atual AND YEAR(ce.created_at) = $ano_atual THEN ce.quantidade_vidas
                         ELSE 0
                    END, 0
                )) AS total
            FROM
                comissoes AS co
            LEFT JOIN
                contratos AS c ON c.id = co.contrato_id
            LEFT JOIN
                clientes AS cl ON cl.id = c.cliente_id
            LEFT JOIN
                contrato_empresarial AS ce ON ce.id = co.contrato_empresarial_id
            LEFT JOIN
                users AS u ON u.id = co.user_id
            WHERE
                u.ativo = 1
            GROUP BY
                u.id
            ORDER BY
                total DESC;
            ");

        $total_coletivo_quantidade_vidas = Cliente::select("*")
            ->join('contratos','contratos.cliente_id','=','clientes.id')
            ->where("contratos.plano_id",3)
            ->whereMonth("contratos.created_at",$mes_atual)->whereYear("contratos.created_at",$ano_atual)
            ->sum('quantidade_vidas');

        $total_individual_quantidade_vidas = Cliente::select("*")
            ->join('contratos','contratos.cliente_id','=','clientes.id')
            ->where("contratos.plano_id",1)
            ->whereMonth("contratos.created_at",$mes_atual)->whereYear("contratos.created_at",$ano_atual)
            ->sum('quantidade_vidas');

        $total_super_simples_quantidade_vidas = ContratoEmpresarial::where("plano_id",5)
            ->whereMonth("created_at",$mes_atual)->whereYear("created_at",$ano_atual)
            ->sum('quantidade_vidas');

        $total_sindipao_quantidade_vidas = ContratoEmpresarial::where("plano_id",6)
            ->whereMonth("created_at",$mes_atual)->whereYear("created_at",$ano_atual)
            ->sum('quantidade_vidas');

        $total_sindimaco_quantidade_vidas = ContratoEmpresarial::where("plano_id",9)
            ->whereMonth("created_at",$mes_atual)->whereYear("created_at",$ano_atual)
            ->sum('quantidade_vidas');

        $total_sincofarma_quantidade_vidas = ContratoEmpresarial::where("plano_id",13)
            ->whereMonth("created_at",$mes_atual)->whereYear("created_at",$ano_atual)
            ->sum('quantidade_vidas');


        return [
            "page" => view('home.tabela-geral',[
                "dados" => $dados
            ])->render(),
            "total_coletivo_quantidade_vidas" => $total_coletivo_quantidade_vidas,
            "total_individual_quantidade_vidas" => $total_individual_quantidade_vidas,
            "total_super_simples_quantidade_vidas" => $total_super_simples_quantidade_vidas,
            "total_sindipao_quantidade_vidas" => $total_sindipao_quantidade_vidas,
            "total_sindimaco_quantidade_vidas" => $total_sindimaco_quantidade_vidas,
            "total_sincofarma_quantidade_vidas" => $total_sincofarma_quantidade_vidas
        ];

    }







    public function dashboardRankingmes(Request $request)
    {
        $dados = $request->valor;
        $mes = explode("/",$dados)[0];
        $ano = explode("/",$dados)[1];

        $ranking = DB::select(
            "
            select
            users.name as usuario,
            users.image AS imagem,
            (
                       (select if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) from clientes
							  INNER JOIN contratos ON contratos.cliente_id = clientes.id

							  where clientes.user_id = comissoes.user_id AND
							  MONTH(contratos.created_at) = '$mes' AND YEAR(contratos.created_at) = '$ano')
                       +
                       (select if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) from contrato_empresarial

							  where contrato_empresarial.user_id = comissoes.user_id AND
							  MONTH(contrato_empresarial.created_at) = '$mes' AND YEAR(contrato_empresarial.created_at) = '$ano')
            ) as quantidade,
            (
                       (select if(sum(valor_adesao)>0,sum(valor_adesao),0) from clientes
							  INNER JOIN contratos ON contratos.cliente_id = clientes.id

							  where clientes.user_id = comissoes.user_id AND
							  MONTH(contratos.created_at) = '$mes' AND YEAR(contratos.created_at) = '$ano')
                       +
                       (select if(sum(valor_plano)>0,sum(valor_plano),0) from contrato_empresarial

							  where contrato_empresarial.user_id = comissoes.user_id AND
							  MONTH(contrato_empresarial.created_at) = '$mes' AND YEAR(contrato_empresarial.created_at) = '$ano')
            ) as valor
            from comissoes

            inner join users on users.id = comissoes.user_id

            group by user_id order by quantidade desc
            "
        );

        $mesesSelect = DB::select(
            "
                SELECT *
                    FROM (
                        SELECT
                            DATE_FORMAT(created_at, '%m/%Y') AS month_date,
                            CONCAT(
                                CASE
                                    WHEN MONTH(created_at) = 1 THEN 'Janeiro'
                                    WHEN MONTH(created_at) = 2 THEN 'Fevereiro'
                                    WHEN MONTH(created_at) = 3 THEN 'Março'
                                    WHEN MONTH(created_at) = 4 THEN 'Abril'
                                    WHEN MONTH(created_at) = 5 THEN 'Maio'
                                    WHEN MONTH(created_at) = 6 THEN 'Junho'
                                    WHEN MONTH(created_at) = 7 THEN 'Julho'
                                    WHEN MONTH(created_at) = 8 THEN 'Agosto'
                                    WHEN MONTH(created_at) = 9 THEN 'Setembro'
                                    WHEN MONTH(created_at) = 10 THEN 'Outubro'
                                    WHEN MONTH(created_at) = 11 THEN 'Novembro'
                                    WHEN MONTH(created_at) = 12 THEN 'Dezembro'
                                END,
                                '/',
                                YEAR(created_at)
                            ) AS month_name_and_year,
                            YEAR(created_at) AS year,
                            MONTH(created_at) AS month
                        FROM contratos
                        WHERE created_at IS NOT NULL
                        GROUP BY YEAR(created_at), MONTH(created_at)
                    ) AS subquery
                    ORDER BY year DESC, month DESC;
          ");

        return view('home.tabela-mes',[
            "ranking" => $ranking,
            "mes" => $mes,
            "ano" => $ano,
            "data_atual" => $mes."/".$ano,
            "mesesSelect" => $mesesSelect
        ]);
    }











    public function dashboardAno(Request $request)
    {
        $ano = $request->ano;
        $total_coletivo_quantidade_vidas = Cliente::select("*")
            ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
            ->where("contratos.plano_id", 3)
            ->whereYear("contratos.created_at", $ano)
            ->sum('quantidade_vidas');

        $total_individual = Contrato
            ::where("plano_id",1)
            ->whereYear("contratos.created_at", $ano)

            ->sum('valor_adesao');

        $total_coletivo = Contrato::where("plano_id",3)
            ->whereYear("contratos.created_at", $ano)

            ->sum('valor_adesao');

        $total_ss = ContratoEmpresarial::where('plano_id',5)
            ->whereYear("contrato_empresarial.created_at", $ano)

            ->sum('valor_plano');//SS

        $total_sindipao = ContratoEmpresarial::where('plano_id',6)
            ->whereYear("contrato_empresarial.created_at", $ano)

            ->sum('valor_plano');//Sindipão

        $total_sindimaco = ContratoEmpresarial::where('plano_id',9)
            ->whereYear("contrato_empresarial.created_at", $ano)

            ->sum('valor_plano');//Sindimaco
        $total_sincofarma = ContratoEmpresarial::where('plano_id',13)
            ->whereYear("contrato_empresarial.created_at", $ano)

            ->sum('valor_plano');//Sincofarma

        $valor_adesao_col_ind = Contrato::

        whereYear("created_at",$ano)
            ->sum('valor_adesao');

        $valor_plano_empresar = ContratoEmpresarial::

        whereYear("created_at",$ano)
            ->sum('valor_plano');
        $total_valor = $valor_adesao_col_ind + $valor_plano_empresar;






        $total_individual_quantidade_vidas = Cliente::select("*")
            ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
            ->where("contratos.plano_id", 1)
            ->whereYear("contratos.created_at", $ano)

            ->sum('quantidade_vidas');






        $total_super_simples_quantidade_vidas = ContratoEmpresarial::where("plano_id",5)
            ->whereYear("contrato_empresarial.created_at", $ano)

            ->sum('quantidade_vidas');


        $total_sindipao_quantidade_vidas = ContratoEmpresarial::where("plano_id",6)
            ->whereYear("contrato_empresarial.created_at", $ano)

            ->sum('quantidade_vidas');

        $total_sindimaco_quantidade_vidas = ContratoEmpresarial::where("plano_id",9)
            ->whereYear("contrato_empresarial.created_at", $ano)

            ->sum('quantidade_vidas');

        $total_sincofarma_quantidade_vidas = ContratoEmpresarial::where("plano_id",13)
            ->whereYear("contrato_empresarial.created_at", $ano)

            ->sum('quantidade_vidas');

        $quantidade_vidas_mes = $total_coletivo_quantidade_vidas + $total_individual_quantidade_vidas + $total_super_simples_quantidade_vidas
            + $total_sindipao_quantidade_vidas + $total_sindimaco_quantidade_vidas + $total_sincofarma_quantidade_vidas;





        return [
            "total_coletivo_quantidade_vidas" => $total_coletivo_quantidade_vidas,
            "total_individual_quantidade_vidas" => $total_individual_quantidade_vidas,
            "total_super_simples_quantidade_vidas" => $total_super_simples_quantidade_vidas,
            "total_sindipao_quantidade_vidas" => $total_sindipao_quantidade_vidas,
            "total_sindimaco_quantidade_vidas" => $total_sindimaco_quantidade_vidas,
            "total_sincofarma_quantidade_vidas" => $total_sincofarma_quantidade_vidas,
            "quantidade_vidas_ano" => $quantidade_vidas_mes,

            "total_individual" => number_format($total_individual,2,",","."),
            "total_coletivo" => number_format($total_coletivo,2,",","."),
            "total_ss" => number_format($total_ss,2,",","."),
            "total_sindipao" => number_format($total_sindipao,2,",","."),
            "total_sindimaco" => number_format($total_sindimaco,2,",","."),
            "total_sincofarma" => number_format($total_sincofarma,2,",","."),
            "total_valor" => number_format($total_valor,2,",",".")
        ];
    }















    public function dashboardSemestre(Request $request)
    {
        $semestreSelecionado = $request->semestre;
        $semestreArray = explode("/", $semestreSelecionado);
        $semestre = $semestreArray[0];
        $ano = $semestreArray[1];

        $startDate = "";
        $endDate = "";

        if ($semestre == 1) {
            // Primeiro semestre (de janeiro a junho)
            $startDate = $ano . "-01-01";
            $endDate = $ano . "-06-30";
        } else {
            // Segundo semestre (de julho a dezembro)
            $startDate = $ano . "-07-01";
            $endDate = $ano . "-12-31";
        }

        $total_coletivo_quantidade_vidas = Cliente::select("*")
            ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
            ->where("contratos.plano_id", 3)
            ->whereYear("contratos.created_at", $ano)
            ->whereBetween("contratos.created_at", [$startDate, $endDate])
            ->sum('quantidade_vidas');

        $total_individual_quantidade_vidas = Cliente::select("*")
            ->join('contratos','contratos.cliente_id','=','clientes.id')
            ->where("contratos.plano_id",1)
            ->whereYear("contratos.created_at", $ano)
            ->whereBetween("contratos.created_at", [$startDate, $endDate])
            ->sum('quantidade_vidas');


        $total_super_simples_quantidade_vidas = ContratoEmpresarial::where("plano_id",5)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereBetween("contrato_empresarial.created_at", [$startDate, $endDate])
            ->sum('quantidade_vidas');


        $total_sindipao_quantidade_vidas = ContratoEmpresarial::where("plano_id",6)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereBetween("contrato_empresarial.created_at", [$startDate, $endDate])
            ->sum('quantidade_vidas');

        $total_sindimaco_quantidade_vidas = ContratoEmpresarial::where("plano_id",9)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereBetween("contrato_empresarial.created_at", [$startDate, $endDate])
            ->sum('quantidade_vidas');

        $total_sincofarma_quantidade_vidas = ContratoEmpresarial::where("plano_id",13)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereBetween("contrato_empresarial.created_at", [$startDate, $endDate])
            ->sum('quantidade_vidas');


        $total_semestre_vidas = $total_coletivo_quantidade_vidas +
            $total_individual_quantidade_vidas +
            $total_super_simples_quantidade_vidas +
            $total_sindipao_quantidade_vidas +
            $total_sindimaco_quantidade_vidas +
            $total_sincofarma_quantidade_vidas;

        $total_individual = Contrato::where("plano_id",1)
            ->whereYear("contratos.created_at", $ano)
            ->whereBetween("contratos.created_at", [$startDate, $endDate])
            ->sum('valor_adesao');
        $total_coletivo = Contrato::where("plano_id",3)
            ->whereYear("contratos.created_at", $ano)
            ->whereBetween("contratos.created_at", [$startDate, $endDate])
            ->sum('valor_adesao');

        $total_ss = ContratoEmpresarial::where('plano_id',5)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereBetween("contrato_empresarial.created_at", [$startDate, $endDate])
            ->sum('valor_plano');//SS

        $total_sindipao = ContratoEmpresarial::where('plano_id',6)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereBetween("contrato_empresarial.created_at", [$startDate, $endDate])
            ->sum('valor_plano');//Sindipão

        $total_sindimaco = ContratoEmpresarial::where('plano_id',9)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereBetween("contrato_empresarial.created_at", [$startDate, $endDate])
            ->sum('valor_plano');//Sindimaco

        $total_sincofarma = ContratoEmpresarial::where('plano_id',13)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereBetween("contrato_empresarial.created_at", [$startDate, $endDate])
            ->sum('valor_plano');//Sincofarma





        return [
            "total_coletivo_quantidade_vidas" => $total_coletivo_quantidade_vidas,
            "total_individual_quantidade_vidas" => $total_individual_quantidade_vidas,
            "total_super_simples_quantidade_vidas" => $total_super_simples_quantidade_vidas,
            "total_sindipao_quantidade_vidas" => $total_sindipao_quantidade_vidas,
            "total_sindimaco_quantidade_vidas" => $total_sindimaco_quantidade_vidas,
            "total_sincofarma_quantidade_vidas" => $total_sincofarma_quantidade_vidas,
            "total_semestre" => $total_semestre_vidas,

            "total_individual" => number_format($total_individual,2,",","."),
            "total_coletivo" => number_format($total_coletivo,2,",","."),
            "total_ss" => number_format($total_ss,2,",","."),
            "total_sindipao" => number_format($total_sindipao,2,",","."),
            "total_sindimaco" => number_format($total_sindimaco,2,",","."),
            "total_sincofarma" => number_format($total_sincofarma,2,",",".")
        ];




    }












    public function mudarGraficoAno(Request $request)
    {
        $ano = $request->ano;
        if($ano != null) {

            $total_coletivo_quantidade_vidas_janeiro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",01)
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_fevereiro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",02)
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_marco = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",03)
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_abril = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",04)
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_maio = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",05)
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_junho = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",06)
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_julho = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",'07')
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_agosto = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",'08')
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_setembro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",'09')
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_outubro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",10)
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_novembro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",11)
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_dezembro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",12)
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_janeiro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",01)
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_fevereiro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",02)
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_marco = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",03)
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');


            $total_individual_quantidade_vidas_abril = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",04)
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_maio = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",05)
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_junho = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",06)
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_julho = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",07)
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_agosto = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",'08')
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_setembro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",'09')
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_outubro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",'10')
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_novembro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",'11')
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_dezembro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",'12')
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialJaneiro = ContratoEmpresarial
                ::whereMonth("created_at",01)
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialFevereiro = ContratoEmpresarial
                ::whereMonth("created_at",02)
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialMarco = ContratoEmpresarial
                ::whereMonth("created_at",03)
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialAbril = ContratoEmpresarial
                ::whereMonth("created_at",04)
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialMaio = ContratoEmpresarial
                ::whereMonth("created_at",05)
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialJunho = ContratoEmpresarial
                ::whereMonth("created_at",06)
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialJulho = ContratoEmpresarial
                ::whereMonth("created_at",07)
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialAgosto = ContratoEmpresarial
                ::whereMonth("created_at",'08')
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialSetembro = ContratoEmpresarial
                ::whereMonth("created_at",'09')
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialOutubro = ContratoEmpresarial
                ::whereMonth("created_at",'10')
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialNovembro = ContratoEmpresarial
                ::whereMonth("created_at",'11')
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialDezembro = ContratoEmpresarial
                ::whereMonth("created_at",'12')
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');


            return [

                "total_coletivo_quantidade_vidas_janeiro" => $total_coletivo_quantidade_vidas_janeiro,
                "total_coletivo_quantidade_vidas_fevereiro" => $total_coletivo_quantidade_vidas_fevereiro,
                "total_coletivo_quantidade_vidas_marco" => $total_coletivo_quantidade_vidas_marco,
                "total_coletivo_quantidade_vidas_abril" => $total_coletivo_quantidade_vidas_abril,
                "total_coletivo_quantidade_vidas_maio" => $total_coletivo_quantidade_vidas_maio,
                "total_coletivo_quantidade_vidas_junho" => $total_coletivo_quantidade_vidas_junho,
                "total_coletivo_quantidade_vidas_julho" => $total_coletivo_quantidade_vidas_julho,
                "total_coletivo_quantidade_vidas_agosto" => $total_coletivo_quantidade_vidas_agosto,
                "total_coletivo_quantidade_vidas_setembro" => $total_coletivo_quantidade_vidas_setembro,
                "total_coletivo_quantidade_vidas_outubro" => $total_coletivo_quantidade_vidas_outubro,
                "total_coletivo_quantidade_vidas_novembro" => $total_coletivo_quantidade_vidas_novembro,
                "total_coletivo_quantidade_vidas_dezembro" => $total_coletivo_quantidade_vidas_dezembro,

                "total_individual_quantidade_vidas_janeiro" => $total_individual_quantidade_vidas_janeiro,
                "total_individual_quantidade_vidas_fevereiro" => $total_individual_quantidade_vidas_fevereiro,
                "total_individual_quantidade_vidas_marco" => $total_individual_quantidade_vidas_marco,
                "total_individual_quantidade_vidas_abril" => $total_individual_quantidade_vidas_abril,
                "total_individual_quantidade_vidas_maio" => $total_individual_quantidade_vidas_maio,
                "total_individual_quantidade_vidas_junho" => $total_individual_quantidade_vidas_junho,
                "total_individual_quantidade_vidas_julho" => $total_individual_quantidade_vidas_julho,
                "total_individual_quantidade_vidas_agosto" => $total_individual_quantidade_vidas_agosto,
                "total_individual_quantidade_vidas_setembro" => $total_individual_quantidade_vidas_setembro,
                "total_individual_quantidade_vidas_outubro" => $total_individual_quantidade_vidas_outubro,
                "total_individual_quantidade_vidas_novembro" => $total_individual_quantidade_vidas_novembro,
                "total_individual_quantidade_vidas_dezembro" => $total_individual_quantidade_vidas_dezembro,

                "totalContratoEmpresarialJaneiro" => $totalContratoEmpresarialJaneiro,
                "totalContratoEmpresarialFevereiro" => $totalContratoEmpresarialFevereiro,
                "totalContratoEmpresarialMarco" => $totalContratoEmpresarialMarco,
                "totalContratoEmpresarialAbril" => $totalContratoEmpresarialAbril,
                "totalContratoEmpresarialMaio" => $totalContratoEmpresarialMaio,
                "totalContratoEmpresarialJunho" => $totalContratoEmpresarialJunho,
                "totalContratoEmpresarialJulho" => $totalContratoEmpresarialJulho,
                "totalContratoEmpresarialAgosto" => $totalContratoEmpresarialAgosto,
                "totalContratoEmpresarialSetembro" => $totalContratoEmpresarialSetembro,
                "totalContratoEmpresarialOutubro" => $totalContratoEmpresarialOutubro,
                "totalContratoEmpresarialNovembro" => $totalContratoEmpresarialNovembro,
                "totalContratoEmpresarialDezembro" => $totalContratoEmpresarialDezembro


            ];









        }
    }






    public function index()
    {

        $mesAtualN = date('n');
        $mes_atual = date("m");
        $ano_atual = date("Y");

        $semestre = ($mesAtualN < 7) ? 1 : 2;
        $semestreAtual = "";
        if ($semestre == 1) {
            // Primeiro semestre (de janeiro a junho)
            $startDate  = $ano_atual . "-01-01";
            $endDate    = $ano_atual . "-06-30";
            $semestreAtual = "1/".date("Y");
        } else {
            // Segundo semestre (de julho a dezembro)
            $startDate  = $ano_atual . "-07-01";
            $endDate    = $ano_atual . "-12-31";
            $semestreAtual = "2/".date("Y");
        }

        $ranking_semestre = DB::connection('tenant')->select(
            "
            select
            users.name as usuario,
            users.image AS imagem,
            (
                       (select if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) from clientes
							  INNER JOIN contratos ON contratos.cliente_id = clientes.id

							  where clientes.user_id = comissoes.user_id AND
							  contratos.created_at BETWEEN '$startDate' AND '$endDate')
                       +
                       (select if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) from contrato_empresarial

							  where contrato_empresarial.user_id = comissoes.user_id AND
							  contrato_empresarial.created_at BETWEEN '$startDate' AND '$endDate')
            ) as quantidade,
            (
                       (select if(sum(valor_adesao)>0,sum(valor_adesao),0) from clientes
							  INNER JOIN contratos ON contratos.cliente_id = clientes.id

							  where clientes.user_id = comissoes.user_id AND
							  contratos.created_at BETWEEN '$startDate' AND '$endDate')
                       +
                       (select if(sum(valor_plano)>0,sum(valor_plano),0) from contrato_empresarial

							  where contrato_empresarial.user_id = comissoes.user_id AND
							  contrato_empresarial.created_at BETWEEN '$startDate' AND '$endDate')
            ) as valor
            from comissoes

            inner join users on users.id = comissoes.user_id

            group by user_id order by quantidade desc
            "
        );



        $ranking_ano = DB::connection('tenant')->select(
            "
            select
            users.name as usuario,
            users.image AS imagem,
            (
                       (select if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) from clientes
							  INNER JOIN contratos ON contratos.cliente_id = clientes.id
							  where clientes.user_id = comissoes.user_id AND
							  YEAR(contratos.created_at) = '$ano_atual')
                       +
                       (select if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) from contrato_empresarial
							  where contrato_empresarial.user_id = comissoes.user_id AND
							  YEAR(contrato_empresarial.created_at) = '$ano_atual')
            ) as quantidade,
            (
                       (select if(sum(valor_adesao)>0,sum(valor_adesao),0) from clientes
							  INNER JOIN contratos ON contratos.cliente_id = clientes.id

							  where clientes.user_id = comissoes.user_id AND
							  YEAR(contratos.created_at) = '$ano_atual')
                       +
                       (select if(sum(valor_plano)>0,sum(valor_plano),0) from contrato_empresarial

							  where contrato_empresarial.user_id = comissoes.user_id AND
							  YEAR(contrato_empresarial.created_at) = '$ano_atual')
            ) as valor
            from comissoes
            inner join users on users.id = comissoes.user_id

            group by user_id order by quantidade desc
            "
        );

        $data_atual = $mes_atual."/".$ano_atual;

        $users = User::where("id","!=",1)->where("ativo",1)->orderBy("name")->get();

        $ranking_mes = DB::connection('tenant')->select(
            "
            select
            users.name as usuario,
            users.image as image,
            users.image AS imagem,
            (
                       (select if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) from clientes
							  INNER JOIN contratos ON contratos.cliente_id = clientes.id

							  where clientes.user_id = comissoes.user_id AND
							  MONTH(contratos.created_at) = '$mes_atual' AND YEAR(contratos.created_at) = '$ano_atual')
                       +
                       (select if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) from contrato_empresarial

							  where contrato_empresarial.user_id = comissoes.user_id AND
							  MONTH(contrato_empresarial.created_at) = '$mes_atual' AND YEAR(contrato_empresarial.created_at) = '$ano_atual')
            ) as quantidade,
            (
                       (select if(sum(valor_adesao)>0,sum(valor_adesao),0) from clientes
							  INNER JOIN contratos ON contratos.cliente_id = clientes.id

							  where clientes.user_id = comissoes.user_id AND
							  MONTH(contratos.created_at) = '$mes_atual' AND YEAR(contratos.created_at) = '$ano_atual')
                       +
                       (select if(sum(valor_plano)>0,sum(valor_plano),0) from contrato_empresarial

							  where contrato_empresarial.user_id = comissoes.user_id AND
							  MONTH(contrato_empresarial.created_at) = '$mes_atual' AND YEAR(contrato_empresarial.created_at) = '$ano_atual')
            ) as valor
            from comissoes

            inner join users on users.id = comissoes.user_id

            group by user_id order by quantidade desc
            "
        );



        $semestreAtual = (date('n') <= 6) ? 1 : 2;
        $mesInicialSemestre = ($semestreAtual == 1) ? 1 : 7;
        $mesFinalSemestre = ($semestreAtual == 1) ? 6 : 12;
        $anoAtual = date("Y");

        $mesesSelect = DB::connection('tenant')->select(
            "
                SELECT *
                    FROM (
                        SELECT
                            DATE_FORMAT(created_at, '%m/%Y') AS month_date,
                            CONCAT(
                                CASE
                                    WHEN MONTH(created_at) = 1 THEN 'Janeiro'
                                    WHEN MONTH(created_at) = 2 THEN 'Fevereiro'
                                    WHEN MONTH(created_at) = 3 THEN 'Março'
                                    WHEN MONTH(created_at) = 4 THEN 'Abril'
                                    WHEN MONTH(created_at) = 5 THEN 'Maio'
                                    WHEN MONTH(created_at) = 6 THEN 'Junho'
                                    WHEN MONTH(created_at) = 7 THEN 'Julho'
                                    WHEN MONTH(created_at) = 8 THEN 'Agosto'
                                    WHEN MONTH(created_at) = 9 THEN 'Setembro'
                                    WHEN MONTH(created_at) = 10 THEN 'Outubro'
                                    WHEN MONTH(created_at) = 11 THEN 'Novembro'
                                    WHEN MONTH(created_at) = 12 THEN 'Dezembro'
                                END,
                                '/',
                                YEAR(created_at)
                            ) AS month_name_and_year,
                            YEAR(created_at) AS year,
                            MONTH(created_at) AS month
                        FROM contratos
                        WHERE created_at IS NOT NULL
                        GROUP BY YEAR(created_at), MONTH(created_at)
                    ) AS subquery
                    ORDER BY year DESC, month DESC;
          ");



        // Cache key
        $cacheKey = 'dashboard_data_now_home_' . date('Ym');
        // Consultas otimizadas usando Cache
        //now()->addHour()
        $data = Cache::remember($cacheKey, 0, function () use ($startDate,$ano_atual ,$endDate,$semestreAtual,$mesInicialSemestre,$mesFinalSemestre,$anoAtual) {
            return [
                'total_coletivo_quantidade_vidas' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",date("m"))->whereYear("contratos.created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",date("m"))->whereYear("contratos.created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'total_super_simples_quantidade_vidas' => ContratoEmpresarial::where("plano_id",5)
                    ->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'total_sindipao_quantidade_vidas' => ContratoEmpresarial::where("plano_id",6)
                    ->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'total_sindimaco_quantidade_vidas' => ContratoEmpresarial::where("plano_id",9)
                    ->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'total_sincofarma_quantidade_vidas' => ContratoEmpresarial::where("plano_id",13)
                    ->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'valor_adesao_col_ind' => Contrato::whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('valor_adesao'),
                'valor_plano_empresar' => ContratoEmpresarial::whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('valor_plano'),
                'total_valor' => Contrato::whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('valor_adesao')
                    +
                    ContratoEmpresarial::whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('valor_plano'),

                'total_individual' => Contrato::where("plano_id",1)->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('valor_adesao'),

                'total_coletivo' => Contrato::where("plano_id",3)->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('valor_adesao'),

                'total_ss' => ContratoEmpresarial::where('plano_id',5)
                    ->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('valor_plano'),

                'total_sindipao' => ContratoEmpresarial::where('plano_id',6)
                    ->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('valor_plano'),//Sindipão
                'total_sindimaco' => ContratoEmpresarial::where('plano_id',9)
                    ->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('valor_plano'),//Sindimaco
                'total_sincofarma' => ContratoEmpresarial::where('plano_id',13)
                    ->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('valor_plano'),

                'total_individual_semestre' => Contrato::where("plano_id", 1)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at", "<=", $mesFinalSemestre)
                    ->whereYear("created_at", date("Y"))
                    ->sum('valor_adesao'),


                'total_coletivo_semestre' => Contrato::where("plano_id", 3)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at", "<=", $mesFinalSemestre)
                    ->whereYear("created_at", date("Y"))
                    ->sum('valor_adesao'),


                'total_ss_semestre' => ContratoEmpresarial::where('plano_id', 5)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at", "<=", $mesFinalSemestre)
                    ->whereYear("created_at", date("Y"))
                    ->sum('valor_plano'),


                'total_sindipao_semestre' => ContratoEmpresarial::where('plano_id', 6)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at", "<=", $mesFinalSemestre)
                    ->whereYear("created_at", date("Y"))
                    ->sum('valor_plano'),


                'total_sindimaco_semestre' => ContratoEmpresarial::where('plano_id', 9)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at", "<=", $mesFinalSemestre)
                    ->whereYear("created_at", date("Y"))
                    ->sum('valor_plano'),


                'total_sincofarma_semestre' => ContratoEmpresarial::where('plano_id', 13)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at", "<=", $mesFinalSemestre)
                    ->whereYear("created_at", date("Y"))
                    ->sum('valor_plano'),

                'total_individual_quantidade_vidas_semestre' => Cliente::select("*")
                    ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
                    ->where("contratos.plano_id", 1)
                    ->whereMonth("contratos.created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("contratos.created_at", "<=", $mesFinalSemestre)
                    ->whereYear("contratos.created_at", date("Y"))
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_semestre' => Cliente::select("*")
                    ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
                    ->where("contratos.plano_id", 3)
                    ->whereMonth("contratos.created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("contratos.created_at", "<=", $mesFinalSemestre)
                    ->whereYear("contratos.created_at", date("Y"))
                    ->sum('quantidade_vidas'),


                'total_super_simples_quantidade_vidas_semestre' => ContratoEmpresarial::where("plano_id",5)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at","<=", $mesFinalSemestre)
                    ->whereYear("created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'total_sindipao_quantidade_vidas_semestre' => ContratoEmpresarial::where("plano_id",6)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at","<=", $mesFinalSemestre)
                    ->whereYear("created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'total_sindimaco_quantidade_vidas_semestre' => ContratoEmpresarial::where("plano_id",9)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at","<=", $mesFinalSemestre)
                    ->whereYear("created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'total_sincofarma_quantidade_vidas_semestre' => ContratoEmpresarial::where("plano_id",13)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at","<=", $mesFinalSemestre)
                    ->whereYear("created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'valor_adesao_col_ind_semestre' => Contrato::whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at", "<=", $mesFinalSemestre)
                    ->whereYear("created_at", date("Y"))
                    ->sum('valor_adesao'),


                'valor_plano_empresar_semestre' => ContratoEmpresarial::whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at", "<=", $mesFinalSemestre)
                    ->whereYear("created_at", date("Y"))
                    ->sum('valor_plano'),


                'total_valor_semestre' => Contrato::whereMonth("created_at", ">=", $mesInicialSemestre)
                        ->whereMonth("created_at", "<=", $mesFinalSemestre)
                        ->whereYear("created_at", date("Y"))
                        ->sum('valor_adesao') + ContratoEmpresarial::whereMonth("created_at", ">=", $mesInicialSemestre)
                        ->whereMonth("created_at", "<=", $mesFinalSemestre)
                        ->whereYear("created_at", date("Y"))
                        ->sum('valor_plano'),

                'total_individual_ano' => Contrato::where("plano_id", 1)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('valor_adesao'),


                'total_coletivo_ano' => Contrato::where("plano_id", 3)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('valor_adesao'),


                'total_ss_ano' => ContratoEmpresarial::where('plano_id', 5)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('valor_plano'),

                'total_sindipao_ano' => ContratoEmpresarial::where('plano_id', 6)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('valor_plano'),

                'total_sindimaco_ano' => ContratoEmpresarial::where('plano_id', 9)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('valor_plano'),

                'total_sincofarma_ano' => ContratoEmpresarial::where('plano_id', 13)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('valor_plano'),


                'total_individual_quantidade_vidas_ano' => Cliente::select("*")
                    ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
                    ->where("contratos.plano_id", 1)
                    ->whereYear("contratos.created_at", $anoAtual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_ano' => Cliente::select("*")
                    ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
                    ->where("contratos.plano_id", 3)
                    ->whereYear("contratos.created_at", $anoAtual)
                    ->sum('quantidade_vidas'),

                'total_super_simples_quantidade_vidas_ano' => ContratoEmpresarial::where("plano_id", 5)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('quantidade_vidas'),

                'total_sindipao_quantidade_vidas_ano' => ContratoEmpresarial::where("plano_id", 6)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('quantidade_vidas'),

                'total_sindimaco_quantidade_vidas_ano' => ContratoEmpresarial::where("plano_id", 9)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('quantidade_vidas'),

                'total_sincofarma_quantidade_vidas_ano' => ContratoEmpresarial::where("plano_id", 13)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('quantidade_vidas'),

                'valor_adesao_col_ind_ano' => Contrato::whereYear("created_at", $anoAtual)->sum('valor_adesao'),
                'valor_plano_empresar_ano' => ContratoEmpresarial::whereYear("created_at", $anoAtual)->sum('valor_plano'),
                'total_valor_ano' => Contrato::whereYear("created_at", $anoAtual)->sum('valor_adesao') + ContratoEmpresarial::whereYear("created_at", $anoAtual)->sum('valor_plano'),
                'totalContratoEmpresarial' => ContratoEmpresarial::whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('quantidade_vidas'),
                'totalClientes' => Cliente::select("*")->join("contratos","contratos.cliente_id","=","clientes.id")->whereMonth("contratos.created_at",date("m"))->whereYear("contratos.created_at",date("Y"))->sum('quantidade_vidas'),
                'totalGeralVidas' =>
                    ContratoEmpresarial::whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('quantidade_vidas')
                    +
                    Cliente::select("*")->join("contratos","contratos.cliente_id","=","clientes.id")->whereMonth("contratos.created_at",date("m"))->whereYear("contratos.created_at",date("Y"))->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_janeiro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",01)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_fevereiro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",02)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_marco' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",03)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_abril' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",04)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_maio' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",05)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_junho' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",06)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_julho' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",'07')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_agosto' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",'08')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_setembro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",'09')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_outubro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",10)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_novembro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",11)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_dezembro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",12)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_janeiro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",01)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_fevereiro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",02)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_marco' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",03)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),


                'total_individual_quantidade_vidas_abril' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",04)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_maio' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",05)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_junho' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",06)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_julho' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",07)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_agosto' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",'08')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_setembro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",'09')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_outubro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",'10')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_novembro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",'11')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_dezembro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",'12')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialJaneiro' => ContratoEmpresarial
                    ::whereMonth("created_at",01)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialFevereiro' => ContratoEmpresarial
                    ::whereMonth("created_at",02)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialMarco' => ContratoEmpresarial
                    ::whereMonth("created_at",03)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialAbril' => ContratoEmpresarial
                    ::whereMonth("created_at",04)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialMaio' => ContratoEmpresarial
                    ::whereMonth("created_at",05)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialJunho' => ContratoEmpresarial
                    ::whereMonth("created_at",06)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialJulho' => ContratoEmpresarial
                    ::whereMonth("created_at",07)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialAgosto' => ContratoEmpresarial
                    ::whereMonth("created_at",'08')
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialSetembro' => ContratoEmpresarial
                    ::whereMonth("created_at",'09')
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialOutubro' => ContratoEmpresarial
                    ::whereMonth("created_at",'10')
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialNovembro' => ContratoEmpresarial
                    ::whereMonth("created_at",'11')
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialDezembro' => ContratoEmpresarial
                    ::whereMonth("created_at",'12')
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas')

            ];
        });

        $dados_tabela = DB::connection('tenant')->select(
            "
            SELECT
                u.name AS user_name,
                SUM(CASE WHEN co.empresarial = 0 AND co.plano_id = 1 AND MONTH(c.created_at) = $mes_atual AND YEAR(c.created_at) = $ano_atual THEN cl.quantidade_vidas ELSE 0 END) AS individual,
                SUM(CASE WHEN co.empresarial = 0 AND co.plano_id = 3 AND MONTH(c.created_at) = $mes_atual AND YEAR(c.created_at) = $ano_atual THEN cl.quantidade_vidas ELSE 0 END) AS coletivo,
                SUM(CASE WHEN co.empresarial = 1 AND co.plano_id = 4 AND MONTH(ce.created_at) = $mes_atual AND YEAR(ce.created_at) = $ano_atual THEN ce.quantidade_vidas ELSE 0 END) AS pme,
                SUM(CASE WHEN co.empresarial = 1 AND co.plano_id = 5 AND MONTH(ce.created_at) = $mes_atual AND YEAR(ce.created_at) = $ano_atual THEN ce.quantidade_vidas ELSE 0 END) AS super_simples,
                SUM(CASE WHEN co.empresarial = 1 AND co.plano_id = 6 AND MONTH(ce.created_at) = $mes_atual AND YEAR(ce.created_at) = $ano_atual THEN ce.quantidade_vidas ELSE 0 END) AS sindipao,
                SUM(CASE WHEN co.empresarial = 1 AND co.plano_id = 9 AND MONTH(ce.created_at) = $mes_atual AND YEAR(ce.created_at) = $ano_atual THEN ce.quantidade_vidas ELSE 0 END) AS sindimaco,
                SUM(CASE WHEN co.empresarial = 1 AND co.plano_id = 13 AND MONTH(ce.created_at) = $mes_atual AND YEAR(ce.created_at) = $ano_atual THEN ce.quantidade_vidas ELSE 0 END) AS sincofarma,
                SUM(COALESCE(
                    CASE WHEN co.empresarial = 0 AND co.plano_id IN (1, 3) AND MONTH(c.created_at) = $mes_atual AND YEAR(c.created_at) = $ano_atual THEN cl.quantidade_vidas
                         WHEN co.empresarial = 1 AND co.plano_id IN (4, 5, 6, 9, 13) AND MONTH(ce.created_at) = $mes_atual AND YEAR(ce.created_at) = $ano_atual THEN ce.quantidade_vidas
                         ELSE 0
                    END, 0
                )) AS total
            FROM
                comissoes AS co
            LEFT JOIN
                contratos AS c ON c.id = co.contrato_id
            LEFT JOIN
                clientes AS cl ON cl.id = c.cliente_id
            LEFT JOIN
                contrato_empresarial AS ce ON ce.id = co.contrato_empresarial_id
            LEFT JOIN
                users AS u ON u.id = co.user_id
            WHERE
                u.ativo = 1
            GROUP BY
                u.id
            ORDER BY
                total DESC;
            ");

        return view('home.administrador',[
            "dados_tabela" => $dados_tabela,
            "users" => $users,
            "ranking_mes" => $ranking_mes,
            "ranking_semestre" => $ranking_semestre,
            "ano_atual" => $ano_atual,
            "ranking_ano" => $ranking_ano,
            "semestreAtual" => $semestreAtual,
            "quantidade_vidas" => $data['totalGeralVidas'],

            "total_coletivo_quantidade_vidas" => $data['total_coletivo_quantidade_vidas'],
            "total_individual_quantidade_vidas" => $data['total_individual_quantidade_vidas'],
            "total_super_simples_quantidade_vidas" => $data['total_super_simples_quantidade_vidas'],
            "total_sindipao_quantidade_vidas" => $data['total_sindipao_quantidade_vidas'],
            "total_sindimaco_quantidade_vidas" => $data['total_sindimaco_quantidade_vidas'],
            "total_sincofarma_quantidade_vidas" => $data['total_sincofarma_quantidade_vidas'],

            "total_valor" =>  $data['total_valor'],
            "total_coletivo" => $data['total_coletivo'],
            "total_individual" => $data['total_individual'],
            "total_super_simples" => $data['total_ss'],
            "total_sindipao" => $data['total_sindipao'],
            "total_sindimaco" => $data['total_sindimaco'],
            "total_sincofarma" => $data['total_sincofarma'],

            "total_individual_quantidade_vidas_semestre" => $data['total_individual_quantidade_vidas_semestre'],
            "total_coletivo_quantidade_vidas_semestre" => $data['total_coletivo_quantidade_vidas_semestre'],
            "total_super_simples_quantidade_vidas_semestre" => $data['total_super_simples_quantidade_vidas_semestre'],
            "total_sindipao_quantidade_vidas_semestre" => $data['total_sindipao_quantidade_vidas_semestre'],
            "total_sindimaco_quantidade_vidas_semestre" => $data['total_sindimaco_quantidade_vidas_semestre'],
            "total_sincofarma_quantidade_vidas_semestre" => $data['total_sincofarma_quantidade_vidas_semestre'],
            "total_quantidade_vidas_semestre" => $data['total_individual_quantidade_vidas_semestre'] + $data['total_coletivo_quantidade_vidas_semestre'] +
                $data['total_super_simples_quantidade_vidas_semestre'] + $data['total_sindipao_quantidade_vidas_semestre'] +
                $data['total_sindimaco_quantidade_vidas_semestre']
                + $data['total_sincofarma_quantidade_vidas_semestre'],

            "total_individual_ano" => $data['total_individual_ano'],
            "total_coletivo_ano" => $data['total_coletivo_ano'],
            "total_ss_ano" => $data['total_ss_ano'],
            "total_sindipao_ano" => $data['total_sindipao_ano'],
            "total_sindimaco_ano" => $data['total_sindimaco_ano'],
            "total_sincofarma_ano" => $data['total_sincofarma_ano'],
            "total_individual_quantidade_vidas_ano" => $data['total_individual_quantidade_vidas_ano'],
            "total_coletivo_quantidade_vidas_ano" => $data['total_coletivo_quantidade_vidas_ano'],
            "total_super_simples_quantidade_vidas_ano" => $data['total_super_simples_quantidade_vidas_ano'],
            "total_sindipao_quantidade_vidas_ano" => $data['total_sindipao_quantidade_vidas_ano'],
            "total_sindimaco_quantidade_vidas_ano" => $data['total_sindimaco_quantidade_vidas_ano'],
            "total_sincofarma_quantidade_vidas_ano" => $data['total_sincofarma_quantidade_vidas_ano'],
            "total_quantidade_vidas_ano" => $data['total_individual_quantidade_vidas_ano'] + $data['total_coletivo_quantidade_vidas_ano'] +
                $data['total_super_simples_quantidade_vidas_ano']
                + $data['total_sindipao_quantidade_vidas_ano'] + $data['total_sindimaco_quantidade_vidas_ano'] + $data['total_sincofarma_quantidade_vidas_ano'],
            "valor_adesao_col_ind_ano" => $data['valor_adesao_col_ind_ano'],
            "valor_plano_empresar_ano" => $data['valor_plano_empresar_ano'],
            "total_valor_ano" => $data['total_valor_ano'],
            "total_individual_semestre" => $data['total_individual_semestre'],
            "total_coletivo_semestre" => $data['total_coletivo_semestre'],
            "total_ss_semestre" => $data['total_ss_semestre'],
            "total_sindipao_semestre" => $data['total_sindipao_semestre'],
            "total_sindimaco_semestre" => $data['total_sindimaco_semestre'],
            "total_sincofarma_semestre" => $data['total_sincofarma_semestre'],
            "total_valor_semestre" => $data['total_valor_semestre'],
            "total_coletivo_quantidade_vidas_janeiro" => $data['total_coletivo_quantidade_vidas_janeiro'],
            "total_coletivo_quantidade_vidas_fevereiro" => $data['total_coletivo_quantidade_vidas_fevereiro'],
            "total_coletivo_quantidade_vidas_marco" => $data['total_coletivo_quantidade_vidas_marco'],
            "total_coletivo_quantidade_vidas_abril" => $data['total_coletivo_quantidade_vidas_abril'],
            "total_coletivo_quantidade_vidas_maio" => $data['total_coletivo_quantidade_vidas_maio'],
            "total_coletivo_quantidade_vidas_junho" => $data['total_coletivo_quantidade_vidas_junho'],
            "total_coletivo_quantidade_vidas_julho" => $data['total_coletivo_quantidade_vidas_julho'],
            "total_coletivo_quantidade_vidas_agosto" => $data['total_coletivo_quantidade_vidas_agosto'],
            "total_coletivo_quantidade_vidas_setembro" => $data['total_coletivo_quantidade_vidas_setembro'],
            "total_coletivo_quantidade_vidas_outubro" => $data['total_coletivo_quantidade_vidas_outubro'],
            "total_coletivo_quantidade_vidas_novembro" => $data['total_coletivo_quantidade_vidas_novembro'],
            "total_coletivo_quantidade_vidas_dezembro" => $data['total_coletivo_quantidade_vidas_dezembro'],
            "total_individual_quantidade_vidas_janeiro" => $data['total_individual_quantidade_vidas_janeiro'],
            "total_individual_quantidade_vidas_fevereiro" => $data['total_individual_quantidade_vidas_fevereiro'],
            "total_individual_quantidade_vidas_marco" => $data['total_individual_quantidade_vidas_marco'],
            "total_individual_quantidade_vidas_abril" => $data['total_individual_quantidade_vidas_abril'],
            "total_individual_quantidade_vidas_maio" => $data['total_individual_quantidade_vidas_maio'],
            "total_individual_quantidade_vidas_junho" => $data['total_individual_quantidade_vidas_junho'],
            "total_individual_quantidade_vidas_julho" => $data['total_individual_quantidade_vidas_julho'],
            "total_individual_quantidade_vidas_agosto" => $data['total_individual_quantidade_vidas_agosto'],
            "total_individual_quantidade_vidas_setembro" => $data['total_individual_quantidade_vidas_setembro'],
            "total_individual_quantidade_vidas_outubro" => $data['total_individual_quantidade_vidas_outubro'],
            "total_individual_quantidade_vidas_novembro" => $data['total_individual_quantidade_vidas_novembro'],
            "total_individual_quantidade_vidas_dezembro" => $data['total_individual_quantidade_vidas_dezembro'],
            "totalContratoEmpresarialJaneiro" => $data['totalContratoEmpresarialJaneiro'],
            "totalContratoEmpresarialFevereiro" => $data['totalContratoEmpresarialFevereiro'],
            "totalContratoEmpresarialMarco" => $data['totalContratoEmpresarialMarco'],
            "totalContratoEmpresarialAbril" => $data['totalContratoEmpresarialAbril'],
            "totalContratoEmpresarialMaio" => $data['totalContratoEmpresarialMaio'],
            "totalContratoEmpresarialJunho" => $data['totalContratoEmpresarialJunho'],
            "totalContratoEmpresarialJulho" => $data['totalContratoEmpresarialJulho'],
            "totalContratoEmpresarialAgosto" => $data['totalContratoEmpresarialAgosto'],
            "totalContratoEmpresarialSetembro" => $data['totalContratoEmpresarialSetembro'],
            "totalContratoEmpresarialOutubro" => $data['totalContratoEmpresarialOutubro'],
            "totalContratoEmpresarialNovembro" => $data['totalContratoEmpresarialNovembro'],
            "totalContratoEmpresarialDezembro" => $data['totalContratoEmpresarialDezembro'],
            "data_atual" => $data_atual,
            "mesesSelect" => $mesesSelect
        ]);
    }

}
