<x-app-layout>

    <div class="flex" style="align-items: flex-start;">

        <div class="w-[40%] mt-2 ml-2 rounded-lg p-1 text-white bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
            <table class="table table-sm listar_user" id="listar_usuarios">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th style="text-align: center;"  class="text-center">Ligar/Desligar</th>
                    <th style="text-align: center;">CLT/Parceiro</th>
                    <th style="text-align: center;">Editar</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>


        <div class="w-[55%] mt-2 ml-2 rounded-lg p-1 text-white bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] p-2">
            <div>
                <h2 class="text-2xl text-center">Editar/Cadastrar</h2>
            </div>

            <div class="flex justify-between">
                <div id="imagem_aqui"></div>
                <div class="interruptor-container">
                    <div id="interruptor" class="interruptor"></div>
                </div>
            </div>

            <div class="flex w-full justify-between mb-3">
                <label for="nome" class="flex w-[48%] flex-wrap">
                    <span class="flex w-full">Nome</span>
                    <input type="text" id="name" name="name" class="w-full rounded-lg text-white bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] border-white">
                </label>

                <label for="email" class="flex w-[48%] flex-wrap">
                    <span class="flex w-full">Email</span>
                    <input type="text" id="email" name="email" class="w-full rounded-lg text-white bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] border-white">
                </label>



            </div>

            <div class="flex w-full justify-between">
                <label for="celular" class="flex w-[48%] flex-wrap">
                    <span class="flex w-full">Celular</span>
                    <input type="text" id="celular" name="celular" class="w-full rounded-lg text-white bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] border-white">
                </label>
                <label for="codigo_vendedor" class="flex w-[48%] flex-wrap">
                    <span class="flex w-full">Codigo Vendedor</span>
                    <input type="text" id="codigo_vendedor" name="codigo_vendedor" class="w-full rounded-lg text-white bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] border-white">
                </label>
            </div>



            <div class="mt-3">
                <label class="block text-lg text-white font-medium" for="large_size">Foto:</label>
                <input class="block w-full text-lg text-gray-900 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] border-white rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="image" type="file">
            </div>

            <div class="w-full mt-2">
                <button type="button" class="text-white bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] border-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 w-full salvar_user">Salvar</button>
            </div>
        </div>

    </div>

@section('css')
    <style>
        table.dataTable tbody td {
            font-size: 0.85em !important;
            padding: 0px !important;
        }

        table.dataTable thead th {
            font-size: 0.85em !important;
            padding: 0px !important;
        }
    </style>
@endsection



@section('scripts')
    <script src="{{asset('js/jquery.mask.min.js')}}"></script>
    <script>
        $(function(){


            const interruptor = document.getElementById("interruptor");

            // Adicionando evento de clique
            interruptor.addEventListener("click", () => {
                interruptor.classList.toggle("ligado");
            });




            $("#celular").mask("(00) 0 0000-0000");

            $(".estilo_btn_plus").on('click',function(){
                $('#exampleModalLong').modal('show')
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("body").on('click','.remover_user',function(){
                let id = $(this).attr('id');
                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Esta ação não pode ser desfeita!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sim, deletar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('destroy.corretor') }}",
                            method: "POST",
                            data: {
                                id: id
                            },
                            success: function(res) {
                                Swal.fire(
                                    'Deletado!',
                                    'O usuário foi deletado com sucesso.',
                                    'success'
                                ).then(()=>{
                                    $(".listar_user").DataTable().ajax.reload();
                                });
                            },
                            error: function(err) {
                                Swal.fire(
                                    'Erro!',
                                    'Não foi possível deletar o usuário.',
                                    'error'
                                );
                                console.log("Erroorr ", err);
                            }
                        });
                    }
                });
            });

            $("body").on('click','.ver_info',function(e){
               e.preventDefault();

                $("#deletar_imagem").empty();

                let id = $(this).attr('data-id');
                let celular = $(this).attr('data-celular');
                let image = $(this).attr('data-imagem');
                let nome = $(this).attr('data-nome')
                let email = $(this).attr('data-email');





                // let removeButton = $("<button>")
                //     .addClass("bg-red-500 text-white p-2 rounded-full hover:bg-red-700 mt-2 remover_user")
                //     .html(`
                //         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                //             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                //         </svg>
                //     `)
                //     .attr("id", id);
                //
                $("#deletar_imagem").append("Ligar/Desligar");

               celular = celular != undefined ? celular : "";
               $("#name").val(nome);
               $("#celular").val(celular);
               $("#email").val(email);

                if (image) {
                    // Cria um objeto de imagem temporário para testar o link
                    let imgTest = new Image();
                    imgTest.src = image;

                    // Flag para verificar se a imagem foi carregada com sucesso
                    let imageLoaded = false;

                    // Se a imagem carregar corretamente
                    imgTest.onload = function() {
                        imageLoaded = true; // Seta a flag como verdadeira
                        $("#imagem_aqui").empty(); // Limpa qualquer conteúdo anterior
                        let imgElement = $("<img>").attr("src", image).attr("alt", "Imagem do usuário")
                            .addClass("max-w-[100px] w-[100px] max-h-[100px] h-[100px] rounded-full shadow-lg border border-gray-300 object-cover");

                        // Adiciona a imagem ao div
                        $("#imagem_aqui").append(imgElement);
                    };

                    // Se a imagem não carregar (link inválido)
                    imgTest.onerror = function() {
                        if (!imageLoaded) { // Só executa se a imagem não carregou
                            $("#imagem_aqui").text("Corretor sem imagem");
                        }
                    };
                } else {
                    $("#imagem_aqui").text("Foto"); // Caso não tenha imagem no atributo
                }


               return false;
            });


            function inicializarTable() {
                $(".listar_user").DataTable({
                    dom: '<"flex justify-between"<"#title_individual">ftr><t><"flex justify-between"lp>',
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
                        "url":"{{ route('corretores.list') }}",
                        "dataSrc": ""
                    },
                    "lengthMenu": [30,40,80,120,160,200,240,280,320],
                    "ordering": false,
                    "paging": true,
                    "searching": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    columns: [
                        {data:"name",name:"name"},
                        { data: null, name: "toggle", orderable: false, searchable: false },
                        { data: null, name: "toggle_clt", orderable: false, searchable: false },
                        {data:"id",name:"id"}
                    ],
                    "columnDefs": [
                        {
                            "targets": 3,
                            "createdCell": function (td, cellData, rowData, row, col) {
                                let id = cellData;
                                let nome = rowData.name;
                                let celular = rowData.celular ?? "";
                                let imagem = rowData.image;
                                let email = rowData.email;
                                $(td).html(`<div class='text-right text-white flex justify-center' style="text-align:right;">
                                        <a href="#" class="text-white ver_info text-right" style="text-align:right;" data-email="${email}" data-id="${id}" data-nome="${nome}" data-celular="${celular}" data-imagem="${imagem}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 div_info">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </a>
                                    </div>
                                `);
                            }
                        },
                        {
                            "targets": 1, // Índice da nova coluna
                            "createdCell": function (td, cellData, rowData, row, col) {
                                let id = rowData.id;
                                let status = rowData.ativo == 1 ? 'checked' : ''; // Verifica o valor de 'ativo'

                                // Criando o botão de Ligar/Desligar
                                $(td).html(`
                                    <label class="switch">
                                        <input type="checkbox" class="toggle-switch" data-id="${id}" ${status}>
                                        <span class="slider"></span>
                                    </label>
                                `);
                            }
                        },
                        {
                            "targets": 2, // Índice da nova coluna CLT
                            "createdCell": function (td, cellData, rowData, row, col) {
                                let id = rowData.id;
                                let isClt = rowData.clt == 1 ? 'checked' : ''; // Verifica se o CLT é 1
                                let isParceiro = rowData.clt == 0 ? 'checked' : ''; // Verifica se o CLT é 0

                                // Adiciona os botões radio para CLT e Parceiro
                                $(td).html(`
                                    <div class="flex justify-around items-center">
                                        <label class="radio-label">
                                            <input type="radio" name="clt_${id}" class="radio-clt" data-id="${id}" value="1" ${isClt}> CLT
                                        </label>
                                        <label class="radio-label">
                                            <input type="radio" name="clt_${id}" class="radio-parceiro" data-id="${id}" value="0" ${isParceiro}> Parceiro
                                        </label>
                                    </div>
                                `);
                            }
                        }

                    ],
                    "initComplete": function( settings, json ) {
                        $('.dataTables_filter input').addClass('texto-branco');
                        $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Listagem</h4>");

                    },
                    "drawCallback": function( settings ) {},
                    footerCallback: function (row, data, start, end, display) {}
                });
            }
            inicializarTable();

            $(document).on('change', '.radio-clt, .radio-parceiro', function () {
                let userId = $(this).data('id');
                let status = $(this).val();

                $.ajax({
                   url:"{{route('corretores.user.alterar')}}",
                   method:"POST",
                   data: {
                       id: userId,
                       ativo: status
                   },
                   success:function(res) {
                       console.log(res);
                   }
                });

            });


            $(document).on('change', '.toggle-switch', function () {
                let userId = $(this).data('id');
                let status = $(this).is(':checked');

                $.ajax({
                    url: "{{route('corretores.alterar')}}",
                    method: 'POST',
                    data: {
                        id: userId,
                        ativo: status,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (res) {
                        console.log(res);
                    }
                });
            });



            let image = ""
            $("#image").on('change',function(e){
                image = e.target.files;
                // Verifica se um arquivo foi selecionado
                if (image.length > 0) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        // Define o preview da imagem no elemento com id 'imagem_aqui'
                        $('#imagem_aqui').html(`<img src="${e.target.result}" alt="Preview da Imagem" class="w-32 h-32 object-cover rounded-full">`);
                    }
                    reader.readAsDataURL(image[0]); // Converte o arquivo selecionado em um URL para pré-visualização
                }
            });

            $("body").on('click','.salvar_user',function(e){
                var fd = new FormData();
                fd.append('file',image[0]);
                fd.append('nome',$('#name').val());
                fd.append('email',$('#email').val());
                fd.append('celular',$('#celular').val());
                fd.append('codigo_vendedor',$('#codigo_vendedor').val());

                $.ajax({
                    url:"{{route('corretores.store')}}",
                    method:"POST",
                    data:fd,
                    contentType: false,
                    processData: false,
                    success:function(res){
                        $(".listar_user").DataTable().ajax.reload();

                        $('#name').val('');
                        $('#email').val('');
                        $('#celular').val('');
                        $('#image').val('');
                        $('#codigo_vendedor').val('');
                        $('#imagem_aqui').html(''); // Remove o preview da imagem

                        Swal.fire(
                            'Sucesso!',
                            res.message, // Supondo que o back-end retorne uma mensagem de sucesso
                            'success'
                        );


                        // if(res == "sucesso") {
                        // 	$('#cadastrarAdministradora').modal('hide');
                        // 	ta.ajax.reload();
                        // } else {

                        // }
                    }
                });




            });

        });
    </script>

@stop

@section('css')
    <style>
        .estilo_btn_plus {background-color:rgba(0,0,0,1);box-shadow:rgba(255,255,255,0.8) 0.1em 0.2em 5px;border-radius: 5px;display: flex;align-items: center;}
        .estilo_btn_plus i {color: #FFF !important;font-size: 0.7em;padding: 8px;}
        .estilo_btn_plus:hover {background-color:rgba(255,255,255,0.8);box-shadow:rgba(0,0,0,1) 0.1em 0.2em 5px;}
        .estilo_btn_plus:hover i {color: #000 !important;}
        .texto-branco {color: #fff;}





    </style>
@stop
    </x-app-layout>
