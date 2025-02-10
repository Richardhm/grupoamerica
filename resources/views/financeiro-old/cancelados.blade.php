@extends('adminlte::page')
@section('title', 'Cancelados')

@section('content_top_nav_right')
   
    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt text-white"></i>
    </a>
    
@stop

@section('content_header')
    <h1 class=" border-bottom border-dark">Histórico</h1>
@stop


@section('content')

<div style="display:flex;justify-content: space-between;">
    
    <section class="p-1" style="flex-basis:49%;background-color:#123449;border-radius:5px;padding:5px;">
            
        <div class="d-flex">
            <div style="flex-basis:25%;">
                <span class="text-white" style="font-size:0.81em;">Administradora:</span>
                <input type="text" value="{{$dados->administradora->nome}}" name="administradora_individual" id="administradora_individual" class="form-control  form-control-sm" readonly>
            </div>
            <div style="flex-basis:33%;margin:0 1%;">    
                <span class="text-white" style="font-size:0.81em;">Tipo Plano:</span>
                <input type="text" value="{{$dados->plano->nome}}"   id="tipo_plano_individual" class="form-control  form-control-sm" readonly>
            </div>
            <div style="flex-basis:40%;margin-top:1%;" id="status">
                <span class="text-white" style="margin:0;padding:0;font-size:0.81em;display:flex;">
                    <span style="flex-basis:50%;">Status:</span>
                </span>
                <input type="text" id="status_individual_view" value="{{$dados->financeiro->nome}}" class="form-control form-control-sm" readonly>
            </div>    
        </div>

        <div class="d-flex">
            <div style="flex-basis:40%;">
                <span class="text-white" style="font-size:0.81em;">Cliente:</span>
                <input type="text" name="cliente" id="cliente" class="form-control form-control-sm" value="{{$dados->clientes->nome}}" readonly>
            </div>
            <div style="flex-basis:28%;margin:0 1%;">
                <span class="text-white" style="font-size:0.81em;">Data Nascimento:</span>
                <input type="text" name="data_nascimento" id="data_nascimento" class="form-control form-control-sm" value="{{date('d/m/Y',strtotime($dados->clientes->data_nascimento))}}" readonly>
            </div>
            <div style="flex-basis:30%;">
                <span class="text-white" style="font-size:0.81em;">Codigo Externo:</span>
                <input type="text" name="codigo_externo" id="codigo_externo_individual" value="{{$dados->codigo_externo}}" class="form-control form-control-sm" readonly>
            </div>    
        </div>
        
        <div class="d-flex">
            <div style="flex-basis:33%;">
                <span class="text-white" style="font-size:0.81em;">CPF:</span>
                <input type="text" id="cpf" class="form-control form-control-sm" value="{{$dados->clientes->cpf}}" readonly>
            </div>
            <div style="flex-basis:33%;margin:0 1%;display:flex;align-items: flex-end;">
                <span style="flex-basis:90%;">
                <span class="text-white" style="font-size:0.81em;">Celular:</span>
                    <input type="text" id="celular_individual_view_input" value="{{$dados->clientes->celular}}" class="form-control form-control-sm" readonly>
                </span>
                <a class="" style="background: #25cb66;flex-basis:10%;
                    border-radius: 8px !important;
                    padding: 3px 7px;
                    text-align: center;
                    color:#FFF;
                    font-weight: bold;
                    margin-bottom: 0px;
                    text-decoration:none;" href="https://api.whatsapp.com/send?phone=55{{$dados->clientes->celular}}&amp;text=Oi tudo bem?" target="_blank" rel="nofollow">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
            <div style="flex-basis:33%;">
                <span class="text-white" style="font-size:0.81em;">Telefone:</span>
                <input type="text" id="telefone_individual_view_input" class="form-control form-control-sm" readonly>
            </div>
        </div>

        <div class="d-flex">
            <div style="flex-basis:40%;">
                <span class="text-white" style="font-size:0.81em;">Email:</span>
                <input type="text" id="email" class="form-control form-control-sm" value="{{$dados->clientes->email}}" readonly>
            </div>
            <div style="flex-basis:25%;margin:0 1%;">
                <span class="text-white" style="font-size:0.81em;">Numero Registro:</span>
                <input type="text" id="numero_registro" class="form-control  form-control-sm" value="{{$dados->clientes->numero_registro_plano}}" readonly>
            </div>
            <div style="flex-basis:35%;">
                <span class="text-white" style="font-size:0.81em;">Carteirinha:</span>
                <input type="text" id="carteirinha" class="form-control  form-control-sm" value="{{$dados->clientes->cateirinha}}" readonly>
            </div>
        </div>
        <div class="d-flex">
            <div style="flex-basis:18%;">
                <span class="text-white" style="font-size:0.81em;">CEP:</span>
                <input type="text" name="cep" id="cep_individual_cadastro" value="{{$dados->clientes->cep}}" class="form-control form-control-sm" readonly>
            </div>
            <div style="flex-basis:35%;margin:0 1%;">
                <span class="text-white" style="font-size:0.81em;">Cidade:</span> 
                <input type="text" id="cidade" class="form-control  form-control-sm" value="{{$dados->clientes->cidade}}" readonly>
            </div>
            
            <div style="flex-basis:45%;">
                <span class="text-white" style="font-size:0.81em;">Bairro:</span>
                <input type="text" id="bairro_individual_cadastro" class="form-control form-control-sm" value="{{$dados->clientes->bairro}}" readonly>
            </div>                       
        </div>
        <div class="d-flex">
            <div style="flex-basis:60%;">
                <span class="text-white" style="font-size:0.81em;">Rua:</span>
                <input type="text" id="rua_individual_cadastro" class="form-control form-control-sm" value="{{$dados->clientes->rua}}" readonly>
            </div>
            <div style="flex-basis:29%;margin:0 1%;">
                <span class="text-white" style="font-size:0.81em;">Complemento:</span>
                <input type="text" id="complemento_individual_cadastro" class="form-control form-control-sm" value="{{$dados->clientes->complemento}}" readonly>
            </div>
            <div style="flex-basis:10%;margin-right:1%;">
                <span class="text-white" style="font-size:0.81em;">UF:</span>
                <input type="text" id="uf" class="form-control form-control-sm" value="{{$dados->clientes->uf}}" readonly>
            </div>      
        </div>       
        <div class="d-flex">
            <div style="flex-basis:23%;">
                <span class="text-white" style="font-size:0.81em;">Data Contrato:</span>
                <input type="text" name="data_contrato" id="data_contrato" class="form-control form-control-sm" value="{{date('d/m/Y',strtotime($dados->clientes->created_at))}}" readonly>
            </div>
            <div style="flex-basis:23%;margin:0 1%;">
                <span class="text-white" style="font-size:0.81em;">Valor Contrato:</span>
                <input type="text" name="valor_contrato" id="valor_contrato" value="R$ {{number_format($dados->valor_plano - 25,2,',','.')}}" class="form-control  form-control-sm" readonly>
            </div>
            <div style="flex-basis:23%;">
                <span class="text-white" style="font-size:0.81em;">Valor Adesão:</span>
                <input type="text" name="valor_adesao" id="valor_adesao" value="R$ {{number_format($dados->valor_adesao,2,',','.')}}"  class="form-control  form-control-sm" readonly>
            </div>

            <div style="flex-basis:20%;margin-left:1%;">
                <span class="text-white" style="font-size:0.81em;">Data Boleto:</span>
                <input type="text" name="data_boleto" id="data_boleto" value="{{date('d/m/Y',strtotime($dados->data_boleto))}}" class="form-control  form-control-sm" readonly>
            </div>
            <div style="flex-basis:8%;margin-left:1%;">    
                <span class="text-white" style="font-size:0.81em;">Vidas</span>
                <input type="text" name="quantidade_vidas" id="quantidade_vidas_individual_cadastrar" value="{{$dados->clientes->quantidade_vidas}}" class="form-control  form-control-sm" readonly>
            </div>  
        </div>
            <div class="d-flex">  
                <div style="flex-basis:23%;margin-right:1%;">
                <span class="text-white" style="font-size:0.81em;">Data Vigência:</span>
                <input type="text" name="data_vigencia" id="data_vigencia" class="form-control  form-control-sm" value="{{date('d/m/Y',strtotime($dados->data_vigencia))}}" readonly>
            </div>
            <div style="flex-basis:35%;margin:0 1%;">
                <span class="text-white" style="font-size:0.81em;">Rede Plano:</span>
                <input type="text" id="rede_plano" class="form-control  form-control-sm" value="{{$dados->clientes->rede_plano}}" readonly>
            </div>
            <div style="flex-basis:39%;">
                <span class="text-white" style="font-size:0.81em;">Segmentaçao:</span>
                <input type="text" id="segmentacao" class="form-control  form-control-sm" value="{{$dados->clientes->segmentacao_plano}}" readonly>
            </div>
            <input type="hidden" id="cliente_id_alvo_individual" />
        </div>    
        <div class="d-flex">
            <div style="flex-basis:70%;margin-right:1%;">
                <span class="text-white" style="font-size:0.81em;">Plano:</span>
                <input type="text" id="plano" class="form-control  form-control-sm" value="{{$dados->clientes->nm_plano}}" readonly>
            </div>

            <div style="flex-basis:29%;">
                <span class="text-white" style="font-size:0.81em;">Tipo Plano:</span>
                <input type="text" id="tipo_acomodacao_plano" class="form-control form-control-sm" value="{{$dados->clientes->tipo_acomodacao_plano}}" readonly>     
            </div>    
        </div> 
    </section> 

    <section class="p-1" style="flex-basis:49%;background-color:#123449;color:#FFF;">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="text-center mt-1 ml-1">Pagamentos</h5>
                <p class="align-self-center mt-3 mr-2">{{$cliente->user->name}}</p>
            </div>
            <table class="table table-sm h-50" style="margin:0;padding:0;">

                <thead>
                    <tr>
                        <th style="font-size:0.875em;">Parcela</th>
                        <th style="font-size:0.875em;">Contrato</th>
                        <th style="font-size:0.875em;">Vencimento</th>
                        <th style="font-size:0.875em;">Valor</th>
                        <th style="font-size:0.875em;">Baixa</th>
                        <th style="font-size:0.875em;margin-left:10px;text-align:center;" align="center">Atrasado</th>
                        <th style="font-size:0.875em;text-align:right;">
                            <span style="margin-right:12px;">
                                Comissão
                            </span>
                        </th>
                        <th>Status Comissao</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_cliente = 0;
                        $total_comissao = 0;
                    @endphp    
                    @foreach($dados->comissao->comissoesLancadas as $kk => $cr)
                            @php
                                if(!empty($cr->data_baixa)):
                                    $total_comissao += $cr->valor;
                                else: 
                                    $total_cliente += $cr->valor;
                                endif;
                            @endphp
                        <tr>
                            <td class="" style="font-size:0.875em;">
                                @if($cr->parcela == 1)
                                    Adesão 
                                @else
                                    <span class="text-center">{{$cr->parcela}} º Parcela</span>
                                @endif
                            </td>
                            <td class="text-center" style="font-size:0.875em;">
                                @if($cr->parcela == 1)
                                    {{number_format($dados->valor_plano,2,',','.')}}
                                @else
                                    {{number_format($dados->valor_plano - 25,2,',','.')}}
                                @endif
                            </td>
                            <td style="font-size:0.875em;">
                               
                                    {{date('d/m/Y',strtotime($cr->data))}}
                               
                                
                            
                            </td>
                            <td style="font-size:0.875em;">
                                @if($cr->valor_pago > 0) 
                                    {{number_format($cr->valor_pago,2,",",".") ?? 0}}
                                @else
                                    <span style="margin-left:10px;">---</span>
                                @endif
                            </td>
                            <td style="font-size:0.875em;">

                                @if($cr->cancelados == 1)
                                    Cancelado
                                @else
                                    {{date('d/m/Y',strtotime($cr->data_baixa))}}
                                @endif



                                
                                     
                            <td style="font-size:0.875em;text-align:center;">{{$cr->quantidade_dias}}</td>
                            <td style="font-size:0.875em;" align="right">

                                @if($cr->cancelados == 1)
                                    Cancelado @if($cr->valor > 0) <small style="color:red;">({{$cr->valor}})</small>  @endif
                                @elseif($cr->valor > 0 && $cr->cancelados != 1)
                                    <span style="margin-right:15px;">{{number_format($cr->valor,2,',','.')}}</span>
                                @else
                                    <span style="margin-right:28px;">---</span>
                                @endif



                                
                            </td>
                            <td align="center">---</td>
                        </tr>
                    @endforeach
                </tbody>
                
            </table>

    </section>    

</div> 
     
<a class="btn btn-block btn-lg mt-3 text-white back" style="background-color:#123449;">Voltar</a>                           

@stop

@section('js')
    <script src="{{asset('js/jquery.mask.min.js')}}"></script>   
    <script>
        $(function(){
            $("#cpf").mask('000.000.000-00');    
            $("#celular_individual_view_input").mask('(00) 0 0000-0000');    

            $(".back").on('click',function(){
                window.history.go(-1);
                return false;
            });


        });
    </script>
@stop

@section('css')

    <style>

        .container_full_cards {display: flex;}
        .card_info {flex-basis:45%;border-radius:5px;display:flex;justify-content:center;}
        .card_info table {width:45%;}
        .historico_pagamento {background-color:#123449;flex-basis: 19%;margin-left:1%;color:#FFF;border-radius:5px;}
        .historico_pagamento h3 {color:#FFF;border-bottom: 1px solid #FFF;text-align: center;padding:5px 0;}
        
        .historico_corretor {background-color:#123449;flex-basis:50%;margin-left:1%;color:#FFF;border-radius:5px;}
        

    </style>


@stop