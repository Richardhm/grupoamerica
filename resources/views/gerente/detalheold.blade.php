@extends('adminlte::page')
@section('title', 'Gerenciavel - Baixas')

@section('content_top_nav_right')
   
    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt text-white"></i>
    </a>
    
@stop

@section('content_header')
    <h1 class=" border-bottom border-dark">Baixas</h1>
@stop





@section('content')
    <input type="hidden" id="valor_plano" value="{{$valor_plano}}">
    <input type="hidden" id="valor_corretora" value="{{$valor_corretora}}">
    <main>

        <section>
           <!-- 0 <h3 style="background-color:#123449;text-align:center;color:#FFF;margin:0;border-top-left-radius: 5px;border-top-right-radius: 5px;height:20px;"></h3> -->
            <table class="table table-sm table-striped table-bordered">
                <thead>
                    
                    <tr style="background-color:#123449;color:#FFF;"> 

                        <th colspan="6" style="vertical-align : middle;text-align:center;">
                            <h6 class="mx-auto" style="margin-top:6px;">{{$cliente}} - {{$cpf}}</h6>
                        </th>

                        <td colspan="3" style="text-align:center;">
                            Corretora
                        </td>

                        <td colspan="3" style="text-align:center;">
                            Corretor
                        </td>

                        <td style="text-align:center;">
                            Status
                        </td>

                    </tr>

                </thead>
                <tbody>
                    <tr>  
                        <th>Parcela</th>
                        <th>Contrato</th>
                        <th>Vencimento</th>
                        <th>Valor Plano</th>
                        <th>Baixa(Laura)</th>
                        <th>Atrasado</th>

                        <th>Comissão</th>
                        <th>%</th>
                        <th>Recebido</th>
                        
                        <th>Comissão</th>
                        <th>%</th>
                        <th>Pagar</th>

                        

                    </tr>
                    @foreach($dados as $co)
                        <tr>
                            <td>{{$co->parcela}}</td>
                            <td>{{$co->codigo_externo}}</td>
                            <td>{{date("d/m/Y",strtotime($co->vencimento))}}</td>
                            <td>R$ {{number_format($co->valor_plano_contratado,2,",",".")}}</td>
                            <td>{{date("d/m/Y",strtotime($co->data_baixa))}}</td>
                            <td>0</td>

                            <td>R$ {{number_format($co->comissao_valor_corretora,2,",",".")}}</td>
                            <td>
                                <input type="text" data-corretora="{{$co->id_corretora}}" data-configuracao-porcentagem="{{$co->porcentagem_parcela_corretora_id}}" id="porcentagem_corretora" value="{{$co->porcentagem_paga ? $co->porcentagem_paga : $co->porcentagem_parcela_corretora}}" style="width:45px">
                            </td>
                            <td>
                                <input type="text" style="color:#666;" name="recebido" data-corretora-valor="{{$co->id_corretora}}" data-configuracao-porcentagem-valor="{{$co->porcentagem_parcela_corretora_id}}" class="form-control-sm" id="recebido" value="{{$co->valor_pago != 0 ? $co->valor_pago : $co->comissao_valor_corretora }}">
                            </td>
                            
                            <td>
                                R$ {{number_format($co->comissao_valor_corretor,2,",",".")}}
                            </td>
                            <td>
                                <input type="text" data-corretor="{{$co->id_corretor}}" data-default-porcentagem="{{$co->porcentagem_parcela_corretor_id}}" id="porcentagem_corretor" value="{{$co->comissao_porcentagem_pago_corretor != 0 ? $co->comissao_porcentagem_pago_corretor :  $co->porcentagem_parcela_corretores}}" style="width:45px">
                            </td>
                            <td>
                                <input type="text" style="color:#666;" data-corretor="{{$co->id_corretor}}" class="form-control-sm" id="recebido_corretor" value="{{$co->comissao_valor_pago_corretor != 0 ? number_format($co->comissao_valor_pago_corretor,2,',','.') : number_format($co->comissao_valor_corretor,2,',','.')}}">
                            </td>
                            
                           
                            <td align="center" v-align="middle" style="vertical-align : middle;text-align:center;">
                                <select name="" id="mudar_pago_parcela" class="form-control-sm mx-auto d-flex" data-corretor="{{$co->id_corretor}}" data-corretora="{{$co->id_corretora}}">
                                    <option value="">Em Aberto</option>
                                    <option value="pago">Pago</option>
                                    <option value="repique">Repique</option>
                                </select>
                            </td>
                            
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        
        </section>

        <hr style="background-color:tomato" width="100%" />

        <section>
            



        </section>





    </main>



    

    <a href="{{route('gerente.index')}}" class="btn btn-block btn-lg mt-3 text-white" style="background-color:#123449;">Voltar</a>
@stop

@section('css')
    <style>
        .container_full_cards {display: flex;}
        .card_info {background-color:#123449;flex-basis: 33%;border-radius: 5px;}
        .historico_pagamento {background-color:#123449;flex-basis: 19%;margin-left:1%;color:#FFF;border-radius: 5px;}
        .historico_pagamento h3 {color:#FFF;border-bottom: 1px solid #FFF;text-align: center;padding:5px 0;}
        .historico_corretora {background-color:#123449;flex-basis: 33%;margin-left:1%;color:#FFF;border-radius: 5px;}
        .historico_corretor {background-color:#123449;flex-basis: 33%;margin-left:1%;color:#FFF;border-radius: 5px;}
    </style>
@stop

@section('js')
    <script src="{{asset('js/jquery.mask.min.js')}}"></script> 
    <script>
        $(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });             

            $('#recebido').mask('#.##0,00', {reverse: true});
            $('#recebido_corretor').mask('#.##0,00', {reverse: true});

            $("#porcentagem_corretora").on('change',function(){
                let id = $(this).attr("data-corretora");
                let valor = $(this).val();
                let valor_plano = $("#valor_plano").val();
                let valor_corretora = $("#valor_corretora").val();
                let id_configuracao_corretora = $(this).attr('data-configuracao-porcentagem');
                $.ajax({
                    url:"{{route('gerente.mudar.valor.corretora')}}",
                    method:"POST",
                    data:"id="+id+"&valor="+valor+"&valor_plano="+valor_plano+"&valor_corretora="+valor_corretora+"&id_configuracao_corretora="+id_configuracao_corretora+"&acao=porcentagem",
                    success:function(res) {
                        
                        $("#recebido").val(res.valor);
                        $("#porcentagem_corretora").val(res.porcentagem);
                    }
                });
            });


            $("#porcentagem_corretor").on('change',function(){
                let id = $(this).attr("data-corretor");
                let valor = $(this).val();
                let valor_plano = $("#valor_plano").val();
                let default_corretor = $(this).attr('data-default-porcentagem');
                $.ajax({
                    url:"{{route('gerente.mudar.valor.corretor')}}",
                    method:"POST",
                    data:"id="+id+"&valor="+valor+"&valor_plano="+valor_plano+"&default_corretor="+default_corretor+"&acao=porcentagem",
                    success:function(res) {
                        
                        $("#recebido_corretor").val(res.valor);
                        $("#porcentagem_corretor").val(res.porcentagem);
                    }
                });
            });

            $("#recebido").on('change',function(){
                let valor = $(this).val();
                let id = $(this).attr("data-corretora-valor");
                let valor_plano = $("#valor_plano").val();
                let id_configuracao = $(this).attr("data-configuracao-porcentagem-valor");
                $.ajax({
                    url:"{{route('gerente.mudar.valor.corretora')}}",
                    method:"POST",
                    data:"id="+id+"&valor="+valor+"&valor_plano="+valor_plano+"&id_configuracao="+id_configuracao+"&acao=valor",
                    success:function(res) {
                        
                        $("#porcentagem_corretora").val(res) 
                        
                    }
                });
                
            });

            $("#recebido_corretor").on('change',function(){
                let valor = $(this).val();
                let id = $(this).attr("data-corretor");
                let valor_plano = $("#valor_plano").val();
                $.ajax({
                    url:"{{route('gerente.mudar.valor.corretor')}}",
                    method:"POST",
                    data:"id="+id+"&valor="+valor+"&valor_plano="+valor_plano+"&acao=valor",
                    success:function(res) {
                        
                        $("#porcentagem_corretor").val(res);    
                        
                    }
                });


                
            });


            $("#mudar_pago_parcela").on('change',function(){
                let valor = $(this).val();
                let corretor = $(this).attr('data-corretor');
                let corretora = $(this).attr('data-corretora');
                let select = $(this);
                $.ajax({
                    url:"{{route('gerente.administradorapagoucomissao')}}",
                    method:"POST",
                    data:"corretor="+corretor+"&corretora="+corretora,
                    success:function(res) {
                        select.closest('tr').slideUp("slow");
                        //console.log(res);    
                        // $("#porcentagem_corretor").val(res);    
                        // $("#recebido_corretor").val(res.valor);
                        // $("#porcentagem_corretor").val(res.porcentagem);
                    }
                });
            });





        });
    </script>
@stop





