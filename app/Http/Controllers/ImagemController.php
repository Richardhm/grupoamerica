<?php

namespace App\Http\Controllers;

use App\Models\Administradora;
use App\Models\Carencia;
use App\Models\Desconto;
use App\Models\Pdf;
use App\Models\PdfExcecao;
use App\Models\Plano;
use App\Models\Tabela;
use App\Models\TabelaOrigens;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDFFile;
class ImagemController extends Controller
{

    public function criarPDF()
    {
        $com_coparticipacao = request()->comcoparticipacao  == "true" ? 1 : 0;
        $sem_coparticipacao = request()->semcoparticipacao  == "true" ? 1 : 0;
        $apenasvalores      = request()->apenasvalores     == "true" ? 1 : 0;
        $tipo_documento     = request()->tipo_documento;

        $ambulatorial = request()->ambulatorial;
        $cidade = request()->tabela_origem;
        $plano = request()->plano;
        $operadora = request()->operadora;

        $odonto = request()->odonto;


        $linhas = 0;

        $sql = "";
        $chaves = [];
        $somar_linhas = 0;
        foreach(request()->faixas[0] as $k => $v) {
            if($v != null AND $v != 0) {
                $sql .= " WHEN tabelas.faixa_etaria_id = {$k} THEN ${v} ";
                $chaves[] = $k;
                $somar_linhas += (int) $v;
            }
        }




        $keys = implode(",",$chaves);
        $linhas = count($chaves);



        $cidade_nome = TabelaOrigens::find($cidade)->nome;

        $plano_nome = Plano::find($plano)->nome;
        $linha_01 = "";
        $linha_02 = "";

        $cidade_uf = TabelaOrigens::find($cidade)->uf;
        $status_excecao = false;

        $hasTabelaOrigens = Pdf::where('plano_id', $plano)
            ->where('tabela_origens_id',$cidade)
            ->exists();
        if ($hasTabelaOrigens) {
            $pdf_copar = Pdf::where('plano_id', $plano)
                ->where('tabela_origens_id',$cidade)
                ->first();
            $itens = explode('|', $pdf_copar->linha02);
            $itensFormatados = array_map(function($item) {
                return trim($item); // Remove espaços extras
            }, $itens);
            $linha_01 = $itensFormatados[0];
            $linha_02 = $itensFormatados[1];
        } else {
            $pdf_copar = Pdf::where('plano_id', $plano)->first();
            $itens = explode('|', $pdf_copar->linha02);
            $itensFormatados = array_map(function($item) {
                return trim($item); // Remove espaços extras
            }, $itens);
            $linha_01 = $itensFormatados[0];
            $linha_02 = $itensFormatados[1];
        }

        $admin_nome = Administradora::find($operadora)->nome;

        $odonto_frase = $odonto == 1 ? " c/ Odonto" : " s/ Odonto";
        $frase = $plano_nome.$odonto_frase;






        $imagem_user = auth()->user()->image;

        $nome = auth()->user()->name;
        $celular = auth()->user()->celular;
        $corretora = auth()->user()->corretora_id;
        $status_carencia = request()->status_carencia == "true" ? 1 : 0;
        $status_desconto = request()->status_desconto == "true" ? 1 : 0;
        if($ambulatorial == 0) {
            $dados = Tabela::select('tabelas.*')
                ->selectRaw("CASE $sql END AS quantidade")
                ->join('faixa_etarias', 'faixa_etarias.id', '=', 'tabelas.faixa_etaria_id')
                ->where('tabelas.tabela_origens_id', $cidade)
                ->where('tabelas.plano_id', $plano)
                ->where('tabelas.administradora_id', $operadora)
                ->where("tabelas.odonto",$odonto)
                ->where("acomodacao_id","!=",3)
                ->whereIn('tabelas.faixa_etaria_id', explode(',', $keys))
                ->get();








            $desconto = Desconto::where('plano_id', $plano)
                ->where('tabela_origens_id', $cidade)
                ->where('administradora_id',$operadora)
                ->first();
            $valor_desconto = "";
            if($desconto) {
                $valor_desconto = $desconto->valor;
            }

            $viewName = "cotacao.modelo1";

            if($apenasvalores == 0) {
                $view = \Illuminate\Support\Facades\View::make($viewName,[
                    'com_coparticipacao' => $com_coparticipacao,
                    'sem_coparticipacao' => $sem_coparticipacao,
                    'apenas_valores' => $apenasvalores,
                    'linha_01' => $linha_01,
                    'linha_02' => $linha_02,

                    'valor_desconto' => $valor_desconto,
                    'desconto' => $status_desconto,
                    //'carencias' => $carencias,
                    'image' => $imagem_user,
                    'dados' => $dados,
                    'pdf' => $pdf_copar,
                    'nome' => $nome,
                    'cidade' => $cidade_nome,
                    'plano_nome' => $plano_nome,
                    'odonto_frase' => $odonto_frase,
                    'administradora' => $admin_nome,
                    'frase' => $frase,
                    'status_carencia' => $status_carencia,
                    'status_desconto' => $status_desconto,
                    'odonto' => $odonto,
                    'celular' => $celular,
                    'status_excecao' => $status_excecao,
                    'linhas' => $linhas,
                    'corretora' => $corretora
                ]);
            } else {
                //cabecalhos

                $cabecalho = 1;

                $cabecalhoName = "cotacao.cabecalho1";

                $desconto = Desconto::where('plano_id', $plano)
                    ->where('tabela_origens_id', $cidade)
                    ->where('administradora_id',$operadora)
                    ->first();

                $valor_desconto = "";
                $status_desconto = 0;
                if($desconto) {
                    $valor_desconto = $desconto->valor;
                    $status_desconto = 1;
                }

                $view = \Illuminate\Support\Facades\View::make($cabecalhoName,[
                    'com_coparticipacao' => $com_coparticipacao,
                    'sem_coparticipacao' => $sem_coparticipacao,
                    'apenas_valores' => $apenasvalores,
                    'cabecalho' => $cabecalho,
                    //'carencias' => $carencias,
                    'dados' => $dados,
                    'pdf' => $pdf_copar,
                    'valor_desconto' => $valor_desconto,
                    'desconto' => $status_desconto,
                    'nome' => $nome,
                    'cidade' => $cidade_nome,
                    'plano_nome' => $plano_nome,
                    'odonto_frase' => $odonto_frase,
                    'administradora' => $admin_nome,
                    'frase' => $frase,
                    'status_desconto' => $status_desconto,
                    'odonto' => $odonto,



                ]);
            }

            $nome_img = "orcamento_". date('d') . "_" . date('m') . "_" . date("Y") . "_" . date('H') . "_" . date("i") . "_" . date("s")."_" . uniqid();
            $altura = match (true) {
                $somar_linhas === 1 => 350,
                $somar_linhas === 2 => 380,
                $somar_linhas === 3 => 420,
                $somar_linhas >= 4 && $linhas <= 5 => 500,
                default => 620,
            };



            if($tipo_documento == "pdf") {

                if ($apenasvalores == 1) {
                    $pdf = PDFFile::loadHTML($view)
                        //->setPaper('A3', 'portrait');
                        ->setPaper([0, 0, 595, $altura]); // Redimensiona o PDF
                    return $pdf->download($nome_img.".pdf");
                } else {
                    $pdf = PDFFile::loadHTML($view)
                        ->setPaper('A3', 'portrait');
                    return $pdf->download($nome_img.".pdf");

                }
            } else {

                $pdfPath = storage_path('app/temp/temp.pdf');

                if($apenasvalores == 1) {

                    //Aquiiiiiiii chatgpt


                    $pdf = PDFFile::loadHTML($view)
                        ->setPaper([0, 0, 595, $altura]);
                } else {
                    $pdf = PDFFile::loadHTML($view)->setPaper('A3', 'portrait');
                }
                $pdf->save($pdfPath);
                $imagemPath = storage_path("app/temp/{$nome_img}.png");
                if (file_exists($imagemPath)) {
                    unlink($imagemPath);  // Exclui a imagem anterior se ela existir
                }

                if($apenasvalores == 1) {
                    $command = "gs -sDEVICE=pngalpha -r300 -dDEVICEWIDTHPOINTS=595 -dDEVICEHEIGHTPOINTS={$altura} -dPDFFitPage -dUseCropBox -dDetectDuplicateImages -dNOTRANSPARENCY -o {$imagemPath} {$pdfPath}";
                    exec($command, $output, $status);
                } else {
                    $command = "gs -sDEVICE=pngalpha -r300 -o {$imagemPath} {$pdfPath}";
                    exec($command, $output, $status);
                }

                if ($status !== 0 || !file_exists($imagemPath)) {
                    return response()->json(['error' => 'Falha ao gerar a imagem.'], 500);
                }

                return response()->download($imagemPath)->deleteFileAfterSend(true);

            }
        } else {



            $frase = "Ambulatorial ".$odonto_frase;
            $imagem_user = auth()->user()->image;
            $dados = Tabela::select('tabelas.*')
                ->selectRaw("CASE $sql END AS quantidade")
                ->join('faixa_etarias', 'faixa_etarias.id', '=', 'tabelas.faixa_etaria_id')
                ->where('tabelas.tabela_origens_id', $cidade)
                ->where('tabelas.plano_id', $plano)
                ->where('tabelas.administradora_id', $operadora)
                ->where("tabelas.odonto",$odonto)
                ->where("acomodacao_id","=",3)
                ->whereIn('tabelas.faixa_etaria_id', explode(',',$keys))
                ->get();





            $hasTabelaOrigens = Pdf::where('plano_id', $plano)
                ->where('tabela_origens_id',$cidade)
                ->exists();
            if ($hasTabelaOrigens) {
                $pdf_copar = Pdf::where('plano_id', $plano)
                    ->where('tabela_origens_id',$cidade)
                    ->first();
            } else {
                $pdf_copar = Pdf::where('plano_id', $plano)->first();
            }

            $layout = 1;
            $layout_user = 1;
            $viewName = "cotacao.cotacao-ambulatorial1";

            $desconto = Desconto::where('plano_id', $plano)
                ->where('tabela_origens_id', $cidade)
                ->first();

            $desconto = Desconto::where('plano_id', $plano)
                ->where('tabela_origens_id', $cidade)
                ->where('administradora_id',$operadora)
                ->first();

            $valor_desconto = "";
            $status_desconto = 0;
            if($desconto) {
                $valor_desconto = $desconto->valor;
                $status_desconto = 1;
            }



            $view = \Illuminate\Support\Facades\View::make($viewName,[
                'com_coparticipacao' => 1,
                'sem_coparticipacao' => 1,
                'image' => $imagem_user,
                'dados' => $dados,
                'pdf' => $pdf_copar,
                'plano_nome' => "Individual",
                'linha_01' => $linha_01,
                'linha_02' => $linha_02,
                'nome' => $nome,
                'desconto' => $status_desconto,
                'valor_desconto' => $valor_desconto,
                'cidade' => $cidade_nome,
                'plano' => $plano_nome,
                'odonto_frase' => $odonto_frase,
                'administradora' => $admin_nome,
                'frase' => $frase,
                'status_carencia' => $status_carencia,
                'status_desconto' => $status_desconto,
                'odonto' => $odonto,
                'celular' => $celular,
                'linhas' => $linhas,
                'corretora' => $corretora
            ]);

            $nome_img = "orcamento_". date('d') . "_" . date('m') . "_" . date("Y") . "_" . date('H') . "_" . date("i") . "_" . date("s")."_" . uniqid();
            if($tipo_documento == "pdf") {

                $pdf = PDFFile::loadHTML($view)
                    ->setPaper('A3', 'portrait');
                return $pdf->download($nome_img.".pdf");

            } else {

                $pdfPath = storage_path('app/temp/temp.pdf');
                $pdf = PDFFile::loadHTML($view)->setPaper('A3', 'portrait');
                $pdf->save($pdfPath);
                $imagemPath = storage_path("app/temp/{$nome_img}.png");

                if (file_exists($imagemPath)) {
                    unlink($imagemPath);  // Exclui a imagem anterior se ela existir
                }

                $command = "gs -sDEVICE=pngalpha -r300 -o {$imagemPath} {$pdfPath}";  // -r150 é a resolução, pode ser ajustada

                exec($command, $output, $status);


                if ($status !== 0 || !file_exists($imagemPath)) {
                    return response()->json(['error' => 'Falha ao gerar a imagem.'], 500);
                }

                return response()
                    ->download($imagemPath)
                    ->deleteFileAfterSend(true);
            }
        }
    }







    public function criarPDFold()
    {
        $ambulatorial = request()->ambulatorial;
        $cidade = request()->tabela_origem;
        $plano = request()->plano;
        $operadora = request()->operadora;
        $odonto = request()->odonto;
        $sql = "";
        $chaves = [];
        $linhas = 0;

        foreach(request()->faixas[0] as $k => $v) {
            if($v != null AND $v != 0) {
                $sql .= " WHEN tabelas.faixa_etaria_id = {$k} THEN ${v} ";
                $chaves[] = $k;
            }
        }


        $linhas = count($chaves);
        $cidade_nome = TabelaOrigens::find($cidade)->nome;
        $plano_nome = Plano::find($plano)->nome;

        $cidade_uf = TabelaOrigens::find($cidade)->uf;
        $status_excecao = false;
        if(($cidade_uf == "MT" || $cidade_uf == "MS") && $plano == 3) {
            $status_excecao = true;
            $pdf_copar = PdfExcecao::where('plano_id', $plano)->first();
        } else {
            $hasTabelaOrigens = Pdf::where('plano_id', $plano)
                ->where('tabela_origens_id',$cidade)
                ->exists();
            if ($hasTabelaOrigens) {
                $pdf_copar = Pdf::where('plano_id', $plano)
                    ->where('tabela_origens_id',$cidade)
                    ->first();
            } else {
                $pdf_copar = Pdf::where('plano_id', $plano)->first();
            }
        }

        $admin_nome = Administradora::find($operadora)->nome;
        $odonto_frase = $odonto == 1 ? " c/ Odonto" : " s/ Odonto";
        $frase = $plano_nome.$odonto_frase;
        $keys = implode(",",$chaves);
        $image_user = "";
        if(auth()->user()->image) {
            $image_user = 'data:image/png;base64,'.base64_encode(file_get_contents(public_path(auth()->user()->image)));
        }
        $nome = auth()->user()->name;
        $celular = auth()->user()->celular;
        $corretora = auth()->user()->corretora_id;
        $status_carencia = request()->status_carencia == "true" ? 1 : 0;
        $status_desconto = request()->status_desconto == "true" ? 1 : 0;
        if($ambulatorial == 0) {
            $dados = Tabela::select('tabelas.*')
                ->selectRaw("CASE $sql END AS quantidade")
                ->join('faixa_etarias', 'faixa_etarias.id', '=', 'tabelas.faixa_etaria_id')
                ->where('tabelas.tabela_origens_id', $cidade)
                ->where('tabelas.plano_id', $plano)
                ->where('tabelas.administradora_id', $operadora)
                ->where("tabelas.odonto",$odonto)
                ->where("acomodacao_id","!=",3)
                ->whereIn('tabelas.faixa_etaria_id', explode(',', $keys))
                ->get();



            $carencias = Carencia::where("plano_id",$plano)->get();

            $view = \Illuminate\Support\Facades\View::make("cotacao.cotacao3",[
                'carencias' => $carencias,
                'image' => $image_user,
                'dados' => $dados,
                'pdf' => $pdf_copar,
                'nome' => $nome,
                'cidade' => $cidade_nome,
                'plano_nome' => $plano_nome,
                'odonto_frase' => $odonto_frase,
                'administradora' => $admin_nome,
                'frase' => $frase,
                'status_carencia' => $status_carencia,
                'status_desconto' => $status_desconto,
                'odonto' => $odonto,
                'celular' => $celular,
                'status_excecao' => $status_excecao,
                'linhas' => $linhas,
                'corretora' => $corretora
            ]);

            $pdf = PDFFile::loadHTML($view);
            return $pdf->stream("teste.pdf");




        } else {

            $dados = Tabela::select('tabelas.*')
                ->selectRaw("CASE $sql END AS quantidade")
                ->join('faixa_etarias', 'faixa_etarias.id', '=', 'tabelas.faixa_etaria_id')
                ->where('tabelas.tabela_origens_id', $cidade)
                ->where('tabelas.plano_id', $plano)
                ->where('tabelas.administradora_id', $operadora)
                ->where("tabelas.odonto",$odonto)
                ->where("acomodacao_id","=",3)
                ->whereIn('tabelas.faixa_etaria_id', explode(',', $keys))
                ->get();
            $hasTabelaOrigens = Pdf::where('plano_id', $plano)
                ->where('tabela_origens_id',$cidade)
                ->exists();
            if ($hasTabelaOrigens) {
                $pdf_copar = Pdf::where('plano_id', $plano)
                    ->where('tabela_origens_id',$cidade)
                    ->first();
            } else {
                $pdf_copar = Pdf::where('plano_id', $plano)->first();
            }
            $view = \Illuminate\Support\Facades\View::make("cotacao.cotacao-ambulatorial-pdf",[
                'image' => $image_user,
                'dados' => $dados,
                'pdf' => $pdf_copar,
                'nome' => $nome,
                'cidade' => $cidade_nome,
                'plano' => $plano_nome,
                'odonto_frase' => $odonto_frase,
                'administradora' => $admin_nome,
                'frase' => $frase,
                'status_carencia' => $status_carencia,
                'status_desconto' => $status_desconto,
                'odonto' => $odonto,
                'celular' => $celular,
                'linhas' => $linhas,
                'corretora' => $corretora
            ]);
            $pdf = PDFFile::loadHTML($view);
            return $pdf->stream("teste.pdf");













        }





    }
}
