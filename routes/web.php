<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EstrelaController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\ImagemController;
use App\Http\Controllers\OrcamentoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TabelaController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

//Route::domain('localhost')->group(function () {
//    Route::get('/', function (string $tenant) {
//        return view('welcome');
//    });
//});

Route::domain('{tenant}.bmsys.test')->group(function () {
    Route::get('/', function ($tenant) {
        return view('welcome');
    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/dashboard/tabela/orcamento',[TabelaController::class,'orcamento'])->middleware(['auth', 'verified'])->name('orcamento.tabela.montarOrcamento');

Route::middleware('auth')->group(function () {

    /***Tabela Full***/

    Route::get('/tabela_completa',[TabelaController::class,'index'])->name('tabela_completa.index');
    Route::get('/tabela',[TabelaController::class,'tabela_preco'])->name('tabela.config');
    Route::post('/corretora/select/planos/administradoras',[TabelaController::class,'planosAdministradoraSelect'])->name('planos.administradora.select');
    Route::post('/corretora/mudar/valor/tabela',[TabelaController::class,'mudarValorTabela'])->name('corretora.mudar.valor.tabela');
    Route::post("/tabela/verificar/valores",[TabelaController::class,'verificarValoresTabela'])->name("verificar.valores.tabela");
    Route::post("/tabela/cadastrar/valores",[TabelaController::class,'cadastrarValoresTabela'])->name("cadastrar.valores.tabela");
    Route::post("/coparticipacao/cadastrar/valores",[TabelaController::class,'cadastrarCoparticipacao'])->name("cadastrar.coparticipacao.tabela");
    Route::post("/coparticipacao/excecao/cadastrar/valores",[TabelaController::class,'cadastrarCoparticipacaoExcecao'])->name("cadastrar.excecao.coparticipacao.tabela");
    Route::post("/coparticipacao/existe/valores",[TabelaController::class,'coparticipacaoJaExiste'])->name("coparticipacao.ja.existe");
    /***Fim Tabela Full***/


    /******Estrela********/
    Route::get("/estrela",[EstrelaController::class,'index'])->name('estrela.index');
    /******Fim Estrela********/




    /*********ORCAMENTO*************/
    Route::get('/orcamento',[OrcamentoController::class,'index'])->name('orcamento');
    Route::post('/buscar_planos',[OrcamentoController::class,'buscar_planos'])->middleware(['auth', 'verified'])->name('buscar_planos');
    Route::post('/dashboard/orcamento',[OrcamentoController::class,'orcamento'])->middleware(['auth', 'verified'])->name('orcamento.montarOrcamento');
    /*********FIM ORCAMENTO*************/

    Route::post("/pdf",[ImagemController::class,'criarPDF'])->middleware(['auth', 'verified'])->name('gerar.imagem');




    /****Financeiro****/
    Route::get('/financeiro',[FinanceiroController::class,'index'])->name('financeiro.index');
    Route::get('/contratos/cadastrar/individual',[FinanceiroController::class,'formCreate'])->name('financeiro.formCreate');
    Route::get("/financeiro/individual/em_geral/{mes?}",[FinanceiroController::class,'geralIndividualPendentes'])->name('financeiro.individual.geralIndividualPendentes');
    Route::post('/financeiro/change/individual',[FinanceiroController::class,'changeIndividual'])->name('financeiro.changeFinanceiro');
    Route::post('/financeiro/change/coletivo',[FinanceiroController::class,'changeColetivo'])->name('financeiro.changeFinanceiroColetivo');
    Route::post('/financeiro/change/empresarial',[FinanceiroController::class,'changeEmpresarial'])->name('financeiro.changeFinanceiroEmpresarial');
    Route::post('/financeiro/valores/change/empresarial',[FinanceiroController::class,'changeValoresEmpresarial'])->name('financeiro.changeValoresFinanceiroEmpresarial');
    Route::post('/financeiro/administradora/change',[FinanceiroController::class,'changeAdministradora'])->name('financeiro.administradora.change');
    Route::post('/contratos/montarPlanosIndividual',[FinanceiroController::class,'montarPlanosIndividual'])->name('contratos.montarPlanosIndividual');
    Route::post('/contratos/individual',[FinanceiroController::class,'storeIndividual'])->name('individual.store');
    Route::get('/contratos/cadastrar/empresarial',[FinanceiroController::class,'formCreateEmpresarial'])->name('contratos.create.empresarial');
    Route::post('/financeiro/sincronizar_baixas/ja_existente',[FinanceiroController::class,'sincronizarBaixasJaExiste'])->name('financeiro.sincronizar.baixas.jaexiste');
    Route::get('/contratos/cadastrar/coletivo',[FinanceiroController::class,'formCreateColetivo'])->name('contratos.create.coletivo');
    Route::post('/contratos/montarPlanos',[FinanceiroController::class,'montarPlanos'])->name('contratos.montarPlanos');
    Route::post('/contratos',[FinanceiroController::class,'store'])->name('contratos.store');
    Route::get('/financeiro/detalhes/coletivo/{id}',[FinanceiroController::class,'detalhesContratoColetivo'])->name('financeiro.detalhes.contrato.coletivo');
    Route::post('/financeiro/modal/individual',[FinanceiroController::class,'modalIndividual'])->name('financeiro.modal.contrato.individual');
    Route::post('/financeiro/modal/coletivo',[FinanceiroController::class,'modalColetivo'])->name('financeiro.modal.contrato.coletivo');
    Route::post('/financeiro/modal/empresarial',[FinanceiroController::class,'modalEmpresarial'])->name('financeiro.modal.contrato.empresarial');
    Route::post('/financeiro/gerente/modal/empresarial',[FinanceiroController::class,'modalEmpresarialGerente'])->name('financeiro.gerente.modal.contrato.empresarial');
    Route::post('/financeiro/excluir',[FinanceiroController::class,'excluirCliente'])->name('financeiro.excluir.cliente');
    Route::post('/financeiro/empresarial/excluir',[FinanceiroController::class,'excluirEmpresarial'])->name('financeiro.excluir.empresarial');
    Route::post('/financeiro/cancelar/empresarial',[FinanceiroController::class,'cancelarEmpresarial'])->name('financeiro.cancelar.empresarial');
    Route::post('/financeiro/mudarEstadosColetivo',[FinanceiroController::class,'mudarEstadosColetivo'])->name('financeiro.mudarStatusColetivo');
    Route::post('/financeiro/cancelados',[FinanceiroController::class,'cancelarContrato'])->name('financeiro.contrato.cancelados');
    Route::post('/financeiro/baixaDaData',[FinanceiroController::class,'baixaDaData'])->name('financeiro.baixa.data');
    Route::post('/financeiro/empresarial/baixaDaDataEmpresarial',[FinanceiroController::class,'baixaDaDataEmpresarial'])->name('financeiro.baixa.data.empresarial');
    Route::post('/financeiro/editarCampoIndividualmente',[FinanceiroController::class,'editarCampoIndividualmente'])->name('financeiro.editar.campoIndividualmente');
    Route::post('/financeiro/editarCampoColetivo',[FinanceiroController::class,'editarCampoColetivo'])->name('financeiro.editar.campoColetvivo');
    Route::post('/financeiro/editarCampoEmpresarial',[FinanceiroController::class,'editarCampoEmpresarial'])->name('financeiro.editar.campoEmpresarial');
    Route::post('/financeiro/desfazer/coletivo',[FinanceiroController::class,'desfazerColetivo'])->name('desfazer.tarefa.coletivo');
    Route::post('/financeiro/sincronizar',[FinanceiroController::class,'sincronizarDados'])->name('financeiro.sincronizar');
    Route::get('/financeiro/detalhes/{id}',[FinanceiroController::class,'detalhesContrato'])->name('financeiro.detalhes.contrato');
    Route::post('/financeiro/analise/coletivo',[FinanceiroController::class,'emAnaliseColetivo'])->name('financeiro.analise.coletivo');
    Route::post('/financeiro/analise/empresarial',[FinanceiroController::class,'emAnaliseEmpresarial'])->name('financeiro.analise.empresarial');
    Route::post('/financeiro/boleto/coletivo',[FinanceiroController::class,'emissaoColetivo'])->name('financeiro.analise.boleto');
    Route::get('/financeiro/zerar/tabela',[FinanceiroController::class,'zerarTabelaFinanceiro'])->name('financeiro.zerar.financeiro');
    Route::get('/financeiro/coletivo/em_geral',[FinanceiroController::class,'coletivoEmGeral'])->name('financeiro.coletivo.em_geral');
    Route::get('/contratos/empendentes/empresarial',[FinanceiroController::class,'listarContratoEmpresaPendentes'])->name('contratos.listarEmpresarial.listarContratoEmpresaPendentes');
    Route::post('/financeiro/sincronizar/cancelados',[FinanceiroController::class,'sincronizarCancelados'])->name('financeiro.sincronizar.cancelados');
    Route::post('/financeiro/atualizar_dados',[FinanceiroController::class,'atualizarDados'])->name('financeiro.atualizar.dados');
    Route::post('/financeiro/sincronizar_baixas',[FinanceiroController::class,'sincronizarBaixas'])->name('financeiro.sincronizar.baixas');
    Route::post('/financeiro/sincronizar/coletivo',[FinanceiroController::class,'sincronizarDadosColetivo'])->name('financeiro.sincronizar.coletivo');
    Route::post('/contratos/empresarial/financeiro',[FinanceiroController::class,'storeEmpresarialFinanceiro'])->name('contratos.storeEmpresarial.financeiro');
    Route::post("/odonto/create",[FinanceiroController::class,'storeOdonto'])->name('odonto.create');
    Route::get("/odonto/listar",[FinanceiroController::class,'listarOdonto'])->name('odonto.listar');
    /****Fim Financeiro****/


    /*******Corretores*********/
    Route::get('/corretores',[ProfileController::class,'listar'])->name('corretores.listar');
    Route::get('/list/corretores',[ProfileController::class,'listUser'])->name('corretores.list');
    Route::post("/store/corretores",[ProfileController::class,'storeUser'])->name('corretores.store');
    Route::post("/destroy/corretore",[ProfileController::class,'destroyUser'])->name('destroy.corretor');
    Route::post('/alterar/corretor',[ProfileController::class,'alterarUser'])->name('corretores.alterar');
    Route::post('/alterar/user/corretor',[ProfileController::class,'alterarUserCLT'])->name('corretores.user.alterar');
    /*******Corretores*********/


    /*****Meus Clientes************/
    Route::get('/clientes',[ClienteController::class,'index'])->name('clientes.index');
    Route::get('/clientes/listar',[ClienteController::class,'listar'])->name('clientes.listar');
    Route::get('/clientes/coletivo/listar',[ClienteController::class,'listarColetivo'])->name('clientes.coletivo.listar');
    Route::get('/clientes/empresarial/listar',[ClienteController::class,'listarEmpresarial'])->name('clientes.empresarial.listar');
    /*****Meus Clientes************/



    /***********PERFIL************/
    Route::get('/perfil',[ProfileController::class,'perfil'])->name('perfil.index');
    Route::put('/perfil/alterar', [ProfileController::class, 'alterar'])->name('profile.alterar');
    /***********FIM PERFIL************/



    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
