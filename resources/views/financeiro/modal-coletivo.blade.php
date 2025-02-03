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
            <input type="hidden" id="id_cliente" value="{{$id}}">
            <div class="grid grid-cols-4 gap-4 mb-2">
                <div>
                    <label for="administradora" class="block text-white text-sm flex justify-between">
                        <span>Administradora</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo_administradora">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <select disabled name="change_administradora_coletivo" id="change_administradora_coletivo" style="color:black;" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-1.5">
                        <option value="">--Escolher Administradora--</option>
                        @foreach($administradoras as $ad)
                            <option value="{{$ad->id}}" {{$ad->id == $administradora_id ? "selected" : ""}} style="color:black;">{{$ad->nome}}</option>
                        @endforeach
                    </select>




                </div>
                <div class="col-span-2">
                    <label for="corretor" class="block text-white text-sm flex justify-between">
                        <span>Corretor</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo_select">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <select disabled name="change_corretor_coletivo" id="change_corretor_coletivo" style="color:black;" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-1.5">
                        <option value="">--Escolher Corretor--</option>
                        @foreach($users as $us)
                            <option value="{{$us->id}}" {{$us->id == $cliente_id ? "selected" : ""}} style="color:black;">{{$us->name}}</option>
                        @endforeach
                    </select>

                </div>
                <div>
                    <label for="status" class="block text-white text-sm flex justify-between">
                        <span>Status</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" value="{{$status}}" id="status" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md" readonly>
                </div>
            </div>

            <!-- 2ª Linha (Cliente, CPF, Data Nascimento) -->
            <div class="grid grid-cols-4 gap-4 mb-2">
                <div class="col-span-2">
                    <label for="cliente" class="block text-white text-sm flex justify-between">
                        <span>Cliente</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="cliente" value="{{$cliente}}" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md mudar_coletivo" readonly>
                </div>
                <div>
                    <label for="cpf" class="block text-white text-sm flex justify-between">
                        <span>CPF</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="cpf" value="{{$cpf}}" class="w-full bg-gray-100 text-gray-800 p-1 rounded-md mudar_coletivo" readonly>
                </div>
                <div>
                    <label for="data_nascimento" class="block text-white text-sm flex justify-between">
                        <span>Data Nascimento</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="date" id="data_nascimento" value="{{$nascimento}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_coletivo rounded-md" readonly>
                </div>
            </div>

            <!-- 3ª Linha (Código Externo e Email) -->
            <div class="grid grid-cols-4 gap-4 mb-2">
                <div>
                    <label for="codigo_externo" class="block text-white text-sm flex justify-between">
                        <span>Código Externo</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="codigo_externo" value="{{$codigo_externo}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_coletivo rounded-md" readonly>
                </div>
                <div>
                    <label for="fone" class="block text-white text-sm flex justify-between">
                        <span>Celular</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="fone" value="{{$fone}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_coletivo rounded-md" readonly>
                </div>
                <div class="col-span-2">
                    <label for="email" class="block text-white text-sm flex justify-between">
                        <span>Email</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="email" id="email" value="{{$email}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_coletivo rounded-md" readonly>
                </div>
            </div>

            <!-- 4ª Linha (CEP, Cidade, UF, Bairro) -->
            <div class="grid grid-cols-4 gap-4 mb-2">
                <div>
                    <label for="cep" class="block text-white text-sm flex justify-between">
                        <span>CEP</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="cep" value="{{$cep}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_coletivo rounded-md" readonly>
                </div>
                <div>
                    <label for="cidade" class="block text-white text-sm flex justify-between">
                        <span>Cidade</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="cidade" value="{{$cidade}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_coletivo rounded-md" readonly>
                </div>
                <div>
                    <label for="uf" class="block text-white text-sm flex justify-between">
                        <span>UF</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="uf" value="{{$uf}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_coletivo rounded-md" readonly>
                </div>
                <div>
                    <label for="bairro" class="block text-white text-sm flex justify-between">
                        <span>Bairro</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="bairro" value="{{$bairro}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_coletivo rounded-md" readonly>
                </div>
            </div>

            <!-- 5ª Linha (Rua, Complemento) -->
            <div class="grid grid-cols-2 gap-4 mb-2">
                <div>
                    <label for="rua" class="block text-white text-sm flex justify-between">
                        <span>Rua</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="rua" value="{{$rua}}" class="w-full bg-gray-100 text-gray-800 p-1 mudar_coletivo rounded-md" readonly>
                </div>
                <div>
                    <label for="complemento" class="block text-white text-sm flex justify-between">
                        <span>Complemento</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="complemento" value="{{$complemento}}" class="w-full bg-gray-100 text-gray-800 mudar_coletivo p-1 rounded-md" readonly>
                </div>
            </div>

            <!-- 6ª Linha (Data Contrato, Valores, Descontos) -->
            <div class="grid grid-cols-5 gap-4 mb-2">
                <div>
                    <label for="data_contrato" class="block text-white text-sm flex justify-between">
                        <span>Data Contrato</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="date" id="data_contrato"  value="{{$contrato}}" class="w-full bg-gray-100 text-gray-800 p-1 text-sm mudar_coletivo rounded-md" readonly>
                </div>
                <div>
                    <label for="valor_contrato" class="block text-white text-sm flex justify-between">
                        <span>Valor Contrato</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="valor_contrato" readonly value="{{number_format($valor_plano,2,",",".")}}" class="w-full mudar_coletivo bg-gray-100 text-gray-800 p-1 text-sm rounded-md">
                </div>
                <div>
                    <label for="valor_adesao" class="block text-white text-sm flex justify-between">
                        <span>Valor Adesão</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="valor_adesao" value="{{number_format($valor_adesao,2,",",".")}}" readonly class="w-full mudar_coletivo bg-gray-100 text-gray-800 p-1 text-sm rounded-md">
                </div>
                <div>
                    <label for="desconto_corretora" class="block text-white text-sm flex justify-between">
                        <span>Desc. Corretora</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="desconto_corretora" readonly value="{{number_format($desconto_corretora,2,",",".") ?? ''}}" class="w-full text-sm mudar_coletivo bg-gray-100 text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="desconto_corretor" class="block text-white text-sm flex justify-between">
                        <span>Desc. Corretor</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="desconto_corretor" readonly value="{{number_format($desconto_corretor,2,",",".") ?? ''}}" class="w-full text-sm mudar_coletivo bg-gray-100 text-gray-800 p-1 rounded-md">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-2">
                <div>
                    <label for="nome_responsavel" class="block text-white text-sm flex justify-between">
                        <span>Nome Responsavel</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="nome_responsavel" value="{{$dependente_nome}}" readonly value="" class="w-full bg-gray-100 text-sm mudar_coletivo text-gray-800 p-1 rounded-md">
                </div>
                <div>
                    <label for="cpf_responsavel" class="block text-white text-sm flex justify-between">
                        <span>CPF Responsavel</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 editar_coletivo">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                        </svg>
                    </label>
                    <input type="text" id="cpf_responsavel" value="{{$dependente_cpf}}" readonly value="" class="w-full bg-gray-100 text-sm mudar_coletivo text-gray-800 p-1 rounded-md">
                </div>
            </div>



        </form>
    </div>

    <!-- Bloco da Direita (40%) -->
    <div class="flex basis-[49%] flex-wrap  p-2 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-lg border">


        <div class="relative overflow-x-auto shadow-md sm:rounded-lg w-full">

            @php $estagioAtual = $dados->financeiro_id;@endphp



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
                    <th scope="col" class="px-1 py-2">
                        Atrasado
                    </th>
                    <th scope="col" class="px-1 py-2">
                        Ação
                    </th>
                    <th scope="col" class="px-1 py-2">
                        Desfazer
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="font-size:0.875em;">Em Analise</td>
                    <td style="font-size:0.875em;">{{$codigo_externo}}</td>
                    <td style="font-size:0.875em;">-</td>
                    <td style="font-size:0.875em;">-</td>
                    <td style="font-size:0.875em;" class="data_analise">-</td>
                    <td style="font-size:0.875em;">-</td>
                    <td class="acao_aqui my-auto mx-auto">
                        @if($dados->data_analise == "")
                            <button type="button" data-id="{{$id}}" class="em_analise text-white flex self-center justify-center mx-auto mt-2 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 w-11/12">Conferido</button>
                        @else
                            <button type="button" class="text-center text-white flex my-auto justify-center bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-blue-800 w-11/12">
                                <svg class="w-6 h-6 text-white dark:text-white text-center mx-auto" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M15.03 9.684h3.965c.322 0 .64.08.925.232.286.153.532.374.717.645a2.109 2.109 0 0 1 .242 1.883l-2.36 7.201c-.288.814-.48 1.355-1.884 1.355-2.072 0-4.276-.677-6.157-1.256-.472-.145-.924-.284-1.348-.404h-.115V9.478a25.485 25.485 0 0 0 4.238-5.514 1.8 1.8 0 0 1 .901-.83 1.74 1.74 0 0 1 1.21-.048c.396.13.736.397.96.757.225.36.32.788.269 1.211l-1.562 4.63ZM4.177 10H7v8a2 2 0 1 1-4 0v-6.823C3 10.527 3.527 10 4.176 10Z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        @endif
                    </td>
                    <td class="text-center flex justify-center items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" data-id="{{$id}}" data-fase="1" viewBox="0 0 24 24" fill="currentColor" id="desfazer_1" class="size-6 mt-2">
                            <path fill-rule="evenodd" d="M9.53 2.47a.75.75 0 0 1 0 1.06L4.81 8.25H15a6.75 6.75 0 0 1 0 13.5h-3a.75.75 0 0 1 0-1.5h3a5.25 5.25 0 1 0 0-10.5H4.81l4.72 4.72a.75.75 0 1 1-1.06 1.06l-6-6a.75.75 0 0 1 0-1.06l6-6a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                        </svg>
                    </td>
                </tr>

                <tr>
                    <td style="font-size:0.875em;">Emissão Boleto</td>
                    <td style="font-size:0.875em;">{{$codigo_externo}}</td>
                    <td style="font-size:0.875em;">-</td>
                    <td style="font-size:0.875em;">-</td>
                    <td style="font-size:0.875em;" class="data_emissao">-</td>
                    <td style="font-size:0.875em;">-</td>
                    <td class="acao_aqui">
                        @if($dados->data_emissao == "")


                            <button type="button" data-id="{{$id}}"   class="flex self-center justify-center mx-auto mt-2 emissao_boleto focus:outline-none text-white bg-purple-700 hover:bg-purple-800 font-medium rounded-lg text-sm px-3 py-1 mb-2 w-11/12">Emitido</button>
                        @else
                            <button type="button" class="text-center text-white flex justify-center mt-2 cursor-not-allowed bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-blue-800 w-11/12">
                                <svg class="w-6 h-6 text-white dark:text-white text-center mx-auto" data-fase="2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M15.03 9.684h3.965c.322 0 .64.08.925.232.286.153.532.374.717.645a2.109 2.109 0 0 1 .242 1.883l-2.36 7.201c-.288.814-.48 1.355-1.884 1.355-2.072 0-4.276-.677-6.157-1.256-.472-.145-.924-.284-1.348-.404h-.115V9.478a25.485 25.485 0 0 0 4.238-5.514 1.8 1.8 0 0 1 .901-.83 1.74 1.74 0 0 1 1.21-.048c.396.13.736.397.96.757.225.36.32.788.269 1.211l-1.562 4.63ZM4.177 10H7v8a2 2 0 1 1-4 0v-6.823C3 10.527 3.527 10 4.176 10Z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        @endif
                    </td>
                    <td class="text-center flex justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" data-fase="2" data-id="{{$id}}" viewBox="0 0 24 24" fill="currentColor" id="desfazer_2" class="size-6">
                            <path fill-rule="evenodd" d="M9.53 2.47a.75.75 0 0 1 0 1.06L4.81 8.25H15a6.75 6.75 0 0 1 0 13.5h-3a.75.75 0 0 1 0-1.5h3a5.25 5.25 0 1 0 0-10.5H4.81l4.72 4.72a.75.75 0 1 1-1.06 1.06l-6-6a.75.75 0 0 1 0-1.06l6-6a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                        </svg>
                    </td>
                </tr>
                @php
                    $ii=0;
                @endphp

                @foreach($dados->comissao->comissoesLancadas as $kk => $cr)
                    @php

                        $fase = 0;
                    @endphp
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
                            {{$dados->quantidade_parcelas}}
                        </td>
                        <td style="font-size:0.875em;">
                            {{date('d/m/Y',strtotime($cr->data))}}
                        </td>
                        <td style="font-size:0.875em;">
                            @if($cr->parcela == 1)
{{--                                @if($quantidade_parcelas >= 1)--}}
{{--                                    @php--}}
{{--                                        $valor_total_adesao =  $dados->valor_plano;--}}
{{--                                        $desconto_adesao = $operadora_valor;--}}
{{--                                        $valorComDescontoAdesao = $valor_total_adesao - ($valor_total_adesao * $desconto_adesao / 100);--}}
{{--                                    @endphp--}}
{{--                                    <span style="margin-left:10px;">{{number_format($valorComDescontoAdesao,2,",",".")}}</span>--}}
{{--                                @else--}}
                                    <span style="margin-left:10px;">{{number_format($dados->valor_adesao ,2,",",".")}}</span>
{{--                                @endif--}}
                            @else
                                <span style="margin-left:10px;">
                                    @if($ii <= $quantidade_parcelas)
                                        @php
                                            $valor_total =  $dados->valor_plano;
                                            $desconto = $operadora_valor;
                                            $valorComDesconto = $valor_total - ($valor_total * $desconto / 100);
                                        @endphp
                                        <span style="margin-left:10px;">{{number_format($valorComDesconto,2,",",".")}}</span>
                                    @else
                                        <span style="margin-left:10px;">{{number_format($dados->valor_plano,2,",",".")}}</span>
                                    @endif


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
                        <td style="font-size:0.875em;text-align:center;">{{$cr->quantidade_dias}}</td>
                        <td class="acao_aqui">
                            @if($cr->status_financeiro == 0)
                                <input type="date" data-id="{{$id}}" min="{{date('Y-m-d', strtotime('1900-01-01'))}}"
                                       max="{{date('Y-m-d')}}"  class="bg-gray-100 text-gray-800 p-1 text-sm rounded-md next date-picker">
                            @else
                                <button type="button" class="text-center text-white flex justify-center cursor-not-allowed bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-blue-800 w-11/12">
                                    <svg class="w-6 h-6 text-white dark:text-white text-center mx-auto" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M15.03 9.684h3.965c.322 0 .64.08.925.232.286.153.532.374.717.645a2.109 2.109 0 0 1 .242 1.883l-2.36 7.201c-.288.814-.48 1.355-1.884 1.355-2.072 0-4.276-.677-6.157-1.256-.472-.145-.924-.284-1.348-.404h-.115V9.478a25.485 25.485 0 0 0 4.238-5.514 1.8 1.8 0 0 1 .901-.83 1.74 1.74 0 0 1 1.21-.048c.396.13.736.397.96.757.225.36.32.788.269 1.211l-1.562 4.63ZM4.177 10H7v8a2 2 0 1 1-4 0v-6.823C3 10.527 3.527 10 4.176 10Z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            @endif
                        </td>
                        <td class="text-center flex justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" data-fase="{{$fase}}" data-id="{{$id}}" viewBox="0 0 24 24" fill="currentColor" id="desfazer_{{$kk+3}}" class="size-6">
                                <path fill-rule="evenodd" d="M9.53 2.47a.75.75 0 0 1 0 1.06L4.81 8.25H15a6.75 6.75 0 0 1 0 13.5h-3a.75.75 0 0 1 0-1.5h3a5.25 5.25 0 1 0 0-10.5H4.81l4.72 4.72a.75.75 0 1 1-1.06 1.06l-6-6a.75.75 0 0 1 0-1.06l6-6a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                            </svg>
                        </td>
                    </tr>
                    @php
                        $ii++;

                    @endphp
                @endforeach
                </tbody>
            </table>
            <div class="flex justify-between w-full items-center">
                <div class="flex" style="flex-basis:45%;">
                    <button data-id="{{$id}}" class="button_excluir w-full text-white bg-red-700 hover:bg-red-800 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2">Excluir</button>
                </div>
                <div class="flex" style="flex-basis:45%;">
                    <button data-id="{{$id}}" class="button_cancelar w-full text-white bg-purple-700 hover:bg-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mb-2">Cancelar</button>
                </div>
            </div>
        </div>

    </div>
</div>
@section('scripts')
    <script>
        $(document).ready(function(){
            $("body").find("#valor_contrato").mask('#.##0,00', {reverse: true});
        });


    </script>
@endsection
