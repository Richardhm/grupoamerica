<x-app-layout>
    <div style="width:95%;margin:0 auto;">
        <ul class="list_abas">
            <li data-id="aba_individual" class="ativo">Individual</li>
            <li data-id="aba_coletivo">Coletivo</li>
            <li data-id="aba_empresarial">Empresarial</li>
        </ul>
    </div>

    <div id="myModalColetivo" class="fixed inset-0 z-50 flex justify-center items-start hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] z-40"></div>
        <!-- Conteúdo da Modal -->
        <div class="relative w-11/12 rounded-lg shadow-3xl p-2 z-50">
            <!-- Botão Fechar no Topo -->
            <div id="modalLoader" class="flex justify-center items-center h-32">
                <div class="dot-flashing">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
            <!-- Borda Animada -->
            <div class="relative p-1 rounded-lg animate-border overflow-hidden content-modal-coletivo hidden">
            </div>
        </div>
    </div>

    <div id="myModalIndividual" class="fixed inset-0 z-50 flex justify-center items-start hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] z-40"></div>
        <!-- Conteúdo da Modal -->
        <div class="relative w-11/12 rounded-lg shadow-3xl p-2 z-50">
            <!-- Botão Fechar no Topo -->
            <div id="modalLoaderIndividual" class="flex justify-center items-center h-32">
                <div class="dot-flashing">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
            <!-- Borda Animada -->
            <div class="relative p-1 rounded-lg animate-border overflow-hidden content-modal-individual hidden">
            </div>
        </div>
    </div>

    <div id="myModalEmpresarial" class="fixed inset-0 z-50 flex items-start justify-center hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] z-40"></div>
        <!-- Conteúdo da Modal -->
        <div class="relative w-11/12 rounded-lg shadow-3xl p-2 z-50">
            <!-- Botão Fechar no Topo -->
            <div id="modalLoaderEmpresa" class="flex justify-center items-start h-32">
                <div class="dot-flashing">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
            <!-- Borda Animada -->
            <div class="relative p-1 rounded-lg animate-border overflow-hidden content-modal-empresarial hidden">
            </div>
        </div>
    </div>






    <main data-aba id="aba_individual" class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-lg" style="width:95%;margin:8px auto;">
        <table id="tabela_individual" class="listarindividual text-left text-white w-full mt-3">
            <thead>
            <tr>
                <th>Data</th>
                <th>Cod.</th>
                <th>Corretor</th>
                <th>Cliente</th>
                <th>CPF</th>
                <th>Vidas</th>
                <th>Valor</th>
                <th>Vencimento</th>
                <th>Atrasado</th>
                <th>Status</th>
                <th>Ver</th>
                <th>Atrasado</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </main>

    <main data-aba id="aba_coletivo" class="hidden bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-lg" style="width:95%;margin:8px auto;">
        <table id="tabela_coletivo" class="table-auto table-sm text-white w-full listardados">
            <thead>
            <tr>
                <th>Data</th>
                <th>Cod.</th>
                <th>Corretor</th>
                <th>Cliente</th>
                <th>Admin</th>
                <th>CPF</th>
                <th>Vidas</th>
                <th>Valor</th>
                <th>Vencimento</th>
                <th>Status</th>
                <th>Ver</th>
                <th>Teste</th>
                <th>Data Nasc.</th>
                <th>Celular</th>
                <th>Email</th>
                <th>Cidade</th>
                <th>UF</th>
            </tr>
            </thead>
        </table>
    </main>

    <main data-aba id="aba_empresarial" class="hidden bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-lg" style="width:95%;margin:8px auto;">
        <table id="tabela_empresarial" class="table-auto table-sm text-white w-full listarempresarial">

            <thead>
            <tr>
                <th>Data</th>
                <th>Cod.</th>
                <th>Corretor</th>
                <th>Cliente</th>
                <th>CNPJ</th>
                <th>Vidas</th>
                <th>Valor</th>
                <th>Plano</th>
                <th>Vencimento</th>
                <th>Status</th>
                <th>Ver</th>
                <th>Resposta</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </main>




    <script>
        $(document).ready(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(".list_abas li").on('click',function(){
                $('li').removeClass('ativo');
                $(this).addClass("ativo");
                let id = $(this).attr('data-id');
                default_formulario = $('.coluna-right.'+id).html();
                $('main[data-aba]').addClass('hidden');
                //$('#'+id).removeClass('ocultar');
                $('#'+id).removeClass('hidden');
                $('tr').removeClass('textoforte');
                if($(this).attr('data-id') == "aba_individual") {
                    inicializarIndividual();
                } else if($(this).attr('data-id') == "aba_coletivo") {
                    inicializarColetivo();
                    //$('#mudar_mes_table').find('option').eq(0).prop('selected', true);
                } else if($(this).attr('data-id') == "aba_empresarial") {
                    inicializarEmpresarial();
                } else {
                    //inicializarOdonto();
                }
                //$("#cliente_id_alvo").val('');
                //$("#cliente_id_alvo_individual").val('');
                //$("#all_pendentes_individual").removeClass('textoforte-list');
                //$("ul#listar li.coletivo").removeClass('textoforte-list');
                //$("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
                //$("ul#listar_individual li.individual").removeClass('textoforte-list');
                //$("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
                //$("ul#listar_empresarial li.empresarial").removeClass('textoforte-list');
                //$("ul#grupo_finalizados_empresarial li.empresarial").removeClass('textoforte-list');
            });

            $("body").on("click",".ver_individual",function(e){
                e.preventDefault();
                let id = $(this).attr("data-id");
                $('#myModalIndividual').removeClass('hidden');
                $.ajax({
                    url:"{{route('gerente.modal.individual')}}",
                    method:"POST",
                    data: {
                        id
                    },
                    success:function(res) {
                        $('.content-modal-individual').removeClass('hidden');
                        $(".content-modal-individual").html(res);
                    }
                });
                //
                return false;
            });

            $("body").on("click",".ver_coletivo",function(e){
                e.preventDefault();
                let id = $(this).attr("data-id");
                $('#myModalColetivo').removeClass('hidden');
                $.ajax({
                    url:"{{route('gerente.modal.coletivo')}}",
                    method:"POST",
                    data: {id},
                    success:function(res) {
                        $('.content-modal-coletivo').removeClass('hidden');
                        $(".content-modal-coletivo").html(res);
                    }
                });
                return false;
            });


            $("body").on("click",".ver_empresarial",function(e){
                e.preventDefault();
                let contrato_id = $(this).attr('data-id');
                $.ajax({
                    url:"{{route('financeiro.gerente.modal.contrato.empresarial')}}",
                    method:"POST",
                    data: {
                        id: contrato_id
                    },
                    success:function(res){
                        $('.content-modal-empresarial').removeClass('hidden');
                        $(".content-modal-empresarial").html(res);
                    }
                });
                $('#myModalEmpresarial').removeClass('hidden');
                return false;
            });


            $("body").on('click','#closeModalEmpresarial',function(){
                $('#myModalEmpresarial').addClass('hidden');
            });

            $("body").on("click", "#myModalEmpresarial", function (event) {
                if(!$(event.target).closest(".content-modal-empresarial").length) {
                    $("#myModalEmpresarial").addClass("hidden");
                }
            });


            $("body").on("click",'#closeModalIndividual',function(){
                $("#myModalIndividual").addClass("hidden");
            });

            $("body").on("click", "#myModalIndividual", function (event) {
                if(!$(event.target).closest(".content-modal-individual").length) {
                    $("#myModalIndividual").addClass("hidden");
                }
            });

            $("body").on("click","#closeModalColetivo",function(){
                $("#myModalColetivo").addClass("hidden");
            });

            $("body").on("click", "#myModalColetivo", function (event) {
                if(!$(event.target).closest(".content-modal-coletivo").length) {
                    $("#myModalColetivo").addClass("hidden");
                }
            });





            function inicializarIndividual() {

                if($.fn.DataTable.isDataTable('.listarindividual')) {
                    $('.listarindividual').DataTable().destroy();
                }


                $(".listarindividual").DataTable({
                    dom: '<"flex justify-between"<"#title_individual">Bftr><t><"flex justify-between"lp>',
                    language: {
                        "search": "Pesquisar",
                        "paginate": {
                            "next": "Próx.",
                            "previous": "Ant.",
                            "first": "Primeiro",
                            "last": "Último"
                        },
                        "emptyTable": "Nenhum registro encontrado",
                        "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                        "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                        "infoFiltered": "(Filtrados de _MAX_ registros)",
                        "infoThousands": ".",
                        "loadingRecords": "Carregando...",
                        "processing": "Processando...",
                        "lengthMenu": "Exibir _MENU_ por página"
                    },
                    processing: true,
                    ajax: {
                        "url":"{{route('clientes.listar')}}",
                        dataSrc:""
                    },
                    "lengthMenu": [100,200,250,300,400],
                    "ordering": false,
                    "paging": true,
                    "searching": true,
                    deferRender: true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    columns: [
                        {data:"data",name:"data"},
                        {data:"orcamento",name:"orcamento"},
                        {data:"corretor",name:"corretor"},
                        {data:"cliente",name:"cliente"},
                        {data:"cpf",name:"cpf",
                            "createdCell": function (td, cellData, rowData, row, col) {
                                let cpf = cellData.substr(0,3)+"."+cellData.substr(3,3)+"."+cellData.substr(6,3)+"-"+cellData.substr(9,2);
                                $(td).html(cpf);
                            }
                        },
                        {data:"quantidade_vidas",name:"vidas"},
                        {data:"valor_plano",name:"valor_plano",render: $.fn.dataTable.render.number('.', ',', 2, '')},
                        {data:"vencimento",name:"vencimento"},
                        {data:"vencimento",name:"atrasado"},
                        {data:"parcelas",name:"parcelas"},
                        {data:"id",name:"ver"},
                        {data:"status",name:"status"},
                        {data:"estagiario",name:"estagiario"}

                    ],
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            title: 'vivaz',
                            text: 'Exportar',
                            className: 'btn-exportar', // Classe personalizada para estilo
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4] // Define as colunas a serem exportadas (ajuste conforme necessário)
                            },
                            filename: 'individual'
                        }
                    ],
                    "columnDefs": [
                        {"targets": 0,"width":"8%"},//data
                        {"targets": 1,"width":"8%"},//codi
                        {"targets": 2,"width":"14%",
                            "createdCell":function(td, cellData, rowData, row, col) {
                                let words = cellData.split(" ");
                                let corretor = "";

                                // Limita para as duas primeiras palavras
                                if (words.length > 2) {
                                    corretor = words.slice(0, 2).join(" ");
                                } else {
                                    corretor = cellData;
                                }

                                if(rowData.estagiario != cellData) {
                                    const estagiarioWords = rowData.estagiario.split(" ");
                                    let estagiarioFormatted;
                                    if (estagiarioWords.length > 1) {
                                        estagiarioFormatted = `${estagiarioWords[0]} ${estagiarioWords[1].substring(0, 3)}`;
                                    } else {
                                        estagiarioFormatted = estagiarioWords[0]; // Usar o nome original se tiver apenas uma palavra
                                    }
                                    $(td).html(corretor + " (" + estagiarioFormatted + ")");
                                } else {
                                    $(td).html(corretor);
                                }

                            }
                        },//Corretor
                        {"targets": 3,"width":"22%"},//cliente
                        {"targets": 4,"width":"12%"},//cpf
                        {"targets": 5,"width":"5%"},//vidas
                        {"targets": 6,"width":"8%"},//valor
                        {"targets": 7,"width":"8%"},//vencimento
                        {"targets": 8,"width":"3%","visible": false},
                        {"targets": 9,"width":"10%"},
                        {"targets": 10,"width":"2%",
                            "createdCell": function (td, cellData, rowData, row, col) {
                                let contrato_id = cellData;
                                if(cellData == "Cancelado") {

                                    $(td).html(`<div class='text-center text-white'>
                                            <a href="#" class="text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 div_info">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                            </a>
                                        </div>
                                    `);
                                } else {

                                    $(td).html(`<div class='text-center text-white'>
                                            <a href="#"
                                                data-id="${contrato_id}"
                                            class="text-white ver_individual">
                                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 div_info">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                            </a>
                                        </div>
                                    `);
                                }
                            }
                        },
                        {"targets": 11,"width":"3%","visible": false},
                        {"targets": 12,"visible": false},
                    ],
                    "initComplete": function( settings, json ) {
                        $('.btn-exportar').css({
                            'background-color': '#4CAF50',
                            'color': '#FFF',
                            'border': 'none',
                            'padding': '8px 16px',
                            'border-radius': '4px'
                        });
                    },
                    "drawCallback": function( settings ) {},
                    footerCallback: function (row, data, start, end, display) {}
                });
            }
            inicializarIndividual();

            function inicializarColetivo() {
                if ($.fn.DataTable.isDataTable('.listardados')) {
                    $('.listardados').DataTable().destroy();
                }
                $(".listardados").DataTable({
                    dom: '<"flex justify-between"<"#title_individual">Bftr><t><"flex justify-between"lp>',
                    language: {
                        "search": "Pesquisar",
                        "paginate": {
                            "next": "Próx.",
                            "previous": "Ant.",
                            "first": "Primeiro",
                            "last": "Último"
                        },
                        "emptyTable": "Nenhum registro encontrado",
                        "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                        "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                        "infoFiltered": "(Filtrados de _MAX_ registros)",
                        "infoThousands": ".",
                        "loadingRecords": "Carregando...",
                        "processing": "Processando...",
                        "lengthMenu": "Exibir _MENU_ por página"
                    },
                    processing: true,
                    ajax: {
                        "url":"{{route('clientes.coletivo.listar')}}",
                        dataSrc:""
                    },
                    "lengthMenu": [1000,2000],
                    "ordering": false,
                    "paging": true,
                    "searching": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    columns: [
                        {data:"data",name:"data"},
                        {data:"orcamento",name:"codigo_externo"},
                        {data:"corretor",name:"corretor"},
                        {data:"cliente",name:"cliente"},
                        {data:"administradora",name:"administradora"},
                        {data:"cpf",name:"cpf"},
                        {data:"quantidade_vidas",name:"vidas"},
                        {data:"valor_plano",name:"valor_plano",render: $.fn.dataTable.render.number('.', ',', 2, 'R$ ')},
                        {data:"vencimento",name:"Vencimento"},
                        {data:"status",name:"status"},
                        {data:"id",name:"detalhes"},
                        {data:"resposta",name:"resposta",visible:false},
                        {data:"nascimento",name:"nascimento",visible:false},
                        {data:"fone",name:"fone",visible:false},
                        {data:"email",name:"email",visible:false},
                        {data:"cidade",name:"cidade",visible:false},
                        {data:"bairro",name:"bairro",visible:false},
                        {data:"rua",name:"rua",visible:false},
                        {data:"cep",name:"cep",visible:false},
                    ],
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            title: 'vivaz-coletivo',
                            text: 'Exportar',
                            className: 'btn-exportar', // Classe personalizada para estilo
                            exportOptions: {
                                columns: [0,1,3,4,5,6] // Define as colunas a serem exportadas (ajuste conforme necessário)
                            },
                            filename: 'Coletivo'
                        }
                    ],
                    "columnDefs": [
                        {
                            "targets": 10,
                            "width":"2%",
                            "createdCell": function (td, cellData, rowData, row, col) {
                                let contrato_id = cellData;
                                $(td).html(
                                    `<div class='text-center text-white'>
                            <a href="#" data-id="${contrato_id}" class="text-white open-modal ver_coletivo">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 div_info">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </a>
                        </div>`
                                );
                            },
                        },
                    ],
                    "initComplete": function( settings, json ) {

                        $('.btn-exportar').css({
                            'background-color': '#4CAF50',
                            'color': '#FFF',
                            'border': 'none',
                            'padding': '8px 16px',
                            'border-radius': '4px'
                        });


                    },
                    "rowCallback": function(row, data, index) {},
                    "footerCallback": function (row, data, start, end, display) {}
                });
            }

            function inicializarEmpresarial() {

                if($.fn.DataTable.isDataTable('.listarempresarial')) {
                    $('.listarempresarial').DataTable().destroy();
                }

                tableempresarial = $(".listarempresarial").DataTable({
                    dom: '<"flex justify-between"<"#title_empresarial">Bftr><t><"flex justify-between"lp>',
                    language: {
                        "search": "Pesquisar",
                        "paginate": {
                            "next": "Próx.",
                            "previous": "Ant.",
                            "first": "Primeiro",
                            "last": "Último"
                        },
                        "emptyTable": "Nenhum registro encontrado",
                        "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                        "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                        "infoFiltered": "(Filtrados de _MAX_ registros)",
                        "infoThousands": ".",
                        "loadingRecords": "Carregando...",
                        "processing": "Processando...",
                        "lengthMenu": "Exibir _MENU_ por página",
                        "zeroRecords": "Nenhum registro encontrado"
                    },
                    ajax: {
                        "url":"{{route('clientes.empresarial.listar')}}",
                        dataSrc:""
                    },
                    "lengthMenu": [1000,2000,3000],
                    "ordering": false,
                    "paging": true,
                    "searching": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "processing": true,
                    columns: [
                        {data:"created_at",name:"created_at",width:"8%"},
                        {data:"codigo_externo",name:"codigo_externo",width:"6%"},
                        {data:"usuario",name:"usuario",width:"10%"},
                        {data:"razao_social",name:"razao_social",width:"31%"},
                        {data:"cnpj",name:"cnpj",width:"14%"},
                        {data:"quantidade_vidas",name:"vidas",width:"5%"},
                        {data:"valor_plano",name:"valor_plano",width:"8%",render: $.fn.dataTable.render.number('.', ',', 2, 'R$ ')},
                        {data:"plano",name:"plano",width:"10%"},
                        {data:"vencimento",name:"vencimento",width:"9%"},
                        {data:"status",name:"status",width:"10%"},
                        {data:"id",name:"id",width:"4%"},
                        {data:"resposta",name:"resposta",width:"10%",visible:false}
                    ],
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            title: 'vivaz-empresarial',
                            text: 'Exportar',
                            className: 'btn-exportar', // Classe personalizada para estilo
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5,6] // Define as colunas a serem exportadas (ajuste conforme necessário)
                            },
                            filename: 'vivaz-empresarial'
                        }
                    ],
                    "columnDefs": [
                        {
                            "targets": 2,
                            "createdCell":function(td, cellData, rowData, row, col) {
                                let words = cellData.split(" ");

                                // Limita para as duas primeiras palavras
                                if (words.length > 2) {
                                    $(td).html(words.slice(0, 2).join(" ") + "...");
                                } else {
                                    $(td).html(cellData);  // Se for menos de 2 palavras, mostra tudo
                                }
                            }
                        },//Corretor
                        {
                            "targets": 3,
                            "createdCell":function(td, cellData, rowData, row, col) {
                                let words = cellData.split(" ");

                                // Limita para as duas primeiras palavras
                                if (words.length > 4) {
                                    $(td).html(words.slice(0,4).join(" "));
                                } else {
                                    $(td).html(cellData);  // Se for menos de 2 palavras, mostra tudo
                                }
                            }
                        },//Razao Social
                        {
                            "targets": 10,
                            "createdCell": function (td, cellData, rowData, row, col) {
                                let id = cellData;
                                $(td).html(`<div class='text-center text-white'>
                                            <a href="#" data-id="${id}"
                                              class="text-white ver_empresarial">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 div_info">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                            </a>
                                        </div>
                                    `);
                            },
                        },
                    ],
                    "initComplete": function( settings, json ) {
                        $('.btn-exportar').css({
                            'background-color': '#4CAF50',
                            'color': '#FFF',
                            'border': 'none',
                            'padding': '8px 16px',
                            'border-radius': '4px'
                        });
                    },
                    "drawCallback":function(settings) {},
                    footerCallback: function (row, data, start, end, display) {

                    }
                });
            }
        });
    </script>

        <style>
            .list_abas {list-style: none;display: flex;border-bottom: 1px solid white;margin: 4px 0;padding: 0;}
            .list_abas li {color: #fff;width: 150px;padding: 8px 5px;text-align:center;border-radius: 5px 5px 0 0;background-color:rgba(254,254,254,0.18);}
            .list_abas li:hover {cursor: pointer;}
            .list_abas li:nth-of-type(2) {margin: 0 1%;}
            .list_abas li:nth-of-type(4) {margin-left:1%;}
            .ativo {background-color:#FFF !important;color: #000 !important;}
            /*.hidden {display: none;}*/
            table td, table th {font-size:0.7em;}


            #tabela_individual_filter label {color:#FFF;}
            #tabela_coletivo_filter label {color:#FFF;}
            #tabela_empresarial_filter label {color:#FFF;}


            #tabela_individual_filter input[type='search'] {background-color: #FFF !important;margin-right:5px;margin-top:3px;color:black !important;}
            #tabela_coletivo_filter input[type='search'] {background-color: #FFF !important;margin-right:5px;margin-top:3px;color:black !important;}
            #tabela_empresarial_filter input[type='search'] {background-color: #FFF !important;color:black !important;}



        </style>


</x-app-layout>
