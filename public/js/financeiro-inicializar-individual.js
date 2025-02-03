function inicializarIndividual(corretora_id = null,refresh = null) {
    if($.fn.DataTable.isDataTable('.listarindividual')) {
        $('.listarindividual').DataTable().destroy();
    }


    table_individual = $(".listarindividual").DataTable({
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
            "url":urlGeralIndividualPendentes,
            data: function (d) {
                d.corretora_id = corretora_id,
                d.refresh = refresh
            }
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
                    columns: [0, 1, 2, 3, 4, 5,6] // Define as colunas a serem exportadas (ajuste conforme necessário)
                },
                filename: 'vivaz'
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
                    if(cellData == "Cancelado") {
                        var id = cellData;
                        $(td).html(`<div class='text-center text-white'>
                                            <a href="/financeiro/cancelado/detalhes/${id}" class="text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 div_info">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                            </a>
                                        </div>
                                    `);
                    } else {
                        var id = rowData.id;
                        let corretor = rowData['corretor'];
                        let cpf = rowData['cpf'];
                        let data_criacao = rowData['data'];
                        let data_nascimento = rowData['data_nascimento'];
                        let email = rowData['email'];
                        let celular = rowData['fone'];
                        let codigo_externo = rowData['codigo_externo'];
                        let status = rowData['parcelas'];
                        let quantidade_vidas = rowData['quantidade_vidas'];
                        let rua = rowData['rua'];
                        let valor_plano = rowData['valor_plano'];
                        let cliente = rowData['cliente'];
                        let cidade = rowData['cidade'];
                        let cep = rowData['cep'];
                        let bairro = rowData['bairro'];
                        let carteirinha = rowData['carteirinha'];
                        let complementos = rowData['complemento'];
                        let uf = rowData['uf'];
                        let valor_adesao = rowData['valor_adesao'];
                        let data_vigencia = rowData['data_vigencia'];
                        let data_boleto = rowData['data_boleto'];
                        let user_id = rowData['user_id'];
                        $(td).html(`<div class='text-center text-white'>
                                            <a href="#"
                                                data-corretor="${corretor}"
                                                data-id="${id}"
                                                data-user_id="${user_id}"
                                                data-vidas="${quantidade_vidas}"
                                                data-status="${status}"
                                                data-rua="${rua}"
                                                data-cpf="${cpf}"
                                                data-criacao="${data_criacao}"
                                                data-nascimento="${data_nascimento}"
                                                data-email="${email}"
                                                data-celular="${celular}"
                                                data-codigo_externo="${codigo_externo}"
                                                data-valor_plano="${valor_plano}"
                                                data-cliente="${cliente}"
                                                data-cidade="${cidade}"
                                                data-cep="${cep}"
                                                data-cidade="${cidade}"
                                                data-bairro="${bairro}"
                                                data-carteirinha="${carteirinha}"
                                                data-complemento="${complementos}"
                                                data-uf="${uf}"
                                                data-valor_adesao="${valor_adesao}"
                                                data-data_vigencia="${data_vigencia}"
                                                data-data_boleto="${data_boleto}"

                                            class="text-white open-model-individual">
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
            let countPagamento1 = this.api().column(9).data().filter((value, index) => value === 'Pag. 1º Parcela').length;
            let countPagamento2 = this.api().column(9).data().filter((value, index) => value === 'Pag. 2º Parcela').length;
            let countPagamento3 = this.api().column(9).data().filter((value, index) =>  value === 'Pag. 3º Parcela').length;
            let countPagamento4 = this.api().column(9).data().filter((value, index) => value === 'Pag. 4º Parcela').length;
            let countPagamento5 = this.api().column(9).data().filter((value, index) =>  value === 'Pag. 5º Parcela').length;
            let countPagamento6 = this.api().column(9).data().filter((value, index) => value === 'Pag. 6º Parcela').length;
            let finalizado = this.api().column(9).data().filter((value, index) => value === 'Finalizado').length;
            let countCancelados = this.api().column(9).data().filter((value, index) =>  value === 'Cancelado').length;
            let countAprovado = this.api().column(11).data().filter((value, index) =>  value === 'Aprovado').length;
            let countAtrasadoTeste = this.api().rows().count(); // Inicialmente, contamos todas as linhas
            countAtrasadoTeste = countAtrasadoTeste - countPagamento6 - finalizado - countCancelados - countAprovado;
            let tablein = $('.listarindividual').DataTable();
            $(".individual_quantidade_1_parcela").text(countPagamento1);
            $(".individual_quantidade_2_parcela").text(countPagamento2);
            $(".individual_quantidade_3_parcela").text(countPagamento3);
            $(".individual_quantidade_4_parcela").text(countPagamento4);
            $(".individual_quantidade_5_parcela").text(countPagamento5);
            $(".individual_quantidade_6_parcela").text(finalizado);
            $(".individual_quantidade_cancelado").text(countCancelados);
            $(".individual_quantidade_atrasado").text(countAtrasadoTeste);
            let corretores = this.api().column(12).data().unique(); // Coluna 2 tem os corretores
            let selectUsuarioIndividual = $('#select_usuario_individual');
            selectUsuarioIndividual.empty(); // Limpa o select
            selectUsuarioIndividual.append('<option value="">-- Todos os Corretores --</option>'); // Adiciona uma opção para todos
            corretores.each(function(d) {
                selectUsuarioIndividual.append(`<option value="${d}" style="color:black;">${d}</option>`);
            });

            let selectAno = $('#mudar_ano_table');
            let selectMes = $('#mudar_mes_table');

            const nomesMeses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];


            function atualizarMeses() {
                let mesesUnicos = new Set();

                // Itera pela coluna 0 (datas) e encontra todos os meses únicos
                table_individual.column(0).data().each(function(value) {
                    if (value) {
                        let dataParts = value.split('/');
                        if (dataParts.length === 3) {
                            let mes = parseInt(dataParts[1]) - 1; // Meses zero-indexados
                            if (!isNaN(mes)) {
                                mesesUnicos.add(mes); // Adiciona o mês à lista de meses únicos
                            }
                        }
                    }
                });

                // Ordena os meses
                let mesesOrdenados = Array.from(mesesUnicos).sort((a, b) => a - b);

                // Limpa e preenche o select de meses
                selectMes.empty().append('<option value="">--Mês--</option>');
                mesesOrdenados.forEach(mes => {
                    selectMes.append(new Option(nomesMeses[mes], mes + 1)); // Exibe o nome do mês
                });
            }

            //Evento de change para o select de ano
            //selectAno.on('change', filtrarTabela);

            //Evento de change para o select de mês
            //selectMes.on('change', filtrarTabela);

            // Inicializa os selects de ano e mês com os dados da tabela
            function inicializarFiltros() {
                let anos = new Set();

                // Itera pela coluna 0 (datas) e encontra anos únicos
                table_individual.column(0).data().each(function(value) {
                    if (value) {
                        let dataParts = value.split('/');
                        if (dataParts.length === 3) {
                            let ano = parseInt(dataParts[2]);
                            if (!isNaN(ano)) {
                                anos.add(ano);
                            }
                        }
                    }
                });

                // Limpa e preenche o select de anos
                selectAno.empty().append('<option value="" class="text-center">--Ano--</option>');
                Array.from(anos).sort((a, b) => a - b).forEach(ano => {
                    selectAno.append(new Option(ano, ano));
                });

                // Preenche o select de meses com todos os meses da tabela
                atualizarMeses();
            }

            // Chama a função de inicialização quando a tabela estiver pronta
            inicializarFiltros();






        },
        "drawCallback": function( settings ) {
            var api = this.api();
            if(settings.ajax.url.split('/')[6] == "atrsado") {
                api.column(8).visible(true);
            } else {
                api.column(8).visible(false);
            }
        },
        footerCallback: function (row, data, start, end, display) {
            var intVal = (i) => typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            total = this.api().column(6,{search: 'applied'}).data().reduce(function (a, b) {return intVal(a) + intVal(b);}, 0);
            total_vidas = this.api().column(5,{search: 'applied'}).data().reduce(function (a, b) {return intVal(a) + intVal(b);},0);
            total_linhas = this.api().column(5,{search: 'applied'}).data().count();
            total_br = total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
            $(".total_por_page").html(total_br);
            $(".total_por_vida").html(total_vidas);
            $(".total_por_orcamento").html(total_linhas);
        }
    });
}

$('#tabela_individual').on('click', 'tbody tr', function () {
    table_individual.$('tr').removeClass('textoforte');
    $(this).closest('tr').addClass('textoforte');
});
inicializarIndividual();
