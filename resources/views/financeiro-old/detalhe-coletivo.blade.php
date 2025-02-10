<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="{{asset('assets/jquery.min.js')}}"></script>


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>


        .container_full_cards {
            display: flex;
            flex-wrap: wrap; /* Permite que os itens se movam para a linha seguinte se não houver espaço suficiente */

        }

        .card_info,
        .historico_corretor {
            background-color: rgba(254,254,254,0.18);
            backdrop-filter: blur(15px);
            border-radius: 5px;
            display: flex;
            flex-direction: column; /* Garantir que os itens sejam organizados em uma coluna */
            flex: 1 1 48%; /* Ajusta a largura base para 45% */
            max-width: 48%; /* Garante que não exceda 45% da largura do container pai */
            margin: 10px; /* Adiciona espaçamento ao redor dos itens, opcional */
            color:#FFF;
        }

        .historico_corretor {
            /* Adicione estilos específicos para .historico_corretor se necessário */
        }

        .card_info input {
            width: 100%; /* Faz com que o input ocupe 100% da largura do contêiner pai */
            box-sizing: border-box; /* Inclui o padding e a borda no cálculo da largura total */
        }

    </style>



</head>

<body>

    <input type="hidden" id="cliente_id_alvo" value="{{$dados->clientes->id}}">



    <div id="dataBaixaModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg max-w-sm w-full">
            <div class="flex justify-between items-center p-4 border-b">
                <h5 class="text-xl font-semibold" id="dataBaixaModalLabel">Data Da Baixa?</h5>
                <button type="button" class="text-gray-600 hover:text-gray-900" id="closeModalBtn">&times;</button>
            </div>
            <div class="p-4">
                <form action="" name="data_da_baixa" id="data_da_baixa" method="POST">
                    <input type="date" name="data_baixa" id="data_baixa" class="w-full p-2 border rounded">
                    <input type="hidden" name="comissao_id" id="comissao_id_baixa" value="{{$dados->comissao->id}}">
                    <div id="error_data_baixa" class="text-red-500 mt-2"></div>
            </div>
            <div class="flex justify-end p-4 border-t">
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded mr-2" id="closeModalBtn">Fechar</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Salvar</button>
            </div>
            </form>
        </div>
    </div>







    <div id="cancelarModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
            <div class="flex justify-between items-center p-4 border-b">
                <h5 class="text-xl font-semibold" id="cancelarModalLabel">Cancelados</h5>
                <button type="button" class="text-gray-600 hover:text-gray-900" id="closeModalBtnCancelado">&times;</button>
            </div>
            <div class="p-4">
                <form action="" method="POST" name="formulario_cancelados" id="formulario_cancelados">
                    <input type="hidden" name="comissao_id_cancelado" id="comissao_id_cancelado" value="{{$dados->comissao->id}}">

                    <div class="mb-4">
                        <label for="date" class="block text-sm font-medium text-gray-700">Data Baixa:</label>
                        <input type="date" name="date" id="date" class="mt-1 w-full p-2 border rounded">
                    </div>

                    <div class="mb-4">
                        <label for="motivo" class="block text-sm font-medium text-gray-700">Motivo Cancelamento:</label>
                        <select name="motivo" id="motivo" class="mt-1 w-full p-2 border rounded">
                            <option value="">--Escolher o Motivo--</option>
                            @foreach($motivo_cancelados as $mm)
                                <option value="{{$mm->id}}">{{$mm->nome}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="obs" class="block text-sm font-medium text-gray-700">Observação:</label>
                        <textarea name="obs" id="obs" cols="30" rows="4" class="mt-1 w-full p-2 border rounded"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded mr-2" id="closeModalBtn">Fechar</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <input type="hidden" id="excluir_cliente" value="{{$dados->clientes->id}}">

    <input type="hidden" id="data_cliente" value="{{$dados->clientes->id}}">
    <input type="hidden" id="data_contrato" value="{{$dados->id}}">

    <input type="hidden" id="data_financeiro" value="{{$dados->financeiro_id}}">

    <main class="container_full_cards">

        <section class="p-2 rounded-lg shadow-md card_info">

            <div class="flex flex-col">
                <label class="text-white text-sm">Corretor:</label>
                <input type="text" value="{{$dados->clientes->user->name}}" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm">
            </div>
            <!-- Linha 1 -->
            <div class="grid grid-cols-3 gap-2 mb-1">
                <div class="flex flex-col space-y-1">
                    <label class="text-white text-xs">Administradora:</label>
                    <input type="text" value="{{$dados->administradora->nome}}" name="administradora_individual" id="administradora_individual" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" readonly>
                </div>

                <div class="flex flex-col space-y-1">
                    <label class="text-white text-xs">Tipo Plano:</label>
                    <input type="text" value="{{$dados->plano->nome}}" id="tipo_plano_individual" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" readonly>
                </div>

                <div class="flex flex-col space-y-1">
                    <label class="text-white text-xs">Status:</label>
                    <input type="text" id="status" value="{{$status != null ? $status : $dados->financeiro->nome}}" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" readonly>
                </div>
            </div>

            <!-- Linha 2 -->
            <div class="grid grid-cols-3 gap-4 mb-1">
                <div class="flex flex-col space-y-1">
                    <label class="text-white text-sm">Cliente:</label>
                    <input type="text" name="cliente" id="cliente" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" value="{{$dados->clientes->nome}}" readonly>
                </div>

                <div class="flex flex-col space-y-1">
                    <label class="text-white text-sm">Data Nascimento:</label>
                    <input type="text" name="data_nascimento" id="data_nascimento" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" value="{{date('d/m/Y',strtotime($dados->clientes->data_nascimento))}}" readonly>
                </div>

                <div class="flex flex-col space-y-1">
                    <label class="text-white text-sm">Codigo Externo:</label>
                    <input type="text" name="codigo_externo" id="codigo_externo" value="{{$dados->codigo_externo}}" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" readonly>
                </div>
            </div>

            <!-- Linha 3 -->
            <div class="grid grid-cols-3 gap-4 mb-1">
                <div class="flex flex-col">
                    <label class="text-white text-sm">CPF:</label>
                    <input type="text" id="cpf" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" value="{{$dados->clientes->cpf}}" readonly>
                </div>

                <div class="flex flex-col">
                    <label class="text-white text-sm">Celular:</label>
                    <input type="text" id="celular" value="{{$dados->clientes->celular}}" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" readonly>
                </div>

                <div class="flex flex-col">
                    <label class="text-white text-sm">Telefone:</label>
                    <input type="text" id="telefone" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" value="{{$dados->clientes->telefone ?? ''}}" readonly>
                </div>
            </div>

            <!-- Linha 4 -->
            <div class="grid grid-cols-4 gap-4 mb-1">
                <div class="flex flex-col">
                    <label class="text-white text-sm">Email:</label>
                    <input type="text" id="email" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" value="{{$dados->clientes->email}}" readonly>
                </div>

                <div class="flex flex-col">
                    <label class="text-white text-sm">CEP:</label>
                    <input type="text" id="cep" value="{{$dados->clientes->cep}}" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" readonly>
                </div>

                <div class="flex flex-col">
                    <label class="text-white text-sm">Cidade:</label>
                    <input type="text" id="cidade" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" value="{{$dados->clientes->cidade}}" readonly>
                </div>

                <div class="flex flex-col">
                    <label class="text-white text-sm">UF:</label>
                    <input type="text" id="uf" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" value="{{$dados->clientes->uf}}" readonly>
                </div>
            </div>

            <!-- Linha 5 -->
            <div class="grid grid-cols-3 gap-4 mb-1">
                <div class="flex flex-col">
                    <label class="text-white text-sm">Bairro:</label>
                    <input type="text" id="bairro" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" value="{{$dados->clientes->bairro}}" readonly>
                </div>

                <div class="flex flex-col">
                    <label class="text-white text-sm">Rua:</label>
                    <input type="text" id="rua" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" value="{{$dados->clientes->rua}}" readonly>
                </div>

                <div class="flex flex-col">
                    <label class="text-white text-sm">Complemento:</label>
                    <input type="text" id="complemento" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" value="{{$dados->clientes->complemento}}" readonly>
                </div>
            </div>

            <!-- Linha 6 -->
            <div class="grid grid-cols-4 gap-4 mb-1">
                <div class="flex flex-col">
                    <label class="text-white text-sm">Data Contrato:</label>
                    <input type="text" name="data_contrato" id="data_contrato" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" value="{{date('d/m/Y',strtotime($dados->created_at))}}" readonly>
                </div>

                <div class="flex flex-col">
                    <label class="text-white text-sm">Valor Contrato:</label>
                    <input type="text" name="valor_contrato" id="valor_contrato" value="R$ {{number_format($dados->valor_plano,2,',','.')}}" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" readonly>
                </div>

                <div class="flex flex-col">
                    <label class="text-white text-sm">Valor Adesão:</label>
                    <input type="text" name="valor_adesao" id="valor_adesao" value="R$ {{number_format($dados->valor_adesao,2,',','.')}}" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" readonly>
                </div>

                <div class="flex flex-col">
                    <label class="text-white text-sm">Data Boleto:</label>
                    <input type="text" name="data_boleto" id="data_boleto" value="{{date('d/m/Y',strtotime($dados->data_boleto))}}" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" readonly>
                </div>
            </div>

            <div class="flex justify-between">
                <div class="flex flex-col w-[45%]">
                    <label class="text-white text-sm">Desconto Corretora:</label>
                    <input type="text" value="{{$dados->desconto_corretora ?? '0,00'}}" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" readonly>
                </div>

                <div class="flex flex-col w-[45%]">
                    <label class="text-white text-sm">Desconto Corretor:</label>
                    <input type="text" value="{{$dados->desconto_corretor ?? '0,00'}}" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" readonly>
                </div>

            </div>




            <!-- Linha 7 -->
            <div class="flex justify-between">
                <div class="flex flex-col w-[45%]">
                    <label class="text-white text-sm">Nome Responsavel:</label>
                    <input type="text" value="{{$dependentes->nome ?? ''}}" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" readonly>
                </div>

                <div class="flex flex-col w-[45%]">
                    <label class="text-white text-sm">CPF Responsavel:</label>
                    <input type="text" value="{{$dependentes->cpf ?? ''}}" class="form-input bg-gray-700 text-white border-gray-600 rounded-md p-1 text-sm" value="{{date('d/m/Y',strtotime($dados->data_vigencia))}}" readonly>
                </div>

            </div>





        </section>
        @php
            @endphp
        <section class="historico_corretor">
            <div class="flex items-center justify-between">
                <h5 class="text-center mt-1 ml-1">Pagamentos</h5>
                <p class="align-self-center mt-3 mr-2">{{$dados->clientes->user->name}}</p>
            </div>


            <table class="table table-sm h-50" style="margin:0;padding:0;">
                <thead>
                <tr>
                    <th style="font-size:0.875em;">Parcela</th>
                    <th style="font-size:0.875em;">Contrato</th>
                    <th style="font-size:0.875em;">Vencimento</th>
                    <th style="font-size:0.875em;">Valor</th>
                    <th style="font-size:0.875em;">Baixa</th>
                    <th style="font-size:0.875em;margin-left:10px;text-align:center;" align="center">Atrasado</th>

                </tr>
                </thead>
                <tbody>

                @foreach($dados->comissao->comissoesLancadas as $kk => $cr)

                    <tr>
                        <td class="" style="font-size:0.875em;">
                            @switch($cr->parcela)
                                @case(1)
                                    Pag. Adesão
                                    @break;

                                @case(2)
                                    Pag. Vigência
                                    @break;

                                @case(3)
                                    Pag. 2º Parcela
                                    @break;

                                @case(4)
                                    Pag. 3º Parcela
                                    @break;

                                @case(5)
                                    Pag. 4º Parcela
                                    @break;

                                @case(6)
                                    Pag. 5º Parcela
                                    @break;

                                @case(7)
                                    Pag. 6º Parcela
                                    @break;
                            @endswitch

                        </td>
                        <td class="text-center" style="font-size:0.875em;">
                            {{$dados->codigo_externo}}

                        </td>
                        <td style="font-size:0.875em;">
                            {{date('d/m/Y',strtotime($cr->data))}}
                        </td>
                        <td style="font-size:0.875em;">

                            @if($cr->parcela == 1)
                                <span style="margin-left:10px;">{{number_format($dados->valor_adesao ,2,",",".")}}</span>
                            @else
                                <span style="margin-left:10px;">{{number_format($dados->valor_plano,2,",",".")}}</span>
                            @endif



                        </td>
                        <td style="font-size:0.875em;">
                            @if(empty($cr->data_baixa) && $cr->cancelados == 1)
                                <span style="margin-left:20px;color:red;">Cancelado</span>
                            @elseif(empty($cr->data_baixa))
                                <span style="margin-left:20px;">---</span>
                            @else
                                <span style="margin-left:20px;">{{date('d/m/Y',strtotime($cr->data_baixa))}}</span>
                        @endif

                        <td style="font-size:0.875em;text-align:center;">{{$cr->quantidade_dias}}</td>

                        <td>
                            <input type="date" class="mudar_fase" >
                        </td>

                    </tr>
                @endforeach
                </tbody>

            </table>





        </section>





    </main>
    <div class="flex mt-3 flex-wrap" style="flex-basis:100%;justify-content: flex-end;">
        <div class="flex flex-wrap" style="flex-basis:50%;justify-content: flex-end;">

            @if($cancelados == 0)


                @switch($dados->financeiro_id)

                    @case(1)
                        <button class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 w-50 mr-1 excluir_coletivo">Excluir</button>
                        <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 next">Conferido</button>
                        @break

                    @case(2)
                        <button class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 w-50 mr-1 excluir_coletivo">Excluir</button>
                        <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 next">Emitiu Boleto</button>
                        @break

                    @case(3)
                        <button class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 w-50 mr-1 cancelar">Cancelar</button>
                        <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 pagamento_adesao next">Pagar Adesão</button>
                        @break

                    @case(4)
                        <button class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 w-50 mr-1 cancelar">Cancelar</button>
                        <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 pagamento_segunda_parcela next">Pagar Vigência</button>
                        @break

                    @case(6)
                        <button class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 w-50 mr-1 cancelar">Cancelar</button>
                        <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 pagamento_terceira_parcela next">Pagar 2º Parcela</button>
                        @break

                    @case(7)
                        <button class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 w-50 mr-1 cancelar">Cancelar</button>
                        <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 pagamento_quarta_parcela next">Pagar 3º Parcela</button>
                        @break

                    @case(8)
                        <button class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 w-50 mr-1 cancelar">Cancelar</button>
                        <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 pagamento_quinta_parcela next">Pagar 4º Parcela</button>
                        @break

                    @case(9)
                        <button class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 w-50 mr-1 cancelar">Cancelar</button>
                        <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 pagamento_sexta_parcela next">Pagar 5º Parcela</button>
                        @break

                    @case(10)
                        <button class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 w-50 mr-1 cancelar">Cancelar</button>
                        <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 pagamento_sexta_parcela next">Pagar 6º Parcela</button>
                        @break



                @endswitch



            @endif
        </div>

    </div>

    <script src="{{asset('js/jquery.mask.min.js')}}"></script>
    <script>
        $(function(){
            $("#cpf").mask('000.000.000-00');
            $("#cpf_responsavel").mask('000.000.000-00');
            $('#desconto_corretora').mask("#.##0,00", {reverse: true});
            $('#desconto_corretor').mask("#.##0,00", {reverse: true});
            $(".back").on('click',function(){
                window.history.go(-1);
                return false;
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("body").on('click','.cancelar',function(){
                $('#cancelarModal').removeClass('hidden').addClass('flex');
            });

            $('#closeModalBtnCancelado').click(function() {
                $('#cancelarModal').removeClass('flex').addClass('hidden');
            });

            // Fechar o modal
            $('#closeModalBtn, #dataBaixaModal').on('click', function(event) {
                if (event.target.id === 'closeModalBtn' || event.target.id === 'dataBaixaModal') {
                    $('#dataBaixaModal').addClass('hidden');
                }
            });
            $("body").on('click','.excluir_coletivo',function(){
                Swal.fire({
                    title: 'Você tem certeza que deseja realizar essa operação?',
                    showDenyButton: true,
                    confirmButtonText: 'Sim',
                    denyButtonText: `Cancelar`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        let id_cliente = $("#excluir_cliente").val();
                        $.ajax({
                            url:"{{route('financeiro.excluir.cliente')}}",
                            method:"POST",
                            data:"id_cliente="+id_cliente,
                            success:function(res) {
                                if(res != "error") {
                                    window.location.href = "/admin/financeiro?ac=coletivo";

                                } else {
                                    Swal.fire('Opss', 'Erro ao excluir o cliente', 'error')
                                }
                            }
                        });
                    } else if (result.isDenied) {
                        //
                    }
                })
                //}
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("body").on('click','.next',function(){
                //if($("#data_cliente").val() && $("#data_contrato").val()) {
                let id_cliente = $("#data_cliente").val();
                let id_contrato = $("#data_contrato").val();

                let financeiro = $("#data_financeiro").val();

                if(financeiro == 1 || financeiro == 2) {
                    let confirmacao = confirm('Você tem certeza que deseja realizar essa operação?');
                    if(confirmacao) {
                        $.ajax({
                            url:"{{route('financeiro.mudarStatusColetivo')}}",
                            data:"id_cliente="+id_cliente+"&id_contrato="+id_contrato,
                            method:"POST",
                            success:function(res) {
                                window.location.href = "/financeiro?ac=coletivo";
                            }
                        })
                    }
                } else {
                    $('#dataBaixaModal').removeClass('hidden');
                }
            });

            $('form[name="formulario_cancelados"]').on('submit',function(){
                $.ajax({
                    url:"{{route('financeiro.contrato.cancelados')}}",
                    method:"POST",
                    data:$(this).serialize(),
                    success:function(res) {
                        if(res == "error") {
                        } else {
                            window.location.href = "/admin/financeiro?ac=coletivo";
                        }
                    }
                });
                return false;
            });







            $("form[name='data_da_baixa']").on('submit',function(){

                let cliente = $("#cliente").val().trim().toLowerCase().replace(/\s+/g, '+');

                let id_cliente = $("#data_cliente").val();
                let id_contrato = $("#data_contrato").val();
                $.ajax({
                    url:"{{route('financeiro.baixa.data')}}",
                    method:"POST",
                    data: {
                        "id_cliente": id_cliente,
                        "id_contrato": id_contrato,
                        "data_baixa": $("#data_baixa").val(),
                        "comissao_id": $("#comissao_id_baixa").val()
                    },
                    beforeSend:function() {
                        if($("#data_baixa").val() == "") {
                            $("#error_data_baixa").html('<p class="alert alert-danger">O campo data é campo obrigatório</p>');
                            return false;
                        } else {
                            $("#error_data_baixa").html('');
                        }
                    },
                    success:function(res) {
                        window.location.href = "/financeiro?ac=coletivo";
                    }
                })
                return false;
            });

            $('.editar_btn').on('click',function(){
                let params = $("#cliente").prop('readonly');
                if(!params) {
                    adicionarReadonly();
                } else {
                    removeReadonly();
                }
            });

            function removeReadonly() {
                $("#cliente").removeAttr('readonly').addClass('editar_campo');
                $("#data_nascimento").removeAttr('readonly').addClass('editar_campo');
                $("#cpf").removeAttr('readonly').addClass('editar_campo')
                $("#nome_responsavel").removeAttr('readonly').addClass('editar_campo');
                $("#cpf_responsavel").removeAttr('readonly').addClass('editar_campo');
                $("#celular").removeAttr('readonly').addClass('editar_campo');
                $("#telefone").removeAttr('readonly').addClass('editar_campo');
                $("#email").removeAttr('readonly').addClass('editar_campo');
                $("#cep").removeAttr('readonly').addClass('editar_campo');
                $("#cidade").removeAttr('readonly').addClass('editar_campo');
                $("#uf").removeAttr('readonly').addClass('editar_campo');
                $("#bairro").removeAttr('readonly').addClass('editar_campo');
                $("#rua").removeAttr('readonly').addClass('editar_campo');
                $("#complemento").removeAttr('readonly').addClass('editar_campo');
                $("#desconto_corretora").removeAttr('readonly').addClass('editar_campo');
                $("#desconto_corretor").removeAttr('readonly').addClass('editar_campo');

                $("#codigo_externo").removeAttr('readonly').addClass('editar_campo');

            }


            function adicionarReadonly() {
                $("#cliente").attr('readonly',true).removeClass('editar_campo');
                $("#data_nascimento").attr('readonly',true).removeClass('editar_campo');
                $("#cpf").attr('readonly',true).removeClass('editar_campo');
                $("#nome_responsavel").attr('readonly',true).removeClass('editar_campo');
                $("#cpf_responsavel").attr('readonly',true).removeClass('editar_campo');
                $("#celular").attr('readonly',true).removeClass('editar_campo');
                $("#telefone").attr('readonly',true).removeClass('editar_campo');
                $("#email").attr('readonly',true).removeClass('editar_campo')
                $("#cep").attr('readonly',true).removeClass('editar_campo')
                $("#cidade").attr('readonly',true).removeClass('editar_campo');
                $("#uf").attr('readonly',true).removeClass('editar_campo')
                $("#bairro").attr('readonly',true).removeClass('editar_campo')
                $("#rua").attr('readonly',true).removeClass('editar_campo')
                $("#complemento").attr('readonly',true).removeClass('editar_campo')
                $("#desconto_corretora").attr('readonly',true).removeClass('editar_campo')
                $("#desconto_corretor").attr('readonly',true).removeClass('editar_campo')
                $("#codigo_externo").attr('readonly',true).removeClass('editar_campo');

            }

            $("body").on('change','.editar_campo',function(){
                let alvo = $(this).attr('id');
                let valor = $("#"+alvo).val();
                let id_cliente = $("#cliente_id_alvo").val();

                $.ajax({
                    url:"{{route('financeiro.editar.campoIndividualmente')}}",
                    method:"POST",
                    data:"alvo="+alvo+"&valor="+valor+"&id_cliente="+id_cliente,
                    success:function(res) {
                        console.log(res);
                        //table.ajax.reload();
                    }
                });
            });


        });
    </script>









</body>
</html>


