@extends('adminlte::page')
@section('title', 'Gerenciavel')

@section('plugins.Datatables', true)
@section('plugins.Toastr', true)

@section('content_top_nav_right')
    <li class="nav-item"><a class="nav-link text-white" href="{{route('orcamento.search.home')}}">Tabela de Preço</a></li>
    <li class="nav-item"><a class="nav-link text-white" href="{{route('home.administrador.consultar')}}">Consultar</a></li>
    <a class="nav-link" data-widget="fullscreen" href="#" role="button"><i class="fas fa-expand-arrows-alt text-white"></i></a>
@stop

@section('content_header')
    <ul class="list_abas">
        <li data-id="aba_folha" class="ativo">Folha</li>
        <li data-id="aba_historico" class="menu-inativo">Historico</li>
    </ul>
@stop

@section('content')
    <input type="hidden" id="cliente_id" value="{{$id}}">
    <input type="hidden" id="janela_atual" value="" />

    <input type="hidden" id="id_cliente_recebidos">
    <input type="hidden" id="id_cliente_a_receber">

    <input type="hidden" id="valores_confirmados" value="{{$ids_confirmados ?? null}}">
    <input type="hidden" id="valores_money_descartado_individual" value="">
    <input type="hidden" id="valores_money_descartado_coletivo" value="">

    <!------------------------------------------------------- CONTEUDO FOLHA -------------------------------------------------------------------------------->

    <section id="aba_folha" class="menu">
        <div style="flex-basis:17%;margin-right:1%;">
            <div style="background-color:#123449;color:#FFF;border-radius:5px;padding:5px 3px;">
                <h3 class="text-center">
                    <select name="mes_folha" id="mes_folha" class="form-control form-control-sm">
                        <option value="" class="text-center">---</option>
                        <option value="01">Janeiro/2023</option>
                        <option value="02">Fevereiro/2023</option>
                        <option value="03">Março/2023</option>
                        <option value="04">Abril/2023</option>
                        <option value="05">Maio/2023</option>
                        <option value="06">Junho/2023</option>
                        <option value="07">Julho/2023</option>
                        <option value="08">Agosto/2023</option>
                        <option value="09">Setembro/2023</option>
                        <option value="10">Outubro/2023</option>
                        <option value="11">Novembro/2023</option>
                        <option value="12">Dezembro/2023</option>
                    </select>

                </h3>
                <ul style="margin:0;padding:0;">
                    <li style="display:flex;justify-content: space-between;">
                        <span style="display:flex;flex-basis:30%;align-self: center;">
                            Salario:
                        </span>
                        <span style="display:flex;flex-basis:65%;">
                            <input type="text" name="salario" id="salario" class="form-control form-control-sm salario_usuario">
                        </span>

                    </li>
                    <li style="display:flex;justify-content: space-between;margin:5px 0;">
                        <span style="display:flex;flex-basis:30%;align-self: center;">
                            Comissão:
                        </span>
                        <span style="display:flex;flex-basis:65%;">
                            <input type="text" name="comissao" id="comissao" class="form-control form-control-sm" readonly placeholder="Comissão" id="valor_comissao" value="{{number_format($total_a_pagar,2,',','.') ?? 0}}">
                        </span>
                    </li>
                    <li style="display:flex;justify-content: space-between;">
                        <span style="display:flex;flex-basis:30%;align-self: center;">
                            Premiação:
                        </span>
                        <span style="display:flex;flex-basis:65%;">
                            <input type="text" name="premiacao" id="premiacao" class="form-control form-control-sm premiacao_usuario">
                        </span>
                    </li>
                </ul>

                <ul style="margin:7px 0 0 0;padding:0;">
                    <li style="display:flex;justify-content: space-between;" id="listar_individual_apto">
                        <span style="display:flex;flex-basis:60%;">Individual</span>
                        <span style="display:flex;flex-basis:10%;" id="total_quantidade_individual">{{$total_individual_quantidade}}</span>
                        <span style="display:flex;flex-basis:30%;justify-content: flex-end;"><span id="valor_total_individual">{{$total_individual}}</span></span>
                    </li>
                    <li class="my-1" style="display:flex;justify-content: space-between;" id="listar_coletivo_apto">
                        <span style="display:flex;flex-basis:60%;">Coletivo</span>
                        <span style="display:flex;flex-basis:10%;" id="total_quantidade_coletivo">{{$total_coletivo_quantidade}}</span>
                        <span style="display:flex;flex-basis:30%;justify-content: flex-end;"><span id="valor_total_coletivo">{{number_format($total_coletivo,2,",",".")}}</span></span>
                    </li>
                    <li style="display:flex;justify-content: space-between;" id="listar_empresarial_apto">
                        <span style="display:flex;flex-basis:60%;">Empresarial</span>
                        <span style="display:flex;flex-basis:10%;" id="total_quantidade_empresarial">0</span>
                        <span style="display:flex;flex-basis:30%;justify-content: flex-end;"><span id="valor_total_empresarial">0</span></span>
                    </li>
                </ul>

                <button class="btn btn-success btn-block mt-2" id="finalizar_folha">
                    Finalizar: <span class="total_a_pagar">R$ {{number_format($total_a_pagar,2,",",".")}}</span>
                </button>

            </div>

            <div style="background-color:#123449;color:#FFF;border-radius:5px;padding:5px 0;margin:5px 0;">
                <h5 class="border-bottom text-center">Recebidas</h5>
                <ul style="margin:0;padding:0;" class="listar">
                    <li class="individual_recebidas ativo py-1">Individual</li>
                    <li class="coletivo_recebidas my-1 ml-1 py-1">Coletivo</li>
                    <li class="empresarial_recebidas ml-1 py-1">Empresarial</li>
                </ul>
            </div>

            <div style="background-color:#123449;color:#FFF;border-radius:5px;padding:5px 0;">
                <h5 class="border-bottom text-center">A Receber</h5>
                <ul style="margin:0;padding:0;" class="listar listar_a_receber_ul">
                    <li class="individual_a_receber ml-1 py-1">Individual</li>
                    <li class="coletivo_a_receber ml-1 py-1">Coletivo</li>
                    <li class="empresarial_a_receber ml-1 py-1">Empresarial</li>
                </ul>
            </div>

        </div>

        <div style="flex-basis:83%;color:#FFF;padding:8px;border-radius:5px;" id="tabela_aptos_a_pagar" class="dsnone">
            <div style="background-color:#123449;border-radius:5px;padding:5px;">
                <table id="tabela_aptos_a_pagar_table" class="table table-sm listaraptosapagar w-100">
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Parcela</th>
                            <th>Cliente</th>
                            <th align="center">Vencimento</th>
                            <th>Baixa</th>
                            <th>Valor<small>(Plano)</small></th>
                            <th>Comissão</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div style="flex-basis:83%;color:#FFF;padding:8px;border-radius:5px;" id="tabela_principal">
            <div style="background-color:#123449;border-radius:5px;padding:5px;">
                <table id="tabela_mes_recebidas" class="table table-sm listarcomissaomesrecebidas w-100">
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Data</th>
                            <th>Orçamento</th>
                            <th>Cliente</th>
                            <th>Parcela</th>
                            <th>Vidas</th>
                            <th>Valor<small>(Plano)</small></th>
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

        <div style="flex-basis:83%;color:#FFF;padding:8px;" id="listar_a_receber" class="dsnone">
            <div style="background-color:#123449;border-radius:5px;padding:5px;">
                <table id="tabela_mes_diferente" class="table table-sm listarcomissaomesdiferente" >
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Data</th>
                            <th>Orçamento</th>
                            <th>Cliente</th>
                            <th>Parcela</th>
                            <th>Vidas</th>
                            <th>Valor<small>(Plano)</small></th>
                            <th align="center">Vencimento</th>
                            <th>Baixa</th>
                            <th>Comissão</th>
                            <th>%</th>
                            <th>Pagar</th>
                            <th>Antecipar</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>

    <!-------------------------------------------------------- FIM DO CONTEUDO FOLHA ------------------------------------------------------------------------>

    <section id="aba_historico" class="ocultar menu">

        <div class="d-flex">

            <div style="flex-basis:16%;background-color:#123449;border-radius:5px;margin-right:1%;">
                <h2 class="text-white text-center">Mês</h2>
                <div class="mb-1" id="selecionar_mes">
                    <select id="select_mes" class="form-control">
                        <option value="todos" class="text-center">---Escolher Mês---</option>
                    </select>
                </div>
            </div>

        </div>
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
            <span class="d-flex" style="flex-basis:20%;">Total:</span>
            <span class="d-flex total_a_pagar_modal"></span>
        </p>







      </div>
      <div class="modal-footer" style="display:flex;justify-content: center;">

        <button type="button" class="btn btn-primary" data-dismiss="modal">Criar o PDF</button>
      </div>
    </div>
  </div>
</div>



@stop

@section('js')
<script src="{{asset('js/jquery.mask.min.js')}}"></script>
    <script>

        $(function(){

            let id = $("#cliente_id").val();

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

                if($("#salario").val() == "") {
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
                let comissao =  $("#comissao").val();
                let salario = $(".salario_usuario").val();
                let premiacao = $(".premiacao_usuario").val();
                let user_id = $("#cliente_id").val();
                let mes = $("#mes_folha").val();
                let total_a_pagar = $(".total_a_pagar").text();

                $(".salario_usuario_modal").text(salario);
                $(".comissao_usuario_modal").text(comissao);
                $(".premiacao_usuario_modal").text(premiacao);
                $(".total_a_pagar_modal").text(total_a_pagar);

            });

            $('#exampleModal').on('hide.bs.modal', function (event) {

                let comissao =  $("#comissao").val();
                let salario = $(".salario_usuario").val();
                let premiacao = $(".premiacao_usuario").val();
                let user_id = $("#cliente_id").val();
                let mes = $("#mes_folha").val();
                let total_a_pagar = $(".total_a_pagar").text().replace("R$","").trim();
                $.ajax({
                    url:"{{route('gerente.finalizar.pagamento')}}",
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
                            window.navigator.msSaveBlob(blob, filename);
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


            $("#listar_individual_apto").on('click',function(){
                $("#listar_coletivo_apto").removeClass("ativo");
                $(".listar li").removeClass("ativo");
                $("#listar_individual_apto").addClass("ativo");
                if($("#tabela_principal").is(':visible')) {
                    $("#tabela_principal").slideUp('fast',function(){
                        listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/confirmadas/${id}') }}`).load();
                        $("#tabela_aptos_a_pagar").slideDown('slow');
                    });
                } else {
                    $("#title_individual_confirmados").html("<h4>Recebidas - Individual</h4>");
                    listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/confirmadas/${id}') }}`).load();
                }


                if($("#listar_a_receber").is(':visible')) {
                    $("#listar_a_receber").slideUp('fast',function(){
                        listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/confirmadas/${id}') }}`).load();
                        $("#tabela_aptos_a_pagar").slideDown('slow');
                    });
                } else {
                    $("#title_individual_confirmados").html("<h4>Recebidas - Individual</h4>");
                    listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/confirmadas/${id}') }}`).load();

                }

            });


            $("#listar_coletivo_apto").on('click',function(){
                $(".listar li").removeClass("ativo");
                $("#listar_coletivo_apto").addClass("ativo");
                $("#listar_individual_apto").removeClass("ativo");
                if($("#tabela_principal").is(':visible')) {
                    $("#tabela_principal").slideUp('fast',function(){
                        $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                        listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/coletivo/confirmadas/${id}') }}`).load();
                        $("#tabela_aptos_a_pagar").slideDown('slow');
                    });
                } else {
                    $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                    listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/coletivo/confirmadas/${id}') }}`).load();
                }

                if($("#listar_a_receber").is(':visible')) {
                    $("#listar_a_receber").slideUp('fast',function(){
                        $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                        listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/coletivo/confirmadas/${id}') }}`).load();
                        $("#tabela_aptos_a_pagar").slideDown('slow');
                    });
                } else {
                    $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                    listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/coletivo/confirmadas/${id}') }}`).load();
                }

            });

            $("#listar_empresarial_apto").on('click',function(){
                $("#listar_coletivo_apto").removeClass("ativo");
                $("#listar_individual_apto").removeClass("ativo");
                $("#listar_empresarial_apto").addClass("ativo");

                if($("#tabela_principal").is(':visible')) {
                    $("#tabela_principal").slideUp('fast',function(){
                        $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                        listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/coletivo/confirmadas/${id}') }}`).load();
                        $("#tabela_aptos_a_pagar").slideDown('slow');
                    });
                } else {
                    $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                    listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/coletivo/confirmadas/${id}') }}`).load();
                }

                if($("#listar_a_receber").is(':visible')) {
                    $("#listar_a_receber").slideUp('fast',function(){
                        $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                        listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/coletivo/confirmadas/${id}') }}`).load();
                        $("#tabela_aptos_a_pagar").slideDown('slow');
                    });
                } else {
                    $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                    listaraptosapagar.ajax.url(`{{ url('/admin/gerente/comissao/coletivo/confirmadas/${id}') }}`).load();
                }

            });





            $(".individual_recebidas").on('click',function(){
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
            });


            $(".coletivo_recebidas").on('click',function(){
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
            });





            $(".empresarial_recebidas").on('click',function(){
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
            });



            $(".individual_a_receber").on('click',function(){
                $("#listar_individual_apto").removeClass("ativo");
                if($("#listar_a_receber").is(":visible")) {
                    $("#title_comissao_diferente").html("<h4>A Receber Individual</h4>")
                    listarcomissaomesdfirente.ajax.url(`{{ url('/admin/gerente/listagem/comissao_mes_diferente/${id}') }}`).load();
                } else {

                    if($("#tabela_principal").is(":visible")) {
                        $("#tabela_principal").slideUp(1000,function(){
                            $("#listar_a_receber").slideDown('slow',function(){
                                $("#title_comissao_diferente").html("<h4>A Receber Individual</h4>")
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
            });

            $(".coletivo_a_receber").on('click',function(){
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
            });


            $(".empresarial_a_receber").on('click',function(){
                $("#listar_individual_apto").removeClass("ativo");
                $("#listar_coletivo_apto").removeClass("ativo");
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
            });








            String.prototype.ucWords = function () {
                let str = this.toLowerCase()
                let re = /(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g
                return str.replace(re, s => s.toUpperCase())
            }

            $(".list_abas li").on('click',function(){

                $("#aba_folha").addClass('ocultar');


                $('li').removeClass('ativo').addClass('menu-inativo');
                $(this).addClass("ativo").removeClass('menu-inativo');
                let id = $(this).attr('data-id');

                $("#janela_atual").val(id);
                $("section.menu").addClass('ocultar');
                $("#aba_folha").addClass('ocultar');
                $('#'+id).removeClass('ocultar');
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.salario_usuario').mask("#.##0,00", {reverse: true});
            $('.premiacao_usuario').mask("#.##0,00", {reverse: true});
            $("#body").find("#comissao_pagando").mask("#.##0,00", {reverse: true});




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



            /*******************************INDIVIDUAL Recebidas ****************************************************************************************************************/
            var listarcomissaomesrecebidas = $(".listarcomissaomesrecebidas").DataTable({
                dom: '<"d-flex justify-content-between"<"#title_recebidas"><"estilizar_search"f>><t><"d-flex justify-content-between align-items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":`{{ url('/admin/gerente/listagem/comissao_mes_atual/${id}') }}`,
                    //"url":`{{ url('/admin/gerente/listagem/empresarial/recebidas/${id}') }}`,
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
                    {data:"data_criacao",name:"data_criacao",
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
                                $(td).html(dados[0]+" "+dados[1]+"...");
                            }
                        }
                    },

                    {data:"parcela",name:"parcela",width:"5%",className: 'dt-center'},
                    {data:"quantidade_vidas",name:"quantidade_vidas"},
                    {data:"valor_plano_contratado",name:"valor_plano",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center'},
                    {data:"data",name:"data",width:"5%",className: 'dt-center'},
                    {data:"data_baixa_gerente",name:"baixa",width:"5%",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            if(cellData == null) {
                                let alvo = rowData.data_antecipacao.split("-").reverse().join("/");
                                $(td).html(alvo);
                            } else {
                                let alvo = cellData.split("-").reverse().join("/")
                                $(td).html(alvo);
                            }
                        }
                    },
                    {data:"comissao_esperada",name:"comissao_esperada",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center comissao_recebida'},
                    {data:"porcentagem_parcela_corretor",name:"porcentagem_parcela_corretor"},
                    {data:"id",name:"comissao_pagando",
                        "createdCell":function(td, cellData, rowData, row, col) {
                            $(td).html('<input type="text" data-id='+cellData+' name="comissao_pagando" id="comissao_pagando" class="comissao_pagando" style="width:80px;" />')
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

            /*******************************FIM INDIVIDUAL Recebidas ****************************************************************************************************************/


            var data = new Date();
            var mes = String(data.getMonth());
            let meses = ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];

            var listarcomissaomesdfirente = $(".listarcomissaomesdiferente").DataTable({
                dom: '<"d-flex justify-content-between"<"#title_comissao_diferente"><"estilizar_search"f>><t><"d-flex justify-content-between align-items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":`{{ url('/admin/gerente/listagem/comissao_mes_diferente/${id}') }}`,
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
                    {data:"administradora",name:"administradora",width:"8%"},
                    {data:"data_criacao",name:"data_criacao",
                        "createdCell": function(td, cellData, rowData, row, col) {
                            let datas = cellData.split(" ")[0].split("-").reverse().join("/");
                            $(td).html(datas);
                        }
                    },
                    {data:"orcamento",name:"orcamento"},
                    {data:"cliente",name:"cliente",width:"30%",
                        "createdCell": function(td, cellData, rowData, row, col) {
                            let palavra = cellData.split(" ");
                            if(palavra.length >= 3) {
                                $(td).html(palavra[0]+" "+palavra[1]+"...")
                            }
                        }
                    },
                    {data:"parcela",name:"parcela",className: 'dt-center',width:"5%"},
                    {data:"quantidade_vidas",name:"quantidade_vidas"},
                    {data:"valor_plano_contratado",name:"valor_plano_contratado",render: $.fn.dataTable.render.number('.',',',2,'R$ ')},
                    {data:"data",name:"data",className: 'dt-center',width:"10%"},
                    {data:"data_baixa",name:"data_baixa",
                        "createdCell": function(td, cellData, rowData, row, col) {
                            let datas = cellData.split("-").reverse().join("/");
                            $(td).html(datas);
                        }
                    },
                    {data:"valor",name:"valor",width:"5%",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center comissao_recebida'},
                    {data:"porcentagem_parcela_corretor",name:"porcentagem_parcela_corretor"},
                    {data:"id",name:"comissao_pagando",render: $.fn.dataTable.render.number('.',',',2,'R$ '),
                        "createdCell":function(td, cellData, rowData, row, col) {
                            $(td).html('<input type="text" data-id='+cellData+' name="comissao_pagando" id="comissao_pagando" class="comissao_pagando" style="width:80px;" />')
                        }


                    },


                    {
                        data:"id",name:"id",width:"5%",
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
                            $(td).html(selected)
                        }
                    }
                ],
                "initComplete": function( settings, json ) {
                    $('#title_comissao_diferente').html("<h4>A Receber Individual</h4>");
                }
            });

            $("body").on('keyup','#comissao_pagando',function(){
                $(this).mask('#.##0,00', {reverse: true});
            });




            $("body").on('change','#comissao_pagando',function(){
                let id = $(this).attr('data-id');
                let valor = $(this).val();
                let valor_plano = $(this).closest("tr").children("td:nth-child(7)").text();
                let self = $(this);
                $.ajax({
                    url:"{{route('gerente.mudar.valor.pago')}}",
                    method:"POST",
                    data:"id="+id+"&valor="+valor+"&valor_plano="+valor_plano,
                    success:function(res) {
                        self.closest("tr").children("td:nth-child(11)").text(res);
                    }
                });
            });




            // $ ('#tabela_mes_diferente tbody'). on ('click', 'tr', function () {
            //     var source = listarcomissaomesdfirente.row(this);
            //     console.log(source);
            //     // listarcomissaomesatual.row.add(source.data()).draw();
            //     // source.remove().draw();
            // });


            $("body").on('change','.mes_diferente_select',function(){
                var source = listarcomissaomesdfirente.row(this.parent);
                listarcomissaomesatual.row.add(source.data()).draw();
                source.remove().draw();
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





            $(".salario_usuario").on('change',function(){



                let comissao_numerica = 0;
                if($("#comissao").val() != "") {

                    let comissao = $("#comissao").val().replace("R$","").replace(/\./g,'').replace(',', '.').trim()
                    comissao_numerica = parseFloat(comissao);
                }

                let valor_premiacao = 0;
                if($('#premiacao').val() != "") {
                    valor_premiacao = parseFloat($('#premiacao').val().replace(/\./g,'').replace(',', '.'));
                }

                let valor = $(this).val().replace(/\./g,'').replace(',', '.');
                let valor_numerico = parseFloat(valor);

                // let total = $(".total_a_pagar").text().replace("R$","").replace(/\./g,'').replace(',', '.');
                // let total_numero = parseFloat(total);

                // //let valor_input = total_numero + valor_numerico;

                let valor_input = comissao_numerica + valor_premiacao + valor_numerico;




                f = valor_input.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                $(".total_a_pagar").html(f);
            });


            $(".premiacao_usuario").on('change',function(){

                let comissao_numerica = 0;
                if($("#comissao").val() != "") {
                    let comissao = $("#comissao").val().replace("R$","").replace(/\./g,'').replace(',', '.').trim()
                    comissao_numerica = parseFloat(comissao);
                }

                let valor_salario = 0;
                if($('.salario_usuario').val() != "") {
                    valor_salario = parseFloat($('.salario_usuario').val().replace(/\./g,'').replace(".","").replace(',', '.'));
                }

                let valor = $(this).val().replace(/\./g,'').replace(',', '.');

                let valor_numerico = parseFloat(valor);
                let total = $(".total_a_pagar").text().trim().replace("R$","").replace(/\./g,'').replace(',', '.');
                let total_numero = parseFloat(total);

                let valor_input = valor_numerico + comissao_numerica + valor_salario;
                f = valor_input.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                $(".total_a_pagar").html(f);
            });

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
                    $(this).addClass('pagar');
                    var linha = $(this).closest('tr');
                    linha.slideUp('fast');

                    if($("#comissao").val()) {
                        let valor_atual = parseFloat($("#comissao").val().replace("R$","").replace(/\./g,'').replace(',', '.').trim())
                        valor_atual += parseFloat(comissao_recebida);
                        f = parseFloat(valor_atual).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                        $("#comissao").val(f);
                        let total = $(".total_a_pagar").text().replace("R$","").replace(/\./g,'').replace(',', '.').trim();
                        let total_numero = parseFloat(total);
                        let resultado = total_numero + parseFloat(comissao_recebida);
                        ff = parseFloat(resultado).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                        $(".total_a_pagar").html(ff);
                    } else {
                        f = parseFloat(comissao_recebida).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                        $("#comissao").val(f);
                        //$(".total_a_pagar").html(f);
                        // let valor_atual = parseFloat($("#valor_comissao").val().replace("R$","").replace(/\./g,'').replace(',', '.').trim())
                        let total = $(".total_a_pagar").text().replace("R$","").replace(/\./g,'').replace(',', '.');
                        let total_numero = parseFloat(total);
                        let valor_total = total_numero + parseFloat(comissao_recebida);
                        //console.log(valor_total);
                        // f = parseFloat(comissao_recebida).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                        // $("#valor_comissao").val(f);
                        ff = parseFloat(valor_total).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                        $(".total_a_pagar").html(ff);
                        // total_numero += comissao_recebida;
                        // ff = parseFloat(total_numero).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                        // $(".total_a_pagar").html(ff);
                    }

                    $.ajax({url:"{{route('gerente.aptar.pagamento')}}",method:"POST",data:"id="+id});


                } else {
                    //$(this).removeClass('pagar');
                }




                // var linha = $(this).closest('tr');
                // linha.slideUp('fast');

                //console.log(comissao_recebida);
                // let total = $(".total_a_pagar").text().replace("R$","").replace(/\./g,'').replace(',', '.');
                // let total_numero = parseFloat(total);
                // let comissao_recebida_valor = parseFloat(comissao_recebida);
                // let total_pagar = comissao_recebida_valor + total_numero;
                // f = total_pagar.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});

                // $(".total_a_pagar").html(f);
                // $("#valor_comissao").val(f);


            });




            $('body').on('change','.pagar_agora',function(){

                //var source = listarcomissaomesdfirente.row($(this).parents('tr'));
                //source.remove().draw();
                // var linha = $(this).closest('tr');
                // linha.slideUp('fast');
                // // console.log("Olaaaaaa");
                // //listarcomissaomesatual.row.add(valores).draw();
                // let id = $(this).attr('data-id');
                // $.ajax({
                //     url:"{{route('gerente.aptar.pagamento')}}",
                //     method:"POST",
                //     data:"id="+id,
                //     success:function(res) {
                //         console.log(res);
                //         // var valores = source.data();
                //         // valores.comissao_esperada = valores.valor;
                //         // valores.data_baixa_gerente = res.data_baixa_gerente;
                //         // valores.valor_plano_contratado = res.valor_plano_contratado;

                //     }
                // });
            });

            $(".listar li").on('click',function(){
                if($(this).hasClass('ativo')) {

                } else {
                    $(".listar li").removeClass('ativo');
                    $(this).addClass('ativo');
                }
                //$(this).addClass('');

            });




        });



    </script>
@stop


@section('css')
    <style>
        .separador {width:100%;height:15px;border:1px solid #C5D4EB;background-color:#C5D4EB;}
        th { font-size: 1em !important; }
        td { font-size: 0.8em !important; }
        #valor_comissao {
            color:#FFF;
        }

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

        .list_abas {list-style: none;display: flex;border-bottom: 1px solid white;margin: 0;padding: 0;}
        .list_abas li {color: #fff;width: 150px;padding: 8px 5px;text-align:center;border-radius: 5px 5px 0 0;background-color:#123449;}
        .list_abas li:hover {cursor: pointer;}
        .list_abas li:nth-of-type(2) {margin: 0 1%;}
        .ativo {background-color:#FFF !important;color: #000 !important;}


        #aba_folha {display:flex;}
        .ocultar {display:none !important;}

        #aba_folha ul {list-style: none;}
        .dsnone {display:none;}


    </style>
@stop
