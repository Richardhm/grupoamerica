<div class="flex flex-wrap">
    <div class="flex justify-end w-full">

        <button id="closeModalEmpresarial" class="text-white bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] hover:bg-red-600 p-1 rounded-full">
            <svg class="w-4 h-4 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
            </svg>

        </button>
    </div>


    <input type="hidden" id="empresarial_cliente_id" name="empresarial_cliente_id" value="{{$id}}">




    <!-- Bloco da Esquerda (Formulário 60%) -->
    <div class="flex basis-[48%] pr-1 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] py-1 shadow-lg rounded-lg border mr-1">
        <form>
            <!-- 1ª Linha (Corretor) -->
            <div class="grid grid-cols-4 gap-4 mb-2">
                <div class="col-span-2">
                    <label for="vendedor" class="block text-white text-sm flex justify-between">
                        <span>Vendedor</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_empresarial_select">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
{{--                    <input type="text" id="vendedor" value="{{$vendedor}}" class="w-full bg-gray-100 text-gray-800 p-1 text-sm rounded-md" readonly>--}}
                    <select name="mudar_corretor_empresarial" id="mudar_corretor_empresarial" disabled style="color:black;" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-1.5">
                        @foreach($corretores as $cc)
                            <option value="{{$cc->id}}" {{$dados->user_id == $cc->id ? 'selected' : ''}}>{{$cc->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="plano" class="block text-white text-sm flex justify-between">
                        <span>Plano</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_empresarial_select">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
{{--                    <input type="text" value="{{$plano}}" id="plano" class="w-full bg-gray-100 text-gray-800 p-1 text-sm mudar_empresarial rounded-md" readonly>--}}
                    <select name="mudar_plano_empresarial" id="mudar_plano_empresarial" style="color:black;" disabled class="w-full disabled bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-1.5">
                        @foreach($planos as $pp)
                            <option value="{{$pp->id}}" {{$pp->id == $dados->plano_id ? "selected" : ''}}>{{$pp->nome}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="tabela_origem" class="block text-white text-sm flex justify-between">
                        <span>Tabela Origem</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_empresarial_select">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <select name="mudar_tabela_origem_empresarial" id="mudar_tabela_origem_empresarial" style="color:black;" disabled class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-1.5 mudar_empresarial">
                        @foreach($tabelas_origens as $to)
                            <option value="{{$to->id}}" {{$dados->tabela_origens_id == $to->id ? 'selected' : ''}}>{{$to->nome}}</option>
                        @endforeach
                    </select>


{{--                    <input type="text" value="{{$origens}}" id="tabela_origem" class="w-full bg-gray-100 text-gray-800 p-1 text-sm  rounded-md" readonly>--}}
                </div>
            </div>

            <!-- 2ª Linha (Cliente, CPF, Data Nascimento) -->
            <div class="grid grid-cols-5 gap-4 mb-2">
                <div class="col-span-2">
                    <label for="razao_social" class="block text-white text-sm flex justify-between">
                        <span>Razão Social</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_empresarial">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="razao_social" value="{{$razao_social}}" class="w-full bg-gray-100 text-gray-800 p-1 text-sm mudar_empresarial rounded-md" readonly>
                </div>
                <div class="col-span-2">
                    <label for="cnpj" class="block text-white text-sm flex justify-between">
                        <span>CNPJ</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_empresarial">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="cnpj" value="{{$cnpj}}" class="w-full bg-gray-100 text-gray-800 p-1 text-sm mudar_empresarial rounded-md" readonly>
                </div>
                <div>
                    <label for="vidas" class="block text-white text-sm flex justify-between">
                        <span>Vidas</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_empresarial">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="vidas" value="{{$vidas}}" class="w-full bg-gray-100 text-sm mudar_empresarial text-gray-800 p-1 rounded-md" readonly>
                </div>
            </div>

            <!-- 3ª Linha (Código Externo e Email) -->
            <div class="grid grid-cols-3 gap-4 mb-2">
                <div>
                    <label for="telefone" class="block text-white text-sm flex justify-between">
                        <span>Telefone</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_empresarial">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="telefone" value="" class="w-full text-sm  bg-gray-100 mudar_empresarial text-gray-800 p-1 rounded-md" readonly>
                </div>
                <div>
                    <label for="celular" class="block text-white text-sm flex justify-between">
                        <span>Celular</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_empresarial">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="celular" value="{{$celular}}" class="w-full bg-gray-100 text-sm mudar_empresarial text-gray-800 p-1 rounded-md" readonly>
                </div>
                <div>
                    <label for="email" class="block text-white text-sm flex justify-between">
                        <span>Email</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_empresarial">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="email" id="email" value="{{$email}}" class="w-full bg-gray-100 text-sm mudar_empresarial text-gray-800 p-1 rounded-md" readonly>
                </div>
            </div>

            <!-- 4ª Linha (CEP, Cidade, UF, Bairro) -->
            <div class="grid grid-cols-7 gap-4 mb-2">
                <div class="col-span-2">
                    <label for="responsavel" class="block text-white text-sm flex justify-between">
                        <span>Responsavel</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_empresarial">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="responsavel" value="{{$responsavel}}" class="w-full bg-gray-100 text-sm mudar_empresarial text-gray-800 p-1 rounded-md" readonly>
                </div>
                <div class="col-span-2">
                    <label for="cidade" class="block text-white text-sm flex justify-between">
                        <span>Cidade</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_empresarial">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="cidade" value="{{$cidade}}" class="w-full bg-gray-100 text-sm mudar_empresarial text-gray-800 p-1 rounded-md" readonly>
                </div>
                <div class="col-span-1">
                    <label for="uf" class="block text-white text-sm flex justify-between">
                        <span>UF</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_empresarial">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="uf" value="{{$uf}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_empresarial rounded-md" readonly>
                </div>
                <div class="col-span-2">
                    <label for="plano_contratado" class="block text-white text-sm flex justify-between">
                        <span>Plano Contratado:</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_empresarial_select">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
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
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_empresarial">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="codigo_corretora" value="{{$codigo_corretora}}" readonly class="w-full mudar_empresarial bg-gray-100 text-sm  text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="codigo_saude" class="block text-white text-sm flex justify-between">
                        <span>Codigo Saude:</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_empresarial">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="codigo_saude" value="{{$codigo_saude}}" readonly class="w-full mudar_empresarial bg-gray-100 text-sm  text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="codigo_odonto" class="block text-white text-sm flex justify-between">
                        <span>Codigo Odonto:</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_empresarial">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="codigo_odonto" value="{{$codigo_odonto}}" readonly class="w-full mudar_empresarial bg-gray-100 text-sm  text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="senha_cliente" class="block text-white text-sm flex justify-between">
                        <span>Senha Cliente:</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_empresarial">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="senha_cliente" value="{{$senha_cliente}}" readonly class="w-full mudar_empresarial bg-gray-100 text-sm  text-gray-800 p-1 rounded-md">
                </div>
            </div>

            <!-- 5ª Linha (Rua, Complemento) -->
            <div class="grid grid-cols-4 gap-4 mb-2">
                <div>
                    <label for="valor_saude" class="block text-white text-sm flex justify-between">
                        <span>Valor Saude:</span>

                    </label>
                    <input type="text" id="valor_saude" value="{{number_format($valor_saude,2,",",".")}}" readonly class="w-full mudar_empresarial_valor bg-gray-100 text-sm  text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="valor_odonto" class="block text-white text-sm flex justify-between">
                        <span>Valor Odonto:</span>

                    </label>
                    <input type="text" id="valor_odonto" value="{{number_format($valor_odonto,2,",",".")}}" readonly class="w-full mudar_empresarial_valor bg-gray-100 text-sm  text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="total_plano" class="block text-white text-sm flex justify-between">
                        <span>Total Plano:</span>

                    </label>
                    <input type="text" id="total_plano" value="{{number_format($valor_total,2,",",".")}}" readonly class="w-full bg-gray-100 mudar_empresarial_valor text-sm text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="taxa_adesao" class="block text-white text-sm flex justify-between">
                        <span>Taxa Adesão:</span>

                    </label>
                    <input type="text" id="taxa_adesao" value="{{number_format($taxa_adesao,2,",",".")}}" readonly class="w-full bg-gray-100 text-sm mudar_empresarial_valor text-gray-800 p-1 rounded-md">
                </div>
            </div>

            <div class="grid grid-cols-4 gap-4 mb-2">
                <div>
                    <label for="plano_adesao" class="block text-white text-sm flex justify-between">
                        <span>Plano c/Adesão:</span>

                    </label>
                    <input type="text" id="plano_adesao" value="{{number_format($valor_total,2,",",".")}}" readonly class="w-full bg-gray-100 mudar_empresarial_valor text-sm text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="valor_boleto" class="block text-white text-sm flex justify-between">
                        <span>Valor Boleto:</span>

                    </label>
                    <input type="text" id="valor_boleto" value="{{number_format($valor_boleto,2,",",".")}}" readonly class="w-full mudar_empresarial_valor bg-gray-100 text-sm  text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="vencimento_boleto" class="block text-white text-sm flex justify-between">
                        <span>Venc. Boleto:</span>

                    </label>
                    <input type="date" id="vencimento_boleto" readonly value="{{$vencimento_boleto}}" class="w-full mudar_empresarial bg-gray-100 text-sm  text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="data_boleto" class="block text-white text-sm flex justify-between">
                        <span>Data 1º Boleto:</span>

                    </label>
                    <input type="date" id="data_boleto" readonly value="{{$data_boleto}}" class="w-full bg-gray-100 text-sm mudar_empresarial text-gray-800 p-1 rounded-md">
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
                    <th style="font-size:0.875em;">Ação</th>

                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="font-size:0.875em;">Em Analise</td>
                    <td class="text-center" style="font-size:0.875em;">{{$codigo_externo}}</td>
                    <td style="margin-left:20px;">---</td>
                    <td style="margin-left:20px;">---</td>
                    <td style="margin-left:0px;" class="em_analise_empresarial">---</td>
                    <td>
                        @if($data_analise == "")
                        <button data-id="{{$id}}" type="button"
                                class="em_analise_empresarial text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 w-11/12">Conferido</button>
                        @else
                            <button type="button" class="em_analise text-center text-white flex justify-center cursor-not-allowed bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-blue-800 w-11/12">
                                <svg class="w-6 h-6 text-white dark:text-white text-center mx-auto" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M15.03 9.684h3.965c.322 0 .64.08.925.232.286.153.532.374.717.645a2.109 2.109 0 0 1 .242 1.883l-2.36 7.201c-.288.814-.48 1.355-1.884 1.355-2.072 0-4.276-.677-6.157-1.256-.472-.145-.924-.284-1.348-.404h-.115V9.478a25.485 25.485 0 0 0 4.238-5.514 1.8 1.8 0 0 1 .901-.83 1.74 1.74 0 0 1 1.21-.048c.396.13.736.397.96.757.225.36.32.788.269 1.211l-1.562 4.63ZM4.177 10H7v8a2 2 0 1 1-4 0v-6.823C3 10.527 3.527 10 4.176 10Z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        @endif
                    </td>
                </tr>
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

                        <td>
                            @if($cr->status_financeiro == 0)
                                <input type="date" data-id="{{$id}}" class="bg-gray-100 text-gray-800 p-1 text-sm rounded-md next_empresarial">
                            @else
                                <button type="button" class="em_analise text-center text-white flex justify-center cursor-not-allowed bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-blue-800 w-11/12">
                                    <svg class="w-6 h-6 text-white dark:text-white text-center mx-auto" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M15.03 9.684h3.965c.322 0 .64.08.925.232.286.153.532.374.717.645a2.109 2.109 0 0 1 .242 1.883l-2.36 7.201c-.288.814-.48 1.355-1.884 1.355-2.072 0-4.276-.677-6.157-1.256-.472-.145-.924-.284-1.348-.404h-.115V9.478a25.485 25.485 0 0 0 4.238-5.514 1.8 1.8 0 0 1 .901-.83 1.74 1.74 0 0 1 1.21-.048c.396.13.736.397.96.757.225.36.32.788.269 1.211l-1.562 4.63ZM4.177 10H7v8a2 2 0 1 1-4 0v-6.823C3 10.527 3.527 10 4.176 10Z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="flex justify-between w-full items-center">
                <div class="flex" style="flex-basis:45%;">
                    <button data-id="{{$id}}" class="button_excluir_empresarial w-full text-white bg-red-700 hover:bg-red-800 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2">Excluir</button>
                </div>
                <div class="flex" style="flex-basis:45%;">
                    <button data-id="{{$id}}" class="button_cancelar_empresarial w-full text-white bg-purple-700 hover:bg-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mb-2">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>
