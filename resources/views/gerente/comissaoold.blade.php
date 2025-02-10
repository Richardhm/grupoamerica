@extends('adminlte::page')
@section('title', 'Gerenciavel')

@section('plugins.Datatables', true)


@section('content_header')
    <ul class="list_abas">
        <li data-id="aba_folha" class="ativo">Folha</li>
        <li data-id="aba_historico" class="menu-inativo">Historico</li>
        
    </ul>
@stop

@section('content')
    <input type="hidden" id="cliente_id" value="{{$id}}">


    <!------------------------------------------------------- CONTEUDO FOLHA -------------------------------------------------------------------------------->


    <section id="aba_folha" class="menu">
        
    <div style="background-color:#123449;display:flex;border-radius:5px;border-radius:5px;margin-bottom:10px;padding:5px;">
        <!-- COLUNA 01 -->
        <!-- <div style="background-color:red;">

            <h2 class="text-white text-center">Mês</h2>  
            <div class="mb-1" id="selecionar_mes">
                <select id="select_mes" class="form-control">
                    <option value="todos" class="text-center">---Escolher Mês---</option>
                    
                </select>
            </div>   
        </div> -->
        <!-- FIM COLUNA 01 -->         
        
        <!-- COLUNA 02 -->

        <div style="display:flex;flex-basis:40%;align-content: center;flex-wrap: wrap;">
            
            <div style="display:flex;flex-basis:100%;justify-content: center;">
                <h6 class="text-white text-center py-2">{{$usuario}}</h6>
            </div>

            <div style="display:flex;flex-basis:100%;justify-content: center;">
                <h6 class="text-white text-center" id="previsao_de_comissao">Previsão da Comissão: {{number_format($total_comissao,2,",",".")}}</h6>
            </div>

        </div>



        <!-- FIM COLUNA 02 -->
        
        <!-- COLUNA 03 -->

        <div style="display:flex;flex-basis:15%;">
            <ul style="margin:0;padding:0;list-style:none;">
                
                <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;align-items: center;" id="aguardando_pagamento_3_parcela_individual" class="individual">
                    <span class="text-white w-25">Comissão</span>
                    <input type="text" class="form-control-sm w-50" placeholder="Comissão" id="valor_comissao" disabled>                        
                </li>

                <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;align-items: center;" id="aguardando_pagamento_3_parcela_individual" class="individual">
                    <span class="text-white w-25">Salario</span>
                    <input type="text" class="form-control-sm w-50 salario_usuario" placeholder="Salario">
                </li>

                <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;align-items: center;" id="aguardando_pagamento_3_parcela_individual" class="individual">
                    <span class="text-white w-25">Premiação</span>
                    <input type="text" class="form-control-sm w-50 premiacao_usuario" placeholder="Premiação">
                </li>
                
            </ul>
        </div>

        <!-- FIM COLUNA 03 -->
            
        <!-- COLUNA 04 -->

        <div style="color:#FFF;display:flex;flex-basis:5%;align-content: center;align-items: center;">
                
            <span class="ml-2">Total:</span>
            <span class="mr-2">
                <span class="total_a_pagar">0</span>
            </span>
                
        </div>



        <!-- FIM COLUNA 04 -->
            

        <!-- COLUNA 05 -->    
        <div style="display:flex;flex-basis:45%;justify-content: flex-end;align-items: center; align-content: center;">

            <div style="display:flex;flex-basis:40%;flex-direction: column;">

                <div class="mb-1">
                    <button class="btn btn-block btn-success btn_finalizar">Finalizar</button>
                </div>
                
                <div>
                    <a class="btn btn-block btn-info" href="{{route('comissao.create.pdf')}}">PDF</a>
                </div>


            </div>
            

        </div>
            
            




        <!-- FIM COLUNA 05 -->    

            

            

           

    </div>







    <section class="d-flex" style="align-items: flex-start;align-content: flex-start;">
        


        <div style="flex-basis:65%;margin-right:1%;background-color:#123449;color:#FFF;padding:8px;border-radius:5px;">

                <table id="tabela_mes_atual" class="table table-sm listarcomissaomesatual" >
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Parcela</th>
                            <th>Cliente</th>
                            <th align="center">Vencimento</th>
                            <th>Baixa</th>
                            <th>Valor<small>(Plano)</small></th>
                            <th>Comissão<small>(Esperada)</small></th>
                            <th>Comissão<small>(Recebida)</small></th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
              
        </div>

        <div style="flex-basis:35%;background-color:#123449;color:#FFF;padding:8px;border-radius:5px;">
            
            <table id="tabela_mes_diferente" class="table table-sm listarcomissaomesdiferente" >
                <thead>
                    <tr>
                        <th>Admin</th>
                        <th>Cliente</th>
                        <th>Parcela</th>
                        <th align="center">Vencimento</th>
                        
                        <th align="center">Comissão</th>
                        <!-- <th>Mês</th> -->
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

        </div>
       





    </section>














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

            <div style="flex-basis:80%;background-color:#123449;border-radius:5px;">

            
            </div>    


        </div>




    </section>

    
@stop




@section('js')
<script src="{{asset('js/jquery.mask.min.js')}}"></script>   
    <script>
        
        $(function(){

            $(".list_abas li").on('click',function(){                
                $('li').removeClass('ativo').addClass('menu-inativo');
                $(this).addClass("ativo").removeClass('menu-inativo');
                let id = $(this).attr('data-id');
                $("#janela_atual").val(id);
                $("section.menu").addClass('ocultar');
                $('#'+id).removeClass('ocultar');
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });          





            $('.salario_usuario').mask("#.##0,00", {reverse: true});
            $('.premiacao_usuario').mask("#.##0,00", {reverse: true});

            let id = $("#cliente_id").val();


            var listarcomissaomesatual = $(".listarcomissaomesatual").DataTable({
                dom: '<"d-flex justify-content-between"<"#title_comissao_atual"><"estilizar_search"f>><t><"d-flex justify-content-between align-items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":`{{ url('/admin/gerente/listagem/comissao_mes_atual/${id}') }}`,
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
                    {data:"cliente",name:"cliente",width:"30%"},
                    {data:"data",name:"data",width:"5%",className: 'dt-center'},
                    {data:"data_baixa_gerente",name:"baixa",width:"5%"},
                    {data:"valor_plano_contratado",name:"valor_plano",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center'},
                    {data:"comissao_esperada",name:"comissao_esperada",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center'},
                    {data:"comissao_recebida",name:"comissao_recebida",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center comissao_recebida'},
                    
                    {data:"id",name:"id",width:"5%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let selected = $('<select />', {
                                name: 'pagar_comissao',
                                class:"pagar_comissao",
                                id:cellData,
                    //             on: {
                    //                 change: function() { 
                    //                     $(this).prop('disabled', 'disabled');
                    //                     if($("#valor_comissao").val() == "") {
                    //                         let teste = $(this).closest("tr").find("td:eq(4)").text();
                    //                         $("#valor_comissao").val(teste);
                    //                         let valor_numero = $(this).closest("tr").find("td:eq(4)").text().replace("R$","").replace(/\./g,'').replace(',', '.');    
                    //                         let valor_numero_float = parseFloat(valor_numero);
                    //                         let total = $(".total_a_pagar").text().replace("R$","").replace(/\./g,'').replace(',', '.');
                    //                         let total_numero = parseFloat(total);   
                    //                         let valor_input = valor_numero_float + total_numero;
                    //                         f = valor_input.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                    //                         $(".total_a_pagar").html(f);
                    //                     } else {
                    //                         let valor_input = "";
                    //                         let valor_click = "";
                    //                         let valor_proximo = "";
                    //                         let f = "";
                    //                         valor_input = $("#valor_comissao").val().replace(/[^0-9][$]/,'').replace(",",".");
                    //                         valor_click = $(this).closest("tr").find("td:eq(4)").text().replace(/[^0-9][$]/,'').replace(",",".");
                    //                         let n1 = parseFloat(valor_input);
                    //                         let n2 = parseFloat(valor_click);
                    //                         valor_proximo = n1 + n2;
                    //                         f = valor_proximo.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                    //                         $("#valor_comissao").val(f);
                    //                         let tp = $(".total_a_pagar").text().replace("R$","").replace(/\./g,'').replace(',', '.');
                    //                         let tp_float = parseFloat(tp);
                    //                         let resultado = tp_float + n2
                    //                         ff = resultado.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                    //                         $(".total_a_pagar").html(ff);
                    //                     }
                    //                 }
                    //             },
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
                    $('#title_comissao_atual').html("<h4>Liquidados</h4>");
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
                    //$("#valor_comissao").val(conditionalRecebida.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
                    //$(".total_a_pagar").html(conditionalRecebida.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}))
                    //$(".valor_quat_comissao_recebido").html(conditionalTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
                    //$("#quat_comissao_recebido").html(qtdLinha);                  
                }
            });  
            
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
                columns: [
                    {data:"administradora",name:"administradora"},
                    {data:"cliente",name:"cliente"},
                    {data:"parcela",name:"parcela",className: 'dt-center'},
                    {data:"data",name:"data",className: 'dt-center'},
                    
                    {data:"valor",name:"valor",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center'},
                    // {data:"mes_atual",name:"mes",
                    //     "createdCell": function (td, cellData, rowData, row, col) {
                    //         let selected = $('<select />', {
                    //             name: 'teste',
                    //             class:"mes_diferente_select",
                    //             on: {
                    //                 change: function() { 
                                        
                    //                 }
                    //             },
                    //             append : [
                    //                 $('<option />', {value : "1", text : cellData}),
                    //                 $('<option />', {value : "2", text : meses[mes]}),
                    //             ]
                    //         });
                    //         $(td).html(selected)
                    //     }
                    // }                          
                ],
                "initComplete": function( settings, json ) {
                    $('#title_comissao_diferente').html("<h4>A Receber</h4>");
                    
                }
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

            






            $(".salario_usuario").on('change',function(){

                let comissao_numerica = 0;

                if($("#valor_comissao").val() != "") {
                    
                    let comissao = $("#valor_comissao").val().replace("R$","").trim().replace(',', '.');
                    comissao_numerica = parseFloat(comissao);
                }


                


                let valor_premiacao = 0;
                if($('.premiacao_usuario').val() != "") {
                    valor_premiacao = parseFloat($('.premiacao_usuario').val().replace(/\./g,'').replace(".",""));
                } 

                
                let valor = $(this).val().replace(/\./g,'').replace(',', '.');
                let valor_numerico = parseFloat(valor);
                
                
                
                
                //let total = $(".total_a_pagar").text().replace("R$","").replace(/\./g,'').replace(',', '.');
                //let total_numero = parseFloat(total);                
                
                //console.log(total_numero);
                let valor_input = valor_numerico + comissao_numerica + valor_premiacao;
                
                


                f = valor_input.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                $(".total_a_pagar").html(f);
            }); 


            $(".premiacao_usuario").on('change',function(){

                let comissao_numerica = 0;
                if($("#valor_comissao").val() != '') { 
                    let comissao = $("#valor_comissao").val().replace("R$","").trim().replace(',', '.');
                    comissao_numerica = parseFloat(comissao);
                }


                
                let valor_salario = 0;
                if($('.salario_usuario').val() != "") {
                    valor_salario = parseFloat($('.salario_usuario').val().replace(/\./g,'').replace(".",""));
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
                let comissao_recebida = $(this).closest('tr').find('.comissao_recebida').text().replace("R$","").replace(/\./g,'').replace(',', '.').trim();
                let total = $(".total_a_pagar").text().replace("R$","").replace(/\./g,'').replace(',', '.');
                let total_numero = parseFloat(total);                
                let comissao_recebida_valor = parseFloat(comissao_recebida);
                let total_pagar = comissao_recebida_valor + total_numero;
                f = total_pagar.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                $(this).addClass('pagar');
                $(".total_a_pagar").html(f);
                $("#valor_comissao").val(f);
            });

            $(".btn_finalizar").on('click',function(){
                let ids = [];

                let select = $("select.pagar").each(function(k,v){
                    ids.push($(v).attr("id"));
                });

                $.ajax({
                    url:"{{route('gerente.finalizar.pagamento')}}",
                    method:"POST",
                    data: "ids="+ids.join("|"),
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
        
        .ocultar {display: none;}

    </style>
@stop