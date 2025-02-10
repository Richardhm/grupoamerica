
function inicializarColetivo(corretora_id = null) {
    console.log("Olaaaaa");
    if ($.fn.DataTable.isDataTable('.listardados')) {
        $('.listardados').DataTable().destroy();
    }
    table = $(".listardados").DataTable({
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
            "url":urlGeralColetivoPendentes,
            data: function (d) {
                d.corretora_id = corretora_id;
            }
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
                    columns: [0,1,2,3,4,5,6] // Define as colunas a serem exportadas (ajuste conforme necessário)
                },
                filename: 'vivaz-coletivo'
            }
        ],
        "columnDefs": [
            {
                "targets": 10,
                "width":"2%",
                "createdCell": function (td, cellData, rowData, row, col) {
                    let id = cellData;
                    let cliente = rowData['cliente'];
                    let cpf = rowData['cpf'];
                    let email = rowData['email'];
                    let nascimento = rowData['nascimento'];
                    let bairro = rowData['bairro'];
                    let rua = rowData['rua'];
                    let cidade = rowData['cidade'];
                    let cep = rowData['cep'];
                    let codigo_externo = rowData['orcamento'];
                    let corretor = rowData['corretor'];
                    let data_nascimento = rowData['nascimento'];
                    let uf = rowData['uf'];
                    let valor_plano = rowData['valor_plano'];
                    let valor_adesao = rowData['valor_adesao'];
                    let desconto_corretora = rowData['desconto_corretora'];
                    let desconto_corretor = rowData['desconto_corretor'];
                    let status = rowData['status'];
                    let financeiro_id = rowData['financeiro_id'];
                    let administradora = rowData['administradora'];
                    let fone = rowData['fone'];
                    let data_contrato = rowData['data_contrato'];
                    let quantidade_parcelas = rowData['quantidade_parcelas'];
                    let desconto_operadora = rowData['desconto_operadora'];
                    $(td).html(
                        `<div class='text-center text-white'>
                            <a href="#" class="text-white open-modal" data-parcelas="${quantidade_parcelas}" data-operadora_valor="${desconto_operadora}"  data-contrato="${data_contrato}" data-fone="${fone}" data-administradora="${administradora}" data-financeiro="${financeiro_id}" data-status="${status}" data-valorplano="${valor_plano}" data-adesao="${valor_adesao}" data-descontocorretora="${desconto_corretora}" data-descontocorretor="${desconto_corretor}" data-uf="${uf}" data-nascimento="${data_nascimento}" data-corretor="${corretor}" data-rua="${rua}" data-cidade="${cidade}" data-cep="${cep}" data-codigo="${codigo_externo}" data-id="${id}" data-cliente="${cliente}" data-cpf="${cpf}" data-email="${email}" data-nascimento="${nascimento}" data-bairro="${bairro}">
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

            $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Listagem(Completa)</h4>");
            let api = this.api();
            let dadosColuna9 = api.column(9,{search: 'applied'}).data();
            let dadosColuna11 = api.column(11,{search: 'applied'}).data();
            let contagemEmAnalise = 0;
            let emissao = 0;
            let adesao = 0;
            let vigencia = 0;
            let segundaParcela = 0;
            let terceiraParcela = 0;
            let quartaParcela = 0;
            let quintaParcela = 0;
            let sextaParcela = 0;
            let finalizado = 0;
            let cancelados = 0;
            let atrasados = 0;
            dadosColuna9.each(function (valor) {
                if (valor.toLowerCase() == 'em análise') {contagemEmAnalise++;}
                if (valor.toLowerCase() == 'emissão boleto') {emissao++;}
                if (valor.toLowerCase() == 'pag. vigência') {vigencia++;}
                if (valor.toLowerCase() == 'pag. adesão') {adesao++;}
                if (valor.toLowerCase() == 'pag. 2º parcela') {segundaParcela++;}
                if (valor.toLowerCase() == 'pag. 3º parcela') {terceiraParcela++;}
                if (valor.toLowerCase() == 'pag. 4º parcela') {quartaParcela++;}
                if (valor.toLowerCase() == 'pag. 5º parcela') {quintaParcela++;}
                if (valor.toLowerCase() == 'pag. 6º parcela') {sextaParcela++;}
                if (valor.toLowerCase() == 'finalizado') {finalizado++;}
                if (valor.toLowerCase() == 'cancelado') {cancelados++;}
            });
            dadosColuna11.each(function(valor){
                if (valor.toLowerCase() == 'atrasado') {atrasados++;}
            });

            console.log("Olaaaaaaaaaaaaaaaa = ",contagemEmAnalise);

            $(".coletivo_quantidade_em_analise").html(`<span class="my-auto flex items-center align-middle self-center h-100">${contagemEmAnalise}</span>`);
            $(".coletivo_quantidade_emissao_boleto").html(`<span class="my-auto flex items-center align-middle self-center h-100">${emissao}</span>`);
            $(".coletivo_quantidade_pagamento_adesao").html(`<span class="my-auto flex items-center align-middle self-center h-100">${adesao}</span>`);
            $(".coletivo_quantidade_pagamento_vigencia").html(`<span class="my-auto flex items-center align-middle self-center h-100">${vigencia}</span>`);
            $(".coletivo_quantidade_segunda_parcela").html(`<span class="my-auto flex items-center align-middle self-center h-100">${segundaParcela}</span>`);
            $(".coletivo_quantidade_terceira_parcela").html(`<span class="my-auto flex items-center align-middle self-center h-100">${terceiraParcela}</span>`);
            $(".coletivo_quantidade_quarta_parcela").html(`<span class="my-auto flex items-center align-middle self-center h-100">${quartaParcela}</span>`);
            $(".coletivo_quantidade_quinta_parcela").html(`<span class="my-auto flex items-center align-middle self-center h-100">${quintaParcela}</span>`);
            $(".coletivo_quantidade_sexta_parcela").html(`<span class="my-auto flex items-center align-middle self-center h-100">${sextaParcela}</span>`);
            $(".quantidade_coletivo_finalizado").html(`<span class="my-auto flex items-center align-middle self-center h-100">${finalizado}</span>`);
            $(".quantidade_coletivo_cancelados").html(`<span class="my-auto flex items-center align-middle self-center h-100">${cancelados}</span>`);
            $(".coletivo_quantidade_atrasado").html(`<span class="my-auto flex items-center align-middle self-center h-100">${atrasados}</span>`);
            let corretoresUnicos = new Set();
            this.api().column(2).data().each(function(v) {
                corretoresUnicos.add(v);
            });
            let corretoresOrdenados = Array.from(corretoresUnicos).sort();
            $('#select_usuario').empty();
            $('#select_usuario').append('<option value="todos">-- Escolher Corretor --</option>');
            corretoresOrdenados.forEach(function(corretor) {
                $('#select_usuario').append('<option value="' + corretor + '">' + corretor + '</option>');
            });

            let anos = this.api().column(0).data().toArray().map(function(value) {
                let year = new Date(value).getFullYear();
                return !isNaN(year) ? year : null;
            });
            let anosUnicos = new Set(anos.filter(function(ano) {return ano !== null;}));
            let selectAno = $('#mudar_ano_table_coletivo');
            selectAno.empty(); // Limpar opções existentes
            selectAno.empty(); // Limpar opções existentes
            selectAno.append('<option value="" selected class="text-center">-Ano-</option>'); // Opção padrão
            anosUnicos.forEach(function(ano) {
                selectAno.append('<option value="' + ano + '">' + ano + '</option>');
            });
            let administradoras = new Set();
            this.api().column(4).data().each(function(v) {
                administradoras.add(v);
            });
            let adminOrdenados = Array.from(administradoras).sort();
            $('#select_coletivo_administradoras').empty();
            $('#select_coletivo_administradoras').append('<option value="todos" class="text-center">-- Administradoras --</option>');
            adminOrdenados.forEach(function(corretor) {
                $('#select_coletivo_administradoras').append('<option value="' + corretor + '" class="text-center">' + corretor + '</option>');
            });
        },
        "rowCallback": function(row, data, index) {

            $(row).find('.open-modal').data('cliente', data['cliente'])
        },
        "footerCallback": function (row, data, start, end, display) {
            let intVal = (i) =>  typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            total = this.api().column(7,{search: 'applied'}).data().reduce(function (a, b) {return intVal(a) + intVal(b);},0);
            total_vidas = this.api().column(6,{search: 'applied'}).data().reduce(function (a, b) {return intVal(a) + intVal(b);},0);
            total_linhas = this.api().column(5,{search: 'applied'}).data().count();
            let total_br = total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
            $(".total_por_vida_coletivo").html(`<span class="my-auto flex items-center align-middle self-center h-100">${total_vidas}</span>`);
            $(".total_por_orcamento_coletivo").html(`<span class="my-auto flex items-center align-middle self-center h-100">${total_linhas}</span>`);
            $(".total_por_page_coletivo").html(`<span class="my-auto flex items-center align-middle self-center h-100">${total_br}</span>`);

        }
    });
}

$('#tabela_coletivo').on('click', 'tbody tr', function () {
    table.$('tr').removeClass('textoforte');
    $(this).closest('tr').addClass('textoforte');
});
