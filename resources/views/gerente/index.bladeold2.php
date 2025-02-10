@extends('adminlte::page')
@section('title', 'Gerente')

@section('plugins.Datatables', true)
@section('plugins.Toastr', true)


@section('content_top_nav_right')
    <li class="nav-item"><a class="nav-link text-white" href="{{route('orcamento.search.home')}}">Tabela de Preço</a></li>
    <li class="nav-item"><a class="nav-link text-white" href="{{route('home.administrador.consultar')}}">Consultar</a></li>
    <a class="nav-link" data-widget="fullscreen" href="#" role="button"><i class="fas fa-expand-arrows-alt text-white"></i></a>
@stop

@section('content_header')

@stop

@section('content')
    <input type="hidden" name="corretor_escolhido" id="corretor_escolhido">
    <input type="hidden" id="valores_confirmados" value="{{$ids_confirmados ?? null}}">
    <input type="hidden" id="mes_fechado" value="{{$mes ?? ''}}">
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

    <div id="confirmationMessage" class="alert alert-success mt-3" style="display: none;">

    </div>


    <section class="conteudo_abas" style="padding:5px;">

    <ul class="list_abas" style="margin-top:6px;">
        <li data-id="aba_contratos" class="ativo">Contratos</li>
        <li data-id="aba_baixas" class="menu-inativo" style="margin:0 1%;">Baixas</li>
        <li data-id="aba_comissao" class="menu-inativo">Comissão</li>
    </ul>

        <!--------------------------------------Contratos------------------------------------------>
        <main id="aba_contratos">
        <div class="d-flex" style="margin:7px 0;overflow: auto;">
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


                    <a href="{{route('gerente.contrato.show.detalhes.todos.visualizar',[2])}}" class="d-flex" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;background-color:#28a745;color:#FFF;">


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

                    </a>

                    <a href="{{route('gerente.contrato.show.detalhes.todos.visualizar',[3])}}" class="d-flex" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;background-color:#28a745;color:#FFF;">
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
                    </a>

                    <a href="{{route('gerente.contrato.show.detalhes.todos.visualizar',[4])}}" class="d-flex" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;background-color:#28a745;color:#FFF;">
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
                    </a>

                    <a href="{{route('gerente.contrato.show.detalhes.todos.visualizar',[5])}}" class="d-flex" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;background-color:#28a745;color:#FFF;">
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
                    </a>

                    <a href="{{route('gerente.contrato.show.detalhes.todos.visualizar',[6])}}" class="d-flex" style="flex-wrap: wrap;justify-content: space-between;margin:5px 0;border-radius:5px;background-color:#28a745;color:#FFF;">
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
                    </a>

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
                <div class="d-flex flex-column text-white ml-1" style="flex-basis:15%;border-radius:5px;">

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


                <div style="background-color: #123449;padding:3px;">

                    <select name="mes_folha" id="mes_folha" class="form-control form-control-sm mb-1 tamanho_de_25" {{$mes != null && !empty($mes) ? 'disabled' : ''}}>
                        <option value="" class="text-center">---</option>
                        <option value="01" {{$mes == '01' ? 'selected' : ''}}>Janeiro/2023</option>
                        <option value="02" {{$mes == '02' ? 'selected' : ''}}>Fevereiro/2023</option>
                        <option value="03" {{$mes == '03' ? 'selected' : ''}}>Março/2023</option>
                        <option value="04" {{$mes == '04' ? 'selected' : ''}}>Abril/2023</option>
                        <option value="05" {{$mes == '05' ? 'selected' : ''}}>Maio/2023</option>
                        <option value="06" {{$mes == '06' ? 'selected' : ''}}>Junho/2023</option>
                        <option value="07" {{$mes == '07' ? 'selected' : ''}}>Julho/2023</option>
                        <option value="08" {{$mes == '08' ? 'selected' : ''}}>Agosto/2023</option>
                        <option value="09" {{$mes == '09' ? 'selected' : ''}}>Setembro/2023</option>
                        <option value="10" {{$mes == '10' ? 'selected' : ''}}>Outubro/2023</option>
                        <option value="11" {{$mes == '11' ? 'selected' : ''}}>Novembro/2023</option>
                        <option value="12" {{$mes == '12' ? 'selected' : ''}}>Dezembro/2023</option>
                    </select>

                    <div style="border-top:1px solid white;margin-bottom:5px;"></div>
                    <ul style="margin:0;padding:0;">
                        <li style="display:flex;justify-content: space-between;">
                        <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;">
                            Salario:
                        </span>
                            <span style="display:flex;flex-basis:50%;font-size:0.7em;">
                            <input type="text" name="salario" id="salario" class="form-control form-control-sm salario_usuario" style="text-align:right;height:20px;">
                        </span>

                        </li>
                        <li style="display:flex;justify-content: space-between;margin:5px 0;">
                        <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;">
                            Comissão:
                        </span>
                            <span style="display:flex;flex-basis:50%;font-size:0.7em;">
                            <input type="text" name="comissao" id="comissao" class="form-control form-control-sm" readonly placeholder="Comissão" id="valor_comissao" value="0" style="text-align:right;height:20px;">
                        </span>
                        </li>
                        <li style="display:flex;justify-content: space-between;margin:5px 0;">
                        <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;">
                            Premiação:
                        </span>
                            <span style="display:flex;flex-basis:50%;">
                            <input type="text" name="premiacao" id="premiacao" class="form-control form-control-sm premiacao_usuario" style="text-align:right;height:20px;">
                        </span>
                        </li>
                        <li style="display:flex;justify-content: space-between;margin:5px 0;">
                        <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;">
                            Desconto:
                        </span>

                            <span style="display:flex;flex-basis:50%;font-size: 0.7em;">
                            <input type="text" disabled id="valor_total_desconto" name="desconto" id="desconto" class="form-control form-control-sm desconto_usuario" style="text-align:right;height:20px;">
                        </span>
                        </li>

                        <li style="display:flex;justify-content: space-between;">
                            <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;height:20px;">
                            Total:
                            </span>
                            <span style="display:flex;flex-basis:50%;">
                                <input type="text" disabled name="total_campo" id="total_campo" class="form-control form-control-sm total_campo" style="text-align:right;height:20px;">
                            </span>
                        </li>

                    </ul>

                </div>

                <div style="background-color: #123449;padding:3px;margin-top:5px;">
                    <p style="color:white;border-bottom:1px solid white;text-align: center;margin:0;padding: 0;">Planos</p>
                    <ul style="margin:0 0 0 0;padding:0;">
                        <li style="display:flex;justify-content: space-between;" id="listar_individual_apto">
                            <span style="display:flex;flex-basis:60%;font-size:0.7em;">Individual</span>
                            <span style="display:flex;flex-basis:10%;font-size:0.7em;" id="total_quantidade_individual">0</span>
                            <span style="display:flex;flex-basis:30%;justify-content: flex-end;font-size:0.7em;"><span id="valor_total_individual">0</span></span>
                        </li>
                        <li style="display:flex;justify-content: space-between;" id="listar_coletivo_apto">
                            <span style="display:flex;flex-basis:60%;font-size:0.7em;">Coletivo</span>
                            <span style="display:flex;flex-basis:10%;font-size:0.7em;" id="total_quantidade_coletivo">0</span>
                            <span style="display:flex;flex-basis:30%;justify-content: flex-end;font-size:0.7em;"><span id="valor_total_coletivo">0</span></span>
                        </li>
                        <li style="display:flex;justify-content: space-between;" id="listar_empresarial_apto">
                            <span style="display:flex;flex-basis:60%;font-size:0.7em;">Empresarial</span>
                            <span style="display:flex;flex-basis:10%;font-size:0.7em;" id="total_quantidade_empresarial">0</span>
                            <span style="display:flex;flex-basis:30%;justify-content: flex-end;font-size:0.7em;"><span id="valor_total_empresarial">0</span></span>
                        </li>

                    </ul>
                </div>

                <div class="finalizar_mes_container" style="flex-basis:100%;margin-top:5px;display: flex;background-color: #123449;height: 30px;align-items: center;justify-content: center;"  id="footer_user">
                   
               </div>    





                <div id="list_user" style="overflow:auto;max-height:500px;background-color:#123449;margin-top:5px;">

                    <ul style="list-style: none;margin:0;padding:0;">
                        @foreach($users_apto_apagar as $uu)
                            @php
                                $texto = $uu->user;
                                $palavras = \Illuminate\Support\Str::words($texto, 2, '');
                                //$textoLimitado = \Illuminate\Support\Str::before($palavras, ' ') . (\Illuminate\Support\Str::contains($texto, ' ') ? '...' : '');
                            @endphp
                            <li class="d-flex justify-content-between border-top border-bottom border-white text-white w-100 py-2">
                                <span style="font-size:0.8em;" class="user_destaque" data-id="{{$uu->user_id}}">{{$palavras}}</span>
                                <span style="font-size:0.8em;" class="total_pagamento_finalizado user_destaque" data-id="{{$uu->user_id}}">{{number_format($uu->total,2,",",".")}}</span>
                                <span><i class="fas fa-file-pdf criar_pdf" data-id="{{$uu->user_id}}"></i></span>
                            </li>
                        @endforeach
                    </ul>

                </div>

            </div>

           <section style="flex-basis:12%;margin-right: 1%;">
                <div style="background-color: #123449;padding:3px;">
                    <select name="escolher_vendedor" id="escolher_vendedor" class="form-control form-control-sm mb-1 tamanho_de_25" {{$status_disabled ? 'disabled' : ''}}>
                        <option value="" class="text-center">--Corretores--</option>
                        @foreach($users as $u)
                            <option value="{{$u->id}}" data-name="{{$u->name}}">{{$u->name}}</option>
                        @endforeach
                    </select>

                    <div style="border-top:1px solid white;margin-bottom:5px;"></div>
                    <ul style="margin:0;padding:0;">
                        <li style="display:flex;justify-content: space-between;">
                        <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;">
                            Salario:
                        </span>
                            <span style="display:flex;flex-basis:50%;font-size:0.8em;">
                            <input type="text" name="salario_vendedor" id="salario_vendedor" class="form-control form-control-sm salario_usuario_vendedor" style="text-align:right;height:20px;">
                        </span>

                        </li>
                        <li style="display:flex;justify-content: space-between;margin:5px 0;">
                        <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;">
                            Comissão:
                        </span>
                            <span style="display:flex;flex-basis:50%;">
                            <input type="text" name="comissao_vendedor" id="comissao_vendedor" class="form-control form-control-sm" readonly placeholder="Comissão" id="valor_comissao" value="0" style="text-align:right;height:20px;">
                        </span>
                        </li>
                        <li style="display:flex;justify-content: space-between;margin:5px 0;">
                        <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;">
                            Premiação:
                        </span>
                            <span style="display:flex;flex-basis:50%;">
                            <input type="text" name="premiacao_vendedor" id="premiacao_vendedor" class="form-control form-control-sm premiacao_usuario_vendedor" style="text-align:right;height:20px;">
                        </span>
                        </li>
                        <li style="display:flex;justify-content: space-between;margin:5px 0;">
                        <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;">
                            Desconto:
                        </span>

                            <span style="display:flex;flex-basis:50%;">
                            <input type="text" disabled id="valor_total_desconto_vendedor" name="desconto_vendedor" class="form-control form-control-sm desconto_usuario_vendedor" style="text-align:right;height:20px;">
                        </span>
                        </li>

                        <li style="display:flex;justify-content: space-between;">
                            <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;height:20px;">
                            Total:
                            </span>
                            <span style="display:flex;flex-basis:50%;">
                                <input type="text" disabled name="total_campo_vendedor" id="total_campo_vendedor" class="form-control form-control-sm total_campo_vendedor" style="text-align:right;height:20px;">
                            </span>
                        </li>

                    </ul>

                </div>

               <div style="background-color:#123449;border-radius:5px;padding:3px;margin:5px 0;">
                   <p class="border-bottom text-center" style="margin:0;padding:0;color:white;">Recebidas</p>
                   <ul style="margin:0 0 0 0;padding:0;list-style:none;" class="listar">
                       <li style="display:flex;font-size:0.7em;" class="individual_recebidas ml-1 ativo">Individual</li>
                       <li style="display:flex;font-size:0.7em;" class="coletivo_recebidas ml-1">Coletivo</li>
                       <li style="display:flex;font-size:0.7em;" class="empresarial_recebidas ml-1">Empresarial</li>
                   </ul>
               </div>

               <div style="background-color:#123449;border-radius:5px;padding:3px;margin:5px 0;">
                   <p class="border-bottom text-center" style="margin:0;padding: 0;color: white;">A Receber</p>
                   <ul style="margin:0 0 0 0;padding:0;list-style:none;" class="listar listar_a_receber_ul">
                       <li style="display:flex;font-size:0.7em;" class="individual_a_receber ml-1">Individual</li>
                       <li style="display:flex;font-size:0.7em;" class="coletivo_a_receber ml-1">Coletivo</li>
                       <li style="display:flex;font-size:0.7em;" class="empresarial_a_receber ml-1">Empresarial</li>
                   </ul>
               </div>

               



                <button class="btn btn-block mt-2" id="finalizar_folha" style="background-color:#123449;color:white;font-size: 0.7em;">
                    Finalizar: <span class="total_a_pagar">0</span>
                </button>

           </section>


           <section style="flex-basis:75%;">
               <div style="color:#FFF;border-radius:5px;" id="tabela_aptos_a_pagar" class="dsnone">
                   <div style="background-color:#123449;border-radius:5px;">
                       <table id="tabela_aptos_a_pagar_table" class="table table-sm listaraptosapagar w-100">
                           <thead>
                           <tr>
                               <th>Admin</th>
                               <th>Parcela</th>
                               <th>Cliente</th>
                               <th align="center">Vencimento</th>
                               <th>Baixa</th>
                               <th>Valor</th>
                               <th>Comissão</th>
                               <th>Ação</th>
                           </tr>
                           </thead>
                           <tbody></tbody>
                       </table>
                   </div>
               </div>

               <div style="color:#FFF;border-radius:5px;" id="tabela_principal">
                   <div style="background-color:#123449;border-radius:5px;">
                       <table id="tabela_mes_recebidas" class="table table-sm listarcomissaomesrecebidas w-100">
                           <thead>
                           <tr>
                               <th>Admin</th>
                               <th>Data</th>
                               <th>Cod.</th>
                               <th>Cliente</th>
                               <th>Parcela</th>
                               <th>Valor</th>
                               <th align="center">Vencimento</th>
                               <th>Baixa</th>
                               <th>Comissão</th>
                               <th>%</th>
                               <th>Pagar</th>
                               <th>Status</th>
                           </tr>
                           </thead>
                           <tbody></tbody>
                       </table>
                   </div>
               </div>

               <div style="color:#FFF;" id="listar_a_receber" class="dsnone">
                   <div style="background-color:#123449;border-radius:5px;">
                       <table id="tabela_mes_diferente" class="table table-sm listarcomissaomesdiferente p-3" >
                           <thead>
                           <tr>
                               <th>Admin</th>
                               <th>Data</th>
                               <th>Cod.</th>
                               <th>Cliente</th>
                               <th>Parcela</th>
                               
                               <th>Valor</th>
                               <th align="center">Vencimento</th>
                               <th>Baixa</th>

                               <th>%</th>
                               <th>Pagar</th>
                               <th>Desconto</th>
                               <th>Antecipar</th>
                           </tr>
                           </thead>
                           <tbody></tbody>
                       </table>
                   </div>
               </div>
           </section>
       </main>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Finalizar o Folha</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <p class="d-flex">
                        <span class="d-flex" style="flex-basis:20%;">Salario:</span>
                        <span class="d-flex salario_usuario_modal" style="flex-basis:70%;"></span>
                    </p>
                    <p class="d-flex">
                        <span class="d-flex" style="flex-basis:20%;">Comissão:</span>
                        <span class="d-flex comissao_usuario_modal"></span>
                    </p>
                    <p class="d-flex">
                        <span class="d-flex" style="flex-basis:20%;">Premiação:</span>
                        <span class="d-flex premiacao_usuario_modal"></span>
                    </p>
                    <p class="d-flex">
                        <span class="d-flex" style="flex-basis:20%;">Desconto:</span>
                        <span class="d-flex desconto_usuario_modal"></span>
                    </p>
                    <p class="d-flex">
                        <span class="d-flex" style="flex-basis:20%;">Total:</span>
                        <span class="d-flex total_a_pagar_modal"></span>
                    </p>







                </div>
                <div class="modal-footer" style="display:flex;justify-content: center;">

                    <button type="button" class="btn btn-primary btn_usuario" data-dismiss="modal">Criar o PDF</button>
                </div>
            </div>
        </div>
    </div>




    <div id="myModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center">Resumo do Fechamento do Mês</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="resultado_tabela">

                    </div>


                    <p class="text-center">Deseja Fechar este Mês?</p>
                    <p class="d-flex">
                        <span class="w-25 text-sm d-flex align-content-center align-items-center">Digite a senha:</span>
                        <input type="password" id="passwordInput" class="form-control form-control-sm w-75" placeholder="Senha">
                    </p>


                    <div id="errorMessage" class="mt-2 text-red font-weight-bold text-center"></div>
                </div>
                <div class="modal-footer">
                    <button id="confirmBtn" class="btn btn-primary btn-block">Fechar Mês</button>

                </div>
            </div>
        </div>
    </div>






@stop








@section('js')
    <script src="{{asset('js/jquery.mask.min.js')}}"></script>
    <script>
        $(function(){

           
            function empresarial_a_receber() {
                let id = $("#corretor_escolhido").val();
                $("#listar_individual_apto").removeClass('ativo');
                $("#listar_coletivo_apto").removeClass('ativo');
                $(".individual_recebidas").removeClass('ativo');
                $(".coletivo_recebidas").removeClass('ativo');
                $(".individual_a_receber").removeClass('ativo');
                $(this).addClass('ativo');
                if(id) {
                    $("#listar_individual_apto").removeClass("ativo");
                    if($("#listar_a_receber").is(":visible")) {
                        $("#title_comissao_diferente").html("<h4>A Receber Coletivo</h4>")
                        listarcomissaomesdfirente.ajax.url(`{{ url('/admin/gerente/coletivo/listar/${id}') }}`).load();
                    } else {
                        if($("#tabela_principal").is(":visible")) {
                            $("#tabela_principal").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Coletivo</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/admin/gerente/coletivo/listar/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_aptos_a_pagar").is(":visible")) {
                            $("#tabela_aptos_a_pagar").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Coletivo</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/admin/gerente/coletivo/listar/${id}') }}`).load();
                                });
                            });
                        }
                    }
                } else {
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false
                    }
                }
            }




            function coletivo_a_receber() {
                let id = $("#corretor_escolhido").val();
                $("#listar_individual_apto").removeClass('ativo');
                $("#listar_coletivo_apto").removeClass('ativo');
                $(".individual_recebidas").removeClass('ativo');
                $(".coletivo_recebidas").removeClass('ativo');
                $(".individual_a_receber").removeClass('ativo');
                $(".empresarial_a_receber").removeClass('ativo');
                $(".empresarial_recebidas").removeClass('ativo');
                $(this).addClass('ativo');
                if(id) {
                    $("#listar_individual_apto").removeClass("ativo");
                    if($("#listar_a_receber").is(":visible")) {
                        $("#title_comissao_diferente").html("<h4>A Receber Coletivo</h4>")
                        listarcomissaomesdfirente.ajax.url(`{{ url('/admin/gerente/coletivo/listar/${id}') }}`).load();
                    } else {
                        if($("#tabela_principal").is(":visible")) {
                            $("#tabela_principal").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Coletivo</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/admin/gerente/coletivo/listar/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_aptos_a_pagar").is(":visible")) {
                            $("#tabela_aptos_a_pagar").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Coletivo</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/admin/gerente/coletivo/listar/${id}') }}`).load();
                                });
                            });
                        }
                    }
                } else {
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false
                    }
                }
            }
            $(".coletivo_a_receber").on('click',coletivo_a_receber);






            function individual_a_receber() {
                let id = $("#corretor_escolhido").val();
                $("#listar_individual_apto").removeClass('ativo');
                $("#listar_coletivo_apto").removeClass('ativo');
                $(".individual_recebidas").removeClass('ativo');
                $(".coletivo_recebidas").removeClass('ativo');
                $(".coletivo_a_receber").removeClass('ativo');
                $(".empresarial_a_receber").removeClass('ativo');
                $(".empresarial_recebidas").removeClass('ativo');


                $(this).addClass('ativo');
                if(id) {
                    $("#listar_individual_apto").removeClass("ativo");
                    if($("#listar_a_receber").is(":visible")) {
                        $("#title_comissao_diferente").html("<h4>A Receber Individual</h4>")
                        listarcomissaomesdfirente.ajax.url(`{{ url('/admin/gerente/listagem/comissao_mes_diferente/${id}') }}`).load();
                    } else {
                        if($("#tabela_principal").is(":visible")) {
                            $("#tabela_principal").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4 class='px-3 mt-3'>A Receber Individual</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/admin/gerente/listagem/comissao_mes_diferente/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_aptos_a_pagar").is(":visible")) {
                            $("#tabela_aptos_a_pagar").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Individual</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/admin/gerente/listagem/comissao_mes_diferente/${id}') }}`).load();
                                });
                            });
                        }
                    }
                } else {
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");

                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false

                    }
                }
            }



            $(".individual_a_receber").on('click',individual_a_receber);
            function showConfirmationMessage() {
                let confirmationMessage = $('#confirmationMessage');
                confirmationMessage.html('<p class="text-align">Mês Finalizado com Sucesso!! =)</p>');
                confirmationMessage.show();

                setTimeout(function() {
                    confirmationMessage.hide();
                }, 5000);
            }
            var total_valor = 0;
            function finalizarMes() {
                $('.total_pagamento_finalizado').each(function(){
                    total_valor += parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
                });
                if(total_valor != 0) {
                    let value = total_valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });    
                    
                    $(".finalizar_mes_container").html(`<button class="btn finalizar_mes" style="height: inherit;display: flex;font-size: 0.7em;position: absolute;flex-basis: 100%;">${value}</button>`);
                }
                total_valor = 0;
            }
            finalizarMes();


            $("body").on('click','.user_destaque',function(){
                let id = $(this).attr("data-id");
                $("#escolher_vendedor option[value='"+id+"']").prop("selected",true);
                $("#corretor_escolhido").val(id);
                $("#list_user ul li").removeClass('user_destaque_ativo');
                $(this).closest("li").addClass('user_destaque_ativo');


                if($("#mes_folha").val() != null) {
                    let mes = $("#mes_folha").val();

                    $.ajax({
                       url:"{{route('gerente.listagem.confirmadas.especifica')}}",
                       data:"mes="+mes+"&id="+id,
                       method:"POST",
                       success:function(res) {

                            

                           $("#salario_vendedor").val(res.salario);
                           $("#comissao_vendedor").val(res.comissao);
                           $("#premiacao_vendedor").val(res.premiacao);

                           $("#valor_total_desconto_vendedor").val(res.desconto);

                           $("#total_campo_vendedor").val(res.total);
                           //$("#").val(res.total);

                           $("#total_quantidade_individual").text(res.total_individual_quantidade);
                           $("#total_quantidade_coletivo").text(res.total_coletivo_quantidade);
                           $("#valor_total_individual").text(res.total_individual);
                           $("#valor_total_coletivo").text(res.total_coletivo);
                           $("#valores_confirmados").val(res.id_confirmados);
                           $(".total_a_pagar").text(res.total);

                           $("#listar_individual_apto").removeClass("ativo");
                           $("#listar_coletivo_apto").removeClass("ativo");
                           listaraptosapagar.ajax.reload(function() {
                               listaraptosapagar.clear().draw();
                           });


                       }
                    });
                }



            });


            var id_confirmados = [];

            $("#finalizar_folha").on('click',function(){

                if($("#mes_folha").val() == "") {
                    toastr["error"]("Mês é campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "00",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                }

                if($("#salario_vendedor").val() == "") {
                    toastr["error"]("Salario é campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "00",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                }
                $('#exampleModal').modal('show')
                return true;
            });

            $("#exampleModal").on('show.bs.modal', function (event) {
                let comissao =  $("#comissao_vendedor").val();
                let salario = $(".salario_usuario_vendedor").val();
                let premiacao = $(".premiacao_usuario_vendedor").val();
                let desconto = $("#valor_total_desconto_vendedor").val();

                console.log(desconto);

                let user_id = $("#cliente_id").val();
                let mes = $("#mes_folha").val();
                let total_a_pagar = $(".total_campo_vendedor").val();

                $(".salario_usuario_modal").text(salario);
                $(".comissao_usuario_modal").text(comissao);
                $(".premiacao_usuario_modal").text(premiacao);
                $(".desconto_usuario_modal").text(desconto);
                $(".total_a_pagar_modal").text(total_a_pagar);

                let selectedOptionText = $('#escolher_vendedor option:selected').text();
                let selectMesText = $("#mes_folha option:selected").text();
                $(".btn_usuario").text("Finalizar folha de "+selectedOptionText+" do mes "+selectMesText);




            });


            $("body").on('click','.finalizar_mes',function(){
                $('#myModal').modal('show');
                id_confirmados = [];
            });


            $('#myModal').on('shown.bs.modal', function() {
                let mes = $("#mes_folha").val();
                $.ajax({
                    url:"{{route('montar.tabela.mes.modal')}}",
                    data:"mes="+mes,
                    method:"POST",
                    success:function(res) {
                        $("#resultado_tabela").html(res)
                    }
                });
                // Outras ações que você deseja executar quando a modal for aberta
            });

            $('#confirmBtn').click(function() {

                let password = $('#passwordInput').val();
                let mes = $("#mes_folha").find('option:selected').val();
                let ano = $("#mes_folha").find('option:selected').text().split("/")[1];

                if (password === '0000') {
                    $.ajax({
                       url:"{{route('gerente.pagamento.mes.finalizado')}}",
                       method:"POST",
                       data:"mes="+mes+"&ano="+ano,
                        success:function(res) {
                           if(res == "sem_mes") {

                           } else {
                               $("#list_user").html('');
                               $('#mes_folha').removeAttr('disabled');
                               $('#mes_folha option').removeAttr('selected');
                           }
                       }
                    });





                    $('#errorMessage').text('');
                    $('#myModal').modal('hide');
                    $("#mes_folha").val("");
                    $(".individual_recebidas").removeClass('ativo');
                    $(".coletivo_recebidas").removeClass('ativo');
                    $(".empresarial_recebidas").removeClass('ativo');

                    $(".individual_a_receber").removeClass('ativo');
                    $(".coletivo_a_receber").removeClass('ativo');
                    $(".empresarial_a_receber").removeClass('ativo');

                    $("#listar_individual_apto").removeClass('ativo');
                    $("#listar_coletivo_apto").removeClass('ativo');
                    $("#listar_empresarial_apto").removeClass('ativo');

                    $(".finalizar_mes").html("Finalizar");

                    $("#escolher_vendedor").prop("disabled",true);
                    $('#passwordInput').val('')

                    showConfirmationMessage();
                } else {

                    $('#errorMessage').text('Senha incorreta. Por favor, tente novamente.');
                }
            });





            $("#mes_folha").on('change',function(){
               let mes = $(this).val();
               if(mes == "") {
                   //$("#escolher_vendedor").prop("disabled",true)
               } else {
                   $("#escolher_vendedor").removeAttr("disabled")
               }
               let selectedText = $("#mes_folha option:selected").text();
               let ano = $(this).find('option:selected').text().split("/")[1];
               let formattedDate = ano + "-" + mes+"-01";
               $.ajax({
                   url:"{{route('gerente.cadastrar.folha_mes')}}",
                   method:"POST",
                   data:"data="+formattedDate,
                   success:function(res) {
                       if(res.resposta != "cadastrado") {
                           let select = $('#escolher_vendedor');
                           select.html('');
                           select.append($('<option>', {
                               value: '',
                               text: '--Corretores--'
                           }).css('text-align', 'center'))

                           $.each(res.users, function(index, user) {
                               let option = $('<option>', {
                                   value: user.user_id,
                                   text: user.user
                               });
                               select.append(option);
                           });
                           $("#list_user").html(res.view);
                           if ($('.list_abas #mes_existe').length > 0) {
                               let selectedTextMudou = $("#mes_folha option:selected").text();
                               $('.list_abas #mes_existe').hide(function(){
                                   let selectedTextMudou = $("#mes_folha option:selected").text();
                                   $('.list_abas #mes_existe').remove();
                                   $(".list_abas").append("<li id='mes_existe' style='width:910px;margin-left:5px;background-color:#B22222;'>O Mês "+selectedTextMudou+" já esta fechado</li>").show();
                               });
                           } else {
                               let selectedTextMudou = $("#mes_folha option:selected").text();
                               $(".list_abas").append("<li id='mes_existe' style='width:910px;margin-left:5px;background-color:#B22222;'>O Mês "+selectedTextMudou+" já esta fechado</li>");
                           }
                           //$('.footer_user').html();

                           $(".menu_aba_comissao").height("210px");



                           $("#footer_user").html(
                               `<div class='d-flex flex-column text-white w-100' style='background-color:#123449;'>
                                    <p class='d-flex justify-content-between' style='margin-bottom:0px;'>
                                        <span style="flex-basis:50%;text-align:center;">Salario:</span>
                                        <span style="flex-basis:50%;text-align:center;">${res.valores.salario}</span>
                                    </p>
                                    <p class='d-flex' style='margin-bottom:0px;'>
                                        <span style="flex-basis:50%;text-align:center;">Comissão:</span>
                                        <span style="flex-basis:50%;text-align:center;">${res.valores.comissao}</span>
                                    </p>
                                    <p class='d-flex' style='margin-bottom:0px;'>
                                        <span style="flex-basis:50%;text-align:center;">Premiação:</span>
                                        <span style="flex-basis:50%;text-align:center;">${res.valores.premiacao}</span>
                                    </p>
                                    <p class='d-flex' style='margin-bottom:0px;'>
                                        <span style="flex-basis:50%;text-align:center;">Total:</span>
                                        <span style="flex-basis:50%;text-align:center;">${res.valores.total}</span>
                                    </p>
                                    <p class='d-flex justify-content-center mt-1'>
                                        <i class="fas fa-file-pdf fa-lg btn_valores_mes" data-mes='${res.valores.mes}'></i>
                                    </p>
                               </div>`
                           );
                           $("#mes_fechado").val(mes);
                           var columnIndex = 7;
                           var column = listaraptosapagar.column(columnIndex);
                           column.visible(false);
                           $("#finalizar_folha").off('click').prop('disabled', true);
                           $(".individual_recebidas").off('click');
                           $(".coletivo_recebidas").off('click');
                           $(".empresarial_recebidas").off('click');
                           $(".individual_a_receber").off('click');
                           $(".coletivo_a_receber").off('click');
                           $(".empresarial_a_receber").off('click');

                           $("#salario_vendedor").val(0);
                           $("#premiacao_vendedor").val(0);
                           $("#comissao_vendedor").val(0);
                           $("#desconto_vendedor").val(0);

                           $("#finalizar_folha").text("Finalizar");

                           $("#total_quantidade_individual").text(0);
                           $("#valor_total_individual").text(0);

                           $("#total_quantidade_coletivo").text(0);
                           $("#valor_total_coletivo").text(0);

                           $("#total_quantidade_empresarial").text(0);
                           $("#valor_total_empresarial").text(0);

                           


                       } else {

                           $("#mes_existe").hide();
                           $(".individual_recebidas").on('click',individual_recebidas);
                           $(".coletivo_recebidas").on('click',coletivo_recebidas);
                           $(".empresarial_recebidas").on('click');
                           $(".individual_a_receber").on('click',individual_a_receber);
                           $(".coletivo_a_receber").on('click',coletivo_a_receber);
                           $(".empresarial_a_receber").on('click');
                           $("#list_user").html("");

                           let select = $('#escolher_vendedor');
                           select.html('');
                           select.append($('<option>', {
                               value: '',
                               text: '--Corretores--'
                           }).css('text-align', 'center'))



                           $.each(res.users_select, function(index, user) {
                               let option = $('<option>', {
                                   value: user.id,
                                   text: user.name
                               });
                               select.append(option);
                           });
                           $("#footer_user").html('<button class="btn btn-info btn-block mx-auto finalizar_mes">Finalizar</button>');
                       }
                   }
                });
            });

            $("body").on('click','.btn_valores_mes',function(){
                let mes = $(this).attr('data-mes');
                $.ajax({
                   url:"{{route('geral.folha.mes.especifica')}}",
                   method:"POST",
                   data:"mes="+mes,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success:function(blob,status,xhr,ppp) {
                        var filename = "";
                        var disposition = xhr.getResponseHeader('Content-Disposition');
                        if (disposition && disposition.indexOf('attachment') !== -1) {
                            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            var matches = filenameRegex.exec(disposition);
                            if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                        }
                        if (typeof window.navigator.msSaveBlob !== 'undefined') {
                            window.navigator.msSaveBlob(blob,filename);
                        } else {
                            $(".salario_usuario").val("");
                            $("#comissao").val("");
                            $("#premiacao").val("");
                            $("#valor_total_individual").val('0.00');
                            $("#valor_total_coletivo").val('0.00');
                            $("#valor_total_empresarial").val('0.00');

                            var URL = window.URL || window.webkitURL;
                            var downloadUrl = URL.createObjectURL(blob);
                            if (filename) {
                                var a = document.createElement("a");
                                if (typeof a.download === 'undefined') {
                                    window.location.href = downloadUrl;
                                } else {
                                    a.href = downloadUrl;
                                    a.download = filename;
                                    document.body.appendChild(a);
                                    a.click();
                                }
                            } else {
                                window.location.href = downloadUrl;
                            }
                            setTimeout(function () {
                                URL.revokeObjectURL(downloadUrl);
                            },100);
                        }
                    }


                });
            });



            String.prototype.ucWords = function () {
                let str = this.toLowerCase()
                let re = /(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g
                return str.replace(re, s => s.toUpperCase())
            }
            const date = new Date();

            function adicionarZero(valor) {
                if(valor.length == 2) return valor;
                return "0"+valor;
            }

            $("#escolher_vendedor").on('change',function(){
                let id = $(this).val();
                let mes = $("#mes_folha option:selected").val();

                $("#corretor_escolhido").val(id);
                $.ajax({
                   url:"{{route('gerente.informacoes.quantidade.corretor')}}",
                   method:"POST",
                   data:"id="+id+"&mes="+mes,
                    success:function(res) {
                       
                        $("#total_quantidade_individual").text(res.total_individual_quantidade);
                        $("#total_quantidade_coletivo").text(res.total_coletivo_quantidade);
                        $("#valor_total_individual").text(res.total_individual);
                        $("#valor_total_coletivo").text(res.total_coletivo);
                        $("#comissao_vendedor").val(res.total_comissao);
                        $("#valores_confirmados").val(res.id_confirmados);
                        $("#salario_vendedor").val(res.total_salario);
                        $("#premiacao_vendedor").val(res.total_premiacao);
                        $("#valor_total_desconto").val(res.desconto);
                        $(".coletivo_a_receber").removeClass('ativo');
                        $(".individual_a_receber").removeClass('ativo');
                        $(".empresarial_a_receber").removeClass('ativo');
                        $(".individual_recebidas").removeClass('ativo');
                        $(".coletivo_recebidas").removeClass('ativo');
                        $(".empresarial_recebidas").removeClass('ativo');    
                        $("#listar_individual_apto").removeClass('ativo');
                        $("#listar_coletivo_apto").removeClass('ativo');
                        $("#listar_empresarial_apto").removeClass('ativo');    

                        // let total_a_pagar = parseFloat(res.total_comissao) - parseFloat(res.desconto)
                        if(parseFloat(res.total_comissao) > 0) {
                            $(".total_a_pagar").text(res.total);

                        } else {
                            $(".total_a_pagar").text(0);
                        }
                        listaraptosapagar.ajax.reload();
                        listaraptosapagar.ajax.url("{{route('gerente.listagem.zerar.tabelas')}}").load();

                        listarcomissaomesdfirente.ajax.reload();
                        listarcomissaomesdfirente.ajax.url("{{route('gerente.listagem.zerar.tabelas')}}").load();





                    }
                });
            });

            $("body").on('change','.confirmar_comissao',function(){
                let acao = $(this).val();
                let id = $(this).attr("id");
                let plano = $(this).attr("data-plano");

                // var linha = $(this).closest('tr');
                // listaraptosapagar.row(linha).remove().draw();

                if(acao == 2) {
                    let valor = $(this).closest("tr").find('.comissao_recebida').text().replace("R$","").replace(/\./g,'').replace(',', '.').trim();
                    let valor_total = $("#comissao").val().replace(',', '.').trim();

                    let calculado = valor_total - valor;

                    $("#comissao").val(calculado.toFixed(2).replace('.', ','));
                    $(".total_a_pagar").html(calculado.toFixed(2).replace('.', ','));

                    if(plano == 1) {

                        let total_quantidade_individual = $("#total_quantidade_individual").text();
                        total_quantidade_individual -= 1
                        $("#total_quantidade_individual").text(total_quantidade_individual);

                        let valor_total_individual = $("#valor_total_individual").text().replace("R$","").trim();
                        let valor_total_individual_calculado = valor_total_individual - valor;
                        $("#valor_total_individual").html(valor_total_individual_calculado.toFixed(2));


                    } else if(plano == 3) {

                        let valor_total_coletivo = $("#valor_total_coletivo").text().replace(/\./g,'').replace("R$","").replace(',', '.').trim();
                        let valor_total_coletivo_cancelado = valor_total_coletivo - valor;
                        let valor_total_coletivo_cancelado_br = valor_total_coletivo_cancelado.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                        // $("valor_total_coletivo").
                        $("#valor_total_coletivo").text(valor_total_coletivo_cancelado_br);
                        let total_quantidade_coletivo = $("#total_quantidade_coletivo").text()
                        total_quantidade_coletivo -= 1;
                        $("#total_quantidade_coletivo").text(total_quantidade_coletivo);



                    } else {

                    }

                    $.ajax({
                        url:"{{route('gerente.mudar.para_a_nao_pago')}}",
                        method:"POST",
                        data:"id="+id,
                        success:function(res) {
                            if(res == true) {
                                listaraptosapagar.ajax.reload();
                                listarcomissaomesrecebidas.ajax.reload();
                                listarcomissaomesdfirente.ajax.reload();
                            }
                        }
                    });



                    // let valores = $("#valores_confirmados").val();
                    // var arrayFiltrado = valores.split(",").filter(function(valor) {
                    //     return valor !== id
                    // });
                    // $("#valores_confirmados").val(arrayFiltrado);






                }








                // id_confirmados = id_confirmados.filter(function(elemento) {
                //     return elemento !== valor;
                // });

                //console.log(id_confirmados);

            });






            var listaraptosapagar = $(".listaraptosapagar").DataTable({
                dom: '<"d-flex justify-content-between"<"#title_individual_confirmados"><"estilizar_search"f>><t><"d-flex justify-content-between align-items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":`{{ route('gerente.listagem.zerar.tabelas') }}`,
                    "dataSrc": "",
                },
                "lengthMenu": [50,100,150,200,300,500],
                // "lengthMenu": [1,2,3,4,5,6],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                columns: [
                    {data:"administradora",name:"administradora",width:"8%"},
                    {data:"parcela",name:"parcela",width:"5%",className: 'dt-center'},
                    {data:"cliente",name:"cliente",width:"30%",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            let palavras = cellData.ucWords();
                            let dados = palavras.split(" ");
                            if(dados.length >= 2) {
                                $(td).html(dados[0]+" "+dados[1]+"...");
                            }
                        }
                    },
                    {data:"data",name:"data",width:"5%",className: 'dt-center'},
                    {data:"data_baixa_gerente",name:"baixa",width:"5%",
                        "createdCell":function(td,cellData,rowData,row,col) {

                            if(cellData == null) {
                                let alvo = rowData.data_antecipacao.split("-").reverse().join("/")
                                $(td).html(alvo);
                            } else {
                                let alvo = cellData.split("-").reverse().join("/")
                                $(td).html(alvo);
                            }
                        }
                    },
                    {data:"valor_plano_contratado",name:"valor_plano",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center'},
                    {data:"comissao_esperada",name:"comissao_esperada",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center comissao_recebida'},
                    {data:"id",name:"id",width:"5%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let selected = $('<select />', {
                                name: 'confirmar_comissao',
                                class:"confirmar_comissao",
                                'data-plano':rowData.plano,
                                id:cellData,
                                append : [
                                    $('<option />', {value : "3", text : "Pagar"}),
                                    $('<option />', {value : "2", text : "Voltar"}),
                                ]
                            });
                            $(td).html(selected)
                        }
                    }
                ],
                "initComplete": function( settings, json ) {
                    $('#title_individual_confirmados').html("<h4>Individual Confirmados</h4>");
                },
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    var intVal = function (i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };
                    conditionalTotal = 0;
                    conditionalRecebida = 0;
                    api.rows( { search: "applied" } ).every( function ( rowIdx, tableLoop, rowLoop ) {
                        var d = this.data();
                        conditionalTotal += intVal(d['comissao_esperada']);
                        conditionalRecebida += intVal(d['comissao_recebida']);
                        //qtdLinha = rowLoop + 1;
                    });
                    $("#previsao_de_comissao").html("Previsão da Comissão: "+conditionalTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
                }
            });

            var listarcomissaomesrecebidas = $(".listarcomissaomesrecebidas").DataTable({
                dom: '<"d-flex justify-content-between"<"#title_recebidas"><"estilizar_search"f>><t><"d-flex justify-content-between align-items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":`{{ route('gerente.listagem.zerar.tabelas') }}`,
                    //"url":`{{ url('/admin/gerente/listagem/empresarial/recebidas/${id}') }}`,
                    "dataSrc": "",
                },
                "lengthMenu": [50,100,150,200,300,500],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                columns: [
                    {data:"administradora",name:"administradora",width:"8%"},
                    {data:"data_criacao",name:"data_criacao",width:"8%",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            let datas = cellData.split(" ")[0].split("-").reverse().join("/");
                            $(td).html(datas);
                        }
                    },
                    {data:"orcamento",name:"orcamento"},
                    {data:"cliente",name:"cliente",width:"30%",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            let palavras = cellData.ucWords();
                            let dados = palavras.split(" ");
                            if(dados.length >= 2) {
                                $(td).html(dados[0]+" "+dados[1]);
                            }
                        }
                    },
                    {data:"parcela",name:"parcela",width:"5%",className: 'dt-center'},
                    
                    {data:"valor_plano_contratado",width:"8%",name:"valor_plano",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center'},
                    {data:"data",name:"data",width:"5%",className: 'dt-center'},
                    {data:"data_baixa_gerente",name:"baixa",width:"5%",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            if(cellData == null) {
                                //let alvo = rowData.data_antecipacao.split("-").reverse().join("/");
                                $(td).html("text");
                            } else {
                                let alvo = cellData.split("-").reverse().join("/")
                                $(td).html(alvo);
                            }
                        }
                    },
                    {data:"comissao_esperada",name:"comissao_esperada",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center comissao_recebida',
                        "createdCell":function(td, cellData, rowData, row, col) {
                            
                            if(rowData.valor_pago != null) {
                                let valor = parseFloat(rowData.valor_pago).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
                                $(td).html(valor);
                            } else {
                                let valor = parseFloat(cellData).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
                                $(td).html(valor);
                            }
                        },
                        width:"8%"

                    },
                    {data:"porcentagem_parcela_corretor",name:"porcentagem_parcela_corretor",
                        "createdCell":function(td, cellData, rowData, row, col) {

                            if(rowData.porcentagem_paga == null) {
                                $(td).html('<input type="text" data-valor-plano='+rowData.valor_plano_contratado+' value='+cellData+' data-id='+rowData.id+' name="comissao_paga_change" class="comissao_paga_change" style="width:30px;" />')
                            } else {
                                $(td).html('<input type="text" data-valor-plano='+rowData.valor_plano_contratado+' value='+rowData.porcentagem_paga+' data-id='+rowData.id+' name="comissao_paga_change" class="comissao_paga_change" style="width:30px;" />')
                            }
                        },
                        width:"8%"
                    },
                    {data:"comissao_esperada",name:"comissao_pagando",
                        "createdCell":function(td, cellData, rowData, row, col) {
                            let valor = parseFloat(cellData).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
                            valor = valor.replace('R$', '');

                            $(td).html('<input type="text" data-id='+rowData.id+' name="comissao_pagando" value='+valor+' class="comissao_pagando" style="width:50px;" />')
                        }
                    },
                    {data:"id",name:"id",width:"5%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let selected = $('<select />', {
                                name: 'pagar_comissao',
                                class:"pagar_comissao",
                                'data-plano':rowData.plano,
                                id:cellData,
                                append : [
                                    $('<option />', {value : "1", text : "Em Aberto"}),
                                    $('<option />', {value : "2", text : "Pagar"}),
                                ]
                            });
                            $(td).html(selected)
                        }
                    }
                ],
                "initComplete": function( settings, json ) {
                    $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                },
                footerCallback: function (row, data, start, end, display) {

                }
            });

            function coletivo_recebidas() {
                let id = $("#corretor_escolhido").val();

                $("#listar_individual_apto").removeClass('ativo');
                $("#listar_coletivo_apto").removeClass('ativo');
                $(".individual_recebidas").removeClass('ativo');
                $(".individual_a_receber").removeClass('ativo');
                $(".coletivo_a_receber").removeClass('ativo');
                $(".empresarial_recebidas").removeClass('ativo');
                $(this).addClass('ativo');


                if(id) {
                    $("#listar_individual_apto").removeClass("ativo");
                    if($("#tabela_principal").is(":visible")) {
                        $('#title_recebidas').html("<h4>Recebidas - Coletivo</h4>");
                        listarcomissaomesrecebidas.ajax.url(`{{ url('/admin/gerente/listagem/recebidas/coletivo/${id}') }}`).load();
                    } else {
                        if($("#listar_a_receber").is(':visible')) {

                            $("#listar_a_receber").slideUp('slow',function(){
                                $("#tabela_principal").slideDown('slow',function(){
                                    $('#title_recebidas').html("<h4>Recebidas - Coletivo</h4>");
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/admin/gerente/listagem/recebidas/coletivo/${id}') }}`).load();
                                });
                            })
                        }
                        if($("#tabela_aptos_a_pagar").is(":visible")) {
                            $("#tabela_aptos_a_pagar").slideUp('slow',function(){
                                $("#tabela_principal").slideDown('slow',function(){
                                    $('#title_recebidas').html("<h4>Recebidas - Coletivo</h4>");
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/admin/gerente/listagem/recebidas/coletivo/${id}') }}`).load();
                                });
                            })
                        }
                    }
                } else {
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false
                    }
                }
            }

            $(".coletivo_recebidas").on('click',coletivo_recebidas);


            function empresarial_recebidas() {
                let id = $("#corretor_escolhido").val();

                $("#listar_individual_apto").removeClass('ativo');
                $("#listar_coletivo_apto").removeClass('ativo');
                $(".individual_recebidas").removeClass('ativo');
                $(".individual_a_receber").removeClass('ativo');
                $(".coletivo_a_receber").removeClass('ativo');
                $(".coletivo_recebidas").removeClass('ativo');


                $(this).addClass('ativo');

                if(id) {

                    $("#listar_individual_apto").removeClass("ativo");
                    if($("#tabela_principal").is(":visible")) {
                        $("#title_recebidas").html("<h4>Recebidas Empresarial</h4>")
                        listarcomissaomesrecebidas.ajax.url(`{{ url('/admin/gerente/listagem/empresarial/recebidas/${id}') }}`).load();
                    } else {
                        if($("#listar_a_receber").is(':visible')) {
                            $("#listar_a_receber").slideUp('slow',function(){
                                $("#tabela_principal").slideDown('slow',function(){
                                    $("#title_recebidas").html("<h4>Recebidas Empresarial</h4>")
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/admin/gerente/listagem/empresarial/recebidas/${id}') }}`).load();
                                });
                            })
                        }
                        if($("#tabela_aptos_a_pagar").is(":visible")) {
                            $("#tabela_aptos_a_pagar").slideUp('slow',function(){
                                $("#tabela_principal").slideDown('slow',function(){
                                    $("#title_recebidas").html("<h4>Recebidas Empresarial</h4>")
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/admin/gerente/listagem/empresarial/recebidas/${id}') }}`).load();
                                });
                            })
                        }
                    }








                } else {

                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false
                    }
                }
            }


            $(".empresarial_recebidas").on('click',empresarial_recebidas);

            $("#listar_empresarial_apto").on('click',function(){

                let id = $("#corretor_escolhido").val();
                let mes = $("#mes_folha").val();

                if(id) {
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    $("#listar_empresarial_apto").addClass("ativo");



                    if($("#tabela_principal").is(':visible')) {
                        $("#tabela_principal").slideUp('fast',function(){
                            if(mes == "") {
                                listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/empresarial/confirmadas/${id}') }}`).load();
                                $("#tabela_aptos_a_pagar").slideDown('slow');
                            } else {
                                listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/empresarial/confirmadas/${id}') }}`).load();
                                $("#tabela_aptos_a_pagar").slideDown('slow');
                            }
                        });
                    }
                    if($("#listar_a_receber").is(':visible')) {
                        $("#listar_a_receber").slideUp('fast',function(){
                            listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/empresarial/confirmadas/${id}') }}`).load();
                            $("#tabela_aptos_a_pagar").slideDown('slow');
                        });
                    } else {
                        $("#title_individual_confirmados").html("<h4>Recebidas - Empresarial</h4>");
                        listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/confirmadas/${id}/${mes}') }}`).load();
                    }







                } else {
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false
                    }
                }



            });




            $("#listar_individual_apto").on('click',function(){
                let id = $("#corretor_escolhido").val();
                let mes = $("#mes_folha").val();
                if(id) {

                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").addClass("ativo");
                    if($("#tabela_principal").is(':visible')) {
                        $("#tabela_principal").slideUp('fast',function(){
                            if(mes == "") {
                                listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/confirmadas/${id}') }}`).load();
                                $("#tabela_aptos_a_pagar").slideDown('slow');
                            } else {
                                listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/confirmadas/${id}/${mes}') }}`).load();
                                $("#tabela_aptos_a_pagar").slideDown('slow');
                            }
                        });
                    }
                    if($("#listar_a_receber").is(':visible')) {
                        $("#listar_a_receber").slideUp('fast',function(){
                            listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/confirmadas/${id}') }}`).load();
                            $("#tabela_aptos_a_pagar").slideDown('slow');
                        });
                    } else {
                        $("#title_individual_confirmados").html("<h4>Recebidas - Individual</h4>");
                        listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/confirmadas/${id}/${mes}') }}`).load();
                    }
                } else {

                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");

                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false

                    }



                    // if($("#tabela_principal").is(':visible')) {
                    //     $("#tabela_principal").slideUp('fast',function(){
                    //         $("#tabela_aptos_a_pagar").html("<h1 style='background-color:#123449;color:#FFF;text-align:center;'>Escolher um corretor</h1>");
                    //         $("#tabela_aptos_a_pagar").slideDown('slow');
                    //     });
                    // } else {
                    //     $("#tabela_aptos_a_pagar").html("<h1 style='background-color:#123449;color:#FFF;text-align:center;'>Escolher um corretor</h1>");
                    //     $("#tabela_aptos_a_pagar").slideDown('slow');
                    // }



                }



            });



            $("body").on('change','.comissao_pagando',function(){

                let id = $(this).attr('data-id');
                let valor = $(this).val();
                let valor_plano = $(this).closest("tr").children("td:nth-child(7)").text();


                let self = $(this);
                $.ajax({
                    url:"{{route('gerente.mudar.valor.pago')}}",
                    method:"POST",
                    data:"id="+id+"&valor="+valor+"&valor_plano="+valor_plano,
                    success:function(res) {
                        self.closest("tr").find(".comissao_paga_change").val(res.porcentagem);
                        self.closest("tr").children("td:nth-child(10)").text(res.valor);
                    }
                });
            });

            $("body").on('change',".comissao_paga_change",function(){
                let id = $("#escolher_vendedor").val();
                let valor = $(this).val();
                let valor_plano = $(this).attr('data-valor-plano');
                let default_corretor = $(this).attr('data-id');
                $.ajax({
                    url:"{{route('gerente.mudar.valor.corretor')}}",
                    method:"POST",
                    data:"id="+id+"&valor="+valor+"&valor_plano="+valor_plano+"&default_corretor="+default_corretor+"&acao=porcentagem",
                    success:function(res) {
                        $(".comissao_pagando").val(res.valor);
                        $(".comissao_paga_change").val(res.porcentagem);
                        listarcomissaomesrecebidas.ajax.reload();
                    }
                });
            });

            function empresarial_a_receber() {
                let id = $("#corretor_escolhido").val();
                $("#listar_individual_apto").removeClass("ativo");
                $("#listar_coletivo_apto").removeClass("ativo");
                $(".individual_recebidas").removeClass('ativo');
                $(".coletivo_recebidas").removeClass('ativo');
                $(".individual_a_receber").removeClass('ativo');
                $(".coletivo_a_receber").removeClass('ativo');
                $(this).addClass('ativo');
                if(id) {
                    if($("#listar_a_receber").is(":visible")) {
                        $("#title_comissao_diferente").html("<h4>A Receber Empresarial</h4>")
                        listarcomissaomesdfirente.ajax.url(`{{ url('/admin/gerente/empresarial/listar/${id}') }}`).load();
                    } else {
                        if($("#tabela_principal").is(":visible")) {
                            $("#tabela_principal").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Empresarial</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/admin/gerente/empresarial/listar/${id}') }}`).load();
                                });
                            });
                        }
                        if($("#tabela_aptos_a_pagar").is(":visible")) {
                            $("#tabela_aptos_a_pagar").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Empresarial</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/admin/gerente/empresarial/listar/${id}') }}`).load();
                                });
                            });
                        }
                    }
                } else {
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false
                    }
                }
            }





            $(".empresarial_a_receber").on('click',empresarial_a_receber);

            $("body").on('change',".pagar_comissao",function(){
                


                let id = $(this).attr('id');
                id_confirmados.push(id);

                $("#valores_confirmados").val(id_confirmados);
                let qtd_individual = parseInt($("#total_quantidade_individual").text());
                let qtd_coletivo = parseInt($("#total_quantidade_coletivo").text());
                let qtd_empresarial = parseInt($("#total_quantidade_empresarial").text());


                let plano = $(this).attr('data-plano');
                let comissao_recebida = 0;
                let comissao_pagando = $(this).closest('tr').find('.comissao_pagando').val().replace(/\./g,'').replace(',', '.').trim();

                if(comissao_pagando == "") {
                    comissao_recebida = $(this).closest('tr').find('.comissao_recebida').text().replace("R$","").replace(/\./g,'').replace(',', '.').trim();
                } else {
                    comissao_recebida = $(this).closest('tr').find('.comissao_pagando').val().replace(/\./g,'').replace(',', '.').trim();
                }

                if(plano == 1) {
                    let valor_total_individual = parseFloat($("#valor_total_individual").text().replace("R$","").replace(/\./g,'').replace(',', '.').trim());
                    qtd_individual += 1;
                    $("#total_quantidade_individual").text(qtd_individual);
                    let total_individual = valor_total_individual + parseFloat(comissao_recebida);
                    $("#valor_total_individual").text(total_individual.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
                } else if(plano == 3) {
                    let valor_total_coletivo = parseFloat($("#valor_total_coletivo").text().replace("R$","").replace(/\./g,'').replace(',', '.').trim());
                    qtd_coletivo += 1;
                    $("#total_quantidade_coletivo").text(qtd_coletivo);
                    let total_coletivo = valor_total_coletivo + parseFloat(comissao_recebida);
                    $("#valor_total_coletivo").text(total_coletivo.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
                } else {

                    let valor_total_empresarial = parseFloat($("#valor_total_empresarial").text().replace("R$","").replace(/\./g,'').replace(',', '.').trim());
                    qtd_empresarial += 1;
                    $("#total_quantidade_empresarial").text(qtd_empresarial);
                    let total_empresarial = valor_total_empresarial + parseFloat(comissao_recebida);
                    $("#valor_total_empresarial").text(total_empresarial.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));

                }


                if($(this).val() == 2) {
                    let formatar_desconto = 0;
                    let formatar_desconto_campo = 0;
                    let somar_desconto_campo = 0;    
                    if($("#valor_total_desconto_vendedor").val()) {
                        desconto = $(this).closest("tr").children("td:nth-child(11)").text().replace(",","."); 
                        formatar_desconto_campo = $("#valor_total_desconto_vendedor").val().replace(",",".");
                        somar_desconto_campo = parseFloat(formatar_desconto_campo) + parseFloat(desconto);
                        let somar_desconto_campo_formatado = 0;
                        somar_desconto_campo_formatado = somar_desconto_campo.toLocaleString('pt-BR',{minimumFractionDigits:2});
                        $("#valor_total_desconto_vendedor").val(somar_desconto_campo_formatado);
                    } else {
                        $("#valor_total_desconto_vendedor").val($(this).closest("tr").children("td:nth-child(11)").text());                      
                    }
                    $(this).addClass('pagar');
                    var linha = $(this).closest('tr');
                    linha.slideUp('fast');

                    if($("#comissao_vendedor").val()) {
                        let valor_atual = $("#comissao_vendedor").val().replace(",",".")
                        valor_atual = parseFloat(valor_atual);
                        valor_atual += parseFloat(comissao_recebida);
                        f =  valor_atual.toLocaleString('pt-BR',{minimumFractionDigits:2});
                            

                        

                        $("#comissao_vendedor").val(f);
                        // let total = $(".total_a_pagar").text().replace("R$","").replace(/\./g,'').replace(',', '.').trim();
                        // let total_numero = parseFloat(total);
                        // let desconto_tste = $("#valor_total_desconto_vendedor").val();
                        // let resultado = total_numero + parseFloat(comissao_recebida);
                        // if(desconto_tste != 0) {
                        //     resultado = resultado - desconto_tste;       
                        // }
                        // ff = parseFloat(resultado).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}).replace("R$","").trim();
                        // $(".total_a_pagar").html(ff);
                        // let valor_desconto = parseFloat($(this).closest("tr").children("td:nth-child(11)").text().replace(',', '.'));
                        // if(valor_desconto != 0) {
                        //     //console.log(valor_desconto);
                        //     // let valor_desconto_campo = parseFloat($("#valor_total_desconto").val());
                        //     // let total_desconto = valor_desconto + valor_desconto_campo;
                        //     // $("#valor_total_desconto_vendedor").val(total_desconto);
                        // }
                    } else {
                        

                        // console.log(comissao_recebida);
                        // f = parseFloat(comissao_recebida);
                        $("#comissao_vendedor").val($(this).closest("tr").find(".comissao_pagando").val());
                        // let total = $(".total_a_pagar").text().replace("R$","").replace(/\./g,'').replace(',', '.');
                        // let total_numero = parseFloat(total);
                        // let valor_total = total_numero + parseFloat(comissao_recebida);
                        //ff = parseFloat(valor_total).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}).replace("R$","").trim();
                        //$(".total_a_pagar").html(ff);
                    }
                    let mes = $("#mes_folha option:selected").val();
                    






                    
                } else {
                    $(this).removeClass('pagar');
                }


                /****** Calcular o Total ***/
                $("#comissao_vendedor").val();

                

                let salario_vendedor_campo = $("#salario_vendedor").val() != "" || $("#salario_vendedor").val() != 0 ? $("#salario_vendedor").val().replace(/\./g, "") : parseFloat(0);
                let salario_vendedor_campo_convertido = salario_vendedor_campo != 0 ? parseFloat(salario_vendedor_campo.replace(",", ".")) : 0;


                let comissao_vendedor_campo = $("#comissao_vendedor").val() != "" || $("#comissao_vendedor").val() != 0 ? $("#comissao_vendedor").val().replace(/\./g, "") : parseFloat(0);
                let comissao_vendedor_campo_convertido = parseFloat(comissao_vendedor_campo.replace(",", "."));

                let premiacao_vendedor_campo = $("#premiacao_vendedor").val() != "" || $("#premiacao_vendedor").val() != 0 ? $("#premiacao_vendedor").val().replace(/\./g, "") : parseFloat(0);
                let premiacao_vendedor_campo_convertido = premiacao_vendedor_campo != 0 ? parseFloat(premiacao_vendedor_campo.replace(",",".")) : 0;
                
                let valor_total_desconto_vendedor = $("#valor_total_desconto_vendedor").val() != "" || $("#valor_total_desconto_vendedor").val() != 0 ? $("#valor_total_desconto_vendedor").val().replace(/\./g, "") : parseFloat(0);
                let valor_total_desconto_vendedor_convertido = valor_total_desconto_vendedor != 0 ? parseFloat(valor_total_desconto_vendedor.replace(",",".")) : 0;


                let somar_ganhos = salario_vendedor_campo_convertido + comissao_vendedor_campo_convertido + premiacao_vendedor_campo_convertido;
                somar_ganhos = parseFloat(somar_ganhos);

                let subtrair_desconto = somar_ganhos - valor_total_desconto_vendedor_convertido;
                let subtrair_desconto_formatado = subtrair_desconto.toLocaleString('pt-BR',{minimumFractionDigits:2})
                $("#total_campo_vendedor").val(subtrair_desconto_formatado);
                $.ajax({
                    url:"{{route('gerente.aptar.pagamento')}}",
                    method:"POST",
                    data:"id="+id+"&mes="+mes+"&desconto="+desconto,
                    success:function(res) {
                        console.log(res);
                    }
                });

            });


            $("#salario_vendedor").on('change',function(){

            });





            $("#listar_coletivo_apto").on('click',function(){
                


                let id = $("#corretor_escolhido").val();
                let mes = $("#mes_folha").val();
                if(id) {

                    $(".listar li").removeClass("ativo");
                    $("#listar_coletivo_apto").addClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    if($("#tabela_principal").is(':visible')) {
                        $("#tabela_principal").slideUp('fast',function(){
                            if(mes == "") {
                                $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                                listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/coletivo/confirmadas/${id}') }}`).load();
                                $("#tabela_aptos_a_pagar").slideDown('slow');
                            } else {
                                $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                                listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/coletivo/confirmadas/${id}/${mes}') }}`).load();
                                $("#tabela_aptos_a_pagar").slideDown('slow');
                            }
                        });
                    } else {
                        $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                        listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/coletivo/confirmadas/${id}/${mes}') }}`).load();
                    }
                    if($("#listar_a_receber").is(':visible')) {
                        $("#listar_a_receber").slideUp('fast',function(){
                            $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                            listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/coletivo/confirmadas/${id}') }}`).load();
                            $("#tabela_aptos_a_pagar").slideDown('slow');
                        });
                    } else {
                        $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                        listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/coletivo/confirmadas/${id}/${mes}') }}`).load();
                    }
                } else {
                    $(".listar li").removeClass("ativo");
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false

                    }
                }
            });

            $("body").on('click','.btn_usuario',function(){
                $('#exampleModal').modal('hide')
                let comissao =  $("#comissao_vendedor").val();
                let salario = $(".salario_usuario_vendedor").val();
                let premiacao = $("#premiacao_vendedor").val();
                let desconto = $("#valor_total_desconto_vendedor").val();
                let user_id = $("#corretor_escolhido").val();
                let mes = $("#mes_folha").val();
                let total_a_pagar = $(".total_campo_vendedor").val();
                let id_confirmados = $("#valores_confirmados").val();
                $.ajax({
                    url:"{{route('gerente.finalizar.pagamento')}}",
                    method:"POST",
                    data:
                        "desconto="
                        +desconto+
                        "&comissao="
                        +comissao+
                        "&salario="
                        +salario+"" +
                        "&premiacao="
                        +premiacao+
                        "&user_id="
                        +user_id+
                        "&mes="
                        +mes+
                        "&total="
                        +total_a_pagar+
                        "&id="+id_confirmados,

                    success:function(res) {
                        console.log(res);
                        const select = $("#escolher_vendedor");
                        select.html('<option value="" class="text-center">--Corretores--</option>');
                        $.each(res.users_aptos, function(index, corretor) {
                            select.append($("<option>").attr("value", corretor.id).text(corretor.name));
                        });
                        $("#salario_vendedor").val('');
                        $("#comissao_vendedor").val('');
                        $("#premiacao_vendedor").val('');
                        $("#corretor_escolhido").val('');
                        $('#valor_total_desconto_vendedor').val('');
                        $('#total_campo_vendedor').val('');
                        //$("#mes_folha").val('');
                        //$(".total_a_pagar").text('0,00');
                        $("#valores_confirmados").val('');
                        $("#list_user").html(res.view);
                        $("#escolher_vendedor").val('');
                        $("#total_quantidade_individual").text(0);
                        $("#total_quantidade_coletivo").text(0);
                        $("#total_quantidade_empresarial").text(0);
                        $("#valor_total_individual").text(0);
                        $("#valor_total_coletivo").text(0);
                        $("#valor_total_empresarial").text(0);
                        $("#total_campo").val('');
                        $("#mes_folha").attr("disabled",true);
                        $(".individual_recebidas").removeClass('ativo');
                        $(".coletivo_recebidas").removeClass('ativo');
                        $(".empresarial_recebidas").removeClass('ativo');
                        $(".individual_a_receber").removeClass('ativo');
                        $(".coletivo_a_receber").removeClass('ativo');
                        $(".empresarial_a_receber").removeClass("ativo");
                        listarcomissaomesdfirente.ajax.reload(function() {
                            listarcomissaomesdfirente.clear().draw();
                        });
                        listarcomissaomesrecebidas.ajax.reload(function() {
                            listarcomissaomesrecebidas.clear().draw();
                        });
                        listaraptosapagar.ajax.reload(function() {
                            listaraptosapagar.clear().draw();
                        });
                        id_confirmados = [];
                        finalizarMes();
                    }
                });


            });




           

            function individual_recebidas() {
                let id = $("#corretor_escolhido").val();
                $("#listar_individual_apto").removeClass('ativo');
                $("#listar_coletivo_apto").removeClass('ativo');
                $(".coletivo_recebidas").removeClass('ativo');
                $(".individual_a_receber").removeClass('ativo');
                $(".coletivo_a_receber").removeClass('ativo');
                $(".empresarial_recebidas").removeClass('ativo');
                $(this).addClass('ativo');
                if(id) {
                    $("#listar_individual_apto").removeClass("ativo");
                    if($("#tabela_principal").is(":visible")) {
                        $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                        listarcomissaomesrecebidas.ajax.url(`{{ url('/admin/gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                    } else {
                        if($("#listar_a_receber").is(':visible')) {
                            $("#listar_a_receber").slideUp('slow',function(){
                                $("#tabela_principal").slideDown(1000,function(){
                                    $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/admin/gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                                });
                            });
                        }
                        if($("#tabela_aptos_a_pagar").is(":visible")) {
                            $("#tabela_aptos_a_pagar").slideUp('slow',function(){
                                $("#tabela_principal").slideDown(1000,function(){
                                    $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/admin/gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                                });
                            });
                        }
                    }
                } else {
                    $(".listar li").removeClass("ativo");
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false

                    }
                }
            }
            $("body").on('click','.individual_recebidas',individual_recebidas);


            var data = new Date();
            var mes = String(data.getMonth());
            let meses = ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];
            var listarcomissaomesdfirente = $(".listarcomissaomesdiferente").DataTable({
                dom: '<"d-flex justify-content-between"<"#title_comissao_diferente"><"estilizar_search"f>><t><"d-flex justify-content-between align-items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":`{{ url('/admin/gerente/tabelas/vazias') }}`,
                    "dataSrc": "",
                },
                "lengthMenu": [50,100,150,200,300,500],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                keys: true,
                columns: [
                    {data:"administradora",name:"administradora",width:"1%"},
                    {data:"data_criacao",name:"data_criacao",
                        "createdCell": function(td, cellData, rowData, row, col) {
                            let datas = cellData.split(" ")[0].split("-").reverse().join("/");
                            $(td).html(datas);
                        },width:"1%"
                    },
                    {data:"orcamento",name:"orcamento",width:"1%"},
                    {data:"cliente",name:"cliente",width:"66%",
                        "createdCell": function(td, cellData, rowData, row, col) {
                            let palavra = cellData.split(" ");
                            let nomeAbreviado = palavra[0] + " " + palavra[1];
                            if(palavra.length >= 3) {
                                $(td).html(nomeAbreviado).attr("title", cellData);
                                //$(td).html(palavra[0]+" "+palavra[1]+" "+palavra[2])
                            }
                        }
                    },
                    {data:"parcela",name:"parcela",className: 'dt-center',width:"1%"},
                    {data:"valor_plano_contratado",name:"valor_plano_contratado",width:"15%",
                        render: $.fn.dataTable.render.number('.',',',2,'')
                    },
                    {data:"data",name:"data",className: 'dt-center',width:"1%"},
                    {data:"data_baixa",name:"data_baixa",
                        "createdCell": function(td, cellData, rowData, row, col) {
                            let datas = cellData.split("-").reverse().join("/");
                            $(td).html(datas);
                        },width:"1%"
                    },

                    {data:"porcentagem_parcela_corretor",name:"porcentagem_parcela_corretor",width:"18%",
                        "createdCell":function(td, cellData, rowData, row, col) {

                            $(td).html('<input type="text"  data-id='+rowData.id+' value='+cellData+' name="comissao_paga_change" class="comissao_paga_change" style="width:100%;" />')
                        }
                    },
                    {data:"id",name:"comissao_pagando",render: $.fn.dataTable.render.number('.',',',2,'R$ '),width:"20%",
                        "createdCell":function(td, cellData, rowData, row, col) {
                            let valor_comisao = parseFloat(rowData.valor).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            $(td).html('<input type="text" value='+valor_comisao+' data-id='+cellData+' name="comissao_pagando" class="comissao_pagando" style="width:40px;" />')
                        }
                    },
                    {data:"desconto",name:"desconto",width:"2%",
                        "createdCell":function(td, cellData, rowData, row, col) {
                            let descondo_calc = parseFloat(cellData).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            $(td).html(descondo_calc)
                        }
                    },
                    {
                        data:"id",name:"id",width:"2%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let selected = $('<select />', {
                                name: 'pagar_agora',
                                class:"pagar_comissao",
                                'id':cellData,
                                'data-plano':rowData.plano,
                                append : [
                                    $('<option />', {value : "1", text : "Não"}),
                                    $('<option />', {value : "2", text : "Sim"}),
                                ]
                            })
                            $(td).html(selected);
                        }
                    }
                ],
                "initComplete": function( settings, json ) {
                    $('#title_comissao_diferente').html("<h4>A Receber Individual</h4>");
                }
            });

            $("body").on('keyup','.comissao_pagando',function(){
                $(this).mask('#.##0,00', {reverse: true});
            });

            $('.salario_usuario').mask("#.##0,00", {reverse: true});
            $('.premiacao_usuario').mask("#.##0,00", {reverse: true});

            $("#salario_vendedor").mask("#.##0,00", {reverse: true});
            $("#premiacao_vendedor").mask("#.##0,00", {reverse: true});

            $("#body").find(".comissao_pagando").mask("#.##0,00", {reverse: true});
            $("body").find(".change_plan").mask("#.##0,00", {reverse: true});

            $("body").on('keyup','.change_plan',function(){
                $(this).mask('#.##0,00', {reverse: true});
            });






            $(".premiacao_usuario_vendedor").on('change',function(){

                let comissao_numerica = 0;
                if($("#comissao_vendedor").val() != "") {
                    let comissao = $("#comissao_vendedor").val().replace("R$","").replace(/\./g,'').replace(',', '.').trim()
                    comissao_numerica = parseFloat(comissao);
                }

                let valor_salario = 0;
                if($('#salario_vendedor').val() != "") {
                    valor_salario = parseFloat($('#salario_vendedor').val().replace(/\./g,'').replace(".","").replace(',', '.'));
                }
                
                let desconto = 0;
                if($("#valor_total_desconto_vendedor").val() != "") {
                    desconto = parseFloat($('#valor_total_desconto_vendedor').val().replace(',', '.'));    
                }
                
                

                let valor = $(this).val().replace(/\./g,'').replace(',', '.');
                // console.log(valor);
                let valor_numerico = parseFloat(valor);
                // let total = $(".total_a_pagar").text().trim().replace("R$","").replace(/\./g,'').replace(',', '.');
                // let total_numero = parseFloat(total);

                let valor_input = (valor_numerico + comissao_numerica + valor_salario) - desconto;
                f = valor_input.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}).replace("R$","").trim();
                


                $(".total_campo_vendedor").val(f);
            });

            $(".premiacao_usuario_vendedor").on('keydown',function(event){
                if (event.key === "Tab") {
                    
                    event.preventDefault();
                    $("input[name='desconto_vendedor']").removeAttr('disabled');
                    $("input[name='desconto_vendedor']").focus().prop('disabled',true);
                }
            });







            $(".salario_usuario_vendedor").on('change',function(){

                



                let comissao_numerica = 0;
                if($("#comissao_vendedor").val() != "") {

                    let comissao = $("#comissao_vendedor").val().replace("R$","").replace(/\./g,'').replace(',', '.').trim()
                    comissao_numerica = parseFloat(comissao);
                }

                let valor_premiacao = 0;
                if($('#premiacao_vendedor').val() != "") {
                    valor_premiacao = parseFloat($('#premiacao_vendedor').val().replace(/\./g,'').replace(',', '.'));
                }
                
                let desconto = 0;
                if($("#valor_total_desconto_vendedor").val() != "") {
                    desconto = parseFloat($('#valor_total_desconto_vendedor').val().replace(',', '.'));    
                }
                

                let valor = $(this).val().replace(/\./g,'').replace(',', '.');
                let valor_numerico = parseFloat(valor);

                // let total = $(".total_a_pagar").text().replace("R$","").replace(/\./g,'').replace(',', '.');
                // let total_numero = parseFloat(total);

                // //let valor_input = total_numero + valor_numerico;

                let valor_input = (comissao_numerica + valor_premiacao + valor_numerico) - desconto;

                f = valor_input.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}).replace("R$","").trim();
                $("#total_campo_vendedor").val(f);
            });

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
                footerCallback: function (row, data, start, end, display) {}
            });

            $("body").on('click','.criar_pdf',function(){
                let comissao =  $("#comissao").val();
                let salario = $(".salario_usuario").val();
                let premiacao = $(".premiacao_usuario").val();
                let user_id = $(this).attr('data-id');
                let mes = $("#mes_folha option:selected").val();
                let total_a_pagar = $(".total_a_pagar").text().replace("R$","").trim();
                let id_confirmados = $("#valores_confirmados").val();
                $.ajax({
                    url:"{{route('gerente.finalizar.criarpdf')}}",
                    method:"POST",
                    data:
                        "comissao="
                        +comissao+
                        "&salario="
                        +salario+"" +
                        "&premiacao="
                        +premiacao+
                        "&user_id="
                        +user_id+
                        "&mes="
                        +mes+
                        "&total="
                        +total_a_pagar+
                        "&id="+id_confirmados,

                    // success:function(res) {
                    //     console.log(res);
                    // }


                    xhrFields: {
                        responseType: 'blob'
                    },
                    success:function(blob,status,xhr,ppp) {
                        var filename = "";
                        var disposition = xhr.getResponseHeader('Content-Disposition');
                        if (disposition && disposition.indexOf('attachment') !== -1) {
                            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            var matches = filenameRegex.exec(disposition);
                            if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                        }
                        if (typeof window.navigator.msSaveBlob !== 'undefined') {
                            window.navigator.msSaveBlob(blob,filename);
                        } else {
                            $(".salario_usuario").val("");
                            $("#comissao").val("");
                            $("#premiacao").val("");
                            $("#valor_total_individual").val('0.00');
                            $("#valor_total_coletivo").val('0.00');
                            $("#valor_total_empresarial").val('0.00');

                            var URL = window.URL || window.webkitURL;
                            var downloadUrl = URL.createObjectURL(blob);
                            if (filename) {
                                var a = document.createElement("a");
                                if (typeof a.download === 'undefined') {
                                    window.location.href = downloadUrl;
                                } else {
                                    a.href = downloadUrl;
                                    a.download = filename;
                                    document.body.appendChild(a);
                                    a.click();
                                }
                            } else {
                                window.location.href = downloadUrl;
                            }
                            setTimeout(function () {
                                URL.revokeObjectURL(downloadUrl);
                            },100);
                        }
                    }
                });
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



            $('form[name="data_da_baixa"]').on('submit',function(){
                let dados = $(this).serialize();
                $.ajax({
                    url:"{{route('gerente.mudar_status')}}",
                    method:"POST",
                    data:dados,
                    success:function(res) {
                        
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

        .tamanho_de_25 {
            height: 25px;
        }



        .dsnone {display:none;}
        .aba_comissao_container {
            display:flex;
            align-content: flex-start;
            position:relative;
        }

        .dataTables_wrapper .dataTables_wrapper .dataTables_scrollBody table.dataTable {
    padding: 0;
}

.dataTables_wrapper .dataTables_wrapper .dataTables_scrollBody td, 
.dataTables_wrapper .dataTables_wrapper .dataTables_scrollBody th {
    padding: 0;
}


        .menu_aba_comissao {flex-basis: 12%;margin-right: 1%;height:130px;}
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

        .ativo {background-color:#FFF !important;color: #000 !important;}

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

        th { font-size: 0.78em !important; }
        td { font-size: 0.65em !important; }

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


        .user_destaque_ativo {
            background-color:rgb(255,255,255);
            color:black !important;
        }

    </style>
@stop




