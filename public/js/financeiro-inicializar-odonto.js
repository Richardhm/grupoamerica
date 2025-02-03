var tableodonto;
function inicializarOdonto() {
    if($.fn.DataTable.isDataTable('.listardadosodonto')) {
        $('.listardadosodonto').DataTable().destroy();
    }
    tableodonto = $(".listardadosodonto").DataTable({
        dom: '<"flex justify-between"<"#title_odonto">Bftr><t><"flex justify-between"lp>',
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
        ajax: {
            "url":listarOdonto,
            "dataSrc": ""
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
            {data:"created_at",name:"created_at"},
            {data:"nome",name:"nome"},
            {data:"usuario",name:"usuario",},
            {data:"valor",name:"valor_plano",render: $.fn.dataTable.render.number('.', ',', 2, 'R$ ')},
        ],
        "columnDefs": [],
        "initComplete": function( settings, json ) {

            $('.btn-exportar').css({
                'background-color': '#4CAF50',
                'color': '#FFF',
                'border': 'none',
                'padding': '8px 16px',
                'border-radius': '4px'
            });

            let uniqueUsers = [];
            json.forEach(row => {
                if (!uniqueUsers.includes(row.usuario)) {
                    uniqueUsers.push(row.usuario);
                }
            });

            // Construir opções como uma string e adicionar ao select
            let optionsHtml = '<option value="todos" class="text-center">---Escolher Corretor---</option>';
            uniqueUsers.forEach(user => {
                optionsHtml += `<option value="${user}" class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] text-black text-lg">${user}</option>`;
            });
            $('#select_usuario_odonto').html(optionsHtml);

        },
        "drawCallback":function(settings) {},
        footerCallback: function (row, data, start, end, display) {}
    });
}

$('#select_usuario_odonto').on('change', function() {
    let selectedUser = $(this).val();
    if (selectedUser === "todos") {
        tableodonto.column(2).search("").draw(); // Limpar filtro
    } else {
        tableodonto.column(2).search('^' + selectedUser + '$', true, false).draw(); // Filtro exato
    }
});


$('#tabela_odonto').on('click', 'tbody tr', function () {
    tableodonto.$('tr').removeClass('textoforte');
    $(this).closest('tr').addClass('textoforte');
});
