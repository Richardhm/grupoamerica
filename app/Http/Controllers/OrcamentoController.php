<?php

namespace App\Http\Controllers;

use App\Models\Administradora;
use App\Models\Desconto;
use App\Models\Layout;
use App\Models\Pdf;
use App\Models\Plano;
use App\Models\Tabela;
use App\Models\TabelaOrigens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrcamentoController extends Controller
{
    public function index()
    {
        $estados = TabelaOrigens::groupBy("uf")->get();
        if(auth()->user()->corretora_id == 1) {
            $administradoras = Administradora::where("id","!=",5)->get();
        } else {
            $administradoras = Administradora::all();
        }
        $planos = Plano::all();
        $ufpreferencia = auth()->user()->uf_preferencia ?? '';

        return view('orcamento.index', compact('estados', 'administradoras','planos','ufpreferencia'));
    }

    public function getCidadesDeOrigem(Request $request)
    {
        $uf = $request->input('uf');
        $cidades = \DB::table('tabela_origens')
            ->where('uf', $uf)
            ->select('id', 'nome')
            ->orderBy('nome')
            ->get();

        return response()->json($cidades);
    }

    public function filtrarAdministradora(Request $request)
    {
        $cidade = $request->cidade;

//        $administradora_id = DB::table('tabelas')
//            ->select('administradora_id')
//            ->where('tabela_origens_id', $cidade)
//            ->groupBy('administradora_id')
//            ->get();

        $administradoraIds = DB::table('tabelas')
            ->select('administradora_id')
            ->where('tabela_origens_id', $cidade)
            ->groupBy('administradora_id')
            ->pluck('administradora_id');
        $operadoras = Administradora::whereIn('id', $administradoraIds)
            //->where('cidade', $cidade)
            ->get();


        //$operadoras = Administradora::where('cidade', $cidade)->get();
        return response()->json($operadoras);
    }

    public function select(Request $request)
    {
        $user = auth()->user();
        $user->layout_id = $request->input('valor');

        if($user->save()) {
            return "sucesso";
        } else {
            return "error";
        }

    }




    public function buscar_planos(Request $request)
    {
        $administradora_id = $request->input('administradora_id');
        $tabela_origens_id = $request->input('tabela_origens_id');
        $planos = DB::table('administradora_planos')
            ->where('administradora_id', $administradora_id)
            ->where('tabela_origens_id', $tabela_origens_id)
            ->pluck('plano_id');
        return response()->json(['planos' => $planos]);
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
                ->orderBy('tabelas.faixa_etaria_id')
                ->get();

            $desconto = Desconto::where("tabela_origens_id",$cidade)->where("plano_id",$plano)->where("administradora_id",$operadora)->count();
            $status_desconto = 0;
            if($desconto == 1) {
                $status_desconto = 1;
            }


                $status = $dados->contains('odonto', 0);
                return view("cotacao.cotacao2",[
                    "dados" => $dados,
                    "operadora" => $imagem_operadora,
                    "plano_nome" => $plano_nome,
                    "cidade_nome" => $cidade_nome,
                    "imagem_plano" => $imagem_plano,
                    "status" => $status,
                    "status_desconto" => $status_desconto
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

            $desconto = Desconto::where("tabela_origens_id",$cidade)->where("plano_id",$plano)->where("administradora_id",$operadora)->count();
            $status_desconto = 0;
            if($desconto == 1) {
                $status_desconto = 1;
            }

            return view("cotacao.cotacao-ambulatorial",[
                "dados" => $dados,
                "operadora" => $imagem_operadora,
                "plano_nome" => $plano_nome,
                "cidade_nome" => $cidade_nome,
                "imagem_plano" => $imagem_plano,
                "status" => $status,
                "status_desconto" => $status_desconto
            ]);
        }


    }

    public function getLayout(Request $request)
    {
        $user = Auth::user();
        $layouts = Layout::all();
        $estados = TabelaOrigens::groupBy('uf')->select('uf')->get();
        return view('orcamento.layouts',compact('layouts','user','estados'));
    }


    public function regiao(Request $request)
    {
        $user = Auth::user(); // ou auth()->user()
        $uf = $request->input('regiao');
        $user->uf_preferencia = $uf ?: null;
        if($user->save()) {
            return true;
        } else {
            return false;
        }
    }




}
