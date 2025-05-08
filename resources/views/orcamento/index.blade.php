<x-app-layout>
    <input type="hidden" id="odonto_resultado" />
    <div class="max-w-full mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-x-4 px-4">
        <x-informacoes :cidades="$cidades" class="sm:mx-5"></x-informacoes>
        <x-operadoras :operadoras="$administradoras" class="sm:mx-5"></x-operadoras>
        <x-planos :planos="$planos" class="sm:mx-5"></x-planos>
        <div class="p-1 rounded mt-2 hidden bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] border w-full lg:w-[30%] sm:mx-5" id="resultado"></div>
    </div>

    <div id="modalPlanoAmbulatorial" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] px-6 py-10 rounded-lg shadow-lg w-96 text-white border-white border-4">
            <div class="flex justify-between">
                <h2 class="text-lg font-bold mb-4 mx-auto">Escolha a Opção</h2>
                <svg xmlns="http://www.w3.org/2000/svg" id="fecharModalAmbulatorial" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-6 border-white border-4 rounded hover:cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>

            {{--                <div class="space-y-2">--}}
            {{--                    <label class="flex items-center space-x-2">--}}
            {{--                        <input type="checkbox" id="comCoparticipacao" checked="checked" class="form-checkbox">--}}
            {{--                        <span class="font-semibold">Com Coparticipação</span>--}}
            {{--                    </label>--}}
            {{--                    <label class="flex items-center space-x-2">--}}
            {{--                        <input type="checkbox" id="semCoparticipacao" checked="checked" class="form-checkbox">--}}
            {{--                        <span class="font-semibold">Sem Coparticipação</span>--}}
            {{--                    </label>--}}
            {{--                    <label class="flex items-center space-x-2">--}}
            {{--                        <input type="checkbox" id="apenasValores" class="form-checkbox">--}}
            {{--                        <span class="font-semibold">Apenas Valores</span>--}}
            {{--                    </label>--}}
            {{--                </div>--}}

            <fieldset class="border-4 border-gray-300 rounded-lg p-4 mt-4">
                <legend class="text-lg font-semibold px-2 mx-auto">Tipo Documento</legend>
                <div class="flex justify-between items-center">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="tipo_cotacao_ambulatorial" value="imagem" checked>
                        <span class="font-semibold">Imagem</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="tipo_cotacao_ambulatorial" value="pdf">
                        <span class="font-semibold">PDF</span>
                    </label>
                </div>
            </fieldset>


            <div class="flex justify-center mt-3">
                <button id="gerarImagemAmbulatorial" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full w-full text-lg">Gerar</button>
            </div>
        </div>
    </div>





    <div id="modalPlano" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] px-6 py-10 rounded-lg shadow-lg w-96 text-white border-white border-4">
            <div class="flex justify-between">
                <h2 class="text-lg font-bold mb-4 mx-auto">Escolha a Opção</h2>
                <svg xmlns="http://www.w3.org/2000/svg" id="fecharModal" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-6 border-white border-4 rounded hover:cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>

            <div class="space-y-2">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" id="comCoparticipacao" checked="checked" class="form-checkbox">
                    <span class="font-semibold">Com Coparticipação</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" id="semCoparticipacao" checked="checked" class="form-checkbox">
                    <span class="font-semibold">Sem Coparticipação</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" id="apenasValores" class="form-checkbox">
                    <span class="font-semibold">Apenas Valores</span>
                </label>
            </div>

            <fieldset class="border-4 border-gray-300 rounded-lg p-4 mt-4">
                <legend class="text-lg font-semibold px-2 mx-auto">Tipo Documento</legend>
                <div class="flex justify-between items-center">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="tipo_cotacao" value="imagem" checked>
                        <span class="font-semibold">Imagem</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="tipo_cotacao" value="pdf">
                        <span class="font-semibold">PDF</span>
                    </label>
                </div>
            </fieldset>


            <div class="flex justify-center mt-3">
                <button id="gerarImagem" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full w-full text-lg">Gerar</button>
            </div>
        </div>
    </div>



    @section('scripts')
       <script>
           $(document).ready(function(){
               function scrollToBottom() {
                   if (window.innerWidth <= 768) { // Aplica apenas para mobile
                       $('html, body').animate({
                           scrollTop: $(document).height() // Define o scroll para o final do documento
                       },1500,'swing'); // Tempo da animação (1 segundo)
                   }
               }

               // Exemplo de onde você pode chamar o scrollToBottom:
               $("input[name='operadoras']").on('change', function(){
                   // Lógica para mostrar operadoras
                   scrollToBottom(); // Chama o scroll para o bottom após a mudança de etapa
               });

               $("input[name='planos-radio']").on('click', function(){
                   // Lógica para selecionar um plano
                   scrollToBottom(); // Chama o scroll para o bottom após a seleção do plano
               });

               $("input[type='text']").on('input', function(){
                   // Quando o usuário digitar algo, o scroll segue o progresso
                   scrollToBottom();
               });

               function ultimaEtapa() {
                   scrollToBottom();
               }

               $.ajaxSetup({
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   }
               });

               $("body").on('change touchstart',"input[name='operadoras']",function(e){
                   e.preventDefault();
                   let valor = $(this).val();
                   let cidade = $("#cidade").val();
                   if($("#resultado").is(":visible")){
                       $("input[name='planos-radio']").prop('checked', false);
                       $("#resultado").hide().empty();
                   }
                   $.ajax({
                       url: '{{route('buscar_planos')}}',  // URL da rota que irá processar a requisição
                       type: 'POST',
                       data: {
                           administradora_id: valor,
                           tabela_origens_id: cidade
                       },
                       headers: {
                           'X-Requested-With': 'XMLHttpRequest', // Define como uma requisição AJAX
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Inclui o CSRF token
                       },
                       success: function(response) {
                           // Atualiza a lista de planos com os dados recebidos
                           $('#planos').removeClass('hidden').find('div[data-plano]').each(function() {
                               let planoId = $(this).data('plano');
                               if (response.planos.includes(planoId)) {
                                   $(this).show();  // Mostra o plano
                               } else {
                                   $(this).hide();  // Esconde o plano
                               }
                           });
                       },
                       error: function() {
                           alert('Erro ao buscar os planos. Tente novamente.');
                       }
                   });
                   return false;
               })

               /*****************verificar se cidade e minus estão preenchidos para aparecer administradoras*******/
               function checkFields() {
                   var hasValue = false;
                   // Verifica se algum campo de texto tem valor diferente de vazio ou zero
                   $('input[type="text"]').each(function() {

                       if ($(this).val().trim() !== '' && $(this).val() !== '0') {
                           hasValue = true;
                       }
                   });
                   // Verifica se o select está preenchido
                   var cidadeSelected = $('#cidade').val() !== '';
                   // Se ambas as condições forem verdadeiras, remova a classe 'hidden'
                   if (hasValue && cidadeSelected) {
                       $('#operadoras').removeClass('hidden');
                   } else {
                       $('#operadoras').addClass('hidden');
                   }

                   if($("#planos").is(":visible") && $("#operadoras").is(":visible") && $("#resultado").is(":visible")) {
                       atualizarResultado();
                   }
               }

               $('input[type="text"]').on('input', checkFields);
               $('#cidade').on('change', checkFields);
               /*****************verificar se cidade e minus estão preenchidos para aparecer administradoras*******/

               /***********Incrementar valores aos input*****************************/
               $('.faixa-etaria-buttons').each(function() {
                   const $container = $(this);
                   const $input = $container.find('.faixa-etaria-input');
                   const $plusBtn = $container.find('.bg-green-400');
                   const $minusBtn = $container.find('.bg-red-400');

                   // Função para atualizar o estado dos botões
                   const updateButtonsState = () => {
                       const currentValue = parseInt($input.val()) || 0;
                       $minusBtn.prop('disabled', currentValue <= 0);
                   };

                   // Evento de incremento
                   $plusBtn.on('click', function(e) {
                       e.preventDefault();
                       const currentTotal = getTotal();
                       if (currentTotal < 7) {
                           $input.val((i, val) => {
                               const newVal = parseInt(val) + 1;
                               return isNaN(newVal) ? 1 : newVal;
                           }).trigger('input');
                           updateButtonsState();
                       } else {
                           toastr.error('O total máximo de 7 pessoas foi atingido!', 'Limite Atingido', {
                               timeOut: 3000,
                               progressBar: true,
                               closeButton: true
                           });
                       }
                   });

                   // Evento de decremento
                   $minusBtn.on('click', function(e) {
                       e.preventDefault();
                       $input.val((i, val) => {
                           const newVal = parseInt(val) - 1;
                           return newVal < 0 ? 0 : newVal;
                       }).trigger('input');
                       updateButtonsState();
                   });

                   // Validação direta no input
                   $input.on('input', function() {
                       let value = parseInt($(this).val()) || 0;
                       if (value < 0) value = 0;

                       // Verifica o total global
                       const currentTotal = getTotal();
                       if (currentTotal > 7) {
                           value -= (currentTotal - 7);
                           toastr.error(`Limitado a 7 pessoas.`, 'Limite Atingido', {
                               timeOut: 3000,
                               progressBar: true,
                               closeButton: true
                           });
                       }

                       $(this).val(value);
                       updateButtonsState();
                   });

                   updateButtonsState(); // Estado inicial
               });

               // Função para calcular o total
               function getTotal() {
                   return $('.faixa-etaria-input').get().reduce((total, input) => {
                       return total + (parseInt(input.value) || 0);
                   }, 0);
               }
               /***********Incrementar valores aos input*****************************/


               function atualizarResultado(ambulatorial = 0) {

                   setTimeout(()=>{
                       let cidade = "";
                       let plano = "";
                       let operadora = "";
                       let faixas = [];
                       let status_carencia = "";

                       cidade = $("#cidade").val();
                       plano = $("input[name='planos-radio']:checked").val();
                       operadora = $("input[name='operadoras']:checked").val();
                       status_carencia = $("input[name='status_carencia']:checked").val();
                       status_carencia = status_carencia === 'true'
                       faixas = [{

                           '1' : $("body").find("#input_0_18").val(),
                           '2' : $("body").find('#input_19_23').val(),
                           '3' : $("body").find('#input_24_28').val(),
                           '4' : $("body").find('#input_29_33').val(),
                           '5' : $("body").find('#input_34_38').val(),
                           '6' : $("body").find('#input_39_43').val(),
                           '7' : $("body").find('#input_44_48').val(),
                           '8' : $("body").find('#input_49_53').val(),
                           '9' : $("body").find('#input_54_58').val(),
                           '10' : $("body").find('#input_59').val()

                       }];

                       $.ajax({
                           url: "{{ route('orcamento.montarOrcamento') }}",
                           method: "POST",
                           headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                           data: {
                               "tabela_origem": cidade,
                               "plano": plano,
                               "operadora": operadora,
                               "faixas": faixas,
                               "status_carencia":status_carencia,
                               "_token": "{{ csrf_token() }}",
                               "ambulatorial" : ambulatorial
                           },
                           success: function(res) {

                               $("#resultado").removeClass('hidden').slideDown('slow').html(res);
                               // //interacaoContador++;
                               return false;
                           }
                       });


                   },0.1);


                   return false;

               }

               $("body").on('click',"input[name='planos-radio']",function(){
                   let valor = $(this).val();
                   atualizarResultado();
               });

               $("#fecharModal").on("click", function () {
                   $("#modalPlano").addClass("hidden");
               });


               $("#modalPlano").on("click", function(event) {
                   // Verifica se clicou diretamente no overlay, e não no conteúdo interno
                   if (event.target === this) {
                       $(this).addClass("hidden");
                   }
               });


               $("body").on('click',".downloadLink",function(){
                   $("#modalPlano").removeClass("hidden"); // Exibe a modal
                   let odonto = $(this).attr('data-odonto');
                   $("#odonto_resultado").val(odonto);
               });

               $("#fecharModalAmbulatorial").on("click",function(){
                   $("#modalPlanoAmbulatorial").addClass("hidden");
               });

               $("#modalPlanoAmbulatorial").on("click", function(event) {
                   // Verifica se clicou diretamente no overlay, e não no conteúdo interno
                   if (event.target === this) {
                       $(this).addClass("hidden");
                   }
               });



               $("body").on('click',"#gerarImagem",function(e){
                   let comCoparticipacaoMarcado = $("#comCoparticipacao").is(":checked");
                   let semCoparticipacaoMarcado = $("#semCoparticipacao").is(":checked");
                   let apenasValores = $("#apenasValores").is(":checked");




                   let load = $(".ajax_load");
                   e.preventDefault();
                   let linkUrl = $(this).attr("href");

                   let cidade = "";
                   let plano = "";
                   let operadora = "";
                   let faixas = [];
                   let odonto = "";
                   odonto = $("#odonto_resultado").val();
                   cidade = $("#cidade").val();
                   plano = $("input[name='planos-radio']:checked").val();
                   operadora = $("input[name='operadoras']:checked").val();
                   let status_carencia = $("input[name='status_carencia']").is(':checked');
                   let status_desconto = $("input[name='status_desconto']").is(':checked');

                   // Exibe o valor booleano no console
                   let tipo_documento = $("input[name='tipo_cotacao']:checked").val();

                   faixas = [{
                       '1' : $("body").find("#input_0_18").val(),
                       '2' : $("body").find('#input_19_23').val(),
                       '3' : $("body").find('#input_24_28').val(),
                       '4' : $("body").find('#input_29_33').val(),
                       '5' : $("body").find('#input_34_38').val(),
                       '6' : $("body").find('#input_39_43').val(),
                       '7' : $("body").find('#input_44_48').val(),
                       '8' : $("body").find('#input_49_53').val(),
                       '9' : $("body").find('#input_54_58').val(),
                       '10' : $("body").find('#input_59').val()
                   }];



                   $.ajax({
                       url: "{{route('gerar.imagem')}}",
                       method: "POST",
                       data: {
                           "comcoparticipacao": comCoparticipacaoMarcado,
                           "semcoparticipacao": semCoparticipacaoMarcado,
                           "tabela_origem": cidade,
                           "plano": plano,
                           "operadora": operadora,
                           "faixas": faixas,
                           "odonto" : odonto,
                           "status_carencia" : status_carencia,
                           "status_desconto" : status_desconto,
                           "ambulatorial": 0,
                           "apenasvalores" : apenasValores,
                           "tipo_documento" : tipo_documento
                           //"cliente" : cliente,
                           //"_token": "{{ csrf_token() }}"
                       },
                       xhrFields: {
                           responseType: 'blob'
                       },
                       beforeSend: function () {
                           load.fadeIn(100).css("display", "flex");
                       },
                       success:function(blob,status,xhr,ppp) {
                           if(blob.size && blob.size != undefined) {
                               var filename = "";
                               var disposition = xhr.getResponseHeader('Content-Disposition');
                               if (disposition && disposition.indexOf('attachment') !== -1) {
                                   var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                                   var matches = filenameRegex.exec(disposition);
                                   if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                               }
                               if (typeof window.navigator.msSaveBlob !== 'undefined') {
                                   window.navigator.msSaveBlob(blob, filename);
                               } else {
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
                                   load.fadeOut(100).css("display", "none");
                               }
                           }
                       }
                   });
                   return false;
               });

               $("body").on('click','#gerarImagemAmbulatorial',function(e){
                   let load = $(".ajax_load");
                   e.preventDefault();
                   let linkUrl = $(this).attr("href");
                   let cidade = "";
                   let plano = "";
                   let operadora = "";
                   let faixas = [];
                   let odonto = "";
                   let status_carencia = $("input[name='status_carencia_ambulatorial']").is(':checked');
                   let status_desconto = $("input[name='status_desconto_ambulatorial']").is(':checked');
                   let tipo_documento = $("input[name='tipo_cotacao_ambulatorial']:checked").val();


                   cidade = $("#cidade").val();
                   plano = $("input[name='planos-radio']:checked").val();
                   operadora = $("input[name='operadoras']:checked").val();
                   // Exibe o valor booleano no console
                   odonto = $("#odonto_resultado").val();
                   faixas = [{
                       '1' : $("body").find("#input_0_18").val(),
                       '2' : $("body").find('#input_19_23').val(),
                       '3' : $("body").find('#input_24_28').val(),
                       '4' : $("body").find('#input_29_33').val(),
                       '5' : $("body").find('#input_34_38').val(),
                       '6' : $("body").find('#input_39_43').val(),
                       '7' : $("body").find('#input_44_48').val(),
                       '8' : $("body").find('#input_49_53').val(),
                       '9' : $("body").find('#input_54_58').val(),
                       '10' : $("body").find('#input_59').val()
                   }];
                   $.ajax({
                       url: "{{route('gerar.imagem')}}",
                       method: "POST",
                       data: {
                           "tabela_origem": cidade,
                           "plano": plano,
                           "operadora": operadora,
                           "faixas": faixas,
                           "odonto" : odonto,
                           "status_carencia" : status_carencia,
                           "status_desconto" : status_desconto,
                           "ambulatorial": 1,
                           "tipo_documento" : tipo_documento
                           //"cliente" : cliente,
                           //"_token": "{{ csrf_token() }}"
                       },
                       xhrFields: {
                           responseType: 'blob'
                       },
                       beforeSend: function () {
                           load.fadeIn(100).css("display", "flex");
                       },
                       success:function(blob,status,xhr,ppp) {
                           if(blob.size && blob.size != undefined) {

                               var filename = "";
                               var disposition = xhr.getResponseHeader('Content-Disposition');
                               if (disposition && disposition.indexOf('attachment') !== -1) {
                                   var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                                   var matches = filenameRegex.exec(disposition);
                                   if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                               }
                               if (typeof window.navigator.msSaveBlob !== 'undefined') {
                                   window.navigator.msSaveBlob(blob, filename);
                               } else {
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
                                   load.fadeOut(100).css("display", "none");
                               }
                           }
                       }
                   });
                   return false;
               });





               $("body").on('click',".downloadLinkAmbulatorial",function(e){
                   $("#modalPlanoAmbulatorial").removeClass("hidden"); // Exibe a modal
                   let odonto = $(this).attr('data-odonto');
                   $("#odonto_resultado").val(odonto);
               });




               $("body").on('click','.btn_ambulatorial',function(){
                  $("#resultado").slideUp("slow");
                  $("#resultado").empty();
                   atualizarResultado(1)

               });

               $("body").on('click','.btn_normal',function(){
                   $("#resultado").slideUp("slow");
                   $("#resultado").empty();
                   atualizarResultado(0)
               });


           });
      </script>
    @endsection
</x-app-layout>
