<div class="flex flex-wrap">
    <div class="flex justify-end w-full">

        <button id="closeModalEmpresarial" class="text-white bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] hover:bg-red-600 p-1 rounded-full">
            <svg class="w-4 h-4 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
            </svg>

        </button>
    </div>
    <input type="hidden" id="empresarial_cliente_id" name="empresarial_cliente_id" value="{{$dados->id}}">
    <!-- Bloco da Esquerda (Formulário 60%) -->
    <div class="flex basis-[48%] pr-1 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] py-1 shadow-lg rounded-lg border mr-1">
        <form>
            <!-- 1ª Linha (Corretor) -->
            <div class="grid grid-cols-4 gap-4 mb-2">
                <div class="col-span-2">
                    <label for="vendedor" class="block text-white text-sm flex justify-between">
                        <span>Vendedor</span>

                    </label>
                    <input type="text" id="vendedor" value="{{$dados->vendedor}}" class="w-full bg-gray-100 text-gray-800 p-1 text-sm rounded-md" readonly>
                </div>
                <div>
                    <label for="plano" class="block text-white text-sm flex justify-between">
                        <span>Plano</span>
                    </label>
                    <input type="text" value="{{$dados->plano}}" id="plano" class="w-full bg-gray-100 text-gray-800 p-1 text-sm mudar_empresarial rounded-md" readonly>

                </div>
                <div>
                    <label for="tabela_origem" class="block text-white text-sm flex justify-between">
                        <span>Tabela Origem</span>
                    </label>
                   <input type="text" value="{{$dados->tabela_origem}}" id="tabela_origem" class="w-full bg-gray-100 text-gray-800 p-1 text-sm  rounded-md" readonly>
                </div>
            </div>

            <!-- 2ª Linha (Cliente, CPF, Data Nascimento) -->
            <div class="grid grid-cols-5 gap-4 mb-2">
                <div class="col-span-2">
                    <label for="razao_social" class="block text-white text-sm flex justify-between">
                        <span>Razão Social</span>
                    </label>
                    <input type="text" id="razao_social" value="{{$dados->razao_social}}" class="w-full bg-gray-100 text-gray-800 p-1 text-sm mudar_empresarial rounded-md" readonly>
                </div>
                <div class="col-span-2">
                    <label for="cnpj" class="block text-white text-sm flex justify-between">
                        <span>CNPJ</span>
                    </label>
                    <input type="text" id="cnpj" value="{{$dados->cnpj}}" class="w-full bg-gray-100 text-gray-800 p-1 text-sm mudar_empresarial rounded-md" readonly>
                </div>
                <div>
                    <label for="vidas" class="block text-white text-sm flex justify-between">
                        <span>Vidas</span>
                    </label>
                    <input type="text" id="vidas" value="{{$dados->quantidade_vidas}}" class="w-full bg-gray-100 text-sm mudar_empresarial text-gray-800 p-1 rounded-md" readonly>
                </div>
            </div>

            <!-- 3ª Linha (Código Externo e Email) -->
            <div class="grid grid-cols-3 gap-4 mb-2">
                <div>
                    <label for="telefone" class="block text-white text-sm flex justify-between">
                        <span>Telefone</span>
                    </label>
                    <input type="text" id="telefone" value="" class="w-full text-sm  bg-gray-100 mudar_empresarial text-gray-800 p-1 rounded-md" readonly>
                </div>
                <div>
                    <label for="celular" class="block text-white text-sm flex justify-between">
                        <span>Celular</span>
                    </label>
                    <input type="text" id="celular" value="{{$dados->celular}}" class="w-full bg-gray-100 text-sm mudar_empresarial text-gray-800 p-1 rounded-md" readonly>
                </div>
                <div>
                    <label for="email" class="block text-white text-sm flex justify-between">
                        <span>Email</span>
                    </label>
                    <input type="email" id="email" value="{{$dados->email}}" class="w-full bg-gray-100 text-sm mudar_empresarial text-gray-800 p-1 rounded-md" readonly>
                </div>
            </div>

            <!-- 4ª Linha (CEP, Cidade, UF, Bairro) -->
            <div class="grid grid-cols-7 gap-4 mb-2">
                <div class="col-span-2">
                    <label for="responsavel" class="block text-white text-sm flex justify-between">
                        <span>Responsavel</span>
                    </label>
                    <input type="text" id="responsavel" value="{{$dados->responsavel}}" class="w-full bg-gray-100 text-sm mudar_empresarial text-gray-800 p-1 rounded-md" readonly>
                </div>
                <div class="col-span-2">
                    <label for="cidade" class="block text-white text-sm flex justify-between">
                        <span>Cidade</span>
                    </label>
                    <input type="text" id="cidade" value="{{$dados->cidade}}" class="w-full bg-gray-100 text-sm mudar_empresarial text-gray-800 p-1 rounded-md" readonly>
                </div>
                <div class="col-span-1">
                    <label for="uf" class="block text-white text-sm flex justify-between">
                        <span>UF</span>
                    </label>
                    <input type="text" id="uf" value="{{$dados->uf}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_empresarial rounded-md" readonly>
                </div>
                <div class="col-span-2">
                    <label for="plano_contratado" class="block text-white text-sm flex justify-between">
                        <span>Plano Contratado:</span>
                    </label>

                    <select name="plano_contrado" id="plano_contrado" style="color:black;" disabled class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-1.5 mudar_empresarial">
                        <option class="text-center" value="">--</option>
                        <option value="1" {{$dados->plano_contrado == 1 ? 'selected' : ''}}>C/ Copart + Odonto</option>
                        <option value="2" {{$dados->plano_contrado == 2 ? 'selected' : ''}}>C/ Copart Sem Odonto</option>
                        <option value="3" {{$dados->plano_contrado == 3 ? 'selected' : ''}}>Sem Copart + Odonto</option>
                        <option value="4" {{$dados->plano_contrado == 4 ? 'selected' : ''}}>Sem Copart Sem Odonto</option>
                    </select>



                    {{--                    <input type="text" id="plano_contratado" value="{{$texto_empresarial}}" readonly class="w-full mudar_empresarial bg-gray-100 text-sm text-gray-800 p-1 rounded-md">--}}
                </div>
            </div>

            <!-- 5ª Linha (Rua, Complemento) -->
            <div class="grid grid-cols-4 gap-4 mb-2">
                <div>
                    <label for="codigo_corretora" class="block text-white text-sm flex justify-between">
                        <span>Cód.Corretora:</span>
                    </label>
                    <input type="text" id="codigo_corretora" value="{{$dados->codigo_corretora}}" readonly class="w-full mudar_empresarial bg-gray-100 text-sm  text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="codigo_saude" class="block text-white text-sm flex justify-between">
                        <span>Codigo Saude:</span>
                    </label>
                    <input type="text" id="codigo_saude" value="{{$dados->codigo_saude}}" readonly class="w-full mudar_empresarial bg-gray-100 text-sm  text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="codigo_odonto" class="block text-white text-sm flex justify-between">
                        <span>Codigo Odonto:</span>
                    </label>
                    <input type="text" id="codigo_odonto" value="{{$dados->codigo_odonto}}" readonly class="w-full mudar_empresarial bg-gray-100 text-sm  text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="senha_cliente" class="block text-white text-sm flex justify-between">
                        <span>Senha Cliente:</span>
                    </label>
                    <input type="text" id="senha_cliente" value="{{$dados->senha_cliente}}" readonly class="w-full mudar_empresarial bg-gray-100 text-sm  text-gray-800 p-1 rounded-md">
                </div>
            </div>

            <!-- 5ª Linha (Rua, Complemento) -->
            <div class="grid grid-cols-4 gap-4 mb-2">
                <div>
                    <label for="valor_saude" class="block text-white text-sm flex justify-between">
                        <span>Valor Saude:</span>

                    </label>
                    <input type="text" id="valor_saude" value="{{number_format($dados->valor_plano_saude,2,",",".")}}" readonly class="w-full mudar_empresarial_valor bg-gray-100 text-sm  text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="valor_odonto" class="block text-white text-sm flex justify-between">
                        <span>Valor Odonto:</span>

                    </label>
                    <input type="text" id="valor_odonto" value="{{number_format($dados->valor_plano_odonto,2,",",".")}}" readonly class="w-full mudar_empresarial_valor bg-gray-100 text-sm  text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="total_plano" class="block text-white text-sm flex justify-between">
                        <span>Total Plano:</span>

                    </label>
                    <input type="text" id="total_plano" value="{{number_format($dados->valor_plano_total,2,",",".")}}" readonly class="w-full bg-gray-100 mudar_empresarial_valor text-sm text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="taxa_adesao" class="block text-white text-sm flex justify-between">
                        <span>Taxa Adesão:</span>

                    </label>
                    <input type="text" id="taxa_adesao" value="{{number_format($dados->taxa_adesao,2,",",".")}}" readonly class="w-full bg-gray-100 text-sm mudar_empresarial_valor text-gray-800 p-1 rounded-md">
                </div>
            </div>

            <div class="grid grid-cols-4 gap-4 mb-2">
                <div>
                    <label for="plano_adesao" class="block text-white text-sm flex justify-between">
                        <span>Plano c/Adesão:</span>

                    </label>
                    <input type="text" id="plano_adesao" value="{{number_format($dados->valor_total,2,",",".")}}" readonly class="w-full bg-gray-100 mudar_empresarial_valor text-sm text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="valor_boleto" class="block text-white text-sm flex justify-between">
                        <span>Valor Boleto:</span>

                    </label>
                    <input type="text" id="valor_boleto" value="{{number_format($dados->valor_boleto,2,",",".")}}" readonly class="w-full mudar_empresarial_valor bg-gray-100 text-sm  text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="vencimento_boleto" class="block text-white text-sm flex justify-between">
                        <span>Venc. Boleto:</span>

                    </label>
                    <input type="date" id="vencimento_boleto" readonly value="{{$dados->vencimento_boleto}}" class="w-full mudar_empresarial bg-gray-100 text-sm  text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="data_boleto" class="block text-white text-sm flex justify-between">
                        <span>Data 1º Boleto:</span>

                    </label>
                    <input type="date" id="data_boleto" readonly value="{{$dados->data_boleto}}" class="w-full bg-gray-100 text-sm mudar_empresarial text-gray-800 p-1 rounded-md">
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
                    <th style="font-size:0.875em;">#</th>
                    <th style="font-size:0.875em;">Contrato</th>
                    <th style="font-size:0.875em;">Vencimento</th>
                    <th style="font-size:0.875em;">Valor</th>

                    <th style="font-size:0.875em;">Baixa</th>


                </tr>
                </thead>
                <tbody>

                @php
                    $total_cliente = 0;
                    $total_comissao = 0;
                    $ii=0;
                @endphp
                @foreach($dados->comissao->comissoesLancadas as $kk => $cr)
                    @php
                        $ii++;
                        if(!empty($cr->data_baixa)):
                            $total_comissao += $cr->valor;
                        else:
                            $total_cliente += $cr->valor;
                        endif;
                    @endphp
                    <tr>
                        <td class="" style="font-size:0.875em;">
                            @switch($cr->parcela)
                                @case(1)
                                    Pag. 1º Parcela
                                    @break;

                                @case(2)
                                    Pag. 2º Parcela
                                    @break;

                                @case(3)
                                    Pag. 3º Parcela
                                    @break;

                                @case(4)
                                    Pag. 4º Parcela
                                    @break;

                                @case(5)
                                    Pag. 5º Parcela
                                    @break;

                                @case(6)
                                    Pag. 6º Parcela
                                    @break;
                            @endswitch

                        </td>
                        <td class="text-center" style="font-size:0.875em;">
                            {{$dados->codigo_externo}}

                        </td>
                        <td style="font-size:0.875em;">
                            @if($ii == 1)

                                {{date('d/m/Y',strtotime($dados->vencimento_boleto))}}
                            @else
                                {{date('d/m/Y',strtotime($cr->data))}}
                            @endif


                        </td>
                        <td style="font-size:0.875em;">
                            @if($ii <= $dados->quantidade_parcelas)
                                @php
                                    $valor_total =  $dados->valor_plano;
                                    $desconto = $dados->desconto_operadora;
                                    $valorComDesconto = $valor_total - ($valor_total * $desconto / 100);

                                @endphp
                                <span style="margin-left:10px;">{{number_format($valorComDesconto,2,",",".")}}</span>
                            @else
                                <span style="margin-left:10px;">{{number_format($dados->valor_plano,2,",",".")}}</span>
                            @endif



                        </td>
                        <td style="font-size:0.875em;" class="data_baixa_empresarial">
                            @if(empty($cr->data_baixa))
                                <span style="margin-left:20px;">---</span>
                            @else
                                {{date('d/m/Y',strtotime($cr->data_baixa))}}
                            @endif
                        </td>


                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
