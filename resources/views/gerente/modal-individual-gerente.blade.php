<div class="flex flex-wrap">

    <div class="flex justify-end w-full">

        <button id="closeModalIndividual" class="text-white bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] hover:bg-red-600 p-1 rounded-full">
            <svg class="w-4 h-4 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
            </svg>

        </button>
    </div>


    <!-- Bloco da Esquerda (Formulário 60%) -->
    <div class="flex basis-[48%] pr-1 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] py-1 shadow-lg rounded-lg border mr-1">
        <form>
            <!-- 1ª Linha (Corretor) -->

            <div class="grid grid-cols-4 gap-4 mb-2">

                <input type="hidden" id="id_cliente" value="">

                <div>
                    <label for="administradora" class="block text-white text-sm flex justify-between">
                        <span>Administradora</span>


                    </label>
                    <input type="text" id="administradora" value="Hapvida" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md editar_campo_individual" readonly>
                </div>
                <div class="col-span-2">
                    <label for="corretor" class="block text-white text-sm flex justify-between">
                        <span>Corretor</span>


                    </label>
                    <input type="text" value="{{$contrato->cliente->user->name}}" id="corretor" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md" readonly>
                </div>
                <div>
                    <label for="status" class="block text-white text-sm">Status</label>
                    <input type="text" value="" id="status" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md" readonly>
                </div>
            </div>

            <!-- 2ª Linha (Cliente, CPF, Data Nascimento) -->
            <div class="grid grid-cols-4 gap-4 mb-2">
                <div class="col-span-2">
                    <label for="cliente" class="block text-white text-sm flex justify-between">
                        <span>
                            Cliente
                        </span>
                    </label>
                    <input type="text" id="cliente" value="{{$contrato->cliente->nome}}" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md editar_campo_individual" readonly>
                </div>
                <div>
                    <label for="cpf" class="block text-white text-sm flex justify-between">
                        <span>
                            CPF
                        </span>

                    </label>
                    <input type="text" id="cpf" value="{{$contrato->cliente->cpf}}" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md editar_campo_individual" readonly>
                </div>
                <div>
                    <label for="data_nascimento" class="block text-white text-sm">Data Nascimento</label>
                    <input type="date" id="data_nascimento" value="{{$contrato->cliente->data_nascimento}}" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md editar_campo_individual" readonly>
                </div>
            </div>

            <!-- 3ª Linha (Código Externo e Email) -->
            <div class="grid grid-cols-4 gap-4 mb-2">
                <div>
                    <label for="codigo_externo" class="block text-white text-sm flex justify-between">
                        <span>Código Externo</span>

                    </label>
                    <input type="text" id="codigo_externo" value="{{$contrato->cliente->codigo_externo}}" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md editar_campo_individual" readonly>
                </div>
                <div>
                    <label for="fone" class="block text-white text-sm flex justify-between">
                        <span>Celular</span>

                    </label>
                    <input type="text" id="fone" value="{{$contrato->cliente->celular}}" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md editar_campo_individual" readonly>
                </div>
                <div class="flex-1">
                    <label class="text-white flex justify-between" style="font-size:0.81em;">
                        <spa>Email</spa>

                    </label>
                    <input type="text" id="email" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md editar_campo_individual" value="" readonly>
                </div>

                <div class="flex-1">
                    <label class="text-white flex justify-between" style="font-size:0.81em;">
                        <span>Carteirinha:</span>

                    </label>
                    <input type="text" id="carteirinha" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md editar_campo_individual" value="{{$contrato->cliente->cateirinha}}" readonly>
                </div>

            </div>

            <!-- 4ª Linha (CEP, Cidade, UF, Bairro) -->
            <div class="grid grid-cols-4 gap-4 mb-2">
                <div>
                    <label for="cep" class="block text-white text-sm flex justify-between">
                        <span>CEP</span>

                    </label>
                    <input type="text" id="cep" value="" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md editar_campo_individual" readonly>
                </div>
                <div>
                    <label for="cidade" class="block text-white text-sm flex justify-between">
                        <span>Cidade</span>

                    </label>
                    <input type="text" id="cidade" value="" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md editar_campo_individual" readonly>
                </div>
                <div>
                    <label for="uf" class="block text-white text-sm flex justify-between">
                        <span>UF</span>

                    </label>
                    <input type="text" id="uf" value="" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md editar_campo_individual" readonly>
                </div>
                <div>
                    <label for="bairro" class="block text-white text-sm flex justify-between">
                        <span>Bairro</span>

                    </label>
                    <input type="text" id="bairro" value="" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md editar_campo_individual" readonly>
                </div>
            </div>

            <!-- 5ª Linha (Rua, Complemento) -->
            <div class="grid grid-cols-5 gap-4 mb-2">
                <div class="col-span-2">
                    <label for="rua" class="block text-white text-sm flex justify-between">
                        <span>Rua</span>

                    </label>
                    <input type="text" id="rua" value="" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md editar_campo_individual" readonly>
                </div>
                <div class="col-span-2">
                    <label for="complemento" class="block text-white text-sm flex justify-between">
                        <span>Complemento</span>

                    </label>
                    <input type="text" id="complemento" value="" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md editar_campo_individual" readonly>
                </div>
                <div>
                    <label for="data_contrato" class="block text-white text-sm flex justify-between">
                        <span>Data Contrato</span>

                    </label>
                    <input type="text" id="data_contrato" value="{{ \Carbon\Carbon::parse($contrato->created_at)->format('d/m/Y') }}" class="w-full bg-gray-100 text-gray-800 p-1 text-sm editar_campo_individual rounded-md" readonly>
                </div>
            </div>

            <!-- 6ª Linha (Data Contrato, Valores, Descontos) -->
            <div class="grid grid-cols-5 gap-1 mb-2">

                <div>
                    <label for="valor_contrato" class="block text-white text-sm flex justify-between">
                        <span>Valor Contrato</span>

                    </label>
                    <input type="text" id="valor_contrato" readonly value="{{number_format($contrato->valor_plano,2,",",".")}}" class="w-full bg-gray-100 text-gray-800 p-1 text-sm editar_campo_individual rounded-md">
                </div>
                <div>
                    <label for="valor_adesao" class="block text-white text-sm flex justify-between">
                        <span>Valor Adesão</span>
                    </label>
                    <input type="text" id="valor_adesao" readonly value="{{number_format($contrato->valor_adesao,2,",",".")}}" class="w-full bg-gray-100 text-gray-800 p-1 editar_campo_individual text-sm rounded-md">
                </div>
                <div class="col-span-2">
                    <label for="nome_responsavel" class="block text-white text-sm flex justify-between">
                        <span>Nome Responsavel</span>

                    </label>
                    <input type="text" id="nome_responsavel" readonly value="" class="w-full bg-gray-100 text-sm editar_campo_individual text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="cpf_responsavel" class="block text-white text-sm flex justify-between">
                        <span>CPF Responsavel</span>

                    </label>
                    <input type="text" id="cpf_responsavel" readonly value="" class="w-full bg-gray-100 text-sm editar_campo_individual text-gray-800 p-1 rounded-md">
                </div>

            </div>





        </form>
    </div>

    <!-- Bloco da Direita (40%) -->
    <div class="flex basis-[49%] flex-wrap  p-2 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-lg border">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg w-full">
            <table class="w-full text-sm text-left text-white rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-1 py-2">
                        #
                    </th>
                    <th scope="col" class="px-1 py-2">
                        Contrato
                    </th>
                    <th scope="col" class="px-1 py-2">
                        Vencimento
                    </th>
                    <th scope="col" class="px-1 py-2">
                        Valor
                    </th>
                    <th scope="col" class="px-1 py-2">
                        Baixa
                    </th>
                </tr>
                </thead>
                <tbody>
                @php
                    $total_cliente = 0;
                    $total_comissao = 0;
                @endphp
                @foreach($contrato->comissao->comissoesLancadas as $kk => $cr)
                    @php
                        if(!empty($cr->data_baixa)):
                            $total_comissao += $cr->valor;
                        else:
                            $total_cliente += $cr->valor;
                        endif;
                    @endphp
                    <tr class="border-t">
                        <td class="text-sm py-2">
                            @if($cr->parcela == 1)
                                Adesão
                            @else
                                <span class="text-center">{{$cr->parcela}} º Parcela</span>
                            @endif
                        </td>
                        <td class="text-center text-sm py-2">{{$contrato->cliente->codigo_externo}}</td>
                        <td class="text-sm py-2">{{date('d/m/Y', strtotime($cr->data))}}</td>
                        <td class="text-sm py-2">
                            @if($cr->valor_pago > 0)
                                {{number_format($cr->valor_pago, 2, ",", ".") ?? 0}}
                            @else
                                <span class="ml-2">---</span>
                            @endif
                        </td>
                        <td class="text-sm py-2">
                            @if(empty($cr->data_baixa))
                                <span class="ml-4">---</span>
                            @else
                                {{date('d/m/Y', strtotime($cr->data_baixa))}}
                            @endif
                        </td>

                    </tr>
                @endforeach






                </tbody>
            </table>

        </div>

    </div>


</div>

