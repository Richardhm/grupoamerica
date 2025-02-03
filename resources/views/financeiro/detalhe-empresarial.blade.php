@extends('adminlte::page')
@section('title', 'Gerenciavel')
@section('plugins.Sweetalert2',true)
@section('content_top_nav_right')
    <!-- <li class="nav-item mostrar_comissao"><a href="" class="nav-link div_info text-white"><i class='fas fa-eye'></i></a></li> -->
    <li class="nav-item"><a class="nav-link text-white" href="{{route('orcamento.search.home')}}">Tabela de Preço</a></li>
    <li class="nav-item"><a class="nav-link text-white" href="{{route('home.administrador.consultar')}}">Consultar</a></li>

    <a class="nav-link" data-widget="fullscreen" href="#" role="button"><i class="fas fa-expand-arrows-alt text-white"></i></a>
@stop

@section('content_header')
    <h1 class=" border-bottom border-dark">Histórico</h1>
@stop

@section('content')




<div class="modal fade" id="dataBaixaEmpresarialModal" tabindex="-1" role="dialog" aria-labelledby="dataBaixaEmpresarialLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dataBaixaEmpresarialLabel">Data Da Baixa?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" name="data_da_baixa_empresarial" id="data_da_baixa_empresarial" method="POST">
                    <input type="date" name="date_baixa_empresarial" id="date_baixa_empresarial" class="form-control form-control-sm">
                    <div id="error_data_baixa_empresarial">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
            </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDiferencaEntreValores" tabindex="-1" role="dialog" aria-labelledby="modalDiferencaEntreValoresLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDiferencaEntreValoresLabel">Data de Cadastro:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="text-center">
                <p>Diferença entre valores: <span class="diferenca_entre_valores"></span>   </p>
            </div>
            <form action="" method="POST" name="update_desconto_corretor_corretora">
                @csrf
                <div style="display:flex;justify-content: space-around;">
                    <div style="display:flex;flex-direction: column;">
                        <span>Corretora:</span>
                        <input type="text" id="desconto_corretora_valores" name="desconto_corretora">
                    </div>
                    <div style="display:flex;flex-direction: column;">
                        <span>Corretor</span>
                        <input type="text" id="desconto_corretor_valores" name="desconto_corretor" readonly>
                    </div>
                </div>

                <input type="hidden" name="id_contrato" id="contrato_id_update" value="{{$dados->id}}">

                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary">Salvar Valores</button>
                </div>

            </form>
            </div>
        </div>
    </div>











    <input type="hidden" id="contrato" value="{{$dados->id}}" />

    <input type="hidden" id="financeiro" value="{{$dados->financeiro->id}}" />


    <main class="container_full_cards">

        <section class="p-1" style="background-color:#123449;flex-basis:48%;">

                <div class="d-flex mb-2">

                    <div style="flex-basis:33%;">
                        <span class="text-white" style="font-size:0.81em;">Vendedor:</span>
                        <input type="text" value="{{$dados->vendedor}}" id="vendedor_view_empresarial" class="form-control form-control-sm" readonly>
                    </div>

                    <div style="flex-basis:33%;margin:0 1%;">
                        <span class="text-white" style="font-size:0.81em;">Plano:</span>
                        <input type="text" value="{{$dados->plano}}" id="plano_view_empresarial" class="form-control  form-control-sm" readonly>
                    </div>

                    <div style="flex-basis:33%;margin-top:4px;">
                        <span class="text-white" style="font-size:0.81em;display:flex;">
                            <span style="flex-basis:60%;">Tabela Origem:</span>
                            <div class="container_edit" style="flex-basis:40%;font-size:0.9em;margin-top:1px;">
                                <i class="fas fa-edit text-white editar_btn_empresarial"></i>
                            </div>
                        </span>
                        <input type="text" value="{{$dados->tabela_origem}}" id="tabela_origem_view_empresarial" class="form-control  form-control-sm" readonly>
                    </div>
                </div>

                <div class="d-flex mb-2">
                    <div style="flex-basis:57%;">
                        <span class="text-white" style="font-size:0.81em;">Razão Social:</span>
                        <input type="text" value="{{$dados->razao_social}}" id="razao_social_view_empresarial" class="form-control form-control-sm" readonly>
                    </div>
                    <div style="flex-basis:33%;margin:0 1%;">
                        <span class="text-white" style="font-size:0.81em;">CNPJ:</span>
                        <input type="text" id="cnpj_view" value="{{$dados->cnpj}}" class="form-control form-control-sm" readonly>
                    </div>
                    <div style="flex-basis:8%;">
                        <span class="text-white" style="font-size:0.81em;">Vidas:</span>
                        <input type="text" id="qtd_vidas" value="{{$dados->quantidade_vidas}}" class="form-control form-control-sm" readonly>
                    </div>

                </div>

                <div class="d-flex mb-2">

                    <div style="flex-basis:30%;">
                        <span class="text-white" style="font-size:0.81em;">Telefone:</span>
                        <input type="text" id="telefone_corretor_view_empresarial" value="{{$dados->telefone}}" class="form-control form-control-sm" readonly>
                    </div>

                    <div style="flex-basis:30%;margin:0 1%;">
                        <span class="text-white" style="font-size:0.81em;">Celular:</span>
                        <input type="text" id="celular_corretor_view_empresarial" value="{{$dados->celular}}" class="form-control form-control-sm" readonly>
                    </div>

                    <div style="flex-basis:40%;">
                        <span class="text-white" style="font-size:0.81em;">Email:</span>
                        <input type="text" id="email_odonto_view_empresarial" value="{{$dados->email}}" class="form-control form-control-sm" readonly>
                    </div>

                </div>

                <div class="d-flex mb-2">

                    <div style="flex-basis:30%;margin-right:1%;">
                        <span class="text-white" style="font-size:0.81em;">Responsavel:</span>
                        <input type="text" id="nome_corretor_view_empresarial" value="{{$dados->responsavel}}" class="form-control form-control-sm" readonly>
                    </div>

                    <div style="flex-basis:10%;margin-right:1%;">
                        <span class="text-white" style="font-size:0.81em;">UF:</span>
                        <input type="text" id="uf_cliente_view_empresarial" value="{{$dados->uf}}" class="form-control form-control-sm" readonly>
                    </div>

                    <div style="flex-basis:24%;margin-right:1%;">
                        <span class="text-white" style="font-size:0.81em;">Cidade:</span>
                        <input type="text" id="cidade_saude_view_empresarial" value="{{$dados->cidade}}" class="form-control form-control-sm" readonly>
                    </div>

                    <div style="flex-basis:38%;">
                        <span class="text-white" style="font-size:0.81em;">Plano Contratado:</span>
                        <input type="text" id="plano_contratado_corretor_view_empresarial" value="{{$texto_empresarial}}" class="form-control form-control-sm" readonly>
                    </div>

                </div>

                <div class="d-flex mb-2">
                    <div style="flex-basis:24%;">
                        <span class="text-white" style="font-size:0.81em;">Cód.Corretora:</span>
                        <input type="text" id="cod_corretora_view_empresarial" value="{{$dados->codigo_corretora}}" class="form-control form-control-sm" readonly>
                    </div>

                    <div style="flex-basis:24%;margin:0 1%;">
                        <span class="text-white" style="font-size:0.81em;">Codigo Saude:</span>
                        <input type="text" id="cod_saude_view_empresarial" value="{{$dados->codigo_saude}}" class="form-control form-control-sm" readonly>
                    </div>

                    <div style="flex-basis:24%;margin-right: 1%;">
                        <span class="text-white" style="font-size:0.81em;">Codigo Odonto:</span>
                        <input type="text" id="cod_odonto_view_empresarial" value="{{$dados->codigo_odonto}}" class="form-control form-control-sm" readonly>
                    </div>

                    <div style="flex-basis:25%;">
                        <span class="text-white" style="font-size:0.81em;">Senha Cliente:</span>
                        <input type="text" id="senha_cliente_view_empresarial" value="{{$dados->senha_cliente}}" class="form-control form-control-sm" readonly>
                    </div>

                </div>

                <div class="d-flex mb-2">
                    <div style="flex-basis:25%;margin-right:1%;">
                        <span class="text-white" style="font-size:0.81em;">Valor Saude:</span>
                        <input type="text" id="valor_plano_saude_view" value="{{number_format($dados->valor_plano_saude,2,',','.')}}" class="form-control form-control-sm" readonly>
                    </div>
                    <div style="flex-basis:25%;margin-right:1%;">
                        <span class="text-white" style="font-size:0.81em;">Valor Odonto:</span>
                        <input type="text" id="valor_plano_odonto_view" value="{{number_format($dados->valor_plano_odonto,2,',','.')}}" class="form-control form-control-sm" readonly>
                    </div>
                    <div style="flex-basis:25%;margin-right:1%;">
                        <span class="text-white" style="font-size:0.81em;">Total Plano:</span>
                        <input type="text" id="valor_plano_view_empresarial" value="{{number_format($dados->valor_total,2,',','.')}}" class="form-control form-control-sm" readonly>
                    </div>
                    <div style="flex-basis:25%;">
                        <span class="text-white" style="font-size:0.81em;">Taxa Adesão:</span>
                        <input type="text" id="taxa_adesao_view_empresarial" value="{{number_format($dados->taxa_adesao,2,',','.')}}" class="form-control form-control-sm" readonly>
                    </div>
                </div>


                <div class="d-flex mb-1">
                    <div style="flex-basis:24%;margin-right:1%;">
                        <span class="text-white" style="font-size:0.81em;">Plano c/Adesão:</span>
                        <input type="text" id="plano_adesao_view_empresarial" class="form-control form-control-sm" value="{{number_format($dados->valor_total + $dados->taxa_adesao,2,',','.')   }}" readonly>
                    </div>
                    <div style="flex-basis:24%;">
                        <span class="text-white" style="font-size:0.81em;">Valor Boleto:</span>
                        <input type="text" id="valor_boleto_view_empresarial" value="{{number_format($dados->valor_boleto,2,',','.')}}" class="form-control form-control-sm" readonly>
                    </div>
                    <div style="flex-basis:25%;margin:0 1%;">
                        <span class="text-white" style="font-size:0.81em;">Venc. Boleto:</span>
                        <input type="text" id="vencimento_boleto_view_empresarial" value="{{date('d/m/Y',strtotime($dados->vencimento_boleto))}}" class="form-control form-control-sm" readonly>
                    </div>
                    <div style="flex-basis:25%;">
                        <span class="text-white" style="font-size:0.9em;">Data 1º Boleto:</span>
                        <input type="text" id="data_boleto_view_empresarial" value="{{date('d/m/Y',strtotime($dados->data_boleto))}}" class="form-control form-control-sm" readonly>
                    </div>


                    <input type="hidden" id="cliente_id_alvo_empresarial" />
                </div>

                <div class="d-flex">
                    <div style="flex-basis:45%;">

                    </div>
                    <div style="flex-basis:45%;">

                    </div>
                </div>



        </section>

        <div class="modal fade" id="cancelarModal" tabindex="-1" aria-labelledby="cancelarModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelarModalLabel">Cancelados</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST" name="formulario_cancelados" id="formulario_cancelados">
                            <input type="hidden" name="comissao_id_cancelado" id="comissao_id_cancelado" value="{{$dados->comissao->id}}">
                            <div class="form-group">
                                <label for="">Data Baixa:</label>
                                <input type="date" name="date" id="date" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="motivo">Motivo Cancelamento:</label>
                                <select name="motivo" id="motivo" class="form-control">
                                    <option value="">--Escolher o Motivo--</option>

                                    @foreach($motivo_cancelados as $mm)
                                        <option value="{{$mm->id}}">{{$mm->nome}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="obs">Observação:</label>
                                <textarea name="obs" id="obs" cols="30" rows="4" class="form-control"></textarea>
                            </div>

                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>








        <section style="flex-basis:48%;background-color:#123449;color:#FFF;border-radius:5px;">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="text-center mt-1 ml-1">Pagamentos</h5>
                <p class="align-self-center mt-3 mr-2">{{$dados->vendedor}}</p>
            </div>

            <table class="table table-sm h-50" style="margin:0;padding:0;">
                <thead>
                    <tr>
                        <th style="font-size:0.875em;">Parcela</th>
                        <th style="font-size:0.875em;">Contrato</th>
                        <th style="font-size:0.875em;">Vencimento</th>
                        <th style="font-size:0.875em;">Valor</th>
                        <th style="font-size:0.875em;">Baixa</th>

                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_cliente = 0;
                        $total_comissao = 0;
                        $ii=0;
                    @endphp
                    @foreach($dados->comissao->comissoesLancadas as $kk => $cr)
                            @php
                                $ii++;
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
                                        Pag. 1º Parcela
                                    @break;

                                    @case(2)
                                        Pag. 2º Parcela
                                    @break;

                                    @case(3)
                                        Pag. 3º Parcela
                                    @break;

                                    @case(4)
                                        Pag. 4º Parcela
                                    @break;

                                    @case(5)
                                        Pag. 5º Parcela
                                    @break;

                                    @case(6)
                                        Pag. 6º Parcela
                                    @break;


                                @endswitch





                            </td>
                            <td class="text-center" style="font-size:0.875em;">
                                {{$dados->codigo_externo}}

                            </td>
                            <td style="font-size:0.875em;">
                                @if($ii == 1)
                                
                                    {{date('d/m/Y',strtotime($dados->vencimento_boleto))}}  
                                @else
                                    {{date('d/m/Y',strtotime($cr->data))}}
                                @endif
                                
                            
                            </td>
                            <td style="font-size:0.875em;">


                                    <span style="margin-left:10px;">{{number_format($dados->valor_plano,2,",",".")}}</span>

                            </td>
                            <td style="font-size:0.875em;">
                                @if(empty($cr->data_baixa))
                                    <span style="margin-left:20px;">---</span>
                                @else
                                    {{date('d/m/Y',strtotime($cr->data_baixa))}}
                                @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
<!-- <div class="button_empresarial mt-1">
    <button class="btn btn-danger w-50 mr-2 excluir_empresarial">Excluir</button>
    <button class="btn btn-success w-50 next_empresarial">Conferido</button>
</div>   -->


    </main>

    <div class="d-flex mt-3 " style="flex-basis:100%;justify-content: flex-end;">
        <div class="d-flex" style="flex-basis:49%;justify-content: flex-end;">


            @if(isset($dados->comissao->comissaoAtualFinanceiro->parcela) && !empty($dados->comissao->comissaoAtualFinanceiro->parcela) &&  $dados->comissao->comissaoAtualFinanceiro->parcela)

                @switch($dados->comissao->comissaoAtualFinanceiro->parcela)

                    @case(1)
                        <button class="btn btn-danger w-50 mr-1 excluir_empresarial">Excluir</button>
                        <button class="btn btn-success w-50 next">Pagar 1º Parcela</button>
                    @break

                    @case(2)
                        <button class="btn btn-danger w-50 mr-1 cancelar">Cancelar</button>
                        <button class="btn btn-success w-50 next">Pagar 2º Parcela</button>
                    @break

                    @case(3)
                        <button class="btn btn-danger w-50 mr-2 cancelar">Cancelar</button>
                        <button class="btn btn-success w-50 pagamento_adesao next">Pagar 3º Parcela</button>
                    @break

                    @case(4)
                        <button class="btn btn-danger w-50 mr-2 cancelar">Cancelar</button>
                        <button class="btn btn-success w-50 pagamento_adesao next">Pagar 4º Parcela</button>
                    @break

                    @case(5)
                        <button class="btn btn-danger w-50 mr-2 cancelar">Cancelar</button>
                        <button class="btn btn-success w-50 pagamento_adesao next">Pagar 5º Parcela</button>
                    @break

                    @case(6)
                        <button class="btn btn-danger w-50 mr-2 cancelar">Cancelar</button>
                        <button class="btn btn-success w-50 pagamento_adesao next">Pagar 6º Parcela</button>
                    @break


                @endswitch







            @endif

        </div>
    </div>





    <a class="btn btn-block btn-lg mt-3 text-white back" style="background-color:#123449;" href="">Voltar</a>
    <!-- <a href="" class="btn btn-block btn-lg mt-3 text-white" style="background-color:#123449;">Voltar</a> -->
@stop

@section('js')
    <script src="{{asset('js/jquery.mask.min.js')}}"></script>
    <script>
        $(function(){
            $("#cpf").mask('000.000.000-00');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("body").on('click','.cancelar',function(){
                $('#cancelarModal').modal('show')
            });

            $('form[name="formulario_cancelados"]').on('submit',function(){
                $.ajax({
                    url:"{{route('financeiro.contrato.empresarial.cancelados')}}",
                    method:"POST",
                    data:$(this).serialize(),
                    success:function(res) {

                        if(res == "error") {

                        } else {
                            window.location.href = "/admin/financeiro?ac=empresarial";
                        }
                    }
                });
                return false;
            });



            $(".back").on('click',function(){
                window.history.go(-1);
                return false;
            });

            $('.editar_btn_empresarial').on('click',function(){
                let params = $("#razao_social_view_empresarial").prop("readonly");
                if(!params) {
                    adicionarReadonlyEmpresarial();
                } else {
                    removeReadonlyEmpresarial();
                }
            });


            $("body").on('click','.excluir_empresarial',function(){
                //if($(this).attr('data-cliente-excluir-empresarial')) {
                    Swal.fire({
                        title: 'Você tem certeza que deseja realizar essa operação?',
                        showDenyButton: true,
                        confirmButtonText: 'Sim',
                        denyButtonText: `Cancelar`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let id_contrato = $("#contrato").val();
                            $.ajax({
                                url:"{{route('financeiro.excluir.cliente.empresarial')}}",
                                method:"POST",
                                data:"id_contrato="+id_contrato,
                                success:function(res) {
                                    if(res != "error") {
                                        window.location.href = "/admin/financeiro?ac=empresarial";
                                    } else {
                                        Swal.fire('Opss', 'Erro ao excluir o cliente', 'error')
                                    }
                                }
                            });
                        } else if (result.isDenied) {
                            //
                        }
                    })
                //}
               //let excluir_empresarial = $(this).attr('data-cliente-excluir-individual');
               return false;
           });











           $("body").on('click','.next',function(){

                let contrato = $("#contrato").val();
                let financeiro = $("#financeiro").val();
                $.ajax({
                    url:"{{route('financeiro.mudarStatusEmpresarial')}}",
                    data:"financeiro="+financeiro+"&id_contrato="+contrato,
                    method:"POST",
                    success:function(res) {


                        if(res.modal == "abrir_modal_desconto") {
                            $('#modalDiferencaEntreValores').modal('show');
                            $(".diferenca_entre_valores").html("R$ "+res.diferenca);
                        } else if(res == "abrir_modal_empresarial") {
                            $("#dataBaixaEmpresarialModal").modal('show');
                        } else {
                            window.location.href = "/admin/financeiro?ac=empresarial";
                        }




                        // if(res == "abrir_modal_empresarial") {
                        //     $("#dataBaixaEmpresarialModal").modal('show');
                        // } else {
                        //     window.location.href = "/admin/financeiro?ac=empresarial";
                        // }
                        // if(res.modal == "abrir_modal_desconto") {
                        //     $('#modalDiferencaEntreValores').modal('show');
                        //     $(".diferenca_entre_valores").html("R$ "+res.diferenca);
                        //     return false;
                        // } else if(res == "abrir_modal_empresarial") {
                        //     $("#dataBaixaEmpresarialModal").modal('show');
                        // } else {


                        // }

                    }
                });
                return false;
            });

            $('#desconto_corretora_valores').mask("#.##0,00", {reverse: true});
            $("#desconto_corretora_valores").change(function(){
                let valor = parseFloat($(this).val().replace(".","").replace(",","."));
                if(valor == 0.00) valor = 0;

                let total = parseFloat($(".diferenca_entre_valores").text().replace("R$","").replace(",",".").trim());

                let corretor = total - valor;

                let resto_corretor = parseFloat(corretor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                $("#desconto_corretor_valores").val(resto_corretor);
                $("#desconto_corretor").val(resto_corretor);
                $("#desconto_corretora").val(valor);
            });

            $("form[name='data_da_baixa']").on('submit',function(){
                let id_cliente = $("#data_cliente").val();
                let id_contrato = $("#data_contrato").val();
                $.ajax({
                    url:"{{route('financeiro.baixa.data')}}",
                    method:"POST",
                    data: {
                        "id_cliente": id_cliente,
                        "id_contrato": id_contrato,
                        "data_baixa": $("#data_baixa").val(),
                        "comissao_id": $("#comissao_id_baixa").val()
                    },
                    beforeSend:function() {
                        if($("#data_baixa").val() == "") {
                            $("#error_data_baixa").html('<p class="alert alert-danger">O campo data é campo obrigatório</p>');
                            return false;
                        } else {
                            $("#error_data_baixa").html('');
                        }
                    },
                    success:function(res) {
                        //console.log(res);
                        window.location.href = "/admin/financeiro?ac=empresarial";
                        // $(".coletivo_quantidade_em_analise").html(res.qtd_coletivo_em_analise);
                        // $(".coletivo_quantidade_emissao_boleto").html(res.qtd_coletivo_emissao_boleto);
                        // $(".coletivo_quantidade_pagamento_adesao").html(res.qtd_coletivo_pg_adesao);
                        // $(".coletivo_quantidade_pagamento_vigencia").html(res.qtd_coletivo_pg_vigencia);
                        // $(".coletivo_quantidade_segunda_parcela").html(res.qtd_coletivo_02_parcela);
                        // $(".coletivo_quantidade_terceira_parcela").html(res.qtd_coletivo_03_parcela);
                        // $(".coletivo_quantidade_quarta_parcela").html(res.qtd_coletivo_04_parcela);
                        // $(".coletivo_quantidade_quinta_parcela").html(res.qtd_coletivo_05_parcela);
                        // $(".coletivo_quantidade_sexta_parcela").html(res.qtd_coletivo_06_parcela);
                        // $(".quantidade_coletivo_finalizado").html(res.qtd_coletivo_finalizado);
                        // table.ajax.reload();
                        // limparFormulario();
                        // $('#dataBaixaModal').modal('hide');
                        // $('#data_baixa').val('');
                    }
                })
                return false;
            });

            $("form[name='data_da_baixa_empresarial']").on('submit',function(){
                let id_contrato = $('#contrato').val();
                let date_baixa_empresarial = $("input[name='date_baixa_empresarial']").val();
                $.ajax({
                    url:"{{route('financeiro.baixa.data.empresarial')}}",
                    method:"POST",
                    data:"id_contrato="+id_contrato+"&data_baixa="+date_baixa_empresarial,
                    beforeSend:function() {
                        if($("#date_baixa_empresarial").val() == "") {
                            $("#error_data_baixa_empresarial").html('<p class="alert alert-danger">O campo data é campo obrigatório</p>');
                            return false;
                        } else {
                            $("#error_data_baixa_empresarial").html('');
                        }

                    },
                    success:function(res) {
                        //console.log(res);
                        window.location.href = "/admin/financeiro?ac=empresarial";
                        // $('#dataBaixaEmpresarialModal').modal('hide');
                        // $(".empresarial_quantidade_em_analise").html(res.qtd_empresarial_em_analise);
                        // $(".empresarial_quantidade_1_parcela").html(res.qtd_empresarial_01_parcela);
                        // $(".empresarial_quantidade_2_parcela").html(res.qtd_empresarial_02_parcela);
                        // $(".empresarial_quantidade_3_parcela").html(res.qtd_empresarial_03_parcela);
                        // $(".empresarial_quantidade_4_parcela").html(res.qtd_empresarial_04_parcela);
                        // $(".empresarial_quantidade_5_parcela").html(res.qtd_empresarial_05_parcela);
                        // $(".empresarial_quantidade_6_parcela").html(res.qtd_empresarial_06_parcela);
                        // $(".empresarial_quantidade_finalizado").html(res.qtd_empresarial_finalizado);
                        // $(".empresarial_quantidade_cancelado").html(res.qtd_empresarial_cancelado);
                        // tableempresarial.ajax.reload();
                        // //tableempresarial.ajax.reload();
                        // $('#date_baixa_empresarial').val('');
                    }
                });
                return false;
            })

            function removeReadonlyEmpresarial() {
                $("#razao_social_view_empresarial").removeAttr('readonly').addClass('editar_campo_empresarial');
                $("#cnpj_view").removeAttr('readonly').addClass('editar_campo_empresarial');
                $("#telefone_corretor_view_empresarial").removeAttr('readonly').addClass('editar_campo_empresarial');
                $("#celular_corretor_view_empresarial").removeAttr('readonly').addClass('editar_campo_empresarial');
                $("#email_odonto_view_empresarial").removeAttr('readonly').addClass('editar_campo_empresarial');
                $("#nome_corretor_view_empresarial").removeAttr('readonly').addClass('editar_campo_empresarial');
                $("#cod_corretora_view_empresarial").removeAttr('readonly').addClass('editar_campo_empresarial');
                $("#cod_saude_view_empresarial").removeAttr('readonly').addClass('editar_campo_empresarial');
                $("#cod_odonto_view_empresarial").removeAttr('readonly').addClass('editar_campo_empresarial');
                $("#senha_cliente_view_empresarial").removeAttr('readonly').addClass('editar_campo_empresarial');

            }

            function adicionarReadonlyEmpresarial() {
                $("#razao_social_view_empresarial").attr('readonly',true).removeClass('editar_campo_empresarial');
                $("#cnpj_view").attr('readonly',true).removeClass('editar_campo_empresarial');
                $("#telefone_corretor_view_empresarial").attr('readonly',true).removeClass('editar_campo_empresarial');
                $("#celular_corretor_view_empresarial").attr('readonly',true).removeClass('editar_campo_empresarial');
                $("#email_odonto_view_empresarial").attr('readonly',true).removeClass('editar_campo_empresarial');
                $("#nome_corretor_view_empresarial").attr('readonly',true).removeClass('editar_campo_empresarial');
                $("#cod_corretora_view_empresarial").attr('readonly',true).removeClass('editar_campo_empresarial');
                $("#cod_saude_view_empresarial").attr('readonly',true).removeClass('editar_campo_empresarial');
                $("#cod_odonto_view_empresarial").attr('readonly',true).removeClass('editar_campo_empresarial');
                $("#senha_cliente_view_empresarial").attr('readonly',true).removeClass('editar_campo_empresarial');

            }

            $("body").on('change','.editar_campo_empresarial',function(){
                let alvo = $(this).attr('id');
                let valor = $("#"+alvo).val();
                let id_cliente = $("#contrato").val();

                $.ajax({
                    url:"{{route('financeiro.editar.empresarial.campoIndividualmente')}}",
                    method:"POST",
                    data:"alvo="+alvo+"&valor="+valor+"&id_cliente="+id_cliente,
                    success:function(res) {

                        //table_individual.ajax.reload();
                    }
                });
            });

            $("form[name='update_desconto_corretor_corretora']").on('submit',function(){
                let valores = $(this).serialize()+"&acao=update_desconto";
                $.ajax({
                    url:"{{route('financeiro.mudarStatusEmpresarialUpdate')}}",
                    data:valores,
                    method:"POST",
                    success:function(res) {
                        window.location.href = "/admin/financeiro?ac=empresarial";
                        // $(".empresarial_quantidade_em_analise").html(res.qtd_empresarial_em_analise);
                        // $(".empresarial_quantidade_1_parcela").html(res.qtd_empresarial_01_parcela);
                        // $(".empresarial_quantidade_2_parcela").html(res.qtd_empresarial_02_parcela);
                        // $(".empresarial_quantidade_3_parcela").html(res.qtd_empresarial_03_parcela);
                        // $(".empresarial_quantidade_4_parcela").html(res.qtd_empresarial_04_parcela);
                        // $(".empresarial_quantidade_5_parcela").html(res.qtd_empresarial_05_parcela);
                        // $(".empresarial_quantidade_6_parcela").html(res.qtd_empresarial_06_parcela);
                        // $(".empresarial_quantidade_finalizado").html(res.qtd_empresarial_finalizado);
                        // $(".empresarial_quantidade_cancelado").html(res.qtd_empresarial_cancelado);
                        // tableempresarial.ajax.reload();
                        //$('#modalDiferencaEntreValores').modal('hide');
                        // limparEmpresarial();
                    }
                });
                return false;
            });









        });
    </script>
@stop

@section('css')
    <style>
        .container_full_cards {display: flex;justify-content: space-around;}
        .card_info {background-color:#123449;flex-basis: 50%;border-radius: 5px;}
        .historico_pagamento {background-color:#123449;flex-basis: 19%;margin-left:1%;color:#FFF;border-radius: 5px;}
        .historico_pagamento h3 {color:#FFF;border-bottom: 1px solid #FFF;text-align: center;padding:5px 0;}
        .historico_corretor {background-color:#123449;flex-basis: 50%;margin-left:1%;color:#FFF;border-radius: 5px;}
    </style>
@stop
