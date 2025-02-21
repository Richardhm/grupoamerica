<?php

namespace App\Http\Controllers;

use App\Models\Acomodacao;
use App\Models\Administradora;
use App\Models\cidadeCodigoVendedor;
use App\Models\Cliente;
use App\Models\ClienteEstagiario;
use App\Models\Comissoes;
use App\Models\ComissoesCorretoraConfiguracoes;
use App\Models\ComissoesCorretoraLancadas;
use App\Models\ComissoesCorretoresConfiguracoes;
use App\Models\ComissoesCorretoresDefault;
use App\Models\ComissoesCorretoresLancadas;
use App\Models\Contrato;
use App\Models\ContratoEmpresarial;
use App\Models\Dependente;
use App\Models\MotivoCancelado;
use App\Models\Odonto;
use App\Models\Plano;
use App\Models\TabelaOrigens;
use App\Models\User;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FinanceiroController extends Controller
{
    public function __construct()
    {


        //return $this->middleware(["can:configuracoes"]);
    }

    public function storeOdonto(Request $request)
    {
        $odonto = new Odonto();
        $odonto->user_id = $request->user_id;
        $odonto->nome = $request->nome;
        $valorFormatado = str_replace(",", ".", str_replace(".", "", $request->valor));
        $odonto->valor = floatval($valorFormatado);
        $comissao = ($odonto->valor * 50) / 100;
        $odonto->comissao = floatval($comissao);
        $odonto->save();
        return true;
    }

    public function listarOdonto()
    {
        $resultado = DB::table('odonto')
            ->join('users', 'users.id', '=', 'odonto.user_id')
            ->select(
                DB::raw("DATE_FORMAT(odonto.created_at, '%d/%m/%Y') as created_at"),
                'nome',
                'valor',
                'users.name as usuario'

            )->get();

        return $resultado;

        //return response()->json(['data' => $resultado]);
    }




    public function index(Request $request)
    {
        $corretora_id = auth()->user()->corretora_id;
        $users = User::where("corretora_id",$corretora_id)
            ->where("name" ,"!=",'Administrador')
            ->whereRaw("name != ''")
            ->get();
        return view('financeiro.index',[
            "users" => $users
        ]);
    }

    public function financeiroCorretoraChange()
    {
        $corretora_id = request()->corretora_id;
        return $corretora_id;
    }

    public function geralIndividualPendentes(Request $request)
    {
        $corretora_id = $request->corretora_id == null ? auth()->user()->corretora_id : $request->corretora_id;

        $mes = $request->mes != '00' && isset($request->mes) ? $request->mes : null;

        // Definir uma chave de cache baseada nos parâmetros da requisição
        $cacheKey = 'geralIndividualPendentes_' . $corretora_id . '_' . ($mes ?? 'todos_meses');
        $tempoDeExpiracao = 900; // Cache por 15 minutos

        if($request->has('refresh') && $request->refresh == 1) {
            Cache::forget($cacheKey); // Limpar o cache para esta chave
        }

        // Verificar se o resultado já está no cache ou executar a consulta
        $resultado = Cache::remember($cacheKey,$tempoDeExpiracao,function() use ($corretora_id, $mes) {
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
                    'clientes.corretora_id as corretora_id',
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

            // Filtros opcionais
            if ($mes) {
                $query->whereMonth('contratos.created_at', $mes);
            }

            if($corretora_id != 0) {
                $query->where('clientes.corretora_id', $corretora_id);
            }

            $query->where('contratos.plano_id', 1);

            // Executar a consulta e retornar o resultado
            return $query->groupBy('comissoes_corretores_lancadas.comissoes_id')->get();
        });

        // Retornar o resultado como JSON
        return response()->json(['data' => $resultado,'refresh' => $request->refresh]);
    }


    public function formCreateEmpresarial()
    {

        $users = User::where("id","!=",1)->where('ativo',1)->where('corretora_id',auth()->user()->corretora_id)->get();
        $plano_empresarial = Plano::where("empresarial",1)->get();
        $tabela_origem = TabelaOrigens::all();
        return view('financeiro.cadastrar-empresa',[
            "users" => $users,
            "planos_empresarial" => $plano_empresarial,
            "origem_tabela" =>  $tabela_origem
        ]);
    }
    public function storeEmpresarialFinanceiro(Request $request)
    {
        $corretora_id = User::find($request->user_id)->corretora_id;
        $codigo_vendedor = User::find($request->user_id)->codigo_vendedor;
        $clt = User::find($request->user_id)->clt;

        $dados = $request->except('_token');
        $dados['taxa_adesao'] = str_replace([".", ","], ["", "."], $request->taxa_adesao);
        $dados['desconto_corretor'] = $request->desconto_corretor != "" ? str_replace([".", ","], ["", "."], $request->desconto_corretor) : 0;
        $dados['desconto_corretora'] = $request->desconto_corretora != "" ? str_replace([".", ","], ["", "."], $request->desconto_corretora) : 0;
        $dados['data_baixa'] = date('Y-m-d');
        $dados['codigo_vendedor'] = $codigo_vendedor;
        $dados['valor_plano'] = str_replace([".", ","], ["", "."], $request->valor_plano);
        $dados['valor_plano_saude'] = str_replace([".", ","], ["", "."], $request->valor_plano_saude);
        $dados['valor_plano_odonto'] = str_replace([".", ","], ["", "."], $request->valor_plano_odonto);
        $dados['valor_plano'] = $dados['valor_plano_saude'] + $dados['valor_plano_odonto'];
        $dados['valor_total'] = $dados['valor_plano'] + $dados['taxa_adesao'];
        $dados['valor_boleto'] = str_replace([".", ","], ["", "."], $request->valor_boleto);
        $dados['data_boleto'] = date('Y-m-d', strtotime($request->data_boleto));
        $dados['corretora_id'] = $corretora_id;
        $dados['created_at'] = $request->created_at;
        $dados['financeiro_id'] = 1;
        $dados['desconto_operadora'] = 0;
        $dados['quantidade_parcelas'] = 0;


        if($request->desconto_operadora && $request->quantidade_parcelas) {
            $dados['desconto_operadora'] = $request->desconto_operadora;
            $dados['quantidade_parcelas'] = $request->quantidade_parcelas;
        }
        $valor = $dados['valor_plano'];
        $contrato = ContratoEmpresarial::create($dados);
        $comissao = new Comissoes();
        $comissao->contrato_empresarial_id = $contrato->id;
        // $comissao->cliente_id = $contrato->cliente_id;
        $comissao->user_id = $request->user_id;
        $comissao->corretora_id = $corretora_id;
        // $comissao->status = 1;
        $comissao->plano_id = $request->plano_id;
        $comissao->administradora_id = 4;
        $comissao->tabela_origens_id = $request->tabela_origens_id;
        $comissao->data = date('Y-m-d');
        $comissao->empresarial = 1;
        $comissao->save();

        $date = new \DateTime(now());
        $date->add(new \DateInterval('PT1M'));
        $data = $date->format('Y-m-d H:i:s');
        $comissao_corretor_contagem = 0;
        $valorComDesconto = 0;
        $comissao_corretor_default = 0;

        if($clt == 1) {
            $dado = ComissoesCorretoresDefault
                ::where("plano_id", $request->plano_id)
                ->where("administradora_id", 4)
                ->where('corretora_id',$corretora_id)
                ->get();
            foreach ($dado as $c) {
                $comissaoVendedor = new ComissoesCorretoresLancadas();
                $comissaoVendedor->comissoes_id = $comissao->id;
                $comissaoVendedor->parcela = $c->parcela;
                if ($comissao_corretor_default == 0) {
                    $comissaoVendedor->data = date('Y-m-d H:i:s', strtotime($request->data_boleto));


                } else {
                    $comissaoVendedor->data = date("Y-m-d H:i:s", strtotime($request->data_boleto . "+{$comissao_corretor_default}month"));
                    $date = new \DateTime($data);
                    $date->add(new \DateInterval("PT{$comissao_corretor_default}M"));
                    //$data_add = $date->format('Y-m-d H:i:s');
                }

                if($dados['quantidade_parcelas'] >= 1 && $dados['desconto_operadora'] >= 0) {
                    if($comissao_corretor_default <= $dados['quantidade_parcelas']) {
                        $valorComDesconto = ($valor * (1 - $dados['desconto_operadora'] / 100)) * $c->valor / 100;
                    }
                } else {
                    $valorComDesconto = ($valor * $c->valor) / 100;
                }
                $comissaoVendedor->valor = $valorComDesconto;
                $comissaoVendedor->save();
                $comissao_corretor_default++;
            }
        } else {
            $comissoes_configuradas_corretor = ComissoesCorretoresConfiguracoes
                ::where("plano_id", $request->plano_id)
                ->where("administradora_id", 4)
                ->where("user_id", $request->user_id)
                ->get();
            if (count($comissoes_configuradas_corretor) >= 1) {
                foreach ($comissoes_configuradas_corretor as $c) {
                    $comissaoVendedor = new ComissoesCorretoresLancadas();
                    $comissaoVendedor->comissoes_id = $comissao->id;
                    //$comissaoVendedor->user_id = auth()->user()->id;
                    $comissaoVendedor->parcela = $c->parcela;
                    if ($comissao_corretor_contagem == 0) {
                        $comissaoVendedor->data = date('Y-m-d H:i:s', strtotime($request->data_boleto));
                        //$comissaoVendedor->tempo = $data;
                    } else {
                        $comissaoVendedor->data = date("Y-m-d H:i:s", strtotime($request->data_boleto . "+{$comissao_corretor_contagem}month"));
                        $date = new \DateTime($data);
                        $date->add(new \DateInterval("PT{$comissao_corretor_contagem}M"));
                        $data_add = $date->format('Y-m-d H:i:s');
                        //$comissaoVendedor->tempo = $data_add;
                    }
                    if($dados['quantidade_parcelas'] >= 1 && $dados['desconto_operadora'] >= 0) {
                        if($comissao_corretor_contagem <= $dados['quantidade_parcelas']) {
                            $valorComDesconto = ($valor * (1 - $dados['desconto_operadora'] / 100)) * $c->valor / 100;
                        }
                    } else {
                        $valorComDesconto = ($valor * $c->valor) / 100;
                    }
                    $comissaoVendedor->valor = $valorComDesconto;
                    $comissaoVendedor->save();
                    $comissao_corretor_contagem++;
                }
            } else {

                $comissoes_configuradas_default = ComissoesCorretoresConfiguracoes
                    ::where("plano_id", $request->plano_id)
                    ->where("administradora_id", 4)
                    ->get();

                foreach ($comissoes_configuradas_default as $c) {
                    $comissaoVendedor = new ComissoesCorretoresLancadas();
                    $comissaoVendedor->comissoes_id = $comissao->id;
                    //$comissaoVendedor->user_id = auth()->user()->id;
                    $comissaoVendedor->parcela = $c->parcela;
                    if ($comissao_corretor_contagem == 0) {
                        $comissaoVendedor->data = date('Y-m-d H:i:s', strtotime($request->data_boleto));
                        //$comissaoVendedor->tempo = $data;
                    } else {
                        $comissaoVendedor->data = date("Y-m-d H:i:s", strtotime($request->data_boleto . "+{$comissao_corretor_contagem}month"));
                        $date = new \DateTime($data);
                        $date->add(new \DateInterval("PT{$comissao_corretor_contagem}M"));
                        $data_add = $date->format('Y-m-d H:i:s');
                        //$comissaoVendedor->tempo = $data_add;
                    }
                    if($dados['quantidade_parcelas'] >= 1 && $dados['desconto_operadora'] >= 0) {
                        if($comissao_corretor_contagem <= $dados['quantidade_parcelas']) {
                            $valorComDesconto = ($valor * (1 - $dados['desconto_operadora'] / 100)) * $c->valor / 100;
                        }
                    } else {
                        $valorComDesconto = ($valor * $c->valor) / 100;
                    }
                    $comissaoVendedor->valor = $valorComDesconto;
                    $comissaoVendedor->save();
                    $comissao_corretor_contagem++;
                }




            }





        }






        /*********************LOgica Antiga*************************/


       return redirect('/financeiro?ac=empresarial');
    }








    public function formCreate(Request $request)
    {
        $users = User::where("id","!=",auth()->user()->id)->get();
        $origem_tabela = TabelaOrigens::all();
        return view('contratos.cadastrar',[
            'users' => $users,
            'origem_tabela' => $origem_tabela
        ]);
    }

    public function montarPlanosIndividual(Request $request)
    {
        $sql = "";
        $chaves = [];
        foreach($request->faixas[0] as $k => $v) {
            if($v != null AND $v != 0) {
                $sql .= "WHEN (SELECT id FROM faixa_etarias WHERE faixa_etarias.id = tabelas.faixa_etaria_id) = $k THEN $v ";
                $chaves[] = $k;
            }
        }


        $chaves = implode(",",$chaves);
        $cidade = $request->tabela_origem;
        $odonto = $request->odonto == "sim" ? 1 : 0;
        $coparticipacao = $request->coparticipacao == "sim" ? 1 : 0;

        $dados = DB::select("SELECT
            id,
            (select logo from administradoras where administradoras.id = tabelas.administradora_id) as logo,
            (select id from administradoras where administradoras.id = tabelas.administradora_id) as administradora,
            tabela_origens_id,
            (select nome from planos where planos.id = tabelas.plano_id) as planos,
            (select nome from acomodacoes where acomodacoes.id = tabelas.acomodacao_id) as acomodacao,

            (select nome from faixa_etarias where faixa_etarias.id = tabelas.faixa_etaria_id) as faixa,
            if(coparticipacao,'Com Coparticipação','Sem Coparticipação') as coparticipacao,
            if(odonto,'Com Odonto','Sem Odonto') as odonto,
        CASE
            $sql
        ELSE 0
        END AS quantidade,
        valor,
        CONCAT('card_',acomodacao_id) AS card
        FROM tabelas
        WHERE faixa_etaria_id IN($chaves) AND tabela_origens_id =  $cidade AND administradora_id = 4 AND plano_id = 1 AND odonto = $odonto AND coparticipacao = $coparticipacao");

        return view("financeiro.acomodacao",[
            "dados" => $dados,
            "card_inicial" => $dados[0]->card,
            "quantidade" => count($dados)
        ]);
    }

    private function codigoExterno($codigo)
    {
        $cliente = Cliente::where("codigo_externo",$codigo)->count();
        return $cliente;
    }

    public function sincronizarDados(Request $request)
    {
        set_time_limit(300);
        $filename = uniqid() . ".xlsx";
        if (move_uploaded_file($request->file, $filename)) {
            $filePath = base_path("public/{$filename}");
            $cpfs = [];
            $reader = ReaderEntityFactory::createReaderFromFile($filePath);
            $reader->open($filePath);
            $cidade = "";
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                    $cells = $row->getCells();
                    if($cells[5]->getValue() != "") {
                        if ($rowNumber === 3) {
                            $cidade = $cells[2]->getValue();
                        }
                        if ($rowNumber >= 5 && $this->codigoExterno($cells[0]->getValue()) == 0) {
                            $cpf = mb_strlen($cells[4]->getValue()) == 11 ? $cells[4]->getValue() : str_pad($cells[4]->getValue(), 11, "000", STR_PAD_LEFT);
                            $dia = str_pad($cells[18]->getValue(), 2, "0", STR_PAD_LEFT);
                            array_push($cpfs, $cells[0]->getValue());
                            //$user_count = User::where('codigo_vendedor', $cells[2]->getValue())->count();
                            //$user_count = cidadeCodigoVendedor::where("codigo_vendedor",$cells[2]->getValue());
                            $user_id = User::where('codigo_vendedor', $cells[2]->getValue())->first()->id;
                            $corretora_id = User::where('codigo_vendedor', $cells[2]->getValue())->first()->corretora_id;
                            $cidade_id = 2;
                            $cliente = new Cliente();
                            $cliente->user_id = $user_id;
                            $cliente->nome = mb_convert_case($cells[5]->getValue(), MB_CASE_TITLE, "UTF-8");
                            $cliente->celular = $cells[7]->getValue();
                            $cliente->corretora_id = $corretora_id;
                            $cliente->cpf = $cpf;
                            $cliente->data_nascimento = implode("-", array_reverse(explode("/", $cells[6]->getValue())));
                            $cliente->pessoa_fisica = 1;
                            $cliente->pessoa_juridica = 0;
                            $cliente->codigo_externo = $cells[0]->getValue();
                            $cliente->corretora_id = $corretora_id;
                            if ($cells[8]->getValue() == "RESPONSÁVEL FINANCEIRO") {
                                $cliente->quantidade_vidas = $cells[15]->getValue();
                            } else {
                                $cliente->quantidade_vidas = $cells[15]->getValue() + 1;
                            }
                            $cliente->save();
                            //$data_vigencia = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                            $dataOriginal = $cells[17]->getValue();
                            $novoDia = $dia; // Coluna 18
                            $dataDateTime = \DateTime::createFromFormat('d/m/Y', $dataOriginal);
                            $dataDateTime->modify('+1 month');
                            $dataDateTime->setDate($dataDateTime->format('Y'), $dataDateTime->format('m'), $novoDia);
                            //$data_vigencia = $dataDateTime->format('Y-m-d');
                            $data_vigencia = implode("-", array_reverse(explode("/", $cells[17]->getValue())));

                            $contrato = new Contrato();
                            //$contrato->acomodacao_id = $acomodacao_id;
                            $contrato->cliente_id = $cliente->id;
                            $contrato->administradora_id = 4;
                            $contrato->tabela_origens_id = $cidade_id;
                            $contrato->plano_id = 1;
                            $contrato->financeiro_id = 5;
                            $contrato->data_vigencia = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                            $contrato->codigo_externo = $cells[0]->getValue();
                            $contrato->data_boleto = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                            $contrato->valor_adesao = str_replace([".",","],["","."], $cells[12]->getValue());
                            $contrato->valor_plano = (float) str_replace([".",","],["","."], $cells[12]->getValue()) - 25;
                            $contrato->coparticipacao = 1;
                            $contrato->odonto = 0;
                            $contrato->created_at = $data_vigencia;
                            $contrato->desconto_corretor = "0";
                            $contrato->desconto_corretora = "0";
                            $contrato->save();

                            $comissao = new Comissoes();
                            $comissao->contrato_id = $contrato->id;
                            // $comissao->cliente_id = $contrato->cliente_id;
                            $comissao->user_id = $user_id;
                            // $comissao->status = 1;
                            $comissao->plano_id = 1;
                            $comissao->administradora_id = 4;
                            $comissao->tabela_origens_id = $cidade_id;
                            $comissao->data = date('Y-m-d');
                            $comissao->corretora_id = $corretora_id;
                            $comissao->save();
                            $user = User::where('codigo_vendedor', $cells[2]->getValue());
                            if ($user->first()->clt == 1) {///SE AQUI O CORRETOR E CLT

                                $dados = ComissoesCorretoresDefault
                                    ::where("plano_id", 1)
                                    ->where("administradora_id", 4)
                                    ->where("corretora_id",$corretora_id)
                                    //->where("tabela_origens_id", 2)
                                    ->get();

                                foreach ($dados as $c) {
                                    $valor_comissao_default = (float)str_replace([".", ","], ["", "."], $cells[12]->getValue()) - 25;
                                    $comissaoVendedor = new ComissoesCorretoresLancadas();
                                    $comissaoVendedor->comissoes_id = $comissao->id;
                                    $comissaoVendedor->parcela = $c->parcela;
                                    $comissaoVendedor->valor = ($valor_comissao_default * $c->valor) / 100;
                                    if ($comissao_corretor_default == 0) {
                                        //$comissaoVendedor->data = $data_vigencia;
                                        $comissaoVendedor->data = null;
                                        $comissaoVendedor->status_financeiro = 1;
                                        if ($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {

                                        }
                                        $comissaoVendedor->data_baixa = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                                        $comissaoVendedor->valor_pago = str_replace([".", ","], ["", "."], $cells[12]->getValue());
                                    } else {
                                        $data_vigencia_sem_dia = date("Y-m", strtotime($data_vigencia));
                                        $dates = date("Y-m", strtotime($data_vigencia_sem_dia . "+{$comissao_corretor_default}month"));

                                        $mes = explode("-", $dates)[1];
                                        if ($dia == 30 && $mes == 02) {
                                            $ano = explode("-", $dates)[0];
                                            $comissaoVendedor->data = date($ano . "-02-28");

                                            $bissexto = date('L', mktime(0, 0, 0, 1, 1, $ano));
                                            if ($bissexto == 1) {
                                                $comissaoVendedor->data = date($ano . "-02-29");
                                            } else {
                                                $comissaoVendedor->data = date($ano . "-02-28");
                                            }
                                        } else {
                                            $comissaoVendedor->data = date("Y-m-" . $dia, strtotime($dates));
                                        }
                                    }
                                    //$comissaoVendedor->data = null;
                                    $comissaoVendedor->save();
                                    $comissao_corretor_default++;
                                }
                                //}
                                /****FIm SE Comissoes Lancadas */
                                $comissao_corretor_default = 0;


                            } else {


                                $comissoes_configuradas_personalizado = ComissoesCorretoresConfiguracoes
                                    ::where("plano_id", 1)
                                    ->where("administradora_id", 4)
                                    ->where("corretora_id",$corretora_id)
                                    ->where("user_id", $user_id)
                                    //->where("tabela_origens_id", 2)
                                    ->get();

                                $comissao_corretor_contagem = 0;
                                $comissao_corretor_default = 0;

                                if (count($comissoes_configuradas_personalizado) >= 1) {//AQUI CORRETOR TEM COMISSOES PERSONALIZADAS
                                    //dd("PARCEIRO COM CONF");
                                    foreach ($comissoes_configuradas_personalizado as $c) {
                                        $valor_comissao = (float)str_replace([".", ","], ["", "."], $cells[12]->getValue()) - 25;
                                        $comissaoVendedor = new ComissoesCorretoresLancadas();
                                        $comissaoVendedor->comissoes_id = $comissao->id;
                                        //$comissaoVendedor->user_id = auth()->user()->id;
                                        // $comissaoVendedor->documento_gerador = "12345678";
                                        $comissaoVendedor->parcela = $c->parcela;
                                        $comissaoVendedor->valor = ($valor_comissao * $c->valor) / 100;
                                        //$comissaoVendedor->data = null;
                                        if ($comissao_corretor_contagem == 0) {
                                            $comissaoVendedor->data = null;
                                            //$comissaoVendedor->data = $data_vigencia;
                                            $comissaoVendedor->status_financeiro = 1;
                                            if ($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {

                                            }
                                            $comissaoVendedor->data_baixa = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                                            $comissaoVendedor->valor_pago = str_replace([".", ","], ["", "."], $cells[12]->getValue());
                                        } else {
                                            $data_vigencia_sem_dia = date("Y-m", strtotime($data_vigencia));
                                            $dates = date("Y-m", strtotime($data_vigencia_sem_dia . "+{$comissao_corretor_contagem}month"));
                                            $mes = explode("-", $dates)[1];

                                            //dd($mes);

                                            if ($dia == 30 && $mes == 02) {
                                                $ano = explode("-", $dates)[0];
                                                $comissaoVendedor->data = date($ano . "-02-28");
                                                $ano = explode("-", $comissaoVendedor->data)[0];
                                                $bissexto = date('L', mktime(0, 0, 0, 1, 1, $ano));
                                                if ($bissexto == 1) {
                                                    $comissaoVendedor->data = date($ano . "-02-29");
                                                } else {
                                                    $comissaoVendedor->data = date($ano . "-02-28");
                                                }
                                            } else {
                                                $comissaoVendedor->data = date("Y-m-" . $dia, strtotime($dates));
                                            }
                                        }
                                        //$comissaoVendedor->data = null;
                                        $comissaoVendedor->save();
                                        $comissao_corretor_contagem++;
                                    }

                                } else {//AQUI O CORRETOR E PARCELA DEFAULT

                                    $comissoes_configuradas_corretor = ComissoesCorretoresConfiguracoes
                                        ::where("plano_id", 1)
                                        ->where("administradora_id", 4)
                                        ->where("corretora_id",$corretora_id)
                                        ->whereNull("user_id")
                                        ->get();

                                    foreach ($comissoes_configuradas_corretor as $c) {
                                        $valor_comissao = (float)str_replace([".", ","], ["", "."], $cells[12]->getValue()) - 25;
                                        $comissaoVendedor = new ComissoesCorretoresLancadas();
                                        $comissaoVendedor->comissoes_id = $comissao->id;
                                        //$comissaoVendedor->user_id = auth()->user()->id;
                                        // $comissaoVendedor->documento_gerador = "12345678";
                                        $comissaoVendedor->parcela = $c->parcela;
                                        $comissaoVendedor->valor = ($valor_comissao * $c->valor) / 100;
                                        //$comissaoVendedor->data = null;
                                        if ($comissao_corretor_contagem == 0) {
                                            $comissaoVendedor->data = null;
                                            //$comissaoVendedor->data = $data_vigencia;
                                            $comissaoVendedor->status_financeiro = 1;
                                            if ($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {

                                            }
                                            $comissaoVendedor->data_baixa = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                                            $comissaoVendedor->valor_pago = str_replace([".", ","], ["", "."], $cells[12]->getValue());
                                        } else {
                                            $data_vigencia_sem_dia = date("Y-m", strtotime($data_vigencia));
                                            $dates = date("Y-m", strtotime($data_vigencia_sem_dia . "+{$comissao_corretor_contagem}month"));
                                            $mes = explode("-", $dates)[1];
                                            //dd($data_vigencia);
                                            if ($dia == 30 && $mes == 02) {
                                                $ano = explode("-", $dates)[0];
                                                $comissaoVendedor->data = date($ano . "-02-28");
                                                $ano = explode("-", $comissaoVendedor->data)[0];
                                                $bissexto = date('L', mktime(0, 0, 0, 1, 1, $ano));
                                                if ($bissexto == 1) {
                                                    $comissaoVendedor->data = date($ano . "-02-29");
                                                } else {
                                                    $comissaoVendedor->data = date($ano . "-02-28");
                                                }
                                            } else {

                                                $comissaoVendedor->data = date("Y-m-" . $dia, strtotime($dates));
                                            }
                                        }
                                        //$comissaoVendedor->data = null;
                                        $comissaoVendedor->save();
                                        $comissao_corretor_contagem++;
                                    }


                                }
                            }
                        }
                    }
                    //unlink("public/".$filename);
                }
            }
        }
        //Cliente::orderBy("id","desc")->first()->update(["last"=>1]);
        return "sucesso";
    }





    public function sincronizarDadosOld(Request $request)
    {
        set_time_limit(600);
        $filename = uniqid() . ".xlsx";
        if (move_uploaded_file($request->file, $filename)) {
            $filePath = base_path("public/{$filename}");
            $cpfs = [];
            $reader = ReaderEntityFactory::createReaderFromFile($filePath);
            $reader->open($filePath);
            $cidade = "";
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                    $cells = $row->getCells();
                    if ($cells[5]->getValue() != "") {
                        if ($rowNumber === 3) {
                            $cidade = $cells[2]->getValue();
                        }
                        if ($rowNumber >= 5 && $this->codigoExterno($cells[0]->getValue()) == 0) {
                            $cpf = mb_strlen($cells[4]->getValue()) == 11 ? $cells[4]->getValue() : str_pad($cells[4]->getValue(), 11, "000", STR_PAD_LEFT);
                            $dia = str_pad($cells[18]->getValue(), 2, "0", STR_PAD_LEFT);
                            array_push($cpfs, $cells[0]->getValue());
                            //$user_count = User::where('codigo_vendedor', $cells[2]->getValue())->count();
                            //$user_count = cidadeCodigoVendedor::where("codigo_vendedor",$cells[2]->getValue());
                            //$user_id = User::where('codigo_vendedor', $cells[2]->getValue())->first()->id;
                            $user = User::where('codigo_vendedor', $cells[2]->getValue());

                            if($user->count() == 1) {
                                $user_id = $user->first()->id;
                                $corretora_id = User::where('codigo_vendedor', $cells[2]->getValue())->first()->corretora_id;
                                $cidade_id = 2;
                                $cliente = new Cliente();
                                $cliente->user_id = $user_id;
                                $cliente->nome = mb_convert_case($cells[5]->getValue(), MB_CASE_TITLE, "UTF-8");
                                $cliente->celular = $cells[7]->getValue();
                                $cliente->corretora_id = $corretora_id;
                                $cliente->cpf = $cpf;
                                $cliente->data_nascimento = implode("-", array_reverse(explode("/", $cells[6]->getValue())));
                                $cliente->pessoa_fisica = 1;
                                $cliente->pessoa_juridica = 0;
                                $cliente->codigo_externo = $cells[0]->getValue();
                                $cliente->corretora_id = $corretora_id;
                                if ($cells[8]->getValue() == "RESPONSÁVEL FINANCEIRO") {
                                    $cliente->quantidade_vidas = $cells[15]->getValue();
                                } else {
                                    $cliente->quantidade_vidas = $cells[15]->getValue() + 1;
                                }
                                $cliente->save();
                                //$data_vigencia = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                            $dataOriginal = $cells[17]->getValue();
                            dd($dataOriginal);
                                $dataDateTime = \DateTime::createFromFormat('d/m/Y', $dataOriginal);
                            $novoDia = $dia; // Coluna 18
//                            $dataDateTime = \DateTime::createFromFormat('d/m/Y', $dataOriginal);
//                            $dataDateTime->modify('+1 month');
//                            $dataDateTime->setDate($dataDateTime->format('Y'), $dataDateTime->format('m'), $novoDia);
//                            $data_vigencia = $dataDateTime->format('Y-m-d');

                                // Armazena o dia original
                                $originalDay = (int)$dataDateTime->format('d');

// Move para o primeiro dia do próximo mês para obter o mês correto
                                $dataDateTime->modify('first day of next month');

// Obtém o último dia do próximo mês
                                $lastDayNextMonth = (int)$dataDateTime->format('t');

// Define o dia como o original ou o último dia do mês, se necessário
                                if ($originalDay > $lastDayNextMonth) {
                                    $dataDateTime->setDate(
                                        (int)$dataDateTime->format('Y'),
                                        (int)$dataDateTime->format('m'),
                                        $lastDayNextMonth
                                    );
                                } else {
                                    $dataDateTime->setDate(
                                        (int)$dataDateTime->format('Y'),
                                        (int)$dataDateTime->format('m'),
                                        $originalDay
                                    );
                                }

                                //
                                if($dataDateTime->format('m') == '02' && $novoDia == '30') {
                                    //dd("Olaaa");
                                    $dataDateFevereiro = \DateTime::createFromFormat('d/m/Y', $dataOriginal);
                                    $dataDateFevereiro->setDate($dataDateFevereiro->format('Y'), $dataDateFevereiro->format('m'), $dataDateFevereiro->format('d'));
                                    //$dataDateTime = \DateTime::createFromFormat('d/m/Y', $dataOriginal);
                                    //$dataDateTime->setDate($dataDateTime->format('Y'), $dataDateTime->format('m'), $novoDia);
                                    $data_vigencia = $dataDateFevereiro->format("Y-m-d");


                                } else if($dataDateTime->format('m') == '02') {

                                    $dataDateFevereiroGeral = \DateTime::createFromFormat('d/m/Y', $dataOriginal);

                                    //$dataDateFevereiroGeral->modify('+1 month');

                                    $dataDateFevereiroGeral->setDate($dataDateFevereiroGeral->format('Y'), '02', $novoDia);


                                    //$dataDateTime = \DateTime::createFromFormat('d/m/Y', $dataOriginal);
                                    //$dataDateTime->setDate($dataDateTime->format('Y'), $dataDateTime->format('m'), $novoDia);
                                    $data_vigencia = $dataDateFevereiroGeral->format("Y-m-d");

                                }  else {

                                    //dd("OPSSS");

                                    //$dataDateTime->modify('+1 month');
                                   // $dataDateTime = \DateTime::createFromFormat('d/m/Y', $dataOriginal);
                                    $dataDateTime->setDate($dataDateTime->format('Y'), $dataDateTime->format('m'), $novoDia);
                                    $data_vigencia = $dataDateTime->format("Y-m-d");
                                }
//
//                                $ultimoDiaDoMes = cal_days_in_month(CAL_GREGORIAN, $dataDateTime->format('m'), $dataDateTime->format('Y'));
//                                if ($novoDia > $ultimoDiaDoMes) {
//                                    $dataDateTime->setDate($dataDateTime->format('Y'), $dataDateTime->format('m'), $ultimoDiaDoMes); // Ajusta para o último dia do mês
//                                } else {
//                                    $dataDateTime->setDate($dataDateTime->format('Y'), $dataDateTime->format('m'), $novoDia); // Caso contrário, usa o novo dia
//                                }



                                $contrato = new Contrato();
                                //$contrato->acomodacao_id = $acomodacao_id;
                                $contrato->cliente_id = $cliente->id;
                                $contrato->administradora_id = 4;
                                $contrato->tabela_origens_id = $cidade_id;
                                $contrato->plano_id = 1;
                                $contrato->financeiro_id = 5;
                                $contrato->data_vigencia = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                                $contrato->codigo_externo = $cells[0]->getValue();
                                $contrato->data_boleto = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                                $contrato->valor_adesao = str_replace([".", ","], ["", "."], $cells[12]->getValue());
                                $contrato->valor_plano = (float)str_replace([".", ","], ["", "."], $cells[12]->getValue()) - 25;
                                $contrato->coparticipacao = 1;
                                $contrato->odonto = 0;
                                $contrato->created_at = $data_vigencia;
                                $contrato->desconto_corretor = "0";
                                $contrato->desconto_corretora = "0";
                                $contrato->save();

                                $comissao = new Comissoes();
                                $comissao->contrato_id = $contrato->id;
                                // $comissao->cliente_id = $contrato->cliente_id;
                                $comissao->user_id = $user_id;
                                // $comissao->status = 1;
                                $comissao->plano_id = 1;
                                $comissao->administradora_id = 4;
                                $comissao->tabela_origens_id = $cidade_id;
                                $comissao->data = date('Y-m-d');
                                $comissao->corretora_id = $corretora_id;
                                $comissao->save();

                                if ($user->first()->clt == 1) {///SE AQUI O CORRETOR E CLT

                                    $dados = ComissoesCorretoresDefault
                                        ::where("plano_id", 1)
                                        ->where("administradora_id", 4)
                                        ->where("corretora_id",$corretora_id)
                                        //->where("tabela_origens_id", 2)
                                        ->get();

                                    foreach ($dados as $c) {
                                        $valor_comissao_default = (float)str_replace([".", ","], ["", "."], $cells[12]->getValue()) - 25;
                                        $comissaoVendedor = new ComissoesCorretoresLancadas();
                                        $comissaoVendedor->comissoes_id = $comissao->id;
                                        $comissaoVendedor->parcela = $c->parcela;
                                        $comissaoVendedor->valor = ($valor_comissao_default * $c->valor) / 100;
                                        if ($comissao_corretor_default == 0) {
                                            //$comissaoVendedor->data = $data_vigencia;
                                            $comissaoVendedor->data = null;
                                            $comissaoVendedor->status_financeiro = 1;
                                            if ($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {

                                            }
                                            $comissaoVendedor->data_baixa = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                                            $comissaoVendedor->valor_pago = str_replace([".", ","], ["", "."], $cells[12]->getValue());
                                        } else {
                                            $data_vigencia_sem_dia = date("Y-m", strtotime($data_vigencia));
                                            $dates = date("Y-m", strtotime($data_vigencia_sem_dia . "+{$comissao_corretor_default}month"));

                                            $mes = explode("-", $dates)[1];
                                            if ($dia == 30 && $mes == 02) {
                                                $ano = explode("-", $dates)[0];
                                                $comissaoVendedor->data = date($ano . "-02-28");

                                                $bissexto = date('L', mktime(0, 0, 0, 1, 1, $ano));
                                                if ($bissexto == 1) {
                                                    $comissaoVendedor->data = date($ano . "-02-29");
                                                } else {
                                                    $comissaoVendedor->data = date($ano . "-02-28");
                                                }
                                            } else {
                                                $comissaoVendedor->data = date("Y-m-" . $dia, strtotime($dates));
                                            }
                                        }
                                        //$comissaoVendedor->data = null;
                                        $comissaoVendedor->save();
                                        $comissao_corretor_default++;
                                    }
                                    //}
                                    /****FIm SE Comissoes Lancadas */
                                    $comissao_corretor_default = 0;


                                } else {


                                    $comissoes_configuradas_personalizado = ComissoesCorretoresConfiguracoes
                                        ::where("plano_id", 1)
                                        ->where("administradora_id", 4)
                                        ->where("corretora_id",$corretora_id)
                                        ->where("user_id", $user_id)
                                        //->where("tabela_origens_id", 2)
                                        ->get();

                                    $comissao_corretor_contagem = 0;
                                    $comissao_corretor_default = 0;

                                    if (count($comissoes_configuradas_personalizado) >= 1) {//AQUI CORRETOR TEM COMISSOES PERSONALIZADAS
                                        //dd("PARCEIRO COM CONF");
                                        foreach ($comissoes_configuradas_personalizado as $c) {
                                            $valor_comissao = (float)str_replace([".", ","], ["", "."], $cells[12]->getValue()) - 25;
                                            $comissaoVendedor = new ComissoesCorretoresLancadas();
                                            $comissaoVendedor->comissoes_id = $comissao->id;
                                            //$comissaoVendedor->user_id = auth()->user()->id;
                                            // $comissaoVendedor->documento_gerador = "12345678";
                                            $comissaoVendedor->parcela = $c->parcela;
                                            $comissaoVendedor->valor = ($valor_comissao * $c->valor) / 100;
                                            //$comissaoVendedor->data = null;
                                            if ($comissao_corretor_contagem == 0) {
                                                $comissaoVendedor->data = null;
                                                //$comissaoVendedor->data = $data_vigencia;
                                                $comissaoVendedor->status_financeiro = 1;
                                                if ($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {

                                                }
                                                $comissaoVendedor->data_baixa = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                                                $comissaoVendedor->valor_pago = str_replace([".", ","], ["", "."], $cells[12]->getValue());
                                            } else {
                                                $data_vigencia_sem_dia = date("Y-m", strtotime($data_vigencia));
                                                $dates = date("Y-m", strtotime($data_vigencia_sem_dia . "+{$comissao_corretor_contagem}month"));
                                                $mes = explode("-", $dates)[1];

                                                //dd($mes);

                                                if ($dia == 30 && $mes == 02) {
                                                    $ano = explode("-", $dates)[0];
                                                    $comissaoVendedor->data = date($ano . "-02-28");
                                                    $ano = explode("-", $comissaoVendedor->data)[0];
                                                    $bissexto = date('L', mktime(0, 0, 0, 1, 1, $ano));
                                                    if ($bissexto == 1) {
                                                        $comissaoVendedor->data = date($ano . "-02-29");
                                                    } else {
                                                        $comissaoVendedor->data = date($ano . "-02-28");
                                                    }
                                                } else {
                                                    $comissaoVendedor->data = date("Y-m-" . $dia, strtotime($dates));
                                                }
                                            }
                                            //$comissaoVendedor->data = null;
                                            $comissaoVendedor->save();
                                            $comissao_corretor_contagem++;
                                        }

                                    } else {//AQUI O CORRETOR E PARCELA DEFAULT

                                        $comissoes_configuradas_corretor = ComissoesCorretoresConfiguracoes
                                            ::where("plano_id", 1)
                                            ->where("administradora_id", 4)
                                            ->where("corretora_id",$corretora_id)
                                            ->get();

                                        foreach ($comissoes_configuradas_corretor as $c) {
                                            $valor_comissao = (float)str_replace([".", ","], ["", "."], $cells[12]->getValue()) - 25;
                                            $comissaoVendedor = new ComissoesCorretoresLancadas();
                                            $comissaoVendedor->comissoes_id = $comissao->id;
                                            //$comissaoVendedor->user_id = auth()->user()->id;
                                            // $comissaoVendedor->documento_gerador = "12345678";
                                            $comissaoVendedor->parcela = $c->parcela;
                                            $comissaoVendedor->valor = ($valor_comissao * $c->valor) / 100;
                                            //$comissaoVendedor->data = null;
                                            if ($comissao_corretor_contagem == 0) {
                                                $comissaoVendedor->data = null;
                                                //$comissaoVendedor->data = $data_vigencia;
                                                $comissaoVendedor->status_financeiro = 1;
                                                if ($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {

                                                }
                                                $comissaoVendedor->data_baixa = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                                                $comissaoVendedor->valor_pago = str_replace([".", ","], ["", "."], $cells[12]->getValue());
                                            } else {
                                                $data_vigencia_sem_dia = date("Y-m", strtotime($data_vigencia));
                                                $dates = date("Y-m", strtotime($data_vigencia_sem_dia . "+{$comissao_corretor_contagem}month"));
                                                $mes = explode("-", $dates)[1];
                                                //dd($data_vigencia);
                                                if ($dia == 30 && $mes == 02) {
                                                    $ano = explode("-", $dates)[0];
                                                    $comissaoVendedor->data = date($ano . "-02-28");
                                                    $ano = explode("-", $comissaoVendedor->data)[0];
                                                    $bissexto = date('L', mktime(0, 0, 0, 1, 1, $ano));
                                                    if ($bissexto == 1) {
                                                        $comissaoVendedor->data = date($ano . "-02-29");
                                                    } else {
                                                        $comissaoVendedor->data = date($ano . "-02-28");
                                                    }
                                                } else {

                                                    $comissaoVendedor->data = date("Y-m-" . $dia, strtotime($dates));
                                                }
                                            }
                                            //$comissaoVendedor->data = null;
                                            $comissaoVendedor->save();
                                            $comissao_corretor_contagem++;
                                        }


                                    }
                                }



                            }






                        }
                        //unlink("public/".$filename);
                    }
                }
            }
            //Cliente::orderBy("id","desc")->first()->update(["last"=>1]);
            return "sucesso";
        }
    }

    public function formCreateColetivo()
    {
        $corretora_id = auth()->user()->corretora_id;
        $users = User::where("id","!=",1)->where('ativo',1)->where('corretora_id',$corretora_id)->get();
        $origem_tabela = TabelaOrigens::all();
        $administradoras = Administradora::whereRaw("id != (SELECT id FROM administradoras WHERE nome LIKE '%hapvida%')")->get();
        return view('financeiro.cadastrar-coletivo',[
            'users' => $users,
            'cidades' => $origem_tabela,
            'administradoras' => $administradoras
        ]);
    }

    public function montarPlanos(Request $request)
    {
        $sql = "";
        $chaves = [];
        foreach($request->faixas[0] as $k => $v) {
            if($v != null AND $v != 0) {
                $sql .= "WHEN (SELECT id FROM faixa_etarias WHERE faixa_etarias.id = tabelas.faixa_etaria_id) = $k THEN $v ";
                $chaves[] = $k;
            }
        }

        $administradora = $request->administradora_id;
        $chaves = implode(",",$chaves);
        $cidade = $request->tabela_origem;
        $odonto = $request->odonto == "sim" ? 1 : 0;
        $coparticipacao = $request->coparticipacao == "sim" ? 1 : 0;

        $dados = DB::select("SELECT
            id,
            (select logo from administradoras where administradoras.id = tabelas.administradora_id) as logo,
            (select id from administradoras where administradoras.id = tabelas.administradora_id) as administradora,
            tabela_origens_id,
            (select nome from planos where planos.id = tabelas.plano_id) as planos,
            (select nome from acomodacoes where acomodacoes.id = tabelas.acomodacao_id) as acomodacao,

            (select nome from faixa_etarias where faixa_etarias.id = tabelas.faixa_etaria_id) as faixa,
            if(coparticipacao,'Com Coparticipação','Sem Coparticipação') as coparticipacao,
            if(odonto,'Com Odonto','Sem Odonto') as odonto,
        CASE
            $sql
        ELSE 0
        END AS quantidade,
        valor,
        CONCAT('card_',acomodacao_id) AS card
        FROM tabelas
        WHERE faixa_etaria_id IN($chaves) AND tabela_origens_id =  $cidade AND administradora_id = $administradora AND odonto = $odonto AND coparticipacao = $coparticipacao");

        return view("financeiro.acomodacao",[
            "dados" => $dados,
            "card_inicial" => $dados[0]->card,
            "quantidade" => count($dados)
        ]);

    }






    public function detalhesContrato($id)
    {

        $contratos = Contrato
            ::where("id",$id)
            ->with(['administradora',
                'financeiro',
                'cidade',
                'comissao',
                'acomodacao',
                'plano',
                'comissao.comissaoAtualFinanceiro','comissao.comissoesLancadas','clientes','clientes.user'])
            ->orderBy("id","desc")
            ->first();

        $users = User::where("id","!=",auth()->user()->id)->get();


        return view('financeiro.detalhe',[
            "dados" => $contratos,
            "users" => $users,

            "plano" => $contratos->plano->id
        ]);
    }


    private function verificarCarteirinha($nome,$codigo)
    {
        $carteirinha = Cliente
            ::select('clientes.*')
            ->join('users','users.id','=','clientes.user_id')
            ->join('contratos','contratos.cliente_id','=','clientes.id')
            ->where('clientes.nome','like',$nome)
            ->where('users.codigo_vendedor',$codigo)
            ->whereNull("cateirinha")
            ///->whereDate('contratos.created_at',$cells[6]->getValue()->format('Y-m-d'))
            ->with(['user','contrato','contrato.comissao','contrato.comissao.comissoesLancadas'])
            ->first();

        if($carteirinha) {
            return $carteirinha->id;
        } else {
            return null;
        }
    }

    public function sincronizarBaixasJaExiste(Request $request)
    {
        set_time_limit(600);
        $filename = uniqid() . ".xlsx";
        if (move_uploaded_file($request->file, $filename)) {
            $filePath = base_path("public/{$filename}");
            $cpfs = [];
            $reader = ReaderEntityFactory::createReaderFromFile($filePath);
            $reader->open($filePath);
            $cidade = "";
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                    if($rowNumber > 1) {
                        $cells = $row->getCells();
                        $nome = $cells[7]->getValue();
                        $codi = $cells[4]->getValue();

                        if($id_alt = $this->verificarCarteirinha($nome,$codi)) {

                            $cliente_alt = Cliente::find($id_alt);
                            $cliente_alt->cateirinha = $cells[5]->getValue();
                            $cliente_alt->save();

                            $contrato_id = Contrato::where("cliente_id",$id_alt)->first()->id;
                            $cc = Contrato::find($contrato_id);
                            $cc->created_at = $cells[6]->getValue()->format('Y-m-d');
                            $cc->save();

                            //$contrato_id_cancelado = $cliente_alt->contrato->id;
                            if($cells[11]->getValue() == "LIQUIDADO" || $cells[11]->getValue() == "LIQUIDADO N/COB") {
                                if($cliente_alt) {
                                    $comissoes      = $cliente_alt->contrato->comissao->comissoesLancadas;
                                    $vencimento     = $cells[13]->getValue()->format('Y-m-d');
                                    $dt_pagamento   = $cells[12]->getValue()->format('Y-m-d');
                                    foreach($comissoes as $c) {

                                        $datasCadastradas = ComissoesCorretoresLancadas::where('comissoes_id', $c->comissoes_id)
                                            ->whereNotNull('data')
                                            ->pluck('data');



                                         // Certifique-se de usar o formato correto da data
                                        $dataMenor = $datasCadastradas->every(fn($data) => $vencimento < $data);

                                        if ($dataMenor) {
                                            // Atualiza a data da parcela 1
                                            ComissoesCorretoresLancadas::where('comissoes_id', $c->comissoes_id)
                                                ->where('parcela', 1)
                                                ->update([
                                                    'data' => $dt_pagamento,
                                                    'data_baixa' => $dt_pagamento
                                                ]);
                                        }


                                        if(date('m', strtotime($c->data)) == date('m', strtotime($vencimento))) {
                                            ComissoesCorretoresLancadas::find($c->id)->update([
                                                'status_financeiro'=> 1,
                                                'data_baixa' => $dt_pagamento,
                                                'valor_pago' => $cells[10]->getValue()
                                            ]);

                                        }
                                    }
                                }
                            }
                        } else {

                            $nome_existe = $cells[7]->getValue();
                            $codi_existe = $cells[4]->getValue();
                            $spreadsheetCode = $cells[5]->getValue();

                            $carteirinha_existe = Cliente
                                ::select('clientes.*')
                                ->join('users','users.id','=','clientes.user_id')
                                ->join('contratos','contratos.cliente_id','=','clientes.id')
                                ->whereRaw("LEFT(cateirinha, 11) = ?", [$spreadsheetCode])
                                ///->whereDate('contratos.created_at',$cells[6]->getValue()->format('Y-m-d'))
                                ->with(['user','contrato','contrato.comissao','contrato.comissao.comissoesLancadas'])
                                ->first();

                            if($cells[11]->getValue() == "LIQUIDADO" || $cells[11]->getValue() == "LIQUIDADO N/COB") {
                                if($carteirinha_existe) {
                                    $comissoes = $carteirinha_existe->contrato->comissao->comissoesLancadas;

                                    $vencimento = $cells[13]->getValue()->format('Y-m-d');
                                    $dt_pagamento = $cells[12]->getValue()->format('Y-m-d');
                                    foreach($comissoes as $c) {

                                        $datasCadastradas = ComissoesCorretoresLancadas::where('comissoes_id', $c->comissoes_id)
                                            ->whereNotNull('data')
                                            ->pluck('data');
//

                                        // Certifique-se de usar o formato correto da data
                                        $dataMenor = $datasCadastradas->every(fn($data) => $vencimento < $data);
                                        if ($dataMenor) {
                                            // Atualiza a data da parcela 1
                                            ComissoesCorretoresLancadas::where('comissoes_id', $c->comissoes_id)
                                                ->where('parcela', 1)
                                                ->update([
                                                    'data' => $dt_pagamento,
                                                    'data_baixa' => $dt_pagamento
                                                ]);
                                        }

                                        if(date('m', strtotime($c->data)) == date('m', strtotime($vencimento))) {
                                            ComissoesCorretoresLancadas::find($c->id)->update([
                                                'status_financeiro'=>1,
                                                'data_baixa' => $dt_pagamento,
                                                'valor_pago' => $cells[10]->getValue()
                                            ]);

                                        }
                                    }
                                }
                            }


                        }
                    }
                }
            }
        }



        $this->atualizarContrato();


        return "successo";
    }



    private function atualizarContrato()
    {
        $comissoes = \Illuminate\Support\Facades\DB::connection('tenant')->select("
               select
                   *
               from comissoes_corretores_lancadas where
               comissoes_id IN(select id from comissoes where contrato_id IN(select id from contratos where plano_id = 1))
               and status_financeiro = 1
         ");
        foreach($comissoes as $cc) {

            switch ($cc->parcela) {
                case 2:
                    $contrato_id = Comissoes::where("id", $cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id", $contrato_id)->update(["financeiro_id" => 6]);
                    break;

                case 3:
                    $contrato_id = Comissoes::where("id", $cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id", $contrato_id)->update(["financeiro_id" => 7]);
                    break;

                case 4:
                    $contrato_id = Comissoes::where("id", $cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id", $contrato_id)->update(["financeiro_id" => 8]);
                    break;

                case 5:
                    $contrato_id = Comissoes::where("id", $cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id", $contrato_id)->update(["financeiro_id" => 9]);
                    break;

                case 6:
                    $contrato_id = Comissoes::where("id", $cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id", $contrato_id)->update(["financeiro_id" => 11]);
                    break;

                default:
                    $contrato_id = Comissoes::where("id", $cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id", $contrato_id)->update(["financeiro_id" => 5]);
                    break;
            }
        }
    }





    public function storeIndividual(Request $request)
    {

        $cpf = str_replace([".","-"],"",$request->cpf_individual);
        $dia = 10;
        $codigo_externo = $request->codigo_externo_individual;




        $valores_numericos = array_filter($request->faixas_etarias, function ($valor) {
            return is_numeric($valor);
        });
        $quantidade_vidas = array_sum($valores_numericos);
        $valor = str_replace([".",","],["","."],$request->valor);
        $cliente = new Cliente();
        $cliente->user_id = $request->users_individual;
        $cliente->corretora_id = auth()->user()->corretora_id;
        $cliente->nome = $request->nome_individual;
        $cliente->cidade = $request->cidade_origem_individual;
        $cliente->celular = $request->celular_individual;
        $cliente->telefone = $request->telefone_individual;
        $cliente->email = $request->email_individual;
        $cliente->cpf = $request->cpf_individual;
        $cliente->data_nascimento = date('Y-m-d',strtotime($request->data_nascimento_individual));
        $cliente->cep = $request->cep_individual;
        $cliente->rua = $request->rua_individual;
        $cliente->bairro = $request->bairro_individual;
        $cliente->complemento = $request->complemento_individual;
        $cliente->uf = $request->uf_individual;
        $cliente->pessoa_fisica = 1;
        $cliente->pessoa_juridica = 0;
        $cliente->dependente = ($request->dependente_individual == "on" ? 1 : 0);
        $cliente->quantidade_vidas = $quantidade_vidas;
        $cliente->nm_plano = null;
        $cliente->numero_registro_plano = null;
        $cliente->rede_plano = null;
        $cliente->tipo_acomodacao_plano = null;
        $cliente->segmentacao_plano = null;
        $cliente->cateirinha = null;
        $cliente->dados = 1;
        $cliente->save();

//        if($cliente->dependente) {
//            $dependente = new Dependentes();
//            $dependente->cliente_id = $cliente->id;
//            $dependente->nome = $request->responsavel_financeiro_individual_cadastro;
//            $dependente->cpf = $request->cpf_financeiro_individual_cadastro;
//            $dependente->save();
//        }


        $acomodacao = $request->acomodacao;
        $acomodacao_id = Acomodacao::selectRaw('id')->whereRaw("nome LIKE '%{$acomodacao}%'")->first()->id;
        $data_vigencia = $request->data_vigencia;
        $data_boleto = $request->data_boleto;
        $valor_adesao = str_replace([".",","],["","."],$request->valor_adesao);
        $valor_plano = str_replace([".",","],["","."],$request->valor);

        $contrato = new Contrato();
        $contrato->cliente_id = $cliente->id;
        $contrato->administradora_id = 4;
        $contrato->acomodacao_id = $acomodacao_id;
        $contrato->tabela_origens_id = $request->tabela_origem_individual;
        $contrato->plano_id = 1;
        $contrato->financeiro_id = 1;
        $contrato->coparticipacao = ($request->coparticipacao_individual == "sim" ? 1 : 0);
        $contrato->odonto = ($request->odonto_individual == "sim" ? 1 : 0);
        $contrato->codigo_externo = $request->codigo_externo_individual;
        $contrato->data_vigencia = $data_vigencia;
        $contrato->data_boleto = $data_boleto;
        $contrato->valor_adesao = $valor_adesao;
        $contrato->valor_plano = $valor_plano;
        $contrato->save();

        // CotacaoFaixaEtaria::where("contrato_id",$contrato->id)->delete();
        $totalVidas = 0;
        $faixas = $request->faixas_etarias;
        foreach($faixas as $k => $v) {
            if($v != 0) {

                $totalVidas += $v;
            }
        }

        $comissao = new Comissoes();
        $comissao->contrato_id = $contrato->id;
        // $comissao->cliente_id = $contrato->cliente_id;
        $comissao->user_id = $request->users_individual;
        // $comissao->status = 1;
        $comissao->plano_id = 1;
        $comissao->corretora_id = auth()->user()->corretora_id;
        $comissao->administradora_id = 4;
        $comissao->tabela_origens_id = $request->tabela_origem_individual;
        $comissao->data = date('Y-m-d');
        $comissao->save();

//        $comissoes_configuradas_corretor = ComissoesCorretoresConfiguracoes
//            ::where("plano_id",1)
//            ->where("administradora_id",4)
//            ->where("user_id",$request->users_individual)
//            ->where("tabela_origens_id",2)
//            ->get();
//
//        $comissao_corretor_contagem = 0;
//        $comissao_corretor_default = 0;
//
//        if(count($comissoes_configuradas_corretor) >= 1) {
//            foreach($comissoes_configuradas_corretor as $c) {
//                $valor_comissao = $valor_plano - 25;
//                $comissaoVendedor = new ComissoesCorretoresLancadas();
//                $comissaoVendedor->comissoes_id = $comissao->id;
//                $comissaoVendedor->parcela = $c->parcela;
//                $comissaoVendedor->valor = ($valor_comissao * $c->valor) / 100;
//                if($comissao_corretor_contagem == 0) {
//                    $comissaoVendedor->data = $data_vigencia;
//                    $comissaoVendedor->status_financeiro = 1;
//                    if($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {
//
//                    }
//                    $comissaoVendedor->data_baixa = implode("-",array_reverse(explode("/",$data_vigencia)));
//                    $comissaoVendedor->valor_pago = $valor_adesao;
//                } else {
//                    $data_vigencia_sem_dia = date("Y-m",strtotime($data_vigencia));
//                    $dates = date("Y-m",strtotime($data_vigencia_sem_dia."+{$comissao_corretor_contagem}month"));
//
//                    $mes = explode("-",$dates)[1];
//                    if($dia == 30 && $mes == 02) {
//                        $comissaoVendedor->data = date("Y-02-28");
//                        $ano = explode("-",$comissaoVendedor->data)[0];
//                        $bissexto= date('L', mktime(0, 0, 0, 1, 1, $ano));
//
//                        if($bissexto == 1) {
//                            $comissaoVendedor->data = date("Y-02-29");
//                        } else {
//                            $comissaoVendedor->data = date("Y-02-28");
//                        }
//
//                    }  else {
//                        $comissaoVendedor->data = date("Y-m-".$dia,strtotime($dates));
//                    }
//
//                }
//                $comissaoVendedor->save();
//                $comissao_corretor_contagem++;
//            }
//        } else {
//
//            $dados = ComissoesCorretoresDefault
//                ::where("plano_id",1)
//                ->where("administradora_id",4)
//                ->where("tabela_origens_id",2)
//                ->get();
//            foreach($dados as $c) {
//                $valor_comissao_default = $valor_plano - 25;
//
//
//                $comissaoVendedor = new ComissoesCorretoresLancadas();
//                $comissaoVendedor->comissoes_id = $comissao->id;
//                $comissaoVendedor->parcela = $c->parcela;
//                $comissaoVendedor->valor = ($valor_comissao_default * $c->valor) / 100;
//
//                if($comissao_corretor_default == 0) {
//                    $comissaoVendedor->data = $data_vigencia;
//                    $comissaoVendedor->status_financeiro = 1;
//                    if($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {
//
//                    }
//                    $comissaoVendedor->data_baixa = implode("-",array_reverse(explode("/",$data_vigencia)));
//                    $comissaoVendedor->valor_pago = $valor_adesao;
//                } else {
//                    $data_vigencia_sem_dia = date("Y-m",strtotime($data_vigencia));
//                    $dates = date("Y-m",strtotime($data_vigencia_sem_dia."+{$comissao_corretor_default}month"));
//
//                    $mes = explode("-",$dates)[1];
//                    if($dia == 30 && $mes == 02) {
//                        $comissaoVendedor->data = date("Y-02-28");
//                        $ano = explode("-",$comissaoVendedor->data)[0];
//                        $bissexto= date('L', mktime(0, 0, 0, 1, 1, $ano));
//
//                        if($bissexto == 1) {
//                            $comissaoVendedor->data = date("Y-02-29");
//                        } else {
//                            $comissaoVendedor->data = date("Y-02-28");
//                        }
//
//                    }  else {
//                        $comissaoVendedor->data = date("Y-m-".$dia,strtotime($dates));
//                    }
//
//                }
//                $comissaoVendedor->save();
//                $comissao_corretor_default++;
//            }
//        } /****FIm SE Comissoes Lancadas */










        if($request->tipo_cadastro == "administrador_cadastro") {
            return "contratos";
        } else {
            return "contrato";
        }

    }


    private function coletivoVerificarCodigoExterno($codigo)
    {


        $contrato = Contrato::where("codigo_externo",$codigo);
        return $contrato->count();
    }



    public function store(Request $request)
    {

        $corretora_id = User::find($request->usuario_coletivo_switch)->corretora_id;
        $clt = User::find($request->usuario_coletivo_switch)->clt;
        if($cod = $this->coletivoVerificarCodigoExterno($request->codigo_externo_coletivo) != 0) {
            return "ja_existe";
        }
        $desconto_corretor = $request->desconto_corretor;
        $desconto_corretora = $request->desconto_corretora;
        $valor = str_replace([".",","],["","."],$request->valor);
        $cliente = new Cliente();
        $cliente->nome = $request->nome_coletivo;
        $cliente->corretora_id = $corretora_id;
        $cliente->user_id = $request->usuario_coletivo_switch;
        $cliente->cidade = $request->cidade_origem_coletivo;
        $cliente->celular = $request->celular;
        $cliente->telefone = $request->telefone;
        $cliente->email = $request->email_coletivo;
        $cliente->cpf = $request->cpf_coletivo;
        $cliente->data_nascimento = date('Y-m-d',strtotime($request->data_nascimento_coletivo));
        $cliente->cep = $request->cep_coletivo;
        $cliente->rua = $request->rua_coletivo;
        $cliente->bairro = $request->bairro_coletivo;
        $cliente->complemento = $request->complemento_coletivo;
        $cliente->uf = $request->uf_coletivo;
        $cliente->pessoa_fisica = 1;
        $cliente->pessoa_juridica = 0;
        $cliente->dependente = ($request->dependente_coletivo == "on" ? 1 : 0);
        if($request->desconto_operadora != null && $request->quantidade_parcelas >= 1) {
            $cliente->desconto_operadora = $request->desconto_operadora;
            $cliente->quantidade_parcelas = $request->quantidade_parcelas;
        }




        $cliente->save();
        if($cliente->dependente) {
            $dependente = new Dependente();
            $dependente->cliente_id = $cliente->id;
            $dependente->nome = $request->responsavel_financeiro_coletivo_cadastrar_nome;
            $dependente->cpf = $request->responsavel_financeiro_coletivo_cadastrar_cpf;
            $dependente->save();
        }

        $acomodacao = $request->acomodacao;
        $acomodacao_id = Acomodacao::selectRaw('id')->whereRaw("nome LIKE '%{$acomodacao}%'")->first()->id;
        $data_vigencia = $request->data_vigencia;
        $data_boleto = $request->data_boleto;
        $valor_adesao = str_replace([".",","],["","."],$request->valor_adesao);
        $valor_plano = str_replace([".",","],["","."],$request->valor);

        $contrato = new Contrato();
        $contrato->acomodacao_id = $acomodacao_id;
        $contrato->cliente_id = $cliente->id;
        $contrato->administradora_id = $request->administradora;
        $contrato->tabela_origens_id = $request->tabela_origem;
        $contrato->plano_id = 3;
        $contrato->financeiro_id = 1;
        $contrato->data_vigencia = $data_vigencia;
        $contrato->codigo_externo = $request->codigo_externo_coletivo;
        $contrato->data_boleto = $data_boleto;
        $contrato->valor_adesao = $valor_adesao;
        $contrato->valor_plano = $valor_plano;
        $contrato->coparticipacao = ($request->coparticipacao_coletivo == "sim" ? 1 : 0);
        $contrato->odonto = ($request->odonto_coletivo == "sim" ? 1 : 0);
        $contrato->created_at = $request->created_at;
        $contrato->desconto_corretor = $desconto_corretor;
        $contrato->desconto_corretora = $desconto_corretora;
        $contrato->save();
        $totalVidas = 0;
        $faixas = $request->faixas_etarias;
        foreach($faixas as $k => $v) {
            if($v != 0) {
//                $orcamentoFaixaEtaria = new CotacaoFaixaEtaria();
//                $orcamentoFaixaEtaria->contrato_id = $contrato->id;
//                $orcamentoFaixaEtaria->faixa_etaria_id = $k;
//                $orcamentoFaixaEtaria->quantidade = $v;
//                $orcamentoFaixaEtaria->save();
                $totalVidas += $v;
            }
        }



        $alt = Cliente::where("id",$cliente->id)->first();
        $alt->quantidade_vidas = $totalVidas;
        $alt->save();
        $comissao = new Comissoes();
        $comissao->contrato_id = $contrato->id;
        // $comissao->cliente_id = $contrato->cliente_id;
        $comissao->user_id = $request->usuario_coletivo_switch;
        // $comissao->status = 1;
        $comissao->plano_id = 3;
        $comissao->administradora_id = $request->administradora;
        $comissao->tabela_origens_id = $request->tabela_origem;
        $comissao->data = date('Y-m-d');
        $comissao->corretora_id = $corretora_id;
        $comissao->save();


        if($clt == 1) {

            $dados = ComissoesCorretoresDefault
                ::where("plano_id",3)
                ->where('corretora_id',$corretora_id)
                //->where("administradora_id",$request->administradora)
                //->where("tabela_origens_id",2)
                ->get();
            foreach($dados as $c) {
                $cd++;
                $comissaoVendedor = new ComissoesCorretoresLancadas();
                $comissaoVendedor->comissoes_id = $comissao->id;
                $comissaoVendedor->parcela = $c->parcela;


                if($comissao_corretor_default == 0) {
                    $comissaoVendedor->data = $data_boleto;
                    //$comissaoVendedor->status_financeiro = 1;
                    if($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {
                        //$comissaoVendedor->status_gerente = 1;
                    }

                } elseif($comissao_corretor_default == 1) {
                    $comissaoVendedor->data = date("Y-m-d H:i:s",strtotime($request->data_vigencia));
                }  else {
                    $mes = $comissao_corretor_default - 1;
                    $comissaoVendedor->data = date("Y-m-d H:i:s",strtotime($request->data_vigencia."+{$mes}month"));
                    //$date = new \DateTime($data);
                    //$date->add(new \DateInterval("PT{$comissao_corretor_default}M"));
                    //$data_add = $date->format('Y-m-d H:i:s');
                }
                if($request->quantidade_parcelas >= 1 && $request->desconto_operadora >= 0 && $cd <= $request->quantidade_parcelas) {
                    //if($comissao_corretor_contagem <= $request->quantidade_parcelas) {
                    $valorComDesconto = ($valor * (1 - $request->desconto_operadora / 100)) * $c->valor / 100;
                    //}
                } else {
                    $valorComDesconto = ($valor * $c->valor) / 100;
                }
                $comissaoVendedor->valor = $valorComDesconto;
                $comissaoVendedor->save();
                $comissao_corretor_default++;
            }







        } else {

            $comissoes_configuradas_corretor = ComissoesCorretoresConfiguracoes
                ::where("plano_id",3)
                //->where("administradora_id",$request->administradora)
                ->where("user_id",$request->usuario_coletivo_switch)
                ->where('corretora_id',$corretora_id)
                //->where("tabela_origens_id",$request->tabela_origem)
                ->get();

            $date = new \DateTime(now());
            $date->add(new \DateInterval('PT1M'));
            $data = $date->format('Y-m-d H:i:s');

            $comissao_corretor_contagem = 0;
            $comissao_corretor_default = 0;
            $valorComDesconto = 0;
            $ii=0;
            $cd=0;

            if(count($comissoes_configuradas_corretor) >= 1) {
                foreach($comissoes_configuradas_corretor as $c) {
                    $comissaoVendedor = new ComissoesCorretoresLancadas();
                    $comissaoVendedor->comissoes_id = $comissao->id;
                    //$comissaoVendedor->user_id = auth()->user()->id;
                    $comissaoVendedor->parcela = $c->parcela;
                    if($comissao_corretor_contagem == 0) {
                        $comissaoVendedor->data = date('Y-m-d H:i:s',strtotime($request->data_vigencia));
                        //$comissaoVendedor->tempo = $data;
                    } else {
                        $comissaoVendedor->data = date("Y-m-d H:i:s",strtotime($request->data_vigencia."+{$ii}month"));
                        $date = new \DateTime($data);
                        $date->add(new \DateInterval("PT{$comissao_corretor_contagem}M"));
                        $data_add = $date->format('Y-m-d H:i:s');
                        //$comissaoVendedor->tempo = $data_add;
                        $ii++;
                    }
                    $comissaoVendedor->valor = ($valor * $c->valor) / 100;
                    $comissaoVendedor->save();
                    $comissao_corretor_contagem++;
                }
            } else {
                $comissoes_configuradas_corretor_geral = ComissoesCorretoresConfiguracoes
                    ::where("plano_id",3)
                    ->where('corretora_id',$corretora_id)
                    //->where("administradora_id",$request->administradora)
                    //->where("user_id",$request->usuario_coletivo_switch)
                    //->where("tabela_origens_id",$request->tabela_origem)
                    ->get();

                foreach($comissoes_configuradas_corretor_geral as $c) {
                    $comissaoVendedor = new ComissoesCorretoresLancadas();
                    $comissaoVendedor->comissoes_id = $comissao->id;
                    //$comissaoVendedor->user_id = auth()->user()->id;
                    $comissaoVendedor->parcela = $c->parcela;
                    if($comissao_corretor_contagem == 0) {
                        $comissaoVendedor->data = date('Y-m-d H:i:s',strtotime($request->data_vigencia));
                        //$comissaoVendedor->tempo = $data;
                    } else {
                        $comissaoVendedor->data = date("Y-m-d H:i:s",strtotime($request->data_vigencia."+{$ii}month"));
                        $date = new \DateTime($data);
                        $date->add(new \DateInterval("PT{$comissao_corretor_contagem}M"));
                        $data_add = $date->format('Y-m-d H:i:s');
                        //$comissaoVendedor->tempo = $data_add;
                        $ii++;
                    }
                    $comissaoVendedor->valor = ($valor * $c->valor) / 100;
                    $comissaoVendedor->save();
                    $comissao_corretor_contagem++;
                }

            }







        }













        if($request->tipo_financeiro) {
            return "financeiro";
        }



//        if($request->tipo_cadastro == "administrador_cadastro") {
//            return "contratos";
//        } else {
//            return "contrato";
//        }
        //return redirect('admin/contratos?ac=coletivo')->with('success',"Contrato com ".$request->nome." cadastrado com sucesso");
    }

    public function detalhesContratoColetivo($id)
    {

        $contratos = Contrato
            ::where("id",$id)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.comissoesLancadas','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->first();


        $cliente_id = $contratos->cliente_id;

        $motivo_cancelados = MotivoCancelado::all();
        $status = "";
        $parcela = "";
        if(isset($contratos->comissao->comissaoAtualFinanceiro->parcela) && $contratos->comissao->comissaoAtualFinanceiro->parcela != null) {
            switch($contratos->comissao->comissaoAtualFinanceiro->parcela) {

                case 3:
                    $status = "Pagou Vigencia";
                    break;
                case 4:
                    $status = "Pagou 2º Parcela";
                    break;
                case 5:
                    $status = "Pagou 3º Parcela";
                    break;
                case 6:
                    $status = "Pagou 4º Parcela";
                    break;
                case 7:
                    $status = "Pagou 5º Parcela";
                    break;

            }

            $parcela = $contratos->comissao->comissaoAtualFinanceiro->parcela;
        }

        //$cancelados = $contratos->whereHas('comissao.')


        $cancelados = $contratos->comissao->comissoesLancadas->where("cancelados",1)->count();

        $dependentes = "";


        if(Dependente::where('cliente_id',$cliente_id)->first()) {
            $dependentes = Dependente::where('cliente_id',$cliente_id)->first();
        }





        return view('financeiro.detalhe-coletivo',[
            "dados" => $contratos,
            "motivo_cancelados" => $motivo_cancelados,
            "status" => $status,
            "parcela" => $parcela,
            "cancelados" => $cancelados,
            "dependentes" => $dependentes
        ]);
    }

    public function recalcularColetivo()
    {
        $qtd_coletivo_em_analise = Contrato::where("financeiro_id",1)
            ->where("plano_id",3)
            ->count();

        $qtd_coletivo_emissao_boleto = Contrato::where("financeiro_id",2)
            ->where("plano_id",3)
            ->count();

        $qtd_coletivo_pg_adesao = Contrato::where('financeiro_id',3)
            ->where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",1);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();


        $qtd_coletivo_pg_vigencia = Contrato::where('financeiro_id',4)
            ->where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",2);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();



        $qtd_coletivo_02_parcela = Contrato::where('financeiro_id',6)
            ->where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",3);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_coletivo_03_parcela = Contrato::where('financeiro_id',7)
            ->where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",4);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_coletivo_04_parcela = Contrato::where('financeiro_id',8)
            ->where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",5);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_coletivo_05_parcela = Contrato::where('financeiro_id',9)
            ->where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",6);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_coletivo_06_parcela = Contrato::where('financeiro_id',10)
            ->where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",7);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_coletivo_finalizado = Contrato::where('financeiro_id',11)->where("plano_id",3)->count();
        $qtd_coletivo_cancelado = Contrato::where('financeiro_id',12)->where("plano_id",3)->count();

        return [
            "qtd_coletivo_em_analise" => $qtd_coletivo_em_analise,
            "qtd_coletivo_emissao_boleto" => $qtd_coletivo_emissao_boleto,
            "qtd_coletivo_pg_adesao" => $qtd_coletivo_pg_adesao,
            "qtd_coletivo_pg_vigencia" => $qtd_coletivo_pg_vigencia,
            "qtd_coletivo_02_parcela" => $qtd_coletivo_02_parcela,
            "qtd_coletivo_03_parcela" => $qtd_coletivo_03_parcela,
            "qtd_coletivo_04_parcela" =>  $qtd_coletivo_04_parcela,
            "qtd_coletivo_05_parcela" => $qtd_coletivo_05_parcela,
            "qtd_coletivo_06_parcela" => $qtd_coletivo_06_parcela,
            "qtd_coletivo_finalizado" => $qtd_coletivo_finalizado,
            "qtd_coletivo_cancelado" => $qtd_coletivo_cancelado
        ];
    }

    public function mudarEstadosColetivo(Request $request)
    {
        $id_cliente = $request->id_cliente;
        $id_contrato = $request->id_contrato;
        $contrato = Contrato::find($id_contrato);
        switch ($contrato->financeiro_id) {
            case 1:
                $contrato->financeiro_id = 2;
                break;
            case 2:
                $contrato->financeiro_id = 3;
                break;
            default:
                return "abrir_modal";
                break;
        }
        $contrato->save();
        return $this->recalcularColetivo();
    }

    public function desfazerColetivo(Request $request)
    {

        $id = $request->contrato_id;
        $contrato = Contrato::find($id);
        $fase = $request->fase;
        $new_fase = 1;

            switch($fase) {
                case 1:
                    $contrato->data_analise = null;
                break;
                case 2:
                    $new_fase = 1;
                    $contrato->data_emissao = null;
                break;
                case 3:
                    $new_fase = 2;
                break;
                case 4:
                    $new_fase = 3;
                break;
                case 5:
                    $new_fase = 4;
                break;
                case 6:
                    $new_fase = 5;
                break;
                case 7:
                    $new_fase = 6;
                break;
                case 8:
                    $new_fase = 7;
                break;
                case 9:
                    $new_fase = 8;
                break;
                case 11:
                    $new_fase = 9;
                break;
            }


        $comissao_id = Comissoes::where("contrato_id",$id)->first()->id;
        $contrato->financeiro_id = $new_fase;
        $contrato->data_baixa = null;
        $ccl = ComissoesCorretoresLancadas::where("comissoes_id",$comissao_id)->where("status_financeiro",1)->orderBy('id', 'desc')->first();
        if($ccl) {
            $ccl->status_financeiro = 0;
            $ccl->data_baixa = null;
            $ccl->save();
        }
        $contrato->save();

    }






    public function baixaDaData(Request $request)
    {
        $id_contrato = $request->id;
        $comissao_id = Comissoes::where("contrato_id",$id_contrato)->first()->id;
        $vencimento = ComissoesCorretoresLancadas::where("comissoes_id",$comissao_id)->where("status_financeiro",0)->where("status_gerente",0)->first()->data;
//        $request->validate([
//            'valor' => [
//                'required',
//                'date',
//                'before_or_equal:' . $vencimento, // Garante que a data não seja maior que a de vencimento
//                'after:1900-01-01', // Evita datas no passado distante como 0002
//                'regex:/^\d{4}-\d{2}-\d{2}$/', // Formato correto (YYYY-MM-DD)
//            ],
//        ]);






        $data_baixa = $request->valor;
        $parcela = ComissoesCorretoresLancadas::where("comissoes_id",$comissao_id)->where("status_financeiro",0)->where("status_gerente",0)->first()->parcela;


        // $id_cliente = $request->id_cliente;

        $contrato = Contrato::find($id_contrato);

        switch ($parcela) {
            case 1:
                $contrato->financeiro_id = 4;
                $contrato->data_baixa = $data_baixa;
                $comissaoCorretor = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",1)
                    ->first();
                if($comissaoCorretor) {
                    $comissaoCorretor->status_financeiro = 1;
                    $comissaoCorretor->data_baixa = $data_baixa;
                    $comissaoCorretor->save();
                }

                ComissoesCorretoresLancadas::where("comissoes_id",$comissao_id)->where("parcela",2)->update(['atual'=>1]);
                $contrato->save();
                return [
                  "status" => "Pag.Vigencia",
                  "baixa" => $data_baixa,

                ];



            break;
            case 2:
                $contrato->financeiro_id = 6;
                $contrato->data_baixa = $data_baixa;
                $comissaoCorretor = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",2)
                    ->first();
                if($comissaoCorretor) {
                    $comissaoCorretor->status_financeiro = 1;
                    $comissaoCorretor->data_baixa = $data_baixa;
                    $comissaoCorretor->atual = 0;
                    $comissaoCorretor->save();
                }
                ComissoesCorretoresLancadas::where("comissoes_id",$comissao_id)->where("parcela",3)->update(['atual'=>1]);
                $contrato->save();
                return [
                    "status" => "Pag. 2º Parcela",
                    "baixa" => $data_baixa,

                ];
            break;
            case 3:
                $contrato->financeiro_id = 7;
                $contrato->data_baixa = $data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",3)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }
                ComissoesCorretoresLancadas::where("comissoes_id",$comissao_id)->where("parcela",4)->update(['atual'=>1]);
                $contrato->save();
                return [
                    "status" => "Pag. 3º Parcela",
                    "baixa" => $data_baixa,

                ];
            break;
            case 4:
                $contrato->financeiro_id = 8;
                $contrato->data_baixa = $data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",4)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }
                ComissoesCorretoresLancadas::where("comissoes_id",$comissao_id)->where("parcela",5)->update(['atual'=>1]);
                $contrato->save();
                return [
                    "status" => "Pag. 4º Parcela",
                    "baixa" => $data_baixa,

                ];
            break;

            case 5:
                $contrato->financeiro_id = 9;
                $contrato->data_baixa = $data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",5)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }
                ComissoesCorretoresLancadas::where("comissoes_id",$comissao_id)->where("parcela",6)->update(['atual'=>1]);
                $contrato->save();
                return [
                    "status" => "Pag. 5º Parcela",
                    "baixa" => $data_baixa,

                ];

                break;

            case 6:
                $contrato->financeiro_id = 10;
                $contrato->data_baixa = $data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",6)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }

                ComissoesCorretoresLancadas::where("comissoes_id",$comissao_id)->where("parcela",7)->update(['atual'=>1]);
                $contrato->save();
                return [
                    "status" => "Pag. 6º Parcela",
                    "baixa" => $data_baixa,

                ];

                break;

            case 7:
                $contrato->financeiro_id = 11;
                $contrato->data_baixa = $data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",7)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }

                $contrato->save();
                return [
                    "status" => "Finalizado",
                    "baixa" => $data_baixa,

                ];

                break;


            // break;


            default:
                return "error";
                break;
        }

        //return $this->recalcularColetivo();
    }

    public function editarCampoEmpresarial(Request $request)
    {

        $cliente = ContratoEmpresarial::find($request->id_cliente);

        switch($request->alvo) {

            case "data_boleto":
                $cliente->data_boleto = $request->valor;
                $cliente->save();
            break;


            case "plano_contrado":
                $cliente->plano_contrado = $request->valor;
                $cliente->save();
            break;


            case "mudar_tabela_origem_empresarial":
                $cliente->tabela_origens_id = $request->valor;
                $cliente->save();
            break;

            case "razao_social":
                $cliente->razao_social = $request->valor;
                $cliente->save();
            break;

            case "cnpj":
                $cliente->cnpj = $request->valor;
                $cliente->save();
            break;

            case "vidas":
                $cliente->quantidade_vidas = $request->valor;
                $cliente->save();
            break;

            case "telefone":
                $cliente->telefone = $request->valor;
                $cliente->save();
            break;

            case "celular":
                $cliente->celular = $request->valor;
                $cliente->save();
            break;

            case "email":
                $cliente->email = $request->valor;
                $cliente->save();
            break;

            case "responsavel":
                $cliente->responsavel = $request->valor;
                $cliente->save();
            break;

            case "cidade":
                $cliente->cidade = $request->valor;
                $cliente->save();
            break;

            case "uf":
                $cliente->uf = $request->valor;
                $cliente->save();
            break;

            case "codigo_corretora":
                $cliente->codigo_corretora = $request->valor;
                $cliente->save();
            break;

            case "codigo_saude":
                $cliente->codigo_saude = $request->valor;
                $cliente->save();
            break;

            case "codigo_odonto":
                $cliente->codigo_odonto = $request->valor;
                $cliente->save();
            break;

            case "senha_cliente":
                $cliente->senha_cliente = $request->valor;
                $cliente->save();
            break;

        }




    }





    public function editarCampoColetivo(Request $request)
    {
        $contrato = Contrato::find($request->id_cliente);
        $cliente = Cliente::where("id",$contrato->cliente_id)->first();
        $dependente = Dependente::where('cliente_id',$cliente->id)->first();

        switch($request->alvo) {

            case "cliente":
                $cliente->nome = $request->valor;
                $cliente->save();
            break;


            case "cpf":
                $cliente->cpf = $request->valor;
                $cliente->save();
            break;


            case "email":
                $cliente->email = $request->valor;
                $cliente->save();
            break;

            case "data_nascimento":
                $data = implode("-",array_reverse(explode("/",$request->valor)));
                $cliente->data_nascimento = $data;
                $cliente->save();
            break;

            case "data_contrato":
                $data = implode("-",array_reverse(explode("/",$request->valor)));
                $contrato->created_at = $data;
                $contrato->save();
                break;

            case "cep":
                $cliente->cep = $request->valor;
                $cliente->save();
            break;

            case "cidade":
                $cliente->cidade = $request->valor;
                $cliente->save();
            break;

            case "uf":
                $cliente->uf = $request->valor;
                $cliente->save();
            break;

            case "bairro":
                $cliente->bairro = $request->valor;
                $cliente->save();
            break;

            case "rua":
                $cliente->rua = $request->valor;
                $cliente->save();
            break;

            case "complemento":
                $cliente->complemento = $request->valor;
                $cliente->save();
            break;

            case "codigo_externo":
                $contrato->codigo_externo = $request->valor;
                $contrato->save();
            break;

            case "fone":
                $cliente->celular = $request->valor;
                $cliente->save();
            break;

            case "desconto_corretora":
                $contrato->desconto_corretora = str_replace([".",","],["","."],$request->valor);
                $contrato->save();
            break;

            case "desconto_corretor":
                $contrato->desconto_corretor = str_replace([".",","],["","."],$request->valor);
                $contrato->save();
            break;

            case "valor_contrato":

                $contrato->valor_plano = str_replace([".",","],["","."],$request->valor);
                $contrato->save();

                $comissao = Comissoes::where("contrato_id",$contrato->id)->first();
                $user_id = $comissao->user_id;
                $plano_id = $comissao->plano_id;
                $administradora_id = $comissao->administradora_id;
                ComissoesCorretoresLancadas::where("comissoes_id",$comissao->id)->update(["valor" => 0]);
                $comissoes_configuradas_corretor = ComissoesCorretoresConfiguracoes
                    ::where("plano_id", 3)
                    ->where("administradora_id",$administradora_id)
                    ->where("user_id", $user_id)
                    ->get();
                $par = 0;
                if (count($comissoes_configuradas_corretor) >= 1) {
                    foreach ($comissoes_configuradas_corretor as $c) {
                        $par++;
                        $valor_comissao = $contrato->valor_plano;
                        $comissaoVendedor = ComissoesCorretoresLancadas::where("comissoes_id",$comissao->id)->where("parcela",$par)->first();
                        $comissaoVendedor->valor = ($valor_comissao * $c->valor) / 100;
                        $comissaoVendedor->save();
                    }
                } else {
                    $dados = ComissoesCorretoresDefault
                        ::where("plano_id", 3)
                        ->where("plano_id", $plano_id)
                        ->where("corretora_id",$cliente->corretora_id)
                        ->get();
                    foreach ($dados as $c) {
                        $par++;
                        $valor_comissao_default = $contrato->valor_plano;
                        $comissaoVendedor = ComissoesCorretoresLancadas::where("comissoes_id",$comissao->id)->where("parcela",$par)->first();
                        $comissaoVendedor->valor = ($valor_comissao_default * $c->valor) / 100;
                        $comissaoVendedor->save();
                    }
                }

            break;

            case "nome_responsavel":

                if(!$dependente) {

                    $cad = new Dependente();
                    $cad->cliente_id = $cliente->id;
                    $cad->nome = $request->valor;
                    $cad->save();
                } else {
                    $dependente->nome = $request->valor;
                    $dependente->save();
                }

            break;

            case "cpf_responsavel":

                if(!$dependente) {
                    $cad = new Dependentes();
                    $cad->cliente_id = $request->id_cliente;
                    $cad->cpf = $request->valor;
                    $cad->save();
                } else {
                    $dependente->cpf = $request->valor;
                    $dependente->save();
                }

            break;

        }

    }





    public function editarCampoIndividualmente(Request $request)
    {
        $contrato = Contrato::find($request->id_cliente);


        $cliente = Cliente::where("id",$contrato->cliente_id)->first();
        $dependente = Dependente::where('cliente_id',$cliente->id)->first();
        switch($request->alvo) {

            case "codigo_externo":
                $contrato->codigo_externo = $request->valor;
                $contrato->save();
                break;

            case "data_vigencia":
                $contrato->data_vigencia = implode("-",array_reverse(explode("/",$request->valor)));
                $contrato->save();
                break;

            case "desconto_corretora":
                $contrato->desconto_corretora = str_replace([".",","],["","."],$request->valor);
                $contrato->save();
                break;

            case "desconto_corretor":
                $contrato->desconto_corretor = str_replace([".",","],["","."],$request->valor);
                $contrato->save();
                break;



            case "cliente":

                $cliente->nome = $request->valor;
                $cliente->save();

                break;

            case "data_nascimento":

                $data = implode("-",array_reverse(explode("/",$request->valor)));
                $cliente->data_nascimento = $data;
                $cliente->save();

                break;

            case "cpf":

                $cliente->cpf = $request->valor;
                $cliente->save();

                break;

            case "nome_responsavel":

                if(!$dependente) {

                    $cad = new Dependente();
                    $cad->cliente_id = $cliente->id;
                    $cad->nome = $request->valor;
                    $cad->save();
                } else {
                    $dependente->nome = $request->valor;
                    $dependente->save();
                }

                break;

            case "cpf_responsavel":

                if(!$dependente) {
                    $cad = new Dependentes();
                    $cad->cliente_id = $request->id_cliente;
                    $cad->cpf = $request->valor;
                    $cad->save();
                } else {
                    $dependente->cpf = $request->valor;
                    $dependente->save();
                }

                break;

            case "fone":

                $cliente->celular = $request->valor;
                $cliente->save();

                break;

            case "telefone":

                $cliente->telefone = $request->valor;
                $cliente->save();

                break;

            case "cep":

                $cliente->cep = $request->valor;
                $cliente->save();

                break;


            case "email":

                $cliente->email = $request->valor;
                $cliente->save();

                break;

            case "cidade":

                $cliente->cidade = $request->valor;
                $cliente->save();

                break;

            case "uf":

                $cliente->uf = $request->valor;
                $cliente->save();

                break;

            case "bairro":

                $cliente->bairro = $request->valor;
                $cliente->save();

                break;

            case "rua":

                $cliente->rua = $request->valor;
                $cliente->save();

                break;

            case "complemento":

                $cliente->complemento = $request->valor;
                $cliente->save();

                break;
            case "carteirinha":

                $cliente->cateirinha = $request->valor;
                $cliente->save();

                break;

            default:

                break;

            //$cliente->save();

        }
    }






    public function cancelarContrato(Request $request)
    {
        $comissao_id_cancelado = Comissoes::where("contrato_id",$request->comissao_id_cancelado)->first()->id;



        $contrato_id = Comissoes::where("id", $comissao_id_cancelado)->first()->contrato_id;
        $contrato = Contrato::where("id", $contrato_id)->first();
        $comissaoLancadas = ComissoesCorretoresLancadas
            ::where('comissoes_id', $comissao_id_cancelado)
            ->where('data_baixa', null)
            ->update(["atual" => 0, "cancelados" => 1]);
        if (!$comissaoLancadas) {
            return "error";
        }
        Contrato::where("id", $contrato->id)->update(['financeiro_id' => 12]);
        return "sucesso";
    }


    public function excluirEmpresarial(Request $request)
    {
        if($request->ajax()) {
            $id_contrato_empresarial = $request->id;
            $empresarial = ContratoEmpresarial::find($id_contrato_empresarial);
            $status = true;

            $comissao = Comissoes::where("contrato_empresarial_id",$empresarial->id)->first()->id;
            if(!ComissoesCorretoresLancadas::where("comissoes_id",$comissao)->delete()) {
                $status = false;
            }
            if(!Comissoes::where("contrato_empresarial_id",$empresarial->id)->delete()) {
                $status = false;
            }

            if(!ContratoEmpresarial::find($id_contrato_empresarial)->delete()) {
                $status = false;
            }
            return $status;
        }
    }

    public function cancelarEmpresarial(Request $request)
    {
        $empresara = ContratoEmpresarial::find($request->id);
        $empresara->financeiro_id = 12;
        if($empresara->save()) {
            return true;
        } else {
            return false;
        }
    }





    public function excluirCliente(Request $request)
    {



        if ($request->ajax()) {
            $id_contrato = $request->id;
            $contrato = Contrato::find($id_contrato);
            $cliente = Cliente::find($contrato->cliente_id);

            $comissao = Comissoes::where("contrato_id",$contrato->id)->first()->id;
            ComissoesCorretoresLancadas::where("comissoes_id",$comissao)->delete();
            Comissoes::where("contrato_id",$contrato->id)->delete();
            Contrato::where("cliente_id",$cliente->id)->delete();
            Cliente::find($cliente->id)->delete();

//            if ($id_cliente != null) {
//                $d = Cliente::where("id", $id_cliente)->delete();
//                if ($d) {
//                    return $this->recalcularColetivo();
//                } else {
//                    return "error";
//                }
//            }
        }

    }



    public function zerarTabelaFinanceiro()
    {
        return [];
    }

    public function emAnaliseEmpresarial()
    {
        $id = request()->id;

        $contrato = ContratoEmpresarial::find($id);
        $contrato->data_analise = date("Y-m-d");
        $contrato->financeiro_id = 5;

        if($contrato->save()) {
            return date("d/m/Y");
        } else {
            return "error";
        }
    }

    public function baixaDaDataEmpresarial(Request $request)
    {
        $id_contrato = $request->id;
        $contrato = ContratoEmpresarial::find($id_contrato);
        $comissao_id = Comissoes::where("contrato_empresarial_id",$contrato->id)->first()->id;


        $parcela = ComissoesCorretoresLancadas::where("comissoes_id",$comissao_id)->where("status_financeiro",0)->where("status_gerente",0)->first()->parcela;



        switch ($parcela) {
            case 1:
                $contrato->financeiro_id = 6;
                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",1)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->save();
                }

                ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",2)
                    ->update(['atual'=>1]);

                if($contrato->save()) {
                    return [
                        "baixa" => $request->data_baixa

                    ];
                } else {
                    return "error";
                }

                break;

            case 2:
                $contrato->financeiro_id = 7;
                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",2)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }

                ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",3)
                    ->update(['atual'=>1]);

                if($contrato->save()) {
                    return [
                        "baixa" => $request->data_baixa

                    ];
                } else {
                    return "error";
                }




                break;

            case 3:
                $contrato->financeiro_id = 8;
                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",3)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }

                ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",4)
                    ->update(['atual'=>1]);

                if($contrato->save()) {
                    return [
                        "baixa" => $request->data_baixa

                    ];
                } else {
                    return "error";
                }


                break;

            case 4:
                $contrato->financeiro_id = 9;
                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",4)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }
                ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",5)
                    ->update(['atual'=>1]);

                if($contrato->save()) {
                    return [
                        "baixa" => $request->data_baixa

                    ];
                } else {
                    return "error";
                }




                break;

            case 5:
                $contrato->financeiro_id = 10;
                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",5)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }

                ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",6)
                    ->update(['atual'=>1]);

                if($contrato->save()) {
                    return [
                        "baixa" => $request->data_baixa

                    ];
                } else {
                    return "error";
                }




                break;

            case 6:
                $contrato->financeiro_id = 11;
                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",6)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }
                //ContratoEmpresarial::where($id_contrato)->where("parcela",6)->update(["atual" => 1]);
                $comissaoCorretora = ComissoesCorretoraLancadas
                    ::where('comissoes_id',$comissao_id)
                    ->where('parcela',6)
                    ->first();
                if(isset($comissaoCorretora) && $comissaoCorretora) {
                    $comissaoCorretora->status_financeiro = 1;
                    $comissaoCorretora->data_baixa = $request->data_baixa;
                    $comissaoCorretora->save();
                }
                if($contrato->save()) {
                    return [
                        "baixa" => $request->data_baixa

                    ];
                } else {
                    return "error";
                }

                break;





        }

        //return $this->recalcularEmpresarial();
    }








    public function emAnaliseColetivo()
    {
        $contrato_id = request()->id;
        $contrato = Contrato::find($contrato_id);
        $contrato->data_analise = date("Y-m-d");
        $contrato->financeiro_id = 2;
        if($contrato->save()) {
            return date("d/m/Y");
        } else {
            return "error";
        }
    }

    public function emissaoColetivo()
    {
        $contrato_id = request()->id;
        $contrato = Contrato::find($contrato_id);
        $contrato->data_emissao = date("Y-m-d");
        $contrato->financeiro_id = 3;

        if($contrato->save()) {
            return date("d/m/Y");
        } else {
            return "error";
        }
    }

    public function modalEmpresarialGerente(Request $request)
    {
        $id = $request->id;
        $dados = ContratoEmpresarial
            ::where("id", $id)
            ->select("*")
            ->selectRaw("(select name from users where users.id = contrato_empresarial.user_id) as vendedor")
            ->selectRaw("(select nome from planos where planos.id = contrato_empresarial.plano_id) as plano")
            ->selectRaw("(select nome from tabela_origens where tabela_origens.id = contrato_empresarial.tabela_origens_id) as tabela_origem")
            ->with(
                [
                    "financeiro",
                    "comissao",
                    "comissao.comissoesLancadas",
                    'comissao.comissaoAtualFinanceiro',
                    'comissao.comissaoAtualLast'
                ]
            )
            ->first();

        return view('financeiro.modal-empresarial-gerente',[
           'dados' => $dados
        ]);
    }



    public function modalEmpresarial(Request $request)
    {
        $id = $request->id;
        $dados = ContratoEmpresarial
            ::where("id", $id)
            ->select("*")
            ->selectRaw("(select name from users where users.id = contrato_empresarial.user_id) as vendedor")
            ->selectRaw("(select nome from grupoamerica.planos where planos.id = contrato_empresarial.plano_id) as plano")
            ->selectRaw("(select nome from grupoamerica.tabela_origens where tabela_origens.id = contrato_empresarial.tabela_origens_id) as tabela_origem")
            ->with(
                ["financeiro",
                    "comissao",
                    "comissao.comissoesLancadas",
                    'comissao.comissaoAtualFinanceiro',
                    'comissao.comissaoAtualLast'
                ]
            )
            ->first();


        $planos = Plano::where("empresarial",1)->where("id","!=",6)->where("id","!=",10)->where("id","!=",11)->where("id","!=",4)->get();
        $tabelas_origens = TabelaOrigens::all();

        $corretores = User::where("corretora_id",auth()->user()->corretora_id)->where("ativo",1)->get();

        $vendedor = $request->input('vendedor');
        $plano = $request->input('plano');
        $origens = $request->input('origens');

        $razao_social = $request->input('razao_social');
        $cnpj = $request->input('cnpj');
        $vidas = $request->input('vidas');

        $celular = $request->input('celular');
        $email = $request->input('email');

        $responsavel = $request->input('responsavel');
        $cidade = $request->input('cidade');
        $uf = $request->input('uf');
        $plano_contrado = $request->input('plano_contratado');

        $codigo_corretora = $request->input('codigo_corretora');
        $codigo_saude = $request->input('codigo_saude');
        $codigo_odonto = $request->input('codigo_odonto');
        $senha_cliente = $request->input('senha_cliente');

        $valor_saude = $request->input('valor_saude');
        $valor_odonto = $request->input('valor_odonto');
        $valor_total = $request->input('valor_total');
        $taxa_adesao = $request->input('taxa_adesao');
        $data_analise = $request->input('data_analise');


        $valor_boleto = $request->input('valor_boleto');
        $vencimento_boleto = $request->input('vencimento_boleto');
        $data_boleto = $request->input('data_boleto');

        $codigo_externo = $request->input('codigo_externo');

        $texto_empresarial = "";
        if ($plano_contrado == 1) {
            $texto_empresarial = "C/ Copart + Odonto";
        } else if ($plano_contrado == 2) {
            $texto_empresarial = "C/ Copart Sem Odonto";
        } else if ($plano_contrado == 3) {
            $texto_empresarial = "Sem Copart + Odonto";
        } else if ($plano_contrado == 4) {
            $texto_empresarial = "Sem Copart Sem Odonto";
        } else {
            $texto_empresarial = "";
        }



        // Retorne uma view ou JSON conforme sua necessidade
        return view('financeiro.modal-empresarial', compact(
            'dados',
            'vendedor',
            'planos',
            'plano',
            'texto_empresarial',
            'origens',
            'razao_social',
            'tabelas_origens',
            'cnpj',
            'vidas',
            'corretores',
            'celular',
            'email',
            'responsavel',
            'cidade',
            'codigo_externo',
            'uf',
            'plano_contrado',
            'codigo_corretora',
            'codigo_saude',
            'codigo_odonto',
            'senha_cliente',
            'valor_saude',
            'valor_odonto',
            'valor_total',
            'taxa_adesao',
            'valor_boleto',
            'vencimento_boleto',
            'data_boleto',
            'data_analise',
            'id'
        ));
    }

    public function changeValoresEmpresarial()
    {
        $valor_saude    = str_replace(".", "", request()->valor_saude);
        $valor_odonto   = (float) str_replace(".", "", request()->valor_odonto);
        $total_plano    = (float) str_replace(".", "", request()->total_plano);
        $plano_adesao   = (float) str_replace(".", "", request()->plano_adesao);
        $taxa_adesao    = (float) str_replace(".", "", request()->taxa_adesao);
        $valor_boleto   = (float) str_replace(".", "", request()->valor_boleto);
        $cliente_id     = request()->cliente_id;
        $alvo           = request()->alvo;
        $contrato       = ContratoEmpresarial::find($cliente_id);
        switch($alvo) {
            case "valor_saude":
                $contrato->valor_plano_saude = $valor_saude;
                $contrato->valor_plano = $valor_saude + $valor_odonto;
                $contrato->valor_total = $contrato->valor_plano + $taxa_adesao;
                $contrato->save();

            break;
        }


    }





    public function changeEmpresarial()
    {
        $contrato_id    = request()->cliente_id;
        $user_id        = request()->user_id;
        $corretora_id   = User::find($user_id)->corretora_id;
        $estagiario     = User::where("id",$user_id)->first()->estagiario;
        $contrato       = ContratoEmpresarial::find($contrato_id);
        $comissao       = Comissoes::where("contrato_empresarial_id",$contrato_id)->first();

        if($estagiario == 0) {
            ComissoesCorretoresLancadas::where("comissoes_id",$comissao->id)->update(["valor" => 0]);
            $comissoes_configuradas_corretor = ComissoesCorretoresConfiguracoes
                ::where("plano_id", $contrato->plano_id)
                ->where("administradora_id", 4)
                ->where("user_id", $user_id)
                ->get();
            $par = 0;
            if (count($comissoes_configuradas_corretor) >= 1) {
                foreach($comissoes_configuradas_corretor as $c) {
                    $par++;
                    $valor_comissao = $contrato->valor_plano;
                    $comissaoVendedor = ComissoesCorretoresLancadas::where("comissoes_id",$comissao->id)->where("parcela",$par)->first();
                    $comissaoVendedor->valor = ($valor_comissao * $c->valor) / 100;
                    $comissaoVendedor->save();
                }
            } else {
                $dados = ComissoesCorretoresDefault
                    ::where("plano_id", $contrato->plano_id)
                    ->where("administradora_id", 4)
                    ->where("corretora_id",$contrato->corretora_id)
                    ->get();
                foreach ($dados as $c) {
                    $par++;
                    $valor_comissao_default = $contrato->valor_plano;
                    $comissaoVendedor = ComissoesCorretoresLancadas::where("comissoes_id",$comissao->id)->where("parcela",$par)->first();
                    $comissaoVendedor->valor = ($valor_comissao_default * $c->valor) / 100;
                    $comissaoVendedor->save();
                }
                //}
//                /****FIm SE Comissoes Lancadas */
            }

            $contrato->user_id = $user_id;
            $contrato->save();

            $comissao->user_id = $user_id;
            $comissao->save();

        } else {

            ClienteEstagiario::updateOrCreate(
                [
                    'cliente_id' => $contrato->id, // Condição de busca
                ],
                [
                    'user_id' => $user_id,       // Dados para atualizar ou criar
                    'data' => now(),
                ]
            );
        }
        return $corretora_id;




    }


    public function changeColetivo()
    {
        $contrato_id = request()->id_contrato;
        $user_id = request()->user_id;
        $estagiario = User::where("id",$user_id)->first()->estagiario;
        $contrato = Contrato::find($contrato_id);
        $cliente = Cliente::where("id",$contrato->cliente_id)->first();
        $comissao = Comissoes::where('contrato_id',$contrato->id)->first();
        $corretora_id = User::find($user_id)->corretora_id;

        if($estagiario == 0) {


            ComissoesCorretoresLancadas::where("comissoes_id",$comissao->id)->update(["valor" => 0]);
            $comissoes_configuradas_corretor = ComissoesCorretoresConfiguracoes
                ::where("plano_id", $contrato->plano_id)
                ->where("administradora_id", $contrato->administradora_id)
                ->where("user_id", $user_id)
                ->get();
            $par = 0;
            if (count($comissoes_configuradas_corretor) >= 1) {
                foreach($comissoes_configuradas_corretor as $c) {
                    $par++;
                    $valor_comissao = $contrato->valor_adesao;
                    $comissaoVendedor = ComissoesCorretoresLancadas::where("comissoes_id",$comissao->id)->where("parcela",$par)->first();
                    $comissaoVendedor->valor = ($valor_comissao * $c->valor) / 100;
                    $comissaoVendedor->save();
                }
            } else {
                $dados = ComissoesCorretoresDefault
                    ::where("plano_id", $contrato->plano_id)
                    ->where("administradora_id", $contrato->administradora_id)
                    ->where("corretora_id",$cliente->corretora_id)
                    ->get();
                foreach ($dados as $c) {
                    $par++;
                    $valor_comissao_default = $contrato->valor_adesao;
                    $comissaoVendedor = ComissoesCorretoresLancadas::where("comissoes_id",$comissao->id)->where("parcela",$par)->first();
                    $comissaoVendedor->valor = ($valor_comissao_default * $c->valor) / 100;
                    $comissaoVendedor->save();
                }
                //}
//                /****FIm SE Comissoes Lancadas */
            }

            $cliente->user_id = $user_id;
            $cliente->save();

            $comissao->user_id = $user_id;
            $comissao->save();

        } else {

            ClienteEstagiario::updateOrCreate(
                [
                    'cliente_id' => $cliente->id, // Condição de busca
                ],
                [
                    'user_id' => $user_id,       // Dados para atualizar ou criar
                    'data' => now(),
                ]
            );
        }
        return $corretora_id;
    }






    public function changeIndividual()
    {

        $contrato_id = request()->id_cliente;
        $user_id = request()->user_id;

        $estagiario = User::where("id",$user_id)->first()->estagiario;

        $contrato = Contrato::find($contrato_id);
        $cliente = Cliente::where("id",$contrato->cliente_id)->first();
        $comissao = Comissoes::where('contrato_id',$contrato->id)->first();

        $corretora_id = User::find($user_id)->corretora_id;


        if($estagiario == 0) {


            ComissoesCorretoresLancadas::where("comissoes_id",$comissao->id)->update(["valor" => 0]);
            $comissoes_configuradas_corretor = ComissoesCorretoresConfiguracoes
                ::where("plano_id", 1)
                ->where("administradora_id", 4)
                ->where("user_id", $user_id)
                //->where("tabela_origens_id", 2)
                ->get();
            $par = 0;
            if (count($comissoes_configuradas_corretor) >= 1) {
                foreach ($comissoes_configuradas_corretor as $c) {
                    $par++;
                    $valor_comissao = $contrato->valor_adesao;
                    $comissaoVendedor = ComissoesCorretoresLancadas::where("comissoes_id",$comissao->id)->where("parcela",$par)->first();
                    $comissaoVendedor->valor = ($valor_comissao * $c->valor) / 100;
                    $comissaoVendedor->save();
                }
            } else {
                $dados = ComissoesCorretoresDefault
                    ::where("plano_id", 1)
                    ->where("administradora_id", 4)
                    ->where("corretora_id",$cliente->corretora_id)
                    ->get();
                foreach ($dados as $c) {
                    $par++;
                    $valor_comissao_default = $contrato->valor_adesao;
                    $comissaoVendedor = ComissoesCorretoresLancadas::where("comissoes_id",$comissao->id)->where("parcela",$par)->first();
                    $comissaoVendedor->valor = ($valor_comissao_default * $c->valor) / 100;
                    $comissaoVendedor->save();
                }
                //}
//                /****FIm SE Comissoes Lancadas */
            }

            $cliente->user_id = $user_id;
            $cliente->save();

            $comissao->user_id = $user_id;
            $comissao->save();

        } else {

            ClienteEstagiario::updateOrCreate(
                [
                    'cliente_id' => $cliente->id, // Condição de busca
                ],
                [
                    'user_id' => $user_id,       // Dados para atualizar ou criar
                    'data' => now(),
                ]
            );



        }


        return $corretora_id;



    }





    public function modalIndividual()
    {

        $id = request()->id;
        $contratos = Contrato
            ::where("id",$id)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.comissoesLancadas','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->first();

        $users = User::where("corretora_id",auth()->user()->corretora_id)->where('ativo',1)->get();

        return view('financeiro.modal-individual',[
            "users" => $users,
            "dados" => $contratos,
            "corretor" => request()->corretor,
            "id" => request()->id,
            "vidas" => request()->vidas,
            "status" => request()->status,
            "rua" => request()->rua,
            "cpf" => request()->cpf,
            "data_criacao" => request()->data_criacao,
            "data_nascimento" => $contratos->clientes->data_nascimento,
            "email" => request()->email,
            "celular" => request()->celular,
            "codigo_externo" => request()->codigo_externo,
            "valor_plano" => request()->valor_plano,
            "cliente" => request()->cliente,
            "cidade" => request()->cidade,
            "cep" => request()->cep,
            "bairro" => request()->bairro,
            "carteirinha" => request()->carteirinha,
            "complemento" => request()->complemento,
            "uf" => request()->uf,
            "valor_adesao" => request()->valor_adesao,
            "data_vigencia" => request()->data_vigencia,
            "data_boleto" => request()->data_boleto,
            "user_id" => request()->user_id
        ]);
    }

    public function changeAdministradora()
    {
        $administradora_id = request()->administradora_id;
        $contrato_id = request()->id_cliente;

        $contrato = Contrato::find($contrato_id);
        $contrato->administradora_id = $administradora_id;
        $contrato->save();

        $comissao = Comissoes::where("contrato_id",$contrato->id)->first();
        $comissao->administradora_id = $administradora_id;
        $comissao->save();


        return "Olaaaaaa";
    }


    public function modalColetivo()
    {

        $id = request()->id;

        $contratos = Contrato
            ::where("id",$id)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.comissoesLancadas','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->first();

        $users = User
            ::where("corretora_id",auth()->user()->corretora_id)
            ->get();

        $administradoras = Administradora::where("id","!=",4)->get();

        return view('financeiro.modal-coletivo',[
            "administradoras" => $administradoras,
            "administradora_id" => $contratos->administradora->id,
            "financeiro" => request()->financeiro,
            "users" => $users,
            "status" => request()->status,
            "contrato" => request()->contrato,
            "administradora" => request()->administradora,
            "dados" => $contratos,
            "cliente" => request()->cliente,
            "cpf" => request()->cpf,
            "codigo" => request()->codigo_externo,
            "rua" => request()->rua,
            "cidade" => request()->cidade,
            "bairro" => request()->bairro,
            "codigo_externo" => request()->codigo_externo,
            "email" => request()->email,
            "cep" => request()->cep,
            "corretor" => request()->corretor,
            "complemento" => request()->complemento,
            "nascimento" => request()->nascimento,
            "uf" => request()->uf,
            "valor_adesao" => request()->valor_adesao,
            "valor_plano" => request()->valor_plano,
            "desconto_corretora" => request()->desconto_corretora,
            "desconto_corretor" => request()->desconto_corretor,
            "id" => request()->id,
            "fone" => request()->fone,
            "quantidade_parcelas" => request()->quantidade_parcelas,
            "operadora_valor" => request()->operadora_valor,
            "cliente_id" => $contratos->clientes->user->id,
            "dependente_nome" => $contratos->clientes->dependentes->nome ?? '',
            "dependente_cpf" => $contratos->clientes->dependentes->cpf ?? ''
        ]);
    }

    public function coletivoEmGeral(Request $request)
    {

        if ($request->ajax()) {
            $corretora_id = $request->corretora_id == null ? auth()->user()->corretora_id : $request->corretora_id;
            $cacheKey = "geralColetivoGeral_" . now()->format('YmdHis');
            $tempoDeExpiracao = 60;

            if($request->has('refresh') && $request->refresh == 1) {
                Cache::forget($cacheKey);
            }






            $dados = Cache::remember($cacheKey, $tempoDeExpiracao, function () use($corretora_id) {
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
                    ->where('contratos.plano_id', 3)
                    ->groupBy('contratos.id');

                if ($corretora_id != 0) {
                    $query->where('clientes.corretora_id', $corretora_id);
                }

                return $query->get();
            });

            return response()->json(['data' => $dados]);
        }
    }


    public function listarContratoEmpresaPendentes(Request $request)
    {
        $corretora_id = $request->corretora_id == null ? auth()->user()->corretora_id : $request->corretora_id;
        if ($request->ajax()) {
            $cacheKey = 'listarContratoEmpresaPendentes';
            $tempoDeExpiracao = 0;
            $resultado = Cache::remember($cacheKey, $tempoDeExpiracao, function () use($corretora_id) {
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

                    ->join(DB::raw('grupoamerica.planos'), 'planos.id', '=', 'contrato_empresarial.plano_id')
                    ->join(DB::raw('grupoamerica.tabela_origens'), 'tabela_origens.id', '=', 'contrato_empresarial.tabela_origens_id')
                    ->join('estagio_financeiros', 'estagio_financeiros.id', '=', 'contrato_empresarial.financeiro_id')

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
                    ->groupBy('comissoes_corretores_lancadas.comissoes_id');

                if ($corretora_id != 0) {
                    $query->where('contrato_empresarial.corretora_id', $corretora_id);
                }

                return $query->get();
            });

            return response()->json(['data' => $resultado]);
        }
    }









    public function sincronizarCancelados(Request $request)
    {
        set_time_limit(300);
        $filename = uniqid() . ".xlsx";
        if (move_uploaded_file($request->file, $filename)) {
            $filePath = base_path("public/{$filename}");
            $cpfs = [];
            $reader = ReaderEntityFactory::createReaderFromFile($filePath);
            $reader->open($filePath);
            $cidade = "";
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                    if($rowNumber >= 1) {
                        $cells = $row->getCells();
                        $cart = $cells[1]->getValue();
                        $status = $cells[4]->getValue();
                        $nome = $cells[3]->getValue();
                        if($status == "CANCELADO") {
                            $carteirinha = Cliente
                                ::select('clientes.*')
                                ->join('users','users.id','=','clientes.user_id')
                                ->join('contratos','contratos.cliente_id','=','clientes.id')
                                ->where('clientes.cateirinha',$cart)
                                ->with(['user','contrato','contrato.comissao','contrato.comissao.comissoesLancadas'])
                                ->first();

                            if($carteirinha) {
                                $c = Contrato::find($carteirinha->contrato->id);
                                $c->financeiro_id = 12;
                                $c->save();
                            }

                        }



//                        $nome = $cells[7]->getValue();
//                        $codi = $cells[4]->getValue();
//                        $carteirinha = Cliente
//                            ::select('clientes.*')
//                            ->join('users','users.id','=','clientes.user_id')
//                            ->join('contratos','contratos.cliente_id','=','clientes.id')
//                            ->where('clientes.nome','like',$nome)
//                            ->where('users.codigo_vendedor',$codi)
//                            ->with(['user','contrato','contrato.comissao','contrato.comissao.comissoesLancadas'])
//                            ->first();
//
//                        if($carteirinha && $carteirinha->cateirinha == null) {
//                            $carteirinha->cateirinha = $cells[5]->getValue();
//                            $carteirinha->save();
//                            $cc = Contrato::find($carteirinha->contrato->id);
//                            $cc->created_at = $cells[6]->getValue()->format('Y-m-d');
//                            $cc->save();
//                        } else {
//                        }

                        /*
                        //Realizar Baixas
                        if($carteirinha && $cells[11]->getValue() == "LIQUIDADO") {
                            $cliente = Cliente::select('clientes.*')
                                ->join('users', 'users.id', '=', 'clientes.user_id')
                                ->where('clientes.nome','like',$nome)
                                //->where('clientes.cateirinha',$cells[7]->getValue())
                                ->where('users.codigo_vendedor',$codi)
                                ->with(['user', 'contrato','contrato.comissao','contrato.comissao.comissoesLancadas'])
                                ->first();
                        }
                        */


                    }
                }
            }
            return "sucesso";
        }
    }

    public function atualizarDados(Request $request)
    {
        $clientes = Cliente::with('contrato')->where("dados",0)->whereRaw("baixa IS NULL")
            ->chunkById(50,function($clientes) {
                foreach($clientes as $v) {
                    $url = "https://api-hapvida.sensedia.com/DESATIVADO_/wssrvonline/v1/beneficiario?cpf=$v->cpf";
                    $ch = curl_init($url);
                    curl_setopt($ch,CURLOPT_URL,$url);
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                    $resultado = (array) json_decode(curl_exec($ch),true);
                    foreach($resultado as $rr) {
                        if($rr['tipoPlanoC'] == "SAUDE" AND $rr['nomeEmpresa'] == "I N D I V I D U A L" AND $rr['nuMatriculaEmpresa'] == $v->codigo_externo) {
                            $cliente = Cliente::where("codigo_externo",$v->codigo_externo)->first();
                            $cliente->cidade = mb_convert_case($rr['cidadeEndereco'], MB_CASE_TITLE, "UTF-8");
                            $cliente->cep = $rr['cepEndereco'];
                            $cliente->rua = $rr['ruaEndereco'];
                            $cliente->bairro =  mb_convert_case($rr['bairroEndereco'], MB_CASE_TITLE, "UTF-8");
                            $cliente->complemento = ($rr['complementoEndereco'] != null ? mb_convert_case($rr['complementoEndereco'], MB_CASE_TITLE, "UTF-8") : null);
                            $cliente->uf = $rr['ufEndereco'];
                            $cliente->pessoa_fisica = 1;
                            $cliente->pessoa_juridica = 0;
                            $cliente->nm_plano = $rr['nmPlano'];
                            $cliente->numero_registro_plano = $rr['nuRegistroPlano'];
                            $cliente->rede_plano = $rr['redePlano'];
                            $cliente->tipo_acomodacao_plano = $rr['tipoAcomodacaoPlano'];
                            $cliente->segmentacao_plano = $rr['segmentacaoPlano'];
                            $cliente->cateirinha = $rr['cdUsuario'];
                            $cliente->save();
                        }
                    }
                }
            });

        Cliente::where("dados",0)->update(["dados"=>1]);
        //Cliente::whereRaw("baixa IS NULL")->update(["baixa"=>date('Y-m-d')]);

        return "sucesso";
    }

    public function sincronizarBaixas(Request $request)
    {
        Cliente::where("dados",1)->whereRaw("baixa IS NULL")
            ->chunkById(50,function($clientes) {
                foreach($clientes as $cc) {
                    $contrato = Contrato::where('cliente_id',$cc->id)->first()->id;
                    $comissao_id = Comissoes::where("contrato_id",$contrato)->first()->id;
                    ComissoesCorretoresLancadas::where("comissoes_id",$comissao_id)->update(['documento_gerador'=>substr($cc->cateirinha,0,-3)]);
                    $url = "https://api-hapvida.sensedia.com/DESATIVADO_/wssrvonline/v1/beneficiario/$cc->cateirinha/financeiro/historico";
                    $curl = curl_init($url);
                    curl_setopt($curl,CURLOPT_URL, $url);
                    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
                    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
                    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
                    $resp=curl_exec($curl);
                    curl_close($curl);
                    $dados=json_decode($resp);
                    $data="";
                    $docu="";
                    if(!empty($dados) && $dados != null) {
                        foreach($dados as $d) {
                            if($d->dtPagamento != null && $d->cdStatus != 16) {
                                // $data = implode("-",array_reverse(explode('/',$d->dtVencimento)));
                                $mes = explode("/",$d->dtVencimento);
                                $data_baixa = implode("-",array_reverse(explode('/',$d->dtPagamento)));
                                $docu = $d->cdDocumentoGerador;
                                ComissoesCorretoresLancadas
                                    ::whereRaw("DATE_FORMAT(data,'%m') = ?",$mes[1])
                                    ->where("documento_gerador",$docu)
                                    ->where("status_financeiro","!=",1)
                                    ->update([
                                        'status_financeiro'=>1,
                                        'valor_pago' => $d->vlObrigacao,
                                        'data_baixa'=>$data_baixa
                                    ]);

                                ComissoesCorretoraLancadas
                                    ::whereRaw("DATE_FORMAT(data,'%m') = ?",$mes[1])
                                    ->where("comissoes_id",$comissao_id)
                                    ->where("status_financeiro","!=",1)
                                    ->update(['status_financeiro'=>1]);
                            }

                            if($d->cdStatus == 8 && $d->dsStatus == "CANCELADO") {
                                $canc = new ComissoesCorretoresCancelados();
                                $canc->comissoes_id = $comissao_id;
                                $canc->data = implode("-",array_reverse(explode('/',$d->dtVencimento)));
                                $canc->documento_gerador = $d->cdDocumentoGerador;
                                $canc->save();
                            }


                        }
                    }

                }

            });


        $comissoes = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            //->where("status_gerente",1)
            ->where("parcela","!=",1)
            //->where("comissoes_id",$comissoes>id)

            ->get();
        foreach($comissoes as $cc) {

            switch($cc->parcela) {
                case 2:
                    $contrato_id = Comissoes::where("id",$cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id",$contrato_id)->update([
                        "financeiro_id" => 6
                    ]);

                    break;

                case 3:
                    $contrato_id = Comissoes::where("id",$cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id",$contrato_id)->update([
                        "financeiro_id" => 7
                    ]);
                    break;

                case 4:
                    $contrato_id = Comissoes::where("id",$cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id",$contrato_id)->update([
                        "financeiro_id" => 8
                    ]);
                    break;

                case 5:
                    $contrato_id = Comissoes::where("id",$cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id",$contrato_id)->update([
                        "financeiro_id" => 9
                    ]);
                    break;

                case 6:
                    $contrato_id = Comissoes::where("id",$cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id",$contrato_id)->update([
                        "financeiro_id" => 10
                    ]);
                    break;

                default:
                    $contrato_id = Comissoes::where("id",$cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id",$contrato_id)->update([
                        "financeiro_id" => 11
                    ]);
                    break;
            }
        }

        $dados = DB::select('
            SELECT * FROM comissoes_corretores_cancelados
            INNER JOIN comissoes_corretores_lancadas ON
            comissoes_corretores_cancelados.documento_gerador = comissoes_corretores_lancadas.documento_gerador
            WHERE MONTH(comissoes_corretores_lancadas.`data`) = MONTH(comissoes_corretores_cancelados.data)
            AND valor_pago IS NULL
            GROUP BY comissoes_corretores_cancelados.documento_gerador
        ');

        foreach($dados as $d) {
            $contrato_id = Comissoes::where("id",$d->comissoes_id)->first()->contrato_id;
            Contrato::where("id",$contrato_id)->update(["financeiro_id"=>12]);
        }

        $canc = DB::select("
            SELECT * FROM comissoes_corretores_cancelados
            INNER JOIN comissoes_corretores_lancadas ON
            comissoes_corretores_cancelados.documento_gerador = comissoes_corretores_lancadas.documento_gerador
            WHERE MONTH(comissoes_corretores_lancadas.`data`) = MONTH(comissoes_corretores_cancelados.data)
            AND valor_pago IS NULL AND comissoes_corretores_lancadas.`data` >= DATE(NOW() - INTERVAL 6 MONTH)
            GROUP BY comissoes_corretores_cancelados.documento_gerador
        ");

        foreach($canc as $c) {
            DB::table('comissoes_corretores_lancadas')
                ->where("id","=",$c->id)
                ->whereRaw("data_baixa IS NULL")
                ->update(['cancelados'=>1]);
        }

        Cliente::whereRaw("baixa IS NULL")->update(["baixa"=>date('Y-m-d')]);


        return "sucesso";
    }

    public function sincronizarDadosColetivo(Request $request)
    {

        $filename = uniqid() . ".xlsx";
        if (move_uploaded_file($request->file, $filename)) {
            $filePath = base_path("public/{$filename}");
            $reader = ReaderEntityFactory::createReaderFromFile($filePath);
            $reader->open($filePath);
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                    $cells = $row->getCells();
                    if ($rowNumber >= 2) {

                        $tabela_origens_id = TabelaOrigens::where("nome", "LIKE", $cells[3]->getValue())->first()->id;
                        $administradora_id = Administradoras::where("nome", "LIKE", $cells[2]->getValue())->first()->id;


                        $user_id = cidadeCodigoVendedor::where("codigo_vendedor", $cells[0]->getValue())
                            ->where("tabela_origens_id", $tabela_origens_id)
                            ->first()
                            ->user_id;


                        $cpf = mb_strlen($cells[6]->getValue()) == 11 ? $cells[6]->getValue() : str_pad($cells[6]->getValue(), 11, "000", STR_PAD_LEFT);
                        //$user_id = User::where('codigo_vendedor', $cells[0]->getValue())->first()->id;

                        $nascimento = $cells[9]->getValue()->format('Y-m-d');
                        $criacao = $cells[18]->getValue()->format('Y-m-d');

                        $alvo = trim($cells[21]->getValue());
                        $id_acomodacao = Acomodacao::where("nome", "LIKE", "%$alvo%")->first()->id;
                        $cliente = new Cliente();
                        $cliente->user_id = $user_id;
                        $cliente->nome = mb_convert_case($cells[4]->getValue(), MB_CASE_TITLE, "UTF-8");
                        $cliente->celular = $cells[11]->getValue();
                        $cliente->cpf = $cpf;
                        $cliente->data_nascimento = $nascimento;
                        $cliente->pessoa_fisica = 1;
                        $cliente->pessoa_juridica = 0;
                        $cliente->codigo_externo = $cells[5]->getValue();
                        $cliente->cep = $cells[12]->getValue();
                        $cliente->cidade = $cells[13]->getValue();
                        $cliente->bairro = $cells[14]->getValue();
                        $cliente->rua = $cells[15]->getValue();
                        $cliente->complemento = $cells[16]->getValue();
                        $cliente->uf = $cells[17]->getValue();

                        $cliente->created_at = $criacao;
                        $cliente->quantidade_vidas = $cells[25]->getValue();
                        $cliente->email = mb_convert_case($cells[10]->getValue(), MB_CASE_LOWER, "UTF-8");
                        $cliente->save();

                        if (!empty($cells[7]->getValue()) && $cells[7]->getValue() != null) {
                            $dependente = new Dependentes();
                            $cpf_responsavel = mb_strlen($cells[8]->getValue()) == 11 ? $cells[8]->getValue() : str_pad($cells[8]->getValue(), 11, "000", STR_PAD_LEFT);
                            $dependente->cliente_id = $cliente->id;
                            $dependente->nome = mb_convert_case($cells[7]->getValue(), MB_CASE_TITLE, "UTF-8");
                            $dependente->cpf = $cpf_responsavel;
                            $dependente->save();
                        }


                        $coparticipacao = 0;
                        if ($cells[22]->getValue() == 1 && $cells[23]->getValue() == 0) {
                            $coparticipacao = 1;
                        } else if ($cells[22]->getValue() == 0 && $cells[23]->getValue() == 1) {
                            $coparticipacao = 0;
                        } else {
                            $coparticipacao = 0;
                        }


                        $contrato = new Contrato();
                        $contrato->acomodacao_id = $id_acomodacao;
                        $contrato->cliente_id = $cliente->id;
                        $contrato->administradora_id = $administradora_id;
                        $contrato->tabela_origens_id = $tabela_origens_id;
                        $contrato->plano_id = 3;
                        $contrato->financeiro_id = 1;
                        $contrato->data_vigencia = $cells[19]->getValue()->format('Y-m-d');
                        $contrato->codigo_externo = $cells[5]->getValue();
                        // $contrato->data_boleto = implode("-",array_reverse(explode("/",$cells[21]->getValue())));
                        $contrato->data_boleto = $cells[20]->getValue()->format('Y-m-d');
                        $contrato->valor_adesao = empty($cells[27]->getValue()) && $cells[27]->getValue() == null ? $cells[27]->getValue() : $cells[27]->getValue();
                        $contrato->valor_plano = $cells[26]->getValue();
                        $contrato->coparticipacao = $coparticipacao;
                        $contrato->odonto = $cells[24];
                        $contrato->created_at = $cells[18]->getValue()->format('Y-m-d');
                        $contrato->desconto_corretor = "0,00";
                        $contrato->desconto_corretora = "0,00";
                        $contrato->save();

                        $comissao = new Comissoes();
                        $comissao->contrato_id = $contrato->id;
                        // $comissao->cliente_id = $contrato->cliente_id;
                        $comissao->user_id = $user_id;
                        // $comissao->status = 1;
                        $comissao->plano_id = 3;
                        $comissao->administradora_id = $administradora_id;
                        $comissao->tabela_origens_id = $tabela_origens_id;
                        $comissao->data = date('Y-m-d');
                        $comissao->save();

                        /* Comissao Corretor */
                        $comissoes_configuradas_corretor = ComissoesCorretoresConfiguracoes
                            ::where("plano_id", 3)
                            ->where("administradora_id", $administradora_id)
                            ->where("user_id", $user_id)
                            //->where("tabela_origens_id", $tabela_origens_id)
                            ->get();
                        $data_vigencia = $cells[19]->getValue()->format('Y-m-d');
                        $comissao_corretor_contagem = 0;
                        $comissao_corretor_default = 0;
                        if (count($comissoes_configuradas_corretor) >= 1) {
                            foreach ($comissoes_configuradas_corretor as $c) {
                                $comissaoVendedor = new ComissoesCorretoresLancadas();
                                $comissaoVendedor->comissoes_id = $comissao->id;
                                //$comissaoVendedor->user_id = auth()->user()->id;
                                $comissaoVendedor->parcela = $c->parcela;
                                if ($comissao_corretor_contagem == 0) {
                                    $comissaoVendedor->data = $cells[20]->getValue()->format('Y-m-d');
                                    //$comissaoVendedor->status_financeiro = 1;
                                    if ($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {
                                        //$comissaoVendedor->status_gerente = 1;
                                    }

                                } elseif ($comissao_corretor_contagem == 1) {
                                    $comissaoVendedor->data = date("Y-m-d H:i:s", strtotime($data_vigencia));
                                } else {
                                    $mes = $comissao_corretor_contagem - 1;
                                    $comissaoVendedor->data = date("Y-m-d H:i:s", strtotime($data_vigencia . "+{$mes}month"));

                                }
                                $comissaoVendedor->valor = ($cells[27]->getValue() * $c->valor) / 100;
                                $comissaoVendedor->save();
                                $comissao_corretor_contagem++;
                            }
                        } else {

                            $dados = ComissoesCorretoresDefault
                                ::where("plano_id", 3)
                                ->where("administradora_id", $administradora_id)
                                ->where("tabela_origens_id", $tabela_origens_id)
                                ->get();
                            foreach ($dados as $c) {
                                $comissaoVendedor = new ComissoesCorretoresLancadas();
                                $comissaoVendedor->comissoes_id = $comissao->id;
                                $comissaoVendedor->parcela = $c->parcela;


                                if ($comissao_corretor_default == 0) {
                                    $comissaoVendedor->data = $cells[20]->getValue()->format('Y-m-d');
                                    //$comissaoVendedor->status_financeiro = 1;
                                    if ($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {
                                        //$comissaoVendedor->status_gerente = 1;
                                    }

                                } elseif ($comissao_corretor_default == 1) {
                                    $comissaoVendedor->data = date("Y-m-d H:i:s", strtotime($data_vigencia));
                                } else {
                                    $mes = $comissao_corretor_default - 1;
                                    $comissaoVendedor->data = date("Y-m-d H:i:s", strtotime($data_vigencia . "+{$mes}month"));

                                }
                                $comissaoVendedor->valor = ($cells[27]->getValue() * $c->valor) / 100;
                                $comissaoVendedor->save();
                                $comissao_corretor_default++;
                            }
                        }

                        $comissoes_configurada_corretora = ComissoesCorretoraConfiguracoes
                            ::where("administradora_id", $administradora_id)
                            ->where('plano_id', 3)
                            ->where('tabela_origens_id', $tabela_origens_id)
                            ->get();

                        $comissoes_corretora_contagem = 0;
                        if (count($comissoes_configurada_corretora) >= 1) {
                            foreach ($comissoes_configurada_corretora as $cc) {
                                $comissaoCorretoraLancadas = new ComissoesCorretoraLancadas();
                                $comissaoCorretoraLancadas->comissoes_id = $comissao->id;
                                $comissaoCorretoraLancadas->parcela = $cc->parcela;
                                if ($comissoes_corretora_contagem == 0) {
                                    $comissaoCorretoraLancadas->data = $data_vigencia;

                                    // } else if($comissoes_corretora_contagem == 1) {
                                    //     $comissaoCorretoraLancadas->data = date("Y-m-d H:i:s",strtotime($data_vigencia));
                                    // } else {
                                    //     $mes = $comissoes_corretora_contagem - 1;
                                    //     $comissaoCorretoraLancadas->data = date("Y-m-d",strtotime($data_vigencia."+{$mes}month"));
                                } else {
                                    $comissaoCorretoraLancadas->data = date("Y-m-d", strtotime($data_vigencia . "+{$comissoes_corretora_contagem}month"));
                                }
                                $comissaoCorretoraLancadas->valor = ($cells[27]->getValue() * $cc->valor) / 100;
                                $comissaoCorretoraLancadas->save();
                                $comissoes_corretora_contagem++;
                            }
                        }


                    }
                }
            }


            return "sucesso";
        }
    }








}
