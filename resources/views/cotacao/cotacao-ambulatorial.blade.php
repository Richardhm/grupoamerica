<style>
    * {margin:0;padding:0;}
    table {border-collapse: separate !important;border-spacing: 0;}
    .bordered {border: solid #ccc 1px;-moz-border-radius: 6px;-webkit-border-radius: 6px;border-radius: 6px;}
    tbody td,tfoot td{width:14%;padding:0;}
</style>
<div class="flex justify-around items-center py-2 w-full mb-2 text-sm font-medium text-white bg-white rounded-lg border border-gray-200 bg-gray-500 bg-opacity-10">
    <img src="{{$imagem_plano}}" alt="Operadora" style="width:100px;background-color:white;max-height:28px;border-radius:5px;">
    <h4 class="text-white">{{$plano_nome}}</h4>
    <p class="text-white text-center">{{$cidade_nome}}</p>
</div>
<div class="flex justify-center items-center w-full
py-0.5 mb-1 text-sm font-medium
text-white focus:outline-none bg-gray-700 rounded-lg border
border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10
focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 bg-gray-500 bg-opacity-10 dark:hover:text-gray-900">
    Com Odonto
</div>

<table class="min-w-full bg-gray-300 bg-opacity-20 rounded-lg bordered" id="tabela_aqui">
    <thead>

    <tr>
        <td rowspan="2" style="vertical-align:middle;text-align:center;font-size:0.7em;border-right:1px solid #ccc;border-bottom:1px solid #ccc;color:#FFF;">Faixa Etária</td>
        <td colspan="2" style="text-align:center;font-size:0.7em;border-bottom:1px solid #ccc;border-right:1px solid #ccc;color:#FFF;" class="">Com Copar</td>
        <td colspan="2" style="text-align:center;font-size:0.7em;border-bottom:1px solid #ccc;color:#FFF;" class="">Sem Copar</td>
    </tr>

    </thead>
    <tbody>
    @php
        $dadosComOdontoComCopar = [];
        $dadosComOdontoSemCopar = [];
        $com_copar = 0;
        $totalApartamentoSemOdonto_com_copar = 0;
        $totalApartamentoSemOdonto_sem_copar = 0;
        $sem_copar = 0;


    @endphp

    @foreach($dados as $dado)
        @php
            $faixaEtaria = $dado->faixaEtaria->nome;
            $acomodacao = $dado->acomodacao_id;
            $valor = $dado->valor;
            $odonto = $dado->odonto;
            $coparticipacao = $dado->coparticipacao;
            $quantidade = $dado->quantidade;

            if($odonto == 1) {
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
                $dadosComOdonto[$faixaEtaria]["{$index}"] = $valor ?? 0;
            }
        @endphp
    @endforeach


    @foreach($dadosComOdonto as $faixaEtaria => $valores)
        @for($i=0;$i<$valores['quantidade'];$i++)
            <tr>
                <td class="text-white" style="font-size: 0.7em;text-align: center;">{{ $faixaEtaria }}</td>
                <td colspan="2" class="text-white" style="font-size: 0.7em;text-align: right;margin-right: 2px;">
                    <span class="mr-2">{{ number_format($valores['com_copar'], 2, ",", ".") }}</span>

                    @php
                        $com_copar += $valores['com_copar'];
                    @endphp


                </td>

                <td colspan="2" class="text-white" style="font-size: 0.7em;text-align: right;margin-right: 2px">
                    <span class="mr-2">{{ number_format($valores['sem_copar'], 2, ",", ".") }}</span>
                    @php
                        $sem_copar += $valores['sem_copar'];
                    @endphp
                </td>


            </tr>
        @endfor
    @endforeach


    </tbody>
    <table class="bg-gray-700 w-full bg-opacity-20 rounded-lg bordered mt-2 py-0.5">

        <tfoot>
        <tr>
            <td class="text-white py-0.5" style="font-size: 0.7em;text-align: center;">Total</td>
            <td class="text-white py-0.5" style="font-size: 0.7em;text-align:right;margin-right: 2px;">
                <span class="mr-2">{{ number_format($com_copar, 2, ",", ".") }}</span>
            </td>
            <td class="text-white py-0.5" style="font-size: 0.7em;text-align:right;margin-right: 2px;">
                <span class="mr-2">{{ number_format($sem_copar, 2, ",", ".") }}</span>
            </td>



        </tr>
        </tfoot>

    </table>

</table>





<button data-odonto="1" class="downloadLinkAmbulatorial flex justify-center items-center w-full
py-0.5 mb-1 text-sm font-medium mt-2
text-white focus:outline-none bg-gray-700 rounded-lg border
border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10
focus:ring-4 focus:ring-gray-200 bg-red-400">
    Gerar Imagem
</button>


@if($status)

    <div class="h-2 my-1 w-full bg-white rounded-lg"></div>
    {{--Sem Odotno--}}
    {{-- Tabela sem Odonto --}}
    <div class="flex justify-center items-center w-full py-0.5 mb-1 text-sm font-medium text-white focus:outline-none bg-gray-700 rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 bg-gray-500 bg-opacity-10 dark:hover:text-gray-900">
        Sem Odonto
    </div>


    <table class="min-w-full bg-white bg-opacity-20 border border-gray-900 rounded-lg overflow-hidden bordered">
        <thead>

        <tr>
            <td rowspan="2" style="vertical-align:middle;text-align:center;font-size:0.7em;border-right:1px solid #FFF;border-bottom:1px solid #FFF;color:#FFF;">Faixa Etária</td>
            <td colspan="2" style="text-align:center;font-size:0.7em;border-bottom:1px solid #FFF;border-right:1px solid #FFF;color:#FFF;" class="">Com Copar</td>
            <td colspan="2" style="text-align:center;font-size:0.7em;border-bottom:1px solid #FFF;color:#FFF;" class="">Sem Copar</td>
        </tr>

        </thead>
        <tbody>
        @php
            $dadosSemOdontoComCopar = [];
            $dadosSemOdontoSemCopar = [];


        @endphp

        @foreach($dados as $dd)
            @php

                $faixaEtariaSemOdonto = $dd->faixaEtaria->nome;
                $acomodacaoSemOdonto = $dd->acomodacao_id;
                $valorSemOdonto = $dd->valor;
                $odontoSemOdonto = $dd->odonto;
                $coparticipacaoSemOdonto = $dd->coparticipacao;
                $quantidadeSemOdonto = $dd->quantidade;



                if($odontoSemOdonto == 0) {
                    // Verifica se tem coparticipação
                    $index_sem_odonto = ($coparticipacaoSemOdonto == 1) ? 'com_copar' : 'sem_copar';

                    if (!isset($dadosSemOdonto[$faixaEtariaSemOdonto])) {
                        $dadosSemOdonto[$faixaEtariaSemOdonto] = [
                            'faixa_etaria_id' => $faixaEtariaSemOdonto,
                            'apartamento_com_copar' => 0,
                            'enfermaria_com_copar' => 0,
                            'apartamento_sem_copar' => 0,
                            'enfermaria_sem_copar' => 0,
                            'quantidade' => $quantidadeSemOdonto
                        ];
                    }

                    $dadosSemOdonto[$faixaEtariaSemOdonto]["{$index_sem_odonto}"] = $valorSemOdonto ?? 0;

                }
            @endphp
        @endforeach

        @foreach($dadosSemOdonto as $faixaEtariaSemOdonto => $valorSemOdonto)
            @for($ii=0;$ii<$valorSemOdonto['quantidade'];$ii++)
                <tr>
                    <td class="text-white" style="font-size: 0.7em;text-align: center;">{{ $faixaEtariaSemOdonto }}</td>
                    <td colspan="2" class="text-white" style="font-size: 0.7em;text-align: right;">
                        <span class="mr-2">{{ number_format($valorSemOdonto['com_copar'], 2, ",", ".") }}</span>
                        @php $totalApartamentoSemOdonto_com_copar += $valorSemOdonto['com_copar'];@endphp
                    </td>
                    <td colspan="2" class="text-white" style="font-size: 0.7em;text-align: right;">
                        <span class="mr-2">{{ number_format($valorSemOdonto['sem_copar'], 2, ",", ".") }}</span>
                        @php $totalApartamentoSemOdonto_sem_copar += $valorSemOdonto['sem_copar'];@endphp
                    </td>


                </tr>
            @endfor
        @endforeach


        </tbody>
        <table class="min-w-full bg-gray-700 bg-opacity-20 rounded-lg bordered mt-2 py-0.5">

            <tfoot>
            <tr>
                <td class="text-white py-0.5" style="font-size: 0.7em;text-align: center;">Total</td>
                <td colspan="2" class="text-white py-0.5" style="font-size: 0.7em;text-align:right;margin-right: 2px;">
                    <span class="mr-2">{{ number_format($totalApartamentoSemOdonto_com_copar, 2, ",", ".") }}</span>
                </td>
                <td colspan="2" class="text-white py-0.5" style="font-size: 0.7em;text-align:right;margin-right: 2px;">
                    <span class="mr-2">{{ number_format($totalApartamentoSemOdonto_sem_copar, 2, ",", ".") }}</span>
                </td>



            </tr>
            </tfoot>

        </table>
    </table>


    <button data-odonto="0" class="downloadLinkAmbulatorial flex justify-center items-center w-full
py-0.5 mb-1 text-sm font-medium mt-2
text-white focus:outline-none bg-gray-700 rounded-lg border
border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10
focus:ring-4 focus:ring-gray-200 bg-red-400">
        Gerar Imagem
    </button>

@endif










<div class="flex justify-around items-center w-full mt-4">
{{--    <label for="status_carencia_ambulatorial">--}}
{{--        <input type="checkbox" name="status_carencia_ambulatorial" id="status_carencia_ambulatorial" class="w-6 h-6 text-teal-600 bg-gray-100 border-gray-300 rounded focus:ring-teal-500 dark:focus:ring-teal-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
{{--        <span style="color:white;" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 text-2xl">Com Carências</span>--}}
{{--    </label>--}}

    <label for="status_desconto_ambulatorial">
        <input type="checkbox" name="status_desconto_ambulatorial" id="status_desconto_ambulatorial" class="w-6 h-6 text-teal-600 bg-gray-100 border-gray-300 rounded focus:ring-teal-500 dark:focus:ring-teal-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <span style="color:white;" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 text-2xl">Desconto</span>
    </label>

    <div>
        <button class="btn_normal focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
           Individual
        </button>
    </div>

</div>

</div>
