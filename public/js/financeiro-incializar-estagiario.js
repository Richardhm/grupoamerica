function inicializarEstagiario(corretora_id = null) {
    if($.fn.DataTable.isDataTable('.listarindividual')) {
        $('.listarindividual').DataTable().destroy();
    }


    table_estagiario = $(".listarindividual").DataTable({
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
                d.corretora_id = corretora_id;
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
            {data:"created_at",name:"created_at"},
            {data:"codigo_externo",name:"codigo_externo"},
            {data:"name",name:"name"},
            {data:"nome",name:"nome"},
            {data:"cpf",name:"cpf",
                "createdCell": function (td, cellData, rowData, row, col) {
                    let cpf = cellData.substr(0,3)+"."+cellData.substr(3,3)+"."+cellData.substr(6,3)+"-"+cellData.substr(9,2);
                    $(td).html(cpf);
                }
            },
            {data:"quantidade_vidas",name:"vidas"}

        ],

        "columnDefs": [
            {"targets": 0,"width":"8%"},//data
            {"targets": 1,"width":"8%"},//codi
            {"targets": 2,"width":"14%",
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
            {"targets": 3,"width":"22%"},//cliente
            {"targets": 4,"width":"12%"},//cpf
            {"targets": 5,"width":"5%"},//vidas

        ],
        "initComplete": function( settings, json ) {

        },
        "drawCallback": function( settings ) {

        },
        footerCallback: function (row, data, start, end, display) {

        }
    });
}

$('#tabela_individual').on('click', 'tbody tr', function () {
    table_individual.$('tr').removeClass('textoforte');
    $(this).closest('tr').addClass('textoforte');
});

