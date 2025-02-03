if (window.location.hash) {
    var tabelaID = window.location.hash.substring(1);
    $('#' + tabelaID).DataTable().state.load();
}
let url = window.location.href.indexOf("?");
if(url != -1) {
    var b =  window.location.href.substring(url);
    var alvo = b.split("=")[1].split("&")[0];
    if(alvo == "coletivo") {
        $('.list_abas li').removeClass('ativo');
        $('.list_abas li:nth-child(2)').addClass("ativo");
        $('.conteudo_abas main').addClass('ocultar');
        $('#aba_coletivo').removeClass('ocultar');
        $('#aba_coletivo').removeClass('hidden');
        var c = window.location.href.replace(b,"");
        window.history.pushState({path:c},'',c);
        $("#janela_atual").val("aba_coletivo");
        inicializarColetivo();
    }
    if(alvo == "empresarial") {
        $('.list_abas li').removeClass('ativo');
        $('.list_abas li:nth-child(3)').addClass("ativo");
        $('.conteudo_abas main').addClass('ocultar');
        $("#aguardando_em_analise_empresarial").addClass("text");
        $("#aguardando_em_analise_empresarial").addClass('textoforte-list');
        $('#aba_empresarial').removeClass('ocultar');
        $('#aba_empresarial').removeClass('hidden');
        var c = window.location.href.replace(b,"");
        window.history.pushState({path:c},'',c);
        $("#janela_atual").val("aba_empresarial");
        inicializarEmpresarial();
    }
}
