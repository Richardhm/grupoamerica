var tableempresarial;
function inicializarEmpresarial(corretora_id = null) {

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
            "url":urlGeralEmpresarialPendentes,
            data: function (d) {
                d.corretora_id = corretora_id
            }
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
                    let corretor = rowData['usuario'];
                    let cidade = rowData['cidade'];
                    let email = rowData['email'];
                    let plano = rowData['plano'];
                    let celular = rowData['fone'];
                    let vidas = rowData['quantidade_vidas'];
                    let cnpj = rowData['cnpj'];
                    let razao_social = rowData['razao_social'];
                    let data_vencimento = rowData['vencimento'];
                    let codigo_corretora = rowData['codigo_corretora'];
                    let codigo_odonto = rowData['codigo_odonto'];
                    let codigo_saude = rowData['codigo_saude'];
                    let senha_cliente = rowData['senha_cliente'];

                    let valor_boleto = rowData['valor_boleto'];
                    let valor_odonto = rowData['valor_odonto'];
                    let valor_saude = rowData['valor_saude'];
                    let taxa_adesao = rowData['taxa_adesao'];
                    let vencimento_boleto = rowData['vencimento_boleto'];
                    let data_boleto = rowData['data_boleto'];
                    let tabela_origens = rowData['tabela_origens'];
                    let responsavel = rowData['responsavel'];
                    let plano_contratado = rowData['plano_contrado'];
                    let codigo_externo = rowData['codigo_externo'];
                    let valor_total = rowData['valor_total'];
                    let uf = rowData['uf'];
                    let data_analise = rowData['data_analise'];




                    $(td).html(`<div class='text-center text-white'>
                                            <a href="#" data-id="${id}" data-vendedor="${corretor}" data-plano="${plano}" data-origens="${tabela_origens}"
                                              data-razao_social="${razao_social}" data-cnpj="${cnpj}" data-vidas="${vidas}" data-celular="${celular}"
                                              data-email="${email}" data-responsavel="${responsavel}" data-cidade="${cidade}"
                                              data-plano_contrado="${plano_contratado}" data-codigo_corretora="${codigo_corretora}"
                                              data-codigo_saude="${codigo_saude}" data-codigo_odonto="${codigo_odonto}" data-senha_cliente="${senha_cliente}"
                                              data-valor_saude="${valor_saude}" data-valor_odonto="${valor_odonto}" data-valor_total="${valor_total}"
                                              data-taxa_adesao="${taxa_adesao}" data-valor_boleto="${valor_boleto}" data-vencimento_boleto="${vencimento_boleto}"
                                              data-boleto="${data_boleto}" data-uf="${uf}" data-codigo_externo="${codigo_externo}"
                                              data-analise="${data_analise}"
                                              class="text-white open-modal-empresarial">

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
            //$('#title_empresarial').html("<h4 style='font-size:1em;margin-top:10px;'>Listagem(Completa)</h4>");
            let corretoresUnicos = new Set();
            this.api().column(2).data().each(function(v) {
                corretoresUnicos.add(v);
            });
            let corretoresOrdenados = Array.from(corretoresUnicos).sort();
            $('#mudar_user_empresarial').empty();
            $('#mudar_user_empresarial').append('<option value="todos">-- Escolher Corretor --</option>');
            corretoresOrdenados.forEach(function(corretor) {
                $('#mudar_user_empresarial').append('<option value="' + corretor + '">' + corretor + '</option>');
            });

            let planosAdmin = new Set();
            this.api().column(7).data().each(function(v) {
                planosAdmin.add(v);
            });
            let planosOrdenados = Array.from(planosAdmin).sort();
            $('#mudar_planos_empresarial').empty();
            $('#mudar_planos_empresarial').append('<option value="todos">-- Escolher Planos --</option>');
            planosOrdenados.forEach(function(corretor) {
                $('#mudar_planos_empresarial').append('<option value="' + corretor + '">' + corretor + '</option>');
            });

            let countEmAnalise  = this.api().column(9).data().filter((value, index) => value === 'Em Análise').length;
            let countPagamento1 = this.api().column(9).data().filter((value, index) => value === 'Pag. 1º Parcela').length;
            let countPagamento2 = this.api().column(9).data().filter((value, index) => value === 'Pag. 2º Parcela').length;
            let countPagamento3 = this.api().column(9).data().filter((value, index) =>  value === 'Pag. 3º Parcela').length;
            let countPagamento4 = this.api().column(9).data().filter((value, index) => value === 'Pag. 4º Parcela').length;
            let countPagamento5 = this.api().column(9).data().filter((value, index) =>  value === 'Pag. 5º Parcela').length;
            let countPagamento6 = this.api().column(9).data().filter((value, index) => value === 'Pag. 6º Parcela').length;
            let countAtrasado   = this.api().column(11).data().filter((value, index) => value === 'Atrasado').length;
            let countCancelados = this.api().column(9).data().filter((value, index) =>  value === 'Cancelado').length;
            let countFinalizado = this.api().column(9).data().filter((value, index) =>  value === 'Finalizado').length;
            $(".empresarial_quantidade_em_analise").text(countEmAnalise);
            $(".empresarial_quantidade_1_parcela").text(countPagamento1);
            $(".empresarial_quantidade_2_parcela").text(countPagamento2);
            $(".empresarial_quantidade_3_parcela").text(countPagamento3);
            $(".empresarial_quantidade_4_parcela").text(countPagamento4);
            $(".empresarial_quantidade_5_parcela").text(countPagamento5);
            $(".empresarial_quantidade_6_parcela").text(countPagamento6);
            $(".empresarial_quantidade_cancelado").text(countCancelados);
            $(".quantidade_empresarial_finalizado").text(countFinalizado);
            $(".empresarial_quantidade_atrasado").text(countAtrasado);

            let anos = this.api().column(0).data().toArray().map(function(value) {
                let year = new Date(value).getFullYear();
                return !isNaN(year) ? year : null;
            });
            let anosUnicos = new Set(anos.filter(function(ano) {return ano !== null;}));


            let selectAno = $('#mudar_ano_table_empresarial');
            selectAno.empty(); // Limpar opções existentes
            selectAno.append('<option value="" selected>- Ano -</option>'); // Opção padrão
            anosUnicos.forEach(function(ano) {
                selectAno.append('<option value="' + ano + '">' + ano + '</option>');
            });

        },
        "drawCallback":function(settings) {

        },
        footerCallback: function (row, data, start, end, display) {
            let intVal = function (i) {return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;};
            total = this.api().column(6,{search: 'applied'}).data().reduce(function (a, b) {return intVal(a) + intVal(b);},0);
            total_vidas = this.api().column(5,{search: 'applied'}).data().reduce(function (a, b) {return intVal(a) + intVal(b);},0);
            total_linhas = this.api().column(5,{search: 'applied'}).data().count();
            let total_br = total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
            $(".total_por_orcamento_empresarial").html(total_linhas);
            $(".total_por_vida_empresarial").html(total_vidas);
            $(".total_por_page_empresarial").html(total_br);
        }
    });


}

$('#tabela_empresarial').on('click', 'tbody tr', function () {
    tableempresarial.$('tr').removeClass('textoforte');
    $(this).closest('tr').addClass('textoforte');
});



