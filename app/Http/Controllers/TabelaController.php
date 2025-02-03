<?php

namespace App\Http\Controllers;

use App\Models\Acomodacao;
use App\Models\Administradora;
use App\Models\AdministradoraPlano;
use App\Models\FaixaEtaria;
use App\Models\Pdf;
use App\Models\PdfExcecao;
use App\Models\Plano;
use App\Models\Tabela;
use App\Models\TabelaOrigens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TabelaController extends Controller
{
    public function index()
    {
        $cidades = TabelaOrigens::all();
        $administradoras = Administradora::all();
        $planos = Plano::all();
        return view('tabela.index', compact('cidades', 'administradoras','planos'));

    }

    public function planosAdministradoraSelect(Request $request)
    {

        $administradora = $request->administradora;
        $dados = AdministradoraPlano::where("administradora_id",$administradora);
        if($dados->count() >= 1) {
            $ids = $dados->pluck('plano_id')->unique();
            $planos = Plano::whereIn("id",$ids)->select('id','nome')->get();
            return $planos;
        } else {
            return "nada";
        }
    }

    public function cadastrarValoresTabela(Request $request)
    {

        foreach($request->valoresApartamento as $k => $v) {
            $tabela = new Tabela();

            $tabela->administradora_id = $request->administradora;
            $tabela->plano_id = $request->planos;
            $tabela->tabela_origens_id = $request->tabela_origem;
            $tabela->acomodacao_id = 1;

            $tabela->coparticipacao = ($request->coparticipacao == "sim" ? true : false);
            $tabela->odonto = ($request->odonto == "sim" ? true : false);
            $tabela->faixa_etaria_id = $k + 1;
            $tabela->valor = str_replace([".",","],["","."],$request->valoresApartamento[$k]);

            if(!$tabela->save()) {
                return "error";
            }
        }

        foreach($request->valoresEnfermaria as $k => $v) {
            $tabela = new Tabela();

            $tabela->administradora_id = $request->administradora;
            $tabela->plano_id = $request->planos;
            $tabela->tabela_origens_id = $request->tabela_origem;
            $tabela->acomodacao_id = 2;

            $tabela->coparticipacao = ($request->coparticipacao == "sim" ? true : false);
            $tabela->odonto = ($request->odonto == "sim" ? true : false);

            $tabela->faixa_etaria_id = $k + 1;
            $tabela->valor = str_replace([".",","],["","."],$request->valoresEnfermaria[$k]);

            if(!$tabela->save()) {
                return "error";
            }
        }

        foreach($request->valoresAmbulatorial as $k => $v) {
            $tabela = new Tabela();

            $tabela->administradora_id = $request->administradora;
            $tabela->plano_id = $request->planos;
            $tabela->tabela_origens_id = $request->tabela_origem;
            $tabela->acomodacao_id = 3;

            $tabela->coparticipacao = ($request->coparticipacao == "sim" ? true : false);
            $tabela->odonto = ($request->odonto == "sim" ? true : false);

            $tabela->faixa_etaria_id = $k + 1;
            $tabela->valor = str_replace([".",","],["","."],$request->valoresAmbulatorial[$k]);


            if(!$tabela->save()) {
                return "error";
            }
        }

        return "sucesso";


    }





    public function verificarValoresTabela(Request $request)
    {

        $administradora_id = $request->administradora;
        $plano_id = $request->planos;
        $tabela_origem_id = $request->tabela_origem;
        $coparticipacao = $request->coparticipacao == "sim" ? 1 : 0;
        $odonto = $request->odonto == "sim" ? 1 : 0;


        $tabela = DB::connection('grupoamerica')->table('tabelas')
            ->where("administradora_id",$administradora_id)
            ->where("tabela_origens_id",$tabela_origem_id)
            ->where("plano_id",$plano_id)
            ->where("coparticipacao",$coparticipacao)
            ->where("odonto",$odonto)
            ->select("acomodacao_id", "valor","id")
            ->get();



        if($tabela->count() >= 1) {
            $ta = $tabela->map(function ($item) {

                $item->valor_formatado = number_format($item->valor, 2, ',', '.');
                return $item;
            });
            return $ta;
        } else {
            return "empty";
        }


    }



    public function mudarValorTabela(Request $request)
    {

        $ta = Tabela::on('grupoamerica')->find($request->id);

        if ($ta) {
            $ta->valor = str_replace([".", ","], ["", "."], $request->valor);
            $ta->save();
        }
    }




    public function tabela_preco()
    {
        $administradoras = DB::connection('grupoamerica')->table('administradoras')->get();
        $planos = DB::connection('grupoamerica')->table('planos')->get();
        $acomodacao = DB::connection('grupoamerica')->table('acomodacoes')->get();
        $faixas = DB::connection('grupoamerica')->table('faixa_etarias')->get();
        $tabela_origem = DB::connection('grupoamerica')->table('tabela_origens')->get();

        return view('tabela.tabela',[
            "administradoras" => $administradoras,
            "planos" => $planos,
            "acomodacao" => $acomodacao,
            "faixas" => $faixas,
            "tabela_origem" => $tabela_origem
        ]);
    }

    public function cadastrarCoparticipacaoExcecao(Request $request)
    {
        PdfExcecao::updateOrCreate(
            [
                'plano_id' => $request->plano_coparticipacao,
                'tabela_origens_id' => $request->cidade_coparticipacao,
            ],
            [
                'linha01' => $request->linha01,
                'linha02' => $request->linha02,
                'linha03' => $request->linha03,

                'consultas_eletivas_total' => $request->consultas_eletiva_total_excecao,
                'pronto_atendimento' => $request->pronto_atendimento_excecao_total,

                'faixa_1' => $request->faixa_01,
                'faixa_2' => $request->faixa_02,
                'faixa_3' => $request->faixa_03,
                'faixa_4' => $request->faixa_04




            ]
        );

    }







    public function cadastrarCoparticipacao(Request $request)
    {
        Pdf::updateOrCreate(
            [
                'plano_id' => $request->plano_coparticipacao,
                'tabela_origens_id' => $request->cidade_coparticipacao,
            ],
            [
                'linha01' => $request->linha01,
                'linha02' => $request->linha02,
                'linha03' => $request->linha03,

                'consultas_eletivas_total' => $request->consultas_eletiva_total,
                'consultas_de_urgencia_total' => $request->consultas_urgencia_total,
                'exames_simples_total' => $request->exames_simples_total,
                'exames_complexos_total' => $request->exames_complexos_total,
                'terapias_especiais_total' => $request->terapias_especiais_total,
                'demais_terapias_total' => $request->demais_terapias_total,
                'internacoes_total' => $request->internacoes_total,
                'cirurgia_total' => $request->cirurgia_total,

                'consultas_eletivas_parcial' => $request->consultas_eletiva_parcial,
                'consultas_de_urgencia_parcial' => $request->consultas_urgencia_parcial,
                'exames_simples_parcial' => $request->exames_simples_parcial,
                'exames_complexos_parcial' => $request->exames_complexos_parcial,
                'terapias_especiais_parcial' => $request->terapias_especiais_parcial,
                'demais_terapias_parcial' => $request->demais_terapias_parcial,
                'internacoes_parcial' => $request->internacoes_parcial,
                'cirurgia_parcial' => $request->cirurgia_parcial,


            ]
        );

    }

    public function coparticipacaoJaExiste(Request $request)
    {
        $plano = $request->planoSelecionado;
        $cidade = $request->cidadeSelecionada;


        if($cidade != 3 && $cidade != 4 && $cidade != 5 && $cidade != 6 && $cidade != 7) {

            // Primeiro verifica plano_id e tabela_origens_id
            $pdf = Pdf::where('plano_id', $plano)->where('tabela_origens_id', $cidade);
            if ($pdf->exists()) {
                return $pdf->first();
            }
            // Caso não encontre, busca apenas por plano_id
            $pdf = Pdf::where('plano_id', $plano);
            if ($pdf->exists()) {
                return $pdf->first();
            }
            return "nada";

        } else {

            $pdf = PdfExcecao::where('plano_id', $plano)->where('tabela_origens_id', $cidade);
            if ($pdf->exists()) {
                return $pdf->first();
            }
            // Caso não encontre, busca apenas por plano_id
            $pdf = Pdf::where('plano_id', $plano);
            if ($pdf->exists()) {
                return $pdf->first();
            }
            return "nada";


        }




    }






    public function orcamento(Request $request)
    {
        $ambulatorial = $request->ambulatorial;
        $sql = "";
        $chaves = [];
        foreach(request()->faixas[0] as $k => $v) {
            if($v != null AND $v != 0) {
                $sql .= " WHEN tabelas.faixa_etaria_id = {$k} THEN ${v} ";
                $chaves[] = $k;
            }
        }
        $keys = implode(",",$chaves);
        $cidade = request()->tabela_origem;
        $plano = request()->plano;
        $operadora = request()->operadora;
        $imagem_operadora = Administradora::find($operadora)->logo;
        $plano_nome = Plano::find($plano)->nome;
        $imagem_plano = Administradora::find($operadora)->logo;
        $cidade_nome = TabelaOrigens::find($cidade)->nome;
        if($ambulatorial == 0) {
            $dados = Tabela::select('tabelas.*')
                ->selectRaw("CASE $sql END AS quantidade")
                ->join('faixa_etarias', 'faixa_etarias.id', '=', 'tabelas.faixa_etaria_id')
                ->where('tabelas.tabela_origens_id', $cidade)
                ->where('tabelas.plano_id', $plano)
                ->where('tabelas.administradora_id', $operadora)
                //->where('acomodacao_id',"!=",3)
                ->whereIn('tabelas.faixa_etaria_id', explode(',', $keys))
                ->get();
            $status = $dados->contains('odonto', 0);
            return view("cotacao.cotacao2-tabela",[
                "dados" => $dados,
                "operadora" => $imagem_operadora,
                "plano_nome" => $plano_nome,
                "cidade_nome" => $cidade_nome,
                "imagem_plano" => $imagem_plano,
                "status" => $status
            ]);
        } else {
            $dados = Tabela::select('tabelas.*')
                ->selectRaw("CASE $sql END AS quantidade")
                ->join('faixa_etarias', 'faixa_etarias.id', '=', 'tabelas.faixa_etaria_id')
                ->where('tabelas.tabela_origens_id', $cidade)
                ->where('tabelas.plano_id', $plano)
                ->where('tabelas.administradora_id', $operadora)
                ->where('acomodacao_id',"=",3)
                ->whereIn('tabelas.faixa_etaria_id', explode(',', $keys))
                ->get();
            //return $dados;
            $status = $dados->contains('odonto', 0);
            return view("cotacao.cotacao-ambulatorial-tabela",[
                "dados" => $dados,
                "operadora" => $imagem_operadora,
                "plano_nome" => $plano_nome,
                "cidade_nome" => $cidade_nome,
                "imagem_plano" => $imagem_plano,
                "status" => $status
            ]);
        }



    }


}
