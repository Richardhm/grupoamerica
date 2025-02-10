@extends('adminlte::page')
@section('title', 'Visualizar Contratos')
@section('plugins.Datatables', true)

@section('content_top_nav_right')
   
    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt text-white"></i>
    </a>
    
@stop

@section('content_header')
    <div class="d-flex border-bottom border-dark">

        <h1 style="flex-basis:50%;">
            <i class="fas fa-arrow-left back"></i>
            Detalhe
        </h1>

        <div style="flex-basis:50%;display:flex;justify-content: space-around;align-items: center;">
            <span class="total_contratos">Contratos: {{$quantidade}}</span>
            <span class="total_vidas">Vidas: {{$vidas}}</span>
            <span class="total_valor">Total: {{number_format($valor,2,",",".")}}</span>
        </div>

    </div>
    
@stop


@section('content')
    <div style="flex-basis:83%;">
        <div class="p-2" style="background-color:#123449;color:#FFF;border-radius:5px;">
            <table id="tabela_individual" class="table table-sm listardados">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Orçamento</th>
                        <th>Corretor</th>
                        <th>Cliente</th>
                        <th>CPF/CNPJ</th>
                        <th>Vidas</th>
                        <th>Valor</th>
                        <th>Vencimento</th>                                  
                        <th>Status</th>
                        <th>Ver</th>
                    </tr>
                </thead>
                <tbody></tbody>
                
            </table>   
        </div>
    </div>  
@stop

@section('js')
    <script>
        $(function(){

            $(".back").on('click',function(){
                window.history.go(-1);
                return false;
            });    



            String.prototype.ucWords = function () {
                let str = this.toLowerCase()
                let re = /(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g
                return str.replace(re, s => s.toUpperCase())
            }

            var id_plano = $("#id_plano").val();
            var id_tipo = $("#id_tipo").val();
            var ano = $("#ano").val();
            var mes = $("#mes").val();
            var corretor = $("#corretor").val();

            
            

            var taindividual = $(".listardados").DataTable({
                dom: '<"d-flex justify-content-between"<"#title_individual">ft><t><"d-flex justify-content-between"lp>',
                order: [[0, 'desc']],
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":`{{route('gerente.contrato.show.detalhes.todos')}}`,
                    "dataSrc": ""
                },
                "lengthMenu": [1000,2000,3000],
                "ordering": true,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
               
                columns: [
                    {data:"created_at",name:"data",
                        "createdCell": function (td,cellData,rowData,row,col) {
                            if(rowData.empresarial) {
                                let datas = rowData.contrato_empresarial.created_at.split("T")[0];
                                let alvo = datas.split("-").reverse().join("/");
                                $(td).html(alvo)    
                            } else {
                                let datas = cellData.split("T")[0]
                                let alvo = datas.split("-").reverse().join("/")
                                $(td).html(alvo)    
                            }
                        },                       
                    },
                    {
                        data:"created_at",name:"orcamento",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            if(rowData.empresarial) {
                                let codigo = rowData.contrato_empresarial.codigo_externo;
                                $(td).html(codigo);
                            } else {
                                let codigo = rowData.contrato.clientes.codigo_externo;
                                $(td).html(codigo);
                            }
                        }
                    },
                    {
                        data:"user.name",name:"corretor",
                        "createdCell":function(td, cellData, rowData, row, col) {
                            let palavra = cellData.split(" ");
                            if(palavra.length >= 3) {
                                $(td).html(palavra[0]+" "+palavra[1]+"...")
                            }
                        }
                    },
                    {
                        data:"id",name:"cliente",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            if(rowData.empresarial) {
                                let cliente = rowData.contrato_empresarial.responsavel;
                                let palavra = cliente.split(" ");
                                if(palavra.length >= 3) {
                                    $(td).html(palavra[0]+" "+palavra[1]+"...")
                                } else {
                                    $(td).html(cliente);
                                }
                                
                            } else {
                                let cliente = rowData.contrato.clientes.nome;
                                let palavra = cliente.split(" ");
                                if(palavra.length >= 3) {
                                    $(td).html(palavra[0]+" "+palavra[1]+"...")
                                } else {
                                    $(td).html(cliente);
                                }
                                
                            }
                        }
                    },
                    {
                        data:"id",name:"cpf",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            if(rowData.empresarial) {
                                let cpf = rowData.contrato_empresarial.cnpj;
                                $(td).html(cpf);    
                            } else {
                                let cpf = rowData.contrato.clientes.cpf;
                                $(td).html(cpf);
                            }
                        }
                    },
                    {
                        data:"id",name:"vidas",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            
                            if(rowData.empresarial) {
                                $(td).html(rowData.contrato_empresarial.quantidade_vidas);
                            } else {
                                $(td).html(rowData.contrato.clientes.quantidade_vidas);
                            }

                        }
                    },
                    {
                        data:"id",name:"valor",
                        "createdCell": function (td, cellData, rowData, row, col) {

                            if(rowData.empresarial) {
                                let valor_total = rowData.contrato_empresarial.valor_total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});  
                                let valor_total_br = parseFloat(valor_total).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                                $(td).html(valor_total_br);
                            } else {
                                let valor_plano = rowData.contrato.valor_plano;  
                                let valor_plano_br = parseFloat(valor_plano).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                                $(td).html(valor_plano_br);
                            }

                        }
                    },
                    {
                        data:"id",name:"vencimento",
                        "createdCell": function (td, cellData, rowData, row, col) {

                            if(rowData.ultima_comissao_paga == null) {
                                let dados = rowData.comissao_atual_financeiro.data.split("-").reverse().join("/");
                                $(td).html(dados);
                            } else {
                                let dados = rowData.comissao_atual_financeiro.data.split("-").reverse().join("/");
                                $(td).html(dados);    
                            }

                        }
                    },
                    {
                        data:"id",name:"status",
                        "createdCell": function (td, cellData, rowData, row, col) {

                            if(rowData.empresarial) {
                                $(td).html(rowData.contrato_empresarial.financeiro.nome);
                            } else {
                                $(td).html(rowData.contrato.financeiro.nome);
                            }
                                
                        }
                    },
                    {
                        data:"id",name:"ver",
                        
                            "createdCell":function(td,cellData,rowData,row,col) {
                                
                                if(rowData.plano_id == 1) {
                                    $(td).css({"text-align":"center"}).html("<a href='/admin/financeiro/detalhes/"+rowData.contrato.id+"' class='text-white'><i class='fas fa-eye'></i></a>")
                                } else if(rowData.plano_id == 3) {
                                    $(td).css({"text-align":"center"}).html("<a href='/admin/financeiro/detalhes/coletivo/"+rowData.contrato.id+"' class='text-white'><i class='fas fa-eye'></i></a>")
                                } else {

                                }


                                
                        
                                // /financeiro/detalhes/coletivo/{id}
                            }
                    }
                    
                ],
                "columnDefs": [
                
                    {
                        "targets": 0,   
                        "width":"2%"
                    }
                   
               ],

               "drawCallback": function( settings ) {
                    var api = this.api();

                    
                    total_linhas = api.column(5).data();
                    
                    
                },
                "initComplete": function( settings, json ) {
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Contratos</h4>");
                    var intVal = function (i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };
                    var api = this.api();

                    //total_vidas = api.column(5).value();

                    // console.log(total_vidas);


                    total_linhas = api.column(0).data().count();

                    valor_total = api.column(6).data().reduce(function (a, b) {
	                    return intVal(a) + intVal(b);
                    }, 0);  

                    // $(".total_contratos").html("Contrato: "+total_linhas);
                    // $(".total_vidas").html("Total Vidas:"+total_vidas);
                    // $(".total_valor").html("Total: "+valor_total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));


                },
                footerCallback: function (row, data, start, end, display) {
                    
                }
            });




        });
    </script>
@stop