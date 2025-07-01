function inicializarIndividual(corretora_id = null,refresh = null,corretor_id = null) {

    if($.fn.DataTable.isDataTable('.listarindividual')) {
        $('.listarindividual').DataTable().destroy();
    }
    const infoIconSVG = `
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 div_info">
          <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        </svg>`;

    function formatarCpf(cpf) {
        return cpf.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, "$1.$2.$3-$4");
    }

    table_individual = $(".listarindividual").DataTable({
        dom: '<"flex justify-between"<"#title_individual">Bftr><t><"flex justify-between"lp>',
        ajax: {
            "url":urlGeralIndividualPendentes,
            data: function (d) {
                d.corretora_id = corretora_id;
                d.refresh = refresh;
                d.corretor_id = corretor_id;
                let anoSelecionado = $("#mudar_ano_table").val();
                let mesSelecionado = $("#mudar_mes_table").val();

                // Só envia se não for vazio e não for o placeholder
                d.ano = (anoSelecionado && anoSelecionado !== '' && anoSelecionado !== '--Ano--') ? anoSelecionado : '';
                d.mes = (mesSelecionado && mesSelecionado !== '' && mesSelecionado !== '--Mês--') ? mesSelecionado : '';
            }
        },
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
        lengthMenu: [100,200,250,300,400],
        pageLength: 100,
        serverSide: true,
        ordering: false,
        paging: true,
        searching: true,
        deferRender: true,
        info: true,
        autoWidth: false,
        responsive: true,
        columns: [
            /*1*/{data:"data",name:"data","width":"8%"},
            /*2*/{data:"orcamento",name:"orcamento","width":"6%"},
            /*3*/{data:"corretor",name:"corretor","width":"9%",},
            /*4*/{data:"cliente",name:"cliente","width":"9%",},
            /*5*/{data:"cpf",name:"cpf","width":"8%",render: data => formatarCpf(data)},
            /*6*/{data:"quantidade_vidas",name:"vidas","width":"5%",className: "text-center"},
            /*7*/{data:"valor_plano",name:"valor_plano",render: $.fn.dataTable.render.number('.', ',', 2, ''),"width":"5%"},
            /*8*/{data:"vencimento",name:"vencimento","width":"8%"},
            /*9*/{data:"vencimento",name:"atrasado"},
            /*10*/{data:"parcelas",name:"parcelas","width":"10%"},
            /*11*/{data:"data_nascimento",name:"data_nascimento","width":"7%"},
            /*12*/{data:"fone",name:"fone"},
            /*13*/{data:"id",name:"ver","width":"2%",
                "createdCell": function (td, cellData, rowData, row, col) {
                    if(cellData == "Cancelado") {
                        var id = cellData;
                        $(td).html(`<div class='text-center text-white'>
                                            <a href="/financeiro/cancelado/detalhes/${id}" class="text-white">
                                               ${infoIconSVG}
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
            /*14*/{data:"status",name:"status"},
            /*15*/{data:"estagiario",name:"estagiario"},
            /*16*/{data:"corretor_id",name:"corretor_id"},


        ],
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Grupo',
                text: 'Exportar Excel',
                className: 'btn-exportar', // Classe personalizada para estilo
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5,6] // Define as colunas a serem exportadas (ajuste conforme necessário)
                },
                filename: 'grupoamerica'
            }
        ],
        "initComplete": function( settings, json ) {
            $('.btn-exportar').css({
                'background-color': '#4CAF50',
                'color': '#FFF',
                'border': 'none',
                'padding': '8px 16px',
                'border-radius': '4px'
            });

            let tablein = $('.listarindividual').DataTable();
            $(".individual_quantidade_1_parcela").text(json.contagens.parcela1);
            $(".individual_quantidade_2_parcela").text(json.contagens.parcela2);
            $(".individual_quantidade_3_parcela").text(json.contagens.parcela3);
            $(".individual_quantidade_4_parcela").text(json.contagens.parcela4);
            $(".individual_quantidade_5_parcela").text(json.contagens.parcela5);
            $(".individual_quantidade_6_parcela").text(json.contagens.finalizado);
            $(".individual_quantidade_cancelado").text(json.contagens.cancelado);
            //$(".individual_quantidade_atrasado").text(json.contagens.atrasado);
            // let corretores = this.api().column(2).data().unique(); // Coluna 2 tem os corretores
            // let selectUsuarioIndividual = $('#select_usuario_individual');
            // selectUsuarioIndividual.empty(); // Limpa o select
            // selectUsuarioIndividual.append('<option value="">-- Todos os Corretores --</option>'); // Adiciona uma opção para todos
            // corretores.each(function(d) {
            //     selectUsuarioIndividual.append(`<option value="${d}" style="color:black;">${d}</option>`);
            // });

            let api = this.api();

            // api.columns([9,14,15,16]) // Colunas status/estagiario/corretor_id (ajuste as posições como necessário)
            //     .visible(false, false); // Oculta sem redesenhar imediatamente

            // HTML do select para corretores
            let selectUsuarioIndividual = $('#select_usuario_individual');
            //selectUsuarioIndividual.empty(); // Limpa o select para evitar duplicação

            // Adiciona a primeira opção
            selectUsuarioIndividual.append('<option value="" data-id="">-- Todos os Corretores --</option>');

            // Captura dados das colunas corretor (2) e corretor_id (17)
            let corretores = {};
            api.rows({ search: 'applied' }).data().each(function(rowData) {
                let nomeCorretor = rowData.corretor; // Coluna 2
                let corretorId = rowData.corretor_id; // Coluna 17

                // Evita duplicados
                if (nomeCorretor && !corretores[corretorId]) {
                    corretores[corretorId] = nomeCorretor;
                }
            });

            // Itera sobre os corretores únicos e adiciona ao select
            $.each(corretores, function(id, nome) {
                selectUsuarioIndividual.append(
                    `<option value="${nome}" data-id="${id}" style="color:black;">${nome}</option>`
                );
            });
            let selectAno = $('#mudar_ano_table');
            let selectMes = $('#mudar_mes_table');

            const nomesMeses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

            // Processar os dados retornados do backend
            let anos = new Set(); // Anos únicos
            let mesesPorAno = {}; // Objeto para mapear [ano] -> [meses disponíveis]

            // Itera pelo array `mesesAnos` e organiza os anos e meses
            json.mesesAnos.forEach(function(mesAno) {
                let [ano, mes] = mesAno.split('-'); // Divide 'YYYY-MM' em ano e mês
                anos.add(ano); // Adiciona o ano no Set de anos únicos

                // Garante que cada ano tenha um array de meses
                if (!mesesPorAno[ano]) {
                    mesesPorAno[ano] = [];
                }

                // Adiciona o mês ao respectivo ano, garantindo que não se repita
                mesesPorAno[ano].push(parseInt(mes) - 1); // Mês zero-indexado
            });

            // 1. Popular o select de anos
            selectAno.empty().append('<option value="">--Ano--</option>');
            Array.from(anos).sort((a, b) => b - a).forEach(function(ano) {
                selectAno.append(new Option(ano, ano));
            });

            // 2. Evento: quando selecionar um ano, filtrar os meses
            selectAno.on('change', function() {
                let anoSelecionado = $(this).val(); // Ano escolhido pelo usuário
                selectMes.val('').trigger('change'); // Limpa o valor e dispara o evento
                // Atualiza o select de meses com os meses do ano escolhido
                selectMes.empty().append('<option value="">--Mês--</option>');

                if (anoSelecionado && mesesPorAno[anoSelecionado]) {
                    let meses = mesesPorAno[anoSelecionado]; // Obtém os meses para o ano selecionado
                    meses.sort((a, b) => a - b).forEach(function(mes) {
                        selectMes.append(new Option(nomesMeses[mes], mes + 1)); // Exibe o nome do mês, mas envia o número
                    });
                }
                table_individual.ajax.reload();
            });

            // 3. Limpar o select de meses inicialmente
            selectMes.empty().append('<option value="">--Mês--</option>');

            // function atualizarMeses() {
            //     let mesesUnicos = new Set();
            //
            //     // Itera pela coluna 0 (datas) e encontra todos os meses únicos
            //     table_individual.column(0).data().each(function(value) {
            //         if (value) {
            //             let dataParts = value.split('/');
            //             if (dataParts.length === 3) {
            //                 let mes = parseInt(dataParts[1]) - 1; // Meses zero-indexados
            //                 if (!isNaN(mes)) {
            //                     mesesUnicos.add(mes); // Adiciona o mês à lista de meses únicos
            //                 }
            //             }
            //         }
            //     });
            //
            //     // Ordena os meses
            //     let mesesOrdenados = Array.from(mesesUnicos).sort((a, b) => a - b);
            //
            //     // Limpa e preenche o select de meses
            //     selectMes.empty().append('<option value="">--Mês--</option>');
            //     mesesOrdenados.forEach(mes => {
            //         selectMes.append(new Option(nomesMeses[mes], mes + 1)); // Exibe o nome do mês
            //     });
            // }
            //
            // // Inicializa os selects de ano e mês com os dados da tabela
            // function inicializarFiltros() {
            //     let anos = new Set();
            //
            //     // Itera pela coluna 0 (datas) e encontra anos únicos
            //     table_individual.column(0).data().each(function(value) {
            //         if (value) {
            //             let dataParts = value.split('/');
            //             if (dataParts.length === 3) {
            //                 let ano = parseInt(dataParts[2]);
            //                 if (!isNaN(ano)) {
            //                     anos.add(ano);
            //                 }
            //             }
            //         }
            //     });
            //
            //     // Limpa e preenche o select de anos
            //     selectAno.empty().append('<option value="" class="text-center">--Ano--</option>');
            //     Array.from(anos).sort((a, b) => a - b).forEach(ano => {
            //         selectAno.append(new Option(ano, ano));
            //     });
            //     // Preenche o select de meses com todos os meses da tabela
            //     atualizarMeses();
            // }
            // // Chama a função de inicialização quando a tabela estiver pronta
            // inicializarFiltros();
        },
        "drawCallback": function( settings ) {
            // let api = this.api();
            // api.columns([9,14,15,16]) // Índices das colunas status/estagiario/corretor_id
            //     .visible(false, false); // Oculta sem redesenhar novamente
            //
            // api.column(13).visible(false);
            // api.column(14).visible(false);
            // if(settings.ajax.url.split('/')[6] == "atrsado") {
            //     api.column(8).visible(true);
            // } else {
            //     api.column(8).visible(false);
            // }

        },
        footerCallback: function (row, data, start, end, display) {
            var intVal = (i) => typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            total = this.api().column(6,{search: 'applied'}).data().reduce(function (a, b) {return intVal(a) + intVal(b);}, 0);
            total_vidas = this.api().column(5,{search: 'applied'}).data().reduce(function (a, b) {return intVal(a) + intVal(b);},0);
            total_linhas = this.api().column(5,{search: 'applied'}).data().count();
            total_br = total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});

            let totalAtrasados = this.api().column(13, { search: 'applied' }).data().reduce((count, valor) => {
                return valor === "Atrasado" ? count + 1 : count;
            }, 0);


            // Atualizar o total de atrasados no DOM
            $(".individual_quantidade_atrasado").html(`${totalAtrasados}`);



            // console.log(total_linhas);
            // console.log(total_vidas);

            // $(".total_por_page").html(total_br);
            //$(".total_por_vida").html(total_vidas);
            // $(".total_por_orcamento").html(total_linhas);
        }
    });
}

$('#tabela_individual').on('click', 'tbody tr', function () {
    table_individual.$('tr').removeClass('textoforte');
    $(this).closest('tr').addClass('textoforte');
});







inicializarIndividual();


