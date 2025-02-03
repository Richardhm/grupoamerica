<x-app-layout>
    @section('css')
       <link rel="stylesheet" href="{{asset('css/estilo-financeiro.css')}}"/>
    @endsection
    <input type="hidden" id="janela_atual" value="aba_individual">

        <!-- Modal -->
        <script>

            var urlGeralIndividualPendentes = "{{ route('financeiro.individual.geralIndividualPendentes') }}";
            var urlGeralColetivoPendentes = "{{ route('financeiro.coletivo.em_geral') }}";
            var editarAdministradoraChange = "{{route('financeiro.administradora.change')}}"
            var urlGeralEmpresarialPendentes = "{{ route('contratos.listarEmpresarial.listarContratoEmpresaPendentes') }}";
            var listarOdonto = "{{ route('odonto.listar') }}";
            var financeiroSincroniza = "{{route('financeiro.sincronizar')}}";
            var atualizarIndividual = "{{route('financeiro.sincronizar.baixas.jaexiste')}}";
            var cancelarIndividual = "{{route('financeiro.sincronizar.cancelados')}}";
            var individualFinanceiroInicializar = "{{route('financeiro.modal.contrato.individual')}}";
            var coletivoFinanceiroInicializar = "{{route('financeiro.modal.contrato.coletivo')}}";
            var empresarialFinanceiroInicializar = "{{route('financeiro.modal.contrato.empresarial')}}";
            var urlBaixaColetivo        = "{{route('financeiro.baixa.data')}}";
            var desfazerColetivo        = "{{route('desfazer.tarefa.coletivo')}}";
            var cadastrarOdonto         = "{{ route('odonto.create') }}";
            var emAnaliseAjax           = "{{route('financeiro.analise.coletivo')}}";
            var emissaoBoleto           = "{{route('financeiro.analise.boleto')}}";
            var empresarialEmAnalise    = "{{route('financeiro.analise.empresarial')}}";
            var empresarialDataBaixa    = "{{route('financeiro.baixa.data.empresarial')}}";
            var changecorretor          = "{{route('financeiro.changeFinanceiro')}}";
            var changecorretorColetivo  = "{{route('financeiro.changeFinanceiroColetivo')}}";
            var changecorretorEmpresarial  = "{{route('financeiro.changeFinanceiroEmpresarial')}}";
            var changeValoresCorretorEmpresarial  = "{{route('financeiro.changeValoresFinanceiroEmpresarial')}}";
            var financeiroCanceladoColetivo = "{{route('financeiro.contrato.cancelados')}}";
            var excluirColetivo         = "{{route('financeiro.excluir.cliente')}}";
            var excluirEmpresarial      = "{{route('financeiro.excluir.empresarial')}}";
            var cancelarEmpresarial      = "{{route('financeiro.cancelar.empresarial')}}";
            var mudarCampoIndividual = "{{route('financeiro.editar.campoIndividualmente')}}";
            var editarCampoColetivo = "{{route('financeiro.editar.campoColetvivo')}}";
            var editarCampoEmpresarial = "{{route('financeiro.editar.campoEmpresarial')}}";
            var table;
            var table_individual;
            var parcelaSelecionada;
            var tableodonto;
            var tableempresarial;
        </script>
            <div style="width:95%;margin:0 auto;">
            <ul class="list_abas">
                <li data-id="aba_individual" class="ativo">Individual</li>
                <li data-id="aba_coletivo">Coletivo</li>
                <li data-id="aba_empresarial">Empresarial</li>
                @if(auth()->user()->corretora_id == 1)
                    <li data-id="aba_odonto">Odonto</li>
                @endif
            </ul>
    </div>
    <x-upload-modal></x-upload-modal>
    <x-upload-atualizar></x-upload-atualizar>

        <div id="uploadCancelados" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadCanceladosLabel">Cancelados</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST" name="formulario_cancelados" id="formulario_cancelados" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="i524MANFIfAQJZREDJDrXQnmGwbTztwzbKviczIP">                        <div class="d-flex">
                                <div style="flex-basis:90%;margin-right:2%;">
                                    <label for="arquivo_cancelados">Arquivo:</label>
                                    <input type="file" name="arquivo_cancelados" id="arquivo_cancelados" class="form-control form-control-sm">
                                </div>
                                <div class="btn btn-danger d-flex align-self-end div_icone_arquivo_upload" style="flex-basis:5%;">
                                    <i class="fas fa-window-close fa-lg"></i>
                                </div>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>




        <!-- O container de loading com 3 pontinhos -->
        <div id="loading-overlay" class="ocultar" style="display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
            <div class="dots-loading">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div id="myModalIndividual" class="fixed inset-0 z-50 flex items-center justify-center hidden">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] z-40"></div>
            <!-- Conteúdo da Modal -->
            <div class="relative w-11/12 rounded-lg shadow-3xl p-2 z-50">
                <!-- Botão Fechar no Topo -->
                <div id="modalLoaderIndividual" class="flex justify-center items-center h-64">
                    <div class="dot-flashing">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
                <!-- Borda Animada -->
                <div class="relative p-1 rounded-lg animate-border overflow-hidden content-modal-individual hidden">
                </div>
            </div>
        </div>

        <div id="myModalColetivo" class="fixed inset-0 z-50 flex items-center justify-center hidden">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] z-40"></div>
            <!-- Conteúdo da Modal -->
            <div class="relative w-11/12 rounded-lg shadow-3xl p-2 z-50">
                <!-- Botão Fechar no Topo -->
                <div id="modalLoader" class="flex justify-center items-center h-64">
                    <div class="dot-flashing">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
                <!-- Borda Animada -->
                <div class="relative p-1 rounded-lg animate-border overflow-hidden content-modal-coletivo hidden">
                </div>
            </div>
        </div>

        <div id="myModalCreateOdonto" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]" onclick="closeModalOnClickOutside(event)">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl relative">
                <!-- Botão para fechar a modal -->
                <button onclick="closeModalOdonto()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl font-bold">
                    &times;
                </button>

                <!-- Conteúdo da Aba Cadastrar -->
                <div id="contentCadastrar" class="p-4">
                    <div class="flex flex-col space-y-4">
                        <div>
                            <label for="user_id" class="block mb-2">Corretor</label>
                            <select name="user_id" id="user_id" class="border rounded px-4 py-2 w-full">
                                <option value="">--Escolher Corretor--</option>
                                @foreach($users as $u)
                                    <option value="{{$u->id}}">{{$u->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="nome" class="block mb-2">Cliente</label>
                            <input type="text" name="nome" id="nome" class="border rounded px-4 py-2 w-full">
                        </div>

                        <div>
                            <label for="valor" class="block mb-2">Valor</label>
                            <input type="text" name="valor" id="valor" class="border rounded px-4 py-2 w-full">
                        </div>

                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full cadastrar_odonto">Cadastrar</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="myModalEmpresarial" class="fixed inset-0 z-50 flex items-center justify-center hidden">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] z-40"></div>
            <!-- Conteúdo da Modal -->
            <div class="relative w-11/12 rounded-lg shadow-3xl p-2 z-50">
                <!-- Botão Fechar no Topo -->
                <div id="modalLoaderEmpresa" class="flex justify-center items-center h-64">
                    <div class="dot-flashing">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
                <!-- Borda Animada -->
                <div class="relative p-1 rounded-lg animate-border overflow-hidden content-modal-empresarial hidden">
                </div>
            </div>
        </div>

        <section class="conteudo_abas mt-2" style="width:95%;margin:0 auto;">
            <x-aba-individual></x-aba-individual>
            <x-aba-coletivo></x-aba-coletivo>
            <x-aba-empresarial></x-aba-empresarial>
            @if(auth()->user()->corretora_id == 1)
                <x-aba-odonto></x-aba-odonto>
            @endif
        </section>

    <script>


        function closeModalOdonto() {
            document.getElementById('myModalCreateOdonto').classList.add('hidden');
        }

        function closeModalOnClickOutside(event) {
            const modal = document.getElementById('myModalCreateOdonto');
            if (event.target === modal) {
                closeModalOdonto();
            }
        }




        const dropdownButton = document.getElementById('dropdownButton');
        const dropdownOptions = document.getElementById('dropdownOptions');
        //const dropdownButtonText = dropdownButton.querySelector('span'); // Apenas o span de texto

        function showTab(tab) {
            var contentListar = document.getElementById('contentListar');
            var contentCadastrar = document.getElementById('contentCadastrar');
            var tabListar = document.getElementById('tabListar');
            var tabCadastrar = document.getElementById('tabCadastrar');

            if (tab === 'listar') {
                contentListar.classList.remove('hidden');
                contentCadastrar.classList.add('hidden');
                tabListar.classList.add('border-blue-500', 'text-blue-500');
                tabCadastrar.classList.remove('border-blue-500', 'text-blue-500');
            } else {
                contentListar.classList.add('hidden');
                contentCadastrar.classList.remove('hidden');
                tabListar.classList.remove('border-blue-500', 'text-blue-500');
                tabCadastrar.classList.add('border-blue-500', 'text-blue-500');
            }
        }
        // dropdownButton.addEventListener('click', () => {
        //     dropdownOptions.classList.toggle('hidden');
        // });

        // dropdownOptions.addEventListener('click', (event) => {
        //     if (event.target.tagName === 'LI') {
        //         const selectedOption = event.target.textContent;
        //         const value = event.target.getAttribute('data-value');
        //         //dropdownButtonText.textContent = selectedOption; // Atualiza apenas o texto do span
        //         dropdownOptions.classList.add('hidden');
        //         inicializarIndividual(value);
        //     }
        // });


        $(document).ready(function(){

            $('#valor').mask('#.##0,00', {reverse: true});




            // $('#valor_contrato').mask('#.##0,00', {reverse: true});
            // $('#valor_adesao').mask('#.##0,00', {reverse: true});
            // $('#desconto_corretor').mask('#.##0,00', {reverse: true});
            // $('#desconto_corretora').mask('#.##0,00', {reverse: true});

            function getUrlParameter(name) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                var results = regex.exec(location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            }

            function totalMes() {
                return $("#select_usuario_individual").val();
            }

            String.prototype.ucWords = function () {
                let str = this.toLowerCase()
                let re = /(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g
                return str.replace(re, s => s.toUpperCase())
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("body").on('keydown', '.next', function(e) {
                e.preventDefault(); // Impede qualquer entrada de texto no campo
            });

            var currentStep = 1; // Etapa inicial

            $('.step-btn').on('click', function() {
                let step = $(this).data('step');
                if(step === currentStep) {
                    currentStep++;
                    $('#step-' + currentStep).show(); // Exibe a próxima etapa
                    if (currentStep >= 3) {
                        $('#step-' + currentStep + '-date').prop('disabled', false); // Habilita o campo de data
                    }
                    $(this).prop('disabled', true);
                } else {
                    alert('Por favor, complete a etapa anterior antes de prosseguir.');
                }
            });

            $('input[type="date"]').on('change', function() {
                let step = parseInt($(this).attr('id').split('-')[1]); // Pega o número da etapa
                if (step === currentStep) {
                    currentStep++;
                    $('#step-' + currentStep).show();
                    $('#step-' + currentStep + '-date').prop('disabled', false);
                } else {
                    alert('Por favor, complete a etapa anterior antes de prosseguir.');
                }
            });
        });
    </script>

        @section('scripts')

            <script src="{{asset('js/financeiro-arquivo.js')}}"></script>
            <script src="{{asset('js/financeiro-inicializar-individual.js')}}"></script>
            <script src="{{asset('js/financeiro-inicializar-coletivo.js')}}"></script>
            <script src="{{asset('js/financeiro-inicializar-empresarial.js')}}"></script>
            <script src="{{asset('js/financeiro-inicializar-odonto.js')}}"></script>

            <script src="{{asset('js/financeiro-click-menus.js')}}"></script>
            <script src="{{asset('js/financeiro-change-menus.js')}}"></script>

            <script src="{{asset('js/financeiro-parametro-url.js')}}"></script>
        @endsection







</x-app-layout>
