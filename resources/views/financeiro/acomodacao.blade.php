@if(count($dados) >= 1)
@php
    $ii=0;
    $total = 0;
    $contagem = 1;
@endphp




    <div class="flex justify-between bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]" style="flex-wrap: wrap;">
        <h4 class="my-2 w-100 text-white w-full">Nossos Planos: </h4>
        @for($i=0;$i < count($dados); $i++)
            @php $contagem = $i + 1; @endphp
            @if($dados[$i]->card == $card_inicial)
                @if($ii==0)
                    <input type="hidden" name="administradora" id="administradora" value="{{$dados[$i]->administradora}}">
                    <div class="flex flex-col rounded valores-acomodacao mb-3 py-2 border border-dark text-black bg-transparent backdrop-blur-[80px]" data-acomodacao="{{$dados[$i]->acomodacao}}"  style="width:32%;">


                            <div class="flex items-center">

                                <h4 class="text-center w-6/12 flex ml-2" style="background-color:#fff;border:2px solid black;border-radius:5px;">
                                    <img src="{{asset($dados[$i]->logo)}}" class="flex align-center p-1" alt="" width="100%" height="100%">
                                </h4>
                                <div class="flex w-6/12 flex-col align-center align-center flex-wrap">
                                    <div class="flex flex-col">
                                        <p class="text-center" style="margin:0;padding:0;font-size:1em;">{{$dados[$i]->planos}}</p>
                                        <p class="text-center tipo" style="margin:0;padding:0;font-size:1em;">{{$dados[$i]->acomodacao}}</p>
                                    </div>
                                </div>


                            </div>


                            <div class="flex border">
                                <div class="w-6/12 border-r">
                                    <p class="text-center h-100 my-auto py-2" style="font-size:1em;">{{$dados[$i]->coparticipacao}}</p>
                                </div>
                                <div class="w-6/12">
                                    <p class="text-center h-100 my-auto py-2" style="font-size:1em;">{{$dados[$i]->odonto}}</p>
                                </div>
                            </div>

                            <div class="flex justify-between my-2 border" style="padding:0px;">
                                <div class="ml-2">
                                    <div class="form-group">
                                        <p style="margin:0;padding:0;font-size:0.875em;">Data Vigencia:</p>
                                        <input type="date" name="vigente" id="vigente_{{strtolower($dados[$i]->acomodacao)}}"  value="" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 vigente">

                                    </div>
                                </div>
                                <div class="mx-1">
                                    <div class="form-group">
                                        <p style="margin:0;padding:0;font-size:0.875em;">Data Boleto:</p>
                                        <input type="date" name="boleto" id="boleto_{{strtolower($dados[$i]->acomodacao)}}" value="" placeholder="Data Boleto" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 boleto">
                                    </div>

                                </div>
                                <div class="mr-2">
                                    <div class="form-group">
                                        <p style="margin:0;padding:0;font-size:0.875em;">Valor Adesão:</p>
                                        <input type="text" name="adesao" id="adesao_{{strtolower($dados[$i]->acomodacao)}}" placeholder="R$" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 valor_adesao">
                                    </div>
                                </div>
                            </div>

                        <div class="flex items-center justify-center my-2">
                            <table class="w-full text-sm text-left text-gray-500 bg-white rounded-lg overflow-hidden shadow-lg">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                                        <tr>
                                            <th class="px-4 py-2">Faixas</th>
                                            <th class="px-4 py-2">Vidas</th>
                                            <th class="px-4 py-2">Valor</th>
                                            <th class="px-4 py-2 text-center">Total</th>
                                        </tr>
                                    </thead>
                                <tbody class="bg-gray-100">


                @endif
                @php
                    $ii++;
                    $total += $dados[$i]->valor * $dados[$i]->quantidade;
                    $acomodacao = $dados[$i]->acomodacao;
                @endphp
                    <tr>
                        <td class="px-4 py-2">{{$dados[$i]->faixa}}</td>
                        <td class="px-4 py-2">{{$dados[$i]->quantidade}}</td>
                        <td class="px-4 py-2">{{number_format($dados[$i]->valor,2,",",".")}}</td>
                        <td class="px-4 py-2 text-right">{{number_format($dados[$i]->valor * $dados[$i]->quantidade,2,",",".")}}</td>
                    </tr>

            @else

                                    </tbody>
                                <tfoot class="bg-gray-200">
                                        <tr>
                                            <td colspan="4" class="px-4 py-2 text-center font-semibold text-gray-900 border-t border-gray-300 valor_plano">
                                                R$ <span class="aqui_total_change">{{number_format($total,2,",",".")}}</span>
                                                <i class="fas fa-pen fa-sm editar_valor_coletivo" data-btn-acomodacao="{{$acomodacao}}"></i>
                                            </td>

                                        </tr>
                                    </tfoot>
                            </table>
                        </div>
                    </div>

                @php $card_inicial = $dados[$i]->card; $i--; $ii=0;$total=0;@endphp
            @endif


        @endfor



    </div>

    @if($contagem == $quantidade)
    </tbody>
                                    <tfoot>
                                        <tr>
                                        <td colspan="4" class="text-center border-top border-white valor_plano">
                                            R$ <span class="aqui_total_change">{{number_format($total,2,",",".")}}</span>
                                            <i class="fas fa-pen fa-sm editar_valor_coletivo" data-btn-acomodacao="{{$acomodacao}}"></i>
                                        </td>
                                        </tr>
                                    </tfoot>
                            </table>
                        </div>
                    </div>
        @endif

<input type="hidden" name="valor" id="valor" value="">
<input type="hidden" name="data_vigencia" id="data_vigencia" value="">
<input type="hidden" name="data_boleto" id="data_boleto" value="">
<input type="hidden" name="valor_adesao" id="valor_adesao" value="">
<input type="hidden" name="acomodacao" id="acomodacao" value="">

<div id="btn_submit" style="clear:both;width:100%;"></div>
@else
    <p class="alert alert-danger">Sem Resultado para essa pesquisa =/</p>
@endif
<script>
    $(function(){
        // $(".valores-acomodacao").css("background-color","red");
        $('.valor_adesao').mask("#.##0,00", {reverse: true});
    });
</script>
