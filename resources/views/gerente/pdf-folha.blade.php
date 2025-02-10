<html>
    <head>
        <title></title>
        <style>
            * {margin:0;padding:0;font-size:1em;}
            td {font-size: 0.7em;}
        </style>
    </head>
    <body>
       <div style="width:95%;margin:0 auto;padding:5px 0;">
            <p style="font-size:0.75em;">Grupo America</p>
       </div>

       <div style="border-top:1px solid black;display:block;width:95%;left:20px;position:absolute;height:70px;padding:10px 0;">
            <div style="width:90%;position:relative;left:0;float:left;">
                <p>COMPOSIÇÃO SALARIAL</p>
                <p>Vendedor: {{$user}}</p>
                <p>Referência: {{$meses}} / {{$ano}}</p>
            </div>

            @if($logo && $logo != '')

                <div style="width:10%;position:relative;right:0;top:0;margin-bottom:5px;float:right;background-color:#A9A9A9;padding:2px;border-radius:5px;">
                    <img src="{{$logo}}" alt="Logo" id="Logo" style="width:100%;height:100%;" />
                </div>

            @endif

       </div>


       <div style="clear: both;"></div>


       <div style="display:block;height:80px;width:95%;left:20px;position:relative;border-top:1px solid black;padding:2px;">
            <div>
                <span style="width:89%;left:0;float:left;">1 Salário Mês</span>
                <span style="width:11%;right:0;top:0;float:right;text-align:right;">
                    <div style="width:75%;float:right;text-align:right;">{{number_format($salario,2,",",".")}}</div>
                </span>
            </div>
            <div style="clear: both;"></div>
            <div>
                <span style="width:89%;left:0;float:left;">1 Comissão</span>
                <div style="width:11%;right:0;top:0;float:right;">

                    <div style="width:75%;float:right;text-align:right;">{{number_format($comissao,2,",",".")}}</div>
                </div>
            </div>
            <div style="clear: both;"></div>
            <div>
                <span style="width:89%;left:0;float:left;">1 Premiação</span>
                <div style="width:11%;right:0;top:0;float:right;">
                    <div style="width:75%;float:right;text-align:right;">{{number_format($premiacao,2,",",".")}}</div>
                </div>
            </div>
            <div style="clear: both;"></div>
           <div>
               <span style="width:89%;left:0;float:left;">1 Desconto</span>
               <span style="width:11%;right:0;top:0;float:right;text-align:right;">{{number_format($desconto,2,",",".")}}</span>
           </div>
           <div style="clear: both;"></div>
           <div>
               <span style="width:89%;left:0;float:left;">1 Estorno</span>
               <span style="width:11%;right:0;top:0;float:right;text-align:right;">{{number_format($estorno,2,",",".")}}</span>
           </div>
           <div style="clear: both;"></div>
           <div>
               <span style="width:89%;left:0;float:left;">1 Odonto</span>
               <span style="width:11%;right:0;top:0;float:right;text-align:right;">{{number_format($odonto,2,",",".")}}</span>
           </div>
           <div style="clear: both;"></div>
           <div>
                <span style="width:50%;left:0;float:left;">Total Geral</span>
                <span style="width:40%;right:0;top:0;float:right;text-align:right;">{{number_format($total,2,",",".")}}</span>
           </div>
        </div>

        <div style="clear: both;"></div>

        <div style="border-top:1px solid black;width:95%;margin:0 auto;border-bottom:1px solid black;padding:5px 0;">
            <p style="font-size:0.875em;">ACOMPANHAMENTO DE VENDAS</p>
            <p style="font-size:0.875em;">Período de {{$primeiro_dia}} até {{$ultimo_dia}}</p>
            <p style="font-size:0.875em;">Status: Somente Pago</p>
        </div>

        <div style="clear: both;"></div>

        @php
            $total_plano_individual = 0;
            $total_comissao_individual = 0;
            $total_desconto_individual = 0;
            $total_valor_individual = 0;
            $total_plano_coletivo = 0;
            $total_comissao_coletivo = 0;
            $total_plano_empresarial = 0;
            $total_comissao_empresarial = 0;
            $total_desconto_empresarial = 0;
            $total_estorno_calculado = 0;
            $total_odonto_calculado = 0;

            $total_empresarial = 0;
            $i_individual = 0;
            $i_coletivo = 0;
            $i_empresarial = 0;
            $i_estorno = 0;
            $i_odonto = 0;
        @endphp

        @if(count($individual) >= 1 && $boolean_individual)





        <div style="width:95%;border-bottom:1px solid black;margin:0 auto;background-color:rgb(231,230,230);font-weight:bold;padding:5px 0;">Plano Individual</div>

        <table style="width:95%;margin:0 auto;">
            <thead style="border-bottom:1px solid black;">
                <tr>
                    <td>#</td>
                    <td>Admin</td>
                    <td>Contrato</td>
                    <td>Data</td>
                    <td>Cliente</td>
                    <td>Parcela</td>
                    <td align="center">Valor</td>
                    @if($tipo == "corretora")
                    <td align="center">Desconto</td>
                    <td align="right">Comissão</td>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($individual as $dd)
                    @php
                        ++$i_individual;
                        $total_plano_individual += $dd->valor_plano_contratado;
                        $total_comissao_individual += $dd->comissao != null ? $dd->comissao : $dd->comissao;
                        $total_desconto_individual += $dd->desconto;
                        $total_valor_individual    += $dd->valor_plano_contratado;
                    @endphp
                    <tr>
                        <td style="width:3%;">{{$i_individual}}</td>
                        <td style="width:10%;">HAPVIDA</td>
                        <td style="width:8%;">{{$dd->codigo_externo}}</td>
                        <td style="width:8%;">{{date('d/m/Y',strtotime($dd->created_at))}}</td>
                        <td style="font-size:0.6em;width:35%;">{{mb_convert_case($dd->cliente,MB_CASE_UPPER,"UTF-8")}}</td>
                        <td style="width:7%;">Parcela {{$dd->parcela}}</td>
                        <td style="width:15%;" align="center">{{number_format($dd->valor_plano_contratado,2,",",".")}}</td>
                        @if($tipo == "corretora")
                        <td style="width:8%;" align="center">{{$dd->desconto}}</td>
                        <td style="width:5%;" align="right">{{$dd->comissao != null ? number_format($dd->comissao,2,",",".") : 0}}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
            @if($tipo == "corretora")
                <tfoot style="border-top:1px solid black;">
                    <tr>
                        <td colspan="7"></td>


                        <td align="center">
                            @php
                                echo number_format($total_desconto_individual,2,",",".");
                            @endphp
                        </td>

                        <td align="right">
                            @php
                               echo number_format($total_comissao_individual,2,",",".");
                            @endphp
                        </td>
                    </tr>
                </tfoot>
            @else
                <tfoot style="border-top:5px solid black;">
                <tr>
                    <td colspan="6"></td>




                    <td align="center">
                        @php
                            echo number_format($total_comissao_individual,2,",",".");
                        @endphp
                    </td>
                </tr>
                </tfoot>
            @endif
        </table>
        @endif
        @if(count($coletivo) >= 1 && $boolean_coletivo)
            @php
                $contrato_coletivo = $coletivo[0]->codigo_externo;
                $contrato_coletivo_total = 0;
                $contrato_coletivo_plano = 0;
                $ii=0;
                $status=null;
            @endphp
        <div style="width:95%;border-bottom:1px solid black;margin:0 auto;background-color:rgb(231,230,230);font-weight:bold;padding:5px 0;">Plano Coletivo</div>
        <table style="width:95%;margin:0 auto;">
            <thead style="border-bottom:1px solid black;">
                <tr>
                    <td>#</td>
                    <td>Admin</td>
                    <td>Contrato</td>
                    <td>Data</td>
                    <td>Cliente</td>
                    <td>Parcela</td>
                    <td align="center">Valor</td>
                    @if($tipo == "corretora")
                    <td align="center">Desconto</td>
                    <td align="right">Comissão</td>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($coletivo as $d)
                    @php
                        ++$i_coletivo;
                        if($d->codigo_externo == $contrato_coletivo) {
                            $status=1;
                            $ii++;

                        } else {
                            $contrato_coletivo = $d->codigo_externo;
                            $status=1;
                            $ii=1;
                        }
                    @endphp
                    @php
                        if(isset($d->valor_plano_contratado) && !empty($d->valor_plano_contratado) && $d->valor_plano_contratado) {
                            $total_plano_coletivo += $d->valor_plano_contratado;
                            $total_comissao_coletivo += $d->comissao_esperada;
                        }
                    @endphp
                    <tr>
                        <td style="width:3%;">{{$i_coletivo}}</td>
                        <td style="width:10%;">{{$d->administradora}}</td>
                        <td style="width:8%;">{{$d->codigo_externo}}</td>
                        <td style="width:8%;">{{date('d/m/Y',strtotime($d->created_at))}}</td>
                        <td style="font-size:0.6em;width:35%;">{{mb_convert_case($d->cliente,MB_CASE_UPPER,"UTF-8")}}</td>
                        <td style="width:7%;">Parcela {{$d->parcela}}</td>
                        <td style="width:15%;" align="center">{{number_format($d->valor_plano_contratado,2,",",".")}}</td>
                        @if($tipo == "corretora")
                        <td style="width:8%;" align="center">
                            @php
                                $contrato_coletivo_total += $d->desconto;
                                echo number_format($d->desconto,2,",",".");
                            @endphp
                        </td>
                        <td style="width:5%;" align="right">{{number_format($d->comissao_esperada,2,",",".")}}</td>
                        @endif
                    </tr>
                    @php $status=0;@endphp
                @endforeach
            </tbody>
            <tfoot style="border-top:1px solid black;">
                <tr>
                    <td colspan="7"></td>

                    @if($tipo == "corretora")
                    <td align="center">
                        @php echo number_format($contrato_coletivo_total,2,",",".");@endphp
                    </td>
                    <td align="right">
                        @php echo number_format($total_comissao_coletivo,2,",",".") ?? '';@endphp
                    </td>
                    @endif
                </tr>
            </tfoot>
        </table>

        @endif

        @if(count($empresarial) >= 1 && $boolean_empresarial)
        <div style="width:95%;border-bottom:1px solid black;margin:0 auto;background-color:rgb(231,230,230);font-weight:bold;padding:5px 0;">Empresarial</div>
        <table style="width:95%;margin:0 auto;">
            <thead style="border-bottom:1px solid black;">
                <tr>
                    <td>#</td>
                    <td>Admin</td>
                    <td>Contrato</td>
                    <td align="center">Data</td>
                    <td>Cliente</td>
                    <td align="center">Parcela</td>
                    <td >Valor</td>
                    @if($tipo == "corretora")
                    <td>Desconto</td>
                    <td align="center">Comissão</td>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($empresarial as $e)
                    @php
                       ++$i_empresarial;

                       $total_plano_empresarial += $e->valor_plano_contratado;
                       $total_comissao_empresarial += $e->comissao;
                       $total_desconto_empresarial += $e->desconto;
                    @endphp
                    <tr>
                        <td style="width:3%;">{{$i_empresarial}}</td>
                        <td style="width:8%;">HAPVIDA</td>
                        <td style="width:6%;">{{$e->codigo_externo}}</td>
                        <td style="width:8%;" align="center">{{$e->data}}</td>
                        <td style="width:30%;">{{mb_convert_case($e->cliente,MB_CASE_UPPER,"UTF-8")}}</td>
                        <td style="width:8%;"  align="center">Parcela {{$e->parcela}}</td>
                        <td style="width:8%;">{{$e->valor_plano_contratado}}</td>
                        @if($tipo == "corretora")
                        <td style="width:8%;">{{number_format($e->desconto,2,",",".")}}</td>
                        <td style="width:8%;" align="center">{{number_format($e->comissao,2,",",".")}}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
            <tfoot style="border-top:1px solid black;">
                <tr>
                    <td colspan="7"></td>


                    @if($tipo == "corretora")
                    <td>
                        @php
                            echo number_format($total_desconto_empresarial,2,",",".") ?? '';
                        @endphp
                    </td>

                    <td align="center">
                        @php
                            echo number_format($total_comissao_empresarial,2,",",".") ?? '';
                        @endphp
                    </td>
                    @endif

                </tr>
            </tfoot>
        </table>
        @endif

        @if($estorno_table && $estorno != 0)
            <div style="width:95%;border-bottom:1px solid black;margin:0 auto;background-color:rgb(231,230,230);font-weight:bold;padding:5px 0;">Estorno</div>
            <table style="width:95%;margin:0 auto;">
                <thead style="border-bottom:1px solid black;">
                <tr>
                    <td>#</td>
                    <td>Admin</td>
                    <td>Plano</td>
                    <td>Contrato</td>
                    <td align="center">Data</td>
                    <td>Cliente</td>
                    <td style="text-align:center;">Parcela</td>
                    <td >Valor</td>
                    <td align="center">Estorno</td>
                </tr>
                </thead>
                <tbody>
                @foreach($estorno_table as $et)
                    @php
                        ++$i_estorno;

                        $total_estorno_calculado += $et->total_estorno;
                    @endphp
                    <tr>
                        <td style="width:3%;">{{$i_estorno}}</td>
                        <td style="width:8%;">{{$et->administradora}}</td>
                        <td style="width:8%;">{{$et->plano}}</td>
                        <td style="width:6%;">{{$et->contrato}}</td>
                        <td style="width:8%;" align="center">{{$et->data}}</td>
                        <td style="width:30%;">{{mb_convert_case($et->cliente,MB_CASE_UPPER,"UTF-8")}}</td>
                        <td style="width:8%;text-align: center;">Parcela {{$et->parcela}}</td>
                        <td style="width:8%;">{{number_format($et->valor,2,",",".")}}</td>
                        <td style="width:8%;" align="center">{{number_format($et->total_estorno,2,",",".")}}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot style="border-top:1px solid black;">
                    <tr>
                        <td colspan="8"></td>
                        <td align="center">
                            @php
                                echo number_format($total_estorno_calculado,2,",",".") ?? '';
                            @endphp
                        </td>
                    </tr>
                </tfoot>
            </table>
        @endif

        @if($odonto_list && count($odonto_list) >= 1)
            <div style="width:95%;border-bottom:1px solid black;margin:0 auto;background-color:rgb(231,230,230);font-weight:bold;padding:5px 0;">Odonto</div>
            <table style="width:95%;margin:0 auto;">
                <thead style="border-bottom:1px solid black;">
                <tr>
                    <td>#</td>
                    <td>Cliente</td>
                    <td>Valor</td>
                    <td>Comissao</td>

                </tr>
                </thead>
                <tbody>
                @foreach($odonto_list as $o)
                    @php
                        ++$i_odonto;
                        $total_odonto_calculado += $o->comissao;
                    @endphp
                    <tr>
                        <td>{{$i_odonto}}</td>
                        <td>{{$o->nome}}</td>
                        <td>{{number_format($o->valor,2,",",".")}}</td>
                        <td>{{number_format($o->comissao,2,",",".")}}</td>

                    </tr>
                @endforeach
                </tbody>
                <tfoot style="border-top:1px solid black;">
                <tr>
                    <td colspan="3"></td>
                    <td>
                        @php
                            echo number_format($total_odonto_calculado,2,",",".") ?? '';
                        @endphp
                    </td>
                </tr>
                </tfoot>
            </table>


        @endif



    </body>
</html>
