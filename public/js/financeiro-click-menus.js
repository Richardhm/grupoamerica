$(".btn-atualizar").on('click', function() {
    $("#atualizarModal").removeClass('hidden').addClass('flex');
});

// Fechar a modal ao clicar no botão de fechar
$(".close-modal").on('click', function() {
    $("#atualizarModal").removeClass('flex').addClass('hidden');
});

$(window).on('click', function(event) {
    if ($(event.target).is('#atualizarModal')) {
        $("#atualizarModal").removeClass('flex').addClass('hidden');
    }
});


$('button[data-corretora]').on('click', function() {
    let corretora_id = $(this).data('corretora');
    if($("#janela_atual").val() == "aba_individual") {
        inicializarIndividual(corretora_id);
    } else if($("#janela_atual").val() == "aba_coletivo") {
        inicializarColetivo(corretora_id);
    } else {
        inicializarEmpresarial(corretora_id);
    }
});

$(".btn-cancelados").on('click',function(){
    $('#uploadCancelados').addClass('show');
});

$('#uploadCancelados .close').on('click', function() {
    $('#uploadCancelados').removeClass('show');
});


$('.modal_upload').on('click', function() {
    $('#uploadModal').addClass('show');
});

$('#uploadModal .close, #uploadModal').on('click', function() {
    $('#uploadModal').removeClass('show');
});

$("body").on('click','.vincular_estagiario',function(){
   $('#myModalVincularEstagiario').removeClass('hidden').addClass('flex');
});

$(document).on('click', function(event) {
    if ($(event.target).is('#myModalVincularEstagiario')) {
        $('#myModalVincularEstagiario').removeClass('flex').addClass('hidden');
    }
});

$(document).on('keydown', function(event) {
    if (event.key === "Escape") {
        $('#myModalVincularEstagiario').removeClass('flex').addClass('hidden');
    }
});


$("body").on('click','.editar_individual_select',function(){

    let select = $(this).closest("div").find('select');
    if(select.prop('disabled')) {
        select.prop('disabled',false);
    } else {
        select.prop('disabled',true);
    }
});


$("body").on('click','.editar_coletivo_select',function(){
    let select = $(this).closest("div").find('select');
    if(select.prop('disabled')) {
        select.prop('disabled',false);
    } else {
        select.prop('disabled',true);
    }
});

$("body").on('click','.editar_coletivo_administradora',function(){
    let select = $(this).closest("div").find('select');
    if(select.prop('disabled')) {
        select.prop('disabled',false);
    } else {
        select.prop('disabled',true);
    }
});






$("body").on('click','.editar_individual',function(){
    let input = $(this).closest("div").find("input");

    if (input.prop('readonly')) {
        input.prop('readonly', false); // Remove a propriedade readonly
    } else {
        input.prop('readonly', true); // Adiciona a propriedade readonly
    }
});

$("body").on('click','.editar_coletivo',function(){

    let input = $(this).closest("div").find("input");

    if (input.prop('readonly')) {
        input.prop('readonly', false); // Remove a propriedade readonly
    } else {
        input.prop('readonly', true); // Adiciona a propriedade readonly
    }
});

$("body").on('click','.editar_empresarial_select',function(){

    let input = $(this).closest("div").find("select");

    if (input.prop('disabled')) {
        input.prop('disabled', false); // Remove a propriedade readonly
    } else {
        input.prop('disabled', true); // Adiciona a propriedade readonly
    }
});



$("body").on('click','.editar_empresarial',function(){

    let input = $(this).closest("div").find("input");

    if (input.prop('readonly')) {
        input.prop('readonly', false); // Remove a propriedade readonly
    } else {
        input.prop('readonly', true); // Adiciona a propriedade readonly
    }
});



$('body').on('click','#btnVincular',function(){
   let usuarioSelect = $("#usuarioSelect").val();
   let clienteSelect = $("#clienteSelect").val();
   $.ajax({
       url:vincular_estagiario,
       data: {
           cliente_id:clienteSelect,
           user_id:usuarioSelect
       },
       method:"POST",
       success:function(res) {
           console.log(res);
       }
   })

});



$(".list_abas li").on('click',function(){
    $('li').removeClass('ativo');
    $(this).addClass("ativo");
    let id = $(this).attr('data-id');
    $("#janela_atual").val(id);
    $("#janela_ativa").val(id);
    default_formulario = $('.coluna-right.'+id).html();
    $('.conteudo_abas main').addClass('ocultar');
    $('#'+id).removeClass('ocultar');
    $('#'+id).removeClass('hidden');
    $('.next').attr('data-cliente','');
    $('.next').attr('data-contrato','');
    $('tr').removeClass('textoforte');
    if($(this).attr('data-id') == "aba_individual") {
        inicializarIndividual();
    } else if($(this).attr('data-id') == "aba_coletivo") {
        inicializarColetivo();
        $('#mudar_mes_table').find('option').eq(0).prop('selected', true);
    } else if($(this).attr('data-id') == "aba_empresarial") {
        inicializarEmpresarial();
    } else {
        inicializarOdonto();
    }
    $("#cliente_id_alvo").val('');
    $("#cliente_id_alvo_individual").val('');
    $("#all_pendentes_individual").removeClass('textoforte-list');
    $("ul#listar li.coletivo").removeClass('textoforte-list');
    $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
    $("ul#listar_individual li.individual").removeClass('textoforte-list');
    $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
    $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list');
    $("ul#grupo_finalizados_empresarial li.empresarial").removeClass('textoforte-list');
});
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$("body").on('click','.open-model-individual',function(e){
    e.preventDefault();
    let corretor = $(this).data('corretor');

    let id = $(this).data('id');
    let vidas = $(this).data('quantidade_vidas');
    let status = $(this).data('status');
    let rua = $(this).data('rua');
    let cpf = $(this).data('cpf');
    let data_criacao = $(this).data('data-criacao');
    let data_nascimento= $(this).data('data_nascimento');
    let email = $(this).data('email');
    let celular = $(this).data('celular');
    let codigo_externo = $(this).data('codigo_externo');
    let valor_plano = $(this).data('valor_plano');
    let cliente = $(this).data('cliente');
    let cidade = $(this).data('cidade');
    let cep = $(this).data('cep');
    let bairro = $(this).data('bairro');
    let carteirinha = $(this).data('carteirinha');
    let complemento = $(this).data('complemento');
    let uf = $(this).data('uf');
    let valor_adesao = $(this).data('valor_adesao');
    let data_vigencia= $(this).data('data_vigencia');
    let data_boleto= $(this).data('data_boleto');
    let user_id = $(this).data('user_id');
    $.ajax({
        url:individualFinanceiroInicializar,
        method:"POST",
        data: {
            user_id,
            corretor,
            id,
            vidas,
            status,
            rua,
            cpf,
            data_criacao,
            data_nascimento,
            email,
            celular,
            codigo_externo,
            valor_plano,
            cliente,
            cidade,
            cep,
            bairro,
            carteirinha,
            complemento,
            uf,
            valor_adesao,
            data_vigencia,
            data_boleto
        },
        success:function(res){
            $('#modalLoaderIndividual').addClass('hidden');
            $('.content-modal-individual').removeClass('hidden');
            $(".content-modal-individual").html(res);
        }
    });
    // Exibe a modal
    $('#myModalIndividual').removeClass('hidden').addClass('flex');
    $('#modalLoaderIndividual').removeClass('hidden');
});

$(document).on('click', '.open-modal', function(e) {
    e.preventDefault();
    $('#modalLoader').removeClass('hidden');
    let cliente = $(this).data("cliente");
    let contrato = $(this).data("contrato");
    let cpf = $(this).data("cpf");
    let codigo_externo = $(this).data("codigo");
    let rua = $(this).data("rua");
    let cidade = $(this).data("cidade");
    let bairro = $(this).data("bairro");
    let email = $(this).data("email");
    let cep = $(this).data("cep");
    let corretor = $(this).data("corretor");
    let nascimento = $(this).data("nascimento");
    let uf = $(this).data("uf");
    let id = $(this).data("id");
    let administradora = $(this).data("administradora");
    let fone = $(this).data("fone");
    let valor_adesao = $(this).data("adesao");
    let valor_plano = $(this).data("valorplano");
    let desconto_corretora = $(this).data("descontocorretora") ?? 0;
    let desconto_corretor = $(this).data("descontocorretor") ?? 0;
    let status = $(this).data('status');
    let financeiro = $(this).data('financeiro');
    let quantidade_parcelas = $(this).data('parcelas');
    let operadora_valor = $(this).data('operadora_valor');


    $.ajax({
        url:coletivoFinanceiroInicializar,
        method:"POST",
        data: {
            quantidade_parcelas,
            operadora_valor,
            status,
            cliente,
            cpf,
            codigo_externo,
            rua,
            cidade,
            bairro,
            email,
            cep,
            corretor,
            nascimento,
            uf,
            valor_adesao,
            valor_plano,
            desconto_corretora,
            desconto_corretor,
            id,
            financeiro,
            administradora,
            contrato,
            fone
        },
        success:function(res){
            $('#modalLoader').addClass('hidden');
            $('.content-modal-coletivo').removeClass('hidden');
            $(".content-modal-coletivo").html(res);
        }
    });
    // Exibe a modal
    $('#myModalColetivo').removeClass('hidden').addClass('flex');
    $('#modalLoader').removeClass('hidden');
});

// Fechar a modal quando clicar no botão de fechar
$("body").on('click','#closeModalColetivo',function(){
    $('#myModalColetivo').removeClass('flex').addClass('hidden');
    $('.content-modal-coletivo').html('');
});

$("body").on('click','.button_cancelar_empresarial',function(){
    let id = $(this).data('id');

    Swal.fire({
        title: 'Você tem certeza?',
        text: "Esta ação não pode ser desfeita!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: cancelarEmpresarial,
                method: "POST",
                data: {
                    id
                },
                success: function(res) {

                    if(res) {
                        Swal.fire(
                            'Cancelado!',
                            'O cliente foi cancelado com sucesso.',
                            'success'
                        ).then(() => {
                            window.location.reload(); // Atualiza a página após confirmar a mensagem de sucesso
                        });
                    }


                    // Exibe mensagem de sucesso após a exclusão

                },
                error: function(err) {
                    // Exibe mensagem de erro caso algo dê errado
                    Swal.fire(
                        'Erro!',
                        'Ocorreu um erro ao excluir o cliente.',
                        'error'
                    );
                }
            });
        }
    });
});



$("body").on('click','.button_excluir_empresarial',function (){
    let id = $(this).data('id');
    // Exibe o alerta de confirmação
    Swal.fire({
        title: 'Você tem certeza?',
        text: "Esta ação não pode ser desfeita!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: excluirEmpresarial,
                method: "POST",
                data: {
                    id
                },
                success: function(res) {

                    if(res) {
                        Swal.fire(
                            'Excluído!',
                            'O cliente foi excluído com sucesso.',
                            'success'
                        ).then(() => {
                            window.location.reload(); // Atualiza a página após confirmar a mensagem de sucesso
                        });
                    }


                    // Exibe mensagem de sucesso após a exclusão

                },
                error: function(err) {
                    // Exibe mensagem de erro caso algo dê errado
                    Swal.fire(
                        'Erro!',
                        'Ocorreu um erro ao excluir o cliente.',
                        'error'
                    );
                }
            });
        }
    });







});



$("body").on('click', '.button_excluir', function() {
    let id = $(this).data('id');

    // Exibe o alerta de confirmação
    Swal.fire({
        title: 'Você tem certeza?',
        text: "Esta ação não pode ser desfeita!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Se o usuário confirmar a exclusão, realiza o AJAX
            $.ajax({
                url: excluirColetivo,
                method: "POST",
                data: {
                    id
                },
                success: function(res) {
                    // Exibe mensagem de sucesso após a exclusão
                    Swal.fire(
                        'Excluído!',
                        'O cliente foi excluído com sucesso.',
                        'success'
                    ).then(() => {
                        window.location.reload(); // Atualiza a página após confirmar a mensagem de sucesso
                    });
                },
                error: function(err) {
                    // Exibe mensagem de erro caso algo dê errado
                    Swal.fire(
                        'Erro!',
                        'Ocorreu um erro ao excluir o cliente.',
                        'error'
                    );
                }
            });
        } else {
            console.log('Ação de exclusão cancelada');
        }
    });
});

$("body").on('click', '.button_cancelar', function() {
    let id = $(this).data('id');
    // Exibe o alerta de confirmação
    Swal.fire({
        title: 'Tem certeza que deseja cancelar o contrato?',
        text: "Esta ação não pode ser desfeita!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, cancelar!',
        cancelButtonText: 'Não, manter ativo'
    }).then((result) => {
        if (result.isConfirmed) {
            // Se o usuário confirmar o cancelamento, realiza o AJAX
            $.ajax({
                url: financeiroCanceladoColetivo,
                method: "POST",
                data: {
                    comissao_id_cancelado: id
                },
                success: function(res) {
                    // Exibe mensagem de sucesso após o cancelamento
                    Swal.fire(
                        'Cancelado!',
                        'O contrato foi cancelado com sucesso.',
                        'success'
                    ).then(() => {
                        window.location.reload(); // Atualiza a página após a confirmação
                    });
                },
                error: function(err) {
                    // Exibe mensagem de erro caso algo dê errado
                    Swal.fire(
                        'Erro!',
                        'Ocorreu um erro ao cancelar o contrato.',
                        'error'
                    );
                }
            });
        } else {
            console.log('Ação de cancelamento cancelada');
        }
    });
});


$("body").on('click','.em_analise_empresarial',function(){
    let id = $(this).data('id');
    let self = $(this);
    $.ajax({
        url:empresarialEmAnalise,
        method:"POST",
        data: {
            id
        },

        success:function(res) {
            if(res != "error") {
                //$("body").find('#status').val("Emissão Boleto");
                $("body").find(".em_analise_empresarial").text(res);
                self.empty();
                self.prop('disabled',true).html(`
                             <button type="button" class="em_analise text-center text-white flex justify-center cursor-not-allowed bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-blue-800 w-11/12">
                                    <svg class="w-6 h-6 text-white dark:text-white text-center mx-auto" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M15.03 9.684h3.965c.322 0 .64.08.925.232.286.153.532.374.717.645a2.109 2.109 0 0 1 .242 1.883l-2.36 7.201c-.288.814-.48 1.355-1.884 1.355-2.072 0-4.276-.677-6.157-1.256-.472-.145-.924-.284-1.348-.404h-.115V9.478a25.485 25.485 0 0 0 4.238-5.514 1.8 1.8 0 0 1 .901-.83 1.74 1.74 0 0 1 1.21-.048c.396.13.736.397.96.757.225.36.32.788.269 1.211l-1.562 4.63ZM4.177 10H7v8a2 2 0 1 1-4 0v-6.823C3 10.527 3.527 10 4.176 10Z" clip-rule="evenodd"/>
                                    </svg>
                             </button>
                            `);

            }
        }
    })
});

$("body").on('click', '.cadastrar_odonto', function(e) {

    e.preventDefault(); // Previne o envio do formulário se houver campos vazios

    // Pegando os valores dos campos
    let user_id = $("select[id='user_id'] :selected").val();
    let nome = $("input[id='nome']").val();
    let valor = $("input[id='valor']").val();

    // Verificar se os campos estão preenchidos
    if (!user_id || !nome || !valor) {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Todos os campos são obrigatórios!',
            confirmButtonText: 'Ok',
            confirmButtonColor: '#d33'
        });
        return; // Impede o envio dos dados se houver campos vazios
    }

    // Se todos os campos estiverem preenchidos, faz a requisição AJAX
    $.ajax({
        url: cadastrarOdonto,
        method: "POST",
        data: {
            user_id,
            nome,
            valor
        },
        success: function(res) {
            inicializarOdonto();
            $("#myModalCreateOdonto").removeClass('flex').addClass('hidden');
        },
        error: function(err) {
            //console.log(err);
            // Aqui você pode exibir uma mensagem de erro
        }
    });
});






$("body").on('change','.next',function(){
    let proximaLinha = $(this).closest("tr").next();

    if (proximaLinha.length) {
        // Verifica se a próxima linha tem a classe 'cursor-not-allowed'
        if (proximaLinha.hasClass('disabled-button')) {
            proximaLinha.removeClass('disabled-button');
            proximaLinha.find(".date-picker").removeAttr("disabled");
            // Ação adicional (opcional)
            //proximaLinha.addClass('classe-nova'); // Exemplo de adicionar uma classe
        }
    } else {
        console.log('Não existe uma próxima linha.');
    }

    var dateInput = $(this).val();
    var datePattern = /^\d{4}-\d{2}-\d{2}$/; // Formato esperado: yyyy-mm-dd
    if (!datePattern.test(dateInput)) {
        $(this).val(''); // Limpa o campo
        return;
    }
    var selectedDate = new Date(dateInput);
    var maxDate = new Date($(this).attr('max'));
    if (selectedDate > maxDate) {
        alert('A data de baixa não pode ser maior que a data de vencimento!');
        $(this).val(''); // Limpa o campo se a data for inválida
        return;
    }
    let id = $(this).data('id');
    let valor = $(this).val();
    let self = $(this);
    $.ajax({
        url:urlBaixaColetivo,
        method:"POST",
        data: {
            id,valor
        },
        success:function(res) {
            console.log(res);
            $("body").find('#status').val(res.status);
            let dataOriginal = res.baixa;

            function formatarData(data) {
                let partes = data.split('-');
                return `${partes[2]}/${partes[1]}/${partes[0]}`;
            }
            self.closest('tr').find('.data_baixa').html(`<span style="margin-left:20px;">${formatarData(dataOriginal)}</span>`);
            self.closest('td').html(`<button type="button" class="em_analise text-center text-white flex justify-center cursor-not-allowed bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-blue-800 w-11/12">
                            <svg class="w-6 h-6 text-white dark:text-white text-center mx-auto" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M15.03 9.684h3.965c.322 0 .64.08.925.232.286.153.532.374.717.645a2.109 2.109 0 0 1 .242 1.883l-2.36 7.201c-.288.814-.48 1.355-1.884 1.355-2.072 0-4.276-.677-6.157-1.256-.472-.145-.924-.284-1.348-.404h-.115V9.478a25.485 25.485 0 0 0 4.238-5.514 1.8 1.8 0 0 1 .901-.83 1.74 1.74 0 0 1 1.21-.048c.396.13.736.397.96.757.225.36.32.788.269 1.211l-1.562 4.63ZM4.177 10H7v8a2 2 0 1 1-4 0v-6.823C3 10.527 3.527 10 4.176 10Z" clip-rule="evenodd"/>
                            </svg>
                            </button>`)
            inicializarColetivo();
        }
    });
});





$("body").on('click','.create_odonto',function(){
    $("#myModalCreateOdonto").removeClass('hidden').addClass('flex');
});

$("body").on('click','.em_analise',function(){
    let id = $(this).data('id');
    let self = $(this);

    let proximaLinha = $(this).closest("tr").next();

    // Verifica se existe uma próxima linha
    if (proximaLinha.length) {

        // Verifica se a próxima linha tem a classe 'cursor-not-allowed'
        if (proximaLinha.hasClass('disabled-button')) {
            proximaLinha.removeClass('disabled-button');
            proximaLinha.find(".emissao_boleto").removeClass('disabled-button').removeClass('pointer-events-none').removeClass('cursor-not-allowed');

            // Ação adicional (opcional)
            //proximaLinha.addClass('classe-nova'); // Exemplo de adicionar uma classe
        }
    } else {
        console.log('Não existe uma próxima linha.');
    }


    $.ajax({
        url:emAnaliseAjax,
        method:"POST",
        data: {
            id
        },

        success:function(res) {
            if(res != "error") {
                $("body").find('#status').val("Emissão Boleto");
                $("body").find(".data_analise").text(res);
                self.closest('td').html(`
                                <button type="button" class="text-center text-white flex justify-center cursor-not-allowed bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-blue-800 w-11/12">
                                    <svg class="w-6 h-6 text-white dark:text-white text-center mx-auto" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M15.03 9.684h3.965c.322 0 .64.08.925.232.286.153.532.374.717.645a2.109 2.109 0 0 1 .242 1.883l-2.36 7.201c-.288.814-.48 1.355-1.884 1.355-2.072 0-4.276-.677-6.157-1.256-.472-.145-.924-.284-1.348-.404h-.115V9.478a25.485 25.485 0 0 0 4.238-5.514 1.8 1.8 0 0 1 .901-.83 1.74 1.74 0 0 1 1.21-.048c.396.13.736.397.96.757.225.36.32.788.269 1.211l-1.562 4.63ZM4.177 10H7v8a2 2 0 1 1-4 0v-6.823C3 10.527 3.527 10 4.176 10Z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            `);
                inicializarColetivo();

            }
        }
    })
});

$("body").on('click','.emissao_boleto',function(event){
    if ($(this).hasClass('pointer-events-none')) {
        // Previne o clique
        event.preventDefault();
        event.stopPropagation();
        return false;
    }

    let proximaLinha = $(this).closest("tr").next();

    // Verifica se existe uma próxima linha
    if (proximaLinha.length) {
        // Verifica se a próxima linha tem a classe 'cursor-not-allowed'
        if (proximaLinha.hasClass('disabled-button')) {
            proximaLinha.removeClass('disabled-button');
            proximaLinha.find(".date-picker").removeAttr("disabled");
        }
    } else {
        console.log('Não existe uma próxima linha.');
    }


    let id = $(this).data('id');
    let self = $(this);
    $.ajax({
        url:emissaoBoleto,
        method:"POST",
        data: {
            id
        },
        success:function(res) {
            if(res != "error") {
                $("body").find('#status').val("Pag. Adesão");
                $("body").find(".data_emissao").text(res);
                //self.closest('td').empty('');
                self.closest('td').html(`
                                <button type="button" class="text-center text-white flex justify-center cursor-not-allowed bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-blue-800 w-11/12">
                                    <svg class="w-6 h-6 text-white dark:text-white text-center mx-auto" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M15.03 9.684h3.965c.322 0 .64.08.925.232.286.153.532.374.717.645a2.109 2.109 0 0 1 .242 1.883l-2.36 7.201c-.288.814-.48 1.355-1.884 1.355-2.072 0-4.276-.677-6.157-1.256-.472-.145-.924-.284-1.348-.404h-.115V9.478a25.485 25.485 0 0 0 4.238-5.514 1.8 1.8 0 0 1 .901-.83 1.74 1.74 0 0 1 1.21-.048c.396.13.736.397.96.757.225.36.32.788.269 1.211l-1.562 4.63ZM4.177 10H7v8a2 2 0 1 1-4 0v-6.823C3 10.527 3.527 10 4.176 10Z" clip-rule="evenodd"/>
                                    </svg>
                               </button>
                           `);
                inicializarColetivo();

            }
        }
    })
});

$("body").on('change','.next_empresarial',function(){
    let id = $(this).data('id');
    let data_baixa = $(this).val();
    let self = $(this);
    $.ajax({
        url:empresarialDataBaixa,
        method:"POST",
        data: {
            id,data_baixa
        },
        success:function(res) {
            let dataOriginal = res.baixa;
            function formatarData(data) {
                let partes = data.split('-');
                return `${partes[2]}/${partes[1]}/${partes[0]}`;
            }
            self.closest('tr').find('.data_baixa_empresarial').html(`<span style="margin-left:20px;">${formatarData(dataOriginal)}</span>`);
            self.closest('td').html(`
                            <button type="button" class="em_analise text-center text-white flex justify-center cursor-not-allowed bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-blue-800 w-11/12">
                                <svg class="w-6 h-6 text-white dark:text-white text-center mx-auto" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M15.03 9.684h3.965c.322 0 .64.08.925.232.286.153.532.374.717.645a2.109 2.109 0 0 1 .242 1.883l-2.36 7.201c-.288.814-.48 1.355-1.884 1.355-2.072 0-4.276-.677-6.157-1.256-.472-.145-.924-.284-1.348-.404h-.115V9.478a25.485 25.485 0 0 0 4.238-5.514 1.8 1.8 0 0 1 .901-.83 1.74 1.74 0 0 1 1.21-.048c.396.13.736.397.96.757.225.36.32.788.269 1.211l-1.562 4.63ZM4.177 10H7v8a2 2 0 1 1-4 0v-6.823C3 10.527 3.527 10 4.176 10Z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        `)
            inicializarEmpresarial();
        }
    });
});

$("body").on('click', '[id^="desfazer_"]', function(){
    let id = $(this).attr('id');
    let number = id.split('_')[1];
    let contrato_id = $(this).data("id");
    let fase = $(this).data('fase');
    if(number == 1) {
        $(this).closest("tr").find('.acao_aqui').html(`
                        <button type="button" class="em_analise text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 w-11/12">Conferido</button>
                    `);
    } else if(number == 2) {
        $(this).closest("tr").find('.acao_aqui').html(`
                        <button type="button" class="emissao_boleto focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-3 py-1 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900 w-11/12">Emitido</button>
                    `);
    } else {
        $(this).closest("tr").find('.acao_aqui').html(`
                        <input type='date' data-id='' min=''
                                       max='' class='bg-gray-100 text-gray-800 p-1 text-sm rounded-md next date-picker'>
                    `);
    }


    $.ajax({
        url:desfazerColetivo,
        method:"POST",
        data: {
            contrato_id,
            fase
        },
        sucession:function(res){
            inicializarColetivo();
        }
    });
});









$("body").on('click','#closeModalIndividual',function(){
    $('#myModalIndividual').removeClass('flex').addClass('hidden');
    $('.content-modal-individual').html('');
});

$("body").on('click','#closeModalEmpresarial',function(){
    $('#myModalEmpresarial').removeClass('flex').addClass('hidden');
    $('.content-modal-empresarial').html('');
});





$(document).on('click','.open-modal-empresarial',function(e){
    e.preventDefault();
    let vendedor = $(this).data("vendedor");
    let plano = $(this).data("plano");
    let origens = $(this).data("origens");
    let razao_social = $(this).data("razao_social");
    let cnpj = $(this).data("cnpj");
    let vidas = $(this).data("vidas");
    let celular = $(this).data("celular");
    let email = $(this).data("email");
    let responsavel = $(this).data("responsavel");
    let cidade = $(this).data("cidade");
    let uf = $(this).data("uf");
    let plano_contratado = $(this).data("plano_contratado");
    let codigo_corretora = $(this).data("codigo_corretora");
    let codigo_saude = $(this).data("codigo_saude");
    let codigo_odonto = $(this).data("codigo_odonto");
    let senha_cliente = $(this).data("senha_cliente");
    let valor_saude = $(this).data("valor_saude");
    let valor_odonto = $(this).data("valor_odonto");
    let valor_total = $(this).data("valor_total");
    let taxa_adesao = $(this).data("taxa_adesao");
    let valor_boleto = $(this).data("valor_boleto");
    let vencimento_boleto = $(this).data("vencimento_boleto");
    let data_boleto = $(this).data("boleto");
    let id = $(this).data('id');
    let codigo_externo = $(this).data('codigo_externo');
    let data_analise = $(this).data('analise');

    $.ajax({
        url:empresarialFinanceiroInicializar,
        method:"POST",
        data: {
            data_analise:data_analise,
            vendedor: vendedor,
            plano: plano,
            origens: origens,
            razao_social: razao_social,
            cnpj: cnpj,
            vidas: vidas,
            celular: celular,
            email: email,
            responsavel: responsavel,
            cidade: cidade,
            uf: uf,
            id: id,
            plano_contratado: plano_contratado,
            codigo_corretora: codigo_corretora,
            codigo_saude: codigo_saude,
            codigo_odonto: codigo_odonto,
            senha_cliente: senha_cliente,
            valor_saude: valor_saude,
            valor_odonto: valor_odonto,
            valor_total: valor_total,
            taxa_adesao: taxa_adesao,
            valor_boleto: valor_boleto,
            vencimento_boleto: vencimento_boleto,
            data_boleto: data_boleto,
            codigo_externo: codigo_externo
        },
        success:function(res){
            $('#modalLoaderEmpresa').addClass('hidden');
            $('.content-modal-empresarial').removeClass('hidden');
            $(".content-modal-empresarial").html(res);
        }
    });
    $('#myModalEmpresarial').removeClass('hidden').addClass('flex');
});



$("#atrasado_corretor_coletivo").on('click',function(){
    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Atrasado</h4>");
    table.column(9).search('').draw();
    table.column(11).search('Atrasado').draw();
    $(".container_edit").addClass('ocultar');
    $("#finalizado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("ul#listar li.coletivo").removeClass('textoforte-list').removeClass('destaque_content');
    $("#grupo_finalizados").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#content_list_coletivo_begin").removeClass('destaque_content_radius');
    $(this).addClass('textoforte-list').addClass('destaque_content_radius');
});





$("#list_individual_begin").on('click',function(){

    table_individual.column(9).search('').draw();
    table_individual.column(11).search('').draw();
    let mes = $("#mudar_mes_table").val() == '' || $("#mudar_mes_table").val() == null ? '00' : $("#mudar_mes_table").val();
    let valorSelecionado = $("#select_usuario_individual").val();

    $("ul#listar_individual li.individual").removeClass('textoforte-list').removeClass('destaque_content');
    $("#atrasado_corretor").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#finalizado_corretor").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#cancelado_corretor").removeClass('destaque_content_radius').removeClass('textoforte-list');
    //$("#content_list_individual_begin").addClass('destaque_content_radius');
    //$('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Listagem(Completa)</h4>");

    if(mes != 00) {
        let ano = $("#mudar_ano_table").val();
        table_individual.search('').columns().search('').draw();
        let mesAno = mes + '/' + ano;
        table_individual.column(0).search(mesAno, true, false).draw();
    } else {
        table_individual.column(0).search('').draw();
    }

    if(valorSelecionado != "todos") {
        table_individual.column(2).search(valorSelecionado).draw();
    } else {
        table_individual.column(2).search('').draw();
    }

    let dadosColuna9 = table_individual.column(9,{search: 'applied'}).data();
    let dadosColuna11 = table_individual.column(11,{search: 'applied'}).data();
    let dadosColuna00 = table_individual.column(0,{search: 'applied'}).data();



    let primeiraParcelaIndividual = 0;
    let segundaParcelaIndividual = 0;
    let terceiraParcelaIndividual = 0;
    let quartaParcelaIndividual = 0;
    let quintaParcelaIndividual = 0;
    let sextaParcelaIndividual = 0;
    let canceladosIndividual = 0;
    let atrasadoIndividual = 0;


    dadosColuna9.each(function (valor) {

        if (valor.toLowerCase() == 'pag. 1º parcela') {primeiraParcelaIndividual++;}
        if (valor.toLowerCase() == 'pag. 2º parcela') {segundaParcelaIndividual++;}
        if (valor.toLowerCase() == 'pag. 3º parcela') {terceiraParcelaIndividual++;}
        if (valor.toLowerCase() == 'pag. 4º parcela') {quartaParcelaIndividual++;}
        if (valor.toLowerCase() == 'pag. 5º parcela') {quintaParcelaIndividual++;}
        if (valor.toLowerCase() == 'finalizado') {sextaParcelaIndividual++;}
        if (valor.toLowerCase() == 'cancelado') {canceladosIndividual++;}

    });

    dadosColuna11.each(function (valor) {
        if (valor.toLowerCase() == 'atrasado') {atrasadoIndividual++;}
    });

    let countAprovado = dadosColuna11.filter((value, index) =>  value === 'Aprovado').length;

    let total = dadosColuna00.count(); // Inicialmente, contamos todas as linhas

    atrasadoIndividual = total - sextaParcelaIndividual - canceladosIndividual - countAprovado;

    $(".individual_quantidade_1_parcela").text(primeiraParcelaIndividual);
    $(".individual_quantidade_2_parcela").text(segundaParcelaIndividual);
    $(".individual_quantidade_3_parcela").text(terceiraParcelaIndividual);
    $(".individual_quantidade_4_parcela").text(quartaParcelaIndividual);
    $(".individual_quantidade_5_parcela").text(quintaParcelaIndividual);
    $(".individual_quantidade_6_parcela").text(sextaParcelaIndividual);
    $(".individual_quantidade_cancelado").text(canceladosIndividual);
    $(".individual_quantidade_atrasado").text(atrasadoIndividual);
});





$("#list_coletivo_begin").on('click',function(){

    table.column(9).search('').draw();
    table.column(11).search('').draw();

    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Listagem(Completa)</h4>");
    $("#atrasado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#finalizado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#cancelado_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#emissao_boleto_coletivo").removeClass('textoforte-list').removeClass('destaque_content');
    $('#content_list_coletivo_begin').addClass('destaque_content_radius');



});

$("#list_empresarial_begin").on('click',function(){

    tableempresarial.column(9).search('').draw();
    tableempresarial.column(11).search('').draw();
    tableempresarial.column(7).search('').draw();

    $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list').removeClass('destaque_content');
    $("#atrasado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#finalizado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#aguardando_cancelado_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#content_list_empresarial_begin").addClass('destaque_content_radius');
    //$("#grupo_finalizados_empresarial li.empresarial").removeClass('textoforte-list').removeClass('destaque_content');
    //$("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Contratos</h4>");
    //tableempresarial.ajax.url('{{route("contratos.listarEmpresarial.listarContratoEmpresaPendentes")}}').load();
});

$("ul#listar li.coletivo").on('click',function(){
    let id_lista = $(this).attr('id');
    if(id_lista == "em_analise_coletivo") {
        $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Em Análise</h4>");

        table.column(11).search('').draw();
        table.column(9).search('Em Análise').draw();

        $(".container_edit").removeClass('ocultar');
        $("ul#listar li.coletivo").removeClass('textoforte-list');
        $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
        $("#all_pendentes_coletivo").removeClass('textoforte-list');
        $("#finalizado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#grupo_finalizados").removeClass('destaque_content_radius').removeClass('textoforte-list');
        $("#listar li").removeClass('destaque_content');
        $("#content_list_coletivo_begin").removeClass('destaque_content_radius');
        $("#atrasado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $(this).addClass('textoforte-list').addClass('destaque_content');
    } else if(id_lista == "emissao_boleto_coletivo") {
        $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Emissão Boleto</h4>");
        table.column(11).search('').draw();
        table.column(9).search('Emissão Boleto').draw();

        $(".container_edit").addClass('ocultar');
        $("ul#listar li.coletivo").removeClass('textoforte-list');
        $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
        $("#finalizado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#grupo_finalizados").removeClass('destaque_content_radius').removeClass('textoforte-list');
        $("#listar li").removeClass('destaque_content');
        $("#content_list_coletivo_begin").removeClass('destaque_content_radius');
        $("#atrasado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $(this).addClass('textoforte-list').addClass('destaque_content');
    } else if(id_lista == "pagamento_adesao_coletivo") {
        $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Pagamento Adesão</h4>");
        table.column(11).search('').draw();
        table.column(9).search('Pag. Adesão').draw();

        $(".container_edit").addClass('ocultar');
        $("ul#listar li.coletivo").removeClass('textoforte-list');
        $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
        $("#finalizado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#grupo_finalizados").removeClass('destaque_content_radius').removeClass('textoforte-list');
        $("#listar li").removeClass('destaque_content');
        $("#content_list_coletivo_begin").removeClass('destaque_content_radius');
        $("#atrasado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $(this).addClass('textoforte-list').addClass('destaque_content');
    } else if(id_lista == "pagamento_vigencia_coletivo") {
        $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Pagamento Vigência</h4>");
        table.column(11).search('').draw();
        table.column(9).search('Pag. Vigência').draw();

        $(".container_edit").addClass('ocultar');
        $("ul#listar li.coletivo").removeClass('textoforte-list');
        $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
        $("#finalizado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#grupo_finalizados").removeClass('destaque_content_radius').removeClass('textoforte-list');
        $("#listar li").removeClass('destaque_content');
        $("#content_list_coletivo_begin").removeClass('destaque_content_radius');
        $("#atrasado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $(this).addClass('textoforte-list').addClass('destaque_content');
    } else if(id_lista == "pagamento_segunda_parcela") {
        $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Pagamento 2º Parcela</h4>");
        table.column(11).search('').draw();
        table.column(9).search('Pag. 2º Parcela').draw();

        $(".container_edit").addClass('ocultar');
        $("#finalizado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("ul#listar li.coletivo").removeClass('textoforte-list');
        $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
        $("#grupo_finalizados").removeClass('destaque_content_radius').removeClass('textoforte-list');
        $("#listar li").removeClass('destaque_content');
        $("#content_list_coletivo_begin").removeClass('destaque_content_radius');
        $("#atrasado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $(this).addClass('textoforte-list').addClass('destaque_content');
    } else if(id_lista == "pagamento_terceira_parcela") {
        $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Pagamento 3º Parcela</h4>");
        table.column(11).search('').draw();
        table.column(9).search('Pag. 3º Parcela').draw();

        $(".container_edit").addClass('ocultar');
        $("#finalizado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("ul#listar li.coletivo").removeClass('textoforte-list');
        $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
        $("#grupo_finalizados").removeClass('destaque_content_radius').removeClass('textoforte-list');
        $("#listar li").removeClass('destaque_content');
        $("#content_list_coletivo_begin").removeClass('destaque_content_radius');
        $("#atrasado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $(this).addClass('textoforte-list').addClass('destaque_content');
    } else if(id_lista == "pagamento_quarta_parcela") {
        $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Pagamento 4º Parcela</h4>");
        table.column(11).search('').draw();
        table.column(9).search('Pag. 4º Parcela').draw();


        $("ul#listar li.coletivo").removeClass('textoforte-list');
        $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
        $("#finalizado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#grupo_finalizados").removeClass('destaque_content_radius').removeClass('textoforte-list');
        $("#listar li").removeClass('destaque_content');
        $("#content_list_coletivo_begin").removeClass('destaque_content_radius');
        $("#atrasado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $(this).addClass('textoforte-list').addClass('destaque_content');
    } else if(id_lista == "pagamento_quinta_parcela") {
        $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Pagamento 5º Parcela</h4>");
        table.column(11).search('').draw();
        table.column(9).search('Pag. 5º Parcela').draw();
        $("ul#listar li.coletivo").removeClass('textoforte-list');
        $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
        $("#finalizado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#grupo_finalizados").removeClass('destaque_content_radius').removeClass('textoforte-list');
        $("#listar li").removeClass('destaque_content');
        $("#content_list_coletivo_begin").removeClass('destaque_content_radius');
        $("#atrasado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $(this).addClass('textoforte-list').addClass('destaque_content');

    } else if(id_lista == "pagamento_sexta_parcela") {
        $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Pagamento 6º Parcela</h4>");
        table.column(11).search('').draw();
        table.column(9).search('Pag. 6º Parcela').draw();
        $("ul#listar li.coletivo").removeClass('textoforte-list');
        $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
        $("#finalizado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#grupo_finalizados").removeClass('destaque_content_radius').removeClass('textoforte-list');
        $("#listar li").removeClass('destaque_content');
        $("#content_list_coletivo_begin").removeClass('destaque_content_radius');
        $("#atrasado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $(this).addClass('textoforte-list').addClass('destaque_content');
    } else {

    }
});


$("#finalizado_corretor_coletivo").on('click',function(){
    table.search('').columns().search('').draw();
    table.column(9).search('Finalizado').draw();

    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Finalizado</h4>");
    $("#grupo_finalizados").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("ul#listar li.coletivo").removeClass('textoforte-list').removeClass('destaque_content');
    $("#content_list_coletivo_begin").removeClass('destaque_content_radius');
    $(this).addClass('textoforte-list').addClass('destaque_content_radius');
});





$("#all_pendentes_individual").on('click',function(){
    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Contratos</h4>");
    table_individual.ajax.url("{{ route('financeiro.individual.geralIndividualPendentes') }}").load();
    $("ul#listar_individual li.individual").removeClass('textoforte-list');
    $("#atrasado_corretor").removeClass('textoforte-list');
    $(this).addClass('textoforte-list');
});






$("ul#listar_individual li.individual").on('click',function(){
    let id_lista = $(this).attr('id');
    parcelaSelecionada = id_lista;
    //updateFiltragemParcela(id_lista);
    if(id_lista == "aguardando_pagamento_1_parcela_individual") {
        let mes = $("#mudar_mes_table").val();
        let dataId = $("#select_usuario_individual").find('option:selected').data('id');
        $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Pagamento 1º Parcela</h4>");
        table_individual.column(11).search('').draw();
        table_individual.column(9).search('Pag. 1º Parcela').draw();
        $(".container_edit").addClass('ocultar');
        $("#atrasado_corretor").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("ul#listar_individual li.individual").removeClass('textoforte-list');
        $("#all_pendentes_individual").removeClass('textoforte-list');
        $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
        $("#content_list_individual_begin").removeClass('destaque_content_radius').removeClass('destaque_content');
        $("#listar_individual li").removeClass('destaque_content');
        $("#cancelado_corretor").removeClass('destaque_content_radius').removeClass('textoforte-list');
        $("#finalizado_corretor").removeClass('destaque_content_radius').removeClass('textoforte-list');
        $(this).addClass('textoforte-list').addClass('destaque_content');
    } else if(id_lista == "aguardando_pagamento_2_parcela_individual") {
        let mes = $("#mudar_mes_table").val();
        let dataId = $("#select_usuario_individual").find('option:selected').data('id');
        $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Pagamento 2º Parcela</h4>");
        table_individual.column(11).search('').draw();
        table_individual.column(9).search('Pag. 2º Parcela').draw();
        $("#atrasado_corretor").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $(".container_edit").addClass('ocultar');
        $("ul#listar_individual li.individual").removeClass('textoforte-list');
        $("#all_pendentes_individual").removeClass('textoforte-list');
        $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
        $("#finalizado_corretor").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#cancelado_individual").removeClass('textoforte-list');
        $("#content_list_individual_begin").removeClass('destaque_content_radius').removeClass('destaque_content');
        $("#listar_individual li").removeClass('destaque_content');
        $("#cancelado_corretor").removeClass('destaque_content_radius').removeClass('textoforte-list');
        $(this).addClass('textoforte-list').addClass('destaque_content');
    } else if(id_lista == "aguardando_pagamento_3_parcela_individual") {
        //table_individual.clear().draw();
        let mes = $("#mudar_mes_table").val();
        let dataId = $("#select_usuario_individual").find('option:selected').data('id');
        $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Pagamento 3º Parcela</h4>");
        table_individual.column(11).search('').draw();
        table_individual.column(9).search('Pag. 3º Parcela').draw();
        $("#atrasado_corretor").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $(".container_edit").addClass('ocultar');
        $("#cancelado_individual").removeClass('textoforte-list');
        //adicionarReadonly();
        $("ul#listar_individual li.individual").removeClass('textoforte-list');
        $("#all_pendentes_individual").removeClass('textoforte-list');
        $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
        $("#finalizado_corretor").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#content_list_individual_begin").removeClass('destaque_content_radius').removeClass('destaque_content');
        $("#listar_individual li").removeClass('destaque_content');
        $("#cancelado_corretor").removeClass('destaque_content_radius').removeClass('textoforte-list');
        $(this).addClass('textoforte-list').addClass('destaque_content');
    } else if(id_lista == "aguardando_pagamento_4_parcela_individual") {
        //table_individual.clear().draw();
        $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Pagamento 4º Parcela</h4>");
        table_individual.column(11).search('').draw();
        table_individual.column(9).search('Pag. 4º Parcela').draw();
        $("#atrasado_corretor").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $(".container_edit").addClass('ocultar')
        $("ul#listar_individual li.individual").removeClass('textoforte-list');
        $("#all_pendentes_individual").removeClass('textoforte-list');
        $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
        $("#finalizado_corretor").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#content_list_individual_begin").removeClass('destaque_content_radius').removeClass('destaque_content');
        $("#listar_individual li").removeClass('destaque_content');
        $("#cancelado_corretor").removeClass('destaque_content_radius').removeClass('textoforte-list');
        $(this).addClass('textoforte-list').addClass('destaque_content');
        $("#cancelado_individual").removeClass('textoforte-list');
    } else if(id_lista == "aguardando_pagamento_5_parcela_individual") {
        //table_individual.clear().draw();
        $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Pagamento 5º Parcela</h4>");
        table_individual.column(11).search('').draw();
        table_individual.column(9).search('Pag. 5º Parcela').draw();
        $(".container_edit").addClass('ocultar');
        $("ul#listar_individual li.individual").removeClass('textoforte-list');
        $("#all_pendentes_individual").removeClass('textoforte-list');
        $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
        $("#finalizado_corretor").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#cancelado_individual").removeClass('textoforte-list').removeClass('destaque_content_radius')
        $("#atrasado_corretor").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#content_list_individual_begin").removeClass('destaque_content_radius').removeClass('destaque_content');
        $("#listar_individual li").removeClass('destaque_content');
        $("#cancelado_corretor").removeClass('destaque_content_radius').removeClass('textoforte-list');
        $(this).addClass('textoforte-list').addClass('destaque_content');
    }
});

$("#aguardando_pagamento_6_parcela_individual").on('click',function(){
    //table_individual.clear().draw();
    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;margin-left:5px;'>Pagamento 6º Parcela</h4>");
    let mes = $("#mudar_mes_table").val();
    let dataId = $("#select_usuario_individual").find('option:selected').data('id');
    table_individual.column(11).search('').draw();
    table_individual.column(9).search('Finalizado').draw();
    $(".container_edit").addClass('ocultar')
    $("ul#listar_individual li.individual").removeClass('textoforte-list').removeClass('destaque_content');
    $("#all_pendentes_individual").removeClass('textoforte-list');
    $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list')
    $("#cancelado_corretor").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#atrasado_corretor").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#finalizado_corretor").addClass('textoforte-list').addClass('destaque_content_radius');
    $("#content_list_individual_begin").removeClass('destaque_content_radius').removeClass('destaque_content');
});



$("ul#grupo_finalizados li.coletivo").on('click',function(){
    let id_lista = $(this).attr('id');
    if(id_lista == "finalizado_coletivo") {
        $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Finalizado</h4>");

        $('.buttons').empty().html();
        $(".container_edit").addClass('ocultar');
        $("ul#listar li.coletivo").removeClass('textoforte-list');
        $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
        $("#all_pendentes_coletivo").removeClass('textoforte-list');
        $(this).addClass('textoforte-list');

    } else if(id_lista == "cancelado_coletivo") {
        $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Cancelado</h4>");
        table.column(9).search('Cancelado').draw();

        $('.buttons').empty().html();
        $(".container_edit").addClass('ocultar');
        $("ul#listar li.coletivo").removeClass('textoforte-list');
        $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
        $("#all_pendentes_coletivo").removeClass('textoforte-list');
        $("#listar li").removeClass('destaque_content')
        $("#content_list_coletivo_begin").removeClass('destaque_content_radius');
        $("#finalizado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#atrasado_corretor_coletivo").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $(this).closest("ul").addClass('textoforte-list').addClass('destaque_content_radius');

    } else {

    }
});




$("#atrasado_corretor_empresarial").on('click',function(){
    $('#title_empresarial').html("<h4 style='font-size:1em;margin-top:10px;'>Atrasado</h4>");

    tableempresarial.column(9).search('').draw();
    tableempresarial.column(11).search('Atrasado').draw();


    $("#content_list_empresarial_begin").removeClass('destaque_content_radius');
    $("#finalizado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#aguardando_cancelado_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#listar_empresarial li").removeClass('textoforte-list').removeClass('destaque_content');
    $(this).addClass('textoforte-list').addClass('destaque_content_radius');


});

$("#finalizado_corretor_empresarial").on('click',function(){
    tableempresarial.column(9).search('Finalizado').draw();
    $('#title_empresarial').html("<h4 style='font-size:1em;margin-top:10px;'>Finalizado</h4>");
    $("#content_list_empresarial_begin").removeClass('destaque_content_radius');
    $("#finalizado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#aguardando_cancelado_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#listar_empresarial li").removeClass('textoforte-list').removeClass('destaque_content');
    $(this).addClass('textoforte-list').addClass('destaque_content_radius');

});

$("#atrasado_corretor").on('click',function(){
    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Atrasado</h4>");
    table_individual.column(9).search('').draw();
    table_individual.column(11).search('Atrasado').draw();
    table_individual.column(9).search('^(?!.*(?:Cancelado|Finalizado)).*$', true, false).draw();
    $("ul#listar_individual li.individual").removeClass('textoforte-list').removeClass('destaque_content');
    $("#all_pendentes_individual").removeClass('textoforte-list');
    $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
    $(this).addClass('textoforte-list').addClass('destaque_content_radius');
    $("#aguardando_pagamento_6_parcela_individual").removeClass('textoforte-list');
    $("#finalizado_corretor").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#cancelado_corretor").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#content_list_individual_begin").removeClass('destaque_content').removeClass('destaque_content_radius');
});

$("#cancelado_individual").on('click',function(){
    let mes = $("#mudar_mes_table").val();
    let dataId = $("#select_usuario_individual").find('option:selected').data('id');
    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Cancelado</h4>");
    $('.button_individual').empty().html('');
    $(".container_edit").addClass('ocultar');
    $("#atrasado_corretor").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("ul#listar_individual li.individual").removeClass('textoforte-list');
    $("#aguardando_pagamento_6_parcela_individual").removeClass('textoforte-list');
    $("#all_pendentes_individual").removeClass('textoforte-list');
    $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
    $("#finalizado_corretor").removeClass('textoforte-list');
    $("#listar_individual li").removeClass('destaque_content');
    $("#cancelado_corretor").addClass('textoforte-list');
    $("#cancelado_corretor").addClass('destaque_content_radius');
    $("#content_list_individual_begin").removeClass('destaque_content_radius').removeClass('destaque_content');
    $("#finalizado_corretor").removeClass('destaque_content_radius');



    table_individual.column(9).search('Cancelado').draw();




});


$("#aguardando_cancelado_empresarial").on('click',function(){
    $('#title_empresarial').html("<h4 style='font-size:1em;margin-top:10px;'>Cancelado</h4>");
    tableempresarial.column(9).search('Cancelado').draw();
    $("#content_list_empresarial_begin").removeClass('destaque_content_radius');
    $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list').removeClass('destaque_content');
    $("#finalizado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#atrasado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $(this).addClass('textoforte-list').addClass('destaque_content_radius');
});

$("#finalizado_corretor_empresarial").on('click',function(){
    $('#title_empresarial').html("<h4 style='font-size:1em;margin-top:10px;'>Cancelado</h4>");
    tableempresarial.column(9).search('Finalizado').draw();
    $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list').removeClass('destaque_content');
    $("#aguardando_cancelado_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $("#atrasado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
    $(this).addClass('textoforte-list').addClass('destaque_content_radius');
});




$("ul#grupo_finalizados_empresarial li.empresarial").on('click',function(){
    let id_lista = $(this).attr('id');
    if(id_lista == "aguardando_finalizado_empresarial") {
        $('#title_empresarial').html("<h4 style='font-size:1em;margin-top:10px;'>Finalizado</h4>");
        tableempresarial.column(9).search('Finalizado').draw();


        $(".container_edit").addClass('ocultar');
        $("ul#grupo_finalizados_empresarial li.empresarial").removeClass('textoforte-list');
        $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list');
        $(this).addClass('textoforte-list');

    }
});

$("ul#listar_empresarial li.empresarial").on('click',function(){
    let id_lista = $(this).attr('id');
    if(id_lista == "aguardando_em_analise_empresarial") {
        $("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;'>Em Análise</h4>");
        $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list').removeClass('destaque_content');
        $("#atrasado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#aguardando_cancelado_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#finalizado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#content_list_empresarial_begin").removeClass('destaque_content_radius');
        $(this).addClass('textoforte-list').addClass('destaque_content');
        tableempresarial.column(9).search('Em Análise').draw();

    } else if(id_lista == "aguardando_pagamento_1_parcela_empresarial") {
        $("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 1º Parcela</h4>");
        $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list').removeClass('destaque_content');
        $("#atrasado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#aguardando_cancelado_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#finalizado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#content_list_empresarial_begin").removeClass('destaque_content_radius');
        $(this).addClass('textoforte-list').addClass('destaque_content');
        tableempresarial.column(9).search('Pag. 1º Parcela').draw();

    } else if(id_lista == "aguardando_pagamento_2_parcela_empresarial") {
        $("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 2º Parcela</h4>");
        $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list').removeClass('destaque_content');
        $("#atrasado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#aguardando_cancelado_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#finalizado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#content_list_empresarial_begin").removeClass('destaque_content_radius');
        $(this).addClass('textoforte-list').addClass('destaque_content');
        tableempresarial.column(9).search('Pag. 2º Parcela').draw();

    } else if(id_lista == "aguardando_pagamento_3_parcela_empresarial") {
        $("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 3º Parcela</h4>");
        $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list').removeClass('destaque_content');
        $("#atrasado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#aguardando_cancelado_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#finalizado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#content_list_empresarial_begin").removeClass('destaque_content_radius');
        $(this).addClass('textoforte-list').addClass('destaque_content');
        tableempresarial.column(9).search('Pag. 3º Parcela').draw();

    } else if(id_lista == "aguardando_pagamento_4_parcela_empresarial") {
        $("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 4º Parcela</h4>");
        $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list').removeClass('destaque_content');
        $("#atrasado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#aguardando_cancelado_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#finalizado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#content_list_empresarial_begin").removeClass('destaque_content_radius');
        $(this).addClass('textoforte-list').addClass('destaque_content');
        tableempresarial.column(9).search('Pag. 4º Parcela').draw();

    } else if(id_lista == "aguardando_pagamento_5_parcela_empresarial") {
        $("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 5º Parcela</h4>");
        $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list').removeClass('destaque_content');
        $("#atrasado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#aguardando_cancelado_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#finalizado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#content_list_empresarial_begin").removeClass('destaque_content_radius');
        $(this).addClass('textoforte-list').addClass('destaque_content');
        tableempresarial.column(9).search('Pag. 5º Parcela').draw();

    } else if(id_lista == "aguardando_pagamento_6_parcela_empresarial") {
        $("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 6º Parcela</h4>");
        $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list').removeClass('destaque_content');
        $("#atrasado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#aguardando_cancelado_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#finalizado_corretor_empresarial").removeClass('textoforte-list').removeClass('destaque_content_radius');
        $("#content_list_empresarial_begin").removeClass('destaque_content_radius');
        $(this).addClass('textoforte-list').addClass('destaque_content');
        tableempresarial.column(9).search('Pag. 6º Parcela').draw();

    }  else {

    }
});
