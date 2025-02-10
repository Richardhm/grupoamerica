<div class="flex flex-wrap">

    <div class="flex justify-end w-full">

        <button id="closeModalColetivo" class="text-white bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] hover:bg-red-600 p-1 rounded-full">
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
                <div>
                    <label for="administradora" class="block text-white text-sm flex justify-between">
                        <span>Administradora</span>
                    </label>
                    <input type="text" name="administradora" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md" id="administradora" value="{{$contrato->administradora->nome}}">
                </div>
                <div class="col-span-2">
                    <label for="corretor" class="block text-white text-sm flex justify-between">
                        <span>Corretor</span>
                    </label>
                    <input type="text" name="administradora" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md" id="administradora" value="{{$contrato->cliente->user->name}}">
                </div>
                <div>
                    <label for="status" class="block text-white text-sm flex justify-between">
                        <span>Status</span>
                    </label>
                    <input type="text" value="" id="status" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md" readonly>
                </div>
            </div>

            <!-- 2ª Linha (Cliente, CPF, Data Nascimento) -->
            <div class="grid grid-cols-4 gap-4 mb-2">
                <div class="col-span-2">
                    <label for="cliente" class="block text-white text-sm flex justify-between">
                        <span>Cliente</span>

                    </label>
                    <input type="text" id="cliente" value="{{$contrato->cliente->nome}}" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md mudar_coletivo" readonly>
                </div>
                <div>
                    <label for="cpf" class="block text-white text-sm flex justify-between">
                        <span>CPF</span>

                    </label>
                    <input type="text" id="cpf" value="{{$contrato->cliente->cpf}}" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md mudar_coletivo" readonly>
                </div>
                <div>
                    <label for="data_nascimento" class="block text-white text-sm flex justify-between">
                        <span>Data Nascimento</span>

                    </label>
                    <input type="date" id="data_nascimento" value="{{$contrato->cliente->data_nascimento}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_coletivo rounded-md" readonly>
                </div>
            </div>

            <!-- 3ª Linha (Código Externo e Email) -->
            <div class="grid grid-cols-4 gap-4 mb-2">
                <div>
                    <label for="codigo_externo" class="block text-white text-sm flex justify-between">
                        <span>Código Externo</span>

                    </label>
                    <input type="text" id="codigo_externo" value="{{$contrato->codigo_externo}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_coletivo rounded-md" readonly>
                </div>
                <div>
                    <label for="fone" class="block text-white text-sm flex justify-between">
                        <span>Celular</span>

                    </label>
                    <input type="text" id="fone" value="{{$contrato->cliente->celular}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_coletivo rounded-md" readonly>
                </div>
                <div class="col-span-2">
                    <label for="email" class="block text-white text-sm flex justify-between">
                        <span>Email</span>
                    </label>
                    <input type="email" id="email" value="{{$contrato->cliente->email}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_coletivo rounded-md" readonly>
                </div>
            </div>

            <!-- 4ª Linha (CEP, Cidade, UF, Bairro) -->
            <div class="grid grid-cols-4 gap-4 mb-2">
                <div>
                    <label for="cep" class="block text-white text-sm flex justify-between">
                        <span>CEP</span>
                    </label>
                    <input type="text" id="cep" value="{{$contrato->cliente->cep}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_coletivo rounded-md" readonly>
                </div>
                <div>
                    <label for="cidade" class="block text-white text-sm flex justify-between">
                        <span>Cidade</span>
                    </label>
                    <input type="text" id="cidade" value="{{$contrato->cliente->cidade}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_coletivo rounded-md" readonly>
                </div>
                <div>
                    <label for="uf" class="block text-white text-sm flex justify-between">
                        <span>UF</span>

                    </label>
                    <input type="text" id="uf" value="{{$contrato->cliente->uf}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_coletivo rounded-md" readonly>
                </div>
                <div>
                    <label for="bairro" class="block text-white text-sm flex justify-between">
                        <span>Bairro</span>

                    </label>
                    <input type="text" id="bairro" value="{{$contrato->cliente->bairro}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_coletivo rounded-md" readonly>
                </div>
            </div>


            <div class="grid grid-cols-2 gap-4 mb-2">
                <div>
                    <label for="rua" class="block text-white text-sm flex justify-between">
                        <span>Rua</span>

                    </label>
                    <input type="text" id="rua" value="{{$contrato->cliente->rua}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_coletivo rounded-md" readonly>
                </div>
                <div>
                    <label for="complemento" class="block text-white text-sm flex justify-between">
                        <span>Complemento</span>

                    </label>
                    <input type="text" id="complemento" value="{{$contrato->cliente->complemento}}" class="w-full bg-gray-100 text-gray-800 mudar_coletivo p-1 rounded-md" readonly>
                </div>
            </div>

            <!-- 6ª Linha (Data Contrato, Valores, Descontos) -->
            <div class="grid grid-cols-5 gap-4 mb-2">
                <div>
                    <label for="data_contrato" class="block text-white text-sm flex justify-between">
                        <span>Data Contrato</span>

                    </label>
                    <input type="text" id="data_contrato"  value="{{ \Carbon\Carbon::parse($contrato->created_at)->format('d/m/Y') }}" class="w-full bg-gray-100 text-gray-800 p-1 text-sm mudar_coletivo rounded-md" readonly>
                </div>
                <div>
                    <label for="valor_contrato" class="block text-white text-sm flex justify-between">
                        <span>Valor Contrato</span>

                    </label>
                    <input type="text" id="valor_contrato" readonly value="{{number_format($contrato->valor_plano,2,",",".")}}" class="w-full mudar_coletivo bg-gray-100 text-gray-800 p-1 text-sm rounded-md">
                </div>
                <div>
                    <label for="valor_adesao" class="block text-white text-sm flex justify-between">
                        <span>Valor Adesão</span>

                    </label>
                    <input type="text" id="valor_adesao" value="{{number_format($contrato->valor_adesao,2,",",".")}}" readonly class="w-full mudar_coletivo bg-gray-100 text-gray-800 p-1 text-sm rounded-md">
                </div>
                <div>
                    <label for="desconto_corretora" class="block text-white text-sm flex justify-between">
                        <span>Desc. Corretora</span>

                    </label>
                    <input type="text" id="desconto_corretora" readonly value="{{$contrato->desconto_corretora ?? 0}}" class="w-full text-sm mudar_coletivo bg-gray-100 text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="desconto_corretor" class="block text-white text-sm flex justify-between">
                        <span>Desc. Corretor</span>

                    </label>
                    <input type="text" id="desconto_corretor" readonly value="{{$contrato->desconto_corretor ?? 0}}" class="w-full text-sm mudar_coletivo bg-gray-100 text-gray-800 p-1 rounded-md">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-2">
                <div>
                    <label for="nome_responsavel" class="block text-white text-sm flex justify-between">
                        <span>Nome Responsavel</span>

                    </label>
                    <input type="text" id="nome_responsavel" value="" readonly value="" class="w-full bg-gray-100 text-sm mudar_coletivo text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="cpf_responsavel" class="block text-white text-sm flex justify-between">
                        <span>CPF Responsavel</span>

                    </label>
                    <input type="text" id="cpf_responsavel" value="" readonly value="" class="w-full bg-gray-100 text-sm mudar_coletivo text-gray-800 p-1 rounded-md">
                </div>
            </div>



        </form>
    </div>

    <!-- Bloco da Direita (40%) -->
    <div class="flex basis-[49%] flex-wrap  p-2 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-lg border">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg w-full">
            <table class="w-full text-lg text-left text-white rtl:text-right text-gray-500 dark:text-gray-400">
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
                    $ii=0;
                @endphp

                @foreach($contrato->comissao->comissoesLancadas as $kk => $cr)
                    @php $fase = 0; @endphp
                    @switch($cr->parcela)
                        @case(1)
                            @php
                                $title = "Pag. Adesão";
                                $fase = 2;
                            @endphp
                            @break;
                        @case(2)
                            @php
                                $title = "Pag. Vigência";
                                $fase = 3;
                            @endphp
                            @break;
                        @case(3)
                            @php
                                $title = "Pag. 2º Parcela";
                                $fase = 4;
                            @endphp
                            @break;
                        @case(4)
                            @php
                                $title = "Pag. 3º Parcela";
                                $fase = 5;
                            @endphp
                            @break;
                        @case(5)
                            @php
                                $title = "Pag. 4º Parcela";
                                $fase = 8;
                            @endphp
                            @break;
                        @case(6)
                            @php
                                $title = "Pag. 5º Parcela";
                                $fase = 9;
                            @endphp
                            @break;
                        @case(7)
                            @php
                                $title = "Pag. 6º Parcela";
                                $fase = 11;
                            @endphp
                            @break;
                    @endswitch

                    <tr class="">
                        <td class="" style="font-size:0.875em;">
                            {{$title}}
                        </td>
                        <td style="font-size:0.875em;">
                            {{$contrato->codigo_externo}}
                        </td>
                        <td style="font-size:0.875em;">
                            {{date('d/m/Y',strtotime($cr->data))}}
                        </td>
                        <td style="font-size:0.875em;">
                            @if($cr->parcela == 1)
                                <span style="margin-left:10px;">{{number_format($contrato->valor_adesao ,2,",",".")}}</span>
                            @else
                                <span style="margin-left:10px;">
                                    {{number_format($contrato->valor_plano,2,",",".")}}
                                </span>
                            @endif
                        </td>
                        <td style="font-size:0.875em;" class="data_baixa">
                            @if(empty($cr->data_baixa) && $cr->cancelados == 1)
                                <span style="margin-left:20px;color:red;">Cancelado</span>
                            @elseif(empty($cr->data_baixa))
                                <span style="margin-left:20px;">---</span>
                            @else
                                <span style="margin-left:20px;">{{date('d/m/Y',strtotime($cr->data_baixa))}}</span>
                        @endif


                    </tr>
                    @php $ii++;@endphp
                @endforeach
                </tbody>
            </table>
            <div class="flex justify-between w-full items-center">

            </div>
        </div>

    </div>
</div>
