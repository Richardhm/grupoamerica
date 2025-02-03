<section class="p-1 card_info">
    <div class="line01">
        <div class="administradora">
            <span class="text-white" style="font-size:0.81em;">Administradora:</span>
            <input type="text" value="Hapvida" name="administradora_individual" id="administradora_individual" class="form-control form-control-sm" readonly>
        </div>
        <div class="tipo_plano">    
            <span class="text-white" style="font-size:0.81em;">Tipo Plano:</span>
            <input type="text" value="Individual"   id="tipo_plano_individual" class="form-control form-control-sm" readonly>
        </div>
        <div class="status">
            <span style="font-size:0.81em;" class="text-white">Status:</span>
            <input type="text" id="status_individual_view" value="" class="form-control form-control-sm" readonly>
        </div>    
    </div>
    <div class="line02">
        <div class="cliente">
            <span class="text-white" style="font-size:0.81em;">Cliente:</span>
            <input type="text" name="cliente" id="cliente" class="form-control form-control-sm" value="{{$dados['nmUsuario']}}" readonly>
        </div>
        <div class="data_nascimento">
            <span class="text-white" style="font-size:0.81em;">Data Nascimento:</span>
            <input type="text" name="data_nascimento" id="data_nascimento" class="form-control form-control-sm" value="{{$dados['dtNascimentoUsuario']}}" readonly>
        </div>
        <div class="codigo_externo">
            <span class="text-white" style="font-size:0.81em;">Codigo Externo:</span>
            <input type="text" name="codigo_externo" id="codigo_externo_individual" value="{{$dados['nuMatriculaEmpresa']}}" class="form-control form-control-sm" readonly>
        </div>    

    </div>
    
    <div class="line03">
        <div class="cpf">
            <span class="text-white" style="font-size:0.81em;">CPF:</span>
            <input type="text" id="cpf" class="form-control form-control-sm" value="{{$dados['nuCpf']}}" readonly>
        </div>
        <div class="celular">
            <span style="flex-basis:90%;">
            <span class="text-white" style="font-size:0.81em;">Celular:</span>
                <input type="text" id="celular_individual_view_input" value="{{$celular}}" class="form-control form-control-sm" readonly>
            </span>
            <a class="" style="background: #25cb66;flex-basis:10%;
                border-radius: 8px !important;
                padding: 3px 7px;
                text-align: center;
                color:#FFF;
                font-weight: bold;
                margin-bottom: 0px;
                text-decoration:none;" href="https://api.whatsapp.com/send?phone=55{{$last->nuFone}}&amp;text=Oi tudo bem?" target="_blank" rel="nofollow">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
        <div class="telefone">
            <span class="text-white" style="font-size:0.81em;">Telefone:</span>
            <input type="text" id="telefone_individual_view_input" class="form-control form-control-sm" readonly>
        </div>
    </div>
    <div class="line04">
        <div class="email">
            <span class="text-white" style="font-size:0.81em;">Email:</span>
            <input type="text" id="email" class="form-control form-control-sm" value="{{$last->dsEmail}}" readonly>
        </div>
        <div class="numero_registro">
            <span class="text-white" style="font-size:0.81em;">Numero Registro:</span>
            <input type="text" id="numero_registro" class="form-control  form-control-sm" value="{{$dados['nuRegistroPlano']}}" readonly>
        </div>
        <div class="carteirinha">
            <span class="text-white" style="font-size:0.81em;">Carteirinha:</span>
            <input type="text" id="carteirinha" class="form-control  form-control-sm" value="{{$dados['cdUsuario']}}" readonly>
        </div>
    </div>
    <div class="line05">
        <div class="cep">
            <span class="text-white" style="font-size:0.81em;">CEP:</span>
            <input type="text" name="cep" id="cep_individual_cadastro" value="{{$dados['cepEndereco']}}" class="form-control form-control-sm" readonly>
        </div>
        <div class="cidade">
            <span class="text-white" style="font-size:0.81em;">Cidade:</span> 
            <input type="text" id="cidade" class="form-control  form-control-sm" value="{{$dados['cidadeEndereco']}}" readonly>
        </div>
        
        <div class="bairro">
            <span class="text-white" style="font-size:0.81em;">Bairro:</span>
            <input type="text" id="bairro_individual_cadastro" class="form-control form-control-sm" value="{{$dados['bairroEndereco']}}" readonly>
        </div>                       
    </div>
    <div class="line06">
        <div class="rua">
            <span class="text-white" style="font-size:0.81em;">Rua:</span>
            <input type="text" id="rua_individual_cadastro" class="form-control form-control-sm" value="{{$dados['ruaEndereco']}}" readonly>
        </div>
        <div class="complemento">
            <span class="text-white" style="font-size:0.81em;">Complemento:</span>
            <input type="text" id="complemento_individual_cadastro" class="form-control form-control-sm" value="" readonly>
        </div>
        <div class="uf">
            <span class="text-white" style="font-size:0.81em;">UF:</span>
            <input type="text" id="uf" class="form-control form-control-sm" value="{{$dados['ufEndereco']}}" readonly>
        </div>      
    </div>       
    <div class="line07">
        <div class="data_contrato">
            <span class="text-white" style="font-size:0.81em;">Data Contrato:</span>
            <input type="text" name="data_contrato" id="data_contrato" class="form-control form-control-sm" value="{{$dados['dtAdesaoC']}}" readonly>
        </div>
        <div class="valor_contrato">
            <span class="text-white" style="font-size:0.81em;">Valor Contrato:</span>
            <input type="text" name="valor_contrato" id="valor_contrato" value="" class="form-control  form-control-sm" readonly>
        </div>
        <div class="valor_adesao">
            <span class="text-white" style="font-size:0.81em;">Valor Adesão:</span>
            <input type="text" name="valor_adesao" id="valor_adesao" value=""  class="form-control  form-control-sm" readonly>
        </div>

        <div class="data_boleto">
            <span class="text-white" style="font-size:0.81em;">Data Boleto:</span>
            <input type="text" name="data_boleto" id="data_boleto" value="" class="form-control  form-control-sm" readonly>
        </div>
        <div class="vidas">    
            <span class="text-white" style="font-size:0.81em;">Vidas</span>
            <input type="text" name="quantidade_vidas" id="quantidade_vidas_individual_cadastrar" value="" class="form-control  form-control-sm" readonly>
        </div>  
    </div>
    <div class="line08">  
            <div class="data_vigencia">
            <span class="text-white" style="font-size:0.81em;">Data Vigência:</span>
            <input type="text" name="data_vigencia" id="data_vigencia" class="form-control  form-control-sm" value="" readonly>
        </div>
        <div class="rede_plano">
            <span class="text-white" style="font-size:0.81em;">Rede Plano:</span>
            <input type="text" id="rede_plano" class="form-control  form-control-sm" value="{{$dados['redePlano']}}" readonly>
        </div>
        <div class="segmentacao">
            <span class="text-white" style="font-size:0.81em;">Segmentaçao:</span>
            <input type="text" id="segmentacao" class="form-control  form-control-sm" value="{{$dados['segmentacaoPlano']}}" readonly>
        </div>
        <input type="hidden" id="cliente_id_alvo_individual" />
    </div>    
    <div class="line09">
        <div class="plano">
            <span class="text-white" style="font-size:0.81em;">Plano:</span>
            <input type="text" id="plano" class="form-control  form-control-sm" value="{{$dados['nmPlano']}}" readonly>
        </div>

        <div class="tipo_plano_acomodacao">
            <span class="text-white" style="font-size:0.81em;">Tipo Plano:</span>
            <input type="text" id="tipo_acomodacao_plano" class="form-control form-control-sm" value="{{$dados['tipoAcomodacaoPlano']}}" readonly>     
        </div>    
    </div> 
</section>   
@php            
@endphp
<section class="historico_corretor" style="">

    @php
        $ii=0;
    @endphp
    

    <table class="table table-sm">
        <thead>
            <tr style="background-color:#FFF;color:#000;">
                <td>Parcela</td>
                <td>Vencimento</td>
                <td>Pagamento</td>
                <td>Valor</td>
                <td>Status</td>
            </tr>
        </thead>
        <tbody>
            @foreach($resultado as $r)
            @php  
                $ii++; 

                


            @endphp
            <tr>
                <td class="parcela_parcela_um">{{$ii}}</td>
                <td>{{$r->dtVencimento}}</td>
                <td>{{$r->dtPagamento != '' ? $r->dtPagamento : '----'}}</td>
                <td>{{number_format($r->vlObrigacao,2,",",".")}}</td>
                <td>{{mb_convert_case($r->dsStatus,MB_CASE_TITLE)}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>









    
</section>    

<section style="flex-basis:100%;">
    <a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-block btn-lg mt-3 text-white" style="background-color:#123449;">Voltar</a>                                                                           
</section>                        