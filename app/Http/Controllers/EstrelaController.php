<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstrelaController extends Controller
{
    public function index()
    {
        $mesAtualN = date('n');
        $mes_atual = date("m");
        $ano_atual = date("Y");



        $semestre = ($mesAtualN < 7) ? 1 : 2;
        $semestreAtual = "";
        if ($semestre == 1) {


            $mesAtualN = date('n');
            $ano_atual = date("Y");

            $semestre = ($mesAtualN < 7) ? 1 : 2;
            $startDate = ($semestre == 1) ? "$ano_atual-01-01" : "$ano_atual-07-01";
            $endDate = ($semestre == 1) ? "$ano_atual-06-30" : "$ano_atual-12-31";
            $semestreAtual = "$semestre/$ano_atual";





            $ranking_semestre = DB::connection('tenant')->select("
        SELECT
            users.name AS usuario,
            users.image AS imagem,
            COALESCE(SUM(IF(MONTH(contratos.created_at) = 1, clientes.quantidade_vidas, 0)), 0) AS janeiro,
            COALESCE(SUM(IF(MONTH(contratos.created_at) = 2, clientes.quantidade_vidas, 0)), 0) AS fevereiro,
            COALESCE(SUM(IF(MONTH(contratos.created_at) = 3, clientes.quantidade_vidas, 0)), 0) AS marco,
            COALESCE(SUM(IF(MONTH(contratos.created_at) = 4, clientes.quantidade_vidas, 0)), 0) AS abril,
            COALESCE(SUM(IF(MONTH(contratos.created_at) = 5, clientes.quantidade_vidas, 0)), 0) AS maio,
            COALESCE(SUM(IF(MONTH(contratos.created_at) = 6, clientes.quantidade_vidas, 0)), 0) AS junho,
            COALESCE(SUM(clientes.quantidade_vidas), 0) AS quantidade,
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
        WHERE contratos.created_at BETWEEN '$startDate' AND '$endDate' AND contratos.plano_id = 1
        GROUP BY comissoes.user_id, users.name, users.image
        ORDER BY quantidade DESC
    ");


        } else {

            // Segundo semestre (de julho a dezembro)
            $startDate = $ano_atual . "-07-01";
            $endDate = $ano_atual . "-12-31";
            $semestreAtual = "2/".date("Y");
            $ranking_semestre = DB::connection('tenant')->select("
        SELECT
            users.name AS usuario,
            users.image AS imagem,
            COALESCE(SUM(IF(MONTH(contratos.created_at) = 7, clientes.quantidade_vidas, 0)), 0) AS julho,
            COALESCE(SUM(IF(MONTH(contratos.created_at) = 8, clientes.quantidade_vidas, 0)), 0) AS agosto,
            COALESCE(SUM(IF(MONTH(contratos.created_at) = 9, clientes.quantidade_vidas, 0)), 0) AS setembro,
            COALESCE(SUM(IF(MONTH(contratos.created_at) = 10, clientes.quantidade_vidas, 0)), 0) AS outubro,
            COALESCE(SUM(IF(MONTH(contratos.created_at) = 11, clientes.quantidade_vidas, 0)), 0) AS novembro,
            COALESCE(SUM(IF(MONTH(contratos.created_at) = 12, clientes.quantidade_vidas, 0)), 0) AS dezembro,
            COALESCE(SUM(clientes.quantidade_vidas), 0) AS quantidade,
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
        WHERE contratos.created_at BETWEEN '$startDate' AND '$endDate' AND contratos.plano_id = 1
        GROUP BY comissoes.user_id, users.name, users.image
        ORDER BY quantidade DESC
    ");

        }





        return view("estrela.index",[
            "ranking" => $ranking_semestre,
            "semestre" => $semestre,
            "ano_atual" => $ano_atual
        ]);
    }

}
