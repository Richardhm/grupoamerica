<main id="aba_coletivo" class="hidden">

    <section class="flex justify-between flex-wrap content-start">
        <!--COLUNA DA ESQUERDA-->

        <div class="flex flex-col text-white basis-[16%] rounded-lg">

            @if(auth()->user()->can('listar_todos'))
                <select
                    style="background-color: rgba(253, 216, 53, 0.7); backdrop-filter: blur(10px);"
                    id="select_corretoras_coletivo"
                        class="
                                w-full mt-1 rounded-lg mb-1 text-center text-sm bg-[rgba(254,254,254,0.18)]
                                active:bg-[rgba(254,254,254,0.18)] hover:bg-gray-800 py-2 mr-1 focus:bg-gray-800 w-full text-xs
                                px-1 mb-2 text-sm font-medium rounded-lg hover:border-transparent focus:border-transparent border-transparent
                                "
                >

                    <option value="1" class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] text-black text-lg">Accert</option>
                    <option value="2" class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] text-black text-lg">Innove</option>
                    <option value="0" class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] text-black text-lg">Vivaz</option>
                </select>
            @endif

            <div class="flex mb-1 justify-between">
            <span class="bg-[rgba(254,254,254,0.18)] hover:cursor-pointer backdrop-blur-[15px] text-white text-sm py-2 text-center rounded basis-[49%]">
                <a class="text-center text-white" href="{{route('contratos.create.coletivo')}}">Cadastrar</a>
            </span>
                <span class="bg-[rgba(254,254,254,0.18)] hover:cursor-pointer backdrop-blur-[15px] text-white text-sm py-2 text-center rounded btn_upload_coletivo basis-[49%]">
                Upload
            </span>
            </div>

            <div id="content_list_coletivo_begin" class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] mb-1 p-1">


                <div class="flex w-full justify-around">
                    <div class="flex-1 flex basis-[49%] mr-1">
                        <select id="mudar_ano_table_coletivo"
                            style="background-color: rgba(253, 216, 53, 0.7); backdrop-filter: blur(10px);"
                            class="flex w-full py-2 text-lg justify-center text-white bg-[rgba(254,254,254,0.18)] focus:outline-none active:outline-none active:bg-[rgba(254,254,254,0.18)] hover:bg-gray-800 py-2 text-black focus:bg-gray-800 w-full text-xs px-1 mb-1 text-sm font-medium text-black rounded-lg hover:border-transparent focus:border-transparent border-transparent">
                            <option value="todos" class="text-center">-Anos-</option>
                            <option value="2024">2024</option>
                        </select>
                    </div>
                    <div class="flex-1 flex basis-[49%]">
                        <select id="mudar_mes_table_coletivo"
                            style="background-color: rgba(253, 216, 53, 0.7); backdrop-filter: blur(10px);"
                            class="flex w-full py-2 text-lg justify-center text-white bg-[rgba(254,254,254,0.18)] focus:outline-none active:outline-none active:bg-[rgba(254,254,254,0.18)] hover:bg-gray-800 py-2 text-black focus:bg-gray-800 w-full text-xs px-1 mb-1 text-sm font-medium text-black rounded-lg hover:border-transparent focus:border-transparent border-transparent">
                            <option value="00" class="text-center">-Mês-</option>
                            <option value="01">Janeiro</option>
                            <option value="02">Fevereiro</option>
                            <option value="03">Março</option>
                            <option value="04">Abril</option>
                            <option value="05">Maio</option>
                            <option value="06">Junho</option>
                            <option value="07">Julho</option>
                            <option value="08">Agosto</option>
                            <option value="09">Setembro</option>
                            <option value="10">Outubro</option>
                            <option value="11">Novembro</option>
                            <option value="12">Dezembro</option>
                        </select>
                    </div>

                </div>

                <select
                    style="background-color: rgba(253, 216, 53, 0.7); backdrop-filter: blur(10px);"
                    class="flex w-full py-2 text-lg focus:outline-none text-white active:outline-none hover:bg-gray-800 py-2 text-center focus:bg-gray-800 w-full text-xs px-1 me-2 mb-1 text-sm font-medium text-black rounded-lg hover:border-transparent focus:border-transparent border-transparent" id="select_usuario">
                    <option value="todos" class="text-center">---Escolher Corretor---</option>
                </select>


                <select
                    style="background-color: rgba(253, 216, 53, 0.7); backdrop-filter: blur(10px);"
                    class="flex w-full py-2 text-lg focus:outline-none active:outline-none hover:bg-gray-800 py-2 text-white focus:bg-gray-800 w-full text-xs px-1 me-2 mb-1 text-sm font-medium text-black rounded-lg hover:border-transparent focus:border-transparent border-transparent" id="select_coletivo_administradoras">
                    <option value="todos" class="text-center">---Administradora---</option>
                </select>


                <ul class="list-none m-0" id="list_coletivo_begin">
                    <li style="height:30px;line-height: 30px;" class="flex justify-between coletivo my-auto">
                        <span class="text-sm my-auto">Contratos:</span>
                        <span class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded text-right pr-1 w-[30%] bg-transparent text-sm flex basis-[50%] justify-end text-sm items-center total_por_orcamento_coletivo">
                            0
                        </span>
                    </li>
                    <li style="height:30px;line-height: 30px;" class="flex justify-between mb-1 space-y-1 coletivo">
                        <span class="flex basis-[50%] text-sm">Vidas:</span>
                        <span class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded text-right pr-1 w-[30%] bg-transparent text-sm flex basis-[50%] justify-end text-sm items-center total_por_vida_coletivo">
                            0
                        </span>
                    </li>
                    <li style="height:30px;line-height: 30px;" class="flex justify-between coletivo">
                        <span class="flex basis-[50%] text-sm">Valor:</span>
                        <span class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded text-right pr-1 w-[30%] bg-transparent text-sm flex basis-[50%] justify-end text-sm items-center total_por_page_coletivo">
                            0
                        </span>
                    </li>
                </ul>
            </div>

            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded p-2 mb-1">
                <ul id="atrasado_corretor_coletivo" >
                    <li class="flex justify-between individual" style="height:30px;line-height: 30px;">
                        <span class="text-sm">Atrasados</span>
                        <span class="text-right rounded w-[30%] text-sm text-black bg-transparent backdrop-blur-[80px] text-white pr-1  text-right flex justify-end coletivo_quantidade_atrasado">0</span>
                    </li>
                </ul>
            </div>

            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded p-2 mb-1">
                <ul id="finalizado_corretor_coletivo">
                    <li class="flex justify-between individual" style="height:30px;line-height: 30px;">
                        <span class="text-sm">Finalizado</span>
                        <span class="text-right rounded w-[30%] text-sm text-black bg-transparent backdrop-blur-[80px] text-white pr-1 quantidade_coletivo_finalizado flex justify-end">0</span>
                    </li>
                </ul>
            </div>

            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded p-2 mb-1">
                <ul class="list-none m-0 py-1" id="listar">
                    <li style="height:30px;line-height: 30px;" class="flex mb-1 justify-between flex justify-end coletivo" id="em_analise_coletivo">
                        <span class="text-sm">Em Analise</span>
                        <span class="text-sm coletivo_quantidade_em_analise bg-transparent backdrop-blur-[80px] w-[30%] text-right pr-1 rounded flex justify-end">0</span>
                    </li>
                    <li class="mb-1 coletivo flex justify-between" id="emissao_boleto_coletivo" style="height:30px;line-height: 30px;">
                        <span class="text-sm">Emissão Boleto</span>
                        <span class="text-sm coletivo_quantidade_emissao_boleto bg-transparent backdrop-blur-[80px] w-[30%] text-right pr-1 rounded flex justify-end">0</span>
                    </li>
                    <li class="mb-1 coletivo flex justify-between" id="pagamento_adesao_coletivo" style="height:30px;line-height: 30px;">
                        <span class="text-sm">Pag. Adesão</span>
                        <span class="text-sm coletivo_quantidade_pagamento_adesao bg-transparent backdrop-blur-[80px] w-[30%] text-right pr-1 rounded flex justify-end">0</span>
                    </li>
                    <li class="mb-1 coletivo flex justify-between" id="pagamento_vigencia_coletivo" style="height:30px;line-height: 30px;">
                        <span class="text-sm">Pag. Vigência</span>
                        <span class="text-sm coletivo_quantidade_pagamento_vigencia bg-transparent backdrop-blur-[80px] w-[30%] text-right pr-1 rounded flex justify-end">0</span>
                    </li>
                    <li class="mb-1 coletivo flex justify-between" id="pagamento_segunda_parcela" style="height:30px;line-height: 30px;">
                        <span class="text-sm">Pag. 2º Parcela</span>
                        <span class="text-sm coletivo_quantidade_segunda_parcela bg-transparent backdrop-blur-[80px] w-[30%] text-right pr-1 rounded flex justify-end">0</span>
                    </li>
                    <li class="mb-1 coletivo flex justify-between" id="pagamento_terceira_parcela" style="height:30px;line-height: 30px;">
                        <span class="text-sm">Pag. 3º Parcela</span>
                        <span class="text-sm coletivo_quantidade_terceira_parcela bg-transparent backdrop-blur-[80px] w-[30%] text-right pr-1 rounded flex justify-end">0</span>
                    </li>
                    <li class="mb-1 coletivo flex justify-between" id="pagamento_quarta_parcela" style="height:30px;line-height: 30px;">
                        <span class="text-sm">Pag. 4º Parcela</span>
                        <span class="text-sm coletivo_quantidade_quarta_parcela bg-transparent backdrop-blur-[80px] w-[30%] text-right pr-1 rounded flex justify-end">0</span>
                    </li>
                    <li class="mb-1 coletivo flex justify-between" id="pagamento_quinta_parcela" style="height:30px;line-height: 30px;">
                        <span class="text-sm">Pag. 5º Parcela</span>
                        <span class="text-sm coletivo_quantidade_quinta_parcela bg-transparent backdrop-blur-[80px] w-[30%] text-right pr-1 rounded flex justify-end">0</span>
                    </li>
                    <li class="mb-1 coletivo flex justify-between" id="pagamento_sexta_parcela" style="height:30px;line-height: 30px;">
                        <span class="text-sm">Pag. 6º Parcela</span>
                        <span class="text-sm coletivo_quantidade_sexta_parcela bg-transparent backdrop-blur-[80px] w-[30%] text-right pr-1 rounded flex justify-end">0</span>
                    </li>
                </ul>
            </div>

            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-lg">
                <ul class="list-none m-0 py-1" id="grupo_finalizados">
                    <li class="px-1 flex justify-between mb-1 coletivo" id="cancelado_coletivo" style="height:30px;line-height: 30px;">
                        <span class="text-sm">Cancelados</span>
                        <span class="text-sm quantidade_coletivo_cancelados bg-transparent backdrop-blur-[80px] w-[30%] text-right pr-1 rounded flex justify-end">0</span>
                    </li>
                </ul>
            </div>
        </div>
        <!--FIM COLUNA DA ESQUERDA-->

        <!--COLUNA DA CENTRAL-->
        <div class="basis-[83%]">
            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] text-white rounded-lg">
                <table id="tabela_coletivo" class="table-auto table-sm listardados w-full">
                    <thead>
                    <tr>
                        <th>Data</th>
                        <th>Cod.</th>
                        <th>Corretor</th>
                        <th>Cliente</th>
                        <th>Admin</th>
                        <th>CPF</th>
                        <th>Vidas</th>
                        <th>Valor</th>
                        <th>Vencimento</th>
                        <th>Status</th>
                        <th>Ver</th>
                        <th>Teste</th>
                        <th>Data Nasc.</th>
                        <th>Celular</th>
                        <th>Email</th>
                        <th>Cidade</th>
                        <th>UF</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!--FIM COLUNA CENTRAL-->
    </section>

</main>
