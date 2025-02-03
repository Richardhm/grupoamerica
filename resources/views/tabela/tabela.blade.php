<x-app-layout>

    <div id="overlay" class="ocultar">
        <div id="loader">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>

    <div class="mx-auto p-2" style="width:95%;">

        <div class="flex justify-between items-center">
            <div class="flex-1">
                <h1 class="text-xl text-white font-semibold">
                    Tabela de Preços
                    <small class="text-red-500 text-xs">(Escolher todos os campos para liberar os campos de valores)</small>
                </h1>
            </div>
            <div class="hidden" id="editar_coparticipacao">
                <a href="#" class="flex items-center text-blue-600">
                    <i class="fas fa-pen"></i>
                    <span class="ml-1">Coparticipação</span>
                </a>
            </div>
        </div>

        <!-- Carregando -->
        <div class="ajax_load fixed inset-0 bg-opacity-50 bg-gray-700 flex items-center justify-center">
            <div class="text-center">
                <div class="ajax_load_box_circle"></div>
                <p class="text-white">Aguarde, carregando...</p>
            </div>
        </div>

        <ul class="flex mt-1 border-b list_abas">
            <li data-id="aba_tabela" class="py-2 px-4 cursor-pointer text-white ativo">Tabelas</li>
            <li data-id="aba_coparticipacao" class="py-2 px-4 cursor-pointer text-white">Coparticipação</li>
        </ul>

        <!-- Conteúdo da aba Tabelas -->
        <div id="aba_tabela" class="mt-4">
            <div id="container_alert_cadastrar" class="text-center"></div>

            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] shadow rounded p-4">
                <form action="" method="POST">
                    @csrf
                    <div class="grid grid-cols-5 gap-4">
                        <!-- Administradora -->
                        <div>
                            <label for="administradora" class="block text-sm font-medium text-white">Administradora:</label>
                            <select name="administradora" id="administradora" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">--Escolher a Administradora--</option>
                                @foreach($administradoras as $aa)
                                    <option value="{{$aa->id}}" {{$aa->id == old('administradora') ? 'selected' : ''}}>{{$aa->nome}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('administradora'))
                                <p class="text-red-500 text-xs mt-1">{{$errors->first('administradora')}}</p>
                            @endif
                        </div>

                        <!-- Planos -->
                        <div>
                            <label for="planos" class="block text-sm font-medium text-white">Planos:</label>
                            <select name="planos" id="planos" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">--Escolher o Plano--</option>
                                @foreach($planos as $pp)
                                    <option value="{{$pp->id}}" {{$pp->id == old('planos') ? 'selected' : ''}}>{{$pp->nome}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('planos'))
                                <p class="text-red-500 text-xs mt-1">{{$errors->first('planos')}}</p>
                            @endif
                        </div>

                        <!-- Cidade -->
                        <div>
                            <label for="tabela_origem" class="block text-sm font-medium text-white">Cidade:</label>
                            <select name="tabela_origem" id="tabela_origem" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">--Escolher a Cidade--</option>
                                @foreach($tabela_origem as $cc)
                                    <option value="{{$cc->id}}" {{$cc->id == old('tabela_origem') ? 'selected' : ''}}>{{$cc->nome}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('tabela_origem'))
                                <p class="text-red-500 text-xs mt-1">{{$errors->first('tabela_origem')}}</p>
                            @endif
                        </div>

                        <!-- Coparticipação -->
                        <div>
                            <label for="coparticipacao" class="block text-sm font-medium text-white">Coparticipação:</label>
                            <select name="coparticipacao" id="coparticipacao" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">--Escolher Coparticipacao--</option>
                                <option value="sim" {{old('coparticipacao') == "sim" ? 'selected' : ''}}>Com Coparticipação</option>
                                <option value="nao" {{old('coparticipacao') == "nao" ? 'selected' : ''}}>Coparticipação Parcial</option>
                            </select>
                            @if($errors->has('coparticipacao'))
                                <p class="text-red-500 text-xs mt-1">{{$errors->first('coparticipacao')}}</p>
                            @endif
                        </div>

                        <!-- Odonto -->
                        <div>
                            <label for="odonto" class="block text-sm font-medium text-white">Odonto:</label>
                            <select name="odonto" id="odonto" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">--Escolher Odonto--</option>
                                <option value="sim" {{old('odonto') == "sim" ? 'selected' : ''}}>Com Odonto</option>
                                <option value="nao" {{old('odonto') == "nao" ? 'selected' : ''}}>Sem Odonto</option>
                            </select>
                            @if($errors->has('odonto'))
                                <p class="text-red-500 text-xs mt-1">{{$errors->first('odonto')}}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Valores Section -->
                    <h4 class="text-center py-2 border-b border-gray-300 font-bold text-white">Valores</h4>
                    <div class="grid grid-cols-3 gap-4 mt-4">
                        <!-- Apartamento -->
                        <div class="border-r border-gray-300 pr-4">
                            <h6 class="font-semibold underline text-white">Apartamento</h6>
                            @foreach($faixas as $k => $f)
                                <div class="flex items-center mt-2">
                                    <input type="text" disabled class="border-none bg-transparent text-white" value="{{$f->nome}}">
                                    <input type="hidden" name="faixa_etaria_id_apartamento[]" value="{{$f->id}}">
                                    <input type="text" name="valor_apartamento[]" class="ml-2 border border-gray-300 rounded px-2 py-1 valor" placeholder="valor" value="{{ old('valor_apartamento')[$k] ?? '' }}" disabled>
                                    @if($errors->any('valor_apartamento'.$k))
                                        <p class="text-red-500 text-xs mt-1">O valor da faixa etaria {{ $f->nome }} é obrigatório</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="border-r border-gray-300 pr-4">
                            <h6 class="font-semibold underline text-white">Enfermaria</h6>
                            @foreach($faixas as $k => $f)
                                <div class="flex items-center mt-2">
                                    <input type="text" disabled class="border-none bg-transparent text-white" value="{{$f->nome}}">
                                    <input type="hidden" name="faixa_etaria_id_enfermaria[]" value="{{$f->id}}">
                                    <input type="text" name="valor_enfermaria[]" class="ml-2 border border-gray-300 rounded px-2 py-1 valor" placeholder="valor" value="{{ old('valor_apartamento')[$k] ?? '' }}" disabled>
                                    @if($errors->any('valor_apartamento'.$k))
                                        <p class="text-red-500 text-xs mt-1">O valor da faixa etaria {{ $f->nome }} é obrigatório</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="col">
                            <h6 class="font-semibold underline text-white">Ambulatorial</h6>
                            @foreach($faixas as $k => $f)
                                <div class="flex items-center mt-2">
                                    <input type="text" disabled class="border-none bg-transparent text-white" value="{{$f->nome}}">
                                    <input type="hidden" name="faixa_etaria_id_ambulatorial[]" value="{{$f->id}}">
                                    <input type="text" name="valor_ambulatorial[]" class="ml-2 border border-gray-300 rounded px-2 py-1 valor" placeholder="valor" value="{{ old('valor_apartamento')[$k] ?? '' }}" disabled>
                                    @if($errors->any('valor_apartamento'.$k))
                                        <p class="text-red-500 text-xs mt-1">O valor da faixa etaria {{ $f->nome }} é obrigatório</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>


                    <div id="container_btn_cadastrar" class="w-full">

                    </div>



                </form>
            </div>
        </div>

        <div id="aba_coparticipacao" class="ocultar">
            <div class="flex justify-between bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] p-7 mt-2">

                <select name="plano_coparticipacao" id="plano_coparticipacao" class="w-[49%] bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block p-2.5">
                    <option value="">Escolher o plano</option>
                    @foreach($planos as $pp)
                        <option value="{{$pp->id}}">{{$pp->nome}}</option>
                    @endforeach
                </select>

                <select name="cidade_coparticipacao" id="cidade_coparticipacao" class="w-[49%] bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block p-2.5">
                    <option value="">Escolher a cidade</option>
                    @foreach($tabela_origem as $cci)
                        <option value="{{$cci->id}}">{{$cci->nome}}</option>
                    @endforeach
                </select>

            </div>


            <!--PDF Excecção-->
            <div id="observacoes_excecao" class="ocultar bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">

                <div class="flex">

                    <div class="flex flex-col w-full">
                        <label>
                            <span class="text-white">Linha 01</span>
                            <input type="text" name="linha01_excecao" id="linha01_excecao" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </label>

                        <label>
                            <span class="text-white">Linha 02</span>
                            <input type="text" name="linha02_excecao" id="linha02_excecao" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </label>

                        <label>
                            <span class="text-white">Linha 03</span>
                            <input type="text" name="linha03_excecao" id="linha03_excecao" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </label>

                    </div>

                </div>


                <div class="flex flex-wrap">
                    <h3 class="w-full text-white text-center">Coparticipação</h3>
                    <div class="relative overflow-x-auto w-full">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="rounded-s-lg">

                                </th>
                                <th scope="col" class="">
                                    Total
                                </th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr class="bg-white dark:bg-gray-800">
                                <th scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Consultas Eletivas
                                </th>
                                <td class="">
                                    <input type="text" name="consultas_eletiva_total_excecao" id="consultas_eletiva_total_excecao" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>

                            </tr>
                            <tr class="bg-white dark:bg-gray-800">
                                <th scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Pronto Atendimento
                                </th>
                                <td class="">
                                    <input type="text" name="pronto_atendimento_excecao_total" id="pronto_atendimento_excecao_total" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>

                            </tr>

                            <tr class="bg-white dark:bg-gray-800">
                                <th scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Faixa 1
                                </th>
                                <td class="">
                                    <input type="text" name="faixa_01" id="faixa_01" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>



                            </tr>

                            <tr class="bg-white dark:bg-gray-800">
                                <th scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Faixa 2
                                </th>
                                <td class="">
                                    <input type="text" name="faixa_2" id="faixa_2" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>

                            </tr>

                            <tr class="bg-white dark:bg-gray-800">
                                <th scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Faixa 3
                                </th>
                                <td class="">
                                    <input type="text" name="faixa_3" id="faixa_3" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>

                            </tr>

                            <tr class="bg-white dark:bg-gray-800">
                                <th scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Faixa 4
                                </th>
                                <td class="">
                                    <input type="text" name="faixa_4" id="faixa_4" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>

                            </tr>





                            </tbody>

                        </table>
                    </div>

                    <button class="salvar_coparticipacao_excecao text-white w-full bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Salvar</button>


                </div>
            </div>
            <!--PDF Execeção-->









            <div id="observacoes" class="ocultar bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">

                <div class="flex">

                    <div class="flex flex-col w-full">
                        <label>
                            <span class="text-white">Linha 01</span>
                            <input type="text" name="linha01" id="linha01" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </label>

                        <label>
                            <span class="text-white">Linha 02</span>
                            <input type="text" name="linha02" id="linha02" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </label>

                        <label>
                            <span class="text-white">Linha 03</span>
                            <input type="text" name="linha03" id="linha03" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </label>

                    </div>

                </div>


                <div class="flex flex-wrap">
                    <h3 class="w-full text-white text-center">Coparticipação</h3>


                    <div class="relative overflow-x-auto w-full">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="rounded-s-lg">

                                </th>
                                <th scope="col" class="">
                                    Total
                                </th>
                                <th scope="col" class="rounded-e-lg">
                                    Parcial
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="bg-white dark:bg-gray-800">
                                <th scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Consultas Eletivas
                                </th>
                                <td class="">
                                    <input type="text" name="consultas_eletiva_total" id="consultas_eletiva_total" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>
                                <td class="">
                                    <input type="text" name="consultas_eletiva_parcial" id="consultas_eletiva_parcial" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>
                            </tr>
                            <tr class="bg-white dark:bg-gray-800">
                                <th scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Consultas De Urgência
                                </th>
                                <td class="">
                                    <input type="text" name="consultas_urgencia_total" id="consultas_urgencia_total" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>
                                <td class="">
                                    <input type="text" name="consultas_urgencia_parcial" id="consultas_urgencia_parcial" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>
                            </tr>

                            <tr class="bg-white dark:bg-gray-800">
                                <th scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Exames Simples
                                </th>
                                <td class="">
                                    <input type="text" name="exames_simples_total" id="exames_simples_total" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>
                                <td class="">
                                    <input type="text" name="exames_simples_parcial" id="exames_simples_parcial" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>
                            </tr>

                            <tr class="bg-white dark:bg-gray-800">
                                <th scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Exames Complexos
                                </th>
                                <td class="">
                                    <input type="text" name="exames_complexos_total" id="exames_complexos_total" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>
                                <td class="">
                                    <input type="text" name="exames_complexos_parcial" id="exames_complexos_parcial" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>
                            </tr>

                            <tr class="bg-white dark:bg-gray-800">
                                <th scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Terapias Especiais
                                </th>
                                <td class="">
                                    <input type="text" name="terapias_especiais_total" id="terapias_especiais_total" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>
                                <td class="">
                                    <input type="text" name="terapias_especiais_parcial" id="terapias_especiais_parcial" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>
                            </tr>

                            <tr class="bg-white dark:bg-gray-800">
                                <th scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Demais Terapias
                                </th>
                                <td class="">
                                    <input type="text" name="demais_terapias_total" id="demais_terapias_total" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>
                                <td class="">
                                    <input type="text" name="demais_terapias_parcial" id="demais_terapias_parcial" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>
                            </tr>

                            <tr class="bg-white dark:bg-gray-800">
                                <th scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Internações
                                </th>
                                <td class="">
                                    <input type="text" name="internacoes_total" id="internacoes_total" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>
                                <td class="">
                                    <input type="text" name="internacoes_parcial" id="internacoes_parcial" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>
                            </tr>

                            <tr class="bg-white dark:bg-gray-800">
                                <th scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Cirurgia
                                </th>
                                <td class="">
                                    <input type="text" name="cirurgia_total" id="cirurgia_total" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>
                                <td class="">
                                    <input type="text" name="cirurgia_parcial" id="cirurgia_parcial" class="ml-2 border border-gray-600 rounded px-3 py-3">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-wrap" style="align-items:flex-start;">

                        <h2 class="w-full flex text-center p-2 justify-center text-white my-6">Carências De Saúde:</h2>
                        <div class="flex w-[55%] flex-wrap">
                            <div class="w-full">
                                <input type="text" class="rounded-lg w-[10%]">
                                <span class="text-sm text-white">Urgência, Emergência E Acidentes Pessoais</span>
                            </div>

                            <div class="w-full my-2">
                                <input type="text" class="rounded-lg w-[10%]">
                                <span class="text-sm text-white">Consultas Médicas, Exames Médicos Simples</span>

                            </div>

                            <div class="w-full">
                                <input type="text" class="rounded-lg w-[10%]">
                                <span class="text-sm text-white">Exames Cardiologicos, Exames De Imagem, Oftalmológicos, Otorrino Simples, Raio X, Ultrassonografia</span>

                            </div>

                            <div class="w-full my-2">
                                <input type="text" class="rounded-lg w-[10%]">
                                <span class="text-sm text-white">
                                    Cirurgias, Internaçõesz Exames De Alto Custo, Tratamento Psicológico, Terapia Ocupacional, Fisioterapia
                                </span>

                            </div>
                        </div>


                       <div class="flex w-[45%] flex-wrap">

                           <div class="w-full mb-2">
                               <input type="text" class="rounded-lg w-[10%]">
                               <span class="text-sm text-white">Parto</span>
                           </div>

                           <div class="w-full">
                               <input type="text" class="rounded-lg w-[10%]">
                               <span class="text-sm text-white">Doenças E Lesões Pré-Existentes</span>
                           </div>
                       </div>
                    </div>
                    <button class="salvar_coparticipacao text-white w-full mt-4 bg-yellow-500 font-medium rounded-lg text-lg py-2.5 text-center">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script src="{{asset('js/jquery.mask.min.js')}}"></script>
        <script>
            $(document).ready(function(){

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                function checkSelects() {
                    const planoSelecionado = $('#plano_coparticipacao').val();
                    const cidadeSelecionada = $('#cidade_coparticipacao').val();

                    // Se ambos os campos tiverem um valor, remove a classe 'ocultar' do 'observacoes'
                    if (planoSelecionado && cidadeSelecionada) {

                        if(cidadeSelecionada != 3 && cidadeSelecionada != 4 && cidadeSelecionada != 5 && cidadeSelecionada != 6 && cidadeSelecionada != 7) {
                            $('#observacoes').removeClass('ocultar');
                            $('#observacoes_excecao').addClass('ocultar');
                        } else {
                            $('#observacoes_excecao').removeClass('ocultar');
                            $('#observacoes').addClass('ocultar');
                        }

                        $.ajax({
                           url:"{{route('coparticipacao.ja.existe')}}",
                           method:"POST",
                           data: {
                               planoSelecionado,
                               cidadeSelecionada
                           },
                           success:function(res) {
                               if(res != "nada" && cidadeSelecionada != 3 && cidadeSelecionada != 4 && cidadeSelecionada != 5 && cidadeSelecionada != 6 && cidadeSelecionada != 7) {

                                   $("#linha01").val(res.linha01);
                                   $("#linha02").val(res.linha02);
                                   $("#linha03").val(res.linha03);

                                   $("#consultas_eletiva_total").val(res.consultas_eletivas_total);
                                   $("#consultas_eletiva_parcial").val(res.consultas_eletivas_parcial);

                                   $("#consultas_urgencia_total").val(res.consultas_de_urgencia_total);
                                   $("#consultas_urgencia_parcial").val(res.consultas_de_urgencia_parcial);

                                   $("#exames_simples_total").val(res.exames_simples_total);
                                   $("#exames_simples_parcial").val(res.exames_simples_parcial);

                                   $("#exames_complexos_total").val(res.exames_complexos_total);
                                   $("#exames_complexos_parcial").val(res.exames_complexos_parcial);

                                   $("#terapias_especiais_total").val(res.terapias_especiais_total);
                                   $("#terapias_especiais_parcial").val(res.terapias_especiais_parcial);

                                   $("#demais_terapias_total").val(res.demais_terapias_total);
                                   $("#demais_terapias_parcial").val(res.demais_terapias_parcial);
                                   $("#internacoes_total").val(res.internacoes_total);
                                   $("#internacoes_parcial").val(res.internacoes_parcial);
                                   $("#cirurgia_total").val(res.cirurgia_total);
                                   $("#cirurgia_parcial").val(res.cirurgia_parcial);
                                } else if(res != "nada" && (cidadeSelecionada != 3 || cidadeSelecionada != 4 || cidadeSelecionada != 5 || cidadeSelecionada != 6 || cidadeSelecionada != 7)) {

                                   $("#linha01_excecao").val(res.linha01);
                                   $("#linha02_excecao").val(res.linha02);
                                   $("#linha03_excecao").val(res.linha03);

                                   $("#consultas_eletiva_total_excecao").val(res.consultas_eletivas_total);
                                   $("#consultas_eletiva_parcial_excecao").val(res.consultas_eletivas_parcial);

                                   $("#pronto_atendimento_excecao_total").val(res.pronto_atendimento);
                                   $("#pronto_atendimento_excecao_parcial").val(0);

                                   $("#faixa_01").val(res.faixa_1);

                                   $("#faixa_2").val(res.faixa_2);
                                   $("#faixa_02_parcial").val(0);

                                   $("#faixa_3").val(res.faixa_3);
                                   $("#faixa_03_parcial").val(0);

                                   $("#faixa_4").val(res.faixa_4);
                                   $("#faixa_04_parcial").val(0);

                               }
                           }
                        });




                    } else {
                        $('#observacoes').addClass('ocultar');
                    }
                }

                $('#plano_coparticipacao, #cidade_coparticipacao').on('change', checkSelects);


                $(".salvar_coparticipacao_excecao").on('click',function(){

                    let plano_coparticipacao = $("#plano_coparticipacao").val();
                    let cidade_coparticipacao = $("#cidade_coparticipacao").val();
                    let linha01 = $("#linha01_excecao").val();
                    let linha02 = $("#linha02_excecao").val();
                    let linha03 = $("#linha03_excecao").val();
                    let consultas_eletiva_total_excecao = $("#consultas_eletiva_total_excecao").val();
                    let pronto_atendimento_excecao_total = $("#pronto_atendimento_excecao_total").val();
                    let faixa_01 = $("#faixa_01").val();
                    let faixa_02 = $("#faixa_2").val();
                    let faixa_03 = $("#faixa_3").val();
                    let faixa_04 = $("#faixa_4").val();


                    $.ajax({
                        url:"{{route('cadastrar.excecao.coparticipacao.tabela')}}",
                        method:"POST",
                        data: {
                            plano_coparticipacao,
                            cidade_coparticipacao,
                            linha01,
                            linha02,
                            linha03,
                            consultas_eletiva_total_excecao,
                            pronto_atendimento_excecao_total,
                            faixa_01,
                            faixa_02,
                            faixa_03,
                            faixa_04
                        },
                        success:function(res) {
                            console.log(res);
                        }
                    });













                });






                $(".salvar_coparticipacao").on('click',function(){

                    let plano_coparticipacao = $("#plano_coparticipacao").val();
                    let cidade_coparticipacao = $("#cidade_coparticipacao").val();

                    let linha01 = $("#linha01").val();
                    let linha02 = $("#linha02").val();
                    let linha03 = $("#linha03").val();

                    let consultas_eletiva_total = $("#consultas_eletiva_total").val();
                    let consultas_eletiva_parcial = $("#consultas_eletiva_parcial").val();

                    let consultas_urgencia_total = $("#consultas_urgencia_total").val();
                    let consultas_urgencia_parcial = $("#consultas_urgencia_parcial").val();

                    let exames_simples_total = $("#exames_simples_total").val();
                    let exames_simples_parcial = $("#exames_simples_parcial").val();

                    let exames_complexos_total = $("#exames_complexos_total").val();
                    let exames_complexos_parcial = $("#exames_complexos_parcial").val();

                    let terapias_especiais_total = $("#terapias_especiais_total").val();
                    let terapias_especiais_parcial = $("#terapias_especiais_parcial").val();

                    let demais_terapias_total = $("#demais_terapias_total").val();
                    let demais_terapias_parcial = $("#demais_terapias_parcial").val();

                    let internacoes_total = $("#internacoes_total").val();
                    let internacoes_parcial = $("#internacoes_parcial").val();

                    let cirurgia_total = $("#cirurgia_total").val();
                    let cirurgia_parcial = $("#cirurgia_parcial").val();

                    $.ajax({
                       url:"{{route('cadastrar.coparticipacao.tabela')}}",
                       method:"POST",
                       data: {
                           plano_coparticipacao,
                           cidade_coparticipacao,
                           linha01,
                           linha02,
                           linha03,
                           consultas_eletiva_total,
                           consultas_eletiva_parcial,
                           consultas_urgencia_total,
                           consultas_urgencia_parcial,

                           exames_simples_total,
                           exames_simples_parcial,
                           exames_complexos_total,
                           exames_complexos_parcial,

                           terapias_especiais_total,
                           terapias_especiais_parcial,

                           demais_terapias_total,
                           demais_terapias_parcial,

                           internacoes_total,
                           internacoes_parcial,

                           cirurgia_total,
                           cirurgia_parcial

                       },
                        success:function(res) {
                           console.log(res);
                        }
                    });




                });







                $('#consulta_eletivas').mask("#.##0,00", {reverse: true});
                $('#consulta_urgencia').mask("#.##0,00", {reverse: true});
                $('#exames_simples').mask("#.##0,00", {reverse: true});
                $('#exames_complexos').mask("#.##0,00", {reverse: true});
                $('#terapias').mask("#.##0,00", {reverse: true});


                $('input[name*="valor_apartamento"]').mask("#.##0,00", {reverse: true});
                $('input[name*="valor_enfermaria"]').mask("#.##0,00", {reverse: true});
                $('input[name*="valor_ambulatorial"]').mask("#.##0,00", {reverse: true});

                // $('#plano_coparticipacao, #cidade_coparticipacao').change(function () {
                //     //Verifica se ambos os selects têm valores selecionados
                //     let planoSelecionado = $('#plano_coparticipacao').val();
                //     let tabelaOrigemSelecionada = $('#cidade_coparticipacao').val();
                //     // Se ambos os selects tiverem valores selecionados
                //     if (planoSelecionado !== '' && tabelaOrigemSelecionada !== '') {
                //
                //         $("#container_form_coparticipacao").slideDown('slow').removeClass("ocultar");
                //         $("input[name='plano_id']").val(planoSelecionado);
                //         $("input[name='tabela_origens_id']").val(tabelaOrigemSelecionada);
                //         // Coloque aqui a lógica que você deseja executar quando ambos os selects estiverem preenchidos.
                //     } else {
                //         $("#container_form_coparticipacao").slideUp('slow').addClass("ocultar");
                //     }
                // });

                $(".list_abas li").on('click',function(){
                    $('.list_abas li')
                        .removeClass('ativo')
                        .addClass('menu-inativo');

                    $(this)
                        .addClass("ativo");
                    let id = $(this).attr('data-id');


                    if(id == "aba_coparticipacao") {
                        $("#aba_coparticipacao").removeClass('ocultar');
                        $("#aba_tabela").addClass('ocultar');
                    }

                    if(id == "aba_tabela") {
                        $("#aba_tabela").removeClass('ocultar');
                        $("#aba_coparticipacao").addClass('ocultar');
                    }

                });

                function verificarCampos() {
                    $('select').each(function() {
                        if ($('select[name="administradora"]').val() == '' ||
                            $('select[name="planos"]').val() == '' ||
                            $('select[name="tabela_origem"]').val() == '' ||
                            $('select[name="coparticipacao"]').val() == '' ||
                            $('select[name="odonto"]').val() == '') {
                            return false;
                        } else {
                            $('input[name="valor_apartamento[]"]').removeAttr('disabled');
                            $('input[name="valor_enfermaria[]"]').removeAttr('disabled');
                            $('input[name="valor_ambulatorial[]"]').removeAttr('disabled');

                            let plano = $("#planos").val();
                            let cidade = $("#tabela_origem").val();
                            //$('.link_coparticipacao').attr('href',`/admin/tabela/coparticipacao/${plano}/${cidade}`);

                        }
                    });
                }
                verificarCampos();


                $("#administradora").on('change',function(){
                    let administradora = $(this).val();
                    $.ajax({
                        url:"{{route('planos.administradora.select')}}",
                        method:"POST",
                        data:"administradora="+administradora,
                        success:function(res) {
                            $('#planos').empty();
                            $('#planos').append('<option value="">--Escolher o Plano--</option>');
                            // Adicionar as opções do objeto
                            $.each(res, function (index, value) {
                                $('#planos').append('<option value="' + value.id + '">' + value.nome + '</option>');
                            });
                        }
                    });
                });


                var valores = [];
                $('select').change(function() {
                    // Verificar se todos os selects têm uma opção selecionada
                    let todosPreenchidos = true;



                    if ($('select[name="administradora"]').val() == '' ||
                        $('select[name="planos"]').val() == '' ||
                        $('select[name="tabela_origem"]').val() == '' ||
                        $('select[name="coparticipacao"]').val() == '' ||
                        $('select[name="odonto"]').val() == '')
                    {
                        todosPreenchidos = false;
                        return false;
                    } else {

                        var valores = {
                            "administradora" : $('select[name="administradora"]').val(),
                            "planos" : $('select[name="planos"]').val(),
                            "tabela_origem" : $('select[name="tabela_origem"]').val(),
                            "coparticipacao" : $('select[name="coparticipacao"]').val(),
                            "odonto" : $('select[name="odonto"]').val()
                        };
                        //valores.push($(this).val());
                        $(".alert-danger").remove();
                    }


                    if (todosPreenchidos) {

                        $('input[name*="valor_apartamento"]').prop('disabled',false);
                        $('input[name*="valor_enfermaria"]').prop('disabled',false);
                        $('input[name*="valor_ambulatorial"]').prop('disabled',false);
                        $('#overlay').removeClass("ocultar");

                        let plano = $("#planos").val();
                        let cidade = $("#tabela_origem").val();
                        $('.valor').removeAttr('disabled');
                        //$('.link_coparticipacao').attr('href',`/admin/tabela/coparticipacao/${plano}/${cidade}`);
                        //$("#editar_coparticipacao").removeClass('dsnone').addClass('d-flex');
                        $("#accordionExample").removeClass('dsnone');
                        $.ajax({
                            url:"{{route('verificar.valores.tabela')}}",
                            method:"POST",
                            data: {
                                "administradora" : $('select[name="administradora"]').val(),
                                "planos" : $('select[name="planos"]').val(),
                                "tabela_origem" : $('select[name="tabela_origem"]').val(),
                                "coparticipacao" : $('select[name="coparticipacao"]').val(),
                                "odonto" : $('select[name="odonto"]').val(),

                            },
                            success:function(res) {
                                $('#overlay').addClass('ocultar');
                                if(res != "empty") {

                                    $('input[name="valor_apartamento[]"]').each(function(index) {
                                        $(this).addClass('valor');
                                        if (res[index] && res[index].acomodacao_id == 1) {
                                            $(this).val(res[index].valor_formatado).attr('data-id',res[index].id);
                                        }
                                    });
                                    $('input[name="valor_enfermaria[]"]').each(function(index) {
                                        $(this).addClass('valor');
                                        if (res[index+10] && res[index+10].acomodacao_id == 2) {
                                            $(this).val(res[index+10].valor_formatado).attr('data-id',res[index+10].id);
                                        }
                                    });
                                    $('input[name="valor_ambulatorial[]"]').each(function(index) {
                                        $(this).addClass('valor');
                                        if (res[index+20] && res[index+20].acomodacao_id == 3) {
                                            $(this).val(res[index+20].valor_formatado).attr('data-id',res[index+20].id)
                                        }
                                    });
                                    $("#container_btn_cadastrar").slideUp('slow').html('');
                                } else {
                                    $('input[name="valor_apartamento[]"]')
                                        .val('')
                                        .removeClass('valor');

                                    $('input[name="valor_enfermaria[]"]')
                                        .val('')
                                        .removeClass('valor');

                                    $('input[name="valor_ambulatorial[]"]')
                                        .val('')
                                        .removeClass('valor');

                                    $("#container_btn_cadastrar")
                                        .html(`<button type="button" class="btn_cadastrar text-white w-full bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Cadastrar</button>`)
                                        .hide()
                                        .slideDown('slow');

                                    $("#container_alert_cadastrar")
                                        .html(`<div class="bg-blue-100 border border-blue-300 text-blue-800 text-2xl px-4 py-3 rounded">
                                            <h4 class="font-semibold">Essa tabela não existe, para inserir os dados clicar no botão cadastrar abaixo, após preencher todos os campos.</h4>
                                        </div>`)
                                        .hide()
                                        .slideDown('slow');
                                }
                            }
                        });





                    } else {

                        $("#editar_coparticipacao").removeClass('d-flex').addClass('dsnone');
                        $('input[name="valor_apartamento[]"]').val('');
                        $('input[name="valor_enfermaria[]"]').val('');
                        $('input[name="valor_ambulatorial[]"]').val('');
                    }
                });

                $("body").on('click','.btn_cadastrar',function(){

                    let load = $(".ajax_load");
                    let camposApartamentoPreenchidos  = verificarCamposPreenchidos("valor_apartamento[]");
                    let camposEnfermariaPreenchidos   = verificarCamposPreenchidos("valor_enfermaria[]");
                    let camposAmbulatorialPreenchidos = verificarCamposPreenchidos("valor_ambulatorial[]");

                    if (camposApartamentoPreenchidos && camposEnfermariaPreenchidos && camposAmbulatorialPreenchidos) {

                        let valoresApartamento = obterValoresDosCampos("valor_apartamento[]");
                        let valoresEnfermaria = obterValoresDosCampos("valor_enfermaria[]");
                        let valoresAmbulatorial = obterValoresDosCampos("valor_ambulatorial[]");

                        // Preparar os dados para enviar ao backend (você pode ajustar de acordo com suas necessidades)
                        let dados = {
                            valoresApartamento: valoresApartamento,
                            valoresEnfermaria: valoresEnfermaria,
                            valoresAmbulatorial: valoresAmbulatorial,
                            administradora : $('select[name="administradora"]').val(),
                            planos : $('select[name="planos"]').val(),
                            tabela_origem : $('select[name="tabela_origem"]').val(),
                            coparticipacao : $('select[name="coparticipacao"]').val(),
                            odonto : $('select[name="odonto"]').val(),
                        };

                        $.ajax({
                            url:"{{route('cadastrar.valores.tabela')}}",
                            method:"POST",
                            data:dados,
                            beforeSend: function () {
                                load.fadeIn(100).css("display", "flex");
                            },
                            success:function(res) {
                                if(res == "sucesso") {
                                    load.fadeOut(300);
                                    toastr["success"]("Tabela cadastrada com sucesso")
                                    toastr.options = {
                                        "closeButton": false,
                                        "debug": false,
                                        "newestOnTop": false,
                                        "progressBar": false,
                                        "positionClass": "toast-top-right",
                                        "preventDuplicates": false,
                                        "onclick": null,
                                        "showDuration": "300",
                                        "hideDuration": "1000",
                                        "timeOut": "5000",
                                        "extendedTimeOut": "1000",
                                        "showEasing": "swing",
                                        "hideEasing": "linear",
                                        "showMethod": "fadeIn",
                                        "hideMethod": "fadeOut"
                                    }
                                    $("#container_btn_cadastrar").html('');
                                    $("#container_alert_cadastrar").html('');





                                } else {
                                    alert("Erro ao cadastrar a tabela")

                                }
                            }
                        });


                    } else {
                        toastr["error"]("Todos os campos são obrigatório")
                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        return false; // Impede o envio do formulário se algum campo estiver em branco
                    }
                    return false; // Isso impede o envio do formulário para evitar que a página seja recarregada
                });


                function verificarCamposPreenchidos(tipoCampo) {
                    var todosPreenchidos = true;
                    $("input[name='" + tipoCampo + "']").each(function () {
                        if ($(this).val() === "") {
                            todosPreenchidos = false;
                            return false; // Encerra o loop se encontrar um campo não preenchido
                        }
                    });
                    return todosPreenchidos;
                }

                // Função para obter os valores dos campos de um determinado tipo
                function obterValoresDosCampos(tipoCampo) {
                    var valores = [];
                    $("input[name='" + tipoCampo + "']").each(function () {
                        valores.push($(this).val());
                    });
                    return valores;
                }





                $('body').on('change','.valor',function(){
                    let valor = $(this).val();
                    let id = $(this).attr('data-id');
                    $.ajax({
                        url:"{{route('corretora.mudar.valor.tabela')}}",
                        method:"POST",
                        data:"id="+id+"&valor="+valor,
                        success:function(res) {
                            console.log(res);
                        }
                    });
                });









            });

        </script>
   @endsection

    @section('css')
        <style>
            .dsnone {display:none;}
            .ocultar {display: none !important;}
            .ajax_load {display:none;position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:1000;}
            .ajax_load_box{margin:auto;text-align:center;color:#fff;font-weight:var(700);text-shadow:1px 1px 1px rgba(0,0,0,.5)}
            .ajax_load_box_circle{border:16px solid #e3e3e3;border-top:16px solid #61DDBC;border-radius:50%;margin:auto;width:80px;height:80px;-webkit-animation:spin 1.2s linear infinite;-o-animation:spin 1.2s linear infinite;animation:spin 1.2s linear infinite}
            @-webkit-keyframes spin{0%{-webkit-transform:rotate(0deg)}100%{-webkit-transform:rotate(360deg)}}
            @keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}
            .list_abas {list-style: none;display: flex;border-bottom: 1px solid white;margin: 0;padding: 0;}
            .list_abas li {color: #fff;width: 150px;padding: 8px 5px;text-align:center;border-radius: 5px 5px 0 0;background-color:#123449;}
            .list_abas li:hover {cursor: pointer;}
            .list_abas li:nth-of-type(2) {margin: 0 1%;}
            .ativo {background-color:#FFF !important;color: #000 !important;}
        </style>
    @stop






</x-app-layout>
