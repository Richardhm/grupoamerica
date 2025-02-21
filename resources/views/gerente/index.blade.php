<x-app-layout>
    <input type="hidden" name="corretor_escolhido" id="corretor_escolhido">
    <input type="hidden" name="corretor_escolhido_historico" id="corretor_escolhido_historico">
    <input type="hidden" id="valores_confirmados" value="{{$ids_confirmados ?? null}}">
    <input type="hidden" id="mes_fechado" value="{{$mes ?? ''}}">
    <input type="hidden" id="mes_historico" value="">
    <input type="hidden" id="user_historico" value="">
    <input type="hidden" id="desconto_individual" value="">
    <input type="hidden" id="desconto_coletivo" value="">
    <input type="hidden" id="desconto_empresarial" value="">

    <div id="myModalColetivo" class="fixed inset-0 z-50 flex justify-center items-start hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] z-40"></div>
        <!-- Conteúdo da Modal -->
        <div class="relative w-11/12 rounded-lg shadow-3xl p-2 z-50">
            <!-- Botão Fechar no Topo -->
            <div id="modalLoader" class="flex justify-center items-center h-32">
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


    <div id="myModalIndividual" class="fixed inset-0 z-50 flex justify-center items-start hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] z-40"></div>
        <!-- Conteúdo da Modal -->
        <div class="relative w-11/12 rounded-lg shadow-3xl p-2 z-50">
            <!-- Botão Fechar no Topo -->
            <div id="modalLoaderIndividual" class="flex justify-center items-center h-32">
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

    <div class="hidden" id="dataBaixaModal" tabindex="-1" role="dialog" aria-labelledby="dataBaixaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dataBaixaModalLabel">Data Da Baixa?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" name="data_da_baixa" id="data_da_baixa" method="POST">
                    <input type="date" name="data_baixa" id="data_baixa" class="form-control form-control-sm">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="corretora" id="corretora">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
            </form>
            </div>
        </div>
    </div>

    <div id="confirmationMessage" class="alert alert-success mt-3 text-center" style="display: none;">

    </div>

    <div id="loading-overlay" class="ocultar" style="display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
        <div class="dots-loading">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

{{--    @if(auth()->user()->can('listar_todos'))--}}
{{--        <div style="display:flex;justify-content: center;">--}}
{{--            <button data-corretora="1" style="background-color:#123449;border:none;color:#FFF;padding:15px;border-radius:5px;margin-right:5px;">Accert</button>--}}
{{--            <button data-corretora="2" style="background-color:#123449;border:none;color:#FFF;padding:15px;border-radius:5px;margin-right:5px;">Innove</button>--}}
{{--            <button data-corretora="0" style="background-color:#123449;border:none;color:#FFF;padding:15px;border-radius:5px;">Vivaz</button>--}}
{{--        </div>--}}
{{--    @endif--}}


    <div id="myModalEmpresarial" class="fixed inset-0 z-50 flex items-start justify-center hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] z-40"></div>
        <!-- Conteúdo da Modal -->
        <div class="relative w-11/12 rounded-lg shadow-3xl p-2 z-50">
            <!-- Botão Fechar no Topo -->
            <div id="modalLoaderEmpresa" class="flex justify-center items-start h-32">
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







    <section class="conteudo_abas" style="padding:5px;width:96%;margin:0 auto;">

            <ul class="list_abas" style="margin-top:1px;">
                <li data-id="aba_comissao" class="menu-inativo ativo">Comissão</li>
                <li data-id="aba_historico" class="menu-inativo" style="margin:0 1%;">Historico</li>
                <li class="ocultar" id="corretor_em_destaque"></li>
            </ul>

           <main id="aba_comissao" class="aba_comissao_container justify-between">
               <section  style="display:flex; flex-wrap:wrap; width:28%;justify-content: space-between;">
                   <div style="display:flex;flex-basis:48%;flex-direction:column;">
                       <select name="mes_folha" id="mes_folha" class="form-control form-control-sm mb-1 w-full border border-gray-300 text-gray-700 text-sm rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-400 tamanho_de_25" {{$mes != null && !empty($mes) ? 'disabled' : ''}}>
                           <option value="" class="text-center">---</option>
                           <option value="01" {{$mes == '01' ? 'selected' : ''}}>Janeiro/2025</option>
                           <option value="02" {{$mes == '02' ? 'selected' : ''}}>Fevereiro/2025</option>
                           <option value="03" {{$mes == '03' ? 'selected' : ''}}>Março/2025</option>
                           <option value="04" {{$mes == '04' ? 'selected' : ''}}>Abril/2025</option>
                           <option value="05" {{$mes == '05' ? 'selected' : ''}}>Maio/2025</option>
                           <option value="06" {{$mes == '06' ? 'selected' : ''}}>Junho/2025</option>
                           <option value="07" {{$mes == '07' ? 'selected' : ''}}>Julho/2025</option>
                           <option value="08" {{$mes == '08' ? 'selected' : ''}}>Agosto/2025</option>
                           <option value="09" {{$mes == '09' ? 'selected' : ''}}>Setembro/2025</option>
                           <option value="10" {{$mes == '10' ? 'selected' : ''}}>Outubro/2025</option>
                           <option value="11" {{$mes == '11' ? 'selected' : ''}}>Novembro/2024</option>
                           <option value="12" {{$mes == '12' ? 'selected' : ''}}>Dezembro/2024</option>
                       </select>

                       <ul style="margin:0;padding:0;width:100%;" class="w-full flex flex-col bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
                           <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Salario:
                                </span>
                               <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" disabled name="salario" id="salario" value="{{$total_salario}}"
                                    class="salario_usuario bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md"
                                    style="text-align:right; height:20px; font-size:0.8em; width: 100%;">
                                </span>
                           </li>

                           <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Comissão:
                                </span>
                               <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" disabled name="comissao" id="comissao" value="{{$total_comissao}}"
                                           class="salario_usuario bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md"
                                           style="text-align:right; height:20px; font-size:0.8em; width: 100%;">
                                </span>
                           </li>

                           <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Premiação:
                                </span>
                               <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" disabled name="premiacao" id="premiacao" value="{{$total_premiacao}}"
                                           class="salario_usuario bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md premiacao_usuario"
                                           style="text-align:right; height:20px; font-size:0.8em; width: 100%;">
                                </span>
                           </li>

                           <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Estorno:
                                </span>
                               <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" disabled id="valor_total_desconto_geral" value="{{$total_estorno}}" name="estorno_geral" id="estorno_geral"
                                           class="salario_usuario bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md estorno_usuario"
                                           style="text-align:right; height:20px; font-size:0.8em; width: 100%;">
                                </span>
                           </li>

                           <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Desconto:
                                </span>
                               <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" disabled id="valor_total_desconto" value="{{$total_desconto}}" name="desconto" id="desconto"
                                           class="desconto_usuario bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md estorno_usuario"
                                           style="text-align:right; height:20px; font-size:0.8em; width: 100%;">
                                </span>
                           </li>

                           <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Total:
                                </span>
                               <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" disabled name="total_campo" value="{{$total_mes}}" id="total_campo"
                                           class="desconto_usuario bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md total_campo"
                                           style="text-align:right; height:20px; font-size:0.8em; width: 100%;">
                                </span>
                           </li>


                       </ul>

                       <div class="w-full bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded my-2 p-1">
                           <div style="display:flex;justify-content:space-between;align-items:center;">
                               <p style="font-size:0.8em;color:#FFF;">Confirmado(!)</p>
                               <div id="criar_excel" style="width:10%; height:10%; padding:2px;background-color:white;">
                                   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="small-svg">
                                       <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                   </svg>
                               </div>
                           </div>
                           <ul style="margin:0 0 0 0;padding:0;">
                               <li style="display:flex;justify-content: space-between;" data-plano="1" id="listar_individual_apto_total">
                                   <span style="display:flex;flex-basis:50%;font-size:0.68em;margin-left:2px;">Individual</span>
                                   <span style="display:flex;flex-basis:10%;font-size:0.68em;" id="total_quantidade_individual_total">{{$total_individual_quantidade}}</span>
                                   <span style="display:flex;flex-basis:40%;justify-content:flex-end;font-size:0.68em;margin-right:2px;"><span id="valor_total_individual_total">{{$total_individual}}</span></span>
                               </li>

                               <li style="display:flex;justify-content: space-between;" data-plano="3" id="listar_coletivo_apto_total">
                                   <span style="display:flex;flex-basis:50%;font-size:0.68em;margin-left:2px;">Coletivo</span>
                                   <span style="display:flex;flex-basis:10%;font-size:0.68em;" id="total_quantidade_coletivo_total">{{$total_coletivo_quantidade}}</span>
                                   <span style="display:flex;flex-basis:40%;justify-content:flex-end;font-size:0.68em;margin-right:2px;"><span id="valor_total_coletivo_total">{{$total_coletivo}}</span></span>
                               </li>

                               <li style="display:flex;justify-content: space-between;" data-plano="0" id="listar_empresarial_apto_total">
                                   <span style="display:flex;flex-basis:50%;font-size:0.68em;margin-left:2px;">Empresarial</span>
                                   <span style="display:flex;flex-basis:10%;font-size:0.68em;" id="total_quantidade_empresarial_total">{{$total_empresarial_quantidade}}</span>
                                   <span style="display:flex;flex-basis:40%;justify-content:flex-end;font-size:0.68em;margin-right:2px;"><span id="valor_total_empresarial_total">{{$total_empresarial}}</span></span>
                               </li>

                           </ul>
                       </div>

                       <div id="list_user" class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
                           <p style="color:white;border-bottom:1px solid white;text-align: center;margin:0;padding: 0;font-size:0.7em;">Corretores</p>
                           <ul style="list-style:none;margin:0;padding:0;" class="w-100">
                               @php
                                   $iix = 0;
                               @endphp
                               @foreach($users_apto_apagar as $uu)
                                   @php  $iix++; @endphp
                                   <li class="flex justify-between text-white w-100 py-1 {{ $iix % 2 == 0 ? 'user_destaque_impar' : '' }}">
                                   <span class="user_nome user_destaque" data-id="{{ $uu->user_id }}">
                                       @php
                                           echo Illuminate\Support\Str::limit($uu->user,20,"");
                                       @endphp
                                   </span>
                                       <span class="user_total total_pagamento_finalizado user_destaque" data-id="{{ $uu->user_id }}">{{ number_format($uu->total, 2, ",", ".") }}</span>
                                   </li>
                               @endforeach
                           </ul>

                       </div>


                   </div>

                   <div style="display:flex;flex-basis:48%;flex-direction:column;">

                       <select name="escolher_vendedor" id="escolher_vendedor"
                               class="form-control form-control-sm mb-1 w-full border border-gray-300 text-gray-700 text-sm rounded-md tamanho_de_25" {{$status_disabled ? 'disabled' : ''}}>
                           <option value="" class="text-center">--Corretores--</option>
                           @foreach($users as $u)
                               <option value="{{$u->id}}" data-name="{{$u->name}}">{{$u->name}}</option>
                           @endforeach
                           <option value="00">--Finalizar--</option>
                       </select>

                       <ul style="margin:0;padding:0;width:100%;" class="w-full flex flex-col bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">

                           <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Salario:
                                </span>
                               <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" disabled name="salario_vendedor" id="salario_vendedor" value=""
                                           class="salario_usuario_vendedor bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md"
                                           style="text-align:right; height:20px; font-size:0.8em; width: 100%;">
                                </span>
                           </li>

                           <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Comissão:
                                </span>
                               <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" disabled name="comissao_vendedor" id="comissao_vendedor" value=""

                                           class="salario_usuario_vendedor bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md"

                                           style="text-align:right; height:20px; font-size:0.8em; width: 100%;">
                                </span>
                           </li>

                           <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Premiação:
                                </span>
                               <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" name="premiacao_vendedor" id="premiacao_vendedor"
                                           class="premiacao_usuario_vendedor bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md"
                                           style="text-align:right; height:20px; font-size:0.8em;width: 100%;">
                                </span>
                           </li>

                           <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Estorno:
                                </span>
                               <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" id="valor_total_estorno_vendedor" name="estorno_vendedor"
                                           class="estorno_usuario_vendedor bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md estorno_usuario_vendedor"
                                           style="text-align:right; height:20px; font-size:0.8em;width: 100%;">
                                </span>
                           </li>

                           <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Desconto:
                                </span>
                               <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" id="valor_total_desconto_vendedor" name="desconto_vendedor"
                                           class="desconto_usuario_vendedor bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md"
                                           style="text-align:right; height:20px; font-size:0.8em;width: 100%;">
                                </span>
                           </li>

                           <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">Total:</span>
                               <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                <input type="text" disabled name="total_campo_vendedor" id="total_campo_vendedor"
                                       class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md total_campo_vendedor"
                                       style="text-align:right; height:20px; font-size:0.8em;width: 100%;">
                                </span>
                           </li>

                       </ul>


                       <div style="margin-top:2px;margin-bottom:2px;" class="w-full bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
                           <span style="justify-content:center;display:flex;font-size:0.7em;color:white;border-bottom:1px solid white;">Confirmados(?)</span>
                           <ul style="margin:0 0 0 0;padding:0;" id="lista_apto_a_pagar_ul">
                               <li style="display:flex;justify-content: space-between;" id="listar_individual_apto">
                                   <span style="display:flex;flex-basis:60%;font-size:0.68em;margin-left:2px;">Individual</span>
                                   <span style="display:flex;flex-basis:10%;font-size:0.68em;" id="total_quantidade_individual">0</span>
                                   <span style="display:flex;flex-basis:30%;justify-content:flex-end;font-size:0.68em;margin-right:2px;"><span id="valor_total_individual">0</span></span>
                               </li>
                               <li style="display:flex;justify-content: space-between;" id="listar_coletivo_apto">
                                   <span style="display:flex;flex-basis:60%;font-size:0.68em;margin-left:2px;">Coletivo</span>
                                   <span style="display:flex;flex-basis:10%;font-size:0.68em;" id="total_quantidade_coletivo">0</span>
                                   <span style="display:flex;flex-basis:30%;justify-content:flex-end;font-size:0.68em;margin-right:2px;"><span id="valor_total_coletivo">0</span></span>
                               </li>
                               <li style="display:flex;justify-content: space-between;" id="listar_empresarial_apto">
                                   <span style="display:flex;flex-basis:60%;font-size:0.68em;margin-left:2px;">Empresarial</span>
                                   <span style="display:flex;flex-basis:10%;font-size:0.68em;" id="total_quantidade_empresarial">0</span>
                                   <span style="display:flex;flex-basis:30%;justify-content: flex-end;font-size:0.68em;margin-right:2px;"><span id="valor_total_empresarial">0</span></span>
                               </li>
                           </ul>
                       </div>

                       <div style="border-radius:5px;margin:2px 0;" class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
                           <p class="border-bottom text-center" style="margin:0;padding: 0;color: white;font-size:0.7em">Confirmar(?)</p>
                           <ul style="margin:0 0 0 0;padding:0;list-style:none;" class="listar listar_a_receber_ul">
                               <li style="display:flex;font-size:0.68em;color:#FFF;margin-left:2px;display:flex;justify-content: space-between;align-items:center;" class="individual_a_receber">
                                   <span>Individual</span>
                                   <span class="valor_individual_a_receber valores_em_destaque">0</span>
                               </li>
                               <li style="display:flex;font-size:0.68em;color:#FFF;margin-left:2px;display:flex;justify-content: space-between;align-items:center;" class="coletivo_a_receber">
                                   <span>Coletivo</span>
                                   <span class="valor_coletivo_a_receber valores_em_destaque">0</span>
                               </li>
                               <li style="display:flex;font-size:0.68em;color:#FFF;margin-left:2px;display:flex;justify-content: space-between;align-items:center;" class="empresarial_a_receber">
                                   <span>Empresarial</span>
                                   <span class="valor_empresarial_a_receber valores_em_destaque">0</span>
                               </li>
                           </ul>
                       </div>




                       <div style="border-radius:5px;margin:2px 0;" class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
                           <p class="border-bottom text-center" style="margin:0;padding: 0;color: white;font-size:0.7em;">Estorno</p>
                           <ul style="margin:0 0 0 0;padding:0;list-style:none;" class="listar_estorno_ul">
                               <li style="font-size:0.68em;display:flex;color:#FFF;margin-left:2px;justify-content: space-between;align-items:center;" class="individual_estorno_receber">
                                   <span>Individual</span>
                                   <span class="quantidade_estorno_individual valores_em_destaque">0</span>
                               </li>
                               <li style="font-size:0.68em;display:flex;color:#FFF;margin-left:2px;justify-content: space-between;align-items:center;" class="coletivo_estorno_receber">
                                   <span>Coletivo</span>
                                   <span class="quantidade_estorno_coletivo valores_em_destaque">0</span>
                               </li>
                               <li style="font-size:0.68em;display:flex;color:#FFF;margin-left:2px;justify-content: space-between;align-items:center;" class="empresarial_estorno_receber">
                                   <span>Empresarial</span>
                                   <span class="quantidade_estorno_empresarial valores_em_destaque">0</span>
                               </li>
                           </ul>
                       </div>

                       <div id="container_btns"></div>

                   </div>


                   <section id="footer_user" class="finalizar_mes_container" style="display:flex;flex-basis:100%;">

                   </section>

               </section>

                <section style="display:flex;flex-basis:69%;flex-grow:1;margin-left:1%;">

                    <section style="flex-basis:100%;">
                        <div style="color:#FFF;border-radius:5px;" id="tabela_aptos_a_pagar" class="dsnone">
                            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] p-2" style="border-radius:5px;">
                                <table id="tabela_aptos_a_pagar_table" class="table table-sm listaraptosapagar w-100" style="table-layout: fixed;">
                                    <thead>
                                    <tr>
                                        <th>Admin</th>
                                        <th>Data</th>
                                        <th>Cod.</th>
                                        <th>Cliente</th>
                                        <th>Parcela</th>
                                        <th>Valor</th>
                                        <th align="center">Vencimento</th>
                                        <th>Baixa</th>
                                        <th>%</th>
                                        <th>Pagar</th>
                                        <th>Desc.</th>
                                        <th>Remover(?)</th>
                                        <th>Ver</th>
                                        <th>Plano</th>
                                        <th>Corretor</th>
                                        <th>Vidas</th>
                                        <th>Parcela</th>
                                        <th>Cod.Externo</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <div style="color:#FFF;border-radius:5px;" id="tabela_principal">
                            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]" style="border-radius:5px;">
                                <table id="tabela_mes_recebidas" class="table table-sm listarcomissaomesrecebidas w-100">
                                    <thead>
                                    <tr>
                                        <th>Admin</th>
                                        <th>Data</th>
                                        <th>Cod.</th>
                                        <th>Cliente</th>
                                        <th>Parcela</th>
                                        <th>Valor</th>
                                        <th align="center">Vencimento</th>
                                        <th>Baixa</th>
                                        <th>Comissão</th>
                                        <th>%</th>
                                        <th>Pagar</th>
                                        <th>Vidas</th>
                                        <th>Desconto</th>
                                        <th>Status</th>
                                        <th>Ver</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <div style="color:#FFF;" id="listar_a_receber" class="dsnone">
                            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]" style="border-radius:5px;">
                                <table id="tabela_mes_diferente" class="table table-sm listarcomissaomesdiferente" style="table-layout: fixed;">
                                    <thead>
                                    <tr>
                                        <th>Admin</th>
                                        <th>Data</th>
                                        <th>Cod.</th>
                                        <th>Cliente</th>
                                        <th>Par.</th>
                                        <th>Valor</th>
                                        <th align="center">Venc.</th>
                                        <th>Baixa</th>
                                        <th>Porc(%)</th>
                                        <th>Pagar</th>
                                        <th style="text-align:left;">Vidas</th>
                                        <th>Desconto</th>
                                        <th>Confirmar(?)</th>
                                        <th>Ver</th>

                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <div style="color:#FFF;border-radius:5px;" id="tabela_estorno" class="dsnone">
                            <div class="p-2 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]" style="border-radius:5px;">
                                <table id="tabela_estorno_table" class="table table-sm listarestornos w-100">
                                    <thead>
                                    <tr>
                                        <th>Admin</th>
                                        <th>Cod.</th>
                                        <th>Data</th>
                                        <th>Cliente</th>
                                        <th>Parcela</th>
                                        <th>Valor</th>
                                        <th>Estorno</th>
                                        <th>Ação</th>
                                        <th>Ver</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>



                        <div style="color:#FFF;" id="listar_cadastrados" class="dsnone">
                            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]" style="border-radius:5px;">
                                <table id="tabela_cadastrados" class="table table-sm listarcadastrados" >
                                    <thead>
                                    <tr>
                                        <th>Admin</th>
                                        <th>Cliente</th>
                                        <th>Corretor</th>
                                        <th>Plano</th>
                                        <th>Ver</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>


                        <div style="color:#FFF;border-radius:5px;" id="tabela_estorno_back" class="dsnone">
                            <div class="p-2 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]" style="border-radius:5px;">
                                <table id="tabela_estorno_table_back" class="table table-sm listarestornosback w-100">
                                    <thead>
                                    <tr>
                                        <th>Admin</th>
                                        <th>Plano</th>
                                        <th>Cod.</th>
                                        <th>Data</th>
                                        <th>Cliente</th>
                                        <th>Parcela</th>
                                        <th>Valor</th>
                                        <th>Estorno</th>
                                        <th>Ação</th>
                                        <th style="text-align:center;">Ver</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </section>

                </section>
           </main>

        <main id="aba_historico" class="ocultar aba_historico_container">
            <section style="display:flex;flex-basis:24%;justify-content: space-between;">
                <div style="display:flex;flex-basis:48%;flex-direction:column;">

                    <select name="ano_folha_historico" id="ano_folha_historico" class="form-control form-control-sm mb-1 w-full border border-gray-300 text-gray-700 text-sm rounded-md tamanho_de_25 mb-1">
                        <option value="" class="text-center">--Ano--</option>

                        <option value="2024">2024</option>
                    </select>

                    <select name="mes_folha_historico" disabled id="mes_folha_historico" class="form-control form-control-sm mb-1 w-full border border-gray-300 text-gray-700 text-sm rounded-md tamanho_de_25 mb-1">
                        <option value="" class="text-center">--Mês--</option>
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


                    <ul style="margin:0;padding:0;" class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
                        <li style="display:flex;justify-content: space-between;">
                            <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;color:#FFF;">Salario:</span>
                            <span style="display:flex;flex-basis:50%;">
                                <input type="text" disabled name="salario_historico" id="salario_historico" value="" class="salario_usuario_historico bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md"  style="text-align:right; height:20px; font-size:0.8em; width: 100%;">
                            </span>
                        </li>

                        <li style="display:flex;justify-content: space-between;margin:2px 0;">
                            <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;color:#FFF;">
                                Comissão:
                            </span>
                            <span style="display:flex;flex-basis:50%;">
                                <input type="text" name="comissao_historico" id="comissao_historico" value="" class="form-control form-control-sm bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md" readonly placeholder="Comissão" value="0" style="text-align:right;height:20px;font-size:0.8em;width:100%;">
                            </span>
                        </li>

                        <li style="display:flex;justify-content: space-between;margin:2px 0;">
                                <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;color:#FFF;">
                                    Premiação:
                                </span>
                                <span style="display:flex;flex-basis:50%;">
                                    <input type="text" disabled name="premiacao_historico" id="premiacao_historico" value="" class="form-control form-control-sm bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md premiacao_usuario" style="text-align:right;height:20px;font-size:0.8em;width:100%;">
                                </span>
                        </li>

                        <li style="display:flex;justify-content: space-between;margin:2px 0;">
                            <span style="display:flex;flex-basis:50%;align-self: center;color:#FFF;font-size:0.7em;">
                                    Estorno:
                            </span>
                            <span style="display:flex;flex-basis:50%;">
                                 <input type="text" disabled value="" name="estorno_geral_historico" id="estorno_geral_historico" class="form-control form-control-sm bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md estorno_usuario_historico" style="text-align:right;height:20px;font-size:0.8em;width:100%;">
                            </span>
                        </li>

                        <li style="display:flex;justify-content: space-between;margin:2px 0;">
                                <span style="display:flex;flex-basis:50%;align-self: center;color:#FFF;font-size:0.7em;">
                                    Desconto:
                                </span>
                            <span style="display:flex;flex-basis:50%;">
                                    <input type="text" disabled id="valor_total_desconto_historico" value="" name="desconto_historico" class="form-control form-control-sm bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md desconto_usuario_historico" style="text-align:right;height:20px;font-size:0.8em;width:100%;">
                                </span>
                        </li>

                        <li style="display:flex;justify-content: space-between;">
                                <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;height:20px;color:#FFF;">
                                    Total:
                                </span>
                            <span style="display:flex;flex-basis:50%;">
                                    <input type="text" disabled name="total_campo_historico" value="" id="total_campo_historico" class="form-control form-control-sm bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md total_campo_historico" style="text-align:right;height:20px;font-size:0.8em;width:100%;">
                                </span>
                        </li>
                    </ul>


                    <div style="margin-top:2px;margin-bottom:2px;" class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
                        <p style="color:white;border-bottom:1px solid white;margin:0;padding: 0;display:flex;">
                            <span style="flex-basis:90%;justify-content:center;display:flex;font-size:0.7em;">Planos</span>
                            <a style="flex-basis:10%;font-size:0.7em;" id="criar_excel_historico" href="">
                                <i class="fas fa-download fa-sm text-white"></i>
                            </a>
                        </p>
                        <ul style="margin:0 0 0 0;padding:0;">
                            <li style="display:flex;justify-content: space-between;" data-plano="1" id="listar_individual_apto_total_historico">
                                <span style="display:flex;flex-basis:50%;font-size:0.68em;margin-left:2px;">Individual</span>
                                <span style="display:flex;flex-basis:10%;font-size:0.68em;" id="total_quantidade_individual_total_historico">0</span>
                                <span style="display:flex;flex-basis:40%;justify-content:flex-end;font-size:0.68em;margin-right:2px;"><span id="valor_total_individual_total_historico">R$ 0,00</span></span>
                            </li>
                            <li style="display:flex;justify-content: space-between;" data-plano="3" id="listar_coletivo_apto_total_historico">
                                <span style="display:flex;flex-basis:50%;font-size:0.68em;margin-left:2px;">Coletivo</span>
                                <span style="display:flex;flex-basis:10%;font-size:0.68em;" id="total_quantidade_coletivo_total_historico">0</span>
                                <span style="display:flex;flex-basis:40%;justify-content:flex-end;font-size:0.68em;margin-right:2px;"><span id="valor_total_coletivo_total_historico">R$ 0,00</span></span>
                            </li>
                            <li style="display:flex;justify-content: space-between;" data-plano="0" id="listar_empresarial_apto_total_historico">
                                <span style="display:flex;flex-basis:50%;font-size:0.68em;margin-left:2px;">Empresarial</span>
                                <span style="display:flex;flex-basis:10%;font-size:0.68em;" id="total_quantidade_empresarial_total_historico">0</span>
                                <span style="display:flex;flex-basis:40%;justify-content:flex-end;font-size:0.68em;margin-right:2px;"><span id="valor_total_empresarial_total_historico">R$ 0,00</span></span>
                            </li>
                        </ul>
                    </div>


                    <div id="list_user_historico" class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
                        <p style="color:white;border-bottom:1px solid white;text-align: center;margin:0;padding: 0;font-size:0.7em;">Corretores</p>
                        <ul style="list-style:none;margin:0;padding:0;" class="w-100">
                        </ul>
                    </div>

                    <div>
                        <button class="btn btn-block btn-sm btn-danger mt-1 estorno_geral_historico_button dsnone">Estorno Geral</button>
                    </div>
                </div>

                <div style="display:flex;flex-basis:48%;flex-direction:column;">

                    <div style="padding:3px;">
                        <select name="escolher_vendedor_vendedor" id="escolher_vendedor_historico" class="form-control form-control-sm mb-1 w-full border border-gray-300 text-gray-700 text-sm rounded-md tamanho_de_25 mb-1 w-full" {{$status_disabled ? 'disabled' : ''}}>
                            <option value="" class="text-center">--Corretores--</option>

                        </select>
                        <div style="border-top:1px solid white;margin-bottom:2px;"></div>
                        <ul style="margin:0;padding:0;" class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
                            <li style="display:flex;justify-content: space-between;">
                                <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;color:#FFF;">
                                    Salario:
                                </span>
                                <span style="display:flex;flex-basis:50%;">
                                    <input type="text" name="salario_vendedor_historico" id="salario_vendedor_historico" class="form-control form-control-sm bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md salario_usuario_vendedor" style="text-align:right;height:20px;font-size:0.8em;width:100%;">
                                </span>
                            </li>

                            <li style="display:flex;justify-content: space-between;margin:2px 0;">
                                <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;color:#FFF;">
                                    Comissão:
                                </span>
                                <span style="display:flex;flex-basis:50%;">
                                    <input type="text" name="comissao_vendedor_historico" id="comissao_vendedor_historico" class="form-control form-control-sm bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md" readonly placeholder="Comissão" style="text-align:right;height:20px;font-size:0.8em;width:100%;">
                                </span>
                            </li>

                            <li style="display:flex;justify-content: space-between;margin:2px 0;">
                                <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;color:#FFF;">
                                    Premiação:
                                </span>
                                <span style="display:flex;flex-basis:50%;">
                                    <input type="text" name="premiacao_vendedor_historico" id="premiacao_vendedor_historico" class="form-control form-control-sm bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md premiacao_usuario_vendedor" style="text-align:right;height:20px;font-size:0.8em;width:100%;">
                                </span>
                            </li>

                            <li style="display:flex;justify-content: space-between;margin:2px 0;">
                                <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;color:#FFF;">
                                    Estorno:
                                </span>
                                <span style="display:flex;flex-basis:50%;">
                                    <input type="text" disabled id="valor_total_estorno_vendedor_historico" name="estorno_vendedor_historico" class="form-control form-control-sm bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md estorno_usuario_vendedor" style="text-align:right;height:20px;font-size:0.8em;width:100%;">
                                </span>
                            </li>

                            <li style="display:flex;justify-content: space-between;margin:2px 0;">
                            <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;color:#FFF;">
                                Desconto:
                            </span>
                                <span style="display:flex;flex-basis:50%;">
                                <input type="text" disabled id="valor_total_desconto_vendedor_historico" name="desconto_vendedor_historico" class="form-control form-control-sm bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md desconto_usuario_vendedor" style="text-align:right;height:20px;font-size:0.8em;width:100%;">
                            </span>
                            </li>

                            <li style="display:flex;justify-content: space-between;">
                            <span style="display:flex;flex-basis:50%;align-self: center;font-size:0.7em;height:20px;color:#FFF;">
                            Total:
                            </span>
                                <span style="display:flex;flex-basis:50%;">
                                <input type="text" disabled name="total_campo_vendedor_historico" id="total_campo_vendedor_historico" class="form-control form-control-sm bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md total_campo_vendedor" style="text-align:right;height:20px;font-size:0.8em;width:100%;">
                            </span>
                            </li>
                        </ul>
                    </div>

                    <div style="margin-top:2px;" class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
                        <span style="justify-content:center;display:flex;font-size:0.7em;color:white;border-bottom:1px solid white;">Planos</span>
                        <ul style="margin:0 0 0 0;padding:0;" id="lista_apto_a_pagar_ul_historico">
                            <li style="display:flex;justify-content: space-between;" id="listar_individual_apto_historico">
                                <span style="display:flex;flex-basis:60%;font-size:0.68em;margin-left:2px;">Individual</span>
                                <span style="display:flex;flex-basis:10%;font-size:0.68em;" id="total_quantidade_individual_historico">0</span>
                                <span style="display:flex;flex-basis:30%;justify-content:flex-end;font-size:0.68em;margin-right:2px;"><span id="valor_total_individual_historico">0</span></span>
                            </li>
                            <li style="display:flex;justify-content: space-between;" id="listar_coletivo_apto_historico">
                                <span style="display:flex;flex-basis:60%;font-size:0.68em;margin-left:2px;">Coletivo</span>
                                <span style="display:flex;flex-basis:10%;font-size:0.68em;" id="total_quantidade_coletivo_historico">0</span>
                                <span style="display:flex;flex-basis:30%;justify-content:flex-end;font-size:0.68em;margin-right:2px;"><span id="valor_total_coletivo_historico">0</span></span>
                            </li>
                            <li style="display:flex;justify-content: space-between;" id="listar_empresarial_apto_historico">
                                <span style="display:flex;flex-basis:60%;font-size:0.68em;margin-left:2px;">Empresarial</span>
                                <span style="display:flex;flex-basis:10%;font-size:0.68em;" id="total_quantidade_empresarial_historico">0</span>
                                <span style="display:flex;flex-basis:30%;justify-content: flex-end;font-size:0.68em;margin-right:2px;"><span id="valor_total_empresarial_historico">0</span></span>
                            </li>
                        </ul>
                    </div>




                    <div style="border-radius:5px;margin:5px 0 0 0;" id="footer_user_historico">

                    </div>








                </div>


            </section>

            <section style="display:flex;flex-basis:75%;">

                <section style="flex-basis:100%;">
                    <div style="color:#FFF;border-radius:5px;" id="tabela_aptos_a_pagar_historico" class="dsnone">
                        <div class="p-2 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]" style="border-radius:5px;">
                            <table id="tabela_aptos_a_pagar_table_historico" class="table table-sm listaraptosapagarhistorico w-100" style="table-layout: fixed;">
                                <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Data</th>
                                    <th>Cod.</th>
                                    <th>Cliente</th>
                                    <th>Parcela</th>
                                    <th>Valor</th>
                                    <th align="center">Vencimento</th>
                                    <th>Baixa</th>
                                    <th>Pagar</th>
                                    <th>Desc.</th>

                                    <th>Ver</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div style="color:#FFF;border-radius:5px;" id="tabela_principal_historico">
                        <div class="p-2 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]" style="border-radius:5px;">
                            <table id="tabela_mes_recebidas_historico" class="table table-sm listarcomissaomesrecebidashistorico w-100">
                                <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Data</th>
                                    <th>Cod.</th>
                                    <th>Cliente</th>
                                    <th>Parcela</th>
                                    <th>Valor</th>
                                    <th align="center">Vencimento</th>
                                    <th>Baixa</th>
                                    <th>Comissão</th>
                                    <th>%</th>
                                    <th>Pagar</th>
                                    <th>Vidas</th>
                                    <th>Desconto</th>
                                    <th>Status</th>
                                    <th>Ver</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div style="color:#FFF;" id="listar_a_receber_historico" class="dsnone">
                        <div class="p-2 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]" style="border-radius:5px;">
                            <table id="tabela_mes_diferente_historico" class="table table-sm listarcomissaomesdiferentehistorico" style="table-layout: fixed;">
                                <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Data</th>
                                    <th>Cod.</th>
                                    <th>Cliente</th>
                                    <th>Par.</th>
                                    <th>Valor</th>
                                    <th align="center">Venc.</th>
                                    <th>Baixa</th>
                                    <th>Porc(%)</th>
                                    <th>Pagar</th>
                                    <th style="text-align:left;">Vidas</th>
                                    <th>Desconto</th>
                                    <th>Confirmar(?)</th>
                                    <th>Ver</th>

                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div style="color:#FFF;border-radius:5px;" id="tabela_estorno_historico" class="dsnone">
                        <div class="p-2 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]" style="border-radius:5px;">
                            <table id="tabela_estorno_table_historico" class="table table-sm listarestornoshistorico w-100">
                                <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Cod.</th>
                                    <th>Data</th>
                                    <th>Cliente</th>
                                    <th>Parcela</th>
                                    <th>Valor</th>
                                    <th>Estorno</th>
                                    <th>Ação</th>
                                    <th>Ver</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>


                    <div style="color:#FFF;border-radius:5px;" id="tabela_estorno_back_historico" class="dsnone">
                        <div class="p-2 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]" style="border-radius:5px;">
                            <table id="tabela_estorno_table_back_historico" class="table table-sm listarestornosbackhistorico w-100">
                                <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Plano</th>
                                    <th>Cod.</th>
                                    <th>Data</th>
                                    <th>Cliente</th>
                                    <th>Parcela</th>
                                    <th>Valor</th>
                                    <th>Estorno</th>
                                    <th style="text-align:center;">Ver</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </section>
        </main>

    </section>

    <!-- Modal -->
    <div class="hidden" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Finalizar o Folha</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <p class="flex">
                        <span class="flex" style="flex-basis:20%;">Salario:</span>
                        <span class="flex salario_usuario_modal" style="flex-basis:70%;"></span>
                    </p>
                    <p class="flex">
                        <span class="flex" style="flex-basis:20%;">Comissão:</span>
                        <span class="flex comissao_usuario_modal"></span>
                    </p>
                    <p class="flex">
                        <span class="flex" style="flex-basis:20%;">Premiação:</span>
                        <span class="flex premiacao_usuario_modal"></span>
                    </p>

                    <p class="flex">
                        <span class="flex" style="flex-basis:20%;">Estorno:</span>
                        <span class="flex estorno_usuario_modal"></span>
                    </p>


                    <p class="flex">
                        <span class="flex" style="flex-basis:20%;">Desconto:</span>
                        <span class="flex desconto_usuario_modal"></span>
                    </p>
                    <p class="flex">
                        <span class="flex" style="flex-basis:20%;">Total:</span>
                        <span class="flex total_a_pagar_modal"></span>
                    </p>

                </div>
                <div class="modal-footer" style="display:flex;justify-content: center;">
                    <button type="button" class="btn btn-primary btn_usuario" data-dismiss="modal">Criar o PDF</button>
                </div>
            </div>
        </div>
    </div>




    <!-- Modal com fundo transparente -->
    <div id="myModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" tabindex="-1" role="dialog">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-6xl p-6">
            <!-- Modal Header -->
            <div class="modal-header flex justify-between items-center mb-4">
                <h5 class="modal-title text-xl font-semibold text-center">Resumo do Fechamento do Mês</h5>
                <button type="button" class="closeFecharMes text-gray-600 hover:text-gray-800" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div id="resultado_tabela">
                    <div class="loading-dots">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>

                </div>
                <div id="errorMessage" class="mt-2 text-red-600 font-bold text-center"></div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer flex justify-end">
                <button id="confirmBtn" style="background-color:green;color:#FFF;" class="focus:outline-none text-white w-full bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5">Fechar Mês</button>
            </div>
        </div>
    </div>




    <!-- MODAL HISTORICO -->



    <div class="hidden" id="exampleModalTipoPlanosHistoricoCorretora" tabindex="-1" aria-labelledby="exampleModalLabelTipoPlanosHistoricoCorretora" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabelTipoPlanosHistoricoCorretora">Planos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <input type="checkbox" name="planos_tipo_individual_historico" id="planos_tipo_individual_historico" checked> Individual
                </div>
                <div>
                    <input type="checkbox" name="planos_tipo_coletivo_historico" id="planos_tipo_coletivo_historico" checked> Coletivo
                    <div class="w-75 ml-4">
                        @foreach($administradoras_coletivo as $a)
                            <div>
                                <input type="checkbox" name="coletivo_historico" data-administradora="{{$a->id}}" id="historico_{{$a->nome}}" checked/>{{$a->nome}}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <input type="checkbox" name="planos_tipo_empresarial_historico" id="planos_tipo_empresarial_historico" checked> Empresarial
                    <div class="w-75 ml-4">
                        @foreach($planos_empresarial as $pe)
                            <div>
                                <input type="checkbox" name="empresarial_historico" data-planos="{{$pe->id}}" id="{{$pe->nome}}_historico" checked/>{{$pe->nome}}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 gerar_pdf_corretora_link_historico">Gerar PDF</button>
            </div>
            </div>
        </div>
    </div>







    <!-- FIM MODAL HISTORICO -->


    <!-- Overlay e Modal -->
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" id="exampleModalTipoPlanos" tabindex="-1" aria-labelledby="exampleModalLabelTipoPlanos" aria-hidden="true">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-xl font-semibold" id="exampleModalLabel">Planos</h5>
                <button type="button" class="text-gray-600 hover:text-gray-800" id="closeModalTipoPlanos">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div>
                    <input type="checkbox" name="planos_tipo_individual" id="planos_tipo_individual" checked> Individual
                </div>
                <div>
                    <input type="checkbox" name="planos_tipo_coletivo" id="planos_tipo_coletivo" checked> Coletivo
                </div>
                <div>
                    <input type="checkbox" name="planos_tipo_empresarial" id="planos_tipo_empresarial" checked> Empresarial
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end mt-4">
                <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md gerar_pdf_corretor_link hover:bg-blue-700">Gerar PDF</button>
            </div>
        </div>
    </div>






    <!-- Modal -->
    <!-- Overlay e Modal -->
    <div class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden" id="exampleModalTipoPlanosCorretora">
        <!-- Modal -->
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-xl font-semibold" id="exampleModalLabelTipoPlanosCorretora">Planos</h5>
                <button type="button" class="text-gray-600 hover:text-gray-800" id="closeModalCorretora">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div>
                    <input type="checkbox" name="planos_tipo_individual_corretora" id="planos_tipo_individual_corretora" checked> Individual
                </div>
                <div>
                    <input type="checkbox" name="planos_tipo_coletivo_corretora" id="planos_tipo_coletivo_corretora" checked> Coletivo
                </div>
                <div>
                    <input type="checkbox" name="planos_tipo_empresarial_corretora" id="planos_tipo_empresarial_corretora" checked> Empresarial
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end mt-4">
                <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md gerar_pdf_corretora_link hover:bg-blue-700">Gerar PDF</button>
            </div>
        </div>
    </div>












@section('scripts')
    <script src="{{asset('js/jquery.mask.min.js')}}"></script>

    <script>

        $(function(){

            $("body").on("click",'#closeModalIndividual',function(){
               $("#myModalIndividual").addClass("hidden");
            });
            $("body").on("click", "#myModalIndividual", function (event) {
                if(!$(event.target).closest(".content-modal-individual").length) {
                    $("#myModalIndividual").addClass("hidden");
                }
            });

            $("body").on("click","#closeModalColetivo",function(){
                $("#myModalColetivo").addClass("hidden");
            });



            $("body").on("click", "#myModalColetivo", function (event) {
                if(!$(event.target).closest(".content-modal-coletivo").length) {
                    $("#myModalColetivo").addClass("hidden");
                }
            });



            $("#planos_tipo_coletivo").change(function() {
                $("input[name='coletivo']").prop("checked", $(this).prop("checked"));
            });

            $("#planos_tipo_empresarial").change(function() {
                $("input[name='empresarial']").prop("checked", $(this).prop("checked"));
            });

            $("#planos_tipo_coletivo_corretora").change(function() {
                $("input[name='coletivo_corretora']").prop("checked", $(this).prop("checked"));
            });

            $("#planos_tipo_empresarial_corretora").change(function() {
                $("input[name='empresarial_corretora']").prop("checked", $(this).prop("checked"));
            });

            var url_padrao = "{{asset('data.json')}}";
            $(".estorno_geral_historico_button").on('click',function(){
                let mes = $("#mes_folha_historico option:selected").val();

                if($("#tabela_principal_historico").is(":visible")) {
                    $("#tabela_principal_historico").slideUp(1000,function(){
                        $("#tabela_estorno_back_historico").slideDown('slow',function(){
                            $('#title_estorno_back').html("<h4>Estorno Geral</h4>");
                            listarestornosbackhistorico.ajax.url(`{{ url('/gerente/mes/geral/estorno/${mes}') }}`).load();
                        });
                    });
                } else {
                    $('#title_estorno_back').html("<h4>Estorno Geral</h4>");
                    listarestornosbackhistorico.ajax.url(`{{ url('/gerente/mes/geral/estorno/${mes}') }}`).load();
                }
            });

            var listarestornos = $(".listarestornos").DataTable({
                dom: '<"flex justify-between"<"#title_estorno_confirmados"><"estilizar_search"f>><t><"flex justify-between items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                language: {
                    "search": "Pesquisar",
                    "paginate": {
                        "next": "Próx.",
                        "previous": "Ant.",
                        "first": "Primeiro",
                        "last": "Último"
                    },
                    "emptyTable": "Nenhum registro encontrado",
                    "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "infoFiltered": "(Filtrados de _MAX_ registros)",
                    "infoThousands": ".",
                    "loadingRecords": "Carregando...",
                    "processing": "Processando...",
                    "lengthMenu": "Exibir _MENU_ por página"
                },
                ajax: {
                    url:url_padrao,
                    dataSrc:""
                },
                "lengthMenu": [50,100,150,200,300,500],
                // "lengthMenu": [1,2,3,4,5,6],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                columns: [
                    {data:"administradora",name:"administradora",width:"4%"},
                    {data:"data",name:"data",width:"2%"},
                    {data:"codigo",name:"codigo",width:"2%"},
                    {data:"cliente",name:"cliente",width:"25%"},
                    {data:"parcela",name:"parcela",width:"1%",className: 'dt-center'},
                    {data:"valor",name:"valor",width:"8%",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center'},
                    {data:"total_estorno",width:"5%",className: 'dt-center total_estorno',render: $.fn.dataTable.render.number('.',',',2,'R$ ')},
                    {
                        data: "id", name: "id", width: "5%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let selected = $('<select />', {
                                name: 'confirmar_estorno',
                                class: "confirmar_estorno",
                                id: cellData,
                                'data-plano':rowData.plano,
                                'data-comissao':rowData.comissoes_id,
                                'data-lancadas':rowData.id_lancadas,
                                // append: [
                                //     $('<option />', {value: "1", text: "---",className:"dt-center"}).css('color', 'black');
                                //     $('<option />', {value: "2", text: "Estornar"}).css('color', 'black');
                                // ]
                            }).css({
                                "width": "80px",  // Defina a largura desejada
                                "height": "30px", // Defina a altura desejada
                                "font-size": "12px", // Ajuste o tamanho da fonte
                                "padding": "2px" // Ajuste o espaçamento interno
                            });
                            selected.append($('<option />', {value: "1", text: "---"}).css('color', 'black'));
                            selected.append($('<option />', {value: "2", text: "Estornar"}).css('color', 'black'));
                            $(td).html(selected)
                        }
                    },
                    {data:"id",name:"id",width:"5%",
                    "createdCell": function (td, cellData, rowData, row, col) {
                        let contrato_id = cellData;
                        $(td).html(`<div class='text-center text-white'>
                                    <a href="/financeiro/detalhes/coletivo/${contrato_id}" target="_blank" class="text-white">
                                        <i class='fas fa-eye'></i>
                                    </a>
                                </div>
                            `);
                    }},


            ],
                "initComplete": function( settings, json ) {
                    $('#title_estorno_confirmados').html("<h4>Estorno Individual</h4>");
                },
                footerCallback: function (row, data, start, end, display) {

                }
            });

            //var table_aba_historico_search = $('#tabelaResultados').DataTable();

            $("body").on('change','.confirmar_estorno',function(){
                let id = $(this).attr('id');
                let id_parcela = $(this).attr('data-lancadas');
                let mes = $("#mes_folha option:selected").val();
                let ano = $("#mes_folha").find('option:selected').text().split("/")[1];
                let user_id = $("#corretor_escolhido").val();
                let linha = $(this).closest('tr');
                //let plano = $(this).attr('data-plano');
                //let comissao = $(this).attr('data-comissao');
                let estorno = linha.find('.total_estorno').text().replace("R$","").replace(/\./g,'').replace(',', '.').trim();
                //let total_estorno = $("#valor_total_estorno_vendedor").val().replace(/\./g, '').replaceAll(',', '.').trim();
                ///let total_comissao = $("#comissao_vendedor").val().replace(/\./g, '').replaceAll(',', '.').trim();
                ///let calcular_estorno = parseFloat(total_estorno) + parseFloat(estorno);
                ///$("#valor_total_estorno_vendedor").val(calcular_estorno.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }).replace("R$",""));
                ///let total_input = $("#total_campo_vendedor").val().replace(/\./g, '').replaceAll(',', '.').trim();
                ///let total_calculado = total_input - parseFloat(estorno);
                //$("#total_campo_vendedor").val(total_calculado.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }).replace("R$",""));
                $.ajax({
                   url:"{{route('gerente.contrato.estorno')}}",
                   data:
                       "id="+id+
                       "&mes="+mes+
                       "&ano="+ano+
                       "&user_id="+user_id+
                       "&valor="+estorno+
                       // "&total="+total_calculado+
                       // "&plano="+plano+
                       // "&comissao="+comissao+
                       // "&total_comissao="+total_comissao+
                       "&id_parcela="+id_parcela,
                   method:"POST",
                   success:function(res) {
                       //$(".valor_individual_a_receber").text(res.valor_individual_a_receber);
                       //$(".valor_coletivo_a_receber").text(res.valor_coletivo_a_receber);
                       //$(".valor_empresarial_a_receber").text(res.valor_empresarial_a_receber);
                       $("#total_quantidade_individual").text(res.total_individual_quantidade);
                       $("#total_quantidade_coletivo").text(res.total_coletivo_quantidade);
                       ///$("#valor_total_individual").text(res.total_individual);
                       //$("#valor_total_coletivo").text(res.total_coletivo);
                       $(".quantidade_estorno_empresarial").text(res.total_empresarial_quantidade_estorno);
                       $(".quantidade_estorno_individual").text(res.total_individual_quantidade_estorno);
                       $("#total_quantidade_empresarial").text(res.total_empresarial_quantidade);
                       //$("#valor_total_empresarial").text(res.total_empresarial);
                       $("#comissao_vendedor").val(res.total_comissao);
                       $("#valores_confirmados").val(res.id_confirmados);
                       $("#salario_vendedor").val(res.total_salario);
                       $("#premiacao_vendedor").val(res.total_premiacao);
                       $("#valor_total_estorno_vendedor").val(res.estorno);
                       $("#valor_total_desconto_vendedor").val(res.desconto);
                       $(".quantidade_estorno_coletivo ").text(res.total_coletivo_quantidade_estorno);
                       $(".quantidade_estorno_empresarial").text(res.total_empresarial_quantidade_estorno);
                       $("#total_campo_vendedor").val(res.total);
                       $(".coletivo_a_receber").removeClass('ativo');
                       $(".individual_a_receber").removeClass('ativo');
                       $(".empresarial_a_receber").removeClass('ativo');
                       $(".individual_recebidas").removeClass('ativo');
                       $(".coletivo_recebidas").removeClass('ativo');
                       $(".empresarial_recebidas").removeClass('ativo');
                       $("#listar_individual_apto").removeClass('ativo');
                       $("#listar_coletivo_apto").removeClass('ativo');
                       $("#listar_empresarial_apto").removeClass('ativo');
                       $(".listar_estorno_ul li").removeClass('ativo');


                       ////$("#total_quantidade_individual_total").text(res.total_individual_quantidade_geral);
                       //$("#valor_total_individual_total").text(res.total_individual_geral);

                       //$("#total_quantidade_coletivo_total").text(res.total_coletivo_quantidade_geral);
                       //$("#valor_total_coletivo_total").text(res.total_coletivo_geral);

                       //$("#total_quantidade_empresarial_total").text(res.total_empresarial_quantidade_geral);
                       //$("#valor_total_empresarial_total").text(res.total_empresarial_geral);


                       $("#salario").val(res.valores_geral.salario);
                       $("#comissao").val(res.valores_geral.comissao);
                       $("#premiacao").val(res.valores_geral.premiacao);
                       $("#estorno_geral").val(res.valores_geral.estorno);
                       $("#valor_total_desconto").val(res.valores_geral.desconto);
                       $("#total_campo").val(res.valores_geral.total)


                       $("#list_user").html(res.view);
                       $("#btn_fechar_mes").html('<button id="confirmBtn" >Fechar Mês</button>');
                       const select = $("#escolher_vendedor");
                       select.html('<option value="" class="text-center">--Corretores--</option>');
                       $.each(res.usuarios, function(index, corretor) {
                           const option = $("<option>").attr("value", corretor.id).text(corretor.name);
                           if (corretor.id == $("#corretor_escolhido").val()) {
                               option.attr("selected", "selected");
                           }
                           select.append(option);
                       });
                       select.append("<option value='00'>--Finalizar--</option>");

                       listarestornos.ajax.reload();
                       // let total_a_pagar = parseFloat(res.total_comissao) - parseFloat(res.desconto)
                       if(parseFloat(res.total_comissao) > 0) {
                           $(".total_a_pagar").text(res.total);
                       } else {
                           $(".total_a_pagar").text(0);
                       }
                       // if(res != "error") {
                       //
                       //     if(res == 1) {
                       //
                       //     } else if(res == 3) {
                       //         let col = $('.quantidade_estorno_coletivo').text();
                       //         col -= 1;
                       //         $('.quantidade_estorno_coletivo').text(col);
                       //     } else {
                       //         let ind = $('.quantidade_estorno_empresarial').text();
                       //         ind -= 1;
                       //         $('.quantidade_estorno_empresarial').text(ind);
                       //     }
                       //
                       //
                       //
                            listarestornos.ajax.reload();
                       // }
                   }
                });
            });

            $(".individual_estorno_receber").on('click',function(){
                let id = $("#corretor_escolhido").val();
                $(this).addClass('ativo');

                if(id) {
                    $("#lista_apto_a_pagar_ul li").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $(".coletivo_estorno_receber").removeClass("ativo");
                    $(".empresarial_estorno_receber").removeClass("ativo");
                    if($("#tabela_estorno").is(":visible")) {
                        $("#title_estorno_confirmados").html("<h4>Estorno Individual</h4>")
                        listarestornos.ajax.url(`{{ url('/gerente/estorno/individual/${id}') }}`).load();
                    } else {
                        if($("#tabela_principal").is(":visible")) {
                            $("#tabela_principal").slideUp(1000,function(){
                                $("#tabela_estorno").slideDown('slow',function(){
                                    $("#title_estorno_confirmados").html("<h4>Estorno Individual</h4>")
                                    listarestornos.ajax.url(`{{ url('/gerente/estorno/individual/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_aptos_a_pagar").is(":visible")) {
                            $("#tabela_aptos_a_pagar").slideUp(1000,function(){
                                $("#tabela_estorno").slideDown('slow',function(){
                                    $("#title_estorno_confirmados").html("<h4>Estorno Individual</h4>")
                                    listarestornos.ajax.url(`{{ url('/gerente/estorno/individual/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_estorno_table_back").is(":visible")) {
                            $("#tabela_estorno_back").slideUp(1000,function(){
                                $("#tabela_estorno").slideDown('slow',function(){
                                    $("#title_estorno_confirmados").html("<h4>Estorno Individual</h4>");
                                    listarestornos.ajax.url(`{{ url('/gerente/estorno/individual/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_mes_diferente").is(":visible")) {
                            $("#listar_a_receber").slideUp(1000,function(){
                                $("#tabela_estorno").slideDown('slow',function(){
                                    $("#title_estorno_confirmados").html("<h4>Estorno Individual</h4>");
                                    listarestornos.ajax.url(`{{ url('/gerente/estorno/individual/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#listar_cadastrados").is(":visible")) {
                            $("#listar_cadastrados").slideUp(1000,function(){
                                $("#tabela_estorno").slideDown('slow',function(){
                                    $("#title_estorno_confirmados").html("<h4>Estorno Individual</h4>")
                                    listarestornos.ajax.url(`{{ url('/gerente/estorno/individual/${id}') }}`).load();
                                });
                            });
                        }
                    }

                } else {
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false
                    }
                }
            });

            var listarestornosbackhistorico = $(".listarestornosbackhistorico").DataTable({
                dom: '<"flex justify-between"<"#title_estorno_back"><"estilizar_search"f>><t><"flex justify-between align-items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                language: {
                    "search": "Pesquisar",
                    "paginate": {
                        "next": "Próx.",
                        "previous": "Ant.",
                        "first": "Primeiro",
                        "last": "Último"
                    },
                    "emptyTable": "Nenhum registro encontrado",
                    "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "infoFiltered": "(Filtrados de _MAX_ registros)",
                    "infoThousands": ".",
                    "loadingRecords": "Carregando...",
                    "processing": "Processando...",
                    "lengthMenu": "Exibir _MENU_ por página"
                },
                ajax: {
                    url:url_padrao,
                    dataSrc:""
                },
                "lengthMenu": [50,100,150,200,300,500],
                // "lengthMenu": [1,2,3,4,5,6],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                columns: [
                    {data:"administradora",name:"administradora",width:"4%"},
                    {data:"plano",name:"plano",width:"4%"},
                    {data:"contrato",name:"contrato",width:"2%"},
                    {data:"data",name:"data",width:"5%"},
                    {data:"cliente",name:"cliente",width:"20%"},
                    {data:"parcela",name:"parcela",width:"1%",className: 'dt-center'},
                    {data:"valor",name:"valor",width:"8%",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center'},
                    {data:"total_estorno",width:"5%",className: 'dt-center total_estorno_back',render: $.fn.dataTable.render.number('.',',',2,'R$ ')},
                    {data:"id",name:"id",width:"5%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let contrato_id = cellData;
                            $(td).html(`<div class='text-center text-white'>
                                    <a href="/financeiro/detalhes/coletivo/${contrato_id}" target="_blank" class="text-white">
                                        <i class='fas fa-eye'></i>
                                    </a>
                                </div>
                            `);
                        }},
                ],
                "initComplete": function( settings, json ) {
                    $('#title_estorno_back').html("<h4>Estorno</h4>");
                },
                footerCallback: function (row, data, start, end, display) {

                }
            });







            var listarestornosback = $(".listarestornosback").DataTable({
                dom: '<"flex justify-between"<"#title_estorno_back"><"estilizar_search"f>><t><"flex justify-between items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                language: {
                    "search": "Pesquisar",
                    "paginate": {
                        "next": "Próx.",
                        "previous": "Ant.",
                        "first": "Primeiro",
                        "last": "Último"
                    },
                    "emptyTable": "Nenhum registro encontrado",
                    "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "infoFiltered": "(Filtrados de _MAX_ registros)",
                    "infoThousands": ".",
                    "loadingRecords": "Carregando...",
                    "processing": "Processando...",
                    "lengthMenu": "Exibir _MENU_ por página"
                },
                ajax: {
                    url:url_padrao,
                    dataSrc:""
                },
                "lengthMenu": [50,100,150,200,300,500],
                // "lengthMenu": [1,2,3,4,5,6],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                columns: [
                    {data:"administradora",name:"administradora",width:"4%"},
                    {data:"plano",name:"plano",width:"4%"},
                    {data:"contrato",name:"contrato",width:"2%"},
                    {data:"data",name:"data",width:"5%"},
                    {data:"cliente",name:"cliente",width:"20%"},
                    {data:"parcela",name:"parcela",width:"1%",className: 'dt-center'},
                    {data:"valor",name:"valor",width:"8%",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center'},
                    {data:"total_estorno",width:"5%",className: 'dt-center total_estorno_back',render: $.fn.dataTable.render.number('.',',',2,'R$ ')},
                    {
                        data: "id", name: "id", width: "5%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let selected = $('<select />', {
                                name: 'estorno_back',
                                class: "estorno_back",
                                id: cellData,
                                'data-plano':rowData.plano,
                                'data-comissao':cellData,
                                'data-lancadas':rowData.id_lancadas,
                                append: [
                                    $('<option />', {value: "1", text: "---",className:"dt-center"}).css({"color":"black"}),
                                    $('<option />', {value: "2", text: "Voltar"}).css({"color":"black"}),
                                ]
                            }).css({
                                "width": "80px",  // Defina a largura desejada
                                "height": "30px", // Defina a altura desejada
                                "font-size": "12px", // Ajuste o tamanho da fonte
                                "padding": "2px" // Ajuste o espaçamento interno
                            });
                            $(td).html(selected)
                        }
                    },
                    {data:"id",name:"id",width:"5%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let contrato_id = cellData;
                            $(td).html(`<div class='text-center text-white'>
                                    <a href="/financeiro/detalhes/coletivo/${contrato_id}" target="_blank" class="text-white">
                                        <i class='fas fa-eye'></i>
                                    </a>
                                </div>
                            `);
                        }},


                ],
                "initComplete": function( settings, json ) {
                    $('#title_estorno_back').html("<h4>Estorno</h4>");
                },
                footerCallback: function (row, data, start, end, display) {

                }
            });


            $("body").on('change','.estorno_back',function(){
                let user_id = $("#corretor_escolhido").val();
                let mes_atual = $("#mes_folha option:selected").val();
                let ano = $("#mes_folha").find('option:selected').text().split("/")[1];
                let id = $(this).attr('data-lancadas');
                let valor = $(this).closest('tr').find('.total_estorno_back').text().replace("R$","").trim();
                $.ajax({
                    url:"{{route('gerente.estorno.valor.voltar')}}",
                    data:"user_id="+user_id+"&mes="+mes_atual+"&ano="+ano+"&id="+id+"&valor="+valor,
                    method:"POST",
                    success:function(res) {
                        if(res != "error") {


                            // if(res.plano == 1) {
                            //
                            // } else if(res.plano == 3) {
                            //     let col = parseInt($('.quantidade_estorno_coletivo').text());
                            //     col += 1;
                            //     $('.quantidade_estorno_coletivo').text("")
                            //     $('.quantidade_estorno_coletivo').text(col);
                            // } else {
                            //     let ind = parseInt($('.quantidade_estorno_empresarial').text());
                            //     ind += 1;
                            //     $('.quantidade_estorno_empresarial').text("");
                            //     $('.quantidade_estorno_empresarial').text(ind);
                            // }

                            //$(".valor_individual_a_receber").text(res.valor_individual_a_receber);
                            //$(".valor_coletivo_a_receber").text(res.valor_coletivo_a_receber);
                            //$(".valor_empresarial_a_receber").text(res.valor_empresarial_a_receber);
                            $("#total_quantidade_individual").text(res.total_individual_quantidade);
                            $("#total_quantidade_coletivo").text(res.total_coletivo_quantidade);
                            ///$("#valor_total_individual").text(res.total_individual);
                            //$("#valor_total_coletivo").text(res.total_coletivo);
                            $(".quantidade_estorno_empresarial").text(res.total_empresarial_quantidade_estorno);
                            $(".quantidade_estorno_individual").text(res.total_individual_quantidade_estorno);
                            $("#total_quantidade_empresarial").text(res.total_empresarial_quantidade);
                            //$("#valor_total_empresarial").text(res.total_empresarial);
                            $("#comissao_vendedor").val(res.total_comissao);
                            $("#valores_confirmados").val(res.id_confirmados);
                            $("#salario_vendedor").val(res.total_salario);
                            $("#premiacao_vendedor").val(res.total_premiacao);
                            $("#valor_total_estorno_vendedor").val(res.estorno);
                            $("#valor_total_desconto_vendedor").val(res.desconto);
                            $(".quantidade_estorno_coletivo ").text(res.total_coletivo_quantidade_estorno);
                            $(".quantidade_estorno_empresarial").text(res.total_empresarial_quantidade_estorno);
                            $("#total_campo_vendedor").val(res.total);
                            $(".coletivo_a_receber").removeClass('ativo');
                            $(".individual_a_receber").removeClass('ativo');
                            $(".empresarial_a_receber").removeClass('ativo');
                            $(".individual_recebidas").removeClass('ativo');
                            $(".coletivo_recebidas").removeClass('ativo');
                            $(".empresarial_recebidas").removeClass('ativo');
                            $("#listar_individual_apto").removeClass('ativo');
                            $("#listar_coletivo_apto").removeClass('ativo');
                            $("#listar_empresarial_apto").removeClass('ativo');
                            $(".listar_estorno_ul li").removeClass('ativo');


                            ////$("#total_quantidade_individual_total").text(res.total_individual_quantidade_geral);
                            //$("#valor_total_individual_total").text(res.total_individual_geral);

                            //$("#total_quantidade_coletivo_total").text(res.total_coletivo_quantidade_geral);
                            //$("#valor_total_coletivo_total").text(res.total_coletivo_geral);

                            //$("#total_quantidade_empresarial_total").text(res.total_empresarial_quantidade_geral);
                            //$("#valor_total_empresarial_total").text(res.total_empresarial_geral);


                            $("#salario").val(res.valores_geral.salario);
                            $("#comissao").val(res.valores_geral.comissao);
                            $("#premiacao").val(res.valores_geral.premiacao);
                            $("#estorno_geral").val(res.valores_geral.estorno);
                            $("#valor_total_desconto").val(res.valores_geral.desconto);
                            $("#total_campo").val(res.valores_geral.total)


                            $("#list_user").html(res.view);
                            $("#btn_fechar_mes").html('<button id="confirmBtn" >Fechar Mês</button>');
                            const select = $("#escolher_vendedor");
                            select.html('<option value="" class="text-center">--Corretores--</option>');
                            $.each(res.usuarios, function(index, corretor) {
                                const option = $("<option>").attr("value", corretor.id).text(corretor.name);
                                if (corretor.id == $("#corretor_escolhido").val()) {
                                    option.attr("selected", "selected");
                                }
                                select.append(option);
                            });
                            select.append("<option value='00'>--Finalizar--</option>");

                            listarestornos.ajax.reload();
                            // let total_a_pagar = parseFloat(res.total_comissao) - parseFloat(res.desconto)
                            if(parseFloat(res.total_comissao) > 0) {
                                $(".total_a_pagar").text(res.total);
                            } else {
                                $(".total_a_pagar").text(0);
                            }
                            // if(res != "error") {
                            //
                            //     if(res == 1) {
                            //
                            //     } else if(res == 3) {
                            //         let col = $('.quantidade_estorno_coletivo').text();
                            //         col -= 1;
                            //         $('.quantidade_estorno_coletivo').text(col);
                            //     } else {
                            //         let ind = $('.quantidade_estorno_empresarial').text();
                            //         ind -= 1;
                            //         $('.quantidade_estorno_empresarial').text(ind);
                            //     }
                            //
                            //
                            //
                            //listarestornos.ajax.reload();
                            // }






                            listarestornosback.ajax.reload();
                            //$(".estorno_usuario_vendedor").val(res.valor_estorno);
                            //$(".total_campo_vendedor").val(res.valor_total);
                        }

                    }
                })
            });

            $("body").on('click','.criar_pdf_estorno_historico',function(){
                let id = $("#corretor_escolhido_historico").val();

                if(id) {
                    if($("#tabela_principal_historico").is(":visible")) {
                        $("#tabela_principal_historico").slideUp(1000,function(){
                            $("#tabela_estorno_back_historico").slideDown('slow',function(){
                                $('#title_estorno_back').html("<h4>Estorno</h4>");
                                listarestornosbackhistorico.ajax.url(`{{ url('/gerente/geral/estorno/${id}') }}`).load();
                            });
                        });
                    }


                }
            });




            $("body").on('click','.criar_estorno',function(){
                let id = $("#corretor_escolhido").val();
                $(".listar_estorno_ul li").removeClass("ativo");
                $(".listar li").removeClass("ativo");
                $("#lista_apto_a_pagar_ul li").removeClass("ativo");
                //$("#listar_coletivo_apto").removeClass("ativo");

                ///$(".individual_estorno_receber").removeClass("ativo");
                //$(".coletivo_estorno_receber").removeClass("ativo");
                //$("#listar_individual_apto").removeClass("ativo");
                ///$(this).addClass('ativo');
                if(id) {

                    //$("#listar_individual_apto").removeClass("ativo");
                    if($("#tabela_estorno_table_back").is(":visible")) {

                        $('#title_estorno_back').html("<h4>Estorno</h4>");
                        listarestornosback.ajax.url(`{{ url('/gerente/geral/estorno/${id}') }}`).load();


                    } else {

                        if($("#tabela_principal").is(":visible")) {

                            $("#tabela_principal").slideUp(1000,function(){
                                $("#tabela_estorno_back").slideDown('slow',function(){
                                    $('#title_estorno_back').html("<h4>Estorno</h4>");
                                    listarestornosback.ajax.url(`{{ url('/gerente/geral/estorno/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_aptos_a_pagar").is(":visible")) {

                            $("#tabela_aptos_a_pagar").slideUp(1000,function(){
                                $("#tabela_estorno_back").slideDown('slow',function(){
                                    $('#title_estorno_back').html("<h4>Estorno</h4>");
                                    listarestornosback.ajax.url(`{{ url('/gerente/geral/estorno/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_mes_diferente").is(":visible")) {

                            $("#listar_a_receber").slideUp(1000,function(){
                                $("#tabela_estorno_back").slideDown('slow',function(){
                                    $('#title_estorno_back').html("<h4>Estorno</h4>");
                                    listarestornosback.ajax.url(`{{ url('/gerente/geral/estorno/${id}') }}`).load();
                                });
                            });
                        }





                        if($("#listar_cadastrados").is(":visible")) {

                            $("#listar_cadastrados").slideUp(1000,function(){
                                $("#tabela_estorno_back").slideDown('slow',function(){
                                    $('#title_estorno_back').html("<h4>Estorno</h4>");
                                    listarestornosback.ajax.url(`{{ url('/gerente/geral/estorno/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_estorno_table").is(':visible')) {

                            $("#tabela_estorno").slideUp('fast',function(){
                                $("#tabela_estorno_back").slideDown('slow',function(){
                                    $('#title_estorno_back').html("<h4>Estorno</h4>");
                                    listarestornosback.ajax.url(`{{ url('/gerente/geral/estorno/${id}') }}`).load();
                                });
                            });
                        }






                    }
                } else {

                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false
                    }
                }
            });



            $(".coletivo_estorno_receber").on('click',function(){
                let id = $("#corretor_escolhido").val();
                $("#listar_coletivo_apto").removeClass("ativo");
                $(".listar li").removeClass("ativo");

                $(".individual_estorno_receber").removeClass("ativo");
                $(".empresarial_estorno_receber").removeClass("ativo");
                $("#listar_individual_apto").removeClass("ativo");
                $("#listar_empresarial_apto").removeClass("ativo");
                $(this).addClass('ativo');
                if(id) {

                    //$("#listar_individual_apto").removeClass("ativo");
                    if($("#tabela_estorno").is(":visible")) {
                        $("#title_estorno_confirmados").html("<h4>Estorno Coletivo</h4>")
                        listarestornos.ajax.url(`{{ url('/gerente/estorno/coletivo/${id}') }}`).load();

                    } else {

                        if($("#tabela_principal").is(":visible")) {
                            $("#tabela_principal").slideUp(1000,function(){
                                $("#tabela_estorno").slideDown('slow',function(){
                                    $("#title_estorno_confirmados").html("<h4>Estorno Coletivo</h4>")
                                    listarestornos.ajax.url(`{{ url('/gerente/estorno/coletivo/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_aptos_a_pagar").is(":visible")) {
                            $("#tabela_aptos_a_pagar").slideUp(1000,function(){
                                $("#tabela_estorno").slideDown('slow',function(){
                                    $("#title_estorno_confirmados").html("<h4>Estorno Coletivo</h4>")
                                    listarestornos.ajax.url(`{{ url('/gerente/estorno/coletivo/${id}') }}`).load();
                                });
                            });
                        }


                        if($("#tabela_estorno_table_back").is(":visible")) {
                            $("#tabela_estorno_back").slideUp(1000,function(){
                                $("#tabela_estorno").slideDown('slow',function(){
                                    $("#title_estorno_confirmados").html("<h4>Estorno Coletivo</h4>")
                                    listarestornos.ajax.url(`{{ url('/gerente/estorno/coletivo/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_mes_diferente").is(":visible")) {
                            $("#listar_a_receber").slideUp(1000,function(){
                                $("#tabela_estorno").slideDown('slow',function(){
                                    $("#title_estorno_confirmados").html("<h4>Estorno Coletivo</h4>");
                                    listarestornos.ajax.url(`{{ url('/gerente/estorno/coletivo/${id}') }}`).load();
                                });
                            });
                        }


                        if($("#listar_cadastrados").is(":visible")) {
                            $("#listar_cadastrados").slideUp(1000,function(){
                                $("#tabela_estorno").slideDown('slow',function(){
                                    $("#title_estorno_confirmados").html("<h4>Estorno Coletivo</h4>")
                                    listarestornos.ajax.url(`{{ url('/gerente/estorno/coletivo/${id}') }}`).load();
                                });
                            });
                        }
                    }
                } else {

                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false
                    }
                }
            });

            $(".empresarial_estorno_receber").on('click',function(){
                let id = $("#corretor_escolhido").val();

                $("#listar_coletivo_apto").removeClass("ativo");
                $(".listar li").removeClass("ativo");
                $("#listar_individual_apto").removeClass("ativo");
                $(".coletivo_estorno_receber").removeClass("ativo");
                $(".individual_estorno_receber").removeClass("ativo");

                $(this).addClass('ativo');
                if(id) {

                    if($("#tabela_estorno").is(":visible")) {
                        $("#title_estorno_confirmados").html("<h4>Estorno Empresarial</h4>")
                        listarestornos.ajax.url(`{{ url('/gerente/estorno/empresarial/${id}') }}`).load();

                    } else {

                        if($("#tabela_principal").is(":visible")) {
                            $("#tabela_principal").slideUp(1000,function(){
                                $("#tabela_estorno").slideDown('slow',function(){
                                    $("#title_estorno_confirmados").html("<h4>Estorno Empresarial</h4>")
                                    listarestornos.ajax.url(`{{ url('/gerente/estorno/empresarial/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_aptos_a_pagar").is(":visible")) {
                            $("#tabela_aptos_a_pagar").slideUp(1000,function(){
                                $("#tabela_estorno").slideDown('slow',function(){
                                    $("#title_estorno_confirmados").html("<h4>Estorno Empresarial</h4>")
                                    listarestornos.ajax.url(`{{ url('/gerente/estorno/empresarial/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_mes_diferente").is(":visible")) {
                            $("#listar_a_receber").slideUp(1000,function(){
                                $("#tabela_estorno").slideDown('slow',function(){
                                    $("#title_estorno_confirmados").html("<h4>Estorno Empresarial</h4>");
                                    listarestornos.ajax.url(`{{ url('/gerente/estorno/empresarial/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_estorno_table_back").is(":visible")) {
                            $("#tabela_estorno_back").slideUp(1000,function(){
                                $("#tabela_estorno").slideDown('slow',function(){
                                    $("#title_estorno_confirmados").html("<h4>Estorno Empresarial</h4>");
                                    listarestornos.ajax.url(`{{ url('/gerente/estorno/empresarial/${id}') }}`).load();
                                });
                            });
                        }


                        if($("#listar_cadastrados").is(":visible")) {
                            $("#listar_cadastrados").slideUp(1000,function(){
                                $("#tabela_estorno").slideDown('slow',function(){
                                    $("#title_estorno_confirmados").html("<h4>Estorno Empresarial</h4>")
                                    listarestornos.ajax.url(`{{ url('/gerente/estorno/empresarial/${id}') }}`).load();
                                });
                            });
                        }
                    }


                } else {

                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false
                    }
                }
            });


            function capitalizarPalavras(str) {
                return str.toLowerCase().replace(/(?:^|\s)\w/g, function(match) {
                    return match.toUpperCase();
                });
            }




            var table_aba_historico = $('#tabelaResultados').DataTable({
                dom: '<"flex justify-between"<"#title_aba_historico"><"estilizar_search"f>><t><"flex justify-between items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                language: {
                    "search": "Pesquisar",
                    "paginate": {
                        "next": "Próx.",
                        "previous": "Ant.",
                        "first": "Primeiro",
                        "last": "Último"
                    },
                    "emptyTable": "Nenhum registro encontrado",
                    "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "infoFiltered": "(Filtrados de _MAX_ registros)",
                    "infoThousands": ".",
                    "loadingRecords": "Carregando...",
                    "processing": "Processando...",
                    "lengthMenu": "Exibir _MENU_ por página"
                },
                ajax: {
                    url:url_padrao,
                    dataSrc:""
                },
                "lengthMenu": [50,100,150,200,300,500],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                columns: [
                    { data: 'administradora',width:"4%"},
                    { data: 'data',width:"6%"},
                    { data: 'codigo_externo',width:"4%"},
                    { data: 'cliente',width:"22%"},
                    { data:'corretor',width:'15%'},
                    { data: 'parcela',width:"8%",className:'dt-center'},
                    { data: 'valor_plano',width:"8%",render:$.fn.dataTable.render.number('.',',',2,'R$ ')},
                    { data: 'valor',width:"8%",render:$.fn.dataTable.render.number('.',',',2,'R$ ')},
                    { data: 'porcentagem',width:"5%",className:'dt-center'},
                    { data: 'plano',width:"10%"}
                ],
                columnDefs: [
                    {
                        targets: 3, // Substitua 0 pelo ID da sua coluna
                        render: function(data, type, row) {
                            if (type == 'display') {
                                return capitalizarPalavras(data);
                            }
                            return data;
                        }
                    },
                    {
                        targets: 9,
                        render: function(data, type, row) {

                            if(data == "Coletivo por Adesão") return "Coletivo";
                            if(data == "Sindicato - Sindipão") return "Sindipão";
                            return data;
                        }
                    }
                ],

                "initComplete": function( settings, json ) {
                    $('#title_aba_historico').html("<h4 style='font-size:1em;margin-top:10px;'>Historico</h4>");
                }

            });


            $("#select_administradora_historico").on('change',function(){
                filtrarTabela();
                calcularContagemESoma();
            });

            $("#select_vendedor_historico").on('change',function (){
                filtrarTabela();
                calcularContagemESoma();
                let user = $(this).val();
                totaisSalariais(user);
                $("#user_historico").val(user);
            });

            $("#select_plano_historico").on('change',function(){
                filtrarTabela();
            });

            function totaisSalariais(user) {
                $.ajax({
                   url:"{{route('gerente.salario.user.historico')}}",
                   data:"user="+user+"&mes="+$("#mes_historico").val(),
                   method:"POST",
                   success:function(res) {
                      $("#salario_historico").val(parseFloat(res.valor_salario).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
                      $("#comissao_historico").val(parseFloat(res.valor_comissao).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
                      $("#premiacao_historico").val(parseFloat(res.valor_premiacao).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
                      $("#valor_total_estorno_historico").val(parseFloat(res.valor_estorno).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
                      $("#valor_total_desconto_historico").val(parseFloat(res.valor_desconto).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
                      $("#total_campo_historico").val(parseFloat(res.valor_total).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
                   }
                });
            }




            function filtrarTabela() {

                var administradoraSelecionada = $('#select_administradora_historico').val();
                var vendedorSelecionado = $('#select_vendedor_historico').val();
                var planoSelecionado = $('#select_plano_historico').val();

                table_aba_historico.columns().search('').draw(); // Limpa todos os filtros

                if (administradoraSelecionada !== 'todos') {
                    table_aba_historico.column(0).search(administradoraSelecionada);
                } else {
                    table_aba_historico.column(0).search('');
                }

                if (vendedorSelecionado !== 'todos') {
                    table_aba_historico.column(4).search(vendedorSelecionado);
                } else {
                    table_aba_historico.column(4).search('');
                }

                if(planoSelecionado !== 'todos') {
                    table_aba_historico.column(9).search(planoSelecionado);
                } else {
                    table_aba_historico.column(9).search('');
                }

                var linhasFiltradas = table_aba_historico.rows({ search: 'applied' }).count();

                var total = 0;
                table_aba_historico.rows({ search: 'applied' }).every(function(rowIdx, tableLoop, rowLoop) {
                    var valorCelula = parseFloat(this.data().valor.replace(',', '.'));
                    if (!isNaN(valorCelula)) {
                        total += valorCelula; // Soma o valor à variável total
                    }
                });
                // console.log(total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));

                table_aba_historico.draw();
            }


            function calcularContagemESoma() {
                var totalValorIndividual = 0;
                var totalValorColetivo = 0;
                var totalValorEmpresarial = 0;

                var totalQuantidadeIndividual = 0;
                var totalQuantidadeColetivo = 0;
                var totalQuantidadeEmpresarial = 0;

                $('#tabelaResultados tbody tr').each(function() {
                    let plano = $(this).find('td:eq(9)').text();
                    let valor = parseFloat($(this).find('td:eq(7)').text().replace('R$', '').replace(',', '.'));
                    switch (plano) {
                        case 'Individual':
                            totalValorIndividual += valor;
                            totalQuantidadeIndividual += 1;
                        break;
                        case 'Coletivo':
                            totalValorColetivo += valor;
                            totalQuantidadeColetivo += 1;
                        break;
                        default:
                            totalValorEmpresarial += valor;
                            totalQuantidadeEmpresarial += 1;
                        break;
                    }
                });

                $("#valor_total_individual_total_historico").text(parseFloat(totalValorIndividual).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
                $("#valor_total_coletivo_total_historico").text(parseFloat(totalValorColetivo).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
                $("#valor_total_empresarial_total_historico").text(parseFloat(totalValorEmpresarial).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));

                $("#total_quantidade_individual_total_historico").text(totalQuantidadeIndividual);
                $("#total_quantidade_coletivo_total_historico").text(totalQuantidadeColetivo);
                $("#total_quantidade_empresarial_total_historico").text(totalQuantidadeEmpresarial);

            }

            $("#select_aba_historico").on('change', function() {
                preencherSelectUser();
                let mesSelecionado = $(this).val(); // Obter o valor seleciona
                $("#mes_historico").val(mesSelecionado);
                // Realizar a requisição AJAX para filtrar os resultados no backend
                $.ajax({
                    url: '{{route('gerente.buscar.historico')}}', // Ajuste para apontar para o seu backend
                    method: 'GET',
                    data: {
                        mes: mesSelecionado
                    },
                    success: function(res) {

                        if(res.data && res.data != null && res.data != "") {
                            $("#salario_historico").val()
                            $("#total_quantidade_individual_total_historico").text(res.total_individual_quantidade);
                            $("#total_quantidade_coletivo_total_historico").text(res.total_coletivo_quantidade);
                            $("#total_quantidade_empresarial_total_historico").text(res.total_empresarial_quantidade);
                            $("#valor_total_individual_total_historico").text(parseFloat(res.total_individual).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
                            $("#valor_total_coletivo_total_historico").text(parseFloat(res.total_coletivo).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
                            $("#valor_total_empresarial_total_historico").text(parseFloat(res.total_empresarial).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
                            $("#aba_historico_empty").html("");
                            $("#container_table_aba_historico").slideDown('slow').css({"display":"flex","align-items": "flex-start"});
                            table_aba_historico.clear().rows.add(res.data).draw();
                            $("#salario_historico").val(res.valores.salario);
                            $("#comissao_historico").val(res.valores.comissao);
                            $("#premiacao_historico").val(res.valores.premiacao);
                            $("#valor_total_desconto_historico").val(res.valores.desconto);
                            $("#total_campo_historico").val(res.valores.total);
                            $("#valor_total_estorno_historico").val(res.valores.estorno);
                        } else {
                           if ($("#container_table_aba_historico").is(":visible")) {
                                $("#container_table_aba_historico").slideUp('slow',function(){
                                    table_aba_historico.clear().draw();
                                    $("#aba_historico_empty").html("<p class='alert alert-danger text-center'>Nada encontrado para este mês especifico</p>");
                                });
                            } else {

                            }
                        }
                    },
                    error: function(error) {
                        console.error('Erro na requisição AJAX:', error);
                    }
                });
            });

            function preencherSelectUser(user=null) {
                var nomesUnicos = new Set();
                $('#tabelaResultados').on('draw.dt', function() {
                    $('#tabelaResultados tbody tr').each(function() {
                        let nome = $(this).find('td:eq(4)').text();
                        nomesUnicos.add(nome);
                    });
                    let selectVendedor = document.getElementById("select_vendedor_historico");
                    let optionEscolherCorretor = document.createElement("option");
                    optionEscolherCorretor.value = "todos"; // Defina o valor conforme necessário
                    optionEscolherCorretor.text = "-Escolher Corretor-";
                    selectVendedor.appendChild(optionEscolherCorretor);
                    let nomesArray = Array.from(nomesUnicos).sort();
                    nomesArray.forEach(function(nome) {
                        var option = document.createElement("option");
                        option.text = nome;
                        option.value = nome
                        selectVendedor.appendChild(option);
                    });
                });
            }

            // function link_excel() {
            //     let mes = $("#mes_folha").val();
            //     $("#criar_excel").attr("href",`/gerente/excel/exportar/${mes}`);
            //
            // }
            // link_excel();

            $("body").on("click","#criar_excel",function(){
                exportAllToExcel();
            });



            function total_mes_atual() {
                let mes_atual = $("#mes_folha").val();
                let ano = $("#mes_folha").find('option:selected').text().split("/")[1];

                $.ajax({
                   url:"{{route('totalizar.mes.gerente')}}",
                   method:"POST",
                   data:"mes="+mes_atual+"&ano="+ano,
                   success:function(res) {
                       console.log(res);
                       $(".salario_usuario").val(res.dados.total_salario);
                       $("#comissao").val(res.dados.total_comissao);
                       $(".premiacao_usuario").val(res.dados.valor_premiacao);
                       $(".desconto_usuario").val(res.dados.valor_desconto);
                       $(".total_campo").val(res.dados.total_mes);

                       $("#valor_total_desconto_geral").val(res.dados.valor_estorno);
                       $("#total_quantidade_coletivo_total").text(res.total_coletivo_quantidade);
                       $("#total_quantidade_empresarial_total").text(res.total_empresarial_quantidade);
                       $("#total_quantidade_individual_total").text(res.total_individual_quantidade);

                       $("#valor_total_individual_total").text(res.total_individual);
                       $("#valor_total_coletivo_total").text(res.total_coletivo);

                       $("#valor_total_empresarial_total").text(res.total_empresarial);


                   }
                });
            }


            function coletivo_a_receber() {

                $(".estilizar_search input[type='search']").val('');
                listarcomissaomesdfirente.search('').draw();
                listarcomissaomesrecebidas.search('').draw();
                listaraptosapagar.search('').draw();

                let id = $("#corretor_escolhido").val();
                $("#listar_individual_apto").removeClass('ativo');
                $("#listar_coletivo_apto").removeClass('ativo');
                $("#listar_empresarial_apto").removeClass('ativo');
                $(".individual_recebidas").removeClass('ativo');
                $(".coletivo_recebidas").removeClass('ativo');
                $(".individual_a_receber").removeClass('ativo');
                $(".empresarial_a_receber").removeClass('ativo');
                $(".empresarial_recebidas").removeClass('ativo');

                $("#listar_individual_apto_total").removeClass('ativo');
                $("#listar_coletivo_apto_total").removeClass('ativo');
                $("#listar_empresarial_apto_total").removeClass('ativo');

                $(".listar_estorno_ul li").removeClass('ativo');

                $(this).addClass('ativo');
                if(id) {
                    $("#listar_individual_apto").removeClass("ativo");
                    if($("#listar_a_receber").is(":visible")) {
                        $("#title_comissao_diferente").html("<h4>A Receber Coletivo</h4>")
                        listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/coletivo/listar/${id}') }}`).load();
                    } else {
                        if($("#tabela_principal").is(":visible")) {
                            $("#tabela_principal").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Coletivo</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/coletivo/listar/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_aptos_a_pagar").is(":visible")) {
                            $("#tabela_aptos_a_pagar").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Coletivo</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/coletivo/listar/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_estorno_table_back").is(":visible")) {
                            $("#tabela_estorno_back").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Coletivo</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/coletivo/listar/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#listar_cadastrados").is(":visible")) {
                            $("#listar_cadastrados").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Coletivo</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/coletivo/listar/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_estorno_table").is(':visible')) {
                            $("#tabela_estorno").slideUp('fast',function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Coletivo</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/coletivo/listar/${id}') }}`).load();
                                });
                            });
                        }


                    }
                } else {
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false
                    }
                }
            }
            $(".coletivo_a_receber").on('click',coletivo_a_receber);

            function individual_a_receber() {
                console.log("Olaaaaaa");

                $(".estilizar_search input[type='search']").val('');
                listarcomissaomesdfirente.search('').draw();
                listarcomissaomesrecebidas.search('').draw();
                listaraptosapagar.search('').draw();

                let id = $("#corretor_escolhido").val();
                $("#listar_individual_apto").removeClass('ativo');
                $("#listar_coletivo_apto").removeClass('ativo');
                $(".individual_recebidas").removeClass('ativo');
                $(".coletivo_recebidas").removeClass('ativo');
                $(".coletivo_a_receber").removeClass('ativo');
                $(".empresarial_a_receber").removeClass('ativo');
                $(".empresarial_recebidas").removeClass('ativo');
                $("#listar_individual_apto_total").removeClass('ativo');
                $("#listar_coletivo_apto_total").removeClass('ativo');
                $("#listar_empresarial_apto_total").removeClass('ativo');
                $(".listar_estorno_ul li").removeClass('ativo');
                $(".listar_estorno_ul li").removeClass('ativo');
                $(this).addClass('ativo');
                if(id) {

                    if($("#listar_a_receber").is(":visible")) {
                        $("#title_comissao_diferente").html("<h4>A Receber Individual</h4>")
                        listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/listagem/comissao_mes_diferente/${id}') }}`).load();
                    } else {
                        if($("#tabela_principal").is(":visible")) {
                            $("#tabela_principal").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Individual</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/listagem/comissao_mes_diferente/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_aptos_a_pagar").is(":visible")) {
                            $("#tabela_aptos_a_pagar").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Individual</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/listagem/comissao_mes_diferente/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#listar_cadastrados").is(":visible")) {
                            $("#listar_cadastrados").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Individual</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/listagem/comissao_mes_diferente/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_estorno_table").is(':visible')) {
                            $("#tabela_estorno").slideUp('fast',function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Individual</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/listagem/comissao_mes_diferente/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_estorno_table_back").is(":visible")) {
                            $("#tabela_estorno_back").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Individual</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/listagem/comissao_mes_diferente/${id}') }}`).load();
                                });
                            });
                        }


                    }
                } else {
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false
                    }
                }
            }

            $('#ano_folha_historico').change(function() {
                // Verifica se o select #ano_folha_historico tem um valor
                if ($(this).val()) {
                    // Remove o atributo 'disabled' do select #mes_folha_historico
                    $('#mes_folha_historico').prop('disabled', false);
                } else {
                    // Adiciona o atributo 'disabled' novamente
                    $('#mes_folha_historico').prop('disabled', true);
                }
            });


            $(".individual_a_receber").on('click',individual_a_receber);
            function showConfirmationMessage() {
                let confirmationMessage = $('#confirmationMessage');
                confirmationMessage.text('Mês Finalizado com Sucesso!! =)');
                confirmationMessage.show();
                setTimeout(function() {
                    confirmationMessage.hide();
                }, 1000);
            }
            var total_valor = 0;
            function finalizarMes() {

                $('.total_pagamento_finalizado').each(function(){
                    total_valor += parseFloat($(this).text().replace(/\./g, '').replace(',', '.'));
                });
                if(total_valor != 0) {
                    let value = total_valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

                    $(".finalizar_mes_container").css({"height":"60px","margin-top":"5px"})
                        .html(`<button style="background-color:green" class="focus:outline-none text-white w-full bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 rounded finalizar_mes" >Fechar Mês</button>`);
                }
                total_valor = 0;
            }
            finalizarMes();

            $("body").on('click','.user_destaque_historico',function(){
                let id = $(this).attr("data-id");
                let nome_corretor = $(this).text();
                let mes_historico = $("#mes_folha_historico :selected").val();
                let ano_historico = $("#ano_folha_historico :selected").val();
                $("#user_historico").val(id);

                $("#corretor_escolhido_historico").val(id);
                $("#footer_user_historico").html(`
                    <button class="text-white w-full bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 gerar_pdf_corretora_link_historico" data-id="${id}">PDF</button>
                `);
                $("#escolher_vendedor_historico").find("option:eq(0)").prop("selected", true);
                $("#escolher_vendedor_historico option[value='" + id + "']").prop("selected", true);


                $("#list_user_historico ul li").removeClass('user_destaque_ativo');
                $(this).closest("li").addClass('user_destaque_ativo');


                if(mes_historico != "" && ano_historico != "") {

                    $.ajax({
                       url:"{{route('gerente.listagem.confirmadas.especifica')}}",
                       data:"mes="+mes_historico+"&ano="+ano_historico+"&id="+id,
                       method:"POST",
                       success:function(res) {
                           console.log(res);
                           $("#salario_vendedor_historico").val(res.salario);
                           $("#comissao_vendedor_historico").val(res.comissao);
                           $("#premiacao_vendedor_historico").val(res.premiacao);
                           $("#valor_total_estorno_vendedor_historico").val(res.estorno);
                           $("#valor_total_desconto_vendedor_historico").val(res.desconto);
                           $("#total_campo_vendedor_historico").val(res.total);




                           $("#total_quantidade_individual_historico").text(res.total_individual_quantidade);
                           $("#valor_total_individual_historico").text(res.total_individual);

                           $("#total_quantidade_coletivo_historico").text(res.total_coletivo_quantidade);
                           $("#valor_total_coletivo_historico").text(res.total_coletivo);

                           $("#total_quantidade_empresarial_historico").text(res.total_empresarial_quantidade);
                           $("#valor_total_empresarial_historico").text(res.total_empresarial);

                           //$("#valores_confirmados").val(res.id_confirmados);
                           ///
                           //$(".total_a_pagar").text(res.total);
                           //$("#listar_individual_apto").removeClass("ativo");
                           ///$("#listar_coletivo_apto").removeClass("ativo");
                           //listaraptosapagar.ajax.reload(function() {
                               //listaraptosapagar.clear().draw();
                           //});
                        }
                    });
                }

                //}
            });


            $("body").on('click','.user_destaque',function(){
                $("#loading-overlay").removeClass('ocultar');
                let id = $(this).attr("data-id");
                let nome_corretor = $(this).text();
                $("#escolher_vendedor").find("option:eq(0)").prop("selected", true);
                $(this).closest("ul").find('.total_pagamento_finalizado').removeClass('valor_total_change');
                $(this).closest("li").find('.total_pagamento_finalizado').addClass('valor_total_change');
                $("#corretor_escolhido").val(id);
                $("#list_user ul li").removeClass('user_destaque_ativo');
                $(this).closest("li").addClass('user_destaque_ativo');
                $("#container_btns").removeClass('hidden').addClass("flex flex-col");
                $("#container_btns").html(`
                    <button class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 criar_estorno" data-id="${id}" style="font-size:0.8em;"  target="_blank">Estorno</button>
                    <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 criar_pdf_corretor" data-id="${id}" style="font-size:0.8em;"  target="_blank">PDF Corretor</button>
                    <button class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 criar_pdf" data-id="${id}" style="font-size:0.8em;"  target="_blank">PDF Corretora</button>
                `).addClass('flex')
                $(".listar_estorno_ul li").removeClass("ativo");
                $(".listar li").removeClass("ativo");
                if($("#tabela_estorno_table").is(':visible')) {
                    $("#tabela_estorno").slideUp('fast',function(){
                        $("#tabela_principal").slideDown(1000,function(){
                            $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                            listarcomissaomesrecebidas.ajax.url(`{{ url('gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                            $(".individual_recebidas").addClass("ativo");
                        });
                    });
                }

                if($("#listar_a_receber").is(':visible')) {
                    $("#listar_a_receber").slideUp('slow',function(){
                        $("#tabela_principal").slideDown(1000,function(){
                            $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                            listarcomissaomesrecebidas.ajax.url(`{{ url('gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                            $(".individual_recebidas").addClass("ativo");
                        });
                    })
                }

                if($("#tabela_aptos_a_pagar").is(":visible")) {
                    $("#tabela_aptos_a_pagar").slideUp(1000,function(){
                        $("#tabela_principal").slideDown(1000,function(){
                            $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                            listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                            $(".individual_recebidas").addClass("ativo");
                        });
                    });
                }


                $("#list_user").css({"height":"293px","max-height":"293px","overflow":"auto"})

                //if($("#mes_folha").val() != "") {

                    let mes = $("#mes_folha").val();
                    let ano = $("#mes_folha").find('option:selected').text().split("/")[1];
                    $.ajax({
                       url:"{{route('gerente.listagem.confirmadas.especifica')}}",
                       data:"mes="+mes+"&id="+id+"&ano="+ano,
                       method:"POST",
                       success:function(res) {
                           $(".valor_individual_a_receber").text(res.valor_individual_a_receber);
                           $(".valor_coletivo_a_receber").text(res.valor_coletivo_a_receber);
                           $(".valor_empresarial_a_receber").text(res.valor_empresarial_a_receber);

                           $("#salario_vendedor").val(res.salario);
                           $("#comissao_vendedor").val(res.comissao);
                           $("#premiacao_vendedor").val(res.premiacao);
                           $(".desconto_usuario_vendedor").val(res.desconto);
                           $("#valor_total_desconto_vendedor").val(res.desconto);
                           $("#total_campo_vendedor").val(res.total);
                           $(".quantidade_estorno_empresarial").text(res.total_empresarial_quantidade_estorno);
                           $(".quantidade_estorno_individual").text(res.total_coletivo_quantidade_estorno);
                           $("#total_quantidade_individual").text(res.total_individual_quantidade);
                           $("#total_quantidade_coletivo").text(res.total_coletivo_quantidade);
                           $("#valor_total_individual").text(res.total_individual);
                           $("#total_quantidade_empresarial").text(res.total_empresarial_quantidade);
                           $("#valor_total_empresarial").text(res.total_empresarial);
                           $("#valor_total_coletivo").text(res.total_coletivo);
                           $("#valores_confirmados").val(res.id_confirmados);
                           $("#valor_total_estorno_vendedor").val(res.estorno);
                           $(".total_a_pagar").text(res.total);
                           $(".quantidade_estorno_coletivo").text(res.total_coletivo_quantidade_estorno);
                           $("#listar_individual_apto").removeClass("ativo");
                           $("#listar_coletivo_apto").removeClass("ativo");
                           listaraptosapagar.ajax.reload(function() {
                               listaraptosapagar.clear().draw();
                           });
                       },
                        complete: function () {
                            // Esconder o overlay de loading
                            $("#loading-overlay").addClass('ocultar');
                        }
                    });
                //}
            });

            var id_confirmados = [];
            $("body").on('click','#finalizar_folha',function(){
                if($("#mes_folha").val() == "") {
                    toastr["error"]("Mês é campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "00",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                }

                if($("#salario_vendedor").val() == "") {
                    toastr["error"]("Salario é campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "00",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                }
                $('#exampleModal').modal('show')
                return true;
            });

            $("#exampleModal").on('show.bs.modal', function (event) {
                let comissao =  $("#comissao_vendedor").val();
                let salario = $(".salario_usuario_vendedor").val();
                let premiacao = $(".premiacao_usuario_vendedor").val();
                let desconto = $("#valor_total_desconto_vendedor").val();
                let estorno = $("#valor_total_estorno_vendedor").val();
                let user_id = $("#cliente_id").val();
                let mes = $("#mes_folha").val();
                let total_a_pagar = $(".total_campo_vendedor").val();
                $(".salario_usuario_modal").text(salario);
                $(".comissao_usuario_modal").text(comissao);
                $(".premiacao_usuario_modal").text(premiacao);
                $(".desconto_usuario_modal").text(desconto);
                $(".estorno_usuario_modal").text(estorno);
                $(".total_a_pagar_modal").text(total_a_pagar);
                let selectedOptionText = $('#escolher_vendedor option:selected').text();
                let selectMesText = $("#mes_folha option:selected").text();
                $(".btn_usuario").text("Finalizar folha de "+selectedOptionText+" do mes "+selectMesText);

            });


            $("body").on('click', '.finalizar_mes', function() {
                $('#myModal').removeClass('hidden').addClass('flex');

                $("#resultado_tabela").html(`
                        <div class="loading-dots">
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                `);

                let mes = $("#mes_folha").val();

                $.ajax({
                    url: "{{route('montar.tabela.mes.modal')}}",
                    data: { mes: mes },
                    method: "POST",
                    success: function(res) {
                        // Substitui o loader pelo conteúdo da tabela após carregar
                        $("#resultado_tabela").html(res);
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro ao carregar os dados da tabela:", error);
                    }
                });

                id_confirmados = [];
            });

            $(".closeFecharMes").on('click', function() {
                $('#myModal').removeClass('flex').addClass('hidden');
            });




            $('#confirmBtn').click(function() {

                let mes = $("#mes_folha").find('option:selected').val();
                let ano = $("#mes_folha").find('option:selected').text().split("/")[1];

                    $.ajax({
                       url:"{{route('gerente.pagamento.mes.finalizado')}}",
                       method:"POST",
                       data:"mes="+mes+"&ano="+ano,
                        success:function(res) {
                           if(res == "sem_mes") {

                           } else {
                               window.location.reload();
                               // $("#list_user").html('');
                               // $('#mes_folha').removeAttr('disabled');
                               // $('#mes_folha option').removeAttr('selected');
                           }
                       }
                    });
                    $("#salario_vendedor").val("0,00");
                    $("#comissao_vendedor").val("0,00");
                    $("#premiacao_vendedor").val("0,00");
                    $("#valor_total_estorno_vendedor").val("0,00");
                    $("#valor_total_desconto_vendedor").val("0,00");
                    $("#total_campo_vendedor").val("0,00");
                    $("#valor_total_desconto").val("0,00");
                    $('.finalizar_mes').fadeOut('fast');
                    $('#escolher_vendedor option:first').prop('selected', true);
                    $(".salario_usuario").val("0,00");
                    $("#comissao").val("0,00");
                    $("#premiacao").val("0,00");
                    $("#valor_total_desconto_geral").val("0,00");
                    $("#total_campo").val("0,00");
                    $("#total_campo").val("0,00");
                    $("#total_campo_vendedor").val("0,00");
                    $("#corretor_escolhido").val("");
                    $("#valores_confirmados").val("");
                    $("#mes_fechado").val("");
                    //$(".premiacao_usuario").val("");
                    $("#valor_total_desconto").val("");
                    $("#total_campo").val("");
                    $("#total_campo_vendedor").val("");
                    $("#total_quantidade_coletivo").text("");
                    $("#valor_total_coletivo").text("0,00");
                    $("#valor_total_individual").text("0,00");
                    $("#valor_total_empresarial").text("0,00");
                    $("#total_quantidade_individual").text("0");
                    $("#total_quantidade_coletivo").text("0");
                    $("#total_quantidade_empresarial").text("0");
                    $("#total_quantidade_individual_total").text("0");
                    $("#total_quantidade_coletivo_total").text("0");
                    $("#total_quantidade_empresarial_total").text("0");
                    $("#valor_total_individual_total").text("0,00");
                    $("#valor_total_coletivo_total").text("0,00");
                    $("#valor_total_empresarial_total").text("0,00");
                    $('#errorMessage').text('');
                    $('#myModal').modal('hide');
                    $("#mes_folha").val("");
                    $(".individual_recebidas").removeClass('ativo');
                    $(".coletivo_recebidas").removeClass('ativo');
                    $(".empresarial_recebidas").removeClass('ativo');

                    $(".individual_a_receber").removeClass('ativo');
                    $(".coletivo_a_receber").removeClass('ativo');
                    $(".empresarial_a_receber").removeClass('ativo');

                    $("#listar_individual_apto").removeClass('ativo');
                    $("#listar_coletivo_apto").removeClass('ativo');
                    $("#listar_empresarial_apto").removeClass('ativo');

                    $(".finalizar_mes").html("Finalizar");

                    $("#escolher_vendedor").prop("disabled",true);
                    $('#passwordInput').val('');


                    listarcomissaomesdfirente.ajax.reload(function() {
                        listarcomissaomesdfirente.clear().draw();
                        listarcomissaomesdfirente.search('').draw();
                    });

                    listarcomissaomesrecebidas.ajax.reload(function() {
                        listarcomissaomesrecebidas.clear().draw();
                        listarcomissaomesrecebidas.search('').draw();
                    });

                    listaraptosapagar.ajax.reload(function() {
                        listaraptosapagar.clear().draw();
                        listaraptosapagar.search('').draw();
                    });

                    listarestornos.ajax.reload(function() {
                        listarestornos.clear().draw();
                        listarestornos.search('').draw();
                    });

                    showConfirmationMessage();

            });

            $("#mes_folha_historico").on('change',function(){
                let mes = $(this).val();
                let ano = $("#ano_folha_historico option:selected").val();

                if(mes != "" && ano != "") {


                    $("#criar_excel_historico").attr('href','/gerente/excel/exportar/'+mes);
                    $("#mes_historico").val(mes);
                    if(mes == "") {
                        //$("#escolher_vendedor").prop("disabled",true)
                    } else {
                        $("#escolher_vendedor").removeAttr("disabled");
                    }
                    let selectedText = $("#mes_folha option:selected").text();
                    let formattedDate = ano+"-"+mes+"-01";
                    $.ajax({
                        url:"{{route('gerente.historico.cadastrar.folha_mes')}}",
                        method:"POST",
                        data:"data="+formattedDate,
                        success:function(res) {

                            $("#total_quantidade_individual_total_historico").text(res.total_individual_quantidade);
                            $("#valor_total_individual_total_historico").text(res.total_individual);

                            $("#total_quantidade_coletivo_total_historico").text(res.total_coletivo_quantidade);
                            $("#valor_total_coletivo_total_historico").text(res.total_coletivo);

                            $("#total_quantidade_empresarial_total_historico").text(res.total_empresarial_quantidade);
                            $("#valor_total_empresarial_total_historico").text(res.total_empresarial);

                            $(".salario_usuario_historico").val(res.dados.total_salario);
                            $("#comissao_historico").val(res.dados.total_comissao);
                            $("#premiacao_historico").val(res.dados.valor_premiacao);
                            $("#estorno_geral_historico").val(res.dados.valor_premiacao)
                            $("#valor_total_desconto_historico").val(res.dados.valor_desconto);
                            $("#total_campo_historico").val(res.dados.total_mes);

                            $("#estorno_geral_historico").val(res.total_estorno);
                            if(res.total_estorno > 0) {
                                $('.estorno_geral_historico_button').removeClass('dsnone');
                            } else {
                                $('.estorno_geral_historico_button').addClass('dsnone');
                            }

                            $("#list_user_historico").css({"height":"100%","overflow":"auto"}).html(res.view);
                            $("#lista_apto_a_pagar_ul li").removeClass('ativo');
                            let select = $('#escolher_vendedor_historico');
                            select.html('');
                            select.append($('<option>', {
                                value: '',
                                text: '--Corretores--'
                            }).css('text-align', 'center'))

                            $.each(res.users, function(index, user) {
                                let option = $('<option>', {
                                    value: user.id,
                                    text: user.name
                                });
                                select.append(option);
                            });
                        }
                    });
                }
            });



            $("#mes_folha").on('change',function(){
               let mes = $(this).val();
               if(mes == "") {
                   //$("#escolher_vendedor").prop("disabled",true)
               } else {
                   $("#escolher_vendedor").removeAttr("disabled")
               }
               let selectedText = $("#mes_folha option:selected").text();
               let ano = $(this).find('option:selected').text().split("/")[1];
               let formattedDate = ano + "-" + mes+"-01";

               $.ajax({
                   url:"{{route('gerente.cadastrar.folha_mes')}}",
                   method:"POST",
                   data:"data="+formattedDate,
                   success:function(res) {
                        if(res.resposta != "cadastrado") {
                           $(".salario_usuario").val(res.dados.total_salario);
                           $("#comissao").val(res.dados.total_comissao);
                           $("#premiacao").val(res.dados.valor_premiacao);
                           $("#valor_total_desconto").val(res.dados.valor_desconto);
                           $("#total_campo").val(res.dados.total_mes);
                           if ($('.list_abas #mes_existe').length > 0) {
                               $('.list_abas #mes_existe').hide(function(){
                                   let selectedTextMudou = $("#mes_folha option:selected").text();
                                   $('.list_abas #mes_existe').remove();
                                   $(".list_abas").append(`<li id='mes_existe' style='width:740px;margin-left:5px;background-color:#B22222;display:flex;justify-content: space-between;'><span>O Mês ${selectedTextMudou} já esta fechado</span><button class="btn_valores_mes flex justify-content-center" style="border:none;font-size:0.8em;background-color:#FF6347;color:#FFF;border-radius:5px;border:1px solid #FFF;" data-mes="${mes}">Criar PDF</button></li>`);
                               });
                           } else {
                               let selectedTextMudou = $("#mes_folha option:selected").text();
                               $(".list_abas").append(`<li id='mes_existe' style='width:740px;margin-left:5px;background-color:#B22222;display:flex;justify-content: space-between;'><span>O Mês ${selectedTextMudou} já esta fechado</span><button class="btn_valores_mes flex justify-content-center" style="border:none;font-size:0.8em;background-color:#FF6347;color:#FFF;border-radius:5px;border:1px solid #FFF;" data-mes="${mes}">Criar PDF</button></li>`);
                           }
                           $("#list_user").css({"height":"325px","max-height":"325px","overflow":"auto"}).html(res.view);
                           $(".salario_usuario_vendedor").prop("disabled",true);
                           $(".premiacao_usuario_vendedor").prop("disabled",true);
                           $("#escolher_vendedor option:not(:first-child)").remove();
                           $(this).prop("disabled",false);
                           let column = listaraptosapagar.column(11);
                           column.visible(false);
                           $(".menu_aba_comissao").height("500px");
                           $("#mes_fechado").val(mes);
                           $("#finalizar_folha").prop('disabled', true);
                           $(".individual_recebidas").off('click');
                           $(".finalizar_mes").fadeOut('fast');
                           $(".individual_estorno_receber").off('click');
                           $(".coletivo_estorno_receber").off('click');
                           $(".empresarial_estorno_receber").off('click');
                           $(".coletivo_recebidas").off('click');
                           $(".empresarial_recebidas").off('click');
                           $(".individual_a_receber").off('click');
                           $(".coletivo_a_receber").off('click');
                           $(".empresarial_a_receber").off('click');
                           $("#salario_vendedor").val(0);
                           $("#premiacao_vendedor").val(0);
                           $("#comissao_vendedor").val(0);
                           $("#desconto_vendedor").val(0);
                           $("#finalizar_folha").text("Finalizar");
                           $("#total_quantidade_individual").text(0);
                           $("#valor_total_individual").text(0);
                           $("#total_quantidade_coletivo").text(0);
                           $("#valor_total_coletivo").text(0);
                           $("#total_quantidade_empresarial").text(0);
                           $("#valor_total_empresarial").text(0);
                       } else {
                           $("#mes_folha").prop("disabled",true);
                           $("#mes_existe").hide();
                           $(".individual_recebidas").on('click',individual_recebidas);
                           $(".coletivo_recebidas").on('click',coletivo_recebidas);
                           $(".empresarial_recebidas").on('click');
                           $(".individual_a_receber").on('click',individual_a_receber);
                           $(".coletivo_a_receber").on('click',coletivo_a_receber);
                           $(".empresarial_a_receber").on('click');
                           $("#finalizar_folha").prop('disabled', false);
                           $("#list_user").html("");
                           $("#footer_user").html("");
                           $(".salario_usuario").val(0);
                           $("#comissao").val(0);
                           $("#premiacao").val(0);
                           $("#valor_total_desconto").val(0);
                           $("#total_campo").val(0);
                           let select = $('#escolher_vendedor');
                           select.html('');
                           select.append($('<option>', {
                               value: '',
                               text: '--Corretores--'
                           }).css('text-align', 'center'));

                           $.each(res.users_select, function(index, user) {
                               let option = $('<option>', {
                                   value: user.id,
                                   text: user.name
                               });
                               select.append(option);
                           });
                           select.append("<option>--Finalizar--</option>");
                           //$("#footer_user").html('<button class="btn btn-info btn-block mx-auto finalizar_mes">Finalizar</button>');
                       }
                   }
                });
            });

            $("body").on('click','.btn_valores_mes',function(){
                let mes = $(this).attr('data-mes');
                $.ajax({
                   url:"{{route('geral.folha.mes.especifica')}}",
                   method:"POST",
                   data:"mes="+mes,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success:function(blob,status,xhr,ppp) {
                        var filename = "";
                        var disposition = xhr.getResponseHeader('Content-Disposition');
                        if (disposition && disposition.indexOf('attachment') !== -1) {
                            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            var matches = filenameRegex.exec(disposition);
                            if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                        }
                        if (typeof window.navigator.msSaveBlob !== 'undefined') {
                            window.navigator.msSaveBlob(blob,filename);
                        } else {
                            // $(".salario_usuario").val("");
                            // $("#comissao").val("");
                            // $("#premiacao").val("");
                            // $("#valor_total_individual").val('0.00');
                            // $("#valor_total_coletivo").val('0.00');
                            // $("#valor_total_empresarial").val('0.00');

                            var URL = window.URL || window.webkitURL;
                            var downloadUrl = URL.createObjectURL(blob);
                            if (filename) {
                                var a = document.createElement("a");
                                if (typeof a.download === 'undefined') {
                                    window.location.href = downloadUrl;
                                } else {
                                    a.href = downloadUrl;
                                    a.download = filename;
                                    document.body.appendChild(a);
                                    a.click();
                                }
                            } else {
                                window.location.href = downloadUrl;
                            }
                            setTimeout(function () {
                                URL.revokeObjectURL(downloadUrl);
                            },100);
                        }
                    }
                });
            });

            String.prototype.ucWords = function () {
                let str = this.toLowerCase()
                let re = /(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g
                return str.replace(re, s => s.toUpperCase())
            }
            const date = new Date();

            function adicionarZero(valor) {
                if(valor.length == 2) return valor;
                return "0"+valor;
            }


            $("#escolher_vendedor_historico").on('change',function(){
                let id = $(this).val();
                let total = $("#total_campo_vendedor_historico").val();
                let dados_user = $("#corretor_escolhido_historico").val();
                let mes = $("#mes_folha_historico option:selected").val();
                let premiacao = $("#premiacao_vendedor_historico").val();
                let salario = $("#salario_vendedor_historico").val();

                // <button class="btn btn-info btn-block btn-sm criar_pdf_historico" data-id="${id}">PDF</button>
                $("#footer_user_historico").html(`
                    <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 gerar_pdf_corretora_link_historico" data-id="${id}">PDF</button>
                `);
                $("#list_user_historico li[data-user='" + id + "']").addClass("user_destaque_ativo");
                $("#list_user_historico").find(".total_pagamento_finalizado").removeClass('.valor_total_change');
                $("#corretor_escolhido_historico").val(id);
                $.ajax({
                   url:"{{route('gerente.historico.informacoes.corretor')}}",
                   method:"POST",
                   data:
                        "id="+id+
                        "&mes="+mes+
                        "&premiacao="+premiacao+
                        "&user_id="+dados_user+
                        "&total="+total+
                        "&salario="+salario,
                    success:function(res) {
                        $("#lista_apto_a_pagar_ul li").removeClass('ativo');
                        $(".listar_historico li").removeClass('ativo');
                        $(".listar_a_receber_ul li").removeClass('ativo');
                        $(".listar_estorno_ul li").removeClass('ativo');
                        $("#listar_individual_apto_historico").addClass('ativo');
                        listaraptosapagarhistorico.clear().draw();
                        if($("#tabela_principal_historico").is(':visible')) {
                            $("#tabela_principal_historico").slideUp('fast',function(){
                                $("#title_individual_confirmados_historico").html("<h4>Individual</h4>");
                                listaraptosapagarhistorico.ajax.url(`{{ url('/gerente/comissao/confirmadas/${id}/${mes}') }}`).load();
                                $("#tabela_aptos_a_pagar_historico").slideDown('slow');
                            });
                        }
                        if($("#tabela_aptos_a_pagar_table_historico").is(":visible")) {
                            $("#title_individual_confirmados_historico").html("<h4>Individual</h4>");
                            listaraptosapagarhistorico.ajax.url(`{{ url('/gerente/comissao/confirmadas/${id}/${mes}') }}`).load();
                        }
                        $("#listar_individual_apto_historico").addClass("ativo");
                        $("#listar_coletivo_apto_historico").removeClass("ativo");
                        $("#listar_empresarial_apto_historico").removeClass("ativo");
                        if($("#tabela_aptos_a_pagar_table_historico").is(":visible")) {
                            $("#title_individual_confirmados_historico").html("<h4>Individual</h4>");
                            listaraptosapagarhistorico.ajax.url(`{{ url('/gerente/comissao/confirmadas/${id}/${mes}') }}`).load();
                        }
                        $("#total_quantidade_individual_historico").text(res.total_individual_quantidade);
                        $("#total_quantidade_coletivo_historico").text(res.total_coletivo_quantidade);
                        $("#total_quantidade_empresarial_historico").text(res.total_empresarial_quantidade);


                        $("#valor_total_individual_historico").text(res.total_individual);
                        $("#valor_total_coletivo_historico").text(res.total_coletivo);
                        $("#valor_total_empresarial_historico").text(res.total_empresarial);



                        $("#comissao_vendedor_historico").val(res.total_comissao);
                        // $("#valores_confirmados").val(res.id_confirmados);
                        $("#salario_vendedor_historico").val(res.total_salario);
                        $("#premiacao_vendedor_historico").val(res.total_premiacao);
                        $("#valor_total_estorno_vendedor_historico").val(res.estorno);
                        $("#valor_total_desconto_vendedor_historico").val(res.desconto);
                        $("#total_campo_vendedor_historico").val(res.total);
                    }
                });
            });

            $("#escolher_vendedor").on('change',function(){
                $("#loading-overlay").removeClass('ocultar');
                let id_user_select = $(this).val();

                let mes = $("#mes_folha option:selected").val();
                let ano = $("#mes_folha option:selected").text().split("/")[1];


                if(id_user_select != 00) {

                    let total = $("#total_campo_vendedor").val().trim();
                    let dados_user = $("#corretor_escolhido").val();


                    let premiacao = $("#premiacao_vendedor").val();
                    let salario = $("#salario_vendedor").val();
                    $("#list_user").find(".total_pagamento_finalizado").removeClass('.valor_total_change');
                    $("#corretor_escolhido").val(id_user_select);
                    $.ajax({
                        url:"{{route('gerente.informacoes.quantidade.corretor')}}",
                        method:"POST",
                        data:
                            "id="+id_user_select+
                            "&mes="+mes+
                            "&ano="+ano+
                            "&premiacao="+premiacao+
                            "&user_id="+dados_user+
                            "&total="+total+
                            "&salario="+salario,
                        success:function(res) {
                            $(".valor_individual_a_receber").text(res.valor_individual_a_receber);
                            $(".valor_coletivo_a_receber").text(res.valor_coletivo_a_receber);
                            $(".valor_empresarial_a_receber").text(res.valor_empresarial_a_receber);
                            $("#total_quantidade_individual").text(res.total_individual_quantidade);
                            $("#total_quantidade_coletivo").text(res.total_coletivo_quantidade);
                            $("#valor_total_individual").text(res.total_individual);
                            $("#valor_total_coletivo").text(res.total_coletivo);
                            $(".quantidade_estorno_empresarial").text(res.total_empresarial_quantidade_estorno);
                            $(".quantidade_estorno_individual").text(res.total_individual_quantidade_estorno);
                            $("#total_quantidade_empresarial").text(res.total_empresarial_quantidade);
                            $("#valor_total_empresarial").text(res.total_empresarial);
                            $("#comissao_vendedor").val(res.total_comissao);
                            $("#valores_confirmados").val(res.id_confirmados);
                            $("#salario_vendedor").val(res.total_salario);
                            $("#premiacao_vendedor").val(res.total_premiacao);
                            $("#valor_total_estorno_vendedor").val(res.estorno);
                            $("#valor_total_desconto_vendedor").val(res.desconto);
                            $(".quantidade_estorno_coletivo ").text(res.total_coletivo_quantidade_estorno);
                            $(".quantidade_estorno_empresarial").text(res.total_empresarial_quantidade_estorno);
                            $("#total_campo_vendedor").val(res.total);
                            $(".coletivo_a_receber").removeClass('ativo');
                            $(".individual_a_receber").removeClass('ativo');
                            $(".empresarial_a_receber").removeClass('ativo');
                            $(".individual_recebidas").removeClass('ativo');
                            $(".coletivo_recebidas").removeClass('ativo');
                            $(".empresarial_recebidas").removeClass('ativo');
                            $("#listar_individual_apto").removeClass('ativo');
                            $("#listar_coletivo_apto").removeClass('ativo');
                            $("#listar_empresarial_apto").removeClass('ativo');
                            $(".listar_estorno_ul li").removeClass('ativo');

                            $("#list_user").html(res.view);
                            $("#btn_fechar_mes").html('<button id="confirmBtn" >Fechar Mês</button>');
                            const select = $("#escolher_vendedor");
                            select.html('<option value="" class="text-center">--Corretores--</option>');
                            $.each(res.usuarios, function(index, corretor) {
                                const option = $("<option>").attr("value", corretor.id).text(corretor.name);
                                if (corretor.id == id_user_select) {
                                    option.attr("selected", "selected");
                                }
                                select.append(option);
                            });
                            select.append("<option value='00'>--Finalizar--</option>");


                            // let total_a_pagar = parseFloat(res.total_comissao) - parseFloat(res.desconto)
                            if(parseFloat(res.total_comissao) > 0) {
                                $(".total_a_pagar").text(res.total);
                            } else {
                                $(".total_a_pagar").text(0);
                            }


                            if($("#tabela_estorno_table").is(':visible')) {
                                $("#tabela_estorno").slideUp('fast',function(){
                                    $("#tabela_principal").slideDown(1000,function(){
                                        $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                                        listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id_user_select}') }}`).load();
                                        $(".individual_recebidas").addClass("ativo");
                                    });
                                });
                            }

                            if($("#listar_a_receber").is(':visible')) {
                                $("#listar_a_receber").slideUp('slow',function(){
                                    $("#tabela_principal").slideDown(1000,function(){
                                        $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                                        listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id_user_select}') }}`).load();
                                        $(".individual_recebidas").addClass("ativo");
                                    });
                                })
                            }

                            if($("#tabela_aptos_a_pagar").is(":visible")) {
                                $("#tabela_aptos_a_pagar").slideUp(1000,function(){
                                    $("#tabela_principal").slideDown(1000,function(){
                                        $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                                        listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id_user_select}') }}`).load();
                                        $(".individual_recebidas").addClass("ativo");
                                    });
                                });
                            }

                            total_mes_atual();
                            finalizarMes();

                            $("#container_btns").removeClass("flex").removeClass('flex-col').addClass('hidden');
                            $("#list_user").css("height","235px");

                        },
                        complete: function () {
                            // Esconder o overlay de loading
                            $("#loading-overlay").addClass('ocultar');
                        }
                    });
                } else {
                    let quantidadeOpcoes = $("#escolher_vendedor option[value!='00'][value!='']").length;
                    console.log(quantidadeOpcoes);
                    if(quantidadeOpcoes == 1 || quantidadeOpcoes == 0) {
                        $("#escolher_vendedor option[value!='00'][value!='']").remove();

                        $.ajax({
                            url:"{{route('gerente.pegar.todos.mes.corrente')}}",
                            method:"POST",
                            data:"mes="+mes+"&ano="+ano,
                            success:function(res) {
                                console.log(res);
                                $("#list_user").html(res.view);

                            },complete: function () {
                                // Esconder o overlay de loading
                                $("#loading-overlay").addClass('ocultar');
                            }
                        });



                    }
                }
            });


            var listarcadastrados = $(".listarcadastrados").DataTable({
                dom: '<"flex justify-between"<"#title_cadastrados"><"estilizar_search"f>><t><"flex justify-between align-items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                language: {
                    "search": "Pesquisar",
                    "paginate": {
                        "next": "Próx.",
                        "previous": "Ant.",
                        "first": "Primeiro",
                        "last": "Último"
                    },
                    "emptyTable": "Nenhum registro encontrado",
                    "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "infoFiltered": "(Filtrados de _MAX_ registros)",
                    "infoThousands": ".",
                    "loadingRecords": "Carregando...",
                    "processing": "Processando...",
                    "lengthMenu": "Exibir _MENU_ por página"
                },
                ajax: {
                    url:url_padrao,
                    dataSrc:""
                },
                "lengthMenu": [50,100,150,200,300,500],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,

                columns: [
                    {data:"administradora",name:"administradora"},
                    {data:"cliente",name:"cliente"},
                    {data:"corretor",name:"corretor"},

                    {data:"plano",name:"plano"},

                    {
                        data: "plano_id", name: "plano_id", width: "1%",
                        "createdCell": function (td, cellData, rowData, row, col) {

                            let contrato_id = rowData.contrato_id;

                            if(cellData == 1) {
                                $(td).html(`<div class='text-center text-white'>
                                    <a href="/financeiro/detalhes/${contrato_id}" target="_blank" class="text-white">
                                        <i class='fas fa-eye'></i>
                                    </a>
                                </div>
                            `);
                            } else if(cellData == 3) {
                                $(td).html(`<div class='text-center text-white'>
                                    <a href="/financeiro/detalhes/coletivo/${contrato_id}" target="_blank" class="text-white">
                                        <i class='fas fa-eye'></i>
                                    </a>
                                </div>
                            `);
                            } else {
                                $(td).html(`<div class='text-center text-white'>
                                    <a href="/financeiro/detalhes/empresarial/${contrato_id}" target="_blank" class="text-white">
                                        <i class='fas fa-eye'></i>
                                    </a>
                                </div>
                            `);
                            }



                        }
                    }






                ],
                "initComplete": function( settings, json ) {
                    $('#title_cadastrados').html("<h4>Cadastrados</h4>");
                }

            });

            var tabela_de_cadastrados = $('#tabela_cadastrados').DataTable();

            $("body").on('click','#all_cadastrados',function(){

                $(".listar listar_a_receber_ul li").removeClass('ativo');
                $(".listar li").removeClass('ativo');
                $(".listar_individual_apto li").removeClass('ativo');


                if($("#listar_a_receber").is(":visible")) {
                    $("#listar_a_receber").slideUp(1000,function(){
                        tabela_de_cadastrados.ajax.url(`{{ route('listar.gerente.cadastrados') }}`).load();
                        $("#listar_cadastrados").slideDown(1000);
                    });
                }

                if($("#tabela_principal").is(":visible")) {
                    $("#tabela_principal").slideUp(1000,function(){
                        tabela_de_cadastrados.ajax.url(`{{ route('listar.gerente.cadastrados') }}`).load();
                        $("#listar_cadastrados").slideDown(1000);
                    });
                }

                if($("#tabela_aptos_a_pagar").is(":visible")) {
                    $("#tabela_aptos_a_pagar").slideUp(1000,function(){
                        tabela_de_cadastrados.ajax.url(`{{ route('listar.gerente.cadastrados') }}`).load();
                        $("#listar_cadastrados").slideDown(1000);
                    });
                }

                if($("#tabela_estorno_table").is(':visible')) {
                    $("#tabela_estorno").slideUp('fast',function(){
                        tabela_de_cadastrados.ajax.url(`{{ route('listar.gerente.cadastrados') }}`).load();
                        $("#listar_cadastrados").slideDown(1000);
                    });
                }
            });

            var listaraptosapagarhistorico = $(".listaraptosapagarhistorico").DataTable({
                dom: '<"flex justify-between"<"#title_individual_confirmados_historico"><"estilizar_search"f>><t><"flex justify-between items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                language: {
                    "search": "Pesquisar",
                    "paginate": {
                        "next": "Próx.",
                        "previous": "Ant.",
                        "first": "Primeiro",
                        "last": "Último"
                    },
                    "emptyTable": "Nenhum registro encontrado",
                    "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "infoFiltered": "(Filtrados de _MAX_ registros)",
                    "infoThousands": ".",
                    "loadingRecords": "Carregando...",
                    "processing": "Processando...",
                    "lengthMenu": "Exibir _MENU_ por página"
                },
                ajax: {
                    url:url_padrao,
                    dataSrc:""
                },
                "lengthMenu": [1000,2000,3000,4000,5000],
                // "lengthMenu": [1,2,3,4,5,6],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,

                columns: [
                    {data:"administradora",name:"administradora",width:"3%"},
                    {data:"created_at",name:"created_at",width:"5%"},
                    {data:"codigo",name:"codigo",width:"3%"},
                    {data:"cliente",name:"cliente",width:"18%"},
                    {data:"parcela",name:"parcela",width:"3%",className: 'dt-center'},
                    {data:"valor_plano",name:"valor_plano",width:"5%",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center'},
                    {data:"vencimento",name:"vencimento",width:"5%",className: 'dt-center'},
                    {data:"data_baixa",name:"data_baixa",width:"5%"},

                    {data:"valor",name:"valor",width:"3%",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'comissao_receber'},
                    {data:"desconto",name:"desconto",className:"desconto_atual",render: $.fn.dataTable.render.number('.',',',2,'R$ '),width:"3%"},

                    {
                        data: "contrato_id", name: "contrato_id", width: "1%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let contrato_id = cellData;
                            if(rowData.plano == 1) {
                                $(td).html(`<div class='text-center text-white'>
                                    <a href="/financeiro/detalhes/${contrato_id}" target="_blank" class="text-white">
                                        <i class='fas fa-eye'></i>
                                    </a>
                                </div>
                            `);
                            } else if(rowData.plano == 3) {
                                $(td).html(`<div class='text-center text-white'>
                                    <a href="/financeiro/detalhes/coletivo/${contrato_id}" target="_blank" class="text-white">
                                        <i class='fas fa-eye'></i>
                                    </a>
                                </div>
                            `);
                            } else {
                                $(td).html(`<div class='text-center text-white'>
                                    <a href="/financeiro/detalhes/empresarial/${contrato_id}" target="_blank" class="text-white">
                                        <i class='fas fa-eye'></i>
                                    </a>
                                </div>
                            `);
                            }



                        }
                    }
                ],
                "initComplete": function( settings, json ) {
                    $('#title_individual_confirmados_historico').html("<h4>Individual Confirmados</h4>");
                },
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    var intVal = function (i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };
                    conditionalTotal = 0;
                    conditionalRecebida = 0;
                    api.rows( { search: "applied" } ).every( function ( rowIdx, tableLoop, rowLoop ) {
                        var d = this.data();
                        conditionalTotal += intVal(d['comissao_esperada']);
                        conditionalRecebida += intVal(d['comissao_recebida']);
                        //qtdLinha = rowLoop + 1;
                    });
                    $("#previsao_de_comissao").html("Previsão da Comissão: "+conditionalTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
                }
            });





            var listaraptosapagar = $(".listaraptosapagar").DataTable({
                //dom: '<"flex justify-between"<"#title_individual_confirmados"><"estilizar_search"f>><"btns"B><"flex justify-between items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                dom: '<"flex justify-between items-center"<"#title_individual_confirmados"><"btns"B><"estilizar_search"f>>' +
                    'tr' +
                    '<"flex justify-between items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Exportar para Excel',
                        className: 'bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 hidden',
                        exportOptions: {
                            columns: [0, 1, 3, 5, 9, 13, 14, 15, 16, 17]
                        }
                    },
                    {
                        text: 'Exportar Tudo para Excel',
                        className: 'bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200 hidden',
                        action: function () {
                            exportAllToExcel();
                        }
                    }
                ],
                language: {
                    "search": "Pesquisar",
                    "paginate": {
                        "next": "Próx.",
                        "previous": "Ant.",
                        "first": "Primeiro",
                        "last": "Último"
                    },
                    "emptyTable": "Nenhum registro encontrado",
                    "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "infoFiltered": "(Filtrados de _MAX_ registros)",
                    "infoThousands": ".",
                    "loadingRecords": "Carregando...",
                    "processing": "Processando...",
                    "lengthMenu": "Exibir _MENU_ por página"
                },
                ajax: {
                    url:url_padrao,
                    dataSrc:""
                },
                "lengthMenu": [1000,2000,3000,4000,5000],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                processing: true,
                columns: [
                    {data:"administradora",name:"administradora",width:"4%"},
                    {data:"created_at",name:"created_at",width:"4%"},
                    {data:"codigo",name:"codigo",width:"3%"},
                    {data:"cliente",name:"cliente",width:"18%"},
                    {data:"parcela",name:"parcela",width:"3%",className: 'dt-center'},
                    {data:"valor_plano",name:"valor_plano",width:"5%",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center'},
                    {data:"vencimento",name:"vencimento",width:"5%",className: 'dt-center'},
                    {data:"data_baixa",name:"data_baixa",width:"5%"},
                    {data:"porcentagem",name:"porcentagem",width:"1%"},
                    {data:"valor",name:"valor",width:"3%",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'comissao_receber'},
                    {data:"desconto",name:"desconto",className:"desconto_atual",render: $.fn.dataTable.render.number('.',',',2,'R$ '),width:"3%"},
                    {data:"id",name:"id",width:"5%",className: 'dt-center',
                        "createdCell": function (td, cellData, rowData, row, col) {

                            $(td).html(`





                                <svg id="${cellData}" data-plano="${rowData.plano}" class="w-6 h-6 text-white removeButton" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V5m0 14-4-4m4 4 4-4"/>
                                </svg>

                                `)
                        }
                    },
                    {
                        data: "contrato_id", name: "contrato_id", width: "1%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let contrato_id = cellData;
                            if(rowData.plano == 1) {
                                $(td).html(`<div class='text-center text-white'>
                                    <a href="#" data-id="${contrato_id}" target="_blank" class="text-white ver_individual">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 div_info">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                    </a>
                                </div>
                            `);
                            } else if(rowData.plano == 3) {
                                $(td).html(`<div class='text-center text-white'>
                                    <a href="#" data-id="${contrato_id}" target="_blank" class="text-white ver_coletivo">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 div_info">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                    </a>
                                </div>
                            `);
                            } else {
                                $(td).html(`<div class='text-center text-white'>
                                    <a href="#" data-id="${contrato_id}" target="_blank" class="text-white ver_empresarial">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 div_info">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                    </a>
                                </div>
                            `);
                            }
                        }
                    },
                    {data:"plano_nome",name:"plano_nome",visible:false},
                    {data:"corretor",name:"corretor",visible:false},
                    {data:"quantidade_vidas",name:"quantidade",visible:false},
                    {data:"parcela",name:"parcela",visible:false},
                    {data:"codigo_externo",name:"codigo_externo",visible:false},

                ],
                "drawCallback":function(settings) {
                    if(settings.aoData.length >= 1) {

                        let title = $(settings.nTableWrapper.childNodes[0].childNodes[0].childNodes[0]).text();

                        let soma = 0;
                        let columnIndex = 10;
                        $(settings.nTable).find('tbody tr').each(function() {
                            let valor = parseFloat($(this).find('td:eq(' + columnIndex + ')').text().replace(',', '.').replace("R$ ","")) || 0;
                            soma += valor;
                        });

                        if (/Individual/.test(title)) {
                            $("#desconto_individual").val(soma.toFixed(2));
                        } else if (/Coletivo/.test(title)) {
                            $("#desconto_coletivo").val(soma.toFixed(2));
                        } else if (/Empresarial/.test(title)) {
                            $("#desconto_empresarial").val(soma.toFixed(2));
                        }


                    }



                },
                "initComplete": function( settings, json ) {
                    $('#title_individual_confirmados').html("<h4>Recebidas - Individual</h4>");
                },
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    var intVal = function (i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };
                    conditionalTotal = 0;
                    conditionalRecebida = 0;
                    api.rows( { search: "applied" } ).every( function ( rowIdx, tableLoop, rowLoop ) {
                        var d = this.data();
                        conditionalTotal += intVal(d['comissao_esperada']);
                        conditionalRecebida += intVal(d['comissao_recebida']);
                        //qtdLinha = rowLoop + 1;
                    });
                    $("#previsao_de_comissao").html("Previsão da Comissão: "+conditionalTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
                }
            });

            function exportAllToExcel() {
                let mes = $("#mes_folha option:selected").val();
                let ano = $("#mes_folha option:selected").text().split("/")[1];

                const urls = [
                    `{{ url('/gerente/mes/fechados/confirmados/${ano}/${mes}/1') }}`,  // Endpoint para dados de Individual
                    `{{ url('/gerente/mes/fechados/confirmados/${ano}/${mes}/3') }}`,  // Endpoint para dados de Coletivo
                    `{{ url('/gerente/mes/fechados/confirmados/${ano}/${mes}/0') }}`   // Endpoint para dados de Empresarial
                ];

                Promise.all(urls.map(url => fetch(url).then(res => res.json())))
                    .then(dataArrays => {
                        // Combine os dados em um único array
                        const combinedData = dataArrays.flat();

                        // Filtro: Apenas contratos com "valor" maior que 0 e "quantidade_vidas" maior que 1
                        const filteredData = combinedData.filter(item => {
                            return item.valor >= 0 && item.quantidade_vidas >= 1; // Condições ajustadas
                        });

                        // Prepare os dados para o Excel
                        const headers = ["Administradora","Data","Cliente","Valor","Pagar","Plano","Corretor","Vidas","Parcela","Cod.Externo"];
                        const rows = filteredData.map(item => [
                            item.administradora,
                            item.created_at,
                            item.cliente,
                            item.valor_plano,
                            item.valor,
                            item.plano_nome,
                            item.corretor,
                            item.quantidade_vidas,
                            item.parcela,
                            item.codigo_externo

                        ]);

                        // Use SheetJS para criar o arquivo Excel
                        const ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);
                        const wb = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(wb, ws, "Dados Filtrados");
                        XLSX.writeFile(wb, "dados_filtrados.xlsx");
                    })
                    .catch(error => {
                        console.error("Erro ao exportar os dados filtrados:", error);
                    });
            }








            $("body").on("click",".ver_individual",function(e){
                e.preventDefault();
                let id = $(this).attr("data-id");
                $('#myModalIndividual').removeClass('hidden');
                $.ajax({
                   url:"{{route('gerente.modal.individual')}}",
                   method:"POST",
                   data: {
                       id
                   },
                   success:function(res) {
                       $('.content-modal-individual').removeClass('hidden');
                       $(".content-modal-individual").html(res);
                   }
                });
                //
                return false;
            });

            $("body").on("click",".ver_coletivo",function(e){
               e.preventDefault();
               let id = $(this).attr("data-id");
               $('#myModalColetivo').removeClass('hidden');
               $.ajax({
                  url:"{{route('gerente.modal.coletivo')}}",
                  method:"POST",
                  data: {id},
                  success:function(res) {
                      $('.content-modal-coletivo').removeClass('hidden');
                      $(".content-modal-coletivo").html(res);
                  }
               });
               return false;
            });

            $("body").on('click','#closeModalEmpresarial',function(){
                $('#myModalEmpresarial').addClass('hidden');
                $('.content-modal-empresarial').addClass('hidden');
            });

            $("body").on("click",".ver_empresarial",function(e){
                e.preventDefault();

                let contrato_id = $(this).attr('data-id');

                $.ajax({
                   url:"{{route('financeiro.gerente.modal.contrato.empresarial')}}",
                   method:"POST",
                   data: {
                       id: contrato_id
                   },
                   success:function(res){
                       $('.content-modal-empresarial').removeClass('hidden');
                       $(".content-modal-empresarial").html(res);
                   }
                });



                $('#myModalEmpresarial').removeClass('hidden');
                return false;
            });








            function formatarMoeda(valor) {
                return parseFloat(valor).toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+\,)/g, '$1.');
            }



            function calcularValorTotal(id) {
                $("#loading-overlay").removeClass('ocultar');
                // let valorIndividual = parseFloat($('#valor_total_individual').text().replace(/\./g,'').replace(',', '.').trim());
                // let valorColetivo = parseFloat($('#valor_total_coletivo').text().replace(/\./g,'').replace(',', '.').trim());
                // let valorEmpresarial = parseFloat($('#valor_total_empresarial').text().replace(/\./g,'').replace(',', '.').trim());
                // let total = valorIndividual + valorColetivo + valorEmpresarial;
                // $("#comissao_vendedor").val(parseFloat(total).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}).replace("R$",""));
                // let salario_input = parseFloat($("#salario_vendedor").val().replace(/\./g,'').replace(',', '.').trim());
                // let premiacao_input = parseFloat($("#premiacao_vendedor").val().replace(/\./g,'').replace(',', '.').trim());
                // let comissao = $("#comissao_vendedor").val().replace(/\./g,'').replace(',', '.');
                // let desconto = $("#valor_total_desconto_vendedor").val().replace(/\./g,'').replace(',', '.');
                // let estorno = $("#valor_total_estorno_vendedor").val().replace(/\./g,'').replace(',', '.');
                // let total_valor = (parseFloat(comissao) + salario_input + premiacao_input) - desconto;
                // total_valor = total_valor - estorno;
                // $("#total_campo_vendedor").val(parseFloat(total_valor).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}).replace("R$",""));
                let mes               = $("#mes_folha").val();
                let ano               = $("#mes_folha option:selected").text().split("/")[1];

                let user_id           = $("#corretor_escolhido").val();
                let id_confirmados    = $("#valores_confirmados").val().split(",");
                let salario           = $("#salario_vendedor").val();
                let premiacao         = $("#premiacao_vendedor").val();
                let comissao_final    = $("#comissao_vendedor").val().trim();
                let desconto_final    = $("#valor_total_desconto_vendedor").val().trim();
                let total_final       = $("#total_campo_vendedor").val().trim();


                $.ajax({
                        url:"{{route('gerente.mudar.para_a_nao_pago')}}",
                        method:"POST",
                        data:
                            "id="+id+
                            "&mes="+mes+
                            "&ano="+ano+
                            "&user_id="+user_id+
                            "&salario="+salario+
                            "&premiacao="+premiacao+
                            "&comissao="+comissao_final+
                            "&desconto="+desconto_final+
                            "&total="+total_final+
                            "&id_confirmados="+id_confirmados,
                        success:function(res) {
                            console.log(res);
                            // if(res.resposta == "sucesso") {
                            //      //listaraptosapagar.ajax.reload();
                            //      listarcomissaomesrecebidas.ajax.reload();
                            //      listarcomissaomesdfirente.ajax.reload();
                            // } else {
                            //     let userId = res.user_id;
                            //     if ($('#escolher_vendedor option[value="' + userId + '"]').length === 0) {
                            //         $('#escolher_vendedor').append('<option value="' + res.user_id + '">' + res.name + '</option>');
                            //
                            //         let options = $('#escolher_vendedor option');
                            //         options.sort(function (a, b) {
                            //             return a.text.toUpperCase().localeCompare(b.text.toUpperCase());
                            //         });
                            //         $('#escolher_vendedor').html(options);
                            //
                            //         let elementoLista = $('#list_user').find('li span[data-id="' + userId + '"]').closest('li');
                            //
                            //         if (elementoLista.length > 0) {
                            //             elementoLista.remove();
                            //         }
                            //
                            //
                            //         $("#container_btns").removeClass("hidden").addClass("flex").addClass('flex-col');
                            //         $("#list_user").css("height","235px");
                            //     }
                            // }
                        },
                        complete: function () {
                            $("#loading-overlay").addClass('ocultar');
                        }
                    });
            }

            let queue = []; // Array para armazenar as requisições

// Função para processar a fila
            function processQueue() {
                if (queue.length === 0) return;

                let item = queue[0]; // Primeiro item da fila

                $.ajax({
                    url: "{{ route('gerente.mudar.para_a_nao_pago') }}",
                    method: "POST",
                    data: item.data,
                    success: function (res) {
                        if (res.resposta === "sucesso") {
                            listarcomissaomesrecebidas.ajax.reload();
                            listarcomissaomesdfirente.ajax.reload();
                        }
                        queue.shift(); // Remove o item da fila após o sucesso
                        processQueue(); // Processa o próximo item na fila
                    },
                    complete: function () {
                        $("#loading-overlay").addClass('ocultar');
                    },
                });
            }

// Evento do botão removeButton
            $('body').on('click', '.removeButton', function () {

                $("#loading-overlay").removeClass('ocultar');
                let row = listaraptosapagar.row($(this).parents('tr'));
                let desconto = listaraptosapagar.row($(this).parents('tr')).data().desconto;
                let comissao = listaraptosapagar.row($(this).parents('tr')).data().valor;

                let id = $(this).attr('id');
                //
                // let total_desconto = $("#valor_total_desconto_vendedor").val().replace(',', '.');
                // let desconto_refatorado = total_desconto - desconto;
                // let total = parseFloat(desconto_refatorado).toLocaleString('pt-br', { style: 'currency', currency: 'BRL' }).replace("R$", "");
                //
                // $("#valor_total_desconto_vendedor").val(total);
                //
                // let plano = $(this).attr('data-plano');
                // row.remove().draw(false);

                // if (plano == 1) recalculateIndividual();
                // else if (plano == 3) recalculateColetivo();
                // else recalculateEmpresarial();
                //
                // calcularValorTotal(id);

                // Adicionar requisição na fila
                let requestData = {
                    id: id,
                    mes: $("#mes_folha").val(),
                    ano: $("#mes_folha").find('option:selected').text().split("/")[1],
                    user_id: $("#corretor_escolhido").val(),
                    salario: $("#salario_vendedor").val(),
                    premiacao: $("#premiacao_vendedor").val(),
                    comissao,
                    desconto,
                    total: $("#total_campo_vendedor").val(),
                };

                $.ajax({
                    url: "{{ route('gerente.mudar.para_a_nao_pago') }}",
                    method: "POST",
                    data: requestData,
                    success: function (res) {
                            console.log(res);
                            $(".valor_individual_a_receber").text(res.valor_individual_a_receber);
                            $(".valor_coletivo_a_receber").text(res.valor_coletivo_a_receber);
                            $(".valor_empresarial_a_receber").text(res.valor_empresarial_a_receber);
                            $("#total_quantidade_individual").text(res.total_individual_quantidade);
                            $("#total_quantidade_coletivo").text(res.total_coletivo_quantidade);
                            $("#valor_total_individual").text(res.total_individual);
                            $("#valor_total_coletivo").text(res.total_coletivo);
                            $(".quantidade_estorno_empresarial").text(res.total_empresarial_quantidade_estorno);
                            $(".quantidade_estorno_individual").text(res.total_individual_quantidade_estorno);
                            $("#total_quantidade_empresarial").text(res.total_empresarial_quantidade);
                            $("#valor_total_empresarial").text(res.total_empresarial);
                            $("#comissao_vendedor").val(res.total_comissao);
                            $("#valores_confirmados").val(res.id_confirmados);
                            $("#salario_vendedor").val(res.total_salario);
                            $("#premiacao_vendedor").val(res.total_premiacao);
                            $("#valor_total_estorno_vendedor").val(res.estorno);
                            $("#valor_total_desconto_vendedor").val(res.desconto);
                            $(".quantidade_estorno_coletivo ").text(res.total_coletivo_quantidade_estorno);
                            $(".quantidade_estorno_empresarial").text(res.total_empresarial_quantidade_estorno);
                            $("#total_campo_vendedor").val(res.total);
                            $(".coletivo_a_receber").removeClass('ativo');
                            $(".individual_a_receber").removeClass('ativo');
                            $(".empresarial_a_receber").removeClass('ativo');
                            $(".individual_recebidas").removeClass('ativo');
                            $(".coletivo_recebidas").removeClass('ativo');
                            $(".empresarial_recebidas").removeClass('ativo');
                            $("#listar_individual_apto").removeClass('ativo');
                            $("#listar_coletivo_apto").removeClass('ativo');
                            $("#listar_empresarial_apto").removeClass('ativo');
                            $(".listar_estorno_ul li").removeClass('ativo');

                            $("#list_user").html(res.view);
                            $("#btn_fechar_mes").html('<button id="confirmBtn" >Fechar Mês</button>');
                            const select = $("#escolher_vendedor");
                            select.html('<option value="" class="text-center">--Corretores--</option>');
                            $.each(res.usuarios, function(index, corretor) {
                                const option = $("<option>").attr("value", corretor.id).text(corretor.name);
                                if (corretor.id == $("#corretor_escolhido").val()) {
                                    option.attr("selected", "selected");
                                }
                                select.append(option);
                            });
                            select.append("<option value='00'>--Finalizar--</option>");


                            // let total_a_pagar = parseFloat(res.total_comissao) - parseFloat(res.desconto)
                            if(parseFloat(res.total_comissao) > 0) {
                                $(".total_a_pagar").text(res.total);
                            } else {
                                $(".total_a_pagar").text(0);
                            }
                            listarcomissaomesrecebidas.ajax.reload();
                            listarcomissaomesdfirente.ajax.reload();
                    },
                    complete: function () {
                        $("#loading-overlay").addClass('ocultar');
                    },
                });







            });






            function recalculateIndividual() {
                let ind = $('#total_quantidade_individual').text();
                ind -= 1;
                $('#total_quantidade_individual').text(ind);
                let indAdd = parseInt($(".valor_individual_a_receber").text());
                indAdd += 1;
                $(".valor_individual_a_receber").text(indAdd);
                let column9Data = listaraptosapagar.column(9).data();
                let column10Data = listaraptosapagar.column(10).data();

                let sum = column9Data.reduce(function(a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);
                $("#valor_total_individual").text(formatarMoeda(sum));
            }

            function recalculateColetivo() {

                let col = $('#total_quantidade_coletivo').text();
                col -= 1;
                $('#total_quantidade_coletivo').text(col);
                let indCol = parseInt($(".valor_coletivo_a_receber").text());
                indCol += 1;
                $(".valor_coletivo_a_receber").text(indCol);
                let column9Data = listaraptosapagar.column(9).data();
                let column10Data = listaraptosapagar.column(10).data();
                let sum = column9Data.reduce(function(a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);
                let des = column10Data.reduce(function(a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);



                $("#valor_total_coletivo").text(formatarMoeda(sum));
                //$("#valor_total_desconto_vendedor").val(formatarMoeda(des));
            }



            function recalculateEmpresarial() {
                let emp = $('#total_quantidade_empresarial').text();
                emp -= 1;
                $('#total_quantidade_empresarial').text(emp);
                let indEmp = parseInt($(".valor_empresarial_a_receber").text());
                indEmp += 1;
                $(".valor_empresarial_a_receber").text(indEmp);
                let column9Data = listaraptosapagar.column(9).data();
                let column10Data = listaraptosapagar.column(10).data();
                let sum = column9Data.reduce(function(a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);
                $("#valor_total_empresarial").text(formatarMoeda(sum));
            }






            var tabela = $('#tabela_aptos_a_pagar_table').DataTable();

            var listarcomissaomesrecebidashistorico = $(".listarcomissaomesrecebidashistorico").DataTable({
                dom: '<"flex justify-between"<"#title_historico"><"estilizar_search"f>><t><"flex justify-between align-items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                language: {
                    "search": "Pesquisar",
                    "paginate": {
                        "next": "Próx.",
                        "previous": "Ant.",
                        "first": "Primeiro",
                        "last": "Último"
                    },
                    "emptyTable": "Nenhum registro encontrado",
                    "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "infoFiltered": "(Filtrados de _MAX_ registros)",
                    "infoThousands": ".",
                    "loadingRecords": "Carregando...",
                    "processing": "Processando...",
                    "lengthMenu": "Exibir _MENU_ por página"
                },
                ajax: {
                    url:url_padrao,
                    dataSrc:""
                },
                "lengthMenu": [1000,2000,3000],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                columns: [
                    {data:"administradora",name:"administradora",width:"8%"},
                    {data:"data_criacao",name:"data_criacao",width:"8%",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            let datas = cellData.split(" ")[0].split("-").reverse().join("/");
                            $(td).html(datas);
                        }
                    },
                    {data:"orcamento",name:"orcamento"},
                    {data:"cliente",name:"cliente",width:"30%",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            let palavras = cellData.ucWords();
                            let dados = palavras.split(" ");
                            if(dados.length >= 2) {
                                $(td).html(dados[0]+" "+dados[1]);
                            }
                        }
                    },
                    {data:"parcela",name:"parcela",width:"5%",className: 'dt-center'},
                    {data:"valor_plano_contratado",width:"8%",name:"valor_plano",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center'},
                    {data:"data",name:"data",width:"5%",className: 'dt-center'},
                    {data:"data_baixa_gerente",name:"baixa",width:"5%",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            if(cellData == null) {
                                //let alvo = rowData.data_antecipacao.split("-").reverse().join("/");
                                $(td).html("text");
                            } else {
                                let alvo = cellData.split("-").reverse().join("/")
                                $(td).html(alvo);
                            }
                        }
                    },
                    {data:"comissao_esperada",name:"comissao_esperada",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center comissao_recebida',
                        "createdCell":function(td, cellData, rowData, row, col) {

                            if(rowData.valor_pago != null) {
                                let valor = parseFloat(rowData.valor_pago).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
                                $(td).html(valor);
                            } else {
                                let valor = parseFloat(cellData).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
                                $(td).html(valor);
                            }
                        },
                        width:"8%"
                    },
                    {data:"porcentagem_parcela_corretor",name:"porcentagem_parcela_corretor",
                        "createdCell":function(td, cellData, rowData, row, col) {
                            if(rowData.porcentagem_paga == null) {
                                $(td).html('<input type="text" data-valor-plano='+rowData.valor_plano_contratado+' value='+cellData+' data-id='+rowData.id+' name="comissao_paga_change" class="comissao_paga_change" style="width:30px;" />')
                            } else {
                                $(td).html('<input type="text" data-valor-plano='+rowData.valor_plano_contratado+' value='+rowData.porcentagem_paga+' data-id='+rowData.id+' name="comissao_paga_change" class="comissao_paga_change" style="width:30px;" />')
                            }
                        },
                        width:"8%"
                    },
                    {data:"comissao_esperada",name:"comissao_pagando",
                        "createdCell":function(td, cellData, rowData, row, col) {
                            let valor = parseFloat(cellData).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
                            valor = valor.replace('R$', '');
                            $(td).html('<input type="text" data-id='+rowData.id+' name="comissao_pagando" value='+valor+' class="comissao_pagando" style="width:50px;" />')
                        }
                    },
                    {data:"quantidade_vidas",name:"quantidade_vidas"},
                    {data:"desconto",name:"desconto"},
                    {data:"id",name:"id",width:"5%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let selected = $('<select />', {
                                name: 'pagar_comissao',
                                class:"pagar_comissao",
                                'data-plano':rowData.plano,
                                id:cellData,
                                append : [
                                    $('<option />', {value : "1", text : "Em Aberto"}),
                                    $('<option />', {value : "2", text : "Pagar"}),
                                ]
                            });
                            $(td).html(selected)
                        }
                    },
                    {
                        data: "contrato_id", name: "contrato_id", width: "1%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let contrato_id = cellData;
                            if (rowData.plano == 3) {
                                $(td).html(`<div class='text-center text-white'>
                                        <a href="/financeiro/detalhes/coletivo/${contrato_id}" target="_blank" class="text-white">
                                            <i class='fas fa-eye'></i>
                                        </a>
                                    </div>
                                `);
                            } else if (rowData.plano == 1) {
                                $(td).html(`<div class='text-center text-white'>
                                        <a href="/financeiro/detalhes/${contrato_id}" target="_blank" class="text-white">
                                            <i class='fas fa-eye'></i>
                                        </a>
                                    </div>
                                `);
                            } else {
                                $(td).html(`<div class='text-center text-white'>
                                        <a href="/financeiro/detalhes/empresarial/${contrato_id}" target="_blank" class="text-white">
                                            <i class='fas fa-eye'></i>
                                        </a>
                                    </div>
                                `);
                            }
                        }
                    }
                ],
                "initComplete": function( settings, json ) {
                    $('#title_historico').html("<h4>Histórico</h4>");
                },
                footerCallback: function (row, data, start, end, display) {
                }
            });

            var listarcomissaomesrecebidas = $(".listarcomissaomesrecebidas").DataTable({
                dom: '<"flex justify-between"<"#title_recebidas"><"estilizar_search"f>><t><"flex justify-between items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                language: {
                    "search": "Pesquisar",
                    "paginate": {
                        "next": "Próx.",
                        "previous": "Ant.",
                        "first": "Primeiro",
                        "last": "Último"
                    },
                    "emptyTable": "Nenhum registro encontrado",
                    "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "infoFiltered": "(Filtrados de _MAX_ registros)",
                    "infoThousands": ".",
                    "loadingRecords": "Carregando...",
                    "processing": "Processando...",
                    "lengthMenu": "Exibir _MENU_ por página"
                },
                ajax: {
                    url:url_padrao,
                    dataSrc:""
                },
                "lengthMenu": [50,100,150,200,300,500],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                columns: [
                    {data:"administradora",name:"administradora",width:"8%"},
                    {data:"data_criacao",name:"data_criacao",width:"8%",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            let datas = cellData.split(" ")[0].split("-").reverse().join("/");
                            $(td).html(datas);
                        }
                    },
                    {data:"orcamento",name:"orcamento"},
                    {data:"cliente",name:"cliente",width:"30%",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            let palavras = cellData.ucWords();
                            let dados = palavras.split(" ");
                            if(dados.length >= 2) {
                                $(td).html(dados[0]+" "+dados[1]);
                            }
                        }
                    },
                    {data:"parcela",name:"parcela",width:"5%",className: 'dt-center'},
                    {data:"valor_plano_contratado",width:"8%",name:"valor_plano",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center'},
                    {data:"data",name:"data",width:"5%",className: 'dt-center'},
                    {data:"data_baixa_gerente",name:"baixa",width:"5%",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            if(cellData == null) {
                                //let alvo = rowData.data_antecipacao.split("-").reverse().join("/");
                                $(td).html("text");
                            } else {
                                let alvo = cellData.split("-").reverse().join("/")
                                $(td).html(alvo);
                            }
                        }
                    },
                    {data:"comissao_esperada",name:"comissao_esperada",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center comissao_recebida',
                        "createdCell":function(td, cellData, rowData, row, col) {

                            if(rowData.valor_pago != null) {
                                let valor = parseFloat(rowData.valor_pago).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
                                $(td).html(valor);
                            } else {
                                let valor = parseFloat(cellData).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
                                $(td).html(valor);
                            }
                        },
                        width:"8%"

                    },
                    {data:"porcentagem_parcela_corretor",name:"porcentagem_parcela_corretor",
                        "createdCell":function(td, cellData, rowData, row, col) {

                            if(rowData.porcentagem_paga == null) {
                                $(td).html('<input type="text" data-valor-plano='+rowData.valor_plano_contratado+' value='+cellData+' data-id='+rowData.id+' name="comissao_paga_change" class="comissao_paga_change" style="width:30px;" />')
                            } else {
                                $(td).html('<input type="text" data-valor-plano='+rowData.valor_plano_contratado+' value='+rowData.porcentagem_paga+' data-id='+rowData.id+' name="comissao_paga_change" class="comissao_paga_change" style="width:30px;" />')
                            }
                        },
                        width:"8%"
                    },
                    {data:"comissao_esperada",name:"comissao_pagando",
                        "createdCell":function(td, cellData, rowData, row, col) {
                            let valor = parseFloat(cellData).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
                            valor = valor.replace('R$', '');
                            $(td).html('<input type="text" data-id='+rowData.id+' name="comissao_pagando" value='+valor+' class="comissao_pagando" style="width:50px;" />')
                        }
                    },
                    {data:"quantidade_vidas",name:"quantidade_vidas"},
                    {data:"desconto",name:"desconto"},
                    {data:"id",name:"id",width:"5%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let selected = $('<select />', {
                                name: 'pagar_comissao',
                                class:"pagar_comissao",
                                'data-plano':rowData.plano,
                                id:cellData,
                                append : [
                                    $('<option />', {value : "1", text : "Em Aberto"}),
                                    $('<option />', {value : "2", text : "Pagar"}),
                                ]
                            });
                            $(td).html(selected)
                        }
                    },
                    {
                        data: "contrato_id", name: "contrato_id", width: "1%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let contrato_id = cellData;
                            if (rowData.plano == 3) {
                                $(td).html(`<div class='text-center text-white'>
                                        <a href="/financeiro/detalhes/coletivo/${contrato_id}" target="_blank" class="text-white">
                                            <i class='fas fa-eye'></i>
                                        </a>
                                    </div>
                                `);
                            } else if (rowData.plano == 1) {
                                $(td).html(`<div class='text-center text-white'>
                                        <a href="/financeiro/detalhes/${contrato_id}" target="_blank" class="text-white">
                                            <i class='fas fa-eye'></i>
                                        </a>
                                    </div>
                                `);
                            } else {
                                $(td).html(`<div class='text-center text-white'>
                                        <a href="/financeiro/detalhes/empresarial/${contrato_id}" target="_blank" class="text-white">
                                            <i class='fas fa-eye'></i>
                                        </a>
                                    </div>
                                `);
                            }
                        }
                    }
                ],

                "initComplete": function( settings, json ) {


                    $('#title_recebidas').html("<h4>Recebidas-Individual</h4>");
                },
                footerCallback: function (row, data, start, end, display) {

                }
            });

            function coletivo_recebidas() {
                let id = $("#corretor_escolhido").val();

                $("#listar_individual_apto").removeClass('ativo');
                $("#listar_coletivo_apto").removeClass('ativo');
                $(".individual_recebidas").removeClass('ativo');
                $(".individual_a_receber").removeClass('ativo');
                $(".coletivo_a_receber").removeClass('ativo');
                $(".empresarial_recebidas").removeClass('ativo');
                $(".lista_apto_a_pagar_ul li").removeClass("ativo");


                $(".listar_estorno_ul li").removeClass("ativo");
                $(this).addClass('ativo');
                if(id) {
                    $("#listar_individual_apto").removeClass("ativo");
                    if($("#tabela_principal").is(":visible")) {
                        $('#title_recebidas').html("<h4>Recebidas - Coletivo</h4>");
                        listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/recebidas/coletivo/${id}') }}`).load();
                    } else {

                        if($("#listar_a_receber").is(':visible')) {
                            $("#listar_a_receber").slideUp('slow',function(){
                                $("#tabela_principal").slideDown('slow',function(){
                                    $('#title_recebidas').html("<h4>Recebidas - Coletivo</h4>");
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/recebidas/coletivo/${id}') }}`).load();
                                });
                            })
                        }

                        if($("#tabela_aptos_a_pagar").is(":visible")) {
                            $("#tabela_aptos_a_pagar").slideUp('slow',function(){
                                $("#tabela_principal").slideDown('slow',function(){
                                    $('#title_recebidas').html("<h4>Recebidas - Coletivo</h4>");
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/recebidas/coletivo/${id}') }}`).load();
                                });
                            })
                        }

                        if($("#listar_cadastrados").is(":visible")) {
                            $("#listar_cadastrados").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>Recebidas - Coletivo</h4>")
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/recebidas/coletivo/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_estorno_table").is(':visible')) {
                            $("#tabela_estorno").slideUp('fast',function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>Recebidas - Coletivo</h4>")
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/recebidas/coletivo/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_estorno_table_back").is(":visible")) {
                            $("#tabela_estorno_back").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>Recebidas - Coletivo</h4>")
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/recebidas/coletivo/${id}') }}`).load();
                                });
                            });
                        }



                    }
                } else {
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false
                    }
                }
            }

            $(".coletivo_recebidas").on('click',coletivo_recebidas);

            function empresarial_recebidas() {
                let id = $("#corretor_escolhido").val();
                $("#listar_individual_apto").removeClass('ativo');
                $("#listar_coletivo_apto").removeClass('ativo');
                $(".individual_recebidas").removeClass('ativo');
                $(".individual_a_receber").removeClass('ativo');
                $(".coletivo_a_receber").removeClass('ativo');
                $(".coletivo_recebidas").removeClass('ativo');
                $(".listar_estorno_ul li").removeClass("ativo");

                $(this).addClass('ativo');

                if(id) {

                    $("#listar_individual_apto").removeClass("ativo");
                    if($("#tabela_principal").is(":visible")) {
                        $("#title_recebidas").html("<h4>Recebidas Empresarial</h4>")
                        listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/empresarial/recebidas/${id}') }}`).load();
                    } else {
                        if($("#listar_a_receber").is(':visible')) {
                            $("#listar_a_receber").slideUp('slow',function(){
                                $("#tabela_principal").slideDown('slow',function(){
                                    $("#title_recebidas").html("<h4>Recebidas Empresarial</h4>")
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/empresarial/recebidas/${id}') }}`).load();
                                });
                            })
                        }
                        if($("#tabela_aptos_a_pagar").is(":visible")) {
                            $("#tabela_aptos_a_pagar").slideUp('slow',function(){
                                $("#tabela_principal").slideDown('slow',function(){
                                    $("#title_recebidas").html("<h4>Recebidas Empresarial</h4>")
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/empresarial/recebidas/${id}') }}`).load();
                                });
                            })
                        }

                        if($("#listar_cadastrados").is(":visible")) {
                            $("#listar_cadastrados").slideUp(1000,function(){
                                $("#tabela_principal").slideDown('slow',function(){
                                    $("#title_recebidas").html("<h4>Recebidas Empresarial</h4>")
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/empresarial/recebidas/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_estorno_table").is(':visible')) {
                            $("#tabela_estorno").slideUp('fast',function(){
                                $("#tabela_principal").slideDown('slow',function(){
                                    $("#title_recebidas").html("<h4>Recebidas Empresarial</h4>")
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/empresarial/recebidas/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_estorno_table_back").is(":visible")) {
                            $("#tabela_estorno_back").slideUp(1000,function(){
                                $("#tabela_principal").slideDown('slow',function(){
                                    $("#title_recebidas").html("<h4>Recebidas Empresarial</h4>")
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/empresarial/recebidas/${id}') }}`).load();
                                });
                            });
                        }
                    }
                } else {
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false
                    }
                }
            }


            $(".empresarial_recebidas").on('click',empresarial_recebidas);

            $("#listar_empresarial_apto").on('click',function(){
                let id = $("#corretor_escolhido").val();
                let mes = $("#mes_folha").val();
                let ano = $("#mes_folha option:selected").text().split("/")[1];
                if(id) {
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    $(".listar_estorno_ul li").removeClass("ativo");
                    $("#listar_empresarial_apto").addClass("ativo");
                    if($("#tabela_principal").is(':visible')) {
                        $("#tabela_principal").slideUp('fast',function(){
                            if(mes == "") {
                                listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/empresarial/confirmadas/${id}') }}`).load();
                                $("#tabela_aptos_a_pagar").slideDown('slow');
                            } else {
                                listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/empresarial/confirmadas/${id}/${mes}/${ano}') }}`).load();
                                $("#tabela_aptos_a_pagar").slideDown('slow');
                            }
                        });
                    }
                    if($("#listar_a_receber").is(':visible')) {
                        $("#listar_a_receber").slideUp('fast',function(){
                            listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/empresarial/confirmadas/${id}/${mes}/${ano}') }}`).load();
                            $("#tabela_aptos_a_pagar").slideDown('slow');
                        });
                    } else {
                        $("#title_individual_confirmados").html("<h4>Recebidas - Empresarial</h4>");
                        listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/empresarial/confirmadas/${id}/${mes}/${ano}') }}`).load();
                    }

                    if($("#tabela_estorno_table").is(':visible')) {
                        $("#tabela_estorno").slideUp('fast',function(){
                            $("#title_individual_confirmados").html("<h4>Recebidas - Empresarial</h4>");
                            listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/empresarial/confirmadas/${id}/${mes}/${ano}') }}`).load();
                            $("#tabela_aptos_a_pagar").slideDown('slow');
                        });
                    } else {
                        $("#title_individual_confirmados").html("<h4>Recebidas - Empresarial</h4>");
                        listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/empresarial/confirmadas/${id}/${mes}/${ano}') }}`).load();
                    }
                    if($("#tabela_estorno_table_back").is(":visible")) {
                        $("#tabela_estorno_back").slideUp(1000,function(){
                            $("#tabela_aptos_a_pagar").slideDown('slow',function(){
                                $("#title_individual_confirmados").html("<h4>Recebidas - Empresarial</h4>");
                                listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/empresarial/confirmadas/${id}/${mes}/${ano}') }}`).load();
                            });
                        });
                    }
                } else {
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false
                    }
                }
            });


            function listar_apto_total_historico() {
                let mes = $("#mes_folha_historico").val();
                let plano = $(this).attr('data-plano');


                if(mes) {
                    let id = $(this).attr('id');

                    if(id == "listar_individual_apto_total_historico") {
                        $("#title_individual_confirmados_historico").html("<h4>Individual</h4>");
                        $("#listar_coletivo_apto_total_historico").removeClass("ativo");
                        $("#listar_empresarial_apto_total_historico").removeClass("ativo");
                        $("#listar_individual_apto_total_historico").addClass("ativo");

                    } else if(id == "listar_coletivo_apto_total_historico") {
                        $("#title_individual_confirmados_historico").html("<h4>Coletivo</h4>");
                        $("#listar_coletivo_apto_total_historico").addClass("ativo");
                        $("#listar_empresarial_apto_total_historico").removeClass("ativo");
                        $("#listar_individual_apto_total_historico").removeClass("ativo");



                    } else if(id == "listar_empresarial_apto_total_historico") {
                        $("#title_individual_confirmados_historico").html("<h4>Empresarial</h4>");
                        $("#listar_coletivo_apto_total_historico").removeClass("ativo");
                        $("#listar_empresarial_apto_total_historico").addClass("ativo");
                        $("#listar_individual_apto_total_historico").removeClass("ativo");

                    }
                }

                if($("#tabela_principal_historico").is(":visible")) {
                    $("#tabela_principal_historico").slideUp(1000,function(){
                        ///$("#title_individual_confirmados_historico").html("<h4>Individual</h4>");
                        ///listaraptosapagarhistorico.ajax.url(`{{ url('/gerente/comissao/confirmadas/${id}/${mes}') }}`).load();
                        listaraptosapagarhistorico.ajax.url(`{{ url('/gerente/mes/fechados/confirmados/${mes}/${plano}') }}`).load();
                        $("#tabela_aptos_a_pagar_historico").slideDown('slow');
                    });
                } else {
                    listaraptosapagarhistorico.ajax.url(`{{ url('/gerente/mes/fechados/confirmados/${mes}/${plano}') }}`).load();
                    $("#tabela_aptos_a_pagar_historico").slideDown('slow');
                }





            }






            function listar_apto_total() {

                let mes = $("#mes_folha option:selected").val();
                let ano = $("#mes_folha option:selected").text().split("/")[1];
                let plano = $(this).attr('data-plano');
                let title = "";
                if(plano == 1) {
                    title = "Recebidos Individual";
                } else if(plano == 3) {
                    title = "Recebidos Coletivo";
                } else {
                    title = "Recebidos Empresarial";
                }


                $("#title_individual_confirmados").html(`<h4>${title}</h4>`);


                if(mes) {


                    if($("#tabela_principal").is(":visible")) {
                        $("#tabela_principal").slideUp(1000,function(){
                            listaraptosapagar.ajax.url(`{{ url('/gerente/mes/fechados/confirmados/${ano}/${mes}/${plano}') }}`).load();
                            $("#tabela_aptos_a_pagar").slideDown('slow');
                        });
                    }



                    $("#listar_coletivo_apto").removeClass("ativo");
                    $("#listar_empresarial_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    $(".listar_estorno_ul li").removeClass("ativo");
                    let id = $(this).attr('id');

                    if(id == "listar_individual_apto_total") {
                        $("#listar_coletivo_apto_total").removeClass("ativo");
                        $("#listar_empresarial_apto_total").removeClass("ativo");
                        $("#listar_individual_apto_total").addClass("ativo");
                    } else if(id == "listar_coletivo_apto_total") {
                        $("#listar_empresarial_apto_total").removeClass("ativo");
                        $("#listar_individual_apto_total").removeClass("ativo");
                        $("#listar_coletivo_apto_total").addClass("ativo");
                    } else {
                        $("#listar_individual_apto_total").removeClass("ativo");
                        $("#listar_coletivo_apto_total").removeClass("ativo");
                        $("#listar_empresarial_apto_total").addClass("ativo");
                    }

                    if($("#listar_a_receber").is(":visible")) {
                        $("#listar_a_receber").slideUp(1000,function(){
                            listaraptosapagar.ajax.url(`{{ url('/gerente/mes/fechados/confirmados/${ano}/${mes}/${plano}') }}`).load();
                            $("#tabela_aptos_a_pagar").slideDown('slow');
                        })
                    }

                    if($("#tabela_principal").is(":visible")) {
                        $("#tabela_principal").slideUp(1000,function(){
                            listaraptosapagar.ajax.url(`{{ url('/gerente/mes/fechados/confirmados/${ano}/${mes}/${plano}') }}`).load();
                            $("#tabela_aptos_a_pagar").slideDown('slow');
                        });
                    }

                    if($("#tabela_aptos_a_pagar").is(":visible")) {
                        listaraptosapagar.ajax.url(`{{ url('/gerente/mes/fechados/confirmados/${ano}/${mes}/${plano}') }}`).load();
                        $("#tabela_aptos_a_pagar").slideDown('slow');
                    }

                    if($("#tabela_estorno_table").is(':visible')) {
                        $("#tabela_estorno").slideUp('fast',function(){
                            listaraptosapagar.ajax.url(`{{ url('/gerente/mes/fechados/confirmados/${ano}/${mes}/${plano}') }}`).load();
                            $("#tabela_aptos_a_pagar").slideDown('slow');
                        });
                    }

                    if($("#tabela_estorno_table_back").is(":visible")) {
                        $("#tabela_estorno_back").slideUp(1000,function(){
                            listaraptosapagar.ajax.url(`{{ url('/gerente/mes/fechados/confirmados/${ano}/${mes}/${plano}') }}`).load();
                            $("#tabela_aptos_a_pagar").slideDown('slow');
                        });
                    }



                }
            }





            $("#listar_individual_apto_total").on('click',listar_apto_total);
            $("#listar_coletivo_apto_total").on('click',listar_apto_total);
            $("#listar_empresarial_apto_total").on('click',listar_apto_total);




            $("#listar_individual_apto_total_historico").on('click',listar_apto_total_historico);
            $("#listar_coletivo_apto_total_historico").on('click',listar_apto_total_historico);
            $("#listar_empresarial_apto_total_historico").on('click',listar_apto_total_historico);



            $("#listar_individual_apto_historico").on('click',function(){
                let id = $("#corretor_escolhido_historico").val();
                let mes = $("#mes_folha_historico option:selected").val();
                let ano = $("#ano_folha_historico option:selected").val();




                if(id) {
                    $("#listar_individual_apto_historico").addClass("ativo");
                    $("#listar_coletivo_apto_historico").removeClass("ativo");
                    $("#listar_empresarial_apto_historico").removeClass("ativo");
                    if($("#tabela_principal_historico").is(':visible')) {
                        $("#tabela_principal_historico").slideUp('fast',function(){
                            $("#title_individual_confirmados_historico").html("<h4>Individual</h4>");
                            listaraptosapagarhistorico.ajax.url(`{{ url('/gerente/comissao/confirmadas/${id}/${mes}/${ano}') }}`).load();
                            $("#tabela_aptos_a_pagar_historico").slideDown('slow');
                        });
                    }
                    if($("#tabela_aptos_a_pagar_table_historico").is(":visible")) {
                        $("#title_individual_confirmados_historico").html("<h4>Individual</h4>");
                        listaraptosapagarhistorico.ajax.url(`{{ url('/gerente/comissao/confirmadas/${id}/${mes}/${ano}') }}`).load();
                    }

                    $("#listar_individual_apto_historico").addClass("ativo");
                    $("#listar_coletivo_apto_historico").removeClass("ativo");
                    $("#listar_empresarial_apto_historico").removeClass("ativo");
                    if($("#tabela_principal_historico").is(':visible')) {
                        $("#tabela_principal_historico").slideUp('fast',function(){
                            $("#title_individual_confirmados_historico").html("<h4>Individual</h4>");
                            listaraptosapagarhistorico.ajax.url(`{{ url('/gerente/comissao/confirmadas/${id}/${mes}/${ano}') }}`).load();
                            $("#tabela_aptos_a_pagar_historico").slideDown('slow');
                        });
                    }
                    if($("#tabela_aptos_a_pagar_table_historico").is(":visible")) {
                        $("#title_individual_confirmados_historico").html("<h4>Individual</h4>");
                        listaraptosapagarhistorico.ajax.url(`{{ url('/gerente/comissao/confirmadas/${id}/${mes}/${ano}') }}`).load();
                    }

                } else {
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false

                    }
                }




            });

            $("#listar_coletivo_apto_historico").on('click',function(){

                let id = $("#corretor_escolhido_historico").val();
                let mes = $("#mes_folha_historico option:selected").val();
                let ano = $("#ano_folha_historico option:selected").val();

                if(id) {

                    $("#listar_individual_apto_historico").removeClass("ativo");
                    $("#listar_coletivo_apto_historico").addClass("ativo");
                    $("#listar_empresarial_apto_historico").removeClass("ativo");

                    if($("#tabela_principal_historico").is(':visible')) {
                        $("#tabela_principal_historico").slideUp('fast',function(){
                            $("#title_individual_confirmados_historico").html("<h4>Coletivo</h4>");
                            listaraptosapagarhistorico.ajax.url(`{{ url('/gerente/comissao/coletivo/confirmadas/${id}/${mes}/${ano}') }}`).load();
                            $("#tabela_aptos_a_pagar_historico").slideDown('slow');
                        });
                    }

                    if($("#tabela_aptos_a_pagar_table_historico").is(":visible")) {
                        $("#title_individual_confirmados_historico").html("<h4>Coletivo</h4>");
                        listaraptosapagarhistorico.ajax.url(`{{ url('/gerente/comissao/coletivo/confirmadas/${id}/${mes}/${ano}') }}`).load();
                    }


                } else {
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false

                    }
                }

            });

            $("#listar_empresarial_apto_historico").on('click',function(){
                let id = $("#corretor_escolhido_historico").val();
                let mes = $("#mes_folha_historico option:selected").val();
                let ano = $("#ano_folha_historico option:selected").val();





                if(id) {

                    $("#listar_individual_apto_historico").removeClass("ativo");
                    $("#listar_coletivo_apto_historico").removeClass("ativo");
                    $("#listar_empresarial_apto_historico").addClass("ativo");

                    if($("#tabela_principal_historico").is(':visible')) {
                        $("#tabela_principal_historico").slideUp('fast',function(){
                            $("#title_individual_confirmados_historico").html("<h4>Empresarial</h4>");
                            listaraptosapagarhistorico.ajax.url(`{{ url('/gerente/comissao/empresarial/confirmadas/${id}/${mes}/${ano}') }}`).load();
                            $("#tabela_aptos_a_pagar_historico").slideDown('slow');
                        });
                    }

                    if($("#tabela_aptos_a_pagar_table_historico").is(":visible")) {
                        $("#title_individual_confirmados_historico").html("<h4>Empresarial</h4>");
                        listaraptosapagarhistorico.ajax.url(`{{ url('/gerente/comissao/empresarial/confirmadas/${id}/${mes}/${ano}') }}`).load();
                    }
                } else {
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false

                    }
                }

            });







            $("#listar_individual_apto").on('click',function(){

                let id = $("#corretor_escolhido").val();
                let mes = $("#mes_folha").val();
                let ano = $("#mes_folha").find('option:selected').text().split("/")[1];
                if(id) {

                    $("#listar_coletivo_apto").removeClass("ativo");
                    $("#listar_empresarial_apto").removeClass("ativo");

                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").addClass("ativo");

                    $(".listar_estorno_ul li").removeClass("ativo");

                    if($("#tabela_principal").is(':visible')) {
                        $("#tabela_principal").slideUp('fast',function(){
                            if(mes == "") {
                                listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/confirmadas/${id}/${mes}/${ano}') }}`).load();
                                $("#tabela_aptos_a_pagar").slideDown('slow');
                            } else {
                                listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/confirmadas/${id}/${mes}/${ano}') }}`).load();
                                $("#tabela_aptos_a_pagar").slideDown('slow');
                            }
                        });
                    }

                    if($("#listar_a_receber").is(':visible')) {
                        $("#listar_a_receber").slideUp('fast',function(){
                            $("#title_individual_confirmados").html("<h4>Recebidas - Individual</h4>");
                            listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/confirmadas/${id}/${mes}/${ano}') }}`).load();
                            $("#tabela_aptos_a_pagar").slideDown('slow');
                        });
                    }


                    if($("#tabela_estorno_table").is(':visible')) {
                        $("#tabela_estorno").slideUp('fast',function(){
                            $("#title_individual_confirmados").html("<h4>Recebidas - Individual</h4>");
                            listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/confirmadas/${id}/${mes}/${ano}') }}`).load();
                            $("#tabela_aptos_a_pagar").slideDown('slow');
                        });
                    }

                    if($("#tabela_aptos_a_pagar_table").is(":visible")) {
                        $("#title_individual_confirmados").html("<h4>Recebidas - Individual</h4>");
                        listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/confirmadas/${id}/${mes}/${ano}') }}`).load();
                    }

                    if($("#tabela_estorno_table_back").is(":visible")) {
                        $("#tabela_estorno_back").slideUp(1000,function(){
                            $("#tabela_aptos_a_pagar").slideDown('slow',function(){
                                $("#title_individual_confirmados").html("<h4>Recebidas - Individual</h4>");
                                listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/confirmadas/${id}/${mes}/${ano}') }}`).load();
                            });
                        });
                    }




                } else {

                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");

                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false

                    }


                }



            });

            $('body').on('keydown', 'input.comissao_paga_change', function(event) {
                if (event.key === 'Tab') {
                    let currentRow = $(this).closest('tr');
                    let alvo = currentRow.find(".pagar_comissao_up")[0];

                    // Adiciona tabindex para tornar o elemento focável
                    alvo.setAttribute('tabindex', '0');

                    // Move o foco para o elemento
                    alvo.focus();

                    // Impede o comportamento padrão do TAB
                    event.preventDefault();
                }
            });

            $('body').on('keydown', '.pagar_comissao_up', function(event) {
                if (event.key === 'Tab') {
                    let currentRow = $(this).closest('tr');

                    // Encontrar o próximo campo comissao_paga_change na linha seguinte
                    let nextComissaoField = currentRow.next('tr').find('input.comissao_paga_change');

                    // Se não houver próximo campo na linha seguinte, volte para o início da tabela
                    if (nextComissaoField.length === 0) {
                        nextComissaoField = listarcomissaomesdfirente.cell(0, 0).nodes().to$().find('input.comissao_paga_change');
                    }

                    // Move o foco para o próximo campo comissao_paga_change
                    nextComissaoField.focus();

                    // Impede o comportamento padrão do TAB
                    event.preventDefault();
                }
            });






            $("body").on('click','.comissao_pagando',function(){
                $(this).prop('readonly', false);
            });



            $("body").on('change','.comissao_pagando',function(){

                let id = $(this).attr('data-id');
                let valor = $(this).val();

                let valor_plano = $(this).closest("tr").children("td:nth-child(6)").text();
                let self = $(this);
                if(valor == 0) {
                    self.closest("tr").find(".porcentagem_change").val(0);
                }
                $.ajax({
                    url:"{{route('gerente.mudar.valor.pago')}}",
                    method:"POST",
                    data:"id="+id+"&valor="+valor+"&valor_plano="+valor_plano,
                    success:function(res) {

                        self.closest("tr").find(".comissao_paga_change").val(res.porcentagem);
                        self.closest("tr").find(".comissao_pagando").val(res.valor);

                    }
                });
            });

            $("body").on('change',".comissao_paga_change",function(){




                let id = $("#escolher_vendedor").val();
                let valor = $(this).val();
                let valor_plano = $(this).attr('data-valor-plano');
                let default_corretor = $(this).attr('data-id');
                let self = $(this);

                if(valor == 0) {
                    self.closest('tr').find('.porcentagem_change').val(0);
                }


                $.ajax({
                    url:"{{route('gerente.mudar.valor.corretor')}}",
                    method:"POST",
                    data:"id="+id+"&valor="+valor+"&valor_plano="+valor_plano+"&default_corretor="+default_corretor+"&acao=porcentagem",
                    success:function(res) {
                        //console.log(res);
                        // console.log("valor ",res.valor);
                        // console.log("porcentagem ",res.porcentagem);
                        self.closest('tr').find('.comissao_pagando').val(res.valor)
                        self.closest('tr').find('.comissao_paga_change').val(res.porcentagem);
                        listarcomissaomesrecebidas.ajax.reload();
                    }
                });
            });

            function empresarial_a_receber() {

                $(".estilizar_search input[type='search']").val('');
                listarcomissaomesdfirente.search('').draw();
                listarcomissaomesrecebidas.search('').draw();
                listaraptosapagar.search('').draw();

                let id = $("#corretor_escolhido").val();
                $("#listar_individual_apto").removeClass("ativo");
                $("#listar_coletivo_apto").removeClass("ativo");
                $(".individual_recebidas").removeClass('ativo');
                $(".coletivo_recebidas").removeClass('ativo');
                $(".individual_a_receber").removeClass('ativo');
                $(".coletivo_a_receber").removeClass('ativo');
                $(".listar_estorno_ul li").removeClass('ativo');
                $(this).addClass('ativo');
                if(id) {
                    if($("#listar_a_receber").is(":visible")) {
                        $("#title_comissao_diferente").html("<h4>A Receber Empresarial</h4>")
                        listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/empresarial/listar/${id}') }}`).load();
                    } else {

                        if($("#tabela_principal").is(":visible")) {
                            $("#tabela_principal").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Empresarial</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/empresarial/listar/${id}') }}`).load();
                                });
                            });
                        }
                        if($("#tabela_aptos_a_pagar").is(":visible")) {
                            $("#tabela_aptos_a_pagar").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Empresarial</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/empresarial/listar/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#listar_cadastrados").is(":visible")) {
                            $("#listar_cadastrados").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Empresarial</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/empresarial/listar/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_estorno_table").is(':visible')) {
                            $("#tabela_estorno").slideUp('fast',function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Empresarial</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/empresarial/listar/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_estorno_table_back").is(":visible")) {
                            $("#tabela_estorno_back").slideUp(1000,function(){
                                $("#listar_a_receber").slideDown('slow',function(){
                                    $("#title_comissao_diferente").html("<h4>A Receber Empresarial</h4>")
                                    listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/empresarial/listar/${id}') }}`).load();
                                });
                            });
                        }






                    }
                } else {
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false
                    }
                }
            }
            $(".empresarial_a_receber").on('click',empresarial_a_receber);


            {{--$("body").on('click', '.pagar_comissao_up', function () {--}}
            {{--    $("#loading-overlay").removeClass('ocultar');--}}

            {{--    // Variáveis principais--}}
            {{--    let mes = $("#mes_folha option:selected").val();--}}
            {{--    let ano = $("#mes_folha").find('option:selected').text().split("/")[1];--}}
            {{--    let id = $(this).attr('id');--}}
            {{--    let plano = $(this).data('plano');--}}
            {{--    let linha = $(this).closest('tr');--}}
            {{--    let desconto = parseFloat(linha.find("input[name='porcentagem_change']").val().replace(/\./g, '').replace(',', '.'));--}}
            {{--    let comissao_pagando = linha.find('.comissao_pagando').val();--}}
            {{--    let comissao_recebida = comissao_pagando--}}
            {{--        ? parseFloat(comissao_pagando.replace(/\./g, '').replace(',', '.'))--}}
            {{--        : parseFloat(linha.find('.comissao_recebida').text().replace("R$", "").replace(/\./g, '').replace(',', '.'));--}}

            {{--    id_confirmados.push(id);--}}
            {{--    $("#valores_confirmados").val(id_confirmados);--}}

            {{--    // Função para atualizar valores de seção--}}
            {{--    const atualizarValores = (totalSeletor, qtdSeletor, valorSeletor, qtdAtual) => {--}}
            {{--        let valorTotal = parseFloat($(totalSeletor).text().replace("R$", "").replace(/\./g, '').replace(',', '.'));--}}
            {{--        $(qtdSeletor).text(qtdAtual + 1);--}}
            {{--        let novoTotal = valorTotal + comissao_recebida;--}}
            {{--        $(totalSeletor).text(novoTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }).replace("R$", ""));--}}
            {{--        let valorAReceber = $(valorSeletor).text();--}}
            {{--        $(valorSeletor).text(valorAReceber - 1);--}}
            {{--    };--}}

            {{--    // Atualizar valores baseados no plano--}}
            {{--    if (plano === 1) {--}}
            {{--        atualizarValores("#valor_total_individual", "#total_quantidade_individual", ".valor_individual_a_receber", parseInt($("#total_quantidade_individual").text()));--}}
            {{--    } else if (plano === 3) {--}}
            {{--        atualizarValores("#valor_total_coletivo", "#total_quantidade_coletivo", ".valor_coletivo_a_receber", parseInt($("#total_quantidade_coletivo").text()));--}}
            {{--    } else {--}}
            {{--        atualizarValores("#valor_total_empresarial", "#total_quantidade_empresarial", ".valor_empresarial_a_receber", parseInt($("#total_quantidade_empresarial").text()));--}}
            {{--    }--}}

            {{--    // Atualizar descontos--}}
            {{--    let totalDescontoAtual = parseFloat($("#valor_total_desconto_vendedor").val().replace(/\./g, '').replace(',', '.')) || 0;--}}
            {{--    let novoDesconto = totalDescontoAtual + desconto;--}}
            {{--    $("#valor_total_desconto_vendedor").val(novoDesconto.toLocaleString('pt-BR', { minimumFractionDigits: 2 }));--}}

            {{--    // Atualizar comissão total--}}
            {{--    let comissaoAtual = parseFloat($("#comissao_vendedor").val().replace(/\./g, '').replace(',', '.')) || 0;--}}
            {{--    let novaComissao = comissaoAtual + comissao_recebida;--}}
            {{--    $("#comissao_vendedor").val(novaComissao.toLocaleString('pt-BR', { minimumFractionDigits: 2 }));--}}

            {{--    // Ocultar a linha atual--}}
            {{--    linha.slideUp('fast').addClass('pagar');--}}

            {{--    // Cálculo do total do vendedor--}}
            {{--    const calcularTotalVendedor = () => {--}}
            {{--        const pegarValor = (seletor) => parseFloat($(seletor).val().replace(/\./g, '').replace(',', '.')) || 0;--}}

            {{--        let salario = pegarValor("#salario_vendedor");--}}
            {{--        let comissao = pegarValor("#comissao_vendedor");--}}
            {{--        let premiacao = pegarValor("#premiacao_vendedor");--}}
            {{--        let totalDesconto = pegarValor("#valor_total_desconto_vendedor");--}}
            {{--        let estorno = pegarValor("#valor_total_estorno_vendedor");--}}

            {{--        let ganhos = salario + comissao + premiacao;--}}
            {{--        let totalFinal = ganhos - totalDesconto - estorno;--}}

            {{--        $("#total_campo_vendedor").val(totalFinal.toLocaleString('pt-BR', { minimumFractionDigits: 2 }));--}}
            {{--    };--}}
            {{--    calcularTotalVendedor();--}}

            {{--    // Limpar campos de busca--}}
            {{--    $(".estilizar_search input[type='search']").val('');--}}
            {{--    listarcomissaomesdfirente.search('').draw();--}}
            {{--    listarcomissaomesrecebidas.search('').draw();--}}
            {{--    listaraptosapagar.search('').draw();--}}

            {{--    // Remover a linha da tabela--}}
            {{--    listarcomissaomesdfirente.row(linha).remove().draw();--}}

            {{--    // Enviar dados via AJAX--}}
            {{--    $.ajax({--}}
            {{--        url: "{{route('gerente.aptar.pagamento')}}",--}}
            {{--        method: "POST",--}}
            {{--        data: {--}}
            {{--            id: id,--}}
            {{--            mes: mes,--}}
            {{--            ano: ano,--}}
            {{--            desconto: novoDesconto,--}}
            {{--            salario: $("#salario_vendedor").val(),--}}
            {{--            comissao: $("#comissao_vendedor").val(),--}}
            {{--            premiacao: $("#premiacao_vendedor").val(),--}}
            {{--            estorno: $("#valor_total_estorno_vendedor").val(),--}}
            {{--            user_id: $("#corretor_escolhido").val(),--}}
            {{--            total: $("#total_campo_vendedor").val()--}}
            {{--        },--}}
            {{--        complete: function () {--}}
            {{--            $("#loading-overlay").addClass('ocultar');--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}

            $("body").on('click', '.pagar_comissao_up', function () {
                $("#loading-overlay").removeClass('ocultar');

                // Coleta de dados
                const elemento = $(this);
                const mes = $("#mes_folha option:selected").val();
                const ano = $("#mes_folha").find('option:selected').text().split("/")[1];
                const id = elemento.attr('id');
                const plano = elemento.data('plano');
                const linha = elemento.closest('tr');
                const desconto = parseFloat(linha.find("input[name='porcentagem_change']").val().replace(/\./g, '').replace(',', '.'));
                const comissao_pagando = linha.find('.comissao_pagando').val();
                const comissao_recebida = comissao_pagando
                    ? parseFloat(comissao_pagando.replace(/\./g, '').replace(',', '.'))
                    : parseFloat(linha.find('.comissao_recebida').text().replace("R$", "").replace(/\./g, '').replace(',', '.'));




                // Dados para enviar ao backend
                const dadosRequest = {
                    id: id,
                    mes: mes,
                    ano: ano,
                    desconto: desconto,
                    salario: $("#salario_vendedor").val(),
                    comissao: comissao_recebida, // Ajuste conforme necessário
                    premiacao: $("#premiacao_vendedor").val(),
                    estorno: $("#valor_total_estorno_vendedor").val(),
                    user_id: $("#corretor_escolhido").val(),
                    total: $("#total_campo_vendedor").val()
                };



                // Requisição AJAX
                $.ajax({
                    url: "{{route('gerente.aptar.pagamento')}}",
                    method: "POST",
                    data: dadosRequest,
                    success: function (res) {
                        //total_quantidade_individual

                        if(res != "error") {
                            console.log(res);
                            $(".valor_individual_a_receber").text(res.valor_individual_a_receber);
                            $(".valor_coletivo_a_receber").text(res.valor_coletivo_a_receber);
                            $(".valor_empresarial_a_receber").text(res.valor_empresarial_a_receber);
                            $("#total_quantidade_individual").text(res.total_individual_quantidade);
                            $("#total_quantidade_coletivo").text(res.total_coletivo_quantidade);
                            $("#valor_total_individual").text(res.total_individual);
                            $("#valor_total_coletivo").text(res.total_coletivo);
                            $(".quantidade_estorno_empresarial").text(res.total_empresarial_quantidade_estorno);
                            $(".quantidade_estorno_individual").text(res.total_individual_quantidade_estorno);
                            $("#total_quantidade_empresarial").text(res.total_empresarial_quantidade);
                            $("#valor_total_empresarial").text(res.total_empresarial);
                            $("#comissao_vendedor").val(res.total_comissao);
                            $("#valores_confirmados").val(res.id_confirmados);
                            $("#salario_vendedor").val(res.total_salario);
                            $("#premiacao_vendedor").val(res.total_premiacao);
                            $("#valor_total_estorno_vendedor").val(res.estorno);
                            $("#valor_total_desconto_vendedor").val(res.desconto);
                            $(".quantidade_estorno_coletivo ").text(res.total_coletivo_quantidade_estorno);
                            $(".quantidade_estorno_empresarial").text(res.total_empresarial_quantidade_estorno);
                            $("#total_campo_vendedor").val(res.total);
                            $(".coletivo_a_receber").removeClass('ativo');
                            $(".individual_a_receber").removeClass('ativo');
                            $(".empresarial_a_receber").removeClass('ativo');
                            $(".individual_recebidas").removeClass('ativo');
                            $(".coletivo_recebidas").removeClass('ativo');
                            $(".empresarial_recebidas").removeClass('ativo');
                            $("#listar_individual_apto").removeClass('ativo');
                            $("#listar_coletivo_apto").removeClass('ativo');
                            $("#listar_empresarial_apto").removeClass('ativo');
                            $(".listar_estorno_ul li").removeClass('ativo');


                            $("#total_quantidade_individual_total").text(res.total_individual_quantidade_geral);
                            $("#valor_total_individual_total").text(res.total_individual_geral);

                            $("#total_quantidade_coletivo_total").text(res.total_coletivo_quantidade_geral);
                            $("#valor_total_coletivo_total").text(res.total_coletivo_geral);

                            $("#total_quantidade_empresarial_total").text(res.total_empresarial_quantidade_geral);
                            $("#valor_total_empresarial_total").text(res.total_empresarial_geral);


                            $("#salario").val(res.valores_geral.salario);
                            $("#comissao").val(res.valores_geral.comissao);
                            $("#premiacao").val(res.valores_geral.premiacao);
                            $("#estorno_geral").val(res.valores_geral.estorno);
                            $("#valor_total_desconto").val(res.valores_geral.desconto);
                            $("#total_campo").val(res.valores_geral.total)


                            $("#list_user").html(res.view);
                            $("#btn_fechar_mes").html('<button id="confirmBtn" >Fechar Mês</button>');
                            const select = $("#escolher_vendedor");
                            select.html('<option value="" class="text-center">--Corretores--</option>');
                            $.each(res.usuarios, function(index, corretor) {
                                const option = $("<option>").attr("value", corretor.id).text(corretor.name);
                                if (corretor.id == $("#corretor_escolhido").val()) {
                                    option.attr("selected", "selected");
                                }
                                select.append(option);
                            });
                            select.append("<option value='00'>--Finalizar--</option>");


                            // let total_a_pagar = parseFloat(res.total_comissao) - parseFloat(res.desconto)
                            if(parseFloat(res.total_comissao) > 0) {
                                $(".total_a_pagar").text(res.total);
                            } else {
                                $(".total_a_pagar").text(0);
                            }
                        }
                        // Atualizações do Frontend APÓS sucesso
                        //id_confirmados.push(id);
                        //$("#valores_confirmados").val(id_confirmados);

                        // Função de atualização (extrair para reutilizar se necessário)
                       //  const atualizarValores = (totalSeletor, qtdSeletor, valorSeletor, qtdAtual) => {
                       //      const valorTotal = parseFloat($(totalSeletor).text().replace("R$", "").replace(/\./g, '').replace(',', '.'));
                       //      $(qtdSeletor).text(qtdAtual + 1);
                       //      const novoTotal = valorTotal + comissao_recebida;
                       //      $(totalSeletor).text(novoTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }).replace("R$", ""));
                       //      const valorAReceber = $(valorSeletor).text();
                       //      $(valorSeletor).text(valorAReceber - 1);
                       //  };
                       //
                       //  // Atualizações baseadas no plano
                       //  if (plano === 1) {
                       //      atualizarValores("#valor_total_individual", "#total_quantidade_individual", ".valor_individual_a_receber", parseInt($("#total_quantidade_individual").text()));
                       //  } else if (plano === 3) {
                       //      atualizarValores("#valor_total_coletivo", "#total_quantidade_coletivo", ".valor_coletivo_a_receber", parseInt($("#total_quantidade_coletivo").text()));
                       //  } else {
                       //      atualizarValores("#valor_total_empresarial", "#total_quantidade_empresarial", ".valor_empresarial_a_receber", parseInt($("#total_quantidade_empresarial").text()));
                       //  }
                       //
                       //  // Atualizar descontos
                       //  const totalDescontoAtual = parseFloat($("#valor_total_desconto_vendedor").val().replace(/\./g, '').replace(',', '.')) || 0;
                       //  const novoDesconto = totalDescontoAtual + desconto;
                       //  $("#valor_total_desconto_vendedor").val(novoDesconto.toLocaleString('pt-BR', { minimumFractionDigits: 2 }));
                       //
                       //  // Atualizar comissão total
                       //  const comissaoAtual = parseFloat($("#comissao_vendedor").val().replace(/\./g, '').replace(',', '.')) || 0;
                       //  const novaComissao = comissaoAtual + comissao_recebida;
                       //  $("#comissao_vendedor").val(novaComissao.toLocaleString('pt-BR', { minimumFractionDigits: 2 }));
                       //
                       //  // Ocultar linha
                       //  linha.slideUp('fast').addClass('pagar');
                       //
                       //  const calcularTotalVendedor = () => {
                       //      const pegarValor = (seletor) => parseFloat($(seletor).val().replace(/\./g, '').replace(',', '.')) || 0;
                       //
                       //
                       //      let salario = pegarValor("#salario_vendedor");
                       //      let comissao = pegarValor("#comissao_vendedor");
                       //      let premiacao = pegarValor("#premiacao_vendedor");
                       //      let totalDesconto = pegarValor("#valor_total_desconto_vendedor");
                       //      let estorno = pegarValor("#valor_total_estorno_vendedor");
                       //
                       //      console.log("salario ",salario);
                       //      console.log("comissao ",comissao);
                       //      console.log("premiacao ",premiacao);
                       //      console.log("totaldesconto ",totalDesconto);
                       //      console.log("estorno ",estorno);
                       //
                       //      let ganhos = salario + comissao + premiacao;
                       //      let totalFinal = ganhos - totalDesconto - estorno;
                       //
                       //      console.log("ganhos ",ganhos);
                       //      console.log("totalFinal ",totalFinal);
                       //
                       //
                       //
                       //      $("#total_campo_vendedor").val(totalFinal.toLocaleString('pt-BR', { minimumFractionDigits: 2 }));
                       //  };
                       // calcularTotalVendedor();
                       //
                       //
                       //
                       //
                       //  // Limpar campos e remover linha da tabela
                       //  $(".estilizar_search input[type='search']").val('');
                       //  listarcomissaomesdfirente.search('').draw();
                       //  listarcomissaomesrecebidas.search('').draw();
                       //  listaraptosapagar.search('').draw();
                       listarcomissaomesdfirente.row(linha).remove().draw();
                    },
                    error: function (xhr) {
                        alert("Erro ao processar pagamento: " + xhr.responseText);
                    },
                    complete: function () {
                        $("#loading-overlay").addClass('ocultar');
                    }
                });
            });






            $("body").on('change',".pagar_comissao",function(){

                let mes = $("#mes_folha option:selected").val();
                let id = $(this).attr('id');
                id_confirmados.push(id);
                let desconto = 0;
                $("#valores_confirmados").val(id_confirmados);
                let qtd_individual = parseInt($("#total_quantidade_individual").text());
                let qtd_coletivo = parseInt($("#total_quantidade_coletivo").text());
                let qtd_empresarial = parseInt($("#total_quantidade_empresarial").text());
                let plano = $(this).attr('data-plano');
                let comissao_recebida = 0;
                let comissao_pagando = $(this).closest('tr').find('.comissao_pagando').val().replace(/\./g,'').replace(',', '.').trim();
                if(comissao_pagando == "") {
                    comissao_recebida = $(this).closest('tr').find('.comissao_recebida').text().replace("R$","").replace(/\./g,'').replace(',', '.').trim();
                } else {
                    comissao_recebida = $(this).closest('tr').find('.comissao_pagando').val().replace(/\./g,'').replace(',', '.').trim();
                }
                if(plano == 1) {
                    let valor_total_individual = parseFloat($("#valor_total_individual").text().replace("R$","").replace(/\./g,'').replace(',', '.').trim());
                    qtd_individual += 1;
                    $("#total_quantidade_individual").text(qtd_individual);

                    let valor_individual_a_receber = $(".valor_individual_a_receber").text();
                    valor_individual_a_receber -= 1;
                    $(".valor_individual_a_receber").text(valor_individual_a_receber);

                    let total_individual = valor_total_individual + parseFloat(comissao_recebida);
                    $("#valor_total_individual").text(total_individual.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}).replace("R$",""));
                    desconto = parseFloat($(this).closest("tr").find("input[name='porcentagem_change']").val().replace(/\./g,'').replace(',', '.').trim());

                } else if(plano == 3) {
                    let valor_total_coletivo = parseFloat($("#valor_total_coletivo").text().replace("R$","").replace(/\./g,'').replace(',', '.').trim());
                    qtd_coletivo += 1;
                    $("#total_quantidade_coletivo").text(qtd_coletivo);

                    let valor_coletivo_a_receber = $(".valor_coletivo_a_receber").text();
                    valor_coletivo_a_receber -= 1;
                    $(".valor_coletivo_a_receber").text(valor_coletivo_a_receber);

                    let total_coletivo = valor_total_coletivo + parseFloat(comissao_recebida);
                    $("#valor_total_coletivo").text(total_coletivo.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}).replace("R$",""));
                    desconto = parseFloat($(this).closest("tr").find("input[name='porcentagem_change']").val().replace(/\./g,'').replace(',', '.').trim());
                } else {

                    let valor_empresarial_a_receber = $(".valor_empresarial_a_receber").text();
                    valor_empresarial_a_receber -= 1;
                    $(".valor_empresarial_a_receber").text(valor_empresarial_a_receber);


                    let valor_total_empresarial = parseFloat($("#valor_total_empresarial").text().replace("R$","").replace(/\./g,'').replace(',', '.').trim());
                    qtd_empresarial += 1;
                    $("#total_quantidade_empresarial").text(qtd_empresarial);
                    let total_empresarial = valor_total_empresarial + parseFloat(comissao_recebida);
                    $("#valor_total_empresarial").text(total_empresarial.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}).replace("R$",""));
                    desconto = parseFloat($(this).closest("tr").find("input[name='porcentagem_change']").val().replace(/\./g,'').replace(',', '.').trim());
                }

                if($(this).val() == 2) {
                    let formatar_desconto = 0;
                    let formatar_desconto_campo = 0;
                    let somar_desconto_campo = 0;
                    if($("#valor_total_desconto_vendedor").val()) {
                        formatar_desconto_campo = $("#valor_total_desconto_vendedor").val().replace(/\./g,'').replace(',', '.').trim();
                        somar_desconto_campo = parseFloat(formatar_desconto_campo) + desconto;
                        let somar_desconto_campo_formatado = 0;
                        somar_desconto_campo_formatado = somar_desconto_campo.toLocaleString('pt-BR',{minimumFractionDigits:2});
                        $("#valor_total_desconto_vendedor").val(somar_desconto_campo_formatado);
                    } else {
                        $("#valor_total_desconto_vendedor").val($(this).closest("tr").find("input[name='porcentagem_change']").val().replace(",","."));
                    }
                    $(this).addClass('pagar');
                    var linha = $(this).closest('tr');
                    linha.slideUp('fast');

                    if($("#comissao_vendedor").val()) {
                        let valor_atual = $("#comissao_vendedor").val().replace(/\./g,'').replace(',', '.').trim();
                        valor_atual = parseFloat(valor_atual);
                        valor_atual += parseFloat(comissao_recebida);
                        f =  valor_atual.toLocaleString('pt-BR',{minimumFractionDigits:2});
                        $("#comissao_vendedor").val(f);
                    } else {
                        $("#comissao_vendedor").val($(this).closest("tr").find(".comissao_pagando").val());
                    }
                } else {
                    $(this).removeClass('pagar');
                }
                //Calcular o Total
                $("#comissao_vendedor").val();
                let salario_vendedor_campo = $("#salario_vendedor").val() != "" || $("#salario_vendedor").val() != 0 ? $("#salario_vendedor").val().replace(/\./g, "") : parseFloat(0);
                let salario_vendedor_campo_convertido = salario_vendedor_campo != 0 ? parseFloat(salario_vendedor_campo.replace(",", ".")) : 0;

                let comissao_vendedor_campo = $("#comissao_vendedor").val() != "" || $("#comissao_vendedor").val() != 0 ? $("#comissao_vendedor").val().replace(/\./g, "") : parseFloat(0);
                let comissao_vendedor_campo_convertido = parseFloat(comissao_vendedor_campo.replace(",", "."));

                let premiacao_vendedor_campo = $("#premiacao_vendedor").val() != "" || $("#premiacao_vendedor").val() != 0 ? $("#premiacao_vendedor").val().replace(/\./g, "") : parseFloat(0);
                let premiacao_vendedor_campo_convertido = premiacao_vendedor_campo != 0 ? parseFloat(premiacao_vendedor_campo.replace(",",".")) : 0;

                let valor_total_desconto_vendedor = $("#valor_total_desconto_vendedor").val() != "" || $("#valor_total_desconto_vendedor").val() != 0 ? $("#valor_total_desconto_vendedor").val() : parseFloat(0);
                let valor_total_desconto_vendedor_convertido = valor_total_desconto_vendedor != 0 ? parseFloat(valor_total_desconto_vendedor.replace(/\./g,'').replace(',', '.').trim()) : parseFloat(0);

                let estorno_total = $("#valor_total_estorno_vendedor").val().trim() != "" || $("#valor_total_estorno_vendedor").val().trim() != 0 ? $("#valor_total_estorno_vendedor").val().trim().replace(/\./g, "") : parseFloat(0);
                let estorno_total_convertido = estorno_total != 0 ? parseFloat(estorno_total.replace(",",".")) : 0;

                let user_id = $("#corretor_escolhido").val();

                let somar_ganhos = salario_vendedor_campo_convertido + comissao_vendedor_campo_convertido + premiacao_vendedor_campo_convertido;
                somar_ganhos = parseFloat(somar_ganhos);

                $("#total_campo_vendedor").val(somar_ganhos.toLocaleString('pt-BR',{minimumFractionDigits:2}));

                let valor_total = $("#total_campo_vendedor").val().replace(/\./g,'').replace(',', '.').trim();

                desconto = $(this).closest("tr").find("input[name='porcentagem_change']").val().replace(",",".");

                let subtrair_desconto = parseFloat(valor_total) - valor_total_desconto_vendedor_convertido;
                let subtrair_desconto_formatado = subtrair_desconto.toLocaleString('pt-BR',{minimumFractionDigits:2});
                let subtrair_estorno = subtrair_desconto - estorno_total_convertido;
                let subtratir_estorno_formatado = subtrair_estorno.toLocaleString('pt-BR',{minimumFractionDigits:2});
                $("#total_campo_vendedor").val(subtratir_estorno_formatado);
                $(".estilizar_search input[type='search']").val('');
                listarcomissaomesdfirente.search('').draw();
                listarcomissaomesrecebidas.search('').draw();
                listaraptosapagar.search('').draw();
                let total_vendedor = $("#total_campo_vendedor").val();
                $.ajax({
                    url:"{{route('gerente.aptar.pagamento')}}",
                    method:"POST",
                    data:
                        "id="+id+
                        "&mes="+mes+
                        "&desconto="+valor_total_desconto_vendedor_convertido+
                        "&salario="+salario_vendedor_campo+
                        "&comissao="+comissao_vendedor_campo+
                        "&premiacao="+premiacao_vendedor_campo+
                        "&estorno="+estorno_total+
                        "&user_id="+user_id+
                        "&total="+total_vendedor,
                    success:function(res) {
                        console.log(res);
                        //total_mes_atual();
                        // $(".salario_usuario").val(res.total_salario);
                        // $("#comissao").val(res.total_comissao);
                        // $(".premiacao_usuario").val(res.valor_premiacao);
                        // $(".estorno_usuario").val(res.total_estorno);
                        // $(".desconto_usuario").val(res.valor_desconto);
                        // $(".total_campo").val(res.total_mes);


                    }
                });


            });


            $("#listar_coletivo_apto_historico").on('click',function(){
                let id = $("#corretor_escolhido_historico").val();
                let mes = $("#mes_folha_historico").val();


                if(id) {

                    $("#listar_coletivo_apto").addClass("ativo");
                    $(".listar li").removeClass("ativo");

                    $("#listar_individual_apto").removeClass("ativo");
                    $("#listar_empresarial_apto").removeClass("ativo");
                    $(".listar_estorno_ul li").removeClass("ativo");






                } else {

                }



            });









            $("#listar_coletivo_apto").on('click',function(){
                let id = $("#corretor_escolhido").val();
                let mes = $("#mes_folha").val();
                let ano = $("#mes_folha").find('option:selected').text().split("/")[1];
                if(id) {
                    $(".listar li").removeClass("ativo");
                    $("#listar_coletivo_apto").addClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    $("#listar_empresarial_apto").removeClass("ativo");
                    $(".listar_estorno_ul li").removeClass("ativo");
                    if($("#tabela_principal").is(':visible')) {
                        $("#tabela_principal").slideUp('fast',function(){
                            if(mes == "") {
                                $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                                listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/coletivo/confirmadas/${id}/${mes}/${ano}') }}`).load();
                                $("#tabela_aptos_a_pagar").slideDown('slow');
                            } else {
                                $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                                listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/coletivo/confirmadas/${id}/${mes}/${ano}') }}`).load();
                                $("#tabela_aptos_a_pagar").slideDown('slow');
                            }
                        });
                    } else {
                        $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                        listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/coletivo/confirmadas/${id}/${mes}/${ano}') }}`).load();
                    }

                    if($("#listar_a_receber").is(':visible')) {
                        $("#listar_a_receber").slideUp('fast',function(){
                            $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                            listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/coletivo/confirmadas/${id}/${mes}/${ano}') }}`).load();
                            $("#tabela_aptos_a_pagar").slideDown('slow');
                        });
                    } else {
                        $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                        listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/coletivo/confirmadas/${id}/${mes}/${ano}') }}`).load();
                    }

                    if($("#tabela_estorno_table_back").is(":visible")) {
                        $("#tabela_estorno_back").slideUp(1000,function(){
                            $("#tabela_aptos_a_pagar").slideDown('slow',function(){
                                $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                                listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/coletivo/confirmadas/${id}/${mes}/${ano}') }}`).load();
                            });
                        });
                    }

                    if($("#tabela_estorno_table").is(':visible')) {
                        $("#tabela_estorno").slideUp('fast',function(){
                            $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                            listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/coletivo/confirmadas/${id}/${mes}/${ano}') }}`).load();
                            $("#tabela_aptos_a_pagar").slideDown('slow');
                        });
                    } else {
                        $("#title_individual_confirmados").html("<h4>Recebidas - Coletivo</h4>");
                        listaraptosapagar.ajax.url(`{{ url('/gerente/comissao/coletivo/confirmadas/${id}/${mes}/${ano}') }}`).load();
                    }

                } else {
                    $(".listar li").removeClass("ativo");
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false

                    }
                }
            });

            $("#comissao_vendedor").on('change',function(){
                //total_mes_atual();
            });


            $("#valor_total_desconto_vendedor").on('change',function(){
                //total_mes_atual();
            });




            $("body").on('click','.btn_usuario',function(){
                $('#exampleModal').modal('hide')
                let comissao =  $("#comissao_vendedor").val().trim();
                let salario = $(".salario_usuario_vendedor").val();
                let premiacao = $("#premiacao_vendedor").val();
                let desconto = $("#valor_total_desconto_vendedor").val().trim();
                let estorno = $("#valor_total_estorno_vendedor").val().trim();
                let user_id = $("#corretor_escolhido").val();
                let mes = $("#mes_folha").val();
                let total_a_pagar = $(".total_campo_vendedor").val().trim();
                let id_confirmados = $("#valores_confirmados").val();
                $.ajax({
                    url:"{{route('gerente.finalizar.pagamento')}}",
                    method:"POST",
                    data:
                        "desconto="
                        +desconto+
                        "&comissao="
                        +comissao+
                        "&salario="
                        +salario+"" +
                        "&premiacao="
                        +premiacao+
                        "&user_id="
                        +user_id+
                        "&mes="
                        +mes+
                        "&total="
                        +total_a_pagar+
                        "&id="+id_confirmados+
                        "&estorno="+estorno,
                    success:function(res) {
                        const select = $("#escolher_vendedor");
                        select.html('<option value="" class="text-center">--Corretores--</option>');
                        $.each(res.users_aptos, function(index, corretor) {
                            select.append($("<option>").attr("value", corretor.id).text(corretor.name));
                        });
                        $("#salario_vendedor").val('');
                        $("#comissao_vendedor").val('');
                        $("#premiacao_vendedor").val('');
                        $("#corretor_escolhido").val('');
                        $('#valor_total_desconto_vendedor').val('');
                        $("#valor_total_estorno_vendedor").val('');
                        $('#total_campo_vendedor').val('');
                        $("#valores_confirmados").val('');
                        $("#list_user").html(res.view);
                        $("#escolher_vendedor").val('');
                        $("#total_quantidade_individual").text(0);
                        $("#total_quantidade_coletivo").text(0);
                        $("#total_quantidade_empresarial").text(0);
                        $("#valor_total_individual").text(0);
                        $("#valor_total_coletivo").text(0);
                        $("#valor_total_empresarial").text(0);
                        $("#total_campo").val('');
                        $("#mes_folha").attr("disabled",true);
                        $(".individual_recebidas").removeClass('ativo');
                        $(".coletivo_recebidas").removeClass('ativo');
                        $(".empresarial_recebidas").removeClass('ativo');
                        $(".individual_a_receber").removeClass('ativo');
                        $(".coletivo_a_receber").removeClass('ativo');
                        $(".empresarial_a_receber").removeClass("ativo");
                        $(".listar_estorno_ul li").removeClass("ativo");
                        $(".estilizar_search input[type='search']").val('');
                        listarcomissaomesdfirente.ajax.reload(function() {
                            listarcomissaomesdfirente.clear().draw();
                            listarcomissaomesdfirente.search('').draw();
                        });
                        listarcomissaomesrecebidas.ajax.reload(function() {
                            listarcomissaomesrecebidas.clear().draw();
                            listarcomissaomesrecebidas.search('').draw();
                        });
                        listaraptosapagar.ajax.reload(function() {
                            listaraptosapagar.clear().draw();
                            listaraptosapagar.search('').draw();
                        });
                        listarestornos.ajax.reload(function() {
                            listarestornos.clear().draw();
                            listarestornos.search('').draw();
                        });
                        id_confirmados = [];
                        finalizarMes();
                        //total_mes_atual();
                    }
                });
            });

            $("body").on('click','.individual_recebidas_historico',individual_recebidas_historico);



            function individual_recebidas_historico() {

                $(".estilizar_search input[type='search']").val('');
                listarcomissaomesdfirente.search('').draw();
                listarcomissaomesrecebidas.search('').draw();
                listaraptosapagar.search('').draw();

                let id = $("#escolher_vendedor_historico").val();
                $("#listar_individual_apto_historico").removeClass('ativo');
                $("#listar_coletivo_apto_historico").removeClass('ativo');
                $(".coletivo_recebidas_historico").removeClass('ativo');
                $(".individual_a_receber_historico").removeClass('ativo');
                $(".coletivo_a_receber_historico").removeClass('ativo');
                $(".empresarial_recebidas_historico").removeClass('ativo');
                $(".listar_estorno_ul_historico li").removeClass("ativo");
                $(".lista_apto_a_pagar_ul_historico li").removeClass("ativo");

                $(this).addClass('ativo');

                if(id) {
                    $("#listar_individual_apto_historico").removeClass("ativo");
                    if($("#tabela_principal").is(":visible")) {
                        $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                        listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                    } else {

                        if($("#listar_a_receber").is(':visible')) {
                            $("#listar_a_receber").slideUp('slow',function(){
                                $("#tabela_principal").slideDown(1000,function(){
                                    $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_aptos_a_pagar").is(":visible")) {
                            $("#tabela_aptos_a_pagar").slideUp('slow',function(){
                                $("#tabela_principal").slideDown(1000,function(){
                                    $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#listar_cadastrados").is(":visible")) {
                            $("#listar_cadastrados").slideUp(1000,function(){
                                $("#tabela_principal").slideDown('slow',function(){
                                    $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_estorno_table").is(':visible')) {
                            $("#tabela_estorno").slideUp('fast',function(){
                                $("#tabela_principal").slideDown('slow',function(){
                                    $("#title_recebidas").html("<h4>Recebidas - Individual</h4>");
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_estorno_table_back").is(":visible")) {
                            $("#tabela_estorno_back").slideUp(1000,function(){
                                $("#tabela_principal").slideDown('slow',function(){
                                    $("#title_recebidas").html("<h4>Recebidas - Individual</h4>");
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                                });
                            });
                        }






                    }
                } else {
                    $(".listar li").removeClass("ativo");
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false

                    }
                }






            }











            function individual_recebidas() {

                $(".estilizar_search input[type='search']").val('');
                listarcomissaomesdfirente.search('').draw();
                listarcomissaomesrecebidas.search('').draw();
                listaraptosapagar.search('').draw();

                let id = $("#corretor_escolhido").val();
                $("#listar_individual_apto").removeClass('ativo');
                $("#listar_coletivo_apto").removeClass('ativo');
                $(".coletivo_recebidas").removeClass('ativo');
                $(".individual_a_receber").removeClass('ativo');
                $(".coletivo_a_receber").removeClass('ativo');
                $(".empresarial_recebidas").removeClass('ativo');
                $(".listar_estorno_ul li").removeClass("ativo");
                $(".lista_apto_a_pagar_ul li").removeClass("ativo");

                $(this).addClass('ativo');
                if(id) {
                    $("#listar_individual_apto").removeClass("ativo");
                    if($("#tabela_principal").is(":visible")) {
                        $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                        listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                    } else {

                        if($("#listar_a_receber").is(':visible')) {
                            $("#listar_a_receber").slideUp('slow',function(){
                                $("#tabela_principal").slideDown(1000,function(){
                                    $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_aptos_a_pagar").is(":visible")) {
                            $("#tabela_aptos_a_pagar").slideUp('slow',function(){
                                $("#tabela_principal").slideDown(1000,function(){
                                    $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#listar_cadastrados").is(":visible")) {
                            $("#listar_cadastrados").slideUp(1000,function(){
                                $("#tabela_principal").slideDown('slow',function(){
                                    $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_estorno_table").is(':visible')) {
                            $("#tabela_estorno").slideUp('fast',function(){
                                $("#tabela_principal").slideDown('slow',function(){
                                    $("#title_recebidas").html("<h4>Recebidas - Individual</h4>");
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                                });
                            });
                        }

                        if($("#tabela_estorno_table_back").is(":visible")) {
                            $("#tabela_estorno_back").slideUp(1000,function(){
                                $("#tabela_principal").slideDown('slow',function(){
                                    $("#title_recebidas").html("<h4>Recebidas - Individual</h4>");
                                    listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                                });
                            });
                        }






                    }
                } else {
                    $(".listar li").removeClass("ativo");
                    $("#listar_coletivo_apto").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    toastr["error"]("Escolha um Corretor")
                    toastr.options = {
                        'time-out': 3000,
                        'close-button':true,
                        'position-class':'toast-top-full-width',
                        'class' : 'fullwidth',
                        'fixed': false

                    }
                }
            }
            $("body").on('click','.individual_recebidas',individual_recebidas);

            var data = new Date();
            var mes = String(data.getMonth());
            let meses = ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];

            var listarcomissaomesdfirente = $(".listarcomissaomesdiferente").DataTable({
                dom: '<"flex justify-between"<"#title_comissao_diferente"><"estilizar_search"f>><tr><"flex justify-between items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                language: {
                    "search": "Pesquisar",
                    "paginate": {
                        "next": "Próx.",
                        "previous": "Ant.",
                        "first": "Primeiro",
                        "last": "Último"
                    },
                    "emptyTable": "Nenhum registro encontrado",
                    "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "infoFiltered": "(Filtrados de _MAX_ registros)",
                    "infoThousands": ".",
                    "loadingRecords": "Carregando...",
                    "processing": "Processando...",
                    "lengthMenu": "Exibir _MENU_ por página"
                },

                "lengthMenu": [1000,2000,3000,4000],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": false,
                processing: true,
                ajax: {
                    url:url_padrao,
                    dataSrc:""
                },
                columns: [
                    {data:"administradora",name:"administradora",width:"5%"},
                    {data:"data_criacao",name:"data_criacao",
                        "createdCell": function(td, cellData, rowData, row, col) {
                            let datas = cellData.split(" ")[0].split("-").reverse().join("/");
                            $(td).html(datas);
                        },width:"7%"
                    },
                    {data:"orcamento",name:"orcamento",width:"5%"},
                    {data:"cliente",name:"cliente",width:"20%"},
                    {data:"parcela",name:"parcela",className: 'dt-center',width:"3%"},
                    {data:"valor_plano_contratado",name:"valor_plano_contratado",width:"5%",
                        render: $.fn.dataTable.render.number('.',',',2,'')
                    },
                    {data:"data",name:"data",className: 'dt-center',width:"7%"},
                    {data:"data_baixa",name:"data_baixa",
                        "createdCell": function(td, cellData, rowData,row, col) {
                            let datas = cellData.split("-").reverse().join("/");
                            $(td).html(datas);
                        },width:"7%"
                    },

                    {data:"porcentagem_parcela_corretor",
                        name:"porcentagem_parcela_corretor",
                        width:"10%",
                        "createdCell":function(td, cellData, rowData, row, col) {
                            $(td).html('<input type="text" data-valor-plano='+rowData.valor_plano_contratado+'  data-id='+rowData.id+' value='+cellData+' name="comissao_paga_change" class="comissao_paga_change" style="width:80%; padding: 4px 8px; border: 1px solid #ccc; border-radius: 8px; font-size: 0.9rem; color: #000; box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);" />')
                        }
                    },
                    {data:"id",name:"comissao_pagando",render: $.fn.dataTable.render.number('.',',',2,'R$ '),width:"10%",
                        "createdCell":function(td, cellData, rowData, row, col) {
                            let valor_comisao = parseFloat(rowData.valor).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            $(td).html('<input type="text" value='+valor_comisao+' data-id='+cellData+' readonly name="comissao_pagando" class="comissao_pagando" style="width:80%; padding: 4px 8px; border: 1px solid #ccc; border-radius: 8px; font-size: 0.9rem; color: #000; box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);" />')
                        }
                    },
                    {data:"quantidade_vidas",name:"quantidade_vidas",width:"3%",className: 'dt-center'},
                    {data:"desconto",name:"desconto",width:"8%",
                        "createdCell":function(td, cellData, rowData, row, col) {
                            let descondo_calc = parseFloat(cellData).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            $(td).html('<input type="text" value='+descondo_calc+' data-id='+rowData.id+' name="porcentagem_change" class="porcentagem_change" style="width:80%; padding: 4px 8px; border: 1px solid #ccc; border-radius: 8px; font-size: 0.9rem; color: #000; box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);" />')
                        }
                    },
                    {
                        data:"id",name:"id",width:"8%",className: 'dt-center',
                        "createdCell": function (td, cellData, rowData, row, col) {
                            $(td).html(`
                                <svg id="${cellData}" data-plano="${rowData.plano}" class="w-6 h-6 text-white pagar_comissao_up" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v13m0-13 4 4m-4-4-4 4"/>
                                </svg>

                            `)
                        }
                    },
                    {
                        data:"contrato_id",name:"contrato_id",width:"3%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let contrato_id = cellData;
                            if(rowData.plano == 3) {
                                $(td).html(`<div data-id="${contrato_id}" class='text-center text-white ver_coletivo'>
                                        <a href="#" target="_blank" class="text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 div_info">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </a>
                                    </div>
                                `);
                            } else if(rowData.plano == 1) {
                                $(td).html(`<div data-id="${contrato_id}" class='text-center text-white ver_individual'>
                                        <a href="#" class="text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 div_info">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </a>
                                    </div>
                                `);
                            } else {
                                $(td).html(`<div data-id="${contrato_id}" class='text-center text-white ver_empresarial'>
                                        <a href="#" target="_blank" class="text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 div_info">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </a>
                                    </div>
                                `);
                            }




                        },
                    }
                ],

                "initComplete": function( settings, json ) {
                    $('#title_comissao_diferente').html("<h4>A Receber Individual</h4>");
                }
            });

            $("body").on('keyup','.comissao_pagando',function(){
                $(this).mask('#.##0,00', {reverse: true});
            });

            $('.salario_usuario').mask("#.##0,00", {reverse: true});
            $('.premiacao_usuario').mask("#.##0,00", {reverse: true});

            $('.estorno_usuario_vendedor').mask("#.##0,00", {reverse: true});

            $("#salario_vendedor").mask("#.##0,00", {reverse: true});
            $("#premiacao_vendedor").mask("#.##0,00", {reverse: true});

            $("#body").find(".comissao_pagando").mask("#.##0,00", {reverse: true});
            $("body").find(".change_plan").mask("#.##0,00", {reverse: true});

            $("body").on('keyup','.change_plan',function(){
                $(this).mask('#.##0,00', {reverse: true});
            });

            $("body").on('keyup','.porcentagem_change',function(){
                $(this).mask('#.##0,00', {reverse: true});
            });



            $("body").on('change','.porcentagem_change',function(){
                 let id = $(this).attr('data-id');
                 let porcentagem = $(this).val();
                 $.ajax({
                     url:"{{route('gerente.aplicar.desconto')}}",
                     method:"POST",
                     data:"id="+id+"&porcentagem="+porcentagem,
                     success:function(res) {
                         console.log(res);
                     }
                 });
            });

            $("#valor_total_estorno_vendedor").on('change',function(){

                let comissao_numerica = 0;
                if($("#comissao_vendedor").val() != "") {
                    let comissao = $("#comissao_vendedor").val().replace("R$","").replace(/\./g,'').replace(',', '.').trim()
                    comissao_numerica = parseFloat(comissao);
                }

                let valor_salario = 0;
                if($('#salario_vendedor').val() != "") {
                    valor_salario = parseFloat($('#salario_vendedor').val().replace(/\./g,'').replace(".","").replace(',', '.'));
                }

                let desconto = 0;
                if($("#valor_total_desconto_vendedor").val() != "") {
                    desconto = parseFloat($('#valor_total_desconto_vendedor').val().replace(',', '.'));
                }

                let premiacao = 0;
                if($("#premiacao_vendedor").val() != "") {
                    premiacao = parseFloat($('#premiacao_vendedor').val().replace(',', '.'));
                }



                let valor = $(this).val().replace(/\./g,'').replace(',', '.');
                // console.log(valor);
                let valor_numerico = parseFloat(valor);
                // let total = $(".total_a_pagar").text().trim().replace("R$","").replace(/\./g,'').replace(',', '.');
                // let total_numero = parseFloat(total);

                let valor_input = (comissao_numerica + valor_salario + premiacao) - desconto;
                let valor_com_estorno = valor_input - valor_numerico
                f = valor_com_estorno.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}).replace("R$","").trim();

                $("#total_campo_vendedor").val(f);

                //total_mes_atual()



            });



            $(".premiacao_usuario_vendedor").on('change',function(){
                let comissao_numerica = 0;
                if($("#comissao_vendedor").val() != "") {
                    let comissao = $("#comissao_vendedor").val().replace("R$","").replace(/\./g,'').replace(',', '.').trim()
                    comissao_numerica = parseFloat(comissao);
                }

                let valor_salario = 0;
                if($('#salario_vendedor').val() != "") {
                    valor_salario = parseFloat($('#salario_vendedor').val().replace(/\./g,'').replace(".","").replace(',', '.'));
                }

                let desconto = 0;
                if($("#valor_total_desconto_vendedor").val() != "") {
                    desconto = parseFloat($('#valor_total_desconto_vendedor').val().replace(',', '.'));
                }

                let estorno = 0;
                if($("#valor_total_estorno_vendedor").val() != "") {
                    estorno = parseFloat($('#valor_total_estorno_vendedor').val().replace(',', '.'));
                }

                let valor = $(this).val().replace(/\./g,'').replace(',', '.');
                let valor_numerico = parseFloat(valor);
                // let total = $(".total_a_pagar").text().trim().replace("R$","").replace(/\./g,'').replace(',', '.');
                // let total_numero = parseFloat(total);

                let valor_input_desconto = (valor_numerico + comissao_numerica + valor_salario) - desconto;
                let valor_input = valor_input_desconto - estorno;
                f = valor_input.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}).replace("R$","").trim();

                $("#total_campo_vendedor").val(f);

                let user_id = $("#corretor_escolhido").val();
                let mes = $("#mes_folha option:selected").val();

                $.ajax({
                    url:"{{route('gerente.mudar.salario')}}",
                    method:"POST",
                    data:
                        "total="+valor_input+
                        "&comissao="+comissao_numerica+
                        "&premiacao="+valor_numerico+
                        "&estorno="+estorno+
                        "&desconto="+desconto+
                        "&salario="+valor_salario+
                        "&user_id="+user_id+
                        "&mes="+mes
                });





            });

            $(".premiacao_usuario_vendedor").on('keydown',function(event){
                if (event.key === "Tab") {

                    event.preventDefault();
                    $("input[name='estorno_vendedor']").removeAttr('disabled');
                    $("input[name='estorno_vendedor']").focus().prop('disabled',true);
                }
            });

            $(".estorno_usuario_vendedor").on('keydown',function(event){
                if (event.key === "Tab") {

                    event.preventDefault();
                    $("input[name='desconto_vendedor']").removeAttr('disabled');
                    $("input[name='desconto_vendedor']").focus().prop('disabled',true);
                }
            });






            $(".salario_usuario_vendedor").on('change',function(){
                let comissao_numerica = 0;
                if($("#comissao_vendedor").val() != "") {
                    let comissao = $("#comissao_vendedor").val().replace("R$","").replace(/\./g,'').replace(',', '.').trim()
                    comissao_numerica = parseFloat(comissao);
                }

                let valor_premiacao = 0;
                if($('#premiacao_vendedor').val() != "") {
                    valor_premiacao = parseFloat($('#premiacao_vendedor').val().replace(/\./g,'').replace(',', '.'));
                }

                let desconto = 0;
                if($("#valor_total_desconto_vendedor").val() != "") {
                    desconto = parseFloat($('#valor_total_desconto_vendedor').val().replace(',', '.'));
                }

                let estorno = 0;
                if($("#valor_total_estorno_vendedor").val() != "") {
                    estorno = parseFloat($('#valor_total_estorno_vendedor').val().replace(',', '.'));
                }

                let total = 0;
                if($("#total_campo_vendedor").val() != "") {
                    total = parseFloat($('#total_campo_vendedor').val().replace(/\./g,'').replace(',', '.'));
                }

                let valor = $(this).val().replace(/\./g,'').replace(',', '.');
                let valor_numerico = parseFloat(valor);

                let valor_total = (comissao_numerica + valor_premiacao + valor_numerico) - desconto;
                let valor_input = valor_total - estorno;

                f = valor_input.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}).replace("R$","").trim();
                $("#total_campo_vendedor").val(f);

                let user_id = $("#corretor_escolhido").val();
                let mes = $("#mes_folha option:selected").val();

                $.ajax({
                   url:"{{route('gerente.mudar.salario')}}",
                   method:"POST",
                   data:
                       "total="+valor_input+
                       "&comissao="+comissao_numerica+
                       "&premiacao="+valor_premiacao+
                       "&estorno="+estorno+
                       "&desconto="+desconto+
                       "&salario="+valor+
                       "&user_id="+user_id+
                       "&mes="+mes,
                   success:function(res) {

                   }
                });

            });

            $(".mudar_valores").on('change',function(){
                let campo_select = $(this).attr('id');
                let campo_ano = $("#selecionar_ano").val();
                let campo_mes = $("#selecionar_mes").val();
                let campo_cor = $("#selecionar_corretor").val();

                let link_ano = campo_ano == "todos" ? "all" : campo_ano;
                let link_mes = campo_mes == "todos" ? "all" : campo_mes;
                let link_cor = campo_cor == "todos" ? "all" : campo_cor;

                $('.link_individual_um').attr("href",`/gerente/ver/1/1/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_individual_dois').attr("href",`/gerente/ver/1/2/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_individual_tres').attr("href",`/gerente/ver/1/3/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_individual_quatro').attr("href",`/gerente/ver/1/4/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_individual_cinco').attr("href",`/gerente/ver/1/5/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_individual_seis').attr("href",`/gerente/ver/1/6/${link_ano}/${link_mes}/${link_cor}`);

                $('.link_coletivo_um').attr("href",`/gerente/ver/2/1/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_coletivo_dois').attr("href",`/gerente/ver/2/2/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_coletivo_tres').attr("href",`/gerente/ver/2/3/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_coletivo_quatro').attr("href",`/gerente/ver/2/4/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_coletivo_cinco').attr("href",`/gerente/ver/2/5/${link_ano}/${link_mes}/${link_cor}`);
                $('.link_coletivo_seis').attr("href",`/gerente/ver/2/6/${link_ano}/${link_mes}/${link_cor}`);

                $('.link_empresarial_um').attr("href",`/gerente/ver/3/1/${link_ano}/${link_mes}/${link_cor}`)
                $('.link_empresarial_dois').attr("href",`/gerente/ver/3/2/${link_ano}/${link_mes}/${link_cor}`)
                $('.link_empresarial_tres').attr("href",`/gerente/ver/3/3/${link_ano}/${link_mes}/${link_cor}`)
                $('.link_empresarial_quatro').attr("href",`/gerente/ver/3/4/${link_ano}/${link_mes}/${link_cor}`)
                $('.link_empresarial_cinco').attr("href",`/gerente/ver/3/5/${link_ano}/${link_mes}/${link_cor}`)
                $('.link_empresarial_seis').attr("href",`/gerente/ver/3/6/${link_ano}/${link_mes}/${link_cor}`)

                $.ajax({
                    url:"{{route('gerente.todos.valores.usuario')}}",
                    method:"POST",
                    data:"campo_ano="+campo_ano+"&campo_mes="+campo_mes+"&campo_cor="+campo_cor,
                    success:function(res) {

                        $("#quantidade_geral").html(res.quantidade_geral);
                        $("#valor_geral").html(res.total_valor_geral);
                        $("#quantidade_geral_vidas").html(res.quantidade_geral_vidas);
                        $("#quantidade_geral_recebidas").html(res.total_geral_recebidas)
                        $("#total_geral_recebidos_valor").html(res.total_geral_recebidos_valor)
                        $("#quantidade_vidas_recebidas_geral").html(res.quantidade_vidas_recebidas_geral);
                        $("#total_quantidade_a_receber_geral").html(res.total_quantidade_a_receber_geral);
                        $("#total_valor_a_receber_geral").html(res.total_valor_a_receber_geral);
                        $("#quantidade_vidas_a_receber_geral").html(res.quantidade_vidas_a_receber_geral);
                        $("#quantidade_atrasado_geral").html(res.quantidade_atrasado_geral);
                        $("#quantidade_atrasado_valor_geral").html(res.quantidade_atrasado_valor_geral);
                        $("#qtd_atrasado_quantidade_vidas_geral").html(res.qtd_atrasado_quantidade_vidas_geral);
                        $("#quantidade_cancelado_vidas_geral").html(res.quantidade_cancelado_vidas_geral);
                        $("#quantidade_geral_cancelado").html(res.quantidade_geral_cancelado);
                        $("#quantidade_geral_cancelado_valor").html(res.quantidade_geral_cancelado_valor);
                        $("#quantidade_finalizado_geral").html(res.quantidade_finalizado_geral);
                        $("#quantidade_finalizado_quantidade_vidas_geral").html(res.quantidade_finalizado_quantidade_vidas_geral);
                        $("#quantidade_geral_finalizado").html(res.quantidade_geral_finalizado);

                        //////////INDIVIDUAL

                        $("#quantidade_individual_geral").html(res.quantidade_individual_geral);
                        $("#total_valor_geral_individual").html(res.total_valor_geral_individual);
                        $("#quantidade_vidas_geral_individual").html(res.quantidade_vidas_geral_individual);

                        $("#total_quantidade_recebidos_individual").html(res.total_quantidade_recebidos_individual);
                        $("#total_valor_recebidos_individual").html(res.total_valor_recebidos_individual);
                        $("#quantidade_vidas_recebidas_individual").html(res.quantidade_vidas_recebidas_individual);

                        $("#total_quantidade_a_receber_individual").html(res.total_quantidade_a_receber_individual);
                        $("#total_valor_a_receber_individual").html(res.total_valor_a_receber_individual);
                        $("#quantidade_vidas_a_receber_individual").html(res.quantidade_vidas_a_receber_individual);

                        $("#qtd_atrasado_individual").html(res.qtd_atrasado_individual);
                        $("#qtd_atrasado_valor_individual").html(res.qtd_atrasado_valor_individual);
                        $("#qtd_atrasado_quantidade_vidas_individual").html(res.qtd_atrasado_quantidade_vidas_individual);

                        $("#qtd_finalizado_individual").html(res.qtd_finalizado_individual);
                        $("#quantidade_valor_finalizado_individual").html(res.quantidade_valor_finalizado_individual);
                        $("#qtd_finalizado_quantidade_vidas_individual").html(res.qtd_finalizado_quantidade_vidas_individual);

                        $("#qtd_cancelado_individual").html(res.qtd_cancelado_individual);
                        $("#quantidade_valor_cancelado_individual").html(res.quantidade_valor_cancelado_individual);
                        $("#qtd_cancelado_quantidade_vidas_individual").html(res.qtd_cancelado_quantidade_vidas_individual);

                        //////////COLETIVO
                        $('#quantidade_coletivo_geral').html(res.quantidade_coletivo_geral);

                        $('#total_valor_geral_coletivo').html(res.total_valor_geral_coletivo);

                        $('#quantidade_vidas_geral_coletivo').html(res.quantidade_vidas_geral_coletivo);

                        $("#total_quantidade_recebidos_coletivo").html(res.total_quantidade_recebidos_coletivo);
                        $('#total_valor_recebidos_coletivo').html(res.total_valor_recebidos_coletivo);
                        $('#quantidade_vidas_recebidas_coletivo').html(res.quantidade_vidas_recebidas_coletivo);
                        $('#total_quantidade_a_receber_coletivo').html(res.total_quantidade_a_receber_coletivo);
                        $('#total_valor_a_receber_coletivo').html(res.total_valor_a_receber_coletivo);
                        $('#quantidade_vidas_a_receber_coletivo').html(res.quantidade_vidas_a_receber_coletivo);

                        $('#qtd_atrasado_coletivo').html(res.qtd_atrasado_coletivo);
                        $('#qtd_atrasado_valor_coletivo').html(res.qtd_atrasado_valor_coletivo);
                        $('#qtd_atrasado_quantidade_vidas_coletivo').html(res.qtd_atrasado_quantidade_vidas_coletivo);

                        $('#qtd_finalizado_coletivo').html(res.qtd_finalizado_coletivo);
                        $('#quantidade_valor_finalizado_coletivo').html(res.quantidade_valor_finalizado_coletivo);
                        $('#qtd_finalizado_quantidade_vidas_coletivo').html(res.qtd_finalizado_quantidade_vidas_coletivo);

                        $('#qtd_cancelado_coletivo').html(res.qtd_cancelado_coletivo);
                        $('#quantidade_valor_cancelado_coletivo').html(res.quantidade_valor_cancelado_coletivo);
                        $('#qtd_cancelado_quantidade_vidas_coletivo').html(res.qtd_cancelado_quantidade_vidas_coletivo);

                        ////////////Empresarial
                        $("#quantidade_com_empresaria_geral").html(res.quantidade_com_empresaria_geral);
                        $("#total_com_empresa_valor_geral").html(res.total_com_empresa_valor_geral);
                        $("#quantidade_com_empresa_vidas_geral").html(res.quantidade_com_empresa_vidas_geral);
                        $("#quantidade_recebidas_empresarial").html(res.quantidade_recebidas_empresarial);
                        $("#total_valor_recebidos_empresarial").html(res.total_valor_recebidos_empresarial);
                        $("#quantidade_vidas_recebidas_empresarial").html(res.quantidade_vidas_recebidas_empresarial);
                        $("#total_quantidade_a_receber_empresarial").html(res.total_quantidade_a_receber_empresarial);
                        $("#total_valor_a_receber_empresarial").html(res.total_valor_a_receber_empresarial);
                        $("#quantidade_vidas_a_receber_empresarial").html(res.quantidade_vidas_a_receber_empresarial);
                        $("#qtd_atrasado_empresarial").html(res.qtd_atrasado_empresarial);
                        $("#qtd_atrasado_valor_empresarial").html(res.qtd_atrasado_valor_empresarial);
                        $("#qtd_atrasado_quantidade_vidas_empresarial").html(res.qtd_atrasado_quantidade_vidas_empresarial);
                        $("#qtd_finalizado_empresarial").html(res.qtd_finalizado_empresarial);
                        $("#quantidade_valor_finalizado_empresarial").html(res.quantidade_valor_finalizado_empresarial);
                        $("#qtd_finalizado_quantidade_vidas_empresarial").html(res.qtd_finalizado_quantidade_vidas_empresarial);
                        $("#qtd_cancelado_empresarial").html(res.qtd_cancelado_empresarial);
                        $("#quantidade_valor_cancelado_empresarial").html(res.quantidade_valor_cancelado_empresarial),
                        $("#qtd_cancelado_quantidade_vidas_empresarial").html(res.qtd_cancelado_quantidade_vidas_empresarial);
                    }
                });
            });

            /*
            const queryString = window.location.search;
            const queryParams = queryString.substring(1);
            const params = new URLSearchParams(queryParams);
            const ac = params.get('ac');

            if(ac == "comissao") {
                $('.list_abas li').removeClass('ativo').addClass('menu-inativo');
                $('li[data-id="aba_comissao"]').addClass("ativo").removeClass('menu');
                let id = "aba_comissao";
                $("#janela_atual").val(id);
                $('.conteudo_abas main').addClass('ocultar');
                $('#'+id).removeClass('ocultar');
                const urlAtual = window.location.href;
                const novaUrl = urlAtual.split('?')[0];
                history.pushState({}, document.title, novaUrl);
            }
            */


            $(".list_abas li").on('click',function(){
                $('li').removeClass('ativo').addClass('menu-inativo');
                $(this).addClass("ativo").removeClass('menu');
                let id = $(this).attr('data-id');

                $("#janela_atual").val(id);
                $('.conteudo_abas main').addClass('ocultar');
                $('#'+id).removeClass('ocultar');
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });



            $("body").on('click', '.criar_pdf', function(){
                $("#exampleModalTipoPlanosCorretora").removeClass('hidden').addClass('flex');
            });

            //Fechar modal ao clicar no botão de fechar
            $("#closeModalCorretora").on('click', function() {
                $("#exampleModalTipoPlanosCorretora").removeClass('flex').addClass('hidden');
            });

            //Também pode fechar clicando fora do modal (opcional)
            // $(window).on('click', function(event) {
            //     if ($(event.target).closest('#exampleModalTipoPlanosCorretora .bg-white').length === 0) {
            //         $("#exampleModalTipoPlanosCorretora").removeClass('flex').addClass('hidden');
            //     }
            // });






            $("body").on('click','.criar_pdf_historico',function(){
                $("#exampleModalTipoPlanosHistoricoCorretora").modal('show');
                //let user_id = $(this).attr('data-id');
                //let mes = $("#mes_folha_historico option:selected").val();
                ///let url = "{{ route('gerente.finalizar.criarpdf') }}";
                ///url += "?user_id=" + user_id + "&mes=" + mes;
                ///window.open(url,'_blank');
            });

            $("body").on('click','.criar_pdf_historico_corretor',function(){
                $("#exampleModalTipoPlanosHistorico").modal('show');
            });

            $("body").on('click','.gerar_pdf_corretor_link_historico',function(){
                let dados = {
                    "individual": $("#planos_tipo_individual_historico").prop("checked"),
                    "coletivo": $("#planos_tipo_coletivo_historico").prop("checked"),
                    "empresarial": $("#planos_tipo_empresarial_historico").prop("checked"),
                    "user_id": $("#user_historico").val(),
                    "mes": $("#mes_historico").val(),
                    "tipo":"corretor"
                };
                let coletivoSelecionado = [];
                $("input[name='coletivo_historico']:checked").each(function () {
                    coletivoSelecionado.push($(this).data("administradora"));
                });

                if (coletivoSelecionado.length > 0) {
                    dados["coletivo_valores"] = coletivoSelecionado;
                }
                let empresarialSelecionado = [];
                $("input[name='empresarial_historico']:checked").each(function () {
                    empresarialSelecionado.push($(this).data("planos"));
                });

                if (empresarialSelecionado.length > 0) {
                    dados["empresarial_valores"] = empresarialSelecionado;
                }
                let queryString = $.param(dados);
                let url = "{{ route('gerente.finalizar.criarpdf') }}";
                url += "?" + queryString;
                window.open(url, '_blank');
                $("#exampleModalTipoPlanosHistorico").modal('hide');

            });

            $("body").on('click','.gerar_pdf_corretora_link_historico',function(){

                let dados = {
                    "individual": true,
                    "coletivo": true,
                    "empresarial": true,
                    "user_id": $("#user_historico").val(),
                    "mes": $("#mes_folha_historico option:selected").val(),
                    "ano": $("#ano_folha_historico option:selected").val(),
                    "tipo":"corretora"
                };

                let coletivoSelecionado = [];
                $("input[name='coletivo_historico']:checked").each(function () {
                    coletivoSelecionado.push($(this).data("administradora"));
                });

                if (coletivoSelecionado.length > 0) {
                    dados["coletivo_valores"] = coletivoSelecionado;
                }
                let empresarialSelecionado = [];
                $("input[name='empresarial_historico']:checked").each(function () {
                    empresarialSelecionado.push($(this).data("planos"));
                });

                if (empresarialSelecionado.length > 0) {
                    dados["empresarial_valores"] = empresarialSelecionado;
                }
                let queryString = $.param(dados);
                let url = "{{ route('gerente.historico.finalizar.criarpdf') }}";
                url += "?" + queryString;
                window.open(url, '_blank');
                $("#exampleModalTipoPlanosHistoricoCorretora").modal('hide');

            });

            $("body").on('click','.criar_pdf_corretor',function(){
                $("#exampleModalTipoPlanos").removeClass('hidden').addClass('flex');
            });

            $("#closeModalTipoPlanos").on('click', function() {
                $("#exampleModalTipoPlanos").removeClass('flex').addClass('hidden');
            });


            $("body").on('click','.gerar_pdf_corretora_link',function(){

                let dados = {
                    "individual": $("#planos_tipo_individual_corretora").prop("checked"),
                    "coletivo": $("#planos_tipo_coletivo_corretora").prop("checked"),
                    "empresarial": $("#planos_tipo_empresarial_corretora").prop("checked"),
                    "user_id": $("#corretor_escolhido").val(),
                    "mes": $("#mes_folha option:selected").val(),
                    "ano": $("#mes_folha option:selected").text().split("/")[1],
                    "tipo":"corretora"
                };

                let queryString = $.param(dados);

                let url = "{{ route('gerente.finalizar.criarpdf') }}";
                url += "?" + queryString;
                window.open(url, '_blank');


            });



            $("body").on('click', '.gerar_pdf_corretor_link', function () {
                let dados = {
                    "individual": $("#planos_tipo_individual").prop("checked"),
                    "coletivo": $("#planos_tipo_coletivo").prop("checked"),
                    "empresarial": $("#planos_tipo_empresarial").prop("checked"),
                    "user_id": $("#corretor_escolhido").val(),
                    "mes": $("#mes_folha option:selected").val(),
                    "ano": $("#mes_folha option:selected").text().split("/")[1],
                    "tipo":"corretor"
                };
                let coletivoSelecionado = [];
                $("input[name='coletivo']:checked").each(function () {
                    coletivoSelecionado.push($(this).data("administradora"));
                });

                if (coletivoSelecionado.length > 0) {
                    dados["coletivo_valores"] = coletivoSelecionado;
                }
                let empresarialSelecionado = [];

                $("input[name='empresarial']:checked").each(function () {
                    empresarialSelecionado.push($(this).data("planos"));
                });

                if (empresarialSelecionado.length > 0) {
                    dados["empresarial_valores"] = empresarialSelecionado;
                }

                let queryString = $.param(dados);
                let url = "{{ route('gerente.finalizar.criarpdf') }}";
                url += "?" + queryString;
                window.open(url, '_blank');
                $("#exampleModalTipoPlanos").modal('hide');
            });






            /** Tabela de Listar A receber */
            var tableareceber = $(".listardados").DataTable({
                dom: '<"flex justify-between"<"#title_coletivo_por_adesao_table"><"estilizar_search"f>><t><"flex justify-between items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                language: {
                    "search": "Pesquisar",
                    "paginate": {
                        "next": "Próx.",
                        "previous": "Ant.",
                        "first": "Primeiro",
                        "last": "Último"
                    },
                    "emptyTable": "Nenhum registro encontrado",
                    "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "infoFiltered": "(Filtrados de _MAX_ registros)",
                    "infoThousands": ".",
                    "loadingRecords": "Carregando...",
                    "processing": "Processando...",
                    "lengthMenu": "Exibir _MENU_ por página"
                },
                ajax: {
                    "url":"{{ route('gerente.listagem.em_geral') }}",
                    "dataSrc": ""
                },
                "lengthMenu": [1000,2000,3000,4000,5000],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                columns: [
                    {data:"administradora",name:"administradora","width":"3%"},
                    {data:"corretor",name:"corretor","width":"10%"},
                    {data:"plano",name:"plano","width":"7%"},
                    {data:"tabela_origens",name:"cidade","width":"7%"},
                    {data:"cliente",name:"cliente","width":"20%"},
                    {data:"codigo_externo",name:"codigo_externo","width":"10%"},
                    {data:"valor",name:"valor_plano",render: $.fn.dataTable.render.number('.', ',', 2, ''),"width":"5%"},
                    {data:"vencimento",name:"data_boleto","width":"5%",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            let alvo = cellData.split("-").reverse().join("/");
                            $(td).html(alvo);
                        }
                    },
                    {data:"comissao",name:"detalhe","width":"5%",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            $(td).css({"text-align":"center"}).html("<a href='/gerente/detalhe/"+cellData+"' class='text-white btn_link'><i class='fas fa-eye'></i></a>")
                        }
                    },
                ],
                "columnDefs": [],
                "initComplete": function( settings, json ) {
                    $('#title_coletivo_por_adesao_table').html("<h4>Corretoras</h4>");
                        this.api()
                       .columns([2])
                       .every(function () {
                            var column = this;
                            var selectPlano = $("#select_plano");
                            selectPlano.on('change',function(){
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                if(val != "todos") {
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                } else {
                                    var val = "";
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                }
                            });
                            column.data().unique().sort().each(function (d, j) {
                                selectPlano.append('<option value="' + d + '">' + d + '</option>');
                            });
                       })
                       this.api()
                       .columns([0])
                       .every(function () {
                            var column = this;
                            var selectAdministradora = $("#select_administradora");

                            selectAdministradora.on('change',function(){
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                if(val != "todos") {
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                } else {
                                    var val = "";
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                }
                            });
                             column.data().unique().sort().each(function (d, j) {
                                 selectAdministradora.append('<option value="' + d + '">' + d + '</option>');
                             });
                        })
                       this.api()
                       .columns([1])
                       .every(function () {
                            var column = this;
                            var selectVendedor = $("#select_vendedor");
                            selectVendedor.on('change',function(){
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                if(val != "todos") {
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                } else {
                                    var val = "";
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                }
                            });
                            column.data().unique().sort().each(function (d, j) {
                                selectVendedor.append('<option value="' + d + '">' + d + '</option>');
                            });
                       })
                },
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    var intVal = function (i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };
                    conditionalTotal = 0;
                    qtdLinha = 0;
                    api.rows( { search: "applied" } ).every( function ( rowIdx, tableLoop, rowLoop ) {
                        var d = this.data();
                        conditionalTotal += intVal(d['valor']);
                        qtdLinha = rowLoop + 1;
                    });
                    $(".valor_quat_comissao_a_receber").html(conditionalTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
                    $("#quat_comissao_a_receber").html(qtdLinha);
                }
            });

            $("body").on('click','.btn_concluido',function(){
                let urlAtual = tableareceber.ajax.url();
                let ends = urlAtual.split("/");
                let ultimoElemento = ends[ends.length - 1];
                if(ultimoElemento == "listagem") {
                    $(".tabela_a_receber_container").slideUp('slow',function(){
                        tableareceber.ajax.url(`{{ route('gerente.listagem.concluidos') }}`).load();
                        tableareceber.on('draw.dt', function () {
                            tableareceber.column(8).nodes().each(function (cell, index) {
                                let link = $(cell).find('a').attr('href').split('/');
                                let id = link[link.length - 1];
                                $(cell).find('a').attr('href',`/gerente/pagos/detalhe/${id}`);
                            });
                        });
                        $(".tabela_a_receber_container").slideDown('fast');
                    });
                    $(this).text("Não Concluidos");
                } else {
                    $(".tabela_a_receber_container").slideUp('slow',function(){
                        tableareceber.ajax.url(`{{ route('gerente.listagem.em_geral') }}`).load();
                        $(".tabela_a_receber_container").slideDown('fast');
                    });
                    $(this).text("Concluidos");
                }
            });

             /** Fim Tabela de Listar A receber */
            var tabela_coletivo = $('#tabela_coletivo').DataTable();
            $('#tabela_coletivo').on('click', 'tbody tr', function () {
                tabela_coletivo.$('tr').removeClass('textoforte');
                $(this).closest('tr').addClass('textoforte');
                let data = tabela_coletivo.row(this).data();
                $("#id").val(data.id);
            });

            $('body').on('click','.btn_recebido',function(){
                $("#corretora").val($(this).attr('data-corretora'));
                $('#dataBaixaModal').modal('show');
            });



            $('form[name="data_da_baixa"]').on('submit',function(){
                let dados = $(this).serialize();
                $.ajax({
                    url:"{{route('gerente.mudar_status')}}",
                    method:"POST",
                    data:dados,
                    success:function(res) {

                        // if(res == "sucesso") {
                        //     tableareceber.ajax.reload();
                        // } else {

                        // }
                        // var select_mes =  $("#select_mes");
                        // select_mes.html('');
                        // select_mes.prepend('<option value="todos" class="text-center">---Escolher Mes---</option>');
                        // select_mes.append(res.datas_select);
                        // tableareceber.ajax.reload();
                        // listarcomissao.ajax.reload();
                        // $('#dataBaixaModal').modal('hide');
                        // $("#quat_comissao_a_receber").text(res.quat_comissao_a_receber);
                        // $("#quat_comissao_recebido").text(res.quat_comissao_recebido);

                        // $(".valor_quat_comissao_a_receber").html("R$ "+res.valor_quat_comissao_a_receber);
                        // $(".valor_quat_comissao_recebido").html("R$ "+res.valor_quat_comissao_recebido);

                        // $("#data_baixa").val('');

                    }
                })
                return false;
            });


            $("#listar_gerenciavel").on('click',function(){

                // var val = "";
                //
                //
                // $(".tabela_a_receber_container ").removeClass('ocultar');
                // $(".tabela_a_recebido_container").addClass('ocultar');
                //
                // $("#select_mes_recebido option[value='todos']").prop("selected",true);
                //
                // $("#select_administradora_recebido option[value='todos']").prop("selected",true);
                // $("#select_vendedor_recebido option[value='todos']").prop("selected",true);
                // $("#select_plano_recebido option[value='todos']").prop("selected",true);

                // tablearecebido.column(9).search(val).draw();
                // tablearecebido.column(9).search(val ? '^' + val + '$' : '',true,false).draw();
                // tablearecebido.column(0).search(val).draw();
                // tablearecebido.column(0).search(val ? '^' + val + '$' : '',true,false).draw();
                // tablearecebido.column(1).search(val).draw();
                // tablearecebido.column(1).search(val ? '^' + val + '$' : '',true,false).draw();
                // tablearecebido.column(2).search(val).draw();
                // tablearecebido.column(2).search(val ? '^' + val + '$' : '',true,false).draw();

                // $("#listar_recebido").removeClass("destaque");
                // $(this).addClass("destaque");

            });

            $('body').on('click','#listar_recebido',function(){
                var val = "";
                $(".tabela_a_receber_container ").addClass('ocultar');
                $(".tabela_a_recebido_container").removeClass('ocultar');
                $('#select_administradora option[value="todos"]').prop('selected',true);
                $('#select_vendedor option[value="todos"]').prop('selected',true);
                $('#select_plano option[value="todos"]').prop('selected',true);
                ///tableareceber.column(0).search(val).draw();
                ///tableareceber.column(0).search(val ? '^' + val + '$' : '',true,false).draw();
                // tableareceber.column(1).search(val).draw();
                // tableareceber.column(1).search(val ? '^' + val + '$' : '',true,false).draw();
                // tableareceber.column(2).search(val).draw();
                // tableareceber.column(2).search(val ? '^' + val + '$' : '',true,false).draw();
                $("#listar_gerenciavel").removeClass("destaque");
                $(this).addClass("destaque");
            });







            $("#select_mes_recebido").on('change',function(){

                if($(this).val() != "todos") {

                    tablearecebido
                    .column(9)
                    .search($(this).val())
                    .draw();
                } else {

                    var val = "";
                    tablearecebido
                    .column(9)
                    .search(val)
                    .draw();
                    tablearecebido.column(9).search(val ? '^' + val + '$' : '', true, false).draw();

                }

            });

            $("#select_administradora").on('change',function(){

            });
        });
    </script>
@stop

@section('css')
    <style>

        .loading-dots {display: flex;justify-content: center;align-items: center;}
        .loading-dots div {width: 12px;height: 12px;margin: 10px 4px;border-radius: 50%;background-color: #333;animation: loading-dots 1.2s infinite ease-in-out;}
        .loading-dots div:nth-child(1) {animation-delay: 0s;}
        .loading-dots div:nth-child(2) {animation-delay: 0.2s;}
        .loading-dots div:nth-child(3) {animation-delay: 0.4s;}
        @keyframes loading-dots {
            0%, 80%, 100% {transform: scale(0);}
            40% {transform: scale(1);}
        }
        .pagar_comissao_up {border:1px solid white;padding:3px;cursor:pointer;}
        .pagar_comissao_up:hover,
        .pagar_comissao_up:focus {border:1px solid orange;background-color:orange;color:black;}
        #listar_individual_apto,#listar_coletivo_apto,#listar_empresarial_apto,#listar_individual_apto_total,#listar_coletivo_apto_total,#listar_empresarial_apto_total span {color:#FFF;}
        .valores_em_destaque {color:black;background-color:white;padding:3px;border-radius:50%;font-size:0.9em;margin:2px;width:20px;display:flex;justify-content: center;font-weight:bold;}
        .btn_concluido {background-color:#123449;border-radius:5px;display:flex;justify-content:center;align-items:center;align-content:center;padding:10px;}
        .btn_concluido:hover {cursor:pointer;}
        .client-cell {max-width: 40%;overflow: hidden;text-overflow: ellipsis;}
        #corretor_em_destaque {margin-left:1%;background-color:#123449;width:600px;}
        .tamanho_de_25 {height: 40px;}
        .dsnone {display:none;}
        .ocultar {display:none !important;}
        .aba_comissao_container,.aba_historico_container {display:flex;position:relative;justify-content: space-between;flex-basis:100%;}
        .dataTables_wrapper .dataTables_wrapper .dataTables_scrollBody table.dataTable {padding: 0;}
        #list_user,#list_user_historico {height:160px;overflow:auto;border-radius:5px;}
        #list_user::-webkit-scrollbar,#list_user_historico::-webkit-scrollbar {width: 2px;height: 2px !important;background-color: white;}
        #list_user::-webkit-scrollbar-thumb,#list_user_historico::-webkit-scrollbar-thumb {background-color: #1a88ff;}
        .user_destaque_impar {background-color:rgba(0,0,0,0.5) !important;}
        .user_destaque_hover {background-color:red;}
        #tabela_coletivo td {white-space: nowrap;overflow: hidden;text-overflow: clip;}
        .dataTables_wrapper .dataTables_wrapper .dataTables_scrollBody td,.dataTables_wrapper .dataTables_wrapper .dataTables_scrollBody th {padding: 0;}
        .menu_aba_comissao {margin-right: 1%;display:flex;flex-direction:column;}
        .list_administradoras {display:flex;flex-direction: column;color:#fff;justify-content: center;}
        .total_mes_comissao {color:#FFF;text-align: center;}
        #container_mostrar_comissao {width:439px;height:555px;background-color: #123449;position: absolute;right:5px;border-radius: 5px;}
        .container_edit {display:flex;justify-content:end;}
        .ativo {background-color:red !important;color:orange !important;}
        .ocultar {display: none;}
        .list_abas {list-style: none;display: flex;border-bottom: 1px solid white;margin: 0;padding: 0;}
        .list_abas li {color: #fff;width: 150px;padding: 8px 5px;text-align:center;border-radius: 5px 5px 0 0;background-color:#123449;}
        .list_abas li:hover {cursor: pointer;}
        .list_abas li:nth-of-type(2) {margin: 0 1%;}
        .textoforte {background-color:rgba(255,255,255,0.5) !important;color:black;}
        .textoforte-list {background-color:rgba(255,255,255,0.5);color:white;}
        .destaque {background-color:rgba(255,255,255,0.5) !important;color:black;border:1px solid black;}
        .ativo {background-color:#FFF !important;color: #000 !important;}
        .botao:hover {background-color: rgba(0,0,0,0.5) !important;color:#FFF !important;}
        .valores-acomodacao {background-color:#123449;color:#FFF;width:32%;box-shadow:rgba(0,0,0,0.8) 0.6em 0.7em 5px;}
        .valores-acomodacao:hover {cursor:pointer;box-shadow: none;}
        .table thead tr {color: white;}
        .destaque {border:4px solid rgba(36,125,157);}
        #coluna_direita {flex-basis:10%;background-color:#123449;border-radius: 5px;}
        #coluna_direita ul {list-style: none;margin: 0;padding: 0;}
        #coluna_direita li {color:#FFF;}
        .coluna-right {flex-basis:30%;flex-wrap: wrap;border-radius:5px;height:720px;}
        .container_div_info {background-color:rgba(0,0,0,1);position:absolute;width:500px;right:0px;top:57px;min-height: 700px;display: none;z-index: 1;color: #FFF;}
        #padrao {width:50px;background-color:#FFF;color:#000;}
        .buttons {display: flex;}
        .button_individual {display:flex;}
        .button_empresarial {display: flex;}
        .menu-inativo {color:#FFF;}
        .btn_recebido {background-color:green;color:#FFF;border:none;}
        th { font-size: 0.78em !important; }
        td { font-size: 0.65em !important; }
        #tabelaResultados th {font-size: 1em !important;}
        #tabelaResultados td {font-size: 0.8em !important;}
        .dt-right {text-align: right !important;}
        .dt-center {text-align: center !important;}
        .estilizar_pagination .pagination {font-size: 0.8em !important;color:#FFF;}
        .estilizar_pagination .pagination li {height:10px;color:#FFF;}
        .por_pagina {font-size: 12px !important;color:#FFF;}
        #tabela_mes_diferente {table-layout: fixed;}
        .por_pagina #tabela_mes_atual_length {display: flex;align-items: center;align-self: center;margin-top: 8px;}
        .por_pagina #tabela_mes_diferente_length {display: flex;align-items: center;align-self: center;margin-top: 8px;}
        .por_pagina select {color:#FFF !important;}
        #tabela_individual_previous {color:#FFF !important;background-color: red !important;}
        #tabela_individual_next {color:#FFF !important;}
        #tabela_coletivo_previous {color:#FFF !important;}
        #tabela_coletivo_next {color:#FFF !important;}
        .estilizar_search input[type='search'] {background-color: #FFF !important;width:500px;}
        .tabela_individual_paginate {color:#FFF !important;}
        .link_coletivo_um:hover {background-color: rgb(245,50,16) !important;}
        .link_coletivo_dois:hover {background-color: rgb(245,50,16) !important;}
        .link_coletivo_tres:hover {background-color: rgb(245,50,16) !important;}
        .link_coletivo_quatro:hover {background-color: rgb(245,50,16) !important;}
        .link_coletivo_cinco:hover {background-color: rgb(245,50,16) !important;}
        .link_coletivo_seis:hover {background-color: rgb(245,50,16) !important;}
        .link_empresarial_um:hover {background-color: rgb(254,200,109) !important;}
        .link_empresarial_dois:hover {background-color: rgb(254,200,109) !important;}
        .link_empresarial_tres:hover {background-color: rgb(254,200,109) !important;}
        .link_empresarial_quatro:hover {background-color: rgb(254,200,109) !important;}
        .link_empresarial_cinco:hover {background-color: rgb(254,200,109) !important;}
        .link_empresarial_seis:hover {background-color: rgb(254,200,109) !important;}
        .user_nome {font-size: 0.7em;flex: 1;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;}
        .user_total {font-size: 0.6em;flex-shrink: 0;margin-left: 5px;}
        .user_destaque_ativo {background-color:#FFF !important;color:black !important;}
        #tabela_mes_diferente_filter input[type="search"],
        #tabela_mes_recebidas_filter input[type="search"],
        #tabela_cadastrados_filter input[type="search"] {margin:5px 5px 0px 9px;}
        #title_comissao_diferente,#title_recebidas,#title_cadastrados {margin:5px 0 0 9px;}
        #tabela_aptos_a_pagar_table td {white-space: nowrap;overflow: hidden;text-overflow: clip;}
        #tabela_mes_diferente td {white-space: nowrap;overflow: hidden;text-overflow: clip;}
        #tabelaResultados td {white-space: nowrap;overflow: hidden;text-overflow: clip;}
        .small-svg
        {
            width: 16px;
            height: 16px;
        }
    </style>
@stop
</x-app-layout>

