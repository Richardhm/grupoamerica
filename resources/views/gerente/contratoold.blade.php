@extends('adminlte::page')
@section('title', 'Detalhe do Contrato')

@section('content_top_nav_right')
   
    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt text-white"></i>
    </a>
    
@stop

@section('content_header')
    <!-- <h1 class=" border-bottom border-dark">Baixas</h1> -->
@stop





@section('content')
    
    <section class="d-flex">

        <main style="flex-basis:35%;background-color:#123449;padding:5px;">
            
                            
                            <div class="d-flex">

                                <div style="flex-basis:32%;">
                                    <span class="text-white" style="font-size:0.81em;">Corretor:</span>
                                    <input type="text" value="{{$dados->clientes->user->name}}" id="name_individual" class="form-control  form-control-sm" readonly>
                                </div>


                                
                                <div style="flex-basis:32%;margin:0 1%;">
                                    <span class="text-white" style="font-size:0.81em;">Administradora:</span>
                                    <input type="text" value="{{$dados->administradora->nome}}" name="administradora_individual" id="administradora_individual" class="form-control  form-control-sm" readonly>
                                </div>
                    
                                <div style="flex-basis:33%;">    
                                    <span class="text-white" style="font-size:0.81em;">Tipo Plano:</span>
                                    <input type="text" value="{{$dados->plano->nome}}"   id="tipo_plano_individual" class="form-control  form-control-sm" readonly>
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
                                    <input type="text" id="celular" value="{{$dados->clientes->celular}}" class="form-control form-control-sm" readonly>
                    
                    
                    
                    
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
                                    <input type="text" id="telefone" class="form-control form-control-sm" value="{{$dados->clientes->telefone ?? ''}}" readonly>
                                </div>
                                
                            </div>
                        
                        
                            <div class="d-flex">
                                
                                
                    
                                <div style="flex-basis:40%;">
                                    <span class="text-white" style="font-size:0.81em;">Email:</span>
                                    <input type="text" id="email" class="form-control form-control-sm" value="{{$dados->clientes->email}}" readonly>
                                </div>
                    
                                <div style="flex-basis:18%;margin:0 1%;">
                                    <span class="text-white" style="font-size:0.81em;">CEP:</span>
                                    <input type="text" id="cep" value="{{$dados->clientes->cep}}" class="form-control form-control-sm" readonly>
                                </div>
                                <div style="flex-basis:35%;margin-right:1%;">
                                    <span class="text-white" style="font-size:0.81em;">Cidade:</span> 
                                    <input type="text" id="cidade" class="form-control  form-control-sm" value="{{$dados->clientes->cidade}}" readonly>
                                </div>
                                
                                <div style="flex-basis:10%;">
                                    <span class="text-white" style="font-size:0.81em;">UF:</span>
                                    <input type="text" id="uf" class="form-control form-control-sm" value="{{$dados->clientes->uf}}" readonly>
                                </div>      
                    
                    
                    
                            </div>
                        
                        
                            <div class="d-flex">
                                
                                <div style="flex-basis:30%;">
                                    <span class="text-white" style="font-size:0.81em;">Bairro:</span>
                                    <input type="text" id="bairro" class="form-control form-control-sm" value="{{$dados->clientes->bairro}}" readonly>
                                </div>   
                                
                                <div style="flex-basis:40%;margin:0 1%;">
                                    <span class="text-white" style="font-size:0.81em;">Rua:</span>
                                    <input type="text" id="rua" class="form-control form-control-sm" value="{{$dados->clientes->rua}}" readonly>
                                </div>
                    
                                <div style="flex-basis:29%;">
                                    <span class="text-white" style="font-size:0.81em;">Complemento:</span>
                                    <input type="text" id="complemento" class="form-control form-control-sm" value="{{$dados->clientes->complemento}}" readonly>
                                </div>    
                    
                    
                            </div>
                                                   
                            <div class="d-flex">
                                <div style="flex-basis:18%;">
                                    <span class="text-white" style="font-size:0.81em;">Data Contrato:</span>
                                    <input type="text" name="data_contrato" id="data_contrato" class="form-control form-control-sm" value="{{date('d/m/Y',strtotime($dados->clientes->created_at))}}" readonly>
                                </div>
                    
                                <div style="flex-basis:18%;margin:0 1%;">
                                    <span class="text-white" style="font-size:0.81em;">Valor Contrato:</span>
                                    <input type="text" name="valor_contrato" id="valor_contrato" value="R$ {{number_format($dados->valor_plano,2,',','.')}}" class="form-control  form-control-sm" readonly>
                                </div>
                    
                                <div style="flex-basis:18%;margin-right:1%;">
                                    <span class="text-white" style="font-size:0.81em;">Valor Adesão:</span>
                                    <input type="text" name="valor_adesao" id="valor_adesao" value="R$ {{number_format($dados->valor_adesao,2,',','.')}}"  class="form-control  form-control-sm" readonly>
                                </div>
                    
                                <div style="flex-basis:18%;margin-right:1%;">
                                    <span class="text-white" style="font-size:0.81em;">Data Boleto:</span>
                                    <input type="text" name="data_boleto" id="data_boleto" value="{{date('d/m/Y',strtotime($dados->data_boleto))}}" class="form-control  form-control-sm" readonly>
                                </div>
                    
                    
                                <div style="flex-basis:8%;margin-right:1%;">    
                                    <span class="text-white" style="font-size:0.81em;">Vidas</span>
                                    <input type="text" name="quantidade_vidas" id="quantidade_vidas_individual_cadastrar" value="{{$dados->somarCotacaoFaixaEtaria[0]->soma}}" class="form-control  form-control-sm" readonly>
                                </div>
                    
                                <div style="flex-basis:18%;">
                                    <span class="text-white" style="font-size:0.81em;">Data Vigência:</span>
                                    <input type="text" name="data_vigencia" id="data_vigencia" class="form-control  form-control-sm" value="{{date('d/m/Y',strtotime($dados->data_vigencia))}}" readonly>
                                </div>
                                    
                            </div>
                        
                            
                            <div class="d-flex" style="justify-content: space-between;">
                                <div style="flex-basis:48%;">
                                    <span class="text-white" style="font-size:0.81em;">Desconto Corretora:</span>
                                    <input type="text" id="desconto_corretora" class="form-control  form-control-sm" value="{{number_format($dados->desconto_corretora,2,',','.') ?? 0}}" readonly>
                                </div>
                    
                                <div style="flex-basis:48%;">
                                    <span class="text-white" style="font-size:0.81em;">Desconto Corretor:</span>
                                    <input type="text" id="desconto_corretor" class="form-control  form-control-sm" value="{{number_format($dados->desconto_corretor,2,',','.') ?? 0}}" readonly>
                                </div>
                            </div>
                            
                            
                                <div class="d-flex" style="justify-content: space-between;">
                                    <div style="flex-basis:48%;">
                                        <span class="text-white" style="font-size:0.81em;">Nome Responsavel:</span>
                                        <input type="text" id="nome_responsavel" class="form-control  form-control-sm" value="{{$dependentes->nome ?? ''}}" readonly>
                                    </div>
                    
                                    <div style="flex-basis:48%;">
                                        <span class="text-white" style="font-size:0.81em;">CPF Responsavel:</span>
                                        <input type="text" id="cpf_responsavel" class="form-control  form-control-sm" value="{{$dependentes->cpf ?? ''}}" readonly>
                                    </div>
                                </div>
                            
                    
                                            













        </main>

        <main style="flex-basis:30%;background-color:#123449;color:#FFF;margin:0% 1%;">
        <h5>Corretor</h5>
        <table class="table table-sm h-50" style="margin:0;padding:0;">
                <thead>
                    <tr>
                        <th style="font-size:0.875em;">Parcela</th>
                        
                        <th style="font-size:0.875em;">Vencimento</th>
                        <th style="font-size:0.875em;">Valor</th>
                        <th style="font-size:0.875em;">Baixa</th>
                        
                        <th style="font-size:0.875em;text-align:right;">
                            <span style="margin-right:12px;">
                                Comissão
                            </span>
                        </th>
                        
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
                                @switch($cr->parcela)
                                    @case(1)
                                        Pag. Adesão
                                    @break;

                                    @case(2)
                                        Pag. Vigência 
                                    @break;

                                    @case(3)
                                        Pag. 2º Parcela
                                    @break;

                                    @case(4)
                                        Pag. 3º Parcela
                                    @break;

                                    @case(5)
                                        Pag. 4º Parcela
                                    @break;

                                    @case(6)
                                        Pag. 5º Parcela
                                    @break;

                                    @case(7)
                                        Pag. 6º Parcela
                                    @break;
                                @endswitch




                               
                            </td>
                            
                            <td style="font-size:0.875em;">{{date('d/m/Y',strtotime($cr->data))}}</td>
                            <td style="font-size:0.875em;">
                                
                                @if($cr->parcela == 1) 
                                    <span style="margin-left:10px;">{{number_format($dados->valor_plano ,2,",",".")}}</span>
                                @else
                                    <span style="margin-left:10px;">{{number_format($dados->valor_plano,2,",",".")}}</span>
                                @endif
                                


                            </td>
                            <td style="font-size:0.875em;">
                                @if(empty($cr->data_baixa) && $cr->cancelados == 1)
                                    <span style="margin-left:20px;color:red;">Cancelado</span>
                                @elseif(empty($cr->data_baixa)) 
                                    <span style="margin-left:20px;">---</span>
                                @else
                                    <span style="margin-left:20px;">{{date('d/m/Y',strtotime($cr->data_baixa))}}</span>
                                @endif
                                     
                            
                            <td style="font-size:0.875em;" align="right">
                                @if($cr->valor > 0)
                                    <span style="margin-right:15px;">{{number_format($cr->valor,2,',','.')}}</span>
                                @else 
                                    <span style="margin-right:28px;">---</span>
                                @endif
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
                
            </table>








        </main>
        
        <main style="flex-basis:30%;background-color:#123449;color:#FFF;margin:0% 1%;">
        <h5>Corretora</h5>
        <table class="table table-sm h-50" style="margin:0;padding:0;">
                <thead>
                    <tr>
                        <th style="font-size:0.875em;">Parcela</th>
                        
                        <th style="font-size:0.875em;">Vencimento</th>
                        <th style="font-size:0.875em;">Valor</th>
                        <th style="font-size:0.875em;">Baixa</th>
                        
                        <th style="font-size:0.875em;text-align:right;">
                            <span style="margin-right:12px;">
                                Comissão
                            </span>
                        </th>
                        
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach($dados->comissao->comissoesLancadasCorretora as $kkc => $crc)
                            
                        <tr>
                            <td class="" style="font-size:0.875em;">
                                @switch($crc->parcela)
                                    @case(1)
                                        Pag. Adesão
                                    @break;

                                    @case(2)
                                        Pag. Vigência 
                                    @break;

                                    @case(3)
                                        Pag. 2º Parcela
                                    @break;

                                    @case(4)
                                        Pag. 3º Parcela
                                    @break;

                                    @case(5)
                                        Pag. 4º Parcela
                                    @break;

                                    @case(6)
                                        Pag. 5º Parcela
                                    @break;

                                    @case(7)
                                        Pag. 6º Parcela
                                    @break;
                                @endswitch




                               
                            </td>
                            
                            <td style="font-size:0.875em;">{{date('d/m/Y',strtotime($crc->data))}}</td>
                            <td style="font-size:0.875em;">
                                
                                @if($crc->parcela == 1) 
                                    <span style="margin-left:10px;">{{number_format($dados->valor_plano ,2,",",".")}}</span>
                                @else
                                    <span style="margin-left:10px;">{{number_format($dados->valor_plano,2,",",".")}}</span>
                                @endif
                                


                            </td>
                            <td style="font-size:0.875em;">
                                @if(empty($crc->data_baixa) && $cr->cancelados == 1)
                                    <span style="margin-left:20px;color:red;">Cancelado</span>
                                @elseif(empty($crc->data_baixa)) 
                                    <span style="margin-left:20px;">---</span>
                                @else
                                    <span style="margin-left:20px;">{{date('d/m/Y',strtotime($crc->data_baixa))}}</span>
                                @endif
                                     
                            
                            <td style="font-size:0.875em;" align="right">
                                @if($crc->valor > 0)
                                    <span style="margin-right:15px;">{{number_format($crc->valor,2,',','.')}}</span>
                                @else 
                                    <span style="margin-right:28px;">---</span>
                                @endif
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
                
            </table>
        </main>




    </section>
    









    

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

            
        });
    </script>
@stop