@extends('adminlte::page')
@section('title', 'Gerente')

@section('plugins.Datatables', true)

@section('content_top_nav_right')
   
    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt text-white"></i>
    </a>
    
@stop

@section('content_header')
    
@stop

@section('content')

    <div class="modal fade" id="dataBaixaModal" tabindex="-1" role="dialog" aria-labelledby="dataBaixaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dataBaixaModalLabel">Data Da Baixa?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" name="data_da_baixa" id="data_da_baixa" method="POST">
                    <input type="date" name="data_baixa" id="data_baixa" class="form-control form-control-sm">
                    <input type="hidden" name="id" id="id">  
                    <input type="hidden" name="corretora" id="corretora">             
                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
            </form>
            </div>
        </div>
    </div>

    

    
    <section class="conteudo_abas" style="padding:5px;">

    <ul class="list_abas" style="margin-top:6px;">    
        <li data-id="aba_contratos" class="ativo">Contratos</li>
        <li data-id="aba_baixas" class="menu-inativo" style="margin:0 1%;">Baixas</li>
        <li data-id="aba_comissao" class="menu-inativo">Comissão</li>
    </ul>


        <!--------------------------------------Contratos------------------------------------------>
        <main id="aba_contratos">

        

        <div class="d-flex" style="margin:7px 0;">
            <select class="form-control mudar_valores" style="flex-basis:15%;" id="selecionar_ano">
                <option value="todos" class="text-center">--Escolher Ano--</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <select class="form-control mudar_valores" style="flex-basis:15%;margin:0 1%;" id="selecionar_mes">
                <option value="todos" class="text-center">--Escolher Mês--</option>
                <option value="01">Janeiro</option>
                <option value="02">Fevereiro</option>
                <option value="03">Março</option>
                <option value="04">Abril</option>
                <option value="05">Maio</option>
                <option value="06">Junho</option>
                <option value="07">Julho</option>
                <option value="08">Agosto</option>
                <option value="09">Setembro</option>
                <option value="10">Outubro</option>
                <option value="11">Novembro</option>
                <option value="12">Dezembro</option>
            </select>
    
            <select class="form-control mudar_valores" style="flex-basis:15%;" id="selecionar_corretor">
                <option value="todos" class="text-center">--Escolher Corretor--</option>
                @foreach($users as $u)
                    <option value="{{$u->id}}">{{$u->name}}</option>
                @endforeach
            </select>




                    
        </div>





            
            <section class="d-flex" style="justify-content: space-between;">

                

                <div style="flex-basis:24%;">

                    <h5 style="margin:0;color:#FFF;text-align: center;background-color: #28a745;border-top-left-radius: 5px;border-top-right-radius: 5px;padding:4px 0;">Contratos</h5>

                    <a href="{{route('gerente.contrato.show.detalhes.todos.visualizar',[1])}}" class="d-flex" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;background-color:#28a745;color:#FFF">

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="quantidade_geral">{{$quantidade_geral}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                                <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Total</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" style="font-weight:bold;" id="valor_geral">R$ {{number_format($total_valor_geral,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="quantidade_geral_vidas">{{$quantidade_vidas_geral}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>

                    </a>

                   
                    <div class="d-flex" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;background-color:#28a745;color:#FFF;">
                        
                    
                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="quantidade_geral_recebidas">{{$total_quantidade_recebidos}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                                <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Recebidos</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" style="font-weight:bold;" id="total_geral_recebidos_valor">R$ {{number_format($total_valor_recebidos,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="quantidade_vidas_recebidas_geral">{{$quantidade_vidas_recebidas}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>
                        
                    </div>

                    <div class="d-flex" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;background-color:#28a745;color:#FFF;">
                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                            <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="total_quantidade_a_receber_geral">{{$total_quantidade_a_receber}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">A Receber</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="total_valor_a_receber_geral">R$ {{$total_valor_a_receber}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="quantidade_vidas_a_receber_geral">{{$quantidade_vidas_a_receber}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>
                    </div>

                    <div class="d-flex" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;background-color:#28a745;color:#FFF;">
                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="quantidade_atrasado_geral">{{$qtd_atrasado}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Atrasados</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="quantidade_atrasado_valor_geral">R$ {{number_format($qtd_atrasado_valor,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="qtd_atrasado_quantidade_vidas_geral">{{$qtd_atrasado_quantidade_vidas}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>
                    </div>

                    <div class="d-flex" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;background-color:#28a745;color:#FFF;">
                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="quantidade_geral_cancelado">{{$qtd_cancelado}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Cancelado</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="quantidade_geral_cancelado_valor">R$ {{number_format($quantidade_valor_cancelado,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="quantidade_cancelado_vidas_geral">{{$qtd_cancelado_quantidade_vidas}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>
                    </div>

                    <div class="d-flex" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;background-color:#28a745;color:#FFF;">
                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="quantidade_finalizado_geral">{{$qtd_finalizado}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Finalizados</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="quantidade_geral_finalizado">R$ {{$quantidade_valor_finalizado}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="quantidade_finalizado_quantidade_vidas_geral">{{$qtd_finalizado_quantidade_vidas}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>
                    </div>

                </div>


                <div style="flex-basis:24%;">

                <h5 class="bg-info" style="margin:0;padding:0;color:#FFF;text-align: center;border-top-left-radius: 5px;border-top-right-radius: 5px;padding:4px 0;">Individual</h5>

                    <a href="{{route('gerente.contrato.ver.detalhe.card',[1,1])}}" class="d-flex bg-info link_individual_um" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;">
                        
                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="quantidade_individual_geral">{{$quantidade_individual_geral}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Total</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="total_valor_geral_individual">R$ {{number_format($total_valor_geral_individual,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="quantidade_vidas_geral_individual">{{$quantidade_vidas_geral_individual}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>

                    </a>

                    <a href="{{route('gerente.contrato.ver.detalhe.card',[1,2])}}" class="d-flex bg-info link_individual_dois" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;">
                        

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="total_quantidade_recebidos_individual">{{$total_quantidade_recebidos_individual}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                                <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Recebidos</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="total_valor_recebidos_individual">R$ {{number_format($total_valor_recebidos_individual,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="quantidade_vidas_recebidas_individual">{{$quantidade_vidas_recebidas_individual}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>

                    </a>

                    <a href="{{route('gerente.contrato.ver.detalhe.card',[1,3])}}" class="d-flex bg-info link_individual_tres" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;">
                        

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="total_quantidade_a_receber_individual">{{$total_quantidade_a_receber_individual}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">A Receber</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="total_valor_a_receber_individual">R$ {{$total_valor_a_receber_individual}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="quantidade_vidas_a_receber_individual">{{$quantidade_vidas_a_receber_individual}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>

                    </a>

                    <a href="{{route('gerente.contrato.ver.detalhe.card',[1,4])}}" class="d-flex bg-info link_individual_quatro" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;">
                        

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="qtd_atrasado_individual">{{$qtd_atrasado_individual}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Atrasados</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="qtd_atrasado_valor_individual">R$ {{number_format($qtd_atrasado_valor_individual,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="qtd_atrasado_quantidade_vidas_individual">{{$qtd_atrasado_quantidade_vidas_individual}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>

                    </a>

                    <a href="{{route('gerente.contrato.ver.detalhe.card',[1,5])}}" class="d-flex bg-info link_individual_cinco" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;">
                        

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="qtd_cancelado_individual">{{$qtd_cancelado_individual}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Cancelado</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="quantidade_valor_cancelado_individual">R$ {{number_format($quantidade_valor_cancelado_individual,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="qtd_cancelado_quantidade_vidas_individual">{{$qtd_cancelado_quantidade_vidas_individual}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>

                    </a>

                    <a href="{{route('gerente.contrato.ver.detalhe.card',[1,6])}}" class="d-flex bg-info link_individual_seis" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;">
                        

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="qtd_finalizado_individual">{{$qtd_finalizado_individual}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Finalizados</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="quantidade_valor_finalizado_individual">R$ {{number_format($quantidade_valor_finalizado_individual,2,",",".")}}</span>
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="qtd_finalizado_quantidade_vidas_individual">{{$qtd_finalizado_quantidade_vidas_individual}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>

                    </a>

                </div>


                <div style="flex-basis:24%;">

                <h5 style="margin:0;padding:0;color:#FFF;text-align: center;background-color: #dc3545;border-top-left-radius: 5px;border-top-right-radius: 5px;padding:4px 0;">Coletivo</h5>

                    <a href="{{route('gerente.contrato.ver.detalhe.card',[2,1])}}" class="d-flex link_coletivo_um" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;background-color:#dc3545;color:#FFF;border-radius:5px;">
                        
                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="quantidade_coletivo_geral">{{$quantidade_coletivo_geral}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Total</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="total_valor_geral_coletivo">R$ {{number_format($total_valor_geral_coletivo,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="quantidade_vidas_geral_coletivo">{{$quantidade_vidas_geral_coletivo}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>





                    </a>

                    <a href="{{route('gerente.contrato.ver.detalhe.card',[2,2])}}" class="d-flex link_coletivo_dois" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;background-color:#dc3545;color:#FFF;border-radius:5px;">
                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="total_quantidade_recebidos_coletivo">{{$total_quantidade_recebidos_coletivo}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Recebidos</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="total_valor_recebidos_coletivo">R$ {{number_format($total_valor_recebidos_coletivo,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="quantidade_vidas_recebidas_coletivo">{{$quantidade_vidas_recebidas_coletivo}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>
                    </a>

                    <a href="{{route('gerente.contrato.ver.detalhe.card',[2,3])}}" class="d-flex link_coletivo_tres" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;background-color:#dc3545;color:#FFF;border-radius:5px;">
                        
                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="total_quantidade_a_receber_coletivo">{{$total_quantidade_a_receber_coletivo}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">A receber</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="total_valor_a_receber_coletivo">R$ {{number_format($total_valor_a_receber_coletivo,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="quantidade_vidas_a_receber_coletivo">{{$quantidade_vidas_a_receber_coletivo}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>

                    </a>

                    <a href="{{route('gerente.contrato.ver.detalhe.card',[2,4])}}" class="d-flex link_coletivo_quatro" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;background-color:#dc3545;color:#FFF;border-radius:5px;">
                        
                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="qtd_atrasado_coletivo">{{$qtd_atrasado_coletivo}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Atrasados</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="qtd_atrasado_valor_coletivo">R$ {{number_format($qtd_atrasado_valor_coletivo,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="qtd_atrasado_quantidade_vidas_coletivo">{{$qtd_atrasado_quantidade_vidas_coletivo}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>




                    </a>

                    <a href="{{route('gerente.contrato.ver.detalhe.card',[2,5])}}" class="d-flex link_coletivo_cinco" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;background-color:#dc3545;color:#FFF;border-radius:5px;">
                        
                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="qtd_cancelado_coletivo">{{$qtd_cancelado_coletivo}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Cancelado</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="quantidade_valor_cancelado_coletivo">R$ {{number_format($quantidade_valor_cancelado_coletivo,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="qtd_cancelado_quantidade_vidas_coletivo">{{$qtd_cancelado_quantidade_vidas_coletivo}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>

                    </a>

                    <a href="{{route('gerente.contrato.ver.detalhe.card',[2,6])}}" class="d-flex link_coletivo_seis" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;background-color:#dc3545;color:#FFF;border-radius:5px;">
                        

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="qtd_finalizado_coletivo">{{$qtd_finalizado_coletivo}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Finalizados</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="quantidade_valor_finalizado_coletivo">R$ {{number_format($quantidade_valor_finalizado_coletivo,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="qtd_finalizado_quantidade_vidas_coletivo">{{$qtd_finalizado_quantidade_vidas_coletivo}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>

                    </a>

                </div>


                <div style="flex-basis:24%;">

                <h5 style="margin:0;padding:0;color:#FFF;text-align: center;background-color: #FF8C00;border-top-left-radius: 5px;border-top-right-radius: 5px;padding:4px 0;">Empresarial</h5>

                    <a href="{{route('gerente.contrato.ver.detalhe.card',[3,1])}}" class="d-flex link_empresarial_um" style="background-color:#FF8C00;color:#FFF;flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;">
                        
                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="quantidade_com_empresaria_geral">{{$quantidade_empresarial_geral}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Total</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="total_com_empresa_valor_geral">R$ {{number_format($total_valor_geral_empresarial,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="quantidade_com_empresa_vidas_geral">{{$quantidade_vidas_geral_empresarial}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>

                    </a>

                    <a href="{{route('gerente.contrato.ver.detalhe.card',[3,2])}}" class="d-flex link_empresarial_dois" style="background-color:#FF8C00;color:#FFF;flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;">
                        
                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="quantidade_recebidas_empresarial">{{$total_quantidade_recebidos_empresarial}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Recebidos</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="total_valor_recebidos_empresarial">R$ {{number_format($total_valor_recebidos_empresarial,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="quantidade_vidas_recebidas_empresarial">{{$quantidade_vidas_recebidas_empresarial}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>







                    </a>

                    <a href="{{route('gerente.contrato.ver.detalhe.card',[3,3])}}" class="d-flex link_empresarial_tres" style="background-color:#FF8C00;color:#FFF;flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;">
                        
                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="total_quantidade_a_receber_empresarial">{{$total_quantidade_a_receber_empresarial}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">A Receber</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="total_valor_a_receber_empresarial">R$ {{number_format($total_valor_a_receber_empresarial,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="quantidade_vidas_a_receber_empresarial">{{$quantidade_vidas_a_receber_empresarial}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>

                    </a>

                    <a href="{{route('gerente.contrato.ver.detalhe.card',[3,4])}}" class="d-flex link_empresarial_quatro" style="background-color:#FF8C00;color:#FFF;flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;">
                       
                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="qtd_atrasado_empresarial">{{$qtd_atrasado_empresarial}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Atrasados</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="qtd_atrasado_valor_empresarial">R$ {{number_format($qtd_atrasado_valor_empresarial,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="qtd_atrasado_quantidade_vidas_empresarial">{{$qtd_atrasado_quantidade_vidas_empresarial}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>


                    </a>

                    <a href="{{route('gerente.contrato.ver.detalhe.card',[3,5])}}" class="d-flex link_empresarial_cinco" style="background-color:#FF8C00;color:#FFF;flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;">
                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="qtd_cancelado_empresarial">{{$qtd_cancelado_empresarial}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Cancelado</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="quantidade_valor_cancelado_empresarial">R$ {{number_format($quantidade_valor_cancelado_empresarial,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="qtd_cancelado_quantidade_vidas_empresarial">{{$qtd_cancelado_quantidade_vidas_empresarial}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>
                    </a>

                    <a href="{{route('gerente.contrato.ver.detalhe.card',[3,6])}}" class="d-flex link_empresarial_seis" style="background-color:#FF8C00;color:#FFF;flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;">
                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;" class="ml-1">
                                <span style="font-size:1.6em;font-weight:bold;padding-left:10px;" id="qtd_finalizado_empresarial">{{$qtd_finalizado_empresarial}}</span>
                                <span style="padding-left:10px;"></span>
                            </span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;display:flex;justify-content:flex-end;font-size:1.8em;padding-right:10px;font-weight:bold;" class="mr-1">
                            <i class="fas fa-users fa-xs" style="font-size:0.7em;margin-top:5px;margin-right:5px;"></i>
                            </span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1;padding:5px 0;">
                            <span style="flex-basis:33%;padding-left:13px;">Finalizado</span>
                            <span style="flex-basis:34%;display:flex;justify-content: center;flex-direction: column;justify-content: center;">
                                <span class="text-center" id="quantidade_valor_finalizado_empresarial">R$ {{number_format($quantidade_valor_finalizado_empresarial,2,",",".")}}</span>
                                
                            </span>
                            <span style="flex-basis:33%;text-align: right;padding-right:16px;">Vidas</span>
                        </div>

                        <div style="display:flex;flex-basis:100%;line-height:1.2;">
                            <span style="flex-basis:33%;font-weight:bold;padding-left:10px;display:block;" class="ml-1"></span>
                            <span style="flex-basis:34%;"></span>
                            <span style="flex-basis:33%;justify-content: flex-end;display:flex;flex-direction: column;justify-content: flex-end;text-align: right;" class="mr-1"> 
                                <span style="padding-right:10px;font-weight:bolder;font-size:1.6em;margin:0px;" id="qtd_finalizado_quantidade_vidas_empresarial">{{$qtd_finalizado_quantidade_vidas_empresarial}}</span>
                                <span style="padding-right:10px;"></span>
                            </span>
                        </div>
                    </a>

                </div>

            </section>

        </main>    
        <!--------------------------------------Fim Contratos------------------------------------------>


        <!--------------------------------------Baixas------------------------------------------>
        <main id="aba_baixas" class="ocultar">
            <section class="d-flex justify-content-between" style="flex-wrap: wrap;align-content: flex-start;">
                
                <!--COLUNA DA ESQUERDA-->
                <div class="d-flex flex-column text-white ml-1" style="flex-basis:16%;border-radius:5px;">                    

                    <div class="mb-1">
                        <span class="btn btn-block" style="background-color:#123449;color:#FFF;font-size:1.2em;">Baixas</span>
                    </div>

                    <div style="background-color:#123449;padding:5px 0;border-radius:5px;margin-bottom:5px;" id="listar_gerenciavel" class="listar_gerenciavel">   

                        <ul style="margin:0;padding:0;list-style:none">
                            <li style="padding:10px;display:flex;justify-content:space-between;" id="container_a_recebido">
                                <span style="flex-basis:35%;font-size:0.875em;">A Receber</span>
                                <span style="flex-basis:65%;background-color:#FFF;color:#000;border-radius:3px;display:flex;justify-content:space-between;">
                                    
                                    <span class="ml-2" id="quat_comissao_a_receber" style="font-size:0.875em;">
                                        {{$quat_comissao_a_receber}}
                                    </span>
                                    <span class="mr-2 valor_quat_comissao_a_receber" style="font-size:0.875em;">
                                        R$ {{number_format($valor_quat_comissao_a_receber,2,",",".")}}
                                    </span>    

                                </span>                        
                            </li>
                        </ul>        

                        <div style="padding:0 5px;">
                            <select id="select_administradora" class="form-control">
                                <option value="todos" class="text-center">-Escolher Administradora-</option>
                            </select>
                        </div>

                        <div style="padding:0 5px;margin:5px 0;">
                            <select id="select_vendedor" class="form-control">
                                <option value="todos" class="text-center">-Escolher Corretor-</option>
                            </select>
                        </div>

                        <div style="padding:0 5px;">
                            <select id="select_plano" class="form-control">
                                <option value="todos" class="text-center">-Escolher Plano-</option>
                            </select>
                        </div>

                    </div>


                    
                </div>
                <!--Fim Coluna da Esquerda  -->
                <!--COLUNA DA CENTRAL-->
                <div style="flex-basis:83%;" class="tabela_a_receber_container">
                    <div class="p-2" style="background-color:#123449;color:#FFF;border-radius:5px;">
                    <table id="tabela_coletivo" class="table table-sm listardados">
                        <thead>
                            <tr>
                                <th>Admin</th>
                                <th>Corretor</th>
                                <th>Plano</th>
                                <th>Origem</th>
                                <th>Cliente</th>
                                <th>Cod. Externo</th>
                                <th>Valor</th>
                                <th>Vencimento</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>   
                    </div>
                </div>  
                <!--FIM COLUNA DA CENTRAL-->

                

                
            </section>
        </main>    
        <!--------------------------------------Fim Baixas------------------------------------------>
        
       
       <main id="aba_comissao" class="ocultar aba_comissao_container">
            <div class="menu_aba_comissao">

                <div class="my-2 mr-2 ml-2">
                    <select name="mes_search" id="mes_search" class="form-control">
                        <option value="" class="text-center">--Escolher o Mês--</option>
                    </select>
                </div>
                <h4 class="text-center text-white my-2">Total Folha</h4>
                <p class="total_mes_comissao">R$ {{number_format($total_mes_comissao,2,",",".")}}</p>   
                <div class="list_administradoras border-bottom border-top py-2">
                    <ul style="margin:0;padding:0;list-style:none;" id="listar_individual">
                        @foreach($administradoras_mes as $ad)
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="aguardando_pagamento_3_parcela_individual" class="individual">
                                <span>{{$ad->administradora}}</span>
                                <span class="badge badge-light individual_quantidade_3_parcela" style="width:45px;text-align:right;">{{number_format($ad->total,2,",",".")}}</span>                        
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="d-flex justify-content-center my-3 mr-2 ml-2">
                    <button class="btn btn-info btn-block">Gerar PDF</button>
                </div>


            </div>

            <div class="p-2 aba_comissao_table" style="background-color:#123449;color:#FFF;border-radius:5px;">
                <table id="tabela_coletivo_comissao" class="table table-sm listarcomissao">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Allcare</th>
                            <th>Alter</th>
                            <th>Qualicorp</th>
                            <th>Hapvida</th>
                            <th>Total</th>
                            <th>Detalhe</th>
                            
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>   
            </div>

       </main>  

       
    </section>
   
@stop  








@section('js')

    <script>
        $(function(){  
            const date = new Date();

            function adicionarZero(valor) {
                if(valor.length == 2) return valor;
                return "0"+valor;
            }




            
            $(".mudar_valores").on('change',function(){
                let campo_select = $(this).attr('id');
                let campo_ano = $("#selecionar_ano").val();
                let campo_mes = $("#selecionar_mes").val();
                let campo_cor = $("#selecionar_corretor").val();

                
                let link_ano = campo_ano == "todos" ? "all" : campo_ano; 
                let link_mes = campo_mes == "todos" ? "all" : campo_mes;
                let link_cor = campo_cor == "todos" ? "all" : campo_cor;

                $('.link_individual_um').attr("href",`/admin/gerente/ver/1/1/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_individual_dois').attr("href",`/admin/gerente/ver/1/2/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_individual_tres').attr("href",`/admin/gerente/ver/1/3/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_individual_quatro').attr("href",`/admin/gerente/ver/1/4/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_individual_cinco').attr("href",`/admin/gerente/ver/1/5/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_individual_seis').attr("href",`/admin/gerente/ver/1/6/${link_ano}/${link_mes}/${link_cor}`);

                $('.link_coletivo_um').attr("href",`/admin/gerente/ver/2/1/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_coletivo_dois').attr("href",`/admin/gerente/ver/2/2/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_coletivo_tres').attr("href",`/admin/gerente/ver/2/3/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_coletivo_quatro').attr("href",`/admin/gerente/ver/2/4/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_coletivo_cinco').attr("href",`/admin/gerente/ver/2/5/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_coletivo_seis').attr("href",`/admin/gerente/ver/2/6/${link_ano}/${link_mes}/${link_cor}`);

                $('.link_empresarial_um').attr("href",`/admin/gerente/ver/3/1/${link_ano}/${link_mes}/${link_cor}`)
                $('.link_empresarial_dois').attr("href",`/admin/gerente/ver/3/2/${link_ano}/${link_mes}/${link_cor}`)
                $('.link_empresarial_tres').attr("href",`/admin/gerente/ver/3/3/${link_ano}/${link_mes}/${link_cor}`)
                $('.link_empresarial_quatro').attr("href",`/admin/gerente/ver/3/4/${link_ano}/${link_mes}/${link_cor}`)
                $('.link_empresarial_cinco').attr("href",`/admin/gerente/ver/3/5/${link_ano}/${link_mes}/${link_cor}`)
                $('.link_empresarial_seis').attr("href",`/admin/gerente/ver/3/6/${link_ano}/${link_mes}/${link_cor}`)

                $.ajax({
                    url:"{{route('gerente.todos.valores.usuario')}}",
                    method:"POST",
                    data:"campo_ano="+campo_ano+"&campo_mes="+campo_mes+"&campo_cor="+campo_cor,
                    success:function(res) {
                        //console.log(res);
                        $("#quantidade_geral").html(res.quantidade_geral);
                        $("#valor_geral").html(res.total_valor_geral);
                        $("#quantidade_geral_vidas").html(res.quantidade_geral_vidas);
                        $("#quantidade_geral_recebidas").html(res.total_geral_recebidas)
                        $("#total_geral_recebidos_valor").html(res.total_geral_recebidos_valor)
                        $("#quantidade_vidas_recebidas_geral").html(res.quantidade_vidas_recebidas_geral);
                        $("#total_quantidade_a_receber_geral").html(res.total_quantidade_a_receber_geral);
                        $("#total_valor_a_receber_geral").html(res.total_valor_a_receber_geral);
                        $("#quantidade_vidas_a_receber_geral").html(res.quantidade_vidas_a_receber_geral);
                        $("#quantidade_atrasado_geral").html(res.quantidade_atrasado_geral);
                        $("#quantidade_atrasado_valor_geral").html(res.quantidade_atrasado_valor_geral);
                        $("#qtd_atrasado_quantidade_vidas_geral").html(res.qtd_atrasado_quantidade_vidas_geral);
                        $("#quantidade_cancelado_vidas_geral").html(res.quantidade_cancelado_vidas_geral);
                        $("#quantidade_geral_cancelado").html(res.quantidade_geral_cancelado);
                        $("#quantidade_geral_cancelado_valor").html(res.quantidade_geral_cancelado_valor);
                        $("#quantidade_finalizado_geral").html(res.quantidade_finalizado_geral);
                        $("#quantidade_finalizado_quantidade_vidas_geral").html(res.quantidade_finalizado_quantidade_vidas_geral);
                        $("#quantidade_geral_finalizado").html(res.quantidade_geral_finalizado);

                        //////////INDIVIDUAL  

                        $("#quantidade_individual_geral").html(res.quantidade_individual_geral);
                        $("#total_valor_geral_individual").html(res.total_valor_geral_individual);
                        $("#quantidade_vidas_geral_individual").html(res.quantidade_vidas_geral_individual);

                        $("#total_quantidade_recebidos_individual").html(res.total_quantidade_recebidos_individual);
                        $("#total_valor_recebidos_individual").html(res.total_valor_recebidos_individual);
                        $("#quantidade_vidas_recebidas_individual").html(res.quantidade_vidas_recebidas_individual);

                        $("#total_quantidade_a_receber_individual").html(res.total_quantidade_a_receber_individual);
                        $("#total_valor_a_receber_individual").html(res.total_valor_a_receber_individual);
                        $("#quantidade_vidas_a_receber_individual").html(res.quantidade_vidas_a_receber_individual);

                        $("#qtd_atrasado_individual").html(res.qtd_atrasado_individual);
                        $("#qtd_atrasado_valor_individual").html(res.qtd_atrasado_valor_individual);
                        $("#qtd_atrasado_quantidade_vidas_individual").html(res.qtd_atrasado_quantidade_vidas_individual);

                        $("#qtd_finalizado_individual").html(res.qtd_finalizado_individual); 
                        $("#quantidade_valor_finalizado_individual").html(res.quantidade_valor_finalizado_individual);
                        $("#qtd_finalizado_quantidade_vidas_individual").html(res.qtd_finalizado_quantidade_vidas_individual);

                        $("#qtd_cancelado_individual").html(res.qtd_cancelado_individual);
                        $("#quantidade_valor_cancelado_individual").html(res.quantidade_valor_cancelado_individual);
                        $("#qtd_cancelado_quantidade_vidas_individual").html(res.qtd_cancelado_quantidade_vidas_individual); 

                        //////////COLETIVO
                        $('#quantidade_coletivo_geral').html(res.quantidade_coletivo_geral);

                        $('#total_valor_geral_coletivo').html(res.total_valor_geral_coletivo);

                        $('#quantidade_vidas_geral_coletivo').html(res.quantidade_vidas_geral_coletivo);

                        $("#total_quantidade_recebidos_coletivo").html(res.total_quantidade_recebidos_coletivo);
                        $('#total_valor_recebidos_coletivo').html(res.total_valor_recebidos_coletivo);
                        $('#quantidade_vidas_recebidas_coletivo').html(res.quantidade_vidas_recebidas_coletivo);
                        $('#total_quantidade_a_receber_coletivo').html(res.total_quantidade_a_receber_coletivo);
                        $('#total_valor_a_receber_coletivo').html(res.total_valor_a_receber_coletivo);
                        $('#quantidade_vidas_a_receber_coletivo').html(res.quantidade_vidas_a_receber_coletivo);

                        $('#qtd_atrasado_coletivo').html(res.qtd_atrasado_coletivo);
                        $('#qtd_atrasado_valor_coletivo').html(res.qtd_atrasado_valor_coletivo); 
                        $('#qtd_atrasado_quantidade_vidas_coletivo').html(res.qtd_atrasado_quantidade_vidas_coletivo);
                        
                        $('#qtd_finalizado_coletivo').html(res.qtd_finalizado_coletivo);
                        $('#quantidade_valor_finalizado_coletivo').html(res.quantidade_valor_finalizado_coletivo);
                        $('#qtd_finalizado_quantidade_vidas_coletivo').html(res.qtd_finalizado_quantidade_vidas_coletivo);

                        $('#qtd_cancelado_coletivo').html(res.qtd_cancelado_coletivo);
                        $('#quantidade_valor_cancelado_coletivo').html(res.quantidade_valor_cancelado_coletivo);
                        $('#qtd_cancelado_quantidade_vidas_coletivo').html(res.qtd_cancelado_quantidade_vidas_coletivo); 

                        ////////////Empresarial
                        $("#quantidade_com_empresaria_geral").html(res.quantidade_com_empresaria_geral);
                        $("#total_com_empresa_valor_geral").html(res.total_com_empresa_valor_geral);
                        $("#quantidade_com_empresa_vidas_geral").html(res.quantidade_com_empresa_vidas_geral);
                        $("#quantidade_recebidas_empresarial").html(res.quantidade_recebidas_empresarial);
                        $("#total_valor_recebidos_empresarial").html(res.total_valor_recebidos_empresarial);
                        $("#quantidade_vidas_recebidas_empresarial").html(res.quantidade_vidas_recebidas_empresarial);
                        $("#total_quantidade_a_receber_empresarial").html(res.total_quantidade_a_receber_empresarial);
                        $("#total_valor_a_receber_empresarial").html(res.total_valor_a_receber_empresarial);
                        $("#quantidade_vidas_a_receber_empresarial").html(res.quantidade_vidas_a_receber_empresarial);
                        $("#qtd_atrasado_empresarial").html(res.qtd_atrasado_empresarial);
                        $("#qtd_atrasado_valor_empresarial").html(res.qtd_atrasado_valor_empresarial); 
                        $("#qtd_atrasado_quantidade_vidas_empresarial").html(res.qtd_atrasado_quantidade_vidas_empresarial);
                        $("#qtd_finalizado_empresarial").html(res.qtd_finalizado_empresarial);
                        $("#quantidade_valor_finalizado_empresarial").html(res.quantidade_valor_finalizado_empresarial);
                        $("#qtd_finalizado_quantidade_vidas_empresarial").html(res.qtd_finalizado_quantidade_vidas_empresarial);
                        $("#qtd_cancelado_empresarial").html(res.qtd_cancelado_empresarial);
                        $("#quantidade_valor_cancelado_empresarial").html(res.quantidade_valor_cancelado_empresarial),
                        $("#qtd_cancelado_quantidade_vidas_empresarial").html(res.qtd_cancelado_quantidade_vidas_empresarial);


                    }

                });    


            });




            $(".list_abas li").on('click',function(){                
                $('li').removeClass('ativo').addClass('menu-inativo');
                $(this).addClass("ativo").removeClass('menu');
                let id = $(this).attr('data-id');
                $("#janela_atual").val(id);
                $('.conteudo_abas main').addClass('ocultar');
                $('#'+id).removeClass('ocultar');
            });
                       
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });     
            
            var listarcontratos = $(".listarcontratos").DataTable({

                dom: '<"d-flex justify-content-between"<"#title_listar_contratos"><"estilizar_search"f>><t><"d-flex justify-content-between align-items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":"{{ route('gerente.listarcontratos.geral') }}",
                    "dataSrc": ""
                },
                "lengthMenu": [50,100,150,200,300,500],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                columns: [
                    {data:"administradora",name:"administradora"},
                    {data:"corretor",name:"corretor","width":"10%"},
                    {data:"cliente",name:"cliente"},  
                    {data:"codigo_externo",name:"codigo_externo"}, 
                    {data:"plano",name:"plano"},
                    {data:"valor",name:"valor_plano",render: $.fn.dataTable.render.number('.', ',', 2, '')},
                    {data:"data_contrato",name:"data_boleto",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            
                            let alvo = cellData.split(" ")[0].split("-").reverse().join("/");
                            $(td).html(alvo);        
                        }
                    },
                    {data:"origem",name:"origem"},
                                    
                   
                    
                    
                    
                    
                    {data:"detalhe",name:"detalhe",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            $(td).css({"text-align":"center"}).html("<a href='/admin/gerente/contrato/"+cellData+"' class='text-white'><i class='fas fa-eye'></i></a>")
                        }
                    },
                   
                ],
                "columnDefs": [],  
                "initComplete": function( settings, json ) {
                    $('#title_listar_contratos').html("<h4>Contratos</h4>");
                    
                },
                footerCallback: function (row, data, start, end, display) {
                    
                }




            });    


            

            /** Tabela de Listar A receber */

            var tableareceber = $(".listardados").DataTable({
                //dom: '<"d-flex justify-content-between"<"#title_coletivo_por_adesao_table">ft><t><"d-flex justify-content-between"lp>',
                dom: '<"d-flex justify-content-between"<"#title_coletivo_por_adesao_table"><"estilizar_search"f>><t><"d-flex justify-content-between align-items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":"{{ route('gerente.listagem.em_geral') }}",
                    "dataSrc": ""
                },
                "lengthMenu": [50,100,150,200,300,500],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                columns: [
                    {data:"administradora",name:"administradora"},
                    {data:"corretor",name:"corretor","width":"10%"},
                    {data:"plano",name:"plano"},
                    {data:"tabela_origens",name:"cidade"},
                    {data:"cliente",name:"cliente"},                   
                    {data:"codigo_externo",name:"codigo_externo"},
                    
                    {data:"valor",name:"valor_plano",render: $.fn.dataTable.render.number('.', ',', 2, '')},
                    {data:"vencimento",name:"data_boleto",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            let alvo = cellData.split("-").reverse().join("/");
                            $(td).html(alvo);        
                        }
                    },
                       
                    {data:"comissao",name:"detalhe",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            $(td).css({"text-align":"center"}).html("<a href='/admin/gerente/detalhe/"+cellData+"' class='text-white'><i class='fas fa-eye'></i></a>")
                        }
                    },
                    
                ],
                "columnDefs": [],  
                "initComplete": function( settings, json ) {
                    $('#title_coletivo_por_adesao_table').html("<h4>Corretora</h4>");
                  
                        this.api()
                       .columns([2])
                       .every(function () {
                            var column = this;
                            var selectPlano = $("#select_plano");
                            selectPlano.on('change',function(){
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                if(val != "todos") {
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();    
                                } else {
                                    var val = "";
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                }
                            });
                            column.data().unique().sort().each(function (d, j) {
                                selectPlano.append('<option value="' + d + '">' + d + '</option>');
                            });
                       })
                       this.api()
                       .columns([0])
                       .every(function () {
                            var column = this;
                            var selectAdministradora = $("#select_administradora");
                           
                            selectAdministradora.on('change',function(){
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                if(val != "todos") {
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();    
                                } else {
                                    var val = "";
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                }
                            });
                             column.data().unique().sort().each(function (d, j) {
                                 selectAdministradora.append('<option value="' + d + '">' + d + '</option>');
                             });
                        })
                       this.api()
                       .columns([1])
                       .every(function () {
                            var column = this;
                            var selectVendedor = $("#select_vendedor");
                            selectVendedor.on('change',function(){
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                if(val != "todos") {
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();    
                                } else {
                                    var val = "";
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                }
                            });
                            column.data().unique().sort().each(function (d, j) {
                                selectVendedor.append('<option value="' + d + '">' + d + '</option>');
                            });
                       })
                },
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    var intVal = function (i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };
                    conditionalTotal = 0;
                    qtdLinha = 0;
                    api.rows( { search: "applied" } ).every( function ( rowIdx, tableLoop, rowLoop ) {
                        var d = this.data();
                        conditionalTotal += intVal(d['valor']);
                        qtdLinha = rowLoop + 1;
                    });
                    $(".valor_quat_comissao_a_receber").html(conditionalTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
                    $("#quat_comissao_a_receber").html(qtdLinha);                  
                }
                
            });  
            
             /** Fim Tabela de Listar A receber */    

             




            var tabela_coletivo = $('#tabela_coletivo').DataTable();
            $('#tabela_coletivo').on('click', 'tbody tr', function () {
                tabela_coletivo.$('tr').removeClass('textoforte');
                $(this).closest('tr').addClass('textoforte');
                let data = tabela_coletivo.row(this).data();
                $("#id").val(data.id);
            });            

            $('body').on('click','.btn_recebido',function(){
                $("#corretora").val($(this).attr('data-corretora'));
                $('#dataBaixaModal').modal('show');
            });


            var listarcomissao = $(".listarcomissao").DataTable({
                dom: '<"d-flex justify-content-between"<"#title_comissao">ft><t><"d-flex justify-content-between"lp>',
                //dom: '<"d-flex"<"row"<"col"B><"col"l><"col"f>>>tr<"container-fluid"<"row"<"col"i> <"col"p>>>',
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":"{{ route('gerente.listagem.comissao') }}",
                    "dataSrc": ""
                },
                "lengthMenu": [50,100,150,200,300,500],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                columns: [
                    {data:"name",name:"name"},
                    {data:"valor_allcare",name:"allcare",
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
                    {data:"valor_alter",name:"alter",
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
                    {data:"valor_qualicorp",name:"qualicorp",
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
                    {data:"valor_hapvida",name:"hapvida",
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
                    {data:"valor",name:"valor",
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                        
                    },
                    {data:"id",name:"detalhe",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            $(td).html(`<a style="margin-left:25px;"  href='/admin/gerente/comissao/${cellData}' class='text-white'>
                                <i class='fas fa-eye'></i>
                            </a>`)       
                        }
                    
                    },
                   
                ],
                "columnDefs": [],              
                "initComplete": function( settings, json ) {
                    $('#title_comissao').html("<h4>Comissão</h4>");
                }
                // footerCallback: function (row, data, start, end, display) {
                    
                    
                //     var api = this.api();
                //     var intVal = function (i) {
                //         return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                //     };
                //     total = api
                //         .column(1)
                //         .data()
                //         .reduce(function (a, b) {
                //             return intVal(a) + intVal(b);
                //         }, 0);

                //         pageTotal = api
                //             .column(1, { page: 'current' })
                //             .data()
                //             .reduce(function (a, b) {
                //             return intVal(a) + intVal(b);
                //         }, 0);    
                //     // console.log(pageTotal);    


                //     $(api.column(1).footer()).html('R$ ' + pageTotal);




                // }
            });
            
            // $("body").on('change','#mudar_status_gerenciavel_individual',function(){
            //     if($(this).is(":checked")) {
            //         let id = $(this).attr('data-id');
            //         $.ajax({
            //             url:"{{route('gerente.mudar_status')}}",
            //             method:"POST",
            //             data:"id="+id,
            //             success:function(res) {
            //                 table.ajax.reload();
            //                 listarcomissao.ajax.reload();
            //             }
            //         })
            //     } 
            // });

            $('form[name="data_da_baixa"]').on('submit',function(){
                let dados = $(this).serialize();
                $.ajax({
                    url:"{{route('gerente.mudar_status')}}",
                    method:"POST",
                    data:dados,
                    success:function(res) {
                        console.log(res);
                        // if(res == "sucesso") {
                        //     tableareceber.ajax.reload();
                        // } else {

                        // }
                        // var select_mes =  $("#select_mes");
                        // select_mes.html('');
                        // select_mes.prepend('<option value="todos" class="text-center">---Escolher Mes---</option>');
                        // select_mes.append(res.datas_select);
                        // tableareceber.ajax.reload();
                        // listarcomissao.ajax.reload();
                        // $('#dataBaixaModal').modal('hide');
                        // $("#quat_comissao_a_receber").text(res.quat_comissao_a_receber);
                        // $("#quat_comissao_recebido").text(res.quat_comissao_recebido);

                        // $(".valor_quat_comissao_a_receber").html("R$ "+res.valor_quat_comissao_a_receber);
                        // $(".valor_quat_comissao_recebido").html("R$ "+res.valor_quat_comissao_recebido);

                        // $("#data_baixa").val('');

                    }
                })
                return false;
            });
               

            $("#listar_gerenciavel").on('click',function(){

                var val = "";


                $(".tabela_a_receber_container ").removeClass('ocultar');
                $(".tabela_a_recebido_container").addClass('ocultar');

                $("#select_mes_recebido option[value='todos']").prop("selected",true);

                $("#select_administradora_recebido option[value='todos']").prop("selected",true);
                $("#select_vendedor_recebido option[value='todos']").prop("selected",true);
                $("#select_plano_recebido option[value='todos']").prop("selected",true);

                tablearecebido.column(9).search(val).draw();
                tablearecebido.column(9).search(val ? '^' + val + '$' : '',true,false).draw();

                tablearecebido.column(0).search(val).draw();
                tablearecebido.column(0).search(val ? '^' + val + '$' : '',true,false).draw();

                tablearecebido.column(1).search(val).draw();
                tablearecebido.column(1).search(val ? '^' + val + '$' : '',true,false).draw();

                tablearecebido.column(2).search(val).draw();
                tablearecebido.column(2).search(val ? '^' + val + '$' : '',true,false).draw();

                $("#listar_recebido").removeClass("destaque");
                $(this).addClass("destaque");
                
            });

            $('body').on('click','#listar_recebido',function(){

                var val = "";

                $(".tabela_a_receber_container ").addClass('ocultar');
                $(".tabela_a_recebido_container").removeClass('ocultar');

                $('#select_administradora option[value="todos"]').prop('selected',true);
                $('#select_vendedor option[value="todos"]').prop('selected',true);
                $('#select_plano option[value="todos"]').prop('selected',true);

                tableareceber.column(0).search(val).draw();
                tableareceber.column(0).search(val ? '^' + val + '$' : '',true,false).draw();

                tableareceber.column(1).search(val).draw();
                tableareceber.column(1).search(val ? '^' + val + '$' : '',true,false).draw();

                tableareceber.column(2).search(val).draw();
                tableareceber.column(2).search(val ? '^' + val + '$' : '',true,false).draw();

                
                

                $("#listar_gerenciavel").removeClass("destaque");
                $(this).addClass("destaque"); 


            });



            

            

            $("#select_mes_recebido").on('change',function(){
                
                if($(this).val() != "todos") {
                    
                    tablearecebido
                    .column(9)
                    .search($(this).val())
                    .draw();
                } else {
                    
                    var val = "";
                    tablearecebido
                    .column(9)
                    .search(val)
                    .draw();
                    tablearecebido.column(9).search(val ? '^' + val + '$' : '', true, false).draw();
                    
                }
                
            });

            $("#select_administradora").on('change',function(){

            });
            

            



        });
    </script>
@stop

@section('css')
    <style>

        .aba_comissao_container {display:flex;justify-content: space-between;align-content: flex-start;}
        .aba_comissao_table {flex-basis: 83%;}
        .menu_aba_comissao {flex-basis: 16%;background-color: #123449;margin-right: 1%;border-radius:5px;}
        .list_administradoras {display:flex;flex-direction: column;color:#fff;justify-content: center;}
        .total_mes_comissao {color:#FFF;text-align: center;}
        #container_mostrar_comissao {width:439px;height:555px;background-color: #123449;position: absolute;right:5px;border-radius: 5px;}
        .container_edit {display:flex;justify-content:end;}
        .ativo {background-color:#FFF !important;color: #000 !important;}
        .ocultar {display: none;}
        .list_abas {list-style: none;display: flex;border-bottom: 1px solid white;margin: 0;padding: 0;}
        .list_abas li {color: #fff;width: 150px;padding: 8px 5px;text-align:center;border-radius: 5px 5px 0 0;background-color:#123449;}
        .list_abas li:hover {cursor: pointer;}    
        .list_abas li:nth-of-type(2) {margin: 0 1%;}
        .textoforte {background-color:rgba(255,255,255,0.5) !important;color:black;}
        .textoforte-list {background-color:rgba(255,255,255,0.5);color:white;}

        .destaque {
            background-color:rgba(255,255,255,0.5) !important;color:black;
            border:1px solid black;
        }

        .botao:hover {background-color: rgba(0,0,0,0.5) !important;color:#FFF !important;}
        .valores-acomodacao {background-color:#123449;color:#FFF;width:32%;box-shadow:rgba(0,0,0,0.8) 0.6em 0.7em 5px;}
        .valores-acomodacao:hover {cursor:pointer;box-shadow: none;}
        .table thead tr {background-color:#123449;color: white;}
        .destaque {border:4px solid rgba(36,125,157);}
        #coluna_direita {flex-basis:10%;background-color:#123449;border-radius: 5px;}
        #coluna_direita ul {list-style: none;margin: 0;padding: 0;}
        #coluna_direita li {color:#FFF;}
        .coluna-right {flex-basis:30%;flex-wrap: wrap;border-radius:5px;height:720px;}
        .container_div_info {background-color:rgba(0,0,0,1);position:absolute;width:500px;right:0px;top:57px;min-height: 700px;display: none;z-index: 1;color: #FFF;}
        #padrao {width:50px;background-color:#FFF;color:#000;}
        .buttons {display: flex;}
        .button_individual {display:flex;}
        .button_empresarial {display: flex;}
        .menu-inativo {background-color: #123449;color:#FFF;}
        .btn_recebido {background-color:green;color:#FFF;border:none;}

        th { font-size: 0.9em !important; }
        td { font-size: 0.9em !important; }

        .dt-right {text-align: right !important;}
        .dt-center {text-align: center !important;}
        .estilizar_pagination .pagination {font-size: 0.8em !important;color:#FFF;}
        .estilizar_pagination .pagination li {height:10px;color:#FFF;}
        .por_pagina {font-size: 12px !important;color:#FFF;}
        .por_pagina #tabela_mes_atual_length {display: flex;align-items: center;align-self: center;margin-top: 8px;}
        .por_pagina #tabela_mes_diferente_length {display: flex;align-items: center;align-self: center;margin-top: 8px;}
        .por_pagina select {color:#FFF !important;}
        #tabela_individual_previous {color:#FFF !important;background-color: red !important;}
        #tabela_individual_next {color:#FFF !important;}
        #tabela_coletivo_previous {color:#FFF !important;}
        #tabela_coletivo_next {color:#FFF !important;}
        .estilizar_search input[type='search'] {background-color: #FFF !important;}
        .tabela_individual_paginate {color:#FFF !important;}


        .link_coletivo_um:hover {background-color: rgb(245,50,16) !important;}
        .link_coletivo_dois:hover {background-color: rgb(245,50,16) !important;}
        .link_coletivo_tres:hover {background-color: rgb(245,50,16) !important;}
        .link_coletivo_quatro:hover {background-color: rgb(245,50,16) !important;}
        .link_coletivo_cinco:hover {background-color: rgb(245,50,16) !important;}
        .link_coletivo_seis:hover {background-color: rgb(245,50,16) !important;}

        .link_empresarial_um:hover {background-color: rgb(254,200,109) !important;}
        .link_empresarial_dois:hover {background-color: rgb(254,200,109) !important;}
        .link_empresarial_tres:hover {background-color: rgb(254,200,109) !important;}
        .link_empresarial_quatro:hover {background-color: rgb(254,200,109) !important;}   
        .link_empresarial_cinco:hover {background-color: rgb(254,200,109) !important;}
        .link_empresarial_seis:hover {background-color: rgb(254,200,109) !important;}



        
    </style>
@stop




