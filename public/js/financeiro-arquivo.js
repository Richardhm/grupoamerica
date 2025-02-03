$("#arquivo_atualizar").on('change',function(){
    let files = $('#arquivo_atualizar')[0].files;
    let load = $(".ajax_load");
    // let file = $(this).val();
    let fd = new FormData();
    fd.append('file',files[0]);
    // fd.append('file',e.target.files[0]);
    $.ajax({
        url:atualizarIndividual,
        method:"POST",
        data:fd,
        contentType: false,
        processData: false,
        beforeSend: function () {
            //$('#atualizarModal').modal('hide');
            load.fadeIn(200).css("display", "flex");

        },
        success:function(res) {
            if(res == "successo") {
                load.fadeOut(200);
                window.location.reload();
            }

        }
    });
});

$("#arquivo_cancelados").on('change',function(){
    let files = $('#arquivo_cancelados')[0].files;
    let load = $(".ajax_load");
    // let file = $(this).val();
    let fd = new FormData();
    fd.append('file',files[0]);
    // fd.append('file',e.target.files[0]);
    $.ajax({
        url:cancelarIndividual,
        method:"POST",
        data:fd,
        contentType: false,
        processData: false,
        beforeSend: function () {
            //load.fadeIn(200).css("display", "flex");
            //$('#uploadModal').modal('hide');
        },
        success:function(res) {
            if(res == "sucesso") {
                window.location.reload();
            }

        }
    });



});




/*************************************************REALIZAR UPLOAD DO EXCEL*********************************************************************/
$("#arquivo_upload").on('change',function(e){
    var files = $('#arquivo_upload')[0].files;
    var load = $(".ajax_load");
    // let file = $(this).val();
    var fd = new FormData();
    fd.append('file',files[0]);
    // fd.append('file',e.target.files[0]);
    $.ajax({
        url:financeiroSincroniza,
        method:"POST",
        data:fd,
        contentType: false,
        processData: false,
        beforeSend: function () {
            load.fadeIn(200).css("display", "flex");
            //$('#uploadModal').modal('hide');
        },
        success:function(res) {

            if(res == "sucesso") {
                window.location.reload();
                // load.fadeOut(200);
                // $('#uploadModal').modal('show');
                // $(".div_icone_arquivo_upload").removeClass('btn-danger').addClass('btn-success').html('<i class="far fa-smile-beam fa-lg"></i>');
                // $("#arquivo_upload").val('').prop('disabled',true);

            } else {

            }

        }
    });
});

/*************************************************Atualizar Dados*********************************************************************/
$(".atualizar_dados").on('click',function(){
    var load = $(".ajax_load");

    $.ajax({
        url:"{{route('financeiro.atualizar.dados')}}",
        method:"POST",
        beforeSend: function (res) {
            load.fadeIn(200).css("display", "flex");
            $('#uploadModal').modal('hide')
        },
        success:function(res) {
            if(res == "sucesso") {
                load.fadeOut(200);
                $('#uploadModal').modal('show');
                $(".div_icone_arquivo_upload").removeClass('btn-danger').addClass('btn-success').html('<i class="far fa-smile-beam fa-lg"></i>');
                $(".div_icone_atualizar_dados").removeClass('btn-danger').addClass('btn-success').html('<i class="far fa-smile-beam fa-lg"></i>');
                $(".atualizar_dados").removeClass('btn-warning').addClass('btn-secondary').prop('disabled',true);
                $("#arquivo_upload").val('').prop('disabled',true);
                window.location.href = response.redirect;
            }
        }
    });

    return false;
});
/*************************************************Sincronizar Dados*********************************************************************/
$(".sincronizar_baixas").on('click',function(){
    var load = $(".ajax_load");
    $.ajax({
        url:"{{route('financeiro.sincronizar.baixas')}}",
        method:"POST",
        beforeSend: function (res) {
            load.fadeIn(200).css("display", "flex");
            $('#uploadModal').modal('hide')

        },
        success:function(res) {

            if(res == "sucesso") {
                window.location.reload();
            } else {

            }
        }
    });
    return false;
});

/*****************************************************UPLOAD COLETIVO****************************************************************************** */
$("#arquivo_upload_coletivo").on('change',function(e){
    var files = $('#arquivo_upload_coletivo')[0].files;
    var load = $(".ajax_load");
    var fd = new FormData();
    fd.append('file',files[0]);
    $.ajax({
        url:"{{route('financeiro.sincronizar.coletivo')}}",
        method:"POST",
        data:fd,
        contentType: false,
        processData: false,
        beforeSend: function () {
            load.fadeIn(200).css("display", "flex");
            $('#uploadModalColetivo').modal('hide');
        },
        success:function(res) {

            if(res == "sucesso") {
                load.fadeOut(200);
            } else {

            }

        }
    });
})
