<main id="aba_empresarial" class="hidden">

    <section class="flex justify-between" style="flex-wrap: wrap;">
        <div class="flex flex-col text-white ml-1" style="flex-basis:16%;border-radius:5px;">

            @if(auth()->user()->can('listar_todos'))
                <select
                        style="background-color: rgba(253, 216, 53, 0.7); backdrop-filter: blur(10px);"
                        id="select_corretoras_empresarial"
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

            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded" style="margin:1px 0;">
                <ul style="list-style:none;margin:0;padding:5px 0;" id="cadastrar_empresarial">
                    <li style="padding:0px 3px;display:flex;text-align:center;justify-content:center;">
                        <a class="text-center w-full text-white text-lg" href="{{route('contratos.create.empresarial')}}">Cadastrar</a>
                    </li>
                </ul>
            </div>

            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] p-1" id="content_list_empresarial_begin">
                <div class="flex flex-wrap w-full mb-1">
                    <div class="flex flex-wrap justify-around mb-0 w-full">
                        <select id="mudar_ano_table_empresarial"
                                style="background-color: rgba(253, 216, 53, 0.7); backdrop-filter: blur(10px);"
                                class="

                            flex basis-[48%] py-2 text-lg justify-center
							text-white bg-[rgba(254,254,254,0.18)] focus:outline-none
							active:outline-none active:bg-[rgba(254,254,254,0.18)]
							hover:bg-gray-800 py-2 focus:bg-gray-800 w-full text-xs
							px-1 mb-2 text-sm font-medium text-black rounded-lg hover:border-transparent
							focus:border-transparent border-transparent

                            ">
                            <option value="" class="text-center">-Anos-</option>

                            <option value="2024" selected>2024</option>
                        </select>

                        <select
                            style="background-color: rgba(253, 216, 53, 0.7); backdrop-filter: blur(10px);"
                            id="mudar_mes_table_empresarial" class="

                            flex basis-[48%] py-2 text-lg justify-center
							text-white bg-[rgba(254,254,254,0.18)] focus:outline-none
							active:outline-none active:bg-[rgba(254,254,254,0.18)]
							hover:bg-gray-800 py-2 focus:bg-gray-800 w-full text-xs
							px-1 mb-2 text-sm font-medium text-black rounded-lg hover:border-transparent
							focus:border-transparent border-transparent

                            ">
                            <option value="" class="text-center">-Meses-</option>
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

                    <select style="flex-basis:99%;background-color: rgba(253, 216, 53, 0.7); backdrop-filter: blur(10px);" name="mudar_user_empresarial" id="mudar_user_empresarial" class="
                        w-full mt-1 rounded-lg mb-1 text-center text-sm bg-[rgba(254,254,254,0.18)]
                            active:bg-[rgba(254,254,254,0.18)] hover:bg-gray-800 py-2 mr-1 focus:bg-gray-800 w-full text-xs
                            px-1 mb-2 text-sm font-medium rounded-lg hover:border-transparent focus:border-transparent border-transparent
                        ">
                        <option value="todos" class="text-center" data-id="0">---Escolher Corretor---</option>

                    </select>

                    <select style="flex-basis:99%;background-color: rgba(253, 216, 53, 0.7); backdrop-filter: blur(10px);"
                            name="mudar_planos_empresarial" id="mudar_planos_empresarial"
                            class="
                                w-full mt-1 rounded-lg mb-1 text-center text-sm bg-[rgba(254,254,254,0.18)]
                                active:bg-[rgba(254,254,254,0.18)] hover:bg-gray-800 py-2 mr-1 focus:bg-gray-800 w-full text-xs
                                px-1 mb-2 text-sm font-medium rounded-lg hover:border-transparent focus:border-transparent border-transparent
                            "
                    >
                        <option value="todos" class="text-center" data-id="0">---Escolher Planos---</option>

                    </select>

                </div>

                <ul id="list_empresarial_begin" class="list-none m-0">
                    <li style="height:30px;line-height: 30px;" class="flex mb-1 justify-between empresarial">
                        <span class="flex basis-[50%] text-sm items-center my-auto">Contratos:</span>
                        <span class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded text-right w-[49%] bg-transparent text-sm pr-1 flex justify-end text-sm total_por_orcamento_empresarial">0</span>
                    </li>
                    <li style="height:30px;line-height: 30px;" class="flex mb-1 justify-between empresarial">
                        <span class="flex basis-[50%] text-sm items-center my-auto">Vidas:</span>
                        <span class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded text-right w-[49%] bg-transparent flex justify-end text-sm pr-1 total_por_vida_empresarial">0</span>
                    </li>
                    <li style="height:30px;line-height: 30px;" class="flex mb-1 justify-between empresarial">
                        <span class="flex basis-[50%] text-sm items-center my-auto">Valor:</span>
                        <span class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded text-right w-[49%] bg-transparent flex justify-end text-sm pr-1 total_por_page_empresarial">0</span>
                    </li>
                </ul>
            </div>

            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded p-2 mb-1 mt-1">
                <ul id="atrasado_corretor_empresarial">
                    <li style="height:30px;line-height: 30px;" class="flex justify-between empresarial">
                        <span class="text-sm">Atrasados</span>
                        <span class="text-right rounded w-[30%] text-sm text-black bg-transparent backdrop-blur-[80px] text-white pr-1  text-right flex justify-end empresarial_quantidade_atrasado">0</span>
                    </li>
                </ul>
            </div>

            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded p-2 mb-1">
                <ul id="finalizado_corretor_empresarial">
                    <li style="height:30px;line-height: 30px;" class="empresarial flex justify-between">
                        <span class="text-sm">Finalizado</span>
                        <span class="text-right rounded w-[30%] text-sm text-black bg-transparent backdrop-blur-[80px] text-white pr-1  text-right flex justify-end quantidade_empresarial_finalizado" style="text-align:right;">0</span>
                    </li>
                </ul>
            </div>

            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded p-2 mb-1">

                <ul class="list-none m-0 py-1" id="listar_empresarial">

                    <li style="height:30px;line-height: 30px;" class="mb-1 justify-between flex justify-end empresarial items-center py-1" id="aguardando_em_analise_empresarial">
                        <span class="text-sm">Em Análise</span>
                        <span class="text-right w-[30%] text-sm text-black bg-transparent backdrop-blur-[80px] text-white pr-1 rounded empresarial_quantidade_em_analise">0</span>
                    </li>
                    <li style="height:30px;line-height: 30px;" class="mb-1 justify-between flex justify-end empresarial" id="aguardando_pagamento_1_parcela_empresarial">
                        <span class="text-sm">Pag. 1º Parcela</span>
                        <span class="text-right w-[30%] text-sm text-black bg-transparent backdrop-blur-[80px] text-white pr-1 rounded empresarial_quantidade_1_parcela">0</span>
                    </li>
                    <li style="height:30px;line-height: 30px;" class="mb-1 justify-between flex justify-end empresarial" id="aguardando_pagamento_2_parcela_empresarial">
                        <span class="text-sm">Pag. 2º Parcela</span>
                        <span class="text-right w-[30%] text-sm text-black bg-transparent backdrop-blur-[80px] text-white pr-1 rounded empresarial_quantidade_2_parcela">0</span>
                    </li>
                    <li style="height:30px;line-height: 30px;" class="mb-1 justify-between flex justify-end empresarial" id="aguardando_pagamento_3_parcela_empresarial">
                        <span class="text-sm">Pag. 3º Parcela</span>
                        <span class="text-right w-[30%] text-sm text-black bg-transparent backdrop-blur-[80px] text-white pr-1 rounded empresarial_quantidade_3_parcela">0</span>
                    </li>
                    <li style="height:30px;line-height: 30px;" class="mb-1 justify-between flex justify-end empresarial" id="aguardando_pagamento_4_parcela_empresarial">
                        <span class="text-sm">Pag. 4º Parcela</span>
                        <span class="text-right w-[30%] text-sm text-black bg-transparent backdrop-blur-[80px] text-white pr-1 rounded empresarial_quantidade_4_parcela">0</span>
                    </li>
                    <li style="height:30px;line-height: 30px;" class="mb-1 justify-between flex justify-end empresarial" id="aguardando_pagamento_5_parcela_empresarial">
                        <span class="text-sm">Pag. 5º Parcela</span>
                        <span class="text-right w-[30%] text-sm text-black bg-transparent backdrop-blur-[80px] text-white pr-1 rounded empresarial_quantidade_5_parcela">0</span>
                    </li>
                    <li style="height:30px;line-height: 30px;" class="mb-1 justify-between flex justify-end empresarial" id="aguardando_pagamento_6_parcela_empresarial">
                        <span class="text-sm">Pag. 6º Parcela</span>
                        <span class="text-right w-[30%] text-sm text-black bg-transparent backdrop-blur-[80px] text-white pr-1 rounded empresarial_quantidade_6_parcela">0</span>
                    </li>
                </ul>
            </div>


            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded p-2 mb-1">
                <ul id="aguardando_cancelado_empresarial">
                    <li style="height:30px;line-height: 30px;" class="empresarial flex justify-between">
                        <span class="text-sm">Cancelado</span>
                        <span class="text-right rounded w-[30%] text-sm text-black bg-transparent backdrop-blur-[80px] text-white pr-1  text-right flex justify-end empresarial_quantidade_cancelado" style="text-align:right;">0</span>
                    </li>
                </ul>
            </div>
        </div>

        <!--COLUNA DA CENTRAL-->
        <div style="flex-basis:83%;">
            <div class="p-2 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded" style="color:#FFF;">
                <table id="tabela_empresarial" class="table table-sm w-100 text-left listarempresarial" style="table-layout: fixed;">

                    <thead>
                    <tr>
                        <th>Data</th>
                        <th>Cod.</th>
                        <th>Corretor</th>
                        <th>Cliente</th>
                        <th>CNPJ</th>
                        <th>Vidas</th>
                        <th>Valor</th>
                        <th>Plano</th>
                        <th>Vencimento</th>
                        <th>Status</th>
                        <th>Ver</th>
                        <th>Resposta</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <!--FIM COLUNA DA CENTRAL-->
    </section>
</main>
