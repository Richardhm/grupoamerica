<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Document</title>
    <style>


       	@font-face {
        	font-family: 'Roboto';
        	src: url('{{ public_path("fonts/Roboto-Regular.ttf") }}') format('truetype');
        	font-weight: normal;
        	font-style: normal;
    	}

    	@font-face {
        	font-family: 'Roboto';
        	src: url('{{ public_path("fonts/Roboto-Bold.ttf") }}') format('truetype');
        	font-weight: bold;
        	font-style: normal;
    	}

        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif !important;
            background-color: rgb(12,77,193);
        }
        tr {
            line-height: 1;
            vertical-align: top;
        }


        .container {
            position: absolute;
            top: 350px;
            left: 50%;
            transform: translateX(-50%);
            width: 97%;
            padding-bottom: 0 !important; /* Remove padding residual */
            margin-bottom: -10px; /* Compensa espaçamento vertical */
        }

        /* Novos estilos */
        .faixa-etaria {
            text-align: center;
            font-size: 1.5em;
            background-color: rgb(5,53,95);
            color:#FFF;
            font-weight:bold;
        }


        /* Estilos do footer */
        .footer {
            position: absolute;
            bottom: 0px;
            width: calc(100% - 40px); /* Ajusta a largura do footer */
            height: 200px; /* Define a altura do footer */
            padding: 0px;
            box-sizing: border-box;
        }

        .footer img {
            position: absolute;
            bottom:15px;
            right: 0px;
            width: 400px;
            height: 400px;
            border-radius: 50%;
        }

        .footer .middle, .footer .right {
            position: absolute;
            bottom:0px;


        }

        .footer .middle {
            bottom:30px;
            left: 10px;
            display:block;
            color: #0c0c0c;
            font-size:1.7em;
            line-height:1;
            padding:0;
        }

        .footer .middle p {
            margin: 0px;
            color:#FFFFFF;
            font-weight: bold;
            line-height:1;
            padding:0;
        }

        .footer .right {
            bottom:20px;
            right: 30px;
            text-align: left;
            display:block;
            font-size:2em;
        }

        .footer .right p {
            margin: 0;
            color:#FFFFFF;
            font-weight: bold;
        }

        .cidade_container {

            position:absolute;

            top:190px;

            left:0%;

            font-weight: bold;

            font-size: 2em;

            color:white;

            text-align:center;



        }



        .frase_container {

            position:absolute;

            top:260px;

            left:0%;

            font-weight: bold;

            font-size: 1.5em;

            color:white;

            text-align: center;

        }




        /**********************/
        .bloco-container {
            width: 100%;
            margin: 0 auto;
            border-spacing: 10px; /* Espaço entre blocos */
            border-collapse: separate;
        }
        .container table {
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: -5px; /* Compensa o espaçamento vertical residual */
        }

        .bloco {
            display: inline-table;
    background: white;
    border-radius: 60px;
    vertical-align: top;
    padding: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Sombra suave */
        }

        .header-orange {
            background: #F88058;
            color: white;
            padding: 20px;
            border-radius: 55px 55px 0 0;
            font-weight: bold;
            font-size: 1em;

        }

        .subheader-blue {
            background: white;
            color: white;
            padding: 8px;
            font-size: 1em;
            color: rgb(8,73,189);
            font-weight:bold;
            text-align:center;
        }

        .linha-faixa {
            background: #FFF3CD;
            padding: 12px;
            margin: 8px 3px;
            border-radius: 8px;
            color: #366EBF;
            font-weight: bold;
            display: block;
        }

        .coluna-azul {
            background: white;
            font-weight:bold;
            padding: 8px;
            width: 50%;
            font-size: 1em;
            color: rgb(8,73,189);
            text-align:center;
        }

        .valor-copart {
            background: rgb(254,199,72);
            padding: 12px !important;
            margin: 5px 3px;
            border-radius: 8px;
            color: rgb(8,73,189);
            font-weight: bold;
            display: block;
            font-size:1.2em;
            text-align:center;
        }

        .valor-copart-laranja {
            background: #F88058;
            padding: 12px !important;
            margin: 5px 3px;
            border-radius: 0 0 55px 55px;
            color: white;
            font-weight: bold;
            display: block;
            font-size:1.2em;
            text-align:center;
        }

        .lista-coparticipacao {
            color: white;
            font-size: 1.2em;
            margin:0;
        }

        .lista-coparticipacao strong {
            display: block;
            margin: 10px 0;
            font-size:1.5em;
        }

        .procedimentos-container {
            width: 70%;
            margin-top: 1px;
            overflow: hidden; /* Contém os floats */
            background-color: white;
            border-radius: 55px;
            padding: 15px;
            min-height: 480px;
        }

        .procedimento-left {
            float: left;
            width: 60%;
            vertical-align: top;
        }

        .procedimento-right {
            float: right;
            width: 35%;
            vertical-align: top;
        }


        .linha-procedimento {
             margin: 0;
             padding:0;
        }

        .linha-procedimento span {
             font-size:1.3em;
             color: rgb(8,73,189);
             display:block;
             font-weight:bold;
             margin:0px;
             padding:0 0 0 30px;

        }


        .valor-procedimento {
            background: #FFF3CD;

            border-radius: 20px;
            display: inline-block;
            margin-left: 10px;
        }

    </style>
</head>
<body>
    <div class="header-container">
        <img style="position: absolute;top: 0;width:100%;height:300px;left: 0;object-fit: cover;" src="cabecalhos/cabecalho{{$cabecalho}}.png" alt="Orçamento">
    </div>
    <p class="cidade_container" style="text-transform:uppercase;width:100%">{{$cidade}}</p>
    <p class="frase_container" style="text-transform:uppercase;width:100%">{{$frase}}</p>


@php
    $dadosComOdontoComCopar = [];
    $dadosComOdontoSemCopar = [];
    $totalApartamento_com_copar = 0;
    $totalEnfermaria_com_copar = 0;
    $totalApartamento_sem_copar = 0;
    $totalEnfermaria_sem_copar = 0;
@endphp

@foreach($dados as $dado)
    @php
        $faixaEtaria = $dado->faixaEtaria->nome;
        $acomodacao = $dado->acomodacao_id;
        $valor = $dado->valor;
        $odonto = $dado->odonto;
        $coparticipacao = $dado->coparticipacao;
        $quantidade = $dado->quantidade;
        // Verifica se tem coparticipação
        $index = ($coparticipacao == 1) ? 'com_copar' : 'sem_copar';
        if (!isset($dadosComOdonto[$faixaEtaria])) {
            $dadosComOdonto[$faixaEtaria] = [
                'faixa_etaria_id' => $faixaEtaria,
                'apartamento_com_copar' => 0,
                'enfermaria_com_copar' => 0,
                'apartamento_sem_copar' => 0,
                'enfermaria_sem_copar' => 0,
                'quantidade' => $quantidade
            ];
        }
        $dadosComOdonto[$faixaEtaria]["{$acomodacao}_{$index}"] = $valor ?? 0;
    @endphp
@endforeach

@php
    // Calcular quantidade de blocos ativos
    $totalBlocos = 1; // Bloco Faixa Etária sempre existe
    if($com_coparticipacao == 1) $totalBlocos++;
    if($sem_coparticipacao == 1) $totalBlocos++;

    // Definir larguras baseado no número de blocos
    $widths = [
        1 => '50%',
        2 => '38%',
        3 => '38%'
    ];

    $margins = [
        1 => '0 auto',
        2 => '0 1%',
        3 => '0 1%'
    ];
@endphp



<div class="container">
    <table class="bloco-container" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <!-- Bloco 1 - Faixa Etária -->
            <td class="bloco" style="width: 22%;{{$totalBlocos <= 2 ? 'margin-left:20%;' : 'margin-left:0%;'}}">
                <table width="100%">
                    <tr>
                        <td class="header-orange" style="text-align:center;">NOSSO PLANO</td>
                    </tr>
                    <tr>
                        <td class="subheader-blue">FAIXA ETÁRIA</td>
                    </tr>

                    @foreach($dadosComOdonto as $faixaEtaria => $valores)
                    <tr>
                        <td>
                            <div class="valor-copart">
                                {{ $faixaEtaria }}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if($desconto == 1)
                        <tr>
                            <td>
                                <div class="valor-copart">TOTAL</div>
                            </td>

                        </tr>
                        <tr>

                            <td>
                                <div class="valor-copart-laranja">Desc.{{intval($valor_desconto)}}%</div>
                            </td>

                        </tr>
                    @else
                        <tr>
                            <td>
                                <div class="valor-copart-laranja">TOTAL</div>
                            </td>

                        </tr>
                    @endif
                </table>
            </td>
            @if($com_coparticipacao == 1)
            <!-- Bloco 2 - Com Coparticipação -->
            <td class="bloco" style="width: {{ $widths[$totalBlocos] }}; margin: {{ $margins[$totalBlocos] }};">
                <table width="100%">
                    <tr>
                        <td colspan="2" class="header-orange" style="text-align:center;">COM COPARTICIPAÇÃO</td>
                    </tr>
                    <tr>
                        <td class="coluna-azul">
                            ENFER
                            @if($odonto_frase == " c/ Odonto" && $plano_nome == "Individual")
                                11819
                            @elseif($odonto_frase == " c/ Odonto" && $plano_nome == "Super Simples")
                                11162
                            @elseif($odonto_frase == " s/ Odonto" && $plano_nome == "Individual")
                                11165
                            @elseif($odonto_frase == " s/ Odonto" && $plano_nome == "Super Simples")
                                11162
                            @endif
                        </td>
                        <td class="coluna-azul">
                            APART
                            @if($odonto_frase == " c/ Odonto" && $plano_nome == "Individual")
                                11820
                            @elseif($odonto_frase == " c/ Odonto" && $plano_nome == "Super Simples")
                                11170
                            @elseif($odonto_frase == " s/ Odonto" && $plano_nome == "Individual")
                                11173
                            @elseif($odonto_frase == " s/ Odonto" && $plano_nome == "Super Simples")
                                11170
                            @endif
                        </td>
                    </tr>
                    @foreach($dadosComOdonto as $faixaEtaria => $valores)
                    <tr>
                        <td>
                           <div class="valor-copart">
                           	@php
                                    $totalEnfermaria_com_copar += $valores['2_com_copar'];
                                @endphp
                                {{ number_format($valores['2_com_copar'], 2, ",", ".") }}
                           </div>
                        </td>
                        <td>
                           <div class="valor-copart">
                           	@php
                                    $totalApartamento_com_copar += $valores['1_com_copar'];
                                @endphp
                                {{ number_format($valores['1_com_copar'], 2, ",", ".") }}
                           </div>
                        </td>
                    </tr>
                    @endforeach
                    <tfoot>

                    @if($desconto == 1)
                        <tr>
                            <td>
                                <div class="valor-copart">
                                    {{number_format($totalEnfermaria_com_copar,2,",",".")}}
                                </div>
                            </td>
                            <td>
                                <div class="valor-copart">
                                    {{number_format($totalApartamento_com_copar,2,",",".")}}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="valor-copart-laranja">
                                    {{ number_format($totalEnfermaria_com_copar * (1 - ($valor_desconto / 100)), 2, ",", ".") }}
                                </div>
                            </td>

                            <td>
                                <div class="valor-copart-laranja">
                                    {{ number_format($totalApartamento_com_copar * (1 - ($valor_desconto / 100)), 2, ",", ".") }}
                                </div>
                            </td>

                        </tr>
                    @else
                        <tr>
                            <td>
                                <div class="valor-copart-laranja">
                                    {{number_format($totalEnfermaria_com_copar,2,",",".")}}
                                </div>
                            </td>
                            <td>
                                <div class="valor-copart-laranja">
                                    {{number_format($totalApartamento_com_copar,2,",",".")}}
                                </div>
                            </td>
                        </tr>
                    @endif





                    </tfoot>
                </table>

            </td>
            @endif

            @if($sem_coparticipacao == 1)
            <!-- Bloco 3 - Sem Coparticipação -->
            <td class="bloco" style="width: {{ $widths[$totalBlocos] }};{{$totalBlocos <= 2 ? 'margin-left:1%;' : 'margin-left:0%;'}} ">
                <table width="100%">
                    <tr>
                        <td colspan="2" class="header-orange" style="text-align:center;">SEM COPARTICIPAÇÃO *</td>
                    </tr>
                    <tr>
                        <td class="coluna-azul">
                            ENFER
                            @if($odonto_frase == " c/ Odonto" && $plano_nome == "Individual")
                                21068
                            @elseif($odonto_frase == " c/ Odonto" && $plano_nome == "Super Simples")
                                21223
                            @elseif($odonto_frase == " s/ Odonto" && $plano_nome == "Individual")
                                21069
                            @elseif($odonto_frase == " s/ Odonto" && $plano_nome == "Super Simples")
                                21223
                            @endif
                        </td>
                        <td class="coluna-azul">
                            APART
                            @if($odonto_frase == " c/ Odonto" && $plano_nome == "Individual")
                                21070
                            @elseif($odonto_frase == " c/ Odonto" && $plano_nome == "Super Simples")
                                21224
                            @elseif($odonto_frase == " s/ Odonto" && $plano_nome == "Individual")
                                21071
                            @elseif($odonto_frase == " s/ Odonto" && $plano_nome == "Super Simples")
                                21224
                            @endif
                        </td>
                    </tr>

                    @foreach($dadosComOdonto as $faixaEtaria => $valores)
                    <tr >
                        <td>
                           <div class="valor-copart">
                           	@php
                                    $totalEnfermaria_sem_copar += $valores['2_sem_copar'];
                                @endphp
                                {{ number_format($valores['2_sem_copar'], 2, ",", ".") }}
                           </div>
                        </td>
                        <td>
                           <div class="valor-copart">
                           	@php
                                    $totalApartamento_sem_copar += $valores['1_sem_copar'];
                                @endphp
                                {{ number_format($valores['1_sem_copar'], 2, ",", ".") }}
                           </div>
                        </td>
                    </tr>
                    @endforeach
                    <tfoot>
                    @if($desconto == 1)
                        <tr>
                            <td>
                                <div class="valor-copart">
                                    {{number_format($totalEnfermaria_sem_copar,2,",",".")}}
                                </div>
                            </td>
                            <td>
                                <div class="valor-copart">
                                    {{number_format($totalApartamento_sem_copar,2,",",".")}}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="valor-copart-laranja">
                                    {{ number_format($totalEnfermaria_sem_copar * (1 - ($valor_desconto / 100)), 2, ",", ".") }}
                                </div>
                            </td>

                            <td>
                                <div class="valor-copart-laranja">
                                    {{ number_format($totalApartamento_sem_copar * (1 - ($valor_desconto / 100)), 2, ",", ".") }}
                                </div>
                            </td>

                        </tr>
                    @else
                        <tr>
                            <td>
                                <div class="valor-copart-laranja">
                                    {{number_format($totalEnfermaria_sem_copar,2,",",".")}}
                                </div>
                            </td>
                            <td>
                                <div class="valor-copart-laranja">
                                    {{number_format($totalApartamento_sem_copar,2,",",".")}}
                                </div>
                            </td>
                        </tr>
                    @endif
        </tfoot>
                </table>
                <tr>
              </tr>
            </td>
            @endif
        </tr>
    </table>

</div>



</body>
</html>

