<x-app-layout>

    <h2 class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] text-center p-1 w-[95%] mx-auto my-1 text-white rounded">Escolher UF e Layout de Preferencia</h2>



    <div class="flex items-start justify-around w-[95%] mx-auto">

        <div class="flex items-center w-[30%] flex-wrap bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] border-white border mb-1 rounded p-1">

               <label  for="regiao" class="block text-sm font-medium text-white mb-1 w-[50%]">Região (UF) de Preferência</label>
               <select name="regiao" id="regiao" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white w-[30%]" translate="no">
                   <option value="" disabled selected>UF</option>
                   @foreach($estados as $uf)
                       <option value="{{ $uf->uf }}" {{ auth()->user()->uf_preferencia === $uf->uf ? 'selected' : '' }}>{{ $uf->uf }}</option>
                   @endforeach
               </select>


        </div>


        <div class="flex w-[50%]">
            <div class="grid grid-cols-2 gap-1">
                @foreach($layouts as $layout)
                    <label
                        style="height: 300px; max-height: 300px; width:350px; max-width:350px;"
                        class="layout relative group flex flex-col items-center border p-0 rounded bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]"
                    >
                        <!-- Input Radio -->
                        <input
                            type="radio"
                            id="layout_{{ $layout->id }}"
                            name="layout_id"
                            value="{{ $layout->id }}"
                            {{ $layout->id == $user->layout_id ? 'checked' : '' }}
                            class="w-[5%] cursor-pointer appearance-none my-1 border-4 border-white rounded-full transition-all duration-300 group-hover:scale-110 group-hover:z-10 group-hover:shadow-xl"
                        />

                        <!-- Imagem -->
                        <div class="w-full" style="height: 270px;">
                            <img
                                src="{{ asset($layout->imagem) }}"
                                alt="{{ $layout->nome }}"
                                class="h-full mx-auto rounded-lg"
                                style="width:60%;"
                            />
                        </div>

                        <!-- Nome do Layout -->
{{--                        <p class="mt-1 text-center text-sm font-semibold text-white drop-shadow-sm">--}}
{{--                            {{ $layout->nome }}--}}
{{--                        </p>--}}
                    </label>
                @endforeach
            </div>
        </div>





    </div>

    <div class="flex mx-auto text-center w-[95%] mt-1">
        <a href="{{route('orcamento')}}"  class="bg-green-500 w-full p-1 rounded text-white">Voltar</a>
    </div>


    @section('scripts')
        <script>
            $(document).ready(function(){

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $("input[name='layout_id']").on('change',function(){

                    let valor = $(this).val();
                    let load = $(".ajax_load");
                    $.ajax({
                        url:"{{route('layouts.select')}}",
                        method:"POST",
                        data: {valor},
                        beforeSend: function () {
                            load.fadeIn(100).css("display", "flex");
                        },
                        success:function(res) {
                            load.fadeOut(100).css("display", "none");
                            if(res == "sucesso") {
                                toastr.success("Layout trocado com sucesso.", "Sucesso",{
                                    toastClass: "toast-custom-width"
                                });
                            } else {
                                toastr.error("Erro ao mudar de Layout. Verifique e tente novamente.", "Error",{
                                    toastClass: "toast-custom-width"
                                });
                            }
                        }
                    });
                });


                $("body").on('change','#regiao',function(){
                    let regiao = $(this).val();
                    $.ajax({
                        url:"{{route('gerenciamento.regiao')}}",
                        method:"POST",
                        data: {regiao},
                        success:function(res) {
                            if(res == true) {
                                alert("Região alterada com sucesso");
                                //toastr.success("Região alterada com sucesso", 'Sucesso');
                            } else {
                                alert("Erro ao alterar região");
                                //toastr.error("Erro ao alterar região", 'Error');
                            }
                        }
                    });
                });
            });
        </script>
    @endsection


</x-app-layout>
