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


    <input type="text" id="estagio" value="{{$estagio}}">


    <div style="flex-basis:83%;">
        <div class="p-2" style="background-color:#123449;color:#FFF;border-radius:5px;">
            <table id="tabela_individual" class="table table-sm listardados" style="table-layout: fixed;">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Orçamento</th>
                        <th>Corretor</th>
                        <th>Cliente</th>
                        <th>CPF/CNPJ</th>
                        <th>Vidas</th>
                        <th>Valor</th>
                        <th>Plano</th>
                        <th>Ver</th>
                    </tr>
                </thead>
                <tbody></tbody>

            </table>
        </div>
    </div>
@stop

@section('css')
    <style>
        #tabela_individual td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: clip;
        }

        th { font-size: 0.8em !important; }
        td { font-size: 0.7em !important; }
    </style>
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

            var estagio = $("#estagio").val();

            var taindividual = $(".listardados").DataTable({
                dom: '<"d-flex justify-content-between"<"#title_individual">ft><t><"d-flex justify-content-between"lp>',
                order: [[0, 'desc']],
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":`/admin/gerente/all/todos/show/${estagio}`,
                    "dataSrc": ""
                },
                "lengthMenu": [1000,2000,3000],

                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "ordering": false,
                columns: [
                    {data:"data",name:"data",width:"5%"},
                    {data:"orcamento",name:"orcamento",width:"5%"},
                    {data:"corretor",name:"corretor",width:"10%"},
                    {data:"cliente",name:"cliente",width:"20%"},
                    {data:"documento",name:"documento",width:"10%"},
                    {data:"vidas",name:"vidas",width:"5%",className:'dt-center'},
                    {data:"valor",name:"valor",width:"8%"},
                    {data:"plano_nome",name:"plano_nome",width:"8%"},
                    {data:"plano",name:"plano",width:"5%","createdCell":function(td,cellData,rowData,row,col) {
                            let contrato_id = rowData.id;
                            if(cellData == 1) {
                                $(td).html(`<div class='text-center text-white'>
                                    <a href="/admin/financeiro/detalhes/${contrato_id}" target="_blank" class="text-white">
                                        <i class='fas fa-eye'></i>
                                    </a>
                                </div>
                            `);
                            } else if(cellData == 3) {
                                $(td).html(`<div class='text-center text-white'>
                                    <a href="/admin/financeiro/detalhes/coletivo/${contrato_id}" target="_blank" class="text-white">
                                        <i class='fas fa-eye'></i>
                                    </a>
                                </div>
                            `);
                            } else {
                                $(td).html(`<div class='text-center text-white'>
                                    <a href="/admin/financeiro/detalhes/empresarial/${contrato_id}" target="_blank" class="text-white">
                                        <i class='fas fa-eye'></i>
                                    </a>
                                </div>
                            `);
                            }





                        }
                    }

                ],
                "columnDefs": [



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
